<?php

namespace App\Models;

use App\Models\Chat\ChatRoom;
use App\Models\Chat\ChatRoomToUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['avatar_src'];


    public function chats()
    {
        return $this->belongsToMany(ChatRoom::class, 'chat_room_to_users', 'user_id', 'chat_room_id');
    }

    public function chatToUser()
    {
        return $this->hasMany(ChatRoomToUser::class);
    }

    public function getAvatarSrcAttribute()
    {
        return $this->avatar
            ?
            Storage::disk('public')->url($this->avatar)
            :
            asset('/img/not_avatar.jpg');
    }
}
