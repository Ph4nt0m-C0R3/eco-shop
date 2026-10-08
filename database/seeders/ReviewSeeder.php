<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Str;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::take(64)->pluck('id');
        $users = User::where('role', 'user')->pluck('id');

        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('No products or users found. Seeder skipped.');
            return;
        }

        // randomly choose how many products to review (10–40 out of 64)
        $productsToReview = $products->random(
            min(rand(10, 40), $products->count())
        );

        foreach ($productsToReview as $productId) {

            // each product gets 1–5 reviews
            $reviewCount = rand(1, 5);

            $randomUsers = $users->random(
                min($reviewCount, $users->count())
            );

            foreach ($randomUsers as $userId) {

                Review::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'user_id' => $userId,
                    ],
                    [
                        'rating' => rand(1, 5),
                        'message' => $this->randomReviewMessage(),
                        'helpful_count' => rand(0, 10),
                    ]
                );
            }
        }

        $this->command->info('Reviews seeded successfully.');
    }

    private function randomReviewMessage(): string
    {
        return collect([
            "Very good product!",
            "Highly recommended.",
            "Good quality and eco friendly.",
            "Worth the price.",
            "Amazing product.",
            "Could be better.",
            "Satisfied with the purchase.",
            "အရမ်းကောင်းပါတယ်။",
            "အရည်အသွေးကောင်းပါတယ်။",
            "အရမ်းကျေနပ်ပါတယ်။"
        ])->random();
    }
}
