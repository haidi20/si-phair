-- --------------------------------------------------------

-- Host:                         185.201.8.213

-- Server version:               5.7.34-log - Source distribution

-- Server OS:                    Linux

-- HeidiSQL Version:             12.0.0.6468

-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */

;

/*!40101 SET NAMES utf8 */

;

/*!50503 SET NAMES utf8mb4 */

;

/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */

;

/*!40103 SET TIME_ZONE='+00:00' */

;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */

;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */

;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */

;

-- Dumping structure for table shipyard.features

DROP TABLE IF EXISTS `features`;

CREATE TABLE
    IF NOT EXISTS `features` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci,
        `created_by` bigint(20) unsigned DEFAULT NULL,
        `updated_by` bigint(20) unsigned DEFAULT NULL,
        `deleted_by` bigint(20) unsigned DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        `deleted_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE = InnoDB AUTO_INCREMENT = 36 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table shipyard.features: ~35 rows (approximately)

INSERT INTO
    `features` (
        `id`,
        `name`,
        `description`,
        `created_by`,
        `updated_by`,
        `deleted_by`,
        `created_at`,
        `updated_at`,
        `deleted_at`
    )
VALUES (
        1,
        'Dashboard',
        'Manajemen Data Dashboard',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        2,
        'Absensi',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        3,
        'Roster',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        4,
        'Kasbon',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        5,
        'Slip Gaji',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        6,
        'Penggajian',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        7,
        'Proyek',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        8,
        'Job Order',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        9,
        'Cuti',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        10,
        'Laporan Job Order',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        11,
        'Laporan Kasbon',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        12,
        'Laporan Surat Perintah Lembur',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        13,
        'Laporan Cuti',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        14,
        'Departemen',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        15,
        'Jabatan',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        16,
        'Perusahaan',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        17,
        'Jenis Karyawan',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        18,
        'Kapal',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        19,
        'Lokasi',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        20,
        'Bahan',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        21,
        'Kategori Job Order',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        22,
        'Jadwal Kerja',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        23,
        'Jenis Pekerjaan',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        24,
        'Karyawan',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        25,
        'Pelanggan',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        26,
        'Alat Finger',
        '',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        27,
        'Penyesuaian Gaji',
        'Manajemen penambahan dan pengurangan gaji karyawan',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        28,
        'Jam Kerja',
        'Manajemen Jam Kerja Karyawan',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        29,
        'Pengguna',
        'Manajemen Data Pengguna',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        30,
        'Hak Akses',
        'Manajemen Hak Akses berdasarkan grup user',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        31,
        'Fitur',
        'Manajemen Data Fitur',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        32,
        'Perhitungan Bpjs',
        'Manajemen Data Perhitungan Bpjs',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        33,
        'Dasar Upah Bpjs',
        'Manajemen Data Dasar Upah Bpjs',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        34,
        'Grup Pengguna',
        'Manajemen Data Grup Pengguna',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    ), (
        35,
        'Tingkat Persetujuan',
        'Manajemen Data Tingkat Persetujuan',
        1,
        NULL,
        NULL,
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32',
        NULL
    );

-- Dumping structure for table shipyard.model_has_permissions

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE
    IF NOT EXISTS `model_has_permissions` (
        `permission_id` bigint(20) unsigned NOT NULL,
        `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `model_id` bigint(20) unsigned NOT NULL,
        PRIMARY KEY (
            `permission_id`,
            `model_id`,
            `model_type`
        ),
        KEY `model_has_permissions_model_id_model_type_index` (`model_id`, `model_type`),
        CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table shipyard.model_has_permissions: ~0 rows (approximately)

