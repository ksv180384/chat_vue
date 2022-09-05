<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_room_id',
        'user_id',
        'show_notification_new_message'
    ];

    public $timestamps = false;

    public function chat()
    {
        return $this->hasOne(ChatRoom::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
