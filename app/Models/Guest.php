<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'Profile_ID',
        'First_name',
        'Last_name',
        'Booking_Channel',
        'Country',
        'City',
        'Amphures',
        'Tambon',
        'Address',
        'Zip_Code',
        'Email',
        'Identification_Number',
        'Contract_Rate_Start_Date',
        'Contract_Rate_End_Date',
        'Discount_Contract_Rate',
        'Lastest_Introduce_By',
    ];
}
