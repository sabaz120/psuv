<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Roles
        $rolesArray=[
            ['name' => 'admin'],
            ['name' => 'operador']
        ];
        foreach ($rolesArray as $role) {
            Role::firstOrCreate(['name' => $role['name']]);
        }
        //rolesArray
    }

}
