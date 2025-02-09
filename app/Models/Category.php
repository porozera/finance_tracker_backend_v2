<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'icon', 
        'color', 
        'budget', 
        'expense'
    ];

    // Relasi ke Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

