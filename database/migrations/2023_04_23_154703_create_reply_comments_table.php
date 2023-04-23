<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplyCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reply_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id', 'users_reply_comments_id_foreign')->references('id')->on('users')->onDelete('set NULL');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->foreign('comment_id', 'reply_comments_id_foreign')->references('id')->on('video_comments')->onDelete('set NULL');
            $table->string('user_comment');
            $table->softDeletes();
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
        Schema::dropIfExists('reply_comments');
    }
}
