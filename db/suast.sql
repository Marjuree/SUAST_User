-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 06:28 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `suast`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblapplicants`
--

CREATE TABLE `tblapplicants` (
  `id` int(11) NOT NULL,
  `lname` varchar(20) CHARACTER SET latin1 NOT NULL,
  `fname` varchar(20) CHARACTER SET latin1 NOT NULL,
  `mname` varchar(20) CHARACTER SET latin1 NOT NULL,
  `bdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `religion` varchar(50) CHARACTER SET latin1 NOT NULL,
  `nationality` varchar(50) CHARACTER SET latin1 NOT NULL,
  `civilstatus` varchar(20) CHARACTER SET latin1 NOT NULL,
  `ethnicity` varchar(50) CHARACTER SET latin1 NOT NULL,
  `contact` varchar(15) CHARACTER SET latin1 NOT NULL,
  `purok` varchar(20) CHARACTER SET latin1 NOT NULL,
  `barangay` varchar(120) CHARACTER SET latin1 NOT NULL,
  `municipality` varchar(50) CHARACTER SET latin1 NOT NULL,
  `province` varchar(50) CHARACTER SET latin1 NOT NULL,
  `first_option` varchar(50) CHARACTER SET latin1 NOT NULL,
  `second_option` varchar(50) CHARACTER SET latin1 NOT NULL,
  `third_option` varchar(50) CHARACTER SET latin1 NOT NULL,
  `campus` varchar(50) CHARACTER SET latin1 NOT NULL,
  `gender` varchar(6) CHARACTER SET latin1 NOT NULL,
  `n_mother` varchar(100) CHARACTER SET latin1 NOT NULL,
  `n_father` varchar(100) CHARACTER SET latin1 NOT NULL,
  `c_mother` varchar(15) CHARACTER SET latin1 NOT NULL,
  `c_father` varchar(15) CHARACTER SET latin1 NOT NULL,
  `m_occupation` varchar(100) CHARACTER SET latin1 NOT NULL,
  `f_occupation` varchar(100) CHARACTER SET latin1 NOT NULL,
  `m_address` text CHARACTER SET latin1 NOT NULL,
  `f_address` text CHARACTER SET latin1 NOT NULL,
  `living_status` varchar(50) CHARACTER SET latin1 NOT NULL,
  `siblings` int(11) NOT NULL,
  `birth_order` int(11) NOT NULL,
  `monthly_income` int(12) NOT NULL,
  `indigenous` varchar(50) CHARACTER SET latin1 NOT NULL,
  `basic_sector` varchar(50) CHARACTER SET latin1 NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) CHARACTER SET latin1 NOT NULL,
  `image` text CHARACTER SET latin1 NOT NULL,
  `date_applied` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblapplicants`
--

INSERT INTO `tblapplicants` (`id`, `lname`, `fname`, `mname`, `bdate`, `age`, `religion`, `nationality`, `civilstatus`, `ethnicity`, `contact`, `purok`, `barangay`, `municipality`, `province`, `first_option`, `second_option`, `third_option`, `campus`, `gender`, `n_mother`, `n_father`, `c_mother`, `c_father`, `m_occupation`, `f_occupation`, `m_address`, `f_address`, `living_status`, `siblings`, `birth_order`, `monthly_income`, `indigenous`, `basic_sector`, `username`, `password`, `image`, `date_applied`) VALUES
(5, '', '', '', '0000-00-00', 0, '', '', '', 'Select Ethnicity', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Yes', 0, 0, 0, '', '', '', '', 'default.png', '2025-03-26 03:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `tblexam_schedule`
--

CREATE TABLE `tblexam_schedule` (
  `id` int(11) NOT NULL,
  `exam_name` varchar(100) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_time` time NOT NULL,
  `subject` varchar(100) NOT NULL,
  `room` varchar(50) NOT NULL,
  `slot_limit` int(11) DEFAULT 30
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tblexam_schedule`
--

INSERT INTO `tblexam_schedule` (`id`, `exam_name`, `exam_date`, `exam_time`, `subject`, `room`, `slot_limit`) VALUES
(28, 'For Taker', '2025-03-20', '12:46:00', 'AB Building, DORSU', 'Testing Room 2', 30),
(29, 'Okay', '2025-03-27', '12:20:00', 'AB Building, DORSU', 'Testing Room 1', 30),
(30, 'sample', '2025-03-26', '12:21:00', 'Request  ', 'Testing Room 4', 30);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_announcement`
--

