<?php

use Faker\Generator as Faker;

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->randomFloat(2, 2, 100),
        'type_id' => $faker->numberBetween(1, 50)
    ];
});
