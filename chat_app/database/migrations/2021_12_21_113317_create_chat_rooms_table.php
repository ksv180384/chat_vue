<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->nullable()->default(null)->index();
            $table->string('title');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users');
        });

        Schema::create('chat_room_to_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('chat_room_id')->index();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
        });

        Schema::table('chat_room_to_users', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['chat_room_id']);
        });
        Schema::dropIfExists('chat_room_to_users');
        Schema::dropIfExists('chat_rooms');
    }
}
