<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SuggestionTest extends TestCase
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
     * Login reader.
     *
     * @return void
     */
    public function loginReader()
    {
        $this->withoutMiddleware();
        $user = factory(\App\User::class)->create(
            [
            'id' => '1'
            ]
        );
        $reader = factory(\App\Reader::class)->create(
            [
            'card_number' => '1212121212',
            'password' => bcrypt('zaq1@WSX')
            ]
        );

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($reader);

        $this->actingAs($reader);
    }

    /**
     * Reader can create new suggestion.
     *
     * @test
     * @return void
     */
    public function testCreateSuggestion()
    {
        $this->loginReader();

        $suggestion = factory(\App\Suggestion::class)->create();

        $response = $this->post('api/suggestions/add', $suggestion->toArray());

        $response->assertStatus(201);
    }

    /**
     * Get all suggestion.
     *
     * @test
     * @return void
     */
    public function testGetAllSuggestions()
    {
        $this->login();
        $response = $this->get('/api/suggestions/getSuggestions');

        $response->assertStatus(200);
    }

    /**
     * Worker can delete suggestion.
     *
     * @test
     * @return void
     */
    public function testDeleteSuggestion()
    {
        $this->login();

        $reader = factory(\App\Reader::class)->create();

        $suggestion = factory(\App\Suggestion::class)->create(
            [
            'id' => '1'
            ]
        );

        $response = $this->post('api/suggestions/add', $suggestion->toArray());

        $response = $this->delete('api/suggestions/delete/1');

        $response->assertStatus(200);
    }
}
