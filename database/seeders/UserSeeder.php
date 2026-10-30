<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Akun awal. Email admin mengikuti test case LOG01 pada proposal.
     */
    private const ACCOUNTS = [
        [
            'email' => 'admin@email.com',
            'name' => 'Administrator',
            'role' => User::ROLE_ADMIN,
        ],
        [
            'email' => 'asesor@email.com',
            'name' => 'Asesor',
            'role' => User::ROLE_ASESOR,
        ],
    ];

    public function run(): void
    {
        foreach (self::ACCOUNTS as $account) {
            // Akun yang sudah ada tidak diubah password-nya
            User::firstOrCreate(
                ['email' => $account['email']],
                ['name' => $account['name'], 'role' => $account['role'], 'password' => 'password']
            );
        }
    }
}
