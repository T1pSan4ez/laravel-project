<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'root',
            'email' => 'root@root.root',
            'password' => Hash::make('rootroot'),
            'avatar' => 'admin-avatar.png',
            'role' => true,
            'user_type' => 'super_admin',
        ]);
    }
}
