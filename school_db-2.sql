-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 05:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE `choices` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `choice_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_tbl`
--

CREATE TABLE `class_tbl` (
  `class_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','fill_blank') DEFAULT 'multiple_choice',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score` decimal(5,2) DEFAULT 0.00,
  `attempt_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_result_tbl`
--

CREATE TABLE `quiz_result_tbl` (
  `result_id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `score` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_tbl`
--

CREATE TABLE `quiz_tbl` (
  `quiz_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `quiz_title` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `student_id` int(11) NOT NULL,
  `student_number` char(12) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(10) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `class_id` int(11) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`student_id`, `student_number`, `avatar`, `first_name`, `middle_initial`, `last_name`, `email`, `password`, `created_at`, `updated_at`, `class_id`, `section`) VALUES
(1, '320701777777', NULL, 'Nicole', 'Q', 'Supnad', 'supnadprincessnicole_bsit@plmun.edu.ph', '$2y$10$czOE5c8c5TMSEPco4Tfjz.kZ3iVe3OPJ4YmS3CGDUmxVW5zQ06Bsi', '2025-08-23 06:19:59', '2025-11-14 11:13:30', NULL, ''),
(2, '320701888885', NULL, 'Jeff', 'P', 'Martorillas', 'jeffp@gmail.com', '$2y$10$qCsu1V22jofjrV7wDdtn0ur.iTBX.V22tzKh4LC/nUGooJYvgExz2', '2025-09-03 01:53:52', '2025-11-14 11:13:30', NULL, ''),
(3, '320701555555', NULL, 'Nicole', 'o', 'Cayabyab', 'nicole@gmail.com', '$2y$10$Fvrk7zMal8DKmD.ybnRVuu2NOELOubmG7qvZQ1x2F56r8jusM7yiC', '2025-09-03 02:25:24', '2025-11-14 11:13:30', NULL, ''),
(5, '320701111111', NULL, 'Pn', 'C', 'Supnad', 'pn@gmail.com', '$2y$10$ECFT9d1tOIVN3dXXtB4bB.vIeQ0eTcy/oLfoE5Fb.WzuveRPmY/uq', '2025-09-03 02:27:30', '2025-11-14 11:13:30', NULL, ''),
(6, '320701444444', NULL, 'Erica', 'T', 'heart', 'erica@gmail.com', '$2y$10$HYgMfVMe9Zpk1Wh2lf0LQemcAhYpF0tnan5iflD7MCglvFDBnmVv2', '2025-09-03 03:07:34', '2025-11-14 11:13:30', NULL, ''),
(8, '320701999999', NULL, 'Eloisa ', 'B', 'Rosita', 'eloisa123@gmail.com', '$2y$10$vsanfeQhToR/4WLNwDdmFeQXa8T58FkjthBgCXi0DjC42gx6WJD3C', '2025-09-20 04:04:31', '2025-11-14 11:13:30', NULL, 'Sapphire'),
(9, '320701123456', 'http://localhost/AI-ADAPTIVE/image/boy2.jpg', 'Kian Bernardo', 'M', 'Bernardo', 'kian@gmail.com', '$2y$10$5FRiDzzOF5RO4HCuv3TU/OlyTaYNuFR/59B2RbiIQItxdKWXD3.e.', '2025-09-20 04:11:39', '2025-11-14 11:13:30', NULL, 'Jasper'),
(10, '320701999888', 'http://localhost/AI-ADAPTIVE/image/girl2.jpg', 'Grace Jurban', 'S', 'Jurban', 'grace987@gmail.com', '$2y$10$03oo2dXJSblAfqrAoBjZ4eC4tZySaE9jNNlGmtHpylMoFkKPTxOZy', '2025-09-20 12:35:54', '2025-11-14 11:13:30', NULL, 'Emerald'),
(11, '320701999513', 'http://localhost/AI-ADAPTIVE/image/girl2.jpg', 'Michelle Ebrada', 'R', 'Ebrada', 'michelle123@gmail.com', '$2y$10$i0taUTQbkmmIF8uOXv1yfOjzzL0z2iPrx9MwetUVcWRyXUxmLhdRG', '2025-09-23 13:51:28', '2025-11-14 11:13:30', NULL, 'Sapphire'),
(12, '320701986134', NULL, 'Nicole', 'B', 'Manibale', 'Nicole2929', '$2y$10$UE8q8MLD7AtYgz07B2nSAunky4x8GgJ1Xc9x6HhFU2EpnamePrmya', '2025-11-10 12:45:56', '2025-11-14 11:13:30', NULL, 'Emerald'),
(13, '320701986555', NULL, 'Nicole', 'B', 'Ghrey', 'Nicole2222@gmail.com', '$2y$10$GTVlWlWXJcFMhqMVjEp.je0NMx8RZbaWoJUUGRvXrfX6Wkn/lYZTG', '2025-11-10 12:47:59', '2025-11-14 11:13:30', NULL, 'Quartz'),
(14, '320701986678', NULL, 'Juan', 'T', 'Bernardo', 'juan123@gmail.com', '$2y$10$8AnL4USqF471.JNMr/IzruHmNECnkkKI3Dde.5DpZSkOY/MFhocwi', '2025-11-14 10:24:38', '2025-11-14 11:13:30', NULL, 'Diamond'),
(15, '320701777098', NULL, 'Allan', 'T', 'Bernardo', 'allan123@gmail.com', '$2y$10$soBsD9DSd6zqZAS8P8Odlug0ocTh46xD0.HKlE6D73kswJuEA5gr2', '2025-11-14 10:44:11', '2025-11-14 11:13:30', NULL, 'Diamond'),
(16, '320701777415', NULL, 'Joel', 'T', 'Bernardo', 'joel123@gmail.com', '$2y$10$bNTPUekZz9mUp7ajQerFYuBvvtC4OPm1CStexTx6VASf.TbhriN/i', '2025-11-14 10:48:46', '2025-11-15 02:32:44', 1, 'Diamond'),
(17, '320701777214', NULL, 'Zion', 'V', 'Bernardo', 'zion123@gmail.com', '$2y$10$UIm0gxxhR7gs6cOHesxslu40b6r/Nt8yjHqQoGmK0hQv52uUpVBf.', '2025-11-14 10:55:31', '2025-11-14 11:13:30', NULL, 'Diamond'),
(18, '320701777009', NULL, 'Czyrus', 'M', 'Bernardo', 'czyruz123@gmail.com', '$2y$10$hkLfWssHIO.k1ZTsz5fNPeItF0mMbM.w8bDNvuX593gNmSOfLo9GK', '2025-11-14 11:27:13', '2025-11-14 11:27:13', NULL, 'Diamond'),
(19, '320701777314', NULL, 'Ricky', 'T', 'Bernardo', 'ricky123@gmail.com', '$2y$10$ZbGm7484rtfrm6ali4ExheAy8XtW1QOS7/M1AsK4Vze7RgSiLsxJm', '2025-11-14 11:30:23', '2025-11-14 11:30:23', NULL, 'Diamond'),
(20, '320701777344', NULL, 'John', 'M', 'Manibale', 'john123@gmail.com', '$2y$10$Gy1Y9pYApRpDQJzioprtWewexkHjVi5AMCWMqJID5bzlQc8AxBQFO', '2025-11-14 11:46:05', '2025-11-14 11:46:36', 1, 'Diamond'),
(21, '320701777333', 'http://localhost/AI-ADAPTIVE/image/girl1.jpg', 'Verna Catipol', 'T', 'Catipol', 'vernat@gmail.com', '$2y$10$5XT7rFr2vBNj70/y38H81egC7KGVQAcQKeq24UbHYgHBBn.f9yI0a', '2025-11-15 02:44:03', '2025-11-15 02:57:03', 1, 'Emerald');

-- --------------------------------------------------------

--
-- Table structure for table `tasks_tbl`
--

CREATE TABLE `tasks_tbl` (
  `task_id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('assigned','missing','done') DEFAULT 'assigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_tbl`
--

CREATE TABLE `teacher_tbl` (
  `teacher_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_initial` varchar(5) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `teacher_number` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'image/teacher1.jpg',
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_tbl`
--

INSERT INTO `teacher_tbl` (`teacher_id`, `first_name`, `middle_initial`, `last_name`, `teacher_number`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, 'Clarizza', 'S', 'Manibale', '12374251762', 'clarizza123@gmail.com', '$2y$10$2fdPE2ZAApEfkoTZCGzF6.R2cUypWw2V.jiEYu9Lq4kaD7DS1NotC', 'http://localhost/AI-ADAPTIVE/image/teacher1%20(4).jpg', '2025-11-10 13:20:32'),
(2, 'Kolasa', 'Q', 'Supnad', '77777777777', 'kolasa@gmail.com', '$2y$10$OOcGAo.8NYaQ9qDLLZZ/kOiuZqo5gK0MQV9jgo25vIjluCMcbw6N.', 'http://localhost/AI-ADAPTIVE/image/teacher1%20(3).jpg', '2025-11-15 02:59:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `class_tbl`
--
ALTER TABLE `class_tbl`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_result_tbl`
--
ALTER TABLE `quiz_result_tbl`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `quiz_tbl`
--
ALTER TABLE `quiz_tbl`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_tbl`
--
ALTER TABLE `student_tbl`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `unique_student_number` (`student_number`);

--
-- Indexes for table `tasks_tbl`
--
ALTER TABLE `tasks_tbl`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `teacher_tbl`
--
ALTER TABLE `teacher_tbl`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_tbl`
--
ALTER TABLE `class_tbl`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_result_tbl`
--
ALTER TABLE `quiz_result_tbl`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_tbl`
--
ALTER TABLE `quiz_tbl`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_tbl`
--
ALTER TABLE `student_tbl`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tasks_tbl`
--
ALTER TABLE `tasks_tbl`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_tbl`
--
ALTER TABLE `teacher_tbl`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `choices`
--
ALTER TABLE `choices`
  ADD CONSTRAINT `choices_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_result_tbl`
--
ALTER TABLE `quiz_result_tbl`
  ADD CONSTRAINT `quiz_result_tbl_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz_tbl` (`quiz_id`),
  ADD CONSTRAINT `quiz_result_tbl_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student_tbl` (`student_id`);

--
-- Constraints for table `quiz_tbl`
--
ALTER TABLE `quiz_tbl`
  ADD CONSTRAINT `quiz_tbl_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class_tbl` (`class_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
