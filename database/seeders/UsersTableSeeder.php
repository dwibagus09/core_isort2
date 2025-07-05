<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'username' => 'murat',
                'email' => 'mkusuma@isort.id',
                'password' => Hash::make('isort2020'),
                'name' => 'Murat',
                'phone_no' => '081234567890',
                'photo' => null,
                'role_id' => 1,
                'site_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'emma',
                'email' => 'edarmawan@isort.id',
                'password' => Hash::make('isort2020'),
                'name' => 'Emma',
                'phone_no' => '081298765432',
                'photo' => null,
                'role_id' => 1,
                'site_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'bagus',
                'email' => 'bwicaksono@isort.id',
                'password' => Hash::make('isort2020'),
                'name' => 'Bagus',
                'phone_no' => '081345678901',
                'photo' => null,
                'role_id' => 1,
                'site_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}

?>