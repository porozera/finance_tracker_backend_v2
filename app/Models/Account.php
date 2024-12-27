<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'holderName', 
        'accountNumber', 
        'icon', 
        'color', 
        'isDefault',
        'balance',
        'income',
        'expense'
    ];

    // Relasi ke Payments
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}


