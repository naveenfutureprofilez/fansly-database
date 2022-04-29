-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 04, 2022 at 05:43 PM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whoyouinto`
--

-- --------------------------------------------------------

--
-- Table structure for table `banned`
--

CREATE TABLE `banned` (
  `id` int UNSIGNED NOT NULL,
  `ip` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `icon`) VALUES
(1, 'Instagramer', NULL),
(2, 'Youtubers', NULL),
(3, 'Financial Tipper ', NULL),
(4, 'Others', NULL),
(6, 'Influencers', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int UNSIGNED NOT NULL,
  `commentable_type` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `commentable_id` bigint UNSIGNED NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `creator_plans`
--

CREATE TABLE `creator_plans` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_general_ci NOT NULL,
  `amount` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `benefits` longtext COLLATE utf8mb4_general_ci,
  `month_2` json DEFAULT NULL,
  `month_3` json DEFAULT NULL,
  `month_6` json DEFAULT NULL,
  `yearly` json DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - InActive, 1 - Active, 2 - Archived',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Creator Subscription Plans';

--
-- Dumping data for table `creator_plans`
--

INSERT INTO `creator_plans` (`id`, `user_id`, `title`, `amount`, `benefits`, `month_2`, `month_3`, `month_6`, `yearly`, `status`, `created_at`, `updated_at`) VALUES
(1, 31, 'Updated Plan', '50', '[\"hello\",\"there\"]', NULL, NULL, NULL, NULL, 0, '2022-02-17 03:43:46', '2022-02-22 01:10:10'),
(2, 31, 'Subscription 1', '5.00', '[\"Hello\\nThere\\nHow\\nAre you\"]', '{\"off\": \"20\", \"amount\": \"4\"}', NULL, NULL, NULL, 1, '2022-02-17 03:44:08', '2022-02-17 03:44:08'),
(3, 31, 'Subscription 1', '5.00', '[\"Hello\",\"There\",\"How\",\"Are you\"]', '{\"off\": \"20\", \"amount\": \"4\"}', NULL, NULL, NULL, 1, '2022-02-17 03:49:45', '2022-02-17 03:49:45');

-- --------------------------------------------------------

--
-- Table structure for table `creator_profiles`
--

CREATE TABLE `creator_profiles` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `category_id` int DEFAULT NULL,
  `username` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `creating` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `profilePic` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `coverPic` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `isVerified` enum('No','Pending','Yes','Rejected') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `isFeatured` enum('No','Yes') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `fbUrl` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `twUrl` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ytUrl` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitchUrl` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `instaUrl` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `monthlyFee` double(10,2) DEFAULT NULL,
  `discountedFee` double(10,2) DEFAULT NULL,
  `minTip` double(10,2) DEFAULT NULL,
  `user_meta` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `popularity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payout_gateway` enum('None','PayPal','Bank Transfer') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'None',
  `payout_details` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `creator_profiles`
--

INSERT INTO `creator_profiles` (`id`, `user_id`, `category_id`, `username`, `name`, `creating`, `profilePic`, `coverPic`, `isVerified`, `isFeatured`, `fbUrl`, `twUrl`, `ytUrl`, `twitchUrl`, `instaUrl`, `monthlyFee`, `discountedFee`, `minTip`, `user_meta`, `popularity`, `created_at`, `updated_at`, `payout_gateway`, `payout_details`) VALUES
(10, 22, 4, 'theadmin', 'Site Admin', 'Patron of this website', 'profilePics/default-profile-pic.png', 'coverPics/default-cover.jpg', 'Yes', 'No', NULL, NULL, NULL, NULL, NULL, 10.00, NULL, 4.00, '{\"country\":\"United States\",\"city\":\"New York\",\"address\":\"NYC, New York\",\"id\":\"verification\\/gZbFPbF0sYuf312lbLkfO32Fk8dpy1tXI1BtZuzZ.png\"}', 5, '2020-12-04 08:19:47', '2021-09-16 08:45:37', 'None', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `creator_requests`
--

CREATE TABLE `creator_requests` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `address` json NOT NULL,
  `id_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_expiry` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_expire` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `id_no` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `verify_img` text COLLATE utf8mb4_general_ci,
  `social` json DEFAULT NULL,
  `remark` text COLLATE utf8mb4_general_ci,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - Pending, 1 - Need Update, 2 - Approved',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Creator Requests';

--
-- Dumping data for table `creator_requests`
--

