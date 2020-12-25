<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class AuthenticationController extends ApiController
{
   public function register(Request $request)
   {
        $registerData = $request->validate([
            'name'                  => 'required|string',
            'address'               => 'required|string',
            'email'                 => 'required|string|email|unique:users',
            'phone_num'             => 'required|regex:/(01)[0-9]{9}/|max:16|unique:users',
            'accept_terms'          =>  ['required',Rule::in([true])],
            'password'              => 'required|string|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/', //confirmed
            ],[
                "phone_num.regex"    =>" Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .",
                "password.regex"    =>" Your Password and Confirm-password must be 8 characters long and should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.",
            ]);



            $registerData['password'] = Hash::make($request->password);
            $user = User::create($registerData);
            $accessToken = $user->createToken('~Token@'.$request->email .now()->timestamp .'~')->accessToken;

             //create profile for every perticular user after registration................
             Profile::create([
                'user_id'                => $user->id,
                'service_charge_id'      => 1
            ]);

            Auth::loginUsingId($user->id, true);

            $authUser = User::where('id', $user->id)->with('profile')->get();

            return $this->showData($authUser, compact("accessToken"));
   }



   public function login(Request $request){
        $request->validate([
                'email'    => 'required|string|email|max:100',
                'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            $incorrect = ["incorrect" => "The Provided Credentials are Incorrent"];
            return $this->errorResponse($incorrect, 401);
        }

        $accessToken = $user->createToken('~Token@'.$request->email .now()->timestamp .'~')->accessToken;
        Auth::loginUsingId($user->id, true);

        $authUser = User::where('id', $user->id)->with('profile')->get();

    return $this->showData($authUser, compact("accessToken"));

  }

    // logout Auth User functionality
    public function logout(Request $request){
             $authToken = Auth()->user()->token();
             $authToken->revoke();
             if ($authToken->revoke()) {
                 return $this->showMessage("successfully LogOut", "done", 200);
            }

    }

    public function authUserDetails(){
        $authId = auth()->user()->id;
        $authUser = User::where('id', $authId)->with('profile')->get();
        return $this->showData($authUser);
    }


}
