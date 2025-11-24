<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'user_id',
        'product_id',
        'payment_method',
        'post_code',
        'address',
        'building'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
