<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class representative_phone extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $fillable = [
        'Profile_ID',
        'Phone_number',
        'sequence',
        'Company_ID',
    ];
}
