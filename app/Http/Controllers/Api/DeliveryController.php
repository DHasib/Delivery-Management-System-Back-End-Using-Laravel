<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Models\Delivery;
use App\Models\Transaction;


use Illuminate\Support\Facades\Auth;

class DeliveryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
        $deliveryDetails = Delivery::where('status', 'pending')    
                                    ->orWhere('status', 'prograssing')
                                    ->with('profile.user','profile.serviceCharge')
                                    ->orderBy('created_at','desc')
                                    ->get();

         return $this->showData($deliveryDetails);
    }


    public function cancelAndDoneDelivery()
    {
        $this->authorize('isAdmin');
        
        $deliveryDetails = Delivery::where('status', 'done')    
                                    ->orWhere('status', 'cancel')
                                    ->with('profile.user','profile.serviceCharge')
                                    ->orderBy('created_at','desc')
                                    ->get();

         return $this->showData($deliveryDetails);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('isMerchant');

        $createDelivery = $request->validate([
            'pickup_address'       => 'required|string|max:200',
            'delivery_address'     => 'required|string|max:200',
            'delivery_mobile'      => 'required|regex:/(01)[0-9]{9}/|max:16',
            'pickup_mobile'        => 'required|regex:/(01)[0-9]{9}/|max:16',
            'amount_Collect'       => 'required|integer',
            'reference_Id'         => 'required|string',
            'instraction'          => 'required|string',
        ]);

        $createDelivery['profile_id']  =  auth()->user()->profile->id;
        $createDelivery['status']      =  'pending';

        Delivery::create($createDelivery);

        return $this->showMessage("Successfully Created", "done");
    }


    // get only auth user Delivery data 
    public function authUserDelivery()
    {
        $this->authorize('isMerchant');

        $authUserDeliveryDetails = Delivery::where('profile_id',auth()->user()->profile->id )->with('profile.user','profile.serviceCharge')
                                    ->orderBy('created_at','desc')
                                    ->get();

         return $this->showData($authUserDeliveryDetails);
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
        $this->authorize('isMerchant');

        $updateDelivery = Delivery::findOrFail($id);


        $createDelivery = $request->validate([
            'pickup_address'       => 'string|max:200',
            'delivery_address'     => 'string|max:200',
            'delivery_mobile'      => 'integer|regex:/(01)[0-9]{9}/|max:16',
            'pickup_mobile'        => 'integer|regex:/(01)[0-9]{9}/|max:16',
            'amount_Collect'       => 'integer',
            'reference_Id'         => 'string',
            'instraction'          => 'string',
        ]);


            if($request->has('pickup_address')){
                $updateDelivery->pickup_address   = $request->pickup_address;
            }
            
           if($request->has('delivery_address')){
                $updateDelivery->delivery_address  = $request->delivery_address;
            }
            if($request->has('delivery_mobile')){
                $updateDelivery->delivery_mobile   = $request->delivery_mobile;
            }

           if($request->has('pickup_mobile')){
                $updateDelivery->pickup_mobile  = $request->pickup_mobile;
            }
            if($request->has('amount_Collect')){
                $updateDelivery->amount_Collect   = $request->amount_Collect;
            }

           if($request->has('reference_Id')){
                $updateDelivery->reference_Id  = $request->reference_Id;
            }
            if($request->has('instraction')){
                $updateDelivery->instraction   = $request->instraction;
            }

            $updateDelivery->save();

            return $this->showMessage("Successfully Updated", "done");

    }

    // update delivery status 
    public function updateDelivertStatus(Request $request, $id)
    {
        $this->authorize('isAdmin');
        // $this->authorize('isMerchant');


        $createDelivery = $request->validate([
            'status'      => ['required',Rule::in(['prograssing', 'pending', 'done', 'cancel'])],
        ]);

        $updateDelivery = Delivery::findOrFail($id);

        if($request->has('status')){

                if ($request->status === 'done' ) {

                    $transactionCreate = Transaction::create([
                        'delivery_id'           => $updateDelivery->id,
                        'service_charge'        => $updateDelivery->profile->serviceCharge->charge_amount, //amount_Collect
                        'net_amount'            => $updateDelivery->amount_Collect,
                        'due_amount'            => ($updateDelivery->amount_Collect - $updateDelivery->profile->serviceCharge->charge_amount),
                        'status'                => 'due',
                    ]);

                    // $transactionCreate = Income::create([
                    //     'transaction_id'        => $transactionCreate->id,
                    //     'service_charge'        => $updateDelivery->profile->serviceCharge->charge_amount, //amount_Collect
                    // ]);

                }
                
            $updateDelivery->status   = $request->status;
        }

        $updateDelivery->save();
        return $this->showMessage("Successfully Updated", "done");

    }
  
}
