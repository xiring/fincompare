<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Src\Auth\Domain\Entities\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Basic roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $consumer = Role::firstOrCreate(['name' => 'consumer']);

        // Example permissions
        foreach (['manage partners','manage categories','manage attributes','manage products','manage leads'] as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $admin->givePermissionTo(Permission::all());

        // Assign admin role to default admin if exists
        if ($user = User::where('email','admin@example.com')->first()) {
            $user->assignRole('admin');
        }
    }
}


