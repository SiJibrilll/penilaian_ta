<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        $users = [
            ['id' => 1, 'name' => 'Dosen A', 'password' => Hash::make('password')],
            ['id' => 2, 'name' => 'Dosen B', 'password' => Hash::make('password')],
            ['id' => 3, 'name' => 'Mahasiswa A', 'password' => Hash::make('password')],
            ['id' => 4, 'name' => 'Mahasiswa B', 'password' => Hash::make('password')],
        ];
        DB::table('users')->insert($users);

        // Dosen profiles
        $dosenProfiles = [
            ['id' => 1,  'nidn' => '1234567890', 'role' => 'PEMBIMBING', 'user_id' => 1],
            ['id' => 2,  'nidn' => '0987654321', 'role' => 'PENGAMPU', 'user_id' => 2],
        ];
        DB::table('dosen_profiles')->insert($dosenProfiles);

        // Mahasiswa profiles
        $mahasiswaProfiles = [
            ['id' => 1,  'nim' => '2201001', 'user_id' => 3],
            ['id' => 2,  'nim' => '2201002', 'user_id' => 4],
        ];
        DB::table('mahasiswa_profiles')->insert($mahasiswaProfiles);

        // Projects
        $projects = [
            ['id' => 1, 'user_id' => 3, 'finalized' => false],
            ['id' => 2, 'user_id' => 4, 'finalized' => false],
        ];
        DB::table('projects')->insert($projects);

        // Grade Types
        $gradeTypes = [
            ['id' => 1, 'name' => 'Kerapihan', 'percentage' => 30],
            ['id' => 2, 'name' => 'Estetika', 'percentage' => 30],
            ['id' => 3, 'name' => 'Efisiensi', 'percentage' => 40],
        ];
        DB::table('grade_types')->insert($gradeTypes);

        // Grade Parameters (example ranges)
        $gradeParameters = [
            ['id' => 1, 'name' => 'Excellent', 'min' => 85],
            ['id' => 2, 'name' => 'Good', 'min' => 70],
            ['id' => 3, 'name' => 'Fair', 'min' => 55],
            ['id' => 4, 'name' => 'Poor', 'min' => 40],
        ];
        DB::table('grade_parameters')->insert($gradeParameters);
    }
}
