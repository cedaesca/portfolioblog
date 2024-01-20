<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminAccount = config('app.admin_account');

        User::factory()->create([
            'name' => $adminAccount['name'],
            'email' => $adminAccount['email'],
            'password' => Hash::make($adminAccount['password'])
        ]);
    }
}
