<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class guest_tax extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'guest_tax';
    protected $fillable = [
        'GuestTax_ID',
        'Company_ID',
        'Company_type',
        'Company_tax',
        'Company_Name',
        'Country',
        'status',
        'City',
        'Amphures',
        'Tambon',
        'Address',
        'Zip_Code',
        'Company_Email',
        'Taxpayer_Identification',
    ];
}
