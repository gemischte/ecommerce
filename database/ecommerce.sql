-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 04:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `category_name` enum('Adult','Computer Components','Electronics','Clothing','Gaming Accessories','Laptop','Computer','Virtual') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `product_id`, `category_name`) VALUES
(1, 12462, 'Adult'),
(2, 16933, 'Electronics'),
(3, 17865, 'Clothing'),
(4, 27643, 'Clothing'),
(5, 38910, 'Clothing'),
(6, 40870, 'Adult'),
(7, 41516, 'Computer Components'),
(8, 42043, 'Gaming Accessories'),
(9, 45129, 'Electronics'),
(10, 50465, 'Adult'),
(11, 50643, 'Computer Components'),
(12, 53859, 'Computer'),
(13, 54321, 'Adult'),
(14, 62987, 'Laptop'),
(15, 67234, 'Laptop'),
(16, 75966, 'Laptop'),
(17, 91625, 'Gaming Accessories'),
(18, 93038, 'Electronics'),
(19, 93586, 'Computer Components'),
(20, 95002, 'Computer Components'),
(21, 78577, 'Virtual');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `iso2` char(2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `calling_codes` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `iso2`, `created_at`, `calling_codes`) VALUES
(1, 'Afghanistan', 'AF', '2026-01-01 18:03:26', '+93'),
(2, 'Åland Islands', 'AX', '2026-01-01 18:03:26', '+358'),
(3, 'Albania', 'AL', '2026-01-01 18:03:26', '+355'),
(4, 'Algeria', 'DZ', '2026-01-01 18:03:26', '+213'),
(5, 'American Samoa', 'AS', '2026-01-01 18:03:26', '+1'),
(6, 'Andorra', 'AD', '2026-01-01 18:03:26', '+376'),
(7, 'Angola', 'AO', '2026-01-01 18:03:26', '+244'),
(8, 'Anguilla', 'AI', '2026-01-01 18:03:26', '+1'),
(9, 'Antarctica', 'AQ', '2026-01-01 18:03:26', '+672'),
(10, 'Antigua and Barbuda', 'AG', '2026-01-01 18:03:26', '+1'),
(11, 'Argentina', 'AR', '2026-01-01 18:03:26', '+54'),
(12, 'Armenia', 'AM', '2026-01-01 18:03:26', '+374'),
(13, 'Aruba', 'AW', '2026-01-01 18:03:26', '+297'),
(14, 'Australia', 'AU', '2026-01-01 18:03:26', '+61'),
(15, 'Austria', 'AT', '2026-01-01 18:03:26', '+43'),
(16, 'Azerbaijan', 'AZ', '2026-01-01 18:03:26', '+994'),
(17, 'Bahamas', 'BS', '2026-01-01 18:03:26', '+1'),
(18, 'Bahrain', 'BH', '2026-01-01 18:03:26', '+973'),
(19, 'Bangladesh', 'BD', '2026-01-01 18:03:26', '+880'),
(20, 'Barbados', 'BB', '2026-01-01 18:03:26', '+1'),
(21, 'Belarus', 'BY', '2026-01-01 18:03:26', '+375'),
(22, 'Belgium', 'BE', '2026-01-01 18:03:26', '+32'),
(23, 'Belize', 'BZ', '2026-01-01 18:03:26', '+501'),
(24, 'Benin', 'BJ', '2026-01-01 18:03:26', '+229'),
(25, 'Bermuda', 'BM', '2026-01-01 18:03:26', '+1'),
(26, 'Bhutan', 'BT', '2026-01-01 18:03:26', '+975'),
(27, 'Bolivia (Plurinational State of)', 'BO', '2026-01-01 18:03:26', '+591'),
(28, 'Bonaire, Sint Eustatius and Saba', 'BQ', '2026-01-01 18:03:26', '+599'),
(29, 'Bosnia and Herzegovina', 'BA', '2026-01-01 18:03:26', '+387'),
(30, 'Botswana', 'BW', '2026-01-01 18:03:26', '+267'),
(31, 'Bouvet Island', 'BV', '2026-01-01 18:03:26', '+47'),
(32, 'Brazil', 'BR', '2026-01-01 18:03:26', '+55'),
(33, 'British Indian Ocean Territory', 'IO', '2026-01-01 18:03:26', '+246'),
(34, 'United States Minor Outlying Islands', 'UM', '2026-01-01 18:03:26', '+246'),
(35, 'Virgin Islands (British)', 'VG', '2026-01-01 18:03:26', '+1'),
(36, 'Virgin Islands (U.S.)', 'VI', '2026-01-01 18:03:26', '+1 340'),
(37, 'Brunei Darussalam', 'BN', '2026-01-01 18:03:26', '+673'),
(38, 'Bulgaria', 'BG', '2026-01-01 18:03:26', '+359'),
(39, 'Burkina Faso', 'BF', '2026-01-01 18:03:26', '+226'),
(40, 'Burundi', 'BI', '2026-01-01 18:03:26', '+257'),
(41, 'Cambodia', 'KH', '2026-01-01 18:03:26', '+855'),
(42, 'Cameroon', 'CM', '2026-01-01 18:03:26', '+237'),
(43, 'Canada', 'CA', '2026-01-01 18:03:26', '+1'),
(44, 'Cabo Verde', 'CV', '2026-01-01 18:03:26', '+238'),
(45, 'Cayman Islands', 'KY', '2026-01-01 18:03:26', '+1'),
(46, 'Central African Republic', 'CF', '2026-01-01 18:03:26', '+236'),
(47, 'Chad', 'TD', '2026-01-01 18:03:26', '+235'),
(48, 'Chile', 'CL', '2026-01-01 18:03:26', '+56'),
(49, 'China', 'CN', '2026-01-01 18:03:26', '+86'),
(50, 'Christmas Island', 'CX', '2026-01-01 18:03:26', '+61'),
(51, 'Cocos (Keeling) Islands', 'CC', '2026-01-01 18:03:26', '+61'),
(52, 'Colombia', 'CO', '2026-01-01 18:03:26', '+57'),
(53, 'Comoros', 'KM', '2026-01-01 18:03:26', '+269'),
(54, 'Congo', 'CG', '2026-01-01 18:03:26', '+242'),
(55, 'Congo (Democratic Republic of the)', 'CD', '2026-01-01 18:03:26', '+243'),
(56, 'Cook Islands', 'CK', '2026-01-01 18:03:26', '+682'),
(57, 'Costa Rica', 'CR', '2026-01-01 18:03:26', '+506'),
(58, 'Croatia', 'HR', '2026-01-01 18:03:26', '+385'),
(59, 'Cuba', 'CU', '2026-01-01 18:03:26', '+53'),
(60, 'Curaçao', 'CW', '2026-01-01 18:03:26', '+599'),
(61, 'Cyprus', 'CY', '2026-01-01 18:03:26', '+357'),
(62, 'Czech Republic', 'CZ', '2026-01-01 18:03:26', '+420'),
(63, 'Denmark', 'DK', '2026-01-01 18:03:26', '+45'),
(64, 'Djibouti', 'DJ', '2026-01-01 18:03:26', '+253'),
(65, 'Dominica', 'DM', '2026-01-01 18:03:26', '+1'),
(66, 'Dominican Republic', 'DO', '2026-01-01 18:03:26', '+1'),
(67, 'Ecuador', 'EC', '2026-01-01 18:03:26', '+593'),
(68, 'Egypt', 'EG', '2026-01-01 18:03:26', '+20'),
(69, 'El Salvador', 'SV', '2026-01-01 18:03:26', '+503'),
(70, 'Equatorial Guinea', 'GQ', '2026-01-01 18:03:26', '+240'),
(71, 'Eritrea', 'ER', '2026-01-01 18:03:26', '+291'),
(72, 'Estonia', 'EE', '2026-01-01 18:03:26', '+372'),
(73, 'Ethiopia', 'ET', '2026-01-01 18:03:26', '+251'),
(74, 'Falkland Islands (Malvinas)', 'FK', '2026-01-01 18:03:26', '+500'),
(75, 'Faroe Islands', 'FO', '2026-01-01 18:03:26', '+298'),
(76, 'Fiji', 'FJ', '2026-01-01 18:03:26', '+679'),
(77, 'Finland', 'FI', '2026-01-01 18:03:26', '+358'),
(78, 'France', 'FR', '2026-01-01 18:03:26', '+33'),
(79, 'French Guiana', 'GF', '2026-01-01 18:03:26', '+594'),
(80, 'French Polynesia', 'PF', '2026-01-01 18:03:26', '+689'),
(81, 'French Southern Territories', 'TF', '2026-01-01 18:03:26', '+262'),
(82, 'Gabon', 'GA', '2026-01-01 18:03:26', '+241'),
(83, 'Gambia', 'GM', '2026-01-01 18:03:26', '+220'),
(84, 'Georgia', 'GE', '2026-01-01 18:03:26', '+995'),
(85, 'Germany', 'DE', '2026-01-01 18:03:26', '+49'),
(86, 'Ghana', 'GH', '2026-01-01 18:03:26', '+233'),
(87, 'Gibraltar', 'GI', '2026-01-01 18:03:26', '+350'),
(88, 'Greece', 'GR', '2026-01-01 18:03:26', '+30'),
(89, 'Greenland', 'GL', '2026-01-01 18:03:26', '+299'),
(90, 'Grenada', 'GD', '2026-01-01 18:03:26', '+1'),
(91, 'Guadeloupe', 'GP', '2026-01-01 18:03:26', '+590'),
(92, 'Guam', 'GU', '2026-01-01 18:03:26', '+1'),
(93, 'Guatemala', 'GT', '2026-01-01 18:03:26', '+502'),
(94, 'Guernsey', 'GG', '2026-01-01 18:03:26', '+44'),
(95, 'Guinea', 'GN', '2026-01-01 18:03:26', '+224'),
(96, 'Guinea-Bissau', 'GW', '2026-01-01 18:03:26', '+245'),
(97, 'Guyana', 'GY', '2026-01-01 18:03:26', '+592'),
(98, 'Haiti', 'HT', '2026-01-01 18:03:26', '+509'),
(99, 'Heard Island and McDonald Islands', 'HM', '2026-01-01 18:03:26', '+672'),
(100, 'Vatican City', 'VA', '2026-01-01 18:03:26', '+379'),
(101, 'Honduras', 'HN', '2026-01-01 18:03:26', '+504'),
(102, 'Hungary', 'HU', '2026-01-01 18:03:26', '+36'),
(103, 'Hong Kong', 'HK', '2026-01-01 18:03:26', '+852'),
(104, 'Iceland', 'IS', '2026-01-01 18:03:26', '+354'),
(105, 'India', 'IN', '2026-01-01 18:03:26', '+91'),
(106, 'Indonesia', 'ID', '2026-01-01 18:03:26', '+62'),
(107, 'Ivory Coast', 'CI', '2026-01-01 18:03:26', '+225'),
(108, 'Iran (Islamic Republic of)', 'IR', '2026-01-01 18:03:26', '+98'),
(109, 'Iraq', 'IQ', '2026-01-01 18:03:26', '+964'),
(110, 'Ireland', 'IE', '2026-01-01 18:03:26', '+353'),
(111, 'Isle of Man', 'IM', '2026-01-01 18:03:26', '+44'),
(112, 'Israel', 'IL', '2026-01-01 18:03:26', '+972'),
(113, 'Italy', 'IT', '2026-01-01 18:03:26', '+39'),
(114, 'Jamaica', 'JM', '2026-01-01 18:03:26', '+1'),
(115, 'Japan', 'JP', '2026-01-01 18:03:26', '+81'),
(116, 'Jersey', 'JE', '2026-01-01 18:03:26', '+44'),
(117, 'Jordan', 'JO', '2026-01-01 18:03:26', '+962'),
(118, 'Kazakhstan', 'KZ', '2026-01-01 18:03:26', '+76'),
(119, 'Kenya', 'KE', '2026-01-01 18:03:26', '+254'),
(120, 'Kiribati', 'KI', '2026-01-01 18:03:26', '+686'),
(121, 'Kuwait', 'KW', '2026-01-01 18:03:26', '+965'),
(122, 'Kyrgyzstan', 'KG', '2026-01-01 18:03:26', '+996'),
(123, 'Lao People\'s Democratic Republic', 'LA', '2026-01-01 18:03:26', '+856'),
(124, 'Latvia', 'LV', '2026-01-01 18:03:26', '+371'),
(125, 'Lebanon', 'LB', '2026-01-01 18:03:26', '+961'),
(126, 'Lesotho', 'LS', '2026-01-01 18:03:26', '+266'),
(127, 'Liberia', 'LR', '2026-01-01 18:03:26', '+231'),
(128, 'Libya', 'LY', '2026-01-01 18:03:26', '+218'),
(129, 'Liechtenstein', 'LI', '2026-01-01 18:03:26', '+423'),
(130, 'Lithuania', 'LT', '2026-01-01 18:03:26', '+370'),
(131, 'Luxembourg', 'LU', '2026-01-01 18:03:26', '+352'),
(132, 'Macao', 'MO', '2026-01-01 18:03:26', '+853'),
(133, 'North Macedonia', 'MK', '2026-01-01 18:03:26', '+389'),
(134, 'Madagascar', 'MG', '2026-01-01 18:03:26', '+261'),
(135, 'Malawi', 'MW', '2026-01-01 18:03:26', '+265'),
(136, 'Malaysia', 'MY', '2026-01-01 18:03:26', '+60'),
(137, 'Maldives', 'MV', '2026-01-01 18:03:26', '+960'),
(138, 'Mali', 'ML', '2026-01-01 18:03:26', '+223'),
(139, 'Malta', 'MT', '2026-01-01 18:03:26', '+356'),
(140, 'Marshall Islands', 'MH', '2026-01-01 18:03:26', '+692'),
(141, 'Martinique', 'MQ', '2026-01-01 18:03:26', '+596'),
(142, 'Mauritania', 'MR', '2026-01-01 18:03:26', '+222'),
(143, 'Mauritius', 'MU', '2026-01-01 18:03:26', '+230'),
(144, 'Mayotte', 'YT', '2026-01-01 18:03:26', '+262'),
(145, 'Mexico', 'MX', '2026-01-01 18:03:26', '+52'),
(146, 'Micronesia (Federated States of)', 'FM', '2026-01-01 18:03:26', '+691'),
(147, 'Moldova (Republic of)', 'MD', '2026-01-01 18:03:26', '+373'),
(148, 'Monaco', 'MC', '2026-01-01 18:03:26', '+377'),
(149, 'Mongolia', 'MN', '2026-01-01 18:03:26', '+976'),
(150, 'Montenegro', 'ME', '2026-01-01 18:03:26', '+382'),
(151, 'Montserrat', 'MS', '2026-01-01 18:03:26', '+1'),
(152, 'Morocco', 'MA', '2026-01-01 18:03:26', '+212'),
(153, 'Mozambique', 'MZ', '2026-01-01 18:03:26', '+258'),
(154, 'Myanmar', 'MM', '2026-01-01 18:03:26', '+95'),
(155, 'Namibia', 'NA', '2026-01-01 18:03:26', '+264'),
(156, 'Nauru', 'NR', '2026-01-01 18:03:26', '+674'),
(157, 'Nepal', 'NP', '2026-01-01 18:03:26', '+977'),
(158, 'Netherlands', 'NL', '2026-01-01 18:03:26', '+31'),
(159, 'New Caledonia', 'NC', '2026-01-01 18:03:26', '+687'),
(160, 'New Zealand', 'NZ', '2026-01-01 18:03:26', '+64'),
(161, 'Nicaragua', 'NI', '2026-01-01 18:03:26', '+505'),
(162, 'Niger', 'NE', '2026-01-01 18:03:26', '+227'),
(163, 'Nigeria', 'NG', '2026-01-01 18:03:26', '+234'),
(164, 'Niue', 'NU', '2026-01-01 18:03:26', '+683'),
(165, 'Norfolk Island', 'NF', '2026-01-01 18:03:26', '+672'),
(166, 'Korea (Democratic People\'s Republic of)', 'KP', '2026-01-01 18:03:26', '+850'),
(167, 'Northern Mariana Islands', 'MP', '2026-01-01 18:03:26', '+1'),
(168, 'Norway', 'NO', '2026-01-01 18:03:26', '+47'),
(169, 'Oman', 'OM', '2026-01-01 18:03:26', '+968'),
(170, 'Pakistan', 'PK', '2026-01-01 18:03:26', '+92'),
(171, 'Palau', 'PW', '2026-01-01 18:03:26', '+680'),
(172, 'Palestine, State of', 'PS', '2026-01-01 18:03:26', '+970'),
(173, 'Panama', 'PA', '2026-01-01 18:03:26', '+507'),
(174, 'Papua New Guinea', 'PG', '2026-01-01 18:03:26', '+675'),
(175, 'Paraguay', 'PY', '2026-01-01 18:03:26', '+595'),
(176, 'Peru', 'PE', '2026-01-01 18:03:26', '+51'),
(177, 'Philippines', 'PH', '2026-01-01 18:03:26', '+63'),
(178, 'Pitcairn', 'PN', '2026-01-01 18:03:26', '+64'),
(179, 'Poland', 'PL', '2026-01-01 18:03:26', '+48'),
(180, 'Portugal', 'PT', '2026-01-01 18:03:26', '+351'),
(181, 'Puerto Rico', 'PR', '2026-01-01 18:03:26', '+1'),
(182, 'Qatar', 'QA', '2026-01-01 18:03:26', '+974'),
(183, 'Republic of Kosovo', 'XK', '2026-01-01 18:03:26', '+383'),
(184, 'Réunion', 'RE', '2026-01-01 18:03:26', '+262'),
(185, 'Romania', 'RO', '2026-01-01 18:03:26', '+40'),
(186, 'Russian Federation', 'RU', '2026-01-01 18:03:26', '+7'),
(187, 'Rwanda', 'RW', '2026-01-01 18:03:26', '+250'),
(188, 'Saint Barthélemy', 'BL', '2026-01-01 18:03:26', '+590'),
(189, 'Saint Helena, Ascension and Tristan da Cunha', 'SH', '2026-01-01 18:03:26', '+290'),
(190, 'Saint Kitts and Nevis', 'KN', '2026-01-01 18:03:26', '+1'),
(191, 'Saint Lucia', 'LC', '2026-01-01 18:03:26', '+1'),
(192, 'Saint Martin (French part)', 'MF', '2026-01-01 18:03:26', '+590'),
(193, 'Saint Pierre and Miquelon', 'PM', '2026-01-01 18:03:26', '+508'),
(194, 'Saint Vincent and the Grenadines', 'VC', '2026-01-01 18:03:26', '+1'),
(195, 'Samoa', 'WS', '2026-01-01 18:03:26', '+685'),
(196, 'San Marino', 'SM', '2026-01-01 18:03:26', '+378'),
(197, 'Sao Tome and Principe', 'ST', '2026-01-01 18:03:26', '+239'),
(198, 'Saudi Arabia', 'SA', '2026-01-01 18:03:26', '+966'),
(199, 'Senegal', 'SN', '2026-01-01 18:03:26', '+221'),
(200, 'Serbia', 'RS', '2026-01-01 18:03:26', '+381'),
(201, 'Seychelles', 'SC', '2026-01-01 18:03:26', '+248'),
(202, 'Sierra Leone', 'SL', '2026-01-01 18:03:26', '+232'),
(203, 'Singapore', 'SG', '2026-01-01 18:03:26', '+65'),
(204, 'Sint Maarten (Dutch part)', 'SX', '2026-01-01 18:03:26', '+1'),
(205, 'Slovakia', 'SK', '2026-01-01 18:03:26', '+421'),
(206, 'Slovenia', 'SI', '2026-01-01 18:03:26', '+386'),
(207, 'Solomon Islands', 'SB', '2026-01-01 18:03:26', '+677'),
(208, 'Somalia', 'SO', '2026-01-01 18:03:26', '+252'),
(209, 'South Africa', 'ZA', '2026-01-01 18:03:26', '+27'),
(210, 'South Georgia and the South Sandwich Islands', 'GS', '2026-01-01 18:03:26', '+500'),
(211, 'Korea (Republic of)', 'KR', '2026-01-01 18:03:26', '+82'),
(212, 'Spain', 'ES', '2026-01-01 18:03:26', '+34'),
(213, 'Sri Lanka', 'LK', '2026-01-01 18:03:26', '+94'),
(214, 'Sudan', 'SD', '2026-01-01 18:03:26', '+249'),
(215, 'South Sudan', 'SS', '2026-01-01 18:03:26', '+211'),
(216, 'Suriname', 'SR', '2026-01-01 18:03:26', '+597'),
(217, 'Svalbard and Jan Mayen', 'SJ', '2026-01-01 18:03:26', '+47'),
(218, 'Swaziland', 'SZ', '2026-01-01 18:03:26', '+268'),
(219, 'Sweden', 'SE', '2026-01-01 18:03:26', '+46'),
(220, 'Switzerland', 'CH', '2026-01-01 18:03:26', '+41'),
(221, 'Syrian Arab Republic', 'SY', '2026-01-01 18:03:26', '+963'),
(222, 'Taiwan', 'TW', '2026-01-01 18:03:26', '+886'),
(223, 'Tajikistan', 'TJ', '2026-01-01 18:03:26', '+992'),
(224, 'Tanzania, United Republic of', 'TZ', '2026-01-01 18:03:26', '+255'),
(225, 'Thailand', 'TH', '2026-01-01 18:03:26', '+66'),
(226, 'Timor-Leste', 'TL', '2026-01-01 18:03:26', '+670'),
(227, 'Togo', 'TG', '2026-01-01 18:03:26', '+228'),
(228, 'Tokelau', 'TK', '2026-01-01 18:03:26', '+690'),
(229, 'Tonga', 'TO', '2026-01-01 18:03:26', '+676'),
(230, 'Trinidad and Tobago', 'TT', '2026-01-01 18:03:26', '+1'),
(231, 'Tunisia', 'TN', '2026-01-01 18:03:26', '+216'),
(232, 'Turkey', 'TR', '2026-01-01 18:03:26', '+90'),
(233, 'Turkmenistan', 'TM', '2026-01-01 18:03:26', '+993'),
(234, 'Turks and Caicos Islands', 'TC', '2026-01-01 18:03:26', '+1'),
(235, 'Tuvalu', 'TV', '2026-01-01 18:03:26', '+688'),
(236, 'Uganda', 'UG', '2026-01-01 18:03:26', '+256'),
(237, 'Ukraine', 'UA', '2026-01-01 18:03:26', '+380'),
(238, 'United Arab Emirates', 'AE', '2026-01-01 18:03:26', '+971'),
(239, 'United Kingdom of Great Britain and Northern Ireland', 'GB', '2026-01-01 18:03:26', '+44'),
(240, 'United States of America', 'US', '2026-01-01 18:03:26', '+1'),
(241, 'Uruguay', 'UY', '2026-01-01 18:03:26', '+598'),
(242, 'Uzbekistan', 'UZ', '2026-01-01 18:03:26', '+998'),
(243, 'Vanuatu', 'VU', '2026-01-01 18:03:26', '+678'),
(244, 'Venezuela (Bolivarian Republic of)', 'VE', '2026-01-01 18:03:26', '+58'),
(245, 'Vietnam', 'VN', '2026-01-01 18:03:26', '+84'),
(246, 'Wallis and Futuna', 'WF', '2026-01-01 18:03:26', '+681'),
(247, 'Western Sahara', 'EH', '2026-01-01 18:03:26', '+212'),
(248, 'Yemen', 'YE', '2026-01-01 18:03:26', '+967'),
(249, 'Zambia', 'ZM', '2026-01-01 18:03:26', '+260'),
(250, 'Zimbabwe', 'ZW', '2026-01-01 18:03:26', '+263');

