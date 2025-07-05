<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessControlSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data ke tabel access_control
        DB::table('access_control')->insert([
            ['access_control_id' => 50, 'role_id' => 21, 'module_id' => 4, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 49, 'role_id' => 21, 'module_id' => 2, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 51, 'role_id' => 22, 'module_id' => 1, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 48, 'role_id' => 21, 'module_id' => 1, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 53, 'role_id' => 22, 'module_id' => 4, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 52, 'role_id' => 22, 'module_id' => 2, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 61, 'role_id' => 2, 'module_id' => 4, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 60, 'role_id' => 2, 'module_id' => 2, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 59, 'role_id' => 2, 'module_id' => 1, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 62, 'role_id' => 40, 'module_id' => 1, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 63, 'role_id' => 40, 'module_id' => 2, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 64, 'role_id' => 40, 'module_id' => 4, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 65, 'role_id' => 1, 'module_id' => 1, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 66, 'role_id' => 1, 'module_id' => 2, 'read_access' => 1, 'write_access' => 1],
            ['access_control_id' => 67, 'role_id' => 1, 'module_id' => 4, 'read_access' => 1, 'write_access' => 1],
        ]);
    }
}
