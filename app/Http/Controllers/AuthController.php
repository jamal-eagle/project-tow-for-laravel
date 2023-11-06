<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TravelController;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['getRandomSupervisor','getUserById','login','confirm_user','disable_user','getNotConfirmedUserList','register','addImage','add-travel','getMyTravels']]);
    }



    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email"              =>          "required|email",
            "password"             =>          "required",]);
        $credentials = request(['email', 'password']);
   if( $validator->fails())
   return response()->json(['error' =>  $validator->messages()], $this->validate_data_error_code    );

//return $validator;
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized user please try another email or password...'], $this->not_confirmed_code);
        }
        else
        if(auth::user()->type=='supervisor'){
            if(!Auth::user()->confirmed){
                return response()->json(['error' => 'Your account does not confirmed by admin please try later...'], 419);


            }
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            "name"             =>          "required",
            "email"              =>          "required|email|unique:users",
            "password"             =>          "required",
            "phone_number"              =>          "required|min:10",
            "type"             =>          "required",
            "image"            => "required"

        ]);

        if( $validator->fails())
        return response()->json(['error' =>  $validator->messages()], $this->validate_data_error_code    );

    $data=$request->all();
    $s=1;
    $imageName =time().$s.".".$data['image']->extension();
    $request->file('image')->move(public_path('images'),$imageName);
        User::create([
            'name'  =>  $data['name'],
            'phone_number' => $data['phone_number'],
            'email' =>  $data['email'],
            'confirmed' => false,
            'password' => bcrypt($data['password']),
             'type'=>$data['type'],
             'image'=>$imageName,
        ]);
         return response()->json('success',$this->success_code);
    }

// get not confirmed supervisor user list
public function getNotConfirmedUserList(){
   $data= User::where(['type'=>'supervisor'])->get();
    return  $data;
    if(Auth::check()){
        if(Auth::user()->type=='admin'){

        }
    }
}

// confirm supervisor account
public function confirm_user(Request $request)
{

    User::where( ['id'=> $request->id ])->update([
        'confirmed' => true,

    ]);
    return response()->json('user confirmed successfully',$this->success_code);

}
// confirm supervisor account
public function disable_user(Request $request)
{

    User::where( ['id'=> $request->id ])->update([
        'confirmed' => false,

    ]);
    return response()->json('user disabled successfully',$this->success_code);

}

// get not confirmed supervisor user list
public function getConfirmedUser(){
    $data= User::where(['confirmed'=>true,'type'=>'supervisor'])->get();
     return  $data;
     if(Auth::check()){
         if(Auth::user()->type=='admin'){

         }
     }
 }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60*12,
            'user' =>auth()->user()
        ]);
    }





    public function getUserById(Request $request){
        $data=User::where( ['id'=> $request->user_id ])->get();
        foreach($data as $user)
            $user->rate=(new TravelController())->getUserRate($request->user_id);

        return $data;
        }



        //get 3 random supervisor
public function getRandomSupervisor(){
    $data=User::where(['type'=>'supervisor','confirmed'=>1])->inRandomOrder()->take(3)->get();
$TC=new TravelController();
    foreach($data as $user)
        $user->supervisorRate=$TC->getUserRate($user->id);


    return $data;
}
    }
