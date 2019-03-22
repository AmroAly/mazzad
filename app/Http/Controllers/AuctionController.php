<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auction;

class AuctionController extends Controller
{
    /**
     * Store Auction in database
     * @param Request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_name' => 'required|min:2',
            'start_bid_amount' => 'required|numeric',
            'location'  => 'required|min:6',
            'end_time' => 'required|date|after:today',
            'auction_status' => 'required|boolean'
        ]);
        
        Auction::create([
            'car_name' => $request->car_name,
            'start_bid_amount' => $request->start_bid_amount,
            'final_price' => $request->start_bid_amount,
            'location' => $request->location,
            'end_time' => $request->end_time,
            'auction_status' => $request->auction_status
        ]);

        return response()->json([
            "message" => "Auction created successflly!"
        ], 201);
    }
}
