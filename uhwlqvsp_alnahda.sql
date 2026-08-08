-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 08, 2026 at 05:26 AM
-- Server version: 8.4.6
-- PHP Version: 8.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uhwlqvsp_alnahda`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `phone2` varchar(50) DEFAULT NULL,
  `county` varchar(100) NOT NULL,
  `age` int NOT NULL,
  `preferredRole` varchar(100) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `languages` varchar(255) NOT NULL,
  `travelledSaudia` enum('yes','no') DEFAULT NULL,
  `returnYear` varchar(10) DEFAULT NULL,
  `durationYears` varchar(10) DEFAULT NULL,
  `finishedContract` enum('yes','no') DEFAULT NULL,
  `issueWithSponsor` enum('yes','no') DEFAULT NULL,
  `contractExplain` text,
  `deported` enum('yes','no') DEFAULT NULL,
  `exitVisa` enum('yes','no') DEFAULT NULL,
  `reentryVisa` enum('yes','no') DEFAULT NULL,
  `lebanon` enum('yes','no') DEFAULT NULL,
  `jordan` enum('yes','no') DEFAULT NULL,
  `medicalFit` enum('yes','no') DEFAULT NULL,
  `willingToReturn` enum('yes','no') DEFAULT NULL,
  `validPassport` enum('yes','no') DEFAULT NULL,
  `validConduct` enum('yes','no') DEFAULT NULL,
  `appointmentPreference` date DEFAULT NULL,
  `consent` tinyint(1) DEFAULT '0',
  `submitted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `fullname`, `email`, `weight`, `phone`, `phone2`, `county`, `age`, `preferredRole`, `gender`, `languages`, `travelledSaudia`, `returnYear`, `durationYears`, `finishedContract`, `issueWithSponsor`, `contractExplain`, `deported`, `exitVisa`, `reentryVisa`, `lebanon`, `jordan`, `medicalFit`, `willingToReturn`, `validPassport`, `validConduct`, `appointmentPreference`, `consent`, `submitted_at`) VALUES
