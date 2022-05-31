<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_group')->insert([
            [
                'title' => 'Superadmin',
                'comment' => 'Access over every options',
                'status' => 'Active'
            ],
            [
                'title' => 'Admin',
                'comment' => 'Access Full Option Without Few',
                'status' => 'Active'
            ],
            [
                'title' => 'Reseller',
                'comment' => 'Reseller',
                'status' => 'Active'
            ],
            [
                'title' => 'Customer',
                'comment' => 'Customer',
                'status' => 'Active'
            ]
        ]);
    }
}
