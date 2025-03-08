<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master_company extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'master_company';
    protected $fillable = [
        'name',
        'tel',
        'address',
        'email',
        'web',
        'fax',
    ];
}
