-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2025 at 08:49 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siakad_db_nest`
--

-- --------------------------------------------------------

--
-- Table structure for table `absences`
--

CREATE TABLE `absences` (
  `id` int NOT NULL,
  `studentId` int DEFAULT NULL,
  `scheduleId` int NOT NULL,
  `statusKehadiran` enum('HADIR','ALPA','SAKIT','IZIN') COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pertemuan` int NOT NULL,
  `createAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `materi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absences`
--

INSERT INTO `absences` (`id`, `studentId`, `scheduleId`, `statusKehadiran`, `keterangan`, `pertemuan`, `createAt`, `updatedAt`, `materi`) VALUES
(1, 1, 5, 'HADIR', NULL, 1, '2024-12-12 15:46:16.498', '2024-12-12 15:46:16.498', 'PKI'),
(2, 2, 5, 'HADIR', NULL, 1, '2024-12-12 15:46:16.498', '2024-12-22 11:35:06.493', 'PKI'),
(3, 1, 5, 'HADIR', NULL, 2, '2024-12-12 16:15:56.018', '2024-12-14 14:25:55.211', 'Mengenal Prabowo'),
(4, 2, 5, 'ALPA', NULL, 2, '2024-12-12 16:15:56.018', '2024-12-12 16:15:56.018', 'Mengenal Prabowo'),
(5, 1, 5, 'HADIR', NULL, 3, '2024-12-24 14:30:58.912', '2024-12-24 14:31:28.787', 'prabowo'),
(6, 1, 15, 'ALPA', NULL, 1, '2024-12-24 15:00:24.298', '2024-12-24 15:00:24.298', 'UAS');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `userId`, `createdAt`, `updatedAt`) VALUES
(1, 1, '2024-12-11 08:30:31.668', '2024-12-11 08:30:31.668'),
(5, 39, '2024-12-22 08:04:21.265', '2024-12-22 08:04:21.265'),
(6, 45, '2024-12-24 13:47:23.982', '2024-12-24 13:47:23.982');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int NOT NULL,
  `judul` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `judul`, `konten`, `createdAt`, `updatedAt`) VALUES
(2, 'Pengumuman Semester Ganjil', 'Kepada seluruh mahasiswa, Dengan ini kami sampaikan bahwa perkuliahan Semester Ganjil Tahun Akademik [Tahun] akan dimulai pada [Contoh: Senin, 5 September 2024] pukul [Contoh: Sesuai dengan j', '2024-12-15 16:19:56.156', '2024-12-15 16:19:56.156'),
(4, 'libur', 'libur libur', '2024-12-24 13:56:59.909', '2024-12-24 13:56:59.909');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('semester_1','semester_2','semester_3','semester_4','semester_5','semester_6','semester_7','semester_8') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks` int NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `programStudi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakultas` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isActive` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `code`, `semester`, `sks`, `createdAt`, `updatedAt`, `programStudi`, `fakultas`, `isActive`) VALUES
(1, 'Pendidikan Agama Islam', 'UNG112', 'semester_1', 2, '2024-12-11 09:24:59.768', '2024-12-23 12:10:06.598', 'Teknik Informatika', 'Teknik', 1),
(2, 'Kewarganegaraan', 'UNG118', 'semester_1', 2, '2024-12-11 09:25:33.310', '2024-12-20 10:28:30.564', 'Teknik Informatika', 'Teknik', 1),
(3, 'Bahasa Indonesia', 'UNG119', 'semester_1', 2, '2024-12-11 09:26:22.910', '2024-12-11 09:26:22.910', 'Teknik Informatika', 'Teknik', 1),
(4, 'Pancasila', 'UNG120', 'semester_1', 2, '2024-12-11 09:26:44.446', '2024-12-11 09:26:44.446', 'Teknik Informatika', 'Teknik', 1),
(5, 'Bahasa Inggris', 'UNG121', 'semester_1', 2, '2024-12-11 09:27:09.820', '2024-12-11 09:27:09.820', 'Teknik Informatika', 'Teknik', 1),
(6, 'Pengantar Teknologi Informasi', 'IF2211', 'semester_1', 4, '2024-12-11 09:28:59.136', '2024-12-11 09:28:59.136', 'Teknik Informatika', 'Teknik', 1),
(7, ' Matematika Teknik', 'IF2212', 'semester_1', 2, '2024-12-11 09:29:19.909', '2024-12-11 09:29:19.909', 'Teknik Informatika', 'Teknik', 1),
(8, 'Algoritma & Dasar Pemrograman', 'IF2213', 'semester_1', 4, '2024-12-11 09:29:43.047', '2024-12-11 09:29:43.047', 'Teknik Informatika', 'Teknik', 1),
(9, 'Struktur Data', 'IF2214', 'semester_2', 4, '2024-12-11 09:30:49.135', '2024-12-11 09:30:49.135', 'Teknik Informatika', 'Teknik', 1),
(10, 'Metode Statistika', 'IF2215', 'semester_2', 3, '2024-12-11 09:31:05.357', '2024-12-11 09:31:05.357', 'Teknik Informatika', 'Teknik', 1),
(11, ' Komputasi Aljabar Linier', 'IF2216', 'semester_2', 3, '2024-12-11 09:35:50.312', '2024-12-11 09:35:50.312', 'Teknik Informatika', 'Teknik', 1),
(12, 'Matematika Diskret', 'IF2217', 'semester_2', 3, '2024-12-11 09:36:07.502', '2024-12-11 09:36:07.502', 'Teknik Informatika', 'Teknik', 1),
(13, 'Organisasi Komputer dan Sistem Operasi ', 'IF2218', 'semester_2', 3, '2024-12-11 09:36:52.144', '2024-12-11 09:36:52.144', 'Teknik Informatika', 'Teknik', 1),
(14, 'Dasar Pemrograman Web', 'IF2219', 'semester_2', 4, '2024-12-11 09:37:35.944', '2024-12-11 09:37:35.944', 'Teknik Informatika', 'Teknik', 1),
(15, 'Pengembangan Aplikasi Web', 'IF2220', 'semester_3', 4, '2024-12-11 09:38:21.407', '2024-12-11 09:38:21.407', 'Teknik Informatika', 'Teknik', 1),
(16, ' Basis Data I', 'IF2221', 'semester_3', 3, '2024-12-11 09:38:42.751', '2024-12-11 09:38:42.751', 'Teknik Informatika', 'Teknik', 1),
(17, 'Jaringan Komputer', 'IF2222', 'semester_3', 4, '2024-12-11 09:39:01.127', '2024-12-11 09:39:01.127', 'Teknik Informatika', 'Teknik', 1),
(18, 'Teori Komputasi', 'IF2223', 'semester_3', 3, '2024-12-11 09:39:21.453', '2024-12-11 09:39:21.453', 'Teknik Informatika', 'Teknik', 1),
(19, 'Etika Profesional', 'IF2241', 'semester_3', 2, '2024-12-11 09:40:25.279', '2024-12-11 09:40:25.279', 'Teknik Informatika', 'Teknik', 1),
(20, 'Sistem Informasi', 'IF2242', 'semester_3', 3, '2024-12-11 09:40:45.862', '2024-12-11 09:40:45.862', 'Teknik Informatika', 'Teknik', 1),
(21, 'Pemrograman Desktop', 'IF2243', 'semester_3', 3, '2024-12-11 09:41:01.766', '2024-12-11 09:41:01.766', 'Teknik Informatika', 'Teknik', 1),
(22, 'Pemrograman Berorientasi Obyek', 'IF2244', 'semester_3', 3, '2024-12-11 09:41:15.103', '2024-12-11 09:41:15.103', 'Teknik Informatika', 'Teknik', 1),
(23, 'Rekayasa Multimedia', 'IF2245', 'semester_3', 3, '2024-12-11 09:41:29.783', '2024-12-11 09:41:29.783', 'Teknik Informatika', 'Teknik', 1),
(24, 'Penambangan Data', 'IF2224', 'semester_4', 3, '2024-12-11 09:53:22.291', '2024-12-22 10:55:51.890', 'Teknik Informatika', 'Teknik', 1),
(25, ' Rekayasa Perangkat Lunak', 'IF2225', 'semester_4', 3, '2024-12-11 09:54:18.903', '2024-12-11 09:54:18.903', 'Teknik Informatika', 'Teknik', 1),
(26, 'Kecerdasan Komputasional ', 'IF2226', 'semester_4', 3, '2024-12-11 09:54:38.767', '2024-12-11 09:54:38.767', 'Teknik Informatika', 'Teknik', 1),
(27, 'Pengembangan Sistem Berbasis Framework ', 'IF2246', 'semester_4', 3, '2024-12-11 10:00:04.227', '2024-12-11 10:00:04.227', 'Teknik Informatika', 'Teknik', 1),
(28, 'Interaksi Manusia & Komputer', 'IF2247', 'semester_4', 3, '2024-12-11 10:00:21.657', '2024-12-11 10:00:21.657', 'Teknik Informatika', 'Teknik', 1),
(29, 'Bahasa Inggris Informatika', 'IF2248', 'semester_4', 3, '2024-12-11 10:02:17.511', '2024-12-11 10:02:17.511', 'Teknik Informatika', 'Teknik', 1),
(30, 'Temu-Kembali informasi', 'IF2249', 'semester_4', 3, '2024-12-11 10:02:38.006', '2024-12-11 10:02:38.006', 'Teknik Informatika', 'Teknik', 1),
(31, 'Pemrograman Perangkat Bergerak', 'IF2250', 'semester_4', 3, '2024-12-11 10:03:24.792', '2024-12-11 10:03:24.792', 'Teknik Informatika', 'Teknik', 1),
(32, 'Grafika Komputer', 'IF2251', 'semester_4', 3, '2024-12-11 10:03:41.352', '2024-12-11 10:03:41.352', 'Teknik Informatika', 'Teknik', 1),
(33, 'Strategi Algoritma', 'IF2252', 'semester_4', 3, '2024-12-11 10:03:57.208', '2024-12-11 10:03:57.208', 'Teknik Informatika', 'Teknik', 1),
(34, 'Jaringan Komputer II', 'IF2253', 'semester_4', 3, '2024-12-11 10:04:55.278', '2024-12-11 10:04:55.278', 'Teknik Informatika', 'Teknik', 1),
(35, 'tes123', '123', 'semester_1', 3, '2024-12-24 14:00:35.390', '2024-12-24 14:03:10.195', 'Teknik Informatika', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int NOT NULL,
  `studentId` int NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `grade` enum('A','B','C','D','E') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduleId` int NOT NULL,
  `isValidated` tinyint(1) NOT NULL DEFAULT '0',
  `validatedAt` datetime(3) DEFAULT NULL,
  `validatedById` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `studentId`, `createdAt`, `updatedAt`, `grade`, `scheduleId`, `isValidated`, `validatedAt`, `validatedById`) VALUES
