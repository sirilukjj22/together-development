<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proposal_overbill extends Model
{
    use HasFactory;
    protected $table = 'proposal_overbill';
    protected $fillable = [
        'Overbill_ID',
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
        'AddTax',
        'Nettotal',
        'total',
        'comment',
        'Document_issuer',
        'Operated_by',
        'Confirm',
        'Confirm_by',
    ];
    public function  company()
    {
        return $this->hasOne(companys::class, 'Profile_ID', 'Company_ID');
    }
    public function  contact()
    {
        return $this->hasOne(representative::class, 'Company_ID', 'Company_ID');
    }
    public function  freelancer()
    {
        return $this->hasOne(Freelancer_Member::class, 'Profile_ID', 'freelanceraiffiliate');
    }
    public function  user()
    {
        return $this->hasOne(User::class, 'id', 'Operated_by');
    }
    public function  userOperated()
    {
        return $this->hasOne(User::class, 'id','Operated_by');
    }
    public function  userConfirm()
    {
        return $this->hasOne(User::class, 'id','Confirm_by');
    }
    public function  guest()
    {
        return $this->hasOne(Guest::class, 'Profile_ID', 'Company_ID');
    }

    public function  document()
    {
        return $this->hasMany(document_quotation::class, 'Quotation_ID', 'DummyNo');
    }
    public function  contact2()
    {
        return $this->hasOne(representative::class, 'Company_ID', 'Company_ID')
                ->where('status', 1);
    }
}
