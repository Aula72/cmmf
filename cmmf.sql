-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 01, 2022 at 05:55 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmmf`
--

-- --------------------------------------------------------

--
-- Table structure for table `grouping`
--

CREATE TABLE `grouping` (
  `g_id` int(11) NOT NULL,
  `g_code` varchar(5) NOT NULL,
  `g_name` varchar(150) NOT NULL,
  `g_location` varchar(150) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grouping`
--

INSERT INTO `grouping` (`g_id`, `g_code`, `g_name`, `g_location`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'G2', 'jaggwe boyz', 'zzana', 1, '2022-10-20 16:13:13', '2022-10-20 16:13:13'),
(3, 'G3', 'simon', 'Location', 1, '2022-10-20 17:01:26', '2022-10-20 17:01:26'),
(4, 'G4', '', '', 1, '2022-10-21 13:10:08', '2022-10-21 13:10:08'),
(5, 'G5', '', '', 1, '2022-10-24 11:26:12', '2022-10-24 11:26:12'),
(6, 'G6', 'simon', 'rere', 1, '2022-10-24 11:33:02', '2022-10-24 11:33:02'),
(7, 'G7', '948594', 'kjkjk', 1, '2022-10-24 11:38:29', '2022-10-24 11:38:29'),
(8, 'G8', 'rrurur', 'rututu', 1, '2022-10-24 11:40:24', '2022-10-24 11:40:24'),
(9, 'G9', 'rutut', 'titit', 1, '2022-10-24 11:49:47', '2022-10-24 11:49:47'),
(10, 'G10', 'rur', 'jfjg', 1, '2022-10-24 11:55:23', '2022-10-24 11:55:23'),
(11, 'G11', 'name', 'group', 1, '2022-10-24 11:58:22', '2022-10-24 11:58:22'),
(12, 'G12', 'name ksjff', 'kalerwe', 1, '2022-10-24 11:58:50', '2022-10-24 11:58:50'),
(13, 'G13', 'Kampala dfifk kf', 'Simon dkfkf', 1, '2022-10-24 11:59:20', '2022-10-24 11:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `group_member`
--

CREATE TABLE `group_member` (
  `m_id` int(11) NOT NULL,
  `m_code` varchar(6) NOT NULL,
  `g_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `m_fname` varchar(20) NOT NULL,
  `m_lname` varchar(20) NOT NULL,
  `m_phone` varchar(13) NOT NULL,
  `m_nin` varchar(14) NOT NULL,
  `m_gender` int(11) NOT NULL,
  `m_dob` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `group_member`
--

INSERT INTO `group_member` (`m_id`, `m_code`, `g_id`, `user_id`, `m_fname`, `m_lname`, `m_phone`, `m_nin`, `m_gender`, `m_dob`, `created_at`, `update_at`) VALUES
(1, 'M1', 2, 1, 'Simon', 'Aula', '0788227288', 'cm10043900ratc', 1, '2022-10-24', '2022-10-24 13:43:25', '2022-10-24 13:43:25'),
(2, 'M2', 2, 1, 'BLwauTEHc6', '1CyLbKyPpw', '5259662771', 'UYm6AXZGFb', 1, '2022-10-25', '2022-10-24 15:57:37', '2022-10-24 15:57:37'),
(3, 'M3', 2, 1, 'dHrdKswLJ7', 'tWAoGK1qc9', '1177439098', 'aLzYfE9Zq9', 1, '2022-10-11', '2022-10-24 15:59:51', '2022-10-24 15:59:51'),
(4, 'M4', 2, 1, 'L4MoG2jffu', 'uEcziJFA39', '7556157764', 'bLf1gVC4Hd', 1, '2022-10-11', '2022-10-24 16:00:39', '2022-10-24 16:00:39'),
(5, 'M5', 3, 1, '8utSjHEA5S', 'tjyk8V0UxM', '6024604042', 'lMdopNOPs1', 1, '2022-10-11', '2022-10-24 16:01:13', '2022-10-24 16:01:13'),
(6, 'M6', 2, 1, 'F5ucbeKvdn', 'HdI48gTtk2', '5782350647', 'NWLBPwMkgE', 2, '2022-10-11', '2022-10-24 16:10:38', '2022-10-24 16:10:38');

-- --------------------------------------------------------

--
-- Table structure for table `guaranter`
--

CREATE TABLE `guaranter` (
  `g_id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `lo_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `kin_relations`
--

CREATE TABLE `kin_relations` (
  `r_id` int(11) NOT NULL,
  `r_name` varchar(50) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `legder`
--

CREATE TABLE `legder` (
  `l_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `l_type` int(11) NOT NULL,
  `trans_id` varchar(13) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `legder`
--

INSERT INTO `legder` (`l_id`, `user_id`, `m_id`, `amount`, `l_type`, `trans_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 6000, 2, '6357070ccca23', '2022-10-24 21:43:40', '2022-10-24 21:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `lo_id` int(11) NOT NULL,
  `lo_code` varchar(13) NOT NULL,
  `lo_rate` int(2) NOT NULL,
  `lo_expiry` date NOT NULL,
  `m_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lo_amount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `next_of_kin`
--

CREATE TABLE `next_of_kin` (
  `n_id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `n_fname` varchar(50) NOT NULL,
  `n_lname` varchar(50) NOT NULL,
  `n_relation` int(11) NOT NULL,
  `n_phone` varchar(13) NOT NULL,
  `n_location` varchar(100) NOT NULL,
  `n_dob` date NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expiry` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `user_id`, `otp`, `created_at`, `expiry`) VALUES
(1, 1, '$2y$10$iXIAIABBvaKtXQWeR9nJhOd7kuSyw9fyJ56MCaShtREmzjiFSgQV6', '2022-10-13 19:20:17', '2022-11-02 14:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `user_id`, `token`, `created_at`) VALUES
(30, 1, '655442809dcdfe94d4e73a9b8c7a4e49c6cc2411', '2022-11-01 11:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `trans_types`
--

CREATE TABLE `trans_types` (
  `ty_id` int(11) NOT NULL,
  `ty_name` varchar(50) NOT NULL,
  `mult` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_types`
--

INSERT INTO `trans_types` (`ty_id`, `ty_name`, `mult`, `created_on`, `updated_on`) VALUES
(1, 'deposit', 1, '2022-10-24 22:08:36', '2022-10-24 22:08:36'),
(2, 'fine', -1, '2022-10-24 22:10:42', '2022-10-24 22:10:42'),
(3, 'UtM4a0P8tc', 1, '2022-10-24 23:17:10', '2022-10-24 23:17:10'),
(4, 'ye568bLCVy', -1, '2022-10-24 23:18:00', '2022-10-24 23:18:00'),
(5, 'D3rpuvJxgZ', -1, '2022-10-24 23:30:36', '2022-10-24 23:30:36'),
(6, 'ejfjrjgkv', -1, '2022-10-25 10:03:27', '2022-10-25 10:03:27'),
(7, 'Simon', -1, '2022-11-01 12:02:05', '2022-11-01 12:02:05');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `mail` varchar(80) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `mail`, `fname`, `lname`, `status`, `created_at`, `updated_at`) VALUES
(1, 'simo@gold.vom', 'Kabonge', 'Abdul', 1, '2022-10-13 18:54:04', '2022-10-13 18:54:04'),
(2, 'tkibirige@cmmf.com', 'Kibirige', 'Twaha', 0, '2022-11-01 13:49:35', '2022-11-01 13:49:35');

-- --------------------------------------------------------

--
-- Table structure for table `weeks`
--

CREATE TABLE `weeks` (
  `w_id` int(11) NOT NULL,
  `w_code` varchar(6) NOT NULL,
  `g_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `weeks`
--

INSERT INTO `weeks` (`w_id`, `w_code`, `g_id`, `user_id`, `created_at`, `updated_on`) VALUES
(1, 'W1', 2, 1, '2022-10-20 16:19:30', '2022-10-20 16:19:30'),
(2, 'W2', 1, 1, '2022-10-20 16:52:05', '2022-10-20 16:52:05'),
(3, 'W3', 1, 1, '2022-10-20 16:54:37', '2022-10-20 16:54:37'),
(4, 'W4', 1, 1, '2022-10-21 12:58:47', '2022-10-21 12:58:47'),
(5, 'W5', 1, 1, '2022-10-21 13:00:50', '2022-10-21 13:00:50'),
(6, 'W6', 1, 1, '2022-10-22 12:28:11', '2022-10-22 12:28:11'),
(7, 'W7', 1, 1, '2022-10-22 13:31:22', '2022-10-22 13:31:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grouping`
--
ALTER TABLE `grouping`
  ADD PRIMARY KEY (`g_id`);

--
-- Indexes for table `group_member`
--
ALTER TABLE `group_member`
  ADD PRIMARY KEY (`m_id`);

--
-- Indexes for table `guaranter`
--
ALTER TABLE `guaranter`
  ADD PRIMARY KEY (`g_id`);

--
-- Indexes for table `kin_relations`
--
ALTER TABLE `kin_relations`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `legder`
--
ALTER TABLE `legder`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`lo_id`);

--
-- Indexes for table `next_of_kin`
--
ALTER TABLE `next_of_kin`
  ADD PRIMARY KEY (`n_id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trans_types`
--
ALTER TABLE `trans_types`
  ADD PRIMARY KEY (`ty_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `weeks`
--
ALTER TABLE `weeks`
  ADD PRIMARY KEY (`w_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grouping`
--
ALTER TABLE `grouping`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `group_member`
--
ALTER TABLE `group_member`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `guaranter`
--
ALTER TABLE `guaranter`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kin_relations`
--
ALTER TABLE `kin_relations`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `legder`
--
ALTER TABLE `legder`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `lo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `next_of_kin`
--
ALTER TABLE `next_of_kin`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `trans_types`
--
ALTER TABLE `trans_types`
  MODIFY `ty_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weeks`
--
ALTER TABLE `weeks`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `otp`
--
ALTER TABLE `otp`
  ADD CONSTRAINT `otp_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
