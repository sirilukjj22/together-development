<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class representative extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $fillable = [
        'Profile_ID',
        'prefix',
        'First_name',
        'Last_name',
        'status',
        'Country',
        'City',
        'Amphures',
        'Tambon',
        'Address',
        'Zip_Code',
        'Email',
        'Company_ID',
        'Company_Name',
        'Branch',
    ];
}
