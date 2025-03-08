<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company_fax extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $fillable = [
        'Profile_ID',
        'Fax_number',
        'sequence',
    ];
}
