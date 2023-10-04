<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallanSettingAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_area_challan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('setting_area_id')->index();
            $table->unsignedBigInteger('challan_id')->index();
            $table->enum('point', ['load','unload']);
        
            $table->foreign('setting_area_id')->references('id')->on('setting_areas')->onDelete('cascade');
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
        Schema::dropIfExists('setting_area_challan');
    }
}
