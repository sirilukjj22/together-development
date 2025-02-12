<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class depositrevenue extends Model
{
    use HasFactory;
    protected $table = 'deposit_revenue';
    protected $fillable = [
        'Deposit_ID',
        'Quotation_ID',
        'Company_ID',
        'payment',
        'amount',
        'fullname',
    ];
    public function  company()
    {
        return $this->hasOne(companys::class, 'Profile_ID', 'Company_ID');
    }
    public function  guest()
    {
        return $this->hasOne(Guest::class, 'Profile_ID', 'Company_ID');
    }
}
