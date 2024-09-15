<?php

namespace App\Http\Middleware;

use App\Models\Role_permission_menu;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $menu)
    {
        $check = Role_permission_menu::where('user_id', auth()->user()->id)->first();
        if (!empty($check) && $check->$menu == 0) {
            // หยุดการทำงานและแสดงหน้า error 403 (Forbidden)
            abort(403, 'Permission Denied');
        }
    
        return $next($request);
    }
}
