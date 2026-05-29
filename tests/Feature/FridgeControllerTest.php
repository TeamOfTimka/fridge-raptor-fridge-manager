<?php

namespace Tests\Feature;

use App\Models\FridgeItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FridgeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products()
    {
        FridgeItem::factory()->create(['name' => 'Milk', 'quantity' => 2]);
        FridgeItem::factory()->create(['name' => 'Bread', 'quantity' => 1]);

        $response = $this->getJson('/api/fridge');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['id', 'name', 'quantity', 'unit']
                ]
            ])
            ->assertJsonCount(2, 'data');
    }

    public function test_can_create_product()
    {
        $productData = [
            'name' => 'Cheese',
            'quantity' => 3,
            'unit' => 'pieces'
        ];

        $response = $this->postJson('/api/fridge', $productData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product added successfully',
                'data' => [
                    'name' => 'Cheese',
                    'quantity' => 3,
                    'unit' => 'pieces'
                ]
            ]);

        $this->assertDatabaseHas('fridge_items', ['name' => 'Cheese']);
    }

    public function test_can_get_single_product()
    {
        $product = FridgeItem::factory()->create(['name' => 'Butter']);

        $response = $this->getJson("/api/fridge/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'id' => $product->id,
                    'name' => 'Butter'
                ]
            ]);
    }

    public function test_returns_404_if_product_not_found()
    {
        $response = $this->getJson('/api/fridge/999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'Product not found'
            ]);
    }

    public function test_can_update_product()
    {
        $product = FridgeItem::factory()->create(['name' => 'Apple', 'quantity' => 5]);

        $response = $this->putJson("/api/fridge/{$product->id}", [
            'quantity' => 10,
            'name' => 'Green Apple'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated successfully',
                'data' => [
                    'name' => 'Green Apple',
                    'quantity' => 10
                ]
            ]);

        $this->assertDatabaseHas('fridge_items', [
            'id' => $product->id,
            'name' => 'Green Apple',
            'quantity' => 10
        ]);
    }

    public function test_can_delete_product()
    {
        $product = FridgeItem::factory()->create();

        $response = $this->deleteJson("/api/fridge/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ]);

        $this->assertDatabaseMissing('fridge_items', ['id' => $product->id]);
    }

    public function test_validation_fails_when_name_missing()
    {
        $response = $this->postJson('/api/fridge', [
            'quantity' => 5,
            'unit' => 'kg'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }
}