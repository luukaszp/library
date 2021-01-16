<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @test
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * User cannot be register without required fields.
     *
     * @test
     * @return void
     */
    public function testRequiredFieldsForRegistration()
    {
        $this->withoutMiddleware();
        $user = factory(\App\User::class)->make([]);
        
        $this->post('api/register', $user->toArray(), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    /**
     * User cannot be register without password confirmation.
     *
     * @test
     * @return void
     */
    public function testRepeatPassword()
    {
        $this->withoutMiddleware();
        $user = factory(\App\User::class)->make(
            [
            "name" => "John",
            "surname" => "Doe",
            "email" => "doe@example.com",
            "password" => bcrypt('secret')
            ]
        );

        $this->post('api/register', $user->toArray(), ['Accept' => 'application/json'])
            ->assertStatus(422);
    }

    /**
     * User can register with proper data.
     *
     * @test
     * @return void
     */
    public function testSuccessfulRegistration()
    {
        $this->withoutMiddleware();
        $user = [
            "name" => "John",
            "surname" => "Doe",
            "email" => "doe@example.com",
            "password" => "zaq1@WSX",
            "card_number" => "1212121212",
            "activation_token" => Str::random(40),
            "password_confirmation" => "zaq1@WSX"
        ];

        $this->post('api/register', $user, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    /**
     * Test if reader can login with proper data.
     *
     * @test
     * @return void
     */
    public function testLoginReader()
    {
        $user = factory(\App\User::class)->create();

        $reader = factory(\App\Reader::class)->create(
            [
            'card_number' => '1212121212',
            'password' => Hash::make('zaq1@WSX'),
            'user_id' => '1'
            ]
        );

        $response = $this->post(
            'api/loginReader', [
            'card_number' => '1212121212',
            'password' => 'zaq1@WSX',
            'user_id' => '1'
            ]
        );

        $response->assertStatus(201);
    }

    /**
     * Test if worker can login with proper data.
     *
     * @test
     * @return void
     */
    public function testLoginWorker()
    {
        $user = factory(\App\User::class)->create();

        $worker = factory(\App\Worker::class)->create(
            [
            'id_number' => '123123123123',
            'password' => Hash::make('zaq1@WSX'),
            'user_id' => '1'
            ]
        );

        $response = $this->post(
            'api/loginWorker', [
            'id_number' => '123123123123',
            'password' => 'zaq1@WSX',
            'user_id' => '1'
            ]
        );

        $response->assertStatus(201);
    }

    /**
     * Test if reader cannot login with wrong data.
     *
     * @test
     * @return void
     */
    public function testFailLoginReader()
    {
        $user = factory(\App\User::class)->create();

        $reader = factory(\App\Reader::class)->create(
            [
            'card_number' => '1212121212',
            'password' => Hash::make('zaq1@WSX'),
            'user_id' => '1'
            ]
        );

        $response = $this->post(
            'api/loginReader', [
            'card_number' => '1212121212',
            'password' => 'abcdefg',
            'user_id' => '1'
            ]
        );

        $response->assertStatus(401);
    }

    /**
     * Test if worker cannot login with wrong data.
     *
     * @test
     * @return void
     */
    public function testFailLoginWorker()
    {
        $user = factory(\App\User::class)->create();

        $worker = factory(\App\Worker::class)->create(
            [
            'id_number' => '123123123123',
            'password' => Hash::make('zaq1@WSX'),
            'user_id' => '1'
            ]
        );

        $response = $this->post(
            'api/loginWorker', [
            'id_number' => '123123123123',
            'password' => 'abcdef',
            'user_id' => '1'
            ]
        );

        $response->assertStatus(401);
    }

    /**
     * Reader can change password.
     *
     * @test
     * @return void
     */
    public function testReaderPasswordChange()
    {
        $this->actingAs(factory(\App\User::class)->create());

        $reader = factory(\App\Reader::class)->create(
            [
            'user_id' => '1'
            ]
        );

        $reader->password = Hash::make('newpassword');
        $response = $this->post('api/password-change/', $reader->toArray());

        $response->assertStatus(200);
    }
}
