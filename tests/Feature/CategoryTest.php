<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testResource()
    {
        $this->seed([CategorySeeder::class]);
        $category = Category::first();

        $this->get("/api/categories/$category->id")
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'created_at' => $category->created_at->toJson(),
                    'updated_at' => $category->updated_at->toJson(),

                ]
            ]);
    }
}
