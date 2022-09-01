<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();

            // FK posts
            $table->unsignedBigInteger("post_id");
            $table->foreign("post_id")->references("id")->on("posts");

            // versione abbrebiata per creare una FK
            // $table->foreignId("post_id")->constrained();

            // FK tags
            $table->unsignedBigInteger("tag_id");
            $table->foreign("tag_id")->references("id")->on("tags");

            // versione abbrebiata per creare una FK
            // $table->foreignId("tag_id")->constrained();

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
        Schema::dropIfExists('post_tag');
    }
}
