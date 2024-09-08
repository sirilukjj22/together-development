<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TB_permission_department_menus extends Model
{
    use HasFactory;

    protected $table = 'tb_permission_department_menu';
    protected $fillable = [
        'department_id',
        'menu_id',
        'add_data',
        'edit_data',
        'delete_data',
        'view_data',
        'discount',
        'special_discount',
        'created_at',
        'updated_at'
    ];
}
