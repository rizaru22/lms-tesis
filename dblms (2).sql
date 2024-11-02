-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2024 at 04:07 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dblms`
--

-- --------------------------------------------------------

--
-- Table structure for table `absens`
--

CREATE TABLE `absens` (
  `id` bigint UNSIGNED NOT NULL,
  `pertemuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT NULL,
  `rangkuman` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `berita_acara` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jadwal_id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `siswa_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `absens`
--

INSERT INTO `absens` (`id`, `pertemuan`, `parent`, `status`, `rangkuman`, `berita_acara`, `jadwal_id`, `guru_id`, `siswa_id`, `created_at`, `updated_at`) VALUES
(18, '1', '0', NULL, 'Pertemuan 1', 'Absensi kelas', 4, 3, NULL, '2024-06-28 07:16:53', '2024-06-28 07:16:53'),
(19, '1', '18', 1, 'Pertemuan 1', 'Absensi kelas', 4, NULL, 1, '2024-06-28 07:16:53', '2024-06-28 07:20:10'),
(20, '1', '18', NULL, 'Pertemuan 1', 'Absensi kelas', 4, NULL, 13, '2024-06-28 07:16:53', '2024-06-28 07:16:53'),
(21, '2', '0', NULL, 'eee', 'eeee', 4, 3, NULL, '2024-06-28 18:04:20', '2024-06-28 18:04:20'),
(22, '2', '21', 1, 'eee', 'eeee', 4, NULL, 1, '2024-06-28 18:04:20', '2024-06-28 18:09:38'),
(23, '2', '21', NULL, 'eee', 'eeee', 4, NULL, 13, '2024-06-28 18:04:20', '2024-06-28 18:04:20');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `group_label_permissions`
--

CREATE TABLE `group_label_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `label_permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `group_label_permissions`
--

INSERT INTO `group_label_permissions` (`id`, `permission_id`, `label_permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(2, 2, 1, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(3, 3, 1, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(4, 4, 1, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(5, 5, 2, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(6, 6, 2, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(7, 7, 2, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(8, 8, 2, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(9, 9, 3, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(10, 10, 3, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(11, 11, 3, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(12, 12, 3, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(13, 13, 4, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(14, 14, 4, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(15, 15, 4, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(16, 16, 4, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(17, 17, 5, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(18, 18, 5, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(19, 19, 5, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(20, 20, 5, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(21, 21, 6, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(22, 22, 6, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(23, 23, 6, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(24, 24, 6, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(25, 25, 7, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(26, 26, 7, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(27, 27, 7, '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(28, 28, 7, '2024-05-30 08:36:16', '2024-05-30 08:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `gurus`
--

CREATE TABLE `gurus` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `gurus`
--

INSERT INTO `gurus` (`id`, `nama`, `nip`, `kode`, `email`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 'Pirman, SST', '11111111', 'PI0S', 'pir@gmail.com', 9, '2024-05-30 08:56:55', '2024-05-30 08:56:55');

-- --------------------------------------------------------

--
-- Table structure for table `guru_kelas`
--

CREATE TABLE `guru_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `mapel_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `guru_kelas`
--

INSERT INTO `guru_kelas` (`id`, `kelas_id`, `guru_id`, `mapel_id`, `created_at`, `updated_at`) VALUES
(2, 11, 3, NULL, '2024-05-30 08:56:55', '2024-05-30 08:56:55'),
(6, 11, 3, 2, '2024-06-28 07:15:25', '2024-06-28 07:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `guru_mapel`
--

CREATE TABLE `guru_mapel` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `mapel_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `guru_mapel`
--

INSERT INTO `guru_mapel` (`id`, `guru_id`, `mapel_id`, `created_at`, `updated_at`) VALUES
(2, 3, 2, '2024-05-30 08:56:55', '2024-05-30 08:56:55');

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint UNSIGNED NOT NULL,
  `hari` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `started_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ended_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `mapel_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `hari`, `started_at`, `ended_at`, `guru_id`, `kelas_id`, `mapel_id`, `created_at`, `updated_at`) VALUES
(4, 'Minggu', '00:15', '23:59', 3, 11, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_ujians`
--

CREATE TABLE `jadwal_ujians` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal_ujian` date NOT NULL,
  `status_ujian` enum('aktif','nonaktif','draft') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nonaktif',
  `started_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ended_at` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guru_can_manage` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `guru_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `mapel_id` bigint UNSIGNED NOT NULL,
  `ujian_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jadwal_ujians`
--

INSERT INTO `jadwal_ujians` (`id`, `tanggal_ujian`, `status_ujian`, `started_at`, `ended_at`, `guru_can_manage`, `guru_id`, `kelas_id`, `mapel_id`, `ujian_id`, `created_at`, `updated_at`) VALUES
(2, '2024-06-27', 'draft', '22:20', '23:00', '1', 3, 11, 2, NULL, '2024-06-27 07:19:56', '2024-06-27 08:48:27');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `kode`, `created_at`, `updated_at`) VALUES
(11, 'XI RPL12', '2024-05-30 08:49:13', '2024-06-30 18:17:27');

-- --------------------------------------------------------

--
-- Table structure for table `label_permissions`
--

CREATE TABLE `label_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `label_permissions`
--

INSERT INTO `label_permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'user', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(2, 'role', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(3, 'permission', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(4, 'programkeahlian', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(5, 'prodi', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(6, 'matapelajaran', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(7, 'kelas', '2024-05-30 08:36:16', '2024-05-30 08:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `mapels`
--

CREATE TABLE `mapels` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `mapels`
--

INSERT INTO `mapels` (`id`, `nama`, `kode`, `jam`, `created_at`, `updated_at`) VALUES
(2, 'Pemrograman Web', 'PW-75', 9, '2024-05-30 08:36:16', '2024-05-30 09:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `materis`
--

CREATE TABLE `materis` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_or_link` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pertemuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `mapel_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `materis`
--

INSERT INTO `materis` (`id`, `judul`, `tipe`, `file_or_link`, `pertemuan`, `deskripsi`, `guru_id`, `kelas_id`, `mapel_id`, `created_at`, `updated_at`) VALUES
(9, 'wwwwwwww', 'pdf', 'XI_RPL1_PW-75_P2_793.pdf', '2', 'ffsdfsdfsdf', 3, 11, 2, '2024-06-28 18:04:44', '2024-06-28 18:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_11_28_194859_create_permission_tables', 1),
(6, '2022_11_29_015122_create_programkeahlian_table', 1),
(7, '2022_11_29_015715_create_prodis_table', 1),
(8, '2022_11_29_015716_create_prodi_programkeahlian_table', 1),
(9, '2022_11_29_015816_create_mapels_table', 1),
(10, '2022_11_29_015829_create_kelas_table', 1),
(11, '2022_11_29_025632_create_gurus_table', 1),
(12, '2022_11_29_025655_create_siswas_table', 1),
(13, '2022_11_29_025901_create_jadwals_table', 1),
(14, '2022_11_29_025925_create_materis_table', 1),
(15, '2022_11_29_025937_create_tugas_table', 1),
(16, '2022_11_29_025938_create_nilai_tugas_table', 1),
(17, '2022_11_29_025950_create_absens_table', 1),
(18, '2022_11_29_030220_create_label_permissions_table', 1),
(19, '2022_11_29_030240_create_group_label_permissions_table', 1),
(20, '2022_11_29_032312_create_guru_kelas_table', 1),
(21, '2022_11_29_032847_create_siswa_kelas_table', 1),
(22, '2022_11_29_041214_create_guru_mapels_table', 1),
(23, '2023_01_06_233714_create_jadwal_ujians_table', 1),
(24, '2023_01_07_033443_create_ujians_table', 1),
(25, '2023_01_09_163100_create_ujian_siswas_table', 1),
(26, '2023_01_12_171134_create_soal_ujian_pgs_table', 1),
(27, '2023_01_12_171214_create_soal_ujian_essays_table', 1),
(28, '2023_01_12_184819_create_ujian_siswa_hasils_table', 1),
(29, '2023_02_27_045655_create_media_table', 1),
(30, '2023_11_18_065124_create_ortus_table', 1),
(31, '2023_11_20_203244_roolback', 1),
(32, '2023_11_20_212734_roolback', 1),
(33, '2023_12_02_182258_create_ortu_kelas_table', 1),
(34, '2023_12_02_185818_create_ortu_mapels_table', 1),
(35, '2023_12_02_193535_create_mapel_ortus_table', 1),
(36, '2024_03_27_173103_create_nilaisiswas_table', 1),
(37, '2024_06_30_232302_add_ortus_to_siswas_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 24),
(4, 'App\\Models\\User', 25),
(4, 'App\\Models\\User', 26);

-- --------------------------------------------------------

--
-- Table structure for table `nilai_tugas`
--

CREATE TABLE `nilai_tugas` (
  `id` bigint UNSIGNED NOT NULL,
  `nilai` int NOT NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `tugas_id` bigint UNSIGNED DEFAULT NULL,
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `siswa_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `ortus`
--

CREATE TABLE `ortus` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nohp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `ortus`
--

INSERT INTO `ortus` (`id`, `nama`, `nik`, `kode`, `alamat`, `nohp`, `email`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'Ortu', '00009999', 'ORVX', 'Desa Wih Nareh1', '0853609500382', 'Ortu@gmail.com', 12, '2024-06-04 22:08:24', '2024-06-04 22:08:24'),
(3, 'Win', '00000001', 'WIHV', 'Desa Wih Nareh', '0853609500383', 'takengon@gmail.com', 25, '2024-06-28 07:24:37', '2024-06-28 07:24:37'),
(4, 'Naira', '00000005', 'NAJ8', 'Bukit', '085222440033', 'naira@gmail.com', 26, '2024-10-24 14:19:14', '2024-10-24 14:19:14');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'user_create', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(2, 'user_read', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(3, 'user_update', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(4, 'user_delete', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(5, 'role_create', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(6, 'role_read', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(7, 'role_update', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(8, 'role_delete', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(9, 'permission_create', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(10, 'permission_read', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(11, 'permission_update', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(12, 'permission_delete', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(13, 'programkeahlian_create', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(14, 'programkeahlian_read', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(15, 'programkeahlian_update', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(16, 'programkeahlian_delete', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(17, 'prodi_create', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(18, 'prodi_read', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(19, 'prodi_update', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(20, 'prodi_delete', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(21, 'matapelajaran_create', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(22, 'matapelajaran_read', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(23, 'matapelajaran_update', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(24, 'matapelajaran_delete', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(25, 'kelas_create', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(26, 'kelas_read', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(27, 'kelas_update', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(28, 'kelas_delete', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `prodis`
--

CREATE TABLE `prodis` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `prodi_programkeahlian`
--

CREATE TABLE `prodi_programkeahlian` (
  `id` bigint UNSIGNED NOT NULL,
  `prodi_id` bigint UNSIGNED NOT NULL,
  `programkeahlian_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `programkeahlian`
--

CREATE TABLE `programkeahlian` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `programkeahlian`
--

INSERT INTO `programkeahlian` (`id`, `nama`, `kode`, `created_at`, `updated_at`) VALUES
(1, 'Rekayasa Perangkat Lunak', 'RPL-70', '2024-05-30 08:58:55', '2024-05-30 08:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(2, 'guru', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(3, 'siswa', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16'),
(4, 'ortu', 'web', '2024-05-30 08:36:16', '2024-05-30 08:36:16');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `siswas`
--

CREATE TABLE `siswas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `programkeahlian_id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ortu_id` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `siswas`
--

INSERT INTO `siswas` (`id`, `nama`, `nis`, `email`, `user_id`, `programkeahlian_id`, `kelas_id`, `created_at`, `updated_at`, `ortu_id`) VALUES
(1, 'Pahlawan', '44444444', 'pahlawan@gmail.com', 10, 1, 11, '2024-05-30 08:59:17', '2024-05-30 08:59:17', 3),
(13, 'Dua satu', '21212121', 'duasatu@mail.com', 24, 1, 11, '2024-06-27 10:22:01', '2024-06-27 10:22:01', 2);

-- --------------------------------------------------------

--
-- Table structure for table `siswa_kelas`
--

CREATE TABLE `siswa_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `kelas_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `siswa_kelas`
--

INSERT INTO `siswa_kelas` (`id`, `kelas_id`, `siswa_id`, `created_at`, `updated_at`) VALUES
(1, 11, 1, '2024-05-30 08:59:17', '2024-05-30 08:59:17'),
(13, 11, 13, '2024-06-27 10:22:01', '2024-06-27 10:22:01');

-- --------------------------------------------------------

--
-- Table structure for table `soal_ujian_essays`
--

CREATE TABLE `soal_ujian_essays` (
  `id` bigint UNSIGNED NOT NULL,
  `nomer_soal` int NOT NULL,
  `pertanyaan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ujian_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `soal_ujian_pgs`
--

CREATE TABLE `soal_ujian_pgs` (
  `id` bigint UNSIGNED NOT NULL,
  `nomer_soal` int NOT NULL,
  `pertanyaan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_a` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_b` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_c` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_d` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pilihan_e` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jawaban_benar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ujian_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_or_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pertemuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pengumpulan` datetime DEFAULT NULL,
  `sudah_dinilai` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mapel_id` bigint UNSIGNED NOT NULL,
  `jadwal_id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `siswa_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `ujians`
--

CREATE TABLE `ujians` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `durasi_ujian` int NOT NULL,
  `semester` int DEFAULT NULL,
  `tipe_ujian` enum('uas','uts') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe_soal` enum('essay','pilihan_ganda') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `random_soal` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lihat_hasil` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jadwal_ujian_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `ujian_siswas`
--

CREATE TABLE `ujian_siswas` (
  `id` bigint UNSIGNED NOT NULL,
  `ujian_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `started_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `nilai` decimal(8,2) DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `ujian_siswa_hasils`
--

CREATE TABLE `ujian_siswa_hasils` (
  `id` bigint UNSIGNED NOT NULL,
  `jawaban` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ragu` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `skor` bigint DEFAULT NULL,
  `komentar_guru` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `guru_id` bigint UNSIGNED DEFAULT NULL,
  `ujian_siswa_id` bigint UNSIGNED NOT NULL,
  `soal_ujian_pg_id` bigint UNSIGNED DEFAULT NULL,
  `soal_ujian_essay_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_induk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'avatar.png',
  `last_seen` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `no_induk`, `foto`, `last_seen`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@mail.com', '00000000', 'avatar.png', '2024-10-30 07:26:55', '2024-05-30 08:36:16', '$2y$10$P.2AQACgpViV3BnLEzDnP.JR6l4vVAeOYa10R9WZ07PhQkkJudWQq', NULL, '2024-05-30 08:36:16', '2024-10-30 07:26:55'),
(9, 'Pirman, SST', 'pir@gmail.com', '11111111', 'avatar.png', '2024-10-30 07:26:10', '2024-05-30 08:56:55', '$2y$10$Rfa08cHjK0USwvTQGKaFl.PRWSrg7qlnSsOnr4jlWXSnzXKF/SEWq', NULL, '2024-05-30 08:56:55', '2024-10-30 07:26:10'),
(10, 'Pahlawan', 'pahlawan@gmail.com', '44444444', 'avatar.png', '2024-06-28 18:09:20', NULL, '$2y$10$/8olz64ZMoDmleiQc/U4w.ch2mj7dtZnSathZHdBWY3wJcMP.vzJC', NULL, '2024-05-30 08:59:17', '2024-06-28 18:09:20'),
(12, 'Ortu', 'Ortu@gmail.com', '00009999', 'avatar.png', '2024-10-30 07:27:50', '2024-06-04 22:08:24', '$2y$10$8N74PMbw8AHLHDzZa0wnh.nbH1Xu7W6s8g65oo8/5rKW.ekh1fZpW', NULL, '2024-06-04 22:08:24', '2024-10-30 07:27:50'),
(24, 'Dua satu', 'duasatu@mail.com', '21212121', 'avatar.png', '2024-06-27 18:28:29', NULL, '$2y$10$3ScStjs4kUGZ41THvLpcLOigdAwhcbX43LF0mgWPIY0.5RCG.l83i', NULL, '2024-06-27 10:22:01', '2024-06-27 18:28:29'),
(25, 'Win', 'takengon@gmail.com', '00000001', 'avatar.png', '2024-10-30 07:25:38', '2024-06-28 07:24:37', '$2y$10$Pe3OMv8HTeI61TvBUnZApODDFm1zZheAFxlAq/RJglp4pKYFyibNa', NULL, '2024-06-28 07:24:37', '2024-10-30 07:25:38'),
(26, 'Naira', 'naira@gmail.com', '00000005', 'avatar.png', '2024-10-24 14:20:20', '2024-10-24 14:19:14', '$2y$10$1Fdgmx6Y2aErmqeNY/8muOBT6qtjJ7PLrsvjaSgz1wlkirxcLzH1.', NULL, '2024-10-24 14:19:14', '2024-10-24 14:20:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absens`
--
ALTER TABLE `absens`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `absens_jadwal_id_foreign` (`jadwal_id`) USING BTREE,
  ADD KEY `absens_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `absens_siswa_id_foreign` (`siswa_id`) USING BTREE;

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`) USING BTREE;

--
-- Indexes for table `group_label_permissions`
--
ALTER TABLE `group_label_permissions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `group_label_permissions_permission_id_foreign` (`permission_id`) USING BTREE,
  ADD KEY `group_label_permissions_label_permission_id_foreign` (`label_permission_id`) USING BTREE;

--
-- Indexes for table `gurus`
--
ALTER TABLE `gurus`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `gurus_nip_unique` (`nip`) USING BTREE,
  ADD UNIQUE KEY `gurus_kode_unique` (`kode`) USING BTREE,
  ADD UNIQUE KEY `gurus_email_unique` (`email`) USING BTREE,
  ADD KEY `gurus_user_id_foreign` (`user_id`) USING BTREE;

--
-- Indexes for table `guru_kelas`
--
ALTER TABLE `guru_kelas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `guru_kelas_kelas_id_foreign` (`kelas_id`) USING BTREE,
  ADD KEY `guru_kelas_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `guru_kelas_mapel_id_foreign` (`mapel_id`) USING BTREE;

--
-- Indexes for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `guru_mapel_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `guru_mapel_mapel_id_foreign` (`mapel_id`) USING BTREE;

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `jadwals_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `jadwals_kelas_id_foreign` (`kelas_id`) USING BTREE,
  ADD KEY `jadwals_mapel_id_foreign` (`mapel_id`) USING BTREE;

--
-- Indexes for table `jadwal_ujians`
--
ALTER TABLE `jadwal_ujians`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `jadwal_ujians_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `jadwal_ujians_kelas_id_foreign` (`kelas_id`) USING BTREE,
  ADD KEY `jadwal_ujians_mapel_id_foreign` (`mapel_id`) USING BTREE;

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `label_permissions`
--
ALTER TABLE `label_permissions`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `mapels`
--
ALTER TABLE `mapels`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `mapels_kode_unique` (`kode`) USING BTREE;

--
-- Indexes for table `materis`
--
ALTER TABLE `materis`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `materis_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `materis_kelas_id_foreign` (`kelas_id`) USING BTREE,
  ADD KEY `materis_mapel_id_foreign` (`mapel_id`) USING BTREE;

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`) USING BTREE,
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`) USING BTREE,
  ADD KEY `media_order_column_index` (`order_column`) USING BTREE;

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`) USING BTREE,
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`) USING BTREE;

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`) USING BTREE,
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`) USING BTREE;

--
-- Indexes for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `nilai_tugas_tugas_id_foreign` (`tugas_id`) USING BTREE,
  ADD KEY `nilai_tugas_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `nilai_tugas_siswa_id_foreign` (`siswa_id`) USING BTREE;

--
-- Indexes for table `ortus`
--
ALTER TABLE `ortus`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `ortus_nik_unique` (`nik`) USING BTREE,
  ADD UNIQUE KEY `ortus_kode_unique` (`kode`) USING BTREE,
  ADD KEY `ortus_user_id_foreign` (`user_id`) USING BTREE;

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`) USING BTREE;

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`) USING BTREE;

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`) USING BTREE,
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`) USING BTREE;

--
-- Indexes for table `prodis`
--
ALTER TABLE `prodis`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `prodi_programkeahlian`
--
ALTER TABLE `prodi_programkeahlian`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `prodi_programkeahlian_prodi_id_foreign` (`prodi_id`) USING BTREE,
  ADD KEY `prodi_programkeahlian_programkeahlian_id_foreign` (`programkeahlian_id`) USING BTREE;

--
-- Indexes for table `programkeahlian`
--
ALTER TABLE `programkeahlian`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `programkeahlian_kode_unique` (`kode`) USING BTREE;

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`) USING BTREE;

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`) USING BTREE,
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`) USING BTREE;

--
-- Indexes for table `siswas`
--
ALTER TABLE `siswas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `siswas_nis_unique` (`nis`) USING BTREE,
  ADD UNIQUE KEY `siswas_email_unique` (`email`) USING BTREE,
  ADD KEY `siswas_user_id_foreign` (`user_id`) USING BTREE,
  ADD KEY `siswas_programkeahlian_id_foreign` (`programkeahlian_id`) USING BTREE,
  ADD KEY `siswas_kelas_id_foreign` (`kelas_id`) USING BTREE;

--
-- Indexes for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `siswa_kelas_kelas_id_foreign` (`kelas_id`) USING BTREE,
  ADD KEY `siswa_kelas_siswa_id_foreign` (`siswa_id`) USING BTREE;

--
-- Indexes for table `soal_ujian_essays`
--
ALTER TABLE `soal_ujian_essays`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `soal_ujian_essays_ujian_id_foreign` (`ujian_id`) USING BTREE;

--
-- Indexes for table `soal_ujian_pgs`
--
ALTER TABLE `soal_ujian_pgs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `soal_ujian_pgs_ujian_id_foreign` (`ujian_id`) USING BTREE;

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `tugas_mapel_id_foreign` (`mapel_id`) USING BTREE,
  ADD KEY `tugas_jadwal_id_foreign` (`jadwal_id`) USING BTREE,
  ADD KEY `tugas_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `tugas_siswa_id_foreign` (`siswa_id`) USING BTREE;

--
-- Indexes for table `ujians`
--
ALTER TABLE `ujians`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `ujians_jadwal_ujian_id_foreign` (`jadwal_ujian_id`) USING BTREE;

--
-- Indexes for table `ujian_siswas`
--
ALTER TABLE `ujian_siswas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `ujian_siswas_ujian_id_foreign` (`ujian_id`) USING BTREE,
  ADD KEY `ujian_siswas_siswa_id_foreign` (`siswa_id`) USING BTREE;

--
-- Indexes for table `ujian_siswa_hasils`
--
ALTER TABLE `ujian_siswa_hasils`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `ujian_siswa_hasils_guru_id_foreign` (`guru_id`) USING BTREE,
  ADD KEY `ujian_siswa_hasils_ujian_siswa_id_foreign` (`ujian_siswa_id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `users_email_unique` (`email`) USING BTREE,
  ADD UNIQUE KEY `users_no_induk_unique` (`no_induk`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absens`
--
ALTER TABLE `absens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_label_permissions`
--
ALTER TABLE `group_label_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `gurus`
--
ALTER TABLE `gurus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `guru_kelas`
--
ALTER TABLE `guru_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jadwal_ujians`
--
ALTER TABLE `jadwal_ujians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `label_permissions`
--
ALTER TABLE `label_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mapels`
--
ALTER TABLE `mapels`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `materis`
--
ALTER TABLE `materis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ortus`
--
ALTER TABLE `ortus`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodis`
--
ALTER TABLE `prodis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prodi_programkeahlian`
--
ALTER TABLE `prodi_programkeahlian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programkeahlian`
--
ALTER TABLE `programkeahlian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `siswas`
--
ALTER TABLE `siswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `soal_ujian_essays`
--
ALTER TABLE `soal_ujian_essays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `soal_ujian_pgs`
--
ALTER TABLE `soal_ujian_pgs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ujians`
--
ALTER TABLE `ujians`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ujian_siswas`
--
ALTER TABLE `ujian_siswas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ujian_siswa_hasils`
--
ALTER TABLE `ujian_siswa_hasils`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absens`
--
ALTER TABLE `absens`
  ADD CONSTRAINT `absens_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `absens_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `absens_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `group_label_permissions`
--
ALTER TABLE `group_label_permissions`
  ADD CONSTRAINT `group_label_permissions_label_permission_id_foreign` FOREIGN KEY (`label_permission_id`) REFERENCES `label_permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `group_label_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `gurus`
--
ALTER TABLE `gurus`
  ADD CONSTRAINT `gurus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `guru_kelas`
--
ALTER TABLE `guru_kelas`
  ADD CONSTRAINT `guru_kelas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `guru_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `guru_kelas_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `guru_mapel`
--
ALTER TABLE `guru_mapel`
  ADD CONSTRAINT `guru_mapel_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `guru_mapel_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `jadwals_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `jadwals_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `jadwal_ujians`
--
ALTER TABLE `jadwal_ujians`
  ADD CONSTRAINT `jadwal_ujians_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `jadwal_ujians_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `jadwal_ujians_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `materis`
--
ALTER TABLE `materis`
  ADD CONSTRAINT `materis_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `materis_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `materis_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `nilai_tugas`
--
ALTER TABLE `nilai_tugas`
  ADD CONSTRAINT `nilai_tugas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `nilai_tugas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `nilai_tugas_tugas_id_foreign` FOREIGN KEY (`tugas_id`) REFERENCES `tugas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `ortus`
--
ALTER TABLE `ortus`
  ADD CONSTRAINT `ortus_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `prodi_programkeahlian`
--
ALTER TABLE `prodi_programkeahlian`
  ADD CONSTRAINT `prodi_programkeahlian_prodi_id_foreign` FOREIGN KEY (`prodi_id`) REFERENCES `prodis` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `prodi_programkeahlian_programkeahlian_id_foreign` FOREIGN KEY (`programkeahlian_id`) REFERENCES `programkeahlian` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `siswas`
--
ALTER TABLE `siswas`
  ADD CONSTRAINT `siswas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `siswas_programkeahlian_id_foreign` FOREIGN KEY (`programkeahlian_id`) REFERENCES `programkeahlian` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `siswas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `siswa_kelas`
--
ALTER TABLE `siswa_kelas`
  ADD CONSTRAINT `siswa_kelas_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `siswa_kelas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `soal_ujian_essays`
--
ALTER TABLE `soal_ujian_essays`
  ADD CONSTRAINT `soal_ujian_essays_ujian_id_foreign` FOREIGN KEY (`ujian_id`) REFERENCES `ujians` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `soal_ujian_pgs`
--
ALTER TABLE `soal_ujian_pgs`
  ADD CONSTRAINT `soal_ujian_pgs_ujian_id_foreign` FOREIGN KEY (`ujian_id`) REFERENCES `ujians` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `tugas_jadwal_id_foreign` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `tugas_mapel_id_foreign` FOREIGN KEY (`mapel_id`) REFERENCES `mapels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `tugas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `ujians`
--
ALTER TABLE `ujians`
  ADD CONSTRAINT `ujians_jadwal_ujian_id_foreign` FOREIGN KEY (`jadwal_ujian_id`) REFERENCES `jadwal_ujians` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `ujian_siswas`
--
ALTER TABLE `ujian_siswas`
  ADD CONSTRAINT `ujian_siswas_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `ujian_siswas_ujian_id_foreign` FOREIGN KEY (`ujian_id`) REFERENCES `ujians` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Constraints for table `ujian_siswa_hasils`
--
ALTER TABLE `ujian_siswa_hasils`
  ADD CONSTRAINT `ujian_siswa_hasils_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `gurus` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `ujian_siswa_hasils_ujian_siswa_id_foreign` FOREIGN KEY (`ujian_siswa_id`) REFERENCES `ujian_siswas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
