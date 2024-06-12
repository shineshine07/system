-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2024 at 09:16 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `my_collection`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(30) NOT NULL,
  `course` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `level` varchar(150) NOT NULL,
  `total_amount` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `course`, `description`, `level`, `total_amount`, `date_created`) VALUES
(2, 'Grade 7', '', 'St. Thomas Aquinas', 12800, '2023-11-24 15:13:57'),
(3, 'Grade 8', '', 'St. John the Baptist de La Salle', 12800, '2023-11-24 15:21:30'),
(5, 'Grade 9', '', 'St. Francis de Sales', 12800, '2023-12-08 20:26:33'),
(9, '', '', '', 123, '2023-12-15 09:19:53'),
(10, '', '', '', 12, '2023-12-15 11:19:00'),
(11, '', '', '', 123, '2023-12-15 11:19:53'),
(12, '', '', '', 12, '2023-12-15 11:20:47'),
(13, '', '', '', 12, '2023-12-15 11:20:48'),
(14, '', '', '', 123, '2023-12-15 11:27:08'),
(15, '', '', '', 12, '2023-12-15 11:28:11'),
(16, '', '', '', 12, '2023-12-15 14:55:53'),
(17, '', '', '', 12, '2023-12-15 15:01:45'),
(18, '', '', '', 200, '2023-12-19 15:36:13'),
(19, '', '', '', 1, '2023-12-19 15:42:51'),
(20, '', '', '', 1, '2023-12-19 15:46:54'),
(21, '', '', '', 300, '2023-12-19 22:24:43');

-- --------------------------------------------------------

--
-- Table structure for table `disbursement`
--

CREATE TABLE `disbursement` (
  `id` int(11) NOT NULL,
  `payee` varchar(255) NOT NULL,
  `cv` int(10) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(255) NOT NULL,
  `amount_in_words` varchar(255) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `account_title` varchar(255) NOT NULL,
  `debit` decimal(10,2) DEFAULT NULL,
  `credit` decimal(10,2) DEFAULT NULL,
  `archived` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disbursement`
--

INSERT INTO `disbursement` (`id`, `payee`, `cv`, `date`, `type`, `amount_in_words`, `total`, `account_title`, `debit`, `credit`, `archived`) VALUES
(160, 'Memphis', 2, '2023-12-14 14:15:47', 'Light and Water', 'Two Thousand', '2000.00', 'Sample2', '2000.00', '2000.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(30) NOT NULL,
  `course_id` int(30) NOT NULL,
  `description` varchar(200) NOT NULL,
  `amount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fees`
--

INSERT INTO `fees` (`id`, `course_id`, `description`, `amount`) VALUES
(4, 2, 'Tuition Fee', 9000),
(5, 2, 'Registration Fee', 600),
(6, 2, 'Medical/Dental Fee', 200),
(8, 2, 'Insurance Fee (IHAMAP)', 55),
(9, 2, 'Death Aide', 55),
(10, 2, 'Library Fee', 400),
(11, 2, 'Test Paper Fee', 450),
(12, 2, 'Student/ School Publication', 150),
(13, 2, 'Athletic Fee', 300),
(14, 2, 'BACS dues', 185),
(15, 2, 'Book Rental', 605),
(16, 2, 'Laboratory Fee', 300),
(17, 2, 'Student ID', 150),
(18, 2, 'Student Passbook', 100),
(19, 2, 'Student Handbook', 50),
(102, 18, 'Dental Fee', 200),
(108, 0, 'Completers Fee', 2100),
(109, 0, 'Tuition Fee (Private)', 14000),
(110, 0, 'Tuition Fee (Public)', 17500),
(111, 0, 'Learning Maerials', 800),
(112, 0, 'Graduation Fee', 2950);

-- --------------------------------------------------------

--
-- Table structure for table `fee_details`
--

CREATE TABLE `fee_details` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `fee_id` int(255) NOT NULL,
  `fee_description` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fee_details`
--

INSERT INTO `fee_details` (`id`, `student_id`, `payment_id`, `fee_id`, `fee_description`, `amount`, `date`) VALUES
(92, 19, 194, 17, 'Student ID', '150.00', '2024-01-03 13:13:17'),
(93, 19, 194, 0, ' Student/ School Publication', '150.00', '2024-01-03 13:14:53'),
(94, 19, 194, 0, ' Tuition Fee (Private)', '14000.00', '2024-01-03 13:14:53'),
(95, 21, 195, 17, 'Student ID', '150.00', '2024-01-05 22:28:02'),
(96, 21, 195, 4, 'Tuition Fee', '9000.00', '2024-01-05 22:28:02'),
(97, 21, 195, 0, ' Athletic Fee', '300.00', '2024-01-05 22:29:52'),
(98, 21, 195, 0, ' BACS dues', '185.00', '2024-01-05 22:29:52'),
(99, 21, 195, 0, ' Book Rental', '605.00', '2024-01-05 22:29:52'),
(100, 21, 195, 0, ' Completers Fee', '2100.00', '2024-01-05 22:29:52'),
(101, 21, 195, 0, ' Death Aide', '55.00', '2024-01-05 22:29:52'),
(102, 21, 195, 0, ' Dental Fee', '200.00', '2024-01-05 22:29:52'),
(103, 21, 195, 0, ' Graduation Fee', '2950.00', '2024-01-05 22:29:52'),
(104, 21, 195, 0, ' Insurance Fee (IHAMAP)', '55.00', '2024-01-05 22:29:52'),
(105, 21, 195, 0, ' Laboratory Fee', '300.00', '2024-01-05 22:29:52'),
(106, 21, 195, 0, ' Learning Maerials', '800.00', '2024-01-05 22:29:52'),
(107, 21, 195, 0, ' Library Fee', '400.00', '2024-01-05 22:29:52'),
(108, 21, 195, 0, ' Medical/Dental Fee', '200.00', '2024-01-05 22:29:52'),
(109, 21, 195, 0, ' Registration Fee', '600.00', '2024-01-05 22:29:52'),
(110, 21, 195, 0, ' Student Handbook', '50.00', '2024-01-05 22:29:52'),
(111, 21, 195, 0, ' Student Passbook', '100.00', '2024-01-05 22:29:52'),
(112, 21, 195, 0, ' Student/ School Publication', '150.00', '2024-01-05 22:29:52'),
(113, 21, 195, 0, ' Test Paper Fee', '450.00', '2024-01-05 22:29:52'),
(114, 21, 195, 0, ' Tuition Fee (Private)', '14000.00', '2024-01-05 22:29:52'),
(115, 21, 195, 0, ' Tuition Fee (Public)', '17500.00', '2024-01-05 22:29:52'),
(116, 8, 196, 111, 'Learning Maerials', '800.00', '2024-02-26 15:46:18'),
(117, 8, 196, 0, ' Registration Fee', '600.00', '2024-02-26 15:46:31'),
(118, 8, 196, 0, ' Student ID', '150.00', '2024-02-26 15:46:31'),
(119, 8, 196, 11, 'Test Paper Fee', '450.00', '2024-02-26 15:54:34'),
(120, 8, 196, 0, ' Registration Fee', '600.00', '2024-02-26 15:54:56'),
(121, 8, 196, 0, ' Student ID', '150.00', '2024-02-26 15:54:56'),
(122, 8, 196, 0, ' Tuition Fee', '9000.00', '2024-02-26 15:54:56'),
(123, 8, 196, 0, ' Registration Fee', '600.00', '2024-02-26 15:55:56'),
(124, 8, 196, 0, ' Student ID', '150.00', '2024-02-26 15:55:56'),
(125, 8, 196, 0, ' Tuition Fee', '9000.00', '2024-02-26 15:55:56'),
(126, 8, 197, 11, 'Test Paper Fee', '450.00', '2024-02-26 16:01:09'),
(127, 7, 198, 8, 'Insurance Fee (IHAMAP)', '55.00', '2024-02-27 16:08:53'),
(128, 7, 198, 16, 'Laboratory Fee', '300.00', '2024-02-27 16:08:53'),
(129, 7, 198, 111, 'Learning Maerials', '800.00', '2024-02-27 16:08:53');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `paid_amount` float NOT NULL,
  `total_amount` float NOT NULL,
  `balance` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `archived` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `course_id`, `paid_amount`, `total_amount`, `balance`, `remarks`, `date_created`, `archived`) VALUES
