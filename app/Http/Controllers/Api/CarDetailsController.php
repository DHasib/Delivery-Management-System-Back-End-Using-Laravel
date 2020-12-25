<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\CarDetails;
use App\Models\CarServices;
use App\Models\Profile;


class CarDetailsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->authorize('isAdmin');

        $carDetails = CarDetails::with('profile.user','carServices')
                                    ->orderBy('created_at','desc')
                                    ->get();

         return $this->showData($carDetails);
    }


    // get the available car 
    public function availableCar()
    {

        $this->authorize('isAdmin');

        $availableCar = CarDetails::where('status', 'free')
                                    ->get(['id', 'reg_num']);

         return $this->showData($availableCar);
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

        $createCarDetails = $request->validate([
            'reg_num'              => 'required|string|max:200',
            'chassis_num'          => 'required|string',
            'model'                => 'required|string|max:200',
            'insurance_image'      => 'required|string',
            'memo_image'           => 'required|string',
        ]);


           //convert into insurance_image 
            $name = time().'.' . explode('/', explode(':', substr($request->insurance_image, 0, strpos($request->insurance_image, ';')))[1])[1];
            \Image::make($request->insurance_image)->save(public_path('car_releted_image/insurance_image/').$name);
            $request->merge(['insurance_image' => $name]);
            $insurance_image  =  $request->root().'/car_releted_image/insurance_image/'.$name;

          //convert into memo_image 
            $name = time().'.' . explode('/', explode(':', substr($request->memo_image, 0, strpos($request->memo_image, ';')))[1])[1];
            \Image::make($request->memo_image)->save(public_path('car_releted_image/memo_image/').$name);
            $request->merge(['memo_image' => $name]);
            $memo_image  =  $request->root().'/car_releted_image/memo_image/'.$name;
        

            $createCarDetails['insurance_image']     = $insurance_image;
            $createCarDetails['memo_image']          = $memo_image;


         CarDetails::create($createCarDetails);

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

          $request->validate([
            'chassis_num'          => 'string|max:200',
            'reg_num'              => 'string|max:200',
            'model'                => 'string|max:200',
            'insurance_image'      => 'string',
            'memo_image'           => 'string',
            ]);

            $updateCarDetails = CarDetails::findOrFail($id);

            if($request->has('chassis_num')){
                $updateCarDetails->chassis_num   = $request->chassis_num;
            }
           if($request->has('reg_num')){
                $updateCarDetails->reg_num  = $request->reg_num;
            }
           if($request->has('model')){
                $updateCarDetails->model  = $request->model;
            }
           if($request->has('insurance_image')){
               
                $name = time().'.' . explode('/', explode(':', substr($request->insurance_image, 0, strpos($request->insurance_image, ';')))[1])[1];
                \Image::make($request->insurance_image)->save(public_path('car_releted_image/insurance_image/').$name);
                $request->merge(['insurance_image' => $name]);

                $insurance_image  =  $request->root().'/car_releted_image/insurance_image/'.$name;

                $updateCarDetails->insurance_image  =  $insurance_image;
            }
           if($request->has('memo_image')){
               //convert into memo_image 
                $name = time().'.' . explode('/', explode(':', substr($request->memo_image, 0, strpos($request->memo_image, ';')))[1])[1];
                \Image::make($request->memo_image)->save(public_path('car_releted_image/memo_image/').$name);
                $request->merge(['memo_image' => $name]);

                $memo_image  =  $request->root().'/car_releted_image/memo_image/'.$name;

                $updateCarDetails->memo_image  =  $memo_image;
            }
          
             $updateCarDetails->save();

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


         // find all service charge releted Merchabnt Profile 
     $careIncludeCarServices = CarServices::where('car_id',$id)->get();


     // useing for loop get each merchant serviceCharge Id Include Profile and  set serviceCharge Id null and save before delete Service charge 
        for ($i=0; $i < count($careIncludeCarServices) ; $i++) { 
 
             $careIncludePerCarService = CarServices::findOrFail($careIncludeCarServices[$i]['id']);
 
             $careIncludePerCarService->car_id = null;

             $careIncludePerCarService->save();
 
        };
         // find all service charge releted Merchabnt Profile 
     $careIncludeProfile = Profile::where('assign_car_id',$id)->get();

     // useing for loop get each merchant serviceCharge Id Include Profile and  set serviceCharge Id null and save before delete Service charge 
        for ($i=0; $i < count($careIncludeProfile) ; $i++) { 
 
             $careIncludePerProfile = Profile::findOrFail($careIncludeProfile[$i]['id']);
 
             $careIncludePerProfile->assign_car_id = null;

             $careIncludePerProfile->save();
 
        };
 

        $DeleteCarDetails = CarDetails::findOrFail($id);
      
        $DeleteCarDetails->delete();
        return $this->showMessage("Successfully Deleted", "done");

    }
}
