<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Book;
use Faker\Generator as Faker;

$factory->define(Book::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'isbn' => $faker->unique()->numberBetween(1000000000000, 9999999999999),
        'description' => $faker->paragraph,
        'publish_year' => $faker->year($max = 'now') ,
        'cover' => 'public/storage/books/13b73edae8443990be1aa8f1a483bc27.jpg',
        'is_available' => '1',
        'category_id' => '1',
        'publisher_id' => '1'
        ];
});
