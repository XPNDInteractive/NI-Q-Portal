<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $perm = new Permission();
        $perm->name = "admin";
        $perm->save();

        $perm = new Permission();
        $perm->name = "donor";
        $perm->save();

        $perm = new Permission();
        $perm->name = "manager";
        $perm->save();

        $user = new User();
        $user->name = "jgetner";
        $user->first_name = 'Joshua';
        $user->last_name = "Getner";
        $user->email = 'jgetner@gmail.com';
        $user->password = bcrypt('eclipse1');
        $user->save();
        $user->permissions()->attach(Permission::where('name', 'admin')->first());

    }
}
