<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
       //get all the merchant User 
        $users = User::where('role', 0)->with('profile', 'profile.serviceCharge')
                    ->orderBy('created_at','desc')
                    ->get();

         return $this->showData($users);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isAdmin');

        $user = User::where('id',$id)->with('profile')->get();
        return $this->showData($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $updateUser = $request->validate([
            'name'                  => 'string|min:4|max:50',
            'address'               => 'string|min:5|max:100',
            'email'                 => 'string|email|unique:users',
            'phone_num'             => 'regex:/(01)[0-9]{9}/|max:16|unique:users',
            'company_name'          => 'string|min:10|max:100',
            'about'                 => 'string|min:10|max:300',
            'image'                 => 'string',
            ],[
                "phone_num.regex"    => "Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .",

            ]);

            $user = User::findOrFail($id);

            // update in User......................
            if($request->has('image')){
                $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                \Image::make($request->image)->save(public_path('profile_images/').$name);
                $request->merge(['image' => $name]);
                
                $user->profile->image   =  $request->root().'/profile_images/'.$name;
            }

            if ($request->has('name')){
                $user->name = $request->name;
            }

             if($request->has('address')){
                $user->address   = $request->address;
            }
             if($request->has('email')){
                $user->email   = $request->email;
            }
              if($request->has('phone_num')){
                $user->phone_num   = $request->phone_num;
            }

            //update in profile......
             if($request->has('about')){
                $user->profile->about  = $request->about;
            }
             
              if($request->has('company_name')){
                $user->profile->company_name  = $request->company_name;
            }


            // Change Password....
             if($request->has('oldPass') || $request->has('newPass')){
                $this->validate($request,[
                    'oldPass'            => 'required|string',
                    'newPass'            => 'required|string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                ],[
                    "newPass.regex"     =>" Your Password and Confirm-password must be 8 characters long and should contain at-least 1 Uppercase, 1 Lowercase, 1 Numeric and 1 special character.",
                ]);

                if( Hash::check($request->oldPass, $user->password)){

                    $user->password    = Hash::make($request->newPass);
                }
                else{

                   $oldPass = ["oldPass" => "Old Passwrod Does Not Match"];
                   return $this->errorResponse($oldPass, 401);
               }
            }
            
            $user->save();
            $user->profile->save();

            return $this->showMessage("Successfully Updated", "done");

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');
        
        $user = User::findOrFail($id);
        $user->delete();
        return $this->showMessage("Successfully Deleted", "done");
    }
}
