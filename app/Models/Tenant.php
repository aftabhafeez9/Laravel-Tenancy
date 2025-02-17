<?php 

namespace App\Models;

use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    protected $fillable = ['name', 'database','user_id'];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($tenant) {
            \DB::statement("CREATE DATABASE {$tenant->database}");
        });
    }
}
