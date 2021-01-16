<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CalendarTest extends TestCase
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
     * Worker can create new event.
     *
     * @test
     * @return void
     */
    public function testCreateEvent()
    {
        $this->login();

        $type = factory(\App\Type::class)->create(
            [
            'id' => '1'
            ]
        );

        $event = factory(\App\Event::class)->create(
            [
            'name' => 'Butterfly',
            'type_id' => '1'
            ]
        );

        $response = $this->post('api/calendar/event/add', $event->toArray());

        $response->assertStatus(201);
    }

    /**
     * Get all events.
     *
     * @test
     * @return void
     */
    public function testGetAllEvents()
    {
        $this->login();

        $response = $this->get('/api/calendar/event/getEvents');

        $response->assertStatus(200);
    }

    /**
     * Worker can delete event.
     *
     * @test
     * @return void
     */
    public function testDeleteEvent()
    {
        $this->login();

        $type = factory(\App\Type::class)->create(
            [
            'id' => '1'
            ]
        );

        $event = factory(\App\Event::class)->create(
            [
            'id' => '1',
            'type_id' => '1'
            ]
        );

        $response = $this->post('api/calendar/event/add', $event->toArray());

        $response = $this->delete('api/calendar/event/delete/1');

        $response->assertStatus(200);
    }

    /**
     * Worker can create new type of event.
     *
     * @test
     * @return void
     */
    public function testCreateType()
    {
        $this->login();

        $type = factory(\App\Type::class)->create(
            [
            'name' => 'Meeting'
            ]
        );

        $response = $this->post('api/calendar/type/add', $type->toArray());

        $response->assertStatus(201);
    }

    /**
     * Get all types.
     *
     * @test
     * @return void
     */
    public function testGetAllTypes()
    {
        $this->login();

        $response = $this->get('/api/calendar/type/getTypes');

        $response->assertStatus(200);
    }

    /**
     * Worker can edit type.
     *
     * @test
     * @return void
     */
    public function testEditType()
    {
        $this->login();

        $type = factory(\App\Type::class)->create(
            [
            'name' => 'Meeting',
            'id' => '1'
            ]
        );

        $response = $this->post('api/calendar/type/add', $type->toArray());

        $response = $this->put('api/calendar/type/edit/1', ['name' => 'Reading']);

        $response->assertStatus(200);
    }

    /**
     * Worker can delete type.
     *
     * @test
     * @return void
     */
    public function testDeleteType()
    {
        $this->login();

        $type = factory(\App\Type::class)->create(
            [
            'id' => '1'
            ]
        );

        $response = $this->post('api/calendar/type/add', $type->toArray());

        $response = $this->delete('api/calendar/type/delete/1');

        $response->assertStatus(200);
    }
}
