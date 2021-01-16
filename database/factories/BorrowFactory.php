<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Borrow;
use Faker\Generator as Faker;

$factory->define(Borrow::class, function (Faker $faker) {
    return [
        'borrows_date' => '2021-02-01',//$faker->dateTime($max = 'now', $timezone = 'Europe/Warsaw') ,
        'returns_date' => '2021-01-01',//$faker->dateTimeBetween('+ 30 days', $timezone = 'Europe/Warsaw'),
        'when_returned' => '2021-01-15',//$faker->dateTimeBetween('+ 15 days', $timezone = 'Europe/Warsaw'),
        'delay' => $faker->numberBetween(1,30),
        'penalty' => $faker->numberBetween(60,120),
        'book_id' => '1',
        'reader_id' => '1',
        'worker_id' => '1'
        ];
});
