<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ThrowSessionUI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $dataCheck = ["id","name","level","levelActive","kode_fak",];
        $sessionRegister = array_keys(Session::all());

        if( count(array_intersect($sessionRegister, $dataCheck))>0 ){
            return $next($request);
        }
        return redirect()->route('auth.authorization');
    }
}
