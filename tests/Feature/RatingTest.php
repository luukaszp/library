<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Login reader.
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
     * Get all ratings.
     *
     * @test
     * @return void
     */
    public function testGetAllRatings()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/rating/all/1');

        $response->assertStatus(200);
    }

    /**
     * Get specific rating.
     *
     * @test
     * @return void
     */
    public function testGetSpecificRating()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/rating/1');

        $response->assertStatus(200);
    }
}
