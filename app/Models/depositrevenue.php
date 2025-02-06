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
}
