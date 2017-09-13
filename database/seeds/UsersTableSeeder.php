<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
        	['member_id'=>'668', 'role_id'=>'1', 'username' => 'admin', 'email' => 'admin@noemail.com', 'password' => 'secret'],
        	/*['name' => 'Luke Skywalker', 'kind'=>'u', 'username' => 'luke2@gmail.com', 'email' => 'luke2@gmail.com', 'password' => 'secret'],
        	['name' => 'Luke Skywalker', 'kind'=>'u', 'username' => 'luke3@gmail.com', 'email' => 'luke3@gmail.com', 'password' => 'secret'],
        	['name' => 'Luke Skywalker', 'kind'=>'u', 'username' => 'luke4@gmail.com', 'email' => 'luke4@gmail.com', 'password' => 'secret'],*/
        );

        foreach($users as $user){
        	User::create($user);
        }
    }
}