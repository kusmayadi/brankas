<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Password;
use App\User;
use Faker\Generator as Faker;

$factory->define(Password::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'url' => $faker->url,
        'login' => $faker->email,
        'notes' => $faker->text,
        'password' => $faker->password,
        'user_id' => factory(App\User::class),
    ];
});