-- Dumping structure for table shipyard.model_has_roles

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE
    IF NOT EXISTS `model_has_roles` (
        `role_id` bigint(20) unsigned NOT NULL,
        `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `model_id` bigint(20) unsigned NOT NULL,
        PRIMARY KEY (
            `role_id`,
            `model_id`,
            `model_type`
        ),
        KEY `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
        CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table shipyard.model_has_roles: ~8 rows (approximately)

INSERT INTO
    `model_has_roles` (
        `role_id`,
        `model_type`,
        `model_id`
    )
VALUES (1, 'App\\Models\\User', 1), (2, 'App\\Models\\User', 2), (3, 'App\\Models\\User', 3), (4, 'App\\Models\\User', 4), (5, 'App\\Models\\User', 5), (6, 'App\\Models\\User', 6), (5, 'App\\Models\\User', 7), (5, 'App\\Models\\User', 8), (5, 'App\\Models\\User', 9), (5, 'App\\Models\\User', 10);

-- Dumping structure for table shipyard.permissions

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE
    IF NOT EXISTS `permissions` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `feature_id` bigint(20) unsigned NOT NULL,
        `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `description` text COLLATE utf8mb4_unicode_ci,
        `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
    ) ENGINE = InnoDB AUTO_INCREMENT = 152 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table shipyard.permissions: ~151 rows (approximately)

