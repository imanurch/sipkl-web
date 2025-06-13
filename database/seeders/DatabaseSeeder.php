<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Admin;
use App\Models\Advisor;
use App\Models\Student;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Models\AdvisorLevel;
use App\Models\SchoolProfile;
use App\Models\AdvisorPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'advisor',
                    'email' => 'advisor@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'advisor',
                    'remember_token' => Str::random(10),
                ],
                [
                    'username' => 'student',
                    'email' => 'student@gmail.com',
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'remember_token' => Str::random(10),
                ],
            ))
            ->create();

        Department::factory()
            ->count(3)
            ->state(new Sequence(
                ['name' => 'RPL'],
                ['name' => 'DPIB'],
                ['name' => 'K3R'],
            ))
            ->create();

        Admin::factory()
            ->state(new Sequence(
                [
                    'user_id' => '1',
                    'name' => 'Admin PKL SMKN 1 Pajangan',
                    'phone_num' => '081324132667',
                ]
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
                    'phone_num' => '081909098970',
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
                    'gender' => 'women',
                    'department_id' => '1',
                    'year' => '2024',
                    'phone_num' => '081243522132',
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
    }
}
