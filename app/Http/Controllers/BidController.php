<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auction;
use App\Bid;

class BidController extends Controller
{
    /**
     * Store Bid in database
     * 
     * @param Request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'auc_id' => 'required|numeric',
            'amount' => 'required|numeric'
        ]);
        
        $auction = Auction::find($request->auc_id);
        if(!$auction)
            return response()->json([
                "message" => "No Auction found with the provided id"
            ], 404);
        
        if($auction->final_price >= $request->amount)
            return response()->json([
                "message" => "The price you've provided must be greater than the final price!"
            ], 422);

        if(!Bid::where([
            'auction_id' => $request->auc_id,
            'user_id' => $request->user()->id,
            ])->first()) {
            $bid = Bid::create([
                'user_id' => $request->user()->id,
                'amount' => $request->amount,
                'auction_id' => $request->auc_id
            ]);

            $auction->final_price = $request->amount;
            $auction->save();
                
            return response()->json([
                "message" => "Bid added successfully"
            ], 201);
        }
    }
}
