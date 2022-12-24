<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncentiveBarcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incentive_barcode', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('barcode_number');
            $table->string('photo');
            $table->integer('points');
            //$table->smallInteger('is_used')->default(0);
            $table->dateTime('is_used')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->dateTime('expired_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
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
        Schema::dropIfExists('incentive_barcode');
    }
}
