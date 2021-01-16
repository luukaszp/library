<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Author;
use Faker\Generator as Faker;

$factory->define(Author::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'surname' => $faker->name,
        'description' => $faker->paragraph,
        'photo' => $faker->image('public/storage/authors',640,480, null, false),
        ];
});
