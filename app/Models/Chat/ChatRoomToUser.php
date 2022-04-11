<?php

namespace App\Models\Chat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomToUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chat_room_id',
    ];

    public $timestamps = false;
}
