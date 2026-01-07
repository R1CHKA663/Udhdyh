-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 07 2022 г., 22:34
-- Версия сервера: 8.0.29
-- Версия PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `kaifuli`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `raceback_procent` double(15,2) NOT NULL DEFAULT '1.00',
  `raceback_game` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `group_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `group_id` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `raceback_procent`, `raceback_game`, `group_token`, `group_id`, `created_at`, `updated_at`) VALUES
(1, 1.15, 'bubbles', 'ed8401c4e93ce2d93b3a80c86fd1c3748f4bc96eb7bdd6bf688cfe8756dbb36cd0976dc94aab89df01140', 216219572, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `banks`
--

CREATE TABLE `banks` (
  `id` bigint UNSIGNED NOT NULL,
  `dice` double NOT NULL DEFAULT '0',
  `bubbles` double NOT NULL DEFAULT '0',
  `mines` double NOT NULL DEFAULT '0',
  `normal_dice` double NOT NULL DEFAULT '0',
  `normal_bubbles` double NOT NULL DEFAULT '0',
  `normal_mines` double NOT NULL DEFAULT '0',
  `income_dice` double NOT NULL DEFAULT '0',
  `income_bubbles` double NOT NULL DEFAULT '0',
  `income_mines` double NOT NULL DEFAULT '0',
  `fee_dice` double NOT NULL DEFAULT '0',
  `fee_bubbles` double NOT NULL DEFAULT '0',
  `fee_mines` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `banks`
--

INSERT INTO `banks` (`id`, `dice`, `bubbles`, `mines`, `normal_dice`, `normal_bubbles`, `normal_mines`, `income_dice`, `income_bubbles`, `income_mines`, `fee_dice`, `fee_bubbles`, `fee_mines`, `created_at`, `updated_at`) VALUES
(1, 933.80960836244, 553.1865, 1202.7464979848, 1200, 1200, 1200, 0, 0, 0, 25, 25, 25, NULL, '2022-10-13 18:59:05');

-- --------------------------------------------------------

--
-- Структура таблицы `bonuses`
--

CREATE TABLE `bonuses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `bubbles`
--

CREATE TABLE `bubbles` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `bet` double NOT NULL,
  `purple` double NOT NULL,
  `win` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `dice`
--

CREATE TABLE `dice` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `bet` double NOT NULL,
  `chance` double NOT NULL,
  `win` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `gamejackpotes`
--

