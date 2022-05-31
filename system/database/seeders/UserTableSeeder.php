<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([
            'id_user_group' => 1,
            'name' => 'ADMIN',
            'username'  => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'address' => 'Nakla',
            'mobile' => '01712377506',
            'status' => 'ACTIVE'
        ]);
    }
}
