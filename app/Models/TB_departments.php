<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TB_departments extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'tb_department';
    protected $fillable = [
        'department',
        'close_day',
    ];

    public function roleMenuSelect($menu, $user_id)
    {
        $check = TB_permission_department_menus::where('department_id', $user_id)->where('menu_id', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = 1;
        }
        return $permission;
    }

    public function roleRevenues()
    {
        return $this->hasOne(TB_permission_department_revenues::class, 'department_id', 'id');
    }

    public static function roleMenuAdd($menu, $user_id)
    {
        $check = TB_permission_department_menus::where('department_id', $user_id)->where('menu_id', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->add_data;
        }
        return $permission;
    }

    public static function roleMenuEdit($menu, $user_id)
    {
        $check = TB_permission_department_menus::where('department_id', $user_id)->where('menu_id', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->edit_data;
        }
        return $permission;
    }

    public static function roleMenuDelete($menu, $user_id)
    {
        $check = TB_permission_department_menus::where('department_id', $user_id)->where('menu_id', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->delete_data;
        }
        return $permission;
    }

    public static function roleMenuView($menu, $user_id)
    {
        $check = TB_permission_department_menus::where('department_id', $user_id)->where('menu_id', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->view_data;
        }
        return $permission;
    }

    public static function roleMenuDiscount($menu, $user_id)
    {
        $check = TB_permission_department_menus::where('department_id', $user_id)->where('menu_id', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->discount;
        }
        return $permission;
    }

    public static function roleMenuSpecialDiscount($menu, $user_id)
    {
        $check = TB_permission_department_menus::where('department_id', $user_id)->where('menu_id', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->special_discount;
        }
        return $permission;
    }
}
