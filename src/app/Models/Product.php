<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Purchase;
use App\Models\Comment;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'condition_id',
        'user_id',
        'name',
        'price',
        'product_image',
        'brand',
        'content'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'user_product_like');
    }

    public function scopeProductSearch($query, $keyword)
    {

        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        return $query;
    }

    public function getIsSoldAttribute()
    {
        return $this->purchases->contains('status', 'paid');
    }
}
