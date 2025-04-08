<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Admin;
use App\Models\Batch;
use App\Models\Advisor;
use App\Models\Student;
use App\Models\Industry;
use App\Models\Department;
use App\Models\SchoolProfile;
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
                    'username' => 'admin',
                    'email' => 'admin@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'admin',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'advisor',
                    'email' => 'advisor@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'advisor',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'student',
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
                    'name' => 'Admin PKL SMKN 1 Pajangan',
                    'phone_num' => '081324132667',
                ],
            ))
            ->create();

        Advisor::factory()
            ->state(new Sequence(
                [
                    'user_id' => '2',
                    'name' => 'Drs. Endah Setyowati',
                    'nip' => '198812282002122003',
                    'department_id' => '1',
                    'phone_num' => fake()->phoneNumber(),
                ],
            ))
            ->create();

        Student::factory()
            ->state(new Sequence(
                [
                    'user_id' => '3',
                    'name' => 'Ima Nur Chasanah',
                    'nisn' => '1234567899',
                    'nis' => '1224',
                    'gender' => 'men',
                    'department_id' => '1',
                    'year' => '2024',
                    'phone_num' => fake()->phoneNumber(),
                ],
            ))
            ->create();

        Industry::factory()
            ->state(new Sequence(
                [
                    'name' => 'PT Maleo Edukasi',
                    'address' => 'Tangerang',
                    'email' => 'maleo@gmail.com',
                    'phone_num' => '085123456789',
                    'leader_name' => 'Dion Mulya',
                ],
            ))
            ->create();

        Batch::factory()
            ->state(new Sequence(
                [
                    'name' => 'Batch 1',
                    'year' => '2024',
                    'status' => '1',
                ],
            ))
            ->create();

        SchoolProfile::factory()
            ->state(new Sequence([
                'name' => 'SMK Negeri 1 Pajangan',
                'address' => 'Pajangan, Triwidadi, Pajangan, Bantul, DIY',
                'email' => 'smknpajanganbantul@gmail.com',
                'phone_num' => '08125967356',
                'website' => 'www.smkn1pajangan.sch.id',
                'principal_name' => 'Sutapa, S.Pd.',
                'principal_nip' => '00000000000',
                'principal_signature' => 'ttd',
                'school_stamp' => 'stamp',
                'internship_team_decree' => '400.3.8.10/260'
            ]))->create();



        // \App\Models\User::factory(1)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
