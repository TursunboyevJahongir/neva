<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = Role::create(['name' => 'superadmin']);
        $admin = Role::create(['name' => 'admin']);
        $customer = Role::create(['name' => "customer"]);
        $seller = Role::create(['name' => "seller"]);//sotuvchi

        $emCreate = Permission::create(['name' => 'create seller']);
        $emRead = Permission::create(['name' => 'read seller']);
        $emUpdate = Permission::create(['name' => 'update seller']);
        $emDel = Permission::create(['name' => 'delete seller']);

        Permission::create(['name' => 'create insurance']);//sug'urtaga bog'liq malumotlar
        Permission::create(['name' => 'read insurance']);

        Permission::create(['name' => 'create customer']);
        Permission::create(['name' => 'read customer']);
        Permission::create(['name' => 'update customer']);
        Permission::create(['name' => 'delete customer']);

        Permission::create(['name' => 'create purpose']);
        Permission::create(['name' => 'read purpose']);
        Permission::create(['name' => 'update purpose']);
        Permission::create(['name' => 'delete purpose']);

        Permission::create(['name' => 'blocked users']);

        Permission::create(['name' => 'send sms']);

        $admin->syncPermissions(Permission::all());
        //#############################################################################################
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'read role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'super_admin']);

        $superadmin->syncPermissions(Permission::all());

        $user = User::query()->where('id', 1)->get()->first();
        $user->assignRole($superadmin);
        $user->save();
    }
}
