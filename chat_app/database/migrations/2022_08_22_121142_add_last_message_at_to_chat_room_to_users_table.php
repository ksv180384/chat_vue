<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastMessageAtToChatRoomToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_room_to_users', function (Blueprint $table) {
            $table->timestamp('last_message_at')
                ->after('chat_room_id')
                ->useCurrent()
                ->comment('дата последнего прочитанного сообщения');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_room_to_users', function (Blueprint $table) {
            $table->dropColumn('last_message_at');
        });
    }
}
