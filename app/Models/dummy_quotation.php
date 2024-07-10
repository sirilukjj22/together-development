<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dummy_quotation extends Model
{
    use HasFactory;
    protected $table = 'dummy_quotation';
    protected $fillable = [
        'DummyNo',
        'Company_ID',
        'valid',
        'checkin',
        'checkout',
        'day',
        'night',
        'adult',
        'children',
        'ComRateCode',
        'freelanceraiffiliate',
        'commissionratecode',
        'SpecialDiscount',
        'eventformat',
        'vat_type',
        'comment',
        'issue_date',
        'Expirationdate',
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
        return $this->hasOne(User::class, 'id', 'Document_issuer');
    }
    public function  userOperated()
    {
        return $this->hasOne(User::class, 'id','Operated_by');
    }
    public function  userConfirm()
    {
        return $this->hasOne(User::class, 'id','Confirm_by');
    }
    public function  company2()
    {
        return $this->hasOne(companys::class, 'Profile_ID', 'Company_ID');
    }
    public function  document()
    {
        return $this->hasMany(document_quotation::class, 'Quotation_ID', 'DummyNo');
    }
    public function  contact2()
    {
        return $this->hasOne(representative::class, 'id', 'company_contact');
    }
}