-- --------------------------------------------------------

--
-- Table structure for table `orders_info`
--

CREATE TABLE `orders_info` (
  `orders_id` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `orders_created_at` datetime NOT NULL,
  `payment_method` enum('Credit Card','PayPal','Bank Transfer','Cash on delivery') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders_info`
--

INSERT INTO `orders_info` (`orders_id`, `country`, `city`, `address`, `postal_code`, `orders_created_at`, `payment_method`) VALUES
('ORD-2025-06-683c10bf4f591-9e44f01d', 'United States of America', 'Cupertino', 'one apple park way California', '95014', '2025-06-01 10:35:11', 'Credit Card');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `orders_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`user_id`, `orders_id`, `product_id`, `quantity`, `price`) VALUES
('user_a3f8b7c15d2e4e7a9f4c6b3a8d7e1c0b', 'ORD-2025-06-683c10bf4f591-9e44f01d', 12462, 4, 105),
('user_a3f8b7c15d2e4e7a9f4c6b3a8d7e1c0b', 'ORD-2025-06-683c10bf4f591-9e44f01d', 16933, 5, 7088),
('user_a3f8b7c15d2e4e7a9f4c6b3a8d7e1c0b', 'ORD-2025-06-683c10bf4f591-9e44f01d', 38910, 1, 410),
('user_a3f8b7c15d2e4e7a9f4c6b3a8d7e1c0b', 'ORD-2025-06-683c10bf4f591-9e44f01d', 93038, 3, 3150);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_images` varchar(255) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `brand` varchar(20) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `star` decimal(2,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_images`, `description`, `brand`, `original_price`, `price`, `stock`, `star`) VALUES
(12462, 'Durex KY Jelly 100g Water-based Lubricant', 'https://ukdirectbd.com/wp-content/uploads/2022/08/ddd.png', 'It is a moist, colourless, odourless and non-greasy water-based gel that reduces friction to help protect you from intimate discomfort.', 'Durex', 30.00, 25.00, 300, 4.5),
(16933, 'Apple Watch Series 10', 'https://http2.mlstatic.com/D_NQ_NP_929358-MLA80684151617_112024-O.webp', 'Apple Watch Series 10. Our thinnest watch with our biggest display. Health insights including sleep apnea notifications. Our fastest-charging watch.', 'Apple', 1500.00, 1350.00, 429, 4.0),
(17865, 'Essentials Linear Duffel Bag Medium', 'https://m.media-amazon.com/images/I/71NTdyIuaRL.jpg', 'Ideal for gym trips and weekend getaways. This adidas duffel bag has a sturdy base to protect your things.', 'Adidas', 45.00, 39.00, 164, 4.0),
(27643, 'Adicolor Classics 3-Stripes Tee', 'https://m.media-amazon.com/images/I/71HYdxu4IkL.jpg', 'Meet your new favorite tee. This classic adidas t-shirt boasts a slim fit and a contrast hem for some refined vintage vibes.', 'Adidas', 65.00, 50.00, 848, 3.7),
(38910, 'Adidas Ultraboost 22', 'https://m.media-amazon.com/images/I/61aXSkFNTUL.jpg', 'The upper of this shoe contains a minimum of 30% natural and renewable materials to design out finite resources and help end plastic waste.', 'Adidas', 150.00, 135.00, 456, 4.7),
(40870, 'Durex Sensory Sleeve', 'https://m.media-amazon.com/images/I/81Fhxy5BanL._AC_UF894,1000_QL80_.jpg', 'Designed with your ultimate pleasure in mind, this sleeve is a symphony of sensations, soft, stretchy, textured, and dotted – every inch crafted to ignite your desires and bring your fantasies to life.', 'Durex', 135.00, 100.00, 276, 4.3),
(41516, 'Samsung SSD 980 PRO', 'https://cdn.mos.cms.futurecdn.net/AtbWMDpo6Pfr9SJ34SqFeL.jpg', 'Powered by Samsung in-house controller for pcie® 4.0 SSD, the 980 PRO is optimized for speed. It delivers read speeds up to 7,000 MB/s, making it 2 times faster than PCIe® 3.0 SSDs and 12.7 times faster than SATA SSDs.', 'Samsung', 999.00, 642.00, 685, 4.5),
(42043, 'Razer BlackWidow V4 Pro', 'https://m.media-amazon.com/images/I/81Wsrt05uLL._AC_UF894,1000_QL80_.jpg', 'Mechanical gaming keyboard with Razer Command Dial, 8 macro keys, and RGB lighting for enhanced control and an unparalleled gaming experience.', 'Razer', 260.00, 255.00, 601, 4.1),
(45129, 'Samsung Galaxy S25 Ultra', 'https://cdn.mos.cms.futurecdn.net/QRZxiRWrMG2NpMauVPf5aB.jpg', 'The Samsung Galaxy S25 is a series of high-end Android-based smartphones developed and marketed by Samsung Electronics as part of its flagship Galaxy S Series.', 'Samsung', 3000.00, 2800.00, 949, 4.9),
(50465, 'Durex Condoms', 'https://gordonsdirect.com/cdn/shop/products/durex-pleasure-me_1.jpg?v=1620736676', 'Durex Extra Safe condoms are designed for people who need reassurance that the condom they are using is really safe.', 'Durex', 60.00, 55.00, 944, 5.0),
(50643, 'Nvidia RTX 4070 Ti', 'https://miro.medium.com/v2/resize:fit:1400/0*WYxozWGhzEL8erIU.jpg', 'Built on the 5 nm process, and based on the AD104 graphics processor, in its AD104-400-A1 variant, the card supports DirectX 12 Ultimate. This ensures that all modern games will run on GeForce RTX 4070 Ti.', 'Nvidia', 1760.00, 1565.00, 877, 4.7),
(53859, 'ACER C24-195ES All-in-One', 'https://down-tw.img.susercontent.com/file/tw-11134207-7rasm-m4pguhy409b167', 'Equipped with an Intel Core Ultra 5 CPU with AI Boost NPU, plus 8GB of RAM, it can easily handle a wide range of tasks. You also get a huge 1TB SSD for all your files.', 'Acer', 0.00, 700.00, 555, 0.0),
(54321, 'Durex 2-in-1 Vibrator', 'https://m.media-amazon.com/images/I/81xFovOzpYL.jpg', 'One of the standout features of this vibrator is its 2-in-1 design. It offers dual functionality – providing both internal stimulation and external teasing.', 'Durex', 400.00, 350.00, 143, 4.2),
(62987, 'Acer Predator Helios 300', 'https://m.media-amazon.com/images/I/81g7AiqWrtL.jpg', 'This 11th Gen gaming laptop is equipped with GeForce RTX™ 30 Series graphics and an RGB backlit keyboard to deliver the high-end performance you want and need.', 'Acer', 45000.00, 43000.00, 10, 4.5),
(67234, 'MacBook Pro M4', 'https://www.apple.com/newsroom/images/2024/10/new-macbook-pro/tile/Apple-MacBook-Pro-M4-lineup-lp.jpg.og.jpg?202505081856', 'Phenomenal single- and multithreaded CPU performance, faster unified memory, enhanced machine learning accelerators — the M4 family of chips gives you the kind of speed and capability you’ve never thought possible.', 'Apple', 3500.00, 3000.00, 824, 4.8),
(75966, 'Acer Nitro AN515', 'https://s.yimg.com/zp/MerchandiseImages/387C4AD3AB-SP-11389424.jpg', 'Acer Nitro 5 Gaming Laptop, 9th Gen Intel Core i5-9300H, NVIDIA GeForce GTX 1650, 15.6\" Full HD IPS Display, 8GB DDR4, 256GB NVMe SSD, Wi-Fi 6, Backlit Keyboard, Alexa Built-in, AN515-54-5812.', 'Acer', 48500.00, 45000.00, 973, 4.3),
(78577, 'YouTube Premium', 'https://www.gstatic.com/youtube/img/promos/growth/YTP_logo_social_1200x630.png?days_since_epoch=20242', 'It includes ad-free viewing, the ability to download videos for offline viewing, background play, and access to YouTube Music Premium, which offers ad-free music listening and offline downloads.', 'Others', 25.00, 20.00, 999, 4.6),
(91625, 'Razer Basilisk Ultimate', 'https://assets2.razerzone.com/images/pnx.assets/08f14e963f0f7935f138ac2d2c5387f9/basilisk-ultimate-gallery-3.jpg', 'The new and improved Razer Basilisk Ultimate is the most customizable wireless mouse perfect for FPS games. It comes with 11 programmable buttons, a tilt click scroll wheel paired with a dial to adjust the scroll wheel resistance.', 'Razer', 750.00, 640.00, 394, 4.9),
(93038, 'iphone16 pro', 'https://file1.jyes.com.tw/data/goods/gallery/202409/1725948365064624965.jpg', 'Splash, Water, and Dust Resistant3\r\nRated IP68 (maximum depth of 6 meters up to 30 minutes) under IEC standard 60529', 'Apple', 0.00, 1000.00, 50, 4.5),
(93586, 'Nvidia RTX 5060', 'https://www.overclockers.co.uk/blog/wp-content/uploads/2025/01/ordering-rtx-50-twitter-1536x864.png', 'The Nvidia GeForce RTX 5060 is a mid-range desktop graphics card utilizing the GB206 chip based on the Blackwell architecture. The 5060 offers 8 GB GDDR7 graphics memory with a 128-bit memory bus.', 'Nvidia', 999.00, 655.00, 67, 2.7),
(95002, 'Nvidia AI Developer Kit', 'https://developer-blogs.nvidia.com/wp-content/uploads/2023/03/jetson-orin-nano-developer-kit-3d-render-.png', 'NVIDIA Jetson developer kits are used by professionals to develop and test software for products based on Jetson modules, and by students and enthusiasts.', 'Nvidia', 12000.00, 11000.00, 188, 4.9);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `admin_role` tinyint(4) NOT NULL DEFAULT 0,
  `token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `account_registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`user_id`, `username`, `password`, `email`, `admin_role`, `token`, `token_expiry`, `account_registered_at`, `last_login_time`) VALUES
