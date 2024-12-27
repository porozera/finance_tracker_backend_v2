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

    public function account()
    {
        return $this->belongsTo(Account::class, 'account'); // pastikan nama kolom foreign key benar
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category'); // pastikan nama kolom foreign key benar
    }
}
