<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableOrdersAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('building_name')->after('order_number');
            $table->string('street_name')->after('building_name');
            $table->string('pincode')->after('street_name');
            $table->string('city')->after('pincode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('building_name');
            $table->dropColumn('street_name');
            $table->dropColumn('pincode');
            $table->dropColumn('city');
        });
    }
}
