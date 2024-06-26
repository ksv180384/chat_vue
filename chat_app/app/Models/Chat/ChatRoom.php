<?php

namespace App\Models\Chat;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
            'id'
        )->orderBy('users.name');
    }

    // Relationships

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function messages()
    {
        return $this->belongsTo(ChatMessage::class, 'chat_room_id', 'id');
    }

    public function settings()
    {

//        $userId = !empty($this->settingsUserId) ? $this->settingsUserId : Auth::id();
//        dd($userId);
        return $this->hasOne(ChatUserSettings::class);
//            ->where('user_id', $userId);
    }

    // Scopes --------

    public function scopeOne($query)
    {
        return $query->with([
            'creator:id,name,avatar',
        ]);
    }

    public function scopeSettingByUserId($query, int $userId)
    {
        return $query->with(['settings' => function ($q) use ($userId) {
            $q->where('user_id', $userId);
        },]);
    }

    public function scopeList($query)
    {
        return $query->with(['creator:id,name,avatar']);
    }

}
