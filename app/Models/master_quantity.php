<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_quantity extends Model
{
    use HasFactory;
    protected $fillable = [
        'Product_ID',
        'name_th',
        'name_en',
    ];
}
