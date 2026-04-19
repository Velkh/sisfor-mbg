<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $kecamatanMap = DB::table('kecamatan')
            ->pluck('id_kecamatan', 'nama_kecamatan')
            ->toArray();

        $dataKelurahan = [
            'Beji' => [
                'Beji',
                'Beji Timur',
                'Kemiri Muka',
                'Kukusan',
                'Pondok Cina',
                'Tanah Baru',
            ],
            'Bojongsari' => [
                'Bojongsari',
                'Bojongsari Baru',
                'Curug',
                'Duren Mekar',
                'Duren Seribu',
                'Pondok Petir',
                'Serua',
            ],
            'Cilodong' => [
                'Cilodong',
                'Jatimulya',
                'Kalibaru',
                'Kalimulya',
                'Sukamaju',
            ],
            'Cimanggis' => [
                'Cisalak Pasar',
                'Curug',
                'Harjamukti',
                'Mekarsari',
                'Pasir Gunung Selatan',
                'Tugu',
            ],
            'Cinere' => [
                'Cinere',
                'Gandul',
                'Pangkalan Jati',
                'Pangkalan Jati Baru',
            ],
            'Cipayung' => [
                'Bojong Pondok Terong',
                'Cipayung',
                'Cipayung Jaya',
                'Pondok Jaya',
                'Ratujaya',
            ],
            'Limo' => [
                'Grogol',
                'Krukut',
                'Limo',
                'Meruyung',
            ],
            'Pancoran Mas' => [
                'Depok',
                'Depok Jaya',
                'Mampang',
                'Pancoran Mas',
                'Rangkapan Jaya',
                'Rangkapan Jaya Baru',
            ],
            'Sawangan' => [
                'Bedahan',
                'Cinangka',
                'Kedaung',
                'Pasir Putih',
                'Pengasinan',
                'Sawangan',
                'Sawangan Baru',
            ],
            'Sukmajaya' => [
                'Abadijaya',
                'Bakti Jaya',
                'Cisalak',
                'Mekarjaya',
                'Sukmajaya',
                'Tirtajaya',
            ],
            'Tapos' => [
                'Cilangkap',
                'Cimpaeun',
                'Jatijajar',
                'Leuwinanggung',
                'Sukamaju Baru',
                'Sukatani',
                'Tapos',
            ],
        ];

        $rows = [];

        foreach ($dataKelurahan as $namaKecamatan => $kelurahans) {
            if (! isset($kecamatanMap[$namaKecamatan])) {
                continue;
            }

            foreach ($kelurahans as $namaKelurahan) {
                $rows[] = [
                    'id_kecamatan' => $kecamatanMap[$namaKecamatan],
                    'nama_kelurahan' => $namaKelurahan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('kelurahan')->insert($rows);
    }
}