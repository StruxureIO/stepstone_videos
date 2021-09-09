-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 08, 2021 at 09:40 PM
-- Server version: 5.7.32
-- PHP Version: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `humhub`
--

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `id` bigint(20) NOT NULL,
  `video_title` varchar(120) NOT NULL,
  `embed_code` text NOT NULL,
  `description` text NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tags` text,
  `image_url` text,
  `views` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `video_title`, `embed_code`, `description`, `date_added`, `tags`, `image_url`, `views`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'Case Study - Hyside', '<iframe src=\"https://player.vimeo.com/video/468242529?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"2015-07-15 1132 Case Study_  6303 Hyside\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>\r\n', 'Hyside description ', '2021-06-16 15:19:44', '1,7,12', 'uploads/video-thumbnails/Case-Study-Hyside-thumbnail.png', 11, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(2, 'Loan Basics with Chad Rhoten', '<iframe src=\"https://player.vimeo.com/video/468236340?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"Guest Speaker_ Chad Rhoten-AMP Lending-How to finance 10 properties in 10 months.4.6.2016\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'How to finance 10 properties in 10 months', '2021-06-15 17:39:43', '1', '', 5, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(3, 'How to take advantage of Self Directed IRAs', '<iframe src=\"https://player.vimeo.com/video/468245293?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"2016-06-01 1136 Quest IRA-Learn all you need to know about Self Directed IRA_s with Rebecca\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'IRA Info', '2021-06-15 17:46:32', '1,2,12', 'uploads/video-thumbnails/How-to-take-advantage-of-Self-Directed-IRAs-thumbnail.png', 2, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(4, 'Dealing with Objections and avoiding foreclosures', '<iframe src=\"https://player.vimeo.com/video/468244724?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" style=\"position:absolute;top:0;left:0;width:100%;height:100%;\" title=\"2016-03-02 Dealing w/Objections and avoiding foreclosures\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'Dealing with Objections and avoiding foreclosures description', '2021-06-14 19:53:37', '2,7,9,10,12', 'uploads/video-thumbnails/Dealing-with-Objections-and-avoiding-foreclosures-thumbnail.png', 2, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(5, 'Lease Options with Alan Ceshker', '<iframe src=\"https://player.vimeo.com/video/468245643?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" style=\"position:absolute;top:0;left:0;width:100%;height:100%;\" title=\"Lease Option11216\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'Lease Option description', '2021-06-10 15:14:05', '', NULL, 1, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(6, 'Preparing to do Sub-To Transactions', '<iframe src=\"https://player.vimeo.com/video/468245721?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" style=\"position:absolute;top:0;left:0;width:100%;height:100%;\" title=\"2015-09-02 1146 Jana-Sub-to Transactions\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>\r\n', 'Special Preparing to do Sub-To Transactions description', '2021-06-14 14:48:58', '1,7,12', '', 11, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(7, 'Case Study - Acadian', '<iframe src=\"https://player.vimeo.com/video/468242329?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"2015-08-19 1134 Making the most from every lead Case Study_ 9618 Acadian San Antonio\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'New Acadian description', '2021-06-20 13:10:33', '2,5,7', 'uploads/video-thumbnails/Case-Study-Acadian-thumbnail-1.png', 3, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(15, 'Cold Calling', '<iframe src=\"https://player.vimeo.com/video/469099843?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"10.16.20 Cold Calling\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'Cold Calling description', '2021-06-21 18:30:34', '1', 'uploads/video-thumbnails/Cold-Calling.png', 16, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(17, 'Cold Calling 2', '<iframe src=\"https://player.vimeo.com/video/468191415?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"5.8.20 Cold Calling\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'Cold Calling 2 description', '2021-06-21 18:37:32', '1', 'uploads/video-thumbnails/Cold-Calling-2.png', 5, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(19, '1031 Exchange', '<iframe src=\"https://player.vimeo.com/video/468198901?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"6.12.20 1031 Exchange\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', '1032 Exchange description', '2021-08-03 19:00:11', '9,10', 'uploads/video-thumbnails/1031-Exchange.png', 1, '2021-08-19 00:00:00', 4, '2021-08-23 15:56:54', 4),
(34, '5.7.21 Tips for Managing Your Metrics', '<iframe src=\"https://player.vimeo.com/video/546645105\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', '5.7.21 Tips for managing your metrics descriptions', '2021-08-03 20:10:47', '2', 'uploads/video-thumbnails/Tips-for-managing-your-metrics-5-7-21.png', 0, '2021-08-19 00:00:00', 4, '2021-08-19 00:00:00', 4),
(36, 'Business Structures', '<iframe src=\"https://player.vimeo.com/video/468201632?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"Biz Structures\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'Business Structures description', '2021-08-05 15:02:07', '2,9', 'uploads/video-thumbnails/Business-Structures.png', 3, '2021-08-19 00:00:00', 4, '2021-08-20 18:48:40', 4),
(37, 'How to utilize Facebook Instagram ads to drive more real estate leads', '<iframe src=\"https://player.vimeo.com/video/527495524?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"3.19.21 How to Utilize Facebook   Instagram Ads to Drive More Real Estate Leads.mp4\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'How to utilize Facebook Instagram ads to drive more real estate leads description', '2021-08-18 21:24:54', NULL, 'uploads/video-thumbnails/How-to-utilize-Facebook-Instagram-ads-to-drive-more-real-estate-leads-1.png', 1, '2021-08-19 00:00:00', 4, '2021-08-22 12:19:51', 4),
(40, 'Getting the ARV', '<iframe src=\"https://player.vimeo.com/video/468214615?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479\" allow=\"autoplay; fullscreen; picture-in-picture\" allowfullscreen=\"\" title=\"10.2.20 Getting the ARV\" width=\"600\" height=\"340\" frameborder=\"0\"></iframe>', 'Getting the ARV description', '2021-08-19 12:27:10', NULL, 'uploads/video-thumbnails/How-to-utilize-Facebook-Instagram-ads-to-drive-more-real-estate-leads-4.png', 6, '2021-08-19 00:00:00', 4, '2021-08-22 12:14:44', 4);

-- --------------------------------------------------------

--
-- Table structure for table `videos_favorites`
--

CREATE TABLE `videos_favorites` (
  `fav_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos_favorites`
--

INSERT INTO `videos_favorites` (`fav_id`, `user_id`, `video_id`) VALUES
(21, 1, 6),
(23, 1, 5),
(24, 2, 3),
(25, 2, 1),
(27, 1, 17),
(32, 1, 3),
(35, 1, 4),
(36, 1, 1),
(37, 1, 7),
(38, 4, 34),
(39, 4, 18);

-- --------------------------------------------------------

--
-- Table structure for table `video_tags`
--

CREATE TABLE `video_tags` (
  `tag_id` int(11) NOT NULL,
  `tag_name` varchar(40) NOT NULL,
  `icon` varchar(30) DEFAULT NULL,
  `menu` tinyint(1) DEFAULT '0',
  `force_top` tinyint(1) NOT NULL DEFAULT '0',
  `views` bigint(20) NOT NULL DEFAULT '0',
  `hide_top` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `video_tags`
--

INSERT INTO `video_tags` (`tag_id`, `tag_name`, `icon`, `menu`, `force_top`, `views`, `hide_top`) VALUES
(1, 'Masterminds', 'fal fa-book', 1, 1, 14, 0),
(2, 'Training Videos', 'fad fa-books', 1, 0, 8, 0),
(5, 'Sub-Transactions', 'far fa-envelope-open-dollar', 1, 0, 2, 0),
(6, 'IRAs', 'far fa-door-closed', 0, 1, 16, 0),
(7, 'Case Studies', 'fal fa-globe-europe', 1, 0, 111, 0),
(9, 'Foreclosures', 'fal fa-angry', 0, 0, 18, 1),
(10, 'Leasing', 'fal fa-bed-empty', 0, 0, 23, 0),
(11, 'Taxes', 'far fa-file-invoice-dollar', 0, 1, 16, 0),
(12, 'Loans', 'far fa-money-bill-wave', 0, 0, 497, 1),
(13, 'Test 9', '', 0, 0, 0, 0),
(14, 'Tag 10', '', 0, 0, 0, 1),
(15, 'Tag 11', '', 0, 0, 0, 1),
(18, 'Tag 15', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `video_tag_list`
--

CREATE TABLE `video_tag_list` (
  `list_id` bigint(20) NOT NULL,
  `video_id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `video_tag_list`
