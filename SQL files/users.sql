CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `user_type` enum('household','company') NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES
(1, 'john_doe', 'john@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'household', '0771234567', '123 Galle Road, Colombo', 'Colombo', '2025-10-17 18:14:27', '2025-10-17 18:14:27', 1),
(2, 'jane_smith', 'jane@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'household', '0779876543', '456 Kandy Road, Kandy', 'Kandy', '2025-10-17 18:14:27', '2025-10-17 18:14:27', 1),
(3, 'green_center', 'green@center.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Green Recycling Center', 'company', '0112345678', '789 Eco Street, Colombo 07', 'Colombo', '2025-10-17 18:14:27', '2025-10-17 18:14:27', 1),
(4, 'eco_kandy', 'eco@kandy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Eco Kandy Collection', 'company', '0812345678', '321 Hill Road, Kandy', 'Kandy', '2025-10-17 18:14:27', '2025-10-17 18:14:27', 1),
(5, 'Lakindu', 'induwaralakindu09@gmail.com', '$2y$10$z9HkDhpLg4E66exvIUOYiuZV5cMFkS1Ftylk4zZYMwAoaIxf.qw.a', 'Induwara Lakindu', 'household', '0724943352', '190 Dr. Britto babapulle place grandpass colombo 14', 'Colombo', '2025-10-18 16:26:51', '2025-10-18 16:26:51', 1),
(6, 'ravindu', 'ravindu@gmail.com', '$2y$10$7Y.eJAoeN0xqLs5SE4h.I./EzzHJ0NRyklfPyQT0tGp9U3eYR5AgW', 'ravindu bandara', 'household', '0724943353', 'something', 'Colombo', '2025-10-18 16:30:58', '2025-10-18 16:30:58', 1),
(7, 'ravi', 'ravi@gmail.com', '$2y$10$I63R97XQERBGX45cUu24.OtOpQy.f98ndnffDTd3iNt7DmLxx.j7i', 'EcoHUB', 'company', '07234432235', 'somethwhere', 'Colombo', '2025-10-18 16:40:36', '2025-10-18 16:40:36', 1),
(8, 'Eco Spindles Collection Center', 'info@ecospindles.com', '$2y$10$cS7filC0fzw7m/NWt9x8WuBCDnmRydAgPDMJja0qB7OuGo0zpPn2O', 'ecospindles', 'company', '+94 11 2307168', 'No. 278/4, Level 17, Access Towers', 'Colombo', '2025-10-20 07:00:19', '2025-10-20 07:00:19', 1),
(9, 'Nimal', 'nimal@gmail.com', '$2y$10$vPMcAL.fAUznfWvOoMLSp.pu8Tmznvu6/7oqpA0NjFi49og.shIEK', 'Nimal Perera', 'household', '0725223146', '433/8 kapuwaththa\r\nJaEla', 'Gampaha', '2025-10-20 07:02:32', '2025-10-20 07:02:32', 1);