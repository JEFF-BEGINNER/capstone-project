-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2025 at 07:31 AM
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
-- Table structure for table `student_tbl`
--

CREATE TABLE `student_tbl` (
  `student_id` int(11) NOT NULL,
  `student_number` char(12) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(10) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `subject_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_tbl`
--

INSERT INTO `student_tbl` (`student_id`, `student_number`, `first_name`, `middle_initial`, `last_name`, `email`, `password`, `subject_code`, `created_at`, `updated_at`) VALUES
(0, '320701777777', 'Nicole', 'Q', 'Supnad', 'supnadprincessnicole_bsit@plmun.edu.ph', '$2y$10$czOE5c8c5TMSEPco4Tfjz.kZ3iVe3OPJ4YmS3CGDUmxVW5zQ06Bsi', '', '2025-08-23 06:19:59', '2025-08-23 06:19:59'),
(0, '320701888885', 'Jeff', 'P', 'Martorillas', 'jeffp@gmail.com', '$2y$10$qCsu1V22jofjrV7wDdtn0ur.iTBX.V22tzKh4LC/nUGooJYvgExz2', '', '2025-09-03 01:53:52', '2025-09-03 01:53:52'),
(0, '320701555555', 'Nicole', 'o', 'Cayabyab', 'nicole@gmail.com', '$2y$10$Fvrk7zMal8DKmD.ybnRVuu2NOELOubmG7qvZQ1x2F56r8jusM7yiC', '', '2025-09-03 02:25:24', '2025-09-03 02:25:24'),
(0, '320701555555', 'Nicole', 'o', 'Cayabyab', 'nicole@gmail.com', '$2y$10$DGaENFJDIW38Ch/2XR67qOo0iez35PhKMPNbI1TQGveYM2LLF.r8.', '', '2025-09-03 02:26:07', '2025-09-03 02:26:07'),
(0, '320701111111', 'Pn', 'C', 'Supnad', 'pn@gmail.com', '$2y$10$ECFT9d1tOIVN3dXXtB4bB.vIeQ0eTcy/oLfoE5Fb.WzuveRPmY/uq', '', '2025-09-03 02:27:30', '2025-09-03 02:27:30'),
(0, '320701444444', 'Erica', 'T', 'heart', 'erica@gmail.com', '$2y$10$HYgMfVMe9Zpk1Wh2lf0LQemcAhYpF0tnan5iflD7MCglvFDBnmVv2', '', '2025-09-03 03:07:34', '2025-09-03 03:07:34'),
(0, '320701444444', 'Erica', 'T', 'heart', 'erica@gmail.com', '$2y$10$ApnnT2tihlznWcAYDkx0xOCqFh6sDvEqkopRvGcEgKSg5rwS03qw2', '', '2025-09-03 03:26:30', '2025-09-03 03:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_tbl`
--

CREATE TABLE `teacher_tbl` (
  `teacher_id` int(11) NOT NULL,
  `teacher_number` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_initial` varchar(10) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_tbl`
--

INSERT INTO `teacher_tbl` (`teacher_id`, `teacher_number`, `first_name`, `middle_initial`, `last_name`, `email`, `password`, `created_at`) VALUES
(0, '33431323', 'Nicole', 'T', 'Bernardo', 'nicole234@gmil.com', '$2y$10$Ve77RjnIVJ.NYsJvw13NXeurUplciyCA3/T5ljHUrt9KWz/N8Wkne', '2025-08-23 06:23:17'),
(0, '56782543', 'Erica', 'S', 'Rosita', 'erica123@gmail.com', '$2y$10$LdpV1AWJjBvDrxrXHaAXE..TWURZ.xcOhE3NhV9kLKUWCAJtpbObu', '2025-08-31 13:08:10'),
(0, '12345678910', 'jonathan', 'y', 'yaya', 'yaya@gmail.com', '$2y$10$q7pE/nWKS9gndUI9cYwnCO.JkiJlFGON4leIveUuhGRrYaPRmPJRe', '2025-09-03 01:57:23'),
(0, '123456789', 'Nicole', 'P', 'Supnad', 'pnicole@gmail.com', '$2y$10$.JkemyfNShoGXtOLyGblDexI5ss2j..Zipc5uuWYAqtdSX/q79d16', '2025-09-03 02:29:01'),
(0, '12341234', 'Vince', 'G', 'Cayabyab', 'vince@gmail.com', '$2y$10$EuVK9emBUzMaPlN6OOCEF.McGK/o/h5UaXVCev2kEKLxhAljwNLjW', '2025-09-03 02:47:07'),
(0, '12349876', 'grace ', 's', 'Jurban ', 'grace@gmail.com', '$2y$10$lIatyPrBp8CVFOUjL1s.o.OSm5Jn.J5SYVqgzzRtuMv2e15Xn31bK', '2025-09-03 03:11:14'),
(0, '12345678929', 'joyce ', 'T', 'men', 'joyce@gmail.com', '$2y$10$YvfLjkME2MH.v.GA65kQx.HS23sXPLVmqQwAxJ2rOXF2QR9QIq2fO', '2025-09-03 03:24:35');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
