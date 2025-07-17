-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 17, 2025 at 10:13 AM
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-942bLXFGtHABlrjV', 'a:1:{s:11:\"valid_until\";i:1752726679;}', 1753936279),
('laravel-cache-frEeT0DfTZQ6KauR', 'a:1:{s:11:\"valid_until\";i:1752663018;}', 1753872678),
('laravel-cache-Jeo5HWhjz28gB9Dp', 'a:1:{s:11:\"valid_until\";i:1752726795;}', 1753936455),
('laravel-cache-kNTUwrCdmFVP6mzM', 'a:1:{s:11:\"valid_until\";i:1752727459;}', 1753936579),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:19:{i:0;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:19:\"create events admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:1;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:17:\"edit events admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:2;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:19:\"delete events admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:3;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:17:\"view events admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:4;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:23:\"create events organizer\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:5;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:21:\"edit events organizer\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:6;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:23:\"delete events organizer\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:7;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:21:\"view events organizer\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:8;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:23:\"create categories admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:9;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:21:\"edit categories admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:10;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:23:\"delete categories admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:11;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:21:\"view categories admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:12;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"manage users admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:13;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:20:\"manage tickets admin\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:14;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:18:\"create ticket user\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:7;}}i:15;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:16:\"View ticket user\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:7;}}i:16;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:20:\"view admin dashboard\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:1:{i:0;i:5;}}i:17;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:24:\"view organizer dashboard\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:6;}}i:18;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:19:\"view user dashboard\";s:1:\"c\";s:3:\"api\";s:1:\"r\";a:2:{i:0;i:5;i:1;i:7;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"api\";}i:1;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:9:\"organizer\";s:1:\"c\";s:3:\"api\";}i:2;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"api\";}}}', 1752823258),
('laravel-cache-XHwTkSw47EhlLpiP', 'a:1:{s:11:\"valid_until\";i:1752726841;}', 1753936501);

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sports', 'inactive', '2025-07-16 03:51:46', '2025-07-16 03:51:46'),
(2, 'Technology', 'active', '2025-07-16 03:51:46', '2025-07-16 03:51:46'),
(3, 'Theater', 'inactive', '2025-07-16 03:51:46', '2025-07-16 03:51:46'),
(4, 'Comedy', 'active', '2025-07-16 03:51:46', '2025-07-16 03:51:46'),
(5, 'Tech', 'inactive', '2025-07-16 03:51:47', '2025-07-16 03:51:47'),
(6, 'Music', 'active', '2025-07-16 21:53:28', '2025-07-16 21:53:28'),
(7, 'Art', 'active', '2025-07-16 21:53:29', '2025-07-16 21:53:29'),
(8, 'Education', 'inactive', '2025-07-16 21:53:29', '2025-07-16 21:53:29'),
(9, 'Business', 'active', '2025-07-16 21:53:29', '2025-07-16 21:53:29'),
(10, 'Gaming', 'active', '2025-07-16 21:53:29', '2025-07-16 21:53:29'),
(11, 'Health', 'inactive', '2025-07-16 21:53:29', '2025-07-16 21:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint UNSIGNED NOT NULL,
  `created_by` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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

INSERT INTO `events` (`id`, `created_by`, `category_id`, `title`, `event_description`, `location`, `start_date`, `end_date`, `ticket_price`, `status`, `privacy_policy`, `image_url`, `created_at`, `updated_at`) VALUES
(16, 18, 1, 'Community Coding Workshop: Laravel Basics', 'A hands-on workshop for beginners to learn the basics of web development with Laravel, focusing on practical exercises and project building.', 'Dhaka University CSE Department', '2025-08-01 10:00:00', '2025-08-01 16:00:00', 0.00, 'draft', 'privacy_policy 02', 'https://example.com/images/music-fest-2025.jpg', '2025-07-16 22:16:29', '2025-07-17 00:45:51'),
(17, 22, 5, 'Event 2', 'Debitis quos voluptatibus impedit ut. Delectus aspernatur ut magnam et. Molestiae tenetur id error aliquid error veniam et. Commodi suscipit qui laboriosam expedita eveniet officiis facilis nihil.', 'East Heavenmouth', '2025-07-19 04:16:29', '2025-07-19 07:16:29', 243.43, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1601582581421-98d09c5badd7', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(18, 19, 9, 'Event 3', 'Quibusdam aliquam laudantium rerum at. Omnis animi repudiandae soluta temporibus eum iste autem. Accusamus est aut pariatur eos molestias fugit corrupti saepe. Iste in officia sed consequatur voluptatibus.', 'West Annabell', '2025-07-20 04:16:29', '2025-07-20 07:16:29', 356.83, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1552664730-d307ca884978', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(19, 19, 9, 'Event 4', 'Quae et tenetur perferendis alias. Et dicta qui consequatur soluta adipisci. Commodi aut excepturi a reiciendis molestiae repudiandae. Ad ut dolor ut doloremque distinctio.', 'East Rosie', '2025-07-21 04:16:29', '2025-07-21 07:16:29', 639.77, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1552664730-d307ca884978', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(20, 22, 9, 'Event 5', 'Minus voluptatem et culpa esse est aspernatur eaque qui. Vel recusandae magnam repellendus et rem suscipit sint.', 'Steuberbury', '2025-07-22 04:16:29', '2025-07-22 07:16:29', 204.92, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1601582581421-98d09c5badd7', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(21, 18, 5, 'Event 6', 'Et eligendi qui deserunt odit. Placeat maiores nulla maxime iste et impedit est ducimus. Accusamus ab facere necessitatibus corporis fugit cupiditate.', 'Carrollview', '2025-07-23 04:16:29', '2025-07-23 07:16:29', 992.38, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1552664730-d307ca884978', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(22, 13, 5, 'Event 7', 'Sint distinctio nam ipsum voluptas dolores omnis tempora illum. Omnis quam aperiam ex nobis iste tenetur. Qui aut modi et magni sunt quis quisquam. Qui itaque repudiandae quod nesciunt magni rerum.', 'Alfberg', '2025-07-24 04:16:29', '2025-07-24 07:16:29', 912.87, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1564869732666-d8e1f82f2ca9', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(23, 21, 5, 'Event 8', 'Illum voluptatem deserunt ducimus facilis placeat. Quos ea voluptates molestiae qui occaecati et voluptas. Ea commodi dolor dicta numquam. Libero eum aperiam reiciendis cum voluptatibus.', 'New Emilio', '2025-07-25 04:16:29', '2025-07-25 07:16:29', 721.34, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1531058020387-3be344556be6', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(24, 13, 6, 'Event 9', 'Nobis repudiandae cumque facere quo rerum et. Voluptatem corrupti earum doloremque expedita ut similique nesciunt doloribus. Recusandae at veniam cupiditate.', 'West Daphneeburgh', '2025-07-26 04:16:29', '2025-07-26 07:16:29', 407.00, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1601582581421-98d09c5badd7', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(25, 22, 5, 'Event 10', 'Veritatis recusandae voluptatibus animi autem. Quibusdam delectus dolor accusamus autem est ut qui. Occaecati autem vitae tempore qui quos eos velit. Eos dolorem error ea omnis.', 'East Boydside', '2025-07-27 04:16:29', '2025-07-27 07:16:29', 926.08, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1531058020387-3be344556be6', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(26, 23, 9, 'Event 11', 'Et ut sunt voluptatem ut. Vitae tenetur consequatur et reiciendis aut. Similique aliquid earum accusantium molestiae. Blanditiis voluptas vero dolor repellendus. Cum ut adipisci omnis aut.', 'South Elenora', '2025-07-28 04:16:29', '2025-07-28 07:16:29', 879.84, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1564869732666-d8e1f82f2ca9', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(27, 19, 5, 'Event 12', 'Voluptates et velit ipsa laborum. Officiis vel consequatur aut voluptas tenetur sit vel. Exercitationem velit eaque dignissimos et blanditiis occaecati.', 'Grahamfurt', '2025-07-29 04:16:29', '2025-07-29 07:16:29', 220.19, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1581324090439-c58f9f06f8a1', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(28, 18, 6, 'Event 13', 'Soluta est sunt et minus illum quam. Non est quod nemo consequatur harum atque est placeat. Ipsam ab voluptate doloremque enim nisi voluptate consequatur.', 'Othoside', '2025-07-30 04:16:29', '2025-07-30 07:16:29', 280.19, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1552664730-d307ca884978', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(29, 13, 9, 'Event 14', 'Voluptate repellat modi quo eius animi mollitia reiciendis. Et necessitatibus ipsam consequatur et delectus id odit. Dolorem rerum et praesentium omnis laborum nulla. Cupiditate aut fugiat cumque autem error iste omnis.', 'South Toniberg', '2025-07-31 04:16:29', '2025-07-31 07:16:29', 198.39, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1531058020387-3be344556be6', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(30, 23, 9, 'Event 15', 'Et magni atque vel nulla. Est sunt consequuntur magni nostrum error. Ipsam quod consequatur distinctio id. Ad ab autem autem voluptas numquam. Esse tempora quia similique aliquid aut quia.', 'Rhodaland', '2025-08-01 04:16:29', '2025-08-01 07:16:29', 327.49, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1531058020387-3be344556be6', '2025-07-16 22:16:29', '2025-07-16 22:16:29'),
(31, 23, 5, 'Event 1', 'Ut cupiditate quis consequatur sit. Delectus porro porro sequi voluptatem consequatur. Accusantium sit quia inventore ut vel et. Quaerat aliquam tempora quod repudiandae quia aut quia.', 'Omerside', '2025-07-18 04:17:36', '2025-07-18 07:17:36', 169.74, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(32, 19, 6, 'Event 2', 'Rerum officia sit rerum vel. Sint minima aliquid officiis aut harum ut. Ut maxime natus ut sit in. Rerum quidem ut soluta.', 'Lake Lynnmouth', '2025-07-19 04:17:36', '2025-07-19 07:17:36', 147.16, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(33, 18, 4, 'Event 3', 'Cumque voluptas animi aliquid et distinctio praesentium vero. Expedita alias labore error temporibus sed. Est hic et quos enim et voluptas reiciendis. Eos earum voluptates ut non modi nulla non.', 'South Geobury', '2025-07-20 04:17:36', '2025-07-20 07:17:36', 462.54, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1464375117522-1311f55a04c7?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(34, 22, 4, 'Event 4', 'Minus sed ea non voluptatem. Doloribus debitis sapiente amet voluptas eum. Sint illo maiores debitis provident praesentium dolorum.', 'Judyland', '2025-07-21 04:17:36', '2025-07-21 07:17:36', 282.77, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(35, 13, 6, 'Event 5', 'Nihil ut nostrum natus eum. Enim est dolorem saepe et. Ea ea esse in in harum non.', 'Kuhlmanport', '2025-07-22 04:17:36', '2025-07-22 07:17:36', 893.13, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(36, 21, 1, 'Event 6', 'Necessitatibus voluptas accusamus ipsum commodi id exercitationem. Quis perferendis quo quasi fuga. Excepturi possimus inventore in id. Illo aliquam sit ab consequatur.', 'Eleanoraburgh', '2025-07-23 04:17:36', '2025-07-23 07:17:36', 899.83, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(37, 23, 1, 'Event 7', 'Dolorem hic officiis itaque unde quis quasi. Et est nemo maiores omnis facilis. Eum sed recusandae possimus tenetur voluptatum voluptatem necessitatibus. Asperiores omnis maiores odit aut quo. Quia aspernatur ex enim qui laudantium cumque.', 'North Malvina', '2025-07-24 04:17:36', '2025-07-24 07:17:36', 985.00, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(38, 23, 1, 'Event 8', 'Est magnam aliquid porro doloremque. Non ut ex nostrum reprehenderit voluptatem rem. Iusto excepturi consequatur eos omnis nam quo. Aliquid repellendus consequatur nesciunt accusantium amet sed minima et.', 'Larkinville', '2025-07-25 04:17:36', '2025-07-25 07:17:36', 740.78, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(39, 21, 9, 'Event 9', 'Dolor impedit animi dolorum voluptatem voluptatum. Est temporibus maiores officia. Autem architecto aliquid eius nemo enim nemo nulla.', 'Lake Briaville', '2025-07-26 04:17:36', '2025-07-26 07:17:36', 414.40, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(40, 18, 9, 'Event 10', 'Voluptatem laborum neque numquam velit. Sit ab blanditiis fuga assumenda eum et. Consequatur ut non ducimus praesentium voluptas dolorem omnis quos.', 'Lake Kylerfurt', '2025-07-27 04:17:36', '2025-07-27 07:17:36', 793.68, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(41, 13, 9, 'Event 11', 'Asperiores ut sed quod non. Voluptates saepe debitis velit corporis nostrum quam consequatur. Ipsa aspernatur et consectetur qui ex nostrum rem.', 'South Borisfort', '2025-07-28 04:17:36', '2025-07-28 07:17:36', 594.58, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(42, 13, 5, 'Event 12', 'Esse corrupti voluptatem nam repellendus saepe. Nostrum magnam ratione rerum unde suscipit expedita occaecati fuga. Delectus pariatur sapiente laudantium quidem fugit.', 'Sigmundtown', '2025-07-29 04:17:36', '2025-07-29 07:17:36', 483.90, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(43, 23, 1, 'Event 13', 'Aut dolor iste voluptatem vero. Non cupiditate voluptas nesciunt corporis adipisci cumque.', 'Port Wiltonville', '2025-07-30 04:17:36', '2025-07-30 07:17:36', 797.24, 'completed', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(44, 22, 6, 'Event 14', 'Et pariatur similique consequuntur. Veritatis facere eius qui facere suscipit consequatur. Sit tenetur aliquid dolorem consequatur tempora veniam. Quod quos ab reiciendis aperiam id cumque dolor necessitatibus. Qui libero quidem explicabo omnis tempora quis voluptas occaecati.', 'Larryborough', '2025-07-31 04:17:36', '2025-07-31 07:17:36', 619.01, 'cancelled', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1464375117522-1311f55a04c7?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(45, 20, 9, 'Event 15', 'Quis eius optio quidem dolore. Aut odio quia illum optio. Maxime eum vel velit quia nam aut. Quos sed eius ex temporibus provident. Nesciunt laboriosam a reiciendis numquam et dignissimos.', 'Schmittfort', '2025-08-01 04:17:36', '2025-08-01 07:17:36', 552.76, 'upcoming', 'All tickets are non-refundable unless the event is cancelled.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', '2025-07-16 22:17:36', '2025-07-16 22:17:36'),
(46, 18, 5, 'Event 1', 'Odit facilis quibusdam mollitia consequatur voluptatem. Id nihil soluta odit voluptatem pariatur. Alias fugiat possimus aut ad aut. Ut numquam dolorem facere in unde.', 'Dariusshire', '2025-07-26 05:00:52', '2025-08-13 05:00:52', 786.98, 'completed', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:52', '2025-07-16 23:00:52'),
(47, 22, 1, 'Event 2', 'Eum et id magni qui aut officiis. Aliquam praesentium quo asperiores quis. Dignissimos cumque ut aspernatur itaque. Nobis ut non quidem totam.', 'East Clovis', '2025-07-26 05:00:53', '2025-08-16 05:00:53', 199.42, 'upcoming', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(48, 22, 6, 'Event 3', 'Quis in autem nulla. Neque voluptatem non porro. Placeat non aspernatur ipsum totam. Eum sed voluptatem voluptatem.', 'Allyfurt', '2025-07-27 05:00:53', '2025-08-09 05:00:53', 254.27, 'upcoming', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(49, 21, 5, 'Event 4', 'Aut aut qui voluptatem ut eius accusamus et. Sapiente sit harum ipsam et quasi quia in. Numquam ad voluptatibus est aliquam eaque esse.', 'Port Angeline', '2025-07-19 05:00:53', '2025-08-07 05:00:53', 800.98, 'upcoming', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1515165562835-cd0c48e6b0a3?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(50, 18, 6, 'Event 5', 'Quia sequi porro facilis dolor et tempora a. Illo illum nesciunt vel est quia et. Tempora necessitatibus aliquam neque iusto sit nihil omnis. Recusandae vero suscipit nam sapiente qui nam sed.', 'North Laurettaview', '2025-07-29 05:00:53', '2025-08-07 05:00:53', 502.83, 'completed', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(51, 23, 1, 'Event 6', 'Quasi aut aliquam optio laboriosam qui et enim. Rerum enim eaque recusandae possimus eos magni. Magnam laudantium error rerum deleniti voluptas omnis.', 'Dickimouth', '2025-08-01 05:00:53', '2025-08-02 05:00:53', 361.84, 'upcoming', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(52, 21, 1, 'Event 7', 'Incidunt nam doloremque et. Voluptatem est ea est ut. Sed et suscipit voluptas commodi deserunt. Qui molestiae numquam voluptas quasi laboriosam ratione eos.', 'Albertchester', '2025-07-29 05:00:53', '2025-08-14 05:00:53', 586.10, 'completed', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(53, 13, 1, 'Event 8', 'Ut aut commodi est aperiam nam sunt. Consectetur provident eius quia ipsum. Dolor ipsa explicabo iste eveniet culpa.', 'Jaidaton', '2025-07-25 05:00:53', '2025-08-02 05:00:53', 765.41, 'completed', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(54, 22, 9, 'Event 9', 'Totam asperiores qui omnis. Quia mollitia aspernatur sunt aliquam nisi. Alias est quaerat velit est voluptate. Commodi iusto natus debitis harum. Et mollitia voluptatibus error qui.', 'Joanaton', '2025-07-19 05:00:53', '2025-08-06 05:00:53', 949.75, 'upcoming', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(55, 13, 9, 'Event 10', 'Velit eveniet soluta nesciunt reprehenderit quia. Consequuntur doloremque ad rerum aut consectetur qui quos. Reprehenderit sed esse tempore facilis.', 'Elysemouth', '2025-07-23 05:00:53', '2025-08-07 05:00:53', 480.26, 'upcoming', 'Tickets are non-refundable unless canceled.', 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=800&q=80', '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(56, 13, 1, 'Community Coding Workshop: Laravel Basics', 'A hands-on workshop for beginners to learn the basics of web development with Laravel, focusing on practical exercises and project building.', 'Dhaka University CSE Department', '2025-08-01 10:00:00', '2025-08-01 16:00:00', 0.00, 'draft', 'privacy_policy 02', 'https://example.com/images/music-fest-2025.jpg', '2025-07-17 00:45:38', '2025-07-17 00:45:38');

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
(5, '2025_07_11_041954_create_categories_table', 1),
(6, '2025_07_13_050216_create_events_table', 1),
(7, '2025_07_13_090454_create_tickets_table', 1),
(8, '2025_07_16_073655_create_permission_tables', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(5, 'App\\Models\\User', 12),
(6, 'App\\Models\\User', 13),
(7, 'App\\Models\\User', 14),
(5, 'App\\Models\\User', 16),
(5, 'App\\Models\\User', 17),
(6, 'App\\Models\\User', 18),
(6, 'App\\Models\\User', 19),
(6, 'App\\Models\\User', 20),
(6, 'App\\Models\\User', 21),
(6, 'App\\Models\\User', 22),
(6, 'App\\Models\\User', 23),
(7, 'App\\Models\\User', 24),
(7, 'App\\Models\\User', 25),
(7, 'App\\Models\\User', 26);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(20, 'create events admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(21, 'edit events admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(22, 'delete events admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(23, 'view events admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(24, 'create events organizer', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(25, 'edit events organizer', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(26, 'delete events organizer', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(27, 'view events organizer', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(28, 'create categories admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(29, 'edit categories admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(30, 'delete categories admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(31, 'view categories admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(32, 'manage users admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(33, 'manage tickets admin', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(34, 'create ticket user', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(35, 'View ticket user', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(36, 'view admin dashboard', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(37, 'view organizer dashboard', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06'),
(38, 'view user dashboard', 'api', '2025-07-17 01:20:06', '2025-07-17 01:20:06');

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
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(5, 'admin', 'api', '2025-07-16 04:27:16', '2025-07-16 04:27:16'),
(6, 'organizer', 'api', '2025-07-16 04:27:16', '2025-07-16 04:27:16'),
(7, 'user', 'api', '2025-07-16 04:27:17', '2025-07-16 04:27:17');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(25, 5),
(26, 5),
(27, 5),
(28, 5),
(29, 5),
(30, 5),
(31, 5),
(32, 5),
(33, 5),
(34, 5),
(35, 5),
(36, 5),
(37, 5),
(38, 5),
(24, 6),
(25, 6),
(26, 6),
(27, 6),
(37, 6),
(34, 7),
(35, 7),
(38, 7);

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

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `event_id`, `ticket_quantity`, `price_per_ticket`, `status`, `purchased_at`, `created_at`, `updated_at`) VALUES
(1, 14, 47, 3, 199.42, 'refunded', '2025-07-12 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(2, 14, 48, 1, 254.27, 'booked', '2025-07-12 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(3, 14, 49, 1, 800.98, 'refunded', '2025-07-13 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(4, 14, 55, 1, 480.26, 'canceled', '2025-07-13 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(5, 24, 48, 3, 254.27, 'canceled', '2025-07-12 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(6, 24, 51, 1, 361.84, 'canceled', '2025-07-15 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(7, 24, 53, 3, 765.41, 'canceled', '2025-07-13 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(8, 25, 51, 1, 361.84, 'refunded', '2025-07-15 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(9, 25, 52, 1, 586.10, 'booked', '2025-07-12 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(10, 25, 55, 3, 480.26, 'booked', '2025-07-11 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(11, 26, 49, 2, 800.98, 'canceled', '2025-07-13 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(12, 26, 50, 1, 502.83, 'booked', '2025-07-13 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(13, 26, 51, 2, 361.84, 'canceled', '2025-07-11 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(14, 26, 52, 1, 586.10, 'refunded', '2025-07-15 23:00:54', '2025-07-16 23:00:54', '2025-07-16 23:00:54'),
(15, 14, 22, 2, 912.87, 'booked', '2025-07-17 00:43:12', '2025-07-17 00:43:12', '2025-07-17 00:43:12');

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
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(12, 'Admin User', 'admin@gmail.com', NULL, '$2y$12$p2DlWFpaNPw0sWe.PlEPAuqZRQHxz1VLBmSiDSpGD8MVvNGDjysOW', NULL, '2025-07-16 04:31:50', '2025-07-16 04:31:50'),
(13, 'Organizer User', 'organizer@gmail.com', NULL, '$2y$12$VoPiPGYyiayd/nyIGM.AnOzFsqRNV/UXxBC3rt47wOfDRkajOM8li', NULL, '2025-07-16 04:31:51', '2025-07-16 04:31:51'),
(14, 'Regular User', 'user@gmail.com', NULL, '$2y$12$PavQL12L2KRFx7PSF6pTUunbn747F60ZhfrKk9CxrTI/WWT2s10ti', NULL, '2025-07-16 04:31:51', '2025-07-16 04:31:51'),
(16, 'Admin Name', 'admin1@gmail.com', NULL, '$2y$12$SInCw3HPRX/68QaKjeXwFeDUlWoKWHGosleG.Ou1MjIcvIWP2kVCK', NULL, '2025-07-16 05:25:55', '2025-07-16 05:33:17'),
(17, 'Jane Doe', 'janea@examp23l.com', NULL, '$2y$12$Y03m9yso.DFFfX0ZJfkjeOu.6se6Hg08S7.C/7XJg1NeRA/4UqbGm', NULL, '2025-07-16 05:27:39', '2025-07-16 05:27:39'),
(18, 'Organizer One', 'organizer1@example.com', NULL, '$2y$12$LoObc8VV6XJbRzATl8GuEeMlRWPNTVqLnIc1qs.XA8REgLuvwMtru', NULL, '2025-07-16 22:03:49', '2025-07-16 22:03:49'),
(19, 'Organizer Two', 'organizer2@example.com', NULL, '$2y$12$ckhADScbUiXTOaRT8tdMVuBL5dRk0umdeo8FalkF9N5bU1Z.Eou02', NULL, '2025-07-16 22:03:50', '2025-07-16 22:03:50'),
(20, 'Organizer Three', 'organizer3@example.com', NULL, '$2y$12$2OzIqzEhf2KU14O4/7Yme.wUqSTYsxOHX9a1TaxvimB3kJaxQfnB.', NULL, '2025-07-16 22:03:50', '2025-07-16 22:03:50'),
(21, 'organizer User1', 'organizer1@gmail.com', NULL, '$2y$12$r5nqI8E775aloklXRb0aouZ5AELBAt9YE2Jx6FhZfRpDn7BT6CqRK', NULL, '2025-07-16 22:13:42', '2025-07-16 22:13:42'),
(22, 'Organizer User2', 'organizer2@gmail.com', NULL, '$2y$12$k.CG1CUW1RAunX7ql699Y.xNEnr/0w7j7utR05IdFuuBCkTnnvoq2', NULL, '2025-07-16 22:13:42', '2025-07-16 22:13:42'),
(23, 'Regular User3', 'organizer3@gmail.com', NULL, '$2y$12$zuu/DD6/6pIQKEQYMwRS.uUnjqbnLZS1owD2AmcLdbDpVmVyIKw9a', NULL, '2025-07-16 22:13:42', '2025-07-16 22:13:42'),
(24, 'User One', 'user1@example.com', NULL, '$2y$12$1EdbiU5wuW4E4v7LfytmcuE4USEMNV40NS8PblfCBsWS1ZIvH.uS2', NULL, '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(25, 'User Two', 'user2@example.com', NULL, '$2y$12$09LT8pYj/oMzA2sBX8N3T.gAqwn7rQiWN/UnL9OOxffgAV0NSqfTK', NULL, '2025-07-16 23:00:53', '2025-07-16 23:00:53'),
(26, 'User Three', 'user3@example.com', NULL, '$2y$12$wAyRKJ.ckRODJZr8vFBcbeBM.bI3g1v11kw5VQ4NjtxmPKM3N3Ycu', NULL, '2025-07-16 23:00:54', '2025-07-16 23:00:54');

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_created_by_foreign` (`created_by`),
  ADD KEY `events_category_id_foreign` (`category_id`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

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
