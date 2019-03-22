<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth Routes
Route::post('/register', 'AuthController@store');
Route::post('/login', 'AuthController@login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@user');

    // Auction Routes
    Route::prefix('auctions')->middleware('role:admin')->group(function() {
        Route::get('all', 'AuctionController@allAuctions');
        Route::get('open', 'AuctionController@openAuctions');
        Route::get('closed', 'AuctionController@closedAuctions');
        Route::post('create', 'AuctionController@store');
        Route::put('edit/{id}', 'AuctionController@update');
        Route::delete('delete', 'AuctionController@delete');        
    });
});