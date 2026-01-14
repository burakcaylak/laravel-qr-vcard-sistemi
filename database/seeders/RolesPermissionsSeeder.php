<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abilities = [
            'read',
            'write',
            'create',
        ];

        $permissions_by_role = [
            'superadmin' => [
                'user management',
                'content management',
                'financial management',
                'reporting',
                'payroll',
                'disputes management',
                'api controls',
                'database management',
                'repository management',
                'file-management',
                'qr-code',
                'media-library',
                'settings',
            ],
            'editor' => [
                'file-management',
                'qr-code',
                'media-library',
            ],
        ];

        foreach ($permissions_by_role['superadmin'] as $permission) {
            foreach ($abilities as $ability) {
                $permissionName = $ability . ' ' . $permission;
                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                }
            }
        }
        
        foreach ($permissions_by_role['editor'] as $permission) {
            foreach ($abilities as $ability) {
                $permissionName = $ability . ' ' . $permission;
                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create(['name' => $permissionName]);
                }
            }
        }

        foreach ($permissions_by_role as $role => $permissions) {
            $full_permissions_list = [];
            foreach ($abilities as $ability) {
                foreach ($permissions as $permission) {
                    $full_permissions_list[] = $ability . ' ' . $permission;
                }
            }
            $roleModel = Role::firstOrCreate(['name' => $role]);
            $roleModel->syncPermissions($full_permissions_list);
        }

        if (User::find(1)) {
            User::find(1)->assignRole('superadmin');
        }
        if (User::find(2)) {
            User::find(2)->assignRole('editor');
        }
    }
}

