<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallanOwnVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('challan_own_vehicles', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('encrypt');
            $table->unsignedBigInteger('challan_id')->index();

            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('guide_id')->nullable();
            $table->unsignedBigInteger('helper_id')->nullable();
            $table->softDeletes();

            $table->foreign('challan_id')->references('id')->on('challans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challan_own_vehicles');
    }
}
