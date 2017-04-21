-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 21, 2017 at 12:28 PM
-- Server version: 5.5.54-0+deb8u1
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `medius_barpi`
--

-- --------------------------------------------------------

--
-- Table structure for table `barCalendar`
--

CREATE TABLE IF NOT EXISTS `barCalendar` (
`id` int(11) NOT NULL,
  `barid` int(11) NOT NULL,
  `sunday` int(11) DEFAULT NULL,
  `monday` int(11) DEFAULT NULL,
  `tuesday` int(11) DEFAULT NULL,
  `wednesday` int(11) DEFAULT NULL,
  `thursday` int(11) DEFAULT NULL,
  `friday` int(11) DEFAULT NULL,
  `saturday` int(11) DEFAULT NULL,
  `repeatweekly` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `barCalendar`
--

INSERT INTO `barCalendar` (`id`, `barid`, `sunday`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `repeatweekly`) VALUES
(1, 1, 8, NULL, NULL, NULL, 8, NULL, 8, 1),
(2, 1002, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, 1003, NULL, NULL, NULL, NULL, 14, NULL, NULL, 1),
(4, 1004, NULL, NULL, NULL, NULL, 13, NULL, NULL, 1),
(5, 1005, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(6, 1006, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bars`
--

CREATE TABLE IF NOT EXISTS `bars` (
`id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `rating` float DEFAULT '0',
  `timesrated` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=1102 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bars`
--

INSERT INTO `bars` (`id`, `username`, `password`, `name`, `description`, `rating`, `timesrated`) VALUES
(1101, 'myUsername@test.com', 'deb1536f480475f7d593219aa1afd74c', 'O''Leary''s', 'Everyone''s favorite 15th street hangout', 14, 4),
(1002, 'myUsername2@test2.com', 'deb1536f480475f7d593219aa1afd74c', 'Pi Lambda Phi', 'Everyone''s fav Pawling hangout', 9, 2),
(1003, 'me@yahoo.com', 'deb1536f480475f7d593219aa1afd74c', 'Clubhouse Pub', 'RPI campus pub located in the Student Union. Open to all RPI students, faculty, staff, alumni, and their respective guests. Currently sells only beer and wine. Snacks available upon request!', 6.5, 2),
(1004, 'test@test.com', 'deb1536f480475f7d593219aa1afd74c', 'Bar Troy', 'The preferred bar of Troy ', 0, 0),
(1005, 'bier@bier.com', 'deb1536f480475f7d593219aa1afd74c', 'Wolff''s Biergarten', 'Rustic. Darts. Shuffleboard. Peanuts. Liters of beer. Futbol. Come drink a liter of cold beer while watching sports on one of the many TVs around the bar.', 0, 0),
(1006, 'ruck@ruck.com', '427348197eafa', 'The Ruck', 'To The Ruck! To The Ruck!', 0, 0),
(1007, 'boot@boot.com', 'jfef98u3q83rua', 'Bootlegger''s', 'Come one, come all. Get ready to have a really fun night with friends!', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `drink`
--

CREATE TABLE IF NOT EXISTS `drink` (
`id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `barid` int(11) NOT NULL,
  `isOnMenuToday` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `drink`
--

INSERT INTO `drink` (`id`, `name`, `description`, `price`, `barid`, `isOnMenuToday`) VALUES
(15, 'Rum and coke', 'rum + coke', 4, 1101, 0),
(3, 'Seans Drank', 'Rum Chata & Fireball', 10.99, 1002, 0),
(4, 'seans new drank', 'H-O2', 0, 1002, 0),
(26, 'my new drink', 'its great', 1000, 1003, 0),
(14, 'Miller High Life', 'the champagne of beers', 2, 1101, 0),
(24, 'Fireball Shot', '1.5 oz Fireball', 9.5, 1004, 0),
(25, 'Snake Bite', 'Guinness and Cider', 12, 1003, 0),
(20, 'Gin and tonic', 'Tonic water and gin mixed together garnished with a lime', 4, 1, 0),
(16, 'Vodka cranberry', 'vodka + cranberry juice', 4, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
`id` int(11) NOT NULL,
  `barid` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `IsEventToday` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `barid`, `name`, `description`, `IsEventToday`) VALUES
(14, 1003, 'half off snakebites', '50% off!', 0),
(13, 1004, 'Bar Troy Thursdays', 'half off fireball shots', 0),
(8, 1101, '25 days', 'Celebrate 25 days until graduation! Come hang out with friends, drink a beer or two or three. Hang loose and relax before finals really start kicking in.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
`id` int(11) NOT NULL,
  `barid` int(11) NOT NULL,
  `aptno` text COLLATE utf8_unicode_ci NOT NULL,
  `street` text COLLATE utf8_unicode_ci NOT NULL,
  `city` text COLLATE utf8_unicode_ci NOT NULL,
  `state` text COLLATE utf8_unicode_ci NOT NULL,
  `zip` text COLLATE utf8_unicode_ci NOT NULL,
  `country` text COLLATE utf8_unicode_ci NOT NULL,
  `longitude` float NOT NULL DEFAULT '360' COMMENT 'for maps',
  `latitude` float NOT NULL DEFAULT '360' COMMENT 'for maps'
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `barid`, `aptno`, `street`, `city`, `state`, `zip`, `country`, `longitude`, `latitude`) VALUES
(2, 1002, ' ', '400 Pawling Ave.', 'Troy', 'NY', '12180', 'USA', -70, 42),
(1, 1101, '2253', '15th St', 'Troy', 'NY', '12180', 'USA', 3, 4),
(14, 1004, '', '121 4th St.', 'Troy', 'NY', '12180', 'USA', -73.6898, 42.7291),
(15, 1003, '3rd Floor Student Union on the left', '15th St.', 'Troy', 'NY', '12180', 'USA', -73.6769, 42.73),
(16, 1005, '', '2 King St.', 'Troy', 'NY', '12180', 'USA', -73.6879, 42.7348),
(17, 1006, '', '104 3rd St.', 'Troy', 'NY', '12180', 'USA', -73.6906, 42.7284),
(18, 1007, '', '200 Broadway', 'Troy', 'NY', '12180', 'USA', -73.6911, 42.7316);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
`id` int(11) NOT NULL,
  `barid` int(11) NOT NULL COMMENT 'cannot be empty',
  `eventid` int(11) NOT NULL DEFAULT '0' COMMENT 'empty if its in the bar chat',
  `uid` int(11) NOT NULL,
  `timestamp` int(11) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `barid`, `eventid`, `uid`, `timestamp`, `message`) VALUES
(1, 1, 0, 1, 1490815204, 'huuuu'),
(2, 1, 0, 1, 1490815503, 'yhj'),
(3, 1, 0, 1, 1490815626, 'urrjuhr'),
(4, 1, 0, 1, 1490821298, 'test45'),
(5, 1, 0, 1, 1490821308, 'hklh'),
(6, 1, 0, 1, 1490821314, 'hjkh'),
(7, 1, 0, 1, 1490821318, 'iooiiiiii'),
(8, 1, 0, 1, 1490821321, 'kkkll'),
(9, 1, 0, 1, 1490821324, 'llllll'),
(10, 1, 0, 1, 1490821325, 'll'),
(11, 1, 0, 1, 1490821326, 'llll'),
(12, 1, 0, 1, 1490821328, 'lllpl'),
(13, 1, 0, 1, 1490821346, 'test'),
(14, 1, 0, 1, 1490823690, 'gio'),
(15, 1, 0, 1, 1490835975, 'hello eryka'),
(16, 1, 0, 1, 1490836096, 'test'),
(17, 1, 0, 1, 1490836106, 'hi'),
(18, 1, 0, 1, 1490836950, 'test3'),
(19, 1, 0, 1, 1490837096, 'test4'),
(20, 1, 0, 1, 1490837596, 'hi'),
(21, 1, 0, 1, 1490838614, 'test'),
(22, 1, 0, 1, 1490839783, 'derp'),
(23, 1, 0, 1, 1490841894, 'test4'),
(24, 1, 0, 1, 1490841902, 'hi'),
(25, 1, 0, 1, 1490843977, 'herro'),
(26, 1, 0, 1, 1490844012, 'hi there'),
(27, 1, 0, 1, 1490844020, 'hi'),
(28, 1, 0, 1, 1490848920, 'poop'),
(29, 1, 0, 1, 1490849013, 'gjk'),
(30, 1, 0, 1, 1490883297, 'test'),
(31, 1, 0, 1, 1490883303, 'test'),
(32, 1, 0, 1, 1490883315, 'interesting'),
(33, 1, 0, 1, 1490883326, 'shmea'),
(34, 1003, 0, 1, 1490904438, 'hi'),
(35, 1003, 0, 1, 1490904474, 'I love the Pub!'),
(36, 1002, 0, 1, 1490909427, 'test'),
(37, 1003, 0, 1, 1490909441, 'test'),
(38, 1004, 0, 1, 1490909472, 'test'),
(39, 1005, 0, 1, 1490909486, 'test'),
(40, 1006, 0, 1, 1491249395, 'dykdgkxhkdykhafj'),
(41, 1006, 0, 1, 1491249401, 'xhxjcjcj'),
(42, 1002, 0, 223, 1492724436, 'hi'),
(43, 1002, 0, 223, 1492724443, 'hey');

-- --------------------------------------------------------

--
-- Table structure for table `regusers`
--

CREATE TABLE IF NOT EXISTS `regusers` (
`id` int(11) NOT NULL COMMENT 'given a new id for every new client. client saves id',
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `regusers`
--

INSERT INTO `regusers` (`id`, `ip`) VALUES
(1, '127.0.0.1'),
(2, '2.0.188.174'),
(3, '2.0.129.169'),
(4, '2.0.132.40'),
(5, '2.0.178.239'),
(6, '2.0.204.218'),
(7, '2.0.137.108'),
(8, '2.0.184.28'),
(9, '2.0.132.223'),
(10, '2.0.237.227'),
(11, '2.0.195.3'),
(12, '2.0.141.171'),
(13, '2.0.225.10'),
(14, '2.0.174.204'),
(15, '2.0.191.7'),
(16, '2.0.148.242'),
(17, '2.0.179.85'),
(18, '2.0.158.56'),
(19, '2.0.193.198'),
(20, '2.0.160.243'),
(21, '2.0.228.116'),
(22, '2.0.132.88'),
(23, '2.0.198.79'),
(24, '2.0.132.120'),
(25, '2.0.222.131'),
(26, '2.0.226.85'),
(27, '2.0.136.176'),
(28, '2.0.136.245'),
(29, '2.0.193.158'),
(30, '2.0.206.200'),
(31, '2.0.159.109'),
(32, '2.0.130.102'),
(33, '2.0.223.205'),
(34, '2.0.149.171'),
(35, '2.0.132.42'),
(36, '2.0.165.206'),
(37, '2.0.188.225'),
(38, '2.0.199.146'),
(39, '2.0.229.172'),
(40, '2.0.181.250'),
(41, '2.0.162.247'),
(42, '2.0.144.72'),
(43, '2.0.199.91'),
(44, '2.0.206.191'),
(45, '2.0.188.6'),
(46, '2.0.236.73'),
(47, '2.0.173.65'),
(48, '2.0.183.199'),
(49, '2.0.216.243'),
(50, '2.0.211.12'),
(51, '2.0.234.237'),
(52, '2.0.205.253'),
(53, '2.0.226.205'),
(54, '2.0.206.30'),
(55, '2.0.206.54'),
(56, '2.0.206.78'),
(57, '2.0.206.98'),
(58, '2.0.206.109'),
(59, '2.0.206.118'),
(60, '2.0.206.165'),
(61, '2.0.206.183'),
(62, '2.0.206.192'),
(63, '2.0.206.227'),
(64, '2.0.207.0'),
(65, '2.0.163.18'),
(66, '2.0.150.96'),
(67, '2.0.215.184'),
(68, '2.0.156.163'),
(69, '2.0.159.185'),
(70, '2.0.174.120'),
(71, '2.0.157.162'),
(72, '2.0.148.209'),
(73, '2.0.201.138'),
(74, '2.0.130.252'),
(75, '2.0.202.155'),
(76, '2.0.212.62'),
(77, '2.0.137.85'),
(78, '2.0.138.217'),
(79, '2.0.192.50'),
(80, '2.0.140.251'),
(81, '2.0.145.110'),
(82, '2.0.186.108'),
(83, '2.0.229.7'),
(84, '2.0.196.244'),
(85, '2.0.223.217'),
(86, '2.0.193.13'),
(87, '2.0.133.86'),
(88, '2.0.137.237'),
(89, '2.0.135.21'),
(90, '2.0.141.207'),
(91, '2.0.228.204'),
(92, '2.0.202.12'),
(93, '2.0.177.161'),
(94, '2.0.191.84'),
(95, '2.0.209.242'),
(96, '2.0.175.118'),
(97, '2.0.151.250'),
(98, '2.0.212.142'),
(99, '2.0.219.71'),
(100, '2.0.221.114'),
(101, '2.0.182.87'),
(102, '2.0.234.48'),
(103, '2.0.226.254'),
(104, '2.0.209.166'),
(105, '2.0.221.116'),
(106, '2.0.197.223'),
(107, '2.0.192.212'),
(108, '2.0.174.24'),
(109, '2.0.198.191'),
(110, '2.0.221.34'),
(111, '2.0.164.231'),
(112, '2.0.217.116'),
(113, '2.0.219.92'),
(114, '2.0.139.222'),
(115, '2.0.135.214'),
(116, '2.0.131.173'),
(117, '2.0.212.197'),
(118, '2.0.185.45'),
(119, '2.0.163.34'),
(120, '2.0.148.200'),
(121, '2.0.131.29'),
(122, '2.0.129.171'),
(123, '2.0.210.185'),
(124, '2.0.197.190'),
(125, '2.0.135.167'),
(126, '2.0.169.73'),
(127, '2.0.154.99'),
(128, '2.0.221.100'),
(129, '2.0.230.216'),
(130, '2.0.131.219'),
(131, '2.0.170.179'),
(132, '2.0.227.21'),
(133, '2.0.152.146'),
(134, '2.0.189.138'),
(135, '2.0.234.25'),
(136, '2.0.156.223'),
(137, '2.0.210.110'),
(138, '2.0.204.184'),
(139, '2.0.183.75'),
(140, '2.0.130.239'),
(141, '2.0.219.253'),
(142, '2.0.183.248'),
(143, '2.0.131.30'),
(144, '2.0.187.27'),
(145, '2.0.192.19'),
(146, '2.0.192.32'),
(147, '2.0.192.41'),
(148, '2.0.135.72'),
(149, '2.0.171.131'),
(150, '2.0.149.118'),
(151, '2.0.216.167'),
(152, '2.0.192.244'),
(153, '2.0.150.215'),
(154, '2.0.170.201'),
(155, '2.0.191.128'),
(156, '2.0.218.70'),
(157, '2.0.219.106'),
(158, '2.0.151.219'),
(159, '2.0.146.109'),
(160, '2.0.226.202'),
(161, '2.0.213.98'),
(162, '2.0.213.144'),
(163, '2.0.213.168'),
(164, '2.0.213.217'),
(165, '2.0.177.130'),
(166, '2.0.214.37'),
(167, '2.0.130.211'),
(168, '2.0.150.81'),
(169, '2.0.10.195'),
(170, '2.0.10.219'),
(171, '2.0.10.197'),
(172, '2.0.10.213'),
(173, '2.0.10.219'),
(174, '2.0.162.66'),
(175, '2.0.167.134'),
(176, '2.0.199.210'),
(177, '2.0.137.146'),
(178, '2.0.209.167'),
(179, '2.0.141.133'),
(180, '2.0.137.134'),
(181, '2.0.205.219'),
(182, '2.0.215.91'),
(183, '2.0.160.76'),
(184, '2.0.169.134'),
(185, '2.0.166.171'),
(186, '2.0.206.12'),
(187, '2.0.226.132'),
(188, '2.0.203.225'),
(189, '2.0.181.48'),
(190, '2.0.214.48'),
(191, '2.0.196.32'),
(192, '2.0.204.129'),
(193, '2.0.202.251'),
(194, '2.0.149.50'),
(195, '2.0.196.158'),
(196, '2.0.191.239'),
(197, '2.0.147.155'),
(198, '2.0.149.141'),
(199, '2.0.211.93'),
(200, '2.0.137.49'),
(201, '2.0.137.87'),
(202, '2.0.137.135'),
(203, '2.0.137.181'),
(204, '2.0.137.218'),
(205, '2.0.137.229'),
(206, '2.0.137.230'),
(207, '2.0.137.231'),
(208, '2.0.137.240'),
(209, '2.0.137.243'),
(210, '2.0.137.246'),
(211, '2.0.222.26'),
(212, '2.0.176.44'),
(213, '2.0.161.27'),
(214, '2.0.225.175'),
(215, '2.0.140.184'),
(216, '2.0.199.119'),
(217, '2.0.194.190'),
(218, '2.0.134.93'),
(219, '2.0.212.252'),
(220, '2.0.138.196'),
(221, '2.0.138.205'),
(222, '2.0.142.32'),
(223, '2.0.145.51'),
(224, '2.0.212.109'),
(225, '2.0.138.217'),
(226, '2.0.180.206');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `sid` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `barid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='web sessions';

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`sid`, `ip`, `barid`) VALUES
('07tnt4hb22l2o4jk5qmuht61h4', '::1', 1),
('1rtp4sf5ear65kuu4rlel46590', '129.161.78.234', 1),
('22472pptenrsm5gmc0qr3hq8e4', '67.240.35.49', 1),
('ae6qt6hkrtdrbhv1gnlahcjdi2', '127.0.0.1', 1),
('ec028va4o7msiau2fu3n596a12', '::1', 0),
('g7ukdnigdribu2lthqcgkd0to2', '129.161.88.63', 1),
('gqme034jta9csm67lk5ogdbsp0', '129.161.74.153', 1),
('hlj9up3h107rr1hkcsr4ba2k95', '::1', 1),
('i4u9a0ao8m153pli37ehh66ti5', '129.161.53.17', 1),
('jfs07ah9h6871lhficu45t51j2', '::1', 1),
('jg1gnobgu0pfgnsvsui8pveeh6', '129.161.78.242', 1101),
('jk6dinjaqhddgv8bnghpg6ao34', '127.0.0.1', 1),
('jp7vm41fuha1m3d0a2u1eiim74', '127.0.0.1', 1),
('kk77dj98ual3fo2na7tfcspml0', '127.0.0.1', 1),
('km4i5h7vmgivgb3hm9djmi5cn5', '129.161.78.8', 1),
('le6u8fu7po1un9fjup0qks9s06', '10.0.0.1', 1),
('n0uc6ib7mk5i4bd66egobhl4c5', '129.161.140.241', 1),
('ntprsdtcg87oktc4epdhgjfc23', '129.161.143.103', 1),
('onv5raqi15vujfuolf5csi1g47', '127.0.0.1', 1),
('q797hrd095u53rd20knqfht8l5', '::1', 1002),
('qe9kjddguoefu0sdvagdggcic4', '129.161.140.241', 1),
('qiujrhrbv3oooqhve42fu574n5', '129.161.68.147', 1),
('qulo08vfudrhqhp1j8oga9hr82', '129.161.78.74', 1),
('u43b1kll2s9v8svuhafkh9pnv1', '127.0.0.1', 1),
('ub2ag023tmcka9vdqiotk4et42', '129.161.52.41', 1),
('vfmh9qlo2bnlgm4kvg7hbsn9l0', '127.0.0.1', 1),
('vt550389ln6olpmhfis0uabmq2', '127.0.0.1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `specialinfo`
--

CREATE TABLE IF NOT EXISTS `specialinfo` (
`id` int(11) NOT NULL,
  `eventid` int(11) NOT NULL,
  `drinkid` int(11) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `specialinfo`
--

INSERT INTO `specialinfo` (`id`, `eventid`, `drinkid`, `price`) VALUES
(46, 14, 25, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barCalendar`
--
ALTER TABLE `barCalendar`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bars`
--
ALTER TABLE `bars`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `drink`
--
ALTER TABLE `drink`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regusers`
--
ALTER TABLE `regusers`
 ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
 ADD UNIQUE KEY `sid` (`sid`);

--
-- Indexes for table `specialinfo`
--
ALTER TABLE `specialinfo`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barCalendar`
--
ALTER TABLE `barCalendar`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `bars`
--
ALTER TABLE `bars`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1102;
--
-- AUTO_INCREMENT for table `drink`
--
ALTER TABLE `drink`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `regusers`
--
ALTER TABLE `regusers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'given a new id for every new client. client saves id',AUTO_INCREMENT=227;
--
-- AUTO_INCREMENT for table `specialinfo`
--
ALTER TABLE `specialinfo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
