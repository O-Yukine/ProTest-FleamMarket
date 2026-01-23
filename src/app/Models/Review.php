<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Review extends Model
{
    use HasFactory;

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }
}