('user_8ab6cd56729ec0d39df872d5eaf', 'Test456', '$2y$10$55029QOlQOPjQ4EGI8GISeGETUad.lsGAnmQ4qVoPzHrQJDAu7xw.', 'Test456@gmail.com', 0, '', NULL, '2025-06-04 16:21:45', '2026-01-04 01:23:24'),
('user_a3f8b7c15d2e4e7a9f4c6b3a8d7e1c0b', 'Test123', '$2y$10$wsi0q/wuRyOZ6K9L2.UJ3OI320seI/D1Nj8v2t6yc0PgqPU05r.Wu', 'Test123@gmail.com', 1, '', NULL, '2024-12-28 14:41:01', '2026-01-07 16:11:17');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `calling_code` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthday` date DEFAULT NULL,
  `country` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `phone`, `calling_code`, `first_name`, `last_name`, `birthday`, `country`, `city`, `address`, `postal_code`) VALUES
('user_8ab6cd56729ec0d39df872d5eaf', '20 7946 0857', '+44', 'John', 'Doe', '2009-06-16', 'United Kingdom of Great Britain and Northern Ireland', 'London', '12 Rosewood Avenue', 'SW1A 1AA'),
('user_a3f8b7c15d2e4e7a9f4c6b3a8d7e1c0b', '650-839-4726', '+1', 'Douglas', 'McGee', '1987-05-14', 'United States of America', 'Cupertino', 'one apple park way California', '95014');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `iso2` (`iso2`);

--
-- Indexes for table `orders_info`
--
ALTER TABLE `orders_info`
  ADD PRIMARY KEY (`orders_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`orders_id`,`product_id`),
  ADD KEY `fk_product_id` (`product_id`),
  ADD KEY `fk_od_uid` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_od_uid` FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_orders_id` FOREIGN KEY (`orders_id`) REFERENCES `orders_info` (`orders_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
