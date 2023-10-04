<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {

            $table->id('id');
            $table->unsignedInteger('sort')->default(0);
            $table->string('encrypt')->nullable();

            // personal
            $table->enum('gender', ['male', 'female', 'third', 'other']);
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->string('present_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('photo', 50)->nullable();

            // document
            $table->string('company_id_number')->nullable();
            $table->date('joining_date')->nullable();
            $table->unsignedBigInteger('designation_id')->index();
            $table->string('nid_number')->nullable();
            $table->string('driving_license_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('birth_certificate_number')->nullable();
            $table->string('port_id')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->decimal('salary_amount', 10, 2)->nullable();
            $table->date('termination_date')->nullable();

            // $table->string('reference_name')->nullable();
            // $table->string('reference_phone')->nullable();
            // $table->string('reference_nid_number')->nullable();
            // $table->string('reference_present_address')->nullable();

            $table->text('note')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked']);

            $table->unsignedBigInteger('company_id')->index();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('designation_id')->references('id')->on('setting_designations')->onDelete('cascade');
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
        Schema::dropIfExists('staffs');
    }
}
