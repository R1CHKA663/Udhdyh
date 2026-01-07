-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 20, 2023 at 12:05 PM
-- Server version: 5.7.41-0ubuntu0.18.04.1
-- PHP Version: 7.2.34-38+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stimule`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `action` varchar(255) NOT NULL,
  `balanceBefore` double(16,2) NOT NULL,
  `balanceAfter` double(16,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bonus_levels`
--

CREATE TABLE `bonus_levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `goal` int(11) DEFAULT NULL,
  `reward` double(16,2) NOT NULL DEFAULT '0.00',
  `background` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `game` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bet` double(255,2) NOT NULL,
  `chance` double(255,2) NOT NULL,
  `win` double(255,2) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fake` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mines`
--

CREATE TABLE `mines` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` float NOT NULL,
  `bombs` int(11) NOT NULL,
  `step` int(11) NOT NULL DEFAULT '0',
  `grid` json DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `fake` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sum` double(255,2) NOT NULL,
  `bonus` double(16,2) NOT NULL DEFAULT '0.00',
  `wager` double(16,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profit`
--

CREATE TABLE `profit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_dice` double(16,2) NOT NULL DEFAULT '0.00',
  `bank_mines` double(16,2) NOT NULL DEFAULT '0.00',
  `bank_bubbles` double(16,2) NOT NULL DEFAULT '0.00',
  `bank_wheel` double(16,2) NOT NULL DEFAULT '0.00',
  `earn_bubbles` double(16,2) NOT NULL DEFAULT '0.00',
  `comission` int(11) NOT NULL DEFAULT '0',
  `earn_dice` double(16,2) NOT NULL DEFAULT '0.00',
  `earn_mines` double(16,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promocodes`
--

CREATE TABLE `promocodes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sum` double(255,2) NOT NULL,
  `activation` int(11) NOT NULL,
  `wager` double(16,2) NOT NULL DEFAULT '0.00',
  `type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promocode_activations`
--

CREATE TABLE `promocode_activations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `promo_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_profits`
--

CREATE TABLE `referral_profits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_id` int(11) NOT NULL,
  `ref_id` int(11) NOT NULL,
  `amount` double(16,2) NOT NULL DEFAULT '0.00',
  `level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kassa_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kassa_secret1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kassa_secret2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kassa_key` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_id` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_secret` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wallet_desc` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vlito_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vlito_secret` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_payment_sum` double(8,2) DEFAULT NULL,
  `min_bonus_sum` double(8,2) DEFAULT NULL,
  `min_withdraw_sum` double(8,2) DEFAULT NULL,
  `min_dep_withdraw` int(11) DEFAULT NULL,
  `withdraw_request_limit` int(11) DEFAULT NULL,
  `vk_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tg_channel` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tg_bot` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vk_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vk_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vk_service_token` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bot_timer` double(8,2) DEFAULT NULL,
  `file_version` int(11) NOT NULL DEFAULT '1',
  `antiminus` int(11) NOT NULL DEFAULT '0',
  `daily_bonus_min` double(16,2) NOT NULL DEFAULT '0.00',
  `daily_bonus_max` double(16,2) NOT NULL DEFAULT '0.00',
  `hourly_bonus_min` double(16,2) NOT NULL DEFAULT '0.00',
  `hourly_bonus_max` double(16,2) NOT NULL DEFAULT '0.00',
  `onetime_bonus` float NOT NULL DEFAULT '0',
  `telegram_chat_id` bigint(30) DEFAULT NULL,
  `telegram_token` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_domain` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_reward` double(16,2) NOT NULL DEFAULT '0.00',
  `deposit_per_n` int(11) NOT NULL DEFAULT '0',
  `deposit_sum_n` double(16,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slots`
--

CREATE TABLE `slots` (
  `id` int(11) NOT NULL,
  `game_id` varchar(150) NOT NULL,
  `title` varchar(150) CHARACTER SET utf8 NOT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `provider` varchar(150) NOT NULL,
  `icon` varchar(150) NOT NULL,
  `show` int(11) NOT NULL DEFAULT '1',
  `is_live` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `slots_data`
--

CREATE TABLE `slots_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unique_id` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `balance` double(255,2) NOT NULL DEFAULT '0.00',
  `repost` int(11) NOT NULL DEFAULT '0',
  `bonus_balance` float(16,2) NOT NULL DEFAULT '0.00',
  `wager` double(16,2) NOT NULL DEFAULT '0.00',
  `wager_status` int(11) NOT NULL DEFAULT '1',
  `avatar` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vk_id` bigint(20) DEFAULT NULL,
  `tg_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `vk_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dice` double(16,2) NOT NULL DEFAULT '0.00',
  `mines` double(16,2) NOT NULL DEFAULT '0.00',
  `bubbles` double(16,2) NOT NULL DEFAULT '0.00',
  `wheel` double(16,2) NOT NULL DEFAULT '0.00',
  `slots` double(16,2) DEFAULT '0.00',
  `total_reposts` int(11) NOT NULL DEFAULT '0',
  `is_bot` tinyint(1) NOT NULL DEFAULT '0',
  `is_admin` int(11) NOT NULL DEFAULT '0',
  `is_youtuber` int(11) NOT NULL DEFAULT '0',
  `is_worker` int(11) NOT NULL DEFAULT '0',
  `referral_use` int(11) DEFAULT '0',
  `referral_send` int(11) NOT NULL DEFAULT '0',
  `referral_balance` double(16,2) NOT NULL DEFAULT '0.00',
  `ref_1_lvl` double(16,2) NOT NULL DEFAULT '0.00',
  `ref_2_lvl` double(16,2) NOT NULL DEFAULT '0.00',
  `ref_3_lvl` double(16,2) NOT NULL DEFAULT '0.00',
  `ban` int(11) NOT NULL DEFAULT '0',
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus_use` int(11) NOT NULL DEFAULT '0',
  `bonus_daily` bigint(20) NOT NULL DEFAULT '0',
  `bonus_hourly` bigint(20) NOT NULL DEFAULT '0',
  `vk_bonus_use` int(11) NOT NULL DEFAULT '0',
  `tg_bonus_use` int(11) NOT NULL DEFAULT '0',
  `created_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `used_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `videocard` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fingerprint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logs_length` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_id` int(11) DEFAULT NULL,
  `current_bet` double(16,2) DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sum` double(255,2) NOT NULL,
  `sumWithCom` double(16,2) NOT NULL DEFAULT '0.00',
  `wallet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `system` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `fake` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_youtuber` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bonus_levels`
--
ALTER TABLE `bonus_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mines`
--
ALTER TABLE `mines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profit`
--
ALTER TABLE `profit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promocodes`
--
ALTER TABLE `promocodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promocode_activations`
--
ALTER TABLE `promocode_activations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_profits`
--
ALTER TABLE `referral_profits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slots`
--
ALTER TABLE `slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slots_data`
--
ALTER TABLE `slots_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bonus_levels`
--
ALTER TABLE `bonus_levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mines`
--
ALTER TABLE `mines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profit`
--
ALTER TABLE `profit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promocodes`
--
ALTER TABLE `promocodes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promocode_activations`
--
ALTER TABLE `promocode_activations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_profits`
--
ALTER TABLE `referral_profits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slots`
--
ALTER TABLE `slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slots_data`
--
ALTER TABLE `slots_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
