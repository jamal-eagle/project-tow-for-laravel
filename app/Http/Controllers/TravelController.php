<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;

use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Carbon\Carbon;
use App\Models\Image;
use App\Models\Booking;
use App\Models\Rating;
use App\Http\Controllers\BookingController;

class TravelController extends Controller
{
    //['manager_id', 'start_date', 'end_date', 'discription', 'participaints_num', 'price']



    public function getCities()
    {
        $data = Travel::select('address')->distinct()->get();
        foreach ($data as $address) {
            $city = $address->address;
            $address->travelCount = count(Travel::where(['address' => $city])->get());
            $address->travel = Travel::where(['address' => $city])->with([
                "locations" => function ($query) {
                    $query->with('images')->first();
                }
            ])->orderBy('id', 'desc')->first();
        }
        return $data;
    }

    public function getLatestFinishedTravel()
    {
        $now = Carbon::now()->format('Y-m-d');
        $data = Travel::whereDate('end_date', '<=', $now)->with([
            "locations" => function ($query) {
                $query->with('images');
            }
        ])->take(3)->get();
        return $data;
    }

    //get travel by id
    public function getTravel(Request $request)
    {

        $data = Travel::where(['id' => $request->travel_id])->with([
            "locations" => function ($query) {
                $query->with('images');
            }
        ])->with('supervisor')->get();
        $BC = new BookingController();
        if (Auth::user()->type != "user") {
            $data[0]->booking_list = (new BookingController())->get_book_request_for_travel($request);
        }

        $data[0]->isBooked = (new BookingController())->isBooked($request->travel_id);

        $data[0]->reminingParticipation = $data[0]->participaints_num - count($BC->get_book_request_for_travel($request));

        return $data;
    }




    //get travels by city
    public function getTravelsByCity(Request $request)
    {
        $data = Travel::where(['address' => $request->city])->with([
            "locations" => function ($query) {
                $query->with('images');
            }
        ])->with('supervisor')->get();
        $BC = new BookingController();
        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Search end", 'travel_count' => count($data), 'data' => $data]);

    }



    //get 1 random travel
    public function getRandomTravel()
    {
        $now = Carbon::now()->format('Y-m-d');
        $data = Travel::inRandomOrder()->with([
            "locations" => function ($query) {
                $query->inRandomOrder()->with('images');
            }
        ])->take(1)->get();
        return $data;
    }



    //get available travels
    public function getAvailableTravel(Request $request)
    {
        $now = Carbon::now()->format('Y-m-d');

        //  return $now;
        $data = Travel::whereDate('start_date', '>', $now)->with([
            "locations" => function ($query) {
                $query->with('images');
            }
        ])->get();
        $BC = new BookingController();
        foreach ($data as $travel) {
            $travel->isBooked = $BC->isBooked($travel->id);
        }
        return $data;
    }


