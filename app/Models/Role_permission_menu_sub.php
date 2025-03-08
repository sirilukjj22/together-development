<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role_permission_menu_sub extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'role_permission_menu_sub';
    protected $fillable = [
        'user_id',
        'menu_name',
        'add_data',
        'edit_data',
        'delete_data',
        'view_data',
        'discount',
        'special_discount',
    ];
}
