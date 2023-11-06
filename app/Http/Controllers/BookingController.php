<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\User;
use App\Models\Travel;

class BookingController extends Controller
{
    //


// book a travel
    public function book_travel(Request $request){

        $booked_travel = Booking::where(['travel_id'=>$request->travel_id,'user_id'=>Auth::user()->id]);
        $booked_travel->delete();

        $bookingArray  = array(
            'travel_id'    =>    $request->travel_id,
            'user_id'   => Auth::user()->id, );
        $booking  =  Booking::create($bookingArray);
        return Auth::user()->name;
        return $booking;}


//get booked travels
        public function get_my_bookings_list(Request $request){
            $data = Booking::where(['user_id'=>$request->user_id])->with('travel')->get();

            return $data;
        }

// candel booked a travel
        public function cancel_travel_booking(Request $request){

            $booked_travel = Booking::where(['id'=>$request->book_id]);
            $booked_travel->delete();        }


            //check if travel is booked
            public function isBooked( $travel_id){
                $booked_travel = Booking::where(['user_id' => Auth::user()->id, 'state' => 'waiting', 'travel_id' => $travel_id]);
                if ($booked_travel->exists())
                    return $booked_travel->get()[0]->id;
                else
                    return false;
            }




              //confirm holded travel by supervisor
    public function confirm_booking_by_admin(Request $request)
    {

        Booking::where('id', '=',$request->book_id)->update([
            'state' => 'confirmed',
        ]);
    }

 

    public function get_not_confirmed_book_request(){

        $now= Carbon::now()->format('Y-m-d');
        $data=Travel::where(['manager_id'=>1])->whereDate('start_date','>',$now)->with(["bookings"=>function($query){
        $query->where(['state'=>'waiting']);}])->get();

      return $data;
    }
//get all booked request (confirmed and not confirmed for a travel)
    public function get_book_request_for_travel( Request $request){
        $data=Booking::where(['travel_id'=>$request->travel_id])->with('user')->orderBy('booking_date','desc')->get();
         return $data;

    }

}
