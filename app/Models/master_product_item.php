<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_product_item extends Model
{
    use HasFactory;
    protected $fillable = [
        'Product_ID',
        'name_th',
        'name_en',
        'detail_th',
        'detail_en',
        'status',
        'pax',
        'category',
        'room_size',
        'normal_price',
        'weekend_price',
        'long_weekend_price',
        'quantity',
        'unit',
        'maximum_discount',
        'type',
        'created_by',
        'updated_by',
        'created_at',
        'created_at',
    ];
    public function  productunit()
    {
        return $this->hasOne(master_unit::class, 'id', 'unit');
    }
}
