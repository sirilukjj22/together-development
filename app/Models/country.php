<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'tbl_country';
    protected $fillable = [
        'ct_nameTHA',
        'ct_nameENG',
        'code',
        'ct_code',
    ];
}
