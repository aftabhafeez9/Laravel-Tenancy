<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use Illuminate\Support\Facades\Config; 
use DB;
class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'User is not logged in'], 401);
        }

        // Get the tenant ID from the authenticated user
        $tenantId = auth()->user()->tenant_id ?? null;
        // dd(auth()->user()->id);

        $checkTenant = Tenant::where('user_id', auth()->user()->id)->first();
        // dd($checkTenant);
        if ($checkTenant) {
                // Set the tenant database connection
                Config::set('database.connections.tenant.database', $checkTenant->database);
                Config::set('database.default', 'tenant');
                DB::purge('tenant');
                DB::reconnect('tenant');

                // dd([
                //     'tenant_database_after_switch' => config('database.connections.tenant.database'),
                //     'default_database_after_switch' => config('database.default'),
                // ]);

        }else {
                return response()->json(['message' => 'Tenant not found'], 404);
            }

        // Continue to the next middleware/request
        return $next($request);
    }
}
