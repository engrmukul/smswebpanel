<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigsTableSeeder::class);
        $this->call(UserGroupTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(MenusTableSeeder::class);
    }
}