(22, 2, '2024-12-20 11:44:50.135', '2024-12-20 11:44:50.135', NULL, 4, 0, NULL, NULL),
(25, 1, '2024-12-23 12:16:58.223', '2024-12-23 12:20:41.912', NULL, 29, 1, NULL, NULL),
(26, 1, '2024-12-23 12:16:58.223', '2024-12-24 14:58:15.360', NULL, 33, 1, NULL, NULL),
(27, 1, '2024-12-23 12:16:58.223', '2024-12-24 14:58:17.510', NULL, 34, 1, NULL, NULL),
(28, 1, '2024-12-23 12:17:15.482', '2024-12-24 14:58:19.652', NULL, 22, 1, NULL, NULL),
(29, 1, '2024-12-24 14:28:24.763', '2024-12-24 14:30:10.590', 'A', 5, 1, NULL, NULL),
(30, 1, '2024-12-24 14:56:05.758', '2024-12-24 14:58:23.961', NULL, 15, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int NOT NULL,
  `enrollmentId` int NOT NULL,
  `nilai` enum('S','A','B','C','D') COLLATE utf8mb4_unicode_ci NOT NULL,
  `komentar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `enrollmentId`, `nilai`, `komentar`, `createdAt`, `updatedAt`) VALUES
(5, 30, 'S', 'Sangat Bermanfaat', '2024-12-24 15:01:53.304', '2024-12-24 15:01:53.304');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int NOT NULL,
  `pesan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `pesan`, `createdAt`, `updatedAt`, `email`, `name`) VALUES
(1, 'Webnya keren', '2024-12-23 09:38:02.577', '2024-12-23 09:38:02.577', 'mufid@gmail.com', 'Mufid'),
(3, 'webnya keren bang', '2024-12-24 13:43:13.152', '2024-12-24 13:43:13.152', 'tes@gmail.com', 'tes');

-- --------------------------------------------------------

--
-- Table structure for table `graduates`
--

