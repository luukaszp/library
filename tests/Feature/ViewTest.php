<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Login worker.
     *
     * @return void
     */
    public function loginWorker()
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
     * Login admin.
     *
     * @return void
     */
    public function loginAdmin()
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
     * As user go to your own profile.
     *
     * @test
     * @return void
     */
    public function testProfileView()
    {
        $this->loginReader();

        $response = $this->get('api/user/profile/1');

        $response->assertStatus(200);
    }

    /**
     * As user check catalog of books.
     *
     * @test
     * @return void
     */
    public function testBookView()
    {
        $response = $this->get('api/book/getBooks');

        $response->assertStatus(200);
    }

    /**
     * As user check new books (10 days).
     *
     * @test
     * @return void
     */
    public function testNewBookView()
    {
        $response = $this->get('api/book/getNewBooks');

        $response->assertStatus(200);
    }

    /**
     * As user check specific book.
     *
     * @test
     * @return void
     */
    public function testSpecificBookView()
    {
        $category = factory(\App\Category::class)->create(
            [
            'id' => '1'
            ]
        );
        $publisher = factory(\App\Publisher::class)->create(
            [
            'id' => '1'
            ]
        );
        $book = factory(\App\Book::class)->create(
            [
            'id' => '1',
            'category_id' => '1',
            'publisher_id' => '1'
            ]
        );

        $response = $this->get('api/book/1');

        $response->assertStatus(200);
    }

    /**
     * As user check author page.
     *
     * @test
     * @return void
     */
    public function testAuthorPageView()
    {
        $author = factory(\App\Author::class)->create(
            [
            'id' => '1'
            ]
        );

        $response = $this->get('api/author/1');

        $response->assertStatus(200);
    }
}
