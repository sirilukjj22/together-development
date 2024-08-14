<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class document_receipt extends Model
{
    use HasFactory;

    protected $table = 'document_receipt';
    protected $fillable = [
        'receipt_ID',
        'Quotation_ID',
        'Nettotal',
        'Refler_ID',
        'deposit',
        'company',
        'sequence_re',
    ];

    public function company00()
    {
        return $this->hasOne(companys::class, 'Profile_ID', 'company');
    }
    public function sequence00()
    {
        return $this->hasOne(document_invoice::class, 'sequence_re','sequence_re');
    }
}
