<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Admin;
use App\Models\Advisor;
use App\Models\Department;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->count(3)
            ->state(new Sequence(
                [
                    'username' => 'admin user',
                    'email' => 'admin@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'admin',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'advisor user',
                    'email' => 'advisor@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'advisor',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'student user',
                    'email' => 'student@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'student',
                    'remember_token' => Str::random(10),
                ]
            ))
            ->create();

        Department::factory()
            ->count(3)
            ->state(new Sequence(
                [
                    'name' => 'RPL',
                ],
                [
                    'name' => 'DPIB',
                ],
                [
                    'name' => 'K3R',
                ],
            ))
            ->create();

        Admin::factory()
            ->state(new Sequence(
                [
                    'user_id' => '1',
                    'name' => fake()->name(),
                    'phone_num' => fake()->phoneNumber(),
                ],
            ))
            ->create();

        Advisor::factory()
            ->state(new Sequence(
                [
                    'user_id' => '2',
                    'name' => fake()->name(),
                    'nip' => '1234567890123456',
                    'department_id' => '1',
                    'phone_num' => fake()->phoneNumber(),
                ],
            ))
            ->create();

        Student::factory()
            ->state(new Sequence(
                [
                    'user_id' => '3',
                    'name' => fake()->name(),
                    'nisn' => '1234567899',
                    'gender' => 'men',
                    'department_id' => '1',
                    'year' => '2024',
                    'phone_num' => fake()->phoneNumber(),
                ],
            ))
            ->create();
        // \App\Models\User::factory(1)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
