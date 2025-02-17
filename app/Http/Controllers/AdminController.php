<?php 
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status !== 'Pending') {
            return response()->json(['message' => 'User already approved']);
        }
        // dd($user->id);

        $tenantDbName = 'tenant_' . str_replace(' ', '', $user->name);
        // dd($tenantDbName);
        // Create Tenant DB
        $tenant = Tenant::create([
            'name' => $user->name,
            'database' => $tenantDbName,
            'user_id' => $user->id,
        ]);
        
        
        // Run migrations on the new tenant database
        config(['database.connections.tenant.database' => $tenantDbName]);

        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--path' => 'database/migrations/tenant',
        ]);
        // Update user status
        $user->update([
            'status' => 'approved',
            'tenant_id' => $tenant->id,
        ]);

        return response()->json(['message' => 'User approved and tenant created']);
    }
}
