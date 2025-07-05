<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data Super Admin
        DB::table('roles')->insert([
            'role' => 'Super Admin',
            'category_id' => 0,
        ]);

        // Menambahkan data Admin
        DB::table('roles')->insert([
            'role' => 'Admin',
            'category_id' => 1, // Misalnya kategori admin 1
        ]);
    }
}
?>
