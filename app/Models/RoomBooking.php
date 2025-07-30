<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    use HasFactory;
    protected $fillable = [
        'hotel_room_id',
        'customer_name',
        'customer_phone',
        'booking_duration',
        'booking_date',
        'total_price',
        'check_in',
        'check_out',
        'is_approved',
    ];

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
    }
}
