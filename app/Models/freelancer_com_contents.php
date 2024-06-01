<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class freelancer_com_contents extends Model
{
    use HasFactory;
    protected $table = 'freelancer_com_contents';
    protected $fillable = [
        'Profile_ID',
        'Product_ID',
        'Quantity',
    ];
    public function  product()
    {
        return $this->hasOne(master_product_item::class, 'Product_ID', 'Product_ID');
    }
    public function  productunit()
    {
        return $this->hasOne(master_unit::class, 'id', 'unit');
    }
}
