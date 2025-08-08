<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        Role::create(['name' => 'employer']);

        // Optional: Dummy users
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('admin');

        $user = User::factory()->create([
            'name' => 'Job Seeker',
            'email' => 'user@example.com',
        ]);
        $user->assignRole('user');

        $employer = User::factory()->create([
            'name' => 'Company HR',
            'email' => 'employer@example.com',
        ]);
        $employer->assignRole('employer');
    }
}
