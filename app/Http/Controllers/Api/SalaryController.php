<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin');
        
        $salaries = Salary::latest()->get();

         return $this->showData($salaries);
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

        $createSalary = $request->validate([
            'salary_amount'  => 'required|integer|min:2|max:200000',
            'bonus'          => 'nullable|integer|min:1|max:100000',
            ]);

            Salary::create($createSalary);

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

        $updateSalary = $request->validate([
            'salary_amount'  => 'integer|min:2|max:200000',
            'bonus'          => 'nullable|integer|min:1|max:100000',
            ]);

            $updateSalary = Salary::findOrFail($id);

            if($request->has('salary_amount')){
                $updateSalary->salary_amount   = $request->salary_amount;
            }
           if($request->has('bonus')){
                $updateSalary->bonus  = $request->bonus;
            }

             $updateSalary->save();

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

        $salaryDelete = Salary::findOrFail($id);
        $salaryDelete->delete();
        return $this->showMessage("Successfully Deleted", "done");
    }
}
