-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 13, 2025 at 11:57 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticketsystem`
--

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
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `privacy_policy` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `created_by`, `title`, `category`, `event_description`, `location`, `start_date`, `end_date`, `ticket_price`, `status`, `privacy_policy`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 4, 'Event 1', 'Tech', 'Et ea nobis sequi excepturi. Vel placeat non rerum molestias saepe hic. Pariatur voluptatem qui blanditiis id aut. Quo nihil aut non blanditiis.', 'Stammfort', '2025-07-14 10:52:11', '2025-07-14 13:52:11', 362.51, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,1', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(2, 4, 'Event 2', 'Tech', 'Omnis architecto consequuntur quidem eveniet. Ratione tenetur animi ut occaecati. Et nulla ratione expedita unde voluptate error et.', 'North Miamouth', '2025-07-15 10:52:11', '2025-07-15 13:52:11', 797.21, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,2', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(3, 4, 'Event 3', 'Tech', 'Repellat omnis quod consequatur inventore animi repudiandae. Iusto quos vel fugiat velit nemo aut. Veritatis facilis beatae eligendi sunt qui facilis. Est quis et laudantium quaerat.', 'Violaview', '2025-07-16 10:52:11', '2025-07-16 13:52:11', 528.63, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,3', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(4, 4, 'Event 4', 'Sports', 'Corporis repellat temporibus iusto fugit. Est aut praesentium blanditiis dicta quia est consequuntur. Et quidem omnis aut inventore quia facilis magni explicabo.', 'Sporertown', '2025-07-17 10:52:11', '2025-07-17 13:52:11', 331.35, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,4', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(5, 4, 'Event 5', 'Tech', 'Reiciendis dolores similique sapiente est. Aliquam ut est voluptas. Eius ipsa iste aut doloremque alias molestiae. Qui omnis tempore iusto dolorum. Voluptas aperiam aut totam dolores qui enim accusamus.', 'Lake Tommie', '2025-07-18 10:52:11', '2025-07-18 13:52:11', 542.11, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,5', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(6, 4, 'Event 6', 'Sports', 'In voluptate natus ut doloremque quis. Alias et enim est earum veniam dolor facilis occaecati. Minus odio vitae aut sit.', 'Graciehaven', '2025-07-19 10:52:11', '2025-07-19 13:52:11', 141.59, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,6', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(7, 4, 'Event 7', 'Sports', 'In velit quos qui quia veritatis maiores provident. Dolores consequuntur officia sit vero. Aut ut hic neque ea illo sunt.', 'Gusikowskichester', '2025-07-20 10:52:11', '2025-07-20 13:52:11', 481.35, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,7', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(8, 4, 'Event 8', 'Sports', 'Ut ut sint mollitia deserunt vel nobis. Ducimus porro similique enim voluptas ea. Dolor ipsum consequuntur rerum et alias rerum magni sint. Asperiores non officiis soluta qui dolorem.', 'Riceburgh', '2025-07-21 10:52:11', '2025-07-21 13:52:11', 195.86, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,8', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(9, 4, 'Event 9', 'Business', 'Aut minus et omnis voluptates debitis ea enim. Cupiditate quasi illum numquam magni aut fugiat natus delectus. Occaecati quo commodi dicta dolore.', 'Brayanfort', '2025-07-22 10:52:11', '2025-07-22 13:52:11', 942.45, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,9', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(10, 4, 'Event 10', 'Business', 'Nam officia labore earum ut at. Animi est suscipit id omnis. Voluptas similique blanditiis voluptas aliquid porro corporis blanditiis.', 'Demarcoside', '2025-07-23 10:52:11', '2025-07-23 13:52:11', 188.56, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,10', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(11, 4, 'Event 11', 'Sports', 'Quaerat et voluptas asperiores corrupti est quam sunt corrupti. Nobis debitis sit ut voluptatem enim. Est distinctio tempore quas. Fuga numquam eum molestiae maiores in minus ut.', 'Port Maureen', '2025-07-24 10:52:11', '2025-07-24 13:52:11', 185.61, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,11', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(12, 4, 'Event 12', 'Music', 'Ullam ut dolor architecto aut sequi. Id sed possimus quos repellendus itaque voluptate deserunt. Sit molestiae eos deserunt corrupti fugiat rerum iste dicta.', 'Johnsland', '2025-07-25 10:52:11', '2025-07-25 13:52:11', 163.40, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,12', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(13, 4, 'Event 13', 'Sports', 'Error in asperiores repellat. Qui facilis quo vero quis. Molestiae omnis molestiae autem.', 'Markville', '2025-07-26 10:52:11', '2025-07-26 13:52:11', 507.88, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,13', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(14, 4, 'Event 14', 'Music', 'Exercitationem qui quo qui minus et magnam similique. Explicabo libero nihil est nihil cum. Quisquam praesentium corporis in illum. Numquam sunt placeat sint consequatur ullam.', 'Bryanafort', '2025-07-27 10:52:11', '2025-07-27 13:52:11', 211.84, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,14', '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(15, 4, 'Event 15', 'Tech', 'Tenetur sequi asperiores accusamus debitis fugiat. Qui et debitis hic neque praesentium maiores eligendi. Aspernatur atque ducimus ab necessitatibus tenetur quis ut.', 'Rogahnshire', '2025-07-28 10:52:11', '2025-07-28 13:52:11', 548.17, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://source.unsplash.com/600x400/?event,15', '2025-07-13 04:52:11', '2025-07-13 04:52:11');

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
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_10_051559_create_personal_access_tokens_table', 1),
(5, '2025_07_13_050216_create_events_table', 1),
(6, '2025_07_13_090454_create_tickets_table', 1);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `event_id` bigint UNSIGNED NOT NULL,
  `ticket_quantity` int NOT NULL,
  `price_per_ticket` decimal(10,2) NOT NULL,
  `status` enum('booked','canceled','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'booked',
  `purchased_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','organizer','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', NULL, '$2y$12$XkwAw.aok346rLus7OMEuu/sIOGverfe8boPTxylh3A6obcr8ABqe', 'admin', NULL, '2025-07-13 04:52:09', '2025-07-13 04:52:09'),
(2, 'Normal User', 'user@gmail.com', NULL, '$2y$12$q2M4FV82/IrAxZXvacO2Ku84bL91ynI3ry7Aus5UwKJuXx1ku.fq.', 'user', NULL, '2025-07-13 04:52:09', '2025-07-13 04:52:09'),
(3, 'Organizer User', 'organizer@gmail.com', NULL, '$2y$12$OU3GNwTHq8UKC0ro.kua3.G7Cce7QJGNOROl5763OwEyHYdUctHym', 'organizer', NULL, '2025-07-13 04:52:10', '2025-07-13 04:52:10'),
(4, 'Organizer', 'organizer@example.com', NULL, '$2y$12$ZCSJIqD3hw6HZEaK839Ms.IjPjQgiFowm2..MI5f1QNDtYvZEejzq', 'organizer', NULL, '2025-07-13 04:52:11', '2025-07-13 04:52:11'),
(5, 'Mark Mckee', 'byva@mailinator.com', NULL, '$2y$12$WwFto0JefUC1occxoAMA/uGWMvk5RBndyCS4.r4.lpYXeXnHcRP72', 'user', NULL, '2025-07-13 05:52:49', '2025-07-13 05:52:49');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_created_by_foreign` (`created_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_user_id_foreign` (`user_id`),
  ADD KEY `tickets_event_id_foreign` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
