<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

use DateTime;

class ChatController extends Controller
{

  // get un readed message count
  public function get_unreaded_message_count(Request $request)
  {
    $data = Message::where([['readed', '=', false], ['receiver_id', '=', $request->user_id]])->count();
    return $data;
  }



  //send new message
  public function addMessage(Request $request)
  {
    // $sender_id=Auth::user()->id;
    $sender_id = $request->user_id;
    // check if user login ok
    //    if (Auth::check()) {
    // check if user is admin
    //  $data->companies = $data;
    // $data->unreaded_message=$this->get_unreaded_message_count();

    $data = Message::create([
      'sender_id' => $sender_id,
      'receiver_id' => $request->receiver_id,
      'date' => new DateTime(),
      'text' => $request->text,
    ]);

    return response()->json(["status" => $this->success_code, "success" => true, "message" => "message_sended"]);



  }



  //get all conversation
  public function get_my_conversation(Request $request)
  {
    //  return response()->json(["status" => $this->success_code, "success" => true, "message" => "message_sended"]);

    //return 1;


    $sender=Message::where([

        ['receiver_id', '=', $request->user_id]
      ])
->with('sender')

      ->distinct()->orderBy('id', 'DESC')->get();


    $receiver=Message::select(['receiver_id'])->where([

        ['sender_id', '=', $request->user_id]
      ])->with('receiver')
      ->distinct()->orderBy('id', 'DESC')->get();



$x=[];
$y=[];
  foreach ($sender as $s){
array_push($x,$s->sender);
    //$x+=[($s->sender_id)];
  }


  foreach ($receiver as $s){
    array_push($y,$s->receiver);
        //$x+=[($s->sender_id)];
      }

      $data=  array_merge($x,$y);

  $unique=  array_unique(array_merge($x,$y));
$data=[];
  foreach($unique as $z){
    array_push($data,$z);
  }

//$data[0]->sender=1;
 // return $data;
    foreach ($data as $user) {
    //  $x++;
  // return $user;
         $user->unreaded_message = Message::where([['readed', '=', false], ['sender_id', '=', $user->id], ['receiver_id', '=', $request->user_id]])->count();
      $user->latest_message = Message::where([['sender_id', '=', $user->id], ['receiver_id', '=', $request->user_id]])->orWhere([['sender_id', '=', $request->user_id], ['receiver_id', '=', $user->id]])->select(['text', 'date'])->orderBy('id', 'DESC')->first();

    }

    //$data->sender=[];
    //$data->sender->unreaded_message=0;



    //  $data->companies = $this->companies();
   // $data->unreaded_message = $this->get_unreaded_message_count($request);



    return $data;

  }





  public function get_message(Request $request)
  { //

    $data = (Message::where(['receiver_id' => $request->sender_id . '', 'sender_id' => $request->user_id . ''])->
      orWhere([['sender_id', '=', $request->sender_id . ''], ['receiver_id', '=', $request->user_id . '']]))
      ->with('sender')->orderBy('id', 'DESC')->get();
    //$data->companies = $this->companies();
    // $data->unreaded_message=$this->get_unreaded_message_count($request);

    $data->receiver_id = $request->sender_id;
    //set all message readed

    Message::where(['receiver_id' => $request->user_id . '' . '', 'sender_id' => $request->sender_id])->update(['readed' => true,]);

    //$data=[['sender_id','=',$request->sender_id.''] ,['receiver_id','=',Auth::user()->id.'']];
    return $data;
    //  return view('mobile.admin.chat_body', compact('data'));


  }

}
