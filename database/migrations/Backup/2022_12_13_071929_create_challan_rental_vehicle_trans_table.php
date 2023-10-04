<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChallanRentalVehicleTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('challan_rental_vehicle_trans', function (Blueprint $table) {

            $table->id('id');
            $table->string('encrypt');
            $table->unsignedBigInteger('challan_rental_vehicle_id')->index();

            $table->enum('type', ['advance_fare', 'billing_fare', 'deduction_fare', 'extend_fair', 'demurrage_fare']);
            $table->string('voucher_number')->nullable();

            $table->unsignedBigInteger('account_tran_id')->index();
            // $table->decimal('amount', 10,2)->default(0);
            // $table->timestamp('payment_date');
            // $table->mediumText('note')->nullable();

            $table->string('recipients_name')->nullable();
            $table->string('recipients_phone')->nullable();
            
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('challan_rental_vehicle_id')->references('id')->on('challan_rental_vehicles')->onDelete('cascade');
            $table->foreign('account_tran_id')->references('id')->on('account_trans')->onDelete('cascade');
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
        Schema::dropIfExists('challan_rental_vehicle_trans');
    }
}
