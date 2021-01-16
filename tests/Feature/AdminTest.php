<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Login admin.
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
        $admin = factory(\App\Worker::class)->create(
            [
            'id_number' => '123123123123',
            'password' => bcrypt('zaq1@WSX'),
            'is_admin' => '1'
            ]
        );

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($admin);

        $this->actingAs($admin);
    }

    /**
     * Get all workers.
     *
     * @test
     * @return void
     */
    public function testGetAllWorkers()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/user/getWorkers');

        $response->assertStatus(200);
    }

    /**
     * Get all roles.
     *
     * @test
     * @return void
     */
    public function testGetAllRoles()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/user/getRoles');

        $response->assertStatus(200);
    }

    /**
     * Admin can edit worker data.
     *
     * @test
     * @return void
     */
    public function testEditWorker()
    {
        $this->login();

        $worker = factory(\App\Worker::class)->create();

        $this->post('api/register', $worker->toArray())
            ->assertStatus(302);

        $this->put('api/worker/edit/2', ['name' => 'Adam']);
    }

    /**
     * Admin can edit role of worker.
     *
     * @test
     * @return void
     */
    public function testEditRole()
    {
        $this->login();

        $worker = factory(\App\Worker::class)->create();

        $response = $this->post('api/register', $worker->toArray());

        $this->put('api/roles/edit/2', ['is_admin' => '1']);

        $response->assertStatus(302);

    }

    /**
     * Admin can delete worker.
     *
     * @test
     * @return void
     */
    public function testDeleteWorker()
    {
        $this->login();

        $worker = factory(\App\Worker::class)->create();

        $this->post('api/register', $worker->toArray())
            ->assertStatus(302);

        $this->delete('api/worker/delete/2');
    }
}
