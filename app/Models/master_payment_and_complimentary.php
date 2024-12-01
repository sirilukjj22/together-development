<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_payment_and_complimentary extends Model
{
    use HasFactory;
    protected $table = 'master_payment_and_complimentary';
    protected $fillable = [
        'name',
        'percent',
        'status',
    ];
}
