<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CropCategory;
use App\Models\CropSubCategory;
use Illuminate\Support\Facades\DB;

class CropCategoryWithSubSeeder extends Seeder
{
     public function run()
    {
        DB::transaction(function () {

            $data = [

                'VEGETABLE' => [
                    'Ash gourd','Beetroot','Bitter Guard','Bottle Guard','Brinjal','Broccoli',
                    'Cabbage','Capsicum','Carrot','Cauliflower','Chilli','Cluster Bean',
                    'Coriander','Cowpea','Cucumber','Dolichos','Drumstick','French Bean',
                    'Karengda','Knol khol','Lab Beans','Lettuce','Long Melon','Okra','Onion',
                    'Parsley','Peas','Pigeon Pea','Pointed gourd','Potato','Pumpkin','Radish',
                    'Ridge Gourd','Snakes Gourd','Spinach','Sponge gourd','Squas','Tandarjo',
                    'Tinda','Tomato','Turnip','Arvi','Celery','Mushroom','Pole Beans',
                    'Shepu','Gum Guvar','Chicory',
                ],

                'OIL SEED' => [
                    'Castor','Cotton','Groundnut','Mustard','Sesame','Soyabean',
                    'Sunflower','Jojoba','Olive','Rapeseed','Taramira',
                ],

                'CEREALS' => [
                    'Bajra','Barley','Juvar','Maize','Oats','Paddy','Wheat',
                    'Bajri','Baby Corn','Sorghum Sudan Grass',
                ],

                'PLUSES' => [
                    'Chickpea','Green Gram','Tur','Urad','Val','Yard Long Beans',
                ],

                'SUGAR AND STARCH CROP' => [
                    'Sugarcane',
                ],

                'FODDER' => [
                    'Fodder Seed','Rajko','Rajka Bajri','Fodder Maize',
                    'Fodder Bajra','Fodder Sorghum',
                ],

                'FRUIT' => [
                    'Pomogranate','Banana','Mulberry','Custard Apple','Dragon fruit',
                    'Ber','Datepalm','Grapes','Guvava','Jamun','Kivi','Litchi','Mango',
                    'Muskmelon','Orange','Papaya','Peach','Pear','Sapota',
                    'Sweet Orange','Watermelon',
                ],

                'MEDICINAL' => [
                    'Isabgol','Tulsi','Amala',
                ],

                'FLOWER' => [
                    'Marigold',
                ],

                'SPICE CROP' => [
                    'Garlic','Turmeric','Cumin','Fenugreek','Fennel',
                    'Coriander','Ginger','Black Pepper',
                ],
            ];

            foreach ($data as $categoryName => $subCategories) {

                // Category
                $category = CropCategory::firstOrCreate(
                    ['name' => $categoryName],
                    ['status' => 1]
                );

                // Sub Categories
                foreach ($subCategories as $sub) {
                    CropSubCategory::firstOrCreate(
                        [
                            'crop_category_id' => $category->id,
                            'name' => $sub
                        ],
                        [
                            'status' => 1
                        ]
                    );
                }
            }
        });
    }
}
