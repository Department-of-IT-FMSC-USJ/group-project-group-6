CREATE TABLE `locations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('drop-off','pickup','recycling','community') NOT NULL,
  `address` text NOT NULL,
  `district` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `hours` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `locations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `locations` VALUES
(1, 3, 'Green Recycling Center - Main', 'drop-off', '789 Eco Street, Colombo 07', 'Colombo', '0112345678', 'Mon-Sat: 8AM-6PM', 'Main collection center in Colombo', NULL, NULL, 1, '2025-10-17 18:14:27', '2025-10-18 16:56:45'),
(2, 4, 'Eco Kandy Collection Point', 'pickup', '321 Hill Road, Kandy', 'Kandy', '0812345678', 'Mon-Fri: 9AM-5PM', 'Pickup service available in Kandy area', NULL, NULL, 1, '2025-10-17 18:14:27', '2025-10-18 16:56:45'),
(4, 7, 'EcoHUB', 'pickup', '123 flower road', 'Colombo', '0724943352', '8 to 10', 'none', 0.000000, 0.000000, 0, '2025-10-18 16:44:35', '2025-10-18 17:00:06'),
(5, 8, 'Eco Spindles Collection Center', 'drop-off', 'No. 278/4, Level 17, Access Towers, Union Place, Colombo 2&#039;', 'Colombo', '+94 11 2307168', 'Mon-Fri 9:00-17:00', 'Accepts PET, HDPE, and PP plastics', 0.000000, 0.000000, 0, '2025-10-20 07:01:34', '2025-10-20 07:01:34');