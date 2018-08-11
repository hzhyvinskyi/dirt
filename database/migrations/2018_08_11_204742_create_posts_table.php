<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->mediumText('intro');
            $table->text('body');
            $table->string('image');
            $table->unsignedTinyInteger('category_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedInteger('views')->default(0);
            $table->unsignedTinyInteger('is_recommended')->default(0);
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
        Schema::dropIfExists('posts');
    }
}
