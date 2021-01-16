<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublisherTest extends TestCase
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
     * Worker can create new publisher.
     *
     * @test
     * @return void
     */
    public function testCreatePublisher()
    {
        $this->login();

        $publisher = factory(\App\Publisher::class)->create(
            [
            'name' => 'Helion'
            ]
        );

        $response = $this->post('api/publisher/add', $publisher->toArray());

        $response->assertStatus(201);
    }

    /**
     * Worker can edit publisher.
     *
     * @test
     * @return void
     */
    public function testEditPublisher()
    {
        $this->login();

        $publisher = factory(\App\Publisher::class)->create(
            [
            'name' => 'Helion',
            'id' => '1'
            ]
        );

        $response = $this->post('api/publisher/add', $publisher->toArray());

        $response = $this->put('api/publisher/edit/1', ['name' => 'Nowa Era']);

        $response->assertStatus(200);
    }

    /**
     * Worker can delete publisher.
     *
     * @test
     * @return void
     */
    public function testDeletePublisher()
    {
        $this->login();

        $publisher = factory(\App\Publisher::class)->create(
            [
            'id' => '1'
            ]
        );

        $response = $this->post('api/publisher/add', $publisher->toArray());

        $response = $this->delete('api/publisher/delete/1');

        $response->assertStatus(200);
    }
}
