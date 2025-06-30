<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DishSize;
use App\Models\Dish;

class DishSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dishes = Dish::all();

        foreach ($dishes as $dish) {
            // Determine base price based on category and dish type
            $basePrice = $this->getBasePriceForDish($dish);

            // Add sizes for each dish with price variations
            $sizes = [
                [
                    'dish_id' => $dish->id,
                    'size' => 'small',
                    'price' => round($basePrice * 0.75, 2), // 75% of base price
                ],
                [
                    'dish_id' => $dish->id,
                    'size' => 'medium',
                    'price' => $basePrice, // Base price
                ],
                [
                    'dish_id' => $dish->id,
                    'size' => 'large',
                    'price' => round($basePrice * 1.4, 2), // 140% of base price
                ]
            ];

            foreach ($sizes as $sizeData) {
                DishSize::create($sizeData);
            }
        }
    }

    /**
     * Determine base price based on dish characteristics
     */
    private function getBasePriceForDish($dish)
    {
        $dishName = strtolower($dish->name);
        
        // Fine dining dishes - higher prices
        if (stripos($dishName, 'truffle') !== false || 
            stripos($dishName, 'duck confit') !== false || 
            stripos($dishName, 'beef tenderloin') !== false) {
            return rand(450, 650); // 450-650 EGP
        }
        
        // Seafood dishes - premium pricing
        if (stripos($dishName, 'seafood') !== false || 
            stripos($dishName, 'salmon') !== false || 
            stripos($dishName, 'fish') !== false ||
            stripos($dishName, 'paella') !== false) {
            return rand(250, 400); // 250-400 EGP
        }
        
        // Italian pasta and pizza - moderate pricing
        if (stripos($dishName, 'pasta') !== false || 
            stripos($dishName, 'pizza') !== false || 
            stripos($dishName, 'risotto') !== false ||
            stripos($dishName, 'carbonara') !== false ||
            stripos($dishName, 'lasagna') !== false) {
            return rand(150, 280); // 150-280 EGP
        }
        
        // Grilled meat dishes - moderate to high pricing
        if (stripos($dishName, 'grill') !== false || 
            stripos($dishName, 'beef') !== false || 
            stripos($dishName, 'lamb') !== false ||
            stripos($dishName, 'burger') !== false) {
            return rand(180, 320); // 180-320 EGP
        }
        
        // Breakfast items - lower pricing
        if (stripos($dishName, 'eggs') !== false || 
            stripos($dishName, 'croissant') !== false || 
            stripos($dishName, 'ful') !== false ||
            stripos($dishName, 'acai') !== false) {
            return rand(45, 120); // 45-120 EGP
        }
        
        // Sandwiches and wraps - moderate pricing
        if (stripos($dishName, 'sandwich') !== false || 
            stripos($dishName, 'wrap') !== false || 
            stripos($dishName, 'shawarma') !== false ||
            stripos($dishName, 'falafel') !== false) {
            return rand(80, 150); // 80-150 EGP
        }
        
        // Salads and healthy options - moderate pricing
        if (stripos($dishName, 'salad') !== false || 
            stripos($dishName, 'quinoa') !== false || 
            stripos($dishName, 'tabbouleh') !== false ||
            stripos($dishName, 'healthy') !== false) {
            return rand(90, 180); // 90-180 EGP
        }
        
        // Desserts - moderate pricing
        if (stripos($dishName, 'cheesecake') !== false || 
            stripos($dishName, 'fondant') !== false || 
            stripos($dishName, 'baklava') !== false) {
            return rand(70, 140); // 70-140 EGP
        }
        
        // Asian cuisine - moderate pricing
        if (stripos($dishName, 'teriyaki') !== false || 
            stripos($dishName, 'curry') !== false || 
            stripos($dishName, 'thai') !== false) {
            return rand(120, 220); // 120-220 EGP
        }
        
        // Traditional Egyptian dishes - affordable pricing
        if (stripos($dishName, 'koshari') !== false || 
            stripos($dishName, 'molokhia') !== false || 
            stripos($dishName, 'mahshi') !== false) {
            return rand(60, 140); // 60-140 EGP
        }
        
        // Default pricing for other dishes
        return rand(100, 200); // 100-200 EGP
    }
}
