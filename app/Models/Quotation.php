<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $table = 'quotation';
    protected $fillable = [
        'Quotation_ID',
        'Company_ID',
        'valid',
        'check-in',
        'check-out',
        'day',
        'night',
        'adult',
        'children',
        'max-discount',
        'ComRateCode',
        'freelancer-aiffiliate',
        'commission-rate-code',
        'event-format',
        'vat-type',
    ];
}
