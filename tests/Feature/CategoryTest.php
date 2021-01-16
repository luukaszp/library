<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Login worker.
     *
     * @return void
     */
    public function login()
    {
        $this->withoutMiddleware();
        $user = factory(\App\User::class)->create(
            [
            'id' => '1'
            ]
        );
        $worker = factory(\App\Worker::class)->create(
            [
            'id_number' => '123123123123',
            'password' => bcrypt('zaq1@WSX')
            ]
        );

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($worker);

        $this->actingAs($worker);
    }

    /**
     * Worker can create new category.
     *
     * @test
     * @return void
     */
    public function testCreateCategory()
    {
        $this->login();

        $category = factory(\App\Category::class)->create(
            [
            'name' => 'Comedy'
            ]
        );

        $response = $this->post('api/category/add', $category->toArray());

        $response->assertStatus(201);
    }

    /**
     * Get all categories.
     *
     * @test
     * @return void
     */
    public function testGetAllCategories()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/category/getCategories');

        $response->assertStatus(200);
    }

    /**
     * Worker can edit category.
     *
     * @test
     * @return void
     */
    public function testEditCategory()
    {
        $this->login();

        $category = factory(\App\Category::class)->create(
            [
            'name' => 'Comedy',
            'id' => '1'
            ]
        );

        $response = $this->post('api/category/add', $category->toArray());

        $response = $this->put('api/category/edit/1', ['name' => 'Thriller']);

        $response->assertStatus(200);
    }

    /**
     * Worker can delete category.
     *
     * @test
     * @return void
     */
    public function testDeleteCategory()
    {
        $this->login();

        $category = factory(\App\Category::class)->create(
            [
            'id' => '1'
            ]
        );

        $response = $this->post('api/category/add', $category->toArray());

        $response = $this->delete('api/category/delete/1');

        $response->assertStatus(200);
    }
}
