<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Suggestion;
use Faker\Generator as Faker;

$factory->define(Suggestion::class, function (Faker $faker) {
    return [
        'type' => $faker->word,
        'description' => $faker->paragraph,
        'reader_id' => '1'
        ];
});
