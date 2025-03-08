<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class amphures extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $fillable = [
        'code',
        'name_th',
        'name_en',
        'province_id',
    ];
}
