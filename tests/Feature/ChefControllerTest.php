<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Chef;
use App\Models\User;
use App\Models\Dish;
use App\Models\Category;

class ChefControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_open_chefs()
    {
        $verifiedChefs = Chef::factory()->count(3)->create(['is_verified' => true]);
        $unverifiedChefs = Chef::factory()->count(2)->create(['is_verified' => false]);

        $response = $this->getJson('/api/open-resturants');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Open Resturants retrieved successfully',
            ])
            ->assertJsonCount($verifiedChefs->count(), 'data');

        foreach ($response->json('data') as $chef) {
            $this->assertTrue($chef['status']['is_verified']);
        }
    }

    /** @test */
    public function it_returns_chef_with_categories_and_meals()
    {
        // Arrange: create a chef, categories, and dishes
        $chef = Chef::factory()->create(['is_verified' => true]);
        $category = Category::factory()->create();
        $dish1 = Dish::factory()->create(['chef_id' => $chef->id, 'category_id' => $category->id]);
        $dish2 = Dish::factory()->create(['chef_id' => $chef->id, 'category_id' => $category->id]);

        // Act: call the endpoint
        $response = $this->getJson('/api/resturants/' . $chef->id);

        // Assert: correct structure and data
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Resturant details retrieved successfully',
            ])
            ->assertJsonStructure([
                'data' => [
                    'Resturant' => [
                        'id', 'name', 'description', 'location', 'profile_image', 'bio', 'phone', 'email', 'status', 'rating', 'stats', 'joined_at'
                    ],
                    'menu' => [
                        'categories_count', 'total_dishes', 'categories'
                    ]
                ]
            ]);
        $this->assertEquals($chef->id, $response->json('data.Resturant.id'));
    }

    /** @test */
    public function it_returns_404_if_chef_not_found()
    {
        $response = $this->getJson('/api/resturants/999999');
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Resturant not found',
            ]);
    }
}
