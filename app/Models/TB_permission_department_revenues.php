<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TB_permission_department_revenues extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'tb_permission_department_revenue';
    protected $fillable = [
        'department_id',
        'front_desk',
        'guest_deposit',
        'all_outlet',
        'agoda',
        'credit_card_hotel',
        'elexa',
        'no_category',
        'water_park',
        'credit_water_park',
        'other_revenue',
        'transfer',
        'time',
        'split',
        'edit',
        'select_revenue_all',
        'created_at',
        'updated_at'
    ];
}
