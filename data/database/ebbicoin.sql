-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 22, 2018 at 01:16 PM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.1.16-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebbicoin`
--

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_accounts`
--

CREATE TABLE `ebbi_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_id` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_accounts`
--

INSERT INTO `ebbi_accounts` (`id`, `user_id`, `account_id`, `currency`) VALUES
(1, 4, '70a9b558-6899-5d6f-9b45-f02d46262def', 'ETH'),
(2, 1, '5f13821f-2276-59ce-ad41-d0a67242484f', 'ETH');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_admin`
--

CREATE TABLE `ebbi_admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `private_key` varchar(255) DEFAULT NULL,
  `eth_address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_admin`
--

INSERT INTO `ebbi_admin` (`id`, `username`, `password`, `status`, `private_key`, `eth_address`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, '0x3ec737406eb015afb0ffaa3464d0706595dfc4d462e82f8d1592d15c212676b4', '0x0d8560552BBE2F7Dd411396A64EAe27Ef9959E68');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_coinbase_tokens`
--

CREATE TABLE `ebbi_coinbase_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `scope` varchar(256) NOT NULL,
  `token` varchar(512) NOT NULL,
  `refresh_token` varchar(512) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_coinbase_tokens`
--

INSERT INTO `ebbi_coinbase_tokens` (`id`, `user_id`, `scope`, `token`, `refresh_token`, `created_at`, `updated_at`) VALUES
(1, 4, 'wallet:accounts:read,wallet:addresses:read,wallet:buys:read,wallet:buys:create,wallet:transactions:read,wallet:transactions:send,wallet:payment-methods:read,wallet:addresses:create', '93f00223cf406d0a58c58ee4f847ee3de6ade571ef147c75ff0fa47681591355', 'f69d2757396d159938ac3469302ee39a3bad1af98c688ff825cf98723fd43314', '2018-03-13 09:36:43', '2018-03-13 09:36:43'),
(2, 1, 'wallet:accounts:read,wallet:addresses:read,wallet:buys:read,wallet:buys:create,wallet:transactions:read,wallet:transactions:send,wallet:payment-methods:read,wallet:addresses:create', '70e7ecff64400526a0d836af49f43c50925ba7899faeb030d8922b8dac778b3e', 'ca7a8f20a6b4bdd4bfb5ae4a0a4157e58aa6d55fe3ec08ee19253661bac46efa', '2018-03-14 07:49:43', '2018-03-14 07:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_login`
--

CREATE TABLE `ebbi_login` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_login`
--

INSERT INTO `ebbi_login` (`id`, `user_id`, `username`, `token`, `ip_address`, `created_at`) VALUES
(1, 1, 'hemant', '5aa3797d0de8720180310115149000000dR56IU8H4nIRIZHw7PWU97aFbv1N145QagRCiYXkVyD6bLNFSgW39rOSz5cGOhjfl67qZJAsBfKTeqbCDzmmVxqtjsw2cEpZMKpNOsohvEeclrKwd3XHPaSiWQJArTBo', 'ebbicoin.us', '2018-03-10 06:21:49'),
(2, 1, 'hemant', '5aa60f7e0616e20180312105622000000hgejaE92ORAuZRcqSzP8Qd6m2bOnsfTPSFWldr5mZ0apWbBUhmVJDIf5vhp53IYo1TzTwNMUwKgLX4rixIOg4vBdq1KVk7Z7ckL8lBHxk9EnuWX9cCtDwDASMCbx04Je', 'ebbicoin.us', '2018-03-12 05:26:22'),
(3, 1, 'hemant', '5aa616cc7226620180312112732000000N4IFsADKvQRZVUeEjdzin8bATT9NputCguAUXSIoo92QLXkPFdKQHpxKYz6bmslSSDeEMh1TrlBCbxjiWFG1w6cY7qJf6BuDrCjYe4yrG3WciVW5XLVxZ88NaoJq30wa', 'ebbicoin.us', '2018-03-12 05:57:32'),
(4, 1, 'hemant', '5aa630daca92720180312131842000000mHe9zuX3oVR98q74QU56wTJOrEfvIdl0tYQghCkhWK49VwyNFZUZc1exvFxP1bKkAqaC7Z3pB4vnuCMqGcBGBgwJnWNc6o5VLxKj8X8zhmaiHDDufQAp2IARyJPdLn5r', 'ebbicoin.us', '2018-03-12 07:48:42'),
(5, 4, 'rajat', '5aa783ecc933e20180313132524000000zytCgZgLrPZsoBOsEuZ4P0WI57wOSDoSGTujKaInxppCLGYWiPTkmfQp2VbabNz0mdBv877raUhF823b3e1UeSEJGDC9gAtT6OBYdMyjQ4z1Ani5iLys2FR5Kv1Ecj6o', 'ebbicoin.us', '2018-03-13 07:55:24'),
(6, 1, 'hemant', '5aa8b9b31d55720180314112707000000VzVqQwMlhIRZDTfxvoMfRA9wkWDb8IG4Jdx31Uljp0swtukO3hTj9i2HG0s4BqpbmK6aF8PdaeLibSXIK6HmTGnfSArup1J8tCLCEXOnuA4lzqZiceEoN6VJM55o7Zgh', 'ebbicoin.us', '2018-03-14 05:57:07'),
(7, 1, 'hemant', '5aaa3ae38553a20180315145035000000nO1YwxRSEzGIWeohkUDydANlaCTXiHcK', 'ebbicoin.us', '2018-03-15 09:20:35'),
(8, 1, 'hemant', '5aaa3d04dd807201803151459400000005tB0zCJOZkMlypQbSuF9IHGaLm7P8c6U', 'ebbicoin.us', '2018-03-15 09:29:40'),
(9, 1, 'hemant', '5aaa46581309e20180315153928000000jcpwCXkfVBntxEFSqeGvrQd4m3giP2hz', 'ebbicoin.us', '2018-03-15 10:09:28'),
(10, 1, 'hemant', '5aab5121bde7b20180316103745000000H41si29lrMSUuzZLYPvhTWXEwRyJQcbg', 'ebbicoin.us', '2018-03-16 05:07:45'),
(11, 1, 'hemant', '5aac9f769e53e20180317102414000000EX4vrHAbsTZBiaJRK0ScF3IktVMnGdW8', 'ebbicoin.us', '2018-03-17 04:54:14'),
(12, 6, 'hemant', '5ab205312387220180321123937000000IRD7ewUWPXQE8vZxhfqJFyKAr4adjsgC', 'ebbicoin.us', '2018-03-21 07:09:37'),
(13, 6, 'hemant', '5ab2201e2aab820180321143430000000UcSY5ajAQVPnKprNEhlvoktIgWB0s16f', 'ebbicoin.us', '2018-03-21 09:04:30'),
(14, 6, 'hemant', '5ab234e98be5120180321160313000000I6WLPjNmGOsF0Rgv4YMBEQeoqJTfA1dH', 'ebbicoin.us', '2018-03-21 10:33:13'),
(15, 6, 'hemant', '5ab2441064cd420180321170752000000pUA7OIsiMr39C5z8wTdyqZ6EF0Rn2VQe', 'ebbicoin.us', '2018-03-21 11:37:52'),
(16, 6, 'hemant', '5ab339cf6f37920180322103623000000gURJpqrQFtPyCTSiHkBb0wWs9XDcmneM', 'ebbicoin.us', '2018-03-22 05:06:23'),
(17, 6, 'hemant', '5ab5d9b420fa820180324102308000000Yb815d2pCRPOKlXwc0LFjAUWxSqkeBz4', 'ebbicoin.us', '2018-03-24 04:53:08'),
(18, 3, 'chandra', '5ab8927b541e720180326115603000000XF94ZQHG3rs52jbUIxunEPplBWqNcfvY', 'ebbicoin.us', '2018-03-26 06:26:03'),
(19, 6, 'hemant', '5abb3cfe8931c20180328122806000000RbAI2QCzgfXNLqMOa7E9v0yDlWF6c4pV', 'ebbicoin.us', '2018-03-28 06:58:06'),
(20, 6, 'hemant', '5abb444de621a20180328125917000000BmbD9PxE2Ig6WGcuS1TRzJQeA7wKjUrM', 'ebbicoin.us', '2018-03-28 07:29:17'),
(21, 1, 'hemant', '5abb6acbaa96120180328154331000000FJg1A436m7LWryvNbaSOUDCjhnTd5ZVP', 'ebbicoin.us', '2018-03-28 10:13:31'),
(22, 1, 'hemant', '5abc76d14d3dc20180329104705000000gybDmL2U9tPNeOvCjaGsq8Jr71wTuE3i', 'ebbicoin.us', '2018-03-29 05:17:05'),
(23, 1, 'hemant', '5abdc3c248d3620180330102738000000z1clx5G2SN9hgeQAjMdw6oyZqv4XBtFa', 'ebbicoin.us', '2018-03-30 04:57:38'),
(24, 1, 'hemant', '5abdd7763d3d720180330115142000000ItnSglMxYCXVyeko18Qv4Ep9AufZ2jPT', 'ebbicoin.us', '2018-03-30 06:21:42'),
(25, 1, 'hemant', '5ac5c3d95200b20180405120609000000ozuTZ8m0Ly7NRnbe5OhP1EMCpviJdgk6', 'ebbicoin.us', '2018-04-05 06:36:09'),
(26, 1, 'hemant', '5ac5c4469a9f720180405120758000000D3bdvw5GqOhVnEfpoIrWT1lNJKzXYisL', 'ebbicoin.us', '2018-04-05 06:37:58'),
(27, 4, 'ritika', '5ac5ccd7c130220180405124431000000i6tbyNVAxLW8THleFBvfXZrQ7U1ouj5c', 'ebbicoin.us', '2018-04-05 07:14:31'),
(28, 1, 'hemant', '5ac5e13385deb20180405141123000000Oqtwp8aC36V0iXuJRhcnlTSBbyfgK2LE', 'ebbicoin.us', '2018-04-05 08:41:23'),
(29, 1, 'hemant', '5ac727fc681fd20180406132540000000rbDUAgmQleJioLZKfnuyTR156YdISM4t', 'ebbicoin.us', '2018-04-06 07:55:40'),
(30, 1, 'hemant', '5ac73bbb171bb201804061449550000003CcxneTbrEPo5a0Uu2i9w64VAtOmBKpz', 'ebbicoin.us', '2018-04-06 09:19:55'),
(31, 1, 'hemant', '5ac76b35bfcde2018040618122900000091fQkCFg3BYhjI2r7D4zHusSqKXTJdAP', 'ebbicoin.us', '2018-04-06 12:42:29'),
(32, 1, 'hemant', '5afd6a45ddeab20180517171053000000Mx4sw28ACzSq6kRUBZJWdEmgPY5oHrb3', 'ebbicoin.us', '2018-05-17 11:40:53'),
(33, 1, 'hemant', '5affac00eee5720180519101552000000zn1697gv4jZlreCwptcfhSyMsELPmFdB', '::1', '2018-05-19 04:45:52'),
(34, 1, 'hemant', '5affb4dd37bf920180519105341000000Ji6UMSOKQ2mTkPb5vua30wqyldDBtFLC', '::1', '2018-05-19 05:23:41'),
(35, 1, 'hemant', '5b02984fbd8ca20180521152839000000s8SYA2DjxHzOgZmCc9Bpu4owWanLQPFI', '::1', '2018-05-21 09:58:39'),
(36, 1, 'hemant', '5b029883743e020180521152931000000nfx1o9pcqSOU3tyQRvDCzWY8MIlKeTiE', '::1', '2018-05-21 09:59:31'),
(37, 1, 'hemant', '5b029955b9a8620180521153301000000fQkrCGJg90Ye4EmMb851AP7SHjw3DTWL', '192.168.1.137', '2018-05-21 10:03:01'),
(38, 1, 'hemant', '5b02aac255b8f20180521164722000000gDnfxsC80oH6SVw4kdPq1XUhRmGMyEtb', '192.168.1.61', '2018-05-21 11:17:22'),
(39, 1, 'hemant', '5b03a92362cec20180522105243000000PSAz4dB5jFEJ0XO8tVkvZqi7ysp1aGxU', '192.168.1.61', '2018-05-22 05:22:43');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_referral_income`
--

CREATE TABLE `ebbi_referral_income` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `referred_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `referral_income` double NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_referral_income`
--

INSERT INTO `ebbi_referral_income` (`id`, `user_id`, `referred_id`, `amount`, `referral_income`, `level`, `created_at`) VALUES
(1, 1, 4, 100, 10, 1, '2018-03-21 09:43:41'),
(2, 2, 3, 2609.2119504, 260.92119504, 1, '2018-03-26 07:10:30'),
(3, 2, 3, 2609.2119504, 78.276358512, 2, '2018-03-26 07:10:30'),
(4, 2, 3, 2609.2119504, 52.184239008, 3, '2018-03-26 07:10:30'),
(5, 2, 3, 2609.2119504, 26.092119504, 4, '2018-03-26 07:10:30'),
(6, 2, 3, 2609.2119504, 13.046059752, 5, '2018-03-26 07:10:30'),
(7, 1, 4, 8583.3927120124, 858.33927120124, 1, '2018-04-05 07:55:00'),
(8, 1, 4, 8571.1099619409, 857.11099619409, 1, '2018-04-05 07:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_tickets`
--

CREATE TABLE `ebbi_tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0=open, 1=closed',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_tickets`
--

INSERT INTO `ebbi_tickets` (`id`, `user_id`, `title`, `description`, `image`, `status`, `created_at`) VALUES
(1, 1, 'Hello this is test ticket', 'Hello this is test ticket creating by hemant.', '', 0, '2018-03-15 13:20:40'),
(2, 1, 'Hello test ticket', 'Hello this is test ticket genarating for checking that ticket eorking ir not', '5aacbf9b9a713download.jpeg', 0, '2018-03-17 07:11:23'),
(3, 6, 'QWerty', 'hello', '5ab22a0a26f0cjokre.jpg', 0, '2018-03-21 09:46:50'),
(4, 1, 'Hello', 'Test', '', 1, '2018-05-19 05:24:04');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_ticket_comments`
--

CREATE TABLE `ebbi_ticket_comments` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` longtext NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_ticket_comments`
--

INSERT INTO `ebbi_ticket_comments` (`id`, `ticket_id`, `user_id`, `comment`, `image`, `created_at`) VALUES
(1, 1, 1, 'qwerty', '5aaa74593df1edownload.jpeg', '2018-03-15 13:25:45'),
(2, 1, 0, 'This is just mail testing by Hemant from admin side. Thank you', '', '2018-03-15 13:28:05'),
(3, 2, 1, 'ddfdfd', '5aacbfa761589download.jpeg', '2018-03-17 07:11:35'),
(4, 4, 1, 'qwert', '', '2018-05-19 05:24:26'),
(5, 4, 0, 'asaddf d', '', '2018-05-19 05:25:05'),
(6, 4, 1, 'fsdgsfsdf', '5affb5537b134Big-Bazaar-Logo.jpg', '2018-05-19 05:25:39'),
(7, 2, 1, 'gjhgj', '5b03a95916566think-logo.png', '2018-05-22 05:23:37'),
(8, 2, 1, 'shalinee', '5b03a9700508cWhy-Software-Testing-is-important-and-Types-of-Software-Testing.jpg', '2018-05-22 05:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_transactions`
--

CREATE TABLE `ebbi_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `exchange` varchar(255) NOT NULL,
  `send_quantity` float NOT NULL,
  `send_rate` float NOT NULL,
  `receive_quantity` float NOT NULL,
  `receive_rate` float NOT NULL,
  `stage` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0=pending, 1=success, 2=failed, 3=rejected',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_transactions`
--

INSERT INTO `ebbi_transactions` (`id`, `user_id`, `transaction_id`, `exchange`, `send_quantity`, `send_rate`, `receive_quantity`, `receive_rate`, `stage`, `status`, `created_at`) VALUES
(1, 1, '123', 'ETH-EBBI', 4.9999, 601.956, 15048.6, 0.2, 0, 1, '2018-03-17 07:05:04'),
(2, 1, '123', 'ETH-EBBI', 1.99989, 601.956, 6019.24, 0.2, 0, 1, '2018-03-17 07:09:08'),
(3, 3, '123', 'ETH-EBBI', 0.98926, 522.587, 2584.87, 0.2, 0, 1, '2018-03-26 06:47:09'),
(4, 3, '123', 'ETH-EBBI', 1.58905, 522.189, 4148.92, 0.2, 0, 1, '2018-03-26 07:05:46'),
(5, 3, '123', 'ETH-EBBI', 0.99979, 522.189, 2610.4, 0.2, 0, 1, '2018-03-26 07:07:48'),
(6, 3, '123', 'ETH-EBBI', 0.99979, 521.952, 2609.21, 0.2, 0, 1, '2018-03-26 07:10:30'),
(7, 4, '123', 'ETH-EBBI', 4.49918, 381.554, 8583.39, 0.2, 0, 1, '2018-04-05 07:55:00'),
(8, 4, '123', 'ETH-EBBI', 4.49918, 381.008, 8571.11, 0.2, 0, 1, '2018-04-05 07:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `ebbi_users`
--

CREATE TABLE `ebbi_users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `referral` varchar(255) DEFAULT NULL,
  `ebbi_balance` float NOT NULL DEFAULT '0',
  `token` varchar(255) DEFAULT NULL,
  `btc_id` varchar(255) DEFAULT NULL,
  `btc_address` varchar(255) DEFAULT NULL,
  `eth_id` varchar(255) DEFAULT NULL,
  `eth_address` varchar(255) DEFAULT NULL,
  `ltc_id` varchar(255) DEFAULT NULL,
  `ltc_address` varchar(255) DEFAULT NULL,
  `bch_id` varchar(255) DEFAULT NULL,
  `bch_address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL COMMENT '0 not active || 1 active || 9 deleted',
  `tfa_key` varchar(255) NOT NULL,
  `tfa_status` tinyint(4) NOT NULL COMMENT '0=not active, 1=active, 2=deactivated',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ebbi_users`
--

INSERT INTO `ebbi_users` (`id`, `username`, `password`, `email`, `phone`, `referral`, `ebbi_balance`, `token`, `btc_id`, `btc_address`, `eth_id`, `eth_address`, `ltc_id`, `ltc_address`, `bch_id`, `bch_address`, `status`, `tfa_key`, `tfa_status`, `created_at`) VALUES
(1, 'hemant', '689f25f3f067756561ec6e9e7dd4d0b7', 'hemant@infograins.com', NULL, NULL, 1715.45, '5abb6a2522056201803281540450000003cPKUhfAIirnDYgV4LOZamtBEy0MTojR', NULL, NULL, '0x3c1b52d14662c7f44a4db714a8980eff4e87dbe5e575403f36197204557633ea', '0x34342F441b05b7CB3241357d16Af3C27564fceEa', NULL, NULL, NULL, NULL, 1, 'CID6SHW7FAAAZLKO', 0, '2018-03-28 10:10:45'),
(4, 'ritika', '18f7e61fa0064f3fba70db0cb1c7117c', 'ritika@infograins.com', NULL, 'hemant', 17154.5, '5ac5ccabac40b20180405124347000000MkwnNbrKCS2OZxUQcl0eAaI6BgVftEyY', NULL, NULL, '0x357235b7c06263a3f2847981aa60814f7b397799e70c3621bdef82fffb65670e', '0xf340d09DacA55c2D80DBDcb3402A24312a3630e9', NULL, NULL, NULL, NULL, 1, 'NGQL2A2IIMQZRPE7', 0, '2018-04-05 07:13:47'),
(6, 'hemant8462', 'e10adc3949ba59abbe56e057f20f883e', 'hemantanjana@yopmail.com', NULL, 'hemant', 0, '5affadba9792a20180519102314000000ywHs469P2j8CvJLt3aBGuFrmcSqlikno', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'ICCMG4JTNKWO5WFC', 0, '2018-05-19 04:53:14'),
(7, 'hemanthemant', 'e10adc3949ba59abbe56e057f20f883e', 'hemanthemant@yopmail.com', NULL, 'hemant', 0, '5affae2994632201805191025050000006pcSz9w5nKGNPCiaYtdlMZTW7XbgIrfA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 'V3DP7WF37XTCYK4C', 0, '2018-05-19 04:55:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ebbi_accounts`
--
ALTER TABLE `ebbi_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebbi_admin`
--
ALTER TABLE `ebbi_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebbi_coinbase_tokens`
--
ALTER TABLE `ebbi_coinbase_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebbi_login`
--
ALTER TABLE `ebbi_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ebbi_referral_income`
--
ALTER TABLE `ebbi_referral_income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebbi_tickets`
--
ALTER TABLE `ebbi_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebbi_ticket_comments`
--
ALTER TABLE `ebbi_ticket_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ebbi_transactions`
--
ALTER TABLE `ebbi_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `ebbi_users`
--
ALTER TABLE `ebbi_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ebbi_accounts`
--
ALTER TABLE `ebbi_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ebbi_admin`
--
ALTER TABLE `ebbi_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ebbi_coinbase_tokens`
--
ALTER TABLE `ebbi_coinbase_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ebbi_login`
--
ALTER TABLE `ebbi_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `ebbi_referral_income`
--
ALTER TABLE `ebbi_referral_income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ebbi_tickets`
--
ALTER TABLE `ebbi_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ebbi_ticket_comments`
--
ALTER TABLE `ebbi_ticket_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ebbi_transactions`
--
ALTER TABLE `ebbi_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ebbi_users`
--
ALTER TABLE `ebbi_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
