<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'account', 
        'category', 
        'amount', 
        'type', 
        'datetime'
    ];

    // Relasi ke Account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
