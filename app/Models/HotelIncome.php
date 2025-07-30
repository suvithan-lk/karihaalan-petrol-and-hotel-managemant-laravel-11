<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelIncome extends Model
{
    use HasFactory;
    protected $table = 'hotel_incomes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'amount',
        'proof',
        'is_approved',
    ];
}
