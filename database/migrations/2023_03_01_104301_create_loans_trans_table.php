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
        Schema::create('loans_trans', function (Blueprint $table) {
            $table->id();
            $table->string('encrypt')->nullable();

            $table->nullableMorphs('providerable'); // reference can be provider or can be client (vendor/client)
            $table->nullableMorphs('purposeable'); // purpose of loan
            $table->enum('type', ['in','out']); // in = loan received and out = loan provide, normally we will use 'in' transection

            $table->decimal('amount', 10,2);
            $table->timestamp('date_time');
            $table->mediumText('note')->nullable();

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();


            // plan:
            // during loan payment, we will use type = out in trans table account_trans
            // and keep the primary key of this table as reference in accout_trans (transection able)

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans_trans');
    }
};
