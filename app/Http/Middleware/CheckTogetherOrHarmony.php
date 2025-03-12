<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckTogetherOrHarmony
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $check = User::where('id', auth()->user()->id)->first();

        if (!empty($check) && $check->current_branch == 1 || !empty($check) && $check->current_branch == 2) {
            return $next($request);
        }

        // หยุดการทำงานและแสดงหน้า error 403 (Forbidden)
        return abort(403, 'Permission Denied');
    }
}
