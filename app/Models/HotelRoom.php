<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_number',
        'type_1',
        'price_full_day',
        'price_half_day',
        'price_hourly',
        'is_available',
        'is_approved',
    ];
}
