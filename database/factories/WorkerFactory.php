<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Worker;
use Faker\Generator as Faker;

$factory->define(Worker::class, function (Faker $faker) {
    return [
        'id_number' => $faker->unique()->numberBetween(100000000000, 999999999999),
        'password' => bcrypt('zaq1@WSX'),
        'is_admin' => $faker->boolean(0),
        'user_id' => '1'
    ];
});
