<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kampus.ac.id'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'mahasiswa@kampus.ac.id'],
            [
                'name' => 'Mahasiswa',
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]
        );

        $dosens = [
            [
                'nidn' => '081277238276',
                'nama' => 'Prof. Suyanto, S.Kom., M.Kom.',
                'jabatan' => 'Rektor',
                'kepakaran' => 'Artificial Intelegence',
            ],
            [
                'nidn' => '081276535312',
                'nama' => 'Rahmat Mulyana, S.Kom., M.Kom.',
                'jabatan' => 'Dosen',
                'kepakaran' => 'Sistem Basis Data',
            ],
        ];

        foreach ($dosens as $dosen) {
            Dosen::updateOrCreate(['nidn' => $dosen['nidn']], $dosen);
        }
    }
}