INSERT INTO
    `permissions` (
        `id`,
        `feature_id`,
        `name`,
        `description`,
        `guard_name`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        1,
        'lihat dashboard',
        'lihat dashboard',
        'web',
        NULL,
        NULL
    ), (
        2,
        1,
        'tambah dashboard',
        'tambah dashboard',
        'web',
        NULL,
        NULL
    ), (
        3,
        1,
        'ubah dashboard',
        'ubah dashboard',
        'web',
        NULL,
        NULL
    ), (
        4,
        1,
        'hapus dashboard',
        'hapus dashboard',
        'web',
        NULL,
        NULL
    ), (
        5,
        2,
        'lihat absensi',
        'lihat absensi',
        'web',
        NULL,
        NULL
    ), (
        6,
        2,
        'tambah absensi',
        'tambah absensi',
        'web',
        NULL,
        NULL
    ), (
        7,
        2,
        'ubah absensi',
        'ubah absensi',
        'web',
        NULL,
        NULL
    ), (
        8,
        2,
        'hapus absensi',
        'hapus absensi',
        'web',
        NULL,
        NULL
    ), (
        9,
        3,
        'lihat roster',
        'lihat roster',
        'web',
        NULL,
        NULL
    ), (
        10,
        3,
        'tambah roster',
        'tambah roster',
        'web',
        NULL,
        NULL
    ), (
        11,
        3,
        'ubah roster',
        'ubah roster',
        'web',
        NULL,
        NULL
    ), (
        12,
        3,
        'hapus roster',
        'hapus roster',
        'web',
        NULL,
        NULL
    ), (
        13,
        4,
        'lihat kasbon',
        'lihat kasbon',
        'web',
        NULL,
        NULL
    ), (
        14,
        4,
        'tambah kasbon',
        'tambah kasbon',
        'web',
        NULL,
        NULL
    ), (
        15,
        4,
        'ubah kasbon',
        'ubah kasbon',
        'web',
        NULL,
        NULL
    ), (
        16,
        4,
        'hapus kasbon',
        'hapus kasbon',
        'web',
        NULL,
        NULL
    ), (
        17,
        5,
        'lihat slip gaji',
        'lihat slip gaji',
        'web',
        NULL,
        NULL
    ), (
        18,
        5,
        'tambah slip gaji',
        'tambah slip gaji',
        'web',
        NULL,
        NULL
    ), (
        19,
        5,
        'ubah slip gaji',
        'ubah slip gaji',
        'web',
        NULL,
        NULL
    ), (
        20,
        5,
        'hapus slip gaji',
        'hapus slip gaji',
        'web',
        NULL,
        NULL
    ), (
        21,
        6,
        'lihat penggajian',
        'lihat penggajian',
        'web',
        NULL,
        NULL
    ), (
        22,
        6,
        'tambah penggajian',
        'tambah penggajian',
        'web',
        NULL,
        NULL
    ), (
        23,
        6,
        'ubah penggajian',
        'ubah penggajian',
        'web',
        NULL,
        NULL
    ), (
        24,
        6,
        'hapus penggajian',
        'hapus penggajian',
        'web',
        NULL,
        NULL
    ), (
        25,
        7,
        'lihat proyek',
        'lihat proyek',
        'web',
        NULL,
        NULL
    ), (
        26,
        7,
        'tambah proyek',
        'tambah proyek',
        'web',
        NULL,
        NULL
    ), (
        27,
        7,
        'ubah proyek',
        'ubah proyek',
        'web',
        NULL,
        NULL
    ), (
        28,
        7,
        'hapus proyek',
        'hapus proyek',
        'web',
        NULL,
        NULL
    ), (
        29,
        8,
        'lihat job order',
        'lihat job order',
        'web',
        NULL,
        NULL
    ), (
        30,
        8,
        'tambah job order',
        'tambah job order',
        'web',
        NULL,
        NULL
    ), (
        31,
        8,
        'ubah job order',
        'ubah job order',
        'web',
        NULL,
        NULL
    ), (
        32,
        8,
        'hapus job order',
        'hapus job order',
        'web',
        NULL,
        NULL
    ), (
        33,
        9,
        'lihat cuti',
        'lihat cuti',
        'web',
        NULL,
        NULL
    ), (
        34,
        9,
        'tambah cuti',
        'tambah cuti',
        'web',
        NULL,
        NULL
    ), (
        35,
        9,
        'ubah cuti',
        'ubah cuti',
        'web',
        NULL,
        NULL
    ), (
        36,
        9,
        'hapus cuti',
        'hapus cuti',
        'web',
        NULL,
        NULL
    ), (
        37,
        10,
        'lihat laporan job order',
        'lihat laporan job order',
        'web',
        NULL,
        NULL
    ), (
        38,
        10,
        'tambah laporan job order',
        'tambah laporan job order',
        'web',
        NULL,
        NULL
    ), (
        39,
        10,
        'ubah laporan job order',
        'ubah laporan job order',
        'web',
        NULL,
        NULL
    ), (
        40,
        10,
        'hapus laporan job order',
        'hapus laporan job order',
        'web',
        NULL,
        NULL
    ), (
        41,
        11,
        'lihat laporan kasbon',
        'lihat laporan kasbon',
        'web',
        NULL,
        NULL
    ), (
        42,
        11,
        'tambah laporan kasbon',
        'tambah laporan kasbon',
        'web',
        NULL,
        NULL
    ), (
        43,
        11,
        'ubah laporan kasbon',
        'ubah laporan kasbon',
        'web',
        NULL,
        NULL
    ), (
        44,
        11,
        'hapus laporan kasbon',
        'hapus laporan kasbon',
        'web',
        NULL,
        NULL
    ), (
        45,
        12,
        'lihat laporan surat perintah lembur',
        'lihat laporan surat perintah lembur',
        'web',
        NULL,
        NULL
    ), (
        46,
        12,
        'tambah laporan surat perintah lembur',
        'tambah laporan surat perintah lembur',
        'web',
        NULL,
        NULL
    ), (
        47,
        12,
        'ubah laporan surat perintah lembur',
        'ubah laporan surat perintah lembur',
        'web',
        NULL,
        NULL
    ), (
        48,
        12,
        'hapus laporan surat perintah lembur',
        'hapus laporan surat perintah lembur',
        'web',
        NULL,
        NULL
    ), (
        49,
        13,
        'lihat laporan cuti',
        'lihat laporan cuti',
        'web',
        NULL,
        NULL
    ), (
        50,
        13,
        'tambah laporan cuti',
        'tambah laporan cuti',
        'web',
        NULL,
        NULL
    ), (
        51,
        13,
        'ubah laporan cuti',
        'ubah laporan cuti',
        'web',
        NULL,
        NULL
    ), (
        52,
        13,
        'hapus laporan cuti',
        'hapus laporan cuti',
        'web',
        NULL,
        NULL
    ), (
        53,
        14,
        'lihat departemen',
        'lihat departemen',
        'web',
        NULL,
        NULL
    ), (
        54,
        14,
        'tambah departemen',
        'tambah departemen',
        'web',
        NULL,
        NULL
    ), (
        55,
        14,
        'ubah departemen',
        'ubah departemen',
        'web',
        NULL,
        NULL
    ), (
        56,
        14,
        'hapus departemen',
        'hapus departemen',
        'web',
        NULL,
        NULL
    ), (
        57,
        15,
        'lihat jabatan',
        'lihat jabatan',
        'web',
        NULL,
        NULL
    ), (
        58,
        15,
        'tambah jabatan',
        'tambah jabatan',
        'web',
        NULL,
        NULL
    ), (
        59,
        15,
        'ubah jabatan',
        'ubah jabatan',
        'web',
        NULL,
        NULL
    ), (
        60,
        15,
        'hapus jabatan',
        'hapus jabatan',
        'web',
        NULL,
        NULL
    ), (
        61,
        16,
        'lihat perusahaan',
        'lihat perusahaan',
        'web',
        NULL,
        NULL
    ), (
        62,
        16,
        'tambah perusahaan',
        'tambah perusahaan',
        'web',
        NULL,
        NULL
    ), (
        63,
        16,
        'ubah perusahaan',
        'ubah perusahaan',
        'web',
        NULL,
        NULL
    ), (
        64,
        16,
        'hapus perusahaan',
        'hapus perusahaan',
        'web',
        NULL,
        NULL
    ), (
        65,
        17,
        'lihat jenis karyawan',
        'lihat jenis karyawan',
        'web',
        NULL,
        NULL
    ), (
        66,
        17,
        'tambah jenis karyawan',
        'tambah jenis karyawan',
        'web',
        NULL,
        NULL
    ), (
        67,
        17,
        'ubah jenis karyawan',
        'ubah jenis karyawan',
        'web',
        NULL,
        NULL
    ), (
        68,
        17,
        'hapus jenis karyawan',
        'hapus jenis karyawan',
        'web',
        NULL,
        NULL
    ), (
        69,
        18,
        'lihat kapal',
        'lihat kapal',
        'web',
        NULL,
        NULL
    ), (
        70,
        18,
        'tambah kapal',
        'tambah kapal',
        'web',
        NULL,
        NULL
    ), (
        71,
        18,
        'ubah kapal',
        'ubah kapal',
        'web',
        NULL,
        NULL
    ), (
        72,
        18,
        'hapus kapal',
        'hapus kapal',
        'web',
        NULL,
        NULL
    ), (
        73,
        19,
        'lihat lokasi',
        'lihat lokasi',
        'web',
        NULL,
        NULL
    ), (
        74,
        19,
        'tambah lokasi',
        'tambah lokasi',
        'web',
        NULL,
        NULL
    ), (
        75,
        19,
        'ubah lokasi',
        'ubah lokasi',
        'web',
        NULL,
        NULL
    ), (
        76,
        19,
        'hapus lokasi',
        'hapus lokasi',
        'web',
        NULL,
        NULL
    ), (
        77,
        20,
        'lihat bahan',
        'lihat bahan',
        'web',
        NULL,
        NULL
    ), (
        78,
        20,
        'tambah bahan',
        'tambah bahan',
        'web',
        NULL,
        NULL
    ), (
        79,
        20,
        'ubah bahan',
        'ubah bahan',
        'web',
        NULL,
        NULL
    ), (
        80,
        20,
        'hapus bahan',
        'hapus bahan',
        'web',
        NULL,
        NULL
    ), (
        81,
        21,
        'lihat kategori job order',
        'lihat kategori job order',
        'web',
        NULL,
        NULL
    ), (
        82,
        21,
        'tambah kategori job order',
        'tambah kategori job order',
        'web',
        NULL,
        NULL
    ), (
        83,
        21,
        'ubah kategori job order',
        'ubah kategori job order',
        'web',
        NULL,
        NULL
    ), (
        84,
        21,
        'hapus kategori job order',
        'hapus kategori job order',
        'web',
        NULL,
        NULL
    ), (
        85,
        22,
        'lihat jadwal kerja',
        'lihat jadwal kerja',
        'web',
        NULL,
        NULL
    ), (
        86,
        22,
        'tambah jadwal kerja',
        'tambah jadwal kerja',
        'web',
        NULL,
        NULL
    ), (
        87,
        22,
        'ubah jadwal kerja',
        'ubah jadwal kerja',
        'web',
        NULL,
        NULL
    ), (
        88,
        22,
        'hapus jadwal kerja',
        'hapus jadwal kerja',
        'web',
        NULL,
        NULL
    ), (
        89,
        23,
        'lihat jenis pekerjaan',
        'lihat jenis pekerjaan',
        'web',
        NULL,
        NULL
    ), (
        90,
        23,
        'tambah jenis pekerjaan',
        'tambah jenis pekerjaan',
        'web',
        NULL,
        NULL
    ), (
        91,
        23,
        'ubah jenis pekerjaan',
        'ubah jenis pekerjaan',
        'web',
        NULL,
        NULL
    ), (
        92,
        23,
        'hapus jenis pekerjaan',
        'hapus jenis pekerjaan',
        'web',
        NULL,
        NULL
    ), (
        93,
        24,
        'lihat karyawan',
        'lihat karyawan',
        'web',
        NULL,
        NULL
    ), (
        94,
        24,
        'tambah karyawan',
        'tambah karyawan',
        'web',
        NULL,
        NULL
    ), (
        95,
        24,
        'ubah karyawan',
        'ubah karyawan',
        'web',
        NULL,
        NULL
    ), (
        96,
        24,
        'hapus karyawan',
        'hapus karyawan',
        'web',
        NULL,
        NULL
    ), (
        97,
        25,
        'lihat pelanggan',
        'lihat pelanggan',
        'web',
        NULL,
        NULL
    ), (
        98,
        25,
        'tambah pelanggan',
        'tambah pelanggan',
        'web',
        NULL,
        NULL
    ), (
        99,
        25,
        'ubah pelanggan',
        'ubah pelanggan',
        'web',
        NULL,
        NULL
    ), (
        100,
        25,
        'hapus pelanggan',
        'hapus pelanggan',
        'web',
        NULL,
        NULL
    ), (
        101,
        26,
        'lihat alat finger',
        'lihat alat finger',
        'web',
        NULL,
        NULL
    ), (
        102,
        26,
        'tambah alat finger',
        'tambah alat finger',
        'web',
        NULL,
        NULL
    ), (
        103,
        26,
        'ubah alat finger',
        'ubah alat finger',
        'web',
        NULL,
        NULL
    ), (
        104,
        26,
        'hapus alat finger',
        'hapus alat finger',
        'web',
        NULL,
        NULL
    ), (
        105,
        27,
        'lihat penyesuaian gaji',
        'lihat penyesuaian gaji',
        'web',
        NULL,
        NULL
    ), (
        106,
        27,
        'tambah penyesuaian gaji',
        'tambah penyesuaian gaji',
        'web',
        NULL,
        NULL
    ), (
        107,
        27,
        'ubah penyesuaian gaji',
        'ubah penyesuaian gaji',
        'web',
        NULL,
        NULL
    ), (
        108,
        27,
        'hapus penyesuaian gaji',
        'hapus penyesuaian gaji',
        'web',
        NULL,
        NULL
    ), (
        109,
        28,
        'lihat jam kerja',
        'lihat jam kerja',
        'web',
        NULL,
        NULL
    ), (
        110,
        28,
        'tambah jam kerja',
        'tambah jam kerja',
        'web',
        NULL,
        NULL
    ), (
        111,
        28,
        'ubah jam kerja',
        'ubah jam kerja',
        'web',
        NULL,
        NULL
    ), (
        112,
        28,
        'hapus jam kerja',
        'hapus jam kerja',
        'web',
        NULL,
        NULL
    ), (
        113,
        29,
        'lihat pengguna',
        'lihat pengguna',
        'web',
        NULL,
        NULL
    ), (
        114,
        29,
        'tambah pengguna',
        'tambah pengguna',
        'web',
        NULL,
        NULL
    ), (
        115,
        29,
        'ubah pengguna',
        'ubah pengguna',
        'web',
        NULL,
        NULL
    ), (
        116,
        29,
        'hapus pengguna',
        'hapus pengguna',
        'web',
        NULL,
        NULL
    ), (
        117,
        30,
        'lihat hak akses',
        'lihat hak akses',
        'web',
        NULL,
        NULL
    ), (
        118,
        30,
        'tambah hak akses',
        'tambah hak akses',
        'web',
        NULL,
        NULL
    ), (
        119,
        30,
        'ubah hak akses',
        'ubah hak akses',
        'web',
        NULL,
        NULL
    ), (
        120,
        30,
        'hapus hak akses',
        'hapus hak akses',
        'web',
        NULL,
        NULL
    ), (
        121,
        31,
        'lihat fitur',
        'lihat fitur',
        'web',
        NULL,
        NULL
    ), (
        122,
        31,
        'tambah fitur',
        'tambah fitur',
        'web',
        NULL,
        NULL
    ), (
        123,
        31,
        'ubah fitur',
        'ubah fitur',
        'web',
        NULL,
        NULL
    ), (
        124,
        31,
        'hapus fitur',
        'hapus fitur',
        'web',
        NULL,
        NULL
    ), (
        125,
        32,
        'lihat perhitungan bpjs',
        'lihat perhitungan bpjs',
        'web',
        NULL,
        NULL
    ), (
        126,
        32,
        'tambah perhitungan bpjs',
        'tambah perhitungan bpjs',
        'web',
        NULL,
        NULL
    ), (
        127,
        32,
        'ubah perhitungan bpjs',
        'ubah perhitungan bpjs',
        'web',
        NULL,
        NULL
    ), (
        128,
        32,
        'hapus perhitungan bpjs',
        'hapus perhitungan bpjs',
        'web',
        NULL,
        NULL
    ), (
        129,
        33,
        'lihat dasar upah bpjs',
        'lihat dasar upah bpjs',
        'web',
        NULL,
        NULL
    ), (
        130,
        33,
        'tambah dasar upah bpjs',
        'tambah dasar upah bpjs',
        'web',
        NULL,
        NULL
    ), (
        131,
        33,
        'ubah dasar upah bpjs',
        'ubah dasar upah bpjs',
        'web',
        NULL,
        NULL
    ), (
        132,
        33,
        'hapus dasar upah bpjs',
        'hapus dasar upah bpjs',
        'web',
        NULL,
        NULL
    ), (
        133,
        34,
        'lihat grup pengguna',
        'lihat grup pengguna',
        'web',
        NULL,
        NULL
    ), (
        134,
        34,
        'tambah grup pengguna',
        'tambah grup pengguna',
        'web',
        NULL,
        NULL
    ), (
        135,
        34,
        'ubah grup pengguna',
        'ubah grup pengguna',
        'web',
        NULL,
        NULL
    ), (
        136,
        34,
        'hapus grup pengguna',
        'hapus grup pengguna',
        'web',
        NULL,
        NULL
    ), (
        137,
        35,
        'lihat tingkat persetujuan',
        'lihat tingkat persetujuan',
        'web',
        NULL,
        NULL
    ), (
        138,
        35,
        'tambah tingkat persetujuan',
        'tambah tingkat persetujuan',
        'web',
        NULL,
        NULL
    ), (
        139,
        35,
        'ubah tingkat persetujuan',
        'ubah tingkat persetujuan',
        'web',
        NULL,
        NULL
    ), (
        140,
        35,
        'hapus tingkat persetujuan',
        'hapus tingkat persetujuan',
        'web',
        NULL,
        NULL
    ), (
        141,
        7,
        'detail fitur',
        '',
        'web',
        NULL,
        NULL
    ), (
        142,
        7,
        'detail grup pengguna',
        '',
        'web',
        NULL,
        NULL
    ), (
        143,
        8,
        'detail proyek',
        '',
        'web',
        NULL,
        NULL
    ), (
        144,
        8,
        'proyek job order',
        '',
        'web',
        NULL,
        NULL
    ), (
        145,
        4,
        'ekspor laporan kasbon',
        '',
        'web',
        NULL,
        NULL
    ), (
        146,
        4,
        'perwakilan laporan kasbon',
        '',
        'web',
        NULL,
        NULL
    ), (
        147,
        4,
        'persetujuan laporan kasbon',
        '',
        'web',
        NULL,
        NULL
    ), (
        148,
        11,
        'detail laporan surat perintah lembur',
        '',
        'web',
        NULL,
        NULL
    ), (
        149,
        11,
        'ekspor laporan surat perintah lembur',
        '',
        'web',
        NULL,
        NULL
    ), (
        150,
        9,
        'ekspor laporan job order',
        '',
        'web',
        NULL,
        NULL
    ), (
        151,
        13,
        'ekspor laporan cuti',
        '',
        'web',
        NULL,
        NULL
    );

