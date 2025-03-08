<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_product_image extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'master_product_image';
    protected $fillable = [
        'Product_ID',
        'image_other',
        'status',
    ];
}
