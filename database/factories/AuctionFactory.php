<?php

use Faker\Generator as Faker;
use App\Auction;

$factory->define(Auction::class, function (Faker $faker) {
        $price = $faker->numberBetween(200, 1000);
        return [
            'car_name' => $faker->word,
            'final_price' => $price,
            'start_bid_amount' => $price,
            'location' => $faker->city, // password
            'end_time' => $faker->dateTimeBetween('+2 days', '2 months'),
        ];
});
