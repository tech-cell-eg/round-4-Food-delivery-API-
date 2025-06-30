<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dish;
use App\Models\Chef;
use App\Models\Category;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chefs = Chef::all();
        $categories = Category::all();

        $dishes = [
            // Traditional Egyptian Dishes
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Koshari',
                'description' => 'Egypt\'s national dish with rice, lentils, pasta, crispy onions, and spicy tomato sauce',
                'image' => 'https://images.unsplash.com/photo-1544807976-9b2b24998552?w=500',
                'is_available' => true,
                'total_rate' => 127,
                'avg_rate' => 4.8,
                'category_id' => $categories->where('name', 'Egyptian Cuisine')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Molokhia with Chicken',
                'description' => 'Traditional Egyptian green soup made with jute leaves, served with tender chicken',
                'image' => 'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=500',
                'is_available' => true,
                'total_rate' => 89,
                'avg_rate' => 4.5,
                'category_id' => $categories->where('name', 'Egyptian Cuisine')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Ful Medames',
                'description' => 'Traditional Egyptian breakfast of slow-cooked fava beans with tahini, olive oil, and vegetables',
                'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=500',
                'is_available' => true,
                'total_rate' => 156,
                'avg_rate' => 4.6,
                'category_id' => $categories->where('name', 'Traditional Breakfast')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Mahshi (Stuffed Vegetables)',
                'description' => 'Cabbage, zucchini, and bell peppers stuffed with rice, herbs, and minced meat',
                'image' => 'https://images.unsplash.com/photo-1574484284002-952d92456975?w=500',
                'is_available' => true,
                'total_rate' => 73,
                'avg_rate' => 4.3,
                'category_id' => $categories->where('name', 'Traditional Egyptian')->first()->id ?? null,
            ],

            // Italian Dishes
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Spaghetti Carbonara',
                'description' => 'Classic Italian pasta with eggs, pancetta, pecorino cheese, and black pepper',
                'image' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=500',
                'is_available' => true,
                'total_rate' => 201,
                'avg_rate' => 4.9,
                'category_id' => $categories->where('name', 'Italian Cuisine')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Risotto ai Frutti di Mare',
                'description' => 'Creamy Italian rice dish with mixed seafood, saffron, and white wine',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=500',
                'is_available' => true,
                'total_rate' => 94,
                'avg_rate' => 4.7,
                'category_id' => $categories->where('name', 'Seafood')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Margherita Pizza',
                'description' => 'Traditional Neapolitan pizza with tomato sauce, mozzarella, basil, and olive oil',
                'image' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=500',
                'is_available' => true,
                'total_rate' => 312,
                'avg_rate' => 4.8,
                'category_id' => $categories->where('name', 'Italian Cuisine')->first()->id ?? null,
            ],

            // Lebanese & Middle Eastern
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Mixed Grill Platter',
                'description' => 'Assorted grilled meats including kebab, kofta, and lamb chops with rice and grilled vegetables',
                'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=500',
                'is_available' => true,
                'total_rate' => 178,
                'avg_rate' => 4.6,
                'category_id' => $categories->where('name', 'Grilled & BBQ')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Hummus with Lamb',
                'description' => 'Creamy chickpea dip topped with spiced ground lamb, pine nuts, and olive oil',
                'image' => 'https://images.unsplash.com/photo-1512852939750-1305098529bf?w=500',
                'is_available' => true,
                'total_rate' => 145,
                'avg_rate' => 4.4,
                'category_id' => $categories->where('name', 'Lebanese & Middle Eastern')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Tabbouleh Salad',
                'description' => 'Fresh parsley salad with tomatoes, onions, mint, bulgur, lemon juice, and olive oil',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500',
                'is_available' => true,
                'total_rate' => 87,
                'avg_rate' => 4.2,
                'category_id' => $categories->where('name', 'Healthy & Diet')->first()->id ?? null,
            ],

            // Seafood Dishes
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Grilled Red Sea Fish',
                'description' => 'Fresh catch of the day grilled with Mediterranean herbs, lemon, and olive oil',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=500',
                'is_available' => true,
                'total_rate' => 132,
                'avg_rate' => 4.7,
                'category_id' => $categories->where('name', 'Seafood')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Seafood Paella',
                'description' => 'Spanish rice dish with shrimp, mussels, calamari, and saffron',
                'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=500',
                'is_available' => true,
                'total_rate' => 98,
                'avg_rate' => 4.5,
                'category_id' => $categories->where('name', 'Seafood')->first()->id ?? null,
            ],

            // Healthy & Diet Options
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Quinoa Buddha Bowl',
                'description' => 'Nutritious bowl with quinoa, roasted vegetables, avocado, and tahini dressing',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500',
                'is_available' => true,
                'total_rate' => 76,
                'avg_rate' => 4.3,
                'category_id' => $categories->where('name', 'Healthy & Diet')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Keto Salmon with Asparagus',
                'description' => 'Grilled salmon fillet with roasted asparagus and herb butter',
                'image' => 'https://images.unsplash.com/photo-1485921325833-c519f76c4927?w=500',
                'is_available' => true,
                'total_rate' => 65,
                'avg_rate' => 4.6,
                'category_id' => $categories->where('name', 'Healthy & Diet')->first()->id ?? null,
            ],

            // Asian Cuisine
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Chicken Teriyaki with Rice',
                'description' => 'Grilled chicken glazed with homemade teriyaki sauce, served with steamed rice and vegetables',
                'image' => 'https://images.unsplash.com/photo-1563379091339-03246963d7d3?w=500',
                'is_available' => true,
                'total_rate' => 143,
                'avg_rate' => 4.4,
                'category_id' => $categories->where('name', 'Asian Cuisine')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Thai Green Curry',
                'description' => 'Spicy coconut curry with chicken, Thai eggplant, basil, and jasmine rice',
                'image' => 'https://images.unsplash.com/photo-1455619452474-d2be8b1e70cd?w=500',
                'is_available' => true,
                'total_rate' => 89,
                'avg_rate' => 4.5,
                'category_id' => $categories->where('name', 'Asian Cuisine')->first()->id ?? null,
            ],

            // Breakfast Items
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Eggs Benedict',
                'description' => 'Poached eggs on English muffins with Canadian bacon and hollandaise sauce',
                'image' => 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=500',
                'is_available' => true,
                'total_rate' => 234,
                'avg_rate' => 4.7,
                'category_id' => $categories->where('name', 'Continental Breakfast')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Acai Bowl',
                'description' => 'Superfood bowl with acai, granola, fresh berries, and coconut flakes',
                'image' => 'https://images.unsplash.com/photo-1484723091739-30a097e8f929?w=500',
                'is_available' => true,
                'total_rate' => 167,
                'avg_rate' => 4.5,
                'category_id' => $categories->where('name', 'Healthy Breakfast')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Croissant with Zaatar',
                'description' => 'Buttery French croissant filled with traditional Middle Eastern zaatar mix',
                'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500',
                'is_available' => true,
                'total_rate' => 98,
                'avg_rate' => 4.2,
                'category_id' => $categories->where('name', 'Pastries & Sweets')->first()->id ?? null,
            ],

            // Sandwiches & Wraps
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Chicken Shawarma Wrap',
                'description' => 'Marinated chicken with vegetables, tahini sauce, and pickles in a warm tortilla',
                'image' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500',
                'is_available' => true,
                'total_rate' => 298,
                'avg_rate' => 4.6,
                'category_id' => $categories->where('name', 'Sandwiches & Wraps')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Falafel Sandwich',
                'description' => 'Crispy chickpea fritters with fresh vegetables and tahini in pita bread',
                'image' => 'https://images.unsplash.com/photo-1529059997568-3d847b1154f0?w=500',
                'is_available' => true,
                'total_rate' => 187,
                'avg_rate' => 4.3,
                'category_id' => $categories->where('name', 'Sandwiches & Wraps')->first()->id ?? null,
            ],

            // Fine Dining
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Duck Confit with Orange Sauce',
                'description' => 'Slow-cooked duck leg with crispy skin, served with orange reduction and roasted vegetables',
                'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=500',
                'is_available' => true,
                'total_rate' => 54,
                'avg_rate' => 4.9,
                'category_id' => $categories->where('name', 'Fine Dining')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Beef Tenderloin with Truffle',
                'description' => 'Premium beef tenderloin with truffle sauce, fondant potatoes, and seasonal vegetables',
                'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=500',
                'is_available' => true,
                'total_rate' => 43,
                'avg_rate' => 4.8,
                'category_id' => $categories->where('name', 'Fine Dining')->first()->id ?? null,
            ],

            // Desserts
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Baklava Cheesecake',
                'description' => 'Fusion dessert combining New York cheesecake with traditional baklava flavors',
                'image' => 'https://images.unsplash.com/photo-1551024739-2bd62a9a3480?w=500',
                'is_available' => true,
                'total_rate' => 112,
                'avg_rate' => 4.7,
                'category_id' => $categories->where('name', 'Pastries & Sweets')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Chocolate Fondant',
                'description' => 'Warm chocolate cake with molten center, served with vanilla ice cream',
                'image' => 'https://images.unsplash.com/photo-1551024739-2bd62a9a3480?w=500',
                'is_available' => true,
                'total_rate' => 189,
                'avg_rate' => 4.8,
                'category_id' => $categories->where('name', 'Pastries & Sweets')->first()->id ?? null,
            ],

            // More varied dishes
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Beef Burger Deluxe',
                'description' => 'Juicy beef patty with cheddar cheese, lettuce, tomato, and special sauce on brioche bun',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500',
                'is_available' => true,
                'total_rate' => 267,
                'avg_rate' => 4.4,
                'category_id' => $categories->where('name', 'Comfort Food')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Vegetarian Lasagna',
                'description' => 'Layers of pasta with ricotta, spinach, mushrooms, and marinara sauce',
                'image' => 'https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=500',
                'is_available' => true,
                'total_rate' => 98,
                'avg_rate' => 4.3,
                'category_id' => $categories->where('name', 'Italian Cuisine')->first()->id ?? null,
            ],
            [
                'chef_id' => $chefs->random()->id,
                'name' => 'Chicken Caesar Salad',
                'description' => 'Crisp romaine lettuce with grilled chicken, parmesan cheese, croutons, and Caesar dressing',
                'image' => 'https://images.unsplash.com/photo-1512852939750-1305098529bf?w=500',
                'is_available' => true,
                'total_rate' => 156,
                'avg_rate' => 4.1,
                'category_id' => $categories->where('name', 'Light Dinner')->first()->id ?? null,
            ],
        ];

        foreach ($dishes as $dishData) {
            Dish::create($dishData);
        }
    }
}
