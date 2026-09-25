-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 08:42 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parham_academy`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `action` varchar(80) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 00:25:41'),
(2, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 00:26:31'),
(3, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 09:55:09'),
(4, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 10:00:53'),
(5, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 10:16:33'),
(6, 1, 'admin_lesson_create', 'افزودن درس به دوره: گریم حرفه‌ای عروس', '::1', '2026-09-04 10:18:26'),
(7, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 10:19:43'),
(8, 1, 'admin_lesson_delete', 'حذف درس: درس دو نیما', '::1', '2026-09-04 10:21:18'),
(9, 1, 'admin_course_update', 'ویرایش دوره: گریم حرفه‌ای عروس', '::1', '2026-09-04 10:22:16'),
(10, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 10:47:02'),
(11, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-04 10:59:58'),
(12, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 15:36:28'),
(13, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 16:17:37'),
(14, 1, 'logout', 'خروج از حساب', '::1', '2026-09-05 16:18:39'),
(15, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 16:18:52'),
(16, 1, 'logout', 'خروج از حساب', '::1', '2026-09-05 17:13:30'),
(17, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 18:23:12'),
(18, 1, 'logout', 'خروج از حساب', '::1', '2026-09-05 18:23:35'),
(19, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 18:24:54'),
(20, 1, 'logout', 'خروج از حساب', '::1', '2026-09-05 18:28:27'),
(21, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 18:29:48'),
(22, 1, 'logout', 'خروج از حساب', '::1', '2026-09-05 18:30:38'),
(23, NULL, 'register', 'ساخت حساب کاربری جدید', '::1', '2026-09-05 18:31:19'),
(24, NULL, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 18:31:20'),
(25, NULL, 'logout', 'خروج از حساب', '::1', '2026-09-05 18:31:41'),
(26, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-05 18:32:54'),
(27, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 09:34:56'),
(28, 1, 'logout', 'خروج از حساب', '::1', '2026-09-06 09:39:53'),
(29, 4, 'register', 'ساخت حساب کاربری جدید', '::1', '2026-09-06 09:41:00'),
(30, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 09:41:00'),
(31, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 09:42:21'),
(32, 4, 'logout', 'خروج از حساب', '::1', '2026-09-06 09:43:55'),
(33, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 09:44:10'),
(34, 1, 'admin_access_grant', 'دسترسی برای nima2 به دورهٔ گریم حرفه‌ای عروس', '::1', '2026-09-06 09:44:49'),
(35, 1, 'logout', 'خروج از حساب', '::1', '2026-09-06 09:45:24'),
(36, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 09:45:46'),
(37, 4, 'logout', 'خروج از حساب', '::1', '2026-09-06 09:49:51'),
(38, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 09:50:16'),
(39, 1, 'admin_user_update', 'ویرایش کاربر: nima2', '::1', '2026-09-06 09:50:59'),
(40, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 10:43:19'),
(41, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 12:20:19'),
(42, 1, 'admin_access_extend', 'تمدید 30 روزه برای nima2', '::1', '2026-09-06 12:27:09'),
(43, 1, 'admin_access_delete', 'حذف دسترسی nima2 از دورهٔ گریم حرفه‌ای عروس', '::1', '2026-09-06 12:27:23'),
(44, 1, 'admin_access_delete', 'حذف دسترسی demo از دورهٔ اصلاح و طراحی ریش حرفه‌ای', '::1', '2026-09-06 12:27:38'),
(45, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 12:36:16'),
(46, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 12:36:38'),
(47, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 19:36:59'),
(48, 1, 'admin_reset_password', 'تغییر رمز کاربر: nima', '::1', '2026-09-06 19:37:21'),
(49, 1, 'admin_user_update', 'ویرایش کاربر: nima', '::1', '2026-09-06 19:37:21'),
(50, 1, 'logout', 'خروج از حساب', '::1', '2026-09-06 19:37:23'),
(51, NULL, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 19:37:43'),
(52, NULL, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 19:40:18'),
(53, NULL, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 19:49:23'),
(54, NULL, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:11:59'),
(55, NULL, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:26:31'),
(56, NULL, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:27:31'),
(57, NULL, 'logout', 'خروج از حساب', '::1', '2026-09-06 20:28:43'),
(58, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:29:36'),
(59, 1, 'admin_user_delete', 'حذف کاربر: nima', '::1', '2026-09-06 20:31:35'),
(60, 1, 'admin_user_delete', 'حذف کاربر: demo', '::1', '2026-09-06 20:31:48'),
(61, 1, 'admin_user_update', 'ویرایش کاربر: nima2', '::1', '2026-09-06 20:37:21'),
(62, 1, 'admin_access_grant', 'دسترسی برای nima2 به دورهٔ گریم حرفه‌ای عروس', '::1', '2026-09-06 20:38:42'),
(63, 1, 'admin_access_unlimited', 'تغییر نامحدودی دسترسی #4', '::1', '2026-09-06 20:39:05'),
(64, 1, 'admin_access_unlimited', 'تغییر نامحدودی دسترسی #4', '::1', '2026-09-06 20:39:21'),
(65, 1, 'admin_access_disable', 'قطع دسترسی کاربر nima2 از دورهٔ گریم حرفه‌ای عروس', '::1', '2026-09-06 20:39:28'),
(66, 1, 'logout', 'خروج از حساب', '::1', '2026-09-06 20:39:44'),
(67, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:40:09'),
(68, 1, 'admin_reset_password', 'تغییر رمز کاربر: nima2', '::1', '2026-09-06 20:40:25'),
(69, 1, 'admin_user_update', 'ویرایش کاربر: nima2', '::1', '2026-09-06 20:40:25'),
(70, 1, 'logout', 'خروج از حساب', '::1', '2026-09-06 20:40:45'),
(71, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:40:59'),
(72, 4, 'logout', 'خروج از حساب', '::1', '2026-09-06 20:41:31'),
(73, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:42:42'),
(74, 1, 'admin_access_enable', 'وصل دسترسی کاربر nima2 از دورهٔ گریم حرفه‌ای عروس', '::1', '2026-09-06 20:42:47'),
(75, 1, 'logout', 'خروج از حساب', '::1', '2026-09-06 20:42:51'),
(76, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:43:08'),
(77, 4, 'logout', 'خروج از حساب', '::1', '2026-09-06 20:43:21'),
(78, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:43:36'),
(79, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:47:17'),
(80, 4, 'logout', 'خروج از حساب', '::1', '2026-09-06 20:47:52'),
(81, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 20:48:04'),
(82, 1, 'admin_lesson_update', 'ویرایش درس: درس ۱ — معرفی دوره و ابزارها', '::1', '2026-09-06 21:38:39'),
(83, 1, 'admin_access_delete', 'حذف دسترسی nima2 از دورهٔ گریم حرفه‌ای عروس', '::1', '2026-09-06 21:39:49'),
(84, 1, 'logout', 'خروج از حساب', '::1', '2026-09-06 21:39:52'),
(85, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 21:41:32'),
(86, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 21:44:49'),
(87, 4, 'login', 'ورود موفق به حساب', '::1', '2026-09-06 21:52:39'),
(88, 4, 'logout', 'خروج از حساب', '::1', '2026-09-06 22:04:04'),
(89, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 11:19:30'),
(90, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 21:17:20'),
(91, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 21:41:10'),
(92, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 21:55:14'),
(93, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 22:08:29'),
(94, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 22:18:19'),
(95, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 22:20:39'),
(96, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 22:43:23'),
(97, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 22:50:39'),
(98, 1, 'admin_course_create', 'ساخت دوره: lk', '::1', '2026-09-08 22:53:11'),
(99, 1, 'logout', 'خروج از حساب', '::1', '2026-09-08 23:00:00'),
(100, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-08 23:18:11'),
(101, 1, 'admin_course_delete', 'حذف دوره: lk', '::1', '2026-09-08 23:20:51'),
(102, 1, 'admin_lesson_update', 'ویرایش درس: درس ۱ — معرفی دوره و ابزارها', '::1', '2026-09-08 23:42:53'),
(103, 1, 'logout', 'خروج از حساب', '::1', '2026-09-12 19:22:10'),
(104, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-19 14:30:54'),
(105, 1, 'logout', 'خروج از حساب', '::1', '2026-09-23 19:04:37'),
(106, 1, 'login', 'ورود موفق به حساب', '::1', '2026-09-23 19:06:10');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `short_description` varchar(300) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price_display` decimal(12,0) NOT NULL DEFAULT 0 COMMENT 'تومان — فقط نمایشی',
  `cover_image` varchar(1000) DEFAULT NULL,
  `level` enum('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'published',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `slug`, `short_description`, `description`, `price_display`, `cover_image`, `level`, `status`, `is_featured`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'گریم حرفه‌ای عروس', 'bridal-makeup', 'از پاکسازی پوست تا گریم کامل عروس در ۶ درس عملی.', 'در این پکیج قدم به قدم با تمام مراحل گریم عروس آشنا می‌شوید: شناخت پوست، اجرای زیرساز، سایه‌زنی حرفه‌ای چشم، کنتور، رژ لب ماندگار و تکنیک‌های ثبات گریم در مراسم طولانی.', 4800000, 'course-20260904-102216-348df505.png', 'intermediate', 'published', 1, 1, '2026-09-04 00:20:06', '2026-09-04 10:22:16'),
(2, 'اصلاح و طراحی ریش حرفه‌ای', 'professional-beard-styling', 'طراحی، فید و فرم‌دهی ریش مخصوص ارایشگران مردانه.', 'آموزش کامل فرم‌دهی ریش متناسب با فرم صورت، کار با ماشین و تیغ، خط‌گیری دقیق، فید محو، رنگ و مراقبت پس از اصلاح.', 2500000, NULL, 'beginner', 'published', 1, 2, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(3, 'میکروپیگمنتیشن ابرو', 'microblading', 'طراحی و اجرای ابروی طبیعی با تکنیک موی به موی.', 'مبانی رنگ‌شناسی، بهداشت و استریلیزاسیون، فرمول طراحی ابرو، عمق کار، مدیریت مشتری و مراقبت‌های بعد از کار.', 7200000, NULL, 'advanced', 'published', 0, 3, '2026-09-04 00:20:06', '2026-09-04 00:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `course_access`
--

CREATE TABLE `course_access` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `start_date` date DEFAULT NULL COMMENT 'NULL = از همین الان',
  `end_date` date DEFAULT NULL COMMENT 'NULL = بی‌پایان',
  `is_unlimited` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'قطع/وصل دستی توسط مدیر',
  `note` varchar(255) DEFAULT NULL,
  `granted_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` text DEFAULT NULL,
  `video_file` varchar(1000) NOT NULL,
  `duration_minutes` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_free_preview` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'درس معرفی رایگان',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `description`, `video_file`, `duration_minutes`, `sort_order`, `is_free_preview`, `created_at`, `updated_at`) VALUES
(1, 1, 'درس ۱ — معرفی دوره و ابزارها', 'معرفی ابزار و محصولات مورد نیاز.', 'https://aspb1.cdn.asset.aparat.com/aparat-video/3c633b3e27e450124e4497732ce035fd6507866-360p.mp4?wmsAuthSign=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0b2tlbiI6IjNkMzZkMTgzMmE2NTNiZTJjMzM1YTVhMDYwYzI0ZGUyIiwiZXhwIjoxNzg4OTE2MzIwLCJpc3MiOiJTYWJhIElkZWEgR1NJRyJ9.yGb-rr76YUeQVgJCq3fbrtJpQV05KR9nmRPSyeV_XBQ', 14, 1, 1, '2026-09-04 00:20:06', '2026-09-08 23:42:53'),
(2, 1, 'درس ۲ — پاکسازی و آماده‌سازی پوست', NULL, 'bridal-02.mp4', 22, 2, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(3, 1, 'درس ۳ — زیرساز و کرم‌پودر', NULL, 'bridal-03.mp4', 27, 3, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(4, 1, 'درس ۴ — سایه‌زنی حرفه‌ای چشم', NULL, 'bridal-04.mp4', 35, 4, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(5, 1, 'درس ۵ — کنتور و هایلایت', NULL, 'bridal-05.mp4', 19, 5, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(6, 1, 'درس ۶ — رژ لب ماندگار و ثبات گریم', NULL, 'bridal-06.mp4', 17, 6, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(7, 2, 'درس ۱ — شناخت فرم صورت', NULL, 'beard-01.mp4', 12, 1, 1, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(8, 2, 'درس ۲ — خط‌گیری و طراحی', NULL, 'beard-02.mp4', 18, 2, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(9, 2, 'درس ۳ — فید محو و کار با ماشین', NULL, 'beard-03.mp4', 24, 3, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(10, 2, 'درس ۴ — مراقبت و محصولات پایانی', NULL, 'beard-04.mp4', 15, 4, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(11, 3, 'درس ۱ — بهداشت و استریلیزاسیون', NULL, 'micro-01.mp4', 20, 1, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(12, 3, 'درس ۲ — رنگ‌شناسی پیگمنت', NULL, 'micro-02.mp4', 26, 2, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06'),
(13, 3, 'درس ۳ — فرمول طراحی ابرو', NULL, 'micro-03.mp4', 31, 3, 0, '2026-09-04 00:20:06', '2026-09-04 00:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(60) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(60) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('access_expired_message', 'دسترسی شما به پایان رسیده است. لطفاً برای تمدید دسترسی با مدیر آکادمی تماس بگیرید.', '2026-09-04 00:20:06'),
('contact_instagram', 'parham.academy', '2026-09-04 00:20:06'),
('contact_phone', '021-00000000', '2026-09-04 00:20:06'),
('contact_telegram', '@parham_academy', '2026-09-04 00:20:06'),
('site_tagline', 'مرجع آموزش تخصصی زیبایی و گریم', '2026-09-04 00:20:06'),
('site_title', 'آکادمی پرهام', '2026-09-04 00:20:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(60) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(120) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_logged_in` tinyint(1) NOT NULL DEFAULT 0,
  `note` varchar(255) DEFAULT NULL COMMENT 'یادداشت داخلی مدیر',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `full_name`, `role`, `is_active`, `is_logged_in`, `note`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$9A.gKWkEf9Rw/mHk5u17NeaigD1peUKeRxageq/sKOjO8.y9.0ajO', 'مدیر آکادمی پرهام', 'admin', 1, 1, NULL, '2026-09-23 19:06:10', '2026-09-04 00:20:06', '2026-09-23 19:09:13'),
(4, 'nima2', '$2y$10$Qc0dv2SziJJVWj9ou2Z9hOc3hnaS1heKAOFpvSaASXvf95GsS4vOq', NULL, 'user', 1, 0, NULL, '2026-09-06 21:52:39', '2026-09-06 09:41:00', '2026-09-06 22:04:04');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_active_access`
-- (See below for the actual view)
--
CREATE TABLE `v_active_access` (
`id` int(10) unsigned
,`user_id` int(10) unsigned
,`username` varchar(60)
,`course_id` int(10) unsigned
,`course_title` varchar(180)
,`start_date` date
,`end_date` date
,`is_unlimited` tinyint(1)
,`access_state` varchar(7)
);

-- --------------------------------------------------------

--
-- Structure for view `v_active_access`
--
DROP TABLE IF EXISTS `v_active_access`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_active_access`  AS SELECT `ca`.`id` AS `id`, `ca`.`user_id` AS `user_id`, `u`.`username` AS `username`, `ca`.`course_id` AS `course_id`, `c`.`title` AS `course_title`, `ca`.`start_date` AS `start_date`, `ca`.`end_date` AS `end_date`, `ca`.`is_unlimited` AS `is_unlimited`, CASE WHEN `ca`.`is_active` = 0 THEN 'revoked' WHEN `ca`.`is_unlimited` = 1 THEN 'active' WHEN `ca`.`start_date` is not null AND `ca`.`start_date` > curdate() THEN 'pending' WHEN `ca`.`end_date` is not null AND `ca`.`end_date` < curdate() THEN 'expired' ELSE 'active' END AS `access_state` FROM ((`course_access` `ca` join `users` `u` on(`u`.`id` = `ca`.`user_id`)) join `courses` `c` on(`c`.`id` = `ca`.`course_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_log_user` (`user_id`,`created_at`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_courses_slug` (`slug`),
  ADD KEY `idx_courses_status` (`status`),
  ADD KEY `idx_courses_featured` (`is_featured`);

--
-- Indexes for table `course_access`
--
ALTER TABLE `course_access`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_access_user_course` (`user_id`,`course_id`),
  ADD KEY `idx_access_course` (`course_id`),
  ADD KEY `idx_access_dates` (`end_date`,`is_unlimited`,`is_active`),
  ADD KEY `fk_access_granted_by` (`granted_by`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lessons_course` (`course_id`,`sort_order`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attempts_lookup` (`username`,`attempted_at`),
  ADD KEY `idx_attempts_ip` (`ip_address`,`attempted_at`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_username` (`username`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_active` (`is_active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course_access`
--
ALTER TABLE `course_access`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `course_access`
--
ALTER TABLE `course_access`
  ADD CONSTRAINT `fk_access_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_access_granted_by` FOREIGN KEY (`granted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_access_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `fk_lessons_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
