<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;


class RoleSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $role = new Role();
        $role->title = 'Админ';
        $role->save();

        $role = new Role();
        $role->title = 'Только просмотр';
        $role->save();

        $role = new Role();
        $role->title = 'Менеджер';
        $role->save();


    }
}
