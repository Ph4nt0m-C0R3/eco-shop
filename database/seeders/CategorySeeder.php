<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'sort_order' => 1,
                'name' => 'Eco Home & Living',
                'name_mm' => 'အိမ်သုံးပစ္စည်းများ',
                'description' => 'Sustainable household products designed to reduce waste.',
                'description_mm' => 'ပတ်ဝန်းကျင်နှင့်လိုက်ဖက်သော အိမ်သုံးပစ္စည်းများ',
            ],
            [
                'sort_order' => 2,
                'name' => 'Sustainable Fashion',
                'name_mm' => 'သဘာဝနှင့်လိုက်ဖက်သော ဖက်ရှင်',
                'description' => 'Ethical clothing made from eco-friendly materials.',
                'description_mm' => 'ပတ်ဝန်းကျင်ကိုမထိခိုက်စေသော အဝတ်အစားများ',
            ],
            [
                'sort_order' => 3,
                'name' => 'Natural Personal Care',
                'name_mm' => 'သဘာဝကိုယ်ရေးကိုယ်တာအသုံးအဆောင်',
                'description' => 'Chemical-free personal care products.',
                'description_mm' => 'ဓာတုမပါသော ကိုယ်ရေးကိုယ်တာ အသုံးအဆောင်များ',
            ],
            [
                'sort_order' => 4,
                'name' => 'Zero-Waste Products',
                'name_mm' => 'အမှိုက်လျော့ချရေးပစ္စည်းများ',
                'description' => 'Reusable alternatives to single-use plastics.',
                'description_mm' => 'တစ်ခါသုံးပလတ်စတစ်အစားထိုး အသုံးပြုနိုင်သော ပစ္စည်းများ',
            ],
            [
                'sort_order' => 5,
                'name' => 'Organic Food & Beverages',
                'name_mm' => 'သဘာဝအစားအစာနှင့်အဖျော်ယမကာ',
                'description' => 'Organic and sustainably sourced food products.',
                'description_mm' => 'သဘာဝစိုက်ပျိုးမှုမှရရှိသော အစားအစာများ',
            ],
            [
                'sort_order' => 6,
                'name' => 'Eco Cleaning Products',
                'name_mm' => 'ပတ်ဝန်းကျင်သန့်ရှင်းရေးပစ္စည်းများ',
                'description' => 'Non-toxic and biodegradable cleaners.',
                'description_mm' => 'အန္တရာယ်မရှိသော သန့်ရှင်းရေးပစ္စည်းများ',
            ],
            [
                'sort_order' => 7,
                'name' => 'Eco Baby & Kids',
                'name_mm' => 'ကလေးများအတွက် သဘာဝပစ္စည်းများ',
                'description' => 'Safe and sustainable products for children.',
                'description_mm' => 'ကလေးများအတွက် လုံခြုံသော သဘာဝပစ္စည်းများ',
            ],
            [
                'sort_order' => 8,
                'name' => 'Gardening & Outdoor',
                'name_mm' => 'ဥယျာဉ်နှင့်ပြင်ပအသုံးအဆောင်',
                'description' => 'Eco-friendly gardening and outdoor products.',
                'description_mm' => 'ပတ်ဝန်းကျင်နှင့်လိုက်ဖက်သော ဥယျာဉ်ပစ္စည်းများ',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['sort_order' => $category['sort_order']],
                $category
            );
        }
    }
}
