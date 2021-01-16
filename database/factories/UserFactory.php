<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory 
 */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(
    User::class, function (Faker $faker) {
        return [
        'name' => $faker->name,
        'surname' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'avatar' => 'public/storage/avatars/13b73edae8443990be1aa8f1a483bc27.jpg',
        'email_verified_at' => now(),
        'activation_token' => Str::random(10),
        ];
    }
);
