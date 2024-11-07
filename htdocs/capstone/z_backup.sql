-- Adminer 4.8.1 MySQL 10.4.34-MariaDB-1:10.4.34+maria~ubu2004 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `analytics`;
CREATE TABLE `analytics` (
  `analytics_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `total_quantity_sold` int(11) DEFAULT NULL,
  `total_earnings` decimal(10,2) DEFAULT NULL,
  `last_calculated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`analytics_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `analytics_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `analytics` (`analytics_id`, `item_id`, `total_quantity_sold`, `total_earnings`, `last_calculated`) VALUES
(1,	1,	100,	1000.00,	'2024-11-05 12:01:11'),
(2,	2,	50,	500.00,	'2024-11-05 12:01:11');

DROP TABLE IF EXISTS `employee_sales`;
CREATE TABLE `employee_sales` (
  `emp_sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`emp_sale_id`),
  KEY `user_id` (`user_id`),
  KEY `sale_id` (`sale_id`),
  CONSTRAINT `employee_sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  CONSTRAINT `employee_sales_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `inventory`;
CREATE TABLE `inventory` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `inventory` (`item_id`, `item_name`, `quantity`, `price`, `last_updated`) VALUES
(1,	'Item A',	50,	10.00,	'2024-11-05 12:01:11'),
(2,	'Item B',	30,	5.00,	'2024-11-05 12:01:11'),
(3,	'Item C',	20,	20.00,	'2024-11-05 12:01:11');

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sale_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `sales` (`sale_id`, `item_id`, `quantity`, `total_price`, `sale_date`) VALUES
(1,	1,	5,	50.00,	'2024-11-05 12:01:11'),
(2,	2,	3,	15.00,	'2024-11-05 12:01:11');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Employee') NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `users` (`user_id`, `username`, `password`, `role`) VALUES
(1,	'admin_user',	'$2y$10$.JLCdWgZbaIWYZTcE/oZnev.gRqygQ5Ed43NexGSvAqJ/TEQy1Y8a',	'Admin'),
(2,	'employee_user',	'$2y$10$O08pqMU1sUvrdrh35.sMVeFfHZEegcEciNsO/kyrMgS0W/KbCLIxW',	'Employee');

DROP TABLE IF EXISTS `user_sessions`;
CREATE TABLE `user_sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `role` enum('Admin','Employee') NOT NULL,
  `session_start` timestamp NOT NULL DEFAULT current_timestamp(),
  `session_end` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


-- 2024-11-05 23:36:53