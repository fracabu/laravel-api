<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger("user_id")->after("id");
            $table->foreign("user_id")
                ->references("id")
                ->on("users");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            //prima di cancellare la colonna user_bisogna cancellare la foreignkey (posts_user_id_foreign)
            $table->dropForeign("posts_user_id_foreign");
            //drop della colonna user_id
            $table->dropColumn("user_id");
        });
    }
}
