<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\GroupChat;
use App\Models\GroupMessage;
use App\Models\Update;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
        'status',
        'image',
        'nickname',
        'location',
        'age',
        'visibility',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function groupChats()
    // {
    //     return $this->hasMany(GroupChat::class);
    // }

    public function groupMessages()
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function groups()
    {
        return $this->belongsToMany(GroupChat::class, 'group_user', 'user_id', 'group_id');
    }

    public function updates()
    {
        return $this->hasMany(Update::class);
    }

}
