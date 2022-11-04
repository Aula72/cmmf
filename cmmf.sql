-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2022 at 12:59 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

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
-- Table structure for table `account_balance`
--

CREATE TABLE `account_balance` (
  `a_id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ls_id` int(11) NOT NULL DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_status`
--

CREATE TABLE `loan_status` (
  `ls_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loan_status`
--

INSERT INTO `loan_status` (`ls_id`, `name`) VALUES
(1, 'pending'),
(2, 'approved'),
(3, 'renewed'),
(4, 'settled');

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
(1, 1, '$2y$10$nrWkXSZPCgGWhSqQfioMC.cX0Jkbyod4MS0Lod8af3P3CBggED1Mm', '2022-11-03 23:01:43', '2022-11-05 02:22:06'),
(2, 2, '$2y$10$lcCCnNLWqm16BbmyhK5Piu/ia3kriq4Xx7V10WRq.tlLLRJZEya3C', '2022-11-03 23:53:39', '2022-11-05 02:53:39');

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

-- --------------------------------------------------------

--
-- Table structure for table `trans_action`
--

CREATE TABLE `trans_action` (
  `t_id` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `trans_type_id` int(11) NOT NULL,
  `t_code` varchar(20) NOT NULL,
  `t_amount` int(11) NOT NULL,
  `t_desc` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(2, 'tkibirige@cmmf.com', 'Kibirige', 'Twaha', 1, '2022-11-01 13:49:35', '2022-11-01 13:49:35');

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
-- Indexes for dumped tables
--

--
-- Indexes for table `account_balance`
--
ALTER TABLE `account_balance`
  ADD PRIMARY KEY (`a_id`);

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
-- Indexes for table `loan_status`
--
ALTER TABLE `loan_status`
  ADD PRIMARY KEY (`ls_id`);

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
-- Indexes for table `trans_action`
--
ALTER TABLE `trans_action`
  ADD PRIMARY KEY (`t_id`);

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
-- AUTO_INCREMENT for table `account_balance`
--
ALTER TABLE `account_balance`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grouping`
--
ALTER TABLE `grouping`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_member`
--
ALTER TABLE `group_member`
  MODIFY `m_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `lo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_status`
--
ALTER TABLE `loan_status`
  MODIFY `ls_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `next_of_kin`
--
ALTER TABLE `next_of_kin`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trans_action`
--
ALTER TABLE `trans_action`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trans_types`
--
ALTER TABLE `trans_types`
  MODIFY `ty_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `weeks`
--
ALTER TABLE `weeks`
  MODIFY `w_id` int(11) NOT NULL AUTO_INCREMENT;

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
