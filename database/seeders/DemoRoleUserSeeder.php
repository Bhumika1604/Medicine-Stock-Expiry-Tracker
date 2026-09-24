<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Additive seeder — creates one demo Pharmacist and one demo Staff account
 * alongside the existing AdminUserSeeder, so role-based access can be
 * demonstrated out of the box. Does not modify AdminUserSeeder's admin user.
 */
class DemoRoleUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'pharmacist@meditrack.test'],
            [
                'name' => 'Priya Sharma',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PHARMACIST,
                'is_active' => true,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'staff@meditrack.test'],
            [
                'name' => 'Rahul Verma',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => true,
            ]
        );
    }
}
