<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\ZoneController;
use App\Http\Controllers\Api\CarDetailsController;
use App\Http\Controllers\Api\CarServicesController;
use App\Http\Controllers\Api\ServiceChargeController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\DeliveryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\IncomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:api'])->group(function () {


    Route::get('/authUser', [AuthenticationController::class, 'authUserDetails']);
    Route::get('/freeCar', [CarDetailsController::class, 'availableCar']);
    Route::get('/authUserDeliveryDetails', [DeliveryController::class, 'authUserDelivery']);
    Route::get('/cancelAndDoneDelivery', [DeliveryController::class, 'cancelAndDoneDelivery']);
    Route::get('/authUserTransaction', [TransactionController::class, 'authUserTransaction']);
    Route::put('/updateDeliveryStatus/{id}', [DeliveryController::class, 'updateDelivertStatus']);
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/UserServiceChargeUpdate', [ServiceChargeController::class, 'updateUserServiceCharge']);

    Route::apiResource('/users', UserController::class);
    Route::apiResource('/salary', SalaryController::class);
    Route::apiResource('/zone', ZoneController::class);
    Route::apiResource('/serviceCharges', ServiceChargeController::class);
    

    Route::apiResource('/carDetails', CarDetailsController::class);
    Route::apiResource('/carServices', CarServicesController::class);
    Route::apiResource('/employee', EmployeeController::class);
    Route::apiResource('/delivery', DeliveryController::class);
    Route::apiResource('/transaction', TransactionController::class);
    Route::apiResource('/income', IncomeController::class);

});



Route::get('/user', [UserController::class, 'index']);


Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);


