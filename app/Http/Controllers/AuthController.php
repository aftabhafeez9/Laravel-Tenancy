<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    public function tenantLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user in main database
        $user = User::where('email', $request->email)->first();

        if (!$user || !Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Get tenant details
        $tenantDatabase = 'tenant_' . $user->name;

        // Switch to tenant database
        // dd($tenantDatabase);
        $this->switchToTenantDatabase($tenantDatabase);

        // Generate API token
        $token = $user->createToken('tenant-api-token')->plainTextToken;

        return response()->json([
            'message' => 'Tenant signed in successfully',
            'token'   => $token,
        ]);
    }

    private function switchToTenantDatabase($databaseName)
    {
        Config::set("database.connections.tenant.database", $databaseName);

        // Purge and reconnect to the new tenant database
        DB::purge('tenant');
        DB::reconnect('tenant');

        // Set the default connection for this request
        DB::setDefaultConnection('tenant');
    }
}
