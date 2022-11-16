<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_user_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_room_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->boolean('show_notification_new_message')
                ->default(false)
                ->comment('оповещение о получении нового сообщения в чате');

            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_user_settings', function (Blueprint $table) {
            $table->dropForeign(['chat_room_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('chat_user_settings');
    }
}
