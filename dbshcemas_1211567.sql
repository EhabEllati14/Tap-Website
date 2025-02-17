-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 12:22 PM
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
-- Database: `tap`
--
CREATE DATABASE IF NOT EXISTS `tap` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tap`;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `AddressID` int(11) NOT NULL AUTO_INCREMENT,
  `FlatHouseNo` varchar(50) NOT NULL,
  `Street` varchar(255) NOT NULL,
  `City` varchar(100) NOT NULL,
  `Country` varchar(100) NOT NULL,
  PRIMARY KEY (`AddressID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(1, '1234', 'ramallah12', 'betunia', 'palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(2, '1234', 'ramallah12', 'betunia', 'palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(3, '1212', 'alQuds15', 'Senjel', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(4, '1234', 'ramallah12', 'betunia', 'palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(5, '22332', 'yaffa', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(6, '11111', 'ram', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(7, '11111', 'ram', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(8, '0098', 'yaffa', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(9, '0098', 'yaffa', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(10, '0098', 'yaffa', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(11, '0098', 'yaffa', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(12, '0098', 'yaffa', 'Ramallah', 'Palestine');
INSERT INTO `address` (`AddressID`, `FlatHouseNo`, `Street`, `City`, `Country`) VALUES(13, '55679', 'jaffa street ramallah', 'Ramallah', 'Palestine');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `project_id` varchar(10) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `project_description` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `budget` decimal(10,2) NOT NULL CHECK (`budget` > 0),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL CHECK (`end_date` > `start_date`),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `team_leader_id` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('AAWW-55555', 'BUG REPORT', 'BUG REPORT', 'sandy', 5500.00, '2025-01-01', '2025-01-19', '2025-01-07 09:28:34', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABAA-12345', 'BUGSS', 'BUGSADS', 'RANA', 5454.00, '2025-01-01', '2025-01-13', '2025-01-07 10:01:57', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-00000', 'ewd', 'wdas', 'dwax', 33333.00, '2025-01-02', '2025-01-16', '2025-01-06 21:24:12', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-11111', 'NEW BUG ', 'new bug in the code', 'lana', 2000.00, '2025-01-15', '2025-02-27', '2025-01-06 20:40:44', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-12121', 'Meeting with stackeholder', 'this is meeting ', 'maher', 40000.00, '2025-01-08', '2025-01-28', '2025-01-07 06:55:25', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-12333', 'bugs', 'bughre', 'cews', 12345.00, '2025-01-02', '2025-01-21', '2025-01-06 21:19:53', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-12344', 'bug22', 'there is bug in ui/ux', 'rula', 9000.00, '2025-01-01', '2025-01-14', '2025-01-06 21:09:07', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-12345', 'hhhhh', 'hello', 'ehab', 1.00, '2025-01-02', '2025-01-22', '2025-01-06 20:36:31', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-22222', 'NEW BUG ', 'new bug in the code', 'lana', 2000.00, '2025-01-15', '2025-01-29', '2025-01-06 20:38:18', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-33333', 'NEW BUG ', 'new bug in the code', 'lana', 2000.00, '2025-01-15', '2025-03-27', '2025-01-06 20:48:48', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-44444', 'NEW BUG ', 'new bug in the code', 'lana', 2000.00, '2025-01-15', '2025-03-27', '2025-01-06 20:49:28', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-55555', 'NEW BUG ', 'new bug in the code', 'lana', 2000.00, '2025-01-15', '2025-03-27', '2025-01-06 20:50:47', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-77777', 'NEW BUG 2', 'new bug in the code', 'lana2', 2002.00, '2025-01-15', '2025-03-27', '2025-01-06 20:55:09', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ABCD-99999', 'Bug 4', 'bug report for new software system', 'emad', 3000.00, '2025-01-07', '2025-01-28', '2025-01-06 20:59:39', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ADDD-32321', 'BUGS', '2 BUGS IN THE SYSTEM IN VISUAL BUG', 'tala', 4400.00, '2025-01-09', '2025-01-29', '2025-01-07 09:27:11', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('ASSD-55667', 'Andriod Project', 'Car Maintenance', 'Sana rahi', 4000.00, '2025-02-13', '2025-02-28', '2025-01-13 15:24:41', '6434101431');
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('DDDD-12345', 'BUGS', 'BUG REPORT FOR OUR SYSTEM', 'RADI', 3000.00, '2025-01-02', '2025-01-27', '2025-01-07 09:35:28', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('HQQH-12125', 'Bugs', 'Bugssss', 'rami', 32320.00, '2025-01-02', '2025-01-26', '2025-01-07 10:00:38', NULL);
INSERT INTO `projects` (`project_id`, `project_title`, `project_description`, `customer_name`, `budget`, `start_date`, `end_date`, `created_at`, `team_leader_id`) VALUES('WWRE-99901', 'Python project ', 'This Project for AI', 'tamer', 50000.00, '2025-01-30', '2025-04-22', '2025-01-15 10:58:38', '6434101431');

-- --------------------------------------------------------

--
-- Table structure for table `project_documents`
--

DROP TABLE IF EXISTS `project_documents`;
CREATE TABLE IF NOT EXISTS `project_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(10) NOT NULL,
  `document_title` varchar(255) NOT NULL,
  `document_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_documents`
--

INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(1, 'ABCD-44444', 'doc1', 'uploads/1736196568_Screenshot 2025-01-06 222110.png');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(2, 'ABCD-55555', 'doc1', 'uploads/1736196647_Screenshot 2025-01-06 222110.png');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(3, 'ABCD-00000', 'docs', 'uploads/Project One.docx');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(4, 'ABCD-12121', 'metting _doc', 'TAP/Project One.docx');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(5, 'ADDD-32321', 'bug report', 'C:/xampp/htdocs/TAP/DOC/Project One.docx');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(6, 'AAWW-55555', 'report', 'C:/xampp/htdocs/TAP/DOCS/Project One.docx');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(7, 'DDDD-12345', 'REPORT', 'C:/xampp/htdocs/TAP/DOCS/comp334-project-1241 (2).pdf');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(8, 'ABAA-12345', 'REPORT SCREEN', 'DOCS/photo database mainpage.png');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(9, 'ABAA-12345', 'REPORT', 'DOCS/Titorial+6 (1).docx');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(10, 'ASSD-55667', 'description', 'DOCS/Project2Report.pdf');
INSERT INTO `project_documents` (`id`, `project_id`, `document_title`, `document_path`) VALUES(11, 'WWRE-99901', 'python report', 'DOCS/Project2Report.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `project_id` varchar(10) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `effort` float NOT NULL,
  `status` enum('Pending','Active','In Progress','Completed') NOT NULL,
  `priority` enum('Low','Medium','High') NOT NULL,
  `progress` int(11) DEFAULT 0,
  PRIMARY KEY (`task_id`),
  KEY `project_id` (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(1, 'Bug one', 'Bug one is describe the UI/UX BUGS', 'ABCD-12121', '2025-01-09', '0000-00-00', 4, 'In Progress', 'Medium', 14);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(2, 'Second Bug', 'Bug Report create', 'ABCD-77777', '2025-01-16', '2025-01-28', 15, 'Active', 'Medium', 0);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(3, 'Third Bug', 'here is visual bug', 'ABAA-12345', '2025-01-02', '0000-00-00', 44, 'In Progress', 'High', 15);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(4, 'Iphone Bug', 'Software need update', 'ABCD-77777', '2025-01-16', '0000-00-00', 70, 'In Progress', 'Medium', 72);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(5, 'Software Updates', 'This task for adding new features of iphone software 18.', 'ABCD-77777', '2025-01-16', '2025-01-23', 4, 'Pending', 'High', 0);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(6, 'add the login page', 'This Task we will create the login page', 'WWRE-99901', '2025-01-31', '0000-00-00', 66, 'In Progress', 'Medium', 50);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(7, 'WhatsApp Api ', 'This Task to connect the WhatsApp Api In our Project ', 'WWRE-99901', '2025-01-31', '0000-00-00', 44, 'In Progress', 'High', 41);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(8, 'Flutter Project', 'Flutter Gym App', 'ASSD-55667', '2025-02-15', '2025-02-12', 77, 'Pending', 'Medium', 0);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(9, 'AI TASK', 'AI TASK BY USING THE TENSORFLOW', 'ASSD-55667', '2025-02-13', '2025-02-28', 70, 'In Progress', 'High', 36);
INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`, `progress`) VALUES(10, 'Newewewew', 'efvszv', 'ASSD-55667', '2025-02-14', '2025-02-20', 66, 'In Progress', 'Low', 48);

-- --------------------------------------------------------

--
-- Table structure for table `task_allocate`
--

DROP TABLE IF EXISTS `task_allocate`;
CREATE TABLE IF NOT EXISTS `task_allocate` (
  `allocation_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `role` enum('Developer','Designer','Tester','Analyst','Support') NOT NULL,
  `contribution_percentage` float NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`allocation_id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_allocate`
--

INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(1, 4, 2634056099, 'Tester', 77, '2025-01-22', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(2, 4, 3680186032, 'Developer', 20, '2025-01-22', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(3, 2, 3680186032, 'Developer', 80, '2025-01-29', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(4, 2, 3680186032, 'Tester', 5, '2025-01-24', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(6, 2, 3680186032, 'Tester', 15, '2025-01-30', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(7, 1, 2634056099, 'Developer', 23, '2025-01-14', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(8, 3, 3680186032, 'Designer', 5, '2025-01-10', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(9, 6, 3680186032, 'Analyst', 44, '2025-01-31', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(10, 7, 3680186032, 'Tester', 10, '2025-01-31', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(11, 9, 2634056099, 'Developer', 30, '2025-02-15', NULL);
INSERT INTO `task_allocate` (`allocation_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `end_date`) VALUES(12, 10, 2634056099, 'Developer', 50, '2025-02-19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `UserID` bigint(20) NOT NULL,
  `IDNumber` char(9) DEFAULT NULL CHECK (octet_length(`IDNumber`) = 9),
  `Name` varchar(255) NOT NULL,
  `AddressID` int(11) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `EmailAddress` varchar(255) NOT NULL,
  `Telephone` char(10) NOT NULL,
  `Role` enum('Manager','Project Leader','Team Member') NOT NULL,
  `Qualification` varchar(255) NOT NULL,
  `Skills` text NOT NULL,
  `Username` varchar(50) NOT NULL CHECK (octet_length(`Username`) between 6 and 13),
  `Password` varchar(12) NOT NULL CHECK (octet_length(`Password`) between 8 and 12),
  `ProfilePicture` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `EmailAddress` (`EmailAddress`),
  UNIQUE KEY `Username` (`Username`),
  UNIQUE KEY `IDNumber` (`IDNumber`),
  KEY `AddressID` (`AddressID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `IDNumber`, `Name`, `AddressID`, `DateOfBirth`, `EmailAddress`, `Telephone`, `Role`, `Qualification`, `Skills`, `Username`, `Password`, `ProfilePicture`, `CreatedAt`, `UpdatedAt`) VALUES(2604152554, '112233445', 'Fadi', 3, '1994-11-16', 'fadiKhalil@gmail.com', '9911223344', 'Manager', 'PhD', 'PHP, JS, CSS, WEBSERVICE', 'fadi1122', 'fadi12345', 'ProfilesPictures/user.png', '2025-01-06 12:00:00', '2025-01-10 12:10:04');
INSERT INTO `users` (`UserID`, `IDNumber`, `Name`, `AddressID`, `DateOfBirth`, `EmailAddress`, `Telephone`, `Role`, `Qualification`, `Skills`, `Username`, `Password`, `ProfilePicture`, `CreatedAt`, `UpdatedAt`) VALUES(2634056099, '111333226', 'ghazal daras', 7, '2002-04-24', 'ghazal@gmail.com', '2222222222', 'Team Member', 'PhD', 'engineer, swim , dance', 'ghazal123', 'ghazal12345', 'ProfilesPictures/user.png', '2025-01-08 09:07:14', '2025-01-10 12:10:04');
INSERT INTO `users` (`UserID`, `IDNumber`, `Name`, `AddressID`, `DateOfBirth`, `EmailAddress`, `Telephone`, `Role`, `Qualification`, `Skills`, `Username`, `Password`, `ProfilePicture`, `CreatedAt`, `UpdatedAt`) VALUES(3680186032, '332200991', 'Jody salameh', 5, '2002-05-21', 'jodyb@gmail.com', '1111111111', 'Team Member', 'PhD', 'design, draw', 'jody123', 'jody12345', 'ProfilesPictures/user.png', '2025-01-08 09:05:25', '2025-01-10 12:10:04');
INSERT INTO `users` (`UserID`, `IDNumber`, `Name`, `AddressID`, `DateOfBirth`, `EmailAddress`, `Telephone`, `Role`, `Qualification`, `Skills`, `Username`, `Password`, `ProfilePicture`, `CreatedAt`, `UpdatedAt`) VALUES(6434101431, '123456789', 'Ehab', 2, '2004-11-24', 'ellatiehab@gmail.com', '0569722661', 'Project Leader', 'PhD', 'Swimming, driving ', 'ehab123', 'ehab12345', 'ProfilesPictures/user.png', '2025-01-06 11:51:58', '2025-01-10 14:12:50');
INSERT INTO `users` (`UserID`, `IDNumber`, `Name`, `AddressID`, `DateOfBirth`, `EmailAddress`, `Telephone`, `Role`, `Qualification`, `Skills`, `Username`, `Password`, `ProfilePicture`, `CreatedAt`, `UpdatedAt`) VALUES(7395361420, '556655665', 'Rama', 8, '2000-11-15', 'rama@gmail.com', '1122112211', '', 'Bachelors', 'PHP, WEB, CSS', 'rama123', 'rama12345', NULL, '2025-01-10 18:41:13', '2025-01-10 18:41:13');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_documents`
--
ALTER TABLE `project_documents`
  ADD CONSTRAINT `project_documents_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`);

--
-- Constraints for table `task_allocate`
--
ALTER TABLE `task_allocate`
  ADD CONSTRAINT `task_allocate_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_allocate_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
