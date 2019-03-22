<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['car_name', 'final_price', 'start_bid_amount', 'location', 'end_time'];

     /**
     * Get the Bids for the Auctions.
     */
    public function Bids()
    {
        return $this->hasMany('App\Bid');
    }
}
