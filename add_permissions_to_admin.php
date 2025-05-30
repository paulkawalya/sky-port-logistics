<?php

// This script adds the new permissions to the admin user

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Permission;
use App\Models\UserPermission;

// Get admin user
$admin = User::where('user_type', 'Admin')->first();

if (!$admin) {
    echo "No admin user found.\n";
    exit;
}

// Get the new permissions
$permissions = Permission::whereIn('name', [
    'view_finance_dashboard',
    'export_finance_reports',
    'view_reports',
    'generate_reports',
    'export_reports'
])->get();

echo "Found " . $permissions->count() . " permissions to add.\n";

// Add permissions to admin user
foreach ($permissions as $permission) {
    // Check if permission already exists
    $exists = UserPermission::where('user_id', $admin->id)
        ->where('permission_id', $permission->id)
        ->exists();
    
    if (!$exists) {
        UserPermission::create([
            'user_id' => $admin->id,
            'permission_id' => $permission->id
        ]);
        echo "Added permission: " . $permission->name . "\n";
    } else {
        echo "Permission already exists: " . $permission->name . "\n";
    }
}

echo "Done!\n";
