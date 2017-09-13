<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     	$roles = array(
        	['code' => 'admin', 'description' => 'Administrador'],
        	['code' => 'user', 'description' => 'Usuario'],
        	['code' => 'provider', 'description' => 'Proveedor'],
        );

        foreach($roles as $role){
        	Role::create($role);
        }
    }
}
