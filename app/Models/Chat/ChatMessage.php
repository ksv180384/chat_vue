<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chat_room_id',
        'message',
        'updated_at',
        'created_at',
    ];

    protected $appends = ['created_at_time', 'created_at_date'];

    public function room()
    {
        return $this->hasOne(ChatRoom::class, 'id', 'chat_room_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function scopeList($query)
    {
        return $query->with(['user:id,name,avatar']);
    }

    public function getCreatedAtHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getCreatedAtTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    public function getCreatedAtDateAttribute()
    {
        return $this->created_at->translatedFormat('d F Y');
    }
}
