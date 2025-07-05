<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessControlModuleSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data untuk module_id = 1 (Open Kaizen)
        DB::table('access_control_modules')->insert([
            'module_id' => 1,
            'menu_name' => 'Open Kaizen',
            'submenu_name' => 'Issues List',
            'url' => '/default/issues/listissues/',
        ]);

        // Menambahkan data untuk module_id = 2 (Closed Kaizen)
        DB::table('access_control_modules')->insert([
            'module_id' => 2,
            'menu_name' => 'Closed Kaizen',
            'submenu_name' => 'Solved Issues',
            'url' => '/default/issue/solvedissues/',
        ]);

        // Menambahkan data untuk module_id = 4 (Business Intelligence)
        DB::table('access_control_modules')->insert([
            'module_id' => 4,
            'menu_name' => 'Business Intelligence',
            'submenu_name' => '-',
            'url' => '/default/index/bidashboard',
        ]);
    }
}
