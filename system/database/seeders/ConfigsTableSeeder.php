<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('site_configs')->insert([
            'brand_name' => 'Fast Sms Portal',
            'phone' => '01912377506',
            'email' => 'info@fastsmsportal.com',
            'logo' => 'd9e4b25a26b9a85ed5ee9fdf48651432.png',
        ]);
    }
}
