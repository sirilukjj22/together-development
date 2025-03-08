<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class freelancer_com_mphones extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'freelancer_com_mphones';
    protected $fillable = [
        'Profile_ID',
        'Phone_number',
        'sequence',
    ];
}
