<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BorrowTest extends TestCase
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
     * Get all borrows (month).
     *
     * @test
     * @return void
     */
    public function testGetAllBorrowsMonth()
    {
        $this->login();
        $response = $this->get('/api/borrow/monthly');

        $response->assertStatus(200);
    }

    /**
     * Get all borrows.
     *
     * @test
     * @return void
     */
    public function testGetAllBorrows()
    {
        $this->login();
        $response = $this->get('/api/borrow/getBorrows');

        $response->assertStatus(200);
    }

    /**
     * Get all delayed borrows.
     *
     * @test
     * @return void
     */
    public function testGetAllDelayedBorrows()
    {
        $this->login();
        $response = $this->get('/api/borrow/getDelayed');

        $response->assertStatus(200);
    }
}
