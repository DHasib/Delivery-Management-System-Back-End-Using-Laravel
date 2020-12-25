<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;     
use App\Models\CarDetails;     
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends ApiController
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
         $users = User::where('role', 3)    
                       ->orWhere('role', 2)
                       ->with('profile', 'profile.user','profile.assignCar','profile.employeeSalary','profile.zone')
                       ->orderBy('created_at','desc')
                       ->get();
 
          return $this->showData($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('isAdmin');

        $createEmployee = $request->validate([

            'name'                     => 'required|string|min:4|max:50',
            'address'                  => 'required|string|min:5|max:100',
            'email'                    => 'required|string|email|unique:users',
            'phone_num'                => 'required|regex:/(01)[0-9]{9}/|max:16|unique:users',

            'national_id_photo'        => 'string',
            'driving_license_photo'    => 'string',
            'salary_id'                => 'required|integer',
            'zone_id'                  => 'integer',

            'about'                    => 'required|string|min:10|max:300',
            'image'                    => 'required|string',

            'role'                     =>  ['required',Rule::in([2,3])],

            ],[    
                "phone_num.regex"       =>" Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .",
              
            ]);


             // convert binart data into an image 
            $pImageName = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('profile_images/').$pImageName);
            $request->merge(['image' => $pImageName]);


            $employee =  User::create([
                'name'              => $request->name,
                'address'           => $request->address,
                'email'             => $request->email,
                'phone_num'         => $request->phone_num,
                'accept_terms'      => true,
                'role'              => $request->role,
                'password'          =>  Hash::make(123456),
            ]);

            $employeeProfile =  Profile::create([
                'user_id'                 => $employee->id,
                'salary_id'               => $request->salary_id,
                'zone_id'                 => $request->zone_id,
                'about'                   => $request->about,
                'image'                   => $request->root().'/profile_images/'.$pImageName,
            ]);
 
            if ($request->has('assign_car_id')) {
                $employeeProfile->assign_car_id      = $request->assign_car_id;
                $employeeProfile->assignCar->status  = "busy";
            }

            if ($request->has('national_id_photo')) {

                 //  // convert binart data into an image 
                    $nIdImageName = time().'.' . explode('/', explode(':', substr($request->national_id_photo, 0, strpos($request->national_id_photo, ';')))[1])[1];
                    \Image::make($request->national_id_photo)->save(public_path('employeeData/nationalId/').$nIdImageName);
                    $request->merge(['national_id_photo' => $nIdImageName]);

                $employeeProfile->national_id_photo      =  $request->root().'/employeeData/nationalId/'.$nIdImageName;
            }

            if ($request->has('driving_license_photo')) {

                    // convert binart data into an image 
                    $dLicenseImageName = time().'.' . explode('/', explode(':', substr($request->driving_license_photo, 0, strpos($request->driving_license_photo, ';')))[1])[1];
                    \Image::make($request->driving_license_photo)->save(public_path('employeeData/drivingLicense/').$dLicenseImageName);
                    $request->merge(['driving_license_photo' => $dLicenseImageName]);

                $employeeProfile->driving_license_photo      = $request->root().'/employeeData/drivingLicense/'.$dLicenseImageName;
            }

          

            $employeeProfile->save();
            $employeeProfile->assignCar->save();
            return $this->showMessage("Successfully Created", "done");
    
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
        $this->authorize('isAdmin');
        $createEmployee = $request->validate([
            'name'                     => 'string|min:4|max:50',
            'address'                  => 'string|min:5|max:100',
            'email'                    => 'string|email|unique:users',
            'phone_num'                => 'regex:/(01)[0-9]{9}/|max:16|unique:users',

            'national_id_photo'        => 'string',
            'driving_license_photo'    => 'string',
            'salary_id'                => 'integer',
            'zone_id'                  => 'integer',

            'about'                     => 'string|min:10|max:300',
            'image'                     => 'string',
            'role'                      =>  ['integer',Rule::in([2,3])],

          
        ],[    
            "phone_num.regex"       =>" Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .",
          
        ]);

            
            $employee = User::findOrFail($id); 

            if ($request->has('name')) {
                $employee->name  = $request->name; 
            }

            if ($request->has('address')) {
                $employee->address  = $request->address; 
            }

            if ($request->has('email')) {
                $employee->email  = $request->email; 
            }

            if ($request->has('phone_num')) {
                $employee->phone_num  = $request->phone_num; 
            }

            if ($request->has('role')) {
           
                if ($request->role === 2) {
                    $employee->profile->assignCar->status  = "free";
                    $employee->profile->assign_car_id = null;
                    $employee->profile->assignCar->save();
                }

                $employee->role  = $request->role; 
            }

            if ($request->has('salary_id')) {
                $employee->profile->salary_id  = $request->salary_id; 
            }

            if ($request->has('zone_id')) {
                $employee->profile->zone_id  = $request->zone_id; 
            }

            if ($request->has('about')) {
                $employee->profile->about  = $request->about;  
            }

            if ($request->has('image')) {

                // convert binart data into an image 
                $pImageName = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
                \Image::make($request->image)->save(public_path('profile_images/').$pImageName);
                $request->merge(['image' => $pImageName]);

                $employee->profile->image  = $request->root().'/profile_images/'.$pImageName;  
            }

            if ($request->has('assign_car_id')) {

                if ($employee->profile->assignCar) {
                    $employee->profile->assignCar->status  = "free";
                    $employee->profile->assignCar->save();
                }


                $employee->profile->assign_car_id      = $request->assign_car_id;
                $employee->profile->save();

                $assignedNewCar = CarDetails::findOrFail($request->assign_car_id); 
                $assignedNewCar->status  = "busy";
                $assignedNewCar->save();
            }

            if ($request->has('national_id_photo')) {

                //  // convert binart data into an image 
                   $nIdImageName = time().'.' . explode('/', explode(':', substr($request->national_id_photo, 0, strpos($request->national_id_photo, ';')))[1])[1];
                   \Image::make($request->national_id_photo)->save(public_path('employeeData/nationalId/').$nIdImageName);
                   $request->merge(['national_id_photo' => $nIdImageName]);

               $employee->profile->national_id_photo      =  $request->root().'/employeeData/nationalId/'.$nIdImageName;
           }

           if ($request->has('driving_license_photo')) {

                   // convert binart data into an image 
                   $dLicenseImageName = time().'.' . explode('/', explode(':', substr($request->driving_license_photo, 0, strpos($request->driving_license_photo, ';')))[1])[1];
                   \Image::make($request->driving_license_photo)->save(public_path('employeeData/drivingLicense/').$dLicenseImageName);
                   $request->merge(['driving_license_photo' => $dLicenseImageName]);

               $employee->profile->driving_license_photo      = $request->root().'/employeeData/drivingLicense/'.$dLicenseImageName;
           }


            $employee->save();
            $employee->profile->save();
            

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
        
        $employee = User::findOrFail($id);
        $employee->delete();
        return $this->showMessage("Successfully Deleted", "done");
    }
}
