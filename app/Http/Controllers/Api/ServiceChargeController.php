<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiController;

use App\Models\ServiceChagre;
use App\Models\Profile;


use Illuminate\Http\Request;

class ServiceChargeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
         $serviceCharges = ServiceChagre::latest()->get();
 
          return $this->showData($serviceCharges);
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

        $createServiceCharge = $request->validate([

            'charge_amount'         => 'required|integer|min:2|max:2000',
            'discount'              => 'nullable|integer|min:1|max:2000',
            ]);

             ServiceChagre::create($createServiceCharge);

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

        $updateServiceCharge = $request->validate([
            'charge_amount'         => 'integer|min:2|max:2000',
            'discount'              => 'nullable|integer|min:1|max:2000',
            ]);

            $serviceCharge = ServiceChagre::findOrFail($id);

            if($request->has('charge_amount')){
                $serviceCharge->charge_amount    = $request->charge_amount;
            }
           if($request->has('discount')){
                $serviceCharge->discount          = $request->discount;
            }

             $serviceCharge->save();

             return $this->showMessage("Successfully Updated", "done");
        
    }


  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // update Merchant User Service Charge 
    public function updateUserServiceCharge(Request $request)
    {     
         $this->authorize('isAdmin');

            $request->validate([
            'service_charge_id'   => 'integer|min:1',
            ]);

            $profile = Profile::findOrFail($request->id);

            $profile->service_charge_id    = $request->service_charge_id;

             $profile->save();

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
     $serviceChargeIdIncludeProfile = Profile::where('service_charge_id',$id)->get();


    //   useing for loop get each merchant serviceCharge Id Include Profile and  set serviceCharge Id null and save before delete Service charge 
       for ($i=0; $i < count($serviceChargeIdIncludeProfile) ; $i++) { 

            $serviceChargeIdIncludePerProfileId = Profile::findOrFail($serviceChargeIdIncludeProfile[$i]['id']);

            $serviceChargeIdIncludePerProfileId->service_charge_id = null;
            
            $serviceChargeIdIncludePerProfileId->save();

       };

    //    after update service ChargeIdInclude Profile Delete Service charge  
       $serviceChargeId = ServiceChagre::findOrFail($id);
          $serviceChargeId->delete();
 
         return $this->showMessage("Successfully Deleted", "done");
    }
}
