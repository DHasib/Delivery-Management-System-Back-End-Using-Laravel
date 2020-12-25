<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('service_charge');
            $table->string('repair_hardware_name')->nullable();
            $table->integer('repair_hardware_price')->nullable();
            $table->string('garage_name');
            $table->string('garage_address');
            $table->string('garage_phone_num');
            $table->timestamps();

             //Defining car_id id through foreignkey to Tracke the Car................
             $table->unsignedBigInteger('car_id')->nullable();
             $table->foreign('car_id')->references('id')->on('car_details')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_services');
    }
}
