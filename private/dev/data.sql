-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2012 at 11:21 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.10-1ubuntu3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `umbbrowser`
--

--
-- Dumping data for table `acl`
--

INSERT INTO `acl` (`id`, `type`, `user`, `usergroup`, `module`, `action`, `extra`, `permission`) VALUES
(17, 'usergroup', NULL, 2, '_core', '*', '*', 'allow'),
(22, 'usergroup', NULL, NULL, 'shop', 'checkout', '*', 'allow'),
(23, 'usergroup', NULL, NULL, 'shop', '*', 'transaction', 'allow'),
(24, 'usergroup', NULL, NULL, 'samples', 'edit', '*', 'allow'),
(25, 'usergroup', NULL, 2, 'admin', '*', '*', 'allow');

--
-- Dumping data for table `browser`
--

INSERT INTO `browser` (`id`, `sort`, `name`, `htmlname`, `shortname`, `current`, `minimum`, `custom`, `update_url`, `info_url`, `iframe_allowed`, `youtube_id`, `color`, `description`, `published`) VALUES
(1, 5, 'Opera', 'Opera', 'opera', '12', '10', NULL, 'http://www.opera.com/browser/', 'http://www.opera.com/browser/features/', 1, 'yrVvZX2nss8', 'ED1C24', '<p>\r\n	Make your web browsing faster. Loading pages and running web applications is really snappy. You can even speed up browsing on slow connections, with Opera Turbo.</p>\r\n', 'yes'),
(2, 2, 'Firefox', 'Firefox', 'firefox', '13', '4', NULL, 'http://www.getfirefox.com/', 'http://www.mozilla.org/firefox/central/', NULL, 'gZWU2estR10', 'F15A22', '<p>\r\n	Firefox is built with you in mind, so it&#39;s easy and instinctive to use even the first time you try it. View web pages way faster, using less of your computers memory.</p>\r\n', 'yes'),
(3, 3, 'Internet Explorer', '<small>Internet Explorer</small>', 'ie', '9', '8', NULL, 'http://www.microsoft.com/ie', 'http://windows.microsoft.com/en-GB/internet-explorer/products/ie-9/features', 1, 'QC0Pk8Cxsu4', '64CCEC', '<p>\r\n	The new graphic capabilities and improved performance in Internet Explorer 9 set the stage for immersive and rich experiences. High-definition videos are smooth, graphics are clear and responsive, colors are true, and websites are interactive like never before.</p>\r\n', 'yes'),
(4, 4, 'Safari', 'Safari', 'safari', '5.1', '4', NULL, 'http://www.apple.com/safari/', 'http://www.apple.com/safari/whats-new.html', 1, 'Uo6NvrFNqt4', '1C75BC', '<p>\r\n	Safari isn&#39;t just the world&Atilde;&cent;&iuml;&iquest;&frac12;&iuml;&iquest;&frac12;s most innovative web browser. It changes the way you interact with the web. With great new features you&Atilde;&cent;&iuml;&iquest;&frac12;&iuml;&iquest;&frac12;ll become completely immersed in everything you see, touch, read, and watch. Oh, and browse.</p>\r\n', 'yes'),
(5, 1, 'Chrome', 'Chrome', 'chrome', '19', '15', NULL, 'http://www.google.com/chrome', 'http://www.google.com/chrome/intl/en/more/index.html', NULL, 'RrDHrwLUtvk', '00A651', '<p>\r\n	Chrome is designed to be fast in every possible way: It&#39;s quick to start up from your desktop, loads web pages in a snap, and runs complex web applications fast.</p>\r\n', 'yes');

--
-- Dumping data for table `user`
--

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`id`, `name`) VALUES
(1, 'users'),
(2, 'admins');
