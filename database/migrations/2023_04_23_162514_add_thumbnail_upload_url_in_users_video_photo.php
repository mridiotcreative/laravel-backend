<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbnailUploadUrlInUsersVideoPhoto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_video_photos', function (Blueprint $table) {
            $table->string('thumbnail_upload_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_video_photos', function (Blueprint $table) {
            Schema::dropIfExists('thumbnail_upload_url');
        });
    }
}
