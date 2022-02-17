<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class VerifyRole
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $login = [
            'login',
            'login.post',
            'forget',
            'forget.post',
            'resetpassword',
            'resetpassword.post',
            'visit',
        ];
        $white_list = [
            'banner.store',
            'banner.update',
            'banner.datatable',
            'banner.status',
            'banner.delete-image',

            'role.store',
            'role.update',

            'permission.update',

            'admin.store',
            'admin.update',
            'admin.datatable',

            'role.datatable',
            'logout',
            'help',

            'product.grid',
            'product.datatable',
            'product.store',
            'product.update',
            'product.delete',
            'product.publish',
            'product.unpublish',
            'product.upload',
            'product.sorting',
            'product.status',
            'product.getoption',

            'category.datatable',
            'category.store',
            'category.update',
            'category.status',

            'option.datatable',
            'option.store',
            'option.update',
            'optionset.datatable',
            'optionset.store',
            'optionset.update',

            'capacity.datatable',
            'capacity.store',
            'capacity.update',
        ];
        if (session('admin')) {
            if (! check_permission($request->route()->getName()) && ! in_array($request->route()->getName(),
                    $white_list) && ! in_array($request->route()->getName(), $login)) {
                return redirect()->route('login');
            }
        } else {
            if (! in_array($request->route()->getName(), $login)) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
