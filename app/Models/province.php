<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class province extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name_th',
        'name_en',
        'geography_id',
    ];
    
}
