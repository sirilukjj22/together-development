<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company_phone extends Model
{
    use HasFactory;
    protected $fillable = [
        'Profile_ID',
        'Phone_number',
        'sequence',
    ];
}
