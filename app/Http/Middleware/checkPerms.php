<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkPerms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$typeRol): Response
    {
        if($request->session()->get('perm')!=null&&$request->session()->get('perm')==$typeRol)
            return $next($request);
        else 
            Abort(403,"Toegang geweigerd");
    }
}
