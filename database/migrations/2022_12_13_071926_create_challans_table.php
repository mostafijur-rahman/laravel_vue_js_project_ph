<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challans', function (Blueprint $table) {

            $table->id('id');
            $table->string('encrypt');

            $table->enum('type', ['deposit', 'commission', 'passenger_going', 'passenger_incoming']);
            $table->string('challan_number')->nullable();
            $table->string('coach_number')->nullable();

            $table->timestamp('start_date_time')->nullable();
            $table->timestamp('destination_reach_date_time')->nullable();
            $table->timestamp('account_take_date_time')->nullable();

            // trip info
            $table->decimal('box', 10, 2)->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->unsignedTinyInteger('unit_id')->nullable();
            $table->string('goods')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_code')->nullable();
            $table->string('order_no')->nullable();
            $table->decimal('depu_change_bill', 10, 2)->nullable();
            $table->string('gate_pass_no')->nullable();
            $table->string('lock_no')->nullable();
            $table->timestamp('load_point_reach_time')->nullable();
            $table->mediumText('note')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('challans');
    }
}
