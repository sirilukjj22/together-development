<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document_invoices extends Model
{
    use HasFactory;
    protected $table = 'document_invoice';
    protected $fillable = [
        'Invoice_ID',
        'Quotation_ID',
        'company',
        'Refler_ID',
        'sequence_re',
        'paymentPercent',
    ];
    public function company00()
    {
        return $this->hasOne(companys::class, 'Profile_ID', 'company');
    }
    public function sequence00()
    {
        return $this->hasOne(document_receipt::class, 'sequence_re','sequence_re');
    }
    public function  guest()
    {
        return $this->hasOne(Guest::class, 'Profile_ID', 'company');
    }
}
