-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 25 Janvier 2015 à 22:42
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `vgbt`
--
CREATE DATABASE IF NOT EXISTS `vgbt` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `vgbt`;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_albums`
--

CREATE TABLE IF NOT EXISTS `vgbt_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

--
-- Contenu de la table `vgbt_albums`
--

INSERT INTO `vgbt_albums` (`id`, `name`, `date`) VALUES
(1, 'Final Fantasy 1 OST', '2015-01-25 18:45:50'),
(2, 'Final Fantasy 2 OST', '2015-01-25 22:55:39'),
(4, 'Final Fantasy III OSV', '2015-01-25 23:10:04'),
(5, 'Final Fantasy 4 OST', '2015-01-25 23:17:02');

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_composers`
--

CREATE TABLE IF NOT EXISTS `vgbt_composers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Contenu de la table `vgbt_composers`
--

INSERT INTO `vgbt_composers` (`id`, `name`, `date`) VALUES
(1, 'Nobuo Uematsu', '2015-01-19 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_consoles`
--

CREATE TABLE IF NOT EXISTS `vgbt_consoles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Contenu de la table `vgbt_consoles`
--

INSERT INTO `vgbt_consoles` (`id`, `name`) VALUES
(1, 'Nes'),
(2, 'Super Nintendo'),
(3, 'Nintendo 64'),
(4, 'GameCube'),
(5, 'Wii'),
(6, 'Wii U'),
(7, 'Mega drive'),
(8, 'Dreamcast'),
(9, 'PlayStation'),
(10, 'PlayStation 2'),
(11, 'PlayStation 3'),
(12, 'PlayStation 4'),
(13, 'Xbox'),
(14, 'Xbox 360'),
(15, 'Xbox One'),
(16, 'Game Boy'),
(17, 'Game Boy Color'),
(18, 'Game Boy Advance'),
(19, 'Nintendo DS'),
(20, 'Nintendo 3DS'),
(21, 'PlayStation Portable'),
(22, 'PlayStation Vita'),
(23, 'Neo Geo'),
(24, 'PC Engine'),
(25, 'Saturn'),
(26, 'Game Gear');

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_extracts`
--

CREATE TABLE IF NOT EXISTS `vgbt_extracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `game_id` int(11) NOT NULL,
  `composer_id` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `md5` varchar(32) COLLATE utf8_bin NOT NULL,
  `bitrate` int(11) NOT NULL,
  `sample_rate` int(11) NOT NULL,
  `encoding` varchar(128) COLLATE utf8_bin NOT NULL,
  `play_time` varchar(16) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=137 ;

--
-- Contenu de la table `vgbt_extracts`
--

INSERT INTO `vgbt_extracts` (`id`, `name`, `game_id`, `composer_id`, `size`, `md5`, `bitrate`, `sample_rate`, `encoding`, `play_time`, `date`) VALUES
(6, 'The Prelude', 1, 1, 840388, 'e441abe67f2000d0ad21eca4c5385cc6', 128000, 44100, 'UTF-8', '0:53', '2015-01-25 21:57:21'),
(7, 'Opening Theme', 1, 1, 1363673, '5c67ee4921563bb35a609d7a87ffbeb6', 128000, 44100, 'UTF-8', '1:25', '2015-01-25 21:57:21'),
(8, 'Cornelia Castle', 1, 1, 753452, 'd5dc55b649b87319ac7bf782acb58aea', 128000, 44100, 'UTF-8', '0:47', '2015-01-25 21:57:21'),
(9, 'Main Theme', 1, 1, 991271, '934081621aead1b727a7e2cc893675dc', 128000, 44100, 'UTF-8', '1:02', '2015-01-25 21:57:21'),
(10, 'Temple Of Chaos', 1, 1, 965776, 'ed9f41aa43c1059aef35d6d269e60774', 128000, 44100, 'UTF-8', '1:00', '2015-01-25 21:57:21'),
(11, 'Matoya''s Cave', 1, 1, 1165560, 'f6ef197de15dfdad2e5af5b56fe453e8', 128000, 44100, 'UTF-8', '1:13', '2015-01-25 21:57:21'),
(12, 'Town', 1, 1, 832447, 'fcdc80b1b30b8f005ccfd6d73a94507e', 128000, 44100, 'UTF-8', '0:52', '2015-01-25 21:57:21'),
(13, 'Shop', 1, 1, 957834, 'b570011805efd1113f3aaf987bdd457d', 128000, 44100, 'UTF-8', '1:00', '2015-01-25 21:57:21'),
(14, 'Ship', 1, 1, 770171, '9e35533f230afe81b223851bd8abe47f', 128000, 44100, 'UTF-8', '0:48', '2015-01-25 21:57:22'),
(15, 'Underwater Temple', 1, 1, 1396274, 'bc0b85e5989823476634815511b46e1f', 128000, 44100, 'UTF-8', '1:27', '2015-01-25 21:57:22'),
(16, 'Dungeon (The Marsh Cave)', 1, 1, 896812, '2ce3600474c9075ac40a67e19e14a820', 128000, 44100, 'UTF-8', '0:56', '2015-01-25 21:57:22'),
(17, 'Menu Screen', 1, 1, 648127, '069942f5ffb5ee2ad98a5b41372dfde4', 128000, 44100, 'UTF-8', '0:40', '2015-01-25 21:57:22'),
(18, 'Airship', 1, 1, 799846, '39730650c533717c336afa4801c6e352', 128000, 44100, 'UTF-8', '0:50', '2015-01-25 21:57:22'),
(19, 'Gurgu Volcano', 1, 1, 1163052, 'e8c06b1b7f80f6265ea5edb9c9b3b60a', 128000, 44100, 'UTF-8', '1:13', '2015-01-25 21:57:22'),
(20, 'The Floating Castle', 1, 1, 1176427, '18ce1126e5b6510aab95396da43cb59c', 128000, 44100, 'UTF-8', '1:14', '2015-01-25 21:57:22'),
(21, 'Battle, Scene I', 1, 1, 1534200, '810f7bf57fb5d5f61abc099418dc87f4', 128000, 44100, 'UTF-8', '1:36', '2015-01-25 21:57:22'),
(22, 'Victory Fanfare', 1, 1, 617616, 'c72789575802dc7ab047401bffd66799', 128000, 44100, 'UTF-8', '0:39', '2015-01-25 21:57:22'),
(23, 'Ending Theme', 1, 1, 1749031, 'e68f54125f7423a099f4eb914c560484', 128000, 44100, 'UTF-8', '1:49', '2015-01-25 21:57:22'),
(24, 'Sorrow', 1, 1, 836208, '2c227b96ae1b91011b78aec1c2f03e8d', 128000, 44100, 'UTF-8', '0:52', '2015-01-25 21:57:22'),
(25, 'Save Music', 1, 1, 117319, 'fc3236c3280658cbf5c7093116804a6f', 128000, 44100, 'UTF-8', '0:07', '2015-01-25 21:57:22'),
(26, 'The Prelude', 2, 1, 1105212, '7804cb61593ce7e70a14c4efd076730c', 192000, 44100, 'UTF-8', '0:46', '2015-01-25 22:56:54'),
(27, 'Battle, Scene I', 2, 1, 2122462, '9c6014d2ac8bc538fc5533e0708de311', 192000, 44100, 'UTF-8', '1:28', '2015-01-25 22:56:54'),
(28, 'Revification', 2, 1, 569356, '33bfe10331254afb088804a1f14ddd84', 192000, 44100, 'UTF-8', '0:24', '2015-01-25 22:56:54'),
(29, 'Reunion', 2, 1, 220048, 'da47d8185f6891ded4afdf3c9f47577f', 192000, 44100, 'UTF-8', '0:09', '2015-01-25 22:56:54'),
(30, 'The Rebel Army', 2, 1, 1781918, '5f8f9f0c57a9f4174f518b23ea62b6a2', 192000, 44100, 'UTF-8', '1:14', '2015-01-25 22:56:54'),
(31, 'Town', 2, 1, 2555654, '6319e1bdc835ff2af0a4e944e4ef75b2', 192000, 44100, 'UTF-8', '1:46', '2015-01-25 22:56:54'),
(32, 'Main Theme', 2, 1, 2054228, 'e42a80788b947487fa0cf4adedf4ef0b', 192000, 44100, 'UTF-8', '1:26', '2015-01-25 22:56:54'),
(33, 'Castle Pandemonium', 2, 1, 1536526, '2e08214766bb863b92d859db077cba96', 192000, 44100, 'UTF-8', '1:04', '2015-01-25 22:56:54'),
(34, 'The Empire Army', 2, 1, 2145624, '1c1c821da51338f0fc509c8270e02f2f', 192000, 44100, 'UTF-8', '1:29', '2015-01-25 22:56:54'),
(35, 'Chocobo', 2, 1, 578746, '5991d49fa7ebd6fd263b60c4019124a1', 192000, 44100, 'UTF-8', '0:24', '2015-01-25 22:56:54'),
(36, 'Magician''s Tower', 2, 1, 2052350, '799fb7e062abf44b6cc6de61abc1c4e1', 192000, 44100, 'UTF-8', '1:26', '2015-01-25 22:56:54'),
(37, 'Run!', 2, 1, 490480, '66ecd36da73eb29ed6625028eee89b77', 192000, 44100, 'UTF-8', '0:20', '2015-01-25 22:56:54'),
(38, 'Ancient Castle', 2, 1, 1215388, '2e7c465b911585ea8c06bfed5397f633', 192000, 44100, 'UTF-8', '0:51', '2015-01-25 22:56:54'),
(39, 'Dungeon', 2, 1, 2542508, '85922ebcafc6cbfa49cbde0b665f0463', 192000, 44100, 'UTF-8', '1:46', '2015-01-25 22:56:54'),
(40, 'The Emperor Revived', 2, 1, 648232, 'fc1b1d22e5522e7dffb03365ad942486', 192000, 44100, 'UTF-8', '0:27', '2015-01-25 22:56:55'),
(41, 'Battle, Scene II', 2, 1, 3120932, 'a4d05ca603521943096a644f20cd167c', 192000, 44100, 'UTF-8', '2:10', '2015-01-25 22:56:55'),
(42, 'Victory Fanfare', 2, 1, 943078, 'ac2820f705d77e19270cbb84a6c64ee7', 192000, 44100, 'UTF-8', '0:39', '2015-01-25 22:56:55'),
(43, 'Finale', 2, 1, 4545082, 'c5c12238285b87b0a43e495b11c3b4c8', 192000, 44100, 'UTF-8', '3:09', '2015-01-25 22:56:55'),
(44, 'Waltz', 2, 1, 975630, '250f8ae2efe87f9d983581ec10bb228c', 192000, 44100, 'UTF-8', '0:41', '2015-01-25 22:56:55'),
(45, 'The Queen''s Temptation', 2, 1, 685166, 'b3c2a7f6102f33e1569bdf30079321d7', 192000, 44100, 'UTF-8', '0:29', '2015-01-25 22:56:55'),
(46, 'Dead Music', 2, 1, 1179706, 'ca253c469ac247506a479dff2891363a', 192000, 44100, 'UTF-8', '0:49', '2015-01-25 22:56:55'),
(47, 'Fanfare', 2, 1, 158074, 'a8d7f27a4171c478ec6e26e35951b5be', 192000, 44100, 'UTF-8', '0:07', '2015-01-25 22:56:55'),
(48, 'Welcome To Our Group', 2, 1, 172472, '6ad7baf9db53bea44150b4abc4985517', 192000, 44100, 'UTF-8', '0:07', '2015-01-25 22:56:55'),
(49, 'The Prelude', 3, 1, 1164955, '2b1875fd2962b44886a3b64033bf79cc', 192000, 44100, 'UTF-8', '0:48', '2015-01-25 23:11:47'),
(50, 'Crystal Cave', 3, 1, 2417114, '918b451db4168e73545fae2d33a75e3c', 192000, 44100, 'UTF-8', '1:41', '2015-01-25 23:11:47'),
(51, 'Battle 1 / Fanfare', 3, 1, 2729488, '66881f10662e9155040d2d0b6e19ac47', 192000, 44100, 'UTF-8', '1:54', '2015-01-25 23:11:47'),
(52, 'Crystal Room', 3, 1, 699996, '7a61a5e5afc370a4a763e0ce849d284d', 192000, 44100, 'UTF-8', '0:29', '2015-01-25 23:11:47'),
(53, 'Opening Theme', 3, 1, 2085960, 'ac422924a54d91019ffa2bcaeda6996b', 192000, 44100, 'UTF-8', '1:27', '2015-01-25 23:11:47'),
(54, 'My Home Town', 3, 1, 2497868, '4b1ef8d0c382474be0afe3e5b1125f63', 192000, 44100, 'UTF-8', '1:44', '2015-01-25 23:11:47'),
(55, 'Eternal Wind', 3, 1, 3052504, 'b640d01d6548152fdb1da1323fc6d074', 192000, 44100, 'UTF-8', '2:07', '2015-01-25 23:11:47'),
(56, 'Jinn, The Fire', 3, 1, 1806764, '92853121a8f178da2a21a8e3bfa219a9', 192000, 44100, 'UTF-8', '1:15', '2015-01-25 23:11:48'),
(57, 'Dungeon', 3, 1, 1618338, '452e6334e18367f9ccb80a3856642694', 192000, 44100, 'UTF-8', '1:07', '2015-01-25 23:11:48'),
(58, 'Return Of The Warrior', 3, 1, 1518804, '7472ce32eaa90abda7b381ab935d0aff', 192000, 44100, 'UTF-8', '1:03', '2015-01-25 23:11:48'),
(59, 'The Way To The Top', 3, 1, 1007988, '0237247869a6aedf7ae4fb60d6182dc8', 192000, 44100, 'UTF-8', '0:42', '2015-01-25 23:11:48'),
(60, 'Cute Little Tozas', 3, 1, 1454952, 'ac27300ae3f7ecf77e6620b289eb8329', 192000, 44100, 'UTF-8', '1:01', '2015-01-25 23:11:48'),
(61, 'Shrine Of Nept', 3, 1, 1391100, '9b61d5d3f211e794202f986b5ce44703', 192000, 44100, 'UTF-8', '0:58', '2015-01-25 23:11:48'),
(62, 'Sailing Enterprise', 3, 1, 1495016, '2bfd83627c6a96c381635b9821d756d0', 192000, 44100, 'UTF-8', '1:02', '2015-01-25 23:11:48'),
(63, 'Living Forest', 3, 1, 1059946, '639b2dbe74c8c2578a99f22caa2f71ec', 192000, 44100, 'UTF-8', '0:44', '2015-01-25 23:11:48'),
(64, 'Time Remains', 3, 1, 2537306, '257a202819627a81da5c9d68d290a351', 192000, 44100, 'UTF-8', '1:46', '2015-01-25 23:11:48'),
(65, 'Chocobos!', 3, 1, 932242, 'c6dc3970a2955f933c80c2612fae0025', 192000, 44100, 'UTF-8', '0:39', '2015-01-25 23:11:48'),
(66, 'Big Chocobo!', 3, 1, 704378, '067628e41f10c0597caf334a55aa992a', 192000, 44100, 'UTF-8', '0:29', '2015-01-25 23:11:48'),
(67, 'Tower of Owen', 3, 1, 1567006, '2539d4d3641359b05623dbca0ffd491a', 192000, 44100, 'UTF-8', '1:05', '2015-01-25 23:11:48'),
(68, 'Vegies of Geasal', 3, 1, 1083108, '0aeb452928889ac9262033226c34ef20', 192000, 44100, 'UTF-8', '0:45', '2015-01-25 23:11:48'),
(69, 'Castle of Hain', 3, 1, 1986426, '70ce67ccdc6c09ed8c92102c0f4e99bc', 192000, 44100, 'UTF-8', '1:23', '2015-01-25 23:11:48'),
(70, 'Battle 2', 3, 1, 2397708, '4c1f8973fbcdd8cb9610a3aa0f30f9f8', 192000, 44100, 'UTF-8', '1:40', '2015-01-25 23:11:48'),
(71, 'The Requiem', 3, 1, 919722, '47e7f74091f6e4451b81bd12efb182f9', 192000, 44100, 'UTF-8', '0:38', '2015-01-25 23:11:48'),
(72, 'Go Above the Clouds!', 3, 1, 1071840, '0de423c2930608420a209cf81939eb9e', 192000, 44100, 'UTF-8', '0:45', '2015-01-25 23:11:48'),
(73, 'Boundless Ocean', 3, 1, 1890022, 'a7ea907c12f63aed93890953a690784d', 192000, 44100, 'UTF-8', '1:19', '2015-01-25 23:11:49'),
(74, 'Elia, The Maiden Of Water', 3, 1, 1962638, '55a48127a001e7c3b62bbcfbf8c9104b', 192000, 44100, 'UTF-8', '1:22', '2015-01-25 23:11:49'),
(75, 'Town of Water', 3, 1, 1606053, 'a1e5a62e8339f4ca7e51c5339b02c6e6', 192000, 44100, 'UTF-8', '1:07', '2015-01-25 23:11:49'),
(76, 'Let''s Play the Piano!', 3, 1, 274942, '361b81eeafb32e74416bc8b9a113bd26', 192000, 44100, 'UTF-8', '0:11', '2015-01-25 23:11:49'),
(77, 'Let''s Play the Piano Again!', 3, 1, 157880, '1a695ddc1f05931c1ea23457327c1531', 192000, 44100, 'UTF-8', '0:07', '2015-01-25 23:11:49'),
(78, 'Swift Twist', 3, 1, 932868, 'fc83f1dd99bca4dc8647d7d0c750d25e', 192000, 44100, 'UTF-8', '0:39', '2015-01-25 23:11:49'),
(79, 'Good Ol'' Fellows', 3, 1, 827308, '23ad9b5c7f514c921056c7b0608f45bd', 192000, 44100, 'UTF-8', '0:34', '2015-01-25 23:11:49'),
(80, 'In the Covert Town', 3, 1, 1469976, '91287d932ebd990bfd6547ac8fde0c9e', 192000, 44100, 'UTF-8', '1:01', '2015-01-25 23:11:49'),
(81, 'Salonia', 3, 1, 2417114, 'feeb415f96df2e07114bf6244f13b59d', 192000, 44100, 'UTF-8', '1:41', '2015-01-25 23:11:49'),
(82, 'Deep Under The Water', 3, 1, 2725106, '48013296672eaf882f1b21ae04519250', 192000, 44100, 'UTF-8', '1:54', '2015-01-25 23:11:49'),
(83, 'Beneath The Horizon', 3, 1, 2069293, 'dac3c6a6c558c613abae7e866de616e8', 192000, 44100, 'UTF-8', '1:26', '2015-01-25 23:11:49'),
(84, 'Let Me Know the Truth', 3, 1, 1342898, '12fb83c2d33f18ae4bc49ab37c94269d', 192000, 44100, 'UTF-8', '0:56', '2015-01-25 23:11:49'),
(85, 'Lute of Noah', 3, 1, 496546, 'c2ece3ec74aee7cacf629314c98aac0b', 192000, 44100, 'UTF-8', '0:21', '2015-01-25 23:11:49'),
(86, 'Good Morning!', 3, 1, 740060, '2d79c624559c56bbd4b11fa495098070', 192000, 44100, 'UTF-8', '0:31', '2015-01-25 23:11:50'),
(87, 'The Invincible', 3, 1, 2037367, '132dded023831efab897919146ea35cf', 192000, 44100, 'UTF-8', '1:25', '2015-01-25 23:11:50'),
(88, 'Forbidden Land', 3, 1, 1191406, '0369759dff5aa0876aedd97c917eb4ec', 192000, 44100, 'UTF-8', '0:50', '2015-01-25 23:11:50'),
(89, 'The Crystal Tower', 3, 1, 1794244, '4eab1879d050b68ed84e09d41b98870e', 192000, 44100, 'UTF-8', '1:15', '2015-01-25 23:11:50'),
(90, 'The Dark Crystals', 3, 1, 2481592, '278fae23d768c6392d933dd5bd1471b7', 192000, 44100, 'UTF-8', '1:43', '2015-01-25 23:11:50'),
(91, 'This Is the Last Battle', 3, 1, 3408072, '7008fcfaab38dc842efc7614fb7e1c4e', 192000, 44100, 'UTF-8', '2:22', '2015-01-25 23:11:50'),
(92, 'The Everlasting World', 3, 1, 9699998, 'fecf81ee6c52d989648f6f9613ce99bd', 192000, 44100, 'UTF-8', '6:44', '2015-01-25 23:11:50'),
(93, 'The Prelude', 4, 1, 1158842, '6bf8d219c30de9f5f8a1dca195d4722a', 128000, 44100, 'UTF-8', '1:12', '2015-01-25 23:33:38'),
(94, 'Red Wings', 4, 1, 2020859, '045b2f97f4deea06716db624a0ac6c66', 128000, 44100, 'UTF-8', '2:06', '2015-01-25 23:33:38'),
(95, 'Kingdom Baron', 4, 1, 1133949, 'b91910fe73eba85385aefb29b2cf7a16', 128000, 44100, 'UTF-8', '1:11', '2015-01-25 23:33:38'),
(96, 'Theme Of Love', 4, 1, 1758380, 'faba758df3cc4252cbfcff46508f35f1', 128000, 44100, 'UTF-8', '1:50', '2015-01-25 23:33:38'),
(97, 'Prologue', 4, 1, 1134367, '86fbb05f1e133443cacd2f6eec4d4b31', 128000, 44100, 'UTF-8', '1:11', '2015-01-25 23:33:38'),
(98, 'Welcome To Our Town', 4, 1, 785626, '56b8bb339377c86801b5eb16b694885e', 128000, 44100, 'UTF-8', '0:49', '2015-01-25 23:33:38'),
(99, 'Main Theme', 4, 1, 1370268, 'db8ba4e6abae048cd67c13df7bf3df72', 128000, 44100, 'UTF-8', '1:25', '2015-01-25 23:33:38'),
(100, 'Fight 1', 4, 1, 966604, '862327d0c874ca2364ed2f7aa4d99e0a', 128000, 44100, 'UTF-8', '1:00', '2015-01-25 23:33:38'),
(101, 'Fanfare', 4, 1, 416138, '3fcaaabf8bb3a646aeb19d591eb65bf2', 128000, 44100, 'UTF-8', '0:26', '2015-01-25 23:33:38'),
(102, 'Hello, Big Chocobo', 4, 1, 390728, '740b661c1a43592871b26bf832973968', 128000, 44100, 'UTF-8', '0:24', '2015-01-25 23:33:38'),
(103, 'Chocobo Chocobo', 4, 1, 489136, '5249b112cd9392034807ea6678f5b130', 128000, 44100, 'UTF-8', '0:30', '2015-01-25 23:33:38'),
(104, 'Into The Darkness', 4, 1, 1143006, 'fbe88e823f358bbb1f686f57ec37fe3a', 112000, 44100, 'UTF-8', '1:21', '2015-01-25 23:33:39'),
(105, 'Fight 2', 4, 1, 1026936, 'baa440a20eaf00e00f445a9e12448c6b', 112000, 44100, 'UTF-8', '1:13', '2015-01-25 23:33:39'),
(106, 'Ring Of Bomb', 4, 1, 740776, '8f94a867d60b58c42436d0af66ecd201', 112000, 44100, 'UTF-8', '0:53', '2015-01-25 23:33:39'),
(107, 'Rydia', 4, 1, 859766, 'c0d01244bee5384cb7f1b9cfbd8b7de5', 112000, 44100, 'UTF-8', '1:01', '2015-01-25 23:33:39'),
(108, 'Castle Damcyan', 4, 1, 889696, '1892f6b6d6ddb0e31fb257eaa79de06b', 112000, 44100, 'UTF-8', '1:03', '2015-01-25 23:33:39'),
(109, 'Cry In Sorrow', 4, 1, 861956, '3e13bbf80a2bf3d8935b7d3ec6369ee7', 112000, 44100, 'UTF-8', '1:01', '2015-01-25 23:33:39'),
(110, 'Melody Of Lute', 4, 1, 764136, '908daf5c76bc2b8bbb68747a901478df', 112000, 44100, 'UTF-8', '0:54', '2015-01-25 23:33:39'),
(111, 'Mt Ordeals', 4, 1, 1103951, '9f3944d5fd0d12eda52c0bcb2935a1f1', 112000, 44100, 'UTF-8', '1:19', '2015-01-25 23:33:39'),
(112, 'Fabul', 4, 1, 1336821, 'bdfbb66c522d6d3097863c189ec7ac4a', 112000, 44100, 'UTF-8', '1:35', '2015-01-25 23:33:39'),
(113, 'Run ', 4, 1, 351686, 'da24690e994f5718e907395043fcade0', 112000, 44100, 'UTF-8', '0:25', '2015-01-25 23:33:39'),
(114, 'Suspicion', 4, 1, 526521, '14ceccfe4b1ba4f6496e6f29ce967fd9', 112000, 44100, 'UTF-8', '0:37', '2015-01-25 23:33:39'),
(115, 'Golbeza Clad In The Dark', 4, 1, 850276, '0b9404968ef588c1d94c4faf2fa832d2', 112000, 44100, 'UTF-8', '1:00', '2015-01-25 23:33:39'),
(116, 'Hey, Cid ', 4, 1, 784941, '1db06ba6c36dbd0c862682722c67d6f9', 112000, 44100, 'UTF-8', '0:56', '2015-01-25 23:33:39'),
(117, 'Mystic Mysidia', 4, 1, 1120376, 'beaf60383a8eafc0d2c078131e121f50', 112000, 44100, 'UTF-8', '1:20', '2015-01-25 23:33:39'),
(118, 'Long Way To Go', 4, 1, 642956, '760df7d75511cfcd54ba323608192c1a', 112000, 44100, 'UTF-8', '0:46', '2015-01-25 23:33:40'),
(119, 'Palom & Porom', 4, 1, 523966, '8e8082d99727401c48d5546f5bed6a27', 112000, 44100, 'UTF-8', '0:37', '2015-01-25 23:33:40'),
(120, 'The Dreadful Fight', 4, 1, 1390476, '58003fc80ca9032d20946754ac7b06c7', 112000, 44100, 'UTF-8', '1:39', '2015-01-25 23:33:40'),
(121, 'The Airship', 4, 1, 779831, 'a7dbd5c863149b3f4c3ef592a49d10af', 112000, 44100, 'UTF-8', '0:55', '2015-01-25 23:33:40'),
(122, 'Trojain Beauty', 4, 1, 1170016, '16f0635061e096411dea69ee4795324b', 112000, 44100, 'UTF-8', '1:23', '2015-01-25 23:33:40'),
(123, 'Samba De Chocobo ', 4, 1, 637846, 'ef37e2b6de7322afa81a64878971f659', 112000, 44100, 'UTF-8', '0:45', '2015-01-25 23:33:40'),
(124, 'Tower Of Bab Il', 4, 1, 1276231, 'd8e122d053f0bfc945b11d949d79d802', 112000, 44100, 'UTF-8', '1:31', '2015-01-25 23:33:40'),
(125, 'Somewhere In The World', 4, 1, 475421, 'ddf7795213f6eced773e3561163c523a', 112000, 44100, 'UTF-8', '0:34', '2015-01-25 23:33:40'),
(126, 'Land Of Dwarves', 4, 1, 733294, 'a0d4caf37c4ed401e730ce1b11a0cf99', 112000, 44100, 'UTF-8', '0:52', '2015-01-25 23:33:40'),
(127, 'Giotto, The Great King', 4, 1, 806476, '2c972acb326aa01d2d1b2c568e0dba70', 112000, 44100, 'UTF-8', '0:57', '2015-01-25 23:33:40'),
(128, 'Dancing Calcobrena', 4, 1, 463376, '3af0d2877050ea454f9cdb848ec431f0', 112000, 44100, 'UTF-8', '0:33', '2015-01-25 23:33:40'),
(129, 'Tower Of Zot', 4, 1, 915611, '0780c4d8de8ae6349581b7e433b36381', 112000, 44100, 'UTF-8', '1:05', '2015-01-25 23:33:40'),
(130, 'Illusionary World', 4, 1, 1043361, 'dc3ffad79a665d13e52e6d5ac7eb3816', 112000, 44100, 'UTF-8', '1:14', '2015-01-25 23:33:41'),
(131, 'The Big Whale', 4, 1, 959776, 'b631c1587d5d9848028979d5e5521707', 112000, 44100, 'UTF-8', '1:08', '2015-01-25 23:33:41'),
(132, 'Another Moon', 4, 1, 938606, '7e5b2d5d2a289e5515eb5060d5a05538', 112000, 44100, 'UTF-8', '1:07', '2015-01-25 23:33:41'),
(133, 'The Lunarians', 4, 1, 1069276, '5435abe9a363ec72376635bd131d72b8', 112000, 44100, 'UTF-8', '1:16', '2015-01-25 23:33:41'),
(134, 'Within The Giant', 4, 1, 1222211, '2ae938773022f9bcd366c05cb0eb60db', 112000, 44100, 'UTF-8', '1:27', '2015-01-25 23:33:41'),
(135, 'The Final Battle', 4, 1, 1842302, '9c932badd51b1d99307cd98cfbc1f993', 128000, 44100, 'UTF-8', '1:55', '2015-01-25 23:33:41'),
(136, 'Epilogue', 4, 1, 8001646, '565b7ed468d0d200b5c385316f84b628', 112000, 44100, 'UTF-8', '9:31', '2015-01-25 23:33:41');

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_extract_album_links`
--

CREATE TABLE IF NOT EXISTS `vgbt_extract_album_links` (
  `extract_id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL,
  `disc_number` tinyint(11) unsigned NOT NULL DEFAULT '1',
  `track_number` tinyint(11) unsigned NOT NULL,
  PRIMARY KEY (`extract_id`,`album_id`,`disc_number`,`track_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Contenu de la table `vgbt_extract_album_links`
--

INSERT INTO `vgbt_extract_album_links` (`extract_id`, `album_id`, `disc_number`, `track_number`) VALUES
(6, 1, 1, 1),
(7, 1, 1, 2),
(8, 1, 1, 3),
(9, 1, 1, 4),
(10, 1, 1, 5),
(11, 1, 1, 6),
(12, 1, 1, 7),
(13, 1, 1, 8),
(14, 1, 1, 9),
(15, 1, 1, 10),
(16, 1, 1, 11),
(17, 1, 1, 12),
(18, 1, 1, 13),
(19, 1, 1, 14),
(20, 1, 1, 15),
(21, 1, 1, 16),
(22, 1, 1, 17),
(23, 1, 1, 18),
(24, 1, 1, 19),
(25, 1, 1, 20),
(26, 2, 1, 1),
(27, 2, 1, 2),
(28, 2, 1, 3),
(29, 2, 1, 4),
(30, 2, 1, 5),
(31, 2, 1, 6),
(32, 2, 1, 7),
(33, 2, 1, 8),
(34, 2, 1, 9),
(35, 2, 1, 10),
(36, 2, 1, 11),
(37, 2, 1, 12),
(38, 2, 1, 13),
(39, 2, 1, 14),
(40, 2, 1, 15),
(41, 2, 1, 16),
(42, 2, 1, 17),
(43, 2, 1, 18),
(44, 2, 1, 19),
(45, 2, 1, 20),
(46, 2, 1, 21),
(47, 2, 1, 22),
(48, 2, 1, 23),
(49, 4, 1, 1),
(50, 4, 1, 2),
(51, 4, 1, 3),
(52, 4, 1, 4),
(53, 4, 1, 5),
(54, 4, 1, 6),
(55, 4, 1, 7),
(56, 4, 1, 8),
(57, 4, 1, 9),
(58, 4, 1, 10),
(59, 4, 1, 11),
(60, 4, 1, 12),
(61, 4, 1, 13),
(62, 4, 1, 14),
(63, 4, 1, 15),
(64, 4, 1, 16),
(65, 4, 1, 17),
(66, 4, 1, 18),
(67, 4, 1, 19),
(68, 4, 1, 20),
(69, 4, 1, 21),
(70, 4, 1, 22),
(71, 4, 1, 23),
(72, 4, 1, 24),
(73, 4, 1, 25),
(74, 4, 1, 26),
(75, 4, 1, 27),
(76, 4, 1, 28),
(77, 4, 1, 29),
(78, 4, 1, 30),
(79, 4, 1, 31),
(80, 4, 1, 32),
(81, 4, 1, 33),
(82, 4, 1, 34),
(83, 4, 1, 35),
(84, 4, 1, 36),
(85, 4, 1, 37),
(86, 4, 1, 38),
(87, 4, 1, 39),
(88, 4, 1, 40),
(89, 4, 1, 41),
(90, 4, 1, 42),
(91, 4, 1, 43),
(92, 4, 1, 44),
(93, 5, 1, 1),
(94, 5, 1, 2),
(95, 5, 1, 3),
(96, 5, 1, 4),
(97, 5, 1, 5),
(98, 5, 1, 6),
(99, 5, 1, 7),
(100, 5, 1, 8),
(101, 5, 1, 9),
(102, 5, 1, 10),
(103, 5, 1, 11),
(104, 5, 1, 12),
(105, 5, 1, 13),
(106, 5, 1, 14),
(107, 5, 1, 15),
(108, 5, 1, 16),
(109, 5, 1, 17),
(110, 5, 1, 18),
(111, 5, 1, 19),
(112, 5, 1, 20),
(113, 5, 1, 21),
(114, 5, 1, 22),
(115, 5, 1, 23),
(116, 5, 1, 24),
(117, 5, 1, 25),
(118, 5, 1, 26),
(119, 5, 1, 27),
(120, 5, 1, 28),
(121, 5, 1, 29),
(122, 5, 1, 30),
(123, 5, 1, 31),
(124, 5, 1, 32),
(125, 5, 1, 33),
(126, 5, 1, 34),
(127, 5, 1, 35),
(128, 5, 1, 36),
(129, 5, 1, 37),
(130, 5, 1, 38),
(131, 5, 1, 39),
(132, 5, 1, 40),
(133, 5, 1, 41),
(134, 5, 1, 42),
(135, 5, 1, 43),
(136, 5, 1, 44);

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_games`
--

CREATE TABLE IF NOT EXISTS `vgbt_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `game_serie` int(11) DEFAULT NULL,
  `release_date` date NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Contenu de la table `vgbt_games`
--

INSERT INTO `vgbt_games` (`id`, `name`, `game_serie`, `release_date`, `date`) VALUES
(1, 'Final Fantasy 1', 1, '1987-12-18', '2015-01-25 17:49:15'),
(2, 'Final Fantasy 2', 1, '1988-12-17', '2015-01-25 17:49:37'),
(3, 'Final Fantasy 3', 1, '1990-04-27', '2015-01-25 17:50:29'),
(4, 'Final Fantasy 4', 1, '1991-07-19', '2015-01-25 17:51:02'),
(5, 'Final Fantasy 5', 1, '1992-12-06', '2015-01-25 17:51:21'),
(6, 'Final Fantasy 6', 1, '1994-04-02', '2015-01-25 17:51:55'),
(7, 'Final Fantasy 7', 1, '1997-01-31', '2015-01-25 17:57:57'),
(8, 'Final Fantasy 8', 1, '1999-02-11', '2015-01-25 17:59:04'),
(9, 'Final Fantasy 9', 1, '2000-07-07', '2015-01-25 17:59:25'),
(10, 'Final Fantasy 10', 1, '2001-02-19', '2015-01-25 17:59:43'),
(11, 'Final Fantasy 12', 1, '2006-03-16', '2015-01-25 18:00:16'),
(12, 'Final Fantasy 13', 1, '2009-12-17', '2015-01-25 18:00:50');

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_games_alternatives`
--

CREATE TABLE IF NOT EXISTS `vgbt_games_alternatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_game_console_links`
--

CREATE TABLE IF NOT EXISTS `vgbt_game_console_links` (
  `game_id` int(11) NOT NULL,
  `console_id` int(11) NOT NULL,
  PRIMARY KEY (`game_id`,`console_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `vgbt_game_console_links`
--

INSERT INTO `vgbt_game_console_links` (`game_id`, `console_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 2),
(6, 2),
(7, 9),
(8, 9),
(9, 9),
(10, 10),
(11, 10),
(12, 11);

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_game_series`
--

CREATE TABLE IF NOT EXISTS `vgbt_game_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Contenu de la table `vgbt_game_series`
--

INSERT INTO `vgbt_game_series` (`id`, `name`, `date`) VALUES
(1, 'Final Fantasy', '2015-01-19 00:22:10');

-- --------------------------------------------------------

--
-- Structure de la table `vgbt_names_alternatives`
--

CREATE TABLE IF NOT EXISTS `vgbt_names_alternatives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