(17, 'Peris Obiero', 'perisobieronyaboke@gmail.com', 68.00, '0704328004', '0704328004', 'Kisii', 35, 'HOUSEMAID', 'Female ', 'English and swahili ', 'yes', '2024', '2', 'yes', 'no', '', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-15', 1, '2025-10-26 00:24:33'),
(18, 'Ann', 'annnjokimuthoni513@gmail.com', 52.00, '0729806819', '0737030295', 'Murang&#039;a', 35, 'HOUSEMAID', 'Women ', 'English Arabic kishahilii', 'yes', '2025', '2', 'yes', 'no', '', 'no', 'yes', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'no', '2025-10-31', 1, '2025-10-26 00:47:18'),
(19, 'Mary njeri mwaura ', 'mwauramary591@gmail.com', 95.00, '0785 817184', '', 'Nakuru', 29, 'HOUSEMAID', 'Female ', 'English Swahili ', 'yes', '2025', '1.6', 'no', 'no', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-10-28', 1, '2025-10-26 08:53:33'),
(21, 'Maureen sindikha ', 'mourinesindikha26@gmail.com', 59.00, '0710969630', '0703940651', 'Bungoma', 34, 'HOUSEMAID', 'Female', 'English and kiswahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2026-01-05', 1, '2025-10-26 16:43:22'),
(22, 'Jane wanjiru kangthe ', 'kangethejane83@gmail.com', 95.00, '0796260681', '0736755663', 'Busia', 33, 'HOUSEMAID', ' female', ' English ', 'yes', '2025', '2', 'yes', 'yes', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-10-30', 1, '2025-10-26 17:36:34'),
(23, 'Jane wanjiru kangthe ', 'kangethejane83@gmail.com', 95.00, '0796260681', '0736755663', 'Busia', 33, 'HOUSEMAID', ' female', ' English ', 'yes', '2025', '2', 'yes', 'yes', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-10-30', 1, '2025-10-26 17:41:17'),
(24, 'Dorcus Engolan Ekamais', 'doreenekamais@gmail.com', 60.00, '0708619864', '0758510078', 'Nairobi City', 24, 'HOUSEMAID', 'Female ', 'English ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'yes', 'no', 'no', '2026-01-07', 1, '2025-10-26 18:48:17'),
(25, 'Synaidah gesare nyabwengi', 'synaidah12@com', 68.00, '0704313934', '0100175023', 'Kisii', 23, 'HOUSEMAID', 'Female ', 'English ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'yes', 'no', 'no', '2025-10-27', 1, '2025-10-26 21:12:07'),
(26, 'Christine Waithera', 'christine.waithera1@gmail.com', 58.00, '+254720919294', '+254720919294', 'Nairobi City', 35, 'HOUSEMAID', 'Female ', 'English, swahili, Basic Arabic ', 'no', '', '', '', '', '', '', '', '', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', '2025-11-12', 1, '2025-10-26 22:20:45'),
(27, 'Hyvonne nyakerarion ondieki', 'nyaksivyondie@gmail.com', 72.00, '0114060155', '0114060155', 'Kisii', 32, 'HOUSEMAID', 'Famale', 'Arabic,english and swahili', 'yes', '2023', '2', 'yes', 'no', '', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'no', 'no', '2025-11-23', 1, '2025-10-27 05:13:48'),
(28, 'BICHUWA IBRAHIM KEA ', 'bichuwaibrahimkea@gmail.com', 49.00, '0740874068', '0702211856', 'Kwale', 29, 'HOUSEMAID', 'Female ', 'Swahili/English ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2025-10-27', 1, '2025-10-27 05:33:19'),
(29, 'Terry wawira', 'nyagahterry10@gmail.com', 80.00, '0706818753', '', 'Tharaka-Nithi', 27, 'HOUSEMAID', 'Female', 'English kiswahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'yes', '2025-10-27', 1, '2025-10-27 07:30:54'),
(30, 'Stephanie jepchirchir', 'chiriestephanie@gmail.com', 64.00, '0727207285', '0712271048', 'Uasin Gishu', 25, 'HOUSEMAID', 'Female', 'Kiswahili', 'yes', '2024', '2', 'yes', 'no', '', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-22', 1, '2025-10-27 09:16:03'),
(31, 'Stephanie jepchirchir', 'chiriestephanie@gmail.com', 62.00, '0727207285', '0712271048', 'Uasin Gishu', 24, 'HOUSEMAID', 'Female', 'Kiswahili', 'yes', '2024', '2', 'yes', 'no', '', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-22', 1, '2025-10-27 09:18:18'),
(32, 'ROSE OSEBE OCHOGO ', 'Ivykaphy@gmail.com', 75.00, '0723084710', '0721380163', 'Kisii', 30, 'HOUSEMAID', 'Female ', 'English ', 'yes', '2025', '2', 'yes', 'no', '', 'no', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-10-30', 1, '2025-10-27 10:05:17'),
(33, 'Clara kesi muramba ', 'sammyjaji81@gmail.com', 61.00, '0794934845', '0112679315', 'Kilifi', 25, 'HOUSEMAID', 'Female', 'English ', 'yes', '2023', '2', 'yes', 'no', '', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-04', 1, '2025-10-27 15:25:37'),
(34, 'Rehema Ramadhan mohamed', 'rehemaramadhanmohamed@gmail.com', 78.00, '0707815120', '0100580390', 'Kilifi', 35, 'HOUSEMAID', 'Femal', 'English and kiswahili.', 'yes', '2011', '1', 'no', 'yes', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-17', 1, '2025-10-27 16:48:48'),
(35, 'Faith Ombeva', 'faxxyombe37@gmail.com', 75.00, '0716937163', '0738425598', 'Kakamega', 36, 'HOUSEMAID', 'Female', 'English,arabic', 'yes', '2016', '2', 'yes', 'no', '', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-20', 1, '2025-10-27 17:05:41'),
(36, 'Trizah wanjeri muthoni ', 'trizahmbashiah@gmail.com', 53.00, '0112326790', '0710565024', 'Murang&#039;a', 21, 'HOUSEMAID', 'Female ', 'English Swahili Arabic ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-03', 1, '2025-10-27 17:49:45'),
(37, 'Sharon mwero ', 'sweetshazy1995@gmail.com', 50.00, '0713050886', '0722390230', 'Mombasa', 24, 'HOUSEMAID', 'Female ', 'English, swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'no', 'no', 'no', '2025-10-31', 1, '2025-10-27 18:31:49'),
(38, 'Dora Andisi Alimu', 'doraalimu@gmail.com', 100.00, '0716126473', '', 'Kwale', 40, 'HOUSEMAID', 'Female ', 'English ', 'yes', '2024', '2', 'yes', 'no', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-30', 1, '2025-10-27 20:51:17'),
(39, 'Cynthia Naliaka wanyama', 'cynthianaliaka885@gmail.com', 57.00, '0797971958', '0735358463', 'Bungoma', 28, 'HOUSEMAID', 'Fimel', 'Swahili', 'yes', '2025', '2', 'yes', 'no', '', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', '2025-11-12', 1, '2025-10-27 21:46:19'),
(40, 'Moureen Thomas', 'moureenthomas58@gmail.com', 70.00, '254726062530', '254745594672', 'Kwale', 34, 'HOUSEMAID', 'Femsle', 'English and swahili', 'yes', '2024', '2', 'yes', 'no', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-06', 1, '2025-10-28 02:29:47'),
(41, 'Jackine saranwa ', 'jacklinejackyz98@gmail.com', 54.00, '0793575067', '', 'Nandi', 26, 'HOUSEMAID', 'Female ', 'English and kiswahili ', 'no', '', '', '', '', '', '', '', '', 'yes', 'no', 'yes', 'yes', 'no', 'no', '2025-12-08', 1, '2025-10-28 04:17:53'),
(42, 'Dessy Obuong', 'dessyobuong28@gmail.com', 65.00, '791 567562', '791 567562', 'Siaya', 28, 'HOUSEMAID', 'Female', 'English', 'yes', '2024', '4', 'yes', 'no', '', 'no', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-10-31', 1, '2025-10-28 05:14:07'),
(43, 'Rose Nekesa', 'irenewamawlwa2025@gmail.com', 89.00, '0111877284', '0734528037', 'Kisumu', 34, 'HOUSEMAID', 'Female', 'English and Arabic', 'yes', '2025', '4', 'yes', 'no', '', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-10-31', 1, '2025-10-28 05:31:04'),
(44, 'Josphine njeri wairagu ', 'allanjosphine76@gmail.com', 60.00, '0724244074', '0795956530', 'Murang&#039;a', 36, 'HOUSEMAID', 'Female ', 'English and Swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-10-31', 1, '2025-10-28 05:39:49'),
(45, 'Delphine kayaja', 'delphinedemesi@gmail.com', 80.00, '0799729593', '0704826701', 'Nairobi City', 23, 'HOUSEMAID', 'Female ', 'Swahili', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2025-11-06', 1, '2025-10-28 05:47:17'),
(46, 'Mildred Rahab Sitati', 'millysitati@gmail.com', 56.00, '0758990844', '0705692083', 'Bungoma', 33, 'HOUSEMAID', 'Female', 'English,swahili,Arabic', 'yes', '2020', '4', 'yes', 'no', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-20', 1, '2025-10-28 08:14:02'),
(47, 'Lucy Naisiae Supeyo', 'lucysupeyo276@gmail.com', 65.00, '0728633917', '', 'Kajiado', 35, 'HOUSEMAID', 'Female', 'English n swahili ', 'yes', '2021', '1', 'no', 'yes', '', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-10-31', 1, '2025-10-28 09:38:03'),
(48, 'Maureen ', 'maureennamono10@gmail.com', 72.00, '+254746155513', '', 'Kakamega', 28, 'HOUSEMAID', 'Female ', 'English ', 'yes', '2025', '3', 'yes', 'no', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-05', 1, '2025-10-28 10:38:52'),
(49, 'Evaline Migele', 'evalineakoth60@gmail.com', 75.00, '0769178352', '0769178352', 'Homa Bay', 29, 'HOUSEMAID', 'Caregiver ', 'English ', 'yes', '2022', '2', 'yes', 'no', '', 'no', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-10-31', 1, '2025-10-28 12:07:42'),
(50, 'Hyline bochaberi makori', 'hylinemakori@gmail.com', 58.00, '0769647745', '0719855291', 'Kisii', 26, 'HOUSEMAID', 'Female ', 'English ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-14', 1, '2025-10-28 12:12:25'),
(51, 'Evaline Migele', 'evalineakoth60@gmail.com', 75.00, '0769178352', '0799033711', 'Homa Bay', 29, 'HOUSEMAID', 'Caregiver ', 'English ', 'yes', '2022', '2', 'yes', 'no', '', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-10-31', 1, '2025-10-28 12:13:05'),
(52, 'Florence wanjiru mukuria ', 'floomukuzi@gmail.com', 75.00, '0741594559', '0781981205', 'Kiambu', 36, 'HOUSEMAID', 'Female ', 'Hi', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2025-10-31', 1, '2025-10-28 12:13:32'),
(53, 'June Ndinda', 'junendinda07@gmail.com', 57.00, '0728836287', '0728836287', 'Nairobi City', 31, 'HOUSEMAID', 'Female ', 'English, swahili, Arabic partialy', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'no', 'no', 'no', '2025-10-29', 1, '2025-10-28 12:23:53'),
(54, 'June Ndinda  Mutunga', 'junendinda07@gmail.com', 56.00, '0728836287', '', 'Nairobi City', 31, 'HOUSEMAID', 'Female ', 'English, swahili  and Arabic partially ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-10-29', 1, '2025-10-28 12:26:44'),
(55, 'Manaid nasiminyu sakwa', 'maggy@gmail.com', 70.00, '0799790630', '0104589948', 'Kakamega', 26, 'HOUSEMAID', 'Fameli', 'swahili', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'no', 'no', 'no', '2026-02-28', 1, '2025-10-28 12:51:14'),
(56, 'Florence wakesho', 'florencenguta1@gmail.com', 54.00, '0758597156', '0784972071', 'Taita-Taveta', 35, 'HOUSEMAID', 'Female', 'English', 'no', '', '', '', '', '', '', '', '', 'no', 'yes', 'yes', 'yes', 'yes', 'no', '2025-11-07', 1, '2025-10-28 14:16:36'),
(57, 'VALLARY MUTORO ', 'nekesavallary@gmail.com', 52.00, '0704569224', '', 'Mombasa', 27, 'HOUSEMAID', 'Female ', 'English, Arabic, Swahili', 'yes', '2025', '3', 'yes', 'no', '', 'no', 'no', 'no', 'yes', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-10', 1, '2025-10-28 15:21:07'),
(58, 'Nunu kutin abass', 'alumonunu@gmail.com1998', 64.00, '0742542707', '0103282422', 'Nairobi City', 26, 'HOUSEMAID', 'F', 'Kiswahil ', 'no', '', '', '', '', '', '', '', '', 'yes', 'no', 'yes', 'yes', 'no', 'yes', '2025-11-06', 1, '2025-10-28 17:27:26'),
(59, 'Rehema Mwalimu Dzogoro ', 'mwalimurehema065@gmail.com', 53.00, '0708193875', '0721338055', 'Kwale', 22, 'HOUSEMAID', 'Female ', 'English ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2025-11-15', 1, '2025-10-29 03:53:19'),
(60, 'Maureen Chepkwony ', 'maureenchepchemutai@gmail.com', 56.00, '0702467288', '0110697647', 'Kericho', 33, 'HOUSEMAID', 'Female', 'English, Swahili', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2025-11-10', 1, '2025-10-29 09:59:24'),
(61, 'Maureen Imbuhila', 'imbuhilamaureen56@gmail.com', 70.00, '0729094469', '0115595728', 'Nairobi City', 34, 'HOUSEMAID', 'Female ', 'English, Swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'yes', 'yes', 'yes', 'yes', 'no', '2025-11-07', 1, '2025-10-29 10:14:47'),
(62, 'Linet Kerubo Ogutu ', 'linnyogutu56@gmail.com', 98.00, '0742294169', '', 'Nyamira', 30, 'HOUSEMAID', 'Female ', 'English, Swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-20', 1, '2025-10-29 12:58:12'),
(63, 'Eunice wabwile ', 'euna.nanjala@gmail.com', 75.00, '0721402885', '0727427346', 'Bungoma', 31, 'HOUSEMAID', 'Female ', 'English ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'no', 'no', 'yes', '2025-12-12', 1, '2025-10-29 14:08:11'),
(64, 'Lucy Muthoni', 'lnmuthoni23@gmail.com', 63.00, '0702514550', '0702514550', 'Nairobi City', 29, 'HOUSEMAID', 'Female ', 'Swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2025-11-03', 1, '2025-10-29 14:41:39'),
(66, 'Cecilia ', 'cecilianyawira00@gmail.com', 60.00, '0742198444', '0738095587', 'Nyeri', 27, 'HOUSEMAID', 'Female ', 'English and kiswahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'yes', '2025-11-03', 1, '2025-10-30 15:42:04'),
(67, 'Tabitha wambui maina ', 'tabithawambui070728509@gmail.com', 70.00, '0707285909', '0707840445', 'Nairobi City', 36, 'HOUSEMAID', 'Female ', 'Arabic/English ', 'yes', '2024', '3', 'yes', 'yes', '', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-07', 1, '2025-11-06 04:09:37'),
(68, 'Tabitha wambui maina ', 'tabithawambui070728509@gmail.com', 70.00, '+254707285909', '+254707840445', 'Nairobi City', 36, 'HOUSEMAID', 'Female ', 'Arabic/English ', 'yes', '2024', '3', 'yes', 'yes', '', 'yes', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-07', 1, '2025-11-06 04:16:57'),
(69, 'Esther watu kimani', 'keishaestherkimani@gmail.com', 100.00, '0114631150', '0764631157', 'Nairobi City', 32, 'HOUSEMAID', 'Female', 'English,swahili', 'yes', '2024', '5', 'yes', 'no', '', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-28', 1, '2025-11-11 04:22:15'),
(70, 'Nathan Ombati', 'nathanombati3@gmail.com', 78.00, '+254706184555', '+254739551881', 'Nairobi City', 41, 'HOUSEMAID', 'Male', 'English, Swahili', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'no', 'yes', 'yes', '2025-11-17', 1, '2025-11-13 00:14:57'),
(71, 'Darwin Sprina Nkirote', 'nkirotesprina@gmail.com', 69.00, '+254722444316', '', 'Nairobi City', 32, 'HOUSEMAID', 'Female', 'English', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-20', 1, '2025-11-14 20:34:58'),
(72, 'AAAAAAAAAAAAAAAAAAAAAA', 'alnahdaagency@gmail.com', 555.00, '705594282', '5555', 'Kwale', 55, 'HOUSEMAID', '55', '55', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'no', 'no', 'no', '0000-00-00', 1, '2025-11-16 10:45:10'),
(73, 'Natasha Akinyi Ahomo ', 'natashaahomo2004@gmail.com', 80.00, '0113148302', '0724996626', 'Nairobi City', 21, 'HOUSEMAID', 'Female ', 'English Swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2026-01-20', 1, '2025-11-18 17:48:01'),
(74, 'Catherine kamene ', 'catherinekamene05@gmail.com', 76.00, '0792792858', '0792792858', 'Mombasa', 24, 'HOUSEMAID', 'Female ', 'English and Swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'yes', '2026-04-02', 1, '2025-11-20 20:59:50'),
(75, 'Pauline ajema demesi', 'Paulinedemesi29@gmail.com', 65.00, '0770 586067', '0723018216', 'Kiambu', 29, 'HOUSEMAID', 'Female ', 'English, arabic ', 'yes', '2019', '2', 'no', 'no', '', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2025-11-26', 1, '2025-11-22 13:53:11'),
(76, 'Pauline ajema demesi', 'Paulinedemesi29@gmail.com', 65.00, '0770 586067', '0723018216', 'Kiambu', 29, 'HOUSEMAID', 'Female ', 'English, Arabic ', 'yes', '2021', '2', 'no', 'no', '', 'yes', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-26', 1, '2025-11-22 13:58:06'),
(77, 'Esther watu kimani', 'keishaestherkimani@gmail.com', 100.00, '0114631150', '', 'Nairobi City', 32, 'HOUSEMAID', 'Female', 'English,swahili ', 'yes', '2024', '5', 'yes', 'no', '', 'no', 'no', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2025-11-28', 1, '2025-11-25 07:08:03'),
(78, 'Margaret Wambui', 'wambuiwangari20@gmail.com', 80.00, '0718184772', '', 'Nairobi City', 29, 'HOUSEMAID', 'Female ', 'English, kiswahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2026-01-08', 1, '2026-01-07 08:09:40'),
(79, 'Caroline Onyango ', 'onyangocaroline94@gmail.com', 60.00, '0795804347', '', 'Nairobi City', 29, 'HOUSEMAID', 'Female ', 'English, basic arabic', 'yes', '2020', '1.5', 'no', 'yes', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2026-01-09', 1, '2026-01-08 11:44:51'),
(80, 'Grace muthoni mwangi ', 'gracemuthoni3131@gmail.com', 70.00, '0704635535', '0704635535', 'Nakuru', 35, 'HOUSEMAID', 'Female ', 'Swahili ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'no', 'no', 'yes', 'yes', '2026-01-30', 1, '2026-01-11 19:31:18'),
(81, 'Ruth wanjiru Muchiri ', 'ruthj9432@gmail.com', 70.00, '0706818761', '0780305299', 'Nakuru', 30, 'HOUSEMAID', 'Female ', 'English, Kiswahili, Arabic ', 'yes', '2023', '3', 'yes', 'no', '', 'no', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2026-01-19', 1, '2026-01-12 11:06:58'),
(82, 'Ruth wanjiru Muchiri ', 'ruthj9432@gmail.com', 70.00, '0706818761', '0780305299', 'Nakuru', 30, 'HOUSEMAID', 'Female ', 'English,Kiswahili, Arabic ', 'yes', '2023', '3', 'yes', 'no', '', 'no', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2026-01-19', 1, '2026-01-13 14:54:40'),
(83, 'Ruth wanjiru Muchiri ', 'ruthj9432@gmail.com', 70.00, '0706818761', '0780305299', 'Nakuru', 30, 'HOUSEMAID', 'Female ', 'English Kiswahili Arabic ', 'yes', '2023', '3', 'yes', 'no', '', 'no', 'yes', 'yes', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2026-01-19', 1, '2026-01-13 14:57:35'),
(84, 'Mourine achola ', 'mourineachola499@gmail.com', 61.00, '+254706775304', '', 'Busia', 32, 'HOUSEMAID', 'Female ', 'English,swahili', 'yes', '2025', '2', 'yes', 'no', '', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', 'yes', '2026-01-19', 1, '2026-01-16 15:46:31'),
(85, 'Rose mawia kathina ', 'karosekarose814@gmail.com', 75.00, '0726296580', '0795007387', 'Kitui', 36, 'HOUSEMAID', 'Female ', 'English and KISWAHILI ', 'yes', '2023', '2', 'yes', 'no', '', 'no', 'no', 'no', 'no', 'no', 'no', 'yes', 'yes', 'yes', '2026-01-19', 1, '2026-01-17 13:42:09'),
(86, 'Lucy wanjiku maina', 'isaacmacharia39@gmail.com', 60.00, '0111322366', '', 'Nairobi City', 25, 'HOUSEMAID', 'Female', 'English,kiswahili,arabic', 'yes', '2024', '2', 'yes', 'no', '', 'no', 'yes', 'no', 'no', 'no', 'yes', 'no', 'yes', 'yes', '2026-03-19', 1, '2026-03-07 04:57:59'),
(87, 'Salman khan', 'salman20.sk6600@gmail.com', 78.00, '00966575185873', '+966 56 963 9753', 'Busia', 33, 'HOUSEMAID', 'Male ', 'English Arabic urdu &amp;pushto ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2026-03-31', 1, '2026-03-29 07:54:13'),
(88, 'Betty imbuhila', 'bettyimbuhila@gmail.com', 62.00, '0113808626', '', 'Nairobi City', 24, 'HOUSEMAID', 'Female', 'English', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2026-04-25', 1, '2026-04-10 06:49:41'),
(89, 'Betty Imbuhila', 'bettyimbuhila@gmail.com', 62.00, '0113808626', '', 'Embu', 24, 'HOUSEMAID', 'female', 'English', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2026-04-25', 1, '2026-04-10 06:51:28'),
(90, 'Jane Gisemba', 'havillaho96@gmail.com', 61.00, '254703882224', '254704508000', 'Nairobi City', 30, 'HOUSEMAID', 'Female', 'Swahili,arabica', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'no', '2026-04-22', 1, '2026-04-19 15:39:47'),
(91, 'Sharon kidayu', 'sharonkidayu79@gmail.com', 58.00, '0740458620', '', 'Vihiga', 30, 'HOUSEMAID', 'Female ', 'English swahili', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'yes', 'no', '2026-08-04', 1, '2026-05-21 13:32:09'),
(92, 'Brenda Wanjiku ', 'kariukibrenda30@gmail.com', 69.00, '+254 735 919016', '', 'Nairobi City', 24, 'HOUSEMAID', 'Female ', 'English, Swahili, German ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'no', 'yes', 'yes', '2026-06-25', 1, '2026-06-12 19:09:41'),
(93, 'Sheila Nangila Nyongesa', 'snyongesa023@gmail.com', 65.00, '0728869644', '0100186611', 'Nairobi City', 21, 'HOUSEMAID', 'Female ', 'English ', 'no', '', '', '', '', '', '', '', '', 'no', 'no', 'yes', 'yes', 'no', 'yes', '2026-07-25', 1, '2026-07-15 11:22:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