INSERT INTO `creator_requests` (`id`, `user_id`, `address`, `id_type`, `id_expiry`, `id_expire`, `id_no`, `verify_img`, `social`, `remark`, `status`, `approved_at`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 31, '{\"zip\": \"32015\", \"city\": \"Jaipur\", \"state\": \"RAJ\", \"street\": \"devi marg\", \"country\": \"IN\"}', 'VI CARD', NULL, 0, '15478979', 'verify_31_1644572130.png', NULL, 'check this', 1, '2022-02-28 01:40:26', 22, '2022-02-11 04:05:30', '2022-02-28 02:11:31'),
(2, 31, '{\"zip\": \"32015\", \"city\": \"Jaipur\", \"state\": \"RAJ\", \"street\": \"devi marg\", \"country\": \"IN\"}', 'VI CARD', NULL, 0, '15478979', 'verify_31_1644572258.png', NULL, NULL, 3, '2022-02-28 02:12:34', 22, '2022-02-11 04:07:38', '2022-02-28 02:12:34'),
(3, 31, '{\"zip\": \"32015\", \"city\": \"Jaipur\", \"state\": \"RAJ\", \"street\": \"devi marg\", \"country\": \"IN\"}', 'VI CARD', NULL, 0, '15478979', 'verify_31_1644572294.png', '{\"facebook\": \"www.facebook.com\"}', NULL, 0, '2022-02-28 01:42:48', 22, '2022-02-11 04:08:14', '2022-02-28 01:42:48'),
(4, 31, '{\"zip\": \"32015\", \"city\": \"Jaipur\", \"state\": \"RAJ\", \"street\": \"devi marg\", \"country\": \"IN\"}', 'VI CARD', '02-11-2022', 1, '15478979', 'verify_31_1644572633.png', '{\"facebook\": \"www.facebook.com\"}', NULL, 0, '2022-02-28 01:26:42', 22, '2022-02-11 04:13:53', '2022-02-28 01:26:42');

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int UNSIGNED NOT NULL,
  `following_id` bigint UNSIGNED NOT NULL,
  `follower_id` bigint UNSIGNED NOT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `following_id`, `follower_id`, `accepted_at`, `created_at`, `updated_at`) VALUES
(6, 31, 30, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int UNSIGNED NOT NULL,
  `invoice_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `subscription_id` int NOT NULL,
  `amount` double(10,2) NOT NULL,
  `payment_status` enum('Paid','Action Required','Created') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `invoice_url` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL COMMENT 'user_id',
  `likeable_type` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `likeable_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `likeable_type`, `likeable_id`, `created_at`, `updated_at`) VALUES
