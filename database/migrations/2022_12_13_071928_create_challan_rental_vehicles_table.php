<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challan_rental_vehicles', function (Blueprint $table) {
            
            $table->id();
            $table->string('encrypt');
            $table->unsignedBigInteger('challan_id')->index();
            $table->unsignedBigInteger('vendor_id')->index();

            // vehicle info
            $table->string('vehicle_number', 50)->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_phone')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('reference_phone')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            // $table->timestamps();
            $table->softDeletes();

            $table->foreign('challan_id')->references('id')->on('challans')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('company_id')->references('company_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challan_rental_vehicle_infos');
    }
};