CREATE TABLE `tbl_announcement` (
  `id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('SUAST','Accounting') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_announcement`
--

INSERT INTO `tbl_announcement` (`id`, `admin_name`, `message`, `status`, `created_at`, `role`) VALUES
(25, 'sample', 'OKOK', 'Inactive', '2025-03-24 18:51:32', 'Accounting'),
(26, 'sample', 'sssss', 'Inactive', '2025-03-24 18:52:57', 'SUAST'),
(27, 'ssssss', 'ssssss', 'Active', '2025-03-24 18:59:46', 'SUAST'),
(28, 'Kenneth', 'Okay', 'Active', '2025-03-24 19:22:55', 'Accounting'),
(29, 'Teacher Kenneth', 'Lorem ipsum (/ˌlɔː.rəm ˈɪp.səm/ LOR-əm IP-səm) is a dummy or placeholder text commonly used in graphic design, publishing, and web development. Its purpose is to permit a page layout to be designed, independently of the copy that will subsequently populate it, or to demonstrate various fonts of a typeface without meaningful text that could be distracting.', 'Active', '2025-03-24 19:42:29', 'Accounting');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_applicant_registration`
--

CREATE TABLE `tbl_applicant_registration` (
  `applicant_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `university_email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `applicant_password` varchar(255) NOT NULL,
  `privacy_notice_accepted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_applicant_registration`
--

INSERT INTO `tbl_applicant_registration` (`applicant_id`, `first_name`, `middle_name`, `last_name`, `university_email`, `username`, `applicant_password`, `privacy_notice_accepted`) VALUES
(20, 'Kenneth', 'Nudalo', 'Genobisa', 'applicant@gmail.com', 'Kenneth', '$2y$10$0ScTLPJ3Fbq0Qv7cPDKwi.JVj0OGIrfrpTcq2zdfuy1skLxrNgsea', 1),
(21, 'Sample', 'sample', 'sample', 'admin@gmail.com', 'sample', '$2y$10$4HnNmCnmMXDTP95KrSO91.fERFfG.3hmHOtgZM/dWzNNdZFT6JXva', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certification_requests`
--

CREATE TABLE `tbl_certification_requests` (
  `id` int(11) NOT NULL,
  `request_type` varchar(50) NOT NULL,
  `date_request` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `request_status` enum('Pending','Approved','Disapproved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clearance_requests`
--

CREATE TABLE `tbl_clearance_requests` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `date_requested` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_clearance_requests`
--

INSERT INTO `tbl_clearance_requests` (`id`, `student_id`, `status`, `date_requested`) VALUES
(18, '010599', 'Pending', '2025-03-28 19:21:57'),
(19, '010599', 'Approved', '2025-03-28 19:23:30'),
(20, '010599', 'Pending', '2025-03-29 01:16:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact`
--

CREATE TABLE `tbl_contact` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_contact`
--

INSERT INTO `tbl_contact` (`id`, `name`, `email`, `phone`, `created_at`) VALUES
(1, 'Kenneth N. Kenneth', 'kenneth@gmail.com', '1061874873', '2025-03-15 20:50:55'),
(2, 'Kenneth N. Kenneth', 'kenneth@gmail.com', '1061874873', '2025-03-15 21:00:39'),
(3, 'Alawabalo', 'alawabalo@gmail.com', '1272828828282', '2025-03-20 14:51:13'),
(4, 'Ehyyyy', 'ehhyyy@gmail.com', '828828282882', '2025-03-20 14:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee_registration`
--

CREATE TABLE `tbl_employee_registration` (
  `employee_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `employee_password` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_employee_registration`
--

INSERT INTO `tbl_employee_registration` (`employee_id`, `username`, `email`, `employee_password`, `first_name`, `middle_name`, `last_name`, `created_at`, `updated_at`) VALUES
(1, 'Kenneth', 'kenneth@gmail.com', '$2y$10$P0AyQ6HPQ3CSaAd1WFbbF.B6sZ2ZwCW/ewGgIff/WrWFfLq1j1wa.', 'Kenneth N.', 'sample', 'Kenneth', '2025-03-28 22:02:36', '2025-03-28 22:02:36'),
(2, 'sample', 'sample@gmail.com', '$2y$10$tocZqwXfDOkXKW0nZ2qeROjWz6tqpi7LyJlSom6.rpHa9G8Q4rcrm', 'sample', 'sample', 'sample', '2025-03-28 23:02:32', '2025-03-28 23:02:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_leave_requests`
--

CREATE TABLE `tbl_leave_requests` (
  `id` int(11) NOT NULL,
  `request_type` varchar(50) NOT NULL,
  `date_request` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `leave_dates` varchar(255) NOT NULL,
  `leave_form` varchar(255) NOT NULL,
  `request_status` enum('Pending','Approved','Disapproved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

CREATE TABLE `tbl_logs` (
  `id` int(11) NOT NULL,
  `log_type` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `log_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_logs`
--

INSERT INTO `tbl_logs` (`id`, `log_type`, `title`, `message`, `log_date`) VALUES
(1, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Employee\'.', '2025-03-25 14:23:49'),
(2, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 14:23:49'),
(3, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Employee\'.', '2025-03-25 14:24:55'),
(4, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 14:24:55'),
(5, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-25 14:27:40'),
(6, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 14:27:40'),
(7, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-25 14:28:33'),
(8, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 14:28:33'),
(9, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Employee\'.', '2025-03-25 14:29:24'),
(10, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 14:29:24'),
(11, 'WARNING', 'Login Failed', 'Admin Office Failed login attempt for user \'Kenneth\'.', '2025-03-25 14:33:51'),
(12, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 14:33:51'),
(13, 'INFO', 'Login Success', 'Admin \'Kenneth\' logged in successfully.', '2025-03-25 14:34:49'),
(14, 'WARNING', 'Login Failed', 'Admin Office Failed login attempt for user \'Kenneth\'.', '2025-03-25 15:32:08'),
(15, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 15:32:08'),
(16, 'INFO', 'Login Success', 'Admin \'Kenneth\' logged in successfully.', '2025-03-25 15:32:21'),
(17, 'INFO', 'Login Success', 'Admin \'Employee\' logged in successfully.', '2025-03-25 15:35:00'),
(18, 'WARNING', 'Login Failed', 'Employee User not found: \'\'.', '2025-03-25 16:38:00'),
(19, 'WARNING', 'Login Failed', 'Employee User not found: \'\'.', '2025-03-25 16:40:16'),
(20, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-25 16:58:42'),
(21, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-25 17:07:46'),
(22, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-25 18:03:50'),
(23, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-25 18:12:06'),
(24, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Expertnode\'.', '2025-03-25 18:13:18'),
(25, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 18:13:18'),
(26, 'INFO', 'Login Success', 'Admin \'Employee \' logged in successfully.', '2025-03-25 18:13:59'),
(27, 'INFO', 'Login Success', 'Applicant \'Kenneth \' logged in successfully.', '2025-03-25 18:15:09'),
(28, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-25 18:29:19'),
(29, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-25 20:14:07'),
(30, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-25 20:15:02'),
(31, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-25 20:15:19'),
(32, 'WARNING', 'Login Failed', 'Student User not found!  \'\'.', '2025-03-25 20:15:19'),
(33, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-25 20:15:50'),
(34, 'INFO', 'Login Success', 'Admin \'Kenneth\' logged in successfully.', '2025-03-25 20:21:11'),
(35, 'INFO', 'Login Success', 'Admin \'Kenneth\' logged in successfully.', '2025-03-26 11:46:13'),
(36, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:12:30'),
(37, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:22:50'),
(38, 'INFO', 'Registration Success', 'New employee \'Kenneth\' registered successfully.', '2025-03-26 12:31:09'),
(39, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 12:32:07'),
(40, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:34:42'),
(41, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:35:04'),
(42, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:35:19'),
(43, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:38:06'),
(44, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:38:16'),
(45, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:40:07'),
(46, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 12:40:19'),
(47, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 12:42:27'),
(48, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 12:44:49'),
(49, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 12:45:23'),
(50, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 12:46:41'),
(51, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 12:53:36'),
(52, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 13:01:55'),
(53, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in.', '2025-03-26 13:02:49'),
(54, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in.', '2025-03-26 13:04:02'),
(55, 'INFO', 'Registration Success', 'New employee \'Ken\' registered.', '2025-03-26 13:04:48'),
(56, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 13:06:33'),
(57, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 13:07:21'),
(58, 'WARNING', 'Login Failed', 'Employee \'213\' not found in database.', '2025-03-26 13:07:43'),
(59, 'ERROR', 'Registration Failed', 'Error registering employee \'Kenneth\': Duplicate entry \'54321\' for key \'emp_school_id\'', '2025-03-26 13:08:30'),
(60, 'INFO', 'Registration Success', 'New employee \'kenn\' registered successfully.', '2025-03-26 13:09:08'),
(61, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 13:09:31'),
(62, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 13:46:42'),
(63, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 13:49:40'),
(64, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 13:54:52'),
(65, 'INFO', 'Registration Success', 'New employee \'choy\' registered successfully.', '2025-03-26 13:56:11'),
(66, 'INFO', 'Login Success', 'Employee \'choy\' logged in successfully.', '2025-03-26 13:56:45'),
(67, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 13:58:20'),
(68, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 13:58:52'),
(69, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 14:03:28'),
(70, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 14:06:08'),
(71, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-26 14:09:23'),
(72, 'INFO', 'Login Success', 'Admin \'Kenneth\' logged in successfully.', '2025-03-26 14:11:16'),
(73, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Employee\'.', '2025-03-26 14:11:48'),
(74, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 14:11:48'),
(75, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Employee\'.', '2025-03-26 14:12:33'),
(76, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 14:12:33'),
(77, 'INFO', 'Login Success', 'Admin \'Kenneth\' logged in successfully.', '2025-03-26 14:12:59'),
(78, 'INFO', 'Login Success', 'Applicant \'Kenneth \' logged in successfully.', '2025-03-26 14:36:40'),
(79, 'INFO', 'Login Success', 'Admin \'Kenneth\' logged in successfully.', '2025-03-26 14:43:09'),
(80, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'HRMO\'.', '2025-03-26 14:43:17'),
(81, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 14:43:17'),
(82, 'WARNING', 'Login Failed', 'Invalid Role Selection Attempt for \'Jonweak\'.', '2025-03-26 14:47:16'),
(83, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 14:47:16'),
(84, 'WARNING', 'Login Failed', 'Invalid Role Selection Attempt for \'Kenneth\'.', '2025-03-26 14:47:33'),
(85, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 14:47:33'),
(86, 'WARNING', 'Login Failed', 'Invalid Role Selection Attempt for \'Accounting\'.', '2025-03-26 16:16:29'),
(87, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:16:29'),
(88, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-26 16:21:58'),
(89, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:21:58'),
(90, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-26 16:22:50'),
(91, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:22:50'),
(92, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-26 16:24:04'),
(93, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:24:04'),
(94, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-26 16:24:31'),
(95, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:24:31'),
(96, 'WARNING', 'Login Failed', 'Invalid Role Selection Attempt for \'jude\'.', '2025-03-26 16:26:27'),
(97, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:26:27'),
(98, 'WARNING', 'Login Failed', 'Invalid Role Selection Attempt for \'accounting_user\'.', '2025-03-26 16:29:53'),
(99, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:29:53'),
(100, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:34:47'),
(101, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:39:00'),
(102, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-26 16:45:08'),
(103, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:45:08'),
(104, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-26 16:45:35'),
(105, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-26 16:45:45'),
(106, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-26 19:22:29'),
(107, 'INFO', 'Login Success', 'Applicant \'Kenneth \' logged in successfully.', '2025-03-26 21:13:49'),
(108, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-27 23:53:06'),
(109, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-27 23:54:23'),
(110, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-28 00:02:47'),
(111, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-28 01:46:24'),
(112, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-28 02:46:41'),
(113, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-28 02:53:35'),
(114, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-28 03:25:25'),
(115, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-28 04:28:53'),
(116, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-28 17:11:11'),
(117, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 17:16:27'),
(118, 'INFO', 'Login Success', 'Admin \'Accounting\' logged in successfully.', '2025-03-28 17:17:17'),
(119, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-28 17:27:08'),
(120, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-28 17:35:25'),
(121, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:12:28'),
(122, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:13:02'),
(123, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:13:14'),
(124, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:13:27'),
(125, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:13:41'),
(126, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:17:36'),
(127, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:20:10'),
(128, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:20:23'),
(129, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:20:34'),
(130, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:20:51'),
(131, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:24:44'),
(132, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-28 20:26:22'),
(133, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-28 20:27:03'),
(134, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:30:41'),
(135, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:31:04'),
(136, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:32:55'),
(137, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:33:11'),
(138, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:33:29'),
(139, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-28 20:33:40'),
(140, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-28 20:33:56'),
(141, 'WARNING', 'Login Failed', 'Student User not found!  \'\'.', '2025-03-28 20:33:56'),
(142, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:34:09'),
(143, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:34:23'),
(144, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:34:37'),
(145, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:36:46'),
(146, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'sample\'.', '2025-03-28 20:37:06'),
(147, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:37:06'),
(148, 'WARNING', 'Login Failed', 'Admin Invalid Role Please Select OFFICE!  \'Accounting\'.', '2025-03-28 20:38:08'),
(149, 'WARNING', 'Login Failed', 'No account found with this username! \'\'.', '2025-03-28 20:38:08'),
(150, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-28 22:33:20'),
(151, 'INFO', 'Login Success', 'Applicant \'Kenneth\' logged in successfully.', '2025-03-28 22:34:15'),
(152, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 22:50:19'),
(153, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 22:50:39'),
(154, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 22:50:53'),
(155, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 22:52:40'),
(156, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 22:56:26'),
(157, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 23:01:28'),
(158, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-28 23:03:00'),
(159, 'INFO', 'Login Success', 'Employee \'sample\' logged in successfully.', '2025-03-28 23:03:17'),
(160, 'WARNING', 'Login Failed', 'Employee Invalid Password! \'Kenneth\'.', '2025-03-28 23:16:07'),
(161, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 23:17:12'),
(162, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-28 23:57:02'),
(163, 'WARNING', 'Login Failed', 'No account found with this username! \'Accounting\'.', '2025-03-29 00:05:29'),
(164, 'WARNING', 'Login Failed', 'No account found with this username! \'Accounting\'.', '2025-03-29 00:05:36'),
(165, 'INFO', 'Login Success', 'Student \'\' logged in successfully.', '2025-03-29 00:05:45'),
(166, 'WARNING', 'Login Failed', 'Student User not found!  \'\'.', '2025-03-29 00:05:45'),
(167, 'WARNING', 'Login Failed', 'Student User \'Accounting\' not found.', '2025-03-29 00:07:09'),
(168, 'WARNING', 'Login Failed', 'No account found with this username! \'sss\'.', '2025-03-29 00:07:28'),
(169, 'WARNING', 'Login Failed', 'Student User \'Accounting\' not found.', '2025-03-29 00:08:18'),
(170, 'WARNING', 'Login Failed', 'Invalid password for user \'Kenneth N.Genobisa\'.', '2025-03-29 00:08:26'),
(171, 'INFO', 'Login Success', 'Student \'Kenneth N.Genobisa\' logged in successfully.', '2025-03-29 00:08:57'),
(172, 'INFO', 'Login Success', 'Student \'Kenneth N.Genobisa\' logged in successfully.', '2025-03-29 00:10:17'),
(173, 'INFO', 'Login Success', 'Employee \'Kenneth\' logged in successfully.', '2025-03-29 00:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_message`
--

CREATE TABLE `tbl_message` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_message`
--

INSERT INTO `tbl_message` (`id`, `username`, `message`, `created_at`) VALUES
(1, 'Kenneth N.', 'sssss', '2025-03-29 00:36:09'),
(2, 'Kenneth N.', 'my name is pogi', '2025-03-29 00:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_permits`
--

CREATE TABLE `tbl_permits` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `purpose_name` varchar(255) NOT NULL,
  `course_year` varchar(50) NOT NULL,
  `type_of_permit` varchar(100) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `date_requested` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_personnel_inquiries`
--

CREATE TABLE `tbl_personnel_inquiries` (
  `id` int(11) NOT NULL,
  `request_type` varchar(50) NOT NULL,
  `date_request` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `request_status` enum('Pending','Resolved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_reservation`
--

CREATE TABLE `tbl_reservation` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `exam_time` time NOT NULL,
  `room` varchar(50) NOT NULL,
  `venue` varchar(255) NOT NULL DEFAULT 'AB Building, DORSU',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_service_requests`
--

CREATE TABLE `tbl_service_requests` (
  `id` int(11) NOT NULL,
  `request_type` varchar(50) NOT NULL,
  `date_request` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `faculty` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  `request_status` enum('Pending','Approved','Disapproved') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student_balances`
--

CREATE TABLE `tbl_student_balances` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `student_name` varchar(100) NOT NULL,
  `total_balance` decimal(10,2) NOT NULL,
  `last_payment` date NOT NULL,
  `due_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_student_balances`
--

INSERT INTO `tbl_student_balances` (`id`, `student_id`, `student_name`, `total_balance`, `last_payment`, `due_date`) VALUES
(21, 10599, 'Kenneth', '22222.00', '2025-03-21', '2025-03-13'),
(22, 12345, 'Janley', '130000.00', '2025-03-27', '2025-03-28'),
(23, 10599, 'Kennethee', '100000.00', '2025-03-04', '2025-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student_users`
--

CREATE TABLE `tbl_student_users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `school_id` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_student_users`
--

INSERT INTO `tbl_student_users` (`id`, `full_name`, `email`, `school_id`, `username`, `password`, `created_at`) VALUES
(8, 'Kenneth N.Genobisa', 'kenneth@gmail.com', '010599', 'Kenneth', '$2y$10$zk/3k/YtepGMERj7ui0kqu04jP2C2.EdaDy/oydJIkkyJRWMlpnc6', '2025-03-28 17:18:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_unauthorized_access`
--

CREATE TABLE `tbl_unauthorized_access` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `error_id` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `access_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_unauthorized_access`
--

INSERT INTO `tbl_unauthorized_access` (`id`, `username`, `error_id`, `ip_address`, `device_name`, `access_time`) VALUES
(209, 'Guest', 'ERR-67e705efde09e6.61650996', '192.168.100.18', 'Windows PC', '2025-03-28 20:26:23'),
(210, 'Kenneth', 'ERR-67e7208eb5b046.65001126', '192.168.100.18', 'Windows PC', '2025-03-28 22:19:58'),
(211, 'Kenneth', 'ERR-67e7208edea391.56653709', '192.168.100.18', 'Windows PC', '2025-03-28 22:19:58'),
(212, 'Kenneth', 'ERR-67e7208f0f48e7.97101249', '192.168.100.18', 'Windows PC', '2025-03-28 22:19:59'),
(213, 'Kenneth', 'ERR-67e7208f8ed4b3.03921363', '192.168.100.18', 'Windows PC', '2025-03-28 22:19:59'),
(214, 'Kenneth', 'ERR-67e720a6153ce9.76525687', '192.168.100.18', 'Windows PC', '2025-03-28 22:20:22'),
(215, 'Kenneth', 'ERR-67e720a71328d5.28964592', '192.168.100.18', 'Windows PC', '2025-03-28 22:20:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_management`
--

CREATE TABLE `tbl_users_management` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `school_id` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('SUAST','Accounting','HRMO','Student','Employee') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users_management`
--

INSERT INTO `tbl_users_management` (`id`, `name`, `email`, `school_id`, `username`, `password`, `role`) VALUES
(70, 'Kenneth N.Genobisa', 'kennethgenobisa45@gmail.com', '21344', 'Kenneth', '$2y$10$iuPgaXsW3QNfERYQUt/bve19gWtW9furVvarHHqCrdEOsv8nmNvB2', 'Employee'),
(74, 'Kenneth N. Genobisa', 'kenneth@gmail.com', '2123445', 'Accounting', '$2y$10$reKpqQhZ4uk/u8ix8NO2Quxb6KPiw.ulgf0qHox0FWYq.cdS93oUS', 'Accounting'),
(76, 'sample', 'sample@gmail.com', '12345', 'sample', '$2y$10$ua/hMKi6AnIw2pj00Din3uUVt9dg055Vtyoz3IPiIW9COfE1tguNy', 'Employee');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblapplicants`
--
ALTER TABLE `tblapplicants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblexam_schedule`
--
ALTER TABLE `tblexam_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_announcement`
--
ALTER TABLE `tbl_announcement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_applicant_registration`
--
ALTER TABLE `tbl_applicant_registration`
  ADD PRIMARY KEY (`applicant_id`),
  ADD UNIQUE KEY `university_email` (`university_email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tbl_certification_requests`
--
ALTER TABLE `tbl_certification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_clearance_requests`
--
ALTER TABLE `tbl_clearance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_employee_registration`
--
ALTER TABLE `tbl_employee_registration`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_leave_requests`
--
ALTER TABLE `tbl_leave_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_message`
--
ALTER TABLE `tbl_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_permits`
--
ALTER TABLE `tbl_permits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_personnel_inquiries`
--
ALTER TABLE `tbl_personnel_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `tbl_service_requests`
--
ALTER TABLE `tbl_service_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_student_balances`
--
ALTER TABLE `tbl_student_balances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_id` (`student_id`);

--
-- Indexes for table `tbl_student_users`
--
ALTER TABLE `tbl_student_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `school_id` (`school_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tbl_unauthorized_access`
--
ALTER TABLE `tbl_unauthorized_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users_management`
--
ALTER TABLE `tbl_users_management`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblapplicants`
--
ALTER TABLE `tblapplicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblexam_schedule`
--
ALTER TABLE `tblexam_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tbl_announcement`
--
ALTER TABLE `tbl_announcement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_applicant_registration`
--
ALTER TABLE `tbl_applicant_registration`
  MODIFY `applicant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbl_certification_requests`
--
ALTER TABLE `tbl_certification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_clearance_requests`
--
ALTER TABLE `tbl_clearance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_contact`
--
ALTER TABLE `tbl_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_employee_registration`
--
ALTER TABLE `tbl_employee_registration`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_leave_requests`
--
ALTER TABLE `tbl_leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `tbl_message`
--
ALTER TABLE `tbl_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_permits`
--
ALTER TABLE `tbl_permits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_personnel_inquiries`
--
ALTER TABLE `tbl_personnel_inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_service_requests`
--
ALTER TABLE `tbl_service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_student_balances`
--
ALTER TABLE `tbl_student_balances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_student_users`
--
ALTER TABLE `tbl_student_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_unauthorized_access`
--
ALTER TABLE `tbl_unauthorized_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `tbl_users_management`
--
ALTER TABLE `tbl_users_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  ADD CONSTRAINT `tbl_reservation_ibfk_1` FOREIGN KEY (`applicant_id`) REFERENCES `tbl_applicant_registration` (`applicant_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
