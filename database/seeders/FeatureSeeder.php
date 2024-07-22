<?php

namespace Database\Seeders;

use App\Models\Feature;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Feature::insert([
            [
                "name" => "Dashboard", "description" => "Manajemen Data Dashboard",
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Absensi", "description" => "",
                'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Roster", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Kasbon", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // ["name" => "Surat Perintah Lembur", "description" => "", ],
            [
                "name" => "Slip Gaji", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Penggajian", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Proyek", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Job Order", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Cuti", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // laporan
            [
                "name" => "Laporan Job Order", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Laporan Kasbon", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Laporan Surat Perintah Lembur", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Laporan Cuti", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // master
            [
                "name" => "Departemen", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Jabatan", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Perusahaan", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Jenis Karyawan", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Kapal", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Lokasi", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Bahan", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Kategori Job Order", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Jadwal Kerja", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Jenis Pekerjaan", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Karyawan", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Pelanggan", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Alat Finger", "description" => "",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // setting
            [
                "name" => "Penyesuaian Gaji", "description" => "Manajemen penambahan dan pengurangan gaji karyawan",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Jam Kerja", "description" => "Manajemen Jam Kerja Karyawan",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Pengguna", "description" => "Manajemen Data Pengguna",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Hak Akses", "description" => "Manajemen Hak Akses berdasarkan grup user",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Fitur", "description" => "Manajemen Data Fitur",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Perhitungan Bpjs", "description" => "Manajemen Data Perhitungan Bpjs",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Dasar Upah Bpjs", "description" => "Manajemen Data Dasar Upah Bpjs",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Grup Pengguna", "description" => "Manajemen Data Grup Pengguna",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Tingkat Persetujuan", "description" => "Manajemen Data Tingkat Persetujuan",  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                "name" => "Log", "description" => null,  'created_by' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ]);
    }
}
