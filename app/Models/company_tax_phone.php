<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company_tax_phone extends Model
{
    use HasFactory;
    protected $table = 'company_tax_phone';
    protected $fillable = [
        'ComTax_ID',
        'Phone_number',
        'sequence',
    ];
}
