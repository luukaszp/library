<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reader;
use Faker\Generator as Faker;

$factory->define(Reader::class, function (Faker $faker) {
    return [
        'card_number' => $faker->unique()->numberBetween(1000000000, 9999999999),
        'password' => bcrypt('zaq1@WSX'),
        'can_extend' => $faker->boolean(1),
        'user_id' => '1'
        ];
});
