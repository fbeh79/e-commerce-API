<?php

namespace Tests\Feature;

    use Tests\TestCase;
    use App\Models\Category;
    use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase

{
    use RefreshDatabase;

    public function test_can_create_category()
    {
        $data = [
            'name' => 'Test Category',
            'status' => 'active',
        ];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['id', 'name', 'status', 'products', 'created_at', 'updated_at']
            ]);

        $this->assertDatabaseHas('categories', ['name' => 'Test Category']);
    }

    public function test_can_update_category()
    {
        // ایجاد دسته‌بندی مستقیم بدون factory
        $category = Category::create([
            'name' => 'Old Name',
            'status' => 'active',
        ]);

        $data = [
            'name' => 'Updated Name',
            'status' => 'inactive',
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('categories', ['name' => 'Updated Name']);
    }

    public function test_can_delete_category()
    {
        $category = Category::create([
            'name' => 'To Delete',
            'status' => 'active',
        ]);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_can_list_categories()
    {
        // ایجاد چند دسته‌بندی بدون factory
        Category::create(['name' => 'Cat 1', 'status' => 'active']);
        Category::create(['name' => 'Cat 2', 'status' => 'active']);
        Category::create(['name' => 'Cat 3', 'status' => 'active']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(201)
            ->assertJsonCount(3, 'data');
    }
}