(1, 29, NULL, 31, NULL, NULL),
(2, 30, NULL, 31, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int UNSIGNED NOT NULL,
  `from_id` int UNSIGNED NOT NULL,
  `to_id` int UNSIGNED NOT NULL,
  `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message_media`
--

CREATE TABLE `message_media` (
  `id` int NOT NULL,
  `message_id` int NOT NULL,
  `media_content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Image',
  `media_type` enum('Image','Video','Audio','ZIP') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lock_type` enum('Free','Paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Free',
  `lock_price` double(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `from_id` int DEFAULT NULL,
  `notifiable_id` bigint UNSIGNED DEFAULT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `target_id` int DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options_table`
--

CREATE TABLE `options_table` (
  `id` int UNSIGNED NOT NULL,
  `option_name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `option_value` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options_table`
--

INSERT INTO `options_table` (`id`, `option_name`, `option_value`) VALUES
(13, 'payment-settings.currency_code', 'USD'),
(14, 'payment-settings.currency_symbol', '$'),
(15, 'payment-settings.site_fee', '5'),
(16, 'STRIPE_PUBLIC_KEY', 'pk_'),
(17, 'STRIPE_SECRET_KEY', 'sk_'),
(18, 'stripeEnable', 'No'),
(19, 'paypalEnable', 'Yes'),
(20, 'STRIPE_WEBHOOK_SECRET', 'whsec_'),
(21, 'paypal_email', 'your@paypal.com'),
(22, 'admin_email', 'you@example.org'),
(29, 'withdraw_min', '20'),
(30, 'minMembershipFee', '2.99'),
(31, 'maxMembershipFee', '1000'),
(32, 'commentsPerPost', '5'),
(33, 'homepage_creators_count', '6'),
(34, 'browse_creators_per_page', '15'),
(35, 'feedPerPage', '10'),
(36, 'followListPerPage', '10'),
(37, 'seo_title', 'FansOnly - Paid Content Creators Platform'),
(38, 'seo_desc', 'REWARD YOUR FAVOURITE CREATORS HARD WORK WITH PHP PATRON CLONE SCRIPT'),
(39, 'seo_keys', 'fansonly, onlyfans clone script, php fansonly, php onlyfans, onlyfans clone'),
(40, 'site_title', 'PHP FansOnly'),
(46, 'homepage_headline', 'Reward your favourite creators hard work'),
(47, 'homepage_intro', 'The best platform where content creators meet their audience. Supporters can subscribe and support to their favourite creators and everyone\'s on win-win.'),
(51, 'home_callout', 'Are you a ##CONTENT CREATOR$$ looking for a way to let your fans support your hard work?\r\nWe will take care of the rest. An entire platform at your fingertips hasslefree.'),
(54, 'homepage_left_title', 'How it works for Creators'),
(55, 'home_left_content', 'Your supporters decide to reward you for your hard work by paying a monthly subscription. In exchange, you keep doing what you love & also offer them some perks. \r\n\r\nAlso, you can get a ton of tips from your most advocate fans.'),
(56, 'homepage_right_title', 'How it works for Supporters'),
(57, 'home_right_content', 'You love what someone does and it is useful to you. You would like to reward them by offering your well appreciated support! Now you have the means to do so by using our platform. \r\n\r\nFind their profile by their name or follow a link provided by the creator and join in.'),
(81, 'minTipAmount', '1.99'),
(82, 'maxTipAmount', '500'),
(83, 'admin_extra_CSS', NULL),
(84, 'admin_extra_JS', NULL),
(85, 'default_storage', 'public'),
(101, 'site_entry_popup', 'No'),
(102, 'entry_popup_title', 'Entry popup title'),
(103, 'entry_popup_message', 'Entry popup message'),
(104, 'entry_popup_confirm_text', 'Continue'),
(105, 'entry_popup_cancel_text', 'Cancel'),
(106, 'entry_popup_awayurl', 'https://google.com'),
(108, 'hide_admin_creators', 'No'),
(109, 'card_gateway', 'Crypto'),
(110, 'ccbill_clientAccnum', NULL),
(111, 'ccbill_Subacc', NULL),
(112, 'ccbill_flexid', NULL),
(113, 'ccbill_salt', NULL),
(118, 'enableMediaDownload', 'No'),
(130, 'laravel_short_pwa', 'FansApp'),
(131, 'PAYSTACK_PUBLIC_KEY', NULL),
(132, 'PAYSTACK_SECRET_KEY', NULL),
(134, 'allow_guest_profile_view', 'Yes'),
(135, 'allow_guest_creators_view', 'Yes'),
(136, 'lock_homepage', 'No'),
(138, 'hideEarningsSimulator', 'Show'),
(139, 'WAS_ACCESS_KEY_ID', NULL),
(140, 'WAS_SECRET_ACCESS_KEY', NULL),
(141, 'WAS_DEFAULT_REGION', NULL),
(142, 'WAS_BUCKET', NULL),
(143, 'DOS_ACCESS_KEY_ID', NULL),
(144, 'DOS_SECRET_ACCESS_KEY', NULL),
(145, 'DOS_DEFAULT_REGION', NULL),
(146, 'DOS_BUCKET', NULL),
(147, 'BACKBLAZE_ACCOUNT_ID', NULL),
(148, 'BACKBLAZE_APP_KEY', NULL),
(149, 'BACKBLAZE_BUCKET', NULL),
(150, 'BACKBLAZE_REGION', NULL),
(151, 'VULTR_ACCESS_KEY_ID', NULL),
(152, 'VULTR_SECRET_ACCESS_KEY', NULL),
(153, 'VULTR_DEFAULT_REGION', NULL),
(154, 'VULTR_BUCKET', NULL),
(155, 'AWS_ACCESS_KEY_ID', NULL),
(156, 'AWS_SECRET_ACCESS_KEY', NULL),
(157, 'AWS_DEFAULT_REGION', NULL),
(158, 'AWS_BUCKET', NULL),
(160, 'TransBank_ENV', 'Testing'),
(161, 'TransBank_CC', NULL),
(162, 'TransBank_Key', NULL),
(163, 'MERCADOPAGO_PUBLIC_KEY', 'test'),
(164, 'MERCADOPAGO_SECRET_KEY', 'test'),
(168, 'paystack-mode', 'DEFAULT'),
(174, 'SL_AUDIENCE_MIN', '10'),
(175, 'SL_AUDIENCE_MAX', '1000'),
(176, 'SL_AUDIENCE_PRE_NUM', '100'),
(177, 'SL_AUDIENCE_STEP', '1'),
(178, 'MSL_MEMBERSHIP_FEE_MIN', '5'),
(179, 'MSL_MEMBERSHIP_FEE_MAX', '900'),
(180, 'MSL_MEMBERSHIP_FEE_PRESET', '9'),
(181, 'MSL_MEMBERSHIP_FEE_STEP', '1'),
(182, 'lk', 'sdaddasdsadasdsadasda');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int UNSIGNED NOT NULL,
  `page_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `page_content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `page_title`, `page_slug`, `page_content`, `created_at`, `updated_at`) VALUES
(1, 'Terms of Service', 'tos', '<p>Phasellus blandit leo ut odio. Suspendisse nisl elit, rhoncus eget, elementum ac, condimentum eget, diam. Fusce a quam. Donec posuere vulputate arcu. Nullam tincidunt adipiscing enim.<br><br>Sed augue ipsum, egestas nec, vestibulum et, malesuada adipiscing, dui. Fusce risus nisl, viverra et, tempor et, pretium in, sapien. Maecenas vestibulum mollis diam. Maecenas ullamcorper, dui et placerat feugiat, eros pede varius nisi, condimentum viverra felis nunc et lorem. Quisque malesuada placerat nisl.<br></p>', '2016-08-21 13:33:03', '2019-06-28 12:03:27'),
(3, 'Privacy Policy', 'privacy-policy', '<h1>Privacy Policy Page Title</h1>\r\n<p>Aliquam eu nunc. Nullam vel sem. Curabitur at lacus ac velit ornare lobortis. Phasellus volutpat, metus eget egestas mollis, lacus lacus blandit dui, id egestas quam mauris ut lacus.</p>\r\n<ul>\r\n<li>one</li>\r\n<li><span style=\"font-size: 18pt;\">two</span></li>\r\n<li>three</li>\r\n</ul>\r\n<p>Sed hendrerit. Proin faucibus arcu quis ante. Cras id dui. Sed fringilla mauris sit amet nibh.</p>', '2016-08-28 03:16:04', '2021-08-26 01:39:36');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `gateway` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `p_meta` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `is_default` enum('No','Yes') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_promotions`
--

CREATE TABLE `plan_promotions` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `plan_id` int UNSIGNED NOT NULL,
  `prom_amount` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prom_discount` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avail_from` timestamp NOT NULL,
  `avail_to` timestamp NOT NULL,
  `allow_existing` tinyint(1) NOT NULL DEFAULT '0',
  `allow_expired` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Plan Promotions';

--
-- Dumping data for table `plan_promotions`
--

INSERT INTO `plan_promotions` (`id`, `user_id`, `plan_id`, `prom_amount`, `prom_discount`, `avail_from`, `avail_to`, `allow_existing`, `allow_expired`, `status`, `created_at`, `updated_at`) VALUES
(1, 31, 2, '3', NULL, '2022-02-16 18:30:00', '2022-02-17 18:30:00', 0, 0, 1, '2022-02-17 03:44:09', '2022-02-17 03:44:09'),
(2, 31, 3, '3', NULL, '2022-02-16 18:30:00', '2022-02-17 18:30:00', 0, 0, 1, '2022-02-17 03:49:45', '2022-02-17 03:49:45'),
(3, 31, 1, '10', NULL, '2022-02-21 18:30:00', '2022-02-22 18:30:00', 0, 0, 1, '2022-02-22 01:55:02', '2022-02-22 04:10:45');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `text_content` longtext COLLATE utf8mb4_general_ci,
  `publish_schedule` date DEFAULT NULL,
  `delete_schedule` date DEFAULT NULL,
  `is_conditional` tinyint(1) NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - Pending, 1 - Published, 2 - Archived, 3 - Reported',
  `approved_by` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Content Posts';

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `text_content`, `publish_schedule`, `delete_schedule`, `is_conditional`, `conditions`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 31, 'sdfsfsdfsdfs', '2022-03-03', '2022-03-03', 0, NULL, 0, NULL, NULL, '2022-03-03 05:30:56', '2022-03-03 05:30:56'),
(2, 31, 'I’ll be in my inbox later tonight, spent ALL day shooting TONS of new collabs with ladies I’ve never done so with before. For now gonna relax and eat some dinner. I can’t wait for you to seeeee.', NULL, NULL, 1, NULL, 0, NULL, NULL, '2022-03-02 18:40:45', '2022-03-02 18:40:45'),
(3, 31, 'sdadasdasdasd', NULL, NULL, 0, NULL, 0, NULL, NULL, '2022-03-02 22:28:38', '2022-03-02 22:28:38'),
(4, 31, 'zxczxcz', NULL, NULL, 0, NULL, 0, NULL, NULL, '2022-03-02 22:50:11', '2022-03-02 22:50:11'),
(5, 31, 'sdfsdf dsfsd fsdf sdfsd', NULL, NULL, 0, NULL, 0, NULL, NULL, '2022-03-02 22:55:30', '2022-03-02 22:55:30'),
(6, 31, 'sdfsdfsd sdfsd', NULL, NULL, 0, NULL, 1, NULL, NULL, '2022-03-02 22:56:17', '2022-03-02 22:56:17'),
(7, 31, 'drfgdfgdfgdfg', NULL, NULL, 0, NULL, 1, NULL, NULL, '2022-03-02 22:58:42', '2022-03-02 22:58:42'),
(8, 31, 'undefined', NULL, NULL, 0, NULL, 1, NULL, NULL, '2022-03-02 22:58:56', '2022-03-02 22:58:56'),
(9, 31, 'zxczxc', NULL, NULL, 0, NULL, 1, NULL, NULL, '2022-03-02 23:09:11', '2022-03-02 23:09:11'),
(10, 31, 'adasdasd', '2022-03-15', NULL, 0, NULL, 1, NULL, NULL, '2022-03-02 23:28:44', '2022-03-02 23:28:44'),
(11, 31, 'reterterterterte', '2022-03-03', NULL, 0, NULL, 1, NULL, NULL, '2022-03-02 23:40:22', '2022-03-02 23:40:22'),
(12, 31, 'easdasdasd', '2022-03-03', '2022-03-04', 0, NULL, 1, NULL, NULL, '2022-03-03 00:09:25', '2022-03-03 00:09:25'),
(13, 31, 'sdasd', '2022-03-03', '2022-03-03', 0, NULL, 1, NULL, NULL, '2022-03-03 00:09:39', '2022-03-03 00:09:39');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Comments';

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `is_like` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Likes';

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `user_id`, `post_id`, `is_like`, `created_at`, `updated_at`) VALUES
(1, 31, 13, 0, '2022-03-04 03:58:48', '2022-03-04 04:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `post_medias`
--

CREATE TABLE `post_medias` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `uid` text COLLATE utf8mb4_general_ci NOT NULL,
  `mime` text COLLATE utf8mb4_general_ci,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` text COLLATE utf8mb4_general_ci,
  `ext` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `aws_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Media Data';

--
-- Dumping data for table `post_medias`
--

INSERT INTO `post_medias` (`id`, `user_id`, `post_id`, `uid`, `mime`, `type`, `name`, `ext`, `size`, `aws_id`, `created_at`, `updated_at`) VALUES
(1, 31, 5, '68529d39-9712-4c2e-bd6c-80156823d266', 'image/png', 'image', 'black-transparent.png', 'png', '51876', '1', '2022-03-02 01:24:50', '2022-03-02 01:24:50'),
(2, 31, 5, '5d8f575c-c0b6-440e-a1fb-50f7fc5f0ce7', 'image/png', 'image', 'apppriceBg.png', 'png', '109313', '1', '2022-03-02 01:24:51', '2022-03-02 01:24:51'),
(3, 31, 5, '68529d39-9712-4c2e-bd6c-80156823d266', 'image/png', 'image', 'black-transparent.png', 'png', '51876', 'post/image/68529d39-9712-4c2e-bd6c-80156823d266.png', '2022-03-02 01:38:26', '2022-03-02 01:38:26'),
(4, 31, 5, '5d8f575c-c0b6-440e-a1fb-50f7fc5f0ce7', 'image/png', 'image', 'apppriceBg.png', 'png', '109313', 'post/image/5d8f575c-c0b6-440e-a1fb-50f7fc5f0ce7.png', '2022-03-02 01:38:27', '2022-03-02 01:38:27');

-- --------------------------------------------------------

--
-- Table structure for table `post_payments`
--

CREATE TABLE `post_payments` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `amount` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `paid_via` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `txn_id` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Payments';

-- --------------------------------------------------------

--
-- Table structure for table `post_previews`
--

CREATE TABLE `post_previews` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `uid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mime` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `aws_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Media Data';

-- --------------------------------------------------------

--
-- Table structure for table `post_reports`
--

CREATE TABLE `post_reports` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `reason` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `blocked_by` int UNSIGNED DEFAULT NULL,
  `blocked_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Reports';

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int NOT NULL,
  `reporter_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `reporter_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `reported_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `report_message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `reporter_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `payload` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('aBHc1Og5cAOoQf5vdJg7teTgifPuLjUu4YUjIUmV', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4814.0 Safari/537.36 Edg/99.0.1135.6', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjFDekFjZ1h1ZDUxbnN2ekRVUzdpQmNnczZ2Z0RwcmQ4NW91N1pWWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3QvbWFuaWZlc3QuanNvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1643108542),
('BGKHQTgG1vF9b5uedrNDzZNM10lSUYOp02n6EV75', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4814.0 Safari/537.36 Edg/99.0.1135.6', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTU2WEVMaVNxTlQzQ0QyVEhCbGtoQXRnc1BwUnB0SzhwNVlOeWFOVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3QvbWFuaWZlc3QuanNvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1643108926),
('tTci5BNjVCraYLREXMUVdPLqNeQQy43abZcDxggu', NULL, '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4814.0 Safari/537.36 Edg/99.0.1135.6', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNThYVkN2OVpnNG51YmdQUGp1SUtmUmxnREViM0hmVzU0QnA4OUN6NSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9sb2NhbGhvc3QvcmVnaXN0ZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1643108925);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `creator_id` int UNSIGNED NOT NULL,
  `plan_id` int UNSIGNED NOT NULL,
  `plan_duration` int NOT NULL COMMENT 'In months 1/2/3/6',
  `amount` varchar(10) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Actual Plan Amount',
  `amount_paid` varchar(10) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Actual Paid Amount',
  `discount` varchar(10) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT 'Discount In %',
  `auto_renew` tinyint NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `auto_renew_discount` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `start` timestamp NOT NULL COMMENT 'Starting time of plan',
  `end` timestamp NULL DEFAULT NULL COMMENT 'End date of the plan',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - Expired, 1 - Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table For Subscriptions';

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions_`
--

CREATE TABLE `subscriptions_` (
  `id` int UNSIGNED NOT NULL,
  `creator_id` int NOT NULL,
  `subscriber_id` int UNSIGNED NOT NULL,
  `subscription_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gateway` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `subscription_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `subscription_expires` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('Active','Canceled') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Active',
  `subscription_price` double(10,2) NOT NULL,
  `creator_amount` double(10,2) NOT NULL,
  `admin_amount` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_files`
--

CREATE TABLE `temp_files` (
  `id` int NOT NULL,
  `uid` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int DEFAULT NULL COMMENT 'Related Post',
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` text COLLATE utf8mb4_general_ci,
  `local_name` text COLLATE utf8mb4_general_ci,
  `mime` text COLLATE utf8mb4_general_ci,
  `size` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `ext` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Temporary Image Uploads';

--
-- Dumping data for table `temp_files`
--

INSERT INTO `temp_files` (`id`, `uid`, `user_id`, `post_id`, `type`, `name`, `local_name`, `mime`, `size`, `ext`, `created_at`, `updated_at`) VALUES
(1, 'c01ea9b2-6ffe-4419-bd9a-6eb152fc6f37', 31, NULL, 'image', 'apppriceBg.png', 'rc-upload-1646136245002-2', 'image/png', '109313', '', '2022-03-01 01:04:49', '2022-03-01 01:04:49'),
(2, '57356ad7-3d43-4593-a9f3-5c39b3e0f158', 31, NULL, 'image', 'black-transparent.png', 'rc-upload-1646136245002-3', 'image/png', '51876', '', '2022-03-01 01:04:49', '2022-03-01 01:04:49'),
(3, '67f4cbfe-46f9-4b1e-8146-395a62aa5035', 31, NULL, 'image', 'apppriceBg.png', 'rc-upload-1646136690535-2', 'image/png', '109313', '', '2022-03-01 01:12:00', '2022-03-01 01:12:00'),
(4, 'e1d1a4fb-5e5b-4d00-b520-b671f816eead', 31, NULL, 'image', 'black-transparent.png', 'rc-upload-1646136690535-3', 'image/png', '51876', '', '2022-03-01 01:12:00', '2022-03-01 01:12:00'),
(5, '4b28b5b6-77e5-46d4-b67d-c5cfea0b4e5a', 31, NULL, 'image', 'black-transparent.png', 'rc-upload-1646137260217-4', 'image/png', '51876', '', '2022-03-01 01:21:15', '2022-03-01 01:21:15'),
(6, '8a45c8e4-cf78-42be-9b6e-a349ad06e076', 31, NULL, 'image', 'apppriceBg.png', 'rc-upload-1646137260217-3', 'image/png', '109313', '', '2022-03-01 01:21:15', '2022-03-01 01:21:15'),
(7, '68529d39-9712-4c2e-bd6c-80156823d266', 31, 5, 'image', 'black-transparent.png', 'rc-upload-1646137656703-3', 'image/png', '51876', '', '2022-03-01 01:27:54', '2022-03-01 01:27:58'),
(8, '5d8f575c-c0b6-440e-a1fb-50f7fc5f0ce7', 31, 5, 'image', 'apppriceBg.png', 'rc-upload-1646137656703-2', 'image/png', '109313', '', '2022-03-01 01:27:54', '2022-03-01 01:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `tips`
--

CREATE TABLE `tips` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `receiver_id` int UNSIGNED NOT NULL,
  `amount` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `tip_type` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tip_via` int UNSIGNED DEFAULT NULL,
  `txn_id` text COLLATE utf8mb4_general_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Tips';

--
-- Dumping data for table `tips`
--

INSERT INTO `tips` (`id`, `user_id`, `receiver_id`, `amount`, `message`, `tip_type`, `tip_via`, `txn_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 30, 31, '100', 'Great', 'post', 13, 'sdsafsfsdfsfsf', 1, '2022-03-04 07:50:05', NULL),
(2, 29, 31, '20', 'gdfgd', 'post', 13, 'sadasfsaf', 1, '2022-03-04 07:50:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tips_`
--

CREATE TABLE `tips_` (
  `id` int NOT NULL,
  `tipper_id` int NOT NULL,
  `creator_id` int NOT NULL,
  `post_id` int NOT NULL,
  `amount` double(10,2) NOT NULL,
  `creator_amount` double(10,2) DEFAULT NULL,
  `admin_amount` double(10,2) DEFAULT NULL,
  `gateway` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` enum('Paid','Pending') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Paid',
  `intent` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unlocks`
--

CREATE TABLE `unlocks` (
  `id` int UNSIGNED NOT NULL,
  `tipper_id` int NOT NULL,
  `creator_id` int NOT NULL,
  `message_id` int NOT NULL,
  `amount` double(10,2) NOT NULL,
  `creator_amount` double(10,2) DEFAULT NULL,
  `admin_amount` double(10,2) DEFAULT NULL,
  `gateway` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_status` enum('Paid','Pending') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Paid',
  `intent` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'default.png',
  `banner` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `balance` double(10,2) NOT NULL DEFAULT '0.00',
  `isAdmin` enum('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'No',
  `role` tinyint NOT NULL DEFAULT '0' COMMENT '0 - User, 1 - Creator',
  `is_pro` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `isBanned` enum('No','Yes') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `bio` longtext COLLATE utf8_unicode_ci,
  `pre_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `phone`, `avatar`, `banner`, `email_verified_at`, `password`, `remember_token`, `settings`, `balance`, `isAdmin`, `role`, `is_pro`, `ip`, `isBanned`, `bio`, `pre_type`, `created_at`, `updated_at`) VALUES
(22, 'admin', 'Site Admin', 'jack@whoyouinto.com', NULL, 'users/default.png', 'default.png', NULL, '$2y$10$oNriGpf5DdsQDGT9PWDsDu1Eoj8zk.EumjoV3HXYZYbHVAsFlgOkK', NULL, NULL, 0.00, 'Yes', 0, 0, '127.0.0.1', 'No', NULL, NULL, '2020-12-04 08:19:47', '2022-02-07 06:34:30'),
(29, 'naveenteh', 'Naveen Tehpariya', 'naveen@fpdemo.com', '9876543210', 'users/default.png', 'default.png', NULL, '$2y$10$h9i3zF2//C8WWXybCnt5pewABDtiR.6t03Gzfi40BESSYChnccs1m', NULL, NULL, 0.00, 'No', 0, 0, NULL, 'No', NULL, NULL, '2022-02-09 04:23:25', '2022-02-09 04:23:25'),
(30, 'habuzabu', 'hhhbbhj ', 'pradeep@fpdemo.com', '7014111037', 'users/default.png', 'default.png', NULL, '$2y$10$C/U6TtWyFU7bUMm.k7Gyeewt0WY9JmrAIUkUrupjOv5YSKyYmSKSe', NULL, NULL, 0.00, 'No', 0, 0, NULL, 'No', NULL, NULL, '2022-02-10 01:27:44', '2022-02-10 01:27:44'),
(31, 'naveen_', 'Eclipsio', 'naveen_@fpdemo.com', '7014111000', 'banner_31_1645790159.png', 'default.png', NULL, '$2y$10$4XS72uNJqCyAAOThaz2.relijtTgP/a7BQXR/vERFvBqqCzAzAlDO', NULL, NULL, 0.00, 'No', 0, 0, NULL, 'No', 'The tech geak.', NULL, '2022-02-11 01:16:28', '2022-02-28 02:12:34'),
(32, 'naveeee', 'Naveen Tehp', 'naveen__@fpdemo.com', '9876543210', 'default.png', 'default.png', NULL, '$2y$10$9jugFYzUXpNwusmazMtVpuD5yywPbUpOHbJm8xz/sMd.CUguQ4wmG', NULL, NULL, 0.00, 'No', 0, 0, NULL, 'No', NULL, NULL, '2022-02-28 04:18:46', '2022-02-28 04:18:46'),
(33, 'naveeeee', 'Naveen Tehp', 'naveen___@fpdemo.com', '9876543210', 'default.png', 'default.png', NULL, '$2y$10$IhmPEvugPCq2rWtocTZDI.Vjrkz7BI1ZRs7WN5Jy3P6XkuK23UZM6', NULL, NULL, 0.00, 'No', 0, 0, NULL, 'No', NULL, NULL, '2022-02-28 04:19:25', '2022-02-28 04:19:25'),
(34, 'naveeeeee', 'Naveen Tehp', 'naveen__e_@fpdemo.com', '9876543210', 'default.png', 'default.png', NULL, '$2y$10$pvVYx5zH5Tns9lY2Fw1or.nP9EToAsCYwo260Se5shmdfVOb0B8FS', NULL, NULL, 0.00, 'No', 0, 0, NULL, 'No', NULL, NULL, '2022-02-28 04:20:46', '2022-02-28 04:20:46'),
(35, 'naveeeeeeee', 'Naveen Tehp', 'naveen__ee_@fpdemo.com', '9876543210', 'default.png', 'default.png', NULL, '$2y$10$rbOxPqwwU4MsDRSv4kgsz.3n3k1RLpfLaNNPAC2qAxEod2CszsTLW', NULL, NULL, 0.00, 'No', 0, 0, NULL, 'No', NULL, 'default', '2022-02-28 04:21:58', '2022-02-28 04:21:58');

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `amount` double(10,2) NOT NULL,
  `status` enum('Pending','Paid','Canceled') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_commentable_type_commentable_id_index` (`commentable_type`,`commentable_id`);

--
-- Indexes for table `creator_plans`
--
ALTER TABLE `creator_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `creator_profiles`
--
ALTER TABLE `creator_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creator_profiles_user_id_foreign` (`user_id`),
  ADD KEY `creator_profiles_username_index` (`username`);

--
-- Indexes for table `creator_requests`
--
ALTER TABLE `creator_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_follower_following_id_index` (`following_id`),
  ADD KEY `user_follower_follower_id_index` (`follower_id`),
  ADD KEY `user_follower_accepted_at_index` (`accepted_at`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_likeable_type_likeable_id_index` (`likeable_type`,`likeable_id`),
  ADD KEY `likes_user_id_index` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_media`
--
ALTER TABLE `message_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `options_table`
--
ALTER TABLE `options_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `options_table_option_name_unique` (`option_name`),
  ADD KEY `options_table_option_name_index` (`option_name`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plan_promotions`
--
ALTER TABLE `plan_promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_medias`
--
ALTER TABLE `post_medias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_payments`
--
ALTER TABLE `post_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_previews`
--
ALTER TABLE `post_previews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_reports`
--
ALTER TABLE `post_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD UNIQUE KEY `sessions_id_unique` (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions_`
--
ALTER TABLE `subscriptions_`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_files`
--
ALTER TABLE `temp_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tips_`
--
ALTER TABLE `tips_`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unlocks`
--
ALTER TABLE `unlocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `creator_plans`
--
ALTER TABLE `creator_plans`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `creator_profiles`
--
ALTER TABLE `creator_profiles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `creator_requests`
--
ALTER TABLE `creator_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `message_media`
--
ALTER TABLE `message_media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `options_table`
--
ALTER TABLE `options_table`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan_promotions`
--
ALTER TABLE `plan_promotions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `post_medias`
--
ALTER TABLE `post_medias`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `post_payments`
--
ALTER TABLE `post_payments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_previews`
--
ALTER TABLE `post_previews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_reports`
--
ALTER TABLE `post_reports`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions_`
--
ALTER TABLE `subscriptions_`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_files`
--
ALTER TABLE `temp_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tips`
--
ALTER TABLE `tips`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tips_`
--
ALTER TABLE `tips_`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `unlocks`
--
ALTER TABLE `unlocks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
