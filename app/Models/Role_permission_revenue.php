<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_permission_revenue extends Model
{
    use HasFactory;

    protected $table = 'role_permission_revenue';
    protected $fillable = [
        'user_id',
        'front_desk',
        'guest_deposit',
        'all_outlet',
        'agoda',
        'credit_card_hotel',
        'elexa',
        'no_category',
        'water_park',
        'credit_water_park',
        'transfer',
        'time',
        'split',
        'edit',
        'select_revenue_all',
    ];
}
