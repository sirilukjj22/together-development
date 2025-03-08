<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class freelancer_com_massage extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'freelancer_com_massages';
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
        'Contact_Name',
        'Check_In_Date',
        'Check_Out_Date',
        'Pax',
        'Member_ID',
    ];
    public function  member()
    {
        return $this->hasOne(Freelancer_Member::class, 'Profile_ID', 'Member_ID');
    }

}
