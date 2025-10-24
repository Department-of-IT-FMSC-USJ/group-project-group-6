CREATE TABLE `pickup_requests` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `company_id` bigint(20) NOT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `estimated_weight` decimal(10,2) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `status` enum('pending','accepted','assigned','in_progress','completed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `pickup_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pickup_requests_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pickup_requests_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `pickup_requests` VALUES
(3, 5, 3, 1, '2025-10-20', '00:00:00', 5.00, '', 'pending', '2025-10-18 16:27:48', '2025-10-18 16:27:48'),
(4, 5, 3, 4, '2025-10-19', '00:00:00', 5.00, 'good stuff', 'pending', '2025-10-18 16:47:02', '2025-10-18 16:47:02'),
(5, 5, 3, 4, '2025-10-19', '00:00:00', 5.00, 'good one', 'pending', '2025-10-18 16:50:25', '2025-10-18 16:50:25'),
(6, 5, 3, 4, '2025-10-21', '00:00:00', 5.00, 'rr', 'pending', '2025-10-18 16:53:27', '2025-10-18 16:53:27'),
(7, 5, 3, 4, '2025-10-29', '00:00:00', 5.00, 'gg', 'pending', '2025-10-18 16:57:54', '2025-10-18 16:57:54'),
(8, 5, 7, 4, '2025-10-20', '00:00:00', 5.00, 'gg', 'completed', '2025-10-18 17:00:43', '2025-10-18 17:05:06'),
(9, 9, 8, 5, '2025-10-21', '00:00:00', 10.00, '', 'completed', '2025-10-20 07:03:05', '2025-10-20 07:03:31');
