<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use App\Models\Zones;
use App\Models\Profile;

class ZoneController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
        $zones = Zones::latest()->get();

         return $this->showData($zones);
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

        $createZone = $request->validate([
            'name'               => 'required|string|min:5|max:200',
            'address'            => 'required|string|min:5|max:200',
            'phone_num'          => 'required|regex:/(01)[0-9]{9}/|max:16|unique:users',
            ],[
                "phone_num.regex"    =>" Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .",
            ]);

            Zones::create($createZone);

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

        $updateZone = $request->validate([
            'name'               => 'string|min:5|max:200',
            'address'            => 'string|min:5|max:200',
            'phone_num'          => 'regex:/(01)[0-9]{9}/|max:16|unique:users',
            ],[
                "phone_num.regex"    =>" Your Phone Number Must Be  11 Digit and start with (o1) and max Phone Number of Digit 16 .",
            ]);


            $updateZone = Zones::findOrFail($id);

            if($request->has('name')){
                $updateZone->name   = $request->name;
            }

           if($request->has('address')){
                $updateZone->address  = $request->address;
            }

           if($request->has('phone_num')){
                $updateZone->phone_num  = $request->phone_num;
            }

             $updateZone->save();

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


        // find all Zone  Assigned Driver Profile 
    $zoneIdncludeProfile = Profile::where('zone_id',$id)->get();


       for ($i=0; $i < count($zoneIdncludeProfile) ; $i++) { 

            $zoneIncludePerProfile = CarServices::findOrFail($zoneIdncludeProfile[$i]['id']);

            $zoneIncludePerProfile->zone_id = null;
            
            $zoneIncludePerProfile->save();

       };

        $deleteZone = Zones::findOrFail($id);
        $deleteZone->delete();
        return $this->showMessage("Successfully Deleted", "done");
    }
}
