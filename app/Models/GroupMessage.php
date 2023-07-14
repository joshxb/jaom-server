<?php

namespace App\Models;

use App\Models\GroupMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

    public function groupChat()
    {
        return $this->belongsTo(GroupChat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'group_id',
        'user_id',
        'content'
    ];
}
