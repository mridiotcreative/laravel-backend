<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoryWatchTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('story_watch_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'user_watch_time_story_id_foreign')->references('id')->on('users')->onDelete('set NULL');
            $table->unsignedBigInteger('story_id')->nullable();
            $table->foreign('story_id', 'watch_time_story_id_foreign')->references('id')->on('users_stories')->onDelete('set NULL');
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
        Schema::dropIfExists('story_watch_times');
    }
}
