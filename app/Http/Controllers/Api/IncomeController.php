<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends ApiController
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
        
        $incomeDetails = Income::with('transaction.delivery','transaction.delivery.profile.user')
                                    ->orderBy('created_at','desc')
                                    ->get();

         return $this->showData($incomeDetails);
    }

   
}
