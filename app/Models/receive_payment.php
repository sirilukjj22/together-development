<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class receive_payment extends Model
{
    use HasFactory;
    protected $table = 'document_receive';
    protected $fillable = [
        'Invoice_ID',
        'Receipt_ID',
        'Quotation_ID',
        'payment_date',
        'company',
        'Amount',
        'Remark',
        'Bank',
        'sequence_re',
        'total',
        'Cheque',
        'Credit',
        'Expire',
        'document_status',
        'balance',
    ];
    public function  guest()
    {
        return $this->hasOne(Guest::class, 'Profile_ID', 'company');
    }
    public function company()
    {
        return $this->hasOne(companys::class, 'Profile_ID', 'company');
    }
    public function  guest_tax()
    {
        return $this->hasOne(guest_tax::class, 'GuestTax_ID', 'company');
    }
    public function  company_tax()
    {
        return $this->hasOne(company_tax::class, 'ComTax_ID', 'company');
    }
    public function  userOperated()
    {
        return $this->hasOne(User::class, 'id','Operated_by');
    }
}