CREATE TABLE `graduates` (
  `id` int NOT NULL,
  `studentId` int NOT NULL,
  `tanggalLulus` datetime(3) NOT NULL,
  `pekerjaan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `perusahaan` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `motivasi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `libraries`
--

CREATE TABLE `libraries` (
  `id` int NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page` int NOT NULL,
  `author` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `overview` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `libraries`
--

INSERT INTO `libraries` (`id`, `title`, `description`, `page`, `author`, `cover`, `overview`, `createdAt`, `updatedAt`) VALUES
(5, 'Atomic habits', 'A supremely practical and useful book. James Clear distills the most fundamental information about habit formation, so you can accomplish more by focusing on less.', 250, 'James Clear', '391ee8ae-1de0-4ee7-a9bc-a2495e70626b.png', 'Atomic Habits is a powerful book that has changed the way I think about how I live and lead.', '2024-12-23 10:41:11.703', '2024-12-23 11:33:44.117'),
(6, 'tes', 'tes', 200, 'tes', '2ce8bbba-253e-42a3-b86d-f52fecee2928.jpeg', 'tes', '2024-12-24 13:41:47.009', '2024-12-24 13:41:47.009');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `userId`, `action`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'Register Teacher By Admin', '2024-12-11 08:47:58.163', '2024-12-11 08:47:58.163'),
(2, 1, 'Register Teacher By Admin', '2024-12-11 08:48:45.216', '2024-12-11 08:48:45.216'),
(3, 1, 'Register Teacher By Admin', '2024-12-11 08:50:19.176', '2024-12-11 08:50:19.176'),
(4, 1, 'Register Teacher By Admin', '2024-12-11 08:50:50.631', '2024-12-11 08:50:50.631'),
(5, 1, 'Register Teacher By Admin', '2024-12-11 08:51:42.400', '2024-12-11 08:51:42.400'),
(6, 1, 'Register Teacher By Admin', '2024-12-11 08:53:52.958', '2024-12-11 08:53:52.958'),
(7, 1, 'Register Teacher By Admin', '2024-12-11 08:54:25.693', '2024-12-11 08:54:25.693'),
(8, 1, 'Register Teacher By Admin', '2024-12-11 08:55:02.792', '2024-12-11 08:55:02.792'),
(9, 1, 'Register Teacher By Admin', '2024-12-11 08:55:46.142', '2024-12-11 08:55:46.142'),
(10, 1, 'Register Teacher By Admin', '2024-12-11 08:56:32.872', '2024-12-11 08:56:32.872'),
(11, 1, 'Register Teacher By Admin', '2024-12-11 08:57:08.373', '2024-12-11 08:57:08.373'),
(12, 1, 'Register Teacher By Admin', '2024-12-11 08:57:44.865', '2024-12-11 08:57:44.865'),
(13, 1, 'Register Teacher By Admin', '2024-12-11 08:58:30.755', '2024-12-11 08:58:30.755'),
(14, 1, 'Register Teacher By Admin', '2024-12-11 08:59:10.818', '2024-12-11 08:59:10.818'),
(15, 1, 'Register Teacher By Admin', '2024-12-11 08:59:52.188', '2024-12-11 08:59:52.188'),
(16, 1, 'Register Teacher By Admin', '2024-12-11 09:00:24.567', '2024-12-11 09:00:24.567'),
(17, 1, 'Register Teacher By Admin', '2024-12-11 09:00:54.315', '2024-12-11 09:00:54.315'),
(18, 1, 'Register Teacher By Admin', '2024-12-11 09:01:37.130', '2024-12-11 09:01:37.130'),
(19, 1, 'Register Teacher By Admin', '2024-12-11 09:02:59.956', '2024-12-11 09:02:59.956'),
(20, 1, 'Register Teacher By Admin', '2024-12-11 09:04:10.780', '2024-12-11 09:04:10.780'),
(21, 1, 'Register Teacher By Admin', '2024-12-11 09:05:03.625', '2024-12-11 09:05:03.625'),
(22, 1, 'Register Teacher By Admin', '2024-12-11 09:05:55.015', '2024-12-11 09:05:55.015'),
(23, 1, 'Register Teacher By Admin', '2024-12-11 09:06:33.155', '2024-12-11 09:06:33.155'),
(24, 1, 'Register Teacher By Admin', '2024-12-11 09:07:01.613', '2024-12-11 09:07:01.613'),
(25, 1, 'Register Teacher By Admin', '2024-12-11 09:07:31.966', '2024-12-11 09:07:31.966'),
(26, 1, 'Register Teacher By Admin', '2024-12-11 09:08:22.136', '2024-12-11 09:08:22.136'),
(27, 1, 'Register Teacher By Admin', '2024-12-11 09:08:48.452', '2024-12-11 09:08:48.452'),
(28, 1, 'Register Teacher By Admin', '2024-12-11 09:09:45.544', '2024-12-11 09:09:45.544'),
(29, 1, 'Register Teacher By Admin', '2024-12-11 09:10:21.520', '2024-12-11 09:10:21.520'),
(30, 1, 'Register Teacher By Admin', '2024-12-11 09:10:46.732', '2024-12-11 09:10:46.732'),
(31, 1, 'Register Student By Admin', '2024-12-11 09:18:01.940', '2024-12-11 09:18:01.940'),
(32, 1, 'Register Student By Admin', '2024-12-11 09:21:42.439', '2024-12-11 09:21:42.439'),
(33, 32, 'Add Course', '2024-12-11 12:05:56.420', '2024-12-11 12:05:56.420'),
(34, 32, 'Delete Course', '2024-12-11 12:06:31.812', '2024-12-11 12:06:31.812'),
(35, 32, 'Delete Course', '2024-12-11 13:20:09.228', '2024-12-11 13:20:09.228'),
(36, 32, 'Delete Course', '2024-12-11 13:20:20.488', '2024-12-11 13:20:20.488'),
(37, 32, 'Add Course', '2024-12-11 13:20:38.214', '2024-12-11 13:20:38.214'),
(38, 32, 'Delete Course', '2024-12-11 13:39:06.673', '2024-12-11 13:39:06.673'),
(39, 32, 'Delete Course', '2024-12-11 13:40:14.908', '2024-12-11 13:40:14.908'),
(40, 32, 'Add Course', '2024-12-11 13:43:25.217', '2024-12-11 13:43:25.217'),
(41, 32, 'Delete Course', '2024-12-11 13:43:39.555', '2024-12-11 13:43:39.555'),
(42, 32, 'Logout Student', '2024-12-11 17:25:53.679', '2024-12-11 17:25:53.679'),
(43, 32, 'Logout Student', '2024-12-12 04:47:40.104', '2024-12-12 04:47:40.104'),
(44, 6, 'Logout Student', '2024-12-12 04:50:39.541', '2024-12-12 04:50:39.541'),
(45, 32, 'Delete Course', '2024-12-12 04:51:11.743', '2024-12-12 04:51:11.743'),
(46, 32, 'Add Course', '2024-12-12 04:53:45.081', '2024-12-12 04:53:45.081'),
(47, 32, 'Logout Student', '2024-12-12 04:53:54.990', '2024-12-12 04:53:54.990'),
(48, 33, 'Add Course', '2024-12-12 04:54:31.765', '2024-12-12 04:54:31.765'),
(49, 33, 'Logout Student', '2024-12-12 04:54:43.232', '2024-12-12 04:54:43.232'),
(50, 6, 'Logout Teacher', '2024-12-12 09:05:24.453', '2024-12-12 09:05:24.453'),
(51, 1, 'Update Teacher By Admin', '2024-12-12 11:11:53.245', '2024-12-12 11:11:53.245'),
(52, 1, 'Update Teacher By Admin', '2024-12-12 11:12:07.197', '2024-12-12 11:12:07.197'),
(53, 1, 'Update Student By Admin', '2024-12-12 11:12:21.369', '2024-12-12 11:12:21.369'),
(54, 1, 'Update Student By Admin', '2024-12-12 11:12:38.344', '2024-12-12 11:12:38.344'),
(55, 6, 'Logout Teacher', '2024-12-12 12:24:17.528', '2024-12-12 12:24:17.528'),
(56, 32, 'Delete Course', '2024-12-12 12:36:05.141', '2024-12-12 12:36:05.141'),
(57, 32, 'Add Course', '2024-12-12 12:36:14.212', '2024-12-12 12:36:14.212'),
(58, 32, 'Logout Student', '2024-12-12 13:36:26.781', '2024-12-12 13:36:26.781'),
(59, 6, 'Logout Teacher', '2024-12-12 13:39:36.623', '2024-12-12 13:39:36.623'),
(60, 33, 'Delete Course', '2024-12-12 13:40:01.028', '2024-12-12 13:40:01.028'),
(61, 33, 'Add Course', '2024-12-12 13:40:09.165', '2024-12-12 13:40:09.165'),
(62, 1, 'Register Student By Admin', '2024-12-12 13:50:19.208', '2024-12-12 13:50:19.208'),
(63, 1, 'Register Student By Admin', '2024-12-12 13:53:08.111', '2024-12-12 13:53:08.111'),
(64, 6, 'Logout Teacher', '2024-12-12 14:00:13.245', '2024-12-12 14:00:13.245'),
(65, 32, 'Logout Student', '2024-12-12 14:04:08.372', '2024-12-12 14:04:08.372'),
(66, 33, 'Logout Student', '2024-12-12 14:05:07.168', '2024-12-12 14:05:07.168'),
(67, 6, 'Logout Teacher', '2024-12-12 17:06:28.775', '2024-12-12 17:06:28.775'),
(68, 32, 'Add Course', '2024-12-12 17:58:06.440', '2024-12-12 17:58:06.440'),
(69, 32, 'Logout Student', '2024-12-12 18:03:02.310', '2024-12-12 18:03:02.310'),
(70, 1, 'Update Teacher By Admin', '2024-12-12 21:19:29.546', '2024-12-12 21:19:29.546'),
(71, 1, 'Update Teacher By Admin', '2024-12-12 21:20:49.462', '2024-12-12 21:20:49.462'),
(72, 32, 'Logout Student', '2024-12-14 14:20:50.424', '2024-12-14 14:20:50.424'),
(73, 32, 'Add Course', '2024-12-14 14:22:25.252', '2024-12-14 14:22:25.252'),
(74, 1, 'Register New Admin By Admin', '2024-12-14 15:28:44.927', '2024-12-14 15:28:44.927'),
(75, 32, 'Logout Student', '2024-12-14 16:25:32.005', '2024-12-14 16:25:32.005'),
(76, 32, 'Logout Student', '2024-12-15 08:14:29.535', '2024-12-15 08:14:29.535'),
(77, 6, 'Logout Teacher', '2024-12-15 08:21:34.980', '2024-12-15 08:21:34.980'),
(78, 32, 'Logout Student', '2024-12-15 09:00:19.584', '2024-12-15 09:00:19.584'),
(79, 6, 'Logout Teacher', '2024-12-15 09:03:15.607', '2024-12-15 09:03:15.607'),
(80, 32, 'Logout Student', '2024-12-15 16:55:53.376', '2024-12-15 16:55:53.376'),
(81, 6, 'Logout Teacher', '2024-12-15 16:57:06.944', '2024-12-15 16:57:06.944'),
(82, 26, 'Logout Teacher', '2024-12-15 16:59:08.401', '2024-12-15 16:59:08.401'),
(83, 25, 'Logout Teacher', '2024-12-15 17:13:41.550', '2024-12-15 17:13:41.550'),
(84, 32, 'Logout Student', '2024-12-16 12:04:26.342', '2024-12-16 12:04:26.342'),
(85, 32, 'Logout Student', '2024-12-17 12:19:15.414', '2024-12-17 12:19:15.414'),
(86, 32, 'Logout Student', '2024-12-17 13:12:43.588', '2024-12-17 13:12:43.588'),
(87, 32, 'Logout Student', '2024-12-17 15:13:46.204', '2024-12-17 15:13:46.204'),
(88, 32, 'Logout Student', '2024-12-17 16:00:31.090', '2024-12-17 16:00:31.090'),
(89, 32, 'Logout Student', '2024-12-17 18:01:25.289', '2024-12-17 18:01:25.289'),
(90, 32, 'Add Course', '2024-12-17 18:13:08.790', '2024-12-17 18:13:08.790'),
(91, 32, 'Delete Course', '2024-12-17 18:13:52.604', '2024-12-17 18:13:52.604'),
(92, 32, 'Add Course', '2024-12-17 18:14:10.359', '2024-12-17 18:14:10.359'),
(93, 32, 'Delete Course', '2024-12-17 18:16:09.997', '2024-12-17 18:16:09.997'),
(94, 32, 'Add Course', '2024-12-17 18:16:16.550', '2024-12-17 18:16:16.550'),
(95, 32, 'Logout Student', '2024-12-17 18:21:50.298', '2024-12-17 18:21:50.298'),
(96, 26, 'Logout Teacher', '2024-12-17 18:22:30.514', '2024-12-17 18:22:30.514'),
(97, 33, 'Logout Student', '2024-12-18 03:31:19.204', '2024-12-18 03:31:19.204'),
(98, 32, 'Logout Student', '2024-12-18 05:42:41.281', '2024-12-18 05:42:41.281'),
(99, 25, 'Logout Teacher', '2024-12-18 06:04:43.768', '2024-12-18 06:04:43.768'),
(100, 32, 'Logout Student', '2024-12-18 06:06:57.898', '2024-12-18 06:06:57.898'),
(101, 33, 'Update Student By Admin', '2024-12-18 06:30:59.312', '2024-12-18 06:30:59.312'),
(102, 33, 'Logout Student', '2024-12-18 06:38:55.839', '2024-12-18 06:38:55.839'),
(103, 6, 'Logout Teacher', '2024-12-18 06:40:01.428', '2024-12-18 06:40:01.428'),
(104, 25, 'Logout Teacher', '2024-12-18 06:41:10.284', '2024-12-18 06:41:10.284'),
(105, 6, 'Logout Teacher', '2024-12-18 06:48:42.657', '2024-12-18 06:48:42.657'),
(106, 32, 'Logout Student', '2024-12-18 07:01:06.745', '2024-12-18 07:01:06.745'),
(107, 25, 'Logout Teacher', '2024-12-18 07:05:32.308', '2024-12-18 07:05:32.308'),
(108, 33, 'Logout Student', '2024-12-18 07:20:27.673', '2024-12-18 07:20:27.673'),
(109, 6, 'Logout Teacher', '2024-12-18 07:22:02.395', '2024-12-18 07:22:02.395'),
(110, 25, 'Logout Teacher', '2024-12-18 08:50:26.894', '2024-12-18 08:50:26.894'),
(111, 32, 'Logout Student', '2024-12-18 09:34:21.194', '2024-12-18 09:34:21.194'),
(112, 32, 'Logout Student', '2024-12-18 12:10:34.572', '2024-12-18 12:10:34.572'),
(113, 6, 'Logout Teacher', '2024-12-19 04:00:38.160', '2024-12-19 04:00:38.160'),
(114, 32, 'Logout Student', '2024-12-19 04:01:41.164', '2024-12-19 04:01:41.164'),
(115, 6, 'Logout Teacher', '2024-12-19 05:11:59.922', '2024-12-19 05:11:59.922'),
(116, 25, 'Logout Teacher', '2024-12-20 02:11:19.381', '2024-12-20 02:11:19.381'),
(117, 25, 'Logout Teacher', '2024-12-20 03:39:15.106', '2024-12-20 03:39:15.106'),
(118, 32, 'Delete Course', '2024-12-20 03:41:30.707', '2024-12-20 03:41:30.707'),
(119, 32, 'Logout Student', '2024-12-20 03:42:56.619', '2024-12-20 03:42:56.619'),
(120, 32, 'Logout Student', '2024-12-20 11:12:50.539', '2024-12-20 11:12:50.539'),
(121, 25, 'Logout Teacher', '2024-12-20 11:30:36.901', '2024-12-20 11:30:36.901'),
(122, 33, 'Logout Student', '2024-12-20 11:34:38.388', '2024-12-20 11:34:38.388'),
(123, 33, 'Delete Course', '2024-12-20 11:44:38.224', '2024-12-20 11:44:38.224'),
(124, 33, 'Add Course', '2024-12-20 11:44:50.147', '2024-12-20 11:44:50.147'),
(125, 33, 'Logout Student', '2024-12-20 11:44:59.953', '2024-12-20 11:44:59.953'),
(126, 6, 'Logout Teacher', '2024-12-20 13:16:31.669', '2024-12-20 13:16:31.669'),
(127, 25, 'Logout Teacher', '2024-12-20 13:55:54.887', '2024-12-20 13:55:54.887'),
(128, 1, 'Register New Admin By Admin', '2024-12-22 08:04:21.286', '2024-12-22 08:04:21.286'),
(129, 1, 'Register Teacher By Admin', '2024-12-22 08:05:01.952', '2024-12-22 08:05:01.952'),
(130, 1, 'Update Teacher By Admin', '2024-12-22 08:05:14.424', '2024-12-22 08:05:14.424'),
(131, 1, 'Delete Teacher By Admin', '2024-12-22 08:05:22.951', '2024-12-22 08:05:22.951'),
(132, 6, 'Logout Teacher', '2024-12-22 11:43:23.416', '2024-12-22 11:43:23.416'),
(133, 32, 'Add Course', '2024-12-23 04:08:35.194', '2024-12-23 04:08:35.194'),
(134, 32, 'Logout Student', '2024-12-23 08:11:44.052', '2024-12-23 08:11:44.052'),
(135, 32, 'Logout Student', '2024-12-23 08:32:29.256', '2024-12-23 08:32:29.256'),
(136, 6, 'Logout Teacher', '2024-12-23 08:35:45.350', '2024-12-23 08:35:45.350'),
(137, 32, 'Logout Student', '2024-12-23 08:36:28.374', '2024-12-23 08:36:28.374'),
(138, 6, 'Logout Teacher', '2024-12-23 09:41:35.187', '2024-12-23 09:41:35.187'),
(139, 18, 'Update Teacher By Admin', '2024-12-23 09:44:33.718', '2024-12-23 09:44:33.718'),
(140, 18, 'Update Teacher By Admin', '2024-12-23 09:44:46.415', '2024-12-23 09:44:46.415'),
(141, 18, 'Logout Teacher', '2024-12-23 09:45:02.253', '2024-12-23 09:45:02.253'),
(142, 32, 'Logout Student', '2024-12-23 11:20:54.543', '2024-12-23 11:20:54.543'),
(143, 6, 'Logout Teacher', '2024-12-23 11:26:37.481', '2024-12-23 11:26:37.481'),
(144, 1, 'Register Student By Admin', '2024-12-23 11:56:04.782', '2024-12-23 11:56:04.782'),
(145, 1, 'Register Student By Admin', '2024-12-23 11:58:22.595', '2024-12-23 11:58:22.595'),
(146, 1, 'Register Student By Admin', '2024-12-23 12:00:29.406', '2024-12-23 12:00:29.406'),
(147, 1, 'Register Student By Admin', '2024-12-23 12:02:06.162', '2024-12-23 12:02:06.162'),
(148, 32, 'Delete Course', '2024-12-23 12:15:04.062', '2024-12-23 12:15:04.062'),
(149, 32, 'Delete Course', '2024-12-23 12:15:12.965', '2024-12-23 12:15:12.965'),
(150, 32, 'Add Course', '2024-12-23 12:16:58.234', '2024-12-23 12:16:58.234'),
(151, 32, 'Add Course', '2024-12-23 12:17:15.492', '2024-12-23 12:17:15.492'),
(152, 32, 'Delete Course', '2024-12-23 12:17:36.960', '2024-12-23 12:17:36.960'),
(153, 32, 'Logout Student', '2024-12-23 12:19:55.022', '2024-12-23 12:19:55.022'),
(154, 25, 'Logout Teacher', '2024-12-23 12:24:31.812', '2024-12-23 12:24:31.812'),
(155, 25, 'Logout Teacher', '2024-12-23 12:39:02.677', '2024-12-23 12:39:02.677'),
(156, 32, 'Update Student By Admin', '2024-12-23 12:41:23.043', '2024-12-23 12:41:23.043'),
(157, 32, 'Logout Student', '2024-12-23 12:50:07.521', '2024-12-23 12:50:07.521'),
(158, 32, 'Logout Student', '2024-12-24 13:45:10.928', '2024-12-24 13:45:10.928'),
(159, 6, 'Logout Teacher', '2024-12-24 13:45:39.399', '2024-12-24 13:45:39.399'),
(160, 1, 'Register New Admin By Admin', '2024-12-24 13:47:23.992', '2024-12-24 13:47:23.992'),
(161, 1, 'Register Teacher By Admin', '2024-12-24 13:48:50.654', '2024-12-24 13:48:50.654'),
(162, 1, 'Register Student By Admin', '2024-12-24 13:50:14.777', '2024-12-24 13:50:14.777'),
(165, 1, 'Delete Teacher By Admin', '2024-12-24 13:54:16.900', '2024-12-24 13:54:16.900'),
(166, 32, 'Logout Student', '2024-12-24 13:58:34.391', '2024-12-24 13:58:34.391'),
(167, 25, 'Logout Teacher', '2024-12-24 14:24:26.579', '2024-12-24 14:24:26.579'),
(168, 32, 'Logout Student', '2024-12-24 14:25:26.459', '2024-12-24 14:25:26.459'),
(169, 6, 'Logout Teacher', '2024-12-24 14:26:08.778', '2024-12-24 14:26:08.778'),
(170, 32, 'Add Course', '2024-12-24 14:28:24.786', '2024-12-24 14:28:24.786'),
(171, 32, 'Logout Student', '2024-12-24 14:28:34.229', '2024-12-24 14:28:34.229'),
(172, 25, 'Logout Teacher', '2024-12-24 14:29:24.878', '2024-12-24 14:29:24.878'),
(173, 25, 'Logout Teacher', '2024-12-24 14:54:10.310', '2024-12-24 14:54:10.310'),
(174, 32, 'Add Course', '2024-12-24 14:56:05.770', '2024-12-24 14:56:05.770'),
(175, 17, 'Logout Teacher', '2024-12-24 14:56:48.279', '2024-12-24 14:56:48.279'),
(176, 25, 'Logout Teacher', '2024-12-24 14:58:30.256', '2024-12-24 14:58:30.256'),
(177, 16, 'Update Teacher By Admin', '2024-12-24 15:02:28.618', '2024-12-24 15:02:28.618'),
(178, 16, 'Update Teacher By Admin', '2024-12-24 15:02:30.772', '2024-12-24 15:02:30.772');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int NOT NULL,
  `judul` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konten` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `judul`, `konten`, `gambar`, `createdAt`, `updatedAt`) VALUES
(8, 'Luthfi Awwalia Raih Silver Winner di Anugerah Diktisaintek 20241', 'Prestasi membanggakan diraih oleh Luthfi Awwalia, seorang humas dari Universitas Trunojoyo Madura (UTM), yang berhasil meraih penghargaan sebagai Silver Winner dalam Sub Kategori Insan Humas Kategori PTN BLU pada ajang Anugerah Diktisaintek 2024, pada 13 desember 2024 di Graha ', '181ef9f7-7119-4048-87cd-996117d70cb2.jpeg', '2024-12-23 10:33:20.552', '2024-12-23 10:33:20.552'),
(9, 'Universitas Trunojoyo Madura Raih 3 Penghargaan di Anugerah DIKTISAINTEK 2024', 'Universitas Trunojoyo Madura (UTM) kembali menorehkan prestasi gemilang di tingkat nasional dengan meraih tiga penghargaan pada ajang bergengsi Anugerah DIKTISAINTEK 2024 pada tanggal 13 Desember 2024 di Graha Diktisaintek Gedung D Lantai 2 J. Jenderal Sudirman, Senayan, Jakarta. Acara yang diselenggarakan oleh Direktorat Jenderal Pendidikan Tinggi, Riset, dan Teknologi (DIKTIRISTEK) ini bertujuan memberikan apresiasi kepada perguruan tinggi yang menunjukkan kinerja unggul dalam berbagai bidang.', 'b72102c1-64f1-4712-8f80-054f44b956f1.jpg', '2024-12-23 10:34:39.329', '2024-12-23 10:34:39.329'),
(10, 'Tujuh Program Studi Universitas Trunojoyo Madura Raih Akreditasi Internasional FIBAA', 'Universitas Trunojoyo Madura (UTM) kembali mencatatkan prestasi membanggakan di kancah pendidikan tinggi. Sebanyak tujuh program studi di universitas ini berhasil', '18887f2d-f887-4342-b7e0-231fb0e30478.jpeg', '2024-12-23 10:37:12.253', '2024-12-23 10:37:12.253'),
(11, 'tes', 'tes', '22344364-dd64-4c8d-9fcd-1789626bcd8d.jpeg', '2024-12-24 13:40:54.984', '2024-12-24 13:40:54.984');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `studentId` int NOT NULL,
  `total` int NOT NULL,
  `statusPembayaran` enum('FAILED','PENDING','SUCCESS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `semester` enum('semester_1','semester_2','semester_3','semester_4','semester_5','semester_6','semester_7','semester_8') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `studentId`, `total`, `statusPembayaran`, `createdAt`, `updatedAt`, `semester`) VALUES
(17, 1, 3000000, 'SUCCESS', '2024-12-17 12:29:00.764', '2024-12-24 14:28:00.656', 'semester_1'),
(18, 2, 3000000, 'SUCCESS', '2024-12-17 12:29:00.764', '2024-12-18 03:30:18.807', 'semester_1'),
(19, 1, 3500000, 'SUCCESS', '2024-12-18 05:39:17.435', '2024-12-18 05:40:39.726', 'semester_2'),
(20, 2, 3500000, 'PENDING', '2024-12-18 05:39:17.435', '2024-12-18 05:39:17.435', 'semester_2');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int NOT NULL,
  `courseId` int NOT NULL,
  `day` enum('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY','SATURDAY','SUNDAY') COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `kouta` int NOT NULL DEFAULT '30',
  `teacherId` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `courseId`, `day`, `time`, `room`, `createdAt`, `updatedAt`, `kouta`, `teacherId`) VALUES
(1, 1, 'MONDAY', '07:00-08:30', 'RKBF 407', '2024-12-11 10:07:52.673', '2024-12-23 12:15:04.053', 30, 1),
(2, 1, 'MONDAY', '07:00-08:30', 'RKBF 405', '2024-12-11 10:08:21.151', '2024-12-11 10:08:21.151', 30, 2),
(3, 1, 'MONDAY', '07:00-08:30', 'RKBF 406', '2024-12-11 10:08:25.112', '2024-12-11 10:08:25.112', 30, 3),
(4, 2, 'MONDAY', '08:30-10:00', 'RKBF 408', '2024-12-11 10:09:42.441', '2024-12-20 11:44:50.139', 29, 4),
(5, 2, 'MONDAY', '08:30-10:00', 'RKBF 404', '2024-12-11 10:09:52.840', '2024-12-24 14:28:24.769', 29, 5),
(6, 2, 'MONDAY', '08:30-10:00', 'RKBF 403', '2024-12-11 10:10:00.576', '2024-12-12 13:40:01.020', 30, 6),
(7, 3, 'MONDAY', '08:30-10:00', 'RKBF 407', '2024-12-11 10:10:22.511', '2024-12-23 12:15:04.053', 30, 7),
(8, 3, 'MONDAY', '08:30-10:00', 'RKBF 406', '2024-12-11 10:10:28.088', '2024-12-11 10:10:28.088', 30, 8),
(9, 3, 'MONDAY', '08:30-10:00', 'RKBF 405', '2024-12-11 10:12:05.012', '2024-12-11 10:12:05.012', 30, 9),
(10, 4, 'MONDAY', '10:00-12:00', 'RKBF 405', '2024-12-11 10:13:11.416', '2024-12-11 10:13:11.416', 30, 10),
(11, 4, 'MONDAY', '10:00-12:00', 'RKBF 406', '2024-12-11 10:13:16.855', '2024-12-11 10:13:16.855', 30, 11),
(12, 4, 'MONDAY', '10:00-12:00', 'RKBF 407', '2024-12-11 10:13:34.336', '2024-12-12 04:51:11.734', 30, 12),
(13, 5, 'MONDAY', '13:00-14:30', 'RKBF 405', '2024-12-11 10:14:49.841', '2024-12-12 04:51:11.734', 30, 13),
(14, 5, 'MONDAY', '13:00-14:30', 'RKBF 406', '2024-12-11 10:14:56.247', '2024-12-11 13:20:09.208', 30, 14),
(15, 6, 'MONDAY', '13:00-14:30', 'LAB CC', '2024-12-11 10:15:33.058', '2024-12-24 14:56:05.761', 29, 15),
(16, 6, 'MONDAY', '13:00-15:30', 'LAB TIA', '2024-12-11 10:15:41.576', '2024-12-11 13:20:20.484', 30, 16),
(17, 6, 'TUESDAY', '13:00-15:30', 'LAB CC', '2024-12-11 10:16:24.831', '2024-12-12 04:51:11.734', 30, 16),
(18, 7, 'TUESDAY', '13:00-14:30', 'RKBF 405', '2024-12-11 10:16:54.097', '2024-12-12 04:51:11.734', 30, 13),
(19, 7, 'TUESDAY', '13:00-14:30', 'RKBF 406', '2024-12-11 10:17:08.224', '2024-12-11 10:17:08.224', 30, 14),
(20, 8, 'WEDNESDAY', '13:00-15:30', 'LAB MULTIMEDIA', '2024-12-11 10:18:29.456', '2024-12-12 04:51:11.734', 30, 3),
(21, 8, 'WEDNESDAY', '13:00-15:30', 'LAB CC', '2024-12-11 10:20:15.616', '2024-12-11 10:20:15.616', 30, 5),
(22, 9, 'THURSDAY', '07:00-09:30', 'LAB CC', '2024-12-11 10:24:16.714', '2024-12-23 12:17:15.484', 29, 6),
(23, 9, 'THURSDAY', '07:00-09:30', 'LAB TIA', '2024-12-11 10:24:27.544', '2024-12-11 10:24:27.544', 30, 2),
(24, 10, 'THURSDAY', '07:00-09:30', 'RKBF 204', '2024-12-11 10:24:57.624', '2024-12-11 10:24:57.624', 30, 6),
(25, 10, 'THURSDAY', '07:00-09:30', 'RKBF 205', '2024-12-11 10:25:03.384', '2024-12-11 10:25:03.384', 30, 7),
(26, 10, 'THURSDAY', '07:00-09:30', 'RKBF 206', '2024-12-11 10:25:14.439', '2024-12-11 10:25:14.439', 30, 8),
(27, 11, 'THURSDAY', '07:00-09:30', 'LAB TIA', '2024-12-11 10:26:36.872', '2024-12-23 12:17:36.954', 30, 9),
(28, 11, 'THURSDAY', '07:00-09:30', 'LAB CC', '2024-12-11 10:27:20.152', '2024-12-11 10:27:20.152', 30, 3),
(29, 12, 'MONDAY', '07:00-09:30', 'RKBF 304', '2024-12-11 10:28:13.122', '2024-12-23 12:16:58.227', 29, 3),
(30, 12, 'MONDAY', '07:00-09:30', 'RKBF 305', '2024-12-11 10:28:17.600', '2024-12-11 10:28:17.600', 30, 5),
(31, 12, 'MONDAY', '07:00-09:30', 'RKBF 306', '2024-12-11 10:28:25.534', '2024-12-11 10:28:25.534', 30, 10),
(32, 13, 'MONDAY', '13:00-15:30', 'RKBF 306', '2024-12-11 10:28:55.992', '2024-12-11 10:28:55.992', 30, 10),
(33, 13, 'MONDAY', '13:00-15:30', 'RKBF 304', '2024-12-11 10:29:04.601', '2024-12-23 12:16:58.227', 29, 11),
(34, 14, 'THURSDAY', '13:00-15:30', 'RKBF 304', '2024-12-11 10:30:55.575', '2024-12-23 12:16:58.227', 29, 14),
(35, 14, 'THURSDAY', '13:00-15:30', 'RKBF 306', '2024-12-11 10:31:05.593', '2024-12-11 10:31:05.593', 30, 16),
(36, 15, 'WEDNESDAY', '13:00-15:30', 'RKBF 306', '2024-12-11 10:33:08.666', '2024-12-11 10:33:08.666', 30, 11),
(37, 15, 'WEDNESDAY', '13:00-15:30', 'RKBF 307', '2024-12-11 10:33:15.272', '2024-12-11 10:33:15.272', 30, 14),
(38, 26, 'SUNDAY', '10:00-12:45', 'RKBF 302', '2024-12-22 10:54:14.115', '2024-12-22 10:54:14.115', 30, 1),
(39, 26, 'SUNDAY', '10:00-12:45', 'RKBF 302', '2024-12-22 10:54:16.476', '2024-12-22 10:54:16.476', 30, 1),
(40, 18, 'WEDNESDAY', '07:00-08:00', 'RKBF 302', '2024-12-23 12:27:51.987', '2024-12-23 12:27:51.987', 30, 24),
(41, 20, 'WEDNESDAY', '13:00-14:00', 'RKBF 306', '2024-12-23 12:28:49.947', '2024-12-23 12:28:49.947', 30, 24),
(42, 35, 'TUESDAY', '07:00-08:00', 'RKBF 306', '2024-12-24 14:02:26.414', '2024-12-24 14:02:26.414', 30, 26);

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` int NOT NULL,
  `nama` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mulai` datetime(3) NOT NULL,
  `akhir` datetime(3) NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `scholarships`
--

INSERT INTO `scholarships` (`id`, `nama`, `deskripsi`, `mulai`, `akhir`, `createdAt`, `updatedAt`, `link`) VALUES
(21, 'ID camp', 'ID camp indonesia', '2024-12-15 16:02:00.000', '2025-01-11 16:02:00.000', '2024-12-15 16:02:58.390', '2024-12-15 16:02:58.390', 'http://idcamp.ioh.co.id/login?referrer_id=3633488');

-- --------------------------------------------------------

--
-- Table structure for table `serverstatus`
--

CREATE TABLE `serverstatus` (
  `id` int NOT NULL,
  `isMaintenance` tinyint(1) NOT NULL DEFAULT '0',
  `updatedAt` datetime(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `serverstatus`
--

INSERT INTO `serverstatus` (`id`, `isMaintenance`, `updatedAt`) VALUES
(1, 0, '2024-12-24 13:56:05.385');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('05CA3TJJhCKO2H1ENEsXCvdLb7FEtoCLC9ib2wPn', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRGpYZnU0Y3dORnl1clJDTWs1enVVdzBoUjlWZzZ4YUdrZWg2V1p1diI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kb3Nlbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU6InRva2VuIjtzOjM2OiI5YTBiMGRjMS1iNDJkLTRkMzMtYTJhOC0wMGZhMzVlNmI0MDAiO30=', 1735052612),
('7oUhKWkyL5NnpQK77XlkK6mL1w8x9NBSKYOTrr4X', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU1FMU1d3YXpFRHJaTmdYcWFJQmlFSk9jNVY3SVpiMU53N0VmeElLZCI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763452380),
('kgLNUux9hAHa9Y5uOwA7frTo2Aaq8ZUt3oaaXufq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlRkUGdPNGxpdXVnRVFhQWJKYW9XUG5yR3dEZ3dudkQxZWNkQzdUayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1763570092),
('LK97JRerZX3O04oVuNm65VgeYj0wntNTxBsuN9YN', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM2ZqZHJqY2hZaG5QT2tUb1h4aEJNVFFJUTNSOFp1UEJRZXJ4eHlIaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763594748),
('nPLfoYNjs3jCo5AV6GrXG5K3B6vI637wXwVx36QS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib1B6a0gwMkhwV0laWDY5RUljRUh0Z2xDcFh6d01ESFExODJTZEdrVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763597076),
('sFyGhGmAo3pXhbb40StATjquRKyjYgFPDyx1Uin6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicXlXUkFFZUN3VGl3aHRCSm5UNUQ0bWVxOUlKR05vaDdnaHZzc1RGciI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1763455609),
('wXla70ctywN3rem9j6D4O9ux8X1r3qBcgx9ezipy', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHB2Q2VyYmVXNEREbHlnTk83cGl6UlJxUk5kYTB0VWVmWk40RkhQUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50L2V2YWwtZG9zZW4vMTUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU6InRva2VuIjtzOjM2OiJkNmEwNmJiOC03ZjQ3LTRmNDctOWRhZS1mOGI1NzMxMzJiNDciO30=', 1735052515);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `nim` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `academicAdvisorId` int DEFAULT NULL,
  `gpa` double NOT NULL DEFAULT '0',
  `sks` int NOT NULL DEFAULT '0',
  `sksOFSemester` int NOT NULL DEFAULT '0',
  `programStudi` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statusStudent` enum('ACTIVE','DO','LULUS') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fakultas` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `userId`, `nim`, `createdAt`, `updatedAt`, `academicAdvisorId`, `gpa`, `sks`, `sksOFSemester`, `programStudi`, `statusStudent`, `fakultas`) VALUES
(1, 32, '230411100031', '2024-12-11 09:18:01.935', '2024-12-24 14:56:05.767', 24, 0, 20, 20, 'Teknik Informatika', 'ACTIVE', 'Fakultas Teknik'),
(2, 33, '230411100198', '2024-12-11 09:21:42.436', '2024-12-20 11:44:50.144', 1, 0, 2, 2, 'Teknik Informatika', 'ACTIVE', 'Fakultas Teknik'),
(5, 41, '230411100059', '2024-12-23 11:56:04.769', '2024-12-23 11:56:04.769', 1, 0, 0, 0, 'Teknik Informatika', 'ACTIVE', NULL),
(6, 42, '230411100003', '2024-12-23 11:58:22.591', '2024-12-23 11:58:22.591', 3, 0, 0, 0, 'Teknik Informatika', 'ACTIVE', NULL),
(7, 43, '230411100192', '2024-12-23 12:00:29.402', '2024-12-23 12:00:29.402', 26, 0, 0, 0, 'Teknik Informatika', 'ACTIVE', NULL),
(8, 44, '230411100195', '2024-12-23 12:02:06.156', '2024-12-23 12:02:06.156', 26, 0, 0, 0, 'Teknik Informatika', 'ACTIVE', NULL),
(9, 47, '123445678', '2024-12-24 13:50:14.772', '2024-12-24 13:50:14.772', 15, 0, 0, 0, 'Teknik Informatika', 'ACTIVE', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `userId` int NOT NULL,
  `nip` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `gelar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keahlian` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fakultas` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `userId`, `nip`, `createdAt`, `updatedAt`, `gelar`, `keahlian`, `fakultas`) VALUES
(1, 2, '19740610200812', '2024-12-11 08:47:58.153', '2024-12-11 08:47:58.153', NULL, NULL, 'Teknik'),
(2, 3, '19860926201404', '2024-12-11 08:48:45.213', '2024-12-11 08:48:45.213', NULL, NULL, 'Teknik'),
(3, 4, '19810109200604', '2024-12-11 08:50:19.172', '2024-12-11 08:50:19.172', NULL, NULL, 'Teknik'),
(4, 5, '19800503200312', '2024-12-11 08:50:50.627', '2024-12-11 08:50:50.627', NULL, NULL, 'Teknik'),
(5, 6, '19790222200501', '2024-12-11 08:51:42.396', '2024-12-11 08:51:42.396', NULL, NULL, 'Teknik'),
(6, 7, '19841104200812', '2024-12-11 08:53:52.955', '2024-12-11 08:53:52.955', NULL, NULL, 'Teknik'),
(7, 8, '19780309200312', '2024-12-11 08:54:25.688', '2024-12-11 08:54:25.688', NULL, NULL, 'Teknik'),
(8, 9, '19800325200312', '2024-12-11 08:55:02.788', '2024-12-11 08:55:02.788', NULL, NULL, 'Teknik'),
(9, 10, '19780225200501', '2024-12-11 08:55:46.133', '2024-12-11 08:55:46.133', NULL, NULL, 'Teknik'),
(10, 11, '19840716200812', '2024-12-11 08:56:32.866', '2024-12-11 08:56:32.866', NULL, NULL, 'Teknik'),
(11, 12, '19830305200604', '2024-12-11 08:57:08.369', '2024-12-11 08:57:08.369', NULL, NULL, 'Teknik'),
(12, 13, '19780820200212', '2024-12-11 08:57:44.860', '2024-12-11 08:57:44.860', NULL, NULL, 'Teknik'),
(13, 14, '19790510200604', '2024-12-11 08:58:30.747', '2024-12-11 08:58:30.747', NULL, NULL, 'Teknik'),
(14, 15, '19780317200312', '2024-12-11 08:59:10.808', '2024-12-11 08:59:10.808', NULL, NULL, 'Teknik'),
(15, 16, '19830607200604', '2024-12-11 08:59:52.179', '2024-12-24 15:02:30.769', NULL, 'Komputasi Ilmiah', 'Teknik'),
(16, 17, '19800820200312', '2024-12-11 09:00:24.563', '2024-12-11 09:00:24.563', NULL, NULL, 'Teknik'),
(17, 18, '19740221200801', '2024-12-11 09:00:54.305', '2024-12-23 09:44:46.412', NULL, NULL, 'Teknik'),
(18, 19, '19891011202012', '2024-12-11 09:01:37.125', '2024-12-11 09:01:37.125', NULL, NULL, 'Teknik'),
(19, 20, '19760627200801', '2024-12-11 09:02:59.953', '2024-12-11 09:02:59.953', NULL, NULL, 'Teknik'),
(20, 21, '19790828200501', '2024-12-11 09:04:10.777', '2024-12-11 09:04:10.777', NULL, NULL, 'Teknik'),
(21, 22, '19770722200312', '2024-12-11 09:05:03.618', '2024-12-11 09:05:03.618', NULL, NULL, 'Teknik'),
(22, 23, '19881018201504', '2024-12-11 09:05:55.010', '2024-12-11 09:05:55.010', NULL, NULL, 'Teknik'),
(23, 24, '19810820200604', '2024-12-11 09:06:33.147', '2024-12-11 09:06:33.147', NULL, NULL, 'Teknik'),
(24, 25, '19790217200312', '2024-12-11 09:07:01.610', '2024-12-11 09:07:01.610', NULL, NULL, 'Teknik'),
(25, 26, '19770713200212', '2024-12-11 09:07:31.955', '2024-12-11 09:07:31.955', NULL, NULL, 'Teknik'),
(26, 27, '19730520200212', '2024-12-11 09:08:22.133', '2024-12-11 09:08:22.133', NULL, NULL, 'Teknik'),
(27, 28, '19691118200112', '2024-12-11 09:08:48.444', '2024-12-11 09:08:48.444', NULL, NULL, 'Teknik'),
(28, 29, '19790313200604', '2024-12-11 09:09:45.536', '2024-12-11 09:09:45.536', NULL, NULL, 'Teknik'),
(29, 30, '19840413200812', '2024-12-11 09:10:21.516', '2024-12-11 09:10:21.516', NULL, NULL, 'Teknik'),
(30, 31, '19800213200604', '2024-12-11 09:10:46.724', '2024-12-11 09:10:46.724', NULL, NULL, 'Teknik');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('ADMIN','STUDENT','TEACHER') COLLATE utf8mb4_unicode_ci NOT NULL,
  `createdAt` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updatedAt` datetime(3) NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recoveryToken` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggalLahir` datetime(3) DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('MAN','WOMAN') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `role`, `createdAt`, `updatedAt`, `token`, `recoveryToken`, `tanggalLahir`, `telephone`, `gender`, `photo`) VALUES
(1, 'admin@gmail.com', '$2b$10$OYCiSPc20.byGhX3MM9/iulhFF.Szg/hqjTRHwK5ZfpPVwwWJAI8W', 'Admin libur', 'ADMIN', '2024-12-11 08:30:31.668', '2024-12-24 14:23:03.655', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'abdullah.basuki@if.trunojoyo.ac.id', '$2b$10$.pfLjnJCyGj/p9E9rFFJAu/bkGfSt49nX2ONHDYR8/Vh2GuX501pC', 'Abdullah Basuki Rahmat, S.Si., M.T.', 'TEACHER', '2024-12-11 08:47:58.153', '2024-12-24 13:55:01.761', NULL, NULL, '1979-03-13 00:00:00.000', NULL, 'MAN', NULL),
(3, 'khozaimi@trunojoyo.ac.id', '$2b$10$qQQhVGB.cpq9lxK3btshz.UIwlzSbnwo/HAEUvFt16OREJcButtgS', 'Ach Khozaimi,S.Kom.,M.Kom.', 'TEACHER', '2024-12-11 08:48:45.213', '2024-12-24 13:55:01.764', NULL, NULL, '1987-05-13 00:00:00.000', NULL, 'MAN', NULL),
(4, 'jauhari@trunojoyo.ac.id', '$2b$10$VY5tiIkBmuCyl.aPk9IkCOMS5n4UPagcO0xEps8WnNUv/Pf/1vuje', 'Achmad Jauhari, S.T.M.Kom.', 'TEACHER', '2024-12-11 08:50:19.172', '2024-12-24 13:55:01.767', NULL, NULL, '1985-04-13 00:00:00.000', NULL, 'MAN', NULL),
(5, 'andharini@if.trunojoyo.ac.id', '$2b$10$vgfaqYCuJwPMAfmdSXjCluXGCm6P9rIV4DpevQVCa5HJAIVGHUC0K', 'Andharini Dwi Cahyani, S.Kom.M.Kom., Ph.D.', 'TEACHER', '2024-12-11 08:50:50.627', '2024-12-24 13:55:01.769', NULL, NULL, '1988-04-13 00:00:00.000', NULL, 'WOMAN', NULL),
(6, 'ari.kusumaningsih@trunojoyo.ac.id', '$2b$10$VmNs.JcsnlLHd2jxusUioOOKwmlNiOGP/ulyusTHET7m5i46ltE5m', 'Ari Kusumaningsih, S.T., M.T.', 'TEACHER', '2024-12-11 08:51:42.396', '2024-12-24 14:29:35.476', 'c7fbf30b-1b6c-407d-b7b5-4f000ab2aee4', NULL, '1988-05-13 00:00:00.000', NULL, 'WOMAN', NULL),
(7, 'devros_gress@trunojoyo.ac.id', '$2b$10$vCA9CZKYsutwzbNadsgX8u6Hh3jHWgBm.Qk9vBbnXQvKG7QP3XTgm', 'Devie Rosa Anamisa, S.Kom.M.Kom.', 'TEACHER', '2024-12-11 08:53:52.955', '2024-12-24 13:55:01.773', NULL, NULL, '1988-03-13 00:00:00.000', NULL, 'WOMAN', NULL),
(8, 'arik.kurniawati@trunojoyo.ac.id', '$2b$10$h9u3SoRe.dBOLAT2WzN7NO70T9CRpKt8TmunpSqDughuYIVV.dYyK', 'Dr. Arik Kurniawati, S.Kom., M.T.', 'TEACHER', '2024-12-11 08:54:25.688', '2024-12-24 13:55:01.775', NULL, NULL, '1987-03-13 00:00:00.000', NULL, 'WOMAN', NULL),
(9, 'bain@trunojoyo.ac.id', '$2b$10$6141SonS.Ids8Y1b4SVqyenUT/ubhG7GI3F1qnq2BjlScjSDUW/6m', 'Dr. Bain Khusnul Khotimah, S.T., M.Kom.', 'TEACHER', '2024-12-11 08:55:02.788', '2024-12-24 13:55:01.777', NULL, NULL, '1987-05-13 00:00:00.000', NULL, 'WOMAN', NULL),
(10, 'cucunvery@trunojoyo.ac.id', '$2b$10$EvQ7xixkQOB24K080vW2x.mgihOLd/wNM7tYqzC0Zw9AVvtTNyf9i', 'Dr. Cucun Very Angkoso, S.T.,M.T.', 'TEACHER', '2024-12-11 08:55:46.133', '2024-12-24 13:55:01.779', NULL, NULL, '1987-05-13 00:00:00.000', NULL, 'MAN', NULL),
(11, 'em_sari@trunojoyo.ac.id', '$2b$10$OkqHQ/u4I6iXSaSwFVyPbuMXZp01B/ohYWAWdR0Cxyc3NCYESP5vy', 'Dr. Eka Mala Sari Rochman, S.Kom.M.Kom.', 'TEACHER', '2024-12-11 08:56:32.866', '2024-12-24 13:55:01.782', NULL, NULL, '1985-05-13 00:00:00.000', NULL, 'WOMAN', NULL),
(12, 'fika@trunojoyo.ac.id', '$2b$10$lpPGbbatk8HaOvFzBY/QWObsD9uiW4g5aMWn1nsenDRV/WzdrS0t2', 'Dr. Fika Hastarita Rachman, S.T.,M.Eng.', 'TEACHER', '2024-12-11 08:57:08.369', '2024-12-24 13:55:01.786', NULL, NULL, '1985-02-13 00:00:00.000', NULL, 'WOMAN', NULL),
(13, 'indah.siradjuddin@trunojoyo.ac.id', '$2b$10$Mm5Yp7lpxk0ElBBBdheG6u3QvjUnPhV130EBpFcku6PbEjvDQ8eFq', 'Dr. Indah Agustien Siradjuddin, S.Kom.,M.Kom', 'TEACHER', '2024-12-11 08:57:44.860', '2024-12-24 13:55:01.789', NULL, NULL, '1985-08-13 00:00:00.000', NULL, 'WOMAN', NULL),
(14, 'meidya@trunojoyo.ac.id', '$2b$10$qAEWJRdatXihjFM.hlmebO0XG6Rp7q7cmiVR0m3QiQnUlXhgrYLzO', 'Dr. Meidya Koeshardianto, S.Si., M.T.', 'TEACHER', '2024-12-11 08:58:30.747', '2024-12-24 13:55:01.791', NULL, NULL, '1985-01-13 00:00:00.000', NULL, 'MAN', NULL),
(15, 'noor.ifada@trunojoyo.ac.id', '$2b$10$YIKhEvi8qcSGdDY5J2nL/en2ovgmczsBfuHJKzNgCxfRpLDckpCoK', 'Dr. Noor Ifada, S.T., MISD.', 'TEACHER', '2024-12-11 08:59:10.808', '2024-12-24 13:55:01.794', NULL, NULL, '1985-03-16 00:00:00.000', NULL, 'WOMAN', NULL),
(16, 'rika.yunitarini@trunojoyo.ac.id', '$2b$10$FDv4CbBf1nxaZiTISZno.eJhjcAdphDc99v3fK0Hjh9Qub1gFjreW', 'Dr. Rika Yunitarini, S.T.M.T.', 'TEACHER', '2024-12-11 08:59:52.179', '2024-12-24 15:02:30.769', '9a0b0dc1-b42d-4d33-a2a8-00fa35e6b400', NULL, '1965-03-16 00:00:00.000', NULL, 'WOMAN', NULL),
(17, 'rimatriwahyuningrum@trunojoyo.ac.id', '$2b$10$hPAuwF/jEXnHO9.ehowTkevX1ETraMVKStk87jtZOKr2c5G4KMaQC', 'Dr. Rima Tri Wahyuningrum, S.T., M.T.', 'TEACHER', '2024-12-11 09:00:24.563', '2024-12-24 14:56:48.276', NULL, NULL, '1965-03-16 00:00:00.000', NULL, 'WOMAN', NULL),
(18, 'dwi.kuswanto@trunojoyo.ac.id', '$2b$10$YpEQK0C9ICJEYKx2FD6UwevniXsRFW.Qq93pLuQ1/9FQGtuKvqDRG', 'Dwi Kuswanto, S.Pd., M.T.', 'TEACHER', '2024-12-11 09:00:54.305', '2024-12-24 13:55:01.802', NULL, NULL, '1965-03-16 00:00:00.000', NULL, 'MAN', NULL),
(19, 'fifin.mufarroha@trunojoyo.ac.id', '$2b$10$dhJbNyNZRYpaIkSpSHSE3e60jeE0TQGKCdmNUpT87b1UHTBrMw0WC', 'Fifin Ayu Mufarroha, S.Kom., M.Kom.', 'TEACHER', '2024-12-11 09:01:37.125', '2024-12-24 13:55:01.804', NULL, NULL, '1968-03-16 00:00:00.000', NULL, 'WOMAN', NULL),
(20, 'firdaus@trunojoyo.ac.id', '$2b$10$GqDY2RVy/Lc37F52Z3ZWr.qp21mcfl8pLcIYReSPmp9pPOYQDXrVG', 'Firdaus Solihin, S.Kom., M.Kom.', 'TEACHER', '2024-12-11 09:02:59.953', '2024-12-24 13:55:01.806', NULL, NULL, '1967-03-16 00:00:00.000', NULL, 'MAN', NULL),
(21, 'hermawan@trunojoyo.ac.id', '$2b$10$bXqrrHXj9EadTOByQb8u9uNr4HSzwOjptWihqPTXhwI6Z6snnr/rq', 'Hermawan, S.T.M.Kom.', 'TEACHER', '2024-12-11 09:04:10.777', '2024-12-24 13:55:01.808', NULL, NULL, '1967-03-16 00:00:00.000', NULL, 'MAN', NULL),
(22, 'husni@trunojoyo.ac.id', '$2b$10$JZN54OoHRr1kvE.9gRpUz.JiRhp9fb1Yqea4l9qqLBKcdQSd2UtIm', 'Husni, S.Kom., M.T.', 'TEACHER', '2024-12-11 09:05:03.618', '2024-12-24 13:55:01.810', NULL, NULL, '1965-03-16 00:00:00.000', NULL, 'MAN', NULL),
(23, 'iosuzanti@trunojoyo.ac.id', '$2b$10$Nwdtw7uHB/1QK0GNBBV3mele9/Ng/Z5lYgKNO3qxsV2EbTOO0iNHe', 'Ika Oktavia Suzanti, S.Kom.,M.Cs.', 'TEACHER', '2024-12-11 09:05:55.010', '2024-12-24 13:55:01.813', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'WOMAN', NULL),
(24, 'ichwan20@gmail.com', '$2b$10$TTUw.XlWLTP2cjZaxa4e/O0zbNOqRkNG8l8fDKRUl/VlLuHVXpHq6', 'Iwan Santosa, S.T.,M.T.', 'TEACHER', '2024-12-11 09:06:33.147', '2024-12-24 13:55:01.816', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(25, 'kurniawan@trunojoyo.ac.id', '$2b$10$Rd9Ou5ys4KgxJb1W2NLbzO.keFFWVjYonDvMORfDhIwaB3qTj1y02', 'Kurniawan Eka Permana, S.Kom., M.Sc.', 'TEACHER', '2024-12-11 09:07:01.610', '2024-12-24 14:58:30.254', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(26, 'kautsar@trunojoyo.ac.id', '$2b$10$WJ.cfhLGtRgsta1U9IL/o.j5sCk2cfcPEgDtmIX1kgqwcJlplWkNC', 'Moch. Kautsar Sophan, S.Kom., M.M.T.', 'TEACHER', '2024-12-11 09:07:31.955', '2024-12-24 13:55:01.820', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(27, 'mulaab@trunojoyo.ac.id', '$2b$10$wbwcfKEoPscmCaFbxh4An.6x4fvetWbr.I5/pOdxjxO8dv56UVwdO', 'Mula\'ab, S.Si., M.Kom.', 'TEACHER', '2024-12-11 09:08:22.133', '2024-12-24 13:55:01.822', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(28, 'arifmuntasa@trunojoyo.ac.id', '$2b$10$ANbVU2kan6rUjVcES7jAD.nhTjvaGpcbzCqwrChxsxxS6H95G7XLm', 'Prof. Dr. Arif Muntasa, M.T.', 'TEACHER', '2024-12-11 09:08:48.444', '2024-12-24 13:55:01.824', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(29, 'sigit.putro@trunojoyo.ac.id', '$2b$10$Eo/s/hsfgAYUVuud0xQq2uIelNfyz1PInXwcY/./DHWELQ96rdiUW', 'Sigit Susanto Putro, S.Kom.M.Kom', 'TEACHER', '2024-12-11 09:09:45.536', '2024-12-24 13:55:01.826', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(30, 'yoga@trunojoyo.ac.id', '$2b$10$LbyPpfM21yMXO2emCoC2dO.ZVGbtIoAMAIejHjFB/U5wlXscUXfXi', 'Yoga Dwitya Pramudita, S.Kom. M.Cs.', 'TEACHER', '2024-12-11 09:10:21.516', '2024-12-24 13:55:01.828', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(31, 'yonathan.hendrawan@trunojoyo.ac.id', '$2b$10$51imb4lTJFzHQN57gC1TzejxV.NFIxbOZ23ciwvrZL3SFmQdf0xhG', 'Yonathan Ferry Hendrawan, S.T.MIT.', 'TEACHER', '2024-12-11 09:10:46.724', '2024-12-24 13:55:01.830', NULL, NULL, '1964-03-16 00:00:00.000', NULL, 'MAN', NULL),
(32, '230411100031@student.trunojoyo.ac.id', '$2b$10$QzJnUG/H7alkGt7TbfJAAOxSD.oLF8cFOJHCjJFLJLXVRdGdeb6/2', 'Ahmad Mufid Risqi', 'STUDENT', '2024-12-11 09:18:01.935', '2024-12-24 14:55:50.534', 'd6a06bb8-7f47-4f47-9dae-f8b573132b47', NULL, '2005-03-27 00:00:00.000', '087715567904', 'MAN', NULL),
(33, '230411100198@student.trunojoyo.ac.id', '$2b$10$Xm7KxqubFapwUDbth0cEcOJuiuGPDG/.uyNcEzw5HALz8Jzzshs4q', 'Imam Syafi\'i', 'STUDENT', '2024-12-11 09:21:42.436', '2024-12-24 13:55:01.835', NULL, NULL, '2005-06-29 00:00:00.000', '0877987654', 'MAN', NULL),
(39, 'admin2@gmail.com', '$2b$10$hp/cwmExz3HPpGn/B0zW5OHk20QuywEYTa3M7zh1G15cPF0b85kJe', 'admin2', 'ADMIN', '2024-12-22 08:04:21.265', '2024-12-22 08:04:21.265', NULL, NULL, NULL, NULL, NULL, NULL),
(41, '230411100059@student.trunojoyo.ac.id', '$2b$10$Mtpipul.fojhPLcKVQERsOLpNW8DkHMVTfUkgf1OfhidAJJxymmA6', 'Ach. Lutfi Madani', 'STUDENT', '2024-12-23 11:56:04.769', '2024-12-24 13:55:01.837', NULL, NULL, '2005-02-23 00:00:00.000', NULL, 'MAN', NULL),
(42, '230411100003@student.trunojoyo.ac.id', '$2b$10$3bwzen9JBc48ucLifeYGueYfUFp2ecFWKQtwreBaj9zqqACa5zvnS', 'Harits Putra Junaidi', 'STUDENT', '2024-12-23 11:58:22.591', '2024-12-24 13:55:01.839', NULL, NULL, '2005-06-16 00:00:00.000', NULL, 'MAN', NULL),
(43, '230411100192@student.trunojoyo.ac.id', '$2b$10$RDNrDzcCCmtYC8ai/yA9zO58xrfeLtXgWZA5OIOWf3.clqg3fK5QW', 'M. Javier Akmal Hadi', 'STUDENT', '2024-12-23 12:00:29.402', '2024-12-24 13:55:01.841', NULL, NULL, '2005-06-23 00:00:00.000', NULL, 'MAN', NULL),
(44, '230411100195@student.trunojoyo.ac.id', '$2b$10$G3sx2/KJctgpQJ9x9cBvl.FmFmmY9tRz8MbgSaIY8zrJd.5Rdv3G2', 'Moh. Ariel Rifqi Ahsan', 'STUDENT', '2024-12-23 12:02:06.156', '2024-12-24 13:55:01.843', NULL, NULL, '2005-06-23 00:00:00.000', NULL, 'MAN', NULL),
(45, 'admin3@gmail.com', '$2b$10$QOlAorN09bHPuHUPmsFTj.clgM7dnqj6XRRngUrP.f2T3MNHmmZn2', 'admin3', 'ADMIN', '2024-12-24 13:47:23.982', '2024-12-24 13:47:23.982', NULL, NULL, NULL, NULL, NULL, NULL),
(47, 'student@student.trunojoyo.ac.id', '$2b$10$TTHI9rlia3HlT.HOwe3G.uF6G4aZeudHIMccfAPy5Y3w15nvcu9G.', 'student1', 'STUDENT', '2024-12-24 13:50:14.772', '2024-12-24 13:55:01.846', NULL, NULL, '2024-12-04 00:00:00.000', NULL, 'MAN', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `_prisma_migrations`
--

CREATE TABLE `_prisma_migrations` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checksum` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `finished_at` datetime(3) DEFAULT NULL,
  `migration_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logs` text COLLATE utf8mb4_unicode_ci,
  `rolled_back_at` datetime(3) DEFAULT NULL,
  `started_at` datetime(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `applied_steps_count` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `_prisma_migrations`
--

INSERT INTO `_prisma_migrations` (`id`, `checksum`, `finished_at`, `migration_name`, `logs`, `rolled_back_at`, `started_at`, `applied_steps_count`) VALUES
('017fd07a-b051-44cd-8f66-676de169f65d', '56fda484fa871ef18e584b506295d90013914a31af05171974c759c7cbb96765', '2024-12-11 08:13:12.696', '20241127023308_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.693', 1),
('22627788-5eea-40a6-9034-24ca2237ffae', '1bed9ad8f79a1e87389756c00170f0472ba35886581cf63630988bae59af96ae', '2024-12-11 08:13:12.711', '20241130154500_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.706', 1),
('2553a2bc-b088-4878-a8cd-4f9410262929', '2f14edade4c41870007d636897fd142bc28cefe5d99c01de09d66c519eec584e', '2024-12-12 11:39:17.528', '20241212113917_siakad_db_nest', NULL, NULL, '2024-12-12 11:39:17.484', 1),
('259c2762-e0db-4255-9bac-a6689a7312f4', '0adb63fe4a8778f80688fe3aa90adadd201da33f360bee9930cfb16febd2a2b6', '2024-12-20 10:50:27.883', '20241220105027_siakad_db_nest', NULL, NULL, '2024-12-20 10:50:27.877', 1),
('28db142f-da31-433d-a444-ede90d2e18a9', '5dad2a64fc82aa2a822e8b839f14275e95909ce44baa9fd3ed285ecb5646be0b', '2024-12-11 08:13:12.690', '20241125055116_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.675', 1),
('3e412052-e50c-451e-a77e-958851d0818a', '5b9f8647a237d646cc16943dcee401c9282ed1a508f3fcf76845477d03a7d859', '2024-12-22 06:36:00.909', '20241222063600_siakad_db_nest', NULL, NULL, '2024-12-22 06:36:00.851', 1),
('42b31491-0aff-40c4-80ed-2e964683c2c9', '6ed816f053b47e28422d896350792f2ba55c6d0c56177764d68b79f6195bf5b9', '2024-12-23 08:59:52.701', '20241223085952_siakad_db_nest', NULL, NULL, '2024-12-23 08:59:52.697', 1),
('63bdf8fd-8383-460f-8222-918826e023b0', 'b0adc6ef1522939699046120bc6ae3892f1cb497a5af705ecff8037423db2e83', '2024-12-11 08:13:12.287', '20241026084149_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.268', 1),
('648efd6a-3310-48ad-b24b-610c4b51715b', 'f9d55d5d3b190ea6e158624bd67dfe9503888febbccfd67a417389876c7a7105', '2024-12-11 08:13:12.267', '20241025104403_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.234', 1),
('6582fe13-6f36-461c-b7ab-88c0a1afd08b', '5fa044d587ebecaaf5230d6be8e81f3e21b0de74cb7b9bf5a5a6f2d18c46d60a', '2024-12-11 08:13:12.467', '20241120083056_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.424', 1),
('68389124-7296-4f3e-8427-80348f3ec5c2', '024f6461d371dce46dc569e1ae918f5a0d1d3a0df25b4c56ef56198c0f8b23c1', '2024-12-14 13:36:30.184', '20241214133630_siakad_db_nest', NULL, NULL, '2024-12-14 13:36:30.178', 1),
('713f9803-152e-485f-80da-9af051d5b069', '692bac9e9d8d636e13987e420b112a5574ffe60cf2728202a23e81492e29444d', '2024-12-11 08:13:12.766', '20241211080747_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.731', 1),
('73fcf18c-7ee0-466d-b367-06eb7ea8dd08', '4c849c4edeec08b59232b8ada80af6a5edb28296e49f0ef3225aad69fd4d9cc2', '2024-12-11 08:13:12.692', '20241126121804_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.690', 1),
('8961f1e8-87ae-4c6f-bf60-7b9680cb9d92', 'c9eed4fd455a3309431f9e2d61ce6195d2861d3e18d19a1b243fff1034526cb6', '2024-12-16 12:11:16.999', '20241216121116_siakad_db_nest', NULL, NULL, '2024-12-16 12:11:16.992', 1),
('8b3ec088-3351-469b-9d92-49cf08a82125', 'b1e9969043b943724baeed7b869113b75392d8eac9b95d786f53d777ab81a398', '2024-12-23 08:58:24.339', '20241223085824_siakad_db_nest', NULL, NULL, '2024-12-23 08:58:24.331', 1),
('8f8b3a44-1a92-456c-924c-992e447242f0', '1d0c0bedb3d50771d8b9519ff372ec2a7831fd6544d4f76125fb7ba22ab4c778', '2024-12-11 08:13:12.423', '20241117141617_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.295', 1),
('a8a655b9-faf5-415f-a92d-5c684c3549bd', '5c5a6d90876bb1ad0f96b22eabb998cb4f07d27c036228c0ef0b621da650f64d', '2024-12-11 08:13:12.654', '20241123123309_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.649', 1),
('a9e6a1e6-565f-482a-90b8-5719ddb42c96', '2804fa4ef5f84e008c87dd5b68a2083b6f5325fc640c41e8fdbe56c0d224eead', '2024-12-11 08:13:12.675', '20241125043954_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.655', 1),
('af8cd27d-07c0-46ac-a504-da80e9c05766', '795fac07576a520adb8cf6de2282d843ee1310bca1a2100792dd767cc8488e5e', '2024-12-20 01:47:06.625', '20241220014706_siakad_db_nest', NULL, NULL, '2024-12-20 01:47:06.619', 1),
('bb59fc9b-d04c-44d9-bb0c-e2262b58d63f', '259a3a0e07837b08bef3395f9f7cdadfad1081c1f0ee6b80b0f21639423ec9ed', '2024-12-11 08:13:12.730', '20241201180523_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.712', 1),
('bfe3e7d2-74f6-4b62-aaff-f08e4a91d9b6', '5dddad9938a55d303f9f0951a5f8970fa218966e9c2599efa285d6eff2c14a95', '2024-12-23 08:57:45.408', '20241223085745_siakad_db_nest', NULL, NULL, '2024-12-23 08:57:45.387', 1),
('c4313736-8dd0-4301-85aa-01a4effed328', '3ad4f028544478aa2c3583336e61501726dfe1d00021a4fce10edb4f67c38fc8', '2024-12-11 08:13:12.705', '20241128041614_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.697', 1),
('c6a8ec10-2e7f-4e98-9308-b6f89efa8b94', '162240cbf276359d776b9143179b99170ed998a68a2707bcef95035c9f2d782d', '2024-12-11 08:13:12.234', '20240930143306_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.066', 1),
('c86ed20c-759f-4481-bc7e-25473a3d3e3c', 'c8a02909100f86d36cfed90e4892c830bb258f55b40627d10bbe04e1daa4a1df', '2024-12-11 08:13:12.294', '20241105033329_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.291', 1),
('ce3f5fb2-8568-488e-b3f7-be86223b5db2', '6012e0bef3a3296e40ea9154394e96254913596f5729a09ce4ed37b21eaf5766', '2024-12-11 08:13:12.065', '20240929165925_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:11.897', 1),
('d726b76c-2b52-471f-9d91-18d129802a0f', 'c76f9b591d6be22373b5dea941e8aa58287e6bf10945e806590c1b2185044d4f', '2024-12-11 08:13:12.649', '20241123101244_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.467', 1),
('dd20a535-3b14-4432-85c3-09fbd4a47900', 'bae4cf38bc7da0e2329673ac952beaea79d4f748f616dc5b0b8633c498ea6596', '2024-12-22 20:33:56.274', '20241222203356_siakad_db_nest', NULL, NULL, '2024-12-22 20:33:56.266', 1),
('e488d7b2-8f4d-4bd8-8bac-7e6709655d87', 'b20b5c2bd4d061e7d9dbbbab8ef690663fe082db09d66c43ee72501fb0f81552', '2024-12-22 06:39:56.517', '20241222063956_siakad_db_nest', NULL, NULL, '2024-12-22 06:39:56.507', 1),
('e5a6eba0-6700-46a8-868f-45bd6ec68ea5', '5b397751929c4b5d19c13b578a262216eff5e4fef6a85143293074404b52dc03', '2024-12-11 08:13:12.290', '20241026164747_siakad_db_nest', NULL, NULL, '2024-12-11 08:13:12.288', 1),
('e7ecff1b-6703-44ad-9309-9137cf2901e3', 'e9ab6a83a485e6538f6f8520296084e264b24b6a63ec5bb69f2e6d943a76069c', '2024-12-22 06:31:11.898', '20241222063111_siakad_db_nest', NULL, NULL, '2024-12-22 06:31:11.866', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absences_studentId_fkey` (`studentId`),
  ADD KEY `absences_scheduleId_fkey` (`scheduleId`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Admin_userId_key` (`userId`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_code_key` (`code`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `enrollments_studentId_fkey` (`studentId`),
  ADD KEY `enrollments_scheduleId_fkey` (`scheduleId`),
  ADD KEY `enrollments_validatedById_fkey` (`validatedById`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evaluations_enrollmentId_key` (`enrollmentId`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `graduates`
--
ALTER TABLE `graduates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `graduates_studentId_key` (`studentId`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `libraries`
--
ALTER TABLE `libraries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_userId_fkey` (`userId`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_studentId_fkey` (`studentId`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_courseId_fkey` (`courseId`),
  ADD KEY `schedules_teacherId_fkey` (`teacherId`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serverstatus`
--
ALTER TABLE `serverstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_userId_key` (`userId`),
  ADD UNIQUE KEY `students_nim_key` (`nim`),
  ADD KEY `students_academicAdvisorId_fkey` (`academicAdvisorId`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teachers_userId_key` (`userId`),
  ADD UNIQUE KEY `teachers_nip_key` (`nip`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_key` (`email`);

--
-- Indexes for table `_prisma_migrations`
--
ALTER TABLE `_prisma_migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `graduates`
--
ALTER TABLE `graduates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `libraries`
--
ALTER TABLE `libraries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `serverstatus`
--
ALTER TABLE `serverstatus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_scheduleId_fkey` FOREIGN KEY (`scheduleId`) REFERENCES `schedules` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `absences_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `students` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `Admin_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_scheduleId_fkey` FOREIGN KEY (`scheduleId`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollments_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `enrollments_validatedById_fkey` FOREIGN KEY (`validatedById`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_enrollmentId_fkey` FOREIGN KEY (`enrollmentId`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `graduates`
--
ALTER TABLE `graduates`
  ADD CONSTRAINT `graduates_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_studentId_fkey` FOREIGN KEY (`studentId`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_courseId_fkey` FOREIGN KEY (`courseId`) REFERENCES `courses` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `schedules_teacherId_fkey` FOREIGN KEY (`teacherId`) REFERENCES `teachers` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_academicAdvisorId_fkey` FOREIGN KEY (`academicAdvisorId`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `students_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_userId_fkey` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
