<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_permission_menu extends Model
{
    use HasFactory;

    protected $table = 'role_permission_menu';
    protected $fillable = [
        'user_id',
        'sms_alert',
        'revenue',
        'debtor',
        'agoda',
        'elexa',
        'profile',
        'company',
        'guest',
        'user',
        'bank',
        'select_menu_all',
    ];
}
