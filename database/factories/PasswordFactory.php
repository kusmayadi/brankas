<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Password;
use Faker\Generator as Faker;

$factory->define(Password::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'url' => $faker->url,
        'login' => $faker->email,
        'password' => $faker->password,
        'notes' => $faker->text
    ];
});
