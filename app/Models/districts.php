<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class districts extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $fillable = [
        'zip_code',
        'name_th',
        'name_en',
        'amphure_id',
    ];
}
