<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CommerceStabilityTest extends TestCase
{
    use RefreshDatabase;

    private function product(array $attributes = []): Product
    {
        return Product::create(array_merge([
            'category_id' => Category::create(['name' => 'Audit category'])->id,
            'name_en' => 'Test product', 'name_mm' => 'စမ်းသပ်ပစ္စည်း', 'price_usd' => 7, 'stock' => 10,
            'reserved_stock' => 3, 'is_active' => true, 'created_by' => 1,
        ], $attributes));
    }

    private function order(array $attributes = []): Order
    {
        $product = $this->product();
        $order = Order::create(array_merge([
            'user_id' => User::factory()->create()->id, 'order_number' => Order::generateOrderNumber(),
            'currency_code' => 'USD', 'exchange_rate_mmk' => 5000, 'subtotal_usd' => 21,
            'subtotal_mmk' => 105000, 'grand_total' => 21, 'payment_method' => 'stripe',
            'payment_status' => 'unpaid', 'status' => 'pending_payment', 'stripe_session_id' => 'cs_'.uniqid(),
        ], $attributes));
        $order->items()->create([
            'product_id' => $product->id, 'product_name' => 'Test product', 'quantity' => 3,
            'price_usd' => 7, 'price_mmk' => 35000, 'total_price' => 21,
        ]);
        return $order;
    }

    private function webhook(Order $order, string $id = 'evt_test', string $type = 'checkout.session.completed', array $changes = [])
    {
        config(['services.stripe.webhook_secret' => 'whsec_test', 'services.stripe.secret' => 'sk_test_example']);
        $payload = json_encode([
            'id' => $id, 'object' => 'event', 'type' => $type,
            'data' => ['object' => array_merge([
                'id' => $order->stripe_session_id, 'object' => 'checkout.session', 'currency' => 'usd',
                'amount_total' => 2100, 'payment_status' => 'paid', 'livemode' => false,
                'metadata' => ['order_id' => (string) $order->id],
            ], $changes)],
        ]);
        $time = time();
        return $this->call('POST', '/api/stripe/webhook', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => 't='.$time.',v1='.hash_hmac('sha256', $time.'.'.$payload, 'whsec_test'),
        ], $payload);
    }

    public function test_stripe_order_routes_require_the_owner_before_provider_calls(): void
    {
        $order = $this->order();
        $this->get(route('stripe.checkout', $order))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create())->get(route('stripe.checkout', $order))->assertForbidden();
        $this->get(route('stripe.cancel', $order))->assertForbidden();
    }

    public function test_success_return_does_not_claim_an_unverified_payment(): void
    {
        $order = $this->order();
        $this->actingAs($order->user)->get('/stripe/success')->assertSessionMissing('success')->assertSessionHas('warning');
        $this->assertSame('unpaid', $order->fresh()->payment_status);
    }

    public function test_repeated_and_distinct_completed_events_commit_inventory_once(): void
    {
        Mail::fake();
        $order = $this->order();
        $this->webhook($order)->assertOk();
        $this->webhook($order)->assertOk();
        $this->webhook($order, 'evt_another')->assertOk();
        $this->assertSame('paid', $order->fresh()->payment_status);
        $product = $order->items->first()->product->fresh();
        $this->assertEquals(7, $product->stock);
        $this->assertEquals(0, $product->reserved_stock);
        $this->assertDatabaseCount('payment_events', 2);
        Mail::assertQueuedCount(1);
    }

    public function test_mismatched_amount_or_environment_cannot_pay_an_order(): void
    {
        $order = $this->order();
        $this->webhook($order, changes: ['amount_total' => 1])->assertStatus(400);
        $this->webhook($order, changes: ['livemode' => true])->assertStatus(400);
        $this->assertSame('unpaid', $order->fresh()->payment_status);
        $this->assertEquals(10, $order->items->first()->product->fresh()->stock);
        $this->assertDatabaseCount('payment_events', 0);
    }

    public function test_unpaid_completion_waits_for_confirmation_and_expiry_releases_once(): void
    {
        $order = $this->order();
        $this->webhook($order, changes: ['payment_status' => 'unpaid'])->assertOk();
        $this->assertSame('unpaid', $order->fresh()->payment_status);
        $this->webhook($order, 'evt_expired', 'checkout.session.expired', ['payment_status' => 'unpaid'])->assertOk();
        $this->webhook($order, 'evt_expired_again', 'checkout.session.expired', ['payment_status' => 'unpaid'])->assertOk();
        $product = $order->items->first()->product->fresh();
        $this->assertEquals(10, $product->stock);
        $this->assertEquals(0, $product->reserved_stock);
        $this->assertSame('cancelled', $order->fresh()->status);
    }

    public function test_invalid_signature_is_rejected(): void
    {
        config(['services.stripe.webhook_secret' => 'whsec_test']);
        $this->postJson('/api/stripe/webhook', ['id' => 'forged'])->assertStatus(400);
    }

    public function test_manual_approval_cannot_override_stripe_or_reject_paid_orders(): void
    {
        $order = $this->order();
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAsMfaAdmin($admin)->post(route('admin.orders.approvePayment', $order))->assertStatus(409);
        $order->update(['payment_method' => 'cod', 'payment_status' => 'paid', 'status' => 'processing']);
        $this->post(route('admin.orders.rejectPayment', $order))->assertStatus(409);
        $this->assertSame('paid', $order->fresh()->payment_status);
    }

    public function test_cod_cancel_is_owned_and_idempotent(): void
    {
        $order = $this->order(['payment_method' => 'cod']);
        $this->actingAs(User::factory()->create())->post(route('user.orders.cancel', $order))->assertForbidden();
        $this->actingAs($order->user)->post(route('user.orders.cancel', $order))->assertRedirect();
        $this->post(route('user.orders.cancel', $order))->assertRedirect();
        $this->assertEquals(0, $order->items->first()->product->fresh()->reserved_stock);
    }

    public function test_inactive_product_is_not_listed_viewed_or_added_to_cart(): void
    {
        $product = $this->product(['is_active' => false, 'name_en' => 'Hidden product']);
        $this->get('/shop')->assertOk()->assertDontSee('Hidden product');
        $this->get(route('product.show', $product))->assertNotFound();
        $this->actingAs(User::factory()->create())->postJson(route('cart.add'), ['product_id' => $product->id])->assertNotFound();
        $this->assertDatabaseCount('carts', 0);
    }

    public function test_checkout_validates_disabled_methods_files_and_cod_delivery_before_reserving(): void
    {
        $user = User::factory()->create();
        $product = $this->product(['reserved_stock' => 0]);
        Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'qty' => 1]);
        $method = PaymentMethod::create(['name_en' => 'Disabled', 'type' => 'cod', 'currency_code' => 'MMK', 'is_active' => false]);
        $input = ['full_name' => 'Buyer', 'email' => 'buyer@example.test', 'phone' => '09123456789', 'delivery_type' => 'pickup', 'payment_method' => $method->id];
        $this->actingAs($user)->postJson(route('checkout.place'), $input)->assertUnprocessable()->assertJsonValidationErrors('payment_method');
        $method->update(['is_active' => true]);
        $this->postJson(route('checkout.place'), $input)->assertUnprocessable()->assertJsonValidationErrors(['shipping_zone_id', 'city', 'postal_code']);
        $method->update(['type' => 'e_wallet']);
        $this->postJson(route('checkout.place'), $input + ['payment_screenshot' => UploadedFile::fake()->create('proof.php', 1, 'text/plain')])
            ->assertUnprocessable()->assertJsonValidationErrors('payment_screenshot');
        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(0, $product->fresh()->reserved_stock);
    }

    public function test_stored_total_and_exchange_snapshot_survive_currency_changes(): void
    {
        $order = $this->order(['grand_total' => 20.95, 'payment_status' => 'paid']);
        Currency::create(['code' => 'MMK', 'rate' => 9000, 'is_active' => true]);
        $this->assertEquals(20.95, $order->fresh()->grand_total);
        $this->assertEquals(104750, $order->fresh()->grand_total_mmk);
        $this->actingAsMfaAdmin(User::factory()->create(['role' => 'admin']))->get('/admin/profile/sales?currency=MMK')->assertOk()->assertSee('104,750');
    }

    public function test_private_proofs_are_only_accessible_to_admins(): void
    {
        Storage::fake('local');
        $order = $this->order(['payment_screenshot' => 'payment_screenshots/proof.png']);
        Storage::disk('local')->put($order->payment_screenshot, 'proof');
        $this->actingAs($order->user)->get(route('admin.orders.paymentProof', $order))->assertRedirect();
        $this->actingAsMfaAdmin(User::factory()->create(['role' => 'admin']))->get(route('admin.orders.paymentProof', $order))->assertOk();
    }

    public function test_scheduled_expiry_can_release_an_order_that_never_created_a_provider_session(): void
    {
        $order = $this->order(['stripe_session_id' => null, 'reservation_expires_at' => now()->subMinute()]);
        config(['services.stripe.secret' => null]);
        $this->artisan('orders:expire-reservations')->assertSuccessful();
        $this->artisan('orders:expire-reservations')->assertSuccessful();
        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertEquals(0, $order->items->first()->product->fresh()->reserved_stock);
    }

    public function test_checkout_saves_private_proof_snapshot_and_reserves_only_once(): void
    {
        Storage::fake('local');
        Currency::create(['code' => 'USD', 'rate' => 1, 'is_base' => true]);
        Currency::create(['code' => 'MMK', 'rate' => 5000]);
        $user = User::factory()->create();
        $product = $this->product(['reserved_stock' => 0]);
        Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'qty' => 1]);
        $method = PaymentMethod::create(['name_en' => 'Wallet', 'type' => 'e_wallet', 'currency_code' => 'MMK']);
        $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jZusAAAAASUVORK5CYII=');
        $input = ['full_name' => 'Buyer', 'email' => 'buyer@example.test', 'phone' => '09123456789',
            'delivery_type' => 'pickup', 'payment_method' => $method->id,
            'payment_screenshot' => UploadedFile::fake()->createWithContent('proof.png', $image)];
        $this->actingAs($user)->post(route('checkout.place'), $input)->assertRedirect(route('user.orders'))->assertSessionHasNoErrors();
        $order = Order::firstOrFail();
        Storage::disk('local')->assertExists($order->payment_screenshot);
        $this->assertEquals(5000, $order->exchange_rate_mmk);
        $this->assertEquals(35000, $order->grand_total);
        $this->assertEquals(1, $product->fresh()->reserved_stock);
        $this->assertEquals(10, $product->fresh()->stock);
        $this->assertDatabaseCount('carts', 0);
        $this->post(route('checkout.place'), $input)->assertRedirect();
        $this->assertDatabaseCount('orders', 1);
        $this->assertEquals(1, $product->fresh()->reserved_stock);
    }

    public function test_legacy_proof_migration_preserves_bytes_and_removes_public_access(): void
    {
        Storage::fake('local'); Storage::fake('public');
        $order = $this->order(['payment_screenshot' => 'payment_screenshots/migration-test.png']);
        Storage::disk('public')->put($order->payment_screenshot, 'proof bytes');
        $this->artisan('payments:privatize-proofs')->assertSuccessful();
        $this->artisan('payments:privatize-proofs')->assertSuccessful();
        Storage::disk('public')->assertMissing($order->payment_screenshot);
        $this->assertSame('proof bytes', Storage::disk('local')->get($order->payment_screenshot));
    }
}
