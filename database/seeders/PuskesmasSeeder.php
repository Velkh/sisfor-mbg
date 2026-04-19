<?php

namespace Database\Seeders;

use App\Models\Puskesmas;
use Illuminate\Database\Seeder;

class PuskesmasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPuskesmas = [
            'Puskesmas Abadijaya',
            'Puskesmas Bakti Jaya',
            'Puskesmas Beji',
            'Puskesmas Beji Timur',
            'Puskesmas Bojongsari',
            'Puskesmas Cilodong',
            'Puskesmas Cimanggis',
            'Puskesmas Cinere',
            'Puskesmas Cipayung',
            'Puskesmas Cisalak Pasar',
            'Puskesmas Curug',
            'Puskesmas Depok Jaya',
            'Puskesmas Duren Seribu',
            'Puskesmas Grogol',
            'Puskesmas Jatimulya',
            'Puskesmas Kalimulya',
            'Puskesmas Kemiri Muka',
            'Puskesmas Limo',
            'Puskesmas Mampang',
            'Puskesmas Mekarjaya',
            'Puskesmas Pancoran Mas',
            'Puskesmas Pasir Gunung Selatan',
            'Puskesmas Pasir Putih',
            'Puskesmas Pengasinan',
            'Puskesmas Rangkapan Jaya',
            'Puskesmas Sawangan',
            'Puskesmas Sukatani',
            'Puskesmas Sukmajaya',
            'Puskesmas Tapos',
        ];

        foreach ($dataPuskesmas as $namaPuskesmas) {
            Puskesmas::query()->firstOrCreate([
                'nama_puskesmas' => $namaPuskesmas,
            ]);
        }
    }
}