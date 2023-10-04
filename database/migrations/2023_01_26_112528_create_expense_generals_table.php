<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseGeneralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_generals', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('encrypt')->nullable();

            $table->unsignedBigInteger('vendor_id')->nullable()->index();
            $table->unsignedBigInteger('vehicle_id')->nullable()->index();
            $table->unsignedBigInteger('challan_id')->nullable()->index();
            $table->unsignedBigInteger('expense_id')->index();
            $table->string('voucher_number')->nullable();
            
            $table->decimal('amount',10,2)->default(0);
            $table->timestamp('date_time')->nullable();
            $table->text('note')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('challan_id')->references('id')->on('challans')->onDelete('cascade');
            $table->foreign('expense_id')->references('id')->on('setting_expenses')->onDelete('cascade');
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
        Schema::dropIfExists('expense_generals');
    }
}
