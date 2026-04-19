<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('kecamatan')->insert([
            ['nama_kecamatan' => 'Beji', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Bojongsari', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Cilodong', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Cimanggis', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Cinere', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Cipayung', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Limo', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Pancoran Mas', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Sawangan', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Sukmajaya', 'created_at' => $now, 'updated_at' => $now],
            ['nama_kecamatan' => 'Tapos', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}