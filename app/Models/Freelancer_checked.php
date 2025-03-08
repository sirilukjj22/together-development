<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freelancer_checked extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'freelancer_checkeds';
    protected $fillable = [
        'prefix',
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
        'Bank_number',
        'Bank_account_Name',
        'Mbank',
        'Imagefreelan',
        'Identification_file',
        'Bank_file',
        'First_day_work',
        'Birthday',
    ];
}
