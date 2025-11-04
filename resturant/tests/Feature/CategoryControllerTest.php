<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_index_returns_paginate_categories(){
        Category::factory()->count(3)->create();
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'status']
            ],
            'links',
            'meta'
        ]);


        $this->assertCount(1,$response->json('data'));
    }
}
