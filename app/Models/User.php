<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $connection = 'mysql';
    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'tel',
        'signature',
        'email',
        'password',
        'discount',
        'additional_discount',
        'status',
        'permission',
        'permission_edit',
        'edit_close_day'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function permissionName()
    {
        return $this->hasOne(TB_departments::class, 'id', 'permission');
    }

    public function rolePermissionData($user_id)
    {
        $check = User::where('id', $user_id)->first();

        $permission = !empty($check) ? $check->permission_edit : 0;

        return $permission;
    }
    public function rolePermission($user_id)
    {
        $check = User::where('id', $user_id)->first();

        $permission = !empty($check) ? $check->permission: 0;

        return $permission;
    }
    public function roleMenu()
    {
        return $this->hasOne(Role_permission_menu::class, 'user_id', 'id');
    }

    public function roleMenuSub($user_id, $menu) // Menu Product Item, Report
    {
        $check = Role_permission_menu_sub::where('user_id', $user_id)->where('menu_name', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = 1;
        }
        return $permission;
    }

    public function roleRevenues()
    {
        return $this->hasOne(Role_permission_revenue::class, 'user_id', 'id');
    }

    // public function roleMenage()
    // {
    //     return $this->hasOne(Role_permission_menu_sub::class, 'user_id', 'id');
    // }

    public static function roleMenuAdd($menu, $user_id)
    {
        $check = Role_permission_menu_sub::where('user_id', $user_id)->where('menu_name', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->add_data;
        }
        return $permission;
    }

    public static function roleMenuEdit($menu, $user_id)
    {
        $check = Role_permission_menu_sub::where('user_id', $user_id)->where('menu_name', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->edit_data;
        }
        return $permission;
    }

    public static function roleMenuDelete($menu, $user_id)
    {
        $check = Role_permission_menu_sub::where('user_id', $user_id)->where('menu_name', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->delete_data;
        }
        return $permission;
    }

    public static function roleMenuView($menu, $user_id)
    {
        $check = Role_permission_menu_sub::where('user_id', $user_id)->where('menu_name', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->view_data;
        }
        return $permission;
    }

    public static function roleMenuDiscount($menu, $user_id)
    {
        $check = Role_permission_menu_sub::where('user_id', $user_id)->where('menu_name', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->discount;
        }
        return $permission;
    }

    public static function roleMenuSpecialDiscount($menu, $user_id)
    {
        $check = Role_permission_menu_sub::where('user_id', $user_id)->where('menu_name', $menu)->first();

        $permission = 0;

        if (!empty($check)) {
            $permission = $check->special_discount;
        }
        return $permission;
    }
}
