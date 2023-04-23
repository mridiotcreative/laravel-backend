<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_likes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'users_comments_likes_id_foreign')->references('id')->on('users')->onDelete('set NULL');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->foreign('comment_id', 'comment_likes_id_foreign')->references('id')->on('video_comments')->onDelete('set NULL');
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
        Schema::dropIfExists('comment_likes');
    }
}
