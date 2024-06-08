<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document_quotation extends Model
{
    use HasFactory;
    protected $table = 'document_quotation';
    protected $fillable = [
        'Quotation_ID',
        'Product_ID',
        'Company_ID',
        'Issue_date',
        'ExpirationDate',
        'freelanceraiffiliate'
    ];
    public function  product()
    {
        return $this->hasOne(master_product_item::class, 'Product_ID', 'Product_ID');
    }

}
