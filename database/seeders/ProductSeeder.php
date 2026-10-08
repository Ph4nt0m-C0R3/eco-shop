<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [

            // =========================
            // CATEGORY 1: Eco Home & Living (8 products)
            // =========================
            [
                'name_en' => 'Traditional Clay Water Pot',
                'name_mm' => 'ရိုးရာမြေအိုးရေသိုလှောင်အိုး',
                'description_en' => 'Handcrafted natural clay pot designed for water storage and natural cooling. Made from locally sourced clay without chemical glazes, this traditional pot keeps water naturally cool without electricity through evaporation. The porous clay adds beneficial minerals to the water while maintaining its natural pH balance. Perfect for eco-conscious households seeking traditional sustainable solutions.',
                'description_mm' => 'ရေသိုလှောင်ရန်နှင့် သဘာဝအတိုင်းအေးမြစေရန် ဒီဇိုင်းထုတ်ထားသော လက်ဖြင့်ပြုလုပ်ထားသည့် သဘာဝမြေအိုး။ ဓာတုဆေးသုတ်ခြင်းမပါဘဲ ဒေသတွင်းရရှိသောမြေဖြင့် ပြုလုပ်ထားပြီး ဤရိုးရာအိုးသည် အငွေ့ပျံခြင်းဖြင့် လျှပ်စစ်ဓာတ်အားမလိုဘဲ ရေကို သဘာဝအတိုင်းအေးမြစေသည်။ ရေထဲသို့ အကျိုးပြုသတ္တုဓာတ်များထည့်ပေးရင်း သဘာဝ pH မျှခြေကို ထိန်းသိမ်းပေးသည်။ ရိုးရာတည်တံ့သော အဖြေများရှာဖွေနေသော ဂေဟစနစ်အသိရှိသော အိမ်ထောင်စုများအတွက် အကောင်းဆုံး။',
                '' => 28000,
                'price_usd' => 13.33,
                'category_id' => 1,
                'stock' => 45,
                'eco_badge' => 'traditional',
                'eco_badge_mm' => 'ရိုးရာပစ္စည်း',
            ],

            [
                'name_en' => 'Solar-Powered LED Lamp',
                'name_mm' => 'နေရောင်ခြည်စွမ်းအင်သုံး LED မီးအိမ်',
                'description_en' => 'Portable solar-powered LED lamp with 10-hour battery backup and waterproof IP65 design. Features three brightness modes and emergency SOS signal. The solar panel charges efficiently even in cloudy conditions, providing reliable lighting for outdoor camping, emergencies, and rural areas without electricity. Made from recycled plastic with replaceable battery system.',
                'description_mm' => 'ဘက်ထရီအားသွင်း ၁၀ နာရီကြာခံသော သွားလာရလွယ်ကူသည့် နေရောင်ခြည်စွမ်းအင်သုံး LED မီးအိမ် နှင့် ရေစိုခံ IP65 ဒီဇိုင်း။ အလင်းရောင်အဆင့်သုံးဆင့်နှင့် အရေးပေါ် SOS အချက်ပြစနစ်ပါဝင်သည်။ နေရောင်ခြည်ပြားသည် မိုးတိမ်ရှိသည့်အခြေအနေများတွင်ပင် ထိရောက်စွာအားသွင်းနိုင်ပြီး လျှပ်စစ်မရှိသော အပြင်ဘက်ကင်းလွှဲခြင်း၊ အရေးပေါ်အခြေအနေများနှင့် ကျေးလက်ဒေသများအတွက် ယုံကြည်ရသောအလင်းရောင်ကို ပေးစွမ်းသည်။ အစားထိုးနိုင်သောဘက်ထရီစနစ်ဖြင့် ပြန်လည်အသုံးပြုထားသောပလတ်စတစ်ဖြင့် ပြုလုပ်ထားသည်။',
                'price_usd' => 24.76,
                'category_id' => 1,
                'stock' => 38,
                'eco_badge' => 'solar-powered',
                'eco_badge_mm' => 'နေရောင်ခြည်စွမ်းအင်သုံး',
            ],

            [
                'name_en' => 'Organic Lavender Sleep Pillow',
                'name_mm' => 'သဘာဝလာဗန်ဒါအိပ်စက်ခြင်းခေါင်းအုံး',
                'description_en' => 'Memory foam pillow filled with certified organic lavender flowers and buckwheat hulls. The combination provides perfect neck support while the lavender promotes relaxation and better sleep quality. Natural lavender scent helps reduce stress and anxiety. Hypoallergenic cover made from organic cotton with removable washable casing.',
                'description_mm' => 'အသိအမှတ်ပြုသဘာဝလာဗန်ဒါပန်းများနှင့် ဘက်ကြီးစေ့ခွံများဖြင့်ဖြည့်ထားသော မှတ်ဉာဏ်အမြှုပ်ခေါင်းအုံး။ ပေါင်းစပ်မှုသည် လည်ပင်းကို ပြီးပြည့်စုံသောထောက်ပံ့မှုပေးစဉ် လာဗန်ဒါသည် စိတ်သက်သာရာရစေပြီး ပိုမိုကောင်းမွန်သောအိပ်စက်မှုအရည်အသွေးကို အားပေးသည်။ သဘာဝလာဗန်ဒါအနံ့သည် စိတ်ဖိစီးမှုနှင့် စိုးရိမ်ပူပန်မှုကို လျော့ကျစေရန် ကူညီပေးသည်။ ဖယ်ရှားလျှော်ဖွပ်နိုင်သောအခွံပါသည့် သဘာဝဝါဂွမ်းဖြင့် ပြုလုပ်ထားသော ဓာတ်မတည့်မှုနည်းခေါင်းအုံး။',
                'price_usd' => 30.95,
                'category_id' => 1,
                'stock' => 32,
                'eco_badge' => 'organic',
                'eco_badge_mm' => 'သဘာဝ',
            ],

            [
                'name_en' => 'Handcrafted Recycled Glass Vase',
                'name_mm' => 'လက်ဖြင့်ပြုလုပ်ထားသော ပြန်လည်အသုံးပြုထားသော ဖန်ပန်းအိုး',
                'description_en' => 'Beautiful artisanal vase made from 100% recycled glass bottles collected from local communities. Each piece is uniquely hand-blown and shaped, resulting in varying colors and patterns that tell a story of sustainability. The glass maintains excellent clarity while reducing landfill waste. Perfect for displaying fresh flowers or as standalone decorative art.',
                'description_mm' => 'ဒေသတွင်းလူထုမှစုဆောင်းထားသော ၁၀၀% ပြန်လည်အသုံးပြုထားသော ဖန်ပုလင်းများဖြင့် ပြုလုပ်ထားသော လှပသောလက်မှုပညာပန်းအိုး။ တစ်ခုချင်းစီသည် ထူးခြားစွာ လက်ဖြင့်မှုတ်ထားပြီး ပုံဖော်ထားကာ ရရှိလာသောအရောင်နှင့်အဆင်အမျိုးမျိုးသည် တည်တံ့မှုဇာတ်လမ်းကို ပြောပြသည်။ ဖန်သားသည် အမှိုက်ပုံများကိုလျော့ကျစေရင်း အလင်းပြန်မှုကောင်းမွန်စွာထိန်းသိမ်းသည်။ လတ်ဆတ်သောပန်းများပြသရန်သို့မဟုတ် သီးသန့်အလှဆင်အနုပညာအဖြစ် အကောင်းဆုံး။',
                'price_usd' => 34.29,
                'category_id' => 1,
                'stock' => 28,
                'eco_badge' => 'recycled',
                'eco_badge_mm' => 'ပြန်လည်အသုံးပြုထားသော',
            ],

            [
                'name_en' => 'Solid Teak Wood Cutting Board',
                'name_mm' => 'ကျွန်းသစ်အစိုင်အခဲ စဥ့်တုံး',
                'description_en' => 'Premium cutting board crafted from solid, sustainably sourced teak wood with natural antibacterial properties. Features juice groove around edges and reversible design for dual-side use. Finished with food-safe mineral oil that enhances wood grain while protecting against moisture and bacteria. Naturally resistant to knife marks and warping, ensuring longevity with proper care.',
                'description_mm' => 'သဘာဝဘက်တီးရီးယားတိုက်ဖျက်ဂုဏ်သတ္တိများပါဝင်သည့် တည်မြဲစွာရရှိထားသော ကျွန်းသစ်အစိုင်အခဲဖြင့် ပြုလုပ်ထားသော အဆင့်မြင့်စဥ့်တုံး။ အစွန်းများပတ်လည်တွင် အရည်အခွက်ပါဝင်ပြီး နှစ်ဖက်သုံးဒီဇိုင်းဖြင့် နှစ်ဖက်စလုံးအသုံးပြုနိုင်သည်။ သစ်သားအဆင်ကို မြှင့်တင်ပေးစဉ် အစိုဓာတ်နှင့် ဘက်တီးရီးယားမှကာကွယ်ပေးသော အစားအစာဘေးကင်းသော သတ္တုဆီဖြင့် အပြီးသတ်ထားသည်။ ဓားအမှတ်အသားများနှင့် ကွေးခြင်းကို သဘာဝအတိုင်းခံနိုင်ရည်ရှိပြီး သင့်လျော်သောထိန်းသိမ်းမှုဖြင့် ကြာရှည်ခံမှုကို အာမခံသည်။',
                'price_usd' => 21.43,
                'category_id' => 1,
                'stock' => 42,
                'eco_badge' => 'sustainable-wood',
                'eco_badge_mm' => 'တည်မြဲသောသစ်သား',
            ],

            [
                'name_en' => 'Organic Cotton Bed Linen Set',
                'name_mm' => 'သဘာဝဝါဂွမ်းအိပ်ယာခင်းအစုံ',
                'description_en' => 'Complete bed set including sheets, pillowcases, and duvet cover made from GOTS certified organic cotton. Features 300 thread count with sateen weave for luxurious softness and breathability. Naturally hypoallergenic and free from harmful chemicals, dyes, and pesticides. Gets softer with each wash while maintaining durability and color vibrancy.',
                'description_mm' => 'GOTS အသိအမှတ်ပြုသဘာဝဝါဂွမ်းဖြင့်ပြုလုပ်ထားသည့် ခင်းများ၊ ခေါင်းအုံးစွပ်များနှင့် စောင်စွပ်တို့ပါဝင်သော အိပ်ယာခင်းအစုံ။ ဇိမ်ခံနူးညံ့မှုနှင့် လေဝင်လေထွက်ကောင်းမှုအတွက် sateen ရက်ကန်းဖြင့် 300 thread count ပါဝင်သည်။ ဓာတ်မတည့်မှုနည်းပြီး ဘေးဖြစ်စေသောဓာတုပစ္စည်းများ၊ ဆိုးဆေးများနှင့် ပိုးသတ်ဆေးများမပါဝင်။ ကြာရှည်ခံမှုနှင့် အရောင်တောက်ပမှုကိုထိန်းသိမ်းရင်း အလျှော်တိုင်းပိုမိုနူးညံ့လာသည်။',
                'price_usd' => 46.67,
                'category_id' => 1,
                'stock' => 36,
                'eco_badge' => 'organic-cotton',
                'eco_badge_mm' => 'သဘာဝဝါဂွမ်း',
            ],

            [
                'name_en' => 'Handwoven Seagrass Storage Baskets',
                'name_mm' => 'လက်ဖြစ်ပင်လယ်မြက်ခြင်းအိတ်',
                'description_en' => 'Natural seagrass baskets handwoven by local artisans using traditional techniques. These versatile storage solutions are perfect for organizing toys, magazines, laundry, or bathroom essentials. The sturdy construction and natural texture add warmth to any room while being completely biodegradable. Each basket features unique patterns that reflect the artisan\'s skill.',
                'description_mm' => 'ရိုးရာနည်းပညာများကိုအသုံးပြု၍ ဒေသတွင်းလက်မှုပညာရှင်များမှ လက်ဖြင့်ရက်လုပ်ထားသော သဘာဝပင်လယ်မြက်ခြင်းအိတ်။ ဤစွယ်စုံသုံးသိုလှောင်ရေးဖြေရှင်းချက်များသည် ကစားစရာများ၊ မဂ္ဂဇင်းများ၊ အဝတ်အစားများ သို့မဟုတ် ရေချိုးခန်းလိုအပ်ချက်များကို စီစဉ်ရန်အတွက် အကောင်းဆုံးဖြစ်သည်။ ခိုင်ခံ့သောတည်ဆောက်မှုနှင့် သဘာဝအသားအရေသည် အခန်းတိုင်းကို အပူအစိမ်းပေးရင်း လုံးဝဇီဝဖြိုခွဲနိုင်သည်။ ခြင်းအိတ်တစ်ခုစီတွင် လက်မှုပညာရှင်၏ကျွမ်းကျင်မှုကိုထင်ဟပ်သော ထူးခြားသောအဆင်အမျိုးမျိုးပါဝင်သည်။',
                'price_usd' => 15.24,
                'category_id' => 1,
                'stock' => 40,
                'eco_badge' => 'biodegradable',
                'eco_badge_mm' => 'ဇီဝဖြိုခွဲနိုင်သော',
            ],

            [
                'name_en' => 'Recycled Paper Tableware Set',
                'name_mm' => 'ပြန်လည်အသုံးပြုထားသော စက္ကူပန်းကန်ပြားအစုံ',
                'description_en' => 'Elegant disposable tableware set made from 100% recycled paper and printed with vegetable-based inks. Completely biodegradable and compostable within 90 days in commercial composting facilities. The set includes plates, bowls, and cups with reinforced edges for durability. Perfect for eco-friendly parties, picnics, and events while reducing plastic waste significantly.',
                'description_mm' => '၁၀၀% ပြန်လည်အသုံးပြုထားသောစက္ကူဖြင့် ပြုလုပ်ထားပြီး ဟင်းသီးဟင်းရွက်အခြေခံမင်ဖြင့် ပုံနှိပ်ထားသော အဆင့်မြင့်ပစ္စလက်ခံပန်းကန်ပြားအစုံ။ လုံးဝဇီဝဖြိုခွဲနိုင်ပြီး စီးပွားဖြစ်မြေဆွေးပြုလုပ်သည့်နေရာများတွင် ၉၀ ရက်အတွင်း မြေဆွေးပြုလုပ်နိုင်သည်။ အစုံတွင် ကြာရှည်ခံမှုအတွက် အားဖြည့်အစွန်းများပါဝင်သော ပန်းကန်ပြား၊ ခွက်နှင့် ခွက်များပါဝင်သည်။ ပလတ်စတစ်အမှိုက်များကို သိသိသာသာလျော့ကျစေရင်း ဂေဟစနစ်နှင့်သင့်လျော်သောပါတီများ၊ ပျော်ပွဲစားခြင်းများနှင့် အခမ်းအနားများအတွက် အကောင်းဆုံး။',
                'price_usd' => 8.57,
                'category_id' => 1,
                'stock' => 80,
                'eco_badge' => 'compostable',
                'eco_badge_mm' => 'မြေဆွေးပြုလုပ်နိုင်သော',
            ],

            // =========================
            // CATEGORY 2: Sustainable Fashion (8 products)
            // =========================
            [
                'name_en' => 'Organic Cotton Tote Bag',
                'name_mm' => 'သဘာဝဝါဂွမ်းလက်ကိုင်အိတ်',
                'description_en' => 'Stylish and sturdy tote bag made from 100% GOTS certified organic cotton with reinforced stitching and natural cotton handles. Features a spacious main compartment and inner pocket for organization. The natural undyed fabric develops a unique patina over time with use. Perfect for grocery shopping, work, or daily use while reducing plastic bag consumption significantly.',
                'description_mm' => 'အားဖြည့်ချုပ်ရိုးနှင့် သဘာဝဝါဂွမ်းလက်ကိုင်ပါသည့် ၁၀၀% GOTS အသိအမှတ်ပြုသဘာဝဝါဂွမ်းဖြင့် ပြုလုပ်ထားသော ခေတ်မီခိုင်ခံ့သောလက်ကိုင်အိတ်။ ကျယ်ပြန့်သောအဓိကအခန်းနှင့် စီစဉ်ရန်အတွက် အတွင်းပိုက်ဆံအိတ်ပါဝင်သည်။ သဘာဝမဆိုးထားသောအထည်သည် အသုံးပြုချိန်နှင့်အမျှ ထူးခြားသောအရောင်တောက်ပမှုကို ဖွံ့ဖြိုးစေသည်။ ပလတ်စတစ်အိတ်အသုံးပြုမှုကို သိသိသာသာလျော့ကျစေရင်း ဈေးဝယ်ခြင်း၊ အလုပ်သွားခြင်းနှင့် နေ့စဉ်အသုံးပြုရန်အတွက် အကောင်းဆုံး။',
                'price_usd' => 7.14,
                'category_id' => 2,
                'stock' => 120,
                'eco_badge' => 'organic-cotton',
                'eco_badge_mm' => 'သဘာဝဝါဂွမ်း',
            ],

            [
                'name_en' => 'Bamboo Fiber Socks (3 Pairs)',
                'name_mm' => 'ဝါးဖိုင်ဘာခြေအိတ် (၃ စုံ)',
                'description_en' => 'Breathable and antimicrobial bamboo fiber socks with reinforced heel and toe areas for durability. Naturally moisture-wicking and odor-resistant due to bamboo\'s micro-gap structure that allows air circulation. The soft texture feels luxurious against skin while providing excellent comfort for daily wear. Features non-binding tops and seamless toe construction to prevent irritation.',
                'description_mm' => 'ကြာရှည်ခံမှုအတွက် အားဖြည့်ဖနှောင့်နှင့် ခြေချောင်းနေရာများပါဝင်သော လေဝင်လေထွက်ကောင်းပြီး ဘက်တီးရီးယားတိုက်ဖျက်နိုင်သော ဝါးဖိုင်ဘာခြေအိတ်။ ဝါး၏လေဝင်လေထွက်ကောင်းစေသော micro-gap တည်ဆောက်မှုကြောင့် သဘာဝအစိုဓာတ်စုပ်ယူပြီး အနံ့ခံနိုင်သည်။ နူးညံ့သောအသားအရေသည် အရေပြားနှင့်ထိတွေ့သည့်အခါ ဇိမ်ခံခံစားရစေပြီး နေ့စဉ်ဝတ်ဆင်မှုအတွက် ကောင်းမွန်သောအဆင်ပြေမှုကိုပေးသည်။ စိတ်အနှောင့်အယှက်ဖြစ်စေခြင်းမှကာကွယ်ရန် ချုပ်ရိုးမဲ့ခြေချောင်းတည်ဆောက်မှုနှင့် မချည်နှောင်သောအဖျားများပါဝင်သည်။',
                'price_usd' => 5.71,
                'category_id' => 2,
                'stock' => 150,
                'eco_badge' => 'bamboo',
                'eco_badge_mm' => 'ဝါးဖိုင်ဘာ',
            ],

            [
                'name_en' => 'Recycled Polyester Jacket',
                'name_mm' => 'ပြန်လည်အသုံးပြုထားသော ပိုလီအက်စတာ ဂျာကင်',
                'description_en' => 'Water-resistant jacket made from 100% recycled polyester sourced from plastic bottles. Features adjustable cuffs, multiple pockets, and breathable membrane technology. The innovative fabric provides excellent protection from wind and light rain while being significantly lighter than conventional polyester. Each jacket repurposes approximately 15 plastic bottles from landfills and oceans.',
                'description_mm' => 'ပလတ်စတစ်ပုလင်းများမှရရှိသော ၁၀၀% ပြန်လည်အသုံးပြုထားသောပိုလီအက်စတာဖြင့် ပြုလုပ်ထားသော ရေစိုခံဂျာကင်။ ချိန်ညှိနိုင်သောလက်ကောက်ဝတ်၊ ပိုက်ဆံအိတ်များစွာနှင့် လေဝင်လေထွက်ကောင်းသောအမြှေးပါးနည်းပညာပါဝင်သည်။ ဆန်းသစ်သောအထည်သည် လေနှင့်မိုးရွာသွန်းမှုမှ ကောင်းမွန်သောကာကွယ်မှုပေးစဉ် ရိုးရာပိုလီအက်စတာထက် သိသိသာသာပေါ့ပါးသည်။ ဂျာကင်တစ်ထည်စီသည် အမှိုက်ပုံများနှင့် သမုဒ္ဒရာများမှ ပလတ်စတစ်ပုလင်း ၁၅ လုံးခန့်ကို ပြန်လည်အသုံးပြုသည်။',
                'price_usd' => 26.19,
                'category_id' => 2,
                'stock' => 60,
                'eco_badge' => 'recycled',
                'eco_badge_mm' => 'ပြန်လည်အသုံးပြုထားသော',
            ],

            [
                'name_en' => 'Hemp Blend Scarf',
                'name_mm' => 'နှံစားသီးနှံရောစပ် လည်ပတ်',
                'description_en' => 'Lightweight and warm scarf made from sustainable hemp and organic cotton blend. Hemp fibers naturally resist mold and UV degradation while becoming softer with each wash. The fabric features excellent breathability and moisture-wicking properties, making it suitable for various climates. Natural earthy tones complement any outfit while being produced with minimal water and pesticides.',
                'description_mm' => 'တည်တံ့သောနှံစားသီးနှံနှင့် သဘာဝဝါဂွမ်းရောစပ်ဖြင့် ပြုလုပ်ထားသော ပေါ့ပါးနွေးထွေးသောလည်ပတ်။ နှံစားသီးနှံအမျှင်များသည် သဘာဝအတိုင်းမှိုနှင့် ခရမ်းလွန်ရောင်ခြည်ပျက်စီးမှုကို ခံနိုင်ရင်း အလျှော်တိုင်းပိုမိုနူးညံ့လာသည်။ အထည်တွင် ကောင်းမွန်သောလေဝင်လေထွက်နှင့် အစိုဓာတ်စုပ်ယူဂုဏ်သတ္တိများပါဝင်ပြီး ရာသီဥတုအမျိုးမျိုးအတွက် သင့်တော်သည်။ ရေနှင့်ပိုးသတ်ဆေးအနည်းဆုံးဖြင့် ထုတ်လုပ်ထားသော သဘာဝမြေဆီလွှာအရောင်များသည် မည်သည့်အဝတ်အစားနှင့်မဆို လိုက်ဖက်ညီသည်။',
                'price_usd' => 10.48,
                'category_id' => 2,
                'stock' => 75,
                'eco_badge' => 'hemp',
                'eco_badge_mm' => 'နှံစားသီးနှံ',
            ],

            [
                'name_en' => 'Cork Leather Wallet',
                'name_mm' => 'သစ်ခွသားလုပ်အိတ်',
                'description_en' => 'Durable and lightweight wallet made from sustainable cork leather harvested without harming cork oak trees. Features multiple card slots, ID window, and cash compartment. Naturally water-resistant, antimicrobial, and vegan-friendly alternative to animal leather. Each wallet showcases unique cork patterns that become richer with use while maintaining structural integrity for years.',
                'description_mm' => 'သစ်ခွသစ်ပင်များကို မထိခိုက်စေဘဲ ရိတ်သိမ်းထားသော တည်တံ့သောသစ်ခွသားဖြင့် ပြုလုပ်ထားသော ကြာရှည်ခံပေါ့ပါးသောပိုက်ဆံအိတ်။ ကတ်များထည့်ရန်နေရာများစွာ၊ ID ပြတင်းပေါက်နှင့် ငွေသားအခန်းပါဝင်သည်။ တိရစ္ဆာန်သားရေအစား သဘာဝရေစိုခံ၊ ဘက်တီးရီးယားတိုက်ဖျက်နိုင်ပြီး သက်သတ်လွတ်စားသူများအတွက်သင့်တော်သည်။ ပိုက်ဆံအိတ်တစ်ခုစီတွင် နှစ်ပေါင်းများစွာ တည်ဆောက်မှုခိုင်မာမှုကိုထိန်းသိမ်းရင်း အသုံးပြုခြင်းဖြင့် ပိုမိုကြွယ်ဝလာသော ထူးခြားသည့်သစ်ခွအဆင်များကို ပြသထားသည်။',
                'price_usd' => 8.57,
                'category_id' => 2,
                'stock' => 90,
                'eco_badge' => 'cork',
                'eco_badge_mm' => 'သစ်ခွသား',
            ],

            [
                'name_en' => 'Natural Dye Cotton Shawl',
                'name_mm' => 'သဘာဝဆိုးဆေးသုံး ချည်သားခေါင်းပေါင်း',
                'description_en' => 'Elegant shawl made from organic cotton and colored with natural plant-based dyes derived from turmeric, indigo, and beetroot. Each piece features subtle color variations that reflect the artisanal dyeing process. Chemical-free and safe for sensitive skin, the fabric becomes softer with each wash while maintaining color intensity through proper care. Versatile accessory for various occasions.',
                'description_mm' => 'နနွင်း၊ နီလာနှင့် မုန်လာဥနီမှရရှိသော သဘာဝအပင်အခြေခံဆိုးဆေးများဖြင့် ဆိုးထားသော သဘာဝဝါဂွမ်းဖြင့် ပြုလုပ်ထားသော အဆင့်မြင့်ခေါင်းပေါင်း။ တစ်ခုချင်းစီတွင် လက်မှုဆိုးခြင်းလုပ်ငန်းစဉ်ကိုထင်ဟပ်သော သိမ်မွေ့သောအရောင်ကွဲပြားမှုများပါဝင်သည်။ ဓာတုကင်းစင်ပြီး အရေပြားနူးညံ့သူများအတွက်ဘေးကင်းပြီး အထည်သည် သင့်လျော်သောထိန်းသိမ်းမှုဖြင့် အရောင်ပြင်းအားကိုထိန်းသိမ်းရင်း အလျှော်တိုင်းပိုမိုနူးညံ့လာသည်။ အခမ်းအနားအမျိုးမျိုးအတွက် စွယ်စုံသုံးအသုံးအဆောင်။',
                'price_usd' => 14.29,
                'category_id' => 2,
                'stock' => 50,
                'eco_badge' => 'natural-dye',
                'eco_badge_mm' => 'သဘာဝဆိုးဆေး',
            ],

            [
                'name_en' => 'Upcycled Denim Backpack',
                'name_mm' => 'ပြန်လည်အဆင့်မြှင့်ထားသော ဒင်းဂျင်စလောက်အိတ်',
                'description_en' => 'Unique backpack crafted from upcycled denim jeans saved from landfills. Features reinforced straps, multiple compartments, and water-resistant lining. Each piece showcases original denim details like pockets and stitching, creating one-of-a-kind fashion statements. Durable construction with ethical production supports circular fashion economy while reducing textile waste significantly.',
                'description_mm' => 'အမှိုက်ပုံများမှကယ်တင်ထားသော ပြန်လည်အဆင့်မြှင့်ထားသောဒင်းဂျင်ဂျင်းများဖြင့် ပြုလုပ်ထားသော ထူးခြားသောစလောက်အိတ်။ အားဖြည့်ကြိုးများ၊ အခန်းများစွာနှင့် ရေစိုခံအတွင်းခင်းပါဝင်သည်။ တစ်ခုချင်းစီတွင် ပိုက်ဆံအိတ်နှင့်ချုပ်ရိုးကဲ့သို့သော မူရင်းဒင်းဂျင်အသေးစိတ်များကိုပြသထားပြီး တစ်မူထူးခြားသောဖက်ရှင်ထုတ်ဖော်မှုများကို ဖန်တီးသည်။ ကျင့်ဝတ်သိက္ခာရှိသောထုတ်လုပ်မှုဖြင့် ကြာရှည်ခံတည်ဆောက်မှုသည် အထည်အမှိုက်များကို သိသိသာသာလျော့ကျစေရင်း စက်ဝန်းဖက်ရှင်စီးပွားရေးကို ထောက်ပံ့သည်။',
                'price_usd' => 21.43,
                'category_id' => 2,
                'stock' => 40,
                'eco_badge' => 'upcycled',
                'eco_badge_mm' => 'ပြန်လည်အဆင့်မြှင့်ထားသော',
            ],

            [
                'name_en' => 'Wood Grain Hair Claw Clip',
                'name_mm' => 'သစ်သားအဆင်ဆံပင်ညှပ်ကလစ်',
                'description_en' => 'Durable hair claw clip made from sustainable wood with non-toxic finish and stainless steel spring mechanism. The natural wood grain pattern varies slightly on each piece, creating unique accessories. Gentle on hair without causing breakage or damage, suitable for all hair types. Lightweight yet strong enough to hold thick hair securely throughout the day.',
                'description_mm' => 'အဆိပ်မရှိသောအပြီးသတ်မှုနှင့် စတိန်းလက်စ်သံမဏိစပရိန်းယန္တရားပါသည့် တည်တံ့သောသစ်သားဖြင့် ပြုလုပ်ထားသော ကြာရှည်ခံဆံပင်ညှပ်ကလစ်။ သဘာဝသစ်သားအဆင်ပုံစံသည် တစ်ခုချင်းစီတွင် အနည်းငယ်ကွဲပြားပြီး ထူးခြားသောအသုံးအဆောင်များကိုဖန်တီးသည်။ ဆံပင်ကျိုးပဲ့ခြင်းသို့မဟုတ် ပျက်စီးခြင်းမဖြစ်စေဘဲ ဆံပင်အမျိုးအစားအားလုံးအတွက်သင့်တော်သည်။ ပေါ့ပါးသော်လည်း ထူထဲသောဆံပင်ကို တစ်နေ့လုံးလုံခြုံစွာကိုင်ထားနိုင်ရန် လုံလောက်သောအားရှိသည်။',
                'price_usd' => 2.38,
                'category_id' => 2,
                'stock' => 100,
                'eco_badge' => 'sustainable',
                'eco_badge_mm' => 'တည်တံ့သော',
            ],

            // =========================
            // CATEGORY 3: Natural Personal Care (8 products)
            // =========================
            [
                'name_en' => 'Herbal Handmade Soap',
                'name_mm' => 'သဘာဝဆေးဖက်ဝင် လက်လုပ်ဆပ်ပြာ',
                'description_en' => 'Chemical-free handcrafted soap made with traditional cold-process method using organic oils, herbal extracts, and essential oils. Gently cleanses without stripping natural oils, suitable for all skin types including sensitive skin. Each bar is cured for 4-6 weeks to ensure hardness and longevity. Natural ingredients like neem, turmeric, and aloe vera provide therapeutic benefits while being biodegradable.',
                'description_mm' => 'သဘာဝဆီများ၊ ဆေးဖက်ဝင်အဆီများနှင့် အဆီပျံများကိုအသုံးပြု၍ ရိုးရာအေးချိန်လုပ်ငန်းစဉ်ဖြင့် ပြုလုပ်ထားသော ဓာတုကင်းစင်လက်လုပ်ဆပ်ပြာ။ သဘာဝဆီများကိုမဖယ်ရှားဘဲ နူးညံ့စွာသန့်စင်ပေးပြီး အရေပြားနူးညံ့သူများအပါအဝင် အရေပြားအမျိုးအစားအားလုံးအတွက်သင့်တော်သည်။ တစ်ချောင်းစီသည် မာကျောမှုနှင့်ကြာရှည်ခံမှုကိုအာမခံရန် ၄-၆ ပတ်ကြာအချဉ်ဖောက်ထားသည်။ နနွင်း၊ နနွင်းနှင့် ရှားစောင်းလက်ပတ်ကဲ့သို့သော သဘာဝပစ္စည်းများသည် ဇီဝဖြိုခွဲနိုင်စဉ် ကုသမှုအကျိုးကျေးဇူးများကိုပေးသည်။',
                'price_usd' => 2.14,
                'category_id' => 3,
                'stock' => 120,
                'eco_badge' => 'chemical-free',
                'eco_badge_mm' => 'ဓာတုမပါသော',
            ],

            [
                'name_en' => 'Soothing Aloe Vera Face & Body Cream',
                'name_mm' => 'ရှားစောင်းလက်ပတ် အသားအရေထိန်းခရင်မ်',
                'description_en' => 'Rich moisturizing cream made with 95% organic aloe vera gel, shea butter, and calendula extract. Provides instant hydration and cooling relief for dry, sun-damaged, or irritated skin. Non-greasy formula absorbs quickly without clogging pores. Contains natural anti-inflammatory properties that help soothe eczema, psoriasis, and minor skin irritations. Suitable for daily use on face and body.',
                'description_mm' => '၉၅% သဘာဝရှားစောင်းလက်ပတ်ဂျယ်၊ ရှီးယားထောပတ်နှင့် ကယ်လန်ဒူလာအဆီဖြင့် ပြုလုပ်ထားသော ကြွယ်ဝသောအစိုဓာတ်ထိန်းခရင်မ်။ အသားခြောက်ခြင်း၊ နေလောင်ဒဏ်ရာသို့မဟုတ် စိတ်အနှောင့်အယှက်ဖြစ်သောအရေပြားအတွက် ချက်ချင်းအစိုဓာတ်ဖြည့်ခြင်းနှင့် အေးမြခြင်းသက်သာရာကိုပေးသည်။ အဆီမပါသောဖော်မြူလာသည် ချွေးပေါက်ပိတ်ခြင်းမရှိဘဲ မြန်မြန်စုပ်ယူသည်။ နှင်းခူ၊ ဆိုရီးယေးစ်နှင့် အရေပြားအနည်းငယ်စိတ်အနှောင့်အယှက်များကို သက်သာစေရန် ကူညီပေးသော သဘာဝအရောင်ကျဂုဏ်သတ္တိများပါဝင်သည်။ မျက်နှာနှင့်ကိုယ်ခန္ဓာပေါ်တွင် နေ့စဉ်အသုံးပြုရန်သင့်တော်သည်။',
                'price_usd' => 5.95,
                'category_id' => 3,
                'stock' => 85,
                'eco_badge' => 'natural-ingredients',
                'eco_badge_mm' => 'သဘာဝပစ္စည်းများဖြင့် ပြုလုပ်ထားသော',
            ],

            [
                'name_en' => 'Herbal Glow Body Scrub',
                'name_mm' => 'သဘာဝဆေးဖက်ဝင် ကိုယ်လိမ်း Scrub',
                'description_en' => 'Exfoliating body scrub made with organic coffee grounds, Himalayan salt, and herbal powders. Removes dead skin cells, improves circulation, and reveals smoother, more radiant skin. Natural oils of coconut, almond, and jojoba provide deep moisturization during exfoliation. The invigorating scent of essential oils energizes the senses while promoting skin renewal and elasticity.',
                'description_mm' => 'သဘာဝကော်ဖီအနည်း၊ ဟိမဝန္တာဆားနှင့် ဆေးဖက်ဝင်အမှုန့်များဖြင့် ပြုလုပ်ထားသော ဆဲလ်သေဖယ်ရှားကိုယ်လိမ်း Scrub။ ဆဲလ်သေများကိုဖယ်ရှား၊ သွေးလှည့်ပတ်မှုတိုးတက်စေပြီး ပိုမိုချောမွေ့တောက်ပသောအရေပြားကိုဖော်ပြ။ အုန်းဆီ၊ ဗာဒံဆီနှင့် ဂျိုဂျိုဘာဆီ၏သဘာဝဆီများသည် ဆဲလ်သေဖယ်ရှားခြင်းအတွင်း နက်ရှိုင်းသောအစိုဓာတ်ဖြည့်မှုကိုပေးသည်။ အဆီပျံများ၏လန်းဆန်းသောအနံ့သည် အရေပြားအသစ်ဖြစ်ခြင်းနှင့်ပျော့ပျောင်းမှုကိုမြှင့်တင်ရင်း အာရုံများကိုတက်ကြွစေသည်။',
                'price_usd' => 8.81,
                'category_id' => 3,
                'stock' => 60,
                'eco_badge' => 'eco-friendly',
                'eco_badge_mm' => 'ပတ်ဝန်းကျင်နှင့်လိုက်ဖက်သော',
            ],

            [
                'name_en' => 'Activated Charcoal Face Wash',
                'name_mm' => 'မီးသွေးနှင့် အာဂန်ဆီ ပါဝင်သော မျက်နှာသစ်ဆေး',
                'description_en' => 'Deep-cleansing face wash with activated charcoal and organic tea tree oil to draw out impurities, excess oil, and toxins from pores. Suitable for oily and acne-prone skin, helping to prevent breakouts and minimize appearance of pores. Contains natural antibacterial properties while maintaining skin\'s pH balance. Gentle formula doesn\'t strip natural oils, leaving skin clean, refreshed, and balanced.',
                'description_mm' => 'မီးသွေးနှင့် သဘာဝတီးထရီဆီပါဝင်သော အနက်ရှိုင်းဆုံးသန့်စင်မျက်နှာသစ်ဆေး ဖြင့် အညစ်အကြေးများ၊ အဆီပိုများနှင့် အဆိပ်အတောက်များကို ချွေးပေါက်များမှထုတ်ယူ။ အဆီပြန်ပြီး ဝက်ခြံထွက်လွယ်သောအရေပြားအတွက်သင့်တော်ကာ ဝက်ခြံထွက်ခြင်းကိုကာကွယ်ရန် ချွေးပေါက်များ၏အသွင်အပြင်ကိုလျှော့ချရန်ကူညီသည်။ အရေပြား၏ pH မျှခြေကိုထိန်းသိမ်းရင်း သဘာဝဘက်တီးရီးယားတိုက်ဖျက်ဂုဏ်သတ္တိများပါဝင်သည်။ နူးညံ့သောဖော်မြူလာသည် သဘာဝဆီများကိုမဖယ်ရှားဘဲ အရေပြားကိုသန့်ရှင်း၊ လန်းဆန်းပြီး မျှခြေဖြစ်စေသည်။',
                'price_usd' => 5.95,
                'category_id' => 3,
                'stock' => 100,
                'eco_badge' => 'natural-charcoal',
                'eco_badge_mm' => 'သဘာဝမီးသွေးဖြင့် ပြုလုပ်ထားသော',
            ],

            [
                'name_en' => 'Organic Healthy Hair Oil',
                'name_mm' => 'သဘာဝဆံကေသာအားဖြည့်ဆီ',
                'description_en' => 'Ayurvedic hair oil blend made with organic coconut, amla, bhringraj, and fenugreek oils. Nourishes scalp, strengthens hair roots, promotes hair growth, and prevents premature graying. Regular use improves hair texture, reduces split ends, and adds natural shine. Warm oil massage increases blood circulation to follicles. Free from mineral oils, silicones, and synthetic fragrances.',
                'description_mm' => 'သဘာဝအုန်းဆီ၊ အာမလာ၊ ဘရင်းဂရာဂျ်နှင့် မက်မြန်ဆီများဖြင့် ပြုလုပ်ထားသော Ayurvedic ဆံပင်ဆီရောစပ်။ ဦးရေပြားကိုအာဟာရဖြည့်၊ ဆံပင်အမြစ်များကိုသန်မာ၊ ဆံပင်ကြီးထွားမှုကိုမြှင့်တင်ပြီး အချိန်မတိုင်မီဆံပြောင်းခြင်းကိုကာကွယ်။ ပုံမှန်အသုံးပြုခြင်းသည် ဆံပင်အသားအရည်ကိုတိုးတက်စေ၊ ဆံပင်ဖျားကွဲခြင်းကိုလျော့ကျစေပြီး သဘာဝတောက်ပမှုကိုပေါင်းထည့်။ ဆီနွေးနွေးနှိပ်နယ်ခြင်းသည် ဆံပင်အုံများသို့သွေးလှည့်ပတ်မှုကိုတိုးစေ။ သတ္တုဆီများ၊ ဆီလီကွန်များနှင့် ဓာတုအနံ့များမပါ။',
                'price_usd' => 32.38,
                'category_id' => 3,
                'stock' => 25,
                'eco_badge' => 'usda-organic',
                'eco_badge_mm' => 'USDA အသိအမှတ်ပြု သဘာဝ',
            ],

            [
                'name_en' => 'Organic Deodorant (Refresh)',
                'name_mm' => 'သဘာဝချွေးနံ့ပျောက် roll on',
                'description_en' => 'Natural deodorant made with baking soda, arrowroot powder, coconut oil, and essential oils. Effectively neutralizes odor without blocking sweat glands or containing aluminum compounds. Provides 24-hour protection with refreshing citrus-herbal scent. Gentle formula suitable for sensitive skin, free from parabens, phthalates, and synthetic preservatives. Vegan and cruelty-free.',
                'description_mm' => 'ဘေကင်းဆိုဒါ၊ ကြက်ဟင်းခါးသီးမှုန့်၊ အုန်းဆီနှင့် အဆီပျံများဖြင့် ပြုလုပ်ထားသော သဘာဝချွေးနံ့ပျောက်။ အလူမီနီယမ်ဒြပ်ပေါင်းများမပါဘဲ ချွေးဂလင်းများကိုပိတ်ဆို့ခြင်းမရှိဘဲ အနံ့ကိုထိရောက်စွာဓာတ်ပြယ်စေ။ လန်းဆန်းသောသံပုရာ-ဆေးဖက်ဝင်အနံ့ဖြင့် ၂၄ နာရီကာကွယ်မှုပေး။ အရေပြားနူးညံ့သူများအတွက်သင့်တော်သော နူးညံ့သောဖော်မြူလာ၊ ပါရဗင်းများ၊ ဖသေးလိတ်များနှင့် ဓာတုကြာရှည်ခံဆေးများမပါ။ သက်သတ်လွတ်နှင့် ရက်စက်မှုမရှိ။',
                'price_usd' => 18.10,
                'category_id' => 3,
                'stock' => 30,
                'eco_badge' => 'plastic-free',
                'eco_badge_mm' => 'ပလတ်စတစ်မပါသော',
            ],

            [
                'name_en' => "Natural Vegan Lip Balm",
                'name_mm' => "သဘာဝသတ်သတ်လွတ်နှုတ်ခမ်းအဆီ",
                'description_en' => 'All-natural vegan lip balm made with shea butter, cocoa butter, beeswax, and vitamin E. Provides deep hydration and protection against chapping, cracking, and environmental damage. Smooth application without stickiness, leaving lips soft and nourished. Contains natural SPF from zinc oxide. Free from petroleum, synthetic fragrances, and artificial colors. Comes in compostable paper packaging.',
                'description_mm' => 'ရှီးယားထောပတ်၊ ကိုကိုးထောပတ်၊ ပျားဖယောင်းနှင့် ဗီတာမင် E ဖြင့် ပြုလုပ်ထားသော သဘာဝသတ်သတ်လွတ်နှုတ်ခမ်းအဆီ။ နက်ရှိုင်းသောအစိုဓာတ်ဖြည့်မှုနှင့် ကွဲအက်ခြင်း၊ အက်ကြောင်းထခြင်းနှင့် ပတ်ဝန်းကျင်ပျက်စီးမှုမှကာကွယ်မှုပေး။ စေးကပ်ခြင်းမရှိဘဲ ချောမွေ့စွာလိမ်းနိုင်ပြီး နှုတ်ခမ်းများကိုနူးညံ့အာဟာရဖြည့်စေသည်။ ဇင့်အောက်ဆိုဒ်မှ သဘာဝ SPF ပါဝင်။ ရေနံချေး၊ ဓာတုအနံ့နှင့် အရောင်တုများမပါ။ မြေဆွေးပြုလုပ်နိုင်သောစက္ကူထုပ်ပိုးခြင်းဖြင့်လာ။',
                'price_usd' => 11.90,
                'category_id' => 3,
                'stock' => 30,
                'eco_badge' => 'vegan',
                'eco_badge_mm' => 'သဘာဝသတ်သတ်လွတ်',
            ],

            [
                'name_en' => 'Reusable Makeup Pads (22 Pack)',
                'name_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော မိတ်ကပ်ဖျက်ဂွမ်းပြား (၂၂ ခုပါ)',
                'description_en' => 'Set of 22 reusable, washable makeup removal pads made from organic bamboo fiber and cotton. Soft on skin, effective at removing makeup, and durable enough for 1000+ washes. Comes with a laundry bag for convenient washing. Reduces disposable cotton pad waste significantly. Perfect for sustainable beauty routines, facial cleansing, and toner application.',
                'description_mm' => 'သဘာဝဝါးဖိုင်ဘာနှင့် ဝါဂွမ်းဖြင့် ပြုလုပ်ထားသော ပြန်လည်အသုံးပြုနိုင်၊ လျှော်ဖွပ်နိုင်သော မိတ်ကပ်ဖျက်ဂွမ်းပြား ၂၂ ခုပါအစုံ။ အရေပြားပေါ်တွင်နူးညံ့ပြီး မိတ်ကပ်ဖျက်ရာတွင်ထိရောက်ကာ အလျှော် ၁၀၀၀+ အတွက်လုံလောက်သောကြာရှည်ခံမှုရှိသည်။ အဆင်ပြေသောလျှော်ဖွပ်ရန်အတွက် အဝတ်လျှော်အိတ်ပါလာ။ ပစ္စလက်ခံဂွမ်းပြားအမှိုက်များကို သိသိသာသာလျော့ကျစေ။ တည်တံ့သောအလှအပလုပ်ရိုးလုပ်စဉ်များ၊ မျက်နှာသန့်စင်ခြင်းနှင့် toner လိမ်းခြင်းအတွက် အကောင်းဆုံး။',
                'price_usd' => 21.43,
                'category_id' => 3,
                'stock' => 45,
                'eco_badge' => 'reusable',
                'eco_badge_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော',
            ],

            // =========================
            // CATEGORY 4: Zero-Waste Products (8 products)
            // =========================
            [
                'name_en' => 'Compostable Kitchen Trash Bags',
                'name_mm' => 'မြေဆွေးပြုလုပ်နိုင်သော မီးဖိုချောင်အမှိုက်အိတ်',
                'description_en' => 'Biodegradable and compostable trash bags made from plant-based materials like cornstarch and PLA. Breaks down completely in commercial composting facilities within 90 days, leaving no toxic residues. Strong and leak-resistant with reinforced seams and handles. Certified compostable according to international standards. Perfect for eco-conscious households reducing plastic waste.',
                'description_mm' => 'ပြောင်းဖူးမှုန့်နှင့် PLA ကဲ့သို့သော အပင်အခြေခံပစ္စည်းများဖြင့် ပြုလုပ်ထားသော ဇီဝဓာတုဖြိုခွဲနိုင်ပြီး မြေဆွေးပြုလုပ်နိုင်သော အမှိုက်အိတ်များ။ စီးပွားဖြစ်မြေဆွေးပြုလုပ်သည့်နေရာများတွင် ၉၀ ရက်အတွင်း လုံးဝပျက်စီးသွားပြီး အဆိပ်အတောက်ကျန်ကြွင်းများမကျန်ရစ်။ အားဖြည့်ချုပ်ရိုးနှင့်လက်ကိုင်များပါဝင်သော ခိုင်ခံ့အရည်ယိုမှုကာကွယ်။ အပြည်ပြည်ဆိုင်ရာစံချိန်စံညွှန်းများအရ အသိအမှတ်ပြုမြေဆွေးပြုလုပ်နိုင်။ ပလတ်စတစ်အမှိုက်များလျှော့ချနေသော ဂေဟစနစ်အသိရှိသောအိမ်ထောင်စုများအတွက် အကောင်းဆုံး။',
                'price_usd' => 7.14,
                'category_id' => 4,
                'stock' => 100,
                'eco_badge' => 'compostable',
                'eco_badge_mm' => 'မြေဆွေးပြုလုပ်နိုင်သော',
            ],

            [
                'name_en' => 'Organic Beeswax Food Wraps',
                'name_mm' => 'သဘာဝပျားဖယောင်းအစားအစာထုပ်ပိုးခြင်း',
                'description_en' => 'Reusable food wraps made from organic cotton coated with beeswax, jojoba oil, and tree resin. Natural alternative to plastic wrap for covering bowls, wrapping sandwiches, and storing produce. Molds to containers with warmth of hands, creating airtight seal. Washable with cool water and mild soap, lasting up to one year with proper care. Biodegradable at end of life cycle.',
                'description_mm' => 'ပျားဖယောင်း၊ ဂျိုဂျိုဘာဆီနှင့် သစ်စေးဖြင့် သုတ်လိမ်းထားသော သဘာဝဝါဂွမ်းဖြင့် ပြုလုပ်ထားသော ပြန်လည်အသုံးပြုနိုင်သော အစားအစာထုပ်ပိုးခြင်းများ။ ခွက်များဖုံးခြင်း၊ ဆန်းဒဝစ်ချ်များထုပ်ပိုးခြင်းနှင့် ထုတ်ကုန်များသိုလှောင်ရန်အတွက် ပလတ်စတစ်အစားထိုးသဘာဝပစ္စည်း။ လက်များ၏အပူဖြင့် ထည့်စရာများသို့ပုံသွင်းပြီး လေလုံသောတံဆိပ်ကိုဖန်တီး။ ရေအေးနှင့် ပျော့ပျောင်းသောဆပ်ပြာဖြင့်လျှော်ဖွပ်နိုင်ပြီး သင့်လျော်သောထိန်းသိမ်းမှုဖြင့် တစ်နှစ်အထိခံသည်။ သက်တမ်းကုန်ဆုံးချိန်တွင် ဇီဝဖြိုခွဲနိုင်။',
                'price_usd' => 11.90,
                'category_id' => 4,
                'stock' => 68,
                'eco_badge' => 'reusable',
                'eco_badge_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော',
            ],

            [
                'name_en' => 'Reusable Stainless Steel Straw Set',
                'name_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော စတီးပိုက်အစုံ',
                'description_en' => 'Set of 4 reusable stainless steel straws with cleaning brush and silicone tips for comfort. Made from food-grade 304 stainless steel, durable, rust-resistant, and easy to clean. Comes with canvas carrying pouch for portability. Eliminates need for single-use plastic straws. Perfect for home, office, or travel while reducing plastic pollution significantly.',
                'description_mm' => 'သန့်ရှင်းရေးဘရပ်ရှ်နှင့် အဆင်ပြေမှုအတွက် ဆီလီကွန်ဖျားများပါဝင်သော ပြန်လည်အသုံးပြုနိုင်သော စတိန်းလက်စ်သံမဏိပိုက် ၄ ခုပါအစုံ။ အစားအစာအဆင့် 304 စတိန်းလက်စ်သံမဏိဖြင့် ပြုလုပ်ထားပြီး ကြာရှည်ခံ၊ သံချေးတက်ခံနိုင်ပြီး သန့်ရှင်းရေးလွယ်ကူ။ သွားလာရလွယ်ကူရန် ကင်းဗတ်သယ်ဆောင်အိတ်ပါလာ။ ပစ္စလက်ခံပလတ်စတစ်ပိုက်လိုအပ်ချက်ကိုဖယ်ရှား။ ပလတ်စတစ်ညစ်ညမ်းမှုကို သိသိသာသာလျော့ကျစေရင်း အိမ်၊ ရုံးသို့မဟုတ် ခရီးသွားခြင်းအတွက် အကောင်းဆုံး။',
                'price_usd' => 3.81,
                'category_id' => 4,
                'stock' => 60,
                'eco_badge' => 'reusable',
                'eco_badge_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော',
            ],

            [
                'name_en' => 'Plantable Seed Paper Notebook',
                'name_mm' => 'အပင်ပေါက်နိုင်သော မျိုးစေ့စာအုပ်',
                'description_en' => 'Eco-friendly notebook made with plantable seed paper cover embedded with wildflower seeds. After using the notebook, plant the cover in soil to grow beautiful flowers. Interior pages made from 100% recycled paper with vegetable-based inks. Spiral binding allows pages to lay flat. Perfect for journaling, notes, or gifts that keep giving back to nature.',
                'description_mm' => 'တောပန်းမျိုးစေ့များစွက်ထည့်ထားသော အပင်ပေါက်နိုင်သောမျိုးစေ့စက္ကူအဖုံးဖြင့် ပြုလုပ်ထားသော ဂေဟစနစ်နှင့်သင့်လျော်သောမှတ်စုစာအုပ်။ မှတ်စုစာအုပ်အသုံးပြုပြီးနောက် လှပသောပန်းများပေါက်ရန် အဖုံးကိုမြေထဲစိုက်။ အတွင်းစာမျက်နှာများကို ၁၀၀% ပြန်လည်အသုံးပြုထားသောစက္ကူဖြင့် ဟင်းသီးဟင်းရွက်အခြေခံမင်ဖြင့် ပြုလုပ်ထားသည်။ စာမျက်နှာများပြားချပ်စွာထားနိုင်ရန် ခရုပတ်ချည်။ ဂျာနယ်ရေးခြင်း၊ မှတ်စုများ သို့မဟုတ် သဘာဝတွင်ပြန်လည်ပေးဆပ်သည့် လက်ဆောင်များအတွက် အကောင်းဆုံး။',
                'price_usd' => 5.71,
                'category_id' => 4,
                'stock' => 45,
                'eco_badge' => 'plantable',
                'eco_badge_mm' => 'အပင်ပြန်စိုက်နိုင်သည်',
            ],

            [
                'name_en' => 'Silicone Reusable Food Bag',
                'name_mm' => 'ဆီလီကွန် အစားအသောက်သိုလှောင်အိတ်',
                'description_en' => 'Reusable silicone food storage bags as plastic-free alternative to single-use plastic bags. Made from food-grade platinum silicone, free from BPA, PVC, and lead. Airtight seal keeps food fresh, freezer-safe, microwave-safe, and dishwasher-safe. Perfect for storing snacks, sandwiches, fruits, vegetables, and meal prep. Can be used hundreds of times with proper care.',
                'description_mm' => 'ပလတ်စတစ်အိတ်များအစား ပလတ်စတစ်မဲ့အစားထိုးအဖြစ် ပြန်လည်အသုံးပြုနိုင်သော ဆီလီကွန်အစားအသောက်သိုလှောင်အိတ်။ အစားအစာအဆင့် platinum ဆီလီကွန်ဖြင့် ပြုလုပ်ထားပြီး BPA၊ PVC နှင့်ခဲမပါ။ လေလုံသောတံဆိပ်သည် အစားအစာကိုလတ်ဆတ်စေပြီး ရေခဲသေတ္တာထဲထည့်နိုင်၊ မိုက်ကရိုဝေ့ဖ်ထဲထည့်နိုင်ပြီး ပန်းကန်ဆေးစက်ထဲထည့်နိုင်။ ရေစာများ၊ ဆန်းဒဝစ်ချ်များ၊ သစ်သီးများ၊ ဟင်းသီးဟင်းရွက်များနှင့် အစားအစာပြင်ဆင်ခြင်းသိုလှောင်ရန်အတွက် အကောင်းဆုံး။ သင့်လျော်သောထိန်းသိမ်းမှုဖြင့် ရာနှင့်ချီအသုံးပြုနိုင်။',
                'price_usd' => 10.48,
                'category_id' => 4,
                'stock' => 25,
                'eco_badge' => 'reusable',
                'eco_badge_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော',
            ],

            [
                'name_en' => 'Charcoal Dental Floss (Bamboo Case)',
                'name_mm' => 'မီးသွေး သွားကြားထိုးကြိုး (ဝါးဘူးပါ)',
                'description_en' => 'Charcoal-infused dental floss stored in refillable bamboo case for zero-waste oral care. The activated charcoal helps remove toxins and bacteria between teeth. Vegan wax coating for smooth glide, free from PFAS and other harmful chemicals. Bamboo case is biodegradable, and refills come in compostable packaging. Perfect for sustainable dental hygiene routine.',
                'description_mm' => 'အမှိုက်မထွက်စေသော သွားကျန်းမာရေးအတွက် ပြန်လည်ဖြည့်သုံးနိုင်သော ဝါးဘူးထဲတွင်သိမ်းထားသော မီးသွေးစွက်ထည့်ထားသည့် သွားကြားထိုးကြိုး။ မီးသွေးသည် သွားကြားမှ အဆိပ်အတောက်များနှင့် ဘက်တီးရီးယားများဖယ်ရှားရန်ကူညီ။ ချောမွေ့စွာရွေ့လျားရန် သက်သတ်လွတ်ဖယောင်းသုတ်လိမ်း၊ PFAS နှင့်အခြားဘေးဖြစ်ဓာတုပစ္စည်းများမပါ။ ဝါးဘူးသည် ဇီဝဖြိုခွဲနိုင်ပြီး အသစ်ဖြည့်များသည် မြေဆွေးပြုလုပ်နိုင်သောထုပ်ပိုးခြင်းဖြင့်လာ။ တည်တံ့သောသွားကျန်းမာရေးလုပ်ရိုးလုပ်စဉ်အတွက် အကောင်းဆုံး။',
                'price_usd' => 8.57,
                'category_id' => 4,
                'stock' => 40,
                'eco_badge' => 'refillable',
                'eco_badge_mm' => 'ပြန်လည်ဖြည့်သုံးနိုင်သည်',
            ],

            [
                'name_en' => 'Reusable Glass Storage Containers',
                'name_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော ဖန်သားသိုလှောင်ခွက်',
                'description_en' => 'Set of 5 glass food storage containers with bamboo lids and silicone seals. Made from tempered glass that\'s microwave-safe, oven-safe, freezer-safe, and dishwasher-safe. Airtight seals keep food fresh longer. Plastic-free alternative to plastic containers, reducing kitchen waste. Glass doesn\'t absorb odors or stains, ensuring food purity and container longevity.',
                'description_mm' => 'ဝါးအဖုံးနှင့် ဆီလီကွန်တံဆိပ်များပါဝင်သော ဖန်သားအစားအစာသိုလှောင်ခွက် ၅ ခုပါအစုံ။ မိုက်ကရိုဝေ့ဖ်ထဲထည့်နိုင်၊ မီးဖိုထဲထည့်နိုင်၊ ရေခဲသေတ္တာထဲထည့်နိုင်ပြီး ပန်းကန်ဆေးစက်ထဲထည့်နိုင်သော ဖန်သားဖြင့် ပြုလုပ်ထားသည်။ လေလုံသောတံဆိပ်များသည် အစားအစာကို ပိုကြာလတ်ဆတ်စေသည်။ ပလတ်စတစ်ခွက်များအစား ပလတ်စတစ်မဲ့အစားထိုး၊ မီးဖိုချောင်အမှိုက်များလျှော့ချ။ ဖန်သားသည် အနံ့သို့မဟုတ် အစွန်းအထင်းများကိုမစုပ်ယူဘဲ အစားအစာသန့်ရှင်းမှုနှင့် ခွက်ကြာရှည်ခံမှုကိုအာမခံ။',
                'price_usd' => 16.67,
                'category_id' => 4,
                'stock' => 55,
                'eco_badge' => 'plastic-free',
                'eco_badge_mm' => 'ပလတ်စတစ်မဲ့',
            ],

            [
                'name_en' => 'Wool Dryer Balls (Set of 6)',
                'name_mm' => 'သိုးမွှေး Dryer Ball (၆ လုံးပါ)',
                'description_en' => 'Set of 6 reusable wool dryer balls to replace disposable dryer sheets and fabric softeners. Naturally softens clothes, reduces drying time by 25%, and decreases static cling. Made from 100% New Zealand wool, chemical-free and hypoallergenic. Can be infused with essential oils for natural fragrance. Lasts for 1000+ loads, saving money and reducing chemical waste.',
                'description_mm' => 'ပစ္စလက်ခံ dryer sheets နှင့် fabric softeners များအစားထိုးရန် ပြန်လည်အသုံးပြုနိုင်သော သိုးမွှေး dryer ball ၆ လုံးပါအစုံ။ အဝတ်အစားများကို သဘာဝအတိုင်းနူးညံ့စေ၊ အခြောက်ခံချိန်ကို ၂၅% လျှော့ချပြီး static cling ကိုလျှော့ချ။ ၁၀၀% နယူးဇီလန်သိုးမွှေးဖြင့် ပြုလုပ်ထားပြီး ဓာတုကင်းစင်နှင့် ဓာတ်မတည့်မှုနည်း။ သဘာဝအနံ့အတွက် အဆီပျံများစွက်ထည့်နိုင်။ အဝတ်လျှော် ၁၀၀၀+ အထိခံပြီး ငွေကြေးစုဆောင်းကာ ဓာတုအမှိုက်များလျှော့ချ။',
                'price_usd' => 21.43,
                'category_id' => 4,
                'stock' => 15,
                'eco_badge' => 'reusable',
                'eco_badge_mm' => 'ပြန်လည်အသုံးပြုနိုင်သော',
            ],

            // =========================
            // CATEGORY 5: Organic Food & Beverages (8 products)
            // =========================
            [
                'name_en' => 'Quinoa Superfood Mix',
                'name_mm' => 'ကီနိုအာ အာဟာရစုံအစုံ',
                'description_en' => 'Premium organic quinoa blend with chia seeds, flax seeds, and amaranth. Certified gluten-free, high in complete protein, fiber, and antioxidants. Grown without synthetic pesticides or fertilizers. Versatile base for salads, bowls, or as rice substitute. Rich in iron, magnesium, and B-vitamins. Perfect for health-conscious individuals, vegetarians, and those with dietary restrictions.',
                'description_mm' => 'ချီးယားစေ့၊ ပေါင်မုန့်သီးစေ့နှင့် သပွတ်သီးပါဝင်သော အထူးကီနိုအာရောစပ်မှု့။ အသိအမှတ်ပြုဂလူတန်မပါ၊ ပြီးပြည့်စုံပရိုတင်း၊ အမျှင်ဓာတ်နှင့် Antioxidants မြင့်မား။ ဓာတုပိုးသတ်ဆေး သို့မဟုတ် မြေဩဇာမသုံးဘဲစိုက်ပျိုး။ သုပ်များ၊ ခွက်များ သို့မဟုတ် ဆန်အစားထိုးအဖြစ် စွယ်စုံသုံးအခြေခံ။ သံဓာတ်၊ magnesium နှင့် B-ဗီတာမင်ကြွယ်ဝ။ ကျန်းမာရေးအသိရှိသူများ၊ သက်သတ်လွတ်စားသူများနှင့် အစားအသောက်ကန့်သတ်ချက်ရှိသူများအတွက် အကောင်းဆုံး။',
                'price_usd' => 10.71,
                'category_id' => 5,
                'stock' => 85,
                'eco_badge' => 'organic',
                'eco_badge_mm' => 'သဘာဝစိုက်ပျိုးထားသော',
            ],

            [
                'name_en' => 'Organic Cold-Pressed Coconut Oil',
                'name_mm' => 'သဘာဝ အုန်းဆီ (စက်အေးဖြင့် ညှစ်ထုတ်)',
                'description_en' => 'Pure cold-pressed virgin coconut oil extracted without heat or chemicals to preserve nutrients. Rich in MCTs (medium-chain triglycerides), lauric acid, and antioxidants. USDA organic certified, non-GMO, and sustainably sourced. Versatile for cooking, baking, skincare, and haircare. Maintains natural coconut aroma and flavor. Supports metabolism, immune function, and healthy cholesterol levels.',
                'description_mm' => 'အာဟာရများကိုထိန်းသိမ်းရန် အပူသို့မဟုတ် ဓာတုပစ္စည်းများမသုံးဘဲ ထုတ်ယူထားသော စက်အေးဖြင့် ညှစ်ထုတ်ထားသော သဘာဝအုန်းဆီအမှန်။ MCTs (medium-chain triglycerides)၊ lauric acid နှင့် Antioxidants ကြွယ်ဝ။ USDA Organic အသိအမှတ်ပြု၊ GMO မဟုတ်၊ တည်တံ့စွာရရှိ။ ချက်ပြုတ်ခြင်း၊ မုန့်ဖုတ်ခြင်း၊ အသားအရေထိန်းသိမ်းရေးနှင့် ဆံပင်ထိန်းသိမ်းရေးအတွက် စွယ်စုံသုံး။ သဘာဝအုန်းအနံ့နှင့်အရသာကိုထိန်းသိမ်း။ အစာခြေစနစ်၊ ကိုယ်ခံအားလုပ်ဆောင်ခြင်းနှင့် ကျန်းမာသောကိုလက်စထရောအဆင့်များကိုထောက်ပံ့။',
                'price_usd' => 8.81,
                'category_id' => 5,
                'stock' => 120,
                'eco_badge' => 'cold-pressed',
                'eco_badge_mm' => 'စက်အေးဖြင့် ညှစ်ထုတ်ထားသော',
            ],

            [
                'name_en' => 'Raw Wildflower Honey',
                'name_mm' => 'သဘာဝပန်းရောင်စုံပျားရည်',
                'description_en' => 'Unprocessed raw honey collected from diverse wildflower sources by local beekeepers. Never heated or filtered, retaining natural enzymes, pollen, and antioxidants. Crystallization indicates purity and can be reversed with gentle warming. Rich flavor profile with floral notes. Supports local agriculture and bee populations. Natural energy source with antimicrobial properties.',
                'description_mm' => 'ဒေသတွင်းပျားမွေးမြူရေးသမားများမှ မျိုးစုံတောပန်းများမှ စုဆောင်းထားသော မကြာခဏအပူမပေးရသေးသော ပျားရည်အစစ်။ အပူမပေးသို့မဟုတ် စစ်ထုတ်ခြင်းမပြု၊ သဘာဝအင်ဇိုင်းများ၊ ဝတ်မှုံနှင့် Antioxidants ထိန်းသိမ်း။ ပုံဆောင်ခဲခြင်းသည် သန့်ရှင်းမှုကိုညွှန်ပြပြီး နူးညံ့သောအပူပေးခြင်းဖြင့် ပြောင်းပြန်လှန်နိုင်။ ပန်းနံ့များပါဝင်သော ကြွယ်ဝသောအရသာပုံစံ။ ဒေသတွင်းစိုက်ပျိုးရေးနှင့် ပျားအုပ်စုများကိုထောက်ပံ့။ ဘက်တီးရီးယားတိုက်ဖျက်ဂုဏ်သတ္တိများပါဝင်သော သဘာဝစွမ်းအင်အရင်းအမြစ်။',
                'price_usd' => 5.95,
                'category_id' => 5,
                'stock' => 65,
                'eco_badge' => 'raw',
                'eco_badge_mm' => 'မချက်ရသေးသော ပျားရည်',
            ],

            [
                'name_en' => 'Kombucha Green Tea',
                'name_mm' => 'ကွန်ဘူချာ လက်ဖက်စိမ်းရည်',
                'description_en' => 'Fermented probiotic green tea drink with natural ginger-lemon flavor. Contains live cultures, organic acids, and antioxidants from extended fermentation. Supports gut health, digestion, and immune function. Low in sugar, naturally carbonated through fermentation process. Brewed in small batches using traditional methods. Refreshing alternative to sugary sodas with health benefits.',
                'description_mm' => 'သဘာဝချင်း-သံပုရာအရသာပါ အချဉ်ဖောက်ထားသော probiotic လက်ဖက်စိမ်းရည်။ အချဉ်ဖောက်ချိန်ကြာရှည်မှုမှ အသက်ရှင် probiotics၊ သဘာဝအက်စစ်များနှင့် Antioxidants ပါဝင်။ အူလမ်းကြောင်းကျန်းမာရေး၊ အစာခြေစနစ်နှင့် ကိုယ်ခံအားလုပ်ဆောင်ခြင်းကိုထောက်ပံ့။ သကြားနည်း၊ အချဉ်ဖောက်လုပ်ငန်းစဉ်မှတစ်ဆင့် သဘာဝဂက်စ်ထုတ်။ ရိုးရာနည်းပညာများကိုအသုံးပြု၍ အသေးစားအချီများဖြင့် ချက်ပြုတ်။ ကျန်းမာရေးအကျိုးကျေးဇူးများပါဝင်သော သကြားများသောဆိုဒါများအစား လန်းဆန်းသောအစားထိုး။',
                'price_usd' => 2.14,
                'category_id' => 5,
                'stock' => 200,
                'eco_badge' => 'probiotic',
                'eco_badge_mm' => 'Probiotic ပါဝင်သော',
            ],

            [
                'name_en' => 'Organic Almond Butter',
                'name_mm' => 'သဘာဝ ဗာဒံစေ့ထောပတ်',
                'description_en' => '100% pure almond butter made from roasted organic almonds without added oils, sugars, or preservatives. Creamy texture rich in healthy monounsaturated fats, vitamin E, magnesium, and protein. Stone-ground to preserve nutrients and create smooth consistency. Perfect for spreads, smoothies, baking, or direct consumption. Supports heart health and provides sustained energy.',
                'description_mm' => 'အခြားဆီများ၊ သကြားများသို့မဟုတ် ကြာရှည်ခံဆေးများမထည့်ဘဲ ကင်ထားသောသဘာဝဗာဒံစေ့များဖြင့် ပြုလုပ်ထားသော ၁၀၀% ဗာဒံစေ့ထောပတ်အစစ်။ ကျန်းမာရေးနှင့်ညီညွတ်သော monounsaturated အဆီများ၊ ဗီတာမင် E၊ magnesium နှင့် ပရိုတင်းကြွယ်ဝသော ချောမွေ့အသားအရေ။ အာဟာရများကိုထိန်းသိမ်းရန်နှင့် ချောမွေ့သောတသမတ်တည်းဖြစ်မှုကိုဖန်တီးရန် ကျောက်ဆုံတွင်ကြိတ်။ လိမ်းရန်၊ စမူသီ၊ မုန့်ဖုတ်ခြင်း သို့မဟုတ် တိုက်ရိုက်စားသုံးခြင်းအတွက် အကောင်းဆုံး။ နှလုံးကျန်းမာရေးကိုထောက်ပံ့ပြီး တည်မြဲသောစွမ်းအင်ကိုပေး။',
                'price_usd' => 9.00,
                'category_id' => 5,
                'stock' => 95,
                'eco_badge' => 'no-sugar-added',
                'eco_badge_mm' => 'သကြားမထည့်ထားသော',
            ],

            [
                'name_en' => 'Organic Himalayan Goji Berries',
                'name_mm' => 'ဟိမဝန္တာ သဘာဝ ကိုက်လန် ဘယ်ရီသီး',
                'description_en' => 'Sun-dried organic goji berries from the Himalayan region, known for high antioxidant content. Rich in vitamins A, C, and E, iron, zinc, and fiber. Sweet-tart flavor perfect for snacking, smoothies, teas, or baking. Traditionally used in Chinese medicine for eye health and longevity. No sulfur dioxide or preservatives added. Supports immune function and overall vitality.',
                'description_mm' => 'ဟိမဝန္တာဒေသမှ နေလှန်းထားသော သဘာဝကိုက်လန် ဘယ်ရီသီးများ၊ Antioxidants အမြင့်မားအတွက်သိကျွမ်း။ ဗီတာမင် A၊ C၊ နှင့် E၊ သံဓာတ်၊ ဇင့်နှင့် အမျှင်ဓာတ်ကြွယ်ဝ။ ချိုချဉ်သောအရသာသည် ရေစာစားခြင်း၊ စမူသီ၊ လက်ဖက်ရည်များ သို့မဟုတ် မုန့်ဖုတ်ခြင်းအတွက် အကောင်းဆုံး။ ရိုးရာတရုတ်ဆေးပညာတွင် မျက်စိကျန်းမာရေးနှင့် သက်တမ်းရှည်ရန်အသုံးပြု။ sulfur dioxide သို့မဟုတ် ကြာရှည်ခံဆေးများမထည့်။ ကိုယ်ခံအားလုပ်ဆောင်ခြင်းနှင့် စွမ်းအင်ပြည့်ဝမှုကိုထောက်ပံ့။',
                'price_usd' => 7.05,
                'category_id' => 5,
                'stock' => 75,
                'eco_badge' => 'sun-dried',
                'eco_badge_mm' => 'သဘာဝနေလှန်းထားသော',
            ],

            [
                'name_en' => 'Cold-Brew Organic Arabica Coffee',
                'name_mm' => 'အေးချို သဘာဝ အာရဗီကာ ကော်ဖီ',
                'description_en' => 'Single-origin Arabica coffee beans, shade-grown and sun-dried for optimal flavor development. Specially selected for cold brew preparation, yielding smooth, low-acidity coffee with chocolate and nut notes. Organic cultivation protects biodiversity and soil health. Grind size optimized for cold extraction. Perfect for refreshing iced coffee without bitterness.',
                'description_mm' => 'တစ်နေရာတည်းထွက်ရှိသော အာရဗီကာကော်ဖီစေ့များ၊ အရိပ်ထဲတွင်စိုက်ပျိုးပြီး အရသာဖွံ့ဖြိုးမှုအကောင်းဆုံးအတွက် နေလှန်း။ အေးချိုပြင်ဆင်ရန်အတွက် အထူးရွေးချယ်၊ ချောကလက်နှင့် အခွံမာသီးအရသာပါ ချောမွေ့၊ အက်စစ်ဓာတ်နည်းသောကော်ဖီထွက်ရှိ။ သဘာဝစိုက်ပျိုးခြင်းသည် ဇီဝမျိုးကွဲများနှင့် မြေဆီလွှာကျန်းမာရေးကိုကာကွယ်။ အေးချိုထုတ်ယူရန်အတွက် ကြိတ်ဖုန်းအရွယ်အစားအကောင်းဆုံးဖြစ်။ ခါးသက်ခြင်းမရှိဘဲ လန်းဆန်းသောရေခဲကော်ဖီအတွက် အကောင်းဆုံး။',
                'price_usd' => 9.29,
                'category_id' => 5,
                'stock' => 110,
                'eco_badge' => 'single-origin',
                'eco_badge_mm' => 'တစ်နေရာတည်းမှ ထုတ်လုပ်သော',
            ],

            [
                'name_en' => 'Sprouted Whole Grain Bread',
                'name_mm' => 'အညှောက်ပေါက် ကောက်နှံအပြည့် ပေါင်မုန့်',
                'description_en' => 'Artisanal bread made from sprouted whole grains including wheat, barley, millet, and lentils. Sprouting increases nutrient bioavailability and reduces phytic acid. Higher in protein, fiber, vitamins, and minerals compared to regular bread. No preservatives, added sugar, or artificial ingredients. Dense, moist texture with nutty flavor. Easier to digest and suitable for sensitive stomachs.',
                'description_mm' => 'ဂျုံ၊ ဘာလီ၊ ချည်နှင့် ပဲစေ့များအပါအဝင် အညှောက်ပေါက်ကောက်နှံများဖြင့် ပြုလုပ်ထားသော လက်ရာမြောက်ပေါင်မုန့်။ အညှောက်ပေါက်ခြင်းသည် အာဟာရရရှိနိုင်မှုကိုတိုးစေပြီး phytic အက်စစ်ကိုလျှော့ချ။ သာမန်ပေါင်မုန့်နှင့်နှိုင်းယှဉ်ပါက ပရိုတင်း၊ အမျှင်ဓာတ်၊ ဗီတာမင်နှင့် သတ္တုဓာတ်မြင့်မား။ ကြာရှည်ခံဆေး၊ ထပ်မံထည့်သွင်းထားသောသကြားသို့မဟုတ် အလားအလာပါဝင်ပစ္စည်းများမပါ။ အခွံမာသီးအရသာပါ သိပ်သည်း၊ အစိုဓာတ်ရှိသောအသားအရေ။ အစာခြေရလွယ်ကူပြီး အစာအိမ်နူးညံ့သူများအတွက်သင့်တော်။',
                'price_usd' => 2.95,
                'category_id' => 5,
                'stock' => 95,
                'eco_badge' => 'preservative-free',
                'eco_badge_mm' => 'ကြာရှည်ခံဆေးမဲ့',
            ],

            // =========================
            // CATEGORY 6: Eco Cleaning Products (8 products)
            // =========================
            [
                'name_en' => 'Natural Citrus All-Purpose Cleaner',
                'name_mm' => 'သဘာဝ သံပုရာရနံ့ စွယ်စုံသန့်စင်ဆေး',
                'description_en' => 'Eco-friendly multi-surface cleaner made from natural citrus extracts and plant-based surfactants. Effectively cleans kitchen counters, bathrooms, glass, and floors without harsh chemicals. Biodegradable formula breaks down safely in the environment. Non-toxic, safe for kids, pets, and septic systems. Refreshing citrus scent derived from real fruit peels without synthetic fragrances.',
                'description_mm' => 'သဘာဝသံပုရာအဆီများနှင့် အပင်အခြေခံ surfactants များဖြင့် ပြုလုပ်ထားသော ဂေဟစနစ်နှင့်သင့်လျော်သော မျက်နှာပြင်စုံသန့်စင်ဆေး။ ဓာတုပစ္စည်းများမပါဘဲ မီးဖိုချောင်ခန်းများ၊ ရေချိုးခန်းများ၊ မှန်နှင့်ကြမ်းပြင်များကို ထိရောက်စွာသန့်စင်။ ဇီဝဖြိုခွဲနိုင်သောဖော်မြူလာသည် ပတ်ဝန်းကျင်တွင်ဘေးကင်းစွာပျက်စီး။ အဆိပ်မရှိ၊ ကလေးများ၊ အိမ်မွေးတိရစ္ဆာန်များနှင့် septic စနစ်များအတွက်ဘေးကင်း။ ဓာတုအနံ့မပါဘဲ အသီးအခွံများမှရရှိသော လန်းဆန်းသံပုရာအနံ့။',
                'price_usd' => 4.67,
                'category_id' => 6,
                'stock' => 150,
                'eco_badge' => 'biodegradable',
                'eco_badge_mm' => 'ဇီဝဖြိုခွဲနိုင်သော',
            ],

            [
                'name_en' => 'Bamboo & Tea Tree Dishwashing Liquid',
                'name_mm' => 'ဝါး/တီးထရီအဆီပါ ပန်းကန်ဆေးဆပ်ပြာ',
                'description_en' => 'Plant-based dish soap with bamboo extract and tea tree oil for natural cleaning power. Cuts through grease quickly while being gentle on hands. Tea tree oil provides natural antibacterial properties. Biodegradable formula free from phosphates, sulfates, and synthetic dyes. Concentrated formula requires less product per wash. Safe for septic systems and aquatic life.',
                'description_mm' => 'သဘာဝသန့်စင်အားအတွက် ဝါးအဆီနှင့် တီးထရီအဆီပါ အပင်အခြေခံ ပန်းကန်ဆေးဆပ်ပြာ။ အဆီများကို မြန်မြန်ဖြတ်စဉ် လက်အသားအရေကို နူးညံ့စွာ ထိန်းပေး။ တီးထရီအဆီသည် သဘာဝဘက်တီးရီးယားတိုက်ဖျက်ဂုဏ်သတ္တိများကိုပေး။ ဖော့စဖိတ်၊ ဆာလ်ဖိတ်နှင့် ဓာတုဆိုးဆေးများမပါဝင်သော ဇီဝဖြိုခွဲနိုင်သောဖော်မြူလာ။ ပြင်းထန်သောဖော်မြူလာသည် အလျှော်တစ်ကြိမ်လျှင် ထုတ်ကုန်အနည်းငယ်သာလိုအပ်။ septic စနစ်များနှင့် ရေသတ္တဝါများအတွက်ဘေးကင်း။',
                'price_usd' => 4.05,
                'category_id' => 6,
                'stock' => 180,
                'eco_badge' => 'plant-based',
                'eco_badge_mm' => 'အပင်အခြေခံ',
            ],

            [
                'name_en' => 'Eco Laundry Detergent Sheets',
                'name_mm' => 'ဂေဟစနစ်နှင့်သင့်လျော်သော အဝတ်လျှော်ဆပ်ပြာချပ်',
                'description_en' => 'Waterless laundry detergent in convenient sheet form, eliminating plastic bottles. Ultra-concentrated formula dissolves completely in water, suitable for all washing machines including HE. Each sheet cleans full loads effectively. Free from phosphates, chlorine, optical brighteners, and synthetic fragrances. Lightweight and compact, reducing carbon footprint in transportation.',
                'description_mm' => 'ပလတ်စတစ်ပုလင်းများကိုဖယ်ရှားသော အဆင်ပြေသောချပ်ပုံစံရှိ ရေမလိုအဝတ်လျှော်ဆပ်ပြာ။ HE အပါအဝင် အဝတ်လျှော်စက်အားလုံးအတွက် သင့်တော်သော ရေထဲတွင် လုံးဝပျော်ဝင်သော အလွန်ပြင်းထန်ဖော်မြူလာ။ ချပ်တစ်ချပ်စီသည် အပြည့်အဝသန့်စင်သည်။ ဖော့စဖိတ်၊ ကလိုရင်း၊ အလင်းတောက်ပဆေးများနှင့် ဓာတုအနံ့များမပါ။ ပေါ့ပါးကျစ်လစ်၊ သယ်ယူပို့ဆောင်ရေးတွင် ကာဗွန်ခြေရာကိုလျှော့ချ။',
                'price_usd' => 7.43,
                'category_id' => 6,
                'stock' => 95,
                'eco_badge' => 'zero-waste',
                'eco_badge_mm' => 'အမှိုက်မထွက်စေသော',
            ],

            [
                'name_en' => 'Coconut Fiber Scrub Brush',
                'name_mm' => 'အုန်းဆံမျှင် ပွတ်တိုက်ဘရပ်ရှ်',
                'description_en' => 'Natural coconut fiber brush with sustainable bamboo handle. Tough on stuck-on food and stains yet gentle on non-stick and delicate surfaces. The coarse coconut fibers provide excellent scrubbing power without scratching. Biodegradable materials ensure minimal environmental impact. Perfect for pots, pans, grills, and outdoor cleaning tasks. Long-lasting and compostable at end of life.',
                'description_mm' => 'တည်တံ့သောဝါးလက်ကိုင်ပါ သဘာဝအုန်းဆံမျှင်ဘရပ်ရှ်။ ကပ်နေသောအစားအစာနှင့် အစွန်းများအတွက် ခက်ခဲသော်လည်း non-stick နှင့် နူးညံ့မျက်နှာပြင်များအတွက် နူးညံ့။ ကြမ်းတမ်းသောအုန်းဆံမျှင်များသည် ခြစ်ရာမရှိဘဲ ကောင်းမွန်သောပွတ်တိုက်အားကိုပေး။ ဇီဝဖြိုခွဲနိုင်သောပစ္စည်းများသည် ပတ်ဝန်းကျင်အပေါ်သက်ရောက်မှုအနည်းဆုံးကိုအာမခံ။ အိုးကင်များ၊ ဒယ်များ၊ မီးဖိုများနှင့် အပြင်ဘက်သန့်ရှင်းရေးလုပ်ငန်းများအတွက် အကောင်းဆုံး။ ကြာရှည်ခံပြီး သက်တမ်းကုန်ဆုံးချိန်တွင် မြေဆွေးပြုလုပ်နိုင်။',
                'price_usd' => 2.14,
                'category_id' => 6,
                'stock' => 210,
                'eco_badge' => 'plastic-free',
                'eco_badge_mm' => 'ပလတ်စတစ်မပါသော',
            ],

            [
                'name_en' => 'Essential Oil Bathroom Cleaner',
                'name_mm' => 'အဆီပျံအနံ့ပါ ရေချိုးခန်းသန့်စင်ဆေး',
                'description_en' => 'Powerful bathroom cleaner with lavender and eucalyptus essential oils for natural disinfection. Removes soap scum, hard water stains, mold, and mildew without bleach or ammonia. The essential oils provide pleasant aroma and additional cleaning power. Safe on tiles, grout, glass shower doors, and fixtures. Non-toxic and septic-safe formula protects plumbing and environment.',
                'description_mm' => 'သဘာဝပိုးသတ်ခြင်းအတွက် လာဗန်ဒါနှင့် ယူကာလစ်ပက်တပ်အဆီပျံများပါ အားကောင်းသောရေချိုးခန်းသန့်စင်ဆေး။ ဘလိပ့်ခ်သို့မဟုတ် အမိုးနီးယားမပါဘဲ ဆပ်ပြာအစွန်း၊ ရေခဲအစွန်း၊ မှို၊ မှိုစွဲမှုများကို ဖယ်ရှား။ အဆီပျံများသည် ကျေနပ်ဖွယ်အနံ့နှင့် အပိုသန့်စင်အားကိုပေး။ ကြွေပြား၊ အင်္ဂတေ၊ ဖန်ရေချိုးခန်းတံခါးများနှင့် ပစ္စည်းများပေါ်တွင်ဘေးကင်း။ အဆိပ်မရှိနှင့် septic-safe ဖော်မြူလာသည် ပိုက်လိုင်းနှင့် ပတ်ဝန်းကျင်ကိုကာကွယ်။',
                'price_usd' => 5.33,
                'category_id' => 6,
                'stock' => 125,
                'eco_badge' => 'chemical-free',
                'eco_badge_mm' => 'ဓာတုဆေးမဲ့',
            ],

            [
                'name_en' => 'Lavender & Eucalyptus Floor Cleaner',
                'name_mm' => 'လာဗန်ဒါနှင့် ယူကာလစ်ပက်တပ်အဆီပါ ကြမ်းပြင်သန့်စင်ဆေး',
                'description_en' => 'Gentle yet effective floor cleaner with natural lavender and eucalyptus essential oils. Safe for all floor types including hardwood, tile, laminate, vinyl, and sealed concrete. Plant-based formula removes dirt and grime without leaving residue. Pleasant natural scent without synthetic perfumes. Concentrated formula dilutes with water for economical use. Biodegradable and pH neutral.',
                'description_mm' => 'သဘာဝလာဗန်ဒါနှင့် ယူကာလစ်ပက်တပ်အဆီပျံများပါ နူးညံ့သော်လည်း ထိရောက်သောကြမ်းပြင်သန့်စင်ဆေး။ သစ်သားကြမ်းပြင်၊ ကြွေပြား၊ လမ်း၊ ဗိုင်းနယ်နှင့် တံဆိပ်ခတ်ကွန်ကရစ်အပါအဝင် ကြမ်းပြင်အားလုံးအတွက်ဘေးကင်း။ အပင်အခြေခံဖော်မြူလာသည် အညစ်အကြေးနှင့် အမှိုက်များကို အကြွင်းအကျန်မထားဘဲဖယ်ရှား။ ဓာတုရေမွှေးမပါဘဲ ကျေနပ်ဖွယ်သဘာဝအနံ့။ စီးပွားရေးအရအသုံးပြုရန် ရေဖြင့်ရောစပ်သော ပြင်းထန်ဖော်မြူလာ။ ဇီဝဖြိုခွဲနိုင်ပြီး pH ကြားနေ။',
                'price_usd' => 5.95,
                'category_id' => 6,
                'stock' => 145,
                'eco_badge' => 'essential-oil',
                'eco_badge_mm' => 'အဆီပျံပါဝင်သော',
            ],

            [
                'name_en' => 'Biodegradable Dishwashing Pods',
                'name_mm' => 'ရေဆွေးပျက်စီးနိုင်သော ပန်းကန်ဆေးဆပ်ပြာလုံး',
                'description_en' => 'Plant-powered dishwasher pods in dissolvable PVA film. Each pod contains precise measurements of detergent, rinse aid, and stain fighters. No phosphates, chlorine, synthetic dyes, or optical brighteners. Works effectively in all water temperatures. Safe for dishes and dishwasher components. The film dissolves completely, leaving no plastic residue in wastewater.',
                'description_mm' => 'အရည်ပျော်နိုင်သော PVA ရုပ်ရှင်အတွင်း ထည့်သွင်းထားသည့် အပင်အခြေခံ ပန်းကန်ဆေးစက် ဆပ်ပြာလုံးများ။ တစ်လုံးစီတွင် ဆပ်ပြာ၊ ရေဆေးအကူနှင့် အစွန်းတိုက်ဖျက်ပစ္စည်းများ၏ တိကျသောတိုင်းတာမှုများပါဝင်။ ဖော့စဖိတ်၊ ကလိုရင်း၊ ဓာတုဆိုးဆေးများသို့မဟုတ် အလင်းတောက်ပဆေးများမပါ။ ရေအပူချိန်အားလုံးတွင် ထိရောက်စွာအလုပ်လုပ်။ ပန်းကန်ခွက်ယောက်များနှင့် ပန်းကန်ဆေးစက်အစိတ်အပိုင်းများအတွက်ဘေးကင်း။ ရုပ်ရှင်သည် လုံးဝပျော်ဝင်သွားပြီး ရေဆိုးစွန့်ပစ်ရာတွင် ပလတ်စတစ်အကြွင်းအကျန်မကျန်ရစ်။',
                'price_usd' => 8.00,
                'category_id' => 6,
                'stock' => 210,
                'eco_badge' => 'plastic-free',
                'eco_badge_mm' => 'ပလတ်စတစ်မဲ့',
            ],

            [
                'name_en' => 'Natural Glass & Mirror Cleaner Spray',
                'name_mm' => 'သဘာဝ မှန်နှင့် ကျောက်သင်ပုန်းသန့်စင်ဆေး',
                'description_en' => 'Streak-free cleaner for glass, mirrors, and stainless steel made with vinegar, citrus extracts, and purified water. Effectively removes fingerprints, smudges, and water spots. No ammonia, alcohol, or harsh chemicals that damage surfaces or cause fumes. Safe for tinted windows and electronic screens. Pleasant mild citrus scent from natural ingredients.',
                'description_mm' => 'ရှလကာရည်၊ သံပုရာအဆီနှင့် သန့်စင်ရေဖြင့် ပြုလုပ်ထားသော မှန်၊ ကျောက်သင်ပုန်းနှင့် စတိန်းလက်စ်သံမဏိများအတွက် အရိပ်အမှောင်ကင်းသောသန့်စင်ဆေး။ လက်ဗွေရာ၊ အစွန်းအထင်းများနှင့် ရေအစက်များကို ထိရောက်စွာဖယ်ရှား။ မျက်နှာပြင်များပျက်စီးစေသို့မဟုတ် မီးခိုးထွက်စေသော အမိုးနီးယား၊ အရက် သို့မဟုတ် ကြမ်းတမ်းသောဓာတုပစ္စည်းများမပါ။ အရောင်ခြယ်ပြတင်းပေါက်များနှင့် အီလက်ထရွန်းနစ်စကရင်များအတွက်ဘေးကင်း။ သဘာဝပစ္စည်းများမှ ကျေနပ်ဖွယ်ပျော့ပျောင်းသံပုရာအနံ့။',
                'price_usd' => 4.24,
                'category_id' => 6,
                'stock' => 185,
                'eco_badge' => 'ammonia-free',
                'eco_badge_mm' => 'အမိုးနီးယားမပါသော',
            ],

            // =========================
            // CATEGORY 7: Eco Baby & Kids (8 products)
            // =========================
            [
                'name_en' => 'Organic Bamboo Baby Towel Set (2-Pack)',
                'name_mm' => 'သဘာဝဝါးဖိုင်ဘာ ကလေးအဝတ်ခြောက် (၂ ခုပါ)',
                'description_en' => 'Super soft hypoallergenic bamboo baby towel set made from organically grown bamboo fibers. Highly absorbent, dries quickly, and naturally antimicrobial. Perfect for newborn delicate skin, preventing rashes and irritation. The fabric becomes softer with each wash while maintaining durability. Hooded design keeps baby warm after bath. Chemical-free and biodegradable.',
                'description_mm' => 'သဘာဝစိုက်ပျိုးထားသောဝါးဖိုင်ဘာများဖြင့် ပြုလုပ်ထားသော အလွန်နူးညံ့ဓာတ်မတည့်မှုနည်းဝါးကလေးအဝတ်ခြောက်အစုံ။ အလွန်စုပ်ယူနိုင်၊ မြန်မြန်ခြောက်ပြီး သဘာဝဘက်တီးရီးယားတိုက်ဖျက်။ နို့စို့ကလေးနူးညံ့သောအရေပြားအတွက်အကောင်းဆုံး၊ အဖုအပိမ့်နှင့်စိတ်အနှောင့်အယှက်ကိုကာကွယ်။ အထည်သည် ကြာရှည်ခံမှုကိုထိန်းသိမ်းရင်း အလျှော်တိုင်းပိုမိုနူးညံ့လာသည်။ ခေါင်းဖုံးဒီဇိုင်းသည် ရေချိုးပြီးနောက် ကလေးကိုနွေးထွေးစေ။ ဓာတုကင်းစင်နှင့် ဇီဝဖြိုခွဲနိုင်။',
                'price_usd' => 13.33,
                'category_id' => 7,
                'stock' => 50,
                'eco_badge' => 'organic-bamboo',
                'eco_badge_mm' => 'သဘာဝဝါးဖိုင်ဘာ',
            ],

            [
                'name_en' => 'Bamboo Baby Toothbrush (4-Pack)',
                'name_mm' => 'ဝါးလက်ကိုင် ကလေးသွားတိုက်တံ (၄ ခုပါ)',
                'description_en' => 'Biodegradable bamboo toothbrush set with soft BPA-free bristles designed for toddlers. The bamboo handle is naturally antimicrobial and sustainably harvested. Ergonomic design fits small hands comfortably. Different colors help identify brushes for multiple children. Compostable after removing bristles. Perfect introduction to sustainable oral care habits from early age.',
                'description_mm' => 'အသက်တစ်နှစ်အရွယ်ကလေးများအတွက် ဒီဇိုင်းထုတ်ထားသော နူးညံ့ BPA ကင်းစင်သွားမွေးပါဝင်သည့် ဇီဝဖြိုခွဲနိုင်သောဝါးသွားတိုက်တံအစုံ။ ဝါးလက်ကိုင်သည် သဘာဝဘက်တီးရီးယားတိုက်ဖျက်ပြီး တည်တံ့စွာရိတ်သိမ်း။ လက်ကိုင်အဆင်ပြေဒီဇိုင်းသည် လက်ငယ်များနှင့်အဆင်ပြေစွာကိုက်ညီ။ ကလေးများစွာအတွက် သွားတိုက်တံများကိုခွဲခြားသိမြင်ရန် အရောင်ကွဲပြားများကူညီ။ သွားမွေးများဖယ်ရှားပြီးနောက် မြေဆွေးပြုလုပ်နိုင်။ အရွယ်အစားငယ်စဉ်မှစ၍ တည်တံ့သောသွားကျန်းမာရေးအလေ့အထများသို့ ပြီးပြည့်စုံသောမိတ်ဆက်။',
                'price_usd' => 4.52,
                'category_id' => 7,
                'stock' => 150,
                'eco_badge' => 'biodegradable',
                'eco_badge_mm' => 'ဇီဝဖြိုခွဲနိုင်သည်',
            ],

            [
                'name_en' => 'Organic Cotton Baby Hat',
                'name_mm' => 'သဘာဝချည်သား ကလေးဦးထုပ်',
                'description_en' => 'Soft breathable organic cotton baby hat with UPF 50+ sun protection. GOTS certified organic cotton ensures no harmful chemicals touch baby\'s sensitive skin. Gentle elastic band provides comfortable fit without leaving marks. Perfect for outdoor protection against sun and wind. Unisex design in natural colors. Gets softer with each wash while maintaining shape.',
                'description_mm' => 'UPF 50+ နေရောင်ကာကွယ်မှုပါဝင်သော နူးညံ့လေဝင်လေထွက်ကောင်းသဘာဝချည်သားကလေးဦးထုပ်။ GOTS အသိအမှတ်ပြုသဘာဝဝါဂွမ်းသည် ကလေး၏နူးညံ့သောအရေပြားကို ဘေးဖြစ်ဓာတုပစ္စည်းများမထိမီးမောင်းထိုးပြ။ နူးညံ့သောရာဘာကြိုးသည် အမှတ်အသားမကျန်ဘဲ အဆင်ပြေသောကိုက်ညီမှုကိုပေး။ နေရောင်နှင့်လေကိုကာကွယ်ရန် အပြင်ဘက်အတွက် အကောင်းဆုံး။ သဘာဝအရောင်များဖြင့် ကျားမမရွေး။ ပုံသဏ္ဍာန်ကိုထိန်းသိမ်းရင်း အလျှော်တိုင်းပိုမိုနူးညံ့လာသည်။',
                'price_usd' => 3.57,
                'category_id' => 7,
                'stock' => 200,
                'eco_badge' => 'organic-cotton',
                'eco_badge_mm' => 'သဘာဝချည်သား',
            ],

            [
                'name_en' => '100% Natural Rubber Orthodontic Pacifier',
                'name_mm' => '၁၀၀% သဘာဝရာဘာ သွားဘက်ဆိုင်ရာ နို့သီးခေါင်း',
                'description_en' => 'Chemical-free orthodontic pacifier made from sustainably sourced natural rubber. Designed to support proper oral development and palate formation. One-piece construction prevents small parts from detaching. The shield has ventilation holes for safety. Softer than silicone alternatives, resembling natural feel. Free from BPA, PVC, phthalates, and artificial colors.',
                'description_mm' => 'တည်တံ့စွာရရှိသော သဘာဝရာဘာဖြင့် ပြုလုပ်ထားသော ဓာတုကင်းစင်သွားဘက်ဆိုင်ရာနို့သီးခေါင်း။ သင့်လျော်သောပါးစပ်ဖွံ့ဖြိုးမှုနှင့် အာခေါင်ဖွဲ့စည်းပုံကိုထောက်ပံ့ရန် ဒီဇိုင်းထုတ်။ တစ်ပိုင်းတည်းတည်ဆောက်မှုသည် အစိတ်အပိုင်းငယ်များခွဲထွက်ခြင်းမှကာကွယ်။ အကာအရံတွင် ဘေးကင်းရန်လေဝင်ပေါက်များပါဝင်။ ဆီလီကွန်အစားထိုးများထက် ပိုနူးညံ့ပြီး သဘာဝခံစားမှုနှင့်တူ။ BPA၊ PVC၊ ဖသေးလိတ်နှင့် အရောင်တုများမပါ။',
                'price_usd' => 4.05,
                'category_id' => 7,
                'stock' => 90,
                'eco_badge' => 'natural-rubber',
                'eco_badge_mm' => 'သဘာဝရာဘာ',
            ],

            [
                'name_en' => 'Natural Rubber Bath Toys (Duck Set)',
                'name_mm' => 'သဘာဝရာဘာ ရေချိုးကစားစရာ (ဘဲအစုံ)',
                'description_en' => 'Chemical-free natural rubber duck bath toy set made from sustainably harvested rubber. Safe, floatable, and baby-friendly with no holes to prevent mold growth. The natural rubber is soft and warm to touch. Hand-painted with non-toxic, water-resistant colors. Encourages sensory development and water play. Biodegradable and compostable at end of life.',
                'description_mm' => 'တည်တံ့စွာရိတ်သိမ်းထားသောရာဘာဖြင့် ပြုလုပ်ထားသော ဓာတုကင်းစင်သဘာဝရာဘာဘဲရေချိုးကစားစရာအစုံ။ ဘေးကင်း၊ မျောနိုင်ပြီး ကလေးအတွက်သင့်တော်ကာ မှိုပေါက်ခြင်းမှကာကွယ်ရန် အပေါက်မရှိ။ သဘာဝရာဘာသည် ထိတွေ့ရန်နူးညံ့နွေးထွေး။ အဆိပ်မရှိရေခံအရောင်များဖြင့် လက်ဖြင့်ဆေးသုတ်။ အာရုံခံစားမှုဖွံ့ဖြိုးခြင်းနှင့် ရေကစားခြင်းကိုအားပေး။ သက်တမ်းကုန်ဆုံးချိန်တွင် ဇီဝဖြိုခွဲနိုင်ပြီး မြေဆွေးပြုလုပ်နိုင်။',
                'price_usd' => 4.05,
                'category_id' => 7,
                'stock' => 95,
                'eco_badge' => 'chemical-free',
                'eco_badge_mm' => 'ဓာတုကင်းစင်',
            ],

            [
                'name_en' => 'Organic Baby Socks (5 Pairs)',
                'name_mm' => 'သဘာဝချည်သား ကလေးခြေအိတ် (၅ စုံ)',
                'description_en' => 'Soft organic cotton baby socks with non-slip silicone dots on soles and gentle stretch for comfort. Seamless toe construction prevents irritation. Breathable fabric regulates temperature, keeping feet comfortable. Various colors in neutral tones for mix-and-match. Made from GOTS certified organic cotton without harmful chemicals. Perfect for crawling babies and toddlers.',
                'description_mm' => 'အောက်ခံပေါ်တွင် ချော်မကျသောဆီလီကွန်အစက်များနှင့် အဆင်ပြေမှုအတွက် နူးညံ့သောဆန့်နိုင်မှုပါဝင်သော နူးညံ့သဘာဝချည်သားကလေးခြေအိတ်။ ချုပ်ရိုးမဲ့ခြေချောင်းတည်ဆောက်မှုသည် စိတ်အနှောင့်အယှက်ကိုကာကွယ်။ လေဝင်လေထွက်ကောင်းသောအထည်သည် အပူချိန်ကိုထိန်းညှိပြီး ခြေထောက်များကိုအဆင်ပြေစေ။ ရောနှောရန်အတွက် ကြားနေအရောင်များဖြင့် အရောင်ကွဲပြားများ။ ဘေးဖြစ်ဓာတုပစ္စည်းများမပါဘဲ GOTS အသိအမှတ်ပြုသဘာဝဝါဂွမ်းဖြင့် ပြုလုပ်ထားသည်။ တွားသွားကလေးများနှင့် အသက်တစ်နှစ်အရွယ်ကလေးများအတွက် အကောင်းဆုံး။',
                'price_usd' => 5.95,
                'category_id' => 7,
                'stock' => 180,
                'eco_badge' => 'organic-cotton',
                'eco_badge_mm' => 'သဘာဝချည်သား',
            ],

            [
                'name_en' => 'Wooden Stacking Rings Toy',
                'name_mm' => 'သစ်သား အဆင့်ဆင့်တပ်ကွင်း ကစားစရာ',
                'description_en' => 'Wooden stacking rings toy painted with non-toxic, child-safe colors to improve hand-eye coordination and fine motor skills. Made from sustainably sourced beechwood with smooth sanded edges. The different sized rings teach size differentiation and sequencing. Durable construction withstands toddler play. Natural wood grain visible through light staining enhances sensory experience.',
                'description_mm' => 'လက်မျက်စိညှိနှိုင်းနိုင်စွမ်းနှင့် သေးငယ်သောမော်တာစွမ်းရည်များတိုးတက်စေရန် အဆိပ်မရှိကလေးဘေးကင်းအရောင်များဖြင့် သုတ်ထားသော သစ်သားအဆင့်ဆင့်တပ်ကွင်းကစားစရာ။ တည်တံ့စွာရရှိသောသစ်သားဖြင့် ပြုလုပ်ထားပြီး ချောမွေ့သောသဲခြစ်အစွန်းများပါဝင်။ အရွယ်အစားကွဲပြားသောကွင်းများသည် အရွယ်အစားခွဲခြားခြင်းနှင့် အစဉ်လိုက်သင်ကြား။ ကြာရှည်ခံတည်ဆောက်မှုသည် အသက်တစ်နှစ်အရွယ်ကစားခြင်းကိုခံနိုင်။ သဘာဝသစ်သားအဆင်သည် ပေါ့သောအစွန်းအထင်းမှတစ်ဆင့် မြင်နိုင်ပြီး အာရုံခံစားမှုအတွေ့အကြုံကိုမြှင့်တင်။',
                'price_usd' => 5.00,
                'category_id' => 7,
                'stock' => 65,
                'eco_badge' => 'non-toxic',
                'eco_badge_mm' => 'အဆိပ်မရှိ',
            ],

            [
                'name_en' => 'Soft Hemp-Cotton Blend Baby Romper',
                'name_mm' => 'နှံစားသီးနှံ-ချည်သားရောစပ် ကလေးဝတ်စုံ',
                'description_en' => 'Breathable romper made from sustainable hemp and organic cotton blend. Naturally antibacterial, temperature-regulating, and becomes softer with each wash. Snaps at crotch for easy diaper changes. Loose fit allows for comfortable movement. Grown without synthetic pesticides, making it gentle for sensitive baby skin. Durable fabric withstands repeated washing and active play.',
                'description_mm' => 'တည်တံ့သောနှံစားသီးနှံနှင့် သဘာဝချည်သားရောစပ်ဖြင့် ပြုလုပ်ထားသော လေဝင်လေထွက်ကောင်းကလေးဝတ်စုံ။ သဘာဝဘက်တီးရီးယားတိုက်ဖျက်၊ အပူချိန်ထိန်းညှိ၊ အလျှော်တိုင်းပိုမိုနူးညံ့လာ။ သွားရည်သုတ်ပဝါလဲရန်လွယ်ကူစေရန် ပေါင်ခြံတွင်ခလုတ်များ။ ချောင်ချောင်ချိချိရွေ့လျားနိုင်ရန် ကျယ်ပြန့်သောကိုက်ညီမှု။ ဓာတုပိုးသတ်ဆေးမသုံးဘဲစိုက်ပျိုး၊ ကလေးအရေပြားနူးညံ့သူများအတွက်နူးညံ့စေ။ ကြာရှည်ခံအထည်သည် အကြိမ်ကြိမ်လျှော်ဖွပ်ခြင်းနှင့် တက်ကြွကစားခြင်းကိုခံနိုင်။',
                'price_usd' => 10.48,
                'category_id' => 7,
                'stock' => 55,
                'eco_badge' => 'sustainable-fabric',
                'eco_badge_mm' => 'တည်တံ့သောအထည်အမျိုးအစား',
            ],

            // =========================
            // CATEGORY 8: Gardening & Outdoor (8 products)
            // =========================
            [
                'name_en' => 'Premium Bamboo Gardening Tool Set',
                'name_mm' => 'အဆင့်မြင့် ဝါးလက်ကိုင် ဥယျာဉ်ခြံလက်နက်အစုံ',
                'description_en' => '5-piece bamboo gardening tool set with stainless steel heads including trowel, transplanter, cultivator, weeder, and fork. Bamboo handles are lightweight, strong, and naturally antimicrobial. Ergonomic design reduces hand fatigue during extended use. Durable stainless steel resists rust and soil corrosion. Perfect for container gardening, raised beds, and small-scale cultivation.',
                'description_mm' => 'ဇလားဘူး၊ ရွှေ့ပြောင်းစိုက်ကိရိယာ၊ ထွန်ယက်ကိရိယာ၊ ပေါင်းပင်၊ နှင့် ခက်ရင်း အပါအဝင် သံမဏိဦးခေါင်းပါဝင်သော ဝါးလက်ကိုင် ၅ မျိုး ဥယျာဉ်ခြံလက်နက်အစုံ။ ဝါးလက်ကိုင်များသည် ပေါ့ပါးခိုင်ခံ့ပြီး သဘာဝဘက်တီးရီးယားတိုက်ဖျက်။ လက်ကိုင်အဆင်ပြေဒီဇိုင်းသည် ကြာရှည်အသုံးပြုခြင်းအတွင်း လက်ပင်ပန်းမှုကိုလျှော့ချ။ ကြာရှည်ခံစတိန်းလက်စ်သံမဏိသည် သံချေးတက်ခြင်းနှင့် မြေဆီလွှာစားခြင်းကိုခံနိုင်။ ထည့်စရာဥယျာဉ်၊ မြင့်ဥယျာဉ်ကုတင်နှင့် အသေးစားစိုက်ပျိုးခြင်းအတွက် အကောင်းဆုံး။',
                'price_usd' => 18.33,
                'category_id' => 8,
                'stock' => 60,
                'eco_badge' => 'sustainable-tools',
                'eco_badge_mm' => 'တည်တံ့သော လက်နက်ပစ္စည်း',
            ],

            [
                'name_en' => 'Organic Vegetable Garden Starter Kit',
                'name_mm' => 'သဘာဝ ဟင်းသီးဟင်းရွက် ဥယျာဉ်စတင်အစုံ',
                'description_en' => 'Complete organic gardening kit with heirloom seeds, nutrient-rich soil, coconut coir pots, bamboo plant markers, and detailed guide. Includes easy-to-grow vegetables like tomatoes, lettuce, radishes, and herbs. All seeds are non-GMO and open-pollinated. Perfect for beginners wanting to start organic gardening at home. Encourages sustainable food production and connection with nature.',
                'description_mm' => 'အမွေအနှစ်မျိုးစေ့များ၊ အာဟာရကြွယ်မြေဆီ၊ အုန်းမျှင်အိုးများ၊ ဝါးအပင်မှတ်သားတံများနှင့် အသေးစိတ်လမ်းညွှန်ပါဝင်သော အပြည့်အစုံ သဘာဝဥယျာဉ်စတင်အစုံ။ ခရမ်းချဉ်၊ ဆလတ်၊ မုန်လာဥနီနှင့် ဟင်းခတ်အမွှေးအကြိုင်များကဲ့သို့ လွယ်ကူစွာကြီးထွားနိုင်သော ဟင်းသီးဟင်းရွက်များပါဝင်။ မျိုးစေ့အားလုံးသည် GMO မဟုတ်ပြီး ပွင့်ဝတ်မှုံကူး။ အိမ်တွင် သဘာဝဥယျာဉ်စတင်လိုသော အစပြုသူများအတွက် အကောင်းဆုံး။ တည်တံ့သောအစားအစာထုတ်လုပ်ခြင်းနှင့် သဘာဝနှင့်ဆက်သွယ်မှုကိုအားပေး။',
                'price_usd' => 14.05,
                'category_id' => 8,
                'stock' => 85,
                'eco_badge' => 'organic-gardening',
                'eco_badge_mm' => 'သဘာဝဥယျာဉ်ထုတ်ကုန်',
            ],

            [
                'name_en' => 'Solar-Powered Garden Watering System',
                'name_mm' => 'နေစွမ်းအင်သုံး ဥယျာဉ်ခြံ ရေလောင်းစနစ်',
                'description_en' => 'Automatic drip irrigation system powered by solar energy with timer settings for efficient water use. Includes solar panel, battery, controller, tubing, and drippers. Customizable layout suits various garden sizes and plant types. Reduces water consumption by up to 70% compared to traditional watering. Perfect for vacation homes or busy gardeners wanting consistent plant care.',
                'description_mm' => 'ထိရောက်သောရေအသုံးပြုမှုအတွက် အချိန်ကိုက်စနစ်ပါဝင်သော နေစွမ်းအင်ဖြင့် လည်ပတ်သော သူ့အလိုလို drip ရေလောင်းစနစ်။ နေရောင်ခြည်ပြား၊ ဘက်ထရီ၊ ထိန်းချုပ်ကိရိယာ၊ ပိုက်နှင့် ရေစက်ချကိရိယာများပါဝင်။ စိတ်ကြိုက်ပြင်ဆင်မှုသည် ဥယျာဉ်အရွယ်အစားနှင့် အပင်အမျိုးအစားမျိုးစုံအတွက် သင့်တော်။ ရိုးရာရေလောင်းခြင်းနှင့်နှိုင်းယှဉ်ပါက ရေအသုံးပြုမှုကို ၇၀% အထိလျှော့ချ။ အားလပ်ရက်အိမ်များ သို့မဟုတ် တသမတ်တည်းအပင်ထိန်းသိမ်းလိုသော မအားလပ်ဥယျာဉ်မှူးများအတွက် အကောင်းဆုံး။',
                'price_usd' => 37.38,
                'category_id' => 8,
                'stock' => 35,
                'eco_badge' => 'solar-powered',
                'eco_badge_mm' => 'နေစွမ်းအင်သုံး',
            ],

            [
                'name_en' => 'Organic Fertilizer Concentrate',
                'name_mm' => 'သဘာဝ မြေဩဇာအရည်',
                'description_en' => 'Plant-based liquid fertilizer made from seaweed and fish emulsion, rich in nitrogen, phosphorus, potassium, and micronutrients. Promotes strong root development, lush foliage, and abundant flowering. Safe for organic gardening, won\'t burn plants when used as directed. Concentrated formula mixes with water for economical application. Improves soil structure and microbial activity over time.',
                'description_mm' => 'ရေညှိနှင့် ငါးအရည်မှ ပြုလုပ်ထားသော အပင်အခြေခံ မြေဩဇာအရည်၊ နိုက်ထရိုဂျင်၊ ဖော့စဖရပ်၊ ပိုတက်စီယမ်နှင့် micronutrients ကြွယ်ဝ။ အားကောင်းသောအမြစ်ဖွံ့ဖြိုးခြင်း၊ သန်စွမ်းသောအရွက်များနှင့် ပန်းပွင့်များစွာကိုမြှင့်တင်။ သဘာဝဥယျာဉ်အတွက်ဘေးကင်း၊ ညွှန်ကြားချက်အတိုင်းအသုံးပြုပါက အပင်များကိုမကျွေး။ စီးပွားရေးအရအသုံးပြုရန် ရေနှင့်ရောစပ်သော ပြင်းထန်ဖော်မြူလာ။ မြေဆီလွှာတည်ဆောက်မှုနှင့် ဇီဝရုပ်လုပ်ဆောင်ခြင်းကို ကြာလာတိုင်းတိုးတက်စေ။',
                'price_usd' => 10.71,
                'category_id' => 8,
                'stock' => 95,
                'eco_badge' => 'plant-based',
                'eco_badge_mm' => 'အပင်အခြေခံ',
            ],

            [
                'name_en' => 'Natural Pest Control Spray',
                'name_mm' => 'သဘာဝ ပိုးမွှားထိန်းချုပ်ဆေး',
                'description_en' => 'Essential oil-based pest control spray using neem oil, garlic, and peppermint formula for organic farming. Effectively repels common garden pests like aphids, mites, and whiteflies without harming beneficial insects. Safe for edible plants up to day of harvest. Biodegradable and leaves no toxic residues. Pleasant herbal scent unlike chemical pesticides. Prevents pest resistance development.',
                'description_mm' => 'နနွင်းဆီ၊ ကြက်သွန်ဖြူနှင့် ပက်ပါမင်းဖော်မြူလာကိုအသုံးပြုသော အဆီပျံအခြေခံ သဘာဝပိုးမွှားထိန်းချုပ်ဆေး။ အကျိုးပြုပိုးမွှားများကိုမထိခိုက်စေဘဲ ပျ၊ အမှုန်များနှင့် whiteflies ကဲ့သို့သော ပုံမှန်ဥယျာဉ်ပိုးမွှားများကို ထိရောက်စွာမောင်းထုတ်။ ရိတ်သိမ်းရန်နေ့အထိ စားသုံးနိုင်သောအပင်များအတွက်ဘေးကင်း။ ဇီဝဖြိုခွဲနိုင်ပြီး အဆိပ်အတောက်ကျန်ကြွင်းများမကျန်ရစ်။ ဓာတုပိုးသတ်ဆေးနှင့်မတူသော ကျေနပ်ဖွယ်ဆေးဖက်ဝင်အနံ့။ ပိုးမွှားခံနိုင်ရည်ဖွံ့ဖြိုးခြင်းကိုကာကွယ်။',
                'price_usd' => 7.86,
                'category_id' => 8,
                'stock' => 110,
                'eco_badge' => 'natural-formula',
                'eco_badge_mm' => 'သဘာဝဖော်မြူလာ',
            ],

            [
                'name_en' => 'Premium Organic Potting Soil Mix (20kg)',
                'name_mm' => 'အဆင့်မြင့် သဘာဝအိုးခင်းမြေဆီရောစပ် (၂၀ ကီလို)',
                'description_en' => 'Nutrient-rich organic soil mix with coconut coir, compost, worm castings, perlite, and mycorrhizal fungi. Specifically formulated for container gardening and raised beds. Excellent water retention and drainage balance prevents overwatering. pH balanced for most vegetables, herbs, and flowering plants. Ready to use straight from bag, no additional amendments needed initially.',
                'description_mm' => 'အုန်းမျှင်၊ မြေဆွေး၊ ကြွက်ချေးဥ၊ perlite နှင့် mycorrhizal မှိုများပါဝင်သော အာဟာရကြွယ် သဘာဝအိုးခင်းမြေဆီရောစပ်။ ထည့်စရာဥယျာဉ်နှင့် မြင့်ဥယျာဉ်ကုတင်များအတွက် အထူးဖော်စပ်။ ကောင်းမွန်သောရေထိန်းသိမ်းမှုနှင့် ရေထုတ်မျှခြေသည် ရေလွန်ကဲခြင်းကိုကာကွယ်။ ဟင်းသီးဟင်းရွက်၊ ဟင်းခတ်အမွှေးအကြိုင်နှင့် ပန်းပွင့်အပင်အများစုအတွက် pH မျှခြေ။ အိတ်မှတိုက်ရိုက်အသုံးပြုရန် အဆင်သင့်၊ ကနဦးတွင် အပိုပြင်ဆင်မှုမလို။',
                'price_usd' => 8.81,
                'category_id' => 8,
                'stock' => 120,
                'eco_badge' => 'organic-soil',
                'eco_badge_mm' => 'သဘာဝမြေဆီ',
            ],

            [
                'name_en' => 'Heirloom Tomato Seed Collection',
                'name_mm' => 'အမွေအနှစ် ခရမ်းချဉ် မျိုးစေ့အစု',
                'description_en' => 'Collection of rare heirloom tomato seeds including Brandywine, Cherokee Purple, and Yellow Pear varieties. Non-GMO and open-pollinated, allowing seed saving for future seasons. Each packet includes detailed planting guide with germination tips. These varieties offer exceptional flavor often missing from commercial hybrids. Supports biodiversity preservation and sustainable seed practices.',
                'description_mm' => 'Brandywine၊ Cherokee Purple နှင့် Yellow Pear အမျိုးအစားများအပါအဝင် GMO မဟုတ်သော အဖိုးတန်အမွေအနှစ်ခရမ်းချဉ်မျိုးစေ့အစု။ GMO မဟုတ်ပြီး ပွင့်ဝတ်မှုံကူး၊ အနာဂတ်ရာသီများအတွက် မျိုးစေ့စုဆောင်းခွင့်ပြု။ တစ်ထုပ်စီတွင် အညှောက်ပေါက်အကြံပြုချက်များပါဝင်သော အသေးစိတ်စိုက်ပျိုးလမ်းညွှန်ပါဝင်။ ဤအမျိုးအစားများသည် စီးပွားဖြစ်စပ်မျိုးများတွင်မကြာခဏပျောက်ဆုံးနေသော ထူးခြားသောအရသာကိုပေး။ ဇီဝမျိုးကွဲထိန်းသိမ်းခြင်းနှင့် တည်တံ့သောမျိုးစေ့အလေ့အထများကိုထောက်ပံ့။',
                'price_usd' => 6.90,
                'category_id' => 8,
                'stock' => 95,
                'eco_badge' => 'non-gmo',
                'eco_badge_mm' => 'GMO မဟုတ်',
            ],

            [
                'name_en' => 'Heavy-Duty Garden Cart with Removable Sides',
                'name_mm' => 'ဖယ်ရှားနိုင်ဘေးဘောင်ပါ လေးလံဥယျာဉ်လှည်း',
                'description_en' => 'Heavy-duty garden cart with steel frame, pneumatic tires, and removable sides for versatile hauling. Capacity of 300lbs (136kg) with easy maneuverability over rough terrain. Flatbed configuration allows transport of large items like compost bags or potted trees. Rust-resistant powder coating ensures longevity. Perfect for moving soil, mulch, plants, tools, and harvest around large gardens.',
                'description_mm' => 'သံမဏိဘောင်ဖြင့် ပြုလုပ်ထားပြီး စွယ်စုံသုံးသယ်ဆောင်ရန်အတွက် လေဖိအားတပ်ထားသောတာယာနှင့် ဖယ်ရှားနိုင်ဘေးဘောင်ပါ လေးလံဥယျာဉ်လှည်း။ ကြမ်းတမ်းသောမြေပြင်ပေါ်တွင် လွယ်ကူစွာရွေ့လျားနိုင်သော ၃၀၀ပေါင် (၁၃၆ကီလို) သယ်ဆောင်နိုင်စွမ်း။ Flatbed ပြင်ဆင်မှုသည် မြေဆွေးအိတ်များ သို့မဟုတ် အိုးခင်းအပင်များကဲ့သို့သော အရာဝတ္ထုကြီးများသယ်ဆောင်ခွင့်ပြု။ သံချေးတက်ခံနိုင်သော powder သုတ်လိမ်းခြင်းသည် ကြာရှည်ခံမှုကိုအာမခံ။ ကြီးမားသောဥယျာဉ်များအနီးရှိ မြေဆီ၊ မျက်နှာပြင်ဖုံး၊ အပင်များ၊ လက်နက်ပစ္စည်းများနှင့် ရိတ်သိမ်းမှုများရွေ့လျားရန်အတွက် အကောင်းဆုံး။',
                'price_usd' => 42.62,
                'category_id' => 8,
                'stock' => 35,
                'eco_badge' => 'heavy-duty',
                'eco_badge_mm' => 'ကြံ့ခိုင်ကြာရှည်ခံ',
            ],

        ];

        // Category Slugs (your fixed order 1 to 8)
        $categorySlugs = [
            1 => 'eco_home_and_living',
            2 => 'sustainable_fashion',
            3 => 'natural_personal_care',
            4 => 'zero_waste_products',
            5 => 'organic_food_and_beverages',
            6 => 'eco_cleaning_products',
            7 => 'eco_baby_and_kids',
            8 => 'gardening_and_outdoor',
        ];

        // Track numbering per category
        $categoryCounters = [];

        // Track NEW product per category
        $newProductGiven = [];

        foreach ($products as $productData) {

            $categoryId = $productData['category_id'];

            if (!isset($categoryCounters[$categoryId])) {
                $categoryCounters[$categoryId] = 1;
            }

            if (!isset($newProductGiven[$categoryId])) {
                $newProductGiven[$categoryId] = false;
            }

            // Make first product of each category NEW
            if ($newProductGiven[$categoryId] === false) {
                $createdAt = now(); // NEW
                $newProductGiven[$categoryId] = true;
            } else {
                $createdAt = now()->subDays(30); // Old product
            }

            $product = Product::create([
                'name_en' => $productData['name_en'],
                'name_mm' => $productData['name_mm'],
                'description_en' => $productData['description_en'],
                'description_mm' => $productData['description_mm'],
                'price_usd' => $productData['price_usd'],
                'category_id' => $categoryId,
                'stock' => $productData['stock'],
                'eco_badge' => $productData['eco_badge'],
                'eco_badge_mm' => $productData['eco_badge_mm'],
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $slug = $categorySlugs[$categoryId];
            $index = $categoryCounters[$categoryId];

            ProductImage::create([
                'product_id' => $product->id,
                'image' => "products/{$slug}_{$index}.jpg",
                'is_primary' => true,
            ]);

            $categoryCounters[$categoryId]++;
        }
    }
}
