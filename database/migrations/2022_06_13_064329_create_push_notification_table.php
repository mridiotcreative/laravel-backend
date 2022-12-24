<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->longText('to')->nullable();
            $table->smallInteger('type')->nullable()->comment('1-health article,2-new product,3-offer');
            $table->unsignedBigInteger('table_rec_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->smallInteger('is_single')->default(1)->comment('0-No,1-Yes');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->smallInteger('is_sent')->default(0)->comment('0-No,1-Yes');
            $table->longText('fcm_response')->nullable();
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
        Schema::dropIfExists('push_notifications');
    }
}
