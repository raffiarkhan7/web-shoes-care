<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first()
            ?? User::where('email', 'admin@shaoscare.id')->first()
            ?? new User;
        $admin->fill([
            'name' => 'Admin Shaos Care',
            'email' => 'admin@gmail.com',
            'password' => 'pe 12345678',
            'role' => User::ROLE_ADMIN,
            'phone' => '081200000001',
            'address' => 'Jl. Merdeka No. 1, Jakarta',
            'is_active' => true,
        ])->save();
    }
}
