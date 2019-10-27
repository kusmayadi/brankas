<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Server;
use App\User;
use Faker\Generator as Faker;

$factory->define(Server::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(3),
        'url' => $faker->url,
        'username' => $faker->userName,
        'password' => $faker->password,
        'console_url' => $faker->url,
        'console_username' => $faker->userName,
        'console_password' => $faker->password,
        'hostname' => $faker->ipv4,
        'notes' => $faker->text,
        'user_id' => function () {
            return User::inRandomOrder()->first()->id;
        }
    ];
});