-- Dumping structure for table shipyard.roles

DROP TABLE IF EXISTS `roles`;

CREATE TABLE
    IF NOT EXISTS `roles` (
        `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
    ) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table shipyard.roles: ~6 rows (approximately)

INSERT INTO
    `roles` (
        `id`,
        `name`,
        `guard_name`,
        `created_at`,
        `updated_at`
    )
VALUES (
        1,
        'Super Admin',
        'web',
        '2023-06-19 00:14:32',
        '2023-06-19 00:14:32'
    ), (
        2,
        'Admin',
        'web',
        '2023-06-19 00:14:33',
        '2023-06-19 00:14:33'
    ), (
        3,
        'HRD',
        'web',
        '2023-06-19 00:14:33',
        '2023-06-19 00:14:33'
    ), (
        4,
        'Kasir',
        'web',
        '2023-06-19 00:14:33',
        '2023-06-19 00:14:33'
    ), (
        5,
        'Pengawas',
        'web',
        '2023-06-19 00:14:33',
        '2023-06-19 00:14:33'
    ), (
        6,
        'Quality Control',
        'web',
        '2023-06-19 00:14:33',
        '2023-06-19 00:14:33'
    );

-- Dumping structure for table shipyard.role_has_permissions

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE
    IF NOT EXISTS `role_has_permissions` (
        `permission_id` bigint(20) unsigned NOT NULL,
        `role_id` bigint(20) unsigned NOT NULL,
        PRIMARY KEY (`permission_id`, `role_id`),
        KEY `role_has_permissions_role_id_foreign` (`role_id`),
        CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
        CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- Dumping data for table shipyard.role_has_permissions: ~307 rows (approximately)

INSERT INTO
    `role_has_permissions` (`permission_id`, `role_id`)
VALUES (1, 1), (2, 1), (3, 1), (4, 1), (5, 1), (6, 1), (7, 1), (8, 1), (9, 1), (10, 1), (11, 1), (12, 1), (13, 1), (14, 1), (15, 1), (16, 1), (17, 1), (18, 1), (19, 1), (20, 1), (21, 1), (22, 1), (23, 1), (24, 1), (25, 1), (26, 1), (27, 1), (28, 1), (29, 1), (30, 1), (31, 1), (32, 1), (33, 1), (34, 1), (35, 1), (36, 1), (37, 1), (38, 1), (39, 1), (40, 1), (41, 1), (42, 1), (43, 1), (44, 1), (45, 1), (46, 1), (47, 1), (48, 1), (49, 1), (50, 1), (51, 1), (52, 1), (53, 1), (54, 1), (55, 1), (56, 1), (57, 1), (58, 1), (59, 1), (60, 1), (61, 1), (62, 1), (63, 1), (64, 1), (65, 1), (66, 1), (67, 1), (68, 1), (69, 1), (70, 1), (71, 1), (72, 1), (73, 1), (74, 1), (75, 1), (76, 1), (77, 1), (78, 1), (79, 1), (80, 1), (81, 1), (82, 1), (83, 1), (84, 1), (85, 1), (86, 1), (87, 1), (88, 1), (89, 1), (90, 1), (91, 1), (92, 1), (93, 1), (94, 1), (95, 1), (96, 1), (97, 1), (98, 1), (99, 1), (100, 1), (101, 1), (102, 1), (103, 1), (104, 1), (105, 1), (106, 1), (107, 1), (108, 1), (109, 1), (110, 1), (111, 1), (112, 1), (113, 1), (114, 1), (115, 1), (116, 1), (117, 1), (118, 1), (119, 1), (120, 1), (121, 1), (122, 1), (123, 1), (124, 1), (125, 1), (126, 1), (127, 1), (128, 1), (129, 1), (130, 1), (131, 1), (132, 1), (133, 1), (134, 1), (135, 1), (136, 1), (137, 1), (138, 1), (139, 1), (140, 1), (141, 1), (142, 1), (143, 1), (144, 1), (145, 1), (146, 1), (147, 1), (148, 1), (149, 1), (150, 1), (151, 1), (1, 2), (2, 2), (3, 2), (4, 2), (5, 2), (6, 2), (7, 2), (8, 2), (9, 2), (10, 2), (11, 2), (12, 2), (13, 2), (14, 2), (15, 2), (16, 2), (17, 2), (18, 2), (19, 2), (20, 2), (21, 2), (22, 2), (23, 2), (24, 2), (25, 2), (26, 2), (27, 2), (28, 2), (29, 2), (30, 2), (31, 2), (32, 2), (33, 2), (34, 2), (35, 2), (36, 2), (37, 2), (38, 2), (39, 2), (40, 2), (41, 2), (42, 2), (43, 2), (44, 2), (45, 2), (46, 2), (47, 2), (48, 2), (49, 2), (50, 2), (51, 2), (52, 2), (54, 2), (55, 2), (56, 2), (57, 2), (58, 2), (59, 2), (60, 2), (61, 2), (62, 2), (63, 2), (64, 2), (66, 2), (67, 2), (68, 2), (69, 2), (70, 2), (71, 2), (72, 2), (74, 2), (75, 2), (76, 2), (78, 2), (79, 2), (80, 2), (81, 2), (82, 2), (83, 2), (84, 2), (86, 2), (87, 2), (88, 2), (89, 2), (90, 2), (91, 2), (92, 2), (93, 2), (94, 2), (95, 2), (96, 2), (97, 2), (98, 2), (99, 2), (100, 2), (101, 2), (102, 2), (103, 2), (104, 2), (105, 2), (106, 2), (107, 2), (108, 2), (110, 2), (111, 2), (112, 2), (113, 2), (114, 2), (115, 2), (116, 2), (117, 2), (118, 2), (119, 2), (121, 2), (122, 2), (123, 2), (124, 2), (125, 2), (126, 2), (127, 2), (128, 2), (129, 2), (130, 2), (131, 2), (132, 2), (133, 2), (134, 2), (135, 2), (136, 2), (138, 2), (139, 2), (140, 2), (141, 2), (142, 2), (143, 2), (144, 2), (145, 2), (146, 2), (147, 2), (148, 2), (149, 2), (150, 2), (151, 2), (1, 3), (41, 3), (146, 3), (147, 3), (1, 4), (41, 4), (146, 4), (147, 4), (1, 5), (13, 5), (14, 5), (15, 5), (16, 5), (29, 5), (30, 5), (33, 5), (34, 5), (35, 5), (36, 5), (1, 6), (29, 6);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */

;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */

;

/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */

;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */

;

/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */

;