<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $user = new User();
        $user->first_name = 'admin';
        $user->last_name = '';
        $user->role_id = '1';
        $user->email = 'sagSUAdmin@gmail.com';
        $user->password = bcrypt('qwerty123');
        $user->avatar = 'default_avatar.png';
        $user->save();

        $user = new User();
        $user->first_name = 'admin1';
        $user->last_name = '';
        $user->role_id = '3';
        $user->email = 'sagSUUser1@gmail.com';
        $user->password = bcrypt('qwerty123');
        $user->avatar = 'default_avatar.png';
        $user->save();


    }
}
