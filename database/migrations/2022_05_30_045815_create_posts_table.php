<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique('posts_slug_unique');
            $table->text('summary');
            $table->longText('description')->nullable();
            $table->text('quote')->nullable();
            $table->string('photo')->nullable();
            $table->string('tags')->nullable();
            $table->unsignedBigInteger('post_cat_id')->nullable();
            $table->unsignedBigInteger('post_tag_id')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->foreign('added_by', 'posts_added_by_foreign')->references('id')->on('users')->onDelete('set NULL');
            $table->foreign('post_cat_id', 'posts_post_cat_id_foreign')->references('id')->on('post_categories')->onDelete('set NULL');
            $table->foreign('post_tag_id', 'posts_post_tag_id_foreign')->references('id')->on('post_tags')->onDelete('set NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
