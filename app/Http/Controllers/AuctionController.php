<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Auction;

class AuctionController extends Controller
{
    /**
     * Get all Auctions
     * 
     * @return Response
     */
    public function allAuctions()
    {
        $auctions = Auction::all();
        
        return response()->json(['auctions' => $auctions]);
    }

    /**
     * Get open Auctions
     * 
     * @return Response
     */
    public function openAuctions()
    {
        $auctions = Auction::where('end_time', '>', Carbon::now())->get();
        
        return response()->json(['auctions' => $auctions]);
    }

    /**
     * Get close Auctions
     * 
     * @return Response
     */
    public function closedAuctions()
    {
        $auctions = Auction::where('end_time', '<=', Carbon::now())->get();
        
        return response()->json(['auctions' => $auctions]);
    }

    /**
     * Store Auction in database
     * 
     * @param Request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'car_name' => 'required|min:2',
            'start_bid_amount' => 'required|numeric',
            'location'  => 'required|min:6',
            'end_time' => 'required|date|after:today'
        ]);
        
        Auction::create([
            'car_name' => $request->car_name,
            'start_bid_amount' => $request->start_bid_amount,
            'final_price' => $request->start_bid_amount,
            'location' => $request->location,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            "message" => "Auction created successflly!"
        ], 201);
    }

    /**
     * Delete Auction
     * 
     * @param Request
     * @return Response
     */
    public function delete(Request $request)
    {
        $auction = Auction::find($request->id);
        
        if($auction)
            $auction->delete();

        return response()->json([
            "message" => "Ok"
        ]);
    }
}
