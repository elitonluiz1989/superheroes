<?php

use Faker\Generator as Faker;

$factory->define(App\Superhero::class, function (Faker $faker) {
    return [
        'nickname' => $faker->name,
        'real_name' => $faker->name,
        'origin_description' => $faker->text,
        'superpowers' => $faker->text,
        'catch_phrase' => $faker->text
    ];
});

$factory->define(App\SuperheroImage::class, function (Faker $faker) {
    return [
        'superhero_id' => $faker->numberBetween(0, 100),
        'superhero_image' => $faker->name,
    ];
});
