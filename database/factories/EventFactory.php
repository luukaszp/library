<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'time' => $faker->time($format = 'H:i', $max = 'now'),
        'type_id' => '1'
        ];
});