--

INSERT INTO `video_tag_list` (`list_id`, `video_id`, `tag_id`) VALUES
(70, 6, 1),
(71, 6, 7),
(72, 6, 12),
(73, 15, 1),
(74, 17, 1),
(78, 1, 1),
(79, 1, 7),
(80, 1, 12),
(85, 2, 1),
(86, 4, 2),
(87, 4, 7),
(88, 4, 9),
(89, 4, 10),
(90, 4, 12),
(93, 3, 1),
(94, 3, 2),
(95, 3, 12),
(96, 7, 2),
(97, 7, 5),
(98, 7, 7),
(114, 34, 2),
(123, 36, 2),
(124, 36, 9),
(127, 19, 9),
(128, 19, 10);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videos_favorites`
--
ALTER TABLE `videos_favorites`
  ADD PRIMARY KEY (`fav_id`);

--
-- Indexes for table `video_tags`
--
ALTER TABLE `video_tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `video_tag_list`
--
ALTER TABLE `video_tag_list`
  ADD PRIMARY KEY (`list_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `videos`
--
ALTER TABLE `videos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `videos_favorites`
--
ALTER TABLE `videos_favorites`
  MODIFY `fav_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `video_tags`
--
ALTER TABLE `video_tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `video_tag_list`
--
ALTER TABLE `video_tag_list`
  MODIFY `list_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
