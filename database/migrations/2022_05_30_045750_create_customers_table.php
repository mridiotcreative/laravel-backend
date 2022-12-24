<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('firm_name');
            $table->string('full_name')->nullable();
            $table->string('email')->unique('customers_email_unique');
            $table->string('password');
            $table->bigInteger('total_points')->default(0);
            $table->integer('state_id');
            $table->integer('city_id');
            $table->integer('pincode');
            $table->string('contact_no_1');
            $table->string('contact_no_2');
            $table->string('gst_no')->nullable();
            $table->text('gst_document')->nullable();
            $table->string('drug_licence_no')->nullable();
            $table->text('drug_document')->nullable();
            $table->text('id_proof_document');
            $table->string('designation')->nullable();
            $table->smallInteger('is_verified')->default(0);
            $table->smallInteger('status')->default(0);
            $table->longText('resion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
