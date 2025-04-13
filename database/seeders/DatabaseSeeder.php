<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Admin;
use App\Models\Batch;
use App\Models\Advisor;
use App\Models\AdvisorLevel;
use App\Models\AdvisorPosition;
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
            ->count(6)
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
                ],
                [
                    'username' => 'Teguh Pramono',
                    'email' => 'admin.teguh@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'admin',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'Leo Agung',
                    'email' => 'admin.leo@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'admin',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'Uswatun Khasanah',
                    'email' => 'admin.uswatun@gmail.com',
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'role' => 'admin',
                    'remember_token' => Str::random(10),
                ],
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
            ->count(4)
            ->state(new Sequence(
                [
                    'user_id' => '1',
                    'name' => 'Admin PKL SMKN 1 Pajangan',
                    'phone_num' => '81324132667',
                ],
                [
                    'user_id' => '4',
                    'name' => 'Teguh Pramono, S.Kom',
                    'phone_num' => '81833445566',
                ],
                [
                    'user_id' => '5',
                    'name' => 'Leo Agung Christa Maharddikha, S.Pd.',
                    'phone_num' => '81911223344',
                ],
                [
                    'user_id' => '6',
                    'name' => 'Uswatun Khasanah, S.Pd.Gr.',
                    'phone_num' => '81266778899',
                ],
            ))
            ->create();

        AdvisorPosition::factory()
            ->count(4)
            ->state(new Sequence(
                [
                    'name' => 'Guru Pertama',
                ],
                [
                    'name' => 'Guru Muda',
                ],
                [
                    'name' => 'Guru Madya',
                ],
                [
                    'name' => 'Guru Utama',
                ],
            ))
            ->create();

        AdvisorLevel::factory()
            ->count(17)
            ->state(new Sequence(
                [
                    'name' => 'I/a',
                ],
                [
                    'name' => 'I/b',
                ],
                [
                    'name' => 'I/c',
                ],
                [
                    'name' => 'I/d',
                ],
                [
                    'name' => 'II/a',
                ],
                [
                    'name' => 'II/b',
                ],
                [
                    'name' => 'II/c',
                ],
                [
                    'name' => 'II/d',
                ],
                [
                    'name' => 'III/a',
                ],
                [
                    'name' => 'III/b',
                ],
                [
                    'name' => 'III/c',
                ],
                [
                    'name' => 'III/d',
                ],
                [
                    'name' => 'IV/a',
                ],
                [
                    'name' => 'IV/b',
                ],
                [
                    'name' => 'IV/c',
                ],
                [
                    'name' => 'IV/d',
                ],
                [
                    'name' => 'IV/e',
                ],
            ))
            ->create();

        Advisor::factory()
            ->state(new Sequence(
                [
                    'user_id' => '2',
                    'name' => 'Drs. Endah Setyowati',
                    'nip' => '198812282002122003',
                    'position_id' => '3',
                    'level_id' => '14',
                    'department_id' => '1',
                    'phone_num' => 81909098970,
                ],
            ))
            ->create();

        Student::factory()
            ->state(new Sequence(
                [
                    'user_id' => '3',
                    'name' => 'Ima Nur Chasanah',
                    'nisn' => '1234567899',
                    'nis' => '1112',
                    'gender' => 'men',
                    'department_id' => '1',
                    'year' => '2024',
                    'phone_num' => 81243522132,
                ],
            ))
            ->create();

        Industry::factory()
            ->count(3)
            ->state(new Sequence(
                [
                    'name' => 'PT Maleo Edukasi',
                    'address' => 'Tangerang',
                    'email' => 'maleo@gmail.com',
                    'phone_num' => '85123456789',
                    'leader_name' => 'Dion Mulya',
                    'status' => '1',
                ],
                [
                    'name' => 'PT Sineas Media',
                    'address' => 'Jl. Abu Bakar Ali, Yogyakarta',
                    'email' => 'sineas@gmail.com',
                    'phone_num' => '85188856789',
                    'leader_name' => 'Dodit Mulyo',
                    'status' => '0',
                ],
                [
                    'name' => 'PT Edutalk',
                    'address' => 'Jl. Parangtritis no 3, Bantul, DIY',
                    'email' => 'edutalk@gmail.com',
                    'phone_num' => '85123499089',
                    'leader_name' => 'Putri Delia',
                    'status' => '2',
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
                'principal_signature' => 'Sutapa, S.Pd.jpeg',
                'school_stamp' => 'Cap.jpg',
                'internship_team_decree' => 'Surat Keputusan Kepala SMK Negeri 1 Pajangan Nomor : 400.3.8.10/259 tentang Pembentukan Tim Pokja Praktik Kerja Lapangan, tanggal 10 Juni 2024'
            ]))->create();



        // \App\Models\User::factory(1)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