(197, 8, 0, 450, 23450, 23000, '', '2024-02-26 16:01:09', 0),
(198, 7, 0, 0, 1155, 1155, '', '2024-02-27 16:08:53', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `fee_id` int(11) NOT NULL,
  `fee_description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`id`, `student_id`, `fee_id`, `fee_description`, `amount`, `date_created`) VALUES
(13, 19, 17, 'Student ID', '150.00', '2024-01-03 13:12:09'),
(14, 19, 12, 'Student/ School Publication', '150.00', '2024-01-03 13:12:09'),
(15, 19, 109, 'Tuition Fee (Private)', '14000.00', '2024-01-03 13:12:09'),
(16, 21, 13, 'Athletic Fee', '300.00', '2024-01-05 22:27:42'),
(17, 21, 14, 'BACS dues', '185.00', '2024-01-05 22:27:42'),
(18, 21, 15, 'Book Rental', '605.00', '2024-01-05 22:27:42'),
(19, 21, 108, 'Completers Fee', '2100.00', '2024-01-05 22:27:42'),
(20, 21, 9, 'Death Aide', '55.00', '2024-01-05 22:27:42'),
(21, 21, 102, 'Dental Fee', '200.00', '2024-01-05 22:27:42'),
(22, 21, 112, 'Graduation Fee', '2950.00', '2024-01-05 22:27:42'),
(23, 21, 8, 'Insurance Fee (IHAMAP)', '55.00', '2024-01-05 22:27:42'),
(24, 21, 16, 'Laboratory Fee', '300.00', '2024-01-05 22:27:42'),
(25, 21, 111, 'Learning Maerials', '800.00', '2024-01-05 22:27:42'),
(26, 21, 10, 'Library Fee', '400.00', '2024-01-05 22:27:42'),
(27, 21, 6, 'Medical/Dental Fee', '200.00', '2024-01-05 22:27:42'),
(28, 21, 5, 'Registration Fee', '600.00', '2024-01-05 22:27:42'),
(29, 21, 19, 'Student Handbook', '50.00', '2024-01-05 22:27:42'),
(30, 21, 17, 'Student ID', '150.00', '2024-01-05 22:27:42'),
(31, 21, 18, 'Student Passbook', '100.00', '2024-01-05 22:27:42'),
(32, 21, 12, 'Student/ School Publication', '150.00', '2024-01-05 22:27:42'),
(33, 21, 11, 'Test Paper Fee', '450.00', '2024-01-05 22:27:42'),
(34, 21, 4, 'Tuition Fee', '9000.00', '2024-01-05 22:27:42'),
(35, 21, 109, 'Tuition Fee (Private)', '14000.00', '2024-01-05 22:27:42'),
(36, 21, 110, 'Tuition Fee (Public)', '17500.00', '2024-01-05 22:27:42'),
(37, 8, 111, 'Learning Maerials', '800.00', '2024-02-26 15:46:13'),
(38, 8, 5, 'Registration Fee', '600.00', '2024-02-26 15:46:13'),
(39, 8, 17, 'Student ID', '150.00', '2024-02-26 15:46:13'),
(40, 8, 11, 'Test Paper Fee', '450.00', '2024-02-26 15:54:25'),
(41, 8, 4, 'Tuition Fee', '9000.00', '2024-02-26 15:54:25'),
(42, 8, 11, 'Test Paper Fee', '450.00', '2024-02-26 16:01:02'),
(43, 8, 4, 'Tuition Fee', '9000.00', '2024-02-26 16:01:02'),
(44, 8, 109, 'Tuition Fee (Private)', '14000.00', '2024-02-26 16:01:02'),
(45, 7, 8, 'Insurance Fee (IHAMAP)', '55.00', '2024-02-27 16:08:45'),
(46, 7, 16, 'Laboratory Fee', '300.00', '2024-02-27 16:08:45'),
(47, 7, 111, 'Learning Maerials', '800.00', '2024-02-27 16:08:45');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(30) NOT NULL,
  `id_no` int(11) NOT NULL,
  `name` text NOT NULL,
  `course` varchar(10) NOT NULL,
  `level` varchar(20) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `id_no`, `name`, `course`, `level`, `contact`, `address`, `email`, `date_created`) VALUES
(6, 123, 'Sunshine', 'Grade 7', 'St. Thomas Aquinas', '09366806338', 'Tagbilaran City, Bohol', 'montes.sunshine05@gmail.com', '2023-11-24 17:29:47'),
(7, 1234, 'Glenn', 'Grade 8', 'St. John the Baptist', '09101244612', 'Tubigon, Bohol', 'glenn@gmail.com', '2023-11-24 17:30:38'),
(8, 12345, 'Memphis', 'Grade 9', 'St. Francis de Sales', '09468078043', 'Alicia, Bohol', 'memphis@gmail.com', '2023-11-24 20:45:54'),
(18, 2468, 'Angela', 'Grade 9', 'St. Francis de Sales', 'dd@gmail.com', '99999999', 'Alicia, Bohol', '2024-01-03 11:59:06'),
(19, 6969, 'Benjamin Omamalin', 'Grade 9', 'St. Francis de Sales', '12334', 'Balilihan', 'benj@gmail.com', '2024-01-03 13:05:57'),
(20, 12345, 'Monry', 'Grade 8', 'St. John the Baptist', 'mm@gmail.com', '949592592', 'Tagbilaran, Bohol', '2024-01-03 13:08:43');

-- --------------------------------------------------------

--
-- Table structure for table `student_ef_list`
--

CREATE TABLE `student_ef_list` (
  `id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `ef_no` varchar(200) NOT NULL,
  `course_id` int(30) NOT NULL,
  `total_fee` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_ef_list`
--

INSERT INTO `student_ef_list` (`id`, `student_id`, `ef_no`, `course_id`, `total_fee`, `date_created`) VALUES
(1, 2, '2020-654278', 1, 4500, '2020-10-31 12:04:18'),
(2, 1, '2020-65427823', 1, 4500, '2020-10-31 13:12:13'),
(3, 6, '', 2, 12800, '2023-11-24 17:49:39');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'School Fees Payment System', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `establishment_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2=Staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `establishment_id`, `name`, `username`, `password`, `type`) VALUES
(1, 0, 'cashier', 'cashier', 'dbb8c54ee649f8af049357a5f99cede6', 1),
(14, 0, 'School Director', 'school_director', 'eaae01eb4bcad3ededea38e325df5901', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disbursement`
--
ALTER TABLE `disbursement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cv` (`cv`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_details`
--
ALTER TABLE `fee_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_ef_list`
--
ALTER TABLE `student_ef_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `disbursement`
--
ALTER TABLE `disbursement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `fee_details`
--
ALTER TABLE `fee_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `student_ef_list`
--
ALTER TABLE `student_ef_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