    //validate and register a new users
    public function addTravel(Request $request)
    { //validate user input
        $validator = Validator::make($request->all(), [
            "manager_id" => "required",
            "start_date" => "required|",
            "end_date" => "required|",
            "discription" => "required",
            "participaints_num" => "required",
            "price" => "required",
            "address" => "required",


        ]);

        if ($validator->fails()) {
            return response()->json(["status" => $request->start_date, "errors" => $validator->errors()]);


            //response()->json(["status" => $this->error_code, "message" => "please fill all column"]);
        }
        $travelDataArray = array(

            "manager_id" => $request->manager_id,
            "start_date" => Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->start_date)->format('m/d/Y')),
            "end_date" => Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->end_date)->format('m/d/Y')),
            "discription" => $request->discription,
            "participaints_num" => $request->participaints_num,
            "price" => $request->price,
            "address" => $request->address,

        );
        $travel = Travel::create($travelDataArray);
        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Travel Added successfully", "travel_id" => $travel->id]);
    }



    //edit travel information by travel id
    public function editTravelData(Request $request)
    {
        Travel::where('id', '=', $request->travel_id)->update([
            'start_date' => Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->start_date)->format('m/d/Y')),
            'end_date' => Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->end_date)->format('m/d/Y')),
            'discription' => $request->discription,
            'participaints_num' => $request->participaints_num,
            'price' => $request->price,
            'address' => $request->address

        ]);

        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Travel edited successfully"]);
    }


    //search for travel by word in discription
    public function searchByText(Request $request)
    {

        //             $data = Travel::Where([['discription', 'LIKE', '%' . $request->name . '%']])->orWhere([['name', 'LIKE', '%' . $request->name . '%']])->with('images')->with('rating')->get();

        $data = Travel::Where([['discription', 'LIKE', '%' . $request->searchText . '%']])->get();

        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Search end", 'data' => $data]);
    }

    public function searchByDate(Request $request)
    {
        //convert string to y m d format 2023/1/1
        $data = Travel::Where([['start_date', '>', (Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y/m/d')))]])->get();
        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Search end", 'data' => $data]);
    }

    //get travel by manager id
    public function getMyTravels(Request $request)
    {
        $data = Travel::Where([['manager_id', $request->manager_id]])->with([
            "locations" => function ($query) {
                $query->with('images');
            }
        ])->with('supervisor')->orderBy('end_date', 'desc')->get();
        $BC = new BookingController();
        foreach ($data as $travel) {
            $travel->rate = $this->getTravelRate($travel->id);
            $request->travel_id = $travel->id;
            $travel->isBooked = $BC->isBooked($travel->id);
            $travel->supervisorRate = $this->getUserRate($request->manager_id);
            $travel->reminingParticipation = $travel->participaints_num - count($BC->get_book_request_for_travel($request));

        }

        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Search end", 'data' => $data]);

    }

    //get all travel
    public function getlAllTravels()
    {
        $request = new Request();
        $data = Travel::with([
            "locations" => function ($query) {
                $query->with('images');
            }
        ])->with('supervisor')->get();
        $BC = new BookingController();
        foreach ($data as $travel) {
            $request->travel_id = $travel->id;
            $travel->rate = $this->getTravelRate($travel->id);
            $travel->isBooked = $BC->isBooked($travel->id);
            $travel->reminingParticipation = $travel->participaints_num - count($BC->get_book_request_for_travel($request));
        }
        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Search end", 'data' => $data]);

    }

    //add new location
    public function addLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "global_info" => "required",
            "travel_id" => "required",

        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->messages()], $this->validate_data_error_code);

        $data = Location::create([
            'name' => $request->name,
            'global_info' => $request->global_info,
            'travel_id' => $request->travel_id,

        ]);
        $s = 0;
        $str = "";
        //foreach(){
        if ($request->has('image')) {
            foreach ($request->file('image') as $imgFile) {
                $name = time() . $s . "." . $imgFile->extension();
                $imgFile->move(public_path('images'), $name);
                $s += 1;

                $image = new Image;
                $image->url = $name;
                $image->location_id = $data->id;
                $image->save();
            }

        }
        return response()->json(['data' => "location added successfully"]);
    }


    public function getTravelRate($travel_id)
    {
        return intval(Rating::where('travel_id', $travel_id)->avg('rating'));
    }

    //rate travel
    public function travel_rate(Request $request)
    {
        $record = Rating::where(['travel_id' => $request->travel_id, 'user_id' => $request->user_id]);
        // if there is old rate for travel for same user delete it
        if ($record->exists()) {
            $record->delete();
        }
        //add new rating
        $rate = new Rating();
        $rate->rating = $request->rating;
        $rate->travel_id = $request->travel_id;
        $rate->user_id = $request->user_id;
        $rate->save();

        return $this->getTravelRate($request->travel_id);
    }



    public function getUserRate($user_id)
    {
        $count = 0;
        $rate = 0;
        $data = Travel::Where([['manager_id', $user_id]])->get();
        foreach ($data as $travel) {
            $rate += $this->getTravelRate($travel->id);
            $count += 1;
        }
        if ($count > 0)
            return $rate / $count;
        else
            return 0;
        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Search end", 'data' => $rate / $count]);

    }


    //get location by id
    public function getLocation(Request $request)
    {
        $data = Location::where(['id' => $request->location_id])->get();
        return $data;
    }

    //edit location information by travel id
    public function editLocation(Request $request)
    {
        Location::where('id', '=', $request->location_id)->update([
            'name' => $request->name,
            'global_info' => $request->global_info

        ]);



        //foreach(){
        if ($request->has('image')) {
            $record = Image::where(['location_id' => $request->location_id]);
            // if there is old image for location delete it
            if ($record->exists()) {
                $record->delete();
            }
            $s = 0;

            foreach ($request->file('image') as $imgFile) {
                $name = time() . $s . "." . $imgFile->extension();
                $imgFile->move(public_path('images'), $name);
                $s += 1;

                $image = new Image;
                $image->url = $name;
                $image->location_id = $request->location_id;
                $image->save();
            }

        }



        return response()->json(["status" => $this->success_code, "success" => true, "message" => "Location edited successfully"]);
    }
      //delete travel with location and rating by travel id
      public function deleteTravel(Request $request)
      {
          Travel::where('id', '=', $request->travel_id)->delete();
          Location::where('travel_id', '=', $request->travel_id)->delete();
          Rating::where('travel_id', '=', $request->travel_id)->delete();
          Booking::where('travel_id','=',$request->travel_id)->delete();
  
  
          return response()->json(["status" => $this->success_code, "success" => true, "message" => "Location edited successfully"]);
      }
  
  
}
