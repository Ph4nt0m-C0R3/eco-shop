<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductDetailRenderingTest extends TestCase
{
    use RefreshDatabase;

    public static function visitors(): array
    {
        return [
            'English guest' => ['en', false],
            'Myanmar guest' => ['mm', false],
            'English customer' => ['en', true],
            'Myanmar customer' => ['mm', true],
        ];
    }

    #[DataProvider('visitors')]
    public function test_product_details_render_with_and_without_reviews(string $locale, bool $authenticated): void
    {
        $user = User::factory()->create(['locale' => $locale]);
        if ($authenticated) {
            $this->actingAs($user);
        }
        $this->withSession(['locale' => $locale]);
        $category = Category::create(['name' => 'Household']);

        foreach ([false, true] as $hasReviews) {
            $product = Product::create([
                'category_id' => $category->id,
                'name_en' => 'Reusable bottle', 'name_mm' => 'ရေဘူး',
                'price_usd' => 7, 'stock' => 10, 'reserved_stock' => 0,
                'is_active' => true, 'created_by' => $user->id,
            ]);
            if ($hasReviews) {
                Review::create([
                    'product_id' => $product->id, 'user_id' => $user->id,
                    'rating' => 5, 'message' => 'Useful everyday product.', 'helpful_count' => 0,
                ]);
            }

            $response = $this->get('/product/'.$product->id)->assertOk();
            if ($hasReviews) {
                $response->assertSee('Useful everyday product.');
            }
            if (!$authenticated) {
                $response->assertDontSee('id="reviewForm"', false);
                continue;
            }

            // The localized form must be usable, with its label targeting the actual textarea.
            $document = new \DOMDocument();
            $previous = libxml_use_internal_errors(true);
            $document->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
            $xpath = new \DOMXPath($document);
            $field = $xpath->query('//form[@id="reviewForm"]//textarea[@name="message"]')->item(0);
            $this->assertNotNull($field);
            $this->assertNotSame('', $field->getAttribute('id'));
            $label = $xpath->query('//label[@for="'.$field->getAttribute('id').'"]')->item(0);
            $this->assertNotNull($label);
            $this->assertStringContainsString($locale === 'mm' ? 'သုံးသပ်ချက်' : 'Your Review', $label->textContent);
            $this->assertSame(
                $locale === 'mm' ? 'ဤထုတ်ကုန်နှင့်ပတ်သက်ပြီးသင်ဘာထင်ပါသလဲ...' : 'What did you think about this product?...',
                $field->getAttribute('placeholder')
            );
        }
    }
}
