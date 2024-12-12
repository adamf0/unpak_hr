<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterMetaDescription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Keywords to block (adjust as needed)
        $blockedKeywords = [
            'slot gacor', 'casino', 'judi', 'poker', 'jackpot', 'gambling'
        ];

        // Check if the meta description is present in the request
        $metaDescription = $request->input('meta_description');
        
        if (in_array($metaDescription, $blockedKeywords)) {
            throw new Exception("'Meta description contains forbidden content.'");
        }

        return $next($request);
    }

}
