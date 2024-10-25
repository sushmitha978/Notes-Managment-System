-- Adminer 4.8.1 MySQL 10.11.8-MariaDB-0ubuntu0.24.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `notes`;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `note_title` varchar(100) DEFAULT NULL,
  `note_content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `notes` (`id`, `user_id`, `note_title`, `note_content`, `created_at`, `updated_at`) VALUES
(16,	2,	'Rvrss',	'{\"ops\":[{\"attributes\":{\"bold\":true},\"insert\":\"beautiful\"},{\"insert\":\"\\n\"}]}',	'2024-10-24 09:08:14',	'2024-10-24 09:08:14'),
(17,	2,	'RVRSS',	'{\"ops\":[{\"insert\":\"RVR Security Solutions (RVRSS) is an Indian cybersecurity firm founded in 2018 by Raj Vardhan Rahul. The company specializes in providing IT and cybersecurity services, including secure website development, security testing, and consultation. RVRSS also offers cybersecurity career training programs with internships to help individuals gain practical experience in the field. Its primary focus is on helping organizations secure their digital assets and improve their cybersecurity measures across multiple sectors​\\n\\n.The company operates in various countries and works with clients globally, offering services like daily security scans, developing security policies, and implementing cybersecurity solutions​\\n\"}]}',	'2024-10-24 09:11:12',	'2024-10-24 09:11:12'),
(18,	1,	'Test',	'<p>Testing</p>',	'2024-10-24 12:12:16',	'2024-10-24 14:19:08'),
(19,	1,	'aa',	'<p>aa</p>',	'2024-10-24 14:33:17',	'2024-10-24 14:33:17'),
(20,	1,	'aaaaaaaaa',	'<p><strong><em><u>aaa</u></em></strong></p>',	'2024-10-24 14:33:24',	'2024-10-24 14:33:24');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1,	'sushmitha',	'rahul@rvrss.in',	'$2y$10$KX0FFGpq3PlY7Gql4TbF6OvQwZ78DzIIVP8nUnrrMLa4MUMBV.c8K',	'2024-10-24 06:46:40'),
(2,	'Bandaru sushmitha',	'sushmithachowdary978@gmail.com',	'$2y$10$CmnkKdU1YAa7DvdioIrShuC0zchb0wbjq3UIbpZfp.ETdqiA9.Awi',	'2024-10-24 09:06:04');

-- 2024-10-25 09:18:58
