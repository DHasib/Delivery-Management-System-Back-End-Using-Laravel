<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\ApiController;
use App\Models\Income;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;

class TransactionController extends ApiController
{


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        // $this->authorize('isMerchant');
        
        $deliveryDetails = Transaction::with('delivery','delivery.profile.user')
                                    ->orderBy('created_at','desc')
                                    ->get();

         return $this->showData($deliveryDetails);
    }


    public function authUserTransaction()
    {
         $this->authorize('isMerchant');

         $authUserTransaction = Delivery::where('profile_id', auth()->user()->profile->id)
                                      ->where( 'status' , 'done' )
                                      ->with('transaction')
                                    ->orderBy('created_at','desc')
                                    ->get();

       

         return $this->showData($authUserTransaction);
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
        // $this->authorize('isMerchant');


        $createDelivery = $request->validate([
            'status'      => ['required',Rule::in(['paid'])],
        ]);

        $updateTransactionStatus = Transaction::findOrFail($id);

        $updateTransactionStatus->status       = $request->status;
        $updateTransactionStatus->due_amount   = 0;
             
            Income::create([
                'transaction_id'        => $updateTransactionStatus->id,
                'service_charge'        => $updateTransactionStatus->delivery->profile->serviceCharge->charge_amount, //amount_Collect
            ]);
        

        $updateTransactionStatus->save();

        return $this->showMessage("Successfully Updated", "done");

   

    }


}
