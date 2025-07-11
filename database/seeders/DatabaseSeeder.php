<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $this->call([
                UsersTableSeeder::class,
        ]);
        
        /*$this->call([
                AccessControlModuleSeeder::class,
        ]);
        
        $this->call([
                AccessControlSeeder::class,
        ]);
        
        $this->call([
                RoleTableSeeder::class,
        ]);*/
    }
}
