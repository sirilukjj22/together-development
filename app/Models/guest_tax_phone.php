<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class guest_tax_phone extends Model
{
    use HasFactory;
    protected $table = 'guest_tax_phone';
    protected $fillable = [
        'GuestTax_ID',
        'Phone_number',
        'sequence',
    ];
}
