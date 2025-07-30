<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetrolDayExpense extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'amount', 'proof', 'is_approved', 'type'];

}
