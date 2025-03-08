<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class freelancer_com_mfaxes extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'freelancer_com_mfaxes';
    protected $fillable = [
        'Profile_ID',
        'Fax_number',
        'sequence',
    ];
}
