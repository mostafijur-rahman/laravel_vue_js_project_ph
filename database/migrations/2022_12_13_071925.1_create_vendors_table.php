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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id('id');
            $table->string('encrypt')->nullable();
            $table->unsignedTinyInteger('sort')->default(0);
            
            $table->string('name');
            $table->string('phone')->nullable();
            $table->mediumText('address')->nullable();
            $table->mediumText('note')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->enum('type', ['pump', 'other', 'vehicle_supplier']);

            $table->decimal('previous_loan', 10, 2)->default(0);
            $table->date('loan_date')->nullable();

            $table->decimal('previous_advance', 10, 2)->default(0);
            $table->date('advance_date')->nullable();

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
        Schema::dropIfExists('vendors');
    }
};
