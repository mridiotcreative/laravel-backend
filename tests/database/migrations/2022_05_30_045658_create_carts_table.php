<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->double('price', 8, 2);
            $table->enum('status', ['new', 'progress', 'delivered', 'cancel'])->default('new');
            $table->integer('quantity');
            $table->double('amount', 8, 2);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamps();
            
            $table->foreign('customer_id', 'carts_customer_id_foreign')->references('id')->on('customers')->onDelete('set NULL');
            $table->foreign('order_id', 'carts_order_id_foreign')->references('id')->on('orders')->onDelete('set NULL');
            $table->foreign('product_id', 'carts_product_id_foreign')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('user_id', 'carts_user_id_foreign')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