CREATE TABLE `gamejackpotes` (
  `id` int NOT NULL,
  `sum` double DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `gamejackpotes`
--

INSERT INTO `gamejackpotes` (`id`, `sum`) VALUES
(1, 53.086978471401636);

-- --------------------------------------------------------

--
-- Структура таблицы `gamejackpotwin`
--

CREATE TABLE `gamejackpotwin` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `winSum` double NOT NULL DEFAULT '0',
  `game` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `group_post`
--

CREATE TABLE `group_post` (
  `id` int NOT NULL,
  `post_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `group_post`
--

INSERT INTO `group_post` (`id`, `post_id`) VALUES
(1, 9),
(2, 10),
(3, 11),
(4, 12),
(5, 13),
(6, 14),
(7, 15),
(8, 16),
(9, 17),
(10, 18),
(11, 19),
(12, 21),
(13, 26),
(14, 27),
(15, 28),
(16, 29),
(17, 30),
(18, 32),
(19, 33),
(20, 34),
(21, 35),
(22, 36),
(23, 37),
(24, 38),
(25, 39),
(26, 40),
(27, 41),
(28, 42),
(29, 43),
(30, 44),
(31, 45),
(32, 46),
(33, 47),
(34, 48),
(35, 49),
(36, 50),
(37, 51),
(38, 52),
(39, 53),
(40, 54),
(41, 55),
(42, 56),
(43, 57),
(44, 58),
(45, 59),
(46, 60),
(47, 61),
(48, 62),
(49, 65),
(50, 66),
(51, 68),
(52, 69),
(53, 70),
(54, 71),
(55, 73),
(56, 74),
(57, 75),
(58, 76),
(59, 77),
(60, 78),
(61, 83),
(62, 84),
(63, 85),
(64, 86),
(65, 87),
(66, 88),
(67, 89),
(68, 90),
(69, 91),
(70, 92),
(71, 93),
(72, 94),
(73, 95),
(74, 96),
(75, 97),
(76, 98),
(77, 99),
(78, 100),
(79, 101),
(80, 102),
(81, 103),
(82, 104),
(83, 105),
(84, 106),
(85, 107),
(86, 108),
(87, 109),
(88, 110),
(89, 111),
(90, 112),
(91, 113),
(92, 114),
(93, 115),
(94, 116),
(95, 117),
(96, 118),
(97, 119);

-- --------------------------------------------------------

--
-- Структура таблицы `jackpot_bet`
--

CREATE TABLE `jackpot_bet` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `bet` double NOT NULL DEFAULT '0',
  `fromTicket` int NOT NULL DEFAULT '0',
  `toTicket` int NOT NULL DEFAULT '0',
  `chance` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `jackpot_status`
--

CREATE TABLE `jackpot_status` (
  `id` int NOT NULL,
  `status` int NOT NULL,
  `comissia` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Дамп данных таблицы `jackpot_status`
--

INSERT INTO `jackpot_status` (`id`, `status`, `comissia`) VALUES
(1, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `jackpot_win`
--

CREATE TABLE `jackpot_win` (
  `id` int NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `chance` double DEFAULT '0',
  `win` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `logs`
--

CREATE TABLE `logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL DEFAULT '0',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'type',
  `info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Описание',
  `oldBalance` double(15,2) NOT NULL DEFAULT '0.00',
  `newBalance` double(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(13, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(14, '2022_08_24_212601_create_users_table', 1),
(15, '2022_08_30_210031_create_mines_table', 1),
(16, '2022_09_04_222304_create_withdraws_table', 1),
(17, '2022_09_05_215134_create_promos_table', 1),
(18, '2022_09_05_215301_create_payments_table', 1),
(19, '2022_09_05_215344_create_bonuses_table', 1),
(20, '2022_09_07_224156_create_banks_table', 1),
(21, '2022_09_09_203259_create_promo_logs_table', 1),
(22, '2022_09_16_190016_create_admins_table', 1),
(23, '2022_09_17_181912_create_users_infos_table', 1),
(24, '2022_09_26_180926_create_logs_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `mines`
--

CREATE TABLE `mines` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `bet` double NOT NULL DEFAULT '1',
  `num_bomb` int NOT NULL DEFAULT '1',
  `clicked` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mines` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `win` double NOT NULL DEFAULT '0',
  `promo_type` int NOT NULL DEFAULT '0',
  `promo_step` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL DEFAULT '1',
  `system` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promo_id` bigint DEFAULT NULL,
  `sum` double(15,2) NOT NULL DEFAULT '1.00',
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `promos`
--

CREATE TABLE `promos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `vk_id` bigint NOT NULL DEFAULT '1',
  `reward` double(15,2) DEFAULT NULL,
  `limit` int NOT NULL DEFAULT '0',
  `limited` int NOT NULL DEFAULT '0',
  `type` int NOT NULL DEFAULT '0',
  `deposit` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '0',
  `mines` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `promo_logs`
--

CREATE TABLE `promo_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT '0',
  `promo_id` int NOT NULL DEFAULT '0',
  `type` int NOT NULL DEFAULT '0',
  `reward` double(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `vk_id` bigint NOT NULL DEFAULT '1',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Чувак',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` double(15,2) NOT NULL DEFAULT '0.00',
  `raceback` double(15,2) NOT NULL DEFAULT '0.00',
  `deposit` double(15,2) NOT NULL DEFAULT '0.00',
  `income_repost` double(15,2) NOT NULL DEFAULT '0.00',
  `repost` bigint NOT NULL DEFAULT '0',
  `videocard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus_vk` int NOT NULL DEFAULT '0',
  `bonus_tg` int NOT NULL DEFAULT '0',
  `day_bonus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hourly_bonus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tg_id` bigint DEFAULT NULL,
  `clicked` int NOT NULL DEFAULT '0',
  `income_all` double NOT NULL DEFAULT '0',
  `referalov` int NOT NULL DEFAULT '0',
  `income` double NOT NULL DEFAULT '0',
  `invited` int DEFAULT NULL,
  `ref_link` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_ban` int NOT NULL DEFAULT '0',
  `is_ban_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_admin` int NOT NULL DEFAULT '0',
  `is_moder` int NOT NULL DEFAULT '0',
  `is_youtuber` int NOT NULL DEFAULT '0',
  `is_promocoder` int NOT NULL DEFAULT '0',
  `verified` int NOT NULL DEFAULT '0',
  `wager` float NOT NULL DEFAULT '0',
  `contest_ref` int NOT NULL DEFAULT '0',
  `comment_admin` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_drain` int NOT NULL DEFAULT '0',
  `is_drain_chance` double NOT NULL DEFAULT '20',
  `promo_limit` int NOT NULL DEFAULT '0',
  `promo_reward` double NOT NULL DEFAULT '0',
  `promo_time` bigint DEFAULT NULL,
  `promo_hours` bigint NOT NULL DEFAULT '24',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users_infos`
--

CREATE TABLE `users_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL DEFAULT '1',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `videocard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_repost`
--

CREATE TABLE `user_repost` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `wheel_bet`
--

CREATE TABLE `wheel_bet` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `bet` double NOT NULL,
  `color` int NOT NULL,
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Структура таблицы `withdraws`
--

CREATE TABLE `withdraws` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `system` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sum` double NOT NULL DEFAULT '0',
  `system_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee_sum` double NOT NULL DEFAULT '0',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `videocard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `status` int NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bonuses`
--
ALTER TABLE `bonuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `bubbles`
--
ALTER TABLE `bubbles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dice`
--
ALTER TABLE `dice`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gamejackpotes`
--
ALTER TABLE `gamejackpotes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `gamejackpotwin`
--
ALTER TABLE `gamejackpotwin`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `group_post`
--
ALTER TABLE `group_post`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `jackpot_bet`
--
ALTER TABLE `jackpot_bet`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `jackpot_status`
--
ALTER TABLE `jackpot_status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `jackpot_win`
--
ALTER TABLE `jackpot_win`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mines`
--
ALTER TABLE `mines`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `promos`
--
ALTER TABLE `promos`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `promo_logs`
--
ALTER TABLE `promo_logs`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_infos`
--
ALTER TABLE `users_infos`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_repost`
--
ALTER TABLE `user_repost`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `wheel_bet`
--
ALTER TABLE `wheel_bet`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `bonuses`
--
ALTER TABLE `bonuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `bubbles`
--
ALTER TABLE `bubbles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `dice`
--
ALTER TABLE `dice`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `gamejackpotes`
--
ALTER TABLE `gamejackpotes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `gamejackpotwin`
--
ALTER TABLE `gamejackpotwin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `group_post`
--
ALTER TABLE `group_post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT для таблицы `jackpot_bet`
--
ALTER TABLE `jackpot_bet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `jackpot_status`
--
ALTER TABLE `jackpot_status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `jackpot_win`
--
ALTER TABLE `jackpot_win`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `mines`
--
ALTER TABLE `mines`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `promos`
--
ALTER TABLE `promos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `promo_logs`
--
ALTER TABLE `promo_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users_infos`
--
ALTER TABLE `users_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `user_repost`
--
ALTER TABLE `user_repost`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `wheel_bet`
--
ALTER TABLE `wheel_bet`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
