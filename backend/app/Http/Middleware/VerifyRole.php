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
        $login = array(
            'login',
            'login.post',
            'forget',
            'forget.post',
            'resetpassword',
            'resetpassword.post',
            'visit'
        );
        $white_list = array(
            'banner.store',
            'banner.update',
            'banner.datatable',
            'banner.status',
            'user.datatable',
            'user.export',
            'user.updatestatus',
            'dashboard.getheader',
            'dashboard.getdataproduct',
            'dashboard.getbestseller',
            'dashboard.getmostviewer',
            'dashboard.getsalesdata',
            'dashboard.getbuyer',
            'getimage',
            'business.datatable',
            'business.request.datatable',
            'business.store',
            'business.update',
            'business.delete',
            'business.publish',
            'business.unpublish',
            'business.upload',
            'treasure_arise.datatable',
            'treasure_arise.request.datatable',
            'treasure_arise.store',
            'treasure_arise.update',
            'treasure_arise.delete',
            'treasure_arise.publish',
            'treasure_arise.unpublish',
            'treasure_arise.upload',
            'category.datatable',
            'category.store',
            'category.update',
            'category.status',
            'role.store',
            'role.update',
            'permission.update',
            'admin.store',
            'admin.update',
            'role.datatable',
            'admin.datatable',
            'logout',
            'help',
            'banner.delete-image',
            'contact_submissions.list',
            'contact_submissions.datatable',
            'dashboard.impact',
            'dashboard.members',
            'dashboard.business',
            'business.statistics',
            'contact_submissions.delete',
        );
        if(session('admin')) {
            if(!check_permission($request->route()->getName()) && !in_array($request->route()->getName(), $white_list) && !in_array($request->route()->getName(), $login)) {
                return redirect()->route('login');
            }
        }else {
            if(!in_array($request->route()->getName(), $login)) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
