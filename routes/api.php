<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('add-image',  [AuthController::class,'addImage']);

    Route::post('login',  [AuthController::class,'login']);
    Route::post('register',  [AuthController::class,'register']);
    Route::post('get_not_confirmed_supervisor',  [AuthController::class,'getNotConfirmedUserList']);
    Route::post('confirm_user',  [AuthController::class,'confirm_user']);
    Route::post('disable_user',  [AuthController::class,'disable_user']);

    Route::post('getRandomSupervisor',  [AuthController::class,'getRandomSupervisor']);

    Route::post('getUserById',  [AuthController::class,'getUserById']);




    Route::group([ 'middleware' => 'api'], function () {
        Route::post('logout', [AuthController::class,'logout']);
        Route::post('refresh',  [AuthController::class,'refresh']);
        Route::post('me', [AuthController::class,'me']);
    });

    Route::post('/edit-travel', [TravelController::class, 'editTravelData']);
    Route::post('/search-by-text', [TravelController::class, 'searchByText']);
    Route::post('/search-by-date', [TravelController::class, 'searchByDate']);
    Route::post('/getTravelsByCity', [TravelController::class, 'getTravelsByCity']);

    Route::post('/getLocation', [TravelController::class, 'getLocation']);
    Route::post('/editLocation', [TravelController::class, 'editLocation']);
    Route::post('/deleteTravel', [TravelController::class, 'deleteTravel']);





    Route::post('/add-travel', [TravelController::class, 'addTravel']);
    Route::post('/add-location', [TravelController::class, 'addLocation']);
    Route::post('/getlAllTravels', [TravelController::class, 'getlAllTravels']);

    Route::post('/get-travel-by-manager', [TravelController::class, 'getMyTravels']);
    Route::post('/get-travel-by_id', [TravelController::class, 'getTravel']);
    Route::post('/getAvailableTravel', [TravelController::class, 'getAvailableTravel']);
    Route::post('/getCities', [TravelController::class, 'getCities']);
    Route::post('/getRandomTravel', [TravelController::class, 'getRandomTravel']);


    Route::post('/rateTravel', [TravelController::class, 'travel_rate']);
    Route::post('/getLatestFinishedTravel', [TravelController::class, 'getLatestFinishedTravel']);



    Route::post('/book_travel', [BookingController::class, 'book_travel']);
    Route::post('/get_my_bookings_list', [BookingController::class, 'get_my_bookings_list']);
    Route::post('/cancel_travel_booking', [BookingController::class, 'cancel_travel_booking']);
    Route::post('/confirm_booking', [BookingController::class, 'confirm_booking_by_admin']);
    Route::post('/not_confirmed_request', [BookingController::class, 'get_not_confirmed_book_request']);
    Route::post('/get_book_request_for_travel', [BookingController::class, 'get_book_request_for_travel']);

    Route::post('/sendMessage', [ChatController::class, 'addMessage']);
    Route::post('/MyConversations', [ChatController::class, 'get_my_conversation']);
    Route::post('/MyMessage', [ChatController::class, 'get_message']);
    Route::post('/UnReadedMessageCount', [ChatController::class, 'get_unreaded_message_count']);

    //get_unreaded_message_count






