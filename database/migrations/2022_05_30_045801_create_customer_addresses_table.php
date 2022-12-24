<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('building_name');
            $table->string('street_name');
            $table->integer('pincode');
            $table->string('city');
            $table->smallInteger('is_primary')->default(0);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id', 'customer_addresses_customer_id_foreign')->references('id')->on('customers')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
}
