<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name')->default("Empty!");
            $table->string('about')->default("Empty!");
            $table->string('image')->nullable();
            $table->string('national_id_photo')->nullable();
            $table->string('driving_license_photo')->nullable();
            $table->timestamps();

              //Defining User id through foreignkey to Tracke the User................
              $table->unsignedBigInteger('user_id');
              $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

              //Defining assign_car_id id through foreignkey to Tracke the car................
              $table->unsignedBigInteger('assign_car_id')->nullable();
              $table->foreign('assign_car_id')->references('id')->on('car_details')->onDelete('cascade');

              //Defining salary_id id through foreignkey to Tracke the salaries................
              $table->unsignedBigInteger('salary_id')->nullable();
              $table->foreign('salary_id')->references('id')->on('salaries')->onDelete('cascade');

              //Defining zone_id id through foreignkey to Tracke the zones................
              $table->unsignedBigInteger('zone_id')->nullable();
              $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');

              //Defining service_charge_id id through foreignkey to Tracke the service_chagres................
              $table->unsignedBigInteger('service_charge_id')->nullable();
              $table->foreign('service_charge_id')->references('id')->on('service_chagres')->onDelete('cascade');

        });
    }




    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
