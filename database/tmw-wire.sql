-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 27, 2013 at 09:24 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tmw-wire`
--

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_app_form_elements`
--

DROP TABLE IF EXISTS `tmw_wire_app_form_elements`;
CREATE TABLE IF NOT EXISTS `tmw_wire_app_form_elements` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `campaignName` varchar(255) NOT NULL DEFAULT 'tmw-app' COMMENT 'the name of the facebook competition this field belongs to',
  `elementName` varchar(255) NOT NULL COMMENT 'the name of the form element',
  `elementType` varchar(255) NOT NULL DEFAULT 'Text' COMMENT 'the form element type',
  `elementLabel` varchar(255) NOT NULL DEFAULT 'textfield' COMMENT 'the form element label',
  `elementValue` varchar(255) DEFAULT NULL COMMENT 'form element default value',
  `elementError` varchar(255) NOT NULL DEFAULT 'error' COMMENT 'form element error message',
  `elementVisibility` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'hide/show form element',
  `elementOrder` int(11) NOT NULL DEFAULT '1' COMMENT 'form element order',
  `elementRequired` varchar(255) NOT NULL DEFAULT '0' COMMENT 'form element attribute for required',
  `elementExtras` varchar(1024) NOT NULL DEFAULT 'Option1|*|Option2' COMMENT 'form element options for multiselects',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `tmw_wire_app_form_elements`
--

