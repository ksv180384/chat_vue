<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'creator_id',
        'title',
    ];

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'chat_room_to_users',
            'chat_room_id',
            'user_id',
            'id',
            'id');
    }

    public function creator()
    {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }

    public function scopeOne($query)
    {
        return $query->with([
            'creator:id,name,avatar',
            'users:users.id,users.name,users.avatar'
        ]);
    }

    public function scopeList($query)
    {
        return $query->with(['creator:id,name,avatar']);
    }

}
