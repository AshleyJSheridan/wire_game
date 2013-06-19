-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2013 at 11:07 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

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
(1, 'wire-game', 'question', 'Radio', 'What do ya digg ma?', '', 'Please select the sex you preffer', 1, 1, '1', 'Male|*|Female|*|All'),
(2, 'wire-game', 'email', 'Email', 'Email', 'e-mail*', 'Please type a valid email', 1, 2, '1', ''),
(3, 'wire-game', 'photo', 'File', 'Send your photos after the competition', '', 'Please upload a file', 0, 0, '0', ''),
(4, 'wire-game', 'firstname', 'Text', 'First Name', 'First Name', 'Please type your first name', 1, 3, '1', ''),
(5, 'wire-game', 'newsletter', 'Checkbox', 'You want newsletter?', 'yes', 'you want one?', 0, 14, '0', ''),
(6, 'wire-game', 'textquestion', 'Textarea', 'Praesent vehicula, sem nec euismod posuere, lectus tortor varius tellus, ut congue nunc est sit amet enim.', '', 'Please type your answer', 0, 5, '0', ''),
(7, 'wire-game', 'lastname', 'Text', 'Last Name', 'Last Name', 'Please type your last name', 1, 4, '1', ''),
(8, 'wire-game', 'tweeter', 'Text', 'Tweeter Username', 'username', 'Please type your Tweeter Username', 1, 6, '0', ''),
(9, 'wire-game', 'address2', 'Text', 'Street Line 2', 'Street Line 2', 'Please type your address 2', 0, 7, '0', ''),
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tmw_wire_app_settings`
--

INSERT INTO `tmw_wire_app_settings` (`id`, `campaignName`, `title`, `facebookAppUrl`, `gaId`, `appId`, `secret`, `summary`) VALUES
(1, 'wire-game', 'Wire Game Competition', 'http://www.facebook.com/pagename', '', NULL, '1a1a1a1a1a1a1a1a1a1a11a11a11a', 'Please share this great app');

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tmw_wire_app_texts`
--

INSERT INTO `tmw_wire_app_texts` (`id`, `campaignName`, `headerText1`, `headerText2`, `displayImage`, `introText`, `introVideo`, `thankText`, `tncText`, `policyText`) VALUES
(1, 'wire-game', 'Fill in your details and get invitation for', 'TMW WIRE GAME', 1, 'Nulla eu arcu est, in porttitor est. Vivamus sagittis mi ut lectus auctor non aliquet elit euismod. Mauris sit amet elit nec enim accumsan dapibus. Quisque ut iaculis risus. Quisque arcu odio, accumsan nec malesuada sed, tempor in tortor. Nunc viverra, turpis a fermentum commodo, orci elit pellentesque lacus, et convallis orci magna nec enim. ', '', 'bbbb advv gh fh sf fgsgs adfa adfa f fda f', 'http://www.tmw.co.uk/', 'http://www.tmw.co.uk/');

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
  PRIMARY KEY (`playerId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `tmw_wire_comp`
--

INSERT INTO `tmw_wire_comp` (`playerId`, `playerEmail`, `campaign`, `registeredOn`) VALUES
(30, 'gbardis@tmw.co.uk', 'wire-game', '2013-06-19 23:06:47');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=200 ;

--
-- Dumping data for table `tmw_wire_comp_details`
--

INSERT INTO `tmw_wire_comp_details` (`playerDetailsId`, `playerId`, `detailsField`, `detailsData`) VALUES
(199, 30, 'playerProgress', '0'),
(198, 30, 'playerScore', '0'),
(197, 30, 'tweeter', 'bardius'),
(196, 30, 'lastname', 'Bardis'),
(195, 30, 'firstname', 'George'),
(194, 30, 'question', 'male');

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
