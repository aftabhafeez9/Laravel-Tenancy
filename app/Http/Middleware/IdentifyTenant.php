<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    	 if ($user = auth()->user()) {
            $tenant = tenancy()->find($user->email); // Finding tenant by email

            if ($tenant) {
                tenancy()->initialize($tenant); // Switches database
            }
        }
        
        return $next($request);
    }
}
