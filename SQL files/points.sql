CREATE TABLE `points` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `points_used` int(11) DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `points_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `points` VALUES
(1, 1, 50, 0, 'Initial collection - 10kg PET bottles', '2025-10-17 18:14:27'),
(2, 1, 25, 0, 'Drop-off collection - 5kg HDPE', '2025-10-17 18:14:27'),
(3, 2, 75, 0, 'Pickup collection - 15kg Mixed plastics', '2025-10-17 18:14:27'),
(4, 5, 25, 0, 'good', '2025-10-18 17:17:36'),
(5, 9, 50, 0, '', '2025-10-20 07:03:45');