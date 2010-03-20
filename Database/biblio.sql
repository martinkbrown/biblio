-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2010 at 04:22 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `biblio`
--

-- --------------------------------------------------------

--
-- Table structure for table `conference`
--

CREATE TABLE IF NOT EXISTS `conference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `acronym` varchar(15) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`acronym`),
  UNIQUE KEY `name_2` (`name`),
  UNIQUE KEY `acronym` (`acronym`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `conference`
--


-- --------------------------------------------------------

--
-- Table structure for table `conference_meeting`
--

CREATE TABLE IF NOT EXISTS `conference_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conference_id` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `publisher_website` varchar(255) DEFAULT NULL,
  `conference_website` varchar(255) DEFAULT NULL,
  `isbn` varchar(255) NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn` (`isbn`),
  KEY `conference_id` (`conference_id`),
  KEY `country_id` (`country_id`),
  KEY `state_id` (`state_id`),
  KEY `publisher` (`publisher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `conference_meeting`
--


-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=274 ;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `name`) VALUES
(273, ''),
(1, 'Afghanistan'),
(2, 'Akrotiri'),
(3, 'Albania'),
(4, 'Algeria'),
(5, 'American Samoa'),
(6, 'Andorra'),
(7, 'Angola'),
(8, 'Anguilla'),
(9, 'Antarctica'),
(10, 'Antigua and Barbuda'),
(11, 'Arctic Ocean'),
(12, 'Argentina'),
(13, 'Armenia'),
(14, 'Aruba'),
(15, 'Ashmore and Cartier Islands'),
(16, 'Atlantic Ocean'),
(17, 'Australia'),
(18, 'Austria'),
(19, 'Azerbaijan'),
(20, 'Bahamas, The'),
(21, 'Bahrain'),
(22, 'Baker Island'),
(23, 'Bangladesh'),
(24, 'Barbados'),
(25, 'Bassas da India'),
(26, 'Belarus'),
(27, 'Belgium'),
(28, 'Belize'),
(29, 'Benin'),
(30, 'Bermuda'),
(31, 'Bhutan'),
(32, 'Bolivia'),
(33, 'Bosnia and Herzegovina'),
(34, 'Botswana'),
(35, 'Bouvet Island'),
(36, 'Brazil'),
(37, 'British Indian Ocean Territory'),
(38, 'British Virgin Islands'),
(39, 'Brunei'),
(40, 'Bulgaria'),
(41, 'Burkina Faso'),
(42, 'Burma'),
(43, 'Burundi'),
(44, 'Cambodia'),
(45, 'Cameroon'),
(46, 'Canada'),
(47, 'Cape Verde'),
(48, 'Cayman Islands'),
(49, 'Central African Republic'),
(50, 'Chad'),
(51, 'Chile'),
(52, 'China'),
(53, 'Christmas Island'),
(54, 'Clipperton Island'),
(55, 'Cocos (Keeling) Islands'),
(56, 'Colombia'),
(57, 'Comoros'),
(58, 'Congo, Democratic Republic of the'),
(59, 'Congo, Republic of the'),
(60, 'Cook Islands'),
(61, 'Coral Sea Islands'),
(62, 'Costa Rica'),
(63, 'Croatia'),
(64, 'Cuba'),
(65, 'Cyprus'),
(66, 'Czech Republic'),
(67, 'Denmark'),
(68, 'Dhekelia'),
(69, 'Djibouti'),
(70, 'Dominica'),
(71, 'Dominican Republic'),
(72, 'East Timor'),
(73, 'Ecuador'),
(74, 'Egypt'),
(75, 'El Salvador'),
(76, 'Equatorial Guinea'),
(77, 'Eritrea'),
(78, 'Estonia'),
(79, 'Ethiopia'),
(80, 'Europa Island'),
(272, 'European Union'),
(81, 'Falkland Islands (Islas Malvinas)'),
(82, 'Faroe Islands'),
(83, 'Fiji'),
(84, 'Finland'),
(85, 'France'),
(86, 'French Guiana'),
(87, 'French Polynesia'),
(88, 'French Southern and Antarctic Lands'),
(89, 'Gabon'),
(90, 'Gambia, The'),
(91, 'Gaza Strip'),
(92, 'Georgia'),
(93, 'Germany'),
(94, 'Ghana'),
(95, 'Gibraltar'),
(96, 'Glorioso Islands'),
(97, 'Greece'),
(98, 'Greenland'),
(99, 'Grenada'),
(100, 'Guadeloupe'),
(101, 'Guam'),
(102, 'Guatemala'),
(103, 'Guernsey'),
(104, 'Guinea'),
(105, 'Guinea-Bissau'),
(106, 'Guyana'),
(107, 'Haiti'),
(108, 'Heard Island and McDonald Islands'),
(109, 'Holy See (Vatican City)'),
(110, 'Honduras'),
(111, 'Hong Kong'),
(112, 'Howland Island'),
(113, 'Hungary'),
(114, 'Iceland'),
(115, 'India'),
(116, 'Indian Ocean'),
(117, 'Indonesia'),
(118, 'Iran'),
(119, 'Iraq'),
(120, 'Ireland'),
(121, 'Israel'),
(122, 'Italy'),
(123, 'Jamaica'),
(124, 'Jan Mayen'),
(125, 'Japan'),
(126, 'Jarvis Island'),
(127, 'Jersey'),
(128, 'Johnston Atoll'),
(129, 'Jordan'),
(130, 'Juan de Nova Island'),
(131, 'Kazakhstan'),
(132, 'Kenya'),
(133, 'Kingman Reef'),
(134, 'Kiribati'),
(135, 'Korea, North'),
(136, 'Korea, South'),
(137, 'Kuwait'),
(138, 'Kyrgyzstan'),
(139, 'Laos'),
(140, 'Latvia'),
(141, 'Lebanon'),
(142, 'Lesotho'),
(143, 'Liberia'),
(144, 'Libya'),
(145, 'Liechtenstein'),
(146, 'Lithuania'),
(147, 'Luxembourg'),
(148, 'Macau'),
(149, 'Macedonia'),
(150, 'Madagascar'),
(151, 'Malawi'),
(152, 'Malaysia'),
(153, 'Maldives'),
(154, 'Mali'),
(155, 'Malta'),
(156, 'Man, Isle of'),
(157, 'Marshall Islands'),
(158, 'Martinique'),
(159, 'Mauritania'),
(160, 'Mauritius'),
(161, 'Mayotte'),
(162, 'Mexico'),
(163, 'Micronesia, Federated States of'),
(164, 'Midway Islands'),
(165, 'Moldova'),
(166, 'Monaco'),
(167, 'Mongolia'),
(168, 'Montenegro'),
(169, 'Montserrat'),
(170, 'Morocco'),
(171, 'Mozambique'),
(172, 'Namibia'),
(173, 'Nauru'),
(174, 'Navassa Island'),
(175, 'Nepal'),
(176, 'Netherlands'),
(177, 'Netherlands Antilles'),
(178, 'New Caledonia'),
(179, 'New Zealand'),
(180, 'Nicaragua'),
(181, 'Niger'),
(182, 'Nigeria'),
(183, 'Niue'),
(184, 'Norfolk Island'),
(185, 'Northern Mariana Islands'),
(186, 'Norway'),
(187, 'Oman'),
(188, 'Pacific Ocean'),
(189, 'Pakistan'),
(190, 'Palau'),
(191, 'Palmyra Atoll'),
(192, 'Panama'),
(193, 'Papua New Guinea'),
(194, 'Paracel Islands'),
(195, 'Paraguay'),
(196, 'Peru'),
(197, 'Philippines'),
(198, 'Pitcairn Islands'),
(199, 'Poland'),
(200, 'Portugal'),
(201, 'Puerto Rico'),
(202, 'Qatar'),
(203, 'Reunion'),
(204, 'Romania'),
(205, 'Russia'),
(206, 'Rwanda'),
(207, 'Saint Helena'),
(208, 'Saint Kitts and Nevis'),
(209, 'Saint Lucia'),
(210, 'Saint Pierre and Miquelon'),
(211, 'Saint Vincent and the Grenadines'),
(212, 'Samoa'),
(213, 'San Marino'),
(214, 'Sao Tome and Principe'),
(215, 'Saudi Arabia'),
(216, 'Senegal'),
(217, 'Serbia'),
(218, 'Seychelles'),
(219, 'Sierra Leone'),
(220, 'Singapore'),
(221, 'Slovakia'),
(222, 'Slovenia'),
(223, 'Solomon Islands'),
(224, 'Somalia'),
(225, 'South Africa'),
(226, 'South Georgia and the South Sandwich '),
(228, 'Southern Ocean'),
(229, 'Spain'),
(230, 'Spratly Islands'),
(231, 'Sri Lanka'),
(232, 'Sudan'),
(233, 'Suriname'),
(234, 'Svalbard'),
(235, 'Swaziland'),
(236, 'Sweden'),
(237, 'Switzerland'),
(238, 'Syria'),
(271, 'Taiwan'),
(239, 'Tajikistan'),
(240, 'Tanzania'),
(241, 'Thailand'),
(242, 'Togo'),
(243, 'Tokelau'),
(244, 'Tonga'),
(245, 'Trinidad and Tobago'),
(246, 'Tromelin Island'),
(247, 'Tunisia'),
(248, 'Turkey'),
(249, 'Turkmenistan'),
(250, 'Turks and Caicos Islands'),
(251, 'Tuvalu'),
(252, 'Uganda'),
(253, 'Ukraine'),
(254, 'United Arab Emirates'),
(255, 'United Kingdom'),
(256, 'United States'),
(257, 'United States Pacific Island Wildlife Refuges'),
(258, 'Uruguay'),
(259, 'Uzbekistan'),
(260, 'Vanuatu'),
(261, 'Venezuela'),
(262, 'Vietnam'),
(263, 'Virgin Islands'),
(264, 'Wake Island'),
(265, 'Wallis and Futuna'),
(266, 'West Bank'),
(267, 'Western Sahara'),
(268, 'Yemen'),
(269, 'Zambia'),
(270, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE IF NOT EXISTS `publisher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `publisher`
--


-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abbreviation` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `abbreviation` (`abbreviation`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `country_id`, `name`, `abbreviation`) VALUES
(1, 256, 'Alabama', 'AL'),
(2, 256, 'Alaska', 'AK'),
(3, 256, 'Arizona', 'AZ'),
(4, 256, 'Arkansas', 'AR'),
(5, 256, 'California', 'CA'),
(6, 256, 'Colorado', 'CO'),
(7, 256, 'Connecticut', 'CT'),
(8, 256, 'Delaware', 'DE'),
(9, 256, 'District of Columbia', 'DC'),
(10, 256, 'Florida', 'FL'),
(11, 256, 'Georgia', 'GA'),
(12, 256, 'Hawaii', 'HI'),
(13, 256, 'Idaho', 'ID'),
(14, 256, 'Illinois', 'IL'),
(15, 256, 'Indiana', 'IN'),
(16, 256, 'Iowa', 'IA'),
(17, 256, 'Kansas', 'KS'),
(18, 256, 'Kentucky', 'KY'),
(19, 256, 'Louisiana', 'LA'),
(20, 256, 'Maine', 'ME'),
(21, 256, 'Maryland', 'MD'),
(22, 256, 'Massachusetts', 'MA'),
(23, 256, 'Michigan', 'MI'),
(24, 256, 'Minnesota', 'MN'),
(25, 256, 'Mississippi', 'MS'),
(26, 256, 'Missouri', 'MO'),
(27, 256, 'Montana', 'MT'),
(28, 256, 'Nebraska', 'NE'),
(29, 256, 'Nevada', 'NV'),
(30, 256, 'New Hampshire', 'NH'),
(31, 256, 'New Jersey', 'NJ'),
(32, 256, 'New Mexico', 'NM'),
(33, 256, 'New York', 'NY'),
(34, 256, 'North Carolina', 'NC'),
(35, 256, 'North Dakota', 'ND'),
(36, 256, 'Ohio', 'OH'),
(37, 256, 'Oklahoma', 'OK'),
(38, 256, 'Oregon', 'OR'),
(39, 256, 'Pennsylvania', 'PA'),
(40, 256, 'Rhode Island', 'RI'),
(41, 256, 'South Carolina', 'SC'),
(42, 256, 'South Dakota', 'SD'),
(43, 256, 'Tennessee', 'TN'),
(44, 256, 'Texas', 'TX'),
(45, 256, 'Utah', 'UT'),
(46, 256, 'Vermont', 'VT'),
(47, 256, 'Virginia', 'VA'),
(48, 256, 'Washington', 'WA'),
(49, 256, 'Wisconsin', 'WI'),
(50, 256, 'Wyoming', 'WY');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `conference_meeting`
--
ALTER TABLE `conference_meeting`
  ADD CONSTRAINT `conference_meeting_ibfk_2` FOREIGN KEY (`conference_id`) REFERENCES `conference` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `state_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE;
