<?php

use Illuminate\Database\Seeder;

class AuctionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Auction::class, 10)->create()->each(function ($auction) {
            $auction->image()->save(factory(App\Image::class)->make());
        });
    }
}
