<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perms = array(
        	['code' => 'v_cat_prov', 'description' => 'providers'],
        	['code' => 'v_cat_usr', 'description' => 'users'],
        	['code' => 'v_cat_oc', 'description' => 'oc'],
        	['code' => 'v_adm_usr', 'description' => 'users'],
        	['code' => 'a_prov_add', 'description' => 'providersadd'],
        	['code' => 'a_prov_edit', 'description' => 'providersedit'],
        	['code' => 'a_prov_del', 'description' => 'providersdel'],
        	['code' => 'a_usr_add', 'description' => 'userscreate'],
        	['code' => 'a_usr_edit', 'description' => 'usersedit'],
        	['code' => 'a_usr_del', 'description' => 'usersdel'],
            ['code' => 'a_add_oc', 'description' => 'providersadd-oc'],
            ['code' => 'a_edit_oc', 'description' => 'providersedit-oc'],
            ['code' => 'a_del_oc', 'description' => 'providersdel-oc'],
        );

        foreach($perms as $perm){
        	Permission::create($perm);
        }
    }
}
