<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            'books.view', 'books.create', 'books.update', 'books.delete',
            'members.view', 'members.create', 'members.update', 'members.delete',
            'loans.create', 'loans.return', 'reports.view', 'settings.manage',
        ];

        foreach($permissions as $permissionName){
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $roles = [
            'Admin' => [
                'books.view','books.create','books.update','books.delete',
                'members.view','members.create','members.update','members.delete',
                'loans.create','loans.return','reports.view','settings.manage',
            ],
            'Librarian' => [
                'books.view','books.create','books.update',
                'members.view','members.create','members.update',
                'loans.create','loans.return','reports.view',
            ],
            'Member' => [
                'books.view','reports.view',
            ],
        ];

        foreach($roles as $roleName => $rolePermissions){
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}


