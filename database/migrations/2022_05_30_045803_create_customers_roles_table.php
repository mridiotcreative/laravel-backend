<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            
            $table->foreign('customer_id', 'customers_roles_customer_id_foreign')->references('id')->on('customers')->onDelete('set NULL');
            $table->foreign('role_id', 'customers_roles_role_id_foreign')->references('id')->on('roles')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers_roles');
    }
}
