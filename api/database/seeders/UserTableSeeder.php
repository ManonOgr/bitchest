<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create user
        User::factory()->count(5)->create();
        // create Admin
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'email' => 'admin@admin.com',
            'status' => 'admin',
            'password' => Hash::make('Admin'),
        ]);

        // Create user quentin
        DB::table('users')->insert([
            'first_name' => 'Quentin',
            'last_name' => 'Leroy',
            'email' => 'quentinleroy@example.com',
            'status' => 'client',
            'password' => Hash::make('QuentinL'),
        ]);
    }
}
