<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;
use App\Models\User;

class Message extends Model
{
    use HasFactory;

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