INSERT INTO `tmw_wire_app_form_elements` (`id`, `campaignName`, `elementName`, `elementType`, `elementLabel`, `elementValue`, `elementError`, `elementVisibility`, `elementOrder`, `elementRequired`, `elementExtras`) VALUES
(1, 'wire-game', 'question', 'Radio', '', '', 'Please select answer', 1, 1, '1', 'Absolutely, wild horses wouldnâ€™t stop me.|*|Waiting for my Green Card. Call me maybe.|*|Oh no, I canâ€™t make it this time. Take lots of photos!'),
(2, 'wire-game', 'email', 'Email', 'Email', 'e-mail*', 'Please type a valid email', 1, 4, '1', ''),
(3, 'wire-game', 'photo', 'File', 'Send your photos after the competition', '', 'Please upload a file', 0, 0, '0', ''),
(4, 'wire-game', 'firstname', 'Text', 'First Name', 'First Name', 'Please type your First Name', 1, 2, '1', ''),
(5, 'wire-game', 'newsletter', 'Checkbox', 'You want newsletter?', 'yes', 'you want one?', 0, 14, '0', ''),
(6, 'wire-game', 'textquestion', 'Textarea', 'Praesent vehicula, sem nec euismod posuere, lectus tortor varius tellus, ut congue nunc est sit amet enim.', '', 'Please type your answer', 0, 5, '0', ''),
(7, 'wire-game', 'lastname', 'Text', 'Last Name', 'Last Name', 'Please type your Last Name', 1, 3, '1', ''),
(8, 'wire-game', 'twitterhandle', 'Text', 'My Twitter handle is ', '', 'Please type your Twitter Handle', 1, 5, '0', ''),
(9, 'wire-game', 'charity', 'Text', 'My charity', '', 'Please enter a charity amount', 1, 6, '0', ''),
(10, 'wire-game', 'city', 'Text', 'City', 'City', 'Please type your city', 0, 8, '0', ''),
(11, 'wire-game', 'postalcode', 'Text', 'Postal Code', 'Postal Code', 'Please give your Postal Code', 0, 9, '0', ''),
(12, 'wire-game', 'country', 'Text', 'Country', 'Country', 'Please type your Country', 0, 10, '0', ''),
(13, 'wire-game', 'mobile', 'Text', 'Mobile Phone', 'Mobile Phone', 'Please give your Mobile Phone', 0, 11, '0', ''),
(14, 'wire-game', 'landline', 'Text', 'Landline Phone', 'Landline Phone', 'Please type your Landline Phone', 0, 12, '0', ''),
(15, 'wire-game', 'birthday', 'Text', 'Date of Birth', 'Date of Birth', 'Please give your Date of Birth', 0, 13, '0', ''),
(16, 'wire-game', 'optin', 'Checkbox', 'Optional Opt-in', '0', 'Accept optional Opt in status', 0, 15, '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_app_settings`
--

DROP TABLE IF EXISTS `tmw_wire_app_settings`;
CREATE TABLE IF NOT EXISTS `tmw_wire_app_settings` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `campaignName` varchar(25) NOT NULL DEFAULT 'tmw-app' COMMENT 'the competition name to distinguish saved user data',
  `title` varchar(255) NOT NULL DEFAULT 'Facebook Competition' COMMENT 'the title for this competition 9visible in the share functinality)',
  `facebookAppUrl` varchar(255) NOT NULL DEFAULT 'http://www.facebook.com/facebookpage' COMMENT 'the url of the facebook page',
  `gaId` varchar(255) DEFAULT NULL COMMENT 'google analytics id',
  `appId` varchar(255) DEFAULT NULL COMMENT 'facebook app id',
  `secret` varchar(255) DEFAULT NULL COMMENT 'facebook app secret',
  `summary` varchar(1024) NOT NULL DEFAULT 'Share this great Application' COMMENT 'competition description that will be shown in the share functionality',
  `oauth_access_token` varchar(255) DEFAULT NULL,
  `oauth_access_token_secret` varchar(255) DEFAULT NULL,
  `consumer_key` varchar(255) DEFAULT NULL,
  `consumer_secret` varchar(255) DEFAULT NULL,
  `twitter_user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tmw_wire_app_settings`
--

INSERT INTO `tmw_wire_app_settings` (`id`, `campaignName`, `title`, `facebookAppUrl`, `gaId`, `appId`, `secret`, `summary`, `oauth_access_token`, `oauth_access_token_secret`, `consumer_key`, `consumer_secret`, `twitter_user`) VALUES
(1, 'wire-game', 'Wire Game Competition', 'http://www.facebook.com/pagename', '', '', '', '', '91971683-ybrsDIEGYHSjtl7akrMKV7ScKcznlVJIeiUkHyrvi', 'YUIVnfJZZYdM6DGmxb9IhVTTS13vY87vfAU46r3rE', '4XUvM9jsTwfLehkY6NQ', 'LYxRZcO1Oao5fSDefhvkrZd41OcrML8we0AOkvCUDS0', 'bardius');

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_app_texts`
--

DROP TABLE IF EXISTS `tmw_wire_app_texts`;
CREATE TABLE IF NOT EXISTS `tmw_wire_app_texts` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `campaignName` varchar(255) NOT NULL DEFAULT 'tmw-app' COMMENT 'the name of the currentfacebook competition',
  `headerText1` varchar(255) DEFAULT 'First Header Text' COMMENT 'the first header text',
  `headerText2` varchar(255) DEFAULT 'Second Header Text' COMMENT 'the second header text',
  `displayImage` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'flag to display or not the add image',
  `introText` text COMMENT 'the intro text',
  `introVideo` varchar(255) DEFAULT NULL COMMENT 'the intro video',
  `thankText` text COMMENT 'the text for the successful submition page',
  `tncText` varchar(255) DEFAULT NULL COMMENT 'the link for the t&c text',
  `policyText` varchar(255) DEFAULT NULL COMMENT 'the link for the policy text',
  `twitter_post` varchar(255) NOT NULL DEFAULT 'Twitted: ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tmw_wire_app_texts`
--

INSERT INTO `tmw_wire_app_texts` (`id`, `campaignName`, `headerText1`, `headerText2`, `displayImage`, `introText`, `introVideo`, `thankText`, `tncText`, `policyText`, `twitter_post`) VALUES
(1, 'wire-game', 'Party on the Roof', 'Our roof top bar is fabulously spacious, but to give us an idea of how many canapÃ©s to serve up and how many cocktails to shake out, weâ€™d love it if you could let us know if we can expect you on the 17th July.', 1, '', '', '<strong>Your name is down. Youâ€™re on the list!</strong><br/><br/>\r\nWe look forward to welcoming you to our new home of Intelligent Influence on 17th July from 6.30', 'http://www.tmw.co.uk/', 'http://www.tmw.co.uk/', 'The party goes on: ');

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_app_users`
--

DROP TABLE IF EXISTS `tmw_wire_app_users`;
CREATE TABLE IF NOT EXISTS `tmw_wire_app_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tmw_wire_app_users`
--

INSERT INTO `tmw_wire_app_users` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'admin', 'gbardis@tmw.co.uk');

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_comp`
--

DROP TABLE IF EXISTS `tmw_wire_comp`;
CREATE TABLE IF NOT EXISTS `tmw_wire_comp` (
  `playerId` int(11) NOT NULL AUTO_INCREMENT,
  `playerEmail` varchar(255) NOT NULL,
  `campaign` varchar(64) NOT NULL,
  `registeredOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `RFHandleId` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`playerId`),
  UNIQUE KEY `RFHandleId` (`RFHandleId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `tmw_wire_comp`
--

INSERT INTO `tmw_wire_comp` (`playerId`, `playerEmail`, `campaign`, `registeredOn`, `RFHandleId`) VALUES
(30, 'gbardis@tmw.co.uk', 'wire-game', '2013-06-19 23:06:47', 'bardis'),
(31, 'tmw@tmw.co.uk', 'wire-game', '2013-06-20 00:44:55', NULL),
(32, 'test@test.com', 'wire-game', '2013-06-21 21:10:23', NULL),
(33, 'tests@test.com', 'wire-game', '2013-06-21 21:12:46', NULL),
(34, 'g@b.com', 'wire-game', '2013-06-22 18:41:10', NULL),
(35, 'a@a.fr', 'wire-game', '2013-06-22 18:45:48', NULL),
(36, 'aaa@aaa.gr', 'wire-game', '2013-06-25 12:32:19', NULL),
(37, 'testdsd@gg.com', 'wire-game', '2013-06-25 16:17:21', NULL),
(38, 'gggg@gg.com', 'wire-game', '2013-06-26 15:51:32', NULL),
(39, 'sfsd@fsgs.gr', 'wire-game', '2013-06-26 15:56:11', NULL),
(40, 'asssdf@sddf.fr', 'wire-game', '2013-06-26 15:57:59', NULL),
(41, 'fsdgfd@sdsdff.gr', 'wire-game', '2013-06-26 16:03:42', '0'),
(42, 'dfsadsa@ssf.gr', 'wire-game', '2013-06-26 16:07:07', 'skata'),
(43, '5536@fsdggd.fr', 'wire-game', '2013-06-26 16:07:56', ''),
(44, 'asssdf@sddddddf.fr', 'wire-game', '2013-06-27 09:21:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_comp_details`
--

DROP TABLE IF EXISTS `tmw_wire_comp_details`;
CREATE TABLE IF NOT EXISTS `tmw_wire_comp_details` (
  `playerDetailsId` int(11) NOT NULL AUTO_INCREMENT,
  `playerId` int(11) NOT NULL,
  `detailsField` text NOT NULL,
  `detailsData` text NOT NULL,
  PRIMARY KEY (`playerDetailsId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=303 ;

--
-- Dumping data for table `tmw_wire_comp_details`
--

INSERT INTO `tmw_wire_comp_details` (`playerDetailsId`, `playerId`, `detailsField`, `detailsData`) VALUES
(200, 31, 'question', 'all'),
(199, 30, 'playerProgress', '0'),
(198, 30, 'playerScore', '100'),
(197, 30, 'twitterhandle', 'bardius'),
(196, 30, 'lastname', 'Bardis'),
(195, 30, 'firstname', 'George'),
(194, 30, 'question', 'male'),
(201, 31, 'firstname', 'Tmw'),
(202, 31, 'lastname', 'Agency'),
(203, 31, 'twitterhandle', 'tmwagency'),
(204, 31, 'playerScore', '98'),
(205, 31, 'playerProgress', '0'),
(206, 31, 'playerTime', '8'),
(207, 30, 'playerTime', '10'),
(208, 32, 'firstname', 'tester'),
(209, 32, 'lastname', 'Testing'),
(210, 32, 'twitterhandle', 'testerT'),
(211, 32, 'question', 'all'),
(212, 32, 'playerScore', '0'),
(213, 32, 'playerProgress', '0'),
(214, 32, 'playerTime', '0'),
(215, 33, 'firstname', 'test2'),
(216, 33, 'lastname', 'test last'),
(217, 33, 'twitterhandle', 'testerT2'),
(218, 33, 'question', 'female'),
(219, 33, 'playerScore', '0'),
(220, 33, 'playerProgress', '0'),
(221, 33, 'playerTime', '0'),
(222, 34, 'firstname', 'test3'),
(223, 34, 'lastname', 'testerl'),
(224, 34, 'twitterhandle', 'bbbb'),
(225, 34, 'question', 'female'),
(226, 34, 'playerScore', '0'),
(227, 34, 'playerProgress', '0'),
(228, 34, 'playerTime', '0'),
(229, 35, 'firstname', 'fasfsd'),
(230, 35, 'lastname', 'fsdfdsf'),
(231, 35, 'twitterhandle', 'ssad'),
(232, 35, 'question', 'female'),
(233, 35, 'playerScore', '0'),
(234, 35, 'playerProgress', '0'),
(235, 35, 'playerTime', '0'),
(236, 36, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(237, 36, 'firstname', 'uuituy'),
(238, 36, 'lastname', 'sgsf'),
(239, 36, 'twitterhandle', ' '),
(240, 36, 'playerScore', '0'),
(241, 36, 'playerProgress', '0'),
(242, 36, 'playerTime', '0'),
(243, 37, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(244, 37, 'firstname', 'noTwet'),
(245, 37, 'lastname', 'tester'),
(246, 37, 'twitterhandle', ''),
(247, 37, 'playerScore', '0'),
(248, 37, 'playerProgress', '0'),
(249, 37, 'playerTime', '0'),
(250, 38, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(251, 38, 'firstname', 'register'),
(252, 38, 'lastname', 'with handle'),
(253, 38, 'twitterhandle', ''),
(254, 38, 'playerScore', '0'),
(255, 38, 'playerProgress', '0'),
(256, 38, 'playerTime', '0'),
(257, 39, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(258, 39, 'firstname', 'gdfgf'),
(259, 39, 'lastname', 'fsggfg'),
(260, 39, 'twitterhandle', ''),
(261, 39, 'playerScore', '0'),
(262, 39, 'playerProgress', '0'),
(263, 39, 'playerTime', '0'),
(264, 40, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(265, 40, 'firstname', 'dhdgh'),
(266, 40, 'lastname', 'gdhgghd'),
(267, 40, 'twitterhandle', ''),
(268, 40, 'playerScore', '0'),
(269, 40, 'playerProgress', '0'),
(270, 40, 'playerTime', '0'),
(271, 41, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(272, 41, 'firstname', 'dfdgg'),
(273, 41, 'lastname', 'dfgfd'),
(274, 41, 'twitterhandle', ''),
(275, 41, 'RFHandleId', 'skata'),
(276, 41, 'playerScore', '0'),
(277, 41, 'playerProgress', '0'),
(278, 41, 'playerTime', '0'),
(279, 42, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(280, 42, 'firstname', 'dghdgf'),
(281, 42, 'lastname', 'sggs'),
(282, 42, 'twitterhandle', ''),
(283, 42, 'RFHandleId', 'skata'),
(284, 42, 'playerScore', '0'),
(285, 42, 'playerProgress', '0'),
(286, 42, 'playerTime', '0'),
(287, 43, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(288, 43, 'firstname', 'fdfg'),
(289, 43, 'lastname', 'dfgdg'),
(290, 43, 'twitterhandle', ''),
(291, 43, 'RFHandleId', ''),
(292, 43, 'playerScore', '0'),
(293, 43, 'playerProgress', '0'),
(294, 43, 'playerTime', '0'),
(295, 44, 'question', 'absolutely__wild_horses_wouldn___t_stop_me.'),
(296, 44, 'firstname', 'fgffg'),
(297, 44, 'lastname', 'dffd'),
(298, 44, 'twitterhandle', 'sfsfdfd'),
(299, 44, 'charity', '10'),
(300, 44, 'playerScore', '0'),
(301, 44, 'playerProgress', '0'),
(302, 44, 'playerTime', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_comp_names`
--

DROP TABLE IF EXISTS `tmw_wire_comp_names`;
CREATE TABLE IF NOT EXISTS `tmw_wire_comp_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `compName` varchar(255) DEFAULT NULL COMMENT 'the name of the fb competition',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tmw_wire_comp_names`
--

INSERT INTO `tmw_wire_comp_names` (`id`, `compName`) VALUES
(1, 'wire-game');

-- --------------------------------------------------------

--
-- Table structure for table `tmw_wire_current_state`
--

DROP TABLE IF EXISTS `tmw_wire_current_state`;
CREATE TABLE IF NOT EXISTS `tmw_wire_current_state` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `playerId` varchar(255) DEFAULT NULL,
  `game_status` varchar(255) DEFAULT NULL,
  `game_progress` varchar(255) DEFAULT NULL,
  `player_photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tmw_wire_current_state`
--

INSERT INTO `tmw_wire_current_state` (`id`, `playerId`, `game_status`, `game_progress`, `player_photo`) VALUES
(1, '30', 'on', '0', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
