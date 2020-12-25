<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pickup_address');
            $table->string('delivery_address');
            $table->string('delivery_mobile');
            $table->string('pickup_mobile');
            $table->integer('amount_Collect');
            $table->string('reference_Id');
            $table->string('instraction');
            $table->string('status');
            $table->timestamps();

              //Defining Profile id through foreignkey to Tracke the User................
              $table->unsignedBigInteger('profile_id');
              $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
