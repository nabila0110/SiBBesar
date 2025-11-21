<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Akuntan',
            'email' => 'akuntan@mfk.com',
            'password' => bcrypt('bismillah'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::create([
            'name' => 'Manager',
            'email' => 'manager@mfk.com',
            'password' => bcrypt('alhamdulillah'),
            'role' => 'user',
        ]);
    }
}
