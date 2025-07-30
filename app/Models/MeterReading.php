<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    protected  $fillable = [
        "meter_reading",
        "amount_received",
        "proof_image",
        'is_approved',
    ];
}
