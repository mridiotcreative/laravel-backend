<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoWatchTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_watch_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_watch_time_video_id_foreign')->references('id')->on('users')->onDelete('set NULL');
            $table->unsignedBigInteger('video_id')->nullable();
            $table->foreign('video_id', 'watch_time_video_id_foreign')->references('id')->on('users_video_photos')->onDelete('set NULL');
            $table->string('watch_time');
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
        Schema::dropIfExists('video_watch_times');
    }
}
