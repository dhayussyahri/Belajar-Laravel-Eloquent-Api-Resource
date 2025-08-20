<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use App\Http\Resources\CategoryResource;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    public function testProduct()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $product = Product::first();

        $this->get("/api/products/$product->id")
            ->assertStatus(200)
            ->assertJson([
                "value" => [
                    "name" => $product->name,
                    "category" => [
                        "id" => $product->category->id,
                        "name" => $product->category->name,
                    ],
                    "price" => $product->price,
                    "created_at" => $product->created_at->toJson(),
                    "updated_at" => $product->updated_at->toJson(),
                ]
            ]);
    }

    public function testCollectionWrap()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get('/api/products')
            ->assertStatus(200);

        $names = $response->json("data.*.name");
        for($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Food", $names);

        }
        for($i = 0; $i < 5; $i++) {
            self::assertContains("Product $i of Gadget", $names);

        }
    }

    public function testProductsPaging()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $response = $this->get('/api/products-paging')
            ->assertStatus(200);

        self::assertCount(2, $response->json("data"));
        self::assertNotNull($response->json("links"));
        self::assertNotNull($response->json("meta"));
        self::assertNotNull($response->json("data"));
    }
}
