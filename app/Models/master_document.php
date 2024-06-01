<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_document extends Model
{
    use HasFactory;
    protected $fillable = [
        'sort',
        'code',
        'swiftcode',
        'Category',
        'lavel',
        'name_th',
        'name_en',
        'detail_th',
        'detail_en',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'created_at',
    ];
}
