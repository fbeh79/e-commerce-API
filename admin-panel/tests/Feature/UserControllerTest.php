<?php

namespace Tests\Feature;

use App\Models\User;
use Couchbase\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
use refreshDatabase, withFaker;

public function it_can_list_users(){
    User::factory()->count(5)->create();
    $response = $this->get('/api/users');
    $response->assertStatus(200);
    $response->assertJsonStructure([

            'data' => [
                '*' => ['id', 'name', 'email', 'cell_phone', 'roles']
            ],
            'links',
            'meta' => ['current_page', 'last_page', 'per_page', 'total']
        ]);
    }

    public function it_can_create_user(){
    $role = Role::factory()->create();

    $userdata=[
        'name' => $this->faker->name,
        'email' => $this->faker->unique()->safeEmail,
        'password'=>$this->faker->password,
        'cell_phone' => $this->faker->phoneNumber,
        'role_id' => $role->id

    ];
    $response = $this->postJson('/api/users', $userdata);
    $response->assertStatus(201);

    $data=assertDatabaseHas('users', $userdata);

    }

 }
