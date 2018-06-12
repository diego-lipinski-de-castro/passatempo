<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween(1, 1000),
        'date' => $faker->date($format = 'Y-m-d H:i:s', $max = 'now')
    ];
});
