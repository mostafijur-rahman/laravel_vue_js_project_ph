<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_references', function (Blueprint $table) {

            $table->id('id');
            $table->unsignedInteger('sort')->default(0);
            $table->string('encrypt')->nullable();

            $table->unsignedBigInteger('staff_id')->index();
            $table->string('name')->nullable();
            $table->string('relation')->nullable();
            $table->string('phone')->nullable();
            $table->string('nid_number', 20)->nullable();
            $table->string('address')->nullable();
            $table->boolean('main_referrer')->default(0);

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
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
        Schema::dropIfExists('staff_references');
    }
}
