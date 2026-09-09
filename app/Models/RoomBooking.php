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

    protected $casts = [
        'booking_date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'total_price' => 'decimal:2',
        'is_approved' => 'boolean',
    ];

    public function room()
    {
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id');
    }
}
