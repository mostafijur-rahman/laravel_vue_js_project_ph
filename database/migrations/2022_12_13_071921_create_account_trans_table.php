<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_trans', function (Blueprint $table) {

            $table->id('id');
            $table->string('encrypt');
            
            $table->unsignedBigInteger('account_id')->index();
            $table->nullableMorphs('transactionable');
            $table->enum('type', ['in','out']);
            $table->decimal('amount', 10,2);
            $table->timestamp('date_time')->nullable();
            $table->mediumText('note')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
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
        Schema::dropIfExists('account_trans');
    }
}
