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
            $table->string('payment_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->double('total_amount', 8, 2);
            $table->enum('payment_method', ['cod', 'razorpay'])->default('cod');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid');
            $table->enum('status', ['pending', 'process', 'delivered', 'cancel'])->default('pending');
            $table->longText('payment_response_log')->nullable();
            $table->timestamps();

            $table->foreign('customer_id', 'orders_customer_id_foreign')->references('id')->on('customers')->onDelete('set NULL');
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
