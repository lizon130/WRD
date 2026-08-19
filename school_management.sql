-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2025 at 08:20 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `course_id` varchar(256) NOT NULL,
  `user_id` varchar(256) NOT NULL,
  `type` varchar(256) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL DEFAULT current_timestamp(),
  `status` varchar(256) NOT NULL,
  `company_id` varchar(256) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `course_id`, `user_id`, `type`, `date`, `time`, `status`, `company_id`, `created_at`, `updated_at`) VALUES
(1, '671b6fd39cd8c-prod-88757483059884922', '671dd964701e1-user-87459872651545202', 'Attendance', '2024-10-30', '17:34:19', 'Present', '671b35c259c6e-comp-37685793406052083', '2024-10-30 05:34:19', '2024-10-30 05:34:19');

-- --------------------------------------------------------

--
-- Table structure for table `attribute`
--

CREATE TABLE `attribute` (
  `id` varchar(40) NOT NULL,
  `title` varchar(256) NOT NULL,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id` varchar(40) NOT NULL,
  `title` varchar(256) NOT NULL,
  `slug` text DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `show_home` int(11) DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `title`, `slug`, `image`, `status`, `show_home`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('671b44bde543e-brnd-38244098845180351', 'Nex Academy', NULL, NULL, 1, 1, NULL, '2024-10-25 01:11:57', '2024-10-25 01:11:57');

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `id` varchar(40) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `session_id` varchar(256) DEFAULT NULL,
  `user_id` varchar(40) DEFAULT NULL,
  `product_id` varchar(40) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` float(16,2) NOT NULL,
  `discount_amount` float(16,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `session_id`, `user_id`, `product_id`, `qty`, `unit_price`, `discount_amount`, `created_at`, `updated_at`) VALUES
(3, '', '6469ec780d0e3-user-62923393878666109', '64661670e0b01-prod-98907306728823268', 1, 50000.00, 15000.00, '2023-06-02 06:50:39', '2023-06-02 06:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `catalog`
--

CREATE TABLE `catalog` (
  `id` varchar(40) NOT NULL,
  `title` varchar(256) NOT NULL,
  `slug` text DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'catalogue',
  `short_description` text DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL,
  `brand_id` varchar(40) DEFAULT NULL,
  `product_id` varchar(40) DEFAULT NULL,
  `file` varchar(256) NOT NULL,
  `thumbnail` varchar(256) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catalog`
--

INSERT INTO `catalog` (`id`, `title`, `slug`, `type`, `short_description`, `category_id`, `brand_id`, `product_id`, `file`, `thumbnail`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('648182240ec63-ctlg-93460437567543138', '2017 Lang Workholding Catalogue', 'catalogue-lang-2017-lang-workholding-catalogue', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '1', NULL, 'Invoice_WD2024011062542.pdf', '170290332365803e1bda139Screenshot 2023-12-18 183558.png', 1, NULL, '2023-06-08 01:24:20', '2024-03-20 23:59:59'),
('6543583c549bd-ctlg-53271021388606610', 'Lang Workholding', 'catalogue-lang-lang-workholding', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '1', NULL, '170619610765b27c8b0f255LangTechnik_Brochure_EN_workholding_MTS.pdf', '16989123156543583be1ec6Screenshot 2023-11-02 140024.png', 1, NULL, '2023-11-02 15:05:16', '2024-03-01 00:41:43'),
('654375e6034ed-ctlg-19037940351209661', 'OK-VISE E-catalogue', 'catalogue-ok-vise-ok-vise-e-catalogue', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '653b46614148c-brnd-14428085574324353', NULL, '1698919910654375e600e2dok-vise_fc_2016v1eng_low-MTS.pdf', '1698919910654375e600b37Screenshot_28.png', 1, NULL, '2023-11-02 17:11:50', '2024-03-01 00:41:43'),
('654382323a6de-ctlg-77484143179822082', 'PINTEC E-Catalogue', 'catalogue-pintec-pintec-e-catalogue', 'catalogue', NULL, '6577e14198114-ctgr-75362801972008934', '653b40ed2bbbd-brnd-56085618647099342', NULL, '170534317965a578cba1af2pintec-MTS-product_catalog_2021-03_s.pdf', '170551707265a82010bab6apintec Thumbnail.png', 1, NULL, '2023-11-02 18:04:18', '2024-03-01 00:41:43'),
('65439c458bdc4-ctlg-13778079566131462', 'VICE in Row E-Catalogue', 'catalogue-ok-vise-vice-in-row-e-catalogue', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '653b46614148c-brnd-14428085574324353', NULL, '169892973365439c458b963E-catalogue-MTS-HRV-VICES.pdf', '169892973365439c458b6f2Screenshot_30.png', 1, NULL, '2023-11-02 19:55:33', '2024-03-01 00:41:43'),
('65439d0aaeed2-ctlg-53751412980993867', 'AR-FILTRAZIONI E-Catalogue', 'catalogue-ar-filtrazioni-ar-filtrazioni-e-catalogue', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '653b4579b3e3b-brnd-38786264451941358', NULL, '169892993065439d0aad5d1AIR-CLEANERS-for-OIL-MIST-SMOKE-DUST-on-MACHINE-TOOLS (1).pdf', '169892993065439d0aad0d4Screenshot_1.png', 1, NULL, '2023-11-02 19:58:50', '2024-03-01 00:41:43'),
('6544e2a5d74ec-ctlg-20974480645031358', 'Workholding ZeroPoint', 'catalogue-lang-workholding-zeropoint', 'catalogue', NULL, '65115ed015bd9-ctgr-97049127326775050', '1', NULL, '2022-09_LangTechnik_Brochure_EN_zeropoint_MTS.pdf', '16990132856544e2a5d6ea2Screenshot_4.png', 1, NULL, '2023-11-03 19:08:05', '2024-03-01 00:41:43'),
('6544e422a9e27-ctlg-68026343640751131', 'Lang Automation', 'catalogue-lang-lang-automation', 'catalogue', NULL, '65115ef3de69d-ctgr-19924755263429886', '1', NULL, '4-2022_LangTechnik_Brochure_EN_automation_MTS.pdf', '16990136666544e422a96c3Screenshot_5.png', 1, NULL, '2023-11-03 19:14:26', '2024-03-01 00:41:43'),
('6544e6b12b92c-ctlg-81483923346776037', 'MTS-SPD-ELECTRO-PERMANENT FOR GRIDING', 'catalogue-spd-mts-spd-electro-permanent-for-griding', 'catalogue', NULL, '65328299b15dd-ctgr-27302527109501547', '653b407814c5d-brnd-30399065672704514', NULL, 'MTS-SPD-ELECTRO-PERMANENT FOR GRIDING.pdf', '16990143216544e6b12b3b6Screenshot_6.png', 1, NULL, '2023-11-03 19:25:21', '2024-03-01 00:41:43'),
('6544e77376ac6-ctlg-47396202602134081', 'MTS-SPD-Electropremanent-Lifting-Battery-Magnets-SB01', 'catalogue-spd-mts-spd-electropremanent-lifting-battery-magnets-sb01', 'catalogue', NULL, '65328299b15dd-ctgr-27302527109501547', '653b407814c5d-brnd-30399065672704514', NULL, '16990145156544e7737680aMTS-SPD-Electropremanent-Lifting-Battery-Magnets-SB01.pdf', '16990145156544e77376537Screenshot_7.png', 1, NULL, '2023-11-03 19:28:35', '2024-03-01 00:41:43'),
('6544e8708258a-ctlg-20746053215675215', 'MTS-SPD-Permanent Lifting', 'catalogue-spd-mts-spd-permanent-lifting', 'catalogue', NULL, '65328471c311f-ctgr-62324388771040640', '653b407814c5d-brnd-30399065672704514', NULL, '16990147686544e87081a9bMTS-SPD-Permanent_Lifting-_Magnets-1.pdf', '16990147686544e870818c5Screenshot_8.png', 1, NULL, '2023-11-03 19:32:48', '2024-03-01 00:41:43'),
('6544e96bedca8-ctlg-68756934007664813', 'MTS-SPD-Radial-ENG- ITA-2009', 'catalogue-spd-mts-spd-radial-eng-ita-2009', 'catalogue', NULL, '65328471c311f-ctgr-62324388771040640', '653b407814c5d-brnd-30399065672704514', NULL, 'MTS-SPD-Radial-ENG- ITA-2009.pdf', '16990150196544e96bed4b3Screenshot_9.png', 1, NULL, '2023-11-03 19:36:59', '2024-03-01 00:41:43'),
('6544eaa193923-ctlg-85114356836069262', 'MTS-SPD-TRETEL', 'catalogue-spd-mts-spd-tretel', 'catalogue', NULL, '65328471c311f-ctgr-62324388771040640', '653b407814c5d-brnd-30399065672704514', NULL, 'MTS-SPD-TRETEL_ EN-1.pdf', '16990153296544eaa193275Screenshot_10.png', 1, NULL, '2023-11-03 19:42:09', '2024-03-01 00:41:43'),
('6544eb2ec6a84-ctlg-68873311284751741', 'SPD-MTS-Griding chucks', 'catalogue-spd-spd-mts-griding-chucks', 'catalogue', NULL, '65328471c311f-ctgr-62324388771040640', '653b407814c5d-brnd-30399065672704514', NULL, 'SPD-MTS-Griding chucks E-catalogue.pdf', '16990154706544eb2ec60c3Screenshot_11.png', 1, NULL, '2023-11-03 19:44:30', '2024-03-01 00:41:43'),
('65453c31cdc46-ctlg-91208777296206854', 'Milling Electro-Permanent System', 'catalogue-spd-milling-electro-permanent-system', 'catalogue', NULL, NULL, '653b407814c5d-brnd-30399065672704514', NULL, '169903620965453c31ccbf7MTS-SPD-MILLING-ELECTRO-PERMANENT-SYSTEM.pdf', '169903620965453c31cca35milling.png', 1, NULL, '2023-11-04 01:30:09', '2024-03-01 00:41:43'),
('654b6f1a6d434-ctlg-32249218384059248', 'AutoGrip - Tuning Solutions E-Catalogue', 'catalogue-autogrip-autogrip-tuning-solutions-e-catalogue', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '653b40cbb988a-brnd-61212735378470921', NULL, 'AUTOGRIP_CATALOGUE 2020.09EN_MTS (1).pdf', '1699442458654b6f1a6ce5aScreenshot_12.png', 1, NULL, '2023-11-08 18:20:58', '2024-03-01 00:41:43'),
('654b7097d2ddf-ctlg-55065409998957338', 'BRISC Permanent Lifting Magnets', 'catalogue-spd-brisc-permanent-lifting-magnets', 'catalogue', NULL, '65328471c311f-ctgr-62324388771040640', '653b407814c5d-brnd-30399065672704514', NULL, 'PLM-01-EN-MTS-2014.pdf', '1699442839654b7097d25beScreenshot_13.png', 1, NULL, '2023-11-08 18:27:19', '2024-03-01 00:41:43'),
('6580486a9f993-ctlg-90137212817494492', 'Lang 2023 New Products', 'catalogue-lang-lang-2023-new-products', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '1', NULL, '17029059626580486a9edc32023.08_LANG_MTS_News Brochure_compressed.pdf', '17029059626580486a9eaabScreenshot 2023-12-18 191350.png', 1, NULL, '2023-12-18 20:26:02', '2024-03-01 00:41:43'),
('658dde6933952-ctlg-49509704599213041', 'Robotic Electro Permanent Magnetic Lifting Form', 'form-spd-robotic-electro-permanent-magnetic-lifting-form', 'form', 'Form for Robotic Electro Permanent Magnetic Lifting Application', '65328471c311f-ctgr-62324388771040640', '653b407814c5d-brnd-30399065672704514', NULL, '1703796329658dde6933794ROBOTIC ELECTROPERMANENT LIFTING MODULE QUESTIONNAIRE-x.pdf', '1703796649658ddfa920da5ROBOTIC-ELECTROPERMANENT-LIFTING thumbnail.jpg', 1, NULL, '2023-12-29 03:45:29', '2024-03-01 00:41:43'),
('65b4117ac4e92-ctlg-66660379458832547', 'Lang - Technique de serrage', 'catalogue-lang-lang-technique-de-serrage', 'catalogue', NULL, '65115eaa217bc-ctgr-80906052148787198', '1', NULL, '170629990065b411fc4e83d2022-04_lang_catalogue-workholding_FR_MTS_compressed.pdf', '170629977065b4117a5d527placeholder.jpg', 0, NULL, '2024-01-27 03:09:30', '2024-03-01 00:41:43'),
('65b4129e6d12f-ctlg-48110515754229209', 'Lang - Automatisation', 'catalogue-lang-lang-automatisation', 'catalogue', NULL, '65115ef3de69d-ctgr-19924755263429886', '1', NULL, '170630006265b4129e48fc52022-04_lang_catalogue-automation_FR_MTS.pdf', '170630006265b4129e48e51placeholder.jpg', 0, NULL, '2024-01-27 03:14:22', '2024-03-01 00:41:43'),
('65b41331e98a2-ctlg-24190092657078653', 'Lang - Système Point Zéro', 'catalogue-lang-lang-systeme-point-zero', 'catalogue', NULL, '65115ed015bd9-ctgr-97049127326775050', '1', NULL, '170630020965b41331d5d482022-09_lang_katalog-nullpunkt-inhalt_FR_vrz_MTS_compressed.pdf', '170630020965b41331d5be6placeholder.jpg', 0, NULL, '2024-01-27 03:16:49', '2024-03-01 00:41:43'),
('65cf745e96427-ctlg-71271051597248528', 'Pintec Power', 'catalogue-pintec-pintec-power', 'catalogue', NULL, '65aaba8fc0b16-ctgr-83259238588199759', '653b40ed2bbbd-brnd-56085618647099342', '65aaa948ce6c2-prod-77168313302307535', '170809455865cf745e960eeChristian-Bewer-Pintec-Power-By-Kostyrka-Catalogue-MTS.pdf', '170809487865cf759ea6c11Christian-Bewer-Pintec-Power-By-Kostyrka-Catalogue-MTS.jpg', 1, NULL, '2024-02-16 21:42:38', '2024-03-01 00:41:43');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` varchar(40) NOT NULL,
  `title` varchar(256) NOT NULL,
  `slug` text DEFAULT NULL,
  `is_parent` int(11) NOT NULL DEFAULT 0,
  `parent_category` varchar(40) DEFAULT NULL,
  `alternate_name` varchar(256) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `show_home` int(11) NOT NULL DEFAULT 0,
  `short_number` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `title`, `slug`, `is_parent`, `parent_category`, `alternate_name`, `value`, `image`, `status`, `show_home`, `short_number`, `ancestor_id`, `company_id`, `created_at`, `updated_at`) VALUES
('671b37e61e7c8-ctgr-89990712782176204', 'Cyber Security', NULL, 1, NULL, NULL, NULL, NULL, 1, 0, 1, NULL, '671b35c259c6e-comp-37685793406052083', '2024-10-25 00:17:10', '2024-10-25 00:21:59');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` varchar(40) NOT NULL,
  `user_id` varchar(40) NOT NULL,
  `type` varchar(256) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `contact_name` varchar(256) DEFAULT NULL,
  `phone_number` varchar(256) DEFAULT NULL,
  `department` varchar(256) DEFAULT NULL,
  `vat_no` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `post_code` varchar(256) DEFAULT NULL,
  `state` varchar(256) DEFAULT NULL,
  `city` varchar(256) DEFAULT NULL,
  `country` varchar(256) DEFAULT NULL,
  `website_url` text DEFAULT NULL,
  `discount_type` varchar(10) DEFAULT NULL,
  `discount` decimal(16,2) DEFAULT NULL,
  `website_logo` varchar(256) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`company_id`, `user_id`, `type`, `name`, `contact_name`, `phone_number`, `department`, `vat_no`, `email`, `address`, `post_code`, `state`, `city`, `country`, `website_url`, `discount_type`, `discount`, `website_logo`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('650293876e6bf-comp-68607265728322501', '650293876e078-user-92625359878879540', 'Partner', 'Nex Academy', 'Abusaid Sheikh', '01628292015', 'Software', '10395823', 'developer.abusaid@gmail.com', 'Dhanmondi', '1205', 'Dhanmondi', 'Dhaka', 'Bangladesh', NULL, NULL, NULL, '1695189491650a89f304026Screenshot_3.png', 1, NULL, '2023-09-14 12:00:55', '2024-10-25 00:05:07'),
('671b35c259c6e-comp-37685793406052083', '671b35c254034-user-22672471868631509', 'Partner', 'ICT Olympiad Bangladesh', 'Shariar Khan', '01407100900', NULL, NULL, 'hello.ictob@gmail.com', '161/2, 2nd floor, Dr. Kudrat E Khuda Road', '1205', 'Dhanmondi', 'Dhaka', 'Bangladesh', 'https://ictolympiadbangladesh.com', NULL, NULL, '1729836482671b35c258b7dlogo_1717430449.png', 1, NULL, '2024-10-25 00:08:02', '2024-10-25 00:08:15'),
('677e1e21129b2-comp-83510458362510380', '677e1e2111194-user-79855310802867056', '4', 'Tech Solutions Ltd', 'John Doe', '0987654321', 'IT', 'VAT123456', 'info@techsolutions.com', '123 Tech Street', '12345', 'California', 'San Francisco', 'USA', 'https://techsolutions.com', NULL, NULL, NULL, 1, NULL, '2025-01-08 06:41:37', '2025-01-08 06:41:37'),
('677e1ecc74eff-comp-39796689566411977', '677e1ecc73c30-user-65403586637187087', '4', 'Tillman Forbes LLC', 'Chantale Bennett', 'Lowe Vang Traders', 'Harum aute et perspi', 'Ullamco dicta volupt', 'pyviqew@mailinator.com', 'Atque itaque dolores', 'At eveniet commodo', 'Quibusdam repudianda', 'Perferendis nisi qui', 'Iste laboris quaerat', 'https://www.nitebegacyqavy.us', NULL, NULL, NULL, 0, NULL, '2025-01-08 06:44:28', '2025-01-08 06:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `company_product`
--

CREATE TABLE `company_product` (
  `id` int(11) NOT NULL,
  `company_id` varchar(40) NOT NULL,
  `partner` varchar(256) DEFAULT NULL,
  `category_id` varchar(40) NOT NULL,
  `subcategory_id` varchar(40) DEFAULT NULL,
  `product_id` varchar(40) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(16,2) NOT NULL,
  `discount_type` varchar(10) DEFAULT NULL,
  `discount_price` decimal(16,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_product`
--

INSERT INTO `company_product` (`id`, `company_id`, `partner`, `category_id`, `subcategory_id`, `product_id`, `quantity`, `price`, `discount_type`, `discount_price`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(1, '6507f19d12ec7-comp-85056884246794701', 'Sajal Ahmed', '65115eaa217bc-ctgr-80906052148787198', '65115f5184454-ctgr-43218855093955634', '65166e4f3db24-prod-47730432815412307', 1, 80.00, 'percent', 20.00, 1, NULL, '2023-10-04 16:46:19', '2023-10-19 06:22:13'),
(2, '651bb96572a2b-comp-67642734584339893', 'D.M. Anamul', '65115eaa217bc-ctgr-80906052148787198', '65115f5184454-ctgr-43218855093955634', '6512ae158742b-prod-64060163992968803', 5, 1.00, 'percent', 5.00, 1, NULL, '2024-02-06 01:23:33', '2024-02-06 01:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `toll_free` varchar(40) DEFAULT NULL,
  `fax` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `google_map` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `is_default` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `title`, `phone`, `toll_free`, `fax`, `email`, `address`, `google_map`, `status`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'BRAMPTON - CANADA', '+1 (905) 790-8640', '+1 (877) 687-7253', '+1 (905) 790-3740', 'info@machinetoolsolutions.ca', '8 Automatic Rd. Building C, Unit #6 Brampton, Ontario L6S 5N4, Canada', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2881.8348115236654!2d-79.71248922387981!3d43.7555269710975!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x882b3d102112c0cb%3A0xb974a2fdb36870dc!2sMachine%20Tool%20Solutions%20Ltd.!5e0!3m2!1sen!2sbd!4v1684412636926!5m2!1sen!2sbd\" width=\"100%\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 1, 1, '2023-06-12 23:29:49', '2023-06-12 23:29:49'),
(2, 'MIAMI - USA', '', '+1 (877) 687-7253', '', 'info@mts-sale.com', '671 W 18th St, suite #1-2 Hialeah, FL 33010, USA', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d29416975.590997808!2d-80.295629!3d25.838542!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9ba6bb796c1b9%3A0xb38724c3ecaeb403!2s671%20W%2018th%20St%2C%20Hialeah%2C%20FL%2033010%2C%20USA!5e0!3m2!1sen!2sca!4v1685688152079!5m2!1sen!2sca\" width=\"100%\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 1, 0, '2023-06-12 23:39:15', '2023-06-12 23:39:15'),
(3, 'SÃO PAULO - BRAZIL', '+55 (11) 4029-2677', '', '', 'info@mts-sale.com', 'Rua Roque Lazazzera, 136 – Jd. N.Sra. Monte Serrat Cep 13323.300 – Salto/SP', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d59864178.73705847!2d-46.595299!3d-23.682412!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce597d462f58ad%3A0x1e5241e2e17b7c17!2sState%20of%20S%C3%A3o%20Paulo%2C%20Brazil!5e0!3m2!1sen!2sus!4v1685688372252!5m2!1sen!2sus\" width=\"100%\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"></iframe>', 1, 0, '2023-06-12 23:40:34', '2023-06-12 23:44:44');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` varchar(40) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `code` varchar(256) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL,
  `product_id` varchar(40) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `field_name` varchar(256) NOT NULL,
  `field_for` varchar(11) NOT NULL DEFAULT 'product',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_fields`
--

INSERT INTO `custom_fields` (`id`, `field_name`, `field_for`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Modules', 'product', 1, '2024-10-25 04:52:46', '2024-10-25 04:52:46'),
(7, 'Topics', 'product', 1, '2024-10-25 04:52:55', '2024-10-25 04:52:55'),
(8, 'Benefits', 'product', 1, '2024-10-25 04:53:31', '2024-10-25 04:53:31'),
(9, 'Instructor', 'product', 1, '2024-10-25 08:58:41', '2024-10-25 08:58:41');

-- --------------------------------------------------------

--
-- Table structure for table `custom_option`
--

CREATE TABLE `custom_option` (
  `id` int(11) NOT NULL,
  `option_for` varchar(256) NOT NULL DEFAULT 'exam',
  `type` varchar(40) NOT NULL DEFAULT 'custom value',
  `is_filter` int(11) NOT NULL DEFAULT 0,
  `value` varchar(256) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `intro_video` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `language_code` varchar(256) DEFAULT NULL,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_option`
--

INSERT INTO `custom_option` (`id`, `option_for`, `type`, `is_filter`, `value`, `title`, `details`, `short_description`, `intro_video`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(1, 'exam', 'question type', 0, 'Multiple Choice', 'Multiple Choice', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:09:04', '2024-11-28 06:09:04'),
(2, 'exam', 'question type', 0, 'Single Choice', 'Single Choice', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:09:04', '2024-11-28 06:09:04'),
(3, 'exam', 'difficulty level', 0, 'Easy', 'Easy', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:12:02', '2024-11-28 06:12:02'),
(4, 'exam', 'difficulty level', 0, 'Moderate', 'Moderate', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:12:02', '2024-11-28 06:12:02'),
(5, 'exam', 'difficulty level', 0, 'Hard', 'Hard', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:12:55', '2024-11-28 06:12:55'),
(6, 'exam', 'difficulty level', 0, 'Advanced', 'Advanced', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:12:55', '2024-11-28 06:12:55'),
(7, 'exam', 'exam purpose', 0, 'Practice', 'Practice', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:15:01', '2024-11-28 06:15:01'),
(8, 'exam', 'exam purpose', 0, 'Mock', 'Mock', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:15:01', '2024-11-28 06:15:01'),
(9, 'exam', 'exam purpose', 0, 'Exam', 'Exam', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:16:07', '2024-11-28 06:16:07'),
(10, 'exam', 'media type', 0, 'Test-Based', 'Test-Based', NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-28 06:18:17', '2024-11-28 06:18:17');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_mode`
--

CREATE TABLE `delivery_mode` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_mode`
--

INSERT INTO `delivery_mode` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Online', 1, '2025-01-09 11:04:04', '2025-01-09 11:07:27'),
(2, 'Offline', 1, '2025-01-09 11:05:27', '2025-01-09 11:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` text DEFAULT NULL,
  `referance_id` varchar(256) DEFAULT NULL,
  `referance_type` varchar(256) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `custom_data` text DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `exam_purpose` int(11) DEFAULT NULL,
  `difficulty_level` int(11) DEFAULT NULL,
  `segmentation` text DEFAULT NULL,
  `is_login_required` int(11) NOT NULL DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL,
  `pass_marks` double(16,2) DEFAULT NULL,
  `no_of_questions` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `is_generated` int(11) NOT NULL DEFAULT 0,
  `instant_result` int(11) NOT NULL DEFAULT 0,
  `segmentwise_question` text DEFAULT NULL,
  `time_limit` int(11) NOT NULL,
  `result_published` int(11) NOT NULL DEFAULT 0,
  `thumbnail` varchar(256) DEFAULT NULL,
  `gallery` varchar(256) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `slug`, `referance_id`, `referance_type`, `company_id`, `custom_data`, `name`, `description`, `short_description`, `type`, `exam_purpose`, `difficulty_level`, `segmentation`, `is_login_required`, `start_date`, `end_date`, `start_time`, `end_time`, `pass_marks`, `no_of_questions`, `status`, `is_generated`, `instant_result`, `segmentwise_question`, `time_limit`, `result_published`, `thumbnail`, `gallery`, `created_at`, `updated_at`) VALUES
(21, 'sample-exam-for-testing', NULL, NULL, '671b35c259c6e-comp-37685793406052083', NULL, 'Sample exam for testing', '<h3><strong>Exam Instructions</strong></h3><p>Welcome to the [Exam Name]! Please read the following instructions carefully before starting the exam.</p><hr><h4><strong>General Instructions</strong></h4><ol><li>Ensure you have a stable internet connection throughout the exam. Any disruptions may affect your submission.</li><li>The exam must be completed within the allocated time limit of <strong>[X minutes]</strong>. Submissions after the time limit may not be accepted or may incur penalties.</li><li>You are required to answer <strong>[Number of Questions]</strong> questions. Each question carries <strong>[Marks per Question]</strong> marks unless stated otherwise.</li><li>Make sure to review your answers before submitting, as some exams may not allow editing after submission.</li><li><strong>Do not refresh or navigate away from the exam page</strong> during the test.</li></ol><hr><h4><strong>Answering Questions</strong></h4><ul><li><strong>Single-Choice Questions:</strong> Select the most appropriate option from the given choices.</li><li><strong>Multiple-Choice Questions:</strong> Choose all applicable options. Partial marks may be awarded if specified.</li><li><strong>Short Answer Questions:</strong> Type your response in the provided text box.</li><li><strong>Essay Questions:</strong> Use the rich text editor to write your answers clearly and concisely.</li></ul><hr><h4><strong>Scoring and Results</strong></h4><ol><li>Each correct answer will earn the designated marks.</li><li><strong>Negative Marking:</strong> Incorrect answers may result in a deduction of <strong>[X marks]</strong> per wrong attempt (if applicable).</li><li>Unanswered questions will not be penalized.</li><li>Instant results will be shown upon submission (if applicable).</li></ol><hr><h4><strong>Code of Conduct</strong></h4><ul><li>This is a closed-book exam. <strong>Use of external materials, devices, or assistance is strictly prohibited.</strong></li><li>Maintain academic integrity. Any instance of cheating, plagiarism, or unethical behavior will lead to disqualification.</li><li>Ensure you remain logged into the exam system. <strong>Multiple login attempts from different devices will be flagged.</strong></li></ul><hr><h4><strong>Exam Timer</strong></h4><ul><li>The timer will start as soon as you begin the exam.</li><li>You will receive a <strong>[Warning Time: e.g., 5-minute]</strong> alert before the end of the exam.</li></ul><hr><p>By clicking \"Start Exam,\" you agree to comply with the above rules and conditions.</p><p>Good luck, and do your best! 🍀</p>', NULL, '9', NULL, 3, '[\"6\"]', 0, '2024-12-01', '2024-12-04', '10:00', '20:00', 15.00, 20, 1, 1, 0, '[\"20\"]', 20, 0, NULL, NULL, '2024-12-02 09:55:13', '2024-12-02 09:55:13'),
(22, 'quiz', NULL, 'course', '671b35c259c6e-comp-37685793406052083', NULL, 'quiz', '<p><br></p>', NULL, '9', NULL, 3, '[\"6\"]', 0, '2024-12-02', '2024-12-02', '09:00', '22:00', 10.00, 20, 1, 1, 1, '[\"20\"]', 15, 0, NULL, NULL, '2024-12-02 10:19:58', '2024-12-02 10:27:14'),
(23, 'test-exam', NULL, NULL, '671b35c259c6e-comp-37685793406052083', NULL, 'Test Exam', '<p><strong>Online Exam Instructions</strong></p><ol><li><p><strong>Preparation Before the Exam</strong>:</p><ul><li>Ensure your device (PC, laptop, or tablet) is fully charged or connected to a power source.</li><li>Check your internet connection for stability.</li><li>Have any allowed materials (e.g., ID, calculator) ready.</li></ul></li><li><p><strong>Login and Access</strong>:</p><ul><li>Log in to the exam platform at least 15 minutes before the scheduled time.</li><li>Use the provided username and password to access the exam.</li></ul></li><li><p><strong>Exam Environment</strong>:</p><ul><li>Choose a quiet, well-lit room free from distractions.</li><li>Keep your desk clear of unauthorized materials.</li><li>Avoid the presence of other people in the room.</li></ul></li><li><p><strong>Conduct During the Exam</strong>:</p><ul><li>Do not switch tabs or windows unless allowed.</li><li>Avoid any form of communication with others during the exam.</li><li>Follow all instructions on the screen carefully.</li></ul></li><li><p><strong>Technical Guidelines</strong>:</p><ul><li>Use a compatible browser as specified (e.g., Chrome, Firefox).</li><li>If you encounter technical issues, contact the technical support team immediately.</li><li>Do not refresh or close the browser unless instructed.</li></ul></li><li><p><strong>Time Management</strong>:</p><ul><li>The timer will begin as soon as you start the exam.</li><li>Keep track of remaining time displayed on the screen.</li></ul></li><li><p><strong>Proctoring (if applicable)</strong>:</p><ul><li>Allow access to your camera and microphone for monitoring.</li><li>Ensure your face remains visible throughout the exam.</li></ul></li><li><p><strong>Submission</strong>:</p><ul><li>Review your answers before submission.</li><li>Submit the exam within the allocated time.</li></ul></li><li><p><strong>Rules and Consequences</strong>:</p><ul><li>Any suspicion of cheating or misconduct may result in disqualification.</li><li>Follow all exam rules as violations will be reported.</li></ul></li></ol><p><strong>Note</strong>: Contact the exam administrator for any clarifications before starting. Good luck!</p>', NULL, '9', NULL, 3, '[\"6\"]', 0, '2024-12-01', '2024-12-03', '10:30', '20:30', 20.00, 30, 1, 1, 1, '[\"30\"]', 35, 1, NULL, NULL, '2024-12-02 12:32:54', '2024-12-02 15:29:37'),
(24, 'howard-and-richards-trading', 'Henson Contreras Traders', 'user', '671b35c259c6e-comp-37685793406052083', NULL, 'Howard and Richards Trading', 'Inventore vero tenet.', NULL, '9', NULL, 6, '[\"6\"]', 0, '2024-05-22', '2024-12-04', '08:55', '05:32', 10.00, 10, 1, 1, 1, '[\"10\"]', 10, 0, NULL, NULL, '2024-12-03 05:12:10', '2024-12-03 05:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `exam_questions`
--

CREATE TABLE `exam_questions` (
  `id` int(11) NOT NULL,
  `exam_id` varchar(40) NOT NULL,
  `company_id` varchar(256) DEFAULT NULL,
  `custom_data` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `question_type` varchar(40) NOT NULL DEFAULT 'single choice',
  `short_description` text DEFAULT NULL,
  `marks` float(16,2) NOT NULL DEFAULT 0.00,
  `user_id` varchar(40) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_question_options`
--

CREATE TABLE `exam_question_options` (
  `id` int(11) NOT NULL,
  `question_id` varchar(40) NOT NULL,
  `title` text NOT NULL,
  `is_correct` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `id` varchar(40) NOT NULL,
  `user_id` varchar(40) DEFAULT NULL,
  `request_by` varchar(256) NOT NULL,
  `date` date NOT NULL,
  `address_information` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `total_price` float(16,2) DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`id`, `user_id`, `request_by`, `date`, `address_information`, `note`, `total_price`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('65030512eca6c-inqr-38307876139591716', '1', 'toto', '2023-09-14', '{\"company\":\"test\",\"name\":\"toto\",\"phone\":\"1966658890\",\"email\":\"jhone@gmail.com\",\"address\":\"34\\/6, BUETA zad Staff Quarter, Dhakeshwari Road, Lalbagh.\",\"post_code\":\"1234\",\"city\":\"Dhaka\",\"state\":\"Dhaka\",\"country\":\"bd\"}', '', 0.00, 0, NULL, '2023-09-14 20:05:22', '2023-09-14 20:05:22'),
('6507f3f8d89dd-inqr-66198428221317596', '6507f19d126ab-user-31532510060520109', 'Sajal Ahmed', '2023-09-18', '{\"company\":\"NexKraft Limited\",\"name\":\"Sajal Ahmed\",\"phone\":\"+8801753487017\",\"email\":\"sajal.nexkraft@gmail.com\",\"address\":\"Gulshan, Dhaka\",\"post_code\":\"1276\",\"city\":\"Dhaka\",\"state\":\"Dhaka\",\"country\":\"Bangladesh\"}', 'Hello', 0.00, 1, NULL, '2023-09-18 13:53:44', '2023-11-03 20:38:15'),
('651a75e40de1c-inqr-89796292094775081', '651a751089463-user-53125218649401490', 'Allen Shapon', '2023-10-02', '{\"company\":\"shapon@gmail.com\",\"name\":\"Allen Shapon\",\"phone\":\"01748399287\",\"email\":\"shapon@gmail.com\",\"address\":\"166, sukrabad, Kolabagan\",\"post_code\":\"1205\",\"city\":\"Dhaka\",\"state\":\"Dhanmondi\",\"country\":\"Bangladesh\"}', 'adadasd', 0.00, 0, NULL, '2023-10-02 14:48:52', '2023-10-02 14:48:52'),
('651bb83a48846-inqr-14802699011063674', '', 'Anamul', '2023-10-03', '{\"company\":\"NexKraft Limited\",\"name\":\"Anamul\",\"phone\":\"01911637103\",\"email\":\"dmanamul@gmail.com\",\"address\":\"Ibrahimpur Bazar, Kafrul, Mirpur, Dhaka.\",\"post_code\":\"1206\",\"city\":\"Dhaka\",\"state\":\"Dhaka\",\"country\":\"Bangladesh\"}', '', 0.00, 0, NULL, '2023-10-03 13:44:10', '2023-10-03 13:44:10'),
('6544d76ae6611-inqr-66163207943126353', '', 'Sajal Ahmed', '2023-11-03', '{\"company\":\"NexKraft Limited\",\"name\":\"Sajal Ahmed\",\"phone\":\"+8801753487017\",\"email\":\"sajal@nexkraft.com\",\"address\":\"Dhanmondi, Dhaka\",\"post_code\":\"1276\",\"city\":\"Dhaka\",\"state\":\"Dhaka\",\"country\":\"Bangladesh\"}', '/\'Hi', 0.00, 1, NULL, '2023-11-03 18:20:10', '2023-11-03 18:24:18'),
('654be1bfbc039-inqr-99985563019444128', '', 'test', '2023-11-08', '{\"company\":\"Test\",\"name\":\"test\",\"phone\":\"1234567890\",\"email\":\"abilash.canada@gmail.om\",\"address\":\"asdasdasdasd\",\"post_code\":\"asdasdsad\",\"city\":\"asdasda\",\"state\":\"asdsaddasd\",\"country\":\"asdsdad\"}', 'asdsdad', 0.00, 0, NULL, '2023-11-09 02:30:07', '2023-11-09 02:30:07'),
('654c8bde60030-inqr-86000565539202332', '6507f19d126ab-user-31532510060520109', 'vajin@mailinator.com', '2023-11-09', '{\"company\":\"NexKraft Limited\",\"name\":\"Sajal Ahmed\",\"phone\":\"123123\",\"email\":\"sajal@nexkraft.com\",\"address\":\"Dhanmondi, Dhaka\",\"post_code\":\"1276\",\"city\":\"Dhaka\",\"state\":\"Dhaka\",\"country\":\"Bangladesh\"}', NULL, 0.00, 0, NULL, '2023-11-09 14:35:58', '2023-11-09 14:35:58'),
('654e5da6672d0-inqr-62577593629923203', '', 'asdasd', '2023-11-10', '{\"company\":\"asdsd\",\"name\":\"asdasd\",\"phone\":\"sadasda\",\"email\":\"sadsad@gmail.com\",\"address\":\"asdasdas\",\"post_code\":\"sadasdas\",\"city\":\"asdsasd\",\"state\":\"asdasdasd\",\"country\":\"asdasasd\"}', '', 0.00, 0, NULL, '2023-11-10 23:43:18', '2023-11-10 23:43:18'),
('65a8135da7d29-inqr-66452821431768998', '', 'margaret', '2024-01-17', '{\"company\":\"margaret\",\"name\":\"margaret\",\"phone\":\"905-790-8640\",\"email\":\"margaret@machinetoolsolutions.ca\",\"address\":\"1233 m\",\"post_code\":\"l7e 3k2\",\"city\":\"bolton\",\"state\":\"ont\",\"country\":\"canada\"}', NULL, 0.00, 0, NULL, '2024-01-18 00:50:21', '2024-01-18 00:52:12'),
('65c469b71be27-inqr-26322750667412809', '', 'Luke Thievin', '2024-02-08', '{\"company\":\"Promac Manufacturing\",\"name\":\"Luke Thievin\",\"phone\":\"2508560455\",\"email\":\"lukethievin@promacgroup.ca\",\"address\":\"2940 Jacob Rd\",\"post_code\":\"V9L 6W4\",\"city\":\"Duncan\",\"state\":\"Bc\",\"country\":\"Canada\"}', '', 0.00, 0, NULL, '2024-02-08 12:42:15', '2024-02-08 12:42:15'),
('65c97fe8c6e5b-inqr-30910550488506101', '', 'Hayden draycott', '2024-02-12', '{\"company\":\"Knifemaker Direct\",\"name\":\"Hayden draycott\",\"phone\":\"780 966 7121\",\"email\":\"hayden@knifemakerdirect.ca\",\"address\":\"571 Evansborough Way NW\",\"post_code\":\"T3P 0M7\",\"city\":\"Calgary\",\"state\":\"Alberta\",\"country\":\"Canada\"}', 'Hi I am interested in some lang quick point plates for my mini mill. Would like to know pricing and any relevant info. Thanks,\r\n\r\nHayden', 0.00, 0, NULL, '2024-02-12 09:18:16', '2024-02-12 09:18:16');

-- --------------------------------------------------------

--
-- Table structure for table `inquiry_product`
--

CREATE TABLE `inquiry_product` (
  `id` int(11) NOT NULL,
  `inquiry_id` varchar(40) NOT NULL,
  `product_id` varchar(40) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(16,2) DEFAULT 0.00,
  `company_id` varchar(40) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subtotal` float(16,2) DEFAULT 0.00,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiry_product`
--

INSERT INTO `inquiry_product` (`id`, `inquiry_id`, `product_id`, `quantity`, `unit_price`, `company_id`, `status`, `ancestor_id`, `created_at`, `updated_at`, `subtotal`, `note`) VALUES
(54, '65030512eca6c-inqr-38307876139591716', '647d8195cc6a3-prod-81909702939051446', 2, NULL, NULL, 0, NULL, '2023-09-14 20:05:22', '2023-09-14 20:05:22', 0.00, ''),
(55, '6507f3f8d89dd-inqr-66198428221317596', '6502da0310b77-prod-38172722525938284', 2, NULL, NULL, 0, NULL, '2023-09-18 13:53:44', '2023-09-18 13:53:44', 0.00, ''),
(56, '651a75e40de1c-inqr-89796292094775081', '65166e4f3db24-prod-47730432815412307', 1, NULL, NULL, 0, NULL, '2023-10-02 14:48:52', '2023-10-02 14:48:52', 0.00, ''),
(57, '651bb83a48846-inqr-14802699011063674', '6512a9f583617-prod-61220542803544136', 1, NULL, NULL, 0, NULL, '2023-10-03 13:44:10', '2023-10-03 13:44:10', 0.00, ''),
(58, '6544d76ae6611-inqr-66163207943126353', '65166e4f3db24-prod-47730432815412307', 2, 80.00, NULL, 0, NULL, '2023-11-03 18:20:10', '2023-11-03 18:20:10', 160.00, ''),
(59, '654be1bfbc039-inqr-99985563019444128', '652e7735de671-prod-71933675794283013', 1, 1.00, NULL, 0, NULL, '2023-11-09 02:30:07', '2023-11-09 02:30:07', 1.00, ''),
(60, '654c8bde60030-inqr-86000565539202332', '6512abc7c47f9-prod-31537002788820495', 0, 1.00, NULL, 0, NULL, '2023-11-09 14:35:58', '2023-11-09 14:35:58', 0.00, NULL),
(61, '654e5da6672d0-inqr-62577593629923203', '652ecf9f22340-prod-99192543148409913', 1, 1.00, NULL, 0, NULL, '2023-11-10 23:43:18', '2023-11-10 23:43:18', 1.00, ''),
(68, '65a8135da7d29-inqr-66452821431768998', '652d3178803fd-prod-75010499802922756', 1, 1.00, NULL, 0, NULL, '2024-01-18 00:52:26', '2024-01-18 00:52:26', 1.00, 'b/o 3 weeks'),
(69, '65a8135da7d29-inqr-66452821431768998', '652ecf9f22340-prod-99192543148409913', 1, 1.00, NULL, 0, NULL, '2024-01-18 00:52:26', '2024-01-18 00:52:26', 1.00, NULL),
(70, '65a8135da7d29-inqr-66452821431768998', '65646675e03c0-prod-21472396152195655', 1, 1.00, NULL, 0, NULL, '2024-01-18 00:52:26', '2024-01-18 00:52:26', 1.00, NULL),
(71, '65c469b71be27-inqr-26322750667412809', '656095286ae83-prod-46194702022146119', 2, 1.00, NULL, 0, NULL, '2024-02-08 12:42:15', '2024-02-08 12:42:15', 2.00, ''),
(72, '65c97fe8c6e5b-inqr-30910550488506101', '652bee1baec1c-prod-62505787411199928', 1, 1.00, NULL, 0, NULL, '2024-02-12 09:18:16', '2024-02-12 09:18:16', 1.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` varchar(40) NOT NULL,
  `title` varchar(256) NOT NULL,
  `message` text DEFAULT NULL,
  `sender` varchar(40) DEFAULT NULL,
  `receiver` varchar(40) DEFAULT NULL,
  `date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `slug` text DEFAULT NULL,
  `publish_date` date NOT NULL,
  `category` varchar(256) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `media` varchar(256) NOT NULL,
  `file` varchar(256) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `publish_date`, `category`, `short_description`, `description`, `url`, `media`, `file`, `gallery_images`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(5, 'LANG presents new stamping unit and a complete new series of 5-Axis Vises', 'news-lang-presents-new-stamping-unit-and-a-complete-new-series-of-5-axis-vises', '2023-09-16', 'News', 'Next generation of the Makro•Grip® series\r\nFS - two letters that stand for major upgrades in the workholding technology of LANG Technik. With a revised stamping unit, a new type of stamping serration, as well as a complete, additional series of 5-Axis Vises, LANG continues to develop its \"original\".', '<h2 style=\"box-sizing: inherit; line-height: 1.4; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; width: auto;\">Next generation of the Makro•Grip® series</h2><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\"><span style=\"box-sizing: inherit;\">FS&nbsp;</span>- two letters that stand for major upgrades in the workholding technology of LANG Technik. With a revised stamping unit, a new type of stamping serration, as well as a complete, additional series of 5-Axis Vises, LANG continues to develop its \"original\". The s</span>tamping technology has been setting the bar for top-notch quality in 5-axis machining through its form-fit clamping philosophy for years.&nbsp;Now, Makro•Grip® FS is taking this to a whole new level with an even more impressive milling performance.<span style=\"box-sizing: inherit;\">&nbsp;The abbreviation \"FS\" stands for fully serrated / full serration and describes the new, continuous holding serration on the clamping jaws of the 5-Axis Vise, which apart from this remain identical in their design. &nbsp;The new form-fit between the continuous holding serration and the matching contour in the pre-stamped workpiece blank increases the holding force by up to 60%, depending on the material and stamping depth. For machining, this means: Even more reliability and safety in workpiece clamping. This in turn allows higher cutting performance and ensures faster milling processes.</span></p><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\">&nbsp;</p><h2 style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; line-height: 1.4; width: auto;\">Stamping unit and trolley with many new features</h2><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">The new stamping units with adapted stamping serrations impress with numerous new features and make operation even easier and more effective. For example, the process of setting the stamping pressure can be significantly accelerated thanks to the new stamping depth gauge. The setting is now data-based by reading off the dial gauge instead of visually checking the workpiece. The new centering unit also makes it child\'s play to insert the workpiece blank exactly in the center. The newly designed stamping trolley - as a version without or with t-slot plate - is now able to support three stamping units at the same time. By optimizing the stamping base bodies, the new stamping unit versions now cover even more vise lengths and clamping ranges.</span></p><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\">&nbsp;</p><h2 style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; line-height: 1.4; width: auto;\">Full compatibility with the previous Makro•Grip® series</h2><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">The new 5-Axis Vises of the FS series are available in all previously known sizes and models and also in a new mini version - the Makro•Grip® micro. The FS series will initially run alongside the well-known Makro•Grip® series, but is expected to replace it in the medium term due to its performance advantages.</span></p><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">Customers who already use the Makro•Grip® 5-Axis Vise in production can continue to use it with the new stamping unit. Pre-stamped workpiece blanks with a continuous contour can be held by form-fit in the previous holding serration of the 5-Axis Vise without any problems - even at even higher holding forces than before. What applies to the 5-Axis Vise in terms of compatibility also applies to the stamping unit. For those customers who decide in favor of the new FS vises but are already working with a stamping unit in their machine shop, a conversion set is available. This enables the use of new types of stamping jaws on existing stamping units.</span></p><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">The new FS Series of 5-Axis Vises is available now. Stamping units can also be ordered and will be available from late fall. Conversion sets for existing stamping units are already in stock.</span></p>', '#', '17018388726570001826ca2170133862465685e002b86dlangTechnik_vorschau_neuprodukte2_11zon.webp', NULL, NULL, 1, NULL, '2023-11-30 16:25:05', '2024-03-01 01:57:55'),
(6, 'LANG with new quick clamping system for machine tables and automation pallets', 'news-lang-with-new-quick-clamping-system-for-machine-tables-and-automation-pallets', '2023-09-15', 'News', 'Clamping bar meets machine table - an all new clamping concept\r\nAt the start of EMO in Hanover, LANG Technik is presenting Quick•Point® Rail, a completely new clamping concept for machine tables and pallet systems. It is based on clamping bars that are attached directly to slot tables and pallets with a hole pattern without having to modify or prepare them. Instead of a zero point base plate, zero point risers or centering vises are mounted directly on the clamping bar. The innovative approach offers enormous cost-saving potential, especially for high volumes in automation systems.', '<h2 style=\"box-sizing: inherit; line-height: 1.4; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; width: auto;\">Clamping bar meets machine table - an all new clamping concept</h2><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">At the start of EMO in Hanover, LANG Technik is presenting Quick•Point® Rail, a completely new clamping concept for machine tables and pallet systems. It is based on clamping bars that are attached directly to slot tables and pallets with a hole pattern without having to modify or prepare them. Instead of a zero point base plate, zero point risers or centering vises are mounted directly on the clamping bar. The innovative approach offers enormous cost-saving potential, especially for high volumes in automation systems. With Quick•Point® Rail, maximum setup speed and flexibility are guaranteed. In less than two minutes, the quick clamping system can be mounted and is ready for operation.</span></p><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">Thanks to the seamless connection of clamping and additional extension bars, the zero point units or 5-Axis Vises matching the system can be positioned absolutely variably - at lightning speed and with maximum precision. With the quick clamping system, Quick•Point® becomes a movable zero point clamping system, which solves the requirements of the respective clamping task as needed and cost-efficiently. By using Quick•Point® on the rail system, LANG\'s zero point system becomes even more powerful and offers numerous possibilities for optimizing your production processes!</span></p><h2 style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; line-height: 1.4; width: auto;\">&nbsp;</h2><h2 style=\"box-sizing: inherit; line-height: 1.4; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; width: auto;\">Maximum flexibility and a wide range of applications</h2><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">Clamping bars are available in two lengths with and without predefined mounting holes. They are positioned and fastened either directly in the grooves of the machine tables or on fixtures / pallets with existing threaded holes. The shorter extension bars complement the clamping bars. The rails are clamped by means of a pressure rod inside the clamping device which engages in the serration of the clamping bar. Thanks to the dovetail guide and the pull-down effect, the connection between the bar and the clamping device is absolutely robust and resilient.</span></p><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">Depending on the requirements and application or the available space on the machine table, one or more rail clamping devices can be placed absolutely freely and flexibly on the bars. The clamping devices can be moved at 4 mm intervals or at the usual 96 mm intervals making use of a snap-in function.</span></p>', '#', '17018388956570002f37c7d170133875865685e863e388langTechnik_vorschau_neuprodukte3_11zon.webp', NULL, NULL, 1, NULL, '2023-11-30 17:05:58', '2024-03-01 01:57:55'),
(7, 'New micro vise with matching zero point system', 'news-new-micro-vise-with-matching-zero-point-system', '2023-09-14', 'News', 'Zero point and workpiece clamping in confined space\r\n\"Small but mighty\" has rarely been more fitting. The new Makro•Grip® 46 micro is a real space-saver - perfectly suited for multiple clamping of small components in confined spaces. Matching: The new Quick•Point® 52 duo series. What\'s special about it?', '<h2 style=\"box-sizing: inherit; line-height: 1.4; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; width: auto;\"><span style=\"box-sizing: inherit;\">Zero point and workpiece clamping in confined space</span></h2><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">\"Small but mighty\" has rarely been more fitting. The new Makro•Grip® 46 micro is a real space-saver - perfectly suited for multiple clamping of small components in confined spaces. Matching: The new Quick•Point® 52 duo series. What\'s special about it? A compact vise base body with only 2 clamping studs, which makes it possible to clamp even two 5-Axis Vises side by side in just one zero point plate.&nbsp;</span><span style=\"box-sizing: inherit;\">For the product launch, customers can choose between two different zero point plates. A version matching the two mounting bolts of the small vises and a plate with six zero point mounting holes, which can be used in three configurations:</span></p><ul style=\"box-sizing: inherit; padding-left: 1.2em; list-style-type: square;\"><li style=\"box-sizing: inherit; margin-bottom: 0.2em; padding-left: 0.5em;\"><span style=\"box-sizing: inherit;\">one Makro•Grip® 46 micro centered,</span></li><li style=\"box-sizing: inherit; margin-bottom: 0.2em; padding-left: 0.5em;\"><span style=\"box-sizing: inherit;\">two Makro•Grip® 46 micro side by side, or</span></li><li style=\"box-sizing: inherit; margin-bottom: 0.2em; padding-left: 0.5em;\"><span style=\"box-sizing: inherit;\">one regular Makro•Grip® 5-Axis Vise (46 or 77) with four clamping studs</span></li></ul><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">In addition, the duo series offers an adapter plate for the Quick•Point® 52 system, providing complete continuity and compatibility from the largest to the smallest zero point system.&nbsp;</span><span style=\"box-sizing: inherit;\">The Makro•Grip® micro with jaw width 46 mm is able to hold workpieces up to clamping range 65 mm, which makes one clearly at home in the small component area. Especially companies from the fields of medical or precision engineering should find pleasure in the new micro vises.</span></p><h2 style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; line-height: 1.4; width: auto;\">&nbsp;</h2><h2 style=\"box-sizing: inherit; line-height: 1.4; margin-right: 0px; margin-bottom: 0.5em; margin-left: 0px; width: auto;\"><span style=\"box-sizing: inherit;\">Ideal for the automation of small parts</span></h2><p style=\"box-sizing: inherit; margin-right: 0px; margin-bottom: 1em; margin-left: 0px;\"><span style=\"box-sizing: inherit;\">LANG wouldn\'t be LANG if it didn\'t immediately think about automated manufacturing when developing the micro vise. At the end of the year, LANG will introduce a new automation solution with these very same 5-Axis Vises, which will require even less floor space compared to the well-known RoboTrex automation system, but will shine with an incredible storage capacity of the micro vises. The new system will be conceptually similar to the RoboTrex (industrial robot, compact storage), but even better tailored to the requirements of small parts production.</span></p>', '#', '170183899665700094ce968170133895465685f4a6583blangTechnik_vorschau_neuprodukte_0_11zon.webp', NULL, NULL, 1, NULL, '2023-11-30 17:09:14', '2024-03-01 01:57:55'),
(8, 'Zero-point clamping system as a combo solution', 'news-zero-point-clamping-system-as-a-combo-solution', '2022-06-29', 'News', 'Two different grid dimensions in one zero-point unit - Selected plate types from LANG Technik\'s zero-point clamping system are now available as a combo version.', '<p>Two different grid dimensions in one zero-point unit - Selected plate types from LANG Technik\'s zero-point clamping system are now available as a combo version. LANG thereby combines its two zero-point grids 52 and 96 mm, enabling the user to clamp the complete range of applicable LANG vises in the same zero-point unit. The flexibility of the mechanical zero-point clamping system is further increased in terms of application versatility, while at the same time it reduces the time required for changeovers. In smaller machine tools with little travel in the z-direction, the elimination of an additional adapter plate can also be helpful. The combo plates are available in square as well as in round design, as well as in a version with a clamping edge. LANG also offers its combo solution as a 5-axis riser and as a twin base for 3-axis machines and rotaries.<br></p>', '#', '1701838794656fffca3a0d1170133905065685faa1f622langTechnik_kombiplatten_quickpoint_11zon (1).webp', NULL, NULL, 1, NULL, '2023-11-30 17:10:50', '2024-03-01 01:57:55'),
(9, 'Centralized filtration system? Absolutely not!', 'media-centralized-filtration-system-absolutely-not', '2020-10-14', 'Media', 'The reasons why LONATI has divested a centralized system in favor of AR Filtrazioni solution', '<p class=\"mb-40\" style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; padding: 0px; direction: ltr; line-height: 1.6; text-rendering: optimizelegibility;\">The reasons why LONATI has divested a centralized system in favor of AR Filtrazioni solution</p><div class=\"row mb-30\" style=\"margin: 0px -0.9375em; padding: 0px; direction: ltr; width: auto; max-width: none;\"><ol style=\"margin: 0px 0px 1.25em 20px; padding: 0px; direction: ltr; line-height: 1.6; list-style-position: outside;\"><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Avoid total blockages of production</b></span></li><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Facilitating workshop Layout changes</b></span></li><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Fire safety</b></span></li><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Oil recovery, reducing consumption by 15%</b></span></li><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Economic Savings</b></span></li><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Greater ambient brightness</b></span></li><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Improvement in air health</b></span></li><li style=\"margin: 0px; padding: 0px; direction: ltr;\"><span style=\"line-height: inherit;\"><b>Reduction of CO2 emissions</b></span></li></ol></div><p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; padding: 0px; direction: ltr; line-height: 1.6; text-rendering: optimizelegibility;\">The CNC machining centers department, core product of mechanical production in Lonati, has been the subject an important intervention of modernization with the disposal of their centralized system for fume generated during the machining processing of their machining centres. They dismantled the centralized system, replacing it with AR filtrazioni mist collectors installed on single machine.</p>', '#', '17014066056569678d12091Mist-Collector.jpg', NULL, NULL, 1, NULL, '2023-12-01 11:56:45', '2024-03-01 01:57:55'),
(11, 'CMTS – Toronto, Canada, Sep 2019', 'show-cmts-toronto-canada-sep-2019', '2019-09-03', 'Show', 'CMTS – Toronto, Canada, Sep 2019', '<p><br></p><p><br></p>', '#', '1701762388656ed554ac76bCMTS-2019-1.jpg', NULL, '[\"1701773297656efff16bc2dCMTS-2019-5.jpg\",\"1701773297656efff16be32CMTS-2019-9.jpg\",\"1701773297656efff16bf5eCMTS-2019-8.jpg\",\"1701773297656efff16c0feCMTS-2019-1.jpg\",\"1701773297656efff16c268CMTS-2019-10.jpg\"]', 1, NULL, '2023-12-01 13:45:31', '2024-03-01 01:57:55'),
(12, 'LANG is Affordable', 'promotions-lang-is-affordable', '2023-12-04', 'Promotions', NULL, '<p><br></p>', 'a', '1701692582656dc4a69bd59WhatsApp-Image-2023-12-04-at-17.png', NULL, NULL, 1, NULL, '2023-12-04 19:23:02', '2024-03-01 01:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `message` text DEFAULT NULL,
  `sender` int(11) DEFAULT NULL,
  `receiver` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text DEFAULT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `secret` varchar(100) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `redirect` text NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) NOT NULL,
  `access_token_id` varchar(100) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` varchar(40) NOT NULL,
  `user_id` varchar(40) NOT NULL,
  `date` date NOT NULL,
  `note` text DEFAULT NULL,
  `total_price` decimal(16,2) NOT NULL,
  `billing_information` text DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `payment_status` int(11) NOT NULL DEFAULT 0,
  `payment_method` varchar(256) DEFAULT NULL,
  `transaction_id` varchar(256) DEFAULT NULL,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `date`, `note`, `total_price`, `billing_information`, `status`, `payment_status`, `payment_method`, `transaction_id`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('671de6e38b912-ordr-12447567931147319', '671dd964701e1-user-87459872651545202', '2024-10-27', NULL, 500.00, '{\"company\":null,\"name\":\"Arman Lison\",\"phone\":\"01628292015\",\"email\":\"developer.abusaid@gmail.com\",\"address\":\"Dhanmondi\",\"post_code\":\"1205\",\"city\":\"Dhaka\",\"state\":\"Dhanmondi\",\"country\":\"Bangladesh\"}', 0, 1, 'Cash', NULL, NULL, '2024-10-27 01:08:19', '2024-10-27 03:30:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `order_id` varchar(40) NOT NULL,
  `product_id` varchar(40) NOT NULL,
  `reference_id` varchar(40) DEFAULT NULL,
  `type` varchar(40) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(16,2) NOT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `discount_type` varchar(256) DEFAULT NULL,
  `discount` float(16,2) DEFAULT NULL,
  `subtotal` float(16,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `product_id`, `reference_id`, `type`, `quantity`, `unit_price`, `company_id`, `status`, `ancestor_id`, `created_at`, `updated_at`, `discount_type`, `discount`, `subtotal`) VALUES
(2, '671de6e38b912-ordr-12447567931147319', '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, 1, 500.00, '671b35c259c6e-comp-37685793406052083', 0, NULL, '2024-10-27 03:30:19', '2024-10-27 03:30:19', 'null', NULL, 500.00);

-- --------------------------------------------------------

--
-- Table structure for table `otp`
--

CREATE TABLE `otp` (
  `id` int(11) NOT NULL,
  `email` varchar(256) NOT NULL,
  `otp` varchar(24) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp`
--

INSERT INTO `otp` (`id`, `email`, `otp`, `status`, `created_at`, `updated_at`) VALUES
(1, 'babu@gmail.com', '729384', 0, '2023-05-22 04:35:12', '2023-05-22 04:35:12'),
(2, 'babu@gmail.com', '416490', 1, '2023-05-22 04:36:46', '2023-05-22 04:37:05'),
(3, 'babu@gmail.com', '841892', 1, '2023-05-22 04:47:31', '2023-05-22 04:47:45'),
(4, 'babu@gmail.com', '249326', 0, '2023-05-25 23:34:57', '2023-05-25 23:34:57'),
(5, 'admin@gmail.com', '591449', 0, '2023-05-29 06:06:04', '2023-05-29 06:06:04'),
(6, 'admin@gmail.com', '970173', 0, '2023-05-29 06:09:42', '2023-05-29 06:09:42'),
(7, 'admin@gmail.com', '614152', 0, '2023-05-29 06:15:13', '2023-05-29 06:15:13'),
(8, 'asd@gmail.com', '507808', 0, '2023-12-19 01:17:41', '2023-12-19 01:17:41'),
(9, 'asd@gmail.com', '811343', 0, '2023-12-19 01:17:52', '2023-12-19 01:17:52'),
(10, 'asd@gmail.com', '570420', 0, '2023-12-19 01:18:12', '2023-12-19 01:18:12');

-- --------------------------------------------------------

--
-- Table structure for table `parts_attribute`
--

CREATE TABLE `parts_attribute` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'attributes',
  `is_filter` int(11) NOT NULL DEFAULT 0,
  `attribute_id` varchar(40) DEFAULT NULL,
  `custom_field_id` varchar(40) DEFAULT NULL,
  `sub_option` varchar(256) DEFAULT NULL,
  `part_id` varchar(40) NOT NULL,
  `attribute_name` varchar(256) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `language_code` varchar(256) DEFAULT NULL,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parts_attribute`
--

INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(21, 'custom value', 0, NULL, '1', 'Scope of delivery:', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:06:01', '2024-01-13 13:06:01'),
(22, 'custom value', 0, NULL, '1', 'Scope of delivery:', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, '-', '', 'en', '21', '2024-01-13 13:06:01', '2024-01-13 13:06:01'),
(23, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:07:30', '2024-01-13 13:07:30'),
(24, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '23', '2024-01-13 13:07:30', '2024-01-13 13:07:30'),
(25, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '23', '2024-01-13 13:07:30', '2024-01-13 13:07:30'),
(26, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '23', '2024-01-13 13:07:30', '2024-01-13 13:07:30'),
(27, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '23', '2024-01-13 13:07:30', '2024-01-13 13:07:30'),
(30, 'custom value', 0, NULL, '1', 'Notice:', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:13:17', '2024-01-13 13:13:17'),
(31, 'custom value', 0, NULL, '1', 'Notice:', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, '-', '', 'en', '30', '2024-01-13 13:13:17', '2024-01-13 13:13:17'),
(33, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:15:28', '2024-01-13 13:15:28'),
(36, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a227b636bfe-pprt-89497772175826729', NULL, NULL, NULL, NULL, '170512653165a22a8391f78_11.2021_MakroGrip_Konturbacken_EN.pdf', 'en', '33', '2024-01-13 13:15:31', '2024-01-13 13:15:31'),
(37, 'attributes', 0, NULL, NULL, NULL, '65a22c7d1093e-pprt-20356038742250174', 'SPINDLE LENGTH', '364 mm (14.33\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:23:57', '2024-01-13 13:23:57'),
(38, 'attributes', 0, NULL, NULL, NULL, '65a22c7d1093e-pprt-20356038742250174', 'THREAD PITCH', 'M20 x 1.5', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:23:57', '2024-01-13 13:23:57'),
(39, 'attributes', 0, NULL, NULL, NULL, '65a22c7d1093e-pprt-20356038742250174', 'DIMENSIONS', 'Jaw width: 125 mm (4.92\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:23:57', '2024-01-13 13:23:57'),
(40, 'attributes', 0, NULL, NULL, NULL, '65a22c7d1093e-pprt-20356038742250174', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:23:57', '2024-01-13 13:23:57'),
(41, 'attributes', 0, NULL, NULL, NULL, '65a22c7d1093e-pprt-20356038742250174', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:23:57', '2024-01-13 13:23:57'),
(42, 'attributes', 0, NULL, NULL, NULL, '65a22c7d1093e-pprt-20356038742250174', 'WEIGHT', '2.1 kg (4.63 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:23:57', '2024-01-13 13:23:57'),
(43, 'attributes', 0, NULL, NULL, NULL, '65a22c7d1093e-pprt-20356038742250174', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:23:57', '2024-01-13 13:23:57'),
(44, 'custom value', 0, NULL, '1', 'Notice', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:24:38', '2024-01-13 13:24:38'),
(45, 'custom value', 0, NULL, '1', 'Notice', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, '-', '', 'en', '44', '2024-01-13 13:24:38', '2024-01-13 13:24:38'),
(46, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:25:00', '2024-01-13 13:25:00'),
(47, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, '-', '', 'en', '46', '2024-01-13 13:25:00', '2024-01-13 13:25:00'),
(48, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:25:52', '2024-01-13 13:25:52'),
(49, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '48', '2024-01-13 13:25:52', '2024-01-13 13:25:52'),
(50, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '48', '2024-01-13 13:25:52', '2024-01-13 13:25:52'),
(51, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '48', '2024-01-13 13:25:52', '2024-01-13 13:25:52'),
(52, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:26:14', '2024-01-13 13:26:14'),
(53, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '52', '2024-01-13 13:26:14', '2024-01-13 13:26:14'),
(54, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:26:36', '2024-01-13 13:26:36'),
(55, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, '170512719665a22d1c3288b_44355-TG125_cad.zip', 'en', '54', '2024-01-13 13:26:36', '2024-01-13 13:26:36'),
(56, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:27:43', '2024-01-13 13:27:43'),
(57, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a22c7d1093e-pprt-20356038742250174', NULL, NULL, NULL, NULL, '170512726365a22d5f65ca0_44355-TG125.pdf', 'en', '56', '2024-01-13 13:27:43', '2024-01-13 13:27:43'),
(58, 'attributes', 0, NULL, NULL, NULL, '65a22e644f657-pprt-49899357613265912', 'SPINDLE LENGTH', '215 mm (8.46\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:32:04', '2024-01-13 13:32:04'),
(59, 'attributes', 0, NULL, NULL, NULL, '65a22e644f657-pprt-49899357613265912', 'THREAD PITCH', 'M16 x 1.5', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:32:04', '2024-01-13 13:32:04'),
(60, 'attributes', 0, NULL, NULL, NULL, '65a22e644f657-pprt-49899357613265912', 'DIMENSIONS', 'Jaw width: 77 mm (3.03\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:32:04', '2024-01-13 13:32:04'),
(61, 'attributes', 0, NULL, NULL, NULL, '65a22e644f657-pprt-49899357613265912', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:32:04', '2024-01-13 13:32:04'),
(62, 'attributes', 0, NULL, NULL, NULL, '65a22e644f657-pprt-49899357613265912', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:32:04', '2024-01-13 13:32:04'),
(63, 'attributes', 0, NULL, NULL, NULL, '65a22e644f657-pprt-49899357613265912', 'WEIGHT', '0.97 kg (2.14 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:32:04', '2024-01-13 13:32:04'),
(64, 'attributes', 0, NULL, NULL, NULL, '65a22e644f657-pprt-49899357613265912', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:32:04', '2024-01-13 13:32:04'),
(65, 'custom value', 0, NULL, '1', 'Notice', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:32:24', '2024-01-13 13:32:24'),
(66, 'custom value', 0, NULL, '1', 'Notice', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, '-', '', 'en', '65', '2024-01-13 13:32:24', '2024-01-13 13:32:24'),
(67, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:32:35', '2024-01-13 13:32:35'),
(68, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, '-', '', 'en', '67', '2024-01-13 13:32:35', '2024-01-13 13:32:35'),
(69, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:33:23', '2024-01-13 13:33:23'),
(70, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22e644f657-pprt-49899357613265912', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '69', '2024-01-13 13:33:23', '2024-01-13 13:33:23'),
(71, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22e644f657-pprt-49899357613265912', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '69', '2024-01-13 13:33:23', '2024-01-13 13:33:23'),
(72, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a22e644f657-pprt-49899357613265912', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '69', '2024-01-13 13:33:23', '2024-01-13 13:33:23'),
(73, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:33:49', '2024-01-13 13:33:49'),
(74, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '73', '2024-01-13 13:33:49', '2024-01-13 13:33:49'),
(75, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:35:36', '2024-01-13 13:35:36'),
(76, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, '170512773665a22f384299e_44200-TG77_cad.zip', 'en', '75', '2024-01-13 13:35:36', '2024-01-13 13:35:36'),
(77, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:36:05', '2024-01-13 13:36:05'),
(78, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a22e644f657-pprt-49899357613265912', NULL, NULL, NULL, NULL, '170512776565a22f5560dbb_44200-TG77.pdf', 'en', '77', '2024-01-13 13:36:05', '2024-01-13 13:36:05'),
(79, 'attributes', 0, NULL, NULL, NULL, '65a2300a89b5d-pprt-69435562011707324', 'SPINDLE LENGTH', '264 mm (10.39\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:39:06', '2024-01-13 13:39:06'),
(80, 'attributes', 0, NULL, NULL, NULL, '65a2300a89b5d-pprt-69435562011707324', 'THREAD PITCH', 'M20 x 1.5', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:39:06', '2024-01-13 13:39:06'),
(81, 'attributes', 0, NULL, NULL, NULL, '65a2300a89b5d-pprt-69435562011707324', 'DIMENSIONS', 'Jaw width: 125 mm (4.92\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:39:06', '2024-01-13 13:39:06'),
(82, 'attributes', 0, NULL, NULL, NULL, '65a2300a89b5d-pprt-69435562011707324', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:39:06', '2024-01-13 13:39:06'),
(83, 'attributes', 0, NULL, NULL, NULL, '65a2300a89b5d-pprt-69435562011707324', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:39:06', '2024-01-13 13:39:06'),
(84, 'attributes', 0, NULL, NULL, NULL, '65a2300a89b5d-pprt-69435562011707324', 'WEIGHT', '1.9 kg (4.19 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:39:06', '2024-01-13 13:39:06'),
(85, 'attributes', 0, NULL, NULL, NULL, '65a2300a89b5d-pprt-69435562011707324', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:39:06', '2024-01-13 13:39:06'),
(86, 'custom value', 0, NULL, '1', 'Notice', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:39:34', '2024-01-13 13:39:34'),
(87, 'custom value', 0, NULL, '1', 'Notice', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, '-', '', 'en', '86', '2024-01-13 13:39:34', '2024-01-13 13:39:34'),
(88, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:39:45', '2024-01-13 13:39:45'),
(89, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, '-', '', 'en', '88', '2024-01-13 13:39:45', '2024-01-13 13:39:45'),
(90, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:40:50', '2024-01-13 13:40:50'),
(91, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '90', '2024-01-13 13:40:50', '2024-01-13 13:40:50'),
(92, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '90', '2024-01-13 13:40:50', '2024-01-13 13:40:50'),
(93, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '90', '2024-01-13 13:40:51', '2024-01-13 13:40:51'),
(94, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:41:10', '2024-01-13 13:41:10'),
(95, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '94', '2024-01-13 13:41:10', '2024-01-13 13:41:10'),
(96, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:41:28', '2024-01-13 13:41:28'),
(97, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, '170512808865a23098b1587_44255-TG125_cad.zip', 'en', '96', '2024-01-13 13:41:28', '2024-01-13 13:41:28'),
(98, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:41:55', '2024-01-13 13:41:55'),
(99, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2300a89b5d-pprt-69435562011707324', NULL, NULL, NULL, NULL, '170512811565a230b3ab11c_44255-TG125.pdf', 'en', '98', '2024-01-13 13:41:55', '2024-01-13 13:41:55'),
(100, 'attributes', 0, NULL, NULL, NULL, '65a2323433d6b-pprt-17501421848607674', 'SPINDLE LENGTH', '135 mm (5.31\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:48:20', '2024-01-13 13:48:20'),
(101, 'attributes', 0, NULL, NULL, NULL, '65a2323433d6b-pprt-17501421848607674', 'THREAD PITCH', 'M16 x 1.5', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:48:20', '2024-01-13 13:48:20'),
(102, 'attributes', 0, NULL, NULL, NULL, '65a2323433d6b-pprt-17501421848607674', 'DIMENSIONS', 'Jaw width: 55 mm (2.17\"), Jaw length: 36 mm (1.42\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:48:20', '2024-01-13 13:48:20'),
(103, 'attributes', 0, NULL, NULL, NULL, '65a2323433d6b-pprt-17501421848607674', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:48:20', '2024-01-13 13:48:20'),
(104, 'attributes', 0, NULL, NULL, NULL, '65a2323433d6b-pprt-17501421848607674', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:48:20', '2024-01-13 13:48:20'),
(105, 'attributes', 0, NULL, NULL, NULL, '65a2323433d6b-pprt-17501421848607674', 'WEIGHT', '0.48 kg (1.06 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:48:20', '2024-01-13 13:48:20'),
(106, 'attributes', 0, NULL, NULL, NULL, '65a2323433d6b-pprt-17501421848607674', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:48:20', '2024-01-13 13:48:20'),
(107, 'custom value', 0, NULL, '1', 'Notice', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:49:24', '2024-01-13 13:49:24'),
(108, 'custom value', 0, NULL, '1', 'Notice', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, '-', '', 'en', '107', '2024-01-13 13:49:24', '2024-01-13 13:49:24'),
(109, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:50:43', '2024-01-13 13:50:43'),
(110, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, '-', '', 'en', '109', '2024-01-13 13:50:43', '2024-01-13 13:50:43'),
(111, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:51:55', '2024-01-13 13:51:55'),
(112, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '111', '2024-01-13 13:51:55', '2024-01-13 13:51:55'),
(113, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '111', '2024-01-13 13:51:55', '2024-01-13 13:51:55'),
(114, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '111', '2024-01-13 13:51:55', '2024-01-13 13:51:55'),
(115, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:52:16', '2024-01-13 13:52:16'),
(116, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '115', '2024-01-13 13:52:16', '2024-01-13 13:52:16'),
(117, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:52:45', '2024-01-13 13:52:45'),
(118, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, '170512876565a2333dca6d3_44120-TG46_cad.zip', 'en', '117', '2024-01-13 13:52:45', '2024-01-13 13:52:45'),
(119, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:53:22', '2024-01-13 13:53:22'),
(120, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2323433d6b-pprt-17501421848607674', NULL, NULL, NULL, NULL, '170512880265a23362bed6b_44120-TG46.pdf', 'en', '119', '2024-01-13 13:53:22', '2024-01-13 13:53:22'),
(121, 'attributes', 0, NULL, NULL, NULL, '65a2341c98ba6-pprt-76750548674283847', 'SPINDLE LENGTH', '314 mm (12.36\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:56:28', '2024-01-13 13:56:28'),
(122, 'attributes', 0, NULL, NULL, NULL, '65a2341c98ba6-pprt-76750548674283847', 'THREAD PITCH', 'M20 x 1.5', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:56:28', '2024-01-13 13:56:28'),
(123, 'attributes', 0, NULL, NULL, NULL, '65a2341c98ba6-pprt-76750548674283847', 'DIMENSIONS', 'Jaw width: 125 mm (4.92\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:56:28', '2024-01-13 13:56:28'),
(124, 'attributes', 0, NULL, NULL, NULL, '65a2341c98ba6-pprt-76750548674283847', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:56:28', '2024-01-13 13:56:28'),
(125, 'attributes', 0, NULL, NULL, NULL, '65a2341c98ba6-pprt-76750548674283847', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:56:28', '2024-01-13 13:56:28'),
(126, 'attributes', 0, NULL, NULL, NULL, '65a2341c98ba6-pprt-76750548674283847', 'WEIGHT', '2 kg (4.41 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:56:28', '2024-01-13 13:56:28'),
(127, 'attributes', 0, NULL, NULL, NULL, '65a2341c98ba6-pprt-76750548674283847', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 13:56:28', '2024-01-13 13:56:28'),
(128, 'custom value', 0, NULL, '1', 'Notice', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:56:46', '2024-01-13 13:56:46'),
(129, 'custom value', 0, NULL, '1', 'Notice', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, '-', '', 'en', '128', '2024-01-13 13:56:46', '2024-01-13 13:56:46'),
(130, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:56:55', '2024-01-13 13:56:55'),
(131, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, '-', '', 'en', '130', '2024-01-13 13:56:55', '2024-01-13 13:56:55'),
(132, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:57:37', '2024-01-13 13:57:37'),
(133, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '132', '2024-01-13 13:57:37', '2024-01-13 13:57:37'),
(134, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '132', '2024-01-13 13:57:37', '2024-01-13 13:57:37'),
(135, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '132', '2024-01-13 13:57:37', '2024-01-13 13:57:37'),
(136, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:57:56', '2024-01-13 13:57:56'),
(137, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '136', '2024-01-13 13:57:56', '2024-01-13 13:57:56'),
(138, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:58:18', '2024-01-13 13:58:18'),
(139, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, '170512909865a2348a2c883_44305-TG125_cad.zip', 'en', '138', '2024-01-13 13:58:18', '2024-01-13 13:58:18'),
(140, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 13:58:44', '2024-01-13 13:58:44'),
(141, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2341c98ba6-pprt-76750548674283847', NULL, NULL, NULL, NULL, '170512912465a234a4b2f32_44305-TG125.pdf', 'en', '140', '2024-01-13 13:58:44', '2024-01-13 13:58:44'),
(142, 'attributes', 0, NULL, NULL, NULL, '65a23548481d1-pprt-32062513147014401', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-13 14:01:28', '2024-01-13 14:01:28'),
(143, 'custom value', 0, NULL, '1', 'Notice', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 14:01:51', '2024-01-13 14:01:51'),
(144, 'custom value', 0, NULL, '1', 'Notice', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, '-', '', 'en', '143', '2024-01-13 14:01:51', '2024-01-13 14:01:51'),
(145, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 14:01:59', '2024-01-13 14:01:59'),
(146, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, '-', '', 'en', '145', '2024-01-13 14:01:59', '2024-01-13 14:01:59'),
(147, 'custom value', 0, NULL, '3', 'Quick•Point® Rail system', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 14:03:10', '2024-01-13 14:03:10'),
(148, 'custom value', 0, NULL, '3', 'Quick•Point® Rail system', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, 'The Quick•Point® Rail system impresses with its enormously diverse range of applications combined with outstanding cost efficiency. Especially when used on pallet automation systems, large 3-axis tables and clamping bridges, it ensures drastic savings, as it does not require a zero point base plate at all. Set-up speed and flexibility are once again key with Quick•Point® Rail! The seamless lining up of the clamping bars enables variable positioning of the zero point units in 4 mm increments – extremely fast and with high repeatability.', '', 'en', '147', '2024-01-13 14:03:10', '2024-01-13 14:03:10'),
(149, 'custom value', 0, NULL, '3', 'Quick•Point® Rail system', '65a23548481d1-pprt-32062513147014401', NULL, NULL, 'Extremely variable clamping possibilities', 'thanks to flexible arrangement of the zero point risers or vises on the clamping bar', '', 'en', '147', '2024-01-13 14:03:10', '2024-01-13 14:03:10'),
(150, 'custom value', 0, NULL, '3', 'Quick•Point® Rail system', '65a23548481d1-pprt-32062513147014401', NULL, NULL, 'Start directly, without preparation', 'as machine tables / pallets do not have to be modified / drilled', '', 'en', '147', '2024-01-13 14:03:10', '2024-01-13 14:03:10'),
(151, 'custom value', 0, NULL, '3', 'Quick•Point® Rail system', '65a23548481d1-pprt-32062513147014401', NULL, NULL, 'Outstanding cost efficiency', 'since no base plates are required', '', 'en', '147', '2024-01-13 14:03:10', '2024-01-13 14:03:10'),
(152, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 14:03:27', '2024-01-13 14:03:27'),
(153, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a23548481d1-pprt-32062513147014401', NULL, NULL, NULL, NULL, '170512940765a235bf2b4fd_73701_cad.zip', 'en', '152', '2024-01-13 14:03:27', '2024-01-13 14:03:27'),
(154, 'attributes', 0, NULL, NULL, NULL, '65a27bc4bdf63-pprt-25321509990214591', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:02:12', '2024-01-13 19:02:12'),
(155, 'attributes', 0, NULL, NULL, NULL, '65a27bc4bdf63-pprt-25321509990214591', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:02:12', '2024-01-13 19:02:12'),
(156, 'custom value', 0, NULL, '1', 'Notice', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:02:47', '2024-01-13 19:02:47'),
(157, 'custom value', 0, NULL, '1', 'Notice', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, NULL, '-', '', 'en', '156', '2024-01-13 19:02:47', '2024-01-13 19:02:47'),
(158, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:02:57', '2024-01-13 19:02:57'),
(159, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, NULL, '-', '', 'en', '158', '2024-01-13 19:02:57', '2024-01-13 19:02:57'),
(160, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:03:51', '2024-01-13 19:03:51'),
(161, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '160', '2024-01-13 19:03:51', '2024-01-13 19:03:51'),
(162, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '160', '2024-01-13 19:03:51', '2024-01-13 19:03:51'),
(163, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '160', '2024-01-13 19:03:51', '2024-01-13 19:03:51'),
(164, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27bc4bdf63-pprt-25321509990214591', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '160', '2024-01-13 19:03:51', '2024-01-13 19:03:51'),
(165, 'attributes', 0, NULL, NULL, NULL, '65a27caac68a7-pprt-65923309492110809', 'WEITERE FEATURES / WERTE', NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:06:02', '2024-01-13 19:06:02'),
(166, 'attributes', 0, NULL, NULL, NULL, '65a27caac68a7-pprt-65923309492110809', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:06:02', '2024-01-13 19:06:02'),
(167, 'custom value', 0, NULL, '1', 'Notice', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:06:21', '2024-01-13 19:06:21'),
(168, 'custom value', 0, NULL, '1', 'Notice', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, NULL, '-', '', 'en', '167', '2024-01-13 19:06:21', '2024-01-13 19:06:21'),
(169, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:06:31', '2024-01-13 19:06:31'),
(170, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, NULL, '-', '', 'en', '169', '2024-01-13 19:06:31', '2024-01-13 19:06:31'),
(171, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:07:30', '2024-01-13 19:07:30'),
(172, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '171', '2024-01-13 19:07:30', '2024-01-13 19:07:30'),
(173, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '171', '2024-01-13 19:07:30', '2024-01-13 19:07:30'),
(174, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '171', '2024-01-13 19:07:30', '2024-01-13 19:07:30'),
(175, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27caac68a7-pprt-65923309492110809', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '171', '2024-01-13 19:07:30', '2024-01-13 19:07:30'),
(176, 'attributes', 0, NULL, NULL, NULL, '65a27d914036e-pprt-88991006884961321', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:09:53', '2024-01-13 19:09:53'),
(177, 'custom value', 0, NULL, '1', 'Notice', '65a27d914036e-pprt-88991006884961321', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:10:11', '2024-01-13 19:10:11'),
(178, 'custom value', 0, NULL, '1', 'Notice', '65a27d914036e-pprt-88991006884961321', NULL, NULL, NULL, '-', '', 'en', '177', '2024-01-13 19:10:11', '2024-01-13 19:10:11'),
(179, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27d914036e-pprt-88991006884961321', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:10:20', '2024-01-13 19:10:20'),
(180, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27d914036e-pprt-88991006884961321', NULL, NULL, NULL, '-', '', 'en', '179', '2024-01-13 19:10:20', '2024-01-13 19:10:20'),
(181, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27d914036e-pprt-88991006884961321', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:11:06', '2024-01-13 19:11:06'),
(182, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27d914036e-pprt-88991006884961321', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '181', '2024-01-13 19:11:06', '2024-01-13 19:11:06'),
(183, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27d914036e-pprt-88991006884961321', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '181', '2024-01-13 19:11:06', '2024-01-13 19:11:06'),
(184, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27d914036e-pprt-88991006884961321', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '181', '2024-01-13 19:11:06', '2024-01-13 19:11:06'),
(185, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27d914036e-pprt-88991006884961321', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '181', '2024-01-13 19:11:06', '2024-01-13 19:11:06'),
(186, 'attributes', 0, NULL, NULL, NULL, '65a27e30e5d48-pprt-73172092007532664', 'WEITERE FEATURES / WERTE', NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:12:32', '2024-01-13 19:12:32'),
(187, 'attributes', 0, NULL, NULL, NULL, '65a27e30e5d48-pprt-73172092007532664', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:12:32', '2024-01-13 19:12:32'),
(188, 'custom value', 0, NULL, '1', 'Notice', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:12:58', '2024-01-13 19:12:58'),
(189, 'custom value', 0, NULL, '1', 'Notice', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, NULL, '-', '', 'en', '188', '2024-01-13 19:12:58', '2024-01-13 19:12:58'),
(190, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:13:09', '2024-01-13 19:13:09'),
(191, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, NULL, '-', '', 'en', '190', '2024-01-13 19:13:09', '2024-01-13 19:13:09'),
(192, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:14:03', '2024-01-13 19:14:03'),
(193, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '192', '2024-01-13 19:14:03', '2024-01-13 19:14:03'),
(194, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '192', '2024-01-13 19:14:03', '2024-01-13 19:14:03'),
(195, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '192', '2024-01-13 19:14:03', '2024-01-13 19:14:03'),
(196, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27e30e5d48-pprt-73172092007532664', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '192', '2024-01-13 19:14:03', '2024-01-13 19:14:03'),
(197, 'attributes', 0, NULL, NULL, NULL, '65a27f6bd6138-pprt-59153097833838958', 'WEITERE FEATURES / WERTE', NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:17:47', '2024-01-13 19:17:47'),
(198, 'attributes', 0, NULL, NULL, NULL, '65a27f6bd6138-pprt-59153097833838958', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:17:47', '2024-01-13 19:17:47'),
(199, 'custom value', 0, NULL, '1', 'Notice', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:18:09', '2024-01-13 19:18:09'),
(200, 'custom value', 0, NULL, '1', 'Notice', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, NULL, '-', '', 'en', '199', '2024-01-13 19:18:09', '2024-01-13 19:18:09'),
(201, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:18:18', '2024-01-13 19:18:18'),
(202, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, NULL, '-', '', 'en', '201', '2024-01-13 19:18:18', '2024-01-13 19:18:18'),
(203, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:19:14', '2024-01-13 19:19:14'),
(204, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '203', '2024-01-13 19:19:14', '2024-01-13 19:19:14'),
(205, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '203', '2024-01-13 19:19:14', '2024-01-13 19:19:14'),
(206, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '203', '2024-01-13 19:19:14', '2024-01-13 19:19:14'),
(207, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a27f6bd6138-pprt-59153097833838958', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '203', '2024-01-13 19:19:14', '2024-01-13 19:19:14'),
(208, 'attributes', 0, NULL, NULL, NULL, '65a28012453c8-pprt-68282642222686446', 'WEITERE FEATURES / WERTE', NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:20:34', '2024-01-13 19:20:34'),
(209, 'attributes', 0, NULL, NULL, NULL, '65a28012453c8-pprt-68282642222686446', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:20:34', '2024-01-13 19:20:34'),
(210, 'custom value', 0, NULL, '1', 'Notice', '65a28012453c8-pprt-68282642222686446', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:20:58', '2024-01-13 19:20:58'),
(211, 'custom value', 0, NULL, '1', 'Notice', '65a28012453c8-pprt-68282642222686446', NULL, NULL, NULL, '-', '', 'en', '210', '2024-01-13 19:20:58', '2024-01-13 19:20:58'),
(212, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a28012453c8-pprt-68282642222686446', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:21:08', '2024-01-13 19:21:08'),
(213, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a28012453c8-pprt-68282642222686446', NULL, NULL, NULL, '-', '', 'en', '212', '2024-01-13 19:21:08', '2024-01-13 19:21:08'),
(214, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a28012453c8-pprt-68282642222686446', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:22:07', '2024-01-13 19:22:07'),
(215, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a28012453c8-pprt-68282642222686446', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '214', '2024-01-13 19:22:07', '2024-01-13 19:22:07'),
(216, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a28012453c8-pprt-68282642222686446', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '214', '2024-01-13 19:22:07', '2024-01-13 19:22:07'),
(217, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a28012453c8-pprt-68282642222686446', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '214', '2024-01-13 19:22:07', '2024-01-13 19:22:07'),
(218, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a28012453c8-pprt-68282642222686446', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '214', '2024-01-13 19:22:07', '2024-01-13 19:22:07'),
(219, 'attributes', 0, NULL, NULL, NULL, '65a2815ccd1cc-pprt-89150010613384045', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:26:04', '2024-01-13 19:26:04'),
(220, 'attributes', 0, NULL, NULL, NULL, '65a2815ccd1cc-pprt-89150010613384045', 'WEIGHT', '0.1 kg (0.22 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:26:04', '2024-01-13 19:26:04'),
(221, 'attributes', 0, NULL, NULL, NULL, '65a2815ccd1cc-pprt-89150010613384045', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:26:04', '2024-01-13 19:26:04'),
(222, 'custom value', 0, NULL, '1', 'Notice', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:26:56', '2024-01-13 19:26:56'),
(223, 'custom value', 0, NULL, '1', 'Notice', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, NULL, '-', '', 'en', '222', '2024-01-13 19:26:56', '2024-01-13 19:26:56'),
(224, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:27:05', '2024-01-13 19:27:05'),
(225, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, NULL, '-', '', 'en', '224', '2024-01-13 19:27:05', '2024-01-13 19:27:05'),
(226, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:28:03', '2024-01-13 19:28:03'),
(227, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '226', '2024-01-13 19:28:03', '2024-01-13 19:28:03'),
(228, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '226', '2024-01-13 19:28:03', '2024-01-13 19:28:03'),
(229, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '226', '2024-01-13 19:28:03', '2024-01-13 19:28:03'),
(230, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a2815ccd1cc-pprt-89150010613384045', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '226', '2024-01-13 19:28:03', '2024-01-13 19:28:03'),
(232, 'custom value', 0, NULL, '1', 'Notice', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:31:21', '2024-01-13 19:31:21'),
(233, 'custom value', 0, NULL, '1', 'Notice', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, '-', '', 'en', '232', '2024-01-13 19:31:21', '2024-01-13 19:31:21'),
(234, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:31:41', '2024-01-13 19:31:41'),
(235, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, '-', '', 'en', '234', '2024-01-13 19:31:41', '2024-01-13 19:31:41');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(236, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:32:38', '2024-01-13 19:32:38'),
(237, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '236', '2024-01-13 19:32:38', '2024-01-13 19:32:38'),
(238, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a282506e421-pprt-35247242445504526', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '236', '2024-01-13 19:32:38', '2024-01-13 19:32:38'),
(239, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a282506e421-pprt-35247242445504526', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '236', '2024-01-13 19:32:38', '2024-01-13 19:32:38'),
(240, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a282506e421-pprt-35247242445504526', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '236', '2024-01-13 19:32:38', '2024-01-13 19:32:38'),
(241, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:32:54', '2024-01-13 19:32:54'),
(242, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a282506e421-pprt-35247242445504526', NULL, NULL, NULL, NULL, '170514917465a282f6cee6c_44400_cad.zip', 'en', '241', '2024-01-13 19:32:54', '2024-01-13 19:32:54'),
(243, 'attributes', 0, NULL, NULL, NULL, '65a2834d2c352-pprt-98286484761237119', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:34:21', '2024-01-13 19:34:21'),
(244, 'attributes', 0, NULL, NULL, NULL, '65a2834d2c352-pprt-98286484761237119', 'WEIGHT', '0.212 kg (0.47 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:34:21', '2024-01-13 19:34:21'),
(245, 'custom value', 0, NULL, '1', 'Notice', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:34:38', '2024-01-13 19:34:38'),
(246, 'custom value', 0, NULL, '1', 'Notice', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, '-', '', 'en', '245', '2024-01-13 19:34:38', '2024-01-13 19:34:38'),
(247, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:34:48', '2024-01-13 19:34:48'),
(248, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, '-', '', 'en', '247', '2024-01-13 19:34:48', '2024-01-13 19:34:48'),
(249, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:35:44', '2024-01-13 19:35:44'),
(250, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems. Flexibility', '', 'en', '249', '2024-01-13 19:35:44', '2024-01-13 19:35:44'),
(251, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '249', '2024-01-13 19:35:44', '2024-01-13 19:35:44'),
(252, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '249', '2024-01-13 19:35:44', '2024-01-13 19:35:44'),
(253, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '249', '2024-01-13 19:35:44', '2024-01-13 19:35:44'),
(254, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:36:01', '2024-01-13 19:36:01'),
(255, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2834d2c352-pprt-98286484761237119', NULL, NULL, NULL, NULL, '170514936165a283b1f3294_44500_cad.zip', 'en', '254', '2024-01-13 19:36:01', '2024-01-13 19:36:01'),
(256, 'attributes', 0, NULL, NULL, NULL, '65a2843ad4a54-pprt-26675197296060185', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-13 19:38:18', '2024-01-13 19:38:18'),
(257, 'custom value', 0, NULL, '1', 'Notice', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:38:47', '2024-01-13 19:38:47'),
(258, 'custom value', 0, NULL, '1', 'Notice', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, '-', '', 'en', '257', '2024-01-13 19:38:47', '2024-01-13 19:38:47'),
(259, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:39:30', '2024-01-13 19:39:30'),
(260, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, '1 x Mechanical screw jack (incl. 2 pendulum supports), 1 x Set of actuating rod (3 lengths), 1 x Clamping screw SW 15', '', 'en', '259', '2024-01-13 19:39:30', '2024-01-13 19:39:30'),
(261, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:40:24', '2024-01-13 19:40:24'),
(262, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '261', '2024-01-13 19:40:24', '2024-01-13 19:40:24'),
(263, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '261', '2024-01-13 19:40:24', '2024-01-13 19:40:24'),
(264, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '261', '2024-01-13 19:40:24', '2024-01-13 19:40:24'),
(265, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '261', '2024-01-13 19:40:24', '2024-01-13 19:40:24'),
(266, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:43:19', '2024-01-13 19:43:19'),
(267, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, NULL, '170514979965a2856738201_82586_cad.zip', 'en', '266', '2024-01-13 19:43:19', '2024-01-13 19:43:19'),
(268, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 19:43:36', '2024-01-13 19:43:36'),
(269, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2843ad4a54-pprt-26675197296060185', NULL, NULL, NULL, NULL, '170514981665a28578dc752_82586.pdf', 'en', '268', '2024-01-13 19:43:36', '2024-01-13 19:43:36'),
(270, 'attributes', 0, NULL, NULL, NULL, '65a2abc896c63-pprt-33542289344220469', 'TYPE', 'Hollow taper shank', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:27:04', '2024-01-13 22:27:04'),
(271, 'attributes', 0, NULL, NULL, NULL, '65a2abc896c63-pprt-33542289344220469', 'TOOL LENGTH', 'ca. 247 mm (ca. 9.72\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:27:04', '2024-01-13 22:27:04'),
(272, 'attributes', 0, NULL, NULL, NULL, '65a2abc896c63-pprt-33542289344220469', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:27:04', '2024-01-13 22:27:04'),
(273, 'attributes', 0, NULL, NULL, NULL, '65a2abc896c63-pprt-33542289344220469', 'WEIGHT', '1 kg (2.2 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:27:04', '2024-01-13 22:27:04'),
(274, 'attributes', 0, NULL, NULL, NULL, '65a2abc896c63-pprt-33542289344220469', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:27:04', '2024-01-13 22:27:04'),
(275, 'custom value', 0, NULL, '1', 'Notice', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:27:30', '2024-01-13 22:27:30'),
(276, 'custom value', 0, NULL, '1', 'Notice', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, 'Norm: DIN 69893-1', '', 'en', '275', '2024-01-13 22:27:30', '2024-01-13 22:27:30'),
(277, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:27:40', '2024-01-13 22:27:40'),
(278, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, '-', '', 'en', '277', '2024-01-13 22:27:40', '2024-01-13 22:27:40'),
(279, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:30:24', '2024-01-13 22:30:24'),
(280, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, 'HAUBEX can be used on practically any machine tool and flexibly throughout the entire production process. With the tool magazine as the storage medium, in which the workholding hood is stored together with the self-centering vise and workpiece blank and is automatically exchanged into the machine, HAUBEX manages completely without additional handling and storage systems.', '', 'en', '279', '2024-01-13 22:30:24', '2024-01-13 22:30:24'),
(281, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, 'Application possibilities', 'Can be retrofitted to almost any type of machine tool and can be ideally integrated into the production environment thanks to the elimination of additional automation elements.', '', 'en', '279', '2024-01-13 22:30:24', '2024-01-13 22:30:24'),
(282, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, 'Flexibility', 'Like any common tool, not bound to a specific machine tool and thus can be used practically throughout the entire production process.', '', 'en', '279', '2024-01-13 22:30:24', '2024-01-13 22:30:24'),
(283, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, 'Cost-efficiency', 'Significant increase in added value of in-house production even with small quantities and low investments.', '', 'en', '279', '2024-01-13 22:30:24', '2024-01-13 22:30:24'),
(284, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:30:45', '2024-01-13 22:30:45'),
(285, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, NULL, '170515984565a2aca5b0ddd_61500-HSK63_1.pdf', 'en', '284', '2024-01-13 22:30:45', '2024-01-13 22:30:45'),
(286, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:32:02', '2024-01-13 22:32:02'),
(287, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2abc896c63-pprt-33542289344220469', NULL, NULL, NULL, NULL, '170515992265a2acf232662_2-2022_Haubex_EN_4.pdf', 'en', '286', '2024-01-13 22:32:02', '2024-01-13 22:32:02'),
(288, 'attributes', 0, NULL, NULL, NULL, '65a2adc0835c9-pprt-57679587319049162', 'TYPE', 'Steep Taper', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:35:28', '2024-01-13 22:35:28'),
(289, 'attributes', 0, NULL, NULL, NULL, '65a2adc0835c9-pprt-57679587319049162', 'TOOL LENGTH', 'ca. 250 mm (ca. 9.84\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:35:28', '2024-01-13 22:35:28'),
(290, 'attributes', 0, NULL, NULL, NULL, '65a2adc0835c9-pprt-57679587319049162', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:35:28', '2024-01-13 22:35:28'),
(291, 'attributes', 0, NULL, NULL, NULL, '65a2adc0835c9-pprt-57679587319049162', 'WEIGHT', '1.3 kg (2.87 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:35:28', '2024-01-13 22:35:28'),
(292, 'attributes', 0, NULL, NULL, NULL, '65a2adc0835c9-pprt-57679587319049162', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:35:28', '2024-01-13 22:35:28'),
(293, 'custom value', 0, NULL, '1', 'Notice', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:36:57', '2024-01-13 22:36:57'),
(294, 'custom value', 0, NULL, '1', 'Notice', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, 'Norm: JIS B6339', '', 'en', '293', '2024-01-13 22:36:57', '2024-01-13 22:36:57'),
(295, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:37:06', '2024-01-13 22:37:06'),
(296, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, '-', '', 'en', '295', '2024-01-13 22:37:06', '2024-01-13 22:37:06'),
(297, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:38:23', '2024-01-13 22:38:23'),
(298, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, 'HAUBEX can be used on practically any machine tool and flexibly throughout the entire production process. With the tool magazine as the storage medium, in which the workholding hood is stored together with the self-centering vise and workpiece blank and is automatically exchanged into the machine, HAUBEX manages completely without additional handling and storage systems.', '', 'en', '297', '2024-01-13 22:38:23', '2024-01-13 22:38:23'),
(299, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, 'Application possibilities', 'Can be retrofitted to almost any type of machine tool and can be ideally integrated into the production environment thanks to the elimination of additional automation elements.', '', 'en', '297', '2024-01-13 22:38:23', '2024-01-13 22:38:23'),
(300, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, 'Flexibility', 'Like any common tool, not bound to a specific machine tool and thus can be used practically throughout the entire production process.', '', 'en', '297', '2024-01-13 22:38:23', '2024-01-13 22:38:23'),
(301, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, 'Cost-efficiency', 'Significant increase in added value of in-house production even with small quantities and low investments.', '', 'en', '297', '2024-01-13 22:38:23', '2024-01-13 22:38:23'),
(302, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:38:49', '2024-01-13 22:38:49'),
(303, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, NULL, '170516032965a2ae89a059f_61500-BT40_1.pdf', 'en', '302', '2024-01-13 22:38:49', '2024-01-13 22:38:49'),
(304, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:40:01', '2024-01-13 22:40:01'),
(306, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2adc0835c9-pprt-57679587319049162', NULL, NULL, NULL, NULL, '170516043565a2aef390f07_2-2022_Haubex_EN_2.pdf', 'en', '304', '2024-01-13 22:40:35', '2024-01-13 22:40:35'),
(307, 'attributes', 0, NULL, NULL, NULL, '65a2afc80d595-pprt-91487894225840128', 'TYPE', 'Steep Taper', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:44:08', '2024-01-13 22:44:08'),
(308, 'attributes', 0, NULL, NULL, NULL, '65a2afc80d595-pprt-91487894225840128', 'TOOL LENGTH', 'ca. 240 mm (ca. 9.45\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:44:08', '2024-01-13 22:44:08'),
(309, 'attributes', 0, NULL, NULL, NULL, '65a2afc80d595-pprt-91487894225840128', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:44:08', '2024-01-13 22:44:08'),
(310, 'attributes', 0, NULL, NULL, NULL, '65a2afc80d595-pprt-91487894225840128', 'WEIGHT', '1.1 kg (2.43 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:44:08', '2024-01-13 22:44:08'),
(311, 'attributes', 0, NULL, NULL, NULL, '65a2afc80d595-pprt-91487894225840128', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:44:08', '2024-01-13 22:44:08'),
(312, 'custom value', 0, NULL, '1', 'Notice', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:44:32', '2024-01-13 22:44:32'),
(313, 'custom value', 0, NULL, '1', 'Notice', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, 'Norm: DIN ISO 7388-1', '', 'en', '312', '2024-01-13 22:44:32', '2024-01-13 22:44:32'),
(314, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:44:41', '2024-01-13 22:44:41'),
(315, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, '-', '', 'en', '314', '2024-01-13 22:44:41', '2024-01-13 22:44:41'),
(316, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:45:32', '2024-01-13 22:45:32'),
(317, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, 'HAUBEX can be used on practically any machine tool and flexibly throughout the entire production process. With the tool magazine as the storage medium, in which the workholding hood is stored together with the self-centering vise and workpiece blank and is automatically exchanged into the machine, HAUBEX manages completely without additional handling and storage systems.', '', 'en', '316', '2024-01-13 22:45:32', '2024-01-13 22:45:32'),
(318, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, 'Application possibilities', 'Can be retrofitted to almost any type of machine tool and can be ideally integrated into the production environment thanks to the elimination of additional automation elements.', '', 'en', '316', '2024-01-13 22:45:32', '2024-01-13 22:45:32'),
(319, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, 'Flexibility', 'Like any common tool, not bound to a specific machine tool and thus can be used practically throughout the entire production process.', '', 'en', '316', '2024-01-13 22:45:32', '2024-01-13 22:45:32'),
(320, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, 'Cost-efficiency', 'Significant increase in added value of in-house production even with small quantities and low investments.', '', 'en', '316', '2024-01-13 22:45:32', '2024-01-13 22:45:32'),
(321, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:46:12', '2024-01-13 22:46:12'),
(323, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, NULL, '170516077465a2b0466a083_61500-SK40_1.pdf', 'en', '321', '2024-01-13 22:46:14', '2024-01-13 22:46:14'),
(324, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:47:39', '2024-01-13 22:47:39'),
(325, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2afc80d595-pprt-91487894225840128', NULL, NULL, NULL, NULL, '170516085965a2b09bda9d7_2-2022_Haubex_EN_5.pdf', 'en', '324', '2024-01-13 22:47:39', '2024-01-13 22:47:39'),
(326, 'attributes', 0, NULL, NULL, NULL, '65a2b12aa46a0-pprt-32621747406073089', 'TYPE', 'Steep Taper', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:50:02', '2024-01-13 22:50:02'),
(327, 'attributes', 0, NULL, NULL, NULL, '65a2b12aa46a0-pprt-32621747406073089', 'TOOL LENGTH', '240 mm (9.45\")', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:50:02', '2024-01-13 22:50:02'),
(328, 'attributes', 0, NULL, NULL, NULL, '65a2b12aa46a0-pprt-32621747406073089', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:50:02', '2024-01-13 22:50:02'),
(329, 'attributes', 0, NULL, NULL, NULL, '65a2b12aa46a0-pprt-32621747406073089', 'WEIGHT', '1.1 kg (2.43 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:50:02', '2024-01-13 22:50:02'),
(330, 'attributes', 0, NULL, NULL, NULL, '65a2b12aa46a0-pprt-32621747406073089', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:50:02', '2024-01-13 22:50:02'),
(331, 'custom value', 0, NULL, '1', 'Notice', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:50:41', '2024-01-13 22:50:41'),
(332, 'custom value', 0, NULL, '1', 'Notice', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, 'Norm: ANSI / ASME B 5.50', '', 'en', '331', '2024-01-13 22:50:41', '2024-01-13 22:50:41'),
(333, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:50:49', '2024-01-13 22:50:49'),
(334, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, '-', '', 'en', '333', '2024-01-13 22:50:49', '2024-01-13 22:50:49'),
(335, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:51:39', '2024-01-13 22:51:39'),
(336, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, 'HAUBEX can be used on practically any machine tool and flexibly throughout the entire production process. With the tool magazine as the storage medium, in which the workholding hood is stored together with the self-centering vise and workpiece blank and is automatically exchanged into the machine, HAUBEX manages completely without additional handling and storage systems.', '', 'en', '335', '2024-01-13 22:51:39', '2024-01-13 22:51:39'),
(337, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, 'Application possibilities', 'Can be retrofitted to almost any type of machine tool and can be ideally integrated into the production environment thanks to the elimination of additional automation elements.', '', 'en', '335', '2024-01-13 22:51:39', '2024-01-13 22:51:39'),
(338, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, 'Flexibility', 'Like any common tool, not bound to a specific machine tool and thus can be used practically throughout the entire production process.', '', 'en', '335', '2024-01-13 22:51:39', '2024-01-13 22:51:39'),
(339, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, 'Cost-efficiency', 'Significant increase in added value of in-house production even with small quantities and low investments.', '', 'en', '335', '2024-01-13 22:51:39', '2024-01-13 22:51:39'),
(340, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:52:19', '2024-01-13 22:52:19'),
(341, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2b12aa46a0-pprt-32621747406073089', NULL, NULL, NULL, NULL, '170516113965a2b1b3cee16_61500-CAT40_neu_1.pdf', 'en', '340', '2024-01-13 22:52:19', '2024-01-13 22:52:19'),
(342, 'attributes', 0, NULL, NULL, NULL, '65a2b2551fbab-pprt-61335727016400831', 'TYPE OF HEXAGONAL WRENCH', 'Hexagon socket', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:55:01', '2024-01-13 22:55:01'),
(343, 'attributes', 0, NULL, NULL, NULL, '65a2b2551fbab-pprt-61335727016400831', 'WRENCH SIZE', 'SW 8', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:55:01', '2024-01-13 22:55:01'),
(344, 'attributes', 0, NULL, NULL, NULL, '65a2b2551fbab-pprt-61335727016400831', 'SCREW SIZE', 'M16', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:55:01', '2024-01-13 22:55:01'),
(345, 'attributes', 0, NULL, NULL, NULL, '65a2b2551fbab-pprt-61335727016400831', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:55:01', '2024-01-13 22:55:01'),
(346, 'custom value', 0, NULL, '1', 'Notice', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:55:19', '2024-01-13 22:55:19'),
(347, 'custom value', 0, NULL, '1', 'Notice', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, NULL, '-', '', 'en', '346', '2024-01-13 22:55:19', '2024-01-13 22:55:19'),
(348, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:55:29', '2024-01-13 22:55:29'),
(349, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, NULL, '-', '', 'en', '348', '2024-01-13 22:55:29', '2024-01-13 22:55:29'),
(350, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:56:29', '2024-01-13 22:56:29'),
(351, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '350', '2024-01-13 22:56:29', '2024-01-13 22:56:29'),
(352, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '350', '2024-01-13 22:56:29', '2024-01-13 22:56:29'),
(353, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '350', '2024-01-13 22:56:29', '2024-01-13 22:56:29'),
(354, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b2551fbab-pprt-61335727016400831', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '350', '2024-01-13 22:56:29', '2024-01-13 22:56:29'),
(355, 'attributes', 0, NULL, NULL, NULL, '65a2b3137554b-pprt-59396672069551994', 'WRENCH SIZE', 'SW 15', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:58:11', '2024-01-13 22:58:11'),
(356, 'attributes', 0, NULL, NULL, NULL, '65a2b3137554b-pprt-59396672069551994', 'TYPE OF HEXAGONAL WRENCH', 'External hexagon', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:58:11', '2024-01-13 22:58:11'),
(357, 'attributes', 0, NULL, NULL, NULL, '65a2b3137554b-pprt-59396672069551994', 'SCREW SIZE', 'M20', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:58:11', '2024-01-13 22:58:11'),
(358, 'attributes', 0, NULL, NULL, NULL, '65a2b3137554b-pprt-59396672069551994', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 22:58:11', '2024-01-13 22:58:11'),
(359, 'custom value', 0, NULL, '1', 'Notice', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:58:27', '2024-01-13 22:58:27'),
(360, 'custom value', 0, NULL, '1', 'Notice', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, NULL, '-', '', 'en', '359', '2024-01-13 22:58:27', '2024-01-13 22:58:27'),
(361, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:58:37', '2024-01-13 22:58:37'),
(362, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, NULL, '-', '', 'en', '361', '2024-01-13 22:58:37', '2024-01-13 22:58:37'),
(363, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 22:59:41', '2024-01-13 22:59:41'),
(364, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '363', '2024-01-13 22:59:41', '2024-01-13 22:59:41'),
(365, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '363', '2024-01-13 22:59:41', '2024-01-13 22:59:41'),
(366, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '363', '2024-01-13 22:59:41', '2024-01-13 22:59:41'),
(367, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3137554b-pprt-59396672069551994', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '363', '2024-01-13 22:59:41', '2024-01-13 22:59:41'),
(368, 'attributes', 0, NULL, NULL, NULL, '65a2b3d0965f8-pprt-54224778697573233', 'WRENCH SIZE', 'SW 12', NULL, NULL, NULL, NULL, NULL, '2024-01-13 23:01:20', '2024-01-13 23:01:20'),
(369, 'attributes', 0, NULL, NULL, NULL, '65a2b3d0965f8-pprt-54224778697573233', 'TYPE OF HEXAGONAL WRENCH', 'External hexagon', NULL, NULL, NULL, NULL, NULL, '2024-01-13 23:01:20', '2024-01-13 23:01:20'),
(370, 'attributes', 0, NULL, NULL, NULL, '65a2b3d0965f8-pprt-54224778697573233', 'SCREW SIZE', 'M20', NULL, NULL, NULL, NULL, NULL, '2024-01-13 23:01:20', '2024-01-13 23:01:20'),
(371, 'attributes', 0, NULL, NULL, NULL, '65a2b3d0965f8-pprt-54224778697573233', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-13 23:01:20', '2024-01-13 23:01:20'),
(372, 'custom value', 0, NULL, '1', 'Notice', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 23:01:38', '2024-01-13 23:01:38'),
(373, 'custom value', 0, NULL, '1', 'Notice', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, NULL, '-', '', 'en', '372', '2024-01-13 23:01:38', '2024-01-13 23:01:38'),
(374, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 23:01:47', '2024-01-13 23:01:47'),
(375, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, NULL, '-', '', 'en', '374', '2024-01-13 23:01:47', '2024-01-13 23:01:47'),
(376, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-13 23:02:44', '2024-01-13 23:02:44'),
(377, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '376', '2024-01-13 23:02:44', '2024-01-13 23:02:44'),
(378, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '376', '2024-01-13 23:02:44', '2024-01-13 23:02:44'),
(379, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '376', '2024-01-13 23:02:44', '2024-01-13 23:02:44'),
(380, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2b3d0965f8-pprt-54224778697573233', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '376', '2024-01-13 23:02:44', '2024-01-13 23:02:44'),
(381, 'attributes', 0, NULL, NULL, NULL, '65a2cdfb485ab-pprt-92320619783752205', 'FOR GRID SIZE', '52', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:52:59', '2024-01-14 00:52:59'),
(382, 'attributes', 0, NULL, NULL, NULL, '65a2cdfb485ab-pprt-92320619783752205', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:52:59', '2024-01-14 00:52:59'),
(383, 'attributes', 0, NULL, NULL, NULL, '65a2cdfb485ab-pprt-92320619783752205', 'WEIGHT', '2.8 kg (6.17 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:52:59', '2024-01-14 00:52:59'),
(384, 'attributes', 0, NULL, NULL, NULL, '65a2cdfb485ab-pprt-92320619783752205', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:52:59', '2024-01-14 00:52:59'),
(385, 'custom value', 0, NULL, '1', 'Notice', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 00:53:54', '2024-01-14 00:53:54'),
(386, 'custom value', 0, NULL, '1', 'Notice', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, '-', '', 'en', '385', '2024-01-14 00:53:54', '2024-01-14 00:53:54'),
(387, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 00:54:06', '2024-01-14 00:54:06'),
(388, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, '-', '', 'en', '387', '2024-01-14 00:54:06', '2024-01-14 00:54:06'),
(389, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 00:55:05', '2024-01-14 00:55:05'),
(390, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '389', '2024-01-14 00:55:05', '2024-01-14 00:55:05'),
(391, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '389', '2024-01-14 00:55:05', '2024-01-14 00:55:05'),
(392, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '389', '2024-01-14 00:55:05', '2024-01-14 00:55:05'),
(393, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '389', '2024-01-14 00:55:05', '2024-01-14 00:55:05'),
(394, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 00:55:26', '2024-01-14 00:55:26'),
(395, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, NULL, '170516852665a2ce8eaa4bf_44521_cad.zip', 'en', '394', '2024-01-14 00:55:26', '2024-01-14 00:55:26'),
(396, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 00:57:42', '2024-01-14 00:57:42'),
(397, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2cdfb485ab-pprt-92320619783752205', NULL, NULL, NULL, NULL, '170516866265a2cf16cff1a_44521_0_1.pdf', 'en', '396', '2024-01-14 00:57:42', '2024-01-14 00:57:42'),
(398, 'attributes', 0, NULL, NULL, NULL, '65a2cf7dc1f4e-pprt-10336231106004670', 'FOR GRID SIZE', '96', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:59:25', '2024-01-14 00:59:25'),
(399, 'attributes', 0, NULL, NULL, NULL, '65a2cf7dc1f4e-pprt-10336231106004670', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:59:25', '2024-01-14 00:59:25'),
(400, 'attributes', 0, NULL, NULL, NULL, '65a2cf7dc1f4e-pprt-10336231106004670', 'WEIGHT', '6.8 kg (14.99 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:59:25', '2024-01-14 00:59:25'),
(401, 'attributes', 0, NULL, NULL, NULL, '65a2cf7dc1f4e-pprt-10336231106004670', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 00:59:25', '2024-01-14 00:59:25'),
(402, 'custom value', 0, NULL, '1', 'Notice', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:00:03', '2024-01-14 01:00:03'),
(403, 'custom value', 0, NULL, '1', 'Notice', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, '-', '', 'en', '402', '2024-01-14 01:00:03', '2024-01-14 01:00:03'),
(404, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:00:15', '2024-01-14 01:00:15'),
(405, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, '-', '', 'en', '404', '2024-01-14 01:00:15', '2024-01-14 01:00:15'),
(406, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:01:14', '2024-01-14 01:01:14'),
(407, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '406', '2024-01-14 01:01:14', '2024-01-14 01:01:14'),
(408, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '406', '2024-01-14 01:01:14', '2024-01-14 01:01:14'),
(409, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '406', '2024-01-14 01:01:14', '2024-01-14 01:01:14'),
(410, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '406', '2024-01-14 01:01:14', '2024-01-14 01:01:14'),
(411, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:01:36', '2024-01-14 01:01:36'),
(412, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, NULL, '170516889665a2d000b9f3c_44961-10_cad.zip', 'en', '411', '2024-01-14 01:01:36', '2024-01-14 01:01:36'),
(413, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:01:51', '2024-01-14 01:01:51'),
(414, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2cf7dc1f4e-pprt-10336231106004670', NULL, NULL, NULL, NULL, '170516891165a2d00fae568_44961-10_1.pdf', 'en', '413', '2024-01-14 01:01:51', '2024-01-14 01:01:51'),
(415, 'attributes', 0, NULL, NULL, NULL, '65a2d12e834e4-pprt-79590701441660054', 'FOR GRID SIZE', '52', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:06:38', '2024-01-14 01:06:38'),
(416, 'attributes', 0, NULL, NULL, NULL, '65a2d12e834e4-pprt-79590701441660054', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:06:38', '2024-01-14 01:06:38'),
(417, 'attributes', 0, NULL, NULL, NULL, '65a2d12e834e4-pprt-79590701441660054', 'WEIGHT', '2.8 kg', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:06:38', '2024-01-14 01:06:38'),
(418, 'attributes', 0, NULL, NULL, NULL, '65a2d12e834e4-pprt-79590701441660054', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:06:38', '2024-01-14 01:06:38'),
(419, 'custom value', 0, NULL, '1', 'Notice', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:07:00', '2024-01-14 01:07:00'),
(420, 'custom value', 0, NULL, '1', 'Notice', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, '-', '', 'en', '419', '2024-01-14 01:07:00', '2024-01-14 01:07:00'),
(421, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:07:08', '2024-01-14 01:07:08'),
(422, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, '-', '', 'en', '421', '2024-01-14 01:07:08', '2024-01-14 01:07:08'),
(423, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:07:52', '2024-01-14 01:07:52'),
(424, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '423', '2024-01-14 01:07:52', '2024-01-14 01:07:52'),
(425, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '423', '2024-01-14 01:07:52', '2024-01-14 01:07:52'),
(426, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '423', '2024-01-14 01:07:52', '2024-01-14 01:07:52'),
(427, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '423', '2024-01-14 01:07:52', '2024-01-14 01:07:52'),
(428, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:08:13', '2024-01-14 01:08:13'),
(429, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, NULL, '170516929365a2d18d19500_44521-10_cad.zip', 'en', '428', '2024-01-14 01:08:13', '2024-01-14 01:08:13'),
(430, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:08:27', '2024-01-14 01:08:27'),
(431, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2d12e834e4-pprt-79590701441660054', NULL, NULL, NULL, NULL, '170516930765a2d19becf6e_44521_0_2.pdf', 'en', '430', '2024-01-14 01:08:27', '2024-01-14 01:08:27'),
(432, 'attributes', 0, NULL, NULL, NULL, '65a2d1edb71bf-pprt-99228314692654698', 'FOR GRID SIZE', '96', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:09:49', '2024-01-14 01:09:49'),
(433, 'attributes', 0, NULL, NULL, NULL, '65a2d1edb71bf-pprt-99228314692654698', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:09:49', '2024-01-14 01:09:49'),
(434, 'attributes', 0, NULL, NULL, NULL, '65a2d1edb71bf-pprt-99228314692654698', 'WEIGHT', '6.8 kg (14.99 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:09:49', '2024-01-14 01:09:49'),
(435, 'attributes', 0, NULL, NULL, NULL, '65a2d1edb71bf-pprt-99228314692654698', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:09:49', '2024-01-14 01:09:49'),
(441, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:11:05', '2024-01-14 01:11:05'),
(442, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, NULL, '170516946565a2d239a01e6_44961_cad.zip', 'en', '441', '2024-01-14 01:11:05', '2024-01-14 01:11:05'),
(443, 'custom value', 0, NULL, '1', 'Notice', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:11:16', '2024-01-14 01:11:16'),
(444, 'custom value', 0, NULL, '1', 'Notice', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, '-', '', 'en', '443', '2024-01-14 01:11:16', '2024-01-14 01:11:16'),
(445, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:11:27', '2024-01-14 01:11:27'),
(446, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, '-', '', 'en', '445', '2024-01-14 01:11:27', '2024-01-14 01:11:27'),
(447, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:12:05', '2024-01-14 01:12:05'),
(448, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '447', '2024-01-14 01:12:05', '2024-01-14 01:12:05'),
(449, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '447', '2024-01-14 01:12:05', '2024-01-14 01:12:05');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(450, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '447', '2024-01-14 01:12:05', '2024-01-14 01:12:05'),
(451, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '447', '2024-01-14 01:12:05', '2024-01-14 01:12:05'),
(452, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:12:32', '2024-01-14 01:12:32'),
(453, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a2d1edb71bf-pprt-99228314692654698', NULL, NULL, NULL, NULL, '170516955265a2d29007c4f_44961_0.pdf', 'en', '452', '2024-01-14 01:12:32', '2024-01-14 01:12:32'),
(454, 'attributes', 0, NULL, NULL, NULL, '65a2d3438769b-pprt-98725720307618551', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:15:31', '2024-01-14 01:15:31'),
(455, 'attributes', 0, NULL, NULL, NULL, '65a2d3438769b-pprt-98725720307618551', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:15:31', '2024-01-14 01:15:31'),
(456, 'custom value', 0, NULL, '1', 'Notice', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:15:48', '2024-01-14 01:15:48'),
(457, 'custom value', 0, NULL, '1', 'Notice', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, NULL, '-', '', 'en', '456', '2024-01-14 01:15:48', '2024-01-14 01:15:48'),
(458, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:15:58', '2024-01-14 01:15:58'),
(459, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, NULL, '-', '', 'en', '458', '2024-01-14 01:15:58', '2024-01-14 01:15:58'),
(460, 'custom value', 0, NULL, '1', 'Quick•Point® Zero-Point Clamping System', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:17:04', '2024-01-14 01:17:04'),
(461, 'custom value', 0, NULL, '1', 'Quick•Point® Zero-Point Clamping System', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '460', '2024-01-14 01:17:04', '2024-01-14 01:17:04'),
(462, 'custom value', 0, NULL, '1', 'Quick•Point® Zero-Point Clamping System', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '460', '2024-01-14 01:17:04', '2024-01-14 01:17:04'),
(463, 'custom value', 0, NULL, '1', 'Quick•Point® Zero-Point Clamping System', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '460', '2024-01-14 01:17:04', '2024-01-14 01:17:04'),
(464, 'custom value', 0, NULL, '1', 'Quick•Point® Zero-Point Clamping System', '65a2d3438769b-pprt-98725720307618551', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '460', '2024-01-14 01:17:04', '2024-01-14 01:17:04'),
(465, 'custom value', 0, NULL, '1', 'Notice', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:22:00', '2024-01-14 01:22:00'),
(466, 'custom value', 0, NULL, '1', 'Notice', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, NULL, '-', '', 'en', '465', '2024-01-14 01:22:00', '2024-01-14 01:22:00'),
(467, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:22:19', '2024-01-14 01:22:19'),
(468, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, NULL, '-', '', 'en', '467', '2024-01-14 01:22:19', '2024-01-14 01:22:19'),
(469, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:27:37', '2024-01-14 01:27:37'),
(470, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '469', '2024-01-14 01:27:37', '2024-01-14 01:27:37'),
(471, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '469', '2024-01-14 01:27:37', '2024-01-14 01:27:37'),
(472, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '469', '2024-01-14 01:27:37', '2024-01-14 01:27:37'),
(473, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d4b10fec9-pprt-15455670843189480', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '469', '2024-01-14 01:27:37', '2024-01-14 01:27:37'),
(474, 'attributes', 0, NULL, NULL, NULL, '65a2d6b45f4c5-pprt-86879937730105903', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:30:12', '2024-01-14 01:30:12'),
(475, 'attributes', 0, NULL, NULL, NULL, '65a2d6b45f4c5-pprt-86879937730105903', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:30:12', '2024-01-14 01:30:12'),
(476, 'custom value', 0, NULL, '1', 'Notice', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:30:31', '2024-01-14 01:30:31'),
(477, 'custom value', 0, NULL, '1', 'Notice', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, '-', '', 'en', '476', '2024-01-14 01:30:31', '2024-01-14 01:30:31'),
(478, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:30:56', '2024-01-14 01:30:56'),
(479, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, '10 x Abdeckungen', '', 'en', '478', '2024-01-14 01:30:56', '2024-01-14 01:30:56'),
(480, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:32:00', '2024-01-14 01:32:00'),
(481, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '480', '2024-01-14 01:32:00', '2024-01-14 01:32:00'),
(482, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '480', '2024-01-14 01:32:00', '2024-01-14 01:32:00'),
(483, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '480', '2024-01-14 01:32:00', '2024-01-14 01:32:00'),
(484, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '480', '2024-01-14 01:32:00', '2024-01-14 01:32:00'),
(485, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:32:59', '2024-01-14 01:32:59'),
(486, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2d6b45f4c5-pprt-86879937730105903', NULL, NULL, NULL, NULL, '170517077965a2d75b176b9_Anleitung_Quick-Point-Modulplatten_DE_1.pdf', 'en', '485', '2024-01-14 01:32:59', '2024-01-14 01:32:59'),
(487, 'attributes', 0, NULL, NULL, NULL, '65a2d7f793411-pprt-45230448123123487', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:35:35', '2024-01-14 01:35:35'),
(488, 'custom value', 0, NULL, '1', 'Notice', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:36:08', '2024-01-14 01:36:08'),
(489, 'custom value', 0, NULL, '1', 'Notice', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, '-', '', 'en', '488', '2024-01-14 01:36:08', '2024-01-14 01:36:08'),
(490, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:36:49', '2024-01-14 01:36:49'),
(491, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, '2 x Connectors, 4 x Sealing Caps, 4 x Socket Head Screws', '', 'en', '490', '2024-01-14 01:36:49', '2024-01-14 01:36:49'),
(492, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:37:49', '2024-01-14 01:37:49'),
(493, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '492', '2024-01-14 01:37:49', '2024-01-14 01:37:49'),
(494, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '492', '2024-01-14 01:37:49', '2024-01-14 01:37:49'),
(495, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '492', '2024-01-14 01:37:49', '2024-01-14 01:37:49'),
(496, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '492', '2024-01-14 01:37:49', '2024-01-14 01:37:49'),
(497, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:38:39', '2024-01-14 01:38:39'),
(498, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2d7f793411-pprt-45230448123123487', NULL, NULL, NULL, NULL, '170517111965a2d8af77d1f_Anleitung_Quick-Point-Modulplatten_EN.pdf', 'en', '497', '2024-01-14 01:38:39', '2024-01-14 01:38:39'),
(499, 'attributes', 0, NULL, NULL, NULL, '65a2d9d35bd83-pprt-15548368347853931', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:43:31', '2024-01-14 01:43:31'),
(500, 'attributes', 0, NULL, NULL, NULL, '65a2d9d35bd83-pprt-15548368347853931', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 01:43:31', '2024-01-14 01:43:31'),
(501, 'custom value', 0, NULL, '1', 'Notice', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:44:49', '2024-01-14 01:44:49'),
(502, 'custom value', 0, NULL, '1', 'Notice', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, '-', '', 'en', '501', '2024-01-14 01:44:49', '2024-01-14 01:44:49'),
(503, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:45:31', '2024-01-14 01:45:31'),
(504, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, '1 x Pressure Bolt, 2 x Connecting Pieces, 4 x Sealing Caps, 4 x Socket Head Screws', '', 'en', '503', '2024-01-14 01:45:31', '2024-01-14 01:45:31'),
(505, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:46:23', '2024-01-14 01:46:23'),
(506, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '505', '2024-01-14 01:46:23', '2024-01-14 01:46:23'),
(507, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '505', '2024-01-14 01:46:23', '2024-01-14 01:46:23'),
(508, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '505', '2024-01-14 01:46:23', '2024-01-14 01:46:23'),
(509, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '505', '2024-01-14 01:46:23', '2024-01-14 01:46:23'),
(510, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 01:47:02', '2024-01-14 01:47:02'),
(511, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a2d9d35bd83-pprt-15548368347853931', NULL, NULL, NULL, NULL, '170517162265a2daa6bfbe6_Anleitung_Quick-Point-Modulplatten_DE.pdf', 'en', '510', '2024-01-14 01:47:02', '2024-01-14 01:47:02'),
(512, 'attributes', 0, NULL, NULL, NULL, '65a355d3ee518-pprt-37644576856140097', 'DIMENSIONS', '126 x 126 x 27 mm (4.96\" x 4.96\" x 1.06\")', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:32:36', '2024-01-14 10:32:36'),
(513, 'attributes', 0, NULL, NULL, NULL, '65a355d3ee518-pprt-37644576856140097', 'DIMENSIONS', '126 x 126 x 27 mm (4.96\" x 4.96\" x 1.06\")', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:32:36', '2024-01-14 10:32:36'),
(514, 'attributes', 0, NULL, NULL, NULL, '65a355d3ee518-pprt-37644576856140097', 'MEASURING LENGTH', '116 mm per side ()', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:32:36', '2024-01-14 10:32:36'),
(515, 'attributes', 0, NULL, NULL, NULL, '65a355d3ee518-pprt-37644576856140097', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:32:36', '2024-01-14 10:32:36'),
(516, 'attributes', 0, NULL, NULL, NULL, '65a355d3ee518-pprt-37644576856140097', 'WEIGHT', '2.8 kg (6.17 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:32:36', '2024-01-14 10:32:36'),
(517, 'attributes', 0, NULL, NULL, NULL, '65a355d3ee518-pprt-37644576856140097', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:32:36', '2024-01-14 10:32:36'),
(518, 'custom value', 0, NULL, '1', 'Notice', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:32:57', '2024-01-14 10:32:57'),
(519, 'custom value', 0, NULL, '1', 'Notice', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, '-', '', 'en', '518', '2024-01-14 10:32:57', '2024-01-14 10:32:57'),
(520, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:33:08', '2024-01-14 10:33:08'),
(521, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, '-', '', 'en', '520', '2024-01-14 10:33:08', '2024-01-14 10:33:08'),
(522, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:33:46', '2024-01-14 10:33:46'),
(523, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '522', '2024-01-14 10:33:46', '2024-01-14 10:33:46'),
(524, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '522', '2024-01-14 10:33:46', '2024-01-14 10:33:46'),
(525, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '522', '2024-01-14 10:33:46', '2024-01-14 10:33:46'),
(526, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '522', '2024-01-14 10:33:46', '2024-01-14 10:33:46'),
(527, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:34:08', '2024-01-14 10:34:08'),
(528, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, NULL, '170520324865a356300e509_44962_cad.zip', 'en', '527', '2024-01-14 10:34:08', '2024-01-14 10:34:08'),
(529, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:34:23', '2024-01-14 10:34:23'),
(530, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a355d3ee518-pprt-37644576856140097', NULL, NULL, NULL, NULL, '170520326365a3563fb4351_44962.pdf', 'en', '529', '2024-01-14 10:34:23', '2024-01-14 10:34:23'),
(531, 'attributes', 0, NULL, NULL, NULL, '65a356a85e3ae-pprt-39974442282038948', 'DIMENSIONS', '80 x 80 x 27 mm (3.15\" x 3.15\" x 1.06\")', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:36:08', '2024-01-14 10:36:08'),
(532, 'attributes', 0, NULL, NULL, NULL, '65a356a85e3ae-pprt-39974442282038948', 'MEASURING LENGTH', '70 mm per side ()', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:36:08', '2024-01-14 10:36:08'),
(533, 'attributes', 0, NULL, NULL, NULL, '65a356a85e3ae-pprt-39974442282038948', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:36:08', '2024-01-14 10:36:08'),
(534, 'attributes', 0, NULL, NULL, NULL, '65a356a85e3ae-pprt-39974442282038948', 'WEIGHT', '1.2 kg (2.65 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:36:08', '2024-01-14 10:36:08'),
(535, 'attributes', 0, NULL, NULL, NULL, '65a356a85e3ae-pprt-39974442282038948', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:36:08', '2024-01-14 10:36:08'),
(536, 'custom value', 0, NULL, '1', 'Notice', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:36:23', '2024-01-14 10:36:23'),
(537, 'custom value', 0, NULL, '1', 'Notice', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, '-', '', 'en', '536', '2024-01-14 10:36:23', '2024-01-14 10:36:23'),
(538, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:36:33', '2024-01-14 10:36:33'),
(539, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, '-', '', 'en', '538', '2024-01-14 10:36:33', '2024-01-14 10:36:33'),
(540, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:36:54', '2024-01-14 10:36:54'),
(541, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '540', '2024-01-14 10:36:54', '2024-01-14 10:36:54'),
(542, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '540', '2024-01-14 10:36:54', '2024-01-14 10:36:54'),
(543, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '540', '2024-01-14 10:36:54', '2024-01-14 10:36:54'),
(544, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '540', '2024-01-14 10:36:54', '2024-01-14 10:36:54'),
(545, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:37:17', '2024-01-14 10:37:17'),
(546, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, NULL, '170520343765a356edd3c65_44522_cad.zip', 'en', '545', '2024-01-14 10:37:17', '2024-01-14 10:37:17'),
(547, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:37:39', '2024-01-14 10:37:39'),
(548, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a356a85e3ae-pprt-39974442282038948', NULL, NULL, NULL, NULL, '170520345965a3570373432_44522.pdf', 'en', '547', '2024-01-14 10:37:39', '2024-01-14 10:37:39'),
(549, 'attributes', 0, NULL, NULL, NULL, '65a3585a7b3ae-pprt-16305145719271613', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:43:22', '2024-01-14 10:43:22'),
(550, 'attributes', 0, NULL, NULL, NULL, '65a3585a7b3ae-pprt-16305145719271613', 'MATERIAL', 'Aluminum', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:43:22', '2024-01-14 10:43:22'),
(551, 'custom value', 0, NULL, '1', 'Notice', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:43:40', '2024-01-14 10:43:40'),
(552, 'custom value', 0, NULL, '1', 'Notice', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, NULL, 'Spannhebel im Lieferumfang des Quick•Point® HAUBEX Nullpunktspannsystems enthalten.', '', 'en', '551', '2024-01-14 10:43:40', '2024-01-14 10:43:40'),
(553, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:43:54', '2024-01-14 10:43:54'),
(554, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, NULL, '-', '', 'en', '553', '2024-01-14 10:43:54', '2024-01-14 10:43:54'),
(555, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:44:44', '2024-01-14 10:44:44'),
(556, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, NULL, 'HAUBEX can be used on practically any machine tool and flexibly throughout the entire production process. With the tool magazine as the storage medium, in which the workholding hood is stored together with the self-centering vise and workpiece blank and is automatically exchanged into the machine, HAUBEX manages completely without additional handling and storage systems.', '', 'en', '555', '2024-01-14 10:44:44', '2024-01-14 10:44:44'),
(557, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, 'Application possibilities', 'Can be retrofitted to almost any type of machine tool and can be ideally integrated into the production environment thanks to the elimination of additional automation elements.', '', 'en', '555', '2024-01-14 10:44:44', '2024-01-14 10:44:44'),
(558, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, 'Flexibility', 'Like any common tool, not bound to a specific machine tool and thus can be used practically throughout the entire production process.', '', 'en', '555', '2024-01-14 10:44:44', '2024-01-14 10:44:44'),
(559, 'custom value', 0, NULL, '3', 'HAUBEX Automation System', '65a3585a7b3ae-pprt-16305145719271613', NULL, NULL, 'Cost-efficiency', 'Significant increase in added value of in-house production even with small quantities and low investments.', '', 'en', '555', '2024-01-14 10:44:44', '2024-01-14 10:44:44'),
(560, 'attributes', 0, NULL, NULL, NULL, '65a359253db49-pprt-61496386010842213', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:46:45', '2024-01-14 10:46:45'),
(561, 'attributes', 0, NULL, NULL, NULL, '65a359253db49-pprt-61496386010842213', 'WEIGHT', '0.93 kg (2.05 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:46:45', '2024-01-14 10:46:45'),
(562, 'attributes', 0, NULL, NULL, NULL, '65a359253db49-pprt-61496386010842213', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:46:45', '2024-01-14 10:46:45'),
(563, 'custom value', 0, NULL, '1', 'Notice', '65a359253db49-pprt-61496386010842213', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:47:13', '2024-01-14 10:47:13'),
(564, 'custom value', 0, NULL, '1', 'Notice', '65a359253db49-pprt-61496386010842213', NULL, NULL, NULL, '-', '', 'en', '563', '2024-01-14 10:47:13', '2024-01-14 10:47:13'),
(565, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a359253db49-pprt-61496386010842213', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:47:24', '2024-01-14 10:47:24'),
(566, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a359253db49-pprt-61496386010842213', NULL, NULL, NULL, '-', '', 'en', '565', '2024-01-14 10:47:24', '2024-01-14 10:47:24'),
(567, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359253db49-pprt-61496386010842213', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:48:08', '2024-01-14 10:48:08'),
(568, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359253db49-pprt-61496386010842213', NULL, NULL, 'Compactness', 'Slim design for easy access', '', 'en', '567', '2024-01-14 10:48:08', '2024-01-14 10:48:08'),
(569, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359253db49-pprt-61496386010842213', NULL, NULL, 'Compatibility', 'Use of commercially available collets', '', 'en', '567', '2024-01-14 10:48:08', '2024-01-14 10:48:08'),
(570, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359253db49-pprt-61496386010842213', NULL, NULL, 'Set-up time savings', 'Fast set-up process thanks to integrated zero-point interface', '', 'en', '567', '2024-01-14 10:48:08', '2024-01-14 10:48:08'),
(571, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a359253db49-pprt-61496386010842213', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:48:26', '2024-01-14 10:48:26'),
(572, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a359253db49-pprt-61496386010842213', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '571', '2024-01-14 10:48:26', '2024-01-14 10:48:26'),
(573, 'attributes', 0, NULL, NULL, NULL, '65a359dd675fe-pprt-53390590724965004', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:49:49', '2024-01-14 10:49:49'),
(574, 'attributes', 0, NULL, NULL, NULL, '65a359dd675fe-pprt-53390590724965004', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:49:49', '2024-01-14 10:49:49'),
(575, 'custom value', 0, NULL, '1', 'Notice', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:50:17', '2024-01-14 10:50:17'),
(576, 'custom value', 0, NULL, '1', 'Notice', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, NULL, '-', '', 'en', '575', '2024-01-14 10:50:17', '2024-01-14 10:50:17'),
(577, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:50:26', '2024-01-14 10:50:26'),
(578, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, NULL, '-', '', 'en', '577', '2024-01-14 10:50:26', '2024-01-14 10:50:26'),
(579, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:50:53', '2024-01-14 10:50:53'),
(580, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, 'Compactness', 'Slim design for easy access', '', 'en', '579', '2024-01-14 10:50:53', '2024-01-14 10:50:53'),
(581, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, 'Compatibility', 'Use of commercially available collets', '', 'en', '579', '2024-01-14 10:50:53', '2024-01-14 10:50:53'),
(582, 'custom value', 0, NULL, '3', 'Benefits Preci•Point', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, 'Set-up time savings', 'Fast set-up process thanks to integrated zero-point interface', '', 'en', '579', '2024-01-14 10:50:53', '2024-01-14 10:50:53'),
(583, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:51:14', '2024-01-14 10:51:14'),
(584, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a359dd675fe-pprt-53390590724965004', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '583', '2024-01-14 10:51:14', '2024-01-14 10:51:14'),
(585, 'attributes', 0, NULL, NULL, NULL, '65a35afe2fafd-pprt-59797981445294285', 'FOR GRID SIZE', '52', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:54:38', '2024-01-14 10:54:38'),
(586, 'attributes', 0, NULL, NULL, NULL, '65a35afe2fafd-pprt-59797981445294285', 'DIAMETER', '16 mm (0.63\")', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:54:38', '2024-01-14 10:54:38'),
(587, 'attributes', 0, NULL, NULL, NULL, '65a35afe2fafd-pprt-59797981445294285', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:54:38', '2024-01-14 10:54:38'),
(588, 'attributes', 0, NULL, NULL, NULL, '65a35afe2fafd-pprt-59797981445294285', 'WEIGHT', '0.26 kg (0.57 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:54:38', '2024-01-14 10:54:38'),
(589, 'attributes', 0, NULL, NULL, NULL, '65a35afe2fafd-pprt-59797981445294285', 'MATERIAL', 'Aluminum', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:54:38', '2024-01-14 10:54:38'),
(590, 'custom value', 0, NULL, '1', 'Notice', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:54:57', '2024-01-14 10:54:57'),
(591, 'custom value', 0, NULL, '1', 'Notice', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, NULL, '-', '', 'en', '590', '2024-01-14 10:54:57', '2024-01-14 10:54:57'),
(592, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:55:07', '2024-01-14 10:55:07'),
(593, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, NULL, '-', '', 'en', '592', '2024-01-14 10:55:07', '2024-01-14 10:55:07'),
(594, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:55:53', '2024-01-14 10:55:53'),
(595, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '594', '2024-01-14 10:55:53', '2024-01-14 10:55:53'),
(596, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '594', '2024-01-14 10:55:53', '2024-01-14 10:55:53'),
(597, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '594', '2024-01-14 10:55:53', '2024-01-14 10:55:53'),
(598, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35afe2fafd-pprt-59797981445294285', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '594', '2024-01-14 10:55:53', '2024-01-14 10:55:53'),
(599, 'attributes', 0, NULL, NULL, NULL, '65a35ba0ce814-pprt-96970708252980421', 'FOR GRID SIZE', '96', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:57:20', '2024-01-14 10:57:20'),
(600, 'attributes', 0, NULL, NULL, NULL, '65a35ba0ce814-pprt-96970708252980421', 'DIAMETER', '20 mm (0.79\")', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:57:20', '2024-01-14 10:57:20'),
(601, 'attributes', 0, NULL, NULL, NULL, '65a35ba0ce814-pprt-96970708252980421', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:57:20', '2024-01-14 10:57:20'),
(602, 'attributes', 0, NULL, NULL, NULL, '65a35ba0ce814-pprt-96970708252980421', 'WEIGHT', '0.3 kg (0.66 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:57:20', '2024-01-14 10:57:20'),
(603, 'attributes', 0, NULL, NULL, NULL, '65a35ba0ce814-pprt-96970708252980421', 'MATERIAL', 'Aluminum', NULL, NULL, NULL, NULL, NULL, '2024-01-14 10:57:20', '2024-01-14 10:57:20'),
(604, 'custom value', 0, NULL, '1', 'Notice', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:57:46', '2024-01-14 10:57:46'),
(605, 'custom value', 0, NULL, '1', 'Notice', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, '-', '', 'en', '604', '2024-01-14 10:57:46', '2024-01-14 10:57:46'),
(606, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:57:56', '2024-01-14 10:57:56'),
(607, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, '-', '', 'en', '606', '2024-01-14 10:57:56', '2024-01-14 10:57:56'),
(608, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:58:18', '2024-01-14 10:58:18'),
(609, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '608', '2024-01-14 10:58:18', '2024-01-14 10:58:18'),
(610, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '608', '2024-01-14 10:58:18', '2024-01-14 10:58:18'),
(611, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '608', '2024-01-14 10:58:18', '2024-01-14 10:58:18'),
(612, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '608', '2024-01-14 10:58:18', '2024-01-14 10:58:18'),
(613, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 10:58:33', '2024-01-14 10:58:33'),
(614, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a35ba0ce814-pprt-96970708252980421', NULL, NULL, NULL, NULL, '170520471365a35be9f1430_46081_cad.zip', 'en', '613', '2024-01-14 10:58:33', '2024-01-14 10:58:33'),
(615, 'attributes', 0, NULL, NULL, NULL, '65a35cf23562f-pprt-94774212001067813', 'SCREW SIZE', 'M8', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:02:58', '2024-01-14 11:02:58'),
(616, 'attributes', 0, NULL, NULL, NULL, '65a35cf23562f-pprt-94774212001067813', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:02:58', '2024-01-14 11:02:58'),
(617, 'attributes', 0, NULL, NULL, NULL, '65a35cf23562f-pprt-94774212001067813', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:02:58', '2024-01-14 11:02:58'),
(618, 'attributes', 0, NULL, NULL, NULL, '65a35d7eefd40-pprt-16309139565077278', 'SCREW SIZE', 'M8', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:05:19', '2024-01-14 11:05:19'),
(619, 'attributes', 0, NULL, NULL, NULL, '65a35d7eefd40-pprt-16309139565077278', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:05:19', '2024-01-14 11:05:19'),
(620, 'attributes', 0, NULL, NULL, NULL, '65a35d7eefd40-pprt-16309139565077278', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:05:19', '2024-01-14 11:05:19'),
(621, 'custom value', 0, NULL, '1', 'Notice', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:05:42', '2024-01-14 11:05:42'),
(622, 'custom value', 0, NULL, '1', 'Notice', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, NULL, '-', '', 'en', '621', '2024-01-14 11:05:42', '2024-01-14 11:05:42'),
(623, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:05:51', '2024-01-14 11:05:51'),
(624, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, NULL, '-', '', 'en', '623', '2024-01-14 11:05:51', '2024-01-14 11:05:51'),
(625, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:06:37', '2024-01-14 11:06:37'),
(626, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '625', '2024-01-14 11:06:37', '2024-01-14 11:06:37'),
(627, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '625', '2024-01-14 11:06:37', '2024-01-14 11:06:37'),
(628, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '625', '2024-01-14 11:06:37', '2024-01-14 11:06:37'),
(629, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35d7eefd40-pprt-16309139565077278', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '625', '2024-01-14 11:06:37', '2024-01-14 11:06:37'),
(630, 'attributes', 0, NULL, NULL, NULL, '65a35e0542673-pprt-40675120517349785', 'SCREW SIZE', 'M8', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:07:33', '2024-01-14 11:07:33'),
(631, 'attributes', 0, NULL, NULL, NULL, '65a35e0542673-pprt-40675120517349785', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:07:33', '2024-01-14 11:07:33'),
(632, 'attributes', 0, NULL, NULL, NULL, '65a35e0542673-pprt-40675120517349785', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:07:33', '2024-01-14 11:07:33'),
(633, 'custom value', 0, NULL, '1', 'Notice', '65a35e0542673-pprt-40675120517349785', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:07:49', '2024-01-14 11:07:49'),
(634, 'custom value', 0, NULL, '1', 'Notice', '65a35e0542673-pprt-40675120517349785', NULL, NULL, NULL, '-', '', 'en', '633', '2024-01-14 11:07:49', '2024-01-14 11:07:49'),
(635, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35e0542673-pprt-40675120517349785', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:08:02', '2024-01-14 11:08:02'),
(637, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35e0542673-pprt-40675120517349785', NULL, NULL, NULL, '-', '', 'en', '635', '2024-01-14 11:08:02', '2024-01-14 11:08:02'),
(638, 'custom value', 0, NULL, '1', 'Makro•Grip® Ultra', '65a35e0542673-pprt-40675120517349785', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:08:23', '2024-01-14 11:08:23'),
(639, 'custom value', 0, NULL, '1', 'Makro•Grip® Ultra', '65a35e0542673-pprt-40675120517349785', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '638', '2024-01-14 11:08:23', '2024-01-14 11:08:23'),
(640, 'custom value', 0, NULL, '1', 'Makro•Grip® Ultra', '65a35e0542673-pprt-40675120517349785', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '638', '2024-01-14 11:08:23', '2024-01-14 11:08:23'),
(641, 'custom value', 0, NULL, '1', 'Makro•Grip® Ultra', '65a35e0542673-pprt-40675120517349785', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '638', '2024-01-14 11:08:23', '2024-01-14 11:08:23'),
(642, 'custom value', 0, NULL, '1', 'Makro•Grip® Ultra', '65a35e0542673-pprt-40675120517349785', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '638', '2024-01-14 11:08:23', '2024-01-14 11:08:23'),
(643, 'attributes', 0, NULL, NULL, NULL, '65a35e866959b-pprt-61222239346160824', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-14 11:09:42', '2024-01-14 11:09:42'),
(644, 'custom value', 0, NULL, '1', 'Notice', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:10:16', '2024-01-14 11:10:16'),
(645, 'custom value', 0, NULL, '1', 'Notice', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, '-', '', 'en', '644', '2024-01-14 11:10:16', '2024-01-14 11:10:16'),
(646, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:10:55', '2024-01-14 11:10:55'),
(647, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, '1 x Mechanical screw jack (incl. 2 pendulum supports), 1 x Set of actuating rod (3 lengths), 1 x Clamping screw SW 15', '', 'en', '646', '2024-01-14 11:10:55', '2024-01-14 11:10:55'),
(648, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:11:36', '2024-01-14 11:11:36'),
(649, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, 'Makro•Grip® Ultra', '', 'en', '648', '2024-01-14 11:11:36', '2024-01-14 11:11:36'),
(650, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35e866959b-pprt-61222239346160824', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '648', '2024-01-14 11:11:36', '2024-01-14 11:11:36'),
(651, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35e866959b-pprt-61222239346160824', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '648', '2024-01-14 11:11:36', '2024-01-14 11:11:36'),
(652, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a35e866959b-pprt-61222239346160824', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '648', '2024-01-14 11:11:36', '2024-01-14 11:11:36'),
(653, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:11:56', '2024-01-14 11:11:56'),
(654, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, NULL, '170520551665a35f0caa4d0_82586_cad (1).zip', 'en', '653', '2024-01-14 11:11:56', '2024-01-14 11:11:56'),
(655, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-14 11:12:11', '2024-01-14 11:12:11'),
(656, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a35e866959b-pprt-61222239346160824', NULL, NULL, NULL, NULL, '170520553165a35f1b26460_82586 (1).pdf', 'en', '655', '2024-01-14 11:12:11', '2024-01-14 11:12:11');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(657, 'attributes', 0, NULL, NULL, NULL, '65a4e1a69ac7f-pprt-94997979587371261', 'DIAMETER', '12 mm (0.47\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:41:26', '2024-01-15 14:41:26'),
(658, 'attributes', 0, NULL, NULL, NULL, '65a4e1a69ac7f-pprt-94997979587371261', 'PACKAGING UNIT', '20 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:41:26', '2024-01-15 14:41:26'),
(659, 'attributes', 0, NULL, NULL, NULL, '65a4e1a69ac7f-pprt-94997979587371261', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:41:26', '2024-01-15 14:41:26'),
(660, 'custom value', 0, NULL, '1', 'Notice', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:42:32', '2024-01-15 14:42:32'),
(661, 'custom value', 0, NULL, '1', 'Notice', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, NULL, '-', '', 'en', '660', '2024-01-15 14:42:32', '2024-01-15 14:42:32'),
(662, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:42:47', '2024-01-15 14:42:47'),
(663, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, NULL, '-', '', 'en', '662', '2024-01-15 14:42:47', '2024-01-15 14:42:47'),
(664, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:45:18', '2024-01-15 14:45:18'),
(669, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '664', '2024-01-15 14:45:20', '2024-01-15 14:45:20'),
(670, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '664', '2024-01-15 14:45:20', '2024-01-15 14:45:20'),
(671, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '664', '2024-01-15 14:45:20', '2024-01-15 14:45:20'),
(672, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a4e1a69ac7f-pprt-94997979587371261', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '664', '2024-01-15 14:45:20', '2024-01-15 14:45:20'),
(673, 'attributes', 0, NULL, NULL, NULL, '65a4e33ad9dc0-pprt-44943948171276247', 'VERSION', 'Standard', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:48:10', '2024-01-15 14:48:10'),
(674, 'attributes', 0, NULL, NULL, NULL, '65a4e33ad9dc0-pprt-44943948171276247', 'T-SLOT PLATE', 'No', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:48:10', '2024-01-15 14:48:10'),
(675, 'attributes', 0, NULL, NULL, NULL, '65a4e33ad9dc0-pprt-44943948171276247', 'STAMPING UNITS', '1', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:48:10', '2024-01-15 14:48:10'),
(676, 'attributes', 0, NULL, NULL, NULL, '65a4e33ad9dc0-pprt-44943948171276247', 'MAX. STAMPING WIDTH', '260 mm (10.24\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:48:10', '2024-01-15 14:48:10'),
(677, 'attributes', 0, NULL, NULL, NULL, '65a4e33ad9dc0-pprt-44943948171276247', 'MAX. STAMPING PRESSURE', '360 bar', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:48:10', '2024-01-15 14:48:10'),
(678, 'attributes', 0, NULL, NULL, NULL, '65a4e33ad9dc0-pprt-44943948171276247', 'FOR MATERIALS', 'up to 35 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:48:10', '2024-01-15 14:48:10'),
(679, 'attributes', 0, NULL, NULL, NULL, '65a4e33ad9dc0-pprt-44943948171276247', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:48:10', '2024-01-15 14:48:10'),
(680, 'custom value', 0, NULL, '1', 'Notice', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:48:49', '2024-01-15 14:48:49'),
(681, 'custom value', 0, NULL, '1', 'Notice', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, NULL, '-', '', 'en', '680', '2024-01-15 14:48:49', '2024-01-15 14:48:49'),
(682, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:50:16', '2024-01-15 14:50:16'),
(683, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, NULL, '1 x Stamping Trolley, 1 x Stamping Vise, 1 x, 1 x 41250: Pneumatic-Hydraulic Pressure Multiplier, for all stamping units, 1 x 50153: Gauging Blocks, for measuring wear of Stamping Jaws, 1 x 41261: End-Stop, for stamping unit, 1 x Protection Shield', '', 'en', '682', '2024-01-15 14:50:16', '2024-01-15 14:50:16'),
(684, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:51:06', '2024-01-15 14:51:06'),
(685, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '684', '2024-01-15 14:51:06', '2024-01-15 14:51:06'),
(686, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '684', '2024-01-15 14:51:06', '2024-01-15 14:51:06'),
(687, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '684', '2024-01-15 14:51:06', '2024-01-15 14:51:06'),
(688, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e33ad9dc0-pprt-44943948171276247', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '684', '2024-01-15 14:51:06', '2024-01-15 14:51:06'),
(689, 'attributes', 0, NULL, NULL, NULL, '65a4e447a5c0f-pprt-87651882406828531', 'VERSION', 'Standard', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:52:39', '2024-01-15 14:52:39'),
(690, 'attributes', 0, NULL, NULL, NULL, '65a4e447a5c0f-pprt-87651882406828531', 'T-SLOT PLATE', 'No', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:52:39', '2024-01-15 14:52:39'),
(691, 'attributes', 0, NULL, NULL, NULL, '65a4e447a5c0f-pprt-87651882406828531', 'STAMPING UNITS', '1', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:52:39', '2024-01-15 14:52:39'),
(692, 'attributes', 0, NULL, NULL, NULL, '65a4e447a5c0f-pprt-87651882406828531', 'MAX. STAMPING WIDTH', '260 mm (10.24\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:52:39', '2024-01-15 14:52:39'),
(693, 'attributes', 0, NULL, NULL, NULL, '65a4e447a5c0f-pprt-87651882406828531', 'MAX. STAMPING PRESSURE', '360 bar', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:52:39', '2024-01-15 14:52:39'),
(694, 'attributes', 0, NULL, NULL, NULL, '65a4e447a5c0f-pprt-87651882406828531', 'FOR MATERIALS', 'up to 45 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:52:39', '2024-01-15 14:52:39'),
(695, 'attributes', 0, NULL, NULL, NULL, '65a4e447a5c0f-pprt-87651882406828531', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:52:39', '2024-01-15 14:52:39'),
(696, 'custom value', 0, NULL, '1', 'Notice', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:53:04', '2024-01-15 14:53:04'),
(697, 'custom value', 0, NULL, '1', 'Notice', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, NULL, '-', '', 'en', '696', '2024-01-15 14:53:04', '2024-01-15 14:53:04'),
(698, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:53:57', '2024-01-15 14:53:57'),
(699, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, NULL, '1 x Stamping Trolley, 1 x Stamping Vise, 1 x 50112: Stamping Jaws, 1 x 41250: Pneumatic-Hydraulic Pressure Multiplier, for all stamping units, 1 x 50153: Gauging Blocks, for measuring wear of Stamping Jaws, 1 x 41261: End-Stop, for stamping unit, 1 x Protection Shield', '', 'en', '698', '2024-01-15 14:53:57', '2024-01-15 14:53:57'),
(700, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:55:45', '2024-01-15 14:55:45'),
(701, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '700', '2024-01-15 14:55:45', '2024-01-15 14:55:45'),
(702, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '700', '2024-01-15 14:55:45', '2024-01-15 14:55:45'),
(703, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '700', '2024-01-15 14:55:45', '2024-01-15 14:55:45'),
(704, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e447a5c0f-pprt-87651882406828531', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '700', '2024-01-15 14:55:45', '2024-01-15 14:55:45'),
(705, 'attributes', 0, NULL, NULL, NULL, '65a4e5506f060-pprt-34330293152093576', 'VERSION', 'Standard', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:57:04', '2024-01-15 14:57:04'),
(706, 'attributes', 0, NULL, NULL, NULL, '65a4e5506f060-pprt-34330293152093576', 'T-SLOT PLATE', 'No', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:57:04', '2024-01-15 14:57:04'),
(707, 'attributes', 0, NULL, NULL, NULL, '65a4e5506f060-pprt-34330293152093576', 'STAMPING UNITS', '2', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:57:04', '2024-01-15 14:57:04'),
(708, 'attributes', 0, NULL, NULL, NULL, '65a4e5506f060-pprt-34330293152093576', 'MAX. STAMPING WIDTH', '410 mm (16.14\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:57:04', '2024-01-15 14:57:04'),
(709, 'attributes', 0, NULL, NULL, NULL, '65a4e5506f060-pprt-34330293152093576', 'MAX. STAMPING PRESSURE', '360 bar', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:57:04', '2024-01-15 14:57:04'),
(710, 'attributes', 0, NULL, NULL, NULL, '65a4e5506f060-pprt-34330293152093576', 'FOR MATERIALS', 'up to 45 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:57:04', '2024-01-15 14:57:04'),
(711, 'attributes', 0, NULL, NULL, NULL, '65a4e5506f060-pprt-34330293152093576', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-15 14:57:04', '2024-01-15 14:57:04'),
(712, 'custom value', 0, NULL, '1', 'Notice', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:57:18', '2024-01-15 14:57:18'),
(713, 'custom value', 0, NULL, '1', 'Notice', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, NULL, '-', '', 'en', '712', '2024-01-15 14:57:18', '2024-01-15 14:57:18'),
(714, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:58:16', '2024-01-15 14:58:16'),
(715, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, NULL, '1 x Stamping Trolley with t-slot plate, 2 x Stamping Vise, 2 x 50112: Stamping Jaws, 1 x 41250: Pneumatic-Hydraulic Pressure Multiplier, for all stamping units, 1 x 50153: Gauging Blocks, for measuring wear of Stamping Jaws, 1 x 41261: End-Stop, for stamping unit, 2 x Protection Shield', '', 'en', '714', '2024-01-15 14:58:16', '2024-01-15 14:58:16'),
(716, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 14:58:47', '2024-01-15 14:58:47'),
(717, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '716', '2024-01-15 14:58:47', '2024-01-15 14:58:47'),
(718, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '716', '2024-01-15 14:58:47', '2024-01-15 14:58:47'),
(719, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '716', '2024-01-15 14:58:47', '2024-01-15 14:58:47'),
(720, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e5506f060-pprt-34330293152093576', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '716', '2024-01-15 14:58:47', '2024-01-15 14:58:47'),
(721, 'attributes', 0, NULL, NULL, NULL, '65a4e61ba5c37-pprt-62866028567184037', 'VERSION', 'Standard', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:00:27', '2024-01-15 15:00:27'),
(722, 'attributes', 0, NULL, NULL, NULL, '65a4e61ba5c37-pprt-62866028567184037', 'T-SLOT PLATE', 'Yes', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:00:27', '2024-01-15 15:00:27'),
(723, 'attributes', 0, NULL, NULL, NULL, '65a4e61ba5c37-pprt-62866028567184037', 'STAMPING UNITS', '1', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:00:27', '2024-01-15 15:00:27'),
(724, 'attributes', 0, NULL, NULL, NULL, '65a4e61ba5c37-pprt-62866028567184037', 'MAX. STAMPING WIDTH', '410 mm (16.14\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:00:27', '2024-01-15 15:00:27'),
(725, 'attributes', 0, NULL, NULL, NULL, '65a4e61ba5c37-pprt-62866028567184037', 'MAX. STAMPING PRESSURE', '360 bar', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:00:27', '2024-01-15 15:00:27'),
(726, 'attributes', 0, NULL, NULL, NULL, '65a4e61ba5c37-pprt-62866028567184037', 'FOR MATERIALS', 'up to 45 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:00:27', '2024-01-15 15:00:27'),
(727, 'attributes', 0, NULL, NULL, NULL, '65a4e61ba5c37-pprt-62866028567184037', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:00:27', '2024-01-15 15:00:27'),
(728, 'custom value', 0, NULL, '1', 'Notice', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:00:53', '2024-01-15 15:00:53'),
(729, 'custom value', 0, NULL, '1', 'Notice', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, NULL, '-', '', 'en', '728', '2024-01-15 15:00:53', '2024-01-15 15:00:53'),
(730, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:01:36', '2024-01-15 15:01:36'),
(731, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, NULL, '1 x Stamping Trolley with t-slot plate, 1 x Stamping Vise, 1 x 50112: Stamping Jaws, 1 x 41250: Pneumatic-Hydraulic Pressure Multiplier, for all stamping units, 1 x 50153: Gauging Blocks, for measuring wear of Stamping Jaws, 1 x 41261: End-Stop, for stamping unit, 1 x Protection Shield', '', 'en', '730', '2024-01-15 15:01:36', '2024-01-15 15:01:36'),
(732, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:01:57', '2024-01-15 15:01:57'),
(733, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '732', '2024-01-15 15:01:57', '2024-01-15 15:01:57'),
(734, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '732', '2024-01-15 15:01:57', '2024-01-15 15:01:57'),
(735, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '732', '2024-01-15 15:01:57', '2024-01-15 15:01:57'),
(736, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e61ba5c37-pprt-62866028567184037', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '732', '2024-01-15 15:01:57', '2024-01-15 15:01:57'),
(737, 'attributes', 0, NULL, NULL, NULL, '65a4e6ccd01e0-pprt-65086090174575712', 'VERSION', 'Standard', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:03:24', '2024-01-15 15:03:24'),
(738, 'attributes', 0, NULL, NULL, NULL, '65a4e6ccd01e0-pprt-65086090174575712', 'T-SLOT PLATE', 'Yes', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:03:24', '2024-01-15 15:03:24'),
(739, 'attributes', 0, NULL, NULL, NULL, '65a4e6ccd01e0-pprt-65086090174575712', 'STAMPING UNITS', '2', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:03:24', '2024-01-15 15:03:24'),
(740, 'attributes', 0, NULL, NULL, NULL, '65a4e6ccd01e0-pprt-65086090174575712', 'MAX. STAMPING WIDTH', '410 mm (16.14\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:03:24', '2024-01-15 15:03:24'),
(741, 'attributes', 0, NULL, NULL, NULL, '65a4e6ccd01e0-pprt-65086090174575712', 'MAX. STAMPING PRESSURE', '360 bar', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:03:24', '2024-01-15 15:03:24'),
(742, 'attributes', 0, NULL, NULL, NULL, '65a4e6ccd01e0-pprt-65086090174575712', 'FOR MATERIALS', 'up to 35 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:03:24', '2024-01-15 15:03:24'),
(743, 'attributes', 0, NULL, NULL, NULL, '65a4e6ccd01e0-pprt-65086090174575712', 'PACKAGING UNIT', '1 Piece', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:03:24', '2024-01-15 15:03:24'),
(744, 'custom value', 0, NULL, '1', 'Notice', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:05:12', '2024-01-15 15:05:12'),
(745, 'custom value', 0, NULL, '1', 'Notice', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, NULL, '-', '', 'en', '744', '2024-01-15 15:05:12', '2024-01-15 15:05:12'),
(746, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:05:53', '2024-01-15 15:05:53'),
(747, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, NULL, '1 x Stamping Trolley with t-slot plate, 2 x Stamping Vise, 2 x 50111: Stamping Jaws, 1 x 41250: Pneumatic-Hydraulic Pressure Multiplier, for all stamping units, 1 x 50153: Gauging Blocks, for measuring wear of Stamping Jaws, 1 x 41261: End-Stop, for stamping unit, 2 x Protection Shield', '', 'en', '746', '2024-01-15 15:05:53', '2024-01-15 15:05:53'),
(748, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:14:53', '2024-01-15 15:14:53'),
(749, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Makro•Grip® FS is the further development of this product. Its compact design and high holding forces make the Makro•Grip® FS 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '748', '2024-01-15 15:14:53', '2024-01-15 15:14:53'),
(750, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, 'Holding force', 'Thanks to a new stamping and holding serration, up to 60% higher holding forces can be achieved with Makro•Grip® FS.', '', 'en', '748', '2024-01-15 15:14:53', '2024-01-15 15:14:53'),
(751, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® FS offers maximum process reliability and allows even higher cutting rates and faster milling processes.', '', 'en', '748', '2024-01-15 15:14:53', '2024-01-15 15:14:53'),
(752, 'custom value', 0, NULL, '3', 'Makro•Grip® FS Stamping Technology and Raw Part Clamping', '65a4e6ccd01e0-pprt-65086090174575712', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® FS self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '748', '2024-01-15 15:14:53', '2024-01-15 15:14:53'),
(753, 'attributes', 0, NULL, NULL, NULL, '65a4ea46af67c-pprt-67396351884940808', 'TYPE OF HEXAGONAL WRENCH', 'Hexagon socket', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:18:14', '2024-01-15 15:18:14'),
(754, 'attributes', 0, NULL, NULL, NULL, '65a4ea46af67c-pprt-67396351884940808', 'WRENCH SIZE', 'SW 5', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:18:14', '2024-01-15 15:18:14'),
(755, 'attributes', 0, NULL, NULL, NULL, '65a4ea46af67c-pprt-67396351884940808', 'SCREW SIZE', 'M6', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:18:14', '2024-01-15 15:18:14'),
(756, 'attributes', 0, NULL, NULL, NULL, '65a4ea46af67c-pprt-67396351884940808', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:18:14', '2024-01-15 15:18:14'),
(757, 'attributes', 0, NULL, NULL, NULL, '65a4ea46af67c-pprt-67396351884940808', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:18:14', '2024-01-15 15:18:14'),
(758, 'custom value', 0, NULL, '1', 'Notice', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:18:41', '2024-01-15 15:18:41'),
(759, 'custom value', 0, NULL, '1', 'Notice', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, NULL, '-', '', 'en', '758', '2024-01-15 15:18:41', '2024-01-15 15:18:41'),
(760, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:18:52', '2024-01-15 15:18:52'),
(761, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, NULL, '-', '', 'en', '760', '2024-01-15 15:18:52', '2024-01-15 15:18:52'),
(762, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:19:49', '2024-01-15 15:19:49'),
(763, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '762', '2024-01-15 15:19:49', '2024-01-15 15:19:49'),
(764, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '762', '2024-01-15 15:19:49', '2024-01-15 15:19:49'),
(765, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '762', '2024-01-15 15:19:49', '2024-01-15 15:19:49'),
(766, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:20:08', '2024-01-15 15:20:08'),
(767, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a4ea46af67c-pprt-67396351884940808', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '766', '2024-01-15 15:20:08', '2024-01-15 15:20:08'),
(768, 'attributes', 0, NULL, NULL, NULL, '65a4f18ae799f-pprt-92607176264975693', 'TYPE OF HEXAGONAL WRENCH', 'Hexagon socket', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:49:14', '2024-01-15 15:49:14'),
(769, 'attributes', 0, NULL, NULL, NULL, '65a4f18ae799f-pprt-92607176264975693', 'WRENCH SIZE', 'SW 6', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:49:14', '2024-01-15 15:49:14'),
(770, 'attributes', 0, NULL, NULL, NULL, '65a4f18ae799f-pprt-92607176264975693', 'SCREW SIZE', 'M6', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:49:14', '2024-01-15 15:49:14'),
(771, 'attributes', 0, NULL, NULL, NULL, '65a4f18ae799f-pprt-92607176264975693', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:49:14', '2024-01-15 15:49:14'),
(772, 'attributes', 0, NULL, NULL, NULL, '65a4f18ae799f-pprt-92607176264975693', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:49:14', '2024-01-15 15:49:14'),
(773, 'custom value', 0, NULL, '1', 'Notice', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:49:55', '2024-01-15 15:49:55'),
(774, 'custom value', 0, NULL, '1', 'Notice', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, NULL, '-', '', 'en', '773', '2024-01-15 15:49:55', '2024-01-15 15:49:55'),
(775, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:50:07', '2024-01-15 15:50:07'),
(776, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, NULL, '-', '', 'en', '775', '2024-01-15 15:50:07', '2024-01-15 15:50:07'),
(777, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:50:59', '2024-01-15 15:50:59'),
(778, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '777', '2024-01-15 15:50:59', '2024-01-15 15:50:59'),
(779, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '777', '2024-01-15 15:50:59', '2024-01-15 15:50:59'),
(780, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '777', '2024-01-15 15:50:59', '2024-01-15 15:50:59'),
(781, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:51:13', '2024-01-15 15:51:13'),
(782, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a4f18ae799f-pprt-92607176264975693', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '781', '2024-01-15 15:51:13', '2024-01-15 15:51:13'),
(783, 'attributes', 0, NULL, NULL, NULL, '65a4f335ee848-pprt-20964131793040864', 'WRENCH SIZE', 'SW 5', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:56:21', '2024-01-15 15:56:21'),
(784, 'attributes', 0, NULL, NULL, NULL, '65a4f335ee848-pprt-20964131793040864', 'TYPE OF HEXAGONAL WRENCH', 'Hexagon socket', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:56:21', '2024-01-15 15:56:21'),
(785, 'attributes', 0, NULL, NULL, NULL, '65a4f335ee848-pprt-20964131793040864', 'SCREW SIZE', 'M6', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:56:21', '2024-01-15 15:56:21'),
(786, 'attributes', 0, NULL, NULL, NULL, '65a4f335ee848-pprt-20964131793040864', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:56:22', '2024-01-15 15:56:22'),
(787, 'attributes', 0, NULL, NULL, NULL, '65a4f335ee848-pprt-20964131793040864', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 15:56:22', '2024-01-15 15:56:22'),
(788, 'custom value', 0, NULL, '1', 'Notice', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:56:41', '2024-01-15 15:56:41'),
(789, 'custom value', 0, NULL, '1', 'Notice', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, NULL, '-', '', 'en', '788', '2024-01-15 15:56:41', '2024-01-15 15:56:41'),
(790, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:56:50', '2024-01-15 15:56:50'),
(791, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, NULL, '-', '', 'en', '790', '2024-01-15 15:56:50', '2024-01-15 15:56:50'),
(792, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:57:12', '2024-01-15 15:57:12'),
(793, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, 'Quick jaw exchange', 'Jaw exchange in just a few seconds with just one screw', '', 'en', '792', '2024-01-15 15:57:12', '2024-01-15 15:57:12'),
(794, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, 'Cost benefits', 'Extremely affordable top jaws available in different heights and materials', '', 'en', '792', '2024-01-15 15:57:12', '2024-01-15 15:57:12'),
(795, 'custom value', 0, NULL, '3', 'Benefits Avanti', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, 'Accuracy', 'Highly precise positioning of the top jaws thanks to the patented interface', '', 'en', '792', '2024-01-15 15:57:12', '2024-01-15 15:57:12'),
(796, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 15:57:27', '2024-01-15 15:57:27'),
(797, 'custom value', 0, NULL, '3', 'Conventional Workholding', '65a4f335ee848-pprt-20964131793040864', NULL, NULL, NULL, '„Conventional Workholding“ offers a multitude of options for clamping round or pre-machined parts. To solve the respective clamping task, the operator can choose between a 6-jaw chuck, two collet chucks and three different types of self-centering vises, whose jaw types are perfectly suited for challenging 2nd operations.', '', 'en', '796', '2024-01-15 15:57:27', '2024-01-15 15:57:27'),
(798, 'attributes', 0, NULL, NULL, NULL, '65a50749c8834-pprt-77570775797363849', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:22:01', '2024-01-15 17:22:01'),
(799, 'custom value', 0, NULL, '1', 'Notice', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:22:32', '2024-01-15 17:22:32'),
(800, 'custom value', 0, NULL, '1', 'Notice', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, 'Already included in the scope of delivery of all Makro•Grip® stamping units.', '', 'en', '799', '2024-01-15 17:22:32', '2024-01-15 17:22:32'),
(801, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:22:42', '2024-01-15 17:22:42'),
(802, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, '-', '', 'en', '801', '2024-01-15 17:22:42', '2024-01-15 17:22:42'),
(803, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:23:24', '2024-01-15 17:23:24'),
(804, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '803', '2024-01-15 17:23:24', '2024-01-15 17:23:24'),
(805, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50749c8834-pprt-77570775797363849', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '803', '2024-01-15 17:23:24', '2024-01-15 17:23:24'),
(806, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50749c8834-pprt-77570775797363849', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '803', '2024-01-15 17:23:24', '2024-01-15 17:23:24'),
(807, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50749c8834-pprt-77570775797363849', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '803', '2024-01-15 17:23:24', '2024-01-15 17:23:24'),
(808, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:23:39', '2024-01-15 17:23:39'),
(809, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a50749c8834-pprt-77570775797363849', NULL, NULL, NULL, NULL, '170531421965a507ab9849b_41261_cad.zip', 'en', '808', '2024-01-15 17:23:39', '2024-01-15 17:23:39'),
(810, 'attributes', 0, NULL, NULL, NULL, '65a508116f996-pprt-61801578261180605', 'SYSTEM', 'RoboTrex 52 / RoboTrex 96', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:25:21', '2024-01-15 17:25:21'),
(811, 'custom value', 0, NULL, '1', 'Notice', '65a508116f996-pprt-61801578261180605', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:25:45', '2024-01-15 17:25:45'),
(812, 'custom value', 0, NULL, '1', 'Notice', '65a508116f996-pprt-61801578261180605', NULL, NULL, NULL, '-', '', 'en', '811', '2024-01-15 17:25:45', '2024-01-15 17:25:45'),
(813, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a508116f996-pprt-61801578261180605', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:28:20', '2024-01-15 17:28:20'),
(814, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a508116f996-pprt-61801578261180605', NULL, NULL, NULL, '-', '', 'en', '813', '2024-01-15 17:28:20', '2024-01-15 17:28:20'),
(815, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a508116f996-pprt-61801578261180605', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:29:06', '2024-01-15 17:29:06'),
(816, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a508116f996-pprt-61801578261180605', NULL, NULL, NULL, 'By using RoboTrex, machining hours and thus productivity can be increased. RoboTrex covers every need from single part to large-scale production. It is flexible, easy to use and offers great value for money. This makes it appealing for SME’s and for those just starting out.', '', 'en', '815', '2024-01-15 17:29:06', '2024-01-15 17:29:06'),
(817, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a508116f996-pprt-61801578261180605', NULL, NULL, 'Easiest operation', 'The intuitive operation and the pre-adjusted robot do not require any special knowledge. Training is kept to a minimum.', '', 'en', '815', '2024-01-15 17:29:06', '2024-01-15 17:29:06'),
(818, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a508116f996-pprt-61801578261180605', NULL, NULL, 'Fastest set-up time', 'Thanks to offline preparation of the trolleys, the system is set up within a few moments without any machine downtime.', '', 'en', '815', '2024-01-15 17:29:06', '2024-01-15 17:29:06'),
(819, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a508116f996-pprt-61801578261180605', NULL, NULL, 'Highest flexibility', 'Both the offline trolley preparation and the diversity of machinable parts guarantee maximum flexibility.', '', 'en', '815', '2024-01-15 17:29:06', '2024-01-15 17:29:06'),
(820, 'attributes', 0, NULL, NULL, NULL, '65a5093d6ffb9-pprt-76261375516426271', 'SYSTEM', 'RoboTrex 52 / RoboTrex 96', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:30:21', '2024-01-15 17:30:21'),
(821, 'custom value', 0, NULL, '1', 'Notice', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:30:37', '2024-01-15 17:30:37'),
(822, 'custom value', 0, NULL, '1', 'Notice', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, NULL, '-', '', 'en', '821', '2024-01-15 17:30:37', '2024-01-15 17:30:37'),
(823, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:30:47', '2024-01-15 17:30:47'),
(824, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, NULL, '-', '', 'en', '823', '2024-01-15 17:30:47', '2024-01-15 17:30:47'),
(825, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:31:31', '2024-01-15 17:31:31'),
(826, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, NULL, 'By using RoboTrex, machining hours and thus productivity can be increased. RoboTrex covers every need from single part to large-scale production. It is flexible, easy to use and offers great value for money. This makes it appealing for SME’s and for those just starting out.', '', 'en', '825', '2024-01-15 17:31:31', '2024-01-15 17:31:31'),
(827, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, 'Easiest operation', 'The intuitive operation and the pre-adjusted robot do not require any special knowledge. Training is kept to a minimum.', '', 'en', '825', '2024-01-15 17:31:31', '2024-01-15 17:31:31'),
(828, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, 'Fastest set-up time', 'Thanks to offline preparation of the trolleys, the system is set up within a few moments without any machine downtime.', '', 'en', '825', '2024-01-15 17:31:31', '2024-01-15 17:31:31'),
(829, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a5093d6ffb9-pprt-76261375516426271', NULL, NULL, 'Highest flexibility', 'Both the offline trolley preparation and the diversity of machinable parts guarantee maximum flexibility.', '', 'en', '825', '2024-01-15 17:31:31', '2024-01-15 17:31:31'),
(830, 'attributes', 0, NULL, NULL, NULL, '65a509e180f6f-pprt-83315886353970083', 'SYSTEM', 'RoboTrex 96', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:33:05', '2024-01-15 17:33:05'),
(831, 'attributes', 0, NULL, NULL, NULL, '65a509e180f6f-pprt-83315886353970083', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:33:05', '2024-01-15 17:33:05'),
(832, 'attributes', 0, NULL, NULL, NULL, '65a509e180f6f-pprt-83315886353970083', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:33:05', '2024-01-15 17:33:05'),
(833, 'custom value', 0, NULL, '1', 'Notice', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:33:20', '2024-01-15 17:33:20'),
(834, 'custom value', 0, NULL, '1', 'Notice', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, NULL, '-', '', 'en', '833', '2024-01-15 17:33:20', '2024-01-15 17:33:20'),
(835, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:33:33', '2024-01-15 17:33:33'),
(836, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, NULL, '-', '', 'en', '835', '2024-01-15 17:33:33', '2024-01-15 17:33:33'),
(837, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:34:10', '2024-01-15 17:34:10'),
(838, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, NULL, 'By using RoboTrex, machining hours and thus productivity can be increased. RoboTrex covers every need from single part to large-scale production. It is flexible, easy to use and offers great value for money. This makes it appealing for SME’s and for those just starting out.', '', 'en', '837', '2024-01-15 17:34:10', '2024-01-15 17:34:10'),
(839, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, 'Easiest operation', 'The intuitive operation and the pre-adjusted robot do not require any special knowledge. Training is kept to a minimum.', '', 'en', '837', '2024-01-15 17:34:10', '2024-01-15 17:34:10'),
(840, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, 'Fastest set-up time', 'Thanks to offline preparation of the trolleys, the system is set up within a few moments without any machine downtime.', '', 'en', '837', '2024-01-15 17:34:10', '2024-01-15 17:34:10'),
(841, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a509e180f6f-pprt-83315886353970083', NULL, NULL, 'Highest flexibility', 'Both the offline trolley preparation and the diversity of machinable parts guarantee maximum flexibility.', '', 'en', '837', '2024-01-15 17:34:10', '2024-01-15 17:34:10'),
(842, 'attributes', 0, NULL, NULL, NULL, '65a50a6449624-pprt-70004189848183424', 'SYSTEM', 'RoboTrex 52', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:35:16', '2024-01-15 17:35:16'),
(843, 'attributes', 0, NULL, NULL, NULL, '65a50a6449624-pprt-70004189848183424', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:35:16', '2024-01-15 17:35:16'),
(844, 'custom value', 0, NULL, '1', 'Notice', '65a50a6449624-pprt-70004189848183424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:35:49', '2024-01-15 17:35:49'),
(845, 'custom value', 0, NULL, '1', 'Notice', '65a50a6449624-pprt-70004189848183424', NULL, NULL, NULL, '-', '', 'en', '844', '2024-01-15 17:35:49', '2024-01-15 17:35:49'),
(846, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50a6449624-pprt-70004189848183424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:35:58', '2024-01-15 17:35:58'),
(847, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50a6449624-pprt-70004189848183424', NULL, NULL, NULL, '-', '', 'en', '846', '2024-01-15 17:35:58', '2024-01-15 17:35:58'),
(848, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a50a6449624-pprt-70004189848183424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:36:22', '2024-01-15 17:36:22'),
(849, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a50a6449624-pprt-70004189848183424', NULL, NULL, NULL, 'By using RoboTrex, machining hours and thus productivity can be increased. RoboTrex covers every need from single part to large-scale production. It is flexible, easy to use and offers great value for money. This makes it appealing for SME’s and for those just starting out.', '', 'en', '848', '2024-01-15 17:36:22', '2024-01-15 17:36:22'),
(850, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a50a6449624-pprt-70004189848183424', NULL, NULL, 'Easiest operation', 'The intuitive operation and the pre-adjusted robot do not require any special knowledge. Training is kept to a minimum.', '', 'en', '848', '2024-01-15 17:36:22', '2024-01-15 17:36:22'),
(851, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a50a6449624-pprt-70004189848183424', NULL, NULL, 'Fastest set-up time', 'Thanks to offline preparation of the trolleys, the system is set up within a few moments without any machine downtime.', '', 'en', '848', '2024-01-15 17:36:22', '2024-01-15 17:36:22'),
(852, 'custom value', 0, NULL, '3', 'RoboTrex Automation System', '65a50a6449624-pprt-70004189848183424', NULL, NULL, 'Highest flexibility', 'Both the offline trolley preparation and the diversity of machinable parts guarantee maximum flexibility.', '', 'en', '848', '2024-01-15 17:36:22', '2024-01-15 17:36:22'),
(853, 'attributes', 0, NULL, NULL, NULL, '65a50b2b94687-pprt-71895377729320539', 'DIMENSIONS', '⌀ 5.6 x 23.7 mm (0.22\" x 0.93\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:38:35', '2024-01-15 17:38:35'),
(854, 'attributes', 0, NULL, NULL, NULL, '65a50b2b94687-pprt-71895377729320539', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:38:35', '2024-01-15 17:38:35'),
(855, 'attributes', 0, NULL, NULL, NULL, '65a50b2b94687-pprt-71895377729320539', 'WEIGHT', '0.05 kg (0.11 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:38:35', '2024-01-15 17:38:35'),
(856, 'attributes', 0, NULL, NULL, NULL, '65a50b2b94687-pprt-71895377729320539', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:38:35', '2024-01-15 17:38:35'),
(857, 'custom value', 0, NULL, '1', 'Notice', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:38:58', '2024-01-15 17:38:58'),
(858, 'custom value', 0, NULL, '1', 'Notice', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, NULL, '-', '', 'en', '857', '2024-01-15 17:38:58', '2024-01-15 17:38:58'),
(859, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:39:07', '2024-01-15 17:39:07'),
(860, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, NULL, '-', '', 'en', '859', '2024-01-15 17:39:07', '2024-01-15 17:39:07'),
(861, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:40:10', '2024-01-15 17:40:10'),
(862, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '861', '2024-01-15 17:40:10', '2024-01-15 17:40:10'),
(863, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '861', '2024-01-15 17:40:10', '2024-01-15 17:40:10');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(864, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '861', '2024-01-15 17:40:10', '2024-01-15 17:40:10'),
(865, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50b2b94687-pprt-71895377729320539', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '861', '2024-01-15 17:40:10', '2024-01-15 17:40:10'),
(866, 'attributes', 0, NULL, NULL, NULL, '65a50bed8cf87-pprt-36655666172004716', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:41:49', '2024-01-15 17:41:49'),
(867, 'attributes', 0, NULL, NULL, NULL, '65a50bed8cf87-pprt-36655666172004716', 'WEIGHT', '21.68 kg', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:41:49', '2024-01-15 17:41:49'),
(868, 'custom value', 0, NULL, '1', 'Notice', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:42:22', '2024-01-15 17:42:22'),
(869, 'custom value', 0, NULL, '1', 'Notice', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, '-', '', 'en', '868', '2024-01-15 17:42:22', '2024-01-15 17:42:22'),
(870, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:42:35', '2024-01-15 17:42:35'),
(871, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, '-', '', 'en', '870', '2024-01-15 17:42:35', '2024-01-15 17:42:35'),
(872, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:43:22', '2024-01-15 17:43:22'),
(873, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '872', '2024-01-15 17:43:22', '2024-01-15 17:43:22'),
(874, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '872', '2024-01-15 17:43:22', '2024-01-15 17:43:22'),
(875, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '872', '2024-01-15 17:43:22', '2024-01-15 17:43:22'),
(876, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '872', '2024-01-15 17:43:22', '2024-01-15 17:43:22'),
(877, 'custom value', 0, NULL, '4', 'Application pictures', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:43:55', '2024-01-15 17:43:55'),
(878, 'custom value', 0, NULL, '4', 'Application pictures', '65a50bed8cf87-pprt-36655666172004716', NULL, NULL, NULL, NULL, '170531543565a50c6b37a78_LANG_AB_41250_001.webp', 'en', '877', '2024-01-15 17:43:55', '2024-01-15 17:43:55'),
(879, 'attributes', 0, NULL, NULL, NULL, '65a50cfc87fc3-pprt-40955485644371902', 'DIMENSIONS', '78 x 30 x 33.5 mm (3.07\" x 1.18\" x 1.32\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:46:20', '2024-01-15 17:46:20'),
(880, 'attributes', 0, NULL, NULL, NULL, '65a50cfc87fc3-pprt-40955485644371902', 'PACKAGING UNIT', '10 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:46:20', '2024-01-15 17:46:20'),
(881, 'attributes', 0, NULL, NULL, NULL, '65a50cfc87fc3-pprt-40955485644371902', 'WEIGHT', '0.02 kg (0.04 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:46:20', '2024-01-15 17:46:20'),
(882, 'custom value', 0, NULL, '1', 'Notice', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:46:53', '2024-01-15 17:46:53'),
(883, 'custom value', 0, NULL, '1', 'Notice', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, NULL, '-', '', 'en', '882', '2024-01-15 17:46:53', '2024-01-15 17:46:53'),
(884, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:47:03', '2024-01-15 17:47:03'),
(885, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, NULL, '-', '', 'en', '884', '2024-01-15 17:47:03', '2024-01-15 17:47:03'),
(886, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:47:32', '2024-01-15 17:47:32'),
(887, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '886', '2024-01-15 17:47:32', '2024-01-15 17:47:32'),
(888, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '886', '2024-01-15 17:47:32', '2024-01-15 17:47:32'),
(889, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '886', '2024-01-15 17:47:32', '2024-01-15 17:47:32'),
(890, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50cfc87fc3-pprt-40955485644371902', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '886', '2024-01-15 17:47:32', '2024-01-15 17:47:32'),
(891, 'attributes', 0, NULL, NULL, NULL, '65a50d77cae77-pprt-25517994232509210', 'DIMENSIONS', '33 x 10 x 17 mm (1.3\" x 0.39\" x 0.67\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:48:23', '2024-01-15 17:48:23'),
(892, 'attributes', 0, NULL, NULL, NULL, '65a50d77cae77-pprt-25517994232509210', 'PACKAGING UNIT', '10 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:48:23', '2024-01-15 17:48:23'),
(893, 'attributes', 0, NULL, NULL, NULL, '65a50d77cae77-pprt-25517994232509210', 'WEIGHT', '0.01 kg (0.02 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:48:23', '2024-01-15 17:48:23'),
(894, 'custom value', 0, NULL, '1', 'Notice', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:48:45', '2024-01-15 17:48:45'),
(895, 'custom value', 0, NULL, '1', 'Notice', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, NULL, '-', '', 'en', '894', '2024-01-15 17:48:45', '2024-01-15 17:48:45'),
(896, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:48:53', '2024-01-15 17:48:53'),
(897, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, NULL, '-', '', 'en', '896', '2024-01-15 17:48:53', '2024-01-15 17:48:53'),
(898, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:49:10', '2024-01-15 17:49:10'),
(899, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '898', '2024-01-15 17:49:10', '2024-01-15 17:49:10'),
(900, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '898', '2024-01-15 17:49:10', '2024-01-15 17:49:10'),
(901, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '898', '2024-01-15 17:49:10', '2024-01-15 17:49:10'),
(902, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50d77cae77-pprt-25517994232509210', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '898', '2024-01-15 17:49:10', '2024-01-15 17:49:10'),
(903, 'attributes', 0, NULL, NULL, NULL, '65a50e035c4d5-pprt-58004291495772672', 'PACKAGING UNIT', '10 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:50:43', '2024-01-15 17:50:43'),
(904, 'attributes', 0, NULL, NULL, NULL, '65a50e035c4d5-pprt-58004291495772672', 'WEIGHT', '0.07 kg (0.15 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:50:43', '2024-01-15 17:50:43'),
(905, 'attributes', 0, NULL, NULL, NULL, '65a50e035c4d5-pprt-58004291495772672', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:50:43', '2024-01-15 17:50:43'),
(906, 'custom value', 0, NULL, '1', 'Notice', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:51:02', '2024-01-15 17:51:02'),
(907, 'custom value', 0, NULL, '1', 'Notice', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, NULL, '-', '', 'en', '906', '2024-01-15 17:51:02', '2024-01-15 17:51:02'),
(908, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:51:11', '2024-01-15 17:51:11'),
(909, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, NULL, '-', '', 'en', '908', '2024-01-15 17:51:11', '2024-01-15 17:51:11'),
(910, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:51:34', '2024-01-15 17:51:34'),
(911, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '910', '2024-01-15 17:51:34', '2024-01-15 17:51:34'),
(912, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '910', '2024-01-15 17:51:34', '2024-01-15 17:51:34'),
(913, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '910', '2024-01-15 17:51:34', '2024-01-15 17:51:34'),
(914, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e035c4d5-pprt-58004291495772672', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '910', '2024-01-15 17:51:34', '2024-01-15 17:51:34'),
(915, 'attributes', 0, NULL, NULL, NULL, '65a50e862a7a0-pprt-43424931952085874', 'DIMENSIONS', '45 x 20 x 23 mm (1.77\" x 0.79\" x 0.91\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:52:54', '2024-01-15 17:52:54'),
(916, 'attributes', 0, NULL, NULL, NULL, '65a50e862a7a0-pprt-43424931952085874', 'PACKAGING UNIT', '10 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:52:54', '2024-01-15 17:52:54'),
(917, 'attributes', 0, NULL, NULL, NULL, '65a50e862a7a0-pprt-43424931952085874', 'WEIGHT', '0.01 kg (0.02 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:52:54', '2024-01-15 17:52:54'),
(918, 'custom value', 0, NULL, '1', 'Notice', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:53:15', '2024-01-15 17:53:15'),
(919, 'custom value', 0, NULL, '1', 'Notice', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, NULL, '-', '', 'en', '918', '2024-01-15 17:53:15', '2024-01-15 17:53:15'),
(920, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:53:24', '2024-01-15 17:53:24'),
(921, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, NULL, '-', '', 'en', '920', '2024-01-15 17:53:24', '2024-01-15 17:53:24'),
(922, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:53:54', '2024-01-15 17:53:54'),
(923, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '922', '2024-01-15 17:53:54', '2024-01-15 17:53:54'),
(924, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '922', '2024-01-15 17:53:54', '2024-01-15 17:53:54'),
(925, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '922', '2024-01-15 17:53:54', '2024-01-15 17:53:54'),
(926, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50e862a7a0-pprt-43424931952085874', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '922', '2024-01-15 17:53:54', '2024-01-15 17:53:54'),
(927, 'attributes', 0, NULL, NULL, NULL, '65a50eff8c719-pprt-21214497440993859', 'PACKAGING UNIT', '10 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:54:55', '2024-01-15 17:54:55'),
(928, 'attributes', 0, NULL, NULL, NULL, '65a50eff8c719-pprt-21214497440993859', 'WEIGHT', '0.25 kg (0.55 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:54:55', '2024-01-15 17:54:55'),
(929, 'attributes', 0, NULL, NULL, NULL, '65a50eff8c719-pprt-21214497440993859', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-15 17:54:55', '2024-01-15 17:54:55'),
(930, 'custom value', 0, NULL, '1', 'Notice', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:55:17', '2024-01-15 17:55:17'),
(931, 'custom value', 0, NULL, '1', 'Notice', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, NULL, '-', '', 'en', '930', '2024-01-15 17:55:17', '2024-01-15 17:55:17'),
(932, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:55:25', '2024-01-15 17:55:25'),
(934, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, NULL, '-', '', 'en', '932', '2024-01-15 17:55:26', '2024-01-15 17:55:26'),
(935, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 17:55:47', '2024-01-15 17:55:47'),
(936, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '935', '2024-01-15 17:55:47', '2024-01-15 17:55:47'),
(937, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '935', '2024-01-15 17:55:47', '2024-01-15 17:55:47'),
(938, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '935', '2024-01-15 17:55:47', '2024-01-15 17:55:47'),
(939, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a50eff8c719-pprt-21214497440993859', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '935', '2024-01-15 17:55:47', '2024-01-15 17:55:47'),
(940, 'attributes', 0, NULL, NULL, NULL, '65a510c765eb6-pprt-32579408934764655', 'DIMENSIONS', '126 x 34 x 18 mm (4.96\" x 1.34\" x 0.71\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:02:31', '2024-01-15 18:02:31'),
(941, 'attributes', 0, NULL, NULL, NULL, '65a510c765eb6-pprt-32579408934764655', 'FOR MATERIALS', 'up to 45 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:02:31', '2024-01-15 18:02:31'),
(942, 'attributes', 0, NULL, NULL, NULL, '65a510c765eb6-pprt-32579408934764655', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:02:31', '2024-01-15 18:02:31'),
(943, 'attributes', 0, NULL, NULL, NULL, '65a510c765eb6-pprt-32579408934764655', 'WEIGHT', '0.46 kg (1.01 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:02:31', '2024-01-15 18:02:31'),
(944, 'attributes', 0, NULL, NULL, NULL, '65a510c765eb6-pprt-32579408934764655', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:02:31', '2024-01-15 18:02:31'),
(945, 'custom value', 0, NULL, '1', 'Notice', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:02:59', '2024-01-15 18:02:59'),
(946, 'custom value', 0, NULL, '1', 'Notice', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, 'Suitable parallels have to be bought separately.', '', 'en', '945', '2024-01-15 18:02:59', '2024-01-15 18:02:59'),
(947, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:03:08', '2024-01-15 18:03:08'),
(948, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, '-', '', 'en', '947', '2024-01-15 18:03:08', '2024-01-15 18:03:08'),
(949, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:03:59', '2024-01-15 18:03:59'),
(950, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '949', '2024-01-15 18:03:59', '2024-01-15 18:03:59'),
(951, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '949', '2024-01-15 18:03:59', '2024-01-15 18:03:59'),
(952, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '949', '2024-01-15 18:03:59', '2024-01-15 18:03:59'),
(953, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '949', '2024-01-15 18:03:59', '2024-01-15 18:03:59'),
(954, 'custom value', 0, NULL, '4', 'Application pictures', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:04:55', '2024-01-15 18:04:55'),
(955, 'custom value', 0, NULL, '4', 'Application pictures', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, '170531669565a5115755538_DSC05432.webp', 'en', '954', '2024-01-15 18:04:55', '2024-01-15 18:04:55'),
(956, 'custom value', 0, NULL, '4', 'Application pictures', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, '170531669565a51157562e2_DSC05452.webp', 'en', '954', '2024-01-15 18:04:55', '2024-01-15 18:04:55'),
(957, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:05:36', '2024-01-15 18:05:36'),
(958, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, '170531673665a51180113b5_41112-06.pdf', 'en', '957', '2024-01-15 18:05:36', '2024-01-15 18:05:36'),
(959, 'custom value', 0, NULL, '5', 'EXTRUSION OIL', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:06:00', '2024-01-15 18:06:00'),
(960, 'custom value', 0, NULL, '5', 'EXTRUSION OIL', '65a510c765eb6-pprt-32579408934764655', NULL, NULL, NULL, NULL, '170531676065a51198ba18d_SDS_SSP70_ExtrusionOil_E_5.pdf', 'en', '959', '2024-01-15 18:06:00', '2024-01-15 18:06:00'),
(961, 'attributes', 0, NULL, NULL, NULL, '65a5122487fdc-pprt-16329921193034497', 'DIMENSIONS', '125 x 8 x 29 mm (4.92\" x 0.31\" x 1.14\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:08:20', '2024-01-15 18:08:20'),
(962, 'attributes', 0, NULL, NULL, NULL, '65a5122487fdc-pprt-16329921193034497', 'THICKNESS', '8 mm (0.31\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:08:20', '2024-01-15 18:08:20'),
(963, 'attributes', 0, NULL, NULL, NULL, '65a5122487fdc-pprt-16329921193034497', 'FOR MATERIALS', 'up to 45 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:08:20', '2024-01-15 18:08:20'),
(964, 'attributes', 0, NULL, NULL, NULL, '65a5122487fdc-pprt-16329921193034497', 'PACKAGING UNIT', '1', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:08:20', '2024-01-15 18:08:20'),
(965, 'attributes', 0, NULL, NULL, NULL, '65a5122487fdc-pprt-16329921193034497', 'WEIGHT', '0.4 kg (0.88 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:08:20', '2024-01-15 18:08:20'),
(966, 'attributes', 0, NULL, NULL, NULL, '65a5122487fdc-pprt-16329921193034497', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:08:20', '2024-01-15 18:08:20'),
(967, 'custom value', 0, NULL, '1', 'Notice', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:08:41', '2024-01-15 18:08:41'),
(968, 'custom value', 0, NULL, '1', 'Notice', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, '-', '', 'en', '967', '2024-01-15 18:08:41', '2024-01-15 18:08:41'),
(969, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:08:52', '2024-01-15 18:08:52'),
(970, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, '-', '', 'en', '969', '2024-01-15 18:08:52', '2024-01-15 18:08:52'),
(971, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:09:21', '2024-01-15 18:09:21'),
(972, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '971', '2024-01-15 18:09:21', '2024-01-15 18:09:21'),
(973, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '971', '2024-01-15 18:09:21', '2024-01-15 18:09:21'),
(974, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '971', '2024-01-15 18:09:21', '2024-01-15 18:09:21'),
(975, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '971', '2024-01-15 18:09:21', '2024-01-15 18:09:21'),
(976, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:09:46', '2024-01-15 18:09:46'),
(977, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a5122487fdc-pprt-16329921193034497', NULL, NULL, NULL, NULL, '170531698665a5127a60cef_41111-0308.pdf', 'en', '976', '2024-01-15 18:09:46', '2024-01-15 18:09:46'),
(978, 'attributes', 0, NULL, NULL, NULL, '65a5189fabe1b-pprt-49343963496920412', 'DIMENSIONS', '125 x 8 x 27 mm (4.92\" x 0.31\" x 1.06\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:35:59', '2024-01-15 18:35:59'),
(979, 'attributes', 0, NULL, NULL, NULL, '65a5189fabe1b-pprt-49343963496920412', 'THICKNESS', '8 mm (0.31\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:35:59', '2024-01-15 18:35:59'),
(980, 'attributes', 0, NULL, NULL, NULL, '65a5189fabe1b-pprt-49343963496920412', 'FOR MATERIALS', 'up to 45 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:35:59', '2024-01-15 18:35:59'),
(981, 'attributes', 0, NULL, NULL, NULL, '65a5189fabe1b-pprt-49343963496920412', 'PACKAGING UNIT', '1', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:35:59', '2024-01-15 18:35:59'),
(982, 'attributes', 0, NULL, NULL, NULL, '65a5189fabe1b-pprt-49343963496920412', 'WEIGHT', '0.37 kg (0.82 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:35:59', '2024-01-15 18:35:59'),
(983, 'attributes', 0, NULL, NULL, NULL, '65a5189fabe1b-pprt-49343963496920412', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:35:59', '2024-01-15 18:35:59'),
(984, 'custom value', 0, NULL, '1', 'Notice', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:36:18', '2024-01-15 18:36:18'),
(985, 'custom value', 0, NULL, '1', 'Notice', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, NULL, '-', '', 'en', '984', '2024-01-15 18:36:18', '2024-01-15 18:36:18'),
(986, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:36:42', '2024-01-15 18:36:42'),
(987, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:37:05', '2024-01-15 18:37:05'),
(988, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '987', '2024-01-15 18:37:05', '2024-01-15 18:37:05'),
(989, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '987', '2024-01-15 18:37:05', '2024-01-15 18:37:05'),
(990, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '987', '2024-01-15 18:37:05', '2024-01-15 18:37:05'),
(991, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '987', '2024-01-15 18:37:05', '2024-01-15 18:37:05'),
(992, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:37:32', '2024-01-15 18:37:32'),
(993, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a5189fabe1b-pprt-49343963496920412', NULL, NULL, NULL, NULL, '170531865265a518fca75cd_41111-0508.pdf', 'en', '992', '2024-01-15 18:37:32', '2024-01-15 18:37:32'),
(994, 'attributes', 0, NULL, NULL, NULL, '65a519e2280dd-pprt-66488020933185852', 'WRENCH SIZE', 'SW 19', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:41:22', '2024-01-15 18:41:22'),
(995, 'attributes', 0, NULL, NULL, NULL, '65a519e2280dd-pprt-66488020933185852', 'TYPE OF HEXAGONAL WRENCH', 'External hexagon', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:41:22', '2024-01-15 18:41:22'),
(996, 'attributes', 0, NULL, NULL, NULL, '65a519e2280dd-pprt-66488020933185852', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:41:22', '2024-01-15 18:41:22'),
(997, 'attributes', 0, NULL, NULL, NULL, '65a519e2280dd-pprt-66488020933185852', 'WEIGHT', '0.07 kg (0.15 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:41:22', '2024-01-15 18:41:22'),
(998, 'attributes', 0, NULL, NULL, NULL, '65a519e2280dd-pprt-66488020933185852', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 18:41:22', '2024-01-15 18:41:22'),
(999, 'custom value', 0, NULL, '1', 'Notice', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:41:46', '2024-01-15 18:41:46'),
(1000, 'custom value', 0, NULL, '1', 'Notice', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, NULL, '-', '', 'en', '999', '2024-01-15 18:41:46', '2024-01-15 18:41:46'),
(1001, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:42:11', '2024-01-15 18:42:11'),
(1002, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, NULL, '-', '', 'en', '1001', '2024-01-15 18:42:11', '2024-01-15 18:42:11'),
(1003, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 18:42:32', '2024-01-15 18:42:32'),
(1004, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1003', '2024-01-15 18:42:32', '2024-01-15 18:42:32'),
(1005, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1003', '2024-01-15 18:42:32', '2024-01-15 18:42:32'),
(1006, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1003', '2024-01-15 18:42:32', '2024-01-15 18:42:32'),
(1007, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a519e2280dd-pprt-66488020933185852', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1003', '2024-01-15 18:42:32', '2024-01-15 18:42:32'),
(1008, 'attributes', 0, NULL, NULL, NULL, '65a528fe44e90-pprt-81039581657713721', 'WRENCH SIZE', 'SW 19', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:45:50', '2024-01-15 19:45:50'),
(1009, 'attributes', 0, NULL, NULL, NULL, '65a528fe44e90-pprt-81039581657713721', 'TYPE OF HEXAGONAL WRENCH', 'External hexagon', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:45:50', '2024-01-15 19:45:50'),
(1010, 'attributes', 0, NULL, NULL, NULL, '65a528fe44e90-pprt-81039581657713721', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:45:50', '2024-01-15 19:45:50'),
(1011, 'attributes', 0, NULL, NULL, NULL, '65a528fe44e90-pprt-81039581657713721', 'WEIGHT', '0.43 kg (0.95 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:45:50', '2024-01-15 19:45:50'),
(1012, 'attributes', 0, NULL, NULL, NULL, '65a528fe44e90-pprt-81039581657713721', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:45:50', '2024-01-15 19:45:50'),
(1013, 'custom value', 0, NULL, '1', 'Notice', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:46:07', '2024-01-15 19:46:07'),
(1014, 'custom value', 0, NULL, '1', 'Notice', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, NULL, '-', '', 'en', '1013', '2024-01-15 19:46:07', '2024-01-15 19:46:07'),
(1015, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:46:16', '2024-01-15 19:46:16'),
(1016, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, NULL, '-', '', 'en', '1015', '2024-01-15 19:46:16', '2024-01-15 19:46:16'),
(1017, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:47:06', '2024-01-15 19:47:06'),
(1018, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1017', '2024-01-15 19:47:06', '2024-01-15 19:47:06'),
(1019, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1017', '2024-01-15 19:47:06', '2024-01-15 19:47:06'),
(1020, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1017', '2024-01-15 19:47:06', '2024-01-15 19:47:06'),
(1021, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a528fe44e90-pprt-81039581657713721', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1017', '2024-01-15 19:47:06', '2024-01-15 19:47:06'),
(1022, 'attributes', 0, NULL, NULL, NULL, '65a529ad59dc0-pprt-48237410740885293', 'WRENCH SIZE', 'SW 19', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:48:45', '2024-01-15 19:48:45'),
(1023, 'attributes', 0, NULL, NULL, NULL, '65a529ad59dc0-pprt-48237410740885293', 'TYPE OF HEXAGONAL WRENCH', 'External hexagon', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:48:45', '2024-01-15 19:48:45'),
(1024, 'attributes', 0, NULL, NULL, NULL, '65a529ad59dc0-pprt-48237410740885293', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:48:45', '2024-01-15 19:48:45'),
(1025, 'attributes', 0, NULL, NULL, NULL, '65a529ad59dc0-pprt-48237410740885293', 'WEIGHT', '0.07 kg (0.15 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:48:45', '2024-01-15 19:48:45'),
(1026, 'attributes', 0, NULL, NULL, NULL, '65a529ad59dc0-pprt-48237410740885293', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:48:45', '2024-01-15 19:48:45'),
(1027, 'custom value', 0, NULL, '1', 'Notice', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:49:14', '2024-01-15 19:49:14'),
(1028, 'custom value', 0, NULL, '1', 'Notice', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, NULL, '-', '', 'en', '1027', '2024-01-15 19:49:14', '2024-01-15 19:49:14'),
(1029, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:49:25', '2024-01-15 19:49:25'),
(1030, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, NULL, '-', '', 'en', '1029', '2024-01-15 19:49:25', '2024-01-15 19:49:25'),
(1031, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:49:54', '2024-01-15 19:49:54'),
(1032, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1031', '2024-01-15 19:49:54', '2024-01-15 19:49:54'),
(1033, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1031', '2024-01-15 19:49:54', '2024-01-15 19:49:54'),
(1034, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1031', '2024-01-15 19:49:54', '2024-01-15 19:49:54'),
(1035, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a529ad59dc0-pprt-48237410740885293', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1031', '2024-01-15 19:49:54', '2024-01-15 19:49:54'),
(1036, 'attributes', 0, NULL, NULL, NULL, '65a52a8cad121-pprt-88124379708574293', 'DIMENSIONS', '144 x 33 x 34 mm (5.67\" x 1.3\" x 1.34\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:52:28', '2024-01-15 19:52:28'),
(1037, 'attributes', 0, NULL, NULL, NULL, '65a52a8cad121-pprt-88124379708574293', 'FOR MATERIALS', 'up to 35 HRC', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:52:28', '2024-01-15 19:52:28'),
(1038, 'attributes', 0, NULL, NULL, NULL, '65a52a8cad121-pprt-88124379708574293', 'PACKAGING UNIT', '1 Pair', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:52:28', '2024-01-15 19:52:28'),
(1039, 'attributes', 0, NULL, NULL, NULL, '65a52a8cad121-pprt-88124379708574293', 'WEIGHT', '1.9 kg (4.19 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:52:28', '2024-01-15 19:52:28'),
(1040, 'attributes', 0, NULL, NULL, NULL, '65a52a8cad121-pprt-88124379708574293', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:52:28', '2024-01-15 19:52:28'),
(1041, 'custom value', 0, NULL, '1', 'Notice', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:53:07', '2024-01-15 19:53:07'),
(1042, 'custom value', 0, NULL, '1', 'Notice', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, '-', '', 'en', '1041', '2024-01-15 19:53:07', '2024-01-15 19:53:07'),
(1043, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:53:19', '2024-01-15 19:53:19'),
(1044, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, '-', '', 'en', '1043', '2024-01-15 19:53:19', '2024-01-15 19:53:19'),
(1045, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:54:17', '2024-01-15 19:54:17'),
(1046, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, 'With a simple and cost-effective retrofit of the Makro•Grip® stamping unit and a LANG centering vise, the form-fit clamping technology can now also be used for round parts. Makro•4Grip clamping jaws are compatible with every vise size and cover clamping ranges from Ø 36 - 300 mm.', '', 'en', '1045', '2024-01-15 19:54:17', '2024-01-15 19:54:17'),
(1047, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, 'Cost-effectiveness', 'Simple and inexpensive retrofit of Makro•4Grip clamping jaws on any LANG vise, as well as the stamping jaws on a stamping unit.', '', 'en', '1045', '2024-01-15 19:54:17', '2024-01-15 19:54:17'),
(1048, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, 'Form-fit clamping', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be machined at the same time.', '', 'en', '1045', '2024-01-15 19:54:17', '2024-01-15 19:54:17'),
(1049, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1045', '2024-01-15 19:54:17', '2024-01-15 19:54:17'),
(1050, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:54:34', '2024-01-15 19:54:34'),
(1051, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, NULL, '170532327465a52b0a26f71_51111_cad.zip', 'en', '1050', '2024-01-15 19:54:34', '2024-01-15 19:54:34'),
(1052, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:54:59', '2024-01-15 19:54:59'),
(1053, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52a8cad121-pprt-88124379708574293', NULL, NULL, NULL, NULL, '170532329965a52b238b412_51111_1.pdf', 'en', '1052', '2024-01-15 19:54:59', '2024-01-15 19:54:59'),
(1054, 'attributes', 0, NULL, NULL, NULL, '65a52b8044cd4-pprt-20802779460938925', 'SCREW SIZE', 'M5', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:56:32', '2024-01-15 19:56:32'),
(1055, 'attributes', 0, NULL, NULL, NULL, '65a52b8044cd4-pprt-20802779460938925', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:56:32', '2024-01-15 19:56:32'),
(1056, 'attributes', 0, NULL, NULL, NULL, '65a52b8044cd4-pprt-20802779460938925', 'WEIGHT', '0.11 kg (0.24 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:56:32', '2024-01-15 19:56:32'),
(1057, 'attributes', 0, NULL, NULL, NULL, '65a52b8044cd4-pprt-20802779460938925', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:56:32', '2024-01-15 19:56:32'),
(1058, 'custom value', 0, NULL, '1', 'Notice', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:56:53', '2024-01-15 19:56:53'),
(1059, 'custom value', 0, NULL, '1', 'Notice', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, 'A set of 4 stamping inserts is already included in the scope of delivery of the Makro•4Grip stamping jaws.', '', 'en', '1058', '2024-01-15 19:56:53', '2024-01-15 19:56:53'),
(1060, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:57:03', '2024-01-15 19:57:03'),
(1061, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, '-', '', 'en', '1060', '2024-01-15 19:57:03', '2024-01-15 19:57:03'),
(1062, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:57:33', '2024-01-15 19:57:33'),
(1063, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, 'With a simple and cost-effective retrofit of the Makro•Grip® stamping unit and a LANG centering vise, the form-fit clamping technology can now also be used for round parts. Makro•4Grip clamping jaws are compatible with every vise size and cover clamping ranges from Ø 36 - 300 mm.', '', 'en', '1062', '2024-01-15 19:57:33', '2024-01-15 19:57:33'),
(1064, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, 'Cost-effectiveness', 'Simple and inexpensive retrofit of Makro•4Grip clamping jaws on any LANG vise, as well as the stamping jaws on a stamping unit.', '', 'en', '1062', '2024-01-15 19:57:33', '2024-01-15 19:57:33'),
(1065, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, 'Form-fit clamping', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be machined at the same time.', '', 'en', '1062', '2024-01-15 19:57:33', '2024-01-15 19:57:33'),
(1066, 'custom value', 0, NULL, '3', 'Makro•4Grip Round Part Clamping', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1062', '2024-01-15 19:57:33', '2024-01-15 19:57:33'),
(1067, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 19:57:49', '2024-01-15 19:57:49'),
(1068, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52b8044cd4-pprt-20802779460938925', NULL, NULL, NULL, NULL, '170532346965a52bcd0a470_51111-40_cad.zip', 'en', '1067', '2024-01-15 19:57:49', '2024-01-15 19:57:49'),
(1069, 'attributes', 0, NULL, NULL, NULL, '65a52c45a057e-pprt-76873471037803464', 'FOR SUPPORT HEIGHT', '228 - 232 mm (8.98\" - 9.13\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:59:49', '2024-01-15 19:59:49'),
(1070, 'attributes', 0, NULL, NULL, NULL, '65a52c45a057e-pprt-76873471037803464', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:59:49', '2024-01-15 19:59:49'),
(1071, 'attributes', 0, NULL, NULL, NULL, '65a52c45a057e-pprt-76873471037803464', 'WEIGHT', '6.6 kg (14.55 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:59:49', '2024-01-15 19:59:49'),
(1072, 'attributes', 0, NULL, NULL, NULL, '65a52c45a057e-pprt-76873471037803464', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 19:59:49', '2024-01-15 19:59:49'),
(1073, 'custom value', 0, NULL, '1', 'Notice', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:00:10', '2024-01-15 20:00:10');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(1074, 'custom value', 0, NULL, '1', 'Notice', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, '-', '', 'en', '1073', '2024-01-15 20:00:10', '2024-01-15 20:00:10'),
(1075, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:00:22', '2024-01-15 20:00:22'),
(1076, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, '-', '', 'en', '1075', '2024-01-15 20:00:22', '2024-01-15 20:00:22'),
(1077, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:01:36', '2024-01-15 20:01:36'),
(1078, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, 'The hydraulic screw jack Hydro•Sup can be clamped with its clamping stud in the zero-point system as a support when clamping protruding workpieces. The screw jack helps to reduce possible vibrations during the machining process, especially of thin sheet material, which results in a higher surface quality of the manufactured parts.', '', 'en', '1077', '2024-01-15 20:01:36', '2024-01-15 20:01:36'),
(1079, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, 'Quality of manufactured parts', 'As a support Hydro•Sup reduces possible vibrations during the machining process.', '', 'en', '1077', '2024-01-15 20:01:36', '2024-01-15 20:01:36'),
(1080, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, 'Modularity', 'By the addition of two different spacers which can be mounted to the screw jack, all Makro•Grip® Ultra system heights can be copied.', '', 'en', '1077', '2024-01-15 20:01:36', '2024-01-15 20:01:36'),
(1081, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, 'Application diversity', 'Not only applicable for Makro•Grip® Ultra, but also for Makro•Grip® 5-Axis Vises 125 when clamping protruding workpieces.', '', 'en', '1077', '2024-01-15 20:01:36', '2024-01-15 20:01:36'),
(1082, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:01:52', '2024-01-15 20:01:52'),
(1083, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, '170532371265a52cc07156e_81523_cad.zip', 'en', '1082', '2024-01-15 20:01:52', '2024-01-15 20:01:52'),
(1084, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:02:12', '2024-01-15 20:02:12'),
(1085, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, '170532373265a52cd49ec02_81523_1.pdf', 'en', '1084', '2024-01-15 20:02:12', '2024-01-15 20:02:12'),
(1086, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:02:45', '2024-01-15 20:02:45'),
(1087, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a52c45a057e-pprt-76873471037803464', NULL, NULL, NULL, NULL, '170532376565a52cf54f0f1_11.2021_HydroSup_EN_1.pdf', 'en', '1086', '2024-01-15 20:02:45', '2024-01-15 20:02:45'),
(1088, 'attributes', 0, NULL, NULL, NULL, '65a52d56dc935-pprt-85562374145695831', 'SUPPORT HEIGHT', '152 mm (5.98\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:04:22', '2024-01-15 20:04:22'),
(1089, 'attributes', 0, NULL, NULL, NULL, '65a52d56dc935-pprt-85562374145695831', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:04:22', '2024-01-15 20:04:22'),
(1090, 'attributes', 0, NULL, NULL, NULL, '65a52d56dc935-pprt-85562374145695831', 'WEIGHT', '2.9 kg (6.39 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:04:22', '2024-01-15 20:04:22'),
(1091, 'attributes', 0, NULL, NULL, NULL, '65a52d56dc935-pprt-85562374145695831', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:04:22', '2024-01-15 20:04:22'),
(1092, 'custom value', 0, NULL, '1', 'Notice', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:04:43', '2024-01-15 20:04:43'),
(1093, 'custom value', 0, NULL, '1', 'Notice', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, '-', '', 'en', '1092', '2024-01-15 20:04:43', '2024-01-15 20:04:43'),
(1094, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:04:53', '2024-01-15 20:04:53'),
(1095, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, '-', '', 'en', '1094', '2024-01-15 20:04:53', '2024-01-15 20:04:53'),
(1096, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:05:48', '2024-01-15 20:05:48'),
(1097, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, 'The hydraulic screw jack Hydro•Sup can be clamped with its clamping stud in the zero-point system as a support when clamping protruding workpieces. The screw jack helps to reduce possible vibrations during the machining process, especially of thin sheet material, which results in a higher surface quality of the manufactured parts.', '', 'en', '1096', '2024-01-15 20:05:48', '2024-01-15 20:05:48'),
(1098, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, 'Quality of manufactured parts', 'As a support Hydro•Sup reduces possible vibrations during the machining process.', '', 'en', '1096', '2024-01-15 20:05:48', '2024-01-15 20:05:48'),
(1099, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, 'Modularity', 'By the addition of two different spacers which can be mounted to the screw jack, all Makro•Grip® Ultra system heights can be copied.', '', 'en', '1096', '2024-01-15 20:05:48', '2024-01-15 20:05:48'),
(1100, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, 'Application diversity', 'Not only applicable for Makro•Grip® Ultra, but also for Makro•Grip® 5-Axis Vises 125 when clamping protruding workpieces.', '', 'en', '1096', '2024-01-15 20:05:48', '2024-01-15 20:05:48'),
(1101, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:06:03', '2024-01-15 20:06:03'),
(1102, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, '170532396365a52dbb0476a_81515_cad.zip', 'en', '1101', '2024-01-15 20:06:03', '2024-01-15 20:06:03'),
(1103, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:06:21', '2024-01-15 20:06:21'),
(1104, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, '170532398165a52dcdd8729_81515.pdf', 'en', '1103', '2024-01-15 20:06:21', '2024-01-15 20:06:21'),
(1105, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:06:52', '2024-01-15 20:06:52'),
(1106, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a52d56dc935-pprt-85562374145695831', NULL, NULL, NULL, NULL, '170532401265a52decc167f_11.2021_HydroSup_EN_1 (1).pdf', 'en', '1105', '2024-01-15 20:06:52', '2024-01-15 20:06:52'),
(1107, 'attributes', 0, NULL, NULL, NULL, '65a52e5f0d9da-pprt-72973285938758470', 'FOR SUPPORT HEIGHT', '85 - 89 mm (3.35\" - 3.5\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:08:47', '2024-01-15 20:08:47'),
(1108, 'attributes', 0, NULL, NULL, NULL, '65a52e5f0d9da-pprt-72973285938758470', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:08:47', '2024-01-15 20:08:47'),
(1109, 'attributes', 0, NULL, NULL, NULL, '65a52e5f0d9da-pprt-72973285938758470', 'WEIGHT', '2.8 kg (6.17 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:08:47', '2024-01-15 20:08:47'),
(1110, 'attributes', 0, NULL, NULL, NULL, '65a52e5f0d9da-pprt-72973285938758470', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 20:08:47', '2024-01-15 20:08:47'),
(1111, 'custom value', 0, NULL, '1', 'Notice', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:09:09', '2024-01-15 20:09:09'),
(1112, 'custom value', 0, NULL, '1', 'Notice', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, '-', '', 'en', '1111', '2024-01-15 20:09:09', '2024-01-15 20:09:09'),
(1113, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:09:18', '2024-01-15 20:09:18'),
(1114, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, '-', '', 'en', '1113', '2024-01-15 20:09:18', '2024-01-15 20:09:18'),
(1115, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:09:58', '2024-01-15 20:09:58'),
(1116, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, 'The hydraulic screw jack Hydro•Sup can be clamped with its clamping stud in the zero-point system as a support when clamping protruding workpieces. The screw jack helps to reduce possible vibrations during the machining process, especially of thin sheet material, which results in a higher surface quality of the manufactured parts.', '', 'en', '1115', '2024-01-15 20:09:58', '2024-01-15 20:09:58'),
(1117, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, 'Quality of manufactured parts', 'As a support Hydro•Sup reduces possible vibrations during the machining process.', '', 'en', '1115', '2024-01-15 20:09:58', '2024-01-15 20:09:58'),
(1118, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, 'Modularity', 'By the addition of two different spacers which can be mounted to the screw jack, all Makro•Grip® Ultra system heights can be copied.', '', 'en', '1115', '2024-01-15 20:09:58', '2024-01-15 20:09:58'),
(1119, 'custom value', 0, NULL, '3', 'Hydro•Sup Screw Jack', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, 'Application diversity', 'Not only applicable for Makro•Grip® Ultra, but also for Makro•Grip® 5-Axis Vises 125 when clamping protruding workpieces.', '', 'en', '1115', '2024-01-15 20:09:58', '2024-01-15 20:09:58'),
(1120, 'custom value', 0, NULL, '4', 'Application pictures', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:10:38', '2024-01-15 20:10:38'),
(1121, 'custom value', 0, NULL, '4', 'Application pictures', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, '170532423865a52ece7ac08_Lang_AB_81586_003.webp', 'en', '1120', '2024-01-15 20:10:38', '2024-01-15 20:10:38'),
(1122, 'custom value', 0, NULL, '4', 'Application pictures', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, '170532423865a52ece7bbad_Lang_AB_81586_004.webp', 'en', '1120', '2024-01-15 20:10:38', '2024-01-15 20:10:38'),
(1123, 'custom value', 0, NULL, '4', 'Application pictures', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, '170532423865a52ece7c7e2_Lang_AB_81586_005.webp', 'en', '1120', '2024-01-15 20:10:38', '2024-01-15 20:10:38'),
(1124, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:11:00', '2024-01-15 20:11:00'),
(1126, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, '170532426265a52ee629dbb_81586_cad.zip', 'en', '1124', '2024-01-15 20:11:02', '2024-01-15 20:11:02'),
(1127, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:11:26', '2024-01-15 20:11:26'),
(1128, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, '170532428665a52efe7f831_81586.pdf', 'en', '1127', '2024-01-15 20:11:26', '2024-01-15 20:11:26'),
(1129, 'custom value', 0, NULL, '5', 'INSTRUCTION MANUAL', '65a52e5f0d9da-pprt-72973285938758470', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 20:11:35', '2024-01-15 20:11:35'),
(1130, 'attributes', 0, NULL, NULL, NULL, '65a541e7becb2-pprt-76741314163955876', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:32:07', '2024-01-15 21:32:07'),
(1131, 'attributes', 0, NULL, NULL, NULL, '65a541e7becb2-pprt-76741314163955876', 'WEIGHT', '0.15 kg (0.33 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:32:07', '2024-01-15 21:32:07'),
(1132, 'attributes', 0, NULL, NULL, NULL, '65a541e7becb2-pprt-76741314163955876', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:32:07', '2024-01-15 21:32:07'),
(1133, 'custom value', 0, NULL, '1', 'Notice', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:42:54', '2024-01-15 21:42:54'),
(1134, 'custom value', 0, NULL, '1', 'Notice', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, '-', '', 'en', '1133', '2024-01-15 21:42:54', '2024-01-15 21:42:54'),
(1135, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:43:06', '2024-01-15 21:43:06'),
(1136, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, '-', '', 'en', '1135', '2024-01-15 21:43:06', '2024-01-15 21:43:06'),
(1137, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:44:08', '2024-01-15 21:44:08'),
(1138, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1137', '2024-01-15 21:44:08', '2024-01-15 21:44:08'),
(1139, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1137', '2024-01-15 21:44:08', '2024-01-15 21:44:08'),
(1140, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1137', '2024-01-15 21:44:08', '2024-01-15 21:44:08'),
(1141, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1137', '2024-01-15 21:44:08', '2024-01-15 21:44:08'),
(1142, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:44:24', '2024-01-15 21:44:24'),
(1143, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a541e7becb2-pprt-76741314163955876', NULL, NULL, NULL, NULL, '170532986465a544c8a1b11_81015_cad.zip', 'en', '1142', '2024-01-15 21:44:24', '2024-01-15 21:44:24'),
(1144, 'attributes', 0, NULL, NULL, NULL, '65a545804869e-pprt-31541056344043245', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:47:28', '2024-01-15 21:47:28'),
(1145, 'attributes', 0, NULL, NULL, NULL, '65a545804869e-pprt-31541056344043245', 'WEIGHT', '0.16 kg (0.35 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:47:28', '2024-01-15 21:47:28'),
(1146, 'attributes', 0, NULL, NULL, NULL, '65a545804869e-pprt-31541056344043245', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:47:28', '2024-01-15 21:47:28'),
(1147, 'custom value', 0, NULL, '1', 'Notice', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:48:09', '2024-01-15 21:48:09'),
(1148, 'custom value', 0, NULL, '1', 'Notice', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, 'Included in delivery of Base-Sets.', '', 'en', '1147', '2024-01-15 21:48:09', '2024-01-15 21:48:09'),
(1149, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:48:23', '2024-01-15 21:48:23'),
(1150, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, '-', '', 'en', '1149', '2024-01-15 21:48:23', '2024-01-15 21:48:23'),
(1151, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:48:57', '2024-01-15 21:48:57'),
(1152, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1151', '2024-01-15 21:48:57', '2024-01-15 21:48:57'),
(1153, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a545804869e-pprt-31541056344043245', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1151', '2024-01-15 21:48:57', '2024-01-15 21:48:57'),
(1154, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a545804869e-pprt-31541056344043245', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1151', '2024-01-15 21:48:57', '2024-01-15 21:48:57'),
(1155, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a545804869e-pprt-31541056344043245', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1151', '2024-01-15 21:48:57', '2024-01-15 21:48:57'),
(1156, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 21:49:18', '2024-01-15 21:49:18'),
(1157, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a545804869e-pprt-31541056344043245', NULL, NULL, NULL, NULL, '170533015865a545ee75bc0_81010_cad.zip', 'en', '1156', '2024-01-15 21:49:18', '2024-01-15 21:49:18'),
(1158, 'attributes', 0, NULL, NULL, NULL, '65a5463d202de-pprt-13232862562592496', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:50:37', '2024-01-15 21:50:37'),
(1159, 'attributes', 0, NULL, NULL, NULL, '65a5463d202de-pprt-13232862562592496', 'WEIGHT', '0.09 kg (0.2 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:50:37', '2024-01-15 21:50:37'),
(1160, 'attributes', 0, NULL, NULL, NULL, '65a5463d202de-pprt-13232862562592496', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 21:50:37', '2024-01-15 21:50:37'),
(1161, 'custom value', 0, NULL, '1', 'Notice', '65a5463d202de-pprt-13232862562592496', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:12:34', '2024-01-15 22:12:34'),
(1162, 'custom value', 0, NULL, '1', 'Notice', '65a5463d202de-pprt-13232862562592496', NULL, NULL, NULL, '-', '', 'en', '1161', '2024-01-15 22:12:34', '2024-01-15 22:12:34'),
(1163, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5463d202de-pprt-13232862562592496', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:12:51', '2024-01-15 22:12:51'),
(1164, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5463d202de-pprt-13232862562592496', NULL, NULL, NULL, '-', '', 'en', '1163', '2024-01-15 22:12:51', '2024-01-15 22:12:51'),
(1165, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5463d202de-pprt-13232862562592496', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:13:13', '2024-01-15 22:13:13'),
(1166, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5463d202de-pprt-13232862562592496', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1165', '2024-01-15 22:13:13', '2024-01-15 22:13:13'),
(1167, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5463d202de-pprt-13232862562592496', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1165', '2024-01-15 22:13:13', '2024-01-15 22:13:13'),
(1168, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5463d202de-pprt-13232862562592496', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1165', '2024-01-15 22:13:13', '2024-01-15 22:13:13'),
(1169, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a5463d202de-pprt-13232862562592496', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1165', '2024-01-15 22:13:13', '2024-01-15 22:13:13'),
(1170, 'attributes', 0, NULL, NULL, NULL, '65a54c615aea4-pprt-60762083671876198', 'SPINDLE LENGTH', '617 mm', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:16:49', '2024-01-15 22:16:49'),
(1171, 'attributes', 0, NULL, NULL, NULL, '65a54c615aea4-pprt-60762083671876198', 'THREAD PITCH', 'M26 x 2', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:16:49', '2024-01-15 22:16:49'),
(1172, 'attributes', 0, NULL, NULL, NULL, '65a54c615aea4-pprt-60762083671876198', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:16:49', '2024-01-15 22:16:49'),
(1173, 'attributes', 0, NULL, NULL, NULL, '65a54c615aea4-pprt-60762083671876198', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:16:49', '2024-01-15 22:16:49'),
(1174, 'attributes', 0, NULL, NULL, NULL, '65a54c615aea4-pprt-60762083671876198', 'WEIGHT', '2.5 kg', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:16:49', '2024-01-15 22:16:49'),
(1175, 'attributes', 0, NULL, NULL, NULL, '65a54c615aea4-pprt-60762083671876198', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:16:49', '2024-01-15 22:16:49'),
(1176, 'custom value', 0, NULL, '1', 'Notice', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:17:14', '2024-01-15 22:17:14'),
(1177, 'custom value', 0, NULL, '1', 'Notice', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, '-', '', 'en', '1176', '2024-01-15 22:17:14', '2024-01-15 22:17:14'),
(1178, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:17:23', '2024-01-15 22:17:23'),
(1179, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, '-', '', 'en', '1178', '2024-01-15 22:17:23', '2024-01-15 22:17:23'),
(1180, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:18:17', '2024-01-15 22:18:17'),
(1181, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1180', '2024-01-15 22:18:17', '2024-01-15 22:18:17'),
(1182, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1180', '2024-01-15 22:18:17', '2024-01-15 22:18:17'),
(1183, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1180', '2024-01-15 22:18:17', '2024-01-15 22:18:17'),
(1184, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1180', '2024-01-15 22:18:17', '2024-01-15 22:18:17'),
(1185, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:18:32', '2024-01-15 22:18:32'),
(1186, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, NULL, '170533191265a54cc8b98f1_81006_cad.zip', 'en', '1185', '2024-01-15 22:18:32', '2024-01-15 22:18:32'),
(1187, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:18:51', '2024-01-15 22:18:51'),
(1188, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a54c615aea4-pprt-60762083671876198', NULL, NULL, NULL, NULL, '170533193165a54cdb50ac9_81006_2.pdf', 'en', '1187', '2024-01-15 22:18:51', '2024-01-15 22:18:51'),
(1189, 'attributes', 0, NULL, NULL, NULL, '65a54d2e1a98d-pprt-87507104211530154', 'SPINDLE LENGTH', '441 mm (17.36\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:20:14', '2024-01-15 22:20:14'),
(1190, 'attributes', 0, NULL, NULL, NULL, '65a54d2e1a98d-pprt-87507104211530154', 'THREAD PITCH', 'M26 x 2', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:20:14', '2024-01-15 22:20:14'),
(1191, 'attributes', 0, NULL, NULL, NULL, '65a54d2e1a98d-pprt-87507104211530154', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:20:14', '2024-01-15 22:20:14'),
(1192, 'attributes', 0, NULL, NULL, NULL, '65a54d2e1a98d-pprt-87507104211530154', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:20:14', '2024-01-15 22:20:14'),
(1193, 'attributes', 0, NULL, NULL, NULL, '65a54d2e1a98d-pprt-87507104211530154', 'WEIGHT', '1.8 kg (3.97 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:20:14', '2024-01-15 22:20:14'),
(1194, 'attributes', 0, NULL, NULL, NULL, '65a54d2e1a98d-pprt-87507104211530154', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:20:14', '2024-01-15 22:20:14'),
(1195, 'custom value', 0, NULL, '1', 'Notice', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:20:32', '2024-01-15 22:20:32'),
(1196, 'custom value', 0, NULL, '1', 'Notice', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, '-', '', 'en', '1195', '2024-01-15 22:20:32', '2024-01-15 22:20:32'),
(1197, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:20:40', '2024-01-15 22:20:40'),
(1198, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, '-', '', 'en', '1197', '2024-01-15 22:20:40', '2024-01-15 22:20:40'),
(1199, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:20:56', '2024-01-15 22:20:56'),
(1200, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1199', '2024-01-15 22:20:56', '2024-01-15 22:20:56'),
(1201, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1199', '2024-01-15 22:20:56', '2024-01-15 22:20:56'),
(1202, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1199', '2024-01-15 22:20:56', '2024-01-15 22:20:56'),
(1203, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1199', '2024-01-15 22:20:56', '2024-01-15 22:20:56'),
(1204, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:21:10', '2024-01-15 22:21:10'),
(1205, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, NULL, '170533207065a54d6661f9b_81004_cad.zip', 'en', '1204', '2024-01-15 22:21:10', '2024-01-15 22:21:10'),
(1206, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:21:29', '2024-01-15 22:21:29'),
(1207, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a54d2e1a98d-pprt-87507104211530154', NULL, NULL, NULL, NULL, '170533208965a54d7930095_81004_1.pdf', 'en', '1206', '2024-01-15 22:21:29', '2024-01-15 22:21:29'),
(1208, 'attributes', 0, NULL, NULL, NULL, '65a54dcc29ae7-pprt-21654328554463424', 'SPINDLE LENGTH', '825 mm (32.48\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:22:52', '2024-01-15 22:22:52'),
(1209, 'attributes', 0, NULL, NULL, NULL, '65a54dcc29ae7-pprt-21654328554463424', 'THREAD PITCH', 'M26 x 2', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:22:52', '2024-01-15 22:22:52'),
(1210, 'attributes', 0, NULL, NULL, NULL, '65a54dcc29ae7-pprt-21654328554463424', 'WORKPIECE SHAPE', 'cubic / cylindrical / asymmetric', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:22:52', '2024-01-15 22:22:52'),
(1211, 'attributes', 0, NULL, NULL, NULL, '65a54dcc29ae7-pprt-21654328554463424', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:22:52', '2024-01-15 22:22:52'),
(1212, 'attributes', 0, NULL, NULL, NULL, '65a54dcc29ae7-pprt-21654328554463424', 'WEIGHT', '3.3 kg (7.28 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:22:52', '2024-01-15 22:22:52'),
(1213, 'attributes', 0, NULL, NULL, NULL, '65a54dcc29ae7-pprt-21654328554463424', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:22:52', '2024-01-15 22:22:52'),
(1214, 'custom value', 0, NULL, '1', 'Notice', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:23:22', '2024-01-15 22:23:22'),
(1215, 'custom value', 0, NULL, '1', 'Notice', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, '-', '', 'en', '1214', '2024-01-15 22:23:22', '2024-01-15 22:23:22'),
(1216, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:23:35', '2024-01-15 22:23:35'),
(1217, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, '-', '', 'en', '1216', '2024-01-15 22:23:35', '2024-01-15 22:23:35'),
(1218, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:23:56', '2024-01-15 22:23:56'),
(1219, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1218', '2024-01-15 22:23:56', '2024-01-15 22:23:56'),
(1220, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1218', '2024-01-15 22:23:56', '2024-01-15 22:23:56'),
(1221, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1218', '2024-01-15 22:23:56', '2024-01-15 22:23:56'),
(1222, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1218', '2024-01-15 22:23:56', '2024-01-15 22:23:56'),
(1223, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:24:49', '2024-01-15 22:24:49'),
(1224, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, NULL, '170533228965a54e4126500_81008_cad.zip', 'en', '1223', '2024-01-15 22:24:49', '2024-01-15 22:24:49'),
(1225, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:25:09', '2024-01-15 22:25:09'),
(1226, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a54dcc29ae7-pprt-21654328554463424', NULL, NULL, NULL, NULL, '170533230965a54e5581874_81008_2.pdf', 'en', '1225', '2024-01-15 22:25:09', '2024-01-15 22:25:09'),
(1227, 'attributes', 0, NULL, NULL, NULL, '65a54eb59afaa-pprt-35569391385089875', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:26:45', '2024-01-15 22:26:45'),
(1228, 'attributes', 0, NULL, NULL, NULL, '65a54eb59afaa-pprt-35569391385089875', 'WEIGHT', '0.2 kg (0.44 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:26:45', '2024-01-15 22:26:45'),
(1229, 'attributes', 0, NULL, NULL, NULL, '65a54eb59afaa-pprt-35569391385089875', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:26:45', '2024-01-15 22:26:45'),
(1230, 'custom value', 0, NULL, '1', 'Notice', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:27:22', '2024-01-15 22:27:22'),
(1231, 'custom value', 0, NULL, '1', 'Notice', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, NULL, '-', '', 'en', '1230', '2024-01-15 22:27:22', '2024-01-15 22:27:22'),
(1232, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:27:32', '2024-01-15 22:27:32'),
(1233, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, NULL, '-', '', 'en', '1232', '2024-01-15 22:27:32', '2024-01-15 22:27:32'),
(1234, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:27:49', '2024-01-15 22:27:49'),
(1235, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1234', '2024-01-15 22:27:49', '2024-01-15 22:27:49'),
(1236, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1234', '2024-01-15 22:27:49', '2024-01-15 22:27:49'),
(1237, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1234', '2024-01-15 22:27:49', '2024-01-15 22:27:49'),
(1238, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54eb59afaa-pprt-35569391385089875', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1234', '2024-01-15 22:27:49', '2024-01-15 22:27:49'),
(1239, 'attributes', 0, NULL, NULL, NULL, '65a54f390d692-pprt-23350241983728928', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:28:57', '2024-01-15 22:28:57'),
(1240, 'attributes', 0, NULL, NULL, NULL, '65a54f390d692-pprt-23350241983728928', 'SCREW SIZE', 'M8', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:28:57', '2024-01-15 22:28:57'),
(1241, 'attributes', 0, NULL, NULL, NULL, '65a54f390d692-pprt-23350241983728928', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:28:57', '2024-01-15 22:28:57'),
(1242, 'custom value', 0, NULL, '1', 'Notice', '65a54f390d692-pprt-23350241983728928', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:29:19', '2024-01-15 22:29:19'),
(1243, 'custom value', 0, NULL, '1', 'Notice', '65a54f390d692-pprt-23350241983728928', NULL, NULL, NULL, '-', '', 'en', '1242', '2024-01-15 22:29:19', '2024-01-15 22:29:19'),
(1244, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54f390d692-pprt-23350241983728928', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:29:30', '2024-01-15 22:29:30'),
(1245, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54f390d692-pprt-23350241983728928', NULL, NULL, NULL, '-', '', 'en', '1244', '2024-01-15 22:29:30', '2024-01-15 22:29:30'),
(1246, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54f390d692-pprt-23350241983728928', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:29:47', '2024-01-15 22:29:47'),
(1247, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54f390d692-pprt-23350241983728928', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1246', '2024-01-15 22:29:47', '2024-01-15 22:29:47'),
(1248, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54f390d692-pprt-23350241983728928', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1246', '2024-01-15 22:29:47', '2024-01-15 22:29:47'),
(1249, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54f390d692-pprt-23350241983728928', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1246', '2024-01-15 22:29:47', '2024-01-15 22:29:47'),
(1250, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54f390d692-pprt-23350241983728928', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1246', '2024-01-15 22:29:47', '2024-01-15 22:29:47'),
(1251, 'attributes', 0, NULL, NULL, NULL, '65a54fadf18df-pprt-43524480162546939', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:30:54', '2024-01-15 22:30:54'),
(1252, 'attributes', 0, NULL, NULL, NULL, '65a54fadf18df-pprt-43524480162546939', 'WEIGHT', '0.2 kg (0.44 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:30:54', '2024-01-15 22:30:54'),
(1253, 'attributes', 0, NULL, NULL, NULL, '65a54fadf18df-pprt-43524480162546939', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:30:54', '2024-01-15 22:30:54'),
(1254, 'custom value', 0, NULL, '1', 'Notice', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:31:23', '2024-01-15 22:31:23'),
(1255, 'custom value', 0, NULL, '1', 'Notice', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, NULL, '-', '', 'en', '1254', '2024-01-15 22:31:23', '2024-01-15 22:31:23'),
(1256, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:31:33', '2024-01-15 22:31:33'),
(1257, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, NULL, '-', '', 'en', '1256', '2024-01-15 22:31:33', '2024-01-15 22:31:33'),
(1258, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:31:51', '2024-01-15 22:31:51'),
(1259, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1258', '2024-01-15 22:31:51', '2024-01-15 22:31:51'),
(1260, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1258', '2024-01-15 22:31:51', '2024-01-15 22:31:51'),
(1261, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1258', '2024-01-15 22:31:51', '2024-01-15 22:31:51'),
(1262, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a54fadf18df-pprt-43524480162546939', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1258', '2024-01-15 22:31:51', '2024-01-15 22:31:51'),
(1263, 'attributes', 0, NULL, NULL, NULL, '65a550b69adf9-pprt-26307288686340551', 'DIMENSIONS', '⌀ 12 x 50 mm (0.47\" x 1.97\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:35:18', '2024-01-15 22:35:18'),
(1264, 'attributes', 0, NULL, NULL, NULL, '65a550b69adf9-pprt-26307288686340551', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:35:18', '2024-01-15 22:35:18'),
(1265, 'attributes', 0, NULL, NULL, NULL, '65a550b69adf9-pprt-26307288686340551', 'WEIGHT', '0.08 kg (0.18 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:35:18', '2024-01-15 22:35:18'),
(1266, 'attributes', 0, NULL, NULL, NULL, '65a550b69adf9-pprt-26307288686340551', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:35:18', '2024-01-15 22:35:18'),
(1267, 'custom value', 0, NULL, '1', 'Notice', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:35:35', '2024-01-15 22:35:35'),
(1268, 'custom value', 0, NULL, '1', 'Notice', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, '-', '', 'en', '1267', '2024-01-15 22:35:35', '2024-01-15 22:35:35'),
(1269, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:35:46', '2024-01-15 22:35:46'),
(1270, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, '-', '', 'en', '1269', '2024-01-15 22:35:46', '2024-01-15 22:35:46'),
(1271, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:36:36', '2024-01-15 22:36:36'),
(1272, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1271', '2024-01-15 22:36:36', '2024-01-15 22:36:36'),
(1273, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1271', '2024-01-15 22:36:36', '2024-01-15 22:36:36'),
(1274, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1271', '2024-01-15 22:36:36', '2024-01-15 22:36:36'),
(1275, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1271', '2024-01-15 22:36:36', '2024-01-15 22:36:36'),
(1276, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:36:49', '2024-01-15 22:36:49'),
(1277, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a550b69adf9-pprt-26307288686340551', NULL, NULL, NULL, NULL, '170533300965a55111d245d_451250_cad.zip', 'en', '1276', '2024-01-15 22:36:49', '2024-01-15 22:36:49'),
(1278, 'attributes', 0, NULL, NULL, NULL, '65a5518cc692a-pprt-67585531526088622', 'DIMENSIONS', '⌀ 25 x 30 mm (0.98\" x 1.18\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:38:52', '2024-01-15 22:38:52'),
(1279, 'attributes', 0, NULL, NULL, NULL, '65a5518cc692a-pprt-67585531526088622', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:38:52', '2024-01-15 22:38:52'),
(1280, 'attributes', 0, NULL, NULL, NULL, '65a5518cc692a-pprt-67585531526088622', 'WEIGHT', '0.05 kg (0.11 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:38:52', '2024-01-15 22:38:52'),
(1281, 'attributes', 0, NULL, NULL, NULL, '65a5518cc692a-pprt-67585531526088622', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:38:52', '2024-01-15 22:38:52'),
(1282, 'custom value', 0, NULL, '1', 'Notice', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:39:21', '2024-01-15 22:39:21'),
(1283, 'custom value', 0, NULL, '1', 'Notice', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, '-', '', 'en', '1282', '2024-01-15 22:39:21', '2024-01-15 22:39:21'),
(1284, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:39:31', '2024-01-15 22:39:31'),
(1285, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, '-', '', 'en', '1284', '2024-01-15 22:39:31', '2024-01-15 22:39:31'),
(1286, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:40:01', '2024-01-15 22:40:01'),
(1287, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1286', '2024-01-15 22:40:01', '2024-01-15 22:40:01'),
(1288, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1286', '2024-01-15 22:40:01', '2024-01-15 22:40:01'),
(1289, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1286', '2024-01-15 22:40:01', '2024-01-15 22:40:01');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(1290, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1286', '2024-01-15 22:40:01', '2024-01-15 22:40:01'),
(1291, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:40:20', '2024-01-15 22:40:20'),
(1292, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a5518cc692a-pprt-67585531526088622', NULL, NULL, NULL, NULL, '170533322065a551e498a80_452530_cad.zip', 'en', '1291', '2024-01-15 22:40:20', '2024-01-15 22:40:20'),
(1293, 'attributes', 0, NULL, NULL, NULL, '65a554b08493f-pprt-78959154189848019', 'DIMENSIONS', '⌀ 50 x 32 mm (1.97\" x 1.26\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:52:16', '2024-01-15 22:52:16'),
(1294, 'attributes', 0, NULL, NULL, NULL, '65a554b08493f-pprt-78959154189848019', 'PACKAGING UNIT', '0.05 kg (0.11 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:52:16', '2024-01-15 22:52:16'),
(1295, 'attributes', 0, NULL, NULL, NULL, '65a554b08493f-pprt-78959154189848019', 'WEIGHT', '0.09 kg (0.2 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:52:16', '2024-01-15 22:52:16'),
(1296, 'attributes', 0, NULL, NULL, NULL, '65a554b08493f-pprt-78959154189848019', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:52:16', '2024-01-15 22:52:16'),
(1297, 'custom value', 0, NULL, '1', 'Notice', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:52:47', '2024-01-15 22:52:47'),
(1298, 'custom value', 0, NULL, '1', 'Notice', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, '-', '', 'en', '1297', '2024-01-15 22:52:47', '2024-01-15 22:52:47'),
(1299, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:53:00', '2024-01-15 22:53:00'),
(1300, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, '-', '', 'en', '1299', '2024-01-15 22:53:00', '2024-01-15 22:53:00'),
(1301, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:53:27', '2024-01-15 22:53:27'),
(1302, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1301', '2024-01-15 22:53:27', '2024-01-15 22:53:27'),
(1303, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a554b08493f-pprt-78959154189848019', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1301', '2024-01-15 22:53:27', '2024-01-15 22:53:27'),
(1304, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a554b08493f-pprt-78959154189848019', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1301', '2024-01-15 22:53:27', '2024-01-15 22:53:27'),
(1305, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a554b08493f-pprt-78959154189848019', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1301', '2024-01-15 22:53:27', '2024-01-15 22:53:27'),
(1306, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:53:48', '2024-01-15 22:53:48'),
(1307, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a554b08493f-pprt-78959154189848019', NULL, NULL, NULL, NULL, '170533402865a5550cb04b3_455032_cad.zip', 'en', '1306', '2024-01-15 22:53:48', '2024-01-15 22:53:48'),
(1308, 'attributes', 0, NULL, NULL, NULL, '65a5557bcac08-pprt-35393386785680256', 'DIMENSIONS', '⌀ 50 x 50 mm (1.97\" x 1.97\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:55:39', '2024-01-15 22:55:39'),
(1309, 'attributes', 0, NULL, NULL, NULL, '65a5557bcac08-pprt-35393386785680256', 'PACKAGING UNIT', '0.05 kg (0.11 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:55:39', '2024-01-15 22:55:39'),
(1310, 'attributes', 0, NULL, NULL, NULL, '65a5557bcac08-pprt-35393386785680256', 'WEIGHT', '0.12 kg (0.26 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:55:39', '2024-01-15 22:55:39'),
(1311, 'attributes', 0, NULL, NULL, NULL, '65a5557bcac08-pprt-35393386785680256', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:55:39', '2024-01-15 22:55:39'),
(1312, 'custom value', 0, NULL, '1', 'Notice', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:56:11', '2024-01-15 22:56:11'),
(1313, 'custom value', 0, NULL, '1', 'Notice', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, '-', '', 'en', '1312', '2024-01-15 22:56:11', '2024-01-15 22:56:11'),
(1314, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:56:47', '2024-01-15 22:56:47'),
(1315, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, '-', '', 'en', '1314', '2024-01-15 22:56:47', '2024-01-15 22:56:47'),
(1316, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:57:21', '2024-01-15 22:57:21'),
(1317, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1316', '2024-01-15 22:57:21', '2024-01-15 22:57:21'),
(1318, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1316', '2024-01-15 22:57:21', '2024-01-15 22:57:21'),
(1319, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1316', '2024-01-15 22:57:21', '2024-01-15 22:57:21'),
(1320, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1316', '2024-01-15 22:57:21', '2024-01-15 22:57:21'),
(1321, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:57:36', '2024-01-15 22:57:36'),
(1322, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a5557bcac08-pprt-35393386785680256', NULL, NULL, NULL, NULL, '170533425665a555f06c024_455050_cad.zip', 'en', '1321', '2024-01-15 22:57:36', '2024-01-15 22:57:36'),
(1323, 'attributes', 0, NULL, NULL, NULL, '65a556641805f-pprt-68956187772782603', 'DIMENSIONS', '⌀ 12 x 32 mm (0.47\" x 1.26\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:59:32', '2024-01-15 22:59:32'),
(1324, 'attributes', 0, NULL, NULL, NULL, '65a556641805f-pprt-68956187772782603', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:59:32', '2024-01-15 22:59:32'),
(1325, 'attributes', 0, NULL, NULL, NULL, '65a556641805f-pprt-68956187772782603', 'WEIGHT', '0.04 kg (0.09 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:59:32', '2024-01-15 22:59:32'),
(1326, 'attributes', 0, NULL, NULL, NULL, '65a556641805f-pprt-68956187772782603', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 22:59:32', '2024-01-15 22:59:32'),
(1327, 'custom value', 0, NULL, '1', 'Notice', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 22:59:57', '2024-01-15 22:59:57'),
(1328, 'custom value', 0, NULL, '1', 'Notice', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, '-', '', 'en', '1327', '2024-01-15 22:59:57', '2024-01-15 22:59:57'),
(1329, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:00:07', '2024-01-15 23:00:07'),
(1330, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, '-', '', 'en', '1329', '2024-01-15 23:00:07', '2024-01-15 23:00:07'),
(1331, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:00:32', '2024-01-15 23:00:32'),
(1332, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1331', '2024-01-15 23:00:32', '2024-01-15 23:00:32'),
(1333, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a556641805f-pprt-68956187772782603', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1331', '2024-01-15 23:00:32', '2024-01-15 23:00:32'),
(1334, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a556641805f-pprt-68956187772782603', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1331', '2024-01-15 23:00:32', '2024-01-15 23:00:32'),
(1335, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a556641805f-pprt-68956187772782603', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1331', '2024-01-15 23:00:32', '2024-01-15 23:00:32'),
(1336, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:00:47', '2024-01-15 23:00:47'),
(1337, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a556641805f-pprt-68956187772782603', NULL, NULL, NULL, NULL, '170533444765a556af210ab_451232_cad.zip', 'en', '1336', '2024-01-15 23:00:47', '2024-01-15 23:00:47'),
(1338, 'attributes', 0, NULL, NULL, NULL, '65a5571c2eb0c-pprt-32684766707114343', 'WRENCH SIZE', 'SW 5', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:02:36', '2024-01-15 23:02:36'),
(1339, 'attributes', 0, NULL, NULL, NULL, '65a5571c2eb0c-pprt-32684766707114343', 'TYPE OF HEXAGONAL WRENCH', 'Hexagon socket', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:02:36', '2024-01-15 23:02:36'),
(1340, 'attributes', 0, NULL, NULL, NULL, '65a5571c2eb0c-pprt-32684766707114343', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:02:36', '2024-01-15 23:02:36'),
(1341, 'attributes', 0, NULL, NULL, NULL, '65a5571c2eb0c-pprt-32684766707114343', 'WEIGHT', '0.055 kg (0.12 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:02:36', '2024-01-15 23:02:36'),
(1342, 'attributes', 0, NULL, NULL, NULL, '65a5571c2eb0c-pprt-32684766707114343', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:02:36', '2024-01-15 23:02:36'),
(1343, 'custom value', 0, NULL, '1', 'Notice', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:03:00', '2024-01-15 23:03:00'),
(1344, 'custom value', 0, NULL, '1', 'Notice', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, NULL, '-', '', 'en', '1343', '2024-01-15 23:03:00', '2024-01-15 23:03:00'),
(1345, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:03:10', '2024-01-15 23:03:10'),
(1346, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, NULL, '-', '', 'en', '1345', '2024-01-15 23:03:10', '2024-01-15 23:03:10'),
(1347, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:04:12', '2024-01-15 23:04:12'),
(1348, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '1347', '2024-01-15 23:04:12', '2024-01-15 23:04:12'),
(1349, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '1347', '2024-01-15 23:04:12', '2024-01-15 23:04:12'),
(1350, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '1347', '2024-01-15 23:04:12', '2024-01-15 23:04:12'),
(1351, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5571c2eb0c-pprt-32684766707114343', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1347', '2024-01-15 23:04:12', '2024-01-15 23:04:12'),
(1352, 'attributes', 0, NULL, NULL, NULL, '65a55844e9d2c-pprt-36134513823591185', 'WRENCH SIZE', 'SW 19', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:07:32', '2024-01-15 23:07:32'),
(1353, 'attributes', 0, NULL, NULL, NULL, '65a55844e9d2c-pprt-36134513823591185', 'TYPE OF HEXAGONAL WRENCH', 'External hexagon', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:07:32', '2024-01-15 23:07:32'),
(1354, 'attributes', 0, NULL, NULL, NULL, '65a55844e9d2c-pprt-36134513823591185', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:07:32', '2024-01-15 23:07:32'),
(1355, 'attributes', 0, NULL, NULL, NULL, '65a55844e9d2c-pprt-36134513823591185', 'WEIGHT', '0.07 kg (0.15 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:07:32', '2024-01-15 23:07:32'),
(1356, 'attributes', 0, NULL, NULL, NULL, '65a55844e9d2c-pprt-36134513823591185', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:07:32', '2024-01-15 23:07:32'),
(1357, 'custom value', 0, NULL, '1', 'Notice', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:07:57', '2024-01-15 23:07:57'),
(1358, 'custom value', 0, NULL, '1', 'Notice', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, NULL, '-', '', 'en', '1357', '2024-01-15 23:07:57', '2024-01-15 23:07:57'),
(1359, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:08:26', '2024-01-15 23:08:26'),
(1360, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, NULL, '-', '', 'en', '1359', '2024-01-15 23:08:26', '2024-01-15 23:08:26'),
(1361, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:09:37', '2024-01-15 23:09:37'),
(1362, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1361', '2024-01-15 23:09:37', '2024-01-15 23:09:37'),
(1363, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1361', '2024-01-15 23:09:37', '2024-01-15 23:09:37'),
(1364, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1361', '2024-01-15 23:09:37', '2024-01-15 23:09:37'),
(1365, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55844e9d2c-pprt-36134513823591185', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1361', '2024-01-15 23:09:37', '2024-01-15 23:09:37'),
(1366, 'attributes', 0, NULL, NULL, NULL, '65a5594bd1cfa-pprt-34971564372624077', 'WRENCH SIZE', 'SW 12', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:11:55', '2024-01-15 23:11:55'),
(1367, 'attributes', 0, NULL, NULL, NULL, '65a5594bd1cfa-pprt-34971564372624077', 'INCH THREAD', '3/8\"', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:11:55', '2024-01-15 23:11:55'),
(1368, 'attributes', 0, NULL, NULL, NULL, '65a5594bd1cfa-pprt-34971564372624077', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:11:55', '2024-01-15 23:11:55'),
(1369, 'attributes', 0, NULL, NULL, NULL, '65a5594bd1cfa-pprt-34971564372624077', 'WEIGHT', '0.02 kg (0.04 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:11:55', '2024-01-15 23:11:55'),
(1370, 'attributes', 0, NULL, NULL, NULL, '65a5594bd1cfa-pprt-34971564372624077', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:11:55', '2024-01-15 23:11:55'),
(1371, 'custom value', 0, NULL, '1', 'Notice', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:12:18', '2024-01-15 23:12:18'),
(1372, 'custom value', 0, NULL, '1', 'Notice', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, NULL, '-', '', 'en', '1371', '2024-01-15 23:12:18', '2024-01-15 23:12:18'),
(1373, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:12:28', '2024-01-15 23:12:28'),
(1374, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, NULL, '-', '', 'en', '1373', '2024-01-15 23:12:28', '2024-01-15 23:12:28'),
(1375, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:12:50', '2024-01-15 23:12:50'),
(1376, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '1375', '2024-01-15 23:12:50', '2024-01-15 23:12:50'),
(1377, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '1375', '2024-01-15 23:12:50', '2024-01-15 23:12:50'),
(1378, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '1375', '2024-01-15 23:12:50', '2024-01-15 23:12:50'),
(1379, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a5594bd1cfa-pprt-34971564372624077', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1375', '2024-01-15 23:12:50', '2024-01-15 23:12:50'),
(1380, 'attributes', 0, NULL, NULL, NULL, '65a559de23cb5-pprt-78968457307496291', 'WRENCH SIZE', 'SW 15', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:14:22', '2024-01-15 23:14:22'),
(1381, 'attributes', 0, NULL, NULL, NULL, '65a559de23cb5-pprt-78968457307496291', 'INCH THREAD', '3/8\"', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:14:22', '2024-01-15 23:14:22'),
(1382, 'attributes', 0, NULL, NULL, NULL, '65a559de23cb5-pprt-78968457307496291', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:14:22', '2024-01-15 23:14:22'),
(1383, 'attributes', 0, NULL, NULL, NULL, '65a559de23cb5-pprt-78968457307496291', 'WEIGHT', '0.04 kg (0.09 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:14:22', '2024-01-15 23:14:22'),
(1384, 'attributes', 0, NULL, NULL, NULL, '65a559de23cb5-pprt-78968457307496291', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:14:22', '2024-01-15 23:14:22'),
(1385, 'attributes', 0, NULL, NULL, NULL, '65a559de23cb5-pprt-78968457307496291', 'TYPE OF HEXAGONAL WRENCH', 'External hexagon', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:14:22', '2024-01-15 23:14:22'),
(1386, 'custom value', 0, NULL, '1', 'Notice', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:15:06', '2024-01-15 23:15:06'),
(1387, 'custom value', 0, NULL, '1', 'Notice', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, NULL, '-', '', 'en', '1386', '2024-01-15 23:15:06', '2024-01-15 23:15:06'),
(1388, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:15:17', '2024-01-15 23:15:17'),
(1389, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, NULL, '-', '', 'en', '1388', '2024-01-15 23:15:17', '2024-01-15 23:15:17'),
(1390, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:15:44', '2024-01-15 23:15:44'),
(1391, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '1390', '2024-01-15 23:15:44', '2024-01-15 23:15:44'),
(1392, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '1390', '2024-01-15 23:15:44', '2024-01-15 23:15:44'),
(1393, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '1390', '2024-01-15 23:15:44', '2024-01-15 23:15:44'),
(1394, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a559de23cb5-pprt-78968457307496291', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1390', '2024-01-15 23:15:44', '2024-01-15 23:15:44'),
(1395, 'attributes', 0, NULL, NULL, NULL, '65a55a838f824-pprt-47425373252277819', 'WRENCH SIZE', 'SW 5', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:17:07', '2024-01-15 23:17:07'),
(1396, 'attributes', 0, NULL, NULL, NULL, '65a55a838f824-pprt-47425373252277819', 'INCH THREAD', '3/8\"', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:17:07', '2024-01-15 23:17:07'),
(1397, 'attributes', 0, NULL, NULL, NULL, '65a55a838f824-pprt-47425373252277819', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:17:07', '2024-01-15 23:17:07'),
(1398, 'attributes', 0, NULL, NULL, NULL, '65a55a838f824-pprt-47425373252277819', 'WEIGHT', '0.055 kg (0.12 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:17:07', '2024-01-15 23:17:07'),
(1399, 'attributes', 0, NULL, NULL, NULL, '65a55a838f824-pprt-47425373252277819', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:17:07', '2024-01-15 23:17:07'),
(1400, 'attributes', 0, NULL, NULL, NULL, '65a55a838f824-pprt-47425373252277819', 'TYPE OF HEXAGONAL WRENCH', 'Hexagon socket', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:17:07', '2024-01-15 23:17:07'),
(1401, 'custom value', 0, NULL, '1', 'Notice', '65a55a838f824-pprt-47425373252277819', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:17:27', '2024-01-15 23:17:27'),
(1402, 'custom value', 0, NULL, '1', 'Notice', '65a55a838f824-pprt-47425373252277819', NULL, NULL, NULL, '-', '', 'en', '1401', '2024-01-15 23:17:27', '2024-01-15 23:17:27'),
(1403, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55a838f824-pprt-47425373252277819', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:17:39', '2024-01-15 23:17:39'),
(1404, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55a838f824-pprt-47425373252277819', NULL, NULL, NULL, '-', '', 'en', '1403', '2024-01-15 23:17:39', '2024-01-15 23:17:39'),
(1405, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55a838f824-pprt-47425373252277819', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:17:54', '2024-01-15 23:17:54'),
(1406, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55a838f824-pprt-47425373252277819', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '1405', '2024-01-15 23:17:54', '2024-01-15 23:17:54'),
(1407, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55a838f824-pprt-47425373252277819', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '1405', '2024-01-15 23:17:54', '2024-01-15 23:17:54'),
(1408, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55a838f824-pprt-47425373252277819', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '1405', '2024-01-15 23:17:54', '2024-01-15 23:17:54'),
(1409, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55a838f824-pprt-47425373252277819', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1405', '2024-01-15 23:17:54', '2024-01-15 23:17:54'),
(1410, 'attributes', 0, NULL, NULL, NULL, '65a55af738101-pprt-95952455688051591', 'WRENCH SIZE', 'SW 19', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:19:03', '2024-01-15 23:19:03'),
(1411, 'attributes', 0, NULL, NULL, NULL, '65a55af738101-pprt-95952455688051591', 'INCH THREAD', '3/8\"', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:19:03', '2024-01-15 23:19:03'),
(1412, 'attributes', 0, NULL, NULL, NULL, '65a55af738101-pprt-95952455688051591', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:19:03', '2024-01-15 23:19:03'),
(1413, 'attributes', 0, NULL, NULL, NULL, '65a55af738101-pprt-95952455688051591', 'WEIGHT', '0.43 kg (0.95 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:19:03', '2024-01-15 23:19:03'),
(1414, 'attributes', 0, NULL, NULL, NULL, '65a55af738101-pprt-95952455688051591', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:19:03', '2024-01-15 23:19:03'),
(1415, 'attributes', 0, NULL, NULL, NULL, '65a55af738101-pprt-95952455688051591', 'TYPE OF HEXAGONAL WRENCH', 'Hexagon socket', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:19:03', '2024-01-15 23:19:03'),
(1416, 'custom value', 0, NULL, '1', 'Notice', '65a55af738101-pprt-95952455688051591', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:19:22', '2024-01-15 23:19:22'),
(1417, 'custom value', 0, NULL, '1', 'Notice', '65a55af738101-pprt-95952455688051591', NULL, NULL, NULL, '-', '', 'en', '1416', '2024-01-15 23:19:22', '2024-01-15 23:19:22'),
(1418, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55af738101-pprt-95952455688051591', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:19:32', '2024-01-15 23:19:32'),
(1419, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55af738101-pprt-95952455688051591', NULL, NULL, NULL, '-', '', 'en', '1418', '2024-01-15 23:19:32', '2024-01-15 23:19:32'),
(1420, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55af738101-pprt-95952455688051591', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:23:06', '2024-01-15 23:23:06'),
(1421, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55af738101-pprt-95952455688051591', NULL, NULL, NULL, 'Makro•Grip® Ultra offers countless clamping possibilities and is perfectly fitted for machining applications of flat or large parts and also mould making. Thanks to its expandability and different jaw types, the modular clamping system practically covers any imaginable machining application.', '', 'en', '1420', '2024-01-15 23:23:06', '2024-01-15 23:23:06'),
(1422, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55af738101-pprt-95952455688051591', NULL, NULL, 'Modularity', 'Changeover of clamping configuration within seconds through expansion of clamping ranges and exchange of clamping jaws', '', 'en', '1420', '2024-01-15 23:23:06', '2024-01-15 23:23:06'),
(1423, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55af738101-pprt-95952455688051591', NULL, NULL, 'Application diversity', 'Equally applicable for single part or multiple clamping, cubic, round our asymmetric workpieces', '', 'en', '1420', '2024-01-15 23:23:06', '2024-01-15 23:23:06'),
(1424, 'custom value', 0, NULL, '3', 'Makro•Grip® Ultra', '65a55af738101-pprt-95952455688051591', NULL, NULL, 'Centric clamping of large components', 'Possibility of clamping workpieces of 800 mm or even larger', '', 'en', '1420', '2024-01-15 23:23:06', '2024-01-15 23:23:06'),
(1425, 'attributes', 0, NULL, NULL, NULL, '65a55c8b453fd-pprt-96403012197130997', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:25:47', '2024-01-15 23:25:47'),
(1426, 'attributes', 0, NULL, NULL, NULL, '65a55c8b453fd-pprt-96403012197130997', 'WEIGHT', '0.11 kg', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:25:47', '2024-01-15 23:25:47'),
(1427, 'attributes', 0, NULL, NULL, NULL, '65a55c8b453fd-pprt-96403012197130997', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:25:47', '2024-01-15 23:25:47'),
(1428, 'custom value', 0, NULL, '1', 'Notice', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:26:23', '2024-01-15 23:26:23'),
(1429, 'custom value', 0, NULL, '1', 'Notice', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, '-', '', 'en', '1428', '2024-01-15 23:26:23', '2024-01-15 23:26:23'),
(1430, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:26:31', '2024-01-15 23:26:31'),
(1431, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, '-', '', 'en', '1430', '2024-01-15 23:26:31', '2024-01-15 23:26:31'),
(1432, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:27:17', '2024-01-15 23:27:17'),
(1433, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '1432', '2024-01-15 23:27:17', '2024-01-15 23:27:17'),
(1434, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '1432', '2024-01-15 23:27:17', '2024-01-15 23:27:17'),
(1435, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '1432', '2024-01-15 23:27:17', '2024-01-15 23:27:17'),
(1436, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1432', '2024-01-15 23:27:17', '2024-01-15 23:27:17'),
(1437, 'custom value', 0, NULL, '4', 'Application pictures', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:28:20', '2024-01-15 23:28:20'),
(1438, 'custom value', 0, NULL, '4', 'Application pictures', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, NULL, '170533610065a55d245b544_Lang_AB_41020_003.webp', 'en', '1437', '2024-01-15 23:28:20', '2024-01-15 23:28:20'),
(1439, 'custom value', 0, NULL, '4', 'Application pictures', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, NULL, '170533610065a55d245c4d5_Lang_AB_41020_002.webp', 'en', '1437', '2024-01-15 23:28:20', '2024-01-15 23:28:20'),
(1440, 'custom value', 0, NULL, '4', 'Application pictures', '65a55c8b453fd-pprt-96403012197130997', NULL, NULL, NULL, NULL, '170533610065a55d245d27d_Lang_AB_41020_001.webp', 'en', '1437', '2024-01-15 23:28:20', '2024-01-15 23:28:20'),
(1441, 'attributes', 0, NULL, NULL, NULL, '65a55ef45deaf-pprt-20912570449368609', 'SCREW SIZE', 'M6', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:36:04', '2024-01-15 23:36:04'),
(1442, 'attributes', 0, NULL, NULL, NULL, '65a55ef45deaf-pprt-20912570449368609', 'DIMENSIONS', '77 x 27 x 20.5 mm (3.03\" x 1.06\" x 0.81\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:36:04', '2024-01-15 23:36:04'),
(1443, 'attributes', 0, NULL, NULL, NULL, '65a55ef45deaf-pprt-20912570449368609', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:36:04', '2024-01-15 23:36:04'),
(1444, 'attributes', 0, NULL, NULL, NULL, '65a55ef45deaf-pprt-20912570449368609', 'WEIGHT', '0.2 kg (0.44 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:36:04', '2024-01-15 23:36:04'),
(1445, 'attributes', 0, NULL, NULL, NULL, '65a55ef45deaf-pprt-20912570449368609', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:36:04', '2024-01-15 23:36:04'),
(1446, 'custom value', 0, NULL, '1', 'Notice', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:36:37', '2024-01-15 23:36:37'),
(1447, 'custom value', 0, NULL, '1', 'Notice', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, 'Not included in delivery of stamping units.', '', 'en', '1446', '2024-01-15 23:36:37', '2024-01-15 23:36:37'),
(1448, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:36:48', '2024-01-15 23:36:48'),
(1449, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, '-', '', 'en', '1448', '2024-01-15 23:36:48', '2024-01-15 23:36:48'),
(1450, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:37:06', '2024-01-15 23:37:06'),
(1451, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '1450', '2024-01-15 23:37:06', '2024-01-15 23:37:06'),
(1452, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '1450', '2024-01-15 23:37:06', '2024-01-15 23:37:06'),
(1453, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '1450', '2024-01-15 23:37:06', '2024-01-15 23:37:06'),
(1454, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1450', '2024-01-15 23:37:06', '2024-01-15 23:37:06'),
(1455, 'custom value', 0, NULL, '4', 'Application pictures', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-15 23:37:22', '2024-01-15 23:37:22'),
(1456, 'custom value', 0, NULL, '4', 'Application pictures', '65a55ef45deaf-pprt-20912570449368609', NULL, NULL, NULL, NULL, '170533664265a55f42f35cc_Lang_AB_41010_002.webp', 'en', '1455', '2024-01-15 23:37:22', '2024-01-15 23:37:22'),
(1457, 'attributes', 0, NULL, NULL, NULL, '65a55fc16afbc-pprt-10978790070792751', 'DIMENSIONS', '⌀ 5.6 x 23.7 mm (0.22\" x 0.93\")', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:39:29', '2024-01-15 23:39:29'),
(1458, 'attributes', 0, NULL, NULL, NULL, '65a55fc16afbc-pprt-10978790070792751', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:39:29', '2024-01-15 23:39:29'),
(1459, 'attributes', 0, NULL, NULL, NULL, '65a55fc16afbc-pprt-10978790070792751', 'WEIGHT', '0.05 kg (0.11 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:39:29', '2024-01-15 23:39:29'),
(1460, 'attributes', 0, NULL, NULL, NULL, '65a55fc16afbc-pprt-10978790070792751', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-15 23:39:29', '2024-01-15 23:39:29'),
(1461, 'custom value', 0, NULL, '1', 'Notice', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:34:16', '2024-01-16 00:34:16'),
(1462, 'custom value', 0, NULL, '1', 'Notice', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, NULL, '-', '', 'en', '1461', '2024-01-16 00:34:16', '2024-01-16 00:34:16'),
(1463, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:34:27', '2024-01-16 00:34:27'),
(1464, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, NULL, '-', '', 'en', '1463', '2024-01-16 00:34:27', '2024-01-16 00:34:27'),
(1465, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:34:46', '2024-01-16 00:34:46'),
(1466, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, NULL, 'The Makro•Grip® 5-Axis Vise and its unique benefits of the stamping technology has been considered „The Original“ and a benchmark in the 5-face machining of raw parts for years. Its compact design and high holding forces make the Makro•Grip® 5-Axis Vise the ideal clamping device for machining raw parts.', '', 'en', '1465', '2024-01-16 00:34:46', '2024-01-16 00:34:46'),
(1467, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, 'Holding force', 'Thanks to the form-fit clamping principle, highest holding forces can be achieved with Makro•Grip®, even at low clamping pressure.', '', 'en', '1465', '2024-01-16 00:34:46', '2024-01-16 00:34:46'),
(1468, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, 'Process reliability', 'Clamping with Makro•Grip® provides maximum process reliability and is easy on the workpiece to be processes at the same time.', '', 'en', '1465', '2024-01-16 00:34:46', '2024-01-16 00:34:46'),
(1469, 'custom value', 0, NULL, '3', 'Makro•Grip® Stamping Technology and Raw Part Clamping', '65a55fc16afbc-pprt-10978790070792751', NULL, NULL, 'Accessibility', 'The compact Makro•Grip® self-centering vises guarantee ideal accessibility in the 5-axis machining of raw parts.', '', 'en', '1465', '2024-01-16 00:34:46', '2024-01-16 00:34:46'),
(1470, 'attributes', 0, NULL, NULL, NULL, '65a56d3b8379c-pprt-57412663916591444', 'DIMENSIONS', '⌀ 12 x 12 mm (0.47\" x 0.47\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:36:59', '2024-01-16 00:36:59'),
(1471, 'attributes', 0, NULL, NULL, NULL, '65a56d3b8379c-pprt-57412663916591444', 'SCREW SIZE', 'M8', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:36:59', '2024-01-16 00:36:59'),
(1472, 'attributes', 0, NULL, NULL, NULL, '65a56d3b8379c-pprt-57412663916591444', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:36:59', '2024-01-16 00:36:59'),
(1473, 'attributes', 0, NULL, NULL, NULL, '65a56d3b8379c-pprt-57412663916591444', 'WEIGHT', '0.01 kg (0.02 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:36:59', '2024-01-16 00:36:59'),
(1474, 'attributes', 0, NULL, NULL, NULL, '65a56d3b8379c-pprt-57412663916591444', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:36:59', '2024-01-16 00:36:59'),
(1475, 'custom value', 0, NULL, '1', 'Notice', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:37:42', '2024-01-16 00:37:42'),
(1476, 'custom value', 0, NULL, '1', 'Notice', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, NULL, '-', '', 'en', '1475', '2024-01-16 00:37:42', '2024-01-16 00:37:42'),
(1477, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:38:01', '2024-01-16 00:38:01'),
(1478, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, NULL, '-', '', 'en', '1477', '2024-01-16 00:38:01', '2024-01-16 00:38:01'),
(1479, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:38:55', '2024-01-16 00:38:55'),
(1480, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1479', '2024-01-16 00:38:55', '2024-01-16 00:38:55'),
(1481, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1479', '2024-01-16 00:38:55', '2024-01-16 00:38:55'),
(1482, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1479', '2024-01-16 00:38:55', '2024-01-16 00:38:55'),
(1483, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56d3b8379c-pprt-57412663916591444', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1479', '2024-01-16 00:38:55', '2024-01-16 00:38:55'),
(1484, 'attributes', 0, NULL, NULL, NULL, '65a56df808c3e-pprt-34165539636490237', 'DIMENSIONS', '⌀ 12 x 12 mm (0.47\" x 0.47\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:40:08', '2024-01-16 00:40:08'),
(1485, 'attributes', 0, NULL, NULL, NULL, '65a56df808c3e-pprt-34165539636490237', 'SCREW SIZE', 'M10', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:40:08', '2024-01-16 00:40:08'),
(1486, 'attributes', 0, NULL, NULL, NULL, '65a56df808c3e-pprt-34165539636490237', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:40:08', '2024-01-16 00:40:08'),
(1487, 'attributes', 0, NULL, NULL, NULL, '65a56df808c3e-pprt-34165539636490237', 'WEIGHT', '0.01 kg (0.02 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:40:08', '2024-01-16 00:40:08'),
(1488, 'attributes', 0, NULL, NULL, NULL, '65a56df808c3e-pprt-34165539636490237', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:40:08', '2024-01-16 00:40:08'),
(1489, 'custom value', 0, NULL, '1', 'Notice', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:41:06', '2024-01-16 00:41:06'),
(1490, 'custom value', 0, NULL, '1', 'Notice', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, NULL, 'Supplied with the following products:, - 75600, - 75710, - 43060, - 43100, - 44060, - 44100, - 44006, - 44010', '', 'en', '1489', '2024-01-16 00:41:06', '2024-01-16 00:41:06'),
(1491, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:41:19', '2024-01-16 00:41:19'),
(1492, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, NULL, '-', '', 'en', '1491', '2024-01-16 00:41:19', '2024-01-16 00:41:19'),
(1493, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:42:24', '2024-01-16 00:42:24'),
(1494, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1493', '2024-01-16 00:42:24', '2024-01-16 00:42:24');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(1495, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1493', '2024-01-16 00:42:24', '2024-01-16 00:42:24'),
(1496, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1493', '2024-01-16 00:42:24', '2024-01-16 00:42:24'),
(1497, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56df808c3e-pprt-34165539636490237', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1493', '2024-01-16 00:42:24', '2024-01-16 00:42:24'),
(1498, 'attributes', 0, NULL, NULL, NULL, '65a56ec38485b-pprt-72436987249928461', 'DIMENSIONS', '⌀ 16 x 15 mm (0.63\" x 0.59\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:43:31', '2024-01-16 00:43:31'),
(1499, 'attributes', 0, NULL, NULL, NULL, '65a56ec38485b-pprt-72436987249928461', 'SCREW SIZE', 'M10', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:43:31', '2024-01-16 00:43:31'),
(1500, 'attributes', 0, NULL, NULL, NULL, '65a56ec38485b-pprt-72436987249928461', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:43:31', '2024-01-16 00:43:31'),
(1501, 'attributes', 0, NULL, NULL, NULL, '65a56ec38485b-pprt-72436987249928461', 'WEIGHT', '0.02 kg (0.04 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:43:31', '2024-01-16 00:43:31'),
(1502, 'attributes', 0, NULL, NULL, NULL, '65a56ec38485b-pprt-72436987249928461', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:43:31', '2024-01-16 00:43:31'),
(1503, 'custom value', 0, NULL, '1', 'Notice', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:43:58', '2024-01-16 00:43:58'),
(1504, 'custom value', 0, NULL, '1', 'Notice', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, NULL, '-', '', 'en', '1503', '2024-01-16 00:43:58', '2024-01-16 00:43:58'),
(1505, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:44:10', '2024-01-16 00:44:10'),
(1506, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, NULL, '-', '', 'en', '1505', '2024-01-16 00:44:10', '2024-01-16 00:44:10'),
(1507, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:44:28', '2024-01-16 00:44:28'),
(1508, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1507', '2024-01-16 00:44:28', '2024-01-16 00:44:28'),
(1509, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1507', '2024-01-16 00:44:28', '2024-01-16 00:44:28'),
(1510, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1507', '2024-01-16 00:44:28', '2024-01-16 00:44:28'),
(1511, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56ec38485b-pprt-72436987249928461', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1507', '2024-01-16 00:44:28', '2024-01-16 00:44:28'),
(1512, 'attributes', 0, NULL, NULL, NULL, '65a56fff9bd20-pprt-26183622565736607', 'FOR GRID SIZE', '52', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:48:47', '2024-01-16 00:48:47'),
(1513, 'attributes', 0, NULL, NULL, NULL, '65a56fff9bd20-pprt-26183622565736607', 'DIAMETER', '16 mm (0.63\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:48:47', '2024-01-16 00:48:47'),
(1514, 'attributes', 0, NULL, NULL, NULL, '65a56fff9bd20-pprt-26183622565736607', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:48:47', '2024-01-16 00:48:47'),
(1515, 'attributes', 0, NULL, NULL, NULL, '65a56fff9bd20-pprt-26183622565736607', 'WEIGHT', '0.26 kg (0.57 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:48:47', '2024-01-16 00:48:47'),
(1516, 'attributes', 0, NULL, NULL, NULL, '65a56fff9bd20-pprt-26183622565736607', 'MATERIAL', 'Aluminum', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:48:47', '2024-01-16 00:48:47'),
(1517, 'custom value', 0, NULL, '1', 'Notice', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:49:07', '2024-01-16 00:49:07'),
(1518, 'custom value', 0, NULL, '1', 'Notice', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, NULL, '-', '', 'en', '1517', '2024-01-16 00:49:07', '2024-01-16 00:49:07'),
(1519, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:49:16', '2024-01-16 00:49:16'),
(1520, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, NULL, '-', '', 'en', '1519', '2024-01-16 00:49:16', '2024-01-16 00:49:16'),
(1521, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:49:47', '2024-01-16 00:49:47'),
(1522, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1521', '2024-01-16 00:49:47', '2024-01-16 00:49:47'),
(1523, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1521', '2024-01-16 00:49:47', '2024-01-16 00:49:47'),
(1524, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1521', '2024-01-16 00:49:47', '2024-01-16 00:49:47'),
(1525, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a56fff9bd20-pprt-26183622565736607', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1521', '2024-01-16 00:49:47', '2024-01-16 00:49:47'),
(1526, 'attributes', 0, NULL, NULL, NULL, '65a570a1c96a6-pprt-84960007491807622', 'FOR GRID SIZE', '96', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:51:29', '2024-01-16 00:51:29'),
(1527, 'attributes', 0, NULL, NULL, NULL, '65a570a1c96a6-pprt-84960007491807622', 'DIAMETER', '20 mm (0.79\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:51:29', '2024-01-16 00:51:29'),
(1528, 'attributes', 0, NULL, NULL, NULL, '65a570a1c96a6-pprt-84960007491807622', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:51:29', '2024-01-16 00:51:29'),
(1529, 'attributes', 0, NULL, NULL, NULL, '65a570a1c96a6-pprt-84960007491807622', 'WEIGHT', '0.3 kg (0.66 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:51:29', '2024-01-16 00:51:29'),
(1530, 'attributes', 0, NULL, NULL, NULL, '65a570a1c96a6-pprt-84960007491807622', 'MATERIAL', 'Aluminum', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:51:29', '2024-01-16 00:51:29'),
(1531, 'custom value', 0, NULL, '1', 'Notice', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:52:17', '2024-01-16 00:52:17'),
(1532, 'custom value', 0, NULL, '1', 'Notice', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, '-', '', 'en', '1531', '2024-01-16 00:52:17', '2024-01-16 00:52:17'),
(1533, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:52:33', '2024-01-16 00:52:33'),
(1534, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, '-', '', 'en', '1533', '2024-01-16 00:52:33', '2024-01-16 00:52:33'),
(1535, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:53:00', '2024-01-16 00:53:00'),
(1536, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1535', '2024-01-16 00:53:00', '2024-01-16 00:53:00'),
(1537, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1535', '2024-01-16 00:53:00', '2024-01-16 00:53:00'),
(1538, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1535', '2024-01-16 00:53:00', '2024-01-16 00:53:00'),
(1539, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1535', '2024-01-16 00:53:00', '2024-01-16 00:53:00'),
(1540, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:53:54', '2024-01-16 00:53:54'),
(1541, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a570a1c96a6-pprt-84960007491807622', NULL, NULL, NULL, NULL, '170534123465a57132985d4_46081_cad (1).zip', 'en', '1540', '2024-01-16 00:53:54', '2024-01-16 00:53:54'),
(1542, 'attributes', 0, NULL, NULL, NULL, '65a5724372af5-pprt-21260557122863031', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:58:27', '2024-01-16 00:58:27'),
(1543, 'attributes', 0, NULL, NULL, NULL, '65a5724372af5-pprt-21260557122863031', 'WEIGHT', '0.22 kg (0.49 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:58:27', '2024-01-16 00:58:27'),
(1544, 'attributes', 0, NULL, NULL, NULL, '65a5724372af5-pprt-21260557122863031', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 00:58:27', '2024-01-16 00:58:27'),
(1545, 'custom value', 0, NULL, '1', 'Notice', '65a5724372af5-pprt-21260557122863031', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:58:53', '2024-01-16 00:58:53'),
(1546, 'custom value', 0, NULL, '1', 'Notice', '65a5724372af5-pprt-21260557122863031', NULL, NULL, NULL, 'Included in delivery of Quick•Point® Multi-Plates and Makro•Grip® Ultra Base-sets.', '', 'en', '1545', '2024-01-16 00:58:53', '2024-01-16 00:58:53'),
(1547, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5724372af5-pprt-21260557122863031', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:59:02', '2024-01-16 00:59:02'),
(1548, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5724372af5-pprt-21260557122863031', NULL, NULL, NULL, '-', '', 'en', '1547', '2024-01-16 00:59:02', '2024-01-16 00:59:02'),
(1549, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5724372af5-pprt-21260557122863031', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 00:59:23', '2024-01-16 00:59:23'),
(1550, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5724372af5-pprt-21260557122863031', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1549', '2024-01-16 00:59:23', '2024-01-16 00:59:23'),
(1551, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5724372af5-pprt-21260557122863031', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1549', '2024-01-16 00:59:23', '2024-01-16 00:59:23'),
(1552, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5724372af5-pprt-21260557122863031', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1549', '2024-01-16 00:59:23', '2024-01-16 00:59:23'),
(1553, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5724372af5-pprt-21260557122863031', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1549', '2024-01-16 00:59:23', '2024-01-16 00:59:23'),
(1554, 'attributes', 0, NULL, NULL, NULL, '65a572dccb4ca-pprt-53774213828007828', 'DIAMETER', '16 mm (0.63\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:01:00', '2024-01-16 01:01:00'),
(1555, 'attributes', 0, NULL, NULL, NULL, '65a572dccb4ca-pprt-53774213828007828', 'PACKAGING UNIT', '4 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:01:00', '2024-01-16 01:01:00'),
(1556, 'attributes', 0, NULL, NULL, NULL, '65a572dccb4ca-pprt-53774213828007828', 'WEIGHT', '0.1 kg (0.22 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:01:00', '2024-01-16 01:01:00'),
(1557, 'attributes', 0, NULL, NULL, NULL, '65a572dccb4ca-pprt-53774213828007828', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:01:00', '2024-01-16 01:01:00'),
(1558, 'attributes', 0, NULL, NULL, NULL, '65a572dccb4ca-pprt-53774213828007828', 'FOR GRID SIZE', '52', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:01:00', '2024-01-16 01:01:00'),
(1559, 'custom value', 0, NULL, '1', 'Notice', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:01:21', '2024-01-16 01:01:21'),
(1560, 'custom value', 0, NULL, '1', 'Notice', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, NULL, '-', '', 'en', '1559', '2024-01-16 01:01:21', '2024-01-16 01:01:21'),
(1561, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:01:30', '2024-01-16 01:01:30'),
(1562, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, NULL, '-', '', 'en', '1561', '2024-01-16 01:01:30', '2024-01-16 01:01:30'),
(1563, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:01:45', '2024-01-16 01:01:45'),
(1564, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1563', '2024-01-16 01:01:45', '2024-01-16 01:01:45'),
(1565, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1563', '2024-01-16 01:01:45', '2024-01-16 01:01:45'),
(1566, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1563', '2024-01-16 01:01:45', '2024-01-16 01:01:45'),
(1567, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a572dccb4ca-pprt-53774213828007828', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1563', '2024-01-16 01:01:45', '2024-01-16 01:01:45'),
(1568, 'attributes', 0, NULL, NULL, NULL, '65a57358e168d-pprt-98545569411347061', 'DIAMETER', '20 mm (0.79\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:03:04', '2024-01-16 01:03:04'),
(1569, 'attributes', 0, NULL, NULL, NULL, '65a57358e168d-pprt-98545569411347061', 'PACKAGING UNIT', '4 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:03:04', '2024-01-16 01:03:04'),
(1570, 'attributes', 0, NULL, NULL, NULL, '65a57358e168d-pprt-98545569411347061', 'WEIGHT', '0.17 kg (0.37 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:03:04', '2024-01-16 01:03:04'),
(1571, 'attributes', 0, NULL, NULL, NULL, '65a57358e168d-pprt-98545569411347061', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:03:04', '2024-01-16 01:03:04'),
(1572, 'attributes', 0, NULL, NULL, NULL, '65a57358e168d-pprt-98545569411347061', 'FOR GRID SIZE', '96', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:03:04', '2024-01-16 01:03:04'),
(1573, 'custom value', 0, NULL, '1', 'Notice', '65a57358e168d-pprt-98545569411347061', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:04:14', '2024-01-16 01:04:14'),
(1574, 'custom value', 0, NULL, '1', 'Notice', '65a57358e168d-pprt-98545569411347061', NULL, NULL, NULL, '-', '', 'en', '1573', '2024-01-16 01:04:14', '2024-01-16 01:04:14'),
(1575, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57358e168d-pprt-98545569411347061', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:04:24', '2024-01-16 01:04:24'),
(1576, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57358e168d-pprt-98545569411347061', NULL, NULL, NULL, '-', '', 'en', '1575', '2024-01-16 01:04:24', '2024-01-16 01:04:24'),
(1577, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57358e168d-pprt-98545569411347061', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:04:44', '2024-01-16 01:04:44'),
(1578, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57358e168d-pprt-98545569411347061', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1577', '2024-01-16 01:04:44', '2024-01-16 01:04:44'),
(1579, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57358e168d-pprt-98545569411347061', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1577', '2024-01-16 01:04:44', '2024-01-16 01:04:44'),
(1580, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57358e168d-pprt-98545569411347061', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1577', '2024-01-16 01:04:44', '2024-01-16 01:04:44'),
(1581, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57358e168d-pprt-98545569411347061', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1577', '2024-01-16 01:04:44', '2024-01-16 01:04:44'),
(1582, 'attributes', 0, NULL, NULL, NULL, '65a57430aac64-pprt-72633930351185261', 'DIAMETER', '20 mm (0.79\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:06:40', '2024-01-16 01:06:40'),
(1583, 'attributes', 0, NULL, NULL, NULL, '65a57430aac64-pprt-72633930351185261', 'PACKAGING UNIT', '4 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:06:40', '2024-01-16 01:06:40'),
(1584, 'attributes', 0, NULL, NULL, NULL, '65a57430aac64-pprt-72633930351185261', 'WEIGHT', '0.04 kg (0.09 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:06:40', '2024-01-16 01:06:40'),
(1585, 'attributes', 0, NULL, NULL, NULL, '65a57430aac64-pprt-72633930351185261', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:06:40', '2024-01-16 01:06:40'),
(1586, 'attributes', 0, NULL, NULL, NULL, '65a57430aac64-pprt-72633930351185261', 'FOR GRID SIZE', '96', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:06:40', '2024-01-16 01:06:40'),
(1587, 'custom value', 0, NULL, '1', 'Notice', '65a57430aac64-pprt-72633930351185261', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:06:56', '2024-01-16 01:06:56'),
(1588, 'custom value', 0, NULL, '1', 'Notice', '65a57430aac64-pprt-72633930351185261', NULL, NULL, NULL, '-', '', 'en', '1587', '2024-01-16 01:06:56', '2024-01-16 01:06:56'),
(1589, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57430aac64-pprt-72633930351185261', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:07:07', '2024-01-16 01:07:07'),
(1590, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57430aac64-pprt-72633930351185261', NULL, NULL, NULL, '-', '', 'en', '1589', '2024-01-16 01:07:07', '2024-01-16 01:07:07'),
(1591, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57430aac64-pprt-72633930351185261', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:07:23', '2024-01-16 01:07:23'),
(1592, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57430aac64-pprt-72633930351185261', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1591', '2024-01-16 01:07:23', '2024-01-16 01:07:23'),
(1593, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57430aac64-pprt-72633930351185261', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1591', '2024-01-16 01:07:23', '2024-01-16 01:07:23'),
(1594, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57430aac64-pprt-72633930351185261', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1591', '2024-01-16 01:07:23', '2024-01-16 01:07:23'),
(1595, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57430aac64-pprt-72633930351185261', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1591', '2024-01-16 01:07:23', '2024-01-16 01:07:23'),
(1596, 'attributes', 0, NULL, NULL, NULL, '65a574bf38a88-pprt-60015222335837395', 'DIAMETER', '16 mm (0.63\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:09:03', '2024-01-16 01:09:03'),
(1597, 'attributes', 0, NULL, NULL, NULL, '65a574bf38a88-pprt-60015222335837395', 'PACKAGING UNIT', '4 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:09:03', '2024-01-16 01:09:03'),
(1598, 'attributes', 0, NULL, NULL, NULL, '65a574bf38a88-pprt-60015222335837395', 'WEIGHT', '0.02 kg (0.04 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:09:03', '2024-01-16 01:09:03'),
(1599, 'attributes', 0, NULL, NULL, NULL, '65a574bf38a88-pprt-60015222335837395', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:09:03', '2024-01-16 01:09:03'),
(1600, 'attributes', 0, NULL, NULL, NULL, '65a574bf38a88-pprt-60015222335837395', 'FOR GRID SIZE', '52', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:09:03', '2024-01-16 01:09:03'),
(1601, 'custom value', 0, NULL, '1', 'Notice', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:09:21', '2024-01-16 01:09:21'),
(1602, 'custom value', 0, NULL, '1', 'Notice', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, NULL, '-', '', 'en', '1601', '2024-01-16 01:09:21', '2024-01-16 01:09:21'),
(1603, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:09:32', '2024-01-16 01:09:32'),
(1604, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, NULL, '-', '', 'en', '1603', '2024-01-16 01:09:32', '2024-01-16 01:09:32'),
(1605, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:09:56', '2024-01-16 01:09:56'),
(1606, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1605', '2024-01-16 01:09:56', '2024-01-16 01:09:56'),
(1607, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1605', '2024-01-16 01:09:56', '2024-01-16 01:09:56'),
(1608, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1605', '2024-01-16 01:09:56', '2024-01-16 01:09:56'),
(1609, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a574bf38a88-pprt-60015222335837395', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1605', '2024-01-16 01:09:56', '2024-01-16 01:09:56'),
(1610, 'attributes', 0, NULL, NULL, NULL, '65a575584c111-pprt-22378944105790441', 'DIMENSIONS', '⌀ 27 x 2 mm (1.06\" x 0.08\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:11:36', '2024-01-16 01:11:36'),
(1611, 'attributes', 0, NULL, NULL, NULL, '65a575584c111-pprt-22378944105790441', 'PACKAGING UNIT', '20 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:11:36', '2024-01-16 01:11:36'),
(1612, 'attributes', 0, NULL, NULL, NULL, '65a575584c111-pprt-22378944105790441', 'WEIGHT', '0.04 kg (0.09 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:11:36', '2024-01-16 01:11:36'),
(1613, 'attributes', 0, NULL, NULL, NULL, '65a575584c111-pprt-22378944105790441', 'MATERIAL', 'StPlasticeel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:11:36', '2024-01-16 01:11:36'),
(1614, 'custom value', 0, NULL, '1', 'Notice', '65a575584c111-pprt-22378944105790441', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:12:07', '2024-01-16 01:12:07'),
(1615, 'custom value', 0, NULL, '1', 'Notice', '65a575584c111-pprt-22378944105790441', NULL, NULL, NULL, '-', '', 'en', '1614', '2024-01-16 01:12:07', '2024-01-16 01:12:07'),
(1616, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a575584c111-pprt-22378944105790441', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:12:18', '2024-01-16 01:12:18'),
(1617, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a575584c111-pprt-22378944105790441', NULL, NULL, NULL, '-', '', 'en', '1616', '2024-01-16 01:12:18', '2024-01-16 01:12:18'),
(1618, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575584c111-pprt-22378944105790441', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:12:29', '2024-01-16 01:12:29'),
(1619, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575584c111-pprt-22378944105790441', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1618', '2024-01-16 01:12:29', '2024-01-16 01:12:29'),
(1620, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575584c111-pprt-22378944105790441', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1618', '2024-01-16 01:12:29', '2024-01-16 01:12:29'),
(1621, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575584c111-pprt-22378944105790441', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1618', '2024-01-16 01:12:29', '2024-01-16 01:12:29'),
(1622, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575584c111-pprt-22378944105790441', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1618', '2024-01-16 01:12:29', '2024-01-16 01:12:29'),
(1623, 'attributes', 0, NULL, NULL, NULL, '65a575eb8b5af-pprt-25978100711417639', 'DIMENSIONS', '⌀ 15 x 2 mm (0.59\" x 0.08\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:14:03', '2024-01-16 01:14:03'),
(1624, 'attributes', 0, NULL, NULL, NULL, '65a575eb8b5af-pprt-25978100711417639', 'PACKAGING UNIT', '20 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:14:03', '2024-01-16 01:14:03'),
(1625, 'attributes', 0, NULL, NULL, NULL, '65a575eb8b5af-pprt-25978100711417639', 'WEIGHT', '0.01 kg (0.02 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:14:03', '2024-01-16 01:14:03'),
(1626, 'attributes', 0, NULL, NULL, NULL, '65a575eb8b5af-pprt-25978100711417639', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:14:03', '2024-01-16 01:14:03'),
(1627, 'custom value', 0, NULL, '1', 'Notice', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:14:23', '2024-01-16 01:14:23'),
(1628, 'custom value', 0, NULL, '1', 'Notice', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, NULL, '-', '', 'en', '1627', '2024-01-16 01:14:23', '2024-01-16 01:14:23'),
(1629, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:14:34', '2024-01-16 01:14:34'),
(1630, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, NULL, '-', '', 'en', '1629', '2024-01-16 01:14:34', '2024-01-16 01:14:34'),
(1631, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:14:53', '2024-01-16 01:14:53'),
(1632, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1631', '2024-01-16 01:14:53', '2024-01-16 01:14:53'),
(1633, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1631', '2024-01-16 01:14:53', '2024-01-16 01:14:53'),
(1634, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1631', '2024-01-16 01:14:53', '2024-01-16 01:14:53'),
(1635, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a575eb8b5af-pprt-25978100711417639', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1631', '2024-01-16 01:14:53', '2024-01-16 01:14:53'),
(1636, 'attributes', 0, NULL, NULL, NULL, '65a57658e15aa-pprt-34715048107359993', 'DIMENSIONS', '⌀ 20 x 2 mm (0.79\" x 0.08\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:15:52', '2024-01-16 01:15:52'),
(1637, 'attributes', 0, NULL, NULL, NULL, '65a57658e15aa-pprt-34715048107359993', 'PACKAGING UNIT', '20 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:15:52', '2024-01-16 01:15:52'),
(1638, 'attributes', 0, NULL, NULL, NULL, '65a57658e15aa-pprt-34715048107359993', 'WEIGHT', '0.02 kg (0.04 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:15:52', '2024-01-16 01:15:52'),
(1639, 'attributes', 0, NULL, NULL, NULL, '65a57658e15aa-pprt-34715048107359993', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:15:52', '2024-01-16 01:15:52'),
(1640, 'custom value', 0, NULL, '1', 'Notice', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:16:13', '2024-01-16 01:16:13'),
(1641, 'custom value', 0, NULL, '1', 'Notice', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, NULL, '-', '', 'en', '1640', '2024-01-16 01:16:13', '2024-01-16 01:16:13'),
(1642, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:16:22', '2024-01-16 01:16:22'),
(1643, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, NULL, '-', '', 'en', '1642', '2024-01-16 01:16:22', '2024-01-16 01:16:22'),
(1644, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:16:35', '2024-01-16 01:16:35'),
(1645, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1644', '2024-01-16 01:16:35', '2024-01-16 01:16:35'),
(1646, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1644', '2024-01-16 01:16:35', '2024-01-16 01:16:35'),
(1647, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1644', '2024-01-16 01:16:35', '2024-01-16 01:16:35'),
(1648, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a57658e15aa-pprt-34715048107359993', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1644', '2024-01-16 01:16:35', '2024-01-16 01:16:35'),
(1649, 'attributes', 0, NULL, NULL, NULL, '65a576f55207c-pprt-79000651130970494', 'WEIGHT', '1.5 kg (3.31 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:18:29', '2024-01-16 01:18:29'),
(1650, 'attributes', 0, NULL, NULL, NULL, '65a576f55207c-pprt-79000651130970494', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:18:29', '2024-01-16 01:18:29'),
(1651, 'custom value', 0, NULL, '1', 'Notice', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:18:46', '2024-01-16 01:18:46'),
(1652, 'custom value', 0, NULL, '1', 'Notice', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, '-', '', 'en', '1651', '2024-01-16 01:18:46', '2024-01-16 01:18:46'),
(1653, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:19:12', '2024-01-16 01:19:12'),
(1654, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, '1 x Quick-Lock fastener, 1 x clamping lever, 4 x washer', '', 'en', '1653', '2024-01-16 01:19:12', '2024-01-16 01:19:12'),
(1655, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:19:55', '2024-01-16 01:19:55'),
(1656, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a576f55207c-pprt-79000651130970494', NULL, NULL, 'Ease of operation', 'Facilitates the clamping process with rectangular zero-point plates', '', 'en', '1655', '2024-01-16 01:19:55', '2024-01-16 01:19:55'),
(1657, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a576f55207c-pprt-79000651130970494', NULL, NULL, 'Quickness', 'Simple 180° motion of the Quick•Lock lever', '', 'en', '1655', '2024-01-16 01:19:55', '2024-01-16 01:19:55'),
(1658, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a576f55207c-pprt-79000651130970494', NULL, NULL, 'Flexibility', 'Can be attached to or removed from a zero-point plate at any time', '', 'en', '1655', '2024-01-16 01:19:55', '2024-01-16 01:19:55'),
(1659, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:20:11', '2024-01-16 01:20:11'),
(1660, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1659', '2024-01-16 01:20:11', '2024-01-16 01:20:11'),
(1661, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:20:27', '2024-01-16 01:20:27'),
(1662, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, '170534282765a5776bd7954_45452_cad.zip', 'en', '1661', '2024-01-16 01:20:27', '2024-01-16 01:20:27'),
(1663, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:20:44', '2024-01-16 01:20:44'),
(1664, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a576f55207c-pprt-79000651130970494', NULL, NULL, NULL, NULL, '170534284465a5777cacdae_45452_1.pdf', 'en', '1663', '2024-01-16 01:20:44', '2024-01-16 01:20:44'),
(1665, 'attributes', 0, NULL, NULL, NULL, '65a577bbcae15-pprt-60314697319399575', 'WEIGHT', '1.6 kg (3.53 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:21:47', '2024-01-16 01:21:47'),
(1666, 'attributes', 0, NULL, NULL, NULL, '65a577bbcae15-pprt-60314697319399575', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:21:47', '2024-01-16 01:21:47'),
(1667, 'custom value', 0, NULL, '1', 'Notice', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:22:09', '2024-01-16 01:22:09'),
(1668, 'custom value', 0, NULL, '1', 'Notice', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, '-', '', 'en', '1667', '2024-01-16 01:22:09', '2024-01-16 01:22:09'),
(1669, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:22:26', '2024-01-16 01:22:26'),
(1670, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, '1 x Quick-Lock fastener, 1 x clamping lever, 4 x washer', '', 'en', '1669', '2024-01-16 01:22:26', '2024-01-16 01:22:26'),
(1671, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:22:52', '2024-01-16 01:22:52'),
(1672, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, 'Ease of operation', 'Facilitates the clamping process with rectangular zero-point plates', '', 'en', '1671', '2024-01-16 01:22:52', '2024-01-16 01:22:52'),
(1673, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, 'Quickness', 'Simple 180° motion of the Quick•Lock lever', '', 'en', '1671', '2024-01-16 01:22:52', '2024-01-16 01:22:52'),
(1674, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, 'Flexibility', 'Can be attached to or removed from a zero-point plate at any time', '', 'en', '1671', '2024-01-16 01:22:52', '2024-01-16 01:22:52'),
(1675, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:23:13', '2024-01-16 01:23:13'),
(1676, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1675', '2024-01-16 01:23:13', '2024-01-16 01:23:13'),
(1677, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1675', '2024-01-16 01:23:13', '2024-01-16 01:23:13'),
(1678, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1675', '2024-01-16 01:23:13', '2024-01-16 01:23:13'),
(1679, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1675', '2024-01-16 01:23:13', '2024-01-16 01:23:13'),
(1680, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:23:33', '2024-01-16 01:23:33'),
(1681, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, '170534301365a57825e6632_45996_cad.zip', 'en', '1680', '2024-01-16 01:23:33', '2024-01-16 01:23:33'),
(1682, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:23:47', '2024-01-16 01:23:47'),
(1683, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a577bbcae15-pprt-60314697319399575', NULL, NULL, NULL, NULL, '170534302765a57833172bf_45996.pdf', 'en', '1682', '2024-01-16 01:23:47', '2024-01-16 01:23:47'),
(1684, 'attributes', 0, NULL, NULL, NULL, '65a5787cecd7a-pprt-87465801426867058', 'WEIGHT', '0.212 kg (0.47 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:25:00', '2024-01-16 01:25:00'),
(1685, 'attributes', 0, NULL, NULL, NULL, '65a5787cecd7a-pprt-87465801426867058', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:25:00', '2024-01-16 01:25:00'),
(1686, 'custom value', 0, NULL, '1', 'Notice', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:25:17', '2024-01-16 01:25:17'),
(1687, 'custom value', 0, NULL, '1', 'Notice', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, '-', '', 'en', '1686', '2024-01-16 01:25:17', '2024-01-16 01:25:17'),
(1688, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:25:27', '2024-01-16 01:25:27'),
(1689, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, '-', '', 'en', '1688', '2024-01-16 01:25:27', '2024-01-16 01:25:27'),
(1690, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:25:51', '2024-01-16 01:25:51'),
(1691, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1690', '2024-01-16 01:25:51', '2024-01-16 01:25:51'),
(1692, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1690', '2024-01-16 01:25:51', '2024-01-16 01:25:51'),
(1693, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1690', '2024-01-16 01:25:51', '2024-01-16 01:25:51'),
(1694, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1690', '2024-01-16 01:25:51', '2024-01-16 01:25:51'),
(1695, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:26:02', '2024-01-16 01:26:02');
INSERT INTO `parts_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `part_id`, `attribute_name`, `value`, `title`, `details`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(1696, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a5787cecd7a-pprt-87465801426867058', NULL, NULL, NULL, NULL, '170534316265a578ba1eea4_44500_cad (1).zip', 'en', '1695', '2024-01-16 01:26:02', '2024-01-16 01:26:02'),
(1697, 'attributes', 0, NULL, NULL, NULL, '65a578fedf7d3-pprt-45823443234728794', 'WEIGHT', '1.6 kg (3.53 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:27:10', '2024-01-16 01:27:10'),
(1698, 'attributes', 0, NULL, NULL, NULL, '65a578fedf7d3-pprt-45823443234728794', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:27:10', '2024-01-16 01:27:10'),
(1699, 'custom value', 0, NULL, '1', 'Notice', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:27:35', '2024-01-16 01:27:35'),
(1700, 'custom value', 0, NULL, '1', 'Notice', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, '-', '', 'en', '1699', '2024-01-16 01:27:35', '2024-01-16 01:27:35'),
(1701, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:27:55', '2024-01-16 01:27:55'),
(1702, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, '1 x Quick-Lock fastener, 1 x clamping lever, 4 x washer', '', 'en', '1701', '2024-01-16 01:27:55', '2024-01-16 01:27:55'),
(1703, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:28:15', '2024-01-16 01:28:15'),
(1704, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, 'Ease of operation', 'Facilitates the clamping process with rectangular zero-point plates', '', 'en', '1703', '2024-01-16 01:28:15', '2024-01-16 01:28:15'),
(1705, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, 'Quickness', 'Simple 180° motion of the Quick•Lock lever', '', 'en', '1703', '2024-01-16 01:28:15', '2024-01-16 01:28:15'),
(1706, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, 'Flexibility', 'Can be attached to or removed from a zero-point plate at any time', '', 'en', '1703', '2024-01-16 01:28:15', '2024-01-16 01:28:15'),
(1707, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:28:31', '2024-01-16 01:28:31'),
(1708, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1707', '2024-01-16 01:28:31', '2024-01-16 01:28:31'),
(1709, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1707', '2024-01-16 01:28:31', '2024-01-16 01:28:31'),
(1710, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1707', '2024-01-16 01:28:31', '2024-01-16 01:28:31'),
(1711, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1707', '2024-01-16 01:28:31', '2024-01-16 01:28:31'),
(1712, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:28:51', '2024-01-16 01:28:51'),
(1713, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, '170534333165a57963db42d_45496_cad.zip', 'en', '1712', '2024-01-16 01:28:51', '2024-01-16 01:28:51'),
(1714, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:29:05', '2024-01-16 01:29:05'),
(1715, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a578fedf7d3-pprt-45823443234728794', NULL, NULL, NULL, NULL, '170534334565a57971754e7_45496.pdf', 'en', '1714', '2024-01-16 01:29:05', '2024-01-16 01:29:05'),
(1716, 'attributes', 0, NULL, NULL, NULL, '65a579b105bc5-pprt-72493227647390491', 'WEIGHT', '1.3 kg (2.87 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:30:09', '2024-01-16 01:30:09'),
(1717, 'attributes', 0, NULL, NULL, NULL, '65a579b105bc5-pprt-72493227647390491', 'MATERIAL', 'Steel', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:30:09', '2024-01-16 01:30:09'),
(1718, 'custom value', 0, NULL, '1', 'Notice', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:30:25', '2024-01-16 01:30:25'),
(1719, 'custom value', 0, NULL, '1', 'Notice', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, '-', '', 'en', '1718', '2024-01-16 01:30:25', '2024-01-16 01:30:25'),
(1720, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:30:44', '2024-01-16 01:30:44'),
(1721, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, '1 x Quick-Lock fastener, 1 x clamping lever, 4 x washer', '', 'en', '1720', '2024-01-16 01:30:44', '2024-01-16 01:30:44'),
(1722, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:30:59', '2024-01-16 01:30:59'),
(1723, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, 'Ease of operation', 'Facilitates the clamping process with rectangular zero-point plates', '', 'en', '1722', '2024-01-16 01:30:59', '2024-01-16 01:30:59'),
(1724, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, 'Quickness', 'Simple 180° motion of the Quick•Lock lever', '', 'en', '1722', '2024-01-16 01:30:59', '2024-01-16 01:30:59'),
(1725, 'custom value', 0, NULL, '3', 'Benefits Quick•Point® Quick•Lock', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, 'Flexibility', 'Can be attached to or removed from a zero-point plate at any time', '', 'en', '1722', '2024-01-16 01:30:59', '2024-01-16 01:30:59'),
(1726, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:31:13', '2024-01-16 01:31:13'),
(1727, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, 'The Quick•Point® Zero-Point Clamping System is characterized by a particularly wide range of variations and provides a solution for any machine tool. Whether round, rectangular or square in shape, for single or multiple clamping, it can be universally used in vertical and horizontal machining centers, on 3- and 5-axis tables and 4th axis rotary or trunnion systems.', '', 'en', '1726', '2024-01-16 01:31:13', '2024-01-16 01:31:13'),
(1728, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, 'Flexibility', 'Thanks to the wide range of variations Quick•Point® can be retrofitted to any machine tool.', '', 'en', '1726', '2024-01-16 01:31:13', '2024-01-16 01:31:13'),
(1729, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, 'Easy operation', 'The simple and robust mechanical principle and the small number of components ensure maximum durability with little maintenance.', '', 'en', '1726', '2024-01-16 01:31:13', '2024-01-16 01:31:13'),
(1730, 'custom value', 0, NULL, '3', 'Quick•Point® Zero-Point Clamping System', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, 'Modularity', 'Whether changing the system size or using additional zero-point components, Quick•Point® can be supplemented and expanded as required.', '', 'en', '1726', '2024-01-16 01:31:13', '2024-01-16 01:31:13'),
(1731, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:31:29', '2024-01-16 01:31:29'),
(1732, 'custom value', 0, NULL, '5', 'CAD MODELS', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, '170534348965a57a0167927_45252_cad.zip', 'en', '1731', '2024-01-16 01:31:29', '2024-01-16 01:31:29'),
(1733, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:31:44', '2024-01-16 01:31:44'),
(1734, 'custom value', 0, NULL, '5', 'DATA SHEET', '65a579b105bc5-pprt-72493227647390491', NULL, NULL, NULL, NULL, '170534350465a57a10e0399_45252.pdf', 'en', '1733', '2024-01-16 01:31:44', '2024-01-16 01:31:44'),
(1735, 'attributes', 0, NULL, NULL, NULL, '65a57a89d1184-pprt-64554023564099125', 'WING LENGTH', '166 mm (6.54\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:33:45', '2024-01-16 01:33:45'),
(1736, 'attributes', 0, NULL, NULL, NULL, '65a57a89d1184-pprt-64554023564099125', 'RPM RANGE', '3000 - 8000 R/min', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:33:45', '2024-01-16 01:33:45'),
(1737, 'attributes', 0, NULL, NULL, NULL, '65a57a89d1184-pprt-64554023564099125', 'APPLICATION', 'large-scale', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:33:45', '2024-01-16 01:33:45'),
(1738, 'attributes', 0, NULL, NULL, NULL, '65a57a89d1184-pprt-64554023564099125', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:33:45', '2024-01-16 01:33:45'),
(1739, 'attributes', 0, NULL, NULL, NULL, '65a57a89d1184-pprt-64554023564099125', 'WEIGHT', '0.08 kg (0.18 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:33:45', '2024-01-16 01:33:45'),
(1740, 'attributes', 0, NULL, NULL, NULL, '65a57a89d1184-pprt-64554023564099125', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:33:45', '2024-01-16 01:33:45'),
(1741, 'custom value', 0, NULL, '1', 'Notice', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:34:04', '2024-01-16 01:34:04'),
(1742, 'custom value', 0, NULL, '1', 'Notice', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, NULL, '-', '', 'en', '1741', '2024-01-16 01:34:04', '2024-01-16 01:34:04'),
(1743, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:34:13', '2024-01-16 01:34:13'),
(1744, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, NULL, '-', '', 'en', '1743', '2024-01-16 01:34:13', '2024-01-16 01:34:13'),
(1745, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:34:50', '2024-01-16 01:34:50'),
(1746, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, NULL, 'The Clean•Tec, „The Original“ chip fan, cleans the machine interior after the machining process, removing chips and coolant without the operator having to open the machine tool door. As a final step in the machining process the chip fan is called up via the NC program and selected from the tool magazine.', '', 'en', '1745', '2024-01-16 01:34:50', '2024-01-16 01:34:50'),
(1747, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, 'Cleanliness', 'No swarf and coolant outside the machine tool', '', 'en', '1745', '2024-01-16 01:34:50', '2024-01-16 01:34:50'),
(1748, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, 'Energy Savings', 'Expensive compressed air not needed', '', 'en', '1745', '2024-01-16 01:34:50', '2024-01-16 01:34:50'),
(1749, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57a89d1184-pprt-64554023564099125', NULL, NULL, 'Unmanned Cleaning', 'Especially essential in automated manufacturing', '', 'en', '1745', '2024-01-16 01:34:50', '2024-01-16 01:34:50'),
(1750, 'attributes', 0, NULL, NULL, NULL, '65a57b179897a-pprt-76164327936349746', 'WING LENGTH', '82 mm (3.23\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:36:07', '2024-01-16 01:36:07'),
(1751, 'attributes', 0, NULL, NULL, NULL, '65a57b179897a-pprt-76164327936349746', 'RPM RANGE', '6000 - 12000 R/min', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:36:07', '2024-01-16 01:36:07'),
(1752, 'attributes', 0, NULL, NULL, NULL, '65a57b179897a-pprt-76164327936349746', 'APPLICATION', 'targeted', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:36:07', '2024-01-16 01:36:07'),
(1753, 'attributes', 0, NULL, NULL, NULL, '65a57b179897a-pprt-76164327936349746', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:36:07', '2024-01-16 01:36:07'),
(1754, 'attributes', 0, NULL, NULL, NULL, '65a57b179897a-pprt-76164327936349746', 'WEIGHT', '0.04 kg (0.09 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:36:07', '2024-01-16 01:36:07'),
(1755, 'attributes', 0, NULL, NULL, NULL, '65a57b179897a-pprt-76164327936349746', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:36:07', '2024-01-16 01:36:07'),
(1756, 'custom value', 0, NULL, '1', 'Notice', '65a57b179897a-pprt-76164327936349746', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:36:28', '2024-01-16 01:36:28'),
(1757, 'custom value', 0, NULL, '1', 'Notice', '65a57b179897a-pprt-76164327936349746', NULL, NULL, NULL, '-', '', 'en', '1756', '2024-01-16 01:36:28', '2024-01-16 01:36:28'),
(1758, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57b179897a-pprt-76164327936349746', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:36:39', '2024-01-16 01:36:39'),
(1759, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57b179897a-pprt-76164327936349746', NULL, NULL, NULL, '-', '', 'en', '1758', '2024-01-16 01:36:39', '2024-01-16 01:36:39'),
(1760, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b179897a-pprt-76164327936349746', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:36:58', '2024-01-16 01:36:58'),
(1761, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b179897a-pprt-76164327936349746', NULL, NULL, NULL, 'The Clean•Tec, „The Original“ chip fan, cleans the machine interior after the machining process, removing chips and coolant without the operator having to open the machine tool door. As a final step in the machining process the chip fan is called up via the NC program and selected from the tool magazine.', '', 'en', '1760', '2024-01-16 01:36:58', '2024-01-16 01:36:58'),
(1762, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b179897a-pprt-76164327936349746', NULL, NULL, 'Cleanliness', 'No swarf and coolant outside the machine tool', '', 'en', '1760', '2024-01-16 01:36:58', '2024-01-16 01:36:58'),
(1763, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b179897a-pprt-76164327936349746', NULL, NULL, 'Energy Savings', 'Expensive compressed air not needed', '', 'en', '1760', '2024-01-16 01:36:58', '2024-01-16 01:36:58'),
(1764, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b179897a-pprt-76164327936349746', NULL, NULL, 'Unmanned Cleaning', 'Especially essential in automated manufacturing', '', 'en', '1760', '2024-01-16 01:36:58', '2024-01-16 01:36:58'),
(1765, 'attributes', 0, NULL, NULL, NULL, '65a57b91d75f9-pprt-25296741568668562', 'WING LENGTH', '130 mm (5.12\")', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:38:09', '2024-01-16 01:38:09'),
(1766, 'attributes', 0, NULL, NULL, NULL, '65a57b91d75f9-pprt-25296741568668562', 'RPM RANGE', '5000 - 8000 R/min', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:38:09', '2024-01-16 01:38:09'),
(1767, 'attributes', 0, NULL, NULL, NULL, '65a57b91d75f9-pprt-25296741568668562', 'APPLICATION', 'universal', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:38:09', '2024-01-16 01:38:09'),
(1768, 'attributes', 0, NULL, NULL, NULL, '65a57b91d75f9-pprt-25296741568668562', 'PACKAGING UNIT', '1 Pack', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:38:09', '2024-01-16 01:38:09'),
(1769, 'attributes', 0, NULL, NULL, NULL, '65a57b91d75f9-pprt-25296741568668562', 'WEIGHT', '0.06 kg (0.13 lbs)', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:38:09', '2024-01-16 01:38:09'),
(1770, 'attributes', 0, NULL, NULL, NULL, '65a57b91d75f9-pprt-25296741568668562', 'MATERIAL', 'Plastic', NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:38:09', '2024-01-16 01:38:09'),
(1771, 'custom value', 0, NULL, '1', 'Notice', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:38:27', '2024-01-16 01:38:27'),
(1772, 'custom value', 0, NULL, '1', 'Notice', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, NULL, '-', '', 'en', '1771', '2024-01-16 01:38:27', '2024-01-16 01:38:27'),
(1773, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:38:37', '2024-01-16 01:38:37'),
(1774, 'custom value', 0, NULL, '1', 'Scope of delivery', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, NULL, '-', '', 'en', '1773', '2024-01-16 01:38:37', '2024-01-16 01:38:37'),
(1775, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-01-16 01:38:52', '2024-01-16 01:38:52'),
(1776, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, NULL, 'The Clean•Tec, „The Original“ chip fan, cleans the machine interior after the machining process, removing chips and coolant without the operator having to open the machine tool door. As a final step in the machining process the chip fan is called up via the NC program and selected from the tool magazine.', '', 'en', '1775', '2024-01-16 01:38:52', '2024-01-16 01:38:52'),
(1777, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, 'Cleanliness', 'No swarf and coolant outside the machine tool', '', 'en', '1775', '2024-01-16 01:38:52', '2024-01-16 01:38:52'),
(1778, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, 'Energy Savings', 'Expensive compressed air not needed', '', 'en', '1775', '2024-01-16 01:38:52', '2024-01-16 01:38:52'),
(1779, 'custom value', 0, NULL, '3', 'Clean•Tec Chip Fan', '65a57b91d75f9-pprt-25296741568668562', NULL, NULL, 'Unmanned Cleaning', 'Especially essential in automated manufacturing', '', 'en', '1775', '2024-01-16 01:38:52', '2024-01-16 01:38:52'),
(1784, 'attributes', 0, NULL, NULL, NULL, '65a282506e421-pprt-35247242445504526', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-02-01 02:58:06', '2024-02-01 02:58:06'),
(1785, 'attributes', 1, NULL, NULL, NULL, '65c123982dd0d-pprt-38617413078081700', 'test', '1', NULL, NULL, NULL, NULL, NULL, '2024-02-06 01:06:16', '2024-02-06 01:06:16'),
(1789, 'attributes', 0, NULL, NULL, NULL, '65a227b636bfe-pprt-89497772175826729', 'PACKAGING UNIT', '1 Set', NULL, NULL, NULL, NULL, NULL, '2024-03-01 04:24:14', '2024-03-01 04:24:14');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` varchar(40) NOT NULL,
  `name` varchar(256) NOT NULL,
  `code` varchar(256) DEFAULT NULL,
  `referance_type` varchar(256) DEFAULT NULL,
  `referance_code` varchar(256) DEFAULT NULL,
  `slug` text DEFAULT NULL,
  `brand_id` varchar(40) DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL,
  `sub_category_id` varchar(40) DEFAULT NULL,
  `price` decimal(16,2) DEFAULT 0.00,
  `duration` float(16,2) DEFAULT NULL,
  `key_features` text DEFAULT NULL,
  `further_information` text DEFAULT NULL,
  `discount_type` varchar(256) DEFAULT NULL,
  `discount` float(16,2) DEFAULT 0.00,
  `thumbnail` varchar(256) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `features` int(11) NOT NULL DEFAULT 0,
  `show_price` int(11) NOT NULL DEFAULT 0,
  `is_free` int(11) DEFAULT 0,
  `show_price_to_partner` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `short_number` int(11) NOT NULL DEFAULT 0,
  `meta_title` text DEFAULT NULL,
  `meta_tags` text DEFAULT NULL,
  `meta_descriptions` text DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `code`, `referance_type`, `referance_code`, `slug`, `brand_id`, `category_id`, `sub_category_id`, `price`, `duration`, `key_features`, `further_information`, `discount_type`, `discount`, `thumbnail`, `images`, `status`, `features`, `show_price`, `is_free`, `show_price_to_partner`, `ancestor_id`, `short_number`, `meta_title`, `meta_tags`, `meta_descriptions`, `company_id`, `created_at`, `updated_at`) VALUES
('671b6fd39cd8c-prod-88757483059884922', 'Cyber Security', '53402-HE', 'Event', 'event007', 'cyber-security', NULL, '671b37e61e7c8-ctgr-89990712782176204', NULL, 500.00, 10.00, 'Cybersecurity affects everyone, including in the delivery of basic products and services. If you or your organization want to better understand how to address your cybersecurity, this is the course for you and your colleagues to take -- from seasoned professionals to your non-technical colleagues.', '<p><span style=\"color: rgb(55, 58, 60); font-family: OpenSans, Arial, sans-serif; font-size: 14px; white-space-collapse: preserve;\">Cybersecurity affects everyone, including in the delivery of basic products and services. If you or your organization want to better understand how to address your cybersecurity, this is the course for you and your colleagues to take -- from seasoned professionals to your non-technical colleagues.</span></p>', NULL, NULL, '1729851347671b6fd39b6bdistockphoto-1339203360-612x612.jpg', '[\"1729851347671b6fd39c739istockphoto-1339203360-612x612.jpg\"]', 1, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, '671b35c259c6e-comp-37685793406052083', '2024-10-25 04:15:47', '2024-10-25 04:30:26');

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute`
--

CREATE TABLE `product_attribute` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL DEFAULT 'custom value',
  `is_filter` int(11) NOT NULL DEFAULT 0,
  `attribute_id` varchar(40) DEFAULT NULL,
  `custom_field_id` varchar(40) DEFAULT NULL,
  `sub_option` varchar(265) DEFAULT NULL,
  `module_id` varchar(40) DEFAULT NULL,
  `product_id` varchar(40) NOT NULL,
  `attribute_name` varchar(256) DEFAULT NULL,
  `value` varchar(256) DEFAULT NULL,
  `title` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `price` float(16,2) DEFAULT NULL,
  `is_free` int(11) DEFAULT 0,
  `discount_type` varchar(256) DEFAULT NULL,
  `discount_amount` float(16,2) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `intro_video` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `language_code` varchar(256) DEFAULT NULL,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_attribute`
--

INSERT INTO `product_attribute` (`id`, `type`, `is_filter`, `attribute_id`, `custom_field_id`, `sub_option`, `module_id`, `product_id`, `attribute_name`, `value`, `title`, `details`, `price`, `is_free`, `discount_type`, `discount_amount`, `short_description`, `intro_video`, `image`, `language_code`, `ancestor_id`, `created_at`, `updated_at`) VALUES
(1, 'custom value', 0, NULL, '8', 'Course Benefits', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-10-25 04:57:30', '2024-10-25 04:57:30'),
(2, 'custom value', 0, NULL, '8', 'Course Benefits', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, 'Free Certificate', 'Free Certificate', 'Free Certificate', NULL, 0, NULL, NULL, NULL, NULL, '', 'en', '1', '2024-10-25 04:57:30', '2024-10-25 04:57:30'),
(3, 'custom value', 0, NULL, '6', 'Foundations of Cybersecurity', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-10-25 06:57:51', '2024-10-25 06:57:51'),
(6, 'custom value', 0, NULL, '6', 'Foundations of Cybersecurity', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, 'Module 1', '<p>Sint sint ipsam ve</p>', 161.00, NULL, 'amount', 20.00, 'Sint sint ipsam ve', '', '', 'en', '3', '2024-10-25 07:14:50', '2024-10-25 07:14:50'),
(7, 'custom value', 0, NULL, '6', 'Foundations of Cybersecurity', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, 'Module 2', '<p>Sint sint ipsam ve</p>', 10.00, NULL, NULL, 20.00, 'sadasdasda', '', '', 'en', '3', '2024-10-25 07:14:50', '2024-10-25 07:14:50'),
(13, 'custom value', 0, NULL, '7', 'Topics 1', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-10-25 08:41:20', '2024-10-25 08:41:20'),
(14, 'custom value', 0, NULL, '7', 'Topics 1', '6', '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, 'Topic 1 title', '<p>asdsadasdasdasdasda</p>', 234234.00, 0, 'percent', 10.00, 'asdsadasdasdasdasda', '', '', 'en', '13', '2024-10-25 08:41:20', '2024-10-25 08:41:20'),
(16, 'custom value', 0, NULL, '9', 'Instructor', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 'en', NULL, '2024-10-26 23:10:16', '2024-10-26 23:10:16'),
(17, 'custom value', 0, NULL, '9', 'Instructor', NULL, '671b6fd39cd8c-prod-88757483059884922', NULL, '671dc395dbcc1-user-29761870869707124', 'Instructor', '<p><br></p>', NULL, 0, NULL, NULL, NULL, '', '', 'en', '16', '2024-10-26 23:10:16', '2024-10-26 23:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `product_part`
--

CREATE TABLE `product_part` (
  `id` varchar(40) NOT NULL,
  `name` varchar(256) NOT NULL,
  `slug` text DEFAULT NULL,
  `code` varchar(256) DEFAULT NULL,
  `parts_type` varchar(40) DEFAULT 'parts',
  `brand_id` varchar(40) DEFAULT NULL,
  `category_id` varchar(50) DEFAULT NULL,
  `product_id` varchar(40) DEFAULT NULL,
  `price` decimal(16,2) NOT NULL,
  `key_features` text DEFAULT NULL,
  `further_information` text DEFAULT NULL,
  `discount_type` varchar(256) DEFAULT NULL,
  `discount` float(16,2) DEFAULT 0.00,
  `thumbnail` varchar(256) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `short_number` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_part`
--

INSERT INTO `product_part` (`id`, `name`, `slug`, `code`, `parts_type`, `brand_id`, `category_id`, `product_id`, `price`, `key_features`, `further_information`, `discount_type`, `discount`, `thumbnail`, `images`, `status`, `ancestor_id`, `short_number`, `created_at`, `updated_at`) VALUES
('65a227b636bfe-pprt-89497772175826729', 'Makro•Grip®, Assembly Tool for magnets', '48420-lang-makrogrip-assembly-tool-for-magnets', '48420', 'parts', '1', '65115eaa217bc-ctgr-80906052148787198', NULL, 1.00, '<p></p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: \" encode=\"\" sans=\"\" compressed\",=\"\" sans-serif;=\"\" font-size:=\"\" 20px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" 0.8px;=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" left;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(247,=\"\" 247,=\"\" 248);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div><p></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The assembly tool is required for mounting and removing the magnets in the first generation of Makro•Grip® contour jaws. In the meantime, contour jaws are already supplied with mounted magnets.</p>', '<p><br></p>', NULL, NULL, '170512581465a227b6369f548420[1]_1.webp', NULL, 1, NULL, 0, '2024-01-13 13:03:34', '2024-03-01 04:20:34'),
('65a22c7d1093e-pprt-20356038742250174', 'Avanti 125, Center Base Jaw + Spindle', '44355-tg125-lang-avanti-125-center-base-jaw-spindle', '44355-TG125', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If required, the Avanti vise can be converted into a multiple clamp. During the conversion, which can be carried out in a few moments, only the set of spindle + center piece from the contour clamp has to be replaced by the center base jaw + spindle. For (multiple) workpiece clamping, the same top jaw as for the base jaws is mounted on the center base jaw.</p>', '<p><br></p>', NULL, NULL, '170512703765a22c7d103bd44355-TG125[1].webp', '[\"170512703765a22c7d1068044355-TG125_Seite_2.webp\",\"170512703765a22c7d107dc44355-TG125_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 13:23:57', '2024-03-01 02:07:16'),
('65a22e644f657-pprt-49899357613265912', 'Avanti 77, Center Base Jaw + Spindle', '44200-tg77-lang-avanti-77-center-base-jaw-spindle', '44200-TG77', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If required, the Avanti vise can be converted into a multiple clamp. During the conversion, which can be carried out in a few moments, only the set of spindle + center piece from the contour clamp has to be replaced by the center base jaw + spindle. For (multiple) workpiece clamping, the same top jaw as for the base jaws is mounted on the center base jaw.</p>', '<p><br></p>', NULL, NULL, '170512752465a22e644f26944200-TG77[1].webp', '[\"170512752465a22e644f45f44200-TG77_Seite_2.webp\",\"170512752465a22e644f54b44200-TG77_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 13:32:04', '2024-03-01 02:07:16'),
('65a2300a89b5d-pprt-69435562011707324', 'Avanti 125, Center Base Jaw + Spindle', '44255-tg125-lang-avanti-125-center-base-jaw-spindle', '44255-TG125', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If required, the Avanti vise can be converted into a multiple clamp. During the conversion, which can be carried out in a few moments, only the set of spindle + center piece from the contour clamp has to be replaced by the center base jaw + spindle. For (multiple) workpiece clamping, the same top jaw as for the base jaws is mounted on the center base jaw.</p>', '<p><br></p>', NULL, NULL, '170512794665a2300a8971544255-TG125[1].webp', '[\"170512794665a2300a8993a44255-TG125_Seite_2.webp\",\"170512794665a2300a89a3a44255-TG125_Seite_1_0.webp\"]', 1, NULL, 0, '2024-01-13 13:39:06', '2024-03-01 02:07:16'),
('65a2323433d6b-pprt-17501421848607674', 'Avanti 77, Center Base Jaw + Spindle', '44120-tg46-lang-avanti-77-center-base-jaw-spindle', '44120-TG46', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If required, the Avanti vice can be converted into a multiple clamp. During the conversion, which can be carried out in a few moments, only the set of spindle + centre piece from the contour clamp has to be replaced by the centre base jaw + spindle. For (multiple) workpiece clamping, the same top jaw as for the base jaws is mounted on the centre base jaw.</p>', '<p><br></p>', NULL, NULL, '170512850065a232343399244120-TG46[1].webp', '[\"170512850065a2323433b3844120-TG46_Seite_2.webp\",\"170512850065a2323433c4044120-TG46_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 13:48:20', '2024-03-01 02:07:16'),
('65a2341c98ba6-pprt-76750548674283847', 'Avanti 125, Center Base Jaw + Spindle', '44305-tg125-lang-avanti-125-center-base-jaw-spindle', '44305-TG125', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If required, the Avanti vise can be converted into a multiple clamp. During the conversion, which can be carried out in a few moments, only the set of spindle + center piece from the contour clamp has to be replaced by the center base jaw + spindle. For (multiple) workpiece clamping, the same top jaw as for the base jaws is mounted on the center base jaw.</p>', '<p><br></p>', NULL, NULL, '170512898865a2341c9867e44305-TG125[1].webp', '[\"170512898865a2341c988fe44305-TG125_Seite_2.webp\",\"170512898865a2341c98a5844305-TG125_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 13:56:28', '2024-03-01 02:07:16'),
('65a23548481d1-pprt-32062513147014401', 'Quick•Point® Rail, Connector', '73701-lang-quickpoint-rail-connector', '73701', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The connector allows the clamping and extension bars of the Quick-Point® Rail System to be mounted to each other.</p>', '<p><br></p>', NULL, NULL, '170512928865a2354847fed85701_Einzel.webp', NULL, 1, NULL, 0, '2024-01-13 14:01:28', '2024-03-01 02:07:16'),
('65a27bc4bdf63-pprt-25321509990214591', 'Makro•Grip® FS, Conversion set', '51260-20-lang-makrogrip-fs-conversion-set', '51260-20', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">With the conversion set, stamping jaws of the new Makro•Grip® FS series can be used on all existing stamping units. The conversion set consists of a fixed and a movable carrier jaw for the stamping jaws. The stamping jaws themselves are not included in the scope of delivery.</p>', '<p><br></p>', NULL, NULL, '170514733265a27bc4bdce151260-20.webp', NULL, 1, NULL, 0, '2024-01-13 19:02:12', '2024-03-01 02:07:16'),
('65a27caac68a7-pprt-65923309492110809', 'Makro•Grip® FS, Set centering device and depth gauge', '50150-lang-makrogrip-fs-set-centering-device-and-depth-gauge', '50150', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The new accessories for Makro•Grip® stamping units - centering device and depth gauge - are also available as a set. Using this set makes operation of the stamping unit even easier and more effective. The centering device speeds up the exact centering of workpiece blanks up to 205 mm wide, since no prior measurement is required. With the stamping depth gauge / measuring device, the correct stamping pressure or stamping depth can be read off on a dial gauge.</p>', '<p><br></p>', NULL, NULL, '170514756265a27caac63b650150.webp', '[\"170514756265a27caac664bPraegetechnik_Beautyshot_04.webp\"]', 1, NULL, 0, '2024-01-13 19:06:02', '2024-03-01 02:07:16'),
('65a27d914036e-pprt-88991006884961321', 'Makro•Grip® FS, Depth gauge', '50152-lang-makrogrip-fs-depth-gauge', '50152', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The stamping depth gauge is available as an accessory for each stamping unit of the Makro•Grip® FS series. The use of this measuring device makes it easier and faster to set correct the stamping pressure. A visual inspection of the workpiece blank and possible re-stamping are no longer necessary. Instead, it is enough to simply increase the pressure until the desired stamping depth is reached. The stamping depth can be easily read off the dial gauge. This method makes the entire stamping process more reliable, as it is based less on experience or feeling and more on exact data.</p>', '<p><br></p>', NULL, NULL, '170514779365a27d914014750152.webp', NULL, 1, NULL, 0, '2024-01-13 19:09:53', '2024-03-01 02:07:16'),
('65a27e30e5d48-pprt-73172092007532664', 'Makro•Grip® FS, Set centering device and depth gauge', '50150-lang-makrogrip-fs-set-centering-device-and-depth-gauge-2', '50150', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The new accessories for Makro•Grip® stamping units - centering device and depth gauge - are also available as a set. Using this set makes operation of the stamping unit even easier and more effective. The centering device speeds up the exact centering of workpiece blanks up to 205 mm wide, since no prior measurement is required. With the stamping depth gauge / measuring device, the correct stamping pressure or stamping depth can be read off on a dial gauge.</p>', '<p><br></p>', NULL, NULL, '170514795265a27e30e5ad5Praegetechnik_Beautyshot_04 (1).webp', NULL, 1, NULL, 0, '2024-01-13 19:12:32', '2024-03-01 02:07:16'),
('65a27f6bd6138-pprt-59153097833838958', 'Makro•Grip® FS, Centering device', '50151-lang-makrogrip-fs-centering-device', '50151', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The centering device, suitable for all stamping units of the Makro•Grip® FS series, facilitates the exact centering of the workpiece blank in the stamping unit. Users benefit from time savings during setup, greater ease of operation, and reduced susceptibility to errors due to their own fault. With the new centering device, which is available as an accessory, workpiece blanks up to 205 mm wide can be inserted exactly in the center. This eliminates the need to measure the workpiece blank beforehand.</p>', '<p><br></p>', NULL, NULL, '170514826765a27f6bd5f3850151.webp', NULL, 1, NULL, 0, '2024-01-13 19:17:47', '2024-03-01 02:07:16'),
('65a28012453c8-pprt-68282642222686446', 'Makro•Grip® FS, Set centering device and depth gauge', '50150-lang-makrogrip-fs-set-centering-device-and-depth-gauge-2', '50150', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The new accessories for Makro•Grip® stamping units - centering device and depth gauge - are also available as a set. Using this set makes operation of the stamping unit even easier and more effective. The centering device speeds up the exact centering of workpiece blanks up to 205 mm wide, since no prior measurement is required. With the stamping depth gauge / measuring device, the correct stamping pressure or stamping depth can be read off on a dial gauge.</p>', '<p><br></p>', NULL, NULL, '170514843465a280124507e50150 (2).webp', '[\"170514843465a280124523dPraegetechnik_Beautyshot_04 (2).webp\"]', 1, NULL, 0, '2024-01-13 19:20:34', '2024-03-01 02:07:16'),
('65a2815ccd1cc-pprt-89150010613384045', 'Makro•Grip® FS, Gauging Blocks', '50153-lang-makrogrip-fs-gauging-blocks', '50153', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">This new version of the gauging blocks is suitable for the stamping jaws of the Makro•Grip® FS stamping units. The gauging blocks, which are included in the scope of delivery of each stamping unit, are used to measure the wear on the serration of the stamping jaws. Regular checking of the serration helps to ensure consistently high quality in workpiece clamping. If one of the four rows of serration is worn, the stamping jaws of the Makro•Grip® FS series are simply turned three more times.</p>', '<p><br></p>', NULL, NULL, '170514876465a2815cccfd250153.webp', NULL, 1, NULL, 0, '2024-01-13 19:26:04', '2024-03-01 02:07:16'),
('65a282506e421-pprt-35247242445504526', 'Quick•Point®, Quick•Lock Clamping Lever 52 / 96, for Multi Plates', '44400-lang-quickpoint-quicklock-clamping-lever-52-96-for-multi-plates', '44400', 'parts', '1', NULL, NULL, 1.00, '<p></p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: \" encode=\"\" sans=\"\" compressed\",=\"\" sans-serif;=\"\" font-size:=\"\" 20px;=\"\" font-style:=\"\" normal;=\"\" font-variant-ligatures:=\"\" font-variant-caps:=\"\" font-weight:=\"\" 400;=\"\" letter-spacing:=\"\" 0.8px;=\"\" orphans:=\"\" 2;=\"\" text-align:=\"\" left;=\"\" text-indent:=\"\" 0px;=\"\" text-transform:=\"\" none;=\"\" widows:=\"\" word-spacing:=\"\" -webkit-text-stroke-width:=\"\" white-space:=\"\" background-color:=\"\" rgb(247,=\"\" 247,=\"\" 248);=\"\" text-decoration-thickness:=\"\" initial;=\"\" text-decoration-style:=\"\" text-decoration-color:=\"\" initial;\"=\"\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div><p></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">With this clamping lever, the user conveniently operates the Quick•Lock quick-release fastener mounted on a zero point plate with a 180° closing movement. This clamping lever is used for Quick•Lock quick-release fasteners mounted on multiple grid plates.</p>', '<p><br></p>', NULL, NULL, '170514900865a282506e24744400.webp', NULL, 1, NULL, 0, '2024-01-13 19:30:08', '2024-03-01 02:07:16'),
('65a2834d2c352-pprt-98286484761237119', 'Quick•Point®, Quick•Lock Clamping Lever', '44500-lang-quickpoint-quicklock-clamping-lever', '44500', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">With this clamping lever, the user conveniently operates the Quick-Lock quick-release fastener mounted on a zero-point plate with a 180° closing movement. This clamping lever is used for Quick-Lock quick-release fasteners mounted on single plates, twin bases, the adapter plate or 5-axis risers.</p>', '<p><br></p>', NULL, NULL, '170514926165a2834d2c1a944500[1].webp', NULL, 1, NULL, 0, '2024-01-13 19:34:21', '2024-03-01 02:07:16'),
('65a2843ad4a54-pprt-26675197296060185', 'Makro•Grip® Ultra, Mechanical Screw Jack', '82586-lang-makrogrip-ultra-mechanical-screw-jack', '82586', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The mechanical screw jack is installed in the center of the Makro•Grip® Ultra base set. Due to its pendulum support, it provides an additional support surface. It prevents bending and vibrations in the machining of plates or thin components. The set includes two pendulum supports that match the contact surfaces in the clamping jaws. The set also includes a clamping screw and three actuating rods for the three different base body lengths of the Makro•Grip® Ultra base sets.<br style=\"box-sizing: inherit;\"></p>', '<p><br></p>', NULL, NULL, '170514949865a2843ad42eb82586_4000x4000px.webp', '[\"170514949865a2843ad456581400_82586_4000x4000px.webp\",\"170514949865a2843ad472681400_82586_Unterstuetzung_Frontal_4000x4000px.webp\",\"170514949865a2843ad484781400_82586_Keyvisual_02_4000x4000px.webp\"]', 1, NULL, 0, '2024-01-13 19:38:18', '2024-03-01 02:07:16'),
('65a2abc896c63-pprt-33542289344220469', 'HAUBEX, Tool Holder', '61500-hsk63-lang-haubex-tool-holder', '61500-HSK63', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The tool holder type HSK-A63 specially adapted to HAUBEX, guarantees an exactly defined positioning of the workholding hood in the machine spindle.</p>', '<p><br></p>', NULL, NULL, '170515962465a2abc896699Aufnahme_Hohlschaftkegel_DIN_69893-1_HSK-A63.webp', '[\"170515962465a2abc8968d161500-HSK-A63_Seite_3.webp\",\"170515962465a2abc8969d961500-HSK-A63_Seite_2.webp\",\"170515962465a2abc896b0b61500-HSK-A63_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 22:27:04', '2024-03-01 02:07:16'),
('65a2adc0835c9-pprt-57679587319049162', 'HAUBEX, Tool Holder', '61500-bt40-lang-haubex-tool-holder', '61500-BT40', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The tool holder type BT-40, specially adapted to HAUBEX, guarantees an exactly defined positioning of the workholding hood in the machine spindle.</p>', '<p><br></p>', NULL, NULL, '170516012865a2adc083190Aufnahme_Steilkegel_JIS_B6339_BT40.webp', '[\"170516012865a2adc08339861500-BT40_Seite_2.webp\",\"170516012865a2adc08349f61500-BT40_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 22:35:28', '2024-03-01 02:07:16'),
('65a2afc80d595-pprt-91487894225840128', 'HAUBEX, Tool Holder', '61500-sk40-lang-haubex-tool-holder', '61500-SK40', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The tool holder type SK-40, specially adapted to HAUBEX, guarantees an exactly defined positioning of the workholding hood in the machine spindle.</p>', '<p><br></p>', NULL, NULL, '170516064865a2afc80cf9cAufnahme_Steilkegel_DIN_ISO_7388-1_SK40.webp', '[\"170516064865a2afc80d21161500-SK40_Seite_3.webp\",\"170516064865a2afc80d32f61500-SK40_Seite_2.webp\",\"170516064865a2afc80d43461500-SK40_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 22:44:08', '2024-03-01 02:07:16'),
('65a2b12aa46a0-pprt-32621747406073089', 'HAUBEX, Tool Holder', '61500-cat40-lang-haubex-tool-holder', '61500-CAT40', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The tool holder type CAT-40, specially adapted to HAUBEX, guarantees an exactly defined positioning of the workholding hood in the machine spindle.</p>', '<p><br></p>', NULL, NULL, '170516100265a2b12aa403f61500-CAT40[1].webp', '[\"170516100265a2b12aa42bb61500-CAT40_Seite_3.webp\",\"170516100265a2b12aa43e961500-CAT40_Seite_2.webp\",\"170516100265a2b12aa44ff61500-CAT40_Seite_1.webp\"]', 1, NULL, 0, '2024-01-13 22:50:02', '2024-03-01 02:07:16'),
('65a2b2551fbab-pprt-61335727016400831', 'Quick•Point®, Actuation Screw', '45003-lang-quickpoint-actuation-screw', '45003', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The actuation screw for hexagon socket 8 mm is a component of all Quick-Point® single zero-point plates (square, rectangular, round), as well as the 5-axis risers, adapter plates and twin bases.</p>', '<p><br></p>', NULL, NULL, '170516130165a2b2551f9be45003.webp', NULL, 1, NULL, 0, '2024-01-13 22:55:01', '2024-03-01 02:07:16'),
('65a2b3137554b-pprt-59396672069551994', 'Quick•Point®, Actuation Screw', '45015-lang-quickpoint-actuation-screw', '45015', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The actuation screw for external hexagon 15 mm is a component of all Quick•Point® 96 multiple zero-point plates.</p>', '<p><br></p>', NULL, NULL, '170516149165a2b3137532845015.webp', NULL, 1, NULL, 0, '2024-01-13 22:58:11', '2024-03-01 02:07:16'),
('65a2b3d0965f8-pprt-54224778697573233', 'Quick•Point®, Actuation Screw', '45013-lang-quickpoint-actuation-screw', '45013', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The actutaion screw for external hexagon 12 mm is a component of all Quick•Point® 52 and 96 multiple zero-point plates.</p>', '<p><br></p>', NULL, NULL, '170516168065a2b3d0963c645013.webp', NULL, 1, NULL, 0, '2024-01-13 23:01:20', '2024-03-01 02:07:16'),
('65a2cdfb485ab-pprt-92320619783752205', 'Quick•Point® 52, Alignment Gauge', '44521-lang-quickpoint-52-alignment-gauge', '44521', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The alignment gauge ensures the exact alignment of two or more grid plates placed next to each other. This enables the clamping of vises or fixtures across plates. The alignment gauge is not only available for purchase but also for loan and can be returned after the successful installation of the zero-point clamping system.</p>', '<p><br></p>', NULL, NULL, '170516837965a2cdfb4825144521.webp', '[\"170516837965a2cdfb4840b44521_Seite_1.webp\"]', 1, NULL, 0, '2024-01-14 00:52:59', '2024-03-01 02:07:16'),
('65a2cf7dc1f4e-pprt-10336231106004670', 'Quick•Point® 96, Alignment Gauge', '44961-10-lang-quickpoint-96-alignment-gauge', '44961-10', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The alignment gauge ensures the exact alignment of two or more grid plates placed next to each other. This enables the clamping of vices or fixtures across plates. The alignment gauge is not only available for purchase but also for loan and can be returned after the successful installation of the zero-point clamping system.</p>', '<p><br></p>', NULL, NULL, '170516876565a2cf7dc1c5144961.webp', '[\"170516876565a2cf7dc1e0144961_Seite_1.webp\"]', 1, NULL, 0, '2024-01-14 00:59:25', '2024-03-01 02:07:16'),
('65a2d12e834e4-pprt-79590701441660054', 'Quick•Point® 52, Alignment Gauge', '44521-10-lang-quickpoint-52-alignment-gauge', '44521-10', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The alignment gauge ensures the exact alignment of two or more grid plates placed next to each other. This enables the clamping of vises or fixtures across plates. The alignment gauge is not only available for purchase but also for loan and can be returned after the successful installation of the zero-point clamping system.</p>', '<p><br></p>', NULL, NULL, '170516919865a2d12e8325f44521 (1).webp', '[\"170516919865a2d12e833bd44521_Seite_1_0.webp\"]', 1, NULL, 0, '2024-01-14 01:06:38', '2024-03-01 02:07:16'),
('65a2d1edb71bf-pprt-99228314692654698', 'Quick•Point® 96, Alignment Gauge', '44961-lang-quickpoint-96-alignment-gauge', '44961', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The alignment gauge ensures the exact alignment of two or more grid plates placed next to each other. This enables the clamping of vices or fixtures across plates. The alignment gauge is not only available for purchase but also for loan and can be returned after the successful installation of the zero-point clamping system.</p>', '<p><br></p>', NULL, NULL, '170516938965a2d1edb6e5f44961 (1).webp', '[\"170516938965a2d1edb701a44961_Seite_1 (1).webp\"]', 1, NULL, 0, '2024-01-14 01:09:49', '2024-03-01 02:07:16');
INSERT INTO `product_part` (`id`, `name`, `slug`, `code`, `parts_type`, `brand_id`, `category_id`, `product_id`, `price`, `key_features`, `further_information`, `discount_type`, `discount`, `thumbnail`, `images`, `status`, `ancestor_id`, `short_number`, `created_at`, `updated_at`) VALUES
('65a2d3438769b-pprt-98725720307618551', 'Quick•Point®, Alignment Claw', '452418-lang-quickpoint-alignment-claw', '452418', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Alignment claws are used for the simultaneous fastening and alignment of Quick•Point® zero point plates lined up next to each other. They can be used when two zero point plates are positioned on the machine table in such a way that they are directly next to each other above a table groove and fastening holes in this area appear to make sense. For this purpose, a corresponding hole must be made across the plate, into which the alignment claw is inserted and fastened with a cylinder head screw countersunk in it and a T-slot nut.</p>', '<p><br></p>', NULL, NULL, '170516973165a2d34387227452418.webp', '[\"170516973165a2d34387483QP_Modulplatten_Rendering_02_0_2.webp\"]', 1, NULL, 0, '2024-01-14 01:15:31', '2024-03-01 02:07:16'),
('65a2d4b10fec9-pprt-15455670843189480', 'Quick•Point®, Alignment Claw', '452414-lang-quickpoint-alignment-claw', '452414', 'parts', '1', NULL, NULL, 1.00, '<p>Alignment claws are used for the simultaneous fastening and alignment of Quick•Point® zero point plates lined up next to each other. They can be used when two zero point plates are positioned on the machine table in such a way that they are directly next to each other above a table groove and fastening holes in this area appear to make sense. For this purpose, a corresponding hole must be made across the plate, into which the alignment claw is inserted and fastened with a cylinder head screw countersunk in it and a T-slot nut.</p><p>in Kombination mit Quick•Point® Modulplatten eingesetzt. Sie ersetzen im Lieferumfang von Modulplatten enthaltene Kunststoffabdeckungen, wenn zwei Modulplatten direkt über einer Maschinentisch-Nut positioniert sind. Durch die Senkung der Ausrichtpratzen werden Zylinderkopfschrauben eingesetzt, die mit T-Nut-Muttern verbunden werden.<br style=\"box-sizing: inherit; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; letter-spacing: 0.8px; background-color: rgb(247, 247, 248);\"></p>', '<p><br></p>', NULL, NULL, '170517009765a2d4b10fb1b452414.webp', '[\"170517009765a2d4b10fcfbQP_Modulplatten_Rendering_02_0_1.webp\"]', 1, NULL, 0, '2024-01-14 01:21:37', '2024-03-01 02:07:16'),
('65a2d6b45f4c5-pprt-86879937730105903', 'Quick•Point®, Covers for Modular Plates', '85702-lang-quickpoint-covers-for-modular-plates', '85702', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The plastic covers seal the recesses of the Quick•Point® modular plates to which no further modular plate is attached. They are available in sets of 10.</p>', '<p><br></p>', NULL, NULL, '170517061265a2d6b45f29f85702[1].webp', NULL, 1, NULL, 0, '2024-01-14 01:30:12', '2024-03-01 02:07:16'),
('65a2d7f793411-pprt-45230448123123487', 'Quick•Point®, Connection Set for Modular Plates', '85701-lang-quickpoint-connection-set-for-modular-plates', '85701', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The connection set for the Quick•Point® modular plates is not only available in the conversion kit (Art. No. 85700), but also individually, for example, to connect modular plates with each other in the x-direction and to enable a cross-plate zero point grid. Due to the precisely fitting connection with the recesses of the zero point plate, module plates that are connected to each other no longer have to be aligned with one another.</p>', '<p><br></p>', NULL, NULL, '170517093565a2d7f792fd485701.webp', '[\"170517093565a2d7f7931db85720_85710.webp\"]', 1, NULL, 0, '2024-01-14 01:35:35', '2024-03-01 02:07:16'),
('65a2d9d35bd83-pprt-15548368347853931', 'Quick•Point®, Conversion kit for Modular Plates', '85700-lang-quickpoint-conversion-kit-for-modular-plates', '85700', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The conversion kit for the Quick•Point® modular plates includes a pressure bolt and two connectors to connect two modular plates and handle them with a tightening screw. Due to the precisely fitting connecting pieces, the alignment of modular plates connected to each other is no longer necessary.</p>', '<p><br></p>', NULL, NULL, '170517141165a2d9d35ba1b85700.webp', NULL, 1, NULL, 0, '2024-01-14 01:43:31', '2024-03-01 02:07:16'),
('65a355d3ee518-pprt-37644576856140097', 'Quick•Point® 96, Gauging Pallet', '44962-lang-quickpoint-96-gauging-pallet', '44962', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The coordinate-ground gauging pallet enables quick and accurate alignment of Quick-Point® plates. This is particularly recommended for rotating axes and chucks. For concentric alignment of the zero-point plate, the inner diameter of the measuring body is used, while for axial alignment the side surfaces of the measuring body are traversed.</p>', '<p><br></p>', NULL, NULL, '170520315565a355d3ee1f244962.webp', NULL, 1, NULL, 0, '2024-01-14 10:32:35', '2024-03-01 02:07:16'),
('65a356a85e3ae-pprt-39974442282038948', 'Quick•Point® 52, Gauging Pallet', '44522-lang-quickpoint-52-gauging-pallet', '44522', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The coordinate-ground gauging pallet enables quick and accurate alignment of Quick-Point® plates. This is particularly recommended for rotating axes and chucks. For concentric alignment of the zero-point plate, the inner diameter of the measuring body is used, while for axial alignment the side surfaces of the measuring body are traversed.</p>', '<p><br></p>', NULL, NULL, '170520336865a356a85e15244522_1.webp', NULL, 1, NULL, 0, '2024-01-14 10:36:08', '2024-03-01 02:07:16'),
('65a3585a7b3ae-pprt-16305145719271613', 'HAUBEX, Clamping Lever', '61113-lang-haubex-clamping-lever', '61113', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">This clamping lever is used to manually operate the Quick•Point® HAUBEX zero-point clamping system when it is used in manual machining without a workholding hood.</p>', '<p><br></p>', NULL, NULL, '170520380265a3585a7b0fe61113_1.webp', NULL, 1, NULL, 0, '2024-01-14 10:43:22', '2024-03-01 02:07:16'),
('65a359253db49-pprt-61496386010842213', 'Preci•Point, Clamping Wrench', '41052-03-lang-precipoint-clamping-wrench', '41052-03', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Handy, sturdy clamping spanner made of steel for operating the clamping nut of the Preci•Point ER 50collet chuck.</p>', '<p><br></p>', NULL, NULL, '170520400565a359253d98a41052-03[1].webp', NULL, 1, NULL, 0, '2024-01-14 10:46:45', '2024-03-01 02:07:16'),
('65a359dd675fe-pprt-53390590724965004', 'Preci•Point, Clamping Wrench', '41032-03-lang-precipoint-clamping-wrench', '41032-03', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Handy, sturdy clamping spanner made of steel for operating the clamping nut of the Preci•Point ER 32 collet chuck.</p>', '<p><br></p>', NULL, NULL, '170520418965a359dd6744441032-03.webp', NULL, 1, NULL, 0, '2024-01-14 10:49:49', '2024-03-01 02:07:16'),
('65a35afe2fafd-pprt-59797981445294285', 'Quick•Point® 52, Handle Bar', '66605-lang-quickpoint-52-handle-bar', '66605', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The easy-to-use handle made of aluminium makes it easier to handle the Quick•Point® products when setting up and dismantling. Just like with the usual LANG clamping devises, the aluminium handle is clamped in the zero-point clamping system with two Quick•Point® clamping studs and is, therefore, particularly suitable for transporting heavier products, such as 5-axis risers or the zero-point automation devises.</p>', '<p><br></p>', NULL, NULL, '170520447865a35afe2f8c266605[1].webp', NULL, 1, NULL, 0, '2024-01-14 10:54:38', '2024-03-01 02:07:16'),
('65a35ba0ce814-pprt-96970708252980421', 'Quick•Point® 96, Handle Bar', '46081-lang-quickpoint-96-handle-bar', '46081', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The easy-to-use handle made of aluminium makes it easier to handle the Quick•Point® products when setting up and dismantling. Just like with the usual LANG clamping devises, the aluminium handle is clamped in the zero-point clamping system with two Quick•Point® clamping studs and is, therefore, particularly suitable for transporting heavier products, such as 5-axis risers or the zero-point automation devises.</p>', '<p><br></p>', NULL, NULL, '170520464065a35ba0ce59f46081[1].webp', NULL, 1, NULL, 0, '2024-01-14 10:57:20', '2024-03-01 02:07:16'),
('65a35cf23562f-pprt-94774212001067813', 'Makro•Grip® Ultra 125, Ultra Screw', '81251-4-lang-makrogrip-ultra-125-ultra-screw', '81251-4', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170520497865a35cf23548881251-48.webp', NULL, 1, NULL, 0, '2024-01-14 11:02:58', '2024-03-01 02:07:16'),
('65a35d7eefd40-pprt-16309139565077278', 'Makro•Grip® Ultra 125, Ultra Screw', '81251-48-lang-makrogrip-ultra-125-ultra-screw', '81251-48', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170520511865a35d7eefb7281251-48.webp', NULL, 1, NULL, 0, '2024-01-14 11:05:18', '2024-03-01 02:07:16'),
('65a35e0542673-pprt-40675120517349785', 'Makro•Grip® Ultra 125, Ultra Screw', '81251-04-lang-makrogrip-ultra-125-ultra-screw', '81251-04', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170520525365a35e05423ec81251-04.webp', NULL, 1, NULL, 0, '2024-01-14 11:07:33', '2024-03-01 02:07:16'),
('65a35e866959b-pprt-61222239346160824', 'Makro•Grip® Ultra, Mechanical Screw Jack', '82586-lang-makrogrip-ultra-mechanical-screw-jack-2', '82586', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The mechanical screw jack is installed in the center of the Makro•Grip® Ultra base set. Due to its pendulum support, it provides an additional support surface. It prevents bending and vibrations in the machining of plates or thin components. The set includes two pendulum supports that match the contact surfaces in the clamping jaws. The set also includes a clamping screw and three actuating rods for the three different base body lengths of the Makro•Grip® Ultra base sets.<br style=\"box-sizing: inherit;\"></p>', '<p><br></p>', NULL, NULL, '170520538265a35e8668aea82586_4000x4000px (1).webp', '[\"170520538265a35e8668e1f81400_82586_4000x4000px (1).webp\",\"170520538265a35e866909981400_82586_Unterstuetzung_Frontal_4000x4000px (1).webp\",\"170520538265a35e86692eb81400_82586_Keyvisual_02_4000x4000px (1).webp\"]', 1, NULL, 0, '2024-01-14 11:09:42', '2024-03-01 02:07:16'),
('65a4e1a69ac7f-pprt-94997979587371261', 'Makro•Grip® Ultra, Cover Discs', '81500-lang-makrogrip-ultra-cover-discs', '81500', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The plastic covers fit the holes on the upper guide surface of the Makro-Grip® Ultra base bodies, unless they are used to attach a centre jaw.</p>', '<p><br></p>', NULL, NULL, '170530448665a4e1a69aa6081500[1].webp', NULL, 1, NULL, 0, '2024-01-15 14:41:26', '2024-03-01 02:07:16'),
('65a4e33ad9dc0-pprt-44943948171276247', 'Makro•Grip® FS, Stamping Trolley', '52521-lang-makrogrip-fs-stamping-trolley', '52521', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The revised version of the stamping unit as a mobile version on the stamping trolley represents a major redesign of the already familiar model. New types of stamping jaws and features ensure optimized and more ergonomic operation. The stamping trolley itself now has a practical drawer for storing accessories and tools. The pressure setting is conveniently made on the stamping trolley so that it is no longer necessary to reach over the stamping unit. The new version is operated exclusively via a fixed foot pedal, leaving both hands free. In this standard version of the Makro•Grip® FS stamping unit, a stamping base body in the short version up to 260 mm stamping width is fixed on the trolley, but could also accommodate a long base body. The stamping jaws with novel, four-row full serration are suitable for material hardness up to 35 HRC.</p>', '<p><br></p>', NULL, NULL, '170530489065a4e33ad9a6152521.webp', NULL, 1, NULL, 0, '2024-01-15 14:48:10', '2024-03-01 02:07:16'),
('65a4e447a5c0f-pprt-87651882406828531', 'Makro•Grip® FS, Stamping Trolley', '52521-he-lang-makrogrip-fs-stamping-trolley', '52521-HE', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The new mobile stamping unit on the revised stamping trolley represents a comprehensive redesign of the familiar model. Innovative features and new types of High-End stamping jaws ensure optimized operability as well as greater ergonomics during pre-stamping. A drawer on the trolley provides space for stowing accessories and tools, while the pressure setting can now be made directly on the trolley - without having to bend over the entire station to do so. In this new version, operation is solely by a fixed foot pedal, leaving both hands free - particularly practical when loading heavy parts into the stamping unit. In the standard version, a short base body is attached to the trolley up to an stamping width of 260 mm; however, a long body could also be mounted without any problems. High-end stamping jaws can be used to pre-stamp materials up to 45 HRC.</p>', '<p><br></p>', NULL, NULL, '170530515965a4e447a57de52521-HE.webp', NULL, 1, NULL, 0, '2024-01-15 14:52:39', '2024-03-01 02:07:16'),
('65a4e5506f060-pprt-34330293152093576', 'Makro•Grip® FS, Stamping Trolley', '53402-he-lang-makrogrip-fs-stamping-trolley', '53402-HE', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">With its dual stamping unit, consisting of a grooved t-slot plate and 2 stamping vises, the Makro•Grip® FS series offers optimized handling and even more flexibility in use. The design of the stamping trolley even allows a third stamping vise to be added if required. By default, the dual stamping unit comes with the long base body up to a stamping width of 410 mm; however, the shorter version for workpieces up to a maximum length of 260 mm can also be mounted on the t-slot plate. The necessary stamping pressure is provided by a pneumatic-hydraulic power multiplier inside the trolley, which can be used to pre-stamp workpiece blanks with up to 20 tons of pressure. A new arrangement of important elements, such as the foot pedal or the pressure control valve, makes operation extremely convenient and comfortable for the user. The High-End stamping jaws - equipped with new full serration - can pre-stamp materials up to a maximum of 45 HRC.</p>', '<p><br></p>', NULL, NULL, '170530542465a4e5506ed0f53402-HE.webp', NULL, 1, NULL, 0, '2024-01-15 14:57:04', '2024-03-01 02:07:16'),
('65a4e61ba5c37-pprt-62866028567184037', 'Makro•Grip® FS, Stamping Trolley', '53400-he-lang-makrogrip-fs-stamping-trolley', '53400-HE', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">A comprehensive technical revision of the stamping trolleys with t-slot plate and one stamping vise makes the new Makro•Grip® FS version even more flexible. With the new concept, it is now possible to add or expand a second or even third stamping unit without any problems. In addition, the short base body can now also be attached to the grooved plate - more flexibility is hardly possible! The long side of the trolley has a practical drawer for storing tools as well as accessories - everything within easy reach! The pressure control valve is also located at this point, as is the firmly fixed foot pedal that triggers the stamping process entirely without hands. With the High-End version of the stamping jaws (now with four-row full serration), materials up to 45 HRC can be pre-stamped.</p>', '<p><br></p>', NULL, NULL, '170530562765a4e61ba58c653400-HE.webp', NULL, 1, NULL, 0, '2024-01-15 15:00:27', '2024-03-01 02:07:16'),
('65a4e6ccd01e0-pprt-65086090174575712', 'Makro•Grip® FS, Stamping Unit', '53402-lang-makrogrip-fs-stamping-unit', '53402', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The dual stamping unit of the Makro•Grip® FS series, with t-slot plate and 2 stamping vises, impresses with optimized operability and even more flexibility in use. The design of the stamping trolley with t-slot plate allows a third stamping vise to be added if required. The dual stamping unit uses the long stamping base body up to a stamping width of 410 mm as standard. However, the short base bodies with a maximum stamping width of 260 mm can also be mounted on the grooved plate. More flexibility is hardly possible! The stamping units are supplied by a pneumatic-hydraulic power multiplier placed inside the stamping trolley. Thanks to the new arrangement of elements such as the pressure control valve and the foot pedal, operation is much more convenient. The stamping jaws of the Makro•Grip® FS stamping units with new, continuous serration are designed for materials up to 35 HRC.</p>', '<p><br></p>', NULL, NULL, '170530580465a4e6cccfdb353402.webp', NULL, 1, NULL, 0, '2024-01-15 15:03:24', '2024-03-01 02:07:16'),
('65a4ea46af67c-pprt-67396351884940808', 'Avanti 77, Actuation Screw', '44771-03-lang-avanti-77-actuation-screw', '44771-03', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170530669465a4ea46af4ba44771-03.webp', NULL, 1, NULL, 0, '2024-01-15 15:18:14', '2024-03-01 02:07:16'),
('65a4f18ae799f-pprt-92607176264975693', 'Avanti 125, Actuation Screw', '44251-03-lang-avanti-125-actuation-screw', '44251-03', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170530855465a4f18ae77a344251-03.webp', NULL, 1, NULL, 0, '2024-01-15 15:49:14', '2024-03-01 02:07:17'),
('65a4f335ee848-pprt-20964131793040864', 'Avanti 46, Actuation Screw', '44461-03-lang-avanti-46-actuation-screw', '44461-03', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170530898165a4f335ee69b44461-03.webp', NULL, 1, NULL, 0, '2024-01-15 15:56:21', '2024-03-01 02:07:17'),
('65a50749c8834-pprt-77570775797363849', 'Makro•Grip®, End-Stop', '41261-lang-makrogrip-end-stop', '41261', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The universal end stop for all versions of the Makro•Grip® stamping unit enables the workpiece blank to be inserted into the center of the stamping unit with high repeat accuracy. It is attached to the side via an M 10 thread. Its thorn is provided with a scale. An exact centered positioning of the blank is guaranteed when the value on the scale is exactly half of the workpiece width. (e.g. workpiece width 80 mm = setting value scaling 40).</p>', '<p><br></p>', NULL, NULL, '170531412165a50749c851641261.webp', NULL, 1, NULL, 0, '2024-01-15 17:22:01', '2024-03-01 02:07:17'),
('65a508116f996-pprt-61801578261180605', 'RoboTrex, Trolley Entry System', '66120-lang-robotrex-trolley-entry-system', '66120', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The Entry System is used equally for the RoboTrex 52 and 96 automation systems. It ensures precise positioning of the automation trolleys in the system.</p>', '<p><br></p>', NULL, NULL, '170531432165a508116f78d66120[1].webp', NULL, 1, NULL, 0, '2024-01-15 17:25:21', '2024-03-01 02:07:17'),
('65a5093d6ffb9-pprt-76261375516426271', 'RoboTrex, Automation Window', '66750-lang-robotrex-automation-window', '66750', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The automation window is used equally for the RoboTrex 52 and 96 automation systems. If the machine tool to be automated does not yet have a window for the desired side loading, this special automation window is inserted during the installation of the automation system.</p>', '<p><br></p>', NULL, NULL, '170531462165a5093d6fd7166750[1].webp', NULL, 1, NULL, 0, '2024-01-15 17:30:21', '2024-03-01 02:07:17'),
('65a509e180f6f-pprt-83315886353970083', 'RoboTrex 96, Positioning Bolt', '64086-lang-robotrex-96-positioning-bolt', '64086', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">This positioning bolt is a spare part for both versions of RoboTrex 96 automation trolleys, but can also be used for individual storage devises.</p>', '<p><br></p>', NULL, NULL, '170531478565a509e180cf164086.webp', NULL, 1, NULL, 0, '2024-01-15 17:33:05', '2024-03-01 02:07:17'),
('65a50a6449624-pprt-70004189848183424', 'RoboTrex 52, Positioning Bolt', '66087-lang-robotrex-52-positioning-bolt', '66087', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">This positioning bolt is a spare part for both versions of RoboTrex 52 automation trolleys, but can also be used for individual storage devises.</p>', '<p><br></p>', NULL, NULL, '170531491665a50a64493f366087.webp', NULL, 1, NULL, 0, '2024-01-15 17:35:16', '2024-03-01 02:07:17'),
('65a50b2b94687-pprt-71895377729320539', 'Makro•Grip®, Spare Stud', '41010-01-lang-makrogrip-spare-stud', '41010-01', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The replacement marking pin is an after-sale part of the center marking tool of the Makro•Grip® stamping unit. It is used to make a small indentation in the raw material during the stamping process to facilitate central insertion in the 5-axis vise, which means that workpiece end stops are barely needed any more.</p>', '<p><br></p>', NULL, NULL, '170531511565a50b2b944b441010-01[1].webp', NULL, 1, NULL, 0, '2024-01-15 17:38:35', '2024-03-01 02:07:17'),
('65a50bed8cf87-pprt-36655666172004716', 'Makro•Grip®, Pneumatic-Hydraulic Pressure Multiplier', '41250-lang-makrogrip-pneumatic-hydraulic-pressure-multiplier', '41250', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The pneumatic-hydraulic power multiplier, which is the same for all stamping units, can generate a pressure of up to 20 tons in order to pre-stamp workpieces with a truncated pyramid-like contour. The power multiplier uses a commercially available HLP 32 hydraulic oil.</p>', '<p><br></p>', NULL, NULL, '170531530965a50bed8cda141250[1].webp', NULL, 1, NULL, 0, '2024-01-15 17:41:49', '2024-03-01 02:07:17'),
('65a50cfc87fc3-pprt-40955485644371902', 'Makro•Grip® 125, Spindle Cover', '47125-80-lang-makrogrip-125-spindle-cover', '47125-80', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170531558065a50cfc87e57placeholder.png', NULL, 1, NULL, 0, '2024-01-15 17:46:20', '2024-03-01 02:07:17'),
('65a50d77cae77-pprt-25517994232509210', 'Makro•Grip® 46, Spindle Cover', '47046-80-lang-makrogrip-46-spindle-cover', '47046-80', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170531570365a50d77caccfplaceholder.png', NULL, 1, NULL, 0, '2024-01-15 17:48:23', '2024-03-01 02:07:17'),
('65a50e035c4d5-pprt-58004291495772672', 'Makro•Grip® 77, Spindle Cover', '47077-70-lang-makrogrip-77-spindle-cover', '47077-70', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170531584365a50e035c347placeholder.png', NULL, 1, NULL, 0, '2024-01-15 17:50:43', '2024-03-01 02:07:17'),
('65a50e862a7a0-pprt-43424931952085874', 'Makro•Grip® 77, Spindle Cover', '47077-80-lang-makrogrip-77-spindle-cover', '47077-80', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170531597465a50e862a5d6placeholder.png', NULL, 1, NULL, 0, '2024-01-15 17:52:54', '2024-03-01 02:07:17'),
('65a50eff8c719-pprt-21214497440993859', 'Makro•Grip® 125, Spindle Cover', '47125-70-lang-makrogrip-125-spindle-cover', '47125-70', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170531609565a50eff8c541placeholder.png', NULL, 1, NULL, 0, '2024-01-15 17:54:55', '2024-03-01 02:07:17');
INSERT INTO `product_part` (`id`, `name`, `slug`, `code`, `parts_type`, `brand_id`, `category_id`, `product_id`, `price`, `key_features`, `further_information`, `discount_type`, `discount`, `thumbnail`, `images`, `status`, `ancestor_id`, `short_number`, `created_at`, `updated_at`) VALUES
('65a510c765eb6-pprt-32579408934764655', 'Makro•Grip® Ultra, Stamping Jaws', '41112-06-lang-makrogrip-ultra-stamping-jaws', '41112-06', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">These high-end stamping jaws are specially designed for use with the Makro-Grip® Ultra stamping unit and are supplied without support bars. Depending on which jaws are mounted on the Makro-Grip® Ultra system, the user can freely decide whether to mount parallels with a clamping depth of 3 mm (item no. 41111-0308) or with a clamping depth of 5 mm (item no. 41111-0508). These are available separately.</p>', '<p><br></p>', NULL, NULL, '170531655165a510c765b4141112-06[1].webp', '[\"170531655165a510c765d2941112-06_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-15 18:02:31', '2024-03-01 02:07:17'),
('65a5122487fdc-pprt-16329921193034497', 'Makro•Grip® Ultra, Parallels', '41111-0308-lang-makrogrip-ultra-parallels', '41111-0308', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">These parallels with a thickness of 8 mm are especially suitable for pre-stamping large workpieces with the Makro-Grip® Ultra stamping unit. The clamping depth is 3 mm (suitable for clamping jaws with item no. 81483).</p>', '<p><br></p>', NULL, NULL, '170531690065a5122487d0041111-0308[1].webp', '[\"170531690065a5122487ea041111-0308_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-15 18:08:20', '2024-03-01 02:07:17'),
('65a5189fabe1b-pprt-49343963496920412', 'Makro•Grip® Ultra, Parallels', '41111-0508-lang-makrogrip-ultra-parallels', '41111-0508', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">These parallels with a thickness of 8 mm are specially suitable for pre-stamping large workpieces with the Makro•Grip® Ultra stamping unit. The clamping depth is 5 mm (suitable for clamping jaws with item no. 81485).</p>', '<p><br></p>', NULL, NULL, '170531855965a5189fab9f341111-0508[1].webp', '[\"170531855965a5189fabc5341111-0508_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-15 18:35:59', '2024-03-01 02:07:17'),
('65a519e2280dd-pprt-66488020933185852', 'Makro•Grip® Ultra, Hexagon Socket', '45511-lang-makrogrip-ultra-hexagon-socket', '45511', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The hexagon socket size 19 mm can be used to clamp the workpieces in the Makro•Grip® Ultra clamping system. Its 1/2” square drive fits standard torque wrenches. The maximum tightening torque of the Makro•Grip® Ultra spindle unit is 170 Nm.</p>', '<p><br></p>', NULL, NULL, '170531888265a519e227ea245509.webp', NULL, 1, NULL, 0, '2024-01-15 18:41:22', '2024-03-01 02:07:17'),
('65a528fe44e90-pprt-81039581657713721', 'Makro•Grip® Ultra, Wrench', '45519-lang-makrogrip-ultra-wrench', '45519', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The wrench with external hexagon and a size of 19 mm can be used for the initial clamping setting in the Makro•Grip® Ultra system. This wrench is already included in the scope of delivery of the Makro•Grip® Ultra base sets. However, the final clamping of the workpieces should be done with a torque wrench.</p>', '<p><br></p>', NULL, NULL, '170532275065a528fe44c2f45501.webp', NULL, 1, NULL, 0, '2024-01-15 19:45:50', '2024-03-01 02:07:17'),
('65a529ad59dc0-pprt-48237410740885293', 'Makro•Grip® Ultra, Hexagon Socket', '45511-lang-makrogrip-ultra-hexagon-socket-2', '45511', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The hexagon socket size 19 mm can be used to clamp the workpieces in the Makro•Grip® Ultra clamping system. Its 1/2” square drive fits standard torque wrenches. The maximum tightening torque of the Makro•Grip® Ultra spindle unit is 170 Nm.</p>', '<p><br></p>', NULL, NULL, '170532292565a529ad59bc345509 (1).webp', NULL, 1, NULL, 0, '2024-01-15 19:48:45', '2024-03-01 02:07:17'),
('65a52a8cad121-pprt-88124379708574293', 'Makro•4Grip, Stamping Jaws', '51111-lang-makro4grip-stamping-jaws', '51111', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The Makro•4Grip stamping jaws, which enable the pre-stamping of round blanks, are compatible with all versions of the stamping unit. Two stamping inserts are mounted on each Makro•4Grip stamping jaw, with which the workpiece is stamped. In order to adapt the stamping inserts to different workpiece diameters or clamping jaw sizes, the stamping inserts are positioned at a total of three possible distances (narrow, medium, wide) from each other.</p>', '<p><br></p>', NULL, NULL, '170532314865a52a8cacdde51111[1].webp', '[\"170532314865a52a8cacfc051111_Seite_1.webp\"]', 1, NULL, 0, '2024-01-15 19:52:28', '2024-03-01 02:07:17'),
('65a52b8044cd4-pprt-20802779460938925', 'Makro•4Grip, Stamping Inserts', '51111-40-lang-makro4grip-stamping-inserts', '51111-40', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Round blanks are pre-stamped with the stamping inserts and then held with Makro•4Grip clamping jaws by form-fit. Four stamping inserts are required per pair of stamping jaws. Each stamping insert has a total of three cutting edges. If one cutting edge is worn out, two others can be used by turning them by 120 degrees and reassembling them.</p>', '<p><br></p>', NULL, NULL, '170532339265a52b8044a6751111-40[1].webp', NULL, 1, NULL, 0, '2024-01-15 19:56:32', '2024-03-01 02:07:17'),
('65a52c45a057e-pprt-76873471037803464', 'Hydro•Sup, Spacer', '81523-lang-hydrosup-spacer', '81523', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">This spacer, in conjunction with the Hydro•Sup hydraulic screw jack, provides an additional support surface at a height of between 228 and 232 mm and is, therefore, suitable for the “L” system height of Makro•Grip® Ultra. Together with the Hydro•Sup, it serves to reduce possible vibrations in the machining process of protruding components.</p>', '<p><br></p>', NULL, NULL, '170532358965a52c459ff9881523[1].webp', '[\"170532358965a52c45a020e81523_Seite_3[1].webp\",\"170532358965a52c45a033581523_Seite_2[1].webp\",\"170532358965a52c45a043c81523_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-15 19:59:49', '2024-03-01 02:07:17'),
('65a52d56dc935-pprt-85562374145695831', 'Hydro•Sup, Spacer', '81515-lang-hydrosup-spacer', '81515', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">This spacer, in conjunction with the Hydro•Sup hydraulic screw jack, provides an additional support surface at a height of between 148 and 152 mm and is, therefore, suitable for the “M” system height of Makro•Grip® Ultra. Together with the Hydro•Sup, it serves to reduce possible vibrations in the machining process of protruding components.</p>', '<p><br></p>', NULL, NULL, '170532386265a52d56dc38481515[1].webp', '[\"170532386265a52d56dc5d181515_Seite_3[1].webp\",\"170532386265a52d56dc70481515_Seite_2[1].webp\",\"170532386265a52d56dc7e781515_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-15 20:04:22', '2024-03-01 02:07:17'),
('65a52e5f0d9da-pprt-72973285938758470', 'Hydro•Sup, Hydraulic Screw Jack', '81586-lang-hydrosup-hydraulic-screw-jack', '81586', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The hydraulic screw jack Hydro•Sup serves to reduce possible vibrations in the machining process of protruding components. With a height of 86 mm, it matches the lowest system height of Makro•Grip® Ultra base body height. Due to its pendulum support, it offers a support height between 85 and 89 mm. By using additional spacers, the total height of the M and L systems can be replicated.</p>', '<p><br></p>', NULL, NULL, '170532412765a52e5f0d46081586[1].webp', '[\"170532412765a52e5f0d69d81586_Seite_3[1].webp\",\"170532412765a52e5f0d7a481586_Seite_2[1].webp\",\"170532412765a52e5f0d89781586_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-15 20:08:47', '2024-03-01 02:07:17'),
('65a541e7becb2-pprt-76741314163955876', 'Makro•Grip® Ultra, Connection Plate', '81015-lang-makrogrip-ultra-connection-plate', '81015', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Connection plates are needed to connect Makro-Grip® Ultra bodies together. They are attached to the rear base body and have a fit on the outside to accommodate a sliding block mounted on the outer end of the front base body. They are already included with the 610 and 810 Base Sets.</p>', '<p><br></p>', NULL, NULL, '170532912765a541e7be62681015[1].png', NULL, 1, NULL, 0, '2024-01-15 21:32:07', '2024-03-01 02:07:17'),
('65a545804869e-pprt-31541056344043245', 'Makro•Grip® Ultra, Centering Plate', '81010-lang-makrogrip-ultra-centering-plate', '81010', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Centring plates are used to provide a precise fit for the spindle unit of Makro-Grip® Ultra. Two centring plates are required per clamping unit, which are attached to the inner ends of the base bodies. This version is required for centric single part clamping and is included in the scope of delivery of the Base Sets.</p>', '<p><br></p>', NULL, NULL, '170533004865a545804843581010[1].webp', NULL, 1, NULL, 0, '2024-01-15 21:47:28', '2024-03-01 02:07:17'),
('65a5463d202de-pprt-13232862562592496', 'Makro•Grip® Ultra, Centering Plate', '81040-lang-makrogrip-ultra-centering-plate', '81040', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Centring plates are used to ensure a precise fit of the spindle unit of Makro-Grip® Ultra. Two centring plates are required per clamping unit, which are attached to the inner ends of the base bodies. This version is required for compensating multiple clamping and is characterised by a milled recess which gives the spindle unit additional clearance for components of different sizes (max. 4 mm).</p>', '<p><br></p>', NULL, NULL, '170533023765a5463d2008881040[1].webp', NULL, 1, NULL, 0, '2024-01-15 21:50:37', '2024-03-01 02:07:17'),
('65a54c615aea4-pprt-60762083671876198', 'Makro•Grip® Ultra, Spindle Unit', '81006-lang-makrogrip-ultra-spindle-unit', '81006', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The spindle unit for the Makro•Grip® Ultra is simply fitted between two high-precision centering plates, which is why the system is set up very quickly. By removing the clamping jaws, the spindle unit can be removed upwards, which makes cleaning after (and between) production processes extremely easy and convenient.</p>', '<p><br></p>', NULL, NULL, '170533180965a54c615abdb81006[1].webp', '[\"170533180965a54c615ad5581006_Seite_1.webp\"]', 1, NULL, 0, '2024-01-15 22:16:49', '2024-03-01 02:07:17'),
('65a54d2e1a98d-pprt-87507104211530154', 'Makro•Grip® Ultra, Spindle Unit', '81004-lang-makrogrip-ultra-spindle-unit', '81004', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The spindle unit for the Makro•Grip® Ultra is simply fitted between two high-precision centering plates, which is why the system is set up very quickly. By removing the clamping jaws, the spindle unit can be removed upwards, which makes cleaning after (and between) production processes extremely easy and convenient.</p>', '<p><br></p>', NULL, NULL, '170533201465a54d2e1a6e781004[1].webp', '[\"170533201465a54d2e1a86981004_Seite_1.webp\"]', 1, NULL, 0, '2024-01-15 22:20:14', '2024-03-01 02:07:17'),
('65a54dcc29ae7-pprt-21654328554463424', 'Makro•Grip® Ultra, Spindle Unit', '81008-lang-makrogrip-ultra-spindle-unit', '81008', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The spindle unit for the Makro•Grip® Ultra is simply fitted between two high-precision centering plates, which is why the system is set up very quickly. By removing the clamping jaws, the spindle unit can be removed upwards, which makes cleaning after (and between) production processes extremely easy and convenient.</p>', '<p><br></p>', NULL, NULL, '170533217265a54dcc297c581008[1].webp', '[\"170533217265a54dcc2998e81008_Seite_1.webp\"]', 1, NULL, 0, '2024-01-15 22:22:52', '2024-03-01 02:07:17'),
('65a54eb59afaa-pprt-35569391385089875', 'Makro•Grip® Ultra, Threaded Cap', '81090-lang-makrogrip-ultra-threaded-cap', '81090', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">One right and one left threaded cap are required per clamping unit. By removing the threaded cap with a magnetic handle, the position of the clamping jaw can be changed in an instant without operating the spindle by sliding it on the base body. The threaded cap is secured by two heavy-duty hexagonal screws, which can be opened and closed with a half turn.</p>', '<p><br></p>', NULL, NULL, '170533240565a54eb59acba81090[1]_0.webp', NULL, 1, NULL, 0, '2024-01-15 22:26:45', '2024-03-01 02:07:17'),
('65a54f390d692-pprt-23350241983728928', 'Makro•Grip® Ultra 125, Ultra Screw', '81251-48-lang-makrogrip-ultra-125-ultra-screw-2', '81251-48', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170533253765a54f390d3ac81251-48 (1).webp', NULL, 1, NULL, 0, '2024-01-15 22:28:57', '2024-03-01 02:07:17'),
('65a54fadf18df-pprt-43524480162546939', 'Makro•Grip® Ultra, Threaded Cap', '81080-lang-makrogrip-ultra-threaded-cap', '81080', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">One right and one left threaded cap are required per clamping unit. By removing the threaded cap with a magnetic handle, the position of the clamping jaw can be changed in an instant without operating the spindle by sliding it on the base body. The threaded cap is secured by two heavy-duty hexagonal screws, which can be opened and closed with a half turn.</p>', '<p><br></p>', NULL, NULL, '170533265365a54fadf169e81080[1].webp', NULL, 1, NULL, 0, '2024-01-15 22:30:53', '2024-03-01 02:07:17'),
('65a550b69adf9-pprt-26307288686340551', 'Quick•Point®, Centering Studs', '451250-lang-quickpoint-centering-studs', '451250', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">For the concentric alignment of our Quick•Point® plates via the respective hole on the underside (Ø 12 mm for single plates, Ø 25 mm for multiple grid plates 52, as well as Ø 50 mm for multiple grid plates 96 and Quick•Tower base plates), we offer you centering studs to match the most common hole diameters of the machine tables (Ø 30 mm, Ø 32 mm, Ø 50 mm).</p>', '<p><br></p>', NULL, NULL, '170533291865a550b69ac25451250.webp', NULL, 1, NULL, 0, '2024-01-15 22:35:18', '2024-03-01 02:07:17'),
('65a5518cc692a-pprt-67585531526088622', 'Quick•Point®, Centering Studs', '452530-lang-quickpoint-centering-studs', '452530', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">For the concentric alignment of our Quick•Point® plates via the respective hole on the underside (Ø 12 mm for single plates, Ø 25 mm for multiple grid plates 52, as well as Ø 50 mm for multiple grid plates 96 and Quick•Tower base plates), we offer you centering studs to match the most common hole diameters of the machine tables (Ø 30 mm, Ø 32 mm, Ø 50 mm).</p>', '<p><br></p>', NULL, NULL, '170533313265a5518cc6758452530.webp', NULL, 1, NULL, 0, '2024-01-15 22:38:52', '2024-03-01 02:07:17'),
('65a554b08493f-pprt-78959154189848019', 'Quick•Point®, Centering Studs', '455032-lang-quickpoint-centering-studs', '455032', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">For the concentric alignment of our Quick•Point® plates via the respective hole on the underside (Ø 12 mm for single plates, Ø 25 mm for multiple grid plates 52, as well as Ø 50 mm for multiple grid plates 96 and Quick•Tower base plates), we offer you centering studs to match the most common hole diameters of the machine tables (Ø 30 mm, Ø 32 mm, Ø 50 mm).</p>', '<p><br></p>', NULL, NULL, '170533393665a554b08465a455032.webp', NULL, 1, NULL, 0, '2024-01-15 22:52:16', '2024-03-01 02:07:17'),
('65a5557bcac08-pprt-35393386785680256', 'Quick•Point®, Centering Studs', '455050-lang-quickpoint-centering-studs', '455050', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">For the concentric alignment of our Quick•Point® plates via the respective hole on the underside (Ø 12 mm for single plates, Ø 25 mm for multiple grid plates 52, as well as Ø 50 mm for multiple grid plates 96 and Quick•Tower base plates), we offer you centering studs to match the most common hole diameters of the machine tables (Ø 30 mm, Ø 32 mm, Ø 50 mm).</p>', '<p><br></p>', NULL, NULL, '170533413965a5557bcaa23455050.webp', NULL, 1, NULL, 0, '2024-01-15 22:55:39', '2024-03-01 02:07:17'),
('65a556641805f-pprt-68956187772782603', 'Quick•Point®, Centering Studs', '451232-lang-quickpoint-centering-studs', '451232', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">For the concentric alignment of our Quick•Point® plates via the respective hole on the underside (Ø 12 mm for single plates, Ø 25 mm for multiple grid plates 52, as well as Ø 50 mm for multiple grid plates 96 and Quick•Tower base plates), we offer you centering studs to match the most common hole diameters of the machine tables (Ø 30 mm, Ø 32 mm, Ø 50 mm).</p>', '<p><br></p>', NULL, NULL, '170533437265a5566417e1a451232.webp', NULL, 1, NULL, 0, '2024-01-15 22:59:32', '2024-03-01 02:07:17'),
('65a5571c2eb0c-pprt-32684766707114343', 'Makro•Grip®, Cordless Drill Attachment', '47005-lang-makrogrip-cordless-drill-attachment', '47005', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170533455665a5571c2e8c447005[1].webp', NULL, 1, NULL, 0, '2024-01-15 23:02:36', '2024-03-01 02:07:17'),
('65a55844e9d2c-pprt-36134513823591185', 'Makro•Grip® Ultra, Hexagon Socket', '45511-lang-makrogrip-ultra-hexagon-socket-2', '45511', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The hexagon socket size 19 mm can be used to clamp the workpieces in the Makro•Grip® Ultra clamping system. Its 1/2” square drive fits standard torque wrenches. The maximum tightening torque of the Makro•Grip® Ultra spindle unit is 170 Nm.</p>', '<p><br></p>', NULL, NULL, '170533485265a55844e9b0445509 (2).webp', NULL, 1, NULL, 0, '2024-01-15 23:07:32', '2024-03-01 02:07:17'),
('65a5594bd1cfa-pprt-34971564372624077', 'Makro•Grip®, Hexagon Socket', '45508-lang-makrogrip-hexagon-socket', '45508', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The hexagon socket (DIN 3120) size 12 mm can be used to clamp workpieces in all LANG vises with a base body width of 77 mm. Its 3/8” square drive fits standard torque wrenches. The maximum tightening torque of the spindles of these vises is 70 Nm.</p>', '<p><br></p>', NULL, NULL, '170533511565a5594bd1b1e45508[1].webp', NULL, 1, NULL, 0, '2024-01-15 23:11:55', '2024-03-01 02:07:17'),
('65a559de23cb5-pprt-78968457307496291', 'Makro•Grip®, Hexagon Socket', '45509-lang-makrogrip-hexagon-socket', '45509', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The hexagon socket (DIN 3120) size 15 mm can be used to clamp workpieces in all LANG vises with a base body width of 125 mm. Its 3/8” square drive fits standard torque wrenches. The maximum tightening torque of the spindles of these vises is 100 Nm..</p>', '<p><br></p>', NULL, NULL, '170533526265a559de23a8745509[1].webp', NULL, 1, NULL, 0, '2024-01-15 23:14:22', '2024-03-01 02:07:17'),
('65a55a838f824-pprt-47425373252277819', 'Makro•Grip®, Cordless Drill Attachment', '47005-lang-makrogrip-cordless-drill-attachment-2', '47005', 'parts', '1', NULL, NULL, 1.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170533542765a55a838f63f47005[1] (1).webp', NULL, 1, NULL, 0, '2024-01-15 23:17:07', '2024-03-01 02:07:17'),
('65a55af738101-pprt-95952455688051591', 'Makro•Grip® Ultra, Wrench', '45519-lang-makrogrip-ultra-wrench-2', '45519', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The wrench with external hexagon and a size of 19 mm can be used for the initial clamping setting in the Makro•Grip® Ultra system. This wrench is already included in the scope of delivery of the Makro•Grip® Ultra base sets. However, the final clamping of the workpieces should be done with a torque wrench.</p>', '<p><br></p>', NULL, NULL, '170533554365a55af737f0945501 (1).webp', NULL, 1, NULL, 0, '2024-01-15 23:19:03', '2024-03-01 02:07:17');
INSERT INTO `product_part` (`id`, `name`, `slug`, `code`, `parts_type`, `brand_id`, `category_id`, `product_id`, `price`, `key_features`, `further_information`, `discount_type`, `discount`, `thumbnail`, `images`, `status`, `ancestor_id`, `short_number`, `created_at`, `updated_at`) VALUES
('65a55c8b453fd-pprt-96403012197130997', 'Makro•Grip®, Gauging Blocks', '41020-lang-makrogrip-gauging-blocks', '41020', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The scope of delivery of each stamping unit includes a set of gauging blocks with which the wear of the stamping jaw can be checked. This is important to ensure constant holding forces during workpiece clamping in the 5-axis vise and thus a high clamping quality. If it transpires that the stamping teeth are worn, they can be reworked up to six times.</p>', '<p><br></p>', NULL, NULL, '170533594765a55c8b4522a41020[1].webp', NULL, 1, NULL, 0, '2024-01-15 23:25:47', '2024-03-01 02:07:17'),
('65a55ef45deaf-pprt-20912570449368609', 'Makro•Grip®, Center Marking Tool', '41010-lang-makrogrip-center-marking-tool', '41010', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The center marking tool is mounted on the movable jaw of the stamping unit above the stamping jaw. With the center marking tool mounted, the raw material is simultaneously provided with a small indentation above to the stamping contour. This facilitates the exact central insertion of the workpiece blanks in the 5-axis vise, on whose scaled clamping jaws the “0” marking indicates the center. End stops are thus barely needed any more.</p>', '<p><br></p>', NULL, NULL, '170533656465a55ef45dca441010[1].webp', NULL, 1, NULL, 0, '2024-01-15 23:36:04', '2024-03-01 02:07:17'),
('65a55fc16afbc-pprt-10978790070792751', 'Makro•Grip®, Spare Stud', '41010-01-lang-makrogrip-spare-stud-2', '41010-01', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The replacement marking pin is an after-sale part of the center marking tool of the Makro•Grip® stamping unit. It is used to make a small indentation in the raw material during the stamping process to facilitate central insertion in the 5-axis vise, which means that workpiece end stops are barely needed any more.</p>', '<p><br></p>', NULL, NULL, '170533676965a55fc16ad7c41010-01[1] (1).webp', NULL, 1, NULL, 0, '2024-01-15 23:39:29', '2024-03-01 02:07:17'),
('65a56d3b8379c-pprt-57412663916591444', 'Quick•Point®, Bushings', '65191-04-lang-quickpoint-bushings', '65191-04', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Bushings are needed to position zero-point plates on the Quick•Point® riser, the 3- or 4-sided clamping tower or the Quick•Tower clamping tower for horizontal machining centers, to align them with a high level of precision and fasten them with M10 cylinder head screws.</p>', '<p><br></p>', NULL, NULL, '170534021965a56d3b8358e65191-04[1].webp', NULL, 1, NULL, 0, '2024-01-16 00:36:59', '2024-03-01 02:07:17'),
('65a56df808c3e-pprt-34165539636490237', 'Quick•Point®, Bushings', '45000-09-lang-quickpoint-bushings', '45000-09', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Bushings are needed to position zero-point plates on the Quick•Point® riser, the 3- or 4-sided clamping tower or the Quick•Tower clamping tower for horizontal machining centers, to align them with a high level of precision and fasten them with M10 cylinder head screws.</p>', '<p><br></p>', NULL, NULL, '170534040865a56df808a1145000-09[1].webp', NULL, 1, NULL, 0, '2024-01-16 00:40:08', '2024-03-01 02:07:17'),
('65a56ec38485b-pprt-72436987249928461', 'Quick•Point®, Bushings', '65191-05-lang-quickpoint-bushings', '65191-05', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Bushings are needed to position zero-point plates on the Quick•Point® riser, the 3- or 4-sided clamping tower or the Quick•Tower clamping tower for horizontal machining centers, to align them with a high level of precision and fasten them with M10 cylinder head screws.</p>', '<p><br></p>', NULL, NULL, '170534061165a56ec38454665191-05[1].webp', NULL, 1, NULL, 0, '2024-01-16 00:43:31', '2024-03-01 02:07:17'),
('65a56fff9bd20-pprt-26183622565736607', 'Quick•Point® 52, Handle Bar', '66605-lang-quickpoint-52-handle-bar-2', '66605', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The easy-to-use handle made of aluminium makes it easier to handle the Quick•Point® products when setting up and dismantling. Just like with the usual LANG clamping devises, the aluminium handle is clamped in the zero-point clamping system with two Quick•Point® clamping studs and is, therefore, particularly suitable for transporting heavier products, such as 5-axis risers or the zero-point automation devises.</p>', '<p><br></p>', NULL, NULL, '170534092765a56fff9ba2a66605[1] (1).webp', NULL, 1, NULL, 0, '2024-01-16 00:48:47', '2024-03-01 02:07:17'),
('65a570a1c96a6-pprt-84960007491807622', 'Quick•Point® 96, Handle Bar', '46081-lang-quickpoint-96-handle-bar-2', '46081', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The easy-to-use handle made of aluminium makes it easier to handle the Quick•Point® products when setting up and dismantling. Just like with the usual LANG clamping devises, the aluminium handle is clamped in the zero-point clamping system with two Quick•Point® clamping studs and is, therefore, particularly suitable for transporting heavier products, such as 5-axis risers or the zero-point automation devises.</p>', '<p><br></p>', NULL, NULL, '170534108965a570a1c940646081[1] (1).webp', NULL, 1, NULL, 0, '2024-01-16 00:51:29', '2024-03-01 02:07:17'),
('65a5724372af5-pprt-21260557122863031', 'Quick•Point®, Cover Plug Remover', '45000-30-lang-quickpoint-cover-plug-remover', '45000-30', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The Quick•Point® cover plug remover is a practical anodized aluminium handle with a magnet at its tip. This can be used to remove protective steel cover plugs from multiple grid plates, as well as the threaded caps from the clamping and base jaws of the Makro•Grip® Ultra clamping system, in order to use the vise jaws quick adjustment function on the system body.</p>', '<p><br></p>', NULL, NULL, '170534150765a57243728bb45000-30[1]_0.webp', NULL, 1, NULL, 0, '2024-01-16 00:58:27', '2024-03-01 02:07:17'),
('65a572dccb4ca-pprt-53774213828007828', 'Quick•Point® 52, Cover Plugs', '45052-30-lang-quickpoint-52-cover-plugs', '45052-30', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Protective steel cover plugs are supplied as standard with multiple grid plates. In addition to protecting against soiling when the clamping holes are not in use, they also serve to distribute the increased clamping force evenly in the case of multiple grid plates. They sit flush with the surface of the zero-point plate in the clamping holes and can be removed using the magnetic plug remover.</p>', '<p><br></p>', NULL, NULL, '170534166065a572dccb21e45052-30-neu.webp', NULL, 1, NULL, 0, '2024-01-16 01:01:00', '2024-03-01 02:07:17'),
('65a57358e168d-pprt-98545569411347061', 'Quick•Point® 96, Cover Plugs', '45096-30-lang-quickpoint-96-cover-plugs', '45096-30', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Protective steel cover plugs are supplied as standard with multiple grid plates. In addition to protecting against soiling when the clamping holes are not in use, they also serve to distribute the increased clamping force evenly in the case of multiple grid plates. They sit flush with the surface of the zero-point plate in the clamping holes and can be removed using the magnetic plug remover.</p>', '<p><br></p>', NULL, NULL, '170534178465a57358e146b45096-30-neu.webp', NULL, 1, NULL, 0, '2024-01-16 01:03:04', '2024-03-01 02:07:17'),
('65a57430aac64-pprt-72633930351185261', 'Quick•Point® 96, Cover Plugs', '45096-20-lang-quickpoint-96-cover-plugs', '45096-20', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Protective plastic cover plugs are supplied as standard with all Quick•Point® single plates, receiver plates, 5-axis risers, twin bases and clamping towers. They serve to protect the zero-point clamping holes from contamination when they are not occupied and can be easily removed by hand.</p>', '<p><br></p>', NULL, NULL, '170534200065a57430aa9ca45096-20[1].webp', NULL, 1, NULL, 0, '2024-01-16 01:06:40', '2024-03-01 02:07:17'),
('65a574bf38a88-pprt-60015222335837395', 'Quick•Point® 52, Cover Plugs', '45052-20-lang-quickpoint-52-cover-plugs', '45052-20', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">Protective plastic cover plugs are supplied as standard with all Quick•Point® single plates, receiver plates, 5-axis risers, twin bases and clamping towers. They serve to protect the zero-point clamping holes from contamination when they are not occupied and can be easily removed by hand.</p>', '<p><br></p>', NULL, NULL, '170534214365a574bf387d345052-20[1].webp', NULL, 1, NULL, 0, '2024-01-16 01:09:03', '2024-03-01 02:07:17'),
('65a575584c111-pprt-22378944105790441', 'Quick•Point®, Cover Discs', '45008-27-lang-quickpoint-cover-discs', '45008-27', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The predefined, as well as the customized mounting holes, which LANG can create at a later stage, are provided with a 2.1 mm deep countersink for inserting Quick•Point® plastic covers. They are available in diameters of 15, 20 and 27 mm and ensure that no swarf and chips can get into these holes and inside the zero-point plates.</p>', '<p><br></p>', NULL, NULL, '170534229665a575584bf2a45008-27.webp', NULL, 1, NULL, 0, '2024-01-16 01:11:36', '2024-03-01 02:07:17'),
('65a575eb8b5af-pprt-25978100711417639', 'Quick•Point®, Cover Discs', '45008-15-lang-quickpoint-cover-discs', '45008-15', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The predefined, as well as the customized mounting holes, which LANG can create at a later stage, are provided with a 2.1 mm deep countersink for inserting Quick•Point® plastic covers. They are available in diameters of 15, 20 and 27 mm and ensure that no swarf and chips can get into these holes and inside the zero-point plates.</p>', '<p><br></p>', NULL, NULL, '170534244365a575eb8b3f345008-15[1].webp', NULL, 1, NULL, 0, '2024-01-16 01:14:03', '2024-03-01 02:07:17'),
('65a57658e15aa-pprt-34715048107359993', 'Quick•Point®, Cover Discs', '45008-20-lang-quickpoint-cover-discs', '45008-20', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">The predefined, as well as the customized mounting holes, which LANG can create at a later stage, are provided with a 2.1 mm deep countersink for inserting Quick•Point® plastic covers. They are available in diameters of 15, 20 and 27 mm and ensure that no swarf and chips can get into these holes and inside the zero-point plates.</p>', '<p><br></p>', NULL, NULL, '170534255265a57658e13be45008-20.webp', NULL, 1, NULL, 0, '2024-01-16 01:15:52', '2024-03-01 02:07:17'),
('65a576f55207c-pprt-79000651130970494', 'Quick•Point® 52, Quick•Lock', '45452-lang-quickpoint-52-quicklock', '45452', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">As an alternative to the actuation screw, the 4-fold grid plate 52 can be conveniently operated with the Quick•Lock fastener. With a 180° closing movement of the Quick•Lock lever, the clamping effect of a clamping device in the zero-point clamping system is established and released, with identical clamping and holding forces as when actuated via the screw.</p>', '<p><br></p>', NULL, NULL, '170534270965a576f551d3245452.webp', '[\"170534270965a576f551f1345452_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-16 01:18:29', '2024-03-01 02:07:17'),
('65a577bbcae15-pprt-60314697319399575', 'Quick•Point® 96, Quick•Lock', '45996-lang-quickpoint-96-quicklock', '45996', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">As an alternative to the actuation screw, all round 2-fold grid plates 96 can be conveniently operated with the Quick•Lock fastener. With a 180° closing movement of the Quick•Lock lever, the clamping effect of a clamping device in the zero-point clamping system is established and released, with identical clamping and holding forces as when actuated via the screw.</p>', '<p><br></p>', NULL, NULL, '170534290765a577bbcaab645996.webp', '[\"170534290765a577bbcac9645996_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-16 01:21:47', '2024-03-01 02:07:17'),
('65a5787cecd7a-pprt-87465801426867058', 'Quick•Point®, Quick•Lock Clamping Lever', '44500-lang-quickpoint-quicklock-clamping-lever-2', '44500', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">With this clamping lever, the user conveniently operates the Quick-Lock quick-release fastener mounted on a zero-point plate with a 180° closing movement. This clamping lever is used for Quick-Lock quick-release fasteners mounted on single plates, twin bases, the adapter plate or 5-axis risers.</p>', '<p><br></p>', NULL, NULL, '170534310065a5787cecb6c44500[1] (1).webp', NULL, 1, NULL, 0, '2024-01-16 01:25:00', '2024-03-01 02:07:17'),
('65a578fedf7d3-pprt-45823443234728794', 'Quick•Point® 96, Quick•Lock', '45496-lang-quickpoint-96-quicklock', '45496', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">As an alternative to the actuation screw, all round 4-fold grid plates 96 can be conveniently operated with the Quick•Lock fastener. With a 180° closing movement of the Quick•Lock lever, the clamping effect of a clamping device in the zero-point clamping system is established and released, with identical clamping and holding forces as when actuated via the screw.</p>', '<p><br></p>', NULL, NULL, '170534323065a578fedf4d245496.webp', '[\"170534323065a578fedf6a345496_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-16 01:27:10', '2024-03-01 02:07:17'),
('65a579b105bc5-pprt-72493227647390491', 'Quick•Point® 52, Quick•Lock', '45252-lang-quickpoint-52-quicklock', '45252', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">As an alternative to the actuation screw, the 2-fold grid plate 52 can be conveniently operated with the Quick-Lock fastener. With a 180° closing movement of the Quick•Lock lever, the clamping effect of a clamping device in the zero•point clamping system is established and released, with identical clamping and holding forces as when actuated via the screw.</p>', '<p><br></p>', NULL, NULL, '170534340965a579b1058e945252.webp', '[\"170534340965a579b105aa045252_Seite_1[1].webp\"]', 1, NULL, 0, '2024-01-16 01:30:09', '2024-03-01 02:07:17'),
('65a57a89d1184-pprt-64554023564099125', 'Clean•Tec 330, Spare Part Kit', '30334-lang-cleantec-330-spare-part-kit', '30334', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If the blades of the Clean•Tec chip fan have become worn out over time, they can be replaced with new blades from the spare parts kit without having to buy a completely new chip fan. The spare parts kit also includes four springs and four locking pins.</p>', '<p><br></p>', NULL, NULL, '170534362565a57a89d0f7130334[1].webp', NULL, 1, NULL, 0, '2024-01-16 01:33:45', '2024-03-01 02:07:17'),
('65a57b179897a-pprt-76164327936349746', 'Clean•Tec 160, Spare Part Kit', '30164-lang-cleantec-160-spare-part-kit', '30164', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If the blades of the Clean•Tec chip fan have become worn out over time, they can be replaced with new blades from the spare parts kit without having to buy a completely new chip fan. The spare parts kit also includes four springs and four locking pins.</p>', '<p><br></p>', NULL, NULL, '170534376765a57b17986d430164[1].webp', NULL, 1, NULL, 0, '2024-01-16 01:36:07', '2024-03-01 02:07:17'),
('65a57b91d75f9-pprt-25296741568668562', 'Clean•Tec 260, Spare Part Kit', '30264-lang-cleantec-260-spare-part-kit', '30264', 'parts', '1', NULL, NULL, 1.00, '<p><div class=\"product-attributes__data-list\" style=\"box-sizing: inherit; margin-bottom: 36px; z-index: 1; position: relative; grid-column: 1 / 2; color: rgb(87, 87, 86); font-family: &quot;Encode Sans Compressed&quot;, sans-serif; font-size: 20px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: 0.8px; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(247, 247, 248); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><div class=\"data__item\" style=\"box-sizing: inherit; padding: 0px; border-bottom: none; display: grid; grid-template-columns: 50% 50%; gap: 7px; font-size: 1rem;\"></div></div></p><p class=\"product__text\" style=\"box-sizing: inherit; margin: 0px 0px 36px; z-index: 1; position: relative; grid-column: 1 / 2; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\">If the blades of the Clean•Tec chip fan have become worn out over time, they can be replaced with new blades from the spare parts kit without having to buy a completely new chip fan. The spare parts kit also includes four springs and four locking pins.</p>', '<p><br></p>', NULL, NULL, '170534388965a57b91d73fc30264[1].webp', NULL, 1, NULL, 0, '2024-01-16 01:38:09', '2024-03-01 02:07:17'),
('65c123982dd0d-pprt-38617413078081700', 'fxfx', 'xcvxcv-spd-fxfx', 'xcvxcv', 'parts', '653b407814c5d-brnd-30399065672704514', NULL, NULL, 11.00, '<p><br></p>', '<p><br></p>', NULL, NULL, '170715637665c123981e3bc5-achs-setup (1).jpg', NULL, 1, NULL, 0, '2024-02-06 01:06:16', '2024-03-01 02:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `qualification_type`
--

CREATE TABLE `qualification_type` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `qualification_type`
--

INSERT INTO `qualification_type` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Certification', 1, '2025-01-09 09:54:54', '2025-01-09 10:24:09'),
(9, 'Diploma', 1, '2025-01-09 10:22:53', '2025-01-09 10:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `company_id` varchar(256) DEFAULT NULL,
  `custom_data` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `question_type` varchar(40) NOT NULL DEFAULT 'single choice',
  `segmentation` text DEFAULT NULL,
  `difficulty_level` int(11) DEFAULT NULL,
  `exam_purpose` int(11) DEFAULT NULL,
  `media_type` int(11) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `marks` float(16,2) NOT NULL DEFAULT 0.00,
  `user_id` varchar(40) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `company_id`, `custom_data`, `title`, `question_type`, `segmentation`, `difficulty_level`, `exam_purpose`, `media_type`, `short_description`, `marks`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(133, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the primary role of variables in programming?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:41:36', '2024-12-02 10:41:55'),
(135, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following best describes a function\'s parameter?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:44:26', '2024-12-02 10:44:55'),
(136, '671b35c259c6e-comp-37685793406052083', NULL, 'What is a pointer typically used for in programming?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:47:47', '2024-12-02 10:52:56'),
(137, '671b35c259c6e-comp-37685793406052083', NULL, 'Which data structure operates on the principle of Last In, First Out (LIFO)?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:49:01', '2024-12-02 10:53:03'),
(140, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following data structures is best suited for implementing a priority queue?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:54:04', '2024-12-02 10:54:04'),
(141, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the time complexity of the best-case scenario for Quick Sort?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:55:01', '2024-12-02 10:55:01'),
(142, '671b35c259c6e-comp-37685793406052083', NULL, 'Which type of SQL join returns all records where there is a match in both tables?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:55:54', '2024-12-02 11:04:11'),
(143, '671b35c259c6e-comp-37685793406052083', NULL, 'What does 2NF (Second Normal Form) ensure in database normalization?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:56:54', '2024-12-02 10:56:54'),
(144, '671b35c259c6e-comp-37685793406052083', NULL, 'Which type of SQL subquery executes once for each row in the main query?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:57:39', '2024-12-02 10:57:39'),
(146, '671b35c259c6e-comp-37685793406052083', NULL, 'What is virtual memory in an operating system?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 10:59:26', '2024-12-02 10:59:26'),
(147, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the main difference between IPv4 and IPv6?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:00:27', '2024-12-02 11:00:27'),
(148, '671b35c259c6e-comp-37685793406052083', NULL, 'What layer of the OSI model is responsible for ensuring data is delivered error-free?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:01:28', '2024-12-02 11:01:28'),
(149, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the function of a VPN in networking?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:02:24', '2024-12-02 11:02:24'),
(150, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the purpose of REST APIs in web development?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:03:33', '2024-12-02 11:03:33'),
(151, '671b35c259c6e-comp-37685793406052083', NULL, 'What is phishing?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:05:25', '2024-12-02 11:05:25'),
(152, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following best describes a DDoS attack?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:07:24', '2024-12-02 11:07:32'),
(153, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the main difference between symmetric and asymmetric encryption?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:08:56', '2024-12-02 11:08:56'),
(154, '671b35c259c6e-comp-37685793406052083', NULL, 'What is a characteristic of a blockchain?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:10:01', '2024-12-02 11:10:01'),
(155, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the key principle of the Agile development model?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:10:46', '2024-12-02 11:10:46'),
(156, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the purpose of git pull in Git?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:11:58', '2024-12-02 11:30:59'),
(157, '671b35c259c6e-comp-37685793406052083', NULL, 'Which type of testing verifies the smallest pieces of code, such as functions or methods?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:12:49', '2024-12-02 11:12:49'),
(158, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following best describes a neural network in AI?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:13:44', '2024-12-02 11:13:44'),
(159, '671b35c259c6e-comp-37685793406052083', NULL, 'What is the key difference between supervised and unsupervised learning?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:14:48', '2024-12-02 11:22:51'),
(160, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following is NOT an example of a reinforcement learning algorithm?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:16:39', '2024-12-02 11:16:39'),
(161, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following is an example of a cloud service model?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:17:32', '2024-12-02 11:17:32'),
(162, '671b35c259c6e-comp-37685793406052083', NULL, 'What is one of the major advantages of cloud computing over traditional computing?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:19:04', '2024-12-02 11:19:04'),
(163, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following is a characteristic of containerization (e.g., Docker)?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:19:58', '2024-12-02 11:19:58'),
(164, '671b35c259c6e-comp-37685793406052083', NULL, 'Which of the following best describes the role of virtualization in cloud computing?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:20:47', '2024-12-02 11:20:47'),
(165, '671b35c259c6e-comp-37685793406052083', NULL, 'What is age range to participant in ICT Olympiad Bangladesh Season 2?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:22:39', '2024-12-02 11:22:57'),
(166, '671b35c259c6e-comp-37685793406052083', NULL, 'Which is NOT a segment of ICT Olympiad Bangladesh Season 2?', '2', '[\"6\"]', 3, 9, 10, NULL, 1.00, NULL, 1, '2024-12-02 11:24:44', '2024-12-02 11:24:44');

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` varchar(40) NOT NULL,
  `title` text NOT NULL,
  `is_correct` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `title`, `is_correct`, `created_at`, `updated_at`) VALUES
(681, '133', 'To execute a program', 0, '2024-12-02 10:41:55', '2024-12-02 10:41:55'),
(682, '133', 'To store data values', 1, '2024-12-02 10:41:55', '2024-12-02 10:41:55'),
(683, '133', 'To format text output', 0, '2024-12-02 10:41:55', '2024-12-02 10:41:55'),
(684, '133', 'To define a program\'s structure', 0, '2024-12-02 10:41:55', '2024-12-02 10:41:55'),
(697, '135', 'The data type of the function', 0, '2024-12-02 10:44:55', '2024-12-02 10:44:55'),
(698, '135', 'The value returned by the function', 0, '2024-12-02 10:44:55', '2024-12-02 10:44:55'),
(699, '135', 'An input passed to the function', 1, '2024-12-02 10:44:55', '2024-12-02 10:44:55'),
(700, '135', 'The error code generated by the function', 0, '2024-12-02 10:44:55', '2024-12-02 10:44:55'),
(717, '136', 'Storing fixed-length strings', 0, '2024-12-02 10:52:56', '2024-12-02 10:52:56'),
(718, '136', 'Accessing memory locations directly', 1, '2024-12-02 10:52:56', '2024-12-02 10:52:56'),
(719, '136', 'Performing arithmetic operations', 0, '2024-12-02 10:52:56', '2024-12-02 10:52:56'),
(720, '136', 'Declaring constant values', 0, '2024-12-02 10:52:56', '2024-12-02 10:52:56'),
(721, '137', 'Queue', 0, '2024-12-02 10:53:03', '2024-12-02 10:53:03'),
(722, '137', 'Stack', 1, '2024-12-02 10:53:03', '2024-12-02 10:53:03'),
(723, '137', 'Linked list', 0, '2024-12-02 10:53:03', '2024-12-02 10:53:03'),
(724, '137', 'Tree', 0, '2024-12-02 10:53:03', '2024-12-02 10:53:03'),
(725, '140', 'Array', 0, '2024-12-02 10:54:04', '2024-12-02 10:54:04'),
(726, '140', 'Stack', 0, '2024-12-02 10:54:04', '2024-12-02 10:54:04'),
(727, '140', 'Heap', 1, '2024-12-02 10:54:04', '2024-12-02 10:54:04'),
(728, '140', 'Linked list', 0, '2024-12-02 10:54:04', '2024-12-02 10:54:04'),
(729, '141', 'O(n2)', 0, '2024-12-02 10:55:01', '2024-12-02 10:55:01'),
(730, '141', 'O(n)', 0, '2024-12-02 10:55:01', '2024-12-02 10:55:01'),
(731, '141', 'O(n log n)', 1, '2024-12-02 10:55:01', '2024-12-02 10:55:01'),
(732, '141', 'O(log n)', 0, '2024-12-02 10:55:01', '2024-12-02 10:55:01'),
(737, '143', 'There are no duplicate rows in a table', 0, '2024-12-02 10:56:54', '2024-12-02 10:56:54'),
(738, '143', 'There are no partial dependencies on the primary key', 1, '2024-12-02 10:56:54', '2024-12-02 10:56:54'),
(739, '143', 'All non-key attributes depend only on the primary key', 0, '2024-12-02 10:56:54', '2024-12-02 10:56:54'),
(740, '143', 'The table has a single column as a primary key', 0, '2024-12-02 10:56:54', '2024-12-02 10:56:54'),
(741, '144', 'Scalar subquery', 0, '2024-12-02 10:57:39', '2024-12-02 10:57:39'),
(742, '144', 'Correlated subquery', 1, '2024-12-02 10:57:39', '2024-12-02 10:57:39'),
(743, '144', 'Independent subquery', 0, '2024-12-02 10:57:39', '2024-12-02 10:57:39'),
(744, '144', 'Inline subquery', 0, '2024-12-02 10:57:39', '2024-12-02 10:57:39'),
(749, '146', 'A portion of RAM that is used for file storage', 0, '2024-12-02 10:59:26', '2024-12-02 10:59:26'),
(750, '146', 'A memory management technique that uses disk space as RAM', 1, '2024-12-02 10:59:26', '2024-12-02 10:59:26'),
(751, '146', 'A permanent storage system', 0, '2024-12-02 10:59:26', '2024-12-02 10:59:26'),
(752, '146', 'A type of cache memory', 0, '2024-12-02 10:59:26', '2024-12-02 10:59:26'),
(753, '147', 'IPv4 uses 64-bit addresses, while IPv6 uses 128-bit addresses.', 0, '2024-12-02 11:00:27', '2024-12-02 11:00:27'),
(754, '147', 'IPv4 has 32-bit addresses, while IPv6 has 128-bit addresses.', 1, '2024-12-02 11:00:27', '2024-12-02 11:00:27'),
(755, '147', 'IPv4 is faster than IPv6.', 0, '2024-12-02 11:00:27', '2024-12-02 11:00:27'),
(756, '147', 'IPv4 is only used in private networks, while IPv6 is public.', 0, '2024-12-02 11:00:27', '2024-12-02 11:00:27'),
(757, '148', 'Physical layer', 0, '2024-12-02 11:01:28', '2024-12-02 11:01:28'),
(758, '148', 'Transport layer', 1, '2024-12-02 11:01:28', '2024-12-02 11:01:28'),
(759, '148', 'Network layer', 0, '2024-12-02 11:01:28', '2024-12-02 11:01:28'),
(760, '148', 'Application layer', 0, '2024-12-02 11:01:28', '2024-12-02 11:01:28'),
(761, '149', 'To create a secure connection over an untrusted network', 1, '2024-12-02 11:02:24', '2024-12-02 11:02:24'),
(762, '149', 'To assign static IP addresses', 0, '2024-12-02 11:02:24', '2024-12-02 11:02:24'),
(763, '149', 'To compress files before transmission', 0, '2024-12-02 11:02:24', '2024-12-02 11:02:24'),
(764, '149', 'To improve network speed', 0, '2024-12-02 11:02:24', '2024-12-02 11:02:24'),
(765, '150', 'To store CSS stylesheets', 0, '2024-12-02 11:03:33', '2024-12-02 11:03:33'),
(766, '150', 'To provide a standard for managing data between a client and a server', 1, '2024-12-02 11:03:33', '2024-12-02 11:03:33'),
(767, '150', 'To ensure webpages are accessible on mobile devices', 0, '2024-12-02 11:03:33', '2024-12-02 11:03:33'),
(768, '150', 'To manage domain names for websites', 0, '2024-12-02 11:03:33', '2024-12-02 11:03:33'),
(769, '142', 'LEFT JOIN', 0, '2024-12-02 11:04:11', '2024-12-02 11:04:11'),
(770, '142', 'INNER JOIN', 0, '2024-12-02 11:04:11', '2024-12-02 11:04:11'),
(771, '142', 'RIGHT JOIN', 0, '2024-12-02 11:04:11', '2024-12-02 11:04:11'),
(772, '142', 'FULL OUTER JOIN', 0, '2024-12-02 11:04:11', '2024-12-02 11:04:11'),
(781, '152', 'Unauthorized access to a computer system', 0, '2024-12-02 11:07:32', '2024-12-02 11:07:32'),
(782, '152', 'Sending a large volume of traffic to a server to disrupt its service', 1, '2024-12-02 11:07:32', '2024-12-02 11:07:32'),
(783, '152', 'Encrypting data to make it inaccessible', 0, '2024-12-02 11:07:32', '2024-12-02 11:07:32'),
(784, '152', 'Stealing sensitive information using social engineering', 0, '2024-12-02 11:07:32', '2024-12-02 11:07:32'),
(785, '153', 'Symmetric encryption uses two keys, while asymmetric uses one key.', 0, '2024-12-02 11:08:56', '2024-12-02 11:08:56'),
(786, '153', 'Symmetric encryption uses the same key for encryption and decryption, while asymmetric uses a public and private key pair.', 1, '2024-12-02 11:08:56', '2024-12-02 11:08:56'),
(787, '153', 'Symmetric encryption is slower than asymmetric encryption.', 0, '2024-12-02 11:08:56', '2024-12-02 11:08:56'),
(788, '153', 'Symmetric encryption is used for digital signatures, while asymmetric encryption is not.', 0, '2024-12-02 11:08:56', '2024-12-02 11:08:56'),
(793, '155', 'Strict sequential development phases', 0, '2024-12-02 11:10:46', '2024-12-02 11:10:46'),
(794, '155', 'Delivering small, incremental changes frequently', 1, '2024-12-02 11:10:46', '2024-12-02 11:10:46'),
(795, '155', 'Comprehensive documentation before development', 0, '2024-12-02 11:10:46', '2024-12-02 11:10:46'),
(796, '155', 'Delayed testing after the project is completed', 0, '2024-12-02 11:10:46', '2024-12-02 11:10:46'),
(801, '157', 'Unit testing', 1, '2024-12-02 11:12:49', '2024-12-02 11:12:49'),
(802, '157', 'Integration testing', 0, '2024-12-02 11:12:49', '2024-12-02 11:12:49'),
(803, '157', 'System testing', 0, '2024-12-02 11:12:49', '2024-12-02 11:12:49'),
(804, '157', 'Regression testing', 0, '2024-12-02 11:12:49', '2024-12-02 11:12:49'),
(805, '158', 'A mathematical function used for calculating probabilities', 0, '2024-12-02 11:13:44', '2024-12-02 11:13:44'),
(806, '158', 'A network of interconnected nodes that attempts to mimic the human brain\'s structure', 1, '2024-12-02 11:13:44', '2024-12-02 11:13:44'),
(807, '158', 'A hardware device for processing AI algorithms', 0, '2024-12-02 11:13:44', '2024-12-02 11:13:44'),
(808, '158', 'A database used for storing machine learning models', 0, '2024-12-02 11:13:44', '2024-12-02 11:13:44'),
(817, '161', 'SaaS', 1, '2024-12-02 11:17:32', '2024-12-02 11:17:32'),
(818, '161', 'TCP/IP', 0, '2024-12-02 11:17:32', '2024-12-02 11:17:32'),
(819, '161', 'HTML', 0, '2024-12-02 11:17:32', '2024-12-02 11:17:32'),
(820, '161', 'SQL', 0, '2024-12-02 11:17:32', '2024-12-02 11:17:32'),
(821, '162', 'Unlimited hardware requirements', 0, '2024-12-02 11:19:04', '2024-12-02 11:19:04'),
(822, '162', 'Ability to store data indefinitely without costs', 0, '2024-12-02 11:19:04', '2024-12-02 11:19:04'),
(823, '162', 'Scalability and flexibility', 1, '2024-12-02 11:19:04', '2024-12-02 11:19:04'),
(824, '162', 'Higher levels of security by default', 0, '2024-12-02 11:19:04', '2024-12-02 11:19:04'),
(825, '163', 'Containers are operating system-dependent', 0, '2024-12-02 11:19:58', '2024-12-02 11:19:58'),
(826, '163', 'Containers are designed to run on virtual machines only', 0, '2024-12-02 11:19:58', '2024-12-02 11:19:58'),
(827, '163', 'Containers allow for quick and isolated application deployment', 1, '2024-12-02 11:19:58', '2024-12-02 11:19:58'),
(828, '163', 'Containers use a large amount of system resources', 0, '2024-12-02 11:19:58', '2024-12-02 11:19:58'),
(829, '164', 'Virtualization increases the cost of cloud services', 0, '2024-12-02 11:20:47', '2024-12-02 11:20:47'),
(830, '164', 'Virtualization restricts the use of cloud resources', 0, '2024-12-02 11:20:47', '2024-12-02 11:20:47'),
(831, '164', 'Virtualization is used to back up data to physical servers', 0, '2024-12-02 11:20:47', '2024-12-02 11:20:47'),
(832, '164', 'Virtualization allows multiple operating systems to run on the same physical machine', 1, '2024-12-02 11:20:47', '2024-12-02 11:20:47'),
(837, '159', 'Supervised learning uses labeled data, while unsupervised learning uses unlabeled data', 1, '2024-12-02 11:22:51', '2024-12-02 11:22:51'),
(838, '159', 'Supervised learning is slower than unsupervised learning', 0, '2024-12-02 11:22:51', '2024-12-02 11:22:51'),
(839, '159', 'Unsupervised learning requires more data than supervised learning', 0, '2024-12-02 11:22:51', '2024-12-02 11:22:51'),
(840, '159', 'Supervised learning does not use algorithms', 0, '2024-12-02 11:22:51', '2024-12-02 11:22:51'),
(841, '165', '11 - 40', 0, '2024-12-02 11:22:57', '2024-12-02 11:22:57'),
(842, '165', '11 - 24', 0, '2024-12-02 11:22:57', '2024-12-02 11:22:57'),
(843, '165', '5 - 35', 1, '2024-12-02 11:22:57', '2024-12-02 11:22:57'),
(844, '165', '5 - 25', 0, '2024-12-02 11:22:57', '2024-12-02 11:22:57'),
(845, '166', 'Cloud computing', 0, '2024-12-02 11:24:44', '2024-12-02 11:24:44'),
(846, '166', 'Facebook monetization', 1, '2024-12-02 11:24:44', '2024-12-02 11:24:44'),
(847, '166', 'Tech entrepreneurship', 0, '2024-12-02 11:24:44', '2024-12-02 11:24:44'),
(848, '166', 'Block chain', 0, '2024-12-02 11:24:44', '2024-12-02 11:24:44'),
(849, '156', 'To upload local changes to the remote repository', 0, '2024-12-02 11:30:59', '2024-12-02 11:30:59'),
(850, '156', 'To fetch and merge changes from the remote repository into the local branch', 1, '2024-12-02 11:30:59', '2024-12-02 11:30:59'),
(851, '156', 'To initialize a new Git repository', 0, '2024-12-02 11:30:59', '2024-12-02 11:30:59'),
(852, '156', 'To stage changes for a commit', 0, '2024-12-02 11:30:59', '2024-12-02 11:30:59'),
(853, '151', 'A type of firewall configuration', 0, '2024-12-02 11:34:52', '2024-12-02 11:34:52'),
(854, '151', 'A cyberattack where fake websites or emails are used to steal personal information', 1, '2024-12-02 11:34:52', '2024-12-02 11:34:52'),
(855, '151', 'A type of malware that locks files until a ransom is paid', 0, '2024-12-02 11:34:52', '2024-12-02 11:34:52'),
(856, '151', 'A secure method of data encryption', 0, '2024-12-02 11:34:52', '2024-12-02 11:34:52'),
(857, '160', 'Q-learning', 0, '2024-12-02 11:49:54', '2024-12-02 11:49:54'),
(858, '160', 'Deep Q-network (DQN)', 0, '2024-12-02 11:49:54', '2024-12-02 11:49:54'),
(859, '160', 'K-means clustering', 1, '2024-12-02 11:49:54', '2024-12-02 11:49:54'),
(860, '160', 'SARSA (State-Action-Reward-State-Action)', 0, '2024-12-02 11:49:54', '2024-12-02 11:49:54'),
(861, '154', 'Data is stored in centralized servers', 0, '2024-12-02 11:50:53', '2024-12-02 11:50:53'),
(862, '154', 'It ensures that once data is added, it cannot be easily altered.', 1, '2024-12-02 11:50:53', '2024-12-02 11:50:53'),
(863, '154', 'It does not use encryption for data security.', 0, '2024-12-02 11:50:53', '2024-12-02 11:50:53'),
(864, '154', 'Transactions are verified only by a single authority.', 0, '2024-12-02 11:50:53', '2024-12-02 11:50:53');

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE `resource` (
  `id` int(11) NOT NULL,
  `type` varchar(40) NOT NULL,
  `image` varchar(256) NOT NULL,
  `title` text DEFAULT NULL,
  `title_color` varchar(20) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `details_color` varchar(20) DEFAULT NULL,
  `button_text` varchar(256) DEFAULT NULL,
  `button_color` varchar(20) DEFAULT NULL,
  `link` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `short_number` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `type`, `image`, `title`, `title_color`, `details`, `details_color`, `button_text`, `button_color`, `link`, `status`, `created_at`, `updated_at`, `short_number`) VALUES
(1, 'partner', '168657272064870eb0f24d2kisslig_logo.png', 'KISSLIG', '#000000', '', '#088395', '', '#088395', '#', 1, '2023-06-12 06:25:20', '2024-01-18 22:29:45', 0),
(4, 'partner', '168836422364a264bf0a0b8ar-filtrazioni-logo.png', 'AR FILTRAZIONI', NULL, '', NULL, '', NULL, '#', 1, '2023-07-03 00:03:43', '2023-07-03 00:05:48', 0),
(5, 'partner', '168836439364a26569b43fdlang-thumb-logo-300x300.png', 'LANG', '#000000', '', '#088395', '', '#088395', '#', 1, '2023-07-03 00:06:33', '2024-01-02 22:37:22', 0),
(6, 'partner', '168836444264a2659a7825bUntitled-design-10.png', 'AUTOGRIP', NULL, '', NULL, '', NULL, '#', 1, '2023-07-03 00:07:22', '2023-07-03 00:07:22', 0),
(7, 'partner', '168836447764a265bdc89c1spd_logo.png', 'SPD', NULL, '', NULL, '', NULL, '#', 1, '2023-07-03 00:07:57', '2023-07-03 00:07:57', 0),
(8, 'partner', '168836454364a265ff591e1brisc_logo.png', 'BRISC', NULL, '', NULL, '', NULL, '#', 1, '2023-07-03 00:09:03', '2023-07-03 00:09:03', 0),
(9, 'partner', '168836456064a26610ed332pintec-logo.jpg', 'PINTEC', NULL, '', NULL, '', NULL, '#', 1, '2023-07-03 00:09:20', '2023-07-03 00:09:20', 0),
(10, 'partner', '168836458264a26626a02a6cgm_logo.png', 'CGM', NULL, '', NULL, '', NULL, '#', 1, '2023-07-03 00:09:42', '2023-07-03 00:09:42', 0),
(11, 'partner', '168836460064a266386cc32ok-vise_logo.png', 'OK-VISE', NULL, '', NULL, '', NULL, '#', 1, '2023-07-03 00:10:00', '2023-07-03 00:10:00', 0),
(30, 'banner', '1701839431657002478dd5a1695206283650acb8b4a70cSlide 1050 X 420 - N-03.png', 'RoboTrex', '#ffffff', 'RoboTrex Automation is flexible, easy to use and can be set up in a very short time. From single-part production to larger batches, it covers every need.', '#dedede', 'View Products', '#088395', '/category/65127f71bafab-ctgr-82883789212911697', 1, '2023-09-20 04:38:03', '2024-01-18 22:30:17', 2),
(32, 'banner', '170183944065700250da4b616959863886516b2d45b1d0Slide 1050 X 420 - N-02.png', 'LANG Technik', '#ffffff', '', '#088395', 'View Products', '#088296', '/brand/1', 1, '2023-09-29 18:19:48', '2024-01-05 21:37:31', 1),
(37, 'banner', '17018394506570025a153e91701759127656ec897c3e3fSlide-1050-X-420---Auto-Grip.png', 'Auto Grip', '#ffffff', '', '#088395', 'View Products', '#088395', '/brand/653b40cbb988a-brnd-61212735378470921', 1, '2023-12-05 13:52:07', '2024-01-05 21:38:15', 5),
(38, 'banner', '170183945865700262c3ec71701759177656ec8c991dcbSlide-1050-X-420---CMM-Workholding.png', 'Pintec - CMM Workholding', '#ffffff', '', '#088395', 'View Products', '#088395', '/brand/653b40ed2bbbd-brnd-56085618647099342', 1, '2023-12-05 13:52:57', '2024-01-05 21:38:07', 4),
(46, 'banner', '17029049276580445f4e18cSlide-1050-X-420---AR-Filtrazioni.png', 'AR Filtariozini', '#ffffff', '', '#088395', 'View Products', '#088395', 'brand/653b4579b3e3b-brnd-38786264451941358', 1, '2023-12-18 20:06:43', '2024-01-05 21:38:28', 6),
(48, 'banner', '170298152665816f9646a84Slide-1050-X-420---Ok-Vice.png', 'OK Vise', '#ffffff', '', '#088395', 'View Products', '#088395', 'https://demo.machinetoolsolutions.ca/brand/653b46614148c-brnd-14428085574324353', 1, '2023-12-19 17:25:26', '2024-01-05 22:03:53', 7);

-- --------------------------------------------------------

--
-- Table structure for table `right`
--

CREATE TABLE `right` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `module` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `right`
--

INSERT INTO `right` (`id`, `name`, `module`, `created_at`, `updated_at`) VALUES
(1, 'role.view', 'role', '2023-05-09 22:11:19', '2023-05-09 22:17:58'),
(2, 'role.create', 'role', '2023-05-09 22:11:44', '2023-05-09 22:17:58'),
(3, 'role.edit', 'role', '2023-05-09 22:11:44', '2023-05-09 22:17:58'),
(4, 'role.delete', 'role', '2023-05-09 22:11:44', '2023-05-09 22:17:58'),
(5, 'user.view', 'user', '2023-05-09 22:12:49', '2023-05-09 22:18:12'),
(6, 'user.create', 'user', '2023-05-09 22:12:49', '2023-05-09 22:18:12'),
(7, 'user.edit', 'user', '2023-05-09 22:12:49', '2023-05-09 22:18:12'),
(8, 'user.delete', 'user', '2023-05-09 22:12:49', '2023-05-09 22:18:12'),
(9, 'dashboard.view', 'dashboard', '2023-05-09 22:13:06', '2023-05-09 22:18:25'),
(10, 'dashboard.create', 'dashboard', '2023-05-09 22:13:06', '2023-05-09 22:18:25'),
(11, 'dashboard.edit', 'dashboard', '2023-05-09 22:13:06', '2023-05-09 22:18:25'),
(12, 'dashboard.delete', 'dashboard', '2023-05-09 22:13:06', '2023-05-09 22:18:25'),
(13, 'category.view', 'category', '2023-05-09 22:13:23', '2023-05-09 22:18:35'),
(14, 'category.create', 'category', '2023-05-09 22:13:23', '2023-05-09 22:18:35'),
(15, 'category.edit', 'category', '2023-05-09 22:13:23', '2023-05-09 22:18:35'),
(16, 'category.delete', 'category', '2023-05-09 22:13:23', '2023-05-09 22:18:35'),
(17, 'course.view', 'course', '2023-05-09 22:13:32', '2023-05-09 22:18:44'),
(18, 'course.create', 'course', '2023-05-09 22:13:32', '2023-05-09 22:18:44'),
(19, 'course.edit', 'course', '2023-05-09 22:13:32', '2023-05-09 22:18:44'),
(20, 'course.delete', 'course', '2023-05-09 22:13:32', '2023-05-09 22:18:44'),
(23, 'right.view', 'right', '2023-05-16 06:21:07', '2023-05-16 06:21:07'),
(24, 'right.create', 'right', '2023-05-16 06:21:20', '2023-05-16 06:21:20'),
(25, 'right.edit', 'right', '2023-05-16 06:21:28', '2023-05-16 06:21:28'),
(26, 'right.delete', 'right', '2023-05-16 06:21:36', '2023-05-16 06:21:36'),
(27, 'partner.view', 'partner', '2023-05-17 04:27:42', '2023-05-17 04:27:42'),
(28, 'partner.create', 'partner', '2023-05-17 04:27:53', '2023-05-17 04:27:53'),
(29, 'partner.edit', 'partner', '2023-05-17 04:28:00', '2023-05-17 04:28:00'),
(30, 'partner.delete', 'partner', '2023-05-17 04:28:11', '2023-05-17 04:28:11'),
(31, 'partnerproduct.view', 'partnerproduct', '2023-05-21 01:34:57', '2023-05-21 01:34:57'),
(32, 'partnerproduct.create', 'partnerproduct', '2023-05-21 01:35:15', '2023-05-21 01:35:15'),
(33, 'partnerproduct.edit', 'partnerproduct', '2023-05-21 01:35:24', '2023-05-21 01:35:24'),
(34, 'partnerproduct.delete', 'partnerproduct', '2023-05-21 01:35:35', '2023-05-21 01:35:35'),
(35, 'setting.view', 'setting', '2023-05-21 23:31:21', '2023-05-21 23:31:21'),
(37, 'setting.edit', 'setting', '2023-05-21 23:32:15', '2023-05-21 23:32:15'),
(38, 'setting.general', 'setting', '2023-05-21 23:32:50', '2023-05-21 23:32:50'),
(39, 'setting.static-content', 'setting', '2023-05-21 23:51:51', '2023-05-21 23:51:51'),
(40, 'part.view', 'part', '2023-05-22 05:44:55', '2023-05-22 05:44:55'),
(41, 'part.create', 'part', '2023-05-22 05:45:01', '2023-05-22 05:45:01'),
(42, 'part.edit', 'part', '2023-05-22 05:45:08', '2023-05-22 05:45:08'),
(43, 'part.delete', 'part', '2023-05-22 05:45:15', '2023-05-22 05:45:15'),
(44, 'service.view', 'service', '2023-05-23 06:34:38', '2023-05-23 06:34:38'),
(45, 'service.create', 'service', '2023-05-23 06:34:45', '2023-05-23 06:34:45'),
(46, 'service.edit', 'service', '2023-05-23 06:34:53', '2023-05-23 06:34:53'),
(47, 'service.delete', 'service', '2023-05-23 06:35:00', '2023-05-23 06:35:00'),
(48, 'enroll.view', 'enroll', '2023-05-24 01:57:26', '2023-05-24 01:57:26'),
(49, 'enroll.edit', 'enroll', '2023-05-24 01:57:32', '2023-05-24 01:57:32'),
(50, 'enroll.create', 'enroll', '2023-05-24 01:57:38', '2023-05-24 01:57:38'),
(51, 'enroll.delete', 'enroll', '2023-05-24 01:57:45', '2023-05-24 01:57:45'),
(52, 'inquiry.view', 'inquiry', '2023-05-25 05:16:52', '2023-05-25 05:16:52'),
(53, 'inquiry.edit', 'inquiry', '2023-05-25 05:16:59', '2023-05-25 05:16:59'),
(54, 'inquiry.create', 'inquiry', '2023-05-25 05:17:05', '2023-05-25 05:17:05'),
(55, 'inquiry.delete', 'inquiry', '2023-05-25 05:17:11', '2023-05-25 05:17:11'),
(56, 'service-order.view', 'service-order', '2023-05-28 23:25:54', '2023-05-28 23:25:54'),
(57, 'service-order.edit', 'service-order', '2023-05-28 23:26:05', '2023-05-28 23:26:05'),
(58, 'service-order.create', 'service-order', '2023-05-28 23:26:12', '2023-05-28 23:26:12'),
(59, 'service-order.delete', 'service-order', '2023-05-28 23:26:21', '2023-05-28 23:26:21'),
(60, 'news.view', 'news', '2023-06-06 22:56:51', '2023-06-06 22:56:51'),
(61, 'news.edit', 'news', '2023-06-06 22:56:58', '2023-06-06 22:56:58'),
(62, 'news.create', 'news', '2023-06-06 22:57:05', '2023-06-06 22:57:05'),
(63, 'news.delete', 'news', '2023-06-06 22:57:14', '2023-06-06 22:57:14'),
(64, 'catalogue.view', 'catalogue', '2023-06-07 22:23:17', '2023-06-07 22:23:17'),
(65, 'catalogue.edit', 'catalogue', '2023-06-07 22:23:23', '2023-06-07 22:23:23'),
(66, 'catalogue.create', 'catalogue', '2023-06-07 22:23:32', '2023-06-07 22:23:32'),
(67, 'catalogue.delete', 'catalogue', '2023-06-07 22:23:39', '2023-06-07 22:23:39'),
(68, 'resource.view', 'resource', '2023-06-12 04:35:12', '2023-06-12 04:35:12'),
(69, 'resource.edit', 'resource', '2023-06-12 04:35:19', '2023-06-12 04:35:19'),
(70, 'resource.create', 'resource', '2023-06-12 04:35:26', '2023-06-12 04:35:26'),
(71, 'resource.delete', 'resource', '2023-06-12 04:35:33', '2023-06-12 04:35:33'),
(72, 'contact.view', 'contact', '2023-06-12 22:40:11', '2023-06-12 22:40:11'),
(73, 'contact.edit', 'contact', '2023-06-12 22:40:18', '2023-06-12 22:40:18'),
(74, 'contact.create', 'contact', '2023-06-12 22:40:25', '2023-06-12 22:40:25'),
(75, 'contact.delete', 'contact', '2023-06-12 22:40:44', '2023-06-12 22:40:44'),
(76, 'setting.legal-content', 'setting', '2023-07-03 01:10:19', '2023-07-03 01:10:19'),
(78, 'service-order.status', 'service-order', '2023-07-14 02:13:16', '2023-07-14 02:13:16'),
(79, 'transaction.view', 'transaction', '2023-08-11 07:31:49', '2023-08-11 07:31:49'),
(80, 'transaction.create', 'transaction', '2023-08-11 07:31:55', '2023-08-11 07:32:43'),
(81, 'transaction.edit', 'transaction', '2023-08-11 07:32:12', '2023-08-11 07:32:33'),
(82, 'transaction.delete', 'transaction', '2023-08-11 07:32:22', '2023-08-11 07:32:22'),
(83, 'custom-field.view', 'custom-field', '2023-08-22 01:20:47', '2023-08-22 01:20:47'),
(84, 'custom-field.create', 'custom-field', '2023-08-22 01:20:58', '2023-08-22 01:20:58'),
(85, 'custom-field.edit', 'custom-field', '2023-08-22 01:21:07', '2023-08-22 01:21:07'),
(86, 'custom-field.delete', 'custom-field', '2023-08-22 01:21:15', '2023-08-22 01:21:15'),
(87, 'course.custom-option', 'course', '2023-08-25 04:30:44', '2023-08-25 04:30:44'),
(88, 'part.custom-option', 'part', '2023-09-21 05:34:42', '2023-09-21 05:34:42'),
(89, 'brand.create', 'brand', '2023-10-25 12:06:39', '2023-10-25 12:06:39'),
(90, 'brand.edit', 'brand', '2023-10-25 12:06:49', '2023-10-25 12:06:49'),
(91, 'brand.view', 'brand', '2023-10-25 12:06:58', '2023-10-25 12:06:58'),
(92, 'brand.delete', 'brand', '2023-10-25 12:07:06', '2023-10-25 12:07:06'),
(93, 'activity.create', 'activity', '2023-10-25 12:06:39', '2023-10-25 12:06:39'),
(94, 'activity.edit', 'activity', '2023-10-25 12:06:49', '2023-10-25 12:06:49'),
(95, 'activity.view', 'activity', '2023-10-25 12:06:58', '2023-10-25 12:06:58'),
(96, 'activity.delete', 'activity', '2023-10-25 12:07:06', '2023-10-25 12:07:06'),
(97, 'question.create', 'question', '2024-10-30 23:10:24', '2024-10-30 23:10:24'),
(98, 'question.edit', 'question', '2024-10-30 23:10:32', '2024-10-30 23:10:32'),
(99, 'question.view', 'question', '2024-10-30 23:10:47', '2024-10-30 23:10:47'),
(100, 'question.delete', 'question', '2024-10-30 23:10:55', '2024-10-30 23:10:55'),
(101, 'exam.create', 'exam', '2024-10-31 04:56:03', '2024-10-31 04:56:03'),
(102, 'exam.delete', 'exam', '2024-10-31 04:56:10', '2024-10-31 04:56:10'),
(103, 'exam.edit', 'exam', '2024-10-31 04:56:17', '2024-10-31 04:56:17'),
(104, 'exam.view', 'exam', '2024-10-31 04:56:24', '2024-10-31 04:56:24'),
(105, 'segmentation.view', 'segmentation', '2024-11-27 22:39:01', '2024-11-27 22:39:01'),
(106, 'segmentation.create', 'segmentation', '2024-11-27 22:39:12', '2024-11-27 22:39:12'),
(107, 'segmentation.edit', 'segmentation', '2024-11-27 22:39:18', '2024-11-27 22:39:18'),
(108, 'segmentation.delete', 'segmentation', '2024-11-27 22:39:24', '2024-11-27 22:39:24'),
(109, 'result.view', 'result', '2024-12-02 08:24:51', '2024-12-02 08:24:51'),
(110, 'result.create', 'result', '2024-12-02 08:25:03', '2024-12-02 08:25:03'),
(111, 'result.edit', 'result', '2024-12-02 08:25:10', '2024-12-02 08:25:10'),
(112, 'result.delete', 'result', '2024-12-02 08:25:22', '2024-12-02 08:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2023-05-07 11:16:21', '2023-05-07 11:16:21'),
(2, 'Participant', '2023-05-07 11:16:21', '2023-05-07 11:16:21'),
(4, 'Partner', '2023-05-16 04:15:06', '2023-05-16 04:15:06'),
(6, 'support', '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(7, 'Instructor', '2024-10-25 09:07:00', '2024-10-25 09:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `role_right`
--

CREATE TABLE `role_right` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL,
  `permission` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_right`
--

INSERT INTO `role_right` (`id`, `role_id`, `right_id`, `permission`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(2, 1, 2, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(3, 1, 3, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(4, 1, 4, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(5, 1, 5, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(6, 1, 6, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(7, 1, 7, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(8, 1, 8, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(9, 1, 9, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(10, 1, 10, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(11, 1, 11, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(12, 1, 12, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(13, 1, 13, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(14, 1, 14, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(15, 1, 15, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(16, 1, 16, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(17, 1, 17, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(18, 1, 18, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(19, 1, 19, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(20, 1, 20, 1, '2023-05-09 23:55:04', '2023-05-16 04:15:06'),
(41, 4, 1, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(42, 4, 2, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(43, 4, 3, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(44, 4, 4, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(45, 4, 5, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(46, 4, 6, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(47, 4, 7, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(48, 4, 8, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(49, 4, 9, 1, '2023-05-16 04:22:37', '2023-05-16 04:22:37'),
(50, 4, 10, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(51, 4, 11, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(52, 4, 12, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(53, 4, 13, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(54, 4, 14, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(55, 4, 15, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(56, 4, 16, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(57, 4, 17, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(58, 4, 18, 0, '2023-05-16 04:22:37', '2023-07-11 05:43:55'),
(59, 4, 19, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(60, 4, 20, 0, '2023-05-16 04:22:37', '2023-05-16 06:18:24'),
(81, 1, 23, 1, '2023-05-16 22:28:46', '2023-05-16 22:28:46'),
(82, 1, 24, 1, '2023-05-16 22:28:46', '2023-05-16 22:28:46'),
(83, 1, 25, 1, '2023-05-16 22:28:46', '2023-05-16 22:28:46'),
(84, 1, 26, 1, '2023-05-16 22:28:46', '2023-05-16 22:28:46'),
(85, 1, 27, 1, '2023-05-17 04:28:22', '2023-05-17 04:28:22'),
(86, 1, 28, 1, '2023-05-17 04:28:22', '2023-05-17 04:28:22'),
(87, 1, 29, 1, '2023-05-17 04:28:22', '2023-05-17 04:28:22'),
(88, 1, 30, 1, '2023-05-17 04:28:22', '2023-05-17 04:28:22'),
(89, 1, 31, 1, '2023-05-21 01:36:08', '2025-01-16 07:13:04'),
(90, 1, 32, 1, '2023-05-21 01:36:08', '2025-01-16 07:13:04'),
(91, 1, 33, 1, '2023-05-21 01:36:08', '2025-01-16 07:13:04'),
(92, 1, 34, 1, '2023-05-21 01:36:08', '2025-01-16 07:13:04'),
(93, 1, 35, 1, '2023-05-21 23:33:20', '2023-05-21 23:33:20'),
(94, 1, 37, 1, '2023-05-21 23:33:20', '2023-05-21 23:33:20'),
(95, 1, 38, 1, '2023-05-21 23:33:20', '2023-05-21 23:33:20'),
(96, 1, 39, 1, '2023-05-21 23:55:53', '2023-05-21 23:55:53'),
(97, 1, 40, 1, '2023-05-22 05:46:42', '2025-01-16 07:13:04'),
(98, 1, 41, 1, '2023-05-22 05:46:42', '2025-01-16 07:13:04'),
(99, 1, 42, 1, '2023-05-22 05:46:42', '2025-01-16 07:13:04'),
(100, 1, 43, 1, '2023-05-22 05:46:42', '2025-01-16 07:13:04'),
(101, 1, 44, 1, '2023-05-23 06:35:38', '2025-01-16 07:13:04'),
(102, 1, 45, 1, '2023-05-23 06:35:38', '2025-01-16 07:13:04'),
(103, 1, 46, 1, '2023-05-23 06:35:38', '2025-01-16 07:13:04'),
(104, 1, 47, 1, '2023-05-23 06:35:38', '2025-01-16 07:13:04'),
(105, 1, 48, 1, '2023-05-24 03:09:45', '2024-10-26 23:37:54'),
(106, 1, 49, 1, '2023-05-24 03:09:45', '2024-10-26 23:37:54'),
(107, 1, 50, 1, '2023-05-24 03:09:45', '2024-10-26 23:37:54'),
(108, 1, 51, 1, '2023-05-24 03:09:45', '2024-10-26 23:37:54'),
(109, 1, 52, 1, '2023-05-25 05:17:22', '2025-01-16 07:13:04'),
(110, 1, 53, 1, '2023-05-25 05:17:22', '2025-01-16 07:13:04'),
(111, 1, 54, 1, '2023-05-25 05:17:22', '2025-01-16 07:13:04'),
(112, 1, 55, 1, '2023-05-25 05:17:22', '2025-01-16 07:13:04'),
(113, 1, 56, 1, '2023-05-28 23:26:35', '2025-01-16 07:13:04'),
(114, 1, 57, 1, '2023-05-28 23:26:35', '2025-01-16 07:13:04'),
(115, 1, 58, 1, '2023-05-28 23:26:35', '2025-01-16 07:13:04'),
(116, 1, 59, 1, '2023-05-28 23:26:35', '2025-01-16 07:13:04'),
(117, 1, 60, 1, '2023-06-06 22:58:55', '2025-01-16 07:13:04'),
(118, 1, 61, 1, '2023-06-06 22:58:55', '2025-01-16 07:13:04'),
(119, 1, 62, 1, '2023-06-06 22:58:55', '2025-01-16 07:13:04'),
(120, 1, 63, 1, '2023-06-06 22:58:55', '2025-01-16 07:13:04'),
(121, 1, 64, 1, '2023-06-07 22:23:52', '2025-01-16 07:13:04'),
(122, 1, 65, 1, '2023-06-07 22:23:52', '2025-01-16 07:13:04'),
(123, 1, 66, 1, '2023-06-07 22:23:52', '2025-01-16 07:13:04'),
(124, 1, 67, 1, '2023-06-07 22:23:52', '2025-01-16 07:13:04'),
(125, 1, 68, 1, '2023-06-12 04:35:48', '2025-01-16 07:13:04'),
(126, 1, 69, 1, '2023-06-12 04:35:48', '2025-01-16 07:13:04'),
(127, 1, 70, 1, '2023-06-12 04:35:48', '2025-01-16 07:13:04'),
(128, 1, 71, 1, '2023-06-12 04:35:48', '2025-01-16 07:13:04'),
(129, 1, 72, 1, '2023-06-12 22:41:07', '2025-01-16 07:13:04'),
(130, 1, 73, 1, '2023-06-12 22:41:07', '2025-01-16 07:13:04'),
(131, 1, 74, 1, '2023-06-12 22:41:07', '2025-01-16 07:13:04'),
(132, 1, 75, 1, '2023-06-12 22:41:07', '2025-01-16 07:13:04'),
(133, 1, 76, 1, '2023-07-03 01:10:38', '2023-07-03 01:10:38'),
(134, 1, 77, 0, '2023-07-07 05:02:26', '2023-07-09 23:25:44'),
(135, 4, 23, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(136, 4, 24, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(137, 4, 25, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(138, 4, 26, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(139, 4, 27, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(140, 4, 28, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(141, 4, 29, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(142, 4, 30, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(143, 4, 31, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(144, 4, 32, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(145, 4, 33, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(146, 4, 34, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(147, 4, 35, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(148, 4, 37, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(149, 4, 38, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(150, 4, 39, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(151, 4, 40, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(152, 4, 41, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(153, 4, 42, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(154, 4, 43, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(155, 4, 44, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(156, 4, 45, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(157, 4, 46, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(158, 4, 47, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(159, 4, 48, 1, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(160, 4, 49, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(161, 4, 50, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(162, 4, 51, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(163, 4, 52, 1, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(164, 4, 53, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(165, 4, 54, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(166, 4, 55, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(167, 4, 56, 1, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(168, 4, 57, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(169, 4, 58, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(170, 4, 59, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(171, 4, 60, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(172, 4, 61, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(173, 4, 62, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(174, 4, 63, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(175, 4, 64, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(176, 4, 65, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(177, 4, 66, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(178, 4, 67, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(179, 4, 68, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(180, 4, 69, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(181, 4, 70, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(182, 4, 71, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(183, 4, 72, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(184, 4, 73, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(185, 4, 74, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(186, 4, 75, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(187, 4, 76, 0, '2023-07-11 05:43:55', '2023-07-11 05:43:55'),
(241, 2, 1, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(242, 2, 2, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(243, 2, 3, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(244, 2, 4, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(245, 2, 5, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(246, 2, 6, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(247, 2, 7, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(248, 2, 8, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(249, 2, 9, 1, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(250, 2, 10, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(251, 2, 11, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(252, 2, 12, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(253, 2, 13, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(254, 2, 14, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(255, 2, 15, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(256, 2, 16, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(257, 2, 17, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(258, 2, 18, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(259, 2, 19, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(260, 2, 20, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(261, 2, 23, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(262, 2, 24, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(263, 2, 25, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(264, 2, 26, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(265, 2, 27, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(266, 2, 28, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(267, 2, 29, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(268, 2, 30, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(269, 2, 31, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(270, 2, 32, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(271, 2, 33, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(272, 2, 34, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(273, 2, 35, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(274, 2, 37, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(275, 2, 38, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(276, 2, 39, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(277, 2, 40, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(278, 2, 41, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(279, 2, 42, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(280, 2, 43, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(281, 2, 44, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(282, 2, 45, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(283, 2, 46, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(284, 2, 47, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(285, 2, 48, 1, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(286, 2, 49, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(287, 2, 50, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(288, 2, 51, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(289, 2, 52, 1, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(290, 2, 53, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(291, 2, 54, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(292, 2, 55, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(293, 2, 56, 1, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(294, 2, 57, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(295, 2, 58, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(296, 2, 59, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(297, 2, 60, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(298, 2, 61, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(299, 2, 62, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(300, 2, 63, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(301, 2, 64, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(302, 2, 65, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(303, 2, 66, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(304, 2, 67, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(305, 2, 68, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(306, 2, 69, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(307, 2, 70, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(308, 2, 71, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(309, 2, 72, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(310, 2, 73, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(311, 2, 74, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(312, 2, 75, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(313, 2, 76, 0, '2023-07-11 05:45:20', '2023-07-11 05:45:20'),
(314, 1, 78, 1, '2023-07-14 02:13:57', '2025-01-16 07:13:04'),
(316, 1, 79, 1, '2023-08-11 07:32:54', '2025-01-16 07:13:04'),
(317, 1, 80, 1, '2023-08-11 07:32:54', '2025-01-16 07:13:04'),
(318, 1, 81, 1, '2023-08-11 07:32:54', '2025-01-16 07:13:04'),
(319, 1, 82, 1, '2023-08-11 07:32:54', '2025-01-16 07:13:04'),
(320, 1, 83, 1, '2023-08-22 01:32:09', '2023-08-22 01:32:09'),
(321, 1, 84, 1, '2023-08-22 01:32:09', '2023-08-22 01:32:09'),
(322, 1, 85, 1, '2023-08-22 01:32:09', '2023-08-22 01:32:09'),
(323, 1, 86, 1, '2023-08-22 01:32:09', '2023-08-22 01:32:09'),
(324, 1, 87, 1, '2023-08-25 04:30:58', '2023-08-25 04:30:58'),
(325, 1, 88, 1, '2023-09-21 05:35:01', '2025-01-16 07:13:04'),
(326, 1, 89, 1, '2023-10-25 12:07:33', '2025-01-16 07:13:04'),
(327, 1, 90, 1, '2023-10-25 12:07:33', '2025-01-16 07:13:04'),
(328, 1, 91, 1, '2023-10-25 12:07:33', '2025-01-16 07:13:04'),
(329, 1, 92, 1, '2023-10-25 12:07:33', '2025-01-16 07:13:04'),
(330, 6, 1, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(331, 6, 2, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(332, 6, 3, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(333, 6, 4, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(334, 6, 5, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(335, 6, 6, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(336, 6, 7, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(337, 6, 8, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(338, 6, 9, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(339, 6, 10, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(340, 6, 11, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(341, 6, 12, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(342, 6, 13, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(343, 6, 14, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(344, 6, 15, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(345, 6, 16, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(346, 6, 17, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(347, 6, 18, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(348, 6, 19, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(349, 6, 20, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(350, 6, 23, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(351, 6, 24, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(352, 6, 25, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(353, 6, 26, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(354, 6, 27, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(355, 6, 28, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(356, 6, 29, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(357, 6, 30, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(358, 6, 31, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(359, 6, 32, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(360, 6, 33, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(361, 6, 34, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(362, 6, 35, 1, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(363, 6, 37, 1, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(364, 6, 38, 1, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(365, 6, 39, 1, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(366, 6, 40, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(367, 6, 41, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(368, 6, 42, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(369, 6, 43, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(370, 6, 44, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(371, 6, 45, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(372, 6, 46, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(373, 6, 47, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(374, 6, 48, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(375, 6, 49, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(376, 6, 50, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(377, 6, 51, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(378, 6, 52, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(379, 6, 53, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(380, 6, 54, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(381, 6, 55, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(382, 6, 56, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(383, 6, 57, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(384, 6, 58, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(385, 6, 59, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(386, 6, 60, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(387, 6, 61, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(388, 6, 62, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(389, 6, 63, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(390, 6, 64, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(391, 6, 65, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(392, 6, 66, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(393, 6, 67, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(394, 6, 68, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(395, 6, 69, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(396, 6, 70, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(397, 6, 71, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(398, 6, 72, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(399, 6, 73, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(400, 6, 74, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(401, 6, 75, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(402, 6, 76, 1, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(403, 6, 78, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(404, 6, 79, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(405, 6, 80, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(406, 6, 81, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(407, 6, 82, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(408, 6, 83, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(409, 6, 84, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(410, 6, 85, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(411, 6, 86, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(412, 6, 87, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(413, 6, 88, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(414, 6, 89, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(415, 6, 90, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(416, 6, 91, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(417, 6, 92, 0, '2024-01-22 17:27:43', '2024-01-22 17:27:43'),
(418, 7, 1, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(419, 7, 2, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(420, 7, 3, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(421, 7, 4, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(422, 7, 5, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(423, 7, 6, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(424, 7, 7, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(425, 7, 8, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(426, 7, 9, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(427, 7, 10, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(428, 7, 11, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(429, 7, 12, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(430, 7, 13, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(431, 7, 14, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(432, 7, 15, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(433, 7, 16, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(434, 7, 17, 1, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(435, 7, 18, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(436, 7, 19, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(437, 7, 20, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(438, 7, 23, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(439, 7, 24, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(440, 7, 25, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(441, 7, 26, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(442, 7, 27, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(443, 7, 28, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(444, 7, 29, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(445, 7, 30, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(446, 7, 31, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(447, 7, 32, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(448, 7, 33, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(449, 7, 34, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(450, 7, 35, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(451, 7, 37, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(452, 7, 38, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(453, 7, 39, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(454, 7, 40, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(455, 7, 41, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(456, 7, 42, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(457, 7, 43, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(458, 7, 44, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(459, 7, 45, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(460, 7, 46, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(461, 7, 47, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(462, 7, 48, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(463, 7, 49, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(464, 7, 50, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(465, 7, 51, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(466, 7, 52, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(467, 7, 53, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(468, 7, 54, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(469, 7, 55, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(470, 7, 56, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(471, 7, 57, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(472, 7, 58, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(473, 7, 59, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(474, 7, 60, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(475, 7, 61, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(476, 7, 62, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(477, 7, 63, 0, '2024-10-25 09:07:00', '2024-10-25 09:07:00'),
(478, 7, 64, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(479, 7, 65, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(480, 7, 66, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(481, 7, 67, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(482, 7, 68, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(483, 7, 69, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(484, 7, 70, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(485, 7, 71, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(486, 7, 72, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(487, 7, 73, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(488, 7, 74, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(489, 7, 75, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(490, 7, 76, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(491, 7, 78, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(492, 7, 79, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(493, 7, 80, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(494, 7, 81, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(495, 7, 82, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(496, 7, 83, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(497, 7, 84, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(498, 7, 85, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(499, 7, 86, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(500, 7, 87, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(501, 7, 88, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(502, 7, 89, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(503, 7, 90, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(504, 7, 91, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(505, 7, 92, 0, '2024-10-25 09:07:01', '2024-10-25 09:07:01'),
(506, 1, 93, 1, '2024-10-27 04:04:18', '2024-10-27 04:04:18'),
(507, 1, 94, 1, '2024-10-27 04:04:18', '2024-10-27 04:04:18'),
(508, 1, 95, 1, '2024-10-27 04:04:18', '2024-10-27 04:04:18'),
(509, 1, 96, 1, '2024-10-27 04:04:18', '2024-10-27 04:04:18'),
(510, 1, 97, 1, '2024-10-30 23:11:15', '2024-10-30 23:11:15'),
(511, 1, 98, 1, '2024-10-30 23:11:15', '2024-10-30 23:11:15'),
(512, 1, 99, 1, '2024-10-30 23:11:15', '2024-10-30 23:11:15'),
(513, 1, 100, 1, '2024-10-30 23:11:15', '2024-10-30 23:11:15'),
(514, 1, 101, 1, '2024-10-31 04:57:25', '2024-10-31 04:57:25'),
(515, 1, 102, 1, '2024-10-31 04:57:25', '2024-10-31 04:57:25'),
(516, 1, 103, 1, '2024-10-31 04:57:25', '2024-10-31 04:57:25'),
(517, 1, 104, 1, '2024-10-31 04:57:25', '2024-10-31 04:57:25'),
(518, 1, 105, 1, '2024-11-27 22:39:55', '2024-11-27 22:39:55'),
(519, 1, 106, 1, '2024-11-27 22:39:55', '2024-11-27 22:39:55'),
(520, 1, 107, 1, '2024-11-27 22:39:55', '2024-11-27 22:39:55'),
(521, 1, 108, 1, '2024-11-27 22:39:55', '2024-11-27 22:39:55'),
(522, 1, 109, 1, '2024-12-02 08:25:45', '2024-12-02 08:25:45'),
(523, 1, 110, 1, '2024-12-02 08:25:45', '2024-12-02 08:25:45'),
(524, 1, 111, 1, '2024-12-02 08:25:45', '2024-12-02 08:25:45'),
(525, 1, 112, 1, '2024-12-02 08:25:45', '2024-12-02 08:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `segmentations`
--

CREATE TABLE `segmentations` (
  `id` int(11) NOT NULL,
  `slug` text DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `ancestor_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ordering` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `segmentations`
--

INSERT INTO `segmentations` (`id`, `slug`, `name`, `ancestor_id`, `status`, `ordering`, `created_at`, `updated_at`) VALUES
(6, NULL, 'DIU ICT Quiz Competition', NULL, 1, NULL, '2024-12-02 06:14:05', '2024-12-02 06:14:05'),
(10, 'gk', 'GK', 6, 0, NULL, '2025-01-09 05:36:54', '2025-01-09 05:57:05'),
(11, 'programming', 'Programming', NULL, 0, NULL, '2025-01-09 05:57:24', '2025-01-09 05:57:24'),
(12, 'java', 'Java', 11, 0, NULL, '2025-01-09 06:00:27', '2025-01-09 06:00:27'),
(13, 'math', 'Math', NULL, 0, NULL, '2025-01-09 06:03:06', '2025-01-09 06:03:06'),
(14, 'class-9', 'Class 9', 13, 0, NULL, '2025-01-09 06:03:18', '2025-01-09 06:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` varchar(40) NOT NULL,
  `code` varchar(256) DEFAULT NULL,
  `product_id` varchar(40) DEFAULT NULL,
  `title` varchar(256) NOT NULL,
  `slug` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `media` varchar(256) NOT NULL,
  `additional_details` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `code`, `product_id`, `title`, `slug`, `short_description`, `description`, `url`, `media`, `additional_details`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('65165cfd55772-srvs-19262356895495930', '13a6b6fed124', NULL, 'Repair and Refurbish', '13a6b6fed124-repair-and-refurbish', 'Magnetic chucks, lifters, and control units.', '<p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; padding: 0px; direction: ltr; line-height: 1.6; text-rendering: optimizelegibility;\">Machine Tool Solutions Ltd. repairs and refurbishes magnetic chucks, lifters, and control units, including:</p><ul style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 20px; padding: 0px; direction: ltr; line-height: 1.6; list-style-position: outside;\"><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Magnetic lifters with internal rotating shaft</li><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Electro-permanent magnetic chucks for milling, grinding &amp; EDM</li><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Control units for magnetic chucks</li><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Electro-permanent magnetic lifters remote controlled/wired</li><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Other magnetic products</li><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Any manufacturer or brand name magnetic products</li></ul><h3 style=\"margin: 0.2em 0px 0.5em; padding: 0px; direction: ltr; text-rendering: optimizelegibility; line-height: 1.4;\"><font face=\"Lato, helvetica, arial, sans-serif\"><span style=\"font-size: 20px; font-family: Tahoma;\">Please CONTACT us to get a no obligation quote on custom made workholding solution.</span></font></h3><h3 style=\"margin: 0.2em 0px 0.5em; padding: 0px; direction: ltr; text-rendering: optimizelegibility; line-height: 1.4;\"><font face=\"Lato, helvetica, arial, sans-serif\"><span style=\"font-size: 20px; font-family: Tahoma;\">We can handle drawings in many formats including DWG, DXF, IGES, PDF and various SOLIDS formats.</span></font></h3>', NULL, '17014319476569ca8b67aff1696248435651ab27307259repair-and-refurbish_50.jpg', '[{\"description\":\"<p><br><\\/p>\",\"image\":\"\"}]', 1, NULL, '2023-09-29 12:13:33', '2024-03-01 01:57:55'),
('65165ee2aebcc-srvs-55053505575370516', '638ce913242c', NULL, 'Re-Certification', '638ce913242c-re-certification', 'Machine Tool Solutions Ltd. performs re-certification services of permanent magnetic lifters and conducts tests under controlled conditions.\r\n\r\nAs per O.H.S.A and Regulations for Industrial Establishments R.R.O.1990, Section #51, (R-28), A lifting device shall be thoroughly examined by a competent person to determine its capability of handling the maximum load as rated at least once a year and a permanent record shall be kept, signed by person doing the examination.', '<p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; padding: 0px; direction: ltr; line-height: 1.6; text-rendering: optimizelegibility;\">Our technician performs internal preventive maintenance as well as test three times, using special equipment designed to determine the most accurate pulling force reading of magnetic lifter. Safety factor is also taken into consideration for re-certification record. In case when the pulling force is lower than required, an estimate cost of repair, if possible, will be quoted to the customer. Magnets inside the lifter would be replaced if required. Magnets will be cleaned, painted and labelled with updated new maximum load rating. The customer is given Load Certificate for his records. We strongly recommend re-certification of all magnet lifters at least once a year. Please contact us to how to expedite the service.<br></p>', NULL, '17014297346569c1e64e9001696337288651c0d88f26eerecertification-MTS_50.jpg', '[{\"description\":\"<p><br><\\/p>\",\"image\":\"\"}]', 1, NULL, '2023-09-29 12:21:38', '2024-03-01 01:57:55'),
('65165f339a091-srvs-57107017096684087', '18546b323a88', NULL, 'COMPONENTS MANUFACTURING OPTIMIZATION', '18546b323a88-components-manufacturing-optimization', 'Are your operations costing too much? Encountering consistent problems with bottlenecks, capacity, or quality issues? Do you need assistance with your continuous improvement mandate? Machine Tools Solutions Ltd. can effectively assist you', '<p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; padding: 0px; direction: ltr; line-height: 1.6; text-rendering: optimizelegibility;\">With proper and thorough consultation with our work-holding equipments expert, we can cut your manufacturing costs! Processing capabilities include cell/fixture/gage design in addition to program optimization with clear vision on the “Big Picture”. Our experienced engineers have an uncanny ability to “see” improvement quickly, efficiently and provide the best solutions in a timely and professional manner. From managing OK-VISE low profile clamps to maximizing the utilization of Quick-Point grid plates applications, we have the professionals to guide you every step of the way. Let us cut your costs on metal working operations, NO RISK! We only receive payment upon your SAVING MONEY, so what do you have to lose?&nbsp;</p><h3 style=\"margin: 0.2em 0px 0.5em; line-height: 1.4; padding: 0px; direction: ltr; text-rendering: optimizelegibility;\"><font face=\"Lato, helvetica, arial, sans-serif\"><span style=\"font-size: 20px; font-family: Tahoma;\">Please CONTACT us to get a no obligation quote on custom made workholding solution.</span></font></h3><h3 style=\"margin: 0.2em 0px 0.5em; line-height: 1.4; padding: 0px; direction: ltr; text-rendering: optimizelegibility;\"><font face=\"Lato, helvetica, arial, sans-serif\"><span style=\"font-size: 20px; font-family: Tahoma;\">We can handle drawings in many formats including DWG, DXF, IGES, PDF and various SOLIDS formats.</span></font></h3>', NULL, '1696337397651c0df51c21aService-3.png', '[{\"description\":\"<p>With proper and thorough consultation with our work-holding equipments expert, we can cut your manufacturing costs! Processing capabilities include cell\\/fixture\\/gage design in addition to program optimization with clear vision on the \\u201cBig Picture\\u201d.<\\/p>\",\"image\":\"16992761926548e5a023ede_49778+49779.png\"},{\"description\":\"<p>With proper and thorough consultation with our work-holding equipments expert, we can cut your manufacturing costs!&nbsp;<\\/p>\",\"image\":\"16992761926548e5a024051_83420-HE_0.png\"}]', 1, NULL, '2023-09-29 12:22:59', '2024-03-01 01:57:55'),
('653b7ccb1fbb2-srvs-25716943780484432', '53402-LN', NULL, 'LANG CUSTOMIZATION AND REPAIR SERVICES', '53402-ln-lang-customization-and-repair-services', 'Modified clamping depth, Tungsten-carbide-coating, Modification of base.', '<p>- Modified clamping depth&nbsp;</p><p>- Tungsten-carbide-coating&nbsp;</p><p>- Modification of base&nbsp;</p>', NULL, '1701839138657001225ff701699419290654b149a7df59Product-Post_11zon.jpg', '[{\"description\":\"<p><br><\\/p>\",\"image\":\"\"}]', 1, NULL, '2023-10-27 16:03:07', '2024-03-01 01:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `service_order`
--

CREATE TABLE `service_order` (
  `id` varchar(40) NOT NULL,
  `user_id` varchar(40) DEFAULT NULL,
  `service_information` text DEFAULT NULL,
  `company_name` varchar(256) DEFAULT NULL,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `date` date NOT NULL,
  `message` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_order`
--

INSERT INTO `service_order` (`id`, `user_id`, `service_information`, `company_name`, `name`, `email`, `address`, `date`, `message`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('651a85d6786f8-sodr-66238166254210215', '651a751089463-user-53125218649401490', '[{\"service_id\":\"65165cfd55772-srvs-19262356895495930\",\"name\":\"Repair and Refurbish\",\"code\":\"13a6b6fed124\",\"file\":\"\"}]', NULL, 'Allen Shapon', 'shapon@gmail.com', '166, sukrabad, Kolabagan', '2023-10-02', 'sdfsjfsdf', 1, NULL, '2023-10-02 15:56:54', '2023-10-02 16:17:29'),
('6544d545a1294-sodr-24779374099088159', '1', '[{\"service_id\":\"65165ee2aebcc-srvs-55053505575370516\",\"name\":\"Re-Certification\",\"code\":\"638ce913242c\",\"file\":\"\"}]', NULL, 'Sajal Ahmed', 'sajal@nexkraft.com', 'Dhanmondi, Dhaka', '2023-11-03', 'Hello', 2, NULL, '2023-11-03 18:11:01', '2023-11-03 18:13:59'),
('654e740193636-sodr-72963431668638145', NULL, '[{\"service_id\":\"65165f339a091-srvs-57107017096684087\",\"name\":\"COMPONENTS MANUFACTURING OPTIMIZATION\",\"code\":\"18546b323a88\",\"file\":\"\"}]', 'Browning Miller Inc', 'test', 'test@gmail.com', 'test location', '2023-11-20', NULL, 2, NULL, '2023-11-11 01:18:41', '2023-11-20 13:37:21'),
('655afda37823e-sodr-44878009867639869', '1', '[{\"service_id\":\"65165ee2aebcc-srvs-55053505575370516\",\"name\":\"Re-Certification\",\"code\":\"638ce913242c\",\"file\":\"\"},{\"service_id\":\"653b7ccb1fbb2-srvs-25716943780484432\",\"name\":\"LANG CUSTOMIZATION AND REPAIR SERVICES\",\"code\":\"53402-LN\",\"file\":\"\"}]', 'Browning Miller Inc', 'Colton Solomon', 'cicapu@mailinator.com', 'Ab vel aut non labor', '2023-11-20', 'Hic ut cillum aliquasdfsfdsf', 0, NULL, '2023-11-20 13:33:07', '2023-11-20 13:33:07');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` longtext DEFAULT NULL,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'application_name', 'NexAcademy', 1, '2023-05-21 22:34:50', '2024-12-24 12:17:35'),
(2, 'site_logo', '17370114076788b0cfce1f1Logo.png', 1, '2023-05-21 22:59:19', '2025-01-16 07:10:07'),
(3, 'site_favicon', '17370114076788b0cfcea66Logo.png', 1, '2023-05-21 23:09:36', '2025-01-16 07:10:07'),
(4, 'application_phone', '017**********', 1, '2023-05-21 23:11:44', '2024-10-24 23:42:43'),
(5, 'application_email', 'info@nexacademy.com', 1, '2023-05-21 23:12:29', '2024-10-24 23:42:43'),
(6, 'application_toll_free', NULL, 1, '2023-05-21 23:20:49', '2024-10-24 23:42:43'),
(7, 'application_fax', NULL, 1, '2023-05-21 23:20:49', '2024-10-24 23:42:43'),
(8, 'application_address', '8 Automatic Rd. Building C, Unit #6 Brampton, Ontario L6S 5N4, Canada', 1, '2023-05-21 23:20:49', '2023-10-27 23:16:08'),
(9, 'about_us', '<p style=\"margin: 0.2em 0px 0.5em; padding: 0px; direction: ltr; text-rendering: optimizelegibility; line-height: 1.4;\"></p><h3 style=\"font-family: Jost, sans-serif;\"><span style=\"line-height: inherit;\"><span style=\"font-weight: bolder;\">Machine Tool Solutions –</span></span></h3><h3><span style=\"font-family: Jost, sans-serif; font-size: 16px; line-height: inherit;\"><span style=\"background-color: var(--bs-card-bg); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align); line-height: inherit;\">Global distributor of reliable and competitively priced&nbsp;products such as AutoGrip Manual / Power Chucks, Lang Technik Clean Tec and AR Filtrazioni, Compact Fixtures, 5-axis Clamping Systems, Stamping Technology, Vises for CNC Machining, Makro-Grip Applications, Precision Index Tables, and more machine tool solutions and services.&nbsp;</span><span style=\"background-color: var(--bs-card-bg); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align); background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; line-height: inherit;\">Contact</span><span style=\"background-color: var(--bs-card-bg); font-size: var(--bs-body-font-size); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align); line-height: inherit;\">&nbsp;Machine Tools Solutions today to learn more about what products we have in stock.</span></span><p style=\"font-family: Jost, sans-serif; font-size: 16px;\"></p><p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; font-size: 16px; padding: 0px; direction: ltr; font-family: Lato, helvetica, arial, sans-serif; line-height: 1.6; text-rendering: optimizelegibility;\"><span style=\"line-height: inherit;\">Machine Tool Solutions Ltd.&nbsp;</span>was established in 1989. For over 35 years, our mission at MTS has been to provide “intelligent workholding for improving productivity” to our customers by delivering high quality, value-minded tools inworkholding andmaterial handling through magnetic systems. Additionally, we provide solutions for non-ferrous materials through innovative fixture and zero-point clamping systems, permanent lifting magnets and Makro-grip profile clamping vices.</p><p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; font-size: 16px; padding: 0px; direction: ltr; font-family: Lato, helvetica, arial, sans-serif; line-height: 1.6; text-rendering: optimizelegibility;\">With powerful and well-crafted components, Machine Tool Solutions Ltd. offers a wide product line to satisfy the needs of various industries including defense, medical, automotive, aerospace and more. Our mission further developed the company into gathering the finest products from world-class manufacturers and producers of effective mechanical and industrial components. We are a distributor of equipment from stamping technology, LANG Technik GmbH, SPD, AR Filtrazioni, Ok-Vise low profile clamps, 5-axis vises and stamping devices from LANG as well as many more.</p><p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; font-size: 16px; padding: 0px; direction: ltr; font-family: Lato, helvetica, arial, sans-serif; line-height: 1.6; text-rendering: optimizelegibility;\">Machine Tool Solutions also provide expert repair, refurbishing and re-certification services, ensuring work safety through proper and thorough consultation of your workholding equipment. Our technical servicescertify your tools work best for you, offering consultations on product efficiency and component manufacturing optimization.</p><p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; font-size: 16px; padding: 0px; direction: ltr; font-family: Lato, helvetica, arial, sans-serif; line-height: 1.6; text-rendering: optimizelegibility;\">We welcome you to be our partner towards continuous success and expanding growth in manufacturing, workholding, automation, and material handling technology.&nbsp;<a rel=\"nofollow\" href=\"https://www.machinetoolsolutions.ca/lang-technovation-technik-gmbh-automation-quick-point-zero-clamping-tower-tombstone-plates-eco-compact-20-canada/\" style=\"color: rgb(10, 77, 104); text-decoration: none; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; line-height: inherit;\">Contact</a>&nbsp;Machine Tools Solutions today to learn more about what products we have in stock.</p><p class=\"h-large\" style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; padding: 0px; direction: ltr; font-family: Lato, helvetica, arial, sans-serif; font-size: 32px; line-height: 32px; text-rendering: optimizelegibility;\">Social Responsibility</p><p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; font-size: 16px; padding: 0px; direction: ltr; font-family: Lato, helvetica, arial, sans-serif; line-height: 1.6; text-rendering: optimizelegibility;\"></p><p style=\"margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; font-size: 16px; padding: 0px; direction: ltr; font-family: Lato, helvetica, arial, sans-serif; line-height: 1.6; text-rendering: optimizelegibility;\">Machine Tool Solutions Ltd. cares about the environment and its employees are encouraged to:</p><ul style=\"padding: 0px; margin-right: 0px; margin-bottom: 1.25em; margin-left: 20px; font-size: 16px; direction: ltr; line-height: 1.6; list-style-position: outside; font-family: Lato, helvetica, arial, sans-serif;\"><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Keep the work environment clean and safe.</li><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Reduce the company’s waste generation by recycling paper and packaging supplies.</li><li style=\"margin: 0px; padding: 0px; direction: ltr;\">Decrease energy and water consumption.</li></ul></h3>', 1, '2023-05-22 01:14:20', '2024-03-04 00:27:14'),
(10, 'about_image_1', '1684754453646b501513684about-1.jpg', 1, '2023-05-22 05:20:53', '2023-05-22 05:20:53'),
(11, 'about_image_2', '1684754453646b501513bc3about-3.jpg', 1, '2023-05-22 05:20:53', '2023-05-22 05:20:53'),
(12, 'about_image_3', '1684754453646b501513e3dabout-2.jpg', 1, '2023-05-22 05:20:53', '2023-05-22 05:20:53'),
(13, 'terms_and_conditions', '<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Effective Date:</span></b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> [Insert Date]<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Please\r\nread these Terms and Conditions (\"Terms\") carefully before using the\r\nwebsite https://machinetoolsolutions.ca/ (\"Website\") operated by\r\nMachine Tool Solutions (\"Company,\" \"we,\" \"us,\" or\r\n\"our\"). These Terms set forth the legally binding agreement between\r\nyou (\"User,\" \"you,\" or \"your\") and Machine Tool\r\nSolutions regarding your use of the Website.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Acceptance of Terms<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">By\r\naccessing or using the Website, you acknowledge that you have read, understood,\r\nand agree to be bound by these Terms and any additional terms and conditions\r\nprovided within the Website. If you do not agree to these Terms, you may not\r\nuse the Website.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Modifications to the Terms<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">We\r\nreserve the right to modify, update, or replace these Terms at any time,\r\nwithout prior notice. It is your responsibility to review the Terms\r\nperiodically for any changes. Your continued use of the Website after any\r\nmodifications to the Terms constitutes your acceptance of the revised Terms.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Website Use<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nEligibility: You must be at least 18 years old to use the Website.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nUser Accounts: Some features of the Website may require you to create a user\r\naccount. You are responsible for maintaining the confidentiality of your\r\naccount credentials and for all activities that occur under your account.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">c.\r\nProhibited Activities: You agree not to engage in any activity that violates\r\nthese Terms, including but not limited to:<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Transmitting\r\nany harmful, unlawful, or fraudulent content.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Interfering\r\nwith the Website\'s functionality, security, or integrity.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Attempting\r\nto gain unauthorized access to the Website or other users\' accounts.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Violating\r\nany applicable laws or regulations.<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Intellectual Property<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nOwnership: All content, trademarks, logos, and intellectual property rights\r\ndisplayed on the Website are owned by Machine Tool Solutions or its licensors.\r\nYou may not use, reproduce, distribute, or modify any of the content without\r\nour prior written consent.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nUser Content: By submitting or uploading any content to the Website, you grant\r\nus a non-exclusive, worldwide, royalty-free license to use, display, reproduce,\r\nand distribute that content for the purpose of operating and improving the\r\nWebsite.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Third-Party Websites and Services<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">The\r\nWebsite may contain links to third-party websites or services that are not\r\nowned or controlled by Machine Tool Solutions. We are not responsible for the\r\ncontent or practices of any third-party websites or services. Your use of such\r\nwebsites or services is subject to their respective terms and conditions and\r\nprivacy policies.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Limitation of Liability<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nDisclaimer of Warranties: The Website is provided on an \"as is\" and\r\n\"as available\" basis, without any warranties or representations of\r\nany kind, whether express or implied. We do not guarantee the accuracy,\r\ncompleteness, or reliability of any content on the Website.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nLimitation of Liability: To the fullest extent permitted by applicable law,\r\nMachine Tool Solutions and its directors, officers, employees, or agents shall\r\nnot be liable for any direct, indirect, incidental, special, consequential, or\r\npunitive damages arising out of or in any way connected with your use of the\r\nWebsite or any content on the Website.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Indemnification<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">You\r\nagree to indemnify and hold Machine Tool Solutions, its affiliates, officers,\r\ndirectors, employees, and agents harmless from any claim or demand, including\r\nreasonable attorney\'s fees, made by any third party due to or arising out of\r\nyour use of the Website, your violation of these Terms, or your violation of\r\nany rights of another party.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Governing Law and Jurisdiction<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">These\r\nTerms shall be governed by and construed in accordance with the laws of [Insert\r\ngoverning law]. Any disputes arising under or in connection with these Terms\r\nshall be subject to the exclusive jurisdiction of the courts located in [Insert\r\njurisdiction].<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Severability<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">If\r\nany provision of these Terms is found to be invalid or unenforceable, the\r\nremaining provisions shall continue to be valid and enforceable to the fullest\r\nextent permitted by law.<o:p></o:p></span></p><p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Entire Agreement<o:p></o:p></span></b></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">These\r\nTerms constitute the entire agreement between you and Machine Tool Solutions\r\nregarding your use of the Website and supersede any prior agreements or\r\nunderstandings, whether oral or written.<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">If\r\nyou have any questions or concerns regarding these Terms, please contact us at\r\n[Insert contact information].<o:p></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">&nbsp;</span></p><p class=\"MsoNormal\">\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">By\r\nusing the Website, you acknowledge that you have read, understood, and agree to\r\nbe bound by these Terms and Conditions.</span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size: 12pt; line-height: 107%; background-color: rgb(255, 0, 0);\"><o:p style=\"\"><br></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size: 12pt; line-height: 107%; background-color: rgb(255, 0, 0);\"><o:p><br></o:p></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size: 12pt; line-height: 107%; background-color: rgb(255, 0, 0);\"><o:p style=\"\">xsfdsdsfsdfsdfsdfsdfsdfsfsdf</o:p></span></p>', 1, '2023-07-03 01:25:51', '2024-01-22 17:18:44'),
(14, 'privacy_policy', '<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Effective Date:</span></b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> [Insert Date]<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">At\r\nMachine Tool Solutions (\"Company,\" \"we,\" \"us,\" or\r\n\"our\"), we are committed to protecting your privacy. This Privacy\r\nPolicy describes how we collect, use, disclose, and store your personal\r\ninformation when you visit or use the website https://machinetoolsolutions.ca/\r\n(\"Website\").<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Information We Collect<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nPersonal Information: We may collect personal information that you voluntarily\r\nprovide to us, such as your name, email address, phone number, and any other\r\ninformation you choose to provide.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nAutomatically Collected Information: When you visit our Website, we may\r\nautomatically collect certain information, including your IP address, browser\r\ntype, device information, and browsing activity.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Use of Information<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nWe may use the information we collect for the following purposes:<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">To\r\nprovide and maintain the Website and its features.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">To\r\ncommunicate with you, respond to your inquiries, and provide customer support.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">To\r\npersonalize your experience on the Website and deliver relevant content.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">To\r\nanalyze and improve the Website\'s performance and functionality.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">To\r\ndetect, prevent, and address technical issues or fraudulent activities.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nWe will only use your personal information for the purposes stated in this\r\nPrivacy Policy or as otherwise disclosed to you at the time of collection. We\r\nwill not sell, rent, or lease your personal information to any third parties\r\nwithout your consent.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Cookies\r\nand Tracking Technologies<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nWe may use cookies and similar tracking technologies to collect and store\r\ninformation about your interactions with the Website. Cookies are small text\r\nfiles that are stored on your device.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nYou have the option to refuse or disable cookies through your browser settings.\r\nHowever, please note that disabling cookies may affect the functionality of the\r\nWebsite.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Third-Party Disclosure<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nWe may share your personal information with trusted third-party service\r\nproviders who assist us in operating our Website, conducting our business, or\r\nproviding services to you. These third parties are obligated to keep your\r\ninformation confidential and are prohibited from using your personal\r\ninformation for any other purposes.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nWe may also disclose your personal information if required by law or if we\r\nbelieve that such disclosure is necessary to protect our rights, comply with a\r\njudicial proceeding, court order, or legal process, or to prevent imminent harm\r\nor financial loss.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Data Security<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nWe implement appropriate technical and organizational measures to protect your\r\npersonal information from unauthorized access, disclosure, alteration, or\r\ndestruction.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nWhile we strive to protect your personal information, no method of transmission\r\nover the Internet or electronic storage is completely secure. Therefore, we\r\ncannot guarantee its absolute security.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Children\'s Privacy<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Our\r\nWebsite is not directed to individuals under the age of 18. We do not knowingly\r\ncollect personal information from children. If you are a parent or guardian and\r\nbelieve that your child has provided personal information on our Website,\r\nplease contact us, and we will promptly delete the information.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Links to Third-Party Websites<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">The\r\nWebsite may contain links to third-party websites. We are not responsible for\r\nthe privacy practices or the content of those websites. We encourage you to\r\nreview the privacy policies of those third-party websites before providing any\r\npersonal information.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Your Rights<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nYou have the right to access, correct, update, or delete your personal\r\ninformation that we hold. If you would like to exercise any of these rights,\r\nplease contact us using the information provided at the end of this Privacy\r\nPolicy.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nWe will respond to your request within a reasonable timeframe and in accordance\r\nwith applicable laws.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Changes to this Privacy Policy<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">We\r\nmay update this Privacy Policy from time to time. Any changes will be posted on\r\nthis page, and the \"Effective Date\" at the top of this policy will be\r\nupdated. We encourage you to review this Privacy Policy periodically to stay\r\ninformed about how we collect, use, and protect your personal information.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Contact Us<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">If\r\nyou have any questions, concerns, or requests regarding this Privacy Policy,\r\nplease contact us at [Insert contact information].<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">By\r\nusing the Website, you acknowledge that you have read, understood, and agree to\r\nbe bound by this Privacy Policy.<o:p></o:p></span></p>', 1, '2023-07-03 01:25:51', '2023-07-28 06:18:15'),
(15, 'return_policy', '<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"><span style=\"font-weight: bolder; font-family: Jost, sans-serif;\"><span lang=\"EN-CA\" style=\"font-size: 12pt; line-height: 17.12px;\">Effective Date:</span></span><span lang=\"EN-CA\" style=\"font-family: Jost, sans-serif; font-size: 12pt; line-height: 17.12px;\"> [Insert Date]</span><br></span></p><p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Thank\r\nyou for shopping at Machine Tool Solutions (\"Company,\"\r\n\"we,\" \"us,\" or \"our\"). We want you to be\r\ncompletely satisfied with your purchase. This Return Policy describes the\r\nguidelines and procedures for returning products purchased from the website https://machinetoolsolutions.ca/\r\n(\"Website\").<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Eligibility<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nTo be eligible for a return, the product must be unused, in its original\r\ncondition, and in the original packaging.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nCertain products, such as personalized or customized items, may not be eligible\r\nfor return unless they are defective or damaged upon arrival.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Return Process<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nInitiation: To initiate a return, please contact our customer service team\r\nwithin [number of days] days of receiving the product. You can reach us by\r\n[provide contact information].<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nReturn Authorization: Our customer service team will provide you with a Return\r\nAuthorization (RA) number and instructions for returning the product.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">c.\r\nPackaging: Ensure that the product is securely packaged in its original\r\npackaging or a suitable alternative to prevent damage during transit.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">d.\r\nShipping: You are responsible for the shipping costs associated with the\r\nreturn, unless the product is defective or damaged.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">e.\r\nTracking: We recommend using a trackable shipping method and keeping the tracking\r\nnumber for reference.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">f.\r\nInspection and Refund: Once we receive the returned product, our team will\r\ninspect it for eligibility and condition. If the return is approved, we will\r\ninitiate a refund to your original payment method within [number of days] days.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Non-Returnable Items<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">The\r\nfollowing items are not eligible for return:<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Consumable\r\nproducts or items that cannot be resold due to health and hygiene reasons.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Products\r\nthat have been used, altered, or damaged after delivery.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Customized\r\nor personalized items, unless they are defective or damaged upon arrival.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Damaged or Defective Products<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nIf the product you received is damaged or defective, please contact our\r\ncustomer service team within [number of days] days of receiving the product.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nWe may request evidence, such as photographs or a detailed description, to\r\nassess the damage or defect.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">c.\r\nOnce the damage or defect is verified, we will provide instructions for\r\nreturning the product, and a replacement or refund will be issued, including\r\nany applicable shipping costs.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Refunds<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">a.\r\nRefunds will be issued in the same form of payment used for the original\r\npurchase.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">b.\r\nDepending on your payment provider, it may take additional time for the refund\r\nto be processed and reflected in your account.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">c.\r\nShipping costs, if applicable, are non-refundable unless the return is due to a\r\ndefect or damage.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Exchanges<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">We\r\ncurrently do not offer direct exchanges. If you wish to exchange a product,\r\nplease follow the return process and place a new order for the desired item on\r\nour Website.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Changes to the Return Policy<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">We\r\nreserve the right to modify, update, or replace this Return Policy at any time,\r\nwithout prior notice. The revised policy will be posted on our Website.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><b><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">Contact Us<o:p></o:p></span></b></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">If\r\nyou have any questions or concerns regarding this Return Policy, please contact\r\nour customer service team using the information provided on our Website.<o:p></o:p></span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\"> </span></p>\r\n\r\n<p class=\"MsoNormal\"><span lang=\"EN-CA\" style=\"font-size:12.0pt;line-height:107%\">By\r\nmaking a purchase on our Website, you acknowledge that you have read,\r\nunderstood, and agree to be bound by this Return Policy.<o:p></o:p></span></p>', 1, '2023-07-03 01:25:51', '2024-01-22 16:30:08'),
(16, 'facebook_link', NULL, 1, '2023-07-03 05:45:16', '2024-10-24 23:42:43'),
(17, 'twitter_link', NULL, 1, '2023-07-03 05:45:16', '2024-10-24 23:42:43'),
(18, 'instagram_link', NULL, 1, '2023-07-03 05:45:16', '2024-10-24 23:42:43'),
(19, 'linkedin_link', NULL, 1, '2023-07-03 05:45:16', '2024-10-24 23:42:43'),
(20, 'youtube_link', NULL, 1, '2023-07-03 05:45:16', '2024-10-24 23:42:43'),
(21, 'question_module_preferances', '{\n  \"modules\": [\n    {\n      \"name\": \"Category\",\n      \"module\": \"App\\\\Models\\\\Category\",\n      \"key\": \"id\",\n      \"value\": \"title\",\n      \"is_required\": \"yes\"\n    }\n  ]\n}', 1, '2024-10-31 05:29:13', '2024-10-31 05:29:17');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `order_id` varchar(40) NOT NULL,
  `transaction_id` varchar(256) NOT NULL,
  `amount` double(16,2) NOT NULL DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 1,
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `transaction_id`, `amount`, `status`, `response`, `created_at`, `updated_at`) VALUES
(4, '653208593bbf0-ordr-44709129065161372', '16977777522031249', 8000.00, 1, NULL, '2023-10-20 11:55:53', '2023-10-20 11:55:53'),
(5, '653213f981d6e-ordr-86617870563019208', '16977806530798346', 80.00, 1, '{\"SRD\":\"VFphVzVoZ0J0anViU1kxZg==\",\"CVNRESULT\":\"TQ==\",\"HPP_CUSTOMER_PHONENUMBER_MOBILE\":\"MDE2MjgyOTIwMTU=\",\"HPP_SHIPPING_STREET3\":\"\",\"HPP_SHIPPING_STREET1\":\"RGhhbm1vbmRp\",\"PASREF\":\"MTY5Nzc4MDY1MzA3OTgzNDY=\",\"HPP_SHIPPING_STREET2\":\"\",\"MESSAGE\":\"WyB0ZXN0IHN5c3RlbSBdIEFVVEhPUklTRUQ=\",\"ACCOUNT\":\"aW50ZXJuZXQ=\",\"AVSPOSTCODERESULT\":\"TQ==\",\"AMOUNT\":\"MDgw\",\"TIMESTAMP\":\"MjAyMzEwMjAwNTQyMTU=\",\"pas_uuid\":\"ZWY4YTMyOWEtNDEzMS00M2FlLThlOGUtY2E0MmFmNGE1ZDMy\",\"HPP_BILLING_STREET3\":\"\",\"HPP_ADDRESS_MATCH_INDICATOR\":\"RkFMU0U=\",\"HPP_BILLING_STREET2\":\"\",\"AUTHCODE\":\"MTIzNDU=\",\"HPP_BILLING_STREET1\":\"RGhhbm1vbmRp\",\"HPP_BILLING_CITY\":\"ZGhha2E=\",\"HPP_SHIPPING_COUNTRY\":\"MDUw\",\"AVSADDRESSRESULT\":\"TQ==\",\"HPP_SHIPPING_POSTALCODE\":\"MTIwNQ==\",\"HPP_BILLING_POSTALCODE\":\"MTIzNDU=\",\"HPP_BILLING_COUNTRY\":\"MDUw\",\"BATCHID\":\"MTMxNzkzOQ==\",\"SHA1HASH\":\"YWIyMDYzYTVjYjI2MWVkMjQ5MTQ5MDU1OTBiNGI3ZTk4NzFkZjJhNQ==\",\"HPP_SHIPPING_CITY\":\"RGhha2E=\",\"ORDER_ID\":\"NjUzMjEzMzdlM2U4NS1vcmRyLTExODUwNTI2NDYzMzQ1NDY0\",\"BILLING_CO\":\"QkQ=\",\"HPP_SHIPPING_STATE\":\"RGhhbm1vbmRp\",\"HPP_CUSTOMER_FIRSTNAME\":\"QWxlbg==\",\"HPP_CUSTOMER_EMAIL\":\"ZGV2ZWxvcGVyLmFidXNhaWRAZ21haWwuY29t\",\"HPP_FRAUDFILTER_RESULT\":\"Tk9UX0VYRUNVVEVE\",\"RESULT\":\"MDA=\",\"SHIPPING_CO\":\"QkQ=\",\"MERCHANT_ID\":\"ZGV2Mjg4MjUxMTAyOTEwMTY0MDgx\"}', '2023-10-20 12:45:29', '2023-10-20 12:45:29'),
(6, '658e988a8456a-ordr-38731006787566220', '17038439774535389', 64.00, 1, '{\"SRD\":\"VnI5eDZHYTFTbXhRRHpmOQ==\",\"CVNRESULT\":\"TQ==\",\"HPP_CUSTOMER_PHONENUMBER_MOBILE\":\"MDE2MjgyOTIwMTU=\",\"HPP_SHIPPING_STREET3\":\"\",\"HPP_SHIPPING_STREET1\":\"RGhhbm1vbmRp\",\"PASREF\":\"MTcwMzg0Mzk3NzQ1MzUzODk=\",\"HPP_SHIPPING_STREET2\":\"\",\"MESSAGE\":\"WyB0ZXN0IHN5c3RlbSBdIEFVVEhPUklTRUQ=\",\"ACCOUNT\":\"aW50ZXJuZXQ=\",\"AVSPOSTCODERESULT\":\"TQ==\",\"AMOUNT\":\"MDY0\",\"TIMESTAMP\":\"MjAyMzEyMjkwOTU2MTk=\",\"pas_uuid\":\"YmE0M2ZmMzQtZDE5Yi00NmNiLWExOGYtNDJhNDgzODhkZWRl\",\"HPP_BILLING_STREET3\":\"\",\"HPP_ADDRESS_MATCH_INDICATOR\":\"RkFMU0U=\",\"HPP_BILLING_STREET2\":\"\",\"AUTHCODE\":\"MTIzNDU=\",\"HPP_BILLING_STREET1\":\"RGhhbm1vbmRp\",\"HPP_BILLING_CITY\":\"RGhha2E=\",\"HPP_SHIPPING_COUNTRY\":\"MDUw\",\"AVSADDRESSRESULT\":\"TQ==\",\"HPP_SHIPPING_POSTALCODE\":\"MTIwNQ==\",\"HPP_BILLING_POSTALCODE\":\"MTI3Ng==\",\"HPP_BILLING_COUNTRY\":\"MDUw\",\"BATCHID\":\"MTM0MTEyNg==\",\"SHA1HASH\":\"MjEwMTcyZGIyMmRkMzljYjE4OGQ4ZmY2NTdmNWEwNmRmOTFiYzI3Ng==\",\"HPP_SHIPPING_CITY\":\"RGhha2E=\",\"ORDER_ID\":\"NjU4ZTk3YzM3ZjY1My1vcmRyLTMwOTM1NzQ3MDYwMDg1Nzgx\",\"BILLING_CO\":\"QkQ=\",\"HPP_SHIPPING_STATE\":\"RGhhbm1vbmRp\",\"HPP_CUSTOMER_FIRSTNAME\":\"QWJ1c2FpZCBTaGVpa2g=\",\"HPP_CUSTOMER_EMAIL\":\"ZGV2ZWxvcGVyLmFidXNhaWRAZ21haWwuY29t\",\"HPP_FRAUDFILTER_RESULT\":\"Tk9UX0VYRUNVVEVE\",\"RESULT\":\"MDA=\",\"SHIPPING_CO\":\"QkQ=\",\"MERCHANT_ID\":\"ZGV2Mjg4MjUxMTAyOTEwMTY0MDgx\"}', '2023-12-29 16:59:38', '2023-12-29 16:59:38');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(11) NOT NULL,
  `translatable_type` varchar(256) NOT NULL,
  `translatable_id` varchar(40) NOT NULL,
  `language_code` varchar(40) NOT NULL,
  `field` varchar(256) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`id`, `translatable_type`, `translatable_id`, `language_code`, `field`, `value`, `created_at`, `updated_at`) VALUES
(3, 'App\\Models\\Segmentation', '10', 'en', 'name', 'GK', '2025-01-09 05:36:54', '2025-01-09 05:36:54'),
(4, 'App\\Models\\Segmentation', '11', 'en', 'name', 'Programming', '2025-01-09 05:57:24', '2025-01-09 05:57:24'),
(5, 'App\\Models\\Segmentation', '12', 'en', 'name', 'Java', '2025-01-09 06:00:27', '2025-01-09 06:00:27'),
(6, 'App\\Models\\Segmentation', '13', 'en', 'name', 'Math', '2025-01-09 06:03:06', '2025-01-09 06:03:06'),
(7, 'App\\Models\\Segmentation', '14', 'en', 'name', 'Class 9', '2025-01-09 06:03:18', '2025-01-09 06:03:18'),
(14, 'App\\Models\\QualificationType', '2', 'en', 'name', 'Certification', '2025-01-09 09:54:54', '2025-01-09 10:24:09'),
(22, 'App\\Models\\QualificationType', '9', 'en', 'name', 'Diploma', '2025-01-09 10:22:53', '2025-01-09 10:24:56'),
(25, 'App\\Models\\DeliveryMode', '1', 'en', 'name', 'Online', '2025-01-09 11:04:04', '2025-01-09 11:04:04'),
(26, 'App\\Models\\DeliveryMode', '2', 'en', 'name', 'Offline', '2025-01-09 11:05:27', '2025-01-09 11:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` varchar(40) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `password` text NOT NULL,
  `profile_image` varchar(256) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `ancestor_id` varchar(40) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `phone`, `role`, `password`, `profile_image`, `status`, `ancestor_id`, `created_at`, `updated_at`) VALUES
('1', 'System', 'admin', 'admin@gmail.com', '01847382920', 1, '$2y$10$kJ5cYJxY51rQL5v4aOPUouMLfISAC4uTS6FDHyuo6rXxCKTG0gRs.', '168991522164ba0f55ee7b6Final.jpeg', 1, NULL, '2023-05-07 11:15:50', '2023-09-14 11:58:48'),
('650293876e078-user-92625359878879540', 'Abusaid', 'Sheikh', 'developer.abusaid@gmail.com', '01628292015', 4, '$2y$10$66HqeBK2fisfDZBLsTnvhe97QXZzBmitoSoB4aVIGJlygvlBCdS9m', NULL, 1, NULL, '2023-09-14 12:00:55', '2024-10-25 00:05:08'),
('671b35c254034-user-22672471868631509', 'Shariar', 'Khan', 'hello.ictob@gmail.com', '01407100900', 4, '$2y$10$A9o4U7JqYQqvWLKEHyoccu8TCQTZYDs3Ayvs7oRJ3oXvETOkGPOaG', NULL, 1, NULL, '2024-10-25 00:08:02', '2024-10-25 00:08:15'),
('671dc395dbcc1-user-29761870869707124', 'Takdir', 'Hossain', 'takdir@gmail.com', '01859402938', 7, '$2y$10$.Kc1roC/n1dVcxV1toKMj.Atg.TZr.luLL2qxfHjiDgOp5pxeDZoi', NULL, 1, NULL, '2024-10-26 22:37:41', '2024-10-26 22:37:41'),
('671dd964701e1-user-87459872651545202', 'Arman', 'Lison', 'arman@gmail.com', '01839382928', 2, '$2y$10$h.ppLuqi9FBTNXb/bPK2Duzk3UHdP55Q2BjIHcPm0/fWc2TTmY9XG', NULL, 1, NULL, '2024-10-27 00:10:44', '2024-10-27 00:10:44'),
('677e1e2111194-user-79855310802867056', 'John', 'Doe', 'john.doe@example.com', '1234567890', 4, '$2y$10$0MXPGXli8ziJ4DgiaRciMONkIkxtaPVcG80zocDfMJLU9DL9YrWX6', NULL, 1, NULL, '2025-01-08 06:41:37', '2025-01-08 06:41:37'),
('677e1ecc73c30-user-65403586637187087', 'Carolyn', 'Adkins', 'lemuzuse@mailinator.com', '01756284739', 4, '$2y$10$tHqm96ACV.c0E7vV0Ezl9u0jm263EGnk7/vwXs3XHrmOxND5uGhSa', NULL, 0, NULL, '2025-01-08 06:44:28', '2025-01-08 06:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_exams`
--

CREATE TABLE `user_exams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `organization` text DEFAULT NULL,
  `achieve_mark` double(8,2) DEFAULT NULL,
  `negative_mark` varchar(255) DEFAULT NULL,
  `total_mark` varchar(255) DEFAULT NULL,
  `total_duration` double(8,2) DEFAULT NULL,
  `exam_id` int(11) NOT NULL,
  `correct_answers` int(11) DEFAULT NULL,
  `wrong_answers` int(11) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT current_timestamp(),
  `ended_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `result_published` int(11) NOT NULL DEFAULT 0 COMMENT '0 = started, 1 = summited, 2 = published',
  `position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_exams`
--

INSERT INTO `user_exams` (`id`, `user_id`, `ip_address`, `name`, `phone`, `email`, `organization`, `achieve_mark`, `negative_mark`, `total_mark`, `total_duration`, `exam_id`, `correct_answers`, `wrong_answers`, `started_at`, `ended_at`, `created_at`, `updated_at`, `status`, `result_published`, `position`) VALUES
(1, NULL, '1e3188dd-e628-4c84-a00a-5d81384cf64e', 'Abusaid Sheikh', '01628292015', 'developer.abusaid@gmail.com', 'Nexkraft', 5.00, '0', '30', 0.00, 23, 5, 14, '2024-12-02 15:27:13', '2024-12-02 15:27:13', '2024-12-02 15:26:33', '2024-12-02 15:29:37', 1, 2, 2),
(2, NULL, '8d45bb09-24b5-4b4c-87e4-4d345b0a2f15', 'Lydia Garrett', '01777020313', 'dehetovod@mailinator.com', 'Church and Knowles Inc', 6.00, '0', '30', 0.00, 23, 6, 24, '2024-12-02 15:29:07', '2024-12-02 15:29:07', '2024-12-02 15:28:38', '2024-12-02 15:29:37', 1, 2, 1),
(3, NULL, 'f340290b-f8d8-4435-9802-517b90ef8c7e', 'Edward Keith', '01628292015', 'developer.abusaid@gmail.com', 'Carlson Holloway LLC', 2.00, '0', '10', 0.00, 24, 2, 8, '2024-12-03 05:12:39', '2024-12-03 05:12:39', '2024-12-03 05:12:16', '2024-12-03 05:12:39', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_exam_result_answers`
--

CREATE TABLE `user_exam_result_answers` (
  `id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answear` text DEFAULT NULL,
  `right_wrong` int(11) NOT NULL DEFAULT 0 COMMENT '0 = wrong, 1 = right',
  `marks` float(16,2) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = not given, 1 given',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_exam_result_answers`
--

INSERT INTO `user_exam_result_answers` (`id`, `result_id`, `question_id`, `answear`, `right_wrong`, `marks`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 165, '842', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(2, 1, 146, '751', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(3, 1, 160, '858', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(4, 1, 141, '730', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(5, 1, 158, '805', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(6, 1, 152, '782', 1, 1.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(7, 1, 142, '771', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(8, 1, 136, '718', 1, 1.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(9, 1, 147, '755', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(10, 1, 144, '741', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(11, 1, 166, '847', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(12, 1, 163, '828', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(13, 1, 149, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(14, 1, 148, '760', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(15, 1, 140, '727', 1, 1.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(16, 1, 133, '684', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(17, 1, 143, '739', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(18, 1, 157, '803', 0, 0.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(19, 1, 156, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(20, 1, 154, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(21, 1, 151, '854', 1, 1.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(22, 1, 162, '823', 1, 1.00, 1, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(23, 1, 135, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(24, 1, 150, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(25, 1, 161, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(26, 1, 137, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(27, 1, 164, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(28, 1, 153, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(29, 1, 159, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(30, 1, 155, NULL, 0, 0.00, 0, '2024-12-02 15:27:13', '2024-12-02 15:27:13'),
(31, 2, 147, '753', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(32, 2, 159, '839', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(33, 2, 136, '719', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(34, 2, 164, '830', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(35, 2, 150, '765', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(36, 2, 148, '760', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(37, 2, 144, '741', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(38, 2, 162, '824', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(39, 2, 154, '864', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(40, 2, 155, '794', 1, 1.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(41, 2, 153, '787', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(42, 2, 166, '848', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(43, 2, 146, '752', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(44, 2, 157, '801', 1, 1.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(45, 2, 141, '731', 1, 1.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(46, 2, 152, '783', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(47, 2, 135, '697', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(48, 2, 151, '856', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(49, 2, 156, '851', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(50, 2, 161, '817', 1, 1.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(51, 2, 149, '762', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(52, 2, 165, '844', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(53, 2, 133, '683', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(54, 2, 160, '857', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(55, 2, 158, '806', 1, 1.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(56, 2, 137, '721', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(57, 2, 143, '737', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(58, 2, 163, '825', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(59, 2, 140, '727', 1, 1.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(60, 2, 142, '772', 0, 0.00, 1, '2024-12-02 15:29:07', '2024-12-02 15:29:07'),
(61, 3, 163, '826', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(62, 3, 148, '758', 1, 1.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(63, 3, 155, '794', 1, 1.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(64, 3, 158, '808', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(65, 3, 142, '769', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(66, 3, 164, '829', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(67, 3, 162, '824', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(68, 3, 141, '730', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(69, 3, 137, '721', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39'),
(70, 3, 153, '787', 0, 0.00, 1, '2024-12-03 05:12:39', '2024-12-03 05:12:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` varchar(40) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute`
--
ALTER TABLE `attribute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catalog`
--
ALTER TABLE `catalog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`,`brand_id`,`product_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `title` (`title`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `company_product`
--
ALTER TABLE `company_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_option`
--
ALTER TABLE `custom_option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `value` (`value`);

--
-- Indexes for table `delivery_mode`
--
ALTER TABLE `delivery_mode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_questions`
--
ALTER TABLE `exam_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_question_options`
--
ALTER TABLE `exam_question_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiry_product`
--
ALTER TABLE `inquiry_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`inquiry_id`,`product_id`,`company_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`) USING BTREE,
  ADD KEY `sender` (`sender`,`receiver`),
  ADD KEY `receiver` (`receiver`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`title`),
  ADD KEY `category` (`category`),
  ADD KEY `publish_date` (`publish_date`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receiver` (`receiver`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`,`product_id`,`company_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `otp`
--
ALTER TABLE `otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parts_attribute`
--
ALTER TABLE `parts_attribute`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`,`part_id`),
  ADD KEY `product_id` (`part_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`,`category_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `name` (`name`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `product_attribute`
--
ALTER TABLE `product_attribute`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_id` (`attribute_id`,`product_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `sub_option` (`sub_option`),
  ADD KEY `attribute_name` (`attribute_name`),
  ADD KEY `value` (`value`);

--
-- Indexes for table `product_part`
--
ALTER TABLE `product_part`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`,`product_id`),
  ADD KEY `category_id` (`product_id`),
  ADD KEY `name` (`name`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `qualification_type`
--
ALTER TABLE `qualification_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `right`
--
ALTER TABLE `right`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_right`
--
ALTER TABLE `role_right`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`,`right_id`),
  ADD KEY `right_id` (`right_id`);

--
-- Indexes for table `segmentations`
--
ALTER TABLE `segmentations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_order`
--
ALTER TABLE `service_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`key`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field` (`field`),
  ADD KEY `translatable_id` (`translatable_id`),
  ADD KEY `translatable_type` (`translatable_type`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- Indexes for table `user_exams`
--
ALTER TABLE `user_exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_exam_result_answers`
--
ALTER TABLE `user_exam_result_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_product`
--
ALTER TABLE `company_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `custom_option`
--
ALTER TABLE `custom_option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `delivery_mode`
--
ALTER TABLE `delivery_mode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `exam_questions`
--
ALTER TABLE `exam_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_question_options`
--
ALTER TABLE `exam_question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inquiry_product`
--
ALTER TABLE `inquiry_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `otp`
--
ALTER TABLE `otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `parts_attribute`
--
ALTER TABLE `parts_attribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1790;

--
-- AUTO_INCREMENT for table `product_attribute`
--
ALTER TABLE `product_attribute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `qualification_type`
--
ALTER TABLE `qualification_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=865;

--
-- AUTO_INCREMENT for table `resource`
--
ALTER TABLE `resource`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `right`
--
ALTER TABLE `right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `role_right`
--
ALTER TABLE `role_right`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=526;

--
-- AUTO_INCREMENT for table `segmentations`
--
ALTER TABLE `segmentations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `user_exams`
--
ALTER TABLE `user_exams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_exam_result_answers`
--
ALTER TABLE `user_exam_result_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
