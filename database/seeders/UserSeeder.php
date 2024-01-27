<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = 'Test123@';
        $user = [
            [
                'id' => (string) Str::uuid(),
                'name' => 'rifki mahsyaf',
                'email' => 'rifkimahsyaf@gmail.com',
                'password' => Hash::make($password),
                'is_active' => 1,
                'email_verified_at' => Carbon::now()
            ],
        ];

        User::insert($user);
    }
}
