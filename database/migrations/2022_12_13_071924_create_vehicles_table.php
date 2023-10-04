<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            
            $table->id('id');
            $table->unsignedInteger('sort')->default(0);
            $table->string('encrypt')->nullable();

            $table->enum('ownership', ['own','rental'])->nullable();
            $table->string('serial', 50)->nullable();
            $table->string('number_plate', 50)->nullable();
            $table->string('registration_number', 50)->nullable();
            $table->string('engine_number', 50)->nullable();
            $table->string('chassis_number', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->string('manufacturer', 50)->nullable();

            $table->string('body_type', 50)->nullable();
            $table->string('engine_down', 50)->nullable();
            $table->decimal('fuel_tank_capacity', 10, 2)->nullable();
            $table->string('gps_id', 50)->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('guide_id')->nullable();
            $table->unsignedBigInteger('helper_id')->nullable();

            $table->string('registration_year', 50)->nullable();
            $table->date('tax_token_expire_date')->nullable();
            $table->date('fitness_expire_date')->nullable();
            $table->date('insurance_expire_date')->nullable();
            $table->date('last_tyre_change_date')->nullable();
            $table->date('last_mobil_change_date')->nullable();
            $table->date('last_servicing_date')->nullable();

            $table->tinyText('note')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('vehicles');
    }
}
