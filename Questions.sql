-- phpMyAdmin SQL Dump
-- version 4.4.15.8
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 30, 2017 at 05:20 AM
-- Server version: 5.6.31
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Questions`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE IF NOT EXISTS `answer` (
  `id` bigint(20) NOT NULL,
  `question_id` bigint(20) NOT NULL,
  `text` text NOT NULL,
  `is_other` tinyint(1) DEFAULT NULL,
  `times_used` int(11) DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`id`, `question_id`, `text`, `is_other`, `times_used`) VALUES
(1, 1, 'Particles in the air scatter blue light more than other colors', NULL, 1),
(3, 1, 'The Earth is covered in blue water', NULL, 1),
(4, 1, 'Science!', NULL, NULL),
(5, 1, 'Blue paint was cheap when the Earth was built', 1, 1),
(6, 2, 'To be good to each other', 1, 1),
(7, 2, 'To find love', 1, 1),
(8, 2, 'To create', 1, 1),
(9, 2, 'There is no meaning, life is chaos', 1, 1),
(10, 3, 'Coke', NULL, 1),
(11, 3, 'Pepsi', NULL, 2),
(12, 3, 'RC Cola', 1, 1),
(13, 4, 'Chocolate', NULL, 2),
(14, 4, 'Vanilla', NULL, 1),
(15, 5, 'Tacos', NULL, 1),
(16, 5, 'Burritos', NULL, 2),
(17, 5, 'Chimichangas', 1, 1),
(18, 6, 'Yes', NULL, 1),
(19, 6, 'No', NULL, 4),
(21, 17, 'Blue', NULL, 2),
(22, 17, 'Yellow', NULL, 1),
(49, 3, 'Mtn Dew', 1, 1),
(58, 2, 'To find purpose', 1, 1),
(61, 17, 'Red', 1, 1),
(62, 2, 'To make the world a better place', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `text` text NOT NULL,
  `allow_other` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `user_id`, `text`, `allow_other`) VALUES
(1, 2, 'Why is the sky blue?', 1),
(2, 2, 'What is the meaning of life?', 1),
(3, 2, 'Coke or Pepsi?', 1),
(4, 2, 'Chocolate or vanilla?', NULL),
(5, 2, 'Tacos or burritos?', 1),
(6, 2, 'Can there be good without evil?', NULL),
(17, 2, 'What is your favorite color?', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `is_admin` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `is_admin`) VALUES
(2, 'quizzer', '5f4dcc3b5aa765d61d8327deb882cf99', NULL),
(3, 'knower', '5f4dcc3b5aa765d61d8327deb882cf99', NULL),
(6, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_answer`
--

CREATE TABLE IF NOT EXISTS `user_answer` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `answer_id` bigint(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_answer`
--

INSERT INTO `user_answer` (`id`, `user_id`, `answer_id`) VALUES
(16, 6, 60),
(17, 6, 18),
(19, 6, 59),
(20, 6, 14),
(21, 6, 11),
(22, 6, 22),
(23, 6, 15),
(24, 6, 2),
(25, 3, 13),
(26, 3, 61),
(27, 3, 62),
(28, 3, 19),
(29, 3, 11),
(30, 3, 16),
(31, 3, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_answer_question_id` (`question_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_question_user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`);

--
-- Indexes for table `user_answer`
--
ALTER TABLE `user_answer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `user_answer`
--
ALTER TABLE `user_answer`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `fk_answer_question_id` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `fk_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
