<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique('orders_order_number_unique');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->double('sub_total', 8, 2);
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->double('coupon', 8, 2)->nullable();
            $table->double('total_amount', 8, 2);
            $table->integer('quantity');
            $table->enum('payment_method', ['cod', 'paypal'])->default('cod');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('status', ['new', 'process', 'delivered', 'cancel'])->default('new');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country');
            $table->string('post_code')->nullable();
            $table->text('address1');
            $table->text('address2')->nullable();
            $table->timestamps();
            
            $table->foreign('shipping_id', 'orders_shipping_id_foreign')->references('id')->on('shippings')->onDelete('set NULL');
            $table->foreign('user_id', 'orders_user_id_foreign')->references('id')->on('users')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
