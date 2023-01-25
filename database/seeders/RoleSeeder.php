<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Create roles
         */
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        /*
         * Create permissions
         */
        $createTicketPermission = Permission::create(['name' => 'create ticket']);
        $editTicketPermission = Permission::create(['name' => 'edit ticket']);
        $deleteTicketPermission = Permission::create(['name' => 'delete ticket']);

        /*
         * Assign permissions to roles
         */
        $adminRole->givePermissionTo($createTicketPermission);
        $adminRole->givePermissionTo($editTicketPermission);
        $adminRole->givePermissionTo($deleteTicketPermission);

        $userRole->givePermissionTo($createTicketPermission);

        /*
         * Assign roles to users
         */
        $superAdminUser = User::where('name', 'Super admin')->first();
        $superAdminUser->assignRole($superAdminRole);

        $adminUser = User::where('name', 'Admin')->first();
        $adminUser->assignRole($adminRole);

        $normalUser = User::where('name', 'User')->first();
        $normalUser->assignRole($userRole);
    }
}
