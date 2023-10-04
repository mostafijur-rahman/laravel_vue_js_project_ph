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
        Schema::create('vendor_payments', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('encrypt');

            $table->nullableMorphs('purposeable'); // purpose of payment

            $table->unsignedBigInteger('vendor_id')->index();
            $table->string('payment_type', 50); // ['contract_rent', 'extend_rent', 'demurrage_rent']
            $table->string('voucher_number')->nullable();
            $table->timestamp('date_time')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('note')->nullable();

            // common
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // index
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
        Schema::dropIfExists('vendor_payments');
    }
};
