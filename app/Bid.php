<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount', 'auction_id', 'user_id'];

    /**
     * Get the Auction that owns the Bid.
     */
    public function Auction()
    {
        return $this->belongsTo('App\Auction');
    }

    /**
     * Get the User that owns the Bid.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
