<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use App\Models\CarServices;

class CarServicesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $this->authorize('isAdmin');

        $carServices = CarServices::with('carDetails','carDetails.profile.user') 
                                 ->orderBy('created_at','desc')
                                 ->get();

         return $this->showData($carServices);
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

        $createCarService = $request->validate([
            'car_id'                       => 'required|integer|min:1',
            'service_charge'               => 'required|integer|min:1|max:100000',
            'repair_hardware_name'         => 'nullable|string|min:3|max:1000',
            'repair_hardware_price'        => 'nullable|integer|min:2|max:100000',
            'garage_name'                  => 'required|string|min:5|max:1000',
            'garage_address'               => 'required|string|min:5|max:1000',
            'garage_phone_num'             => 'required|regex:/(01)[0-9]{9}/|max:16|unique:car_services',
            ],[
                "garage_phone_num.regex"    =>" Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .", 
            ]);
            echo $request->car_id;

            $checkUser = CarServices::create($createCarService);
        

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

        $updateCarServices = $request->validate([
            'car_id'                       => 'integer|min:1',
            'service_charge'               => 'integer|min:1|max:100000',
            'repair_hardware_name'         => 'nullable|string|min:3|max:1000',
            'repair_hardware_price'        => 'nullable|integer|min:2|max:100000',
            'garage_name'                  => 'string|min:5|max:1000',
            'garage_address'               => 'string|min:5|max:1000',
            'garage_phone_num'             => 'regex:/(01)[0-9]{9}/|max:16|unique:users',
            ],[
                "garage_phone_num.regex"    =>" Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .", 
            ]);

            $CarService = CarServices::findOrFail($id);

    
           if($request->has('car_id')){
            $CarService->car_id   = $request->car_id;
            }
           if($request->has('service_charge')){
            $CarService->service_charge  = $request->service_charge;
            }
           if($request->has('repair_hardware_name')){
                $CarService->repair_hardware_name  = $request->repair_hardware_name;
            }
           if($request->has('repair_hardware_price')){
                $CarService->repair_hardware_price  = $request->repair_hardware_price;
            }
            
           if($request->has('garage_name')){
                $CarService->garage_name  = $request->garage_name;
            }
           if($request->has('garage_address')){
                $CarService->garage_address  = $request->garage_address;
            }
           if($request->has('garage_phone_num')){
                $CarService->garage_phone_num  = $request->garage_phone_num;
            }

             $CarService->save();

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
        
        $carServiceDelete = CarServices::findOrFail($id);
        $carServiceDelete->delete();
        return $this->showMessage("Successfully Deleted", "done");
    }
}
