<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Rating;
use Faker\Generator as Faker;

$factory->define(Rating::class, function (Faker $faker) {
    return [
        'rate' => $faker->numberBetween(1, 5),
        'opinion' => $faker->paragraph,
        'book_id' => '1',
        'reader_id' => '1'
        ];
});
