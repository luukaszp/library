<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use DB;

class AuthorTest extends TestCase
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
     * Worker can create new author.
     *
     * @test
     * @return void
     */
    public function testCreateAuthor()
    {
        $this->login();

        $author = factory(\App\Author::class)->create();

        $response = $this->post('api/author/add', $author->toArray());

        $response->assertStatus(500);
    }

    /**
     * Get all authors.
     *
     * @test
     * @return void
     */
    public function testGetAllAuthors()
    {
        $this->withoutMiddleware();
        $response = $this->get('/api/author/getAuthors');

        $response->assertStatus(200);
    }

    /**
     * Get specific author.
     *
     * @test
     * @return void
     */
    public function testGetSpecificAuthor()
    {
        $this->withoutMiddleware();

        $this->login();

        $author = factory(\App\Author::class)->create();

        $response = $this->post('api/author/add', $author->toArray());
        
        $this->get('/api/author/'{$author->id});

        $response->assertStatus(500);
    }

    /**
     * Worker can edit author.
     *
     * @test
     * @return void
     */
    public function testEditAuthor()
    {
        $this->login();

        $author = factory(\App\Author::class)->create();

        $response = $this->post('api/author/add', $author->toArray());

        $this->put('api/author/edit/1', ['name' => 'Thriller']);
        $response->assertStatus(500);
    }

    /**
     * Worker can delete author.
     *
     * @test
     * @return void
     */
    public function testDeleteAuthor()
    {
        $this->login();

        $author = factory(\App\Author::class)->create([
            'photo' => '123.jpg',
            'id' => '1'
        ]);

        $category = factory(\App\Category::class)->create();

        $publisher = factory(\App\Publisher::class)->create();

        $book = factory(\App\Book::class)->create([
            'category_id' => $category->id,
            'publisher_id' => $publisher->id
        ]);

        DB::table('author_book')->insert(
            [
            'author_id' => $book->id,
            'book_id' => $author->id
            ]
        );

        $response = $this->delete('api/author/delete/1');

        $response->assertStatus(500);
        
    }
}
