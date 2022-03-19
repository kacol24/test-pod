<?php

namespace App\Http\Middleware;

use App\Models\StoreReferral;
use Closure;
use Illuminate\Http\Request;

class CheckForRefCode
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
        if ($request->has(StoreReferral::REF_SESSION_KEY)) {
            session([StoreReferral::REF_SESSION_KEY => request(StoreReferral::REF_SESSION_KEY)]);

            return redirect()->to($request->fullUrlWithoutQuery(StoreReferral::REF_SESSION_KEY));
        }

        return $next($request);
    }
}
