<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Auction;
use App\Image as ImageUploader;
use Image;

class AuctionController extends Controller
{
    /**
     * Get all Auctions
     * 
     * @return Response
     */
    public function allAuctions()
    {
        $auctions = Auction::with(['bids', 'image'])->get();
        
        return response()->json(['auctions' => $auctions]);
    }

    /**
     * Get open Auctions
     * 
     * @return Response
     */
    public function openAuctions()
    {
        $auctions = Auction::with('image')->where('end_time', '>', Carbon::now())->get();
        
        return response()->json(['auctions' => $auctions]);
    }

    /**
     * Get close Auctions
     * 
     * @return Response
     */
    public function closedAuctions()
    {
        $auctions = Auction::with('image')->where('end_time', '<=', Carbon::now())->get();
        
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
            'location'  => 'required|min:5',
            'end_time' => 'required|date|after:today',
            'image' => 'required'
        ]);
        
        $auction = Auction::create([
            'car_name' => $request->car_name,
            'start_bid_amount' => $request->start_bid_amount,
            'final_price' => $request->start_bid_amount,
            'location' => $request->location,
            'end_time' => $request->end_time,
        ]);

        if($request->get('image'))
       {
          $image = $request->get('image');
          $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
          \Image::make($request->get('image'))->save(public_path('images/').$name);
        }

       $image= new ImageUploader();
       $image->name = $name;
       $image->auction_id = $auction->id;
       $image->save();

        return response()->json([
            "message" => "Auction created successflly!"
        ], 201);
    }

    /**
     * Update Auction
     * 
     * @param Request
     * @param id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // if it's a close auction request
        if($request->close) {
            Auction::where('id', $id)->update([
                'end_time' => Carbon::now(),
            ]); 
        } else {
            $request->validate([
                'end_time' => 'required|date|after:today'
            ]);
            
            Auction::where('id', $id)->update([
                'end_time' => $request->end_time,
            ]); 
        }        

        return response()->json([
            "message" => "Auction updated successflly!"
        ]);
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
        
        if($auction) {
            $auction->bids()->delete();
            $auction->delete();
        }

        return response()->json([
            "message" => "Ok"
        ]);
    }

    /**
     * Get all bids of Auction
     * 
     * @param id
     * @return Response
     */
    public function bids($id)
    {
        $auction = Auction::find($id);
        if($auction)
            return response()->json([
                "bids" => $auction->bids()->get()
            ]);
    }
}
