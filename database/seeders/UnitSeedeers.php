<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitUsaha;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Puskesmas;

class UnitSeedeers extends Seeder
{
    public function run(): void
    {
        // Data dari file CSV yang diunggah
        $dataSppg = [
            [
                'nama' => 'SPPG Kota Depok Tapos Kebayunan 1',
                'pemilik' => 'Erni Ambarwati',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'TAPOS',
                'alamat' => 'Jl. Kampung Kebayunan RT04/RW18, Tapos, Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Kebayunan 2',
                'pemilik' => 'Ebsukianto Maulana Hadi',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'TAPOS',
                'alamat' => 'Jln Kebayunan RT 04/RW 15, Kelurahan Tapos, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Kebayunan 3',
                'pemilik' => 'Khoirul Hasan',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'TAPOS',
                'alamat' => 'Kp. Kebayunan RT. 004 / RW. 020 Kecamatan Tapos Kelurahan Tapos, Kota Depok, Jawa Barat 16457',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Kebayunan 4',
                'pemilik' => 'Sakinatunnafsih',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'TAPOS',
                'alamat' => 'Jl. Kp. Kebayunan RT4/RW18',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Kebayunan 5',
                'pemilik' => 'Fajri Subiantoro',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'TAPOS',
                'alamat' => 'SPPG Khusus GSI Kebayunan 5 - Kp. Kebayunan RT. 004 / RW. 022 Kecamatan Tapos Kelurahan Tapos, Kota Depok, Jawa Barat 16457',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok - Tapos Khusus 01',
                'pemilik' => 'Leonora Suherma Berutu',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'CILANGKAP',
                'alamat' => 'Jl. Kp. Cilangkap Rt02/ Rw 11',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Bedahan 1',
                'pemilik' => 'Widya purnamadjati',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'BEDAHAN',
                'alamat' => 'Jalan Jambu RT 03 RW 08 Kelurahan Bedahan Kecamatan Sawangan Kota Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Sukamaju',
                'pemilik' => 'Agung Beny Saputra',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'SUKAMAJU',
                'alamat' => 'Jl. Jati raya no.02 RT.03 RW.08 kelurahan  Sukamaju Kecamatan Cilodong Kota Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Tugu',
                'pemilik' => 'Muhammad Aditya Nugroho',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'TUGU',
                'alamat' => 'Jalan Bukit Duta 1 Nomor 1 Kelurahan Tugu',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cinere Cinere',
                'pemilik' => 'Afif Maulana Rivai',
                'kecamatan' => 'Cinere',
                'kelurahan' => 'CINERE',
                'alamat' => 'Jalan Mawar Blok F III,  No. 60A, RT 02, RW 15, Kelurahan Cinere, Kecamatan Cinere, Kota Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Harjamukti Harjamukti',
                'pemilik' => 'Rayhan Nur Ma`arif',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'HARJAMUKTI',
                'alamat' => 'Jl. Alternatif Transyogi No.23, Kelurahan Harjamukti, Kecamatan Cimanggis, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Mekarsari',
                'pemilik' => 'Aditya Candra',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'MEKARSARI',
                'alamat' => 'Jl. Alternatif Transyogi No.23, Kelurahan Harjamukti, Kecamatan Cimanggis, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Bedahan 2',
                'pemilik' => 'Devia Novitasari',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'BEDAHAN',
                'alamat' => 'Jl. H. Sulaiman No.4, Rt.003/Rw.002, Bedahan, Kec.Sawangan, Kota Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Mekarjaya',
                'pemilik' => 'Felix Dwi Kurniawan',
                'kecamatan' => 'SUKMAJAYA',
                'kelurahan' => 'MEKARJAYA',
                'alamat' => 'Jl. K.H.M. Yusuf Raya No.3,Mekar Jaya, Kec.Sukmajaya, Kota Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Kedaung 1',
                'pemilik' => 'Fijriani Widya',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'KEDAUNG',
                'alamat' => 'Jl. Cinangka Raya RT 01/01',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Kalimulya',
                'pemilik' => 'Tema Febriana',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'KALIMULYA',
                'alamat' => 'Jln. Kencana  1 No. 13, Kalimulya, Cilodong, Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Bedahan 3',
                'pemilik' => 'Haidar Alydrus',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'BEDAHAN',
                'alamat' => 'Jl. JabonNo 14 RT.003 /RW 004 Kel Bedahan Kec. Sawangan Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Kedaung 2',
                'pemilik' => 'Ringga Wijaya Kusuma',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'KEDAUNG',
                'alamat' => 'Jl. Jambu Sai`hi III No. 50 RT/01 RW/04, Kedaung, Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Sukamaju 2',
                'pemilik' => 'Rian Setiyawan',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'SUKAMAJU',
                'alamat' => 'Jalan H. Dimun Raya, RT 001/RW 011, Kelurahan Sukamaju, Kecamatan Cilodong, Kota Depok, Provinsi Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Duren Seribu',
                'pemilik' => 'Valery Ilhamna Putri',
                'kecamatan' => 'BOJONGSARI',
                'kelurahan' => 'DUREN SERIBU',
                'alamat' => 'Jalan Masjid Nurul Hidayah RT 001 RW 004, Kelurahan Duren Seribu, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Harjamukti 2',
                'pemilik' => 'Muhammad Adi Arti',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'HARJAMUKTI',
                'alamat' => 'Jalan Sumur Bandung 1 Rt 001 Rw 002, Kelurahan Harjamukti, Kecamatan Cimanggis Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Pasir Gunung Selatan 2',
                'pemilik' => 'I Gede Ari Yudhana',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'PASIR GUNUNG SELATAN',
                'alamat' => 'Jalan Komjen Pol M Jasin Nomor 23, Kelurahan Pasir Gunung Selatan, Kecamatan Cimanggis, Kota Depok, Provinsi Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Bedahan 4',
                'pemilik' => 'LInda Yuniza',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'BEDAHAN',
                'alamat' => 'Jl. H. Sulaiman No.20, Bedahan, Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Grogol',
                'pemilik' => 'Muhammad Fika Habibi',
                'kecamatan' => 'Limo',
                'kelurahan' => 'GROGOL',
                'alamat' => 'Desa Grogol Kecamatan Limo Kota Depok Jawa Barat , Grogol, Limo, Kota Depok, Jawa Barat',
                'pegawai' => 48,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Sawangan Baru',
                'pemilik' => 'Fikri Maulana Ibrahim',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'SAWANGAN BARU',
                'alamat' => 'Jl. H Hamid I, Sawangan Baru, Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Cinangka',
                'pemilik' => 'Abi Adhitama',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'CINANGKA',
                'alamat' => 'Jl. Pahlawan No. 10 RT 002 RW 001',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Leuwinanggung',
                'pemilik' => 'Agung Hudya Suad',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'LEUWINANGGUNG',
                'alamat' => 'Jalan Raya Leuwinanggung, Kelurahan Leuwinanggung, Kecamatan Tapos, Kota Depok, Provinsi Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Leuwinanggung 2',
                'pemilik' => 'Badrudin',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'LEUWINANGGUNG',
                'alamat' => 'Leuwinanggung, Depok City, West Java, Leuwinanggung, Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Pondok Petir',
                'pemilik' => 'Raihan Baitor Rahman',
                'kecamatan' => 'BOJONGSARI',
                'kelurahan' => 'PONDOK PETIR',
                'alamat' => 'Jl. Kesadaran I No.12, Rt.4/Rw.1, Pondok Petir, Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Depok',
                'pemilik' => 'Kevin Ferdinan',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'DEPOK',
                'alamat' => 'Jl. Kartini No.57, Kelurahan Depok, Kecamatan Pancoran Mas, Kota Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Mampang 1',
                'pemilik' => 'Martini Nur Azizah',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'MAMPANG',
                'alamat' => 'Jl. Damai 1 Mampang Pancoran Mas Depok, Mampang, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Sukmajaya',
                'pemilik' => 'Luviana Noviardita',
                'kecamatan' => 'SUKMAJAYA',
                'kelurahan' => 'SUKMAJAYA',
                'alamat' => 'Jl. Raden Saleh No. 1A, Kel. Sukmajaya, Kec. Sukmajaya - Kota Depok',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Pondok Jaya',
                'pemilik' => 'Muhammad Fathurrahman Saleh',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'PONDOK JAYA',
                'alamat' => 'Jl. Padat Karya No.7 Rt02/Rw06, Pondok Jaya, Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Mampang 2',
                'pemilik' => 'Eriansah',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'MAMPANG',
                'alamat' => 'Jl. Arsip, mampang',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Depok Jaya',
                'pemilik' => 'Abdul Aziiz',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'DEPOK JAYA',
                'alamat' => 'Jl. Mawar Raya No.190 Rt.007/Rw.005, Kelurahan Depok Jaya, Kecamatan Pancoran Mas, Kota Depok, Provinsi Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Bojong Pondok Terong',
                'pemilik' => 'Ahmad Asep Kurniawan',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'BOJONG PONDOK TERONG',
                'alamat' => 'Jl. Raya Citayam H. Dul Rt 04 Rw 05 No. 100 Kelurahan. Bojong Pondokterong, Kecamatan Cipayung, Kota. Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Krukut 1',
                'pemilik' => 'Adam Julian Faturrahman',
                'kecamatan' => 'Limo',
                'kelurahan' => 'KRUKUT',
                'alamat' => 'Krukut, Kecamatan Limo, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Limo',
                'pemilik' => 'Ahmad Fikri Alizar',
                'kecamatan' => 'Limo',
                'kelurahan' => 'LIMO',
                'alamat' => 'Jalan Bukit Tambora Kelurahan Limo, Kec. Limo, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cinere Gandul',
                'pemilik' => 'Fansuri',
                'kecamatan' => 'Cinere',
                'kelurahan' => 'GANDUL',
                'alamat' => 'Jl. Tareliman, Gandul, Kecamatan Cinere, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Sawangan',
                'pemilik' => 'Prabowo Sri Wiratmo',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'SAWANGAN',
                'alamat' => 'Jl. Raya Pengasinan, Gang Kamboja, Rt001/Rw02 Sawangan Kec. Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Tapos 6',
                'pemilik' => 'Muhammad Insan Kamil',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'TAPOS',
                'alamat' => 'Jl. Mayor Idrus No. 38 Rt 003 Rw 005 Kel.Tapos Kecamatan Tapos, Kota Depok Provisi Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Bedahan 5',
                'pemilik' => 'Rizki Saputra',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'BEDAHAN',
                'alamat' => 'Jl. H. Sulaiman No.70, Bedahan, Kecamatan Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 33,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Meruyung Limo Depok (Krukut 2)',
                'pemilik' => 'SYAIFUL RAHMAN',
                'kecamatan' => 'Limo',
                'kelurahan' => 'MERUYUNG',
                'alamat' => 'Jl. Raya Meruyung No. 40',
                'pegawai' => 50,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Cilodong (Kalimulya 3)',
                'pemilik' => 'Muhammad Sholehuddin Al Ayyubi',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'CILODONG',
                'alamat' => 'Jl. Raya Kalimulya No. 66 Rt. 002/005, Kelurahan Kalimulya, Kecamatan Cilodong, Kota Depok, Provinsi Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Rangkapan Jaya',
                'pemilik' => 'Ahmad Murodi Wajdi',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'RANGKAPAN JAYA',
                'alamat' => 'Jl. Raya Keadilan No. 94 B, Rangkapan Jaya, Kec. Pancoran Mas, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Sukmajaya 2',
                'pemilik' => 'Karina Afni Prabandini',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Sukmajaya',
                'alamat' => 'Jl. Raya Ksu, Rt.03/Rw.06, Sukmajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat 16412',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Krukut 3',
                'pemilik' => 'Ryan Aruman',
                'kecamatan' => 'Limo',
                'kelurahan' => 'Krukut 3',
                'alamat' => 'Jl. Kh. M Yusuf Rt 03/02 Kel. Krukut Kec. Limo Kota Depok, Jawa Barat',
                'pegawai' => 47,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Pondok Cina',
                'pemilik' => 'Bagagarsyah Wira Kusuma',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Pondok Cina',
                'alamat' => 'Jl. H. Mahali No.49 Rt.004 Rw.002, Pondok Cina, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Sukamaju Baru',
                'pemilik' => 'Bramastya Faris Rasyad Ahmad',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Sukamaju Baru',
                'alamat' => 'Jl. Bakti Abri. Rt 04/ Rw 05. Depok 16462, Sukamaju Baru, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Jatijajar',
                'pemilik' => 'Daniel Singgih Anditya Bagaskara',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Jatijajar',
                'alamat' => 'Jl. Raya Bogor KM 35.5 No. 85, Perum Jatijajar Estate, Kelurahan Jatijajar ',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Kemiri Muka',
                'pemilik' => 'Ahmad Faris Ramadhan',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Kemiri muka',
                'alamat' => 'Jl. Depok Indah 1 Blok A No.3, Kel.Kemirimuka, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Sukamaju 3',
                'pemilik' => 'Bagus Nazhareta Pratama Setiawan',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Sukamaju',
                'alamat' => 'Jl. Mandala Raya No. 40A, Sukamaju, Kec. Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Curug',
                'pemilik' => 'Sujatmiko',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Curug',
                'alamat' => 'Jl. Taman Firdaus 86 Rt 05/ Rw09, Curug, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Limo 2',
                'pemilik' => 'Adryan Rizki Fauzi',
                'kecamatan' => 'Limo',
                'kelurahan' => 'Meruyung',
                'alamat' => 'Jl. Singgalang Rt/Rw 008/014, Limo, Kecamatan Limo, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Pasir Putih',
                'pemilik' => 'Virgiawan Ratresianto',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Pasir Putih',
                'alamat' => 'Jl. Raya Pasir Putih Rt 004/Rw 001, Pasir Putih, Kecamatan Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Curug 2',
                'pemilik' => 'Fadli Ramadhan',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Curug',
                'alamat' => 'Jl. Mandor Tadjir No.184',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Curug 3',
                'pemilik' => 'Naufal Alif Ramadhan',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Curug',
                'alamat' => 'Jl. Raya Curug No.33, Curug, Kecamatan Bojongsari, Kota Depok, Jawa Barat ',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Kalimulya 2',
                'pemilik' => 'Ravizio Fakhri Badjuri',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Kalimulya',
                'alamat' => 'Jl. Maharani Rt 001 / Rw 04, Kelurahan Kalimulya, Kecamatan Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cimpaeun 1',
                'pemilik' => 'Linda Destiyani',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Cimpaeun',
                'alamat' => 'Cimpaeun, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Duren Seribu 2',
                'pemilik' => 'Daniswara Zuhdi',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Duren Seribu',
                'alamat' => 'Jl. Manggis B76 No. 11, Komplek Arco, Rt.01/Rw.07, Kel. Duren Seribu, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Grogol 2',
                'pemilik' => 'Muhammad Nendi Novatino',
                'kecamatan' => 'Limo',
                'kelurahan' => 'Grogol',
                'alamat' => 'Jl. Kampung Rw. Kalong No.5, Rt.1/Rw.9, Grogol, Kec. Limo, Kota Depok, Jawa Barat 16514',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cimpaeun 2',
                'pemilik' => 'Muhammad Robbi Heriyansah',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Cimpaeun ',
                'alamat' => 'Jl. Rkb Rt.02 Rw.04 No. 35 Kel. Cimpaeun Kecamatan Tapos. Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Leuwinanggung 3',
                'pemilik' => 'Jovanra Herwin Arofah',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Leuwinanggung',
                'alamat' => 'Jl. Raya Kebayunan No. 85 Rt/Rw 002/004, Kel. Leuwinanggung, Kec. Tapos, Kota Depok Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Limo 3',
                'pemilik' => 'Muhamad Syarifudin',
                'kecamatan' => 'Limo',
                'kelurahan' => 'LIMO',
                'alamat' => 'Jl. Sasak Raya, Desa/Kelurahan Limo, Kecamatan Limo, Kota Depok, Provinsi Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Curug',
                'pemilik' => 'Alfian Rizky Nurzaman',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'Cisalak Pasar',
                'alamat' => 'Jl.Raya Bogor KM.31 Cisalak Pasar - Cimanggis - Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cimpaeun 3',
                'pemilik' => 'Shafira Alyssa Difiputri',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Cimpaeun ',
                'alamat' => 'Jl. Cimpaeun, Rt 02/14, Cimpaeun, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Pondok Jaya 2',
                'pemilik' => 'ACHMAD DIANSYAH',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Pondok Jaya',
                'alamat' => 'Perumahan Jambu Tree Residence Blok A1 No.8, Pondok Jaya, Kec. Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Rangkapan Jaya Baru',
                'pemilik' => 'Maria Jesica Putri Audelia',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Rangkapan Jaya Baru',
                'alamat' => 'Jl. Puring Raya No.50, Rt.009 Rw.01, Rangkapan Jaya Baru, Kec. Pancoran Mas, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Tirtajaya',
                'pemilik' => 'Muhammad Fikri',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Tirtajaya',
                'alamat' => 'Ruko Grand Depok City Blok Cluster Verbena/Blok B No.12, Tirtajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Bedahan 6',
                'pemilik' => 'Dika Mahmudi',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Bedahan',
                'alamat' => 'Jl. H. Sulaiman No.4, Rt.003/Rw.002, Kel. Bedahan, Kecamatan Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Cinangka 2',
                'pemilik' => 'FERDINAN RAHMAN',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Cinangka',
                'alamat' => 'Jl. Raya Pahlawan No. 10, Cinangka, Kec. Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Curug 2',
                'pemilik' => 'Dyah Puspito Rini',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'Curug',
                'alamat' => 'Kampung Babakan Rajabrana Rt 02 Rw 10,Curug, Kec. Cimanggis, Kota Depok, Provinsi Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Harjamukti 3',
                'pemilik' => 'Fathan Qoriba Junelry',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'HARJAMUKTI',
                'alamat' => 'Jl. Putri Tunggal No 42, Harjamukti, Kec. Cimanggis, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Bedahan 7',
                'pemilik' => 'Gandung',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Bedahan',
                'alamat' => 'Jl. Haji Sulaiman Prajna Village, Bedahan, Kec. Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Abadijaya',
                'pemilik' => 'Bagas Pratama Hidayatullah',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Abadijaya',
                'alamat' => 'Jl. Ampel. Kp Cipayung RT 10/1 Kelurahan Abadijaya, Kecamatan Sukmajaya, Depok II Timur, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Depok 2',
                'pemilik' => 'Luthfil Chakim',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Depok',
                'alamat' => 'Jl. Anggrek Dalam No. 48 Rt. 010 Rw. 003 Kelurahan Depok, Kecamatan Pancoran Mas, Kota Depok Provinsi Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Limo 4',
                'pemilik' => ' Muhammad Irfan',
                'kecamatan' => 'Limo',
                'kelurahan' => 'Limo',
                'alamat' => 'Jl. Pinang 2 No,002 Kecamatan Limo, Kelurahan Limo, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Cipayung',
                'pemilik' => 'Syifa Fauziah Putri',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Cipayung',
                'alamat' => 'Jl. Pitara No.124, Cipayung, Kec. Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Tanah Baru',
                'pemilik' => 'Jhosua Silitonga',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Tanah Baru',
                'alamat' => 'Jl. Raden Sanim RW 11, Kelurahan Tanah Baru, Beji',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Kukusan',
                'pemilik' => 'Dede Risna Ayu Ajhari',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Kukusan',
                'alamat' => 'Jl. K H M Usman, No.15, Rt 004 Rw 02, Kukusan, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG PONDOK PETIR 02 Bojongsari',
                'pemilik' => 'Febrian Rizky Ramadhan',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Pondok Petir',
                'alamat' => 'Jl. Kesadaran I Rt.04/01 No 73 Pondok Petir, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cimpaeun 4',
                'pemilik' => 'Muhammad Syifa',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Cimpaeun',
                'alamat' => 'Jl. Raya Tapos Rt 01 Rw 05 Kel. Cimpaeun, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Limo 5',
                'pemilik' => 'Aprila Parma Regina',
                'kecamatan' => 'Limo',
                'kelurahan' => 'Limo',
                'alamat' => 'Jl. Parkit II Blok C2 Nomor 1, Perumahan Griya Cinere 1, Limo, Kec. Limo, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Harjamukti 4',
                'pemilik' => 'Mustika Fie Salsabila',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'Harjamukti',
                'alamat' => 'Jl. Bungur 1 no 12 rt 03 rw 07, Kelurahan Harjamukti Kecamatan Cimanggis',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG KEDAUNG 3, SAWANGAN',
                'pemilik' => 'Khairul Hazhar',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'KEDAUNG',
                'alamat' => 'Jalan Jambu Saihi 3 RT 03 RW 04 Kedaung, Sawangan',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG BOJONG PONDOK TERONG, CIPAYUNG 2',
                'pemilik' => 'Sandi Lazuardi',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'BOJONG PONDOK TERONG',
                'alamat' => 'Jl. Raya Cipayung Rt. 03 Rw. 09, Bojong Pondok Terong, Kec. Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Pondok Petir 3',
                'pemilik' => 'Dimas Fahmi Zuhri Ramadhan',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Pondok Petir',
                'alamat' => 'Jl. Sabran No.03 Rt.001/019, Pondok Petir, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Sukamaju 4',
                'pemilik' => 'Rendy Ramadhan Saputra',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Sukamaju ',
                'alamat' => 'Jl. Tole Iskandar No.74, Sukamaju, Cilodong',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Pasir Putih 3',
                'pemilik' => 'Ricky Vinensius Ginting',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Pasir Putih',
                'alamat' => 'Jl. Mawar No.1 Rt.01/04, Pasir Putih, Kec. Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Pasir Putih 2',
                'pemilik' => 'Muhammad Rizki',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Pasir Putih',
                'alamat' => 'Sawangan Permai Raya Blok E10 No.11, Pasir Putih, Kec. Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG KOTA DEPOK TAPOS TAPOS 2',
                'pemilik' => 'Syahrudin',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Tapos',
                'alamat' => 'Jl. Mayor Idrus, Tapos, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG KOTA DEPOK CILODONG KALIBARU',
                'pemilik' => 'Firman Fauzi',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Kalibaru',
                'alamat' => 'Jl. Mandor Samin No 86 Rt 002/006, Kalibaru, Kec. Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG KOTA DEPOK SUKMAJAYA SUKMAJAYA 4',
                'pemilik' => 'Bungaran Shallom Adonai Ompusunggu',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Sukmajaya',
                'alamat' => 'Jl. Studio alam TVRI, RT 04 RW 08, Kel. Sukmajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG KOTA DEPOK SUKMAJAYA ABADIJAYA 2',
                'pemilik' => 'Muhammad Farhan',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Abadijaya',
                'alamat' => 'Jl. Damai, Abadijaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG SUKMAJAYA SUKMAJAYA 3',
                'pemilik' => 'Nova Aryani',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Sukmajaya',
                'alamat' => 'Jl. Tole Iskandar No.1, RT.06/RW.04, Sukmajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG KOTA DEPOK PANCORAN MAS RANGKAPAN JAYA BARU 2',
                'pemilik' => 'Siti Regita Nurhaliza',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Rangkapan Jaya Baru',
                'alamat' => 'Jl. Meruyung Raya No.72 D, Kel Rangkapan Jaya Baru, Kec. Pancoran Mas, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG CURUG 4',
                'pemilik' => 'Javiera Putri Motali',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Curug',
                'alamat' => 'Jl. Pasiron Rt 02 Rw 10 Kelurahan Curug, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Sukamaju Baru 2',
                'pemilik' => 'Abu Hanifah Huzaifa',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Sukamaju Baru',
                'alamat' => 'Jl. Bakti Abri Rt 04/ Rw 05. Sukamaju Baru, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Tugu 2',
                'pemilik' => 'Muhammad Dandi Rachmadi',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'Tugu',
                'alamat' => 'Jl. Setu Indah No. 74, Tugu, Cimanggis , Depok',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG RATU JAYA 3',
                'pemilik' => 'Muhammad Rizkiandra',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Ratu Jaya',
                'alamat' => 'Jl. Raya Citayam Jl. Gandaria 1 Rt 002 Rw 006, Ratu Jaya, Kecamatan Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Mampang 3',
                'pemilik' => 'Muhammad Irsa Renggi Darmawan',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Mampang',
                'alamat' => 'Perumahan Pancoran Mas Depok Blok A No. 2 Rt. 005 Rw. 005, Kel. Mampang, Kec. Pancoran Mas, Kota Depok Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Sukmajaya Abadijaya 4',
                'pemilik' => 'Achmad Zam Zam Noor',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Abadijaya',
                'alamat' => 'Perumahan Taman Cipayung Blok 11 No 71, Kelurahan Abadijaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Sukmajaya Abadijaya 3',
                'pemilik' => 'Helsa Cynthia Sitompul',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Abadijaya',
                'alamat' => 'Jl. Barito Raya, No. 171-172, RT. 02/RW. 15, Kel. Abadijaya, Kec. Sukmajaya, Kota Depok',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Pasir Putih 4',
                'pemilik' => 'Aldo Nursetiawan',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Pasir Putih',
                'alamat' => 'Jl. H Totong RT 04 RW 06 Pasir Putih, Sawangan',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG CINERE 2',
                'pemilik' => 'BAGAS RIZKY DARMAWAN',
                'kecamatan' => 'Cinere',
                'kelurahan' => 'PANGKALAN JATI',
                'alamat' => 'Jl. Bersama Raya No.1, Cinere, Kec. Cinere, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Rangkapan Jaya Baru 3',
                'pemilik' => 'Anis Arfia',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Rangkapan Jaya Baru',
                'alamat' => 'Griya Pancoran Mas Indah Blok B 7 No 12 A, Rangkapan Jaya Baru, Kec. Pancoran Mas, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG BEJI, BEJI',
                'pemilik' => 'Johansyah Nadeak',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Beji',
                'alamat' => 'Jl. Arief Rahman Hakim 69A/69B, Kelurahan Beji, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Kalibaru 2',
                'pemilik' => 'Erlly Akbar Gumay',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Kalibaru',
                'alamat' => 'Jl. Studio Alam No88 Rt001/003 Kalibaru, Kecamatan Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Bojong Pondok Terong 3, Cipayung',
                'pemilik' => 'Muhammad Rafly Fadlilah',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Bojong Pondok Terong',
                'alamat' => 'Jl. Nurul Hayat No. 102 Bojong Pondok Terong, Kec. Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Sukamaju 5',
                'pemilik' => 'Krisna Adriansyah',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Sukamaju',
                'alamat' => 'Jl. Bahagia Raya, No. 11 Rt.4/Rw.4 Sukamaju, Kecamatan Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Jatimulya 01',
                'pemilik' => 'Arif Budiman',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Jatimulya',
                'alamat' => 'Jl. Ar Ridho Rt. 01 Rw. 03, Jatimulya, Kecamatan Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos TAPOS 3',
                'pemilik' => 'Muhammad Rahmanda Alfandi',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Tapos',
                'alamat' => 'Jl. Raya Tapos, Kel. Tapos, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Beji Timur',
                'pemilik' => 'Muhammad Nur Rohman',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Beji Timur',
                'alamat' => 'Jl. Arif Rahman Hakim Blok Mekar 1 No.8, Rt.3/Rw.10, Beji Timur, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Meruyung',
                'pemilik' => 'Seto Pri Anggoro',
                'kecamatan' => 'Limo',
                'kelurahan' => 'Meruyung',
                'alamat' => 'Jl. H. Usman Rt.002/Rw.002 Kelurahan Meruyung, Kecamatan Limo, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Kukusan 3',
                'pemilik' => 'Muhammad Fikti Muharom',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Kukusan',
                'alamat' => 'Jl. H. Mustofa No. 1, Rt 006 Rw 04, Kukusan, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG kota Depok Cilodong Sukamaju 6',
                'pemilik' => 'Yose Armadhani',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Sukamaju',
                'alamat' => 'Jl. Gang Ibu Nyai Rt 001/Rw 010, Kelurahan Sukamaju, Kecamatan Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Sukmajaya 5',
                'pemilik' => 'Ignatius Peis Mathher Gerald Sinsiw Perasetio',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Sukmajaya',
                'alamat' => 'Jl. Ksu Raya No. 48 Sukmajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Tirtajaya 2',
                'pemilik' => 'Nur Fatah',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Tirtajaya',
                'alamat' => 'Jl. Raya Ksu No.52, Tirtajaya, Kec. Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cilangkap 2',
                'pemilik' => 'Aprilia Ayu Pangesti',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'CILANGKAP',
                'alamat' => 'Jl. Setu Cilangkap No.97, Cilangkap, Kec. Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cilangkap 3',
                'pemilik' => 'Simon Andre',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Cilangkap',
                'alamat' => 'Kampung Banjaran Pucung Rt 01/Rw 10, Kelurahan Cilangkap, Kecamatan Tapos, Kabupaten Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukamaju 07',
                'pemilik' => 'Mega Lanina',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Sukamaju',
                'alamat' => 'Jl. Al Muwahhidiin, Sukamaju, Kec. Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kedaung Sawangan 4',
                'pemilik' => 'Raden Krisna Halcema',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Kedaung',
                'alamat' => 'Jl. Abdul Wahad Kel. Kedaung Kec. Sawangan, Kota Depok Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Kukusan 2',
                'pemilik' => 'Wiyan Akhadi Ahmad',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Kukusan',
                'alamat' => 'Jl. Kukusan Raya Rt 05, RW 02',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Jatijajar 2',
                'pemilik' => 'Timdek Oloan',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Jatijajar',
                'alamat' => 'Jl. Kelurahan Jatijajar II No 29, Rt/Rw 04/08, Kel. Jatijajar, Kec. Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG KOTA DEPOK CILODONG KALIBARU 3',
                'pemilik' => 'Fitra Auladi',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Kalibaru',
                'alamat' => 'Jl. H. Sikam No.23, Kalibaru, Kec. Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Tapos 4',
                'pemilik' => 'Hadi Murtadha',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Sukatani',
                'alamat' => 'Jl. Raya Tapos Rt 003 Rw012, Kelurahan Tapos, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Bojong Pondok Terong 4',
                'pemilik' => 'Dika Tri Koncoro',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Sukatani',
                'alamat' => 'Jl. Rawa Indah 1, Rt03/Rw01 Bojong Pondok Terong, Kec. Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG CIMANGGIS CURUG 3',
                'pemilik' => 'MOCHAMAD DAFFA NUR RACHMAN',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'Curug',
                'alamat' => 'Jl. Raya Pekapuran Kampung Baru Rt.001/007 No.21, Curug, Kecamatan Cimanggis, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Kemiri Muka 2',
                'pemilik' => 'Rahmawati Tri Astuti',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Kemiri muka',
                'alamat' => 'Jl. Karet Kampung Gedong Rt.01 04 Kelurahan Kemirimuka, Kecamatan Limo, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Depok Jaya 2',
                'pemilik' => 'Zakirullah',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Depok Jaya',
                'alamat' => 'Jl. Salak, Depok Jaya, Kecamatan Pancoran Mas, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Duren Seribu 3',
                'pemilik' => 'Tammi Hadi',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Duren Seribu',
                'alamat' => 'Jl. Markisah No. 10 Rt 173 Rw 06, Duren Seribu, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Pancoran Mas Mampang 4',
                'pemilik' => 'Taufik Kurrahman',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Mampang',
                'alamat' => 'Jl. Makam Bojong, Mampang Rt 004 003, Kec. Pancoran Mas, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Duren Seribu 4',
                'pemilik' => 'Joko Setiyono ',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Duren Seribu',
                'alamat' => 'Jl. H Maat No.23 Rt 001 Rw 003, Duren Seribu, Kec. Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Beji 2',
                'pemilik' => 'Ishlahul Fuadi ',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Beji',
                'alamat' => 'Jl. H. Asmawi No. 5, Jl. Perumahan Depok Mulya 1 No.15, Rt.05, Beji, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Limo Meruyung 2',
                'pemilik' => 'Thariq Kemal Rizal',
                'kecamatan' => 'Limo',
                'kelurahan' => 'Meruyung',
                'alamat' => 'Jl. Haji Musa Rt 02 Rw 11 No. 78 Kel. Maruyung, Kecamatan Limo, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong, Cilodong 2',
                'pemilik' => 'Raafi Alfarizi',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Kalibaru',
                'alamat' => 'Jl.H.Abdul Gani 1 No.1 RT.02/RW.02,Kalibaru, Kec. Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Pondok Petir 4',
                'pemilik' => 'Zenteno Achmad',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Pondok Petir',
                'alamat' => 'Serua Bulak Rt 003 Rw 003, Kel. Pondok Petir, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cimpaeun 5',
                'pemilik' => 'Mattheus Victor Parulian',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Cimpaeun',
                'alamat' => 'Jl. Raya Tapos, Cimpaeun, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Beji Timur 2',
                'pemilik' => 'Imam Feri Juanda',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Beji',
                'alamat' => 'Jl. Karya Pemuda No.1 Rt.3 Rw.5, Kelurahan Beji Timur, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Beji 3',
                'pemilik' => 'Muhammad Ilham',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Beji',
                'alamat' => 'Jl. Serdang II Nomor 53 Rt 03 Rw 04, Beji, Kec. Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cimanggis Tugu 3',
                'pemilik' => 'Pintarsama Telaumbanua',
                'kecamatan' => 'Cimanggis',
                'kelurahan' => 'TUGU',
                'alamat' => 'Jl. H Rijin 130B, Tugu, Kecamatan Cimanggis, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Tapos 5',
                'pemilik' => 'Muhammad Pazri',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Tapos',
                'alamat' => 'Jl. Raya Tapos No.8, Tapos, Kec. Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cinere Cinere 3',
                'pemilik' => 'Muhammad Ari Pernanda Rizki',
                'kecamatan' => 'Cinere',
                'kelurahan' => 'Cinere',
                'alamat' => 'Jl. Delima No.15, Rt.01 Rw.05, Cinere, Kecamatan Cinere, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Tirtajaya 3',
                'pemilik' => 'Anugrah Jaya Telaumbanua',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Tirtajaya',
                'alamat' => 'Jl. Raya Ksu No.5 Rt.001 Rw.005 Kel. Tirtajaya, Kecamatan Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Leuwinanggung 4',
                'pemilik' => 'Nikmat kalkausar Hrp',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Leuwinanggung',
                'alamat' => 'Jl. Raya Leuwinanggung, Leuwinanggung, Kec. Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Sukamaju 8',
                'pemilik' => 'Heri Wanda',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Sukamaju',
                'alamat' => 'Jl. Perumahan D`Cordova, Kel. Sukamaju, Kec. Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Cipayung 2',
                'pemilik' => 'Mukhlis Adami',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Cipayung',
                'alamat' => 'Jl. Bulak Timur Rt 007 Rw 09 Kelurahan Cipayung, Kecamatan Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Bojongsari',
                'pemilik' => 'Muhammad Bayu Mutollib',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Bojongsari',
                'alamat' => 'Gang Masjid Ra Rahmat Duren Mekar, Bojongsari, Kecamatan Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Tapos Cimpaeun 5',
                'pemilik' => 'Sefrius Dakhi',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Cimpaeun',
                'alamat' => 'Jl. Kiray No.03, Rt 2 Rw 15, Cimpaeun, Kecamatan Tapos, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Beji 4',
                'pemilik' => 'Syahpul Nove Putra Telaumbanua',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Beji',
                'alamat' => 'Jl. Ridwan Rais No. 12, Rt 05 Rw 4, Beji, Kecamatan Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Mekarjaya',
                'pemilik' => 'Andi Putra Laia',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'Mekar Jaya',
                'alamat' => 'Jl. Kh.M. Yusuf I No.49 Rt 01 Rw 021, Mekar Jaya, Kec. Sukmajaya, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cilodong Kalibaru 4',
                'pemilik' => 'Muhammad Ilham Gifari',
                'kecamatan' => 'Cilodong',
                'kelurahan' => 'Kalimulya',
                'alamat' => 'Jl. Raya Kalimulya No.66, Kalimulya, Kec. Cilodong, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Sawangan 2',
                'pemilik' => 'Zamzami',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'sawangan',
                'alamat' => 'Jl. Abdul Wahab No 2 Rt 01 Rw 06, Sawangan, Kec. Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sawangan Sawangan 3',
                'pemilik' => 'Matius Giawa',
                'kecamatan' => 'Sawangan',
                'kelurahan' => 'Sawangan',
                'alamat' => 'Jl. Abdul Wahab Rt 03 Rw 03 Kel. Sawangan, Kec. Sawangan, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Cipayung Jaya',
                'pemilik' => 'Iqbal Rafiud Draja',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Cipayung Jaya',
                'alamat' => 'Jl. H. Muhidin No. 114 Rt. 001 Rw. 002, Cipayung Jaya, Kec. Cipayung, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Sukmajaya Baktijaya 2',
                'pemilik' => 'YORIO DWIDIASTARA BETTY',
                'kecamatan' => 'Sukmajaya',
                'kelurahan' => 'BAKTIJAYA',
                'alamat' => '',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Cipayung 3',
                'pemilik' => 'OKTAVIANUS GILI DHOU',
                'kecamatan' => 'Cipayung',
                'kelurahan' => 'Cipayung',
                'alamat' => '',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Bojongsari Serua',
                'pemilik' => 'David putra jaya hulu',
                'kecamatan' => 'Bojongsari',
                'kelurahan' => 'Serua',
                'alamat' => 'Jl. Raya Parung Ciputat Blok B20-B21, Serua, Kec. Bojongsari, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Beji Kukusan 4',
                'pemilik' => 'Rizal Aditya Daik',
                'kecamatan' => 'Beji',
                'kelurahan' => 'Kukusan',
                'alamat' => 'Jl. K.H.M. Usman No. 160 H, Kel. Kukusan, Kec. Beji, Kota Depok, Jawa Barat',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Leuwinanggung 4',
                'pemilik' => 'Muhammad Pazri',
                'kecamatan' => 'Tapos',
                'kelurahan' => 'Leuwinanggung',
                'alamat' => '',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Pancoran Mas Pancoran Mas',
                'pemilik' => 'Marthin Luther Tafanao',
                'kecamatan' => 'Pancoran Mas',
                'kelurahan' => 'Pancoran Mas',
                'alamat' => '',
                'pegawai' => 0,
                'penjamah' => 0
            ],
            [
                'nama' => 'SPPG Kota Depok Cipayung Cipayung 4',
                'pemilik' => 'Berkat Syukur Zai',
                'kecamatan' => 'Cipayung',
                'kelurahan' => '',
                'alamat' => '',
                'pegawai' => 0,
                'penjamah' => 0
            ]
        ];

    foreach ($dataSppg as $row) {
            // 1. Format & Cari/Buat ID Kecamatan
            // Contoh: "TAPOS " -> "tapos" -> "Tapos"
            $namaKecamatanFormat = ucwords(strtolower(trim($row['kecamatan'])));
            
            $kecamatan = Kecamatan::firstOrCreate([
                'nama_kecamatan' => $namaKecamatanFormat
            ]);
            $idKecamatan = $kecamatan->id_kecamatan ?? $kecamatan->id;

            // 2. Format & Cari/Buat ID Kelurahan
            // Contoh: "KEMIRI muka" -> "kemiri muka" -> "Kemiri Muka"
            $rawKelurahan = empty($row['kelurahan']) ? 'Tidak Diketahui' : $row['kelurahan'];
            $namaKelurahanFormat = ucwords(strtolower(trim($rawKelurahan)));
            
            $kelurahan = Kelurahan::firstOrCreate([
                'id_kecamatan' => $idKecamatan,
                'nama_kelurahan' => $namaKelurahanFormat
            ]);
            $idKelurahan = $kelurahan->id_kelurahan ?? $kelurahan->id;

            // 3. Cari Puskesmas berdasarkan nama Kecamatan yang sudah diformat
            $namaPuskesmasTarget = 'Puskesmas ' . $namaKecamatanFormat;

            $puskesmas = Puskesmas::where('nama_puskesmas', $namaPuskesmasTarget)->first();
            
            // Ambil ID puskesmas, jika kebetulan tidak ada di master, jadikan null
            $idPuskesmas = $puskesmas->id_puskesmas ?? $puskesmas->id ?? null;

            // 4. Input tabel utama Unit Usaha (SPPG)
            UnitUsaha::create([
                'id_kecamatan' => $idKecamatan,
                'id_kelurahan' => $idKelurahan,
                'id_puskesmas' => $idPuskesmas,
                'jenis_usaha'  => 'sppg',
                'nama_unit_usaha' => trim($row['nama']),
                'nama_pemilik' => trim($row['pemilik']),
                'alamat' => empty($row['alamat']) ? '-' : trim($row['alamat']),
                'jumlah_pegawai' => $row['pegawai'],
                'jumlah_penjamah_terlatih' => $row['penjamah'], 
                'status_aktif' => true,
            ]);
        }

        $this->command->info('102 Data Unit Usaha (SPPG) berhasil di-seed tanpa duplikasi wilayah!');
    }
}