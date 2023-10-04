<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientBillsTable  extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('client_bills', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('encrypt');

            $table->nullableMorphs('purposeable'); // purpose of bill

            $table->unsignedBigInteger('client_id')->index();
            $table->string('bill_type', 50); // ['contract_rent', 'extend_rent', 'demurrage_rent']
            $table->string('voucher_number')->nullable();
            $table->timestamp('date_time')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->text('note')->nullable();
            
            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
        Schema::dropIfExists('client_bills');
    }
}
