<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShareCountInTableUsersVideoPhoto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_video_photos', function (Blueprint $table) {
            $table->string('share_count')->after('upload_url')->default('0');
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
            $table->dropColumn('share_count');
        });
    }
}
