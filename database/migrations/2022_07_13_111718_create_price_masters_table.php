<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_master', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'products_product_id_foreign')->references('id')->on('products')->onDelete('set NULL');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'products_user_id_foreign')->references('id')->on('customers')->onDelete('set NULL');
            $table->string('special_price')->nullable();
            $table->string('created_by');
            $table->enum('status', ['active', 'inactive'])->default('inactive');
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
        Schema::dropIfExists('price_master');
    }
}
