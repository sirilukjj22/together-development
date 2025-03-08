<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelancer_checked_phone extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'freelancer_checked_phones';
    protected $fillable = [
        'Profile_ID',
        'Phone_number',
        'sequence',
    ];
}
