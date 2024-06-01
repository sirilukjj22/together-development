<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class companys extends Model
{
    use HasFactory;
    protected $fillable = [
        'Profile_ID',
        'Company_type',
        'Company_Name',
        'Branch',
        'status',
        'Market',
        'Booking_Channel',
        'Country',
        'City',
        'Amphures',
        'Tambon',
        'Address',
        'Zip_Code',
        'Company_Email',
        'Company_Website',
        'Taxpayer_Identification',
        'Contract_Rate_Start_Date',
        'Contract_Rate_End_Date',
        'Discount_Contract_Rate',
        'Lastest_Introduce_By',
    ];
}
