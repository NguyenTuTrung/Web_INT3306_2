-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2023 at 04:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'open : 1, closed : 2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `warehouse_id`, `name`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(6, 3, 'Chi Nhánh Cầu Giấy', 'caugiay_br@gmail.com', '0123456788', 'Nguyễn Khánh Toán, Cầu Giấy, Hà Nội', 1, '2023-12-22 10:17:33', '2023-12-22 10:17:33'),
(7, 4, 'Chi Nhánh Mỹ Đình', 'mydinh_br@gmail.com', '01234567891', 'Mỹ Đình, Hà Nội', 1, '2023-12-24 09:24:48', '2023-12-24 09:24:48');

-- --------------------------------------------------------

--
-- Table structure for table `courier_infos`
--

CREATE TABLE `courier_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` varchar(40) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `sender_warehouse_id` int(11) NOT NULL,
  `sender_branch_id` int(11) NOT NULL,
  `sender_staff_branch` int(11) NOT NULL,
  `sender_staff_id` int(11) NOT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `sender_email` varchar(40) DEFAULT NULL,
  `sender_phone` varchar(40) DEFAULT NULL,
  `sender_address` varchar(255) DEFAULT NULL,
  `receiver_warehouse_id` int(11) NOT NULL,
  `receiver_branch_id` int(11) DEFAULT NULL,
  `receiver_staff_id` int(11) DEFAULT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `receiver_email` varchar(40) DEFAULT NULL,
  `receiver_phone` varchar(40) DEFAULT NULL,
  `receiver_address` varchar(255) DEFAULT NULL,
  `status` tinyint(10) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courier_infos`
--

INSERT INTO `courier_infos` (`id`, `invoice_id`, `code`, `sender_warehouse_id`, `sender_branch_id`, `sender_staff_branch`, `sender_staff_id`, `sender_name`, `sender_email`, `sender_phone`, `sender_address`, `receiver_warehouse_id`, `receiver_branch_id`, `receiver_staff_id`, `receiver_name`, `receiver_email`, `receiver_phone`, `receiver_address`, `status`, `created_at`, `updated_at`) VALUES
(13, 'P5KYEKC86H58', 'SJUX22ZEZBYT', 3, 6, 14, 17, 'Nguyễn Tử Trung', 'sender@gmail.com', '0123456784', 'Cầu Giấy, Hà Nội', 4, 7, 17, 'Nguyễn Ngọc Huy', 'receiver@gmail.com', '0123456783', 'Mỹ Đình, Hà Nội', 3, '2023-12-22 12:41:00', '2023-12-24 12:23:45'),
(14, 'PAR4FB125ZTV', 'ADNCD12Y3H19', 4, 6, 14, 19, 'a', 'a@gmail.com', '123487654325', 'abc', 0, 7, 21, 'b', 'b@gmail.com', '213454343356', 'bcd', 6, '2023-12-23 09:10:09', '2023-12-24 09:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `courier_payments`
--

CREATE TABLE `courier_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `courier_info_id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Unpaid : 0, Paid : 1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courier_payments`
--

INSERT INTO `courier_payments` (`id`, `courier_info_id`, `receiver_id`, `branch_id`, `date`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(12, 13, 14, 6, '2023-12-22', 12.00000000, 1, '2023-12-22 12:41:00', '2023-12-22 12:42:00'),
(13, 14, 14, 6, '2023-12-23', 12.00000000, 1, '2023-12-23 09:10:09', '2023-12-23 09:10:17'),
(14, 15, NULL, NULL, NULL, 2.00000000, 0, '2023-12-25 00:43:42', '2023-12-25 00:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `courier_products`
--

CREATE TABLE `courier_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `courier_info_id` int(11) NOT NULL,
  `courier_type_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `fee` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courier_products`
--

INSERT INTO `courier_products` (`id`, `courier_info_id`, `courier_type_id`, `qty`, `fee`, `created_at`, `updated_at`) VALUES
(13, 13, 2, 12, 12.00000000, '2023-12-22 12:41:00', '2023-12-22 12:41:00'),
(14, 14, 2, 12, 12.00000000, '2023-12-23 09:10:09', '2023-12-23 09:10:09'),
(15, 15, 2, 2, 2.00000000, '2023-12-25 00:43:42', '2023-12-25 00:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_couriers`
--

CREATE TABLE `delivery_couriers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `courier_id` bigint(20) UNSIGNED NOT NULL,
  `reasons` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `mail_sender` varchar(40) DEFAULT NULL,
  `email_from` varchar(40) DEFAULT NULL,
  `email_to` varchar(40) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_sms_templates`
--

CREATE TABLE `email_sms_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `act` varchar(40) NOT NULL,
  `name` varchar(40) NOT NULL,
  `subj` varchar(255) NOT NULL,
  `email_body` text DEFAULT NULL,
  `sms_body` text DEFAULT NULL,
  `shortcodes` text NOT NULL,
  `email_status` tinyint(1) NOT NULL DEFAULT 1,
  `sms_status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `frontends`
--

CREATE TABLE `frontends` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `data_keys` varchar(40) NOT NULL,
  `data_values` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frontends`
--

INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `created_at`, `updated_at`) VALUES
(1, 'seo.data', '{\"seo_image\":\"1\",\"keywords\":[\"courier\",\"parcel\",\"cargo\",\"shipping\",\"shipment\",\"letter\"],\"description\":\"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit\",\"social_title\":\"CourierLab - Online Courier Service\",\"social_description\":\"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit ff\",\"image\":\"60eaae52f28dc1625992786.jpg\"}', '2020-07-04 23:42:52', '2021-07-12 12:13:39'),
(24, 'about.content', '{\"has_image\":\"1\",\"title\":\"Why We are The Best\",\"heading\":\"30+ Years Experiences in Courier Service\",\"sub_heading\":\"Doloribus debitis dolores amet, minus qui eaque itaque, doloremque at ipsa ab reiciendis assumenda et labore asperiores, cumque impedit! Corrupti, alias laboriosam!\",\"background_image\":\"60dc05976f7ce1625032087.png\"}', '2020-10-28 00:51:20', '2021-06-30 05:18:07'),
(25, 'blog.content', '{\"title\":\"Latest News\",\"heading\":\"Our Blog Posts\",\"sub_heading\":\"Non iste sint temporibus, facilis praesentium at eius excepturi veritatis natus maxime veniam eum impedit minus pariatur architecto necessitatibus dolorum? Minima, nihil?\"}', '2020-10-28 00:51:34', '2021-06-30 08:51:48'),
(26, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Praesentium at nobis unde quis aut quaerat autem libero in consequuntur recusandae provident\",\"description_nic\":\"<div class=\\\"post__header\\\" style=\\\"margin-bottom:45px;color:rgb(85,85,85);font-family:Nunito, sans-serif;\\\"><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Asperiores nisi voluptates enim numquam vel recusandae consequatur libero, laboriosam possimus hic officiis voluptatum reprehenderit placeat voluptatibus aspernatur tempore quisquam tempora eius incidunt dignissimos maxime praesentium veniam. Veniam, sapiente.<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Vitae optio minima nulla iusto, praesentium, natus exercitationem maiores qui temporibus consequatur, fuga repudiandae. Rem mollitia suscipit blanditiis, at porro recusandae vitae.<\\/p><\\/div><blockquote class=\\\"post__quote\\\" style=\\\"margin-bottom:30px;font-size:22px;line-height:1.5;font-family:Nunito, sans-serif;color:rgb(25,42,86);font-style:italic;text-align:center;padding:0px 30px;border-left:3px solid rgb(30,144,255);\\\">\\u201c Works together with striker consulting firms active in USA. Globally we work with more than 150 leading consulting firms and with a select number of partners. \\u201d<\\/blockquote><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Architecto quis nobis repudiandae porro perferendis quisquam, ut exercitationem quae aliquid eveniet. Recusandae officia alias sapiente ullam quae veniam optio exercitationem incidunt nisi totam reiciendis expedita harum vel debitis ad quam ut rem porro ratione voluptatem quod, laboriosam ducimus magni. Molestias, distinctio!<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Explicabo nobis dolorum, voluptates provident quasi harum optio nesciunt est accusantium eos soluta fugit illo vitae error numquam, sit ipsa quas nihil.<\\/p>\",\"blog_image\":\"60dc37d9a41691625044953.jpg\"}', '2020-10-28 00:57:19', '2021-06-30 08:52:33'),
(27, 'contact_us.content', '{\"title\":\"Start Conversation\",\"heading\":\"Get in Touch\",\"sub_heading\":\"Voluptatem, fugit, facilis iure eligendi doloremque nisi minima ipsam corrupti vero eaque quo aut voluptatibus! Necessitatibus minima\",\"has_image\":\"1\",\"contact_image\":\"60dc4387c9ab61625047943.png\"}', '2020-10-28 00:59:19', '2021-06-30 09:42:23'),
(28, 'counter.content', '{\"has_image\":\"1\",\"background_image\":\"60dc194ef07d61625037134.jpg\"}', '2020-10-28 01:04:02', '2021-06-30 06:42:15'),
(30, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Nobis unde quis aut quaerat autem libero in consequuntur recusandae provident Praesentium at\",\"description_nic\":\"<div class=\\\"post__header\\\" style=\\\"margin-bottom:45px;color:rgb(85,85,85);font-family:Nunito, sans-serif;\\\"><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Asperiores nisi voluptates enim numquam vel recusandae consequatur libero, laboriosam possimus hic officiis voluptatum reprehenderit placeat voluptatibus aspernatur tempore quisquam tempora eius incidunt dignissimos maxime praesentium veniam. Veniam, sapiente.<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Vitae optio minima nulla iusto, praesentium, natus exercitationem maiores qui temporibus consequatur, fuga repudiandae. Rem mollitia suscipit blanditiis, at porro recusandae vitae.<\\/p><\\/div><blockquote class=\\\"post__quote\\\" style=\\\"margin-bottom:30px;font-size:22px;line-height:1.5;font-family:Nunito, sans-serif;color:rgb(25,42,86);font-style:italic;text-align:center;padding:0px 30px;border-left:3px solid rgb(30,144,255);\\\">\\u201c Works together with striker consulting firms active in USA. Globally we work with more than 150 leading consulting firms and with a select number of partners. \\u201d<\\/blockquote><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Architecto quis nobis repudiandae porro perferendis quisquam, ut exercitationem quae aliquid eveniet. Recusandae officia alias sapiente ullam quae veniam optio exercitationem incidunt nisi totam reiciendis expedita harum vel debitis ad quam ut rem porro ratione voluptatem quod, laboriosam ducimus magni. Molestias, distinctio!<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Explicabo nobis dolorum, voluptates provident quasi harum optio nesciunt est accusantium eos soluta fugit illo vitae error numquam, sit ipsa quas nihil.<\\/p>\",\"blog_image\":\"60dc3808aaf1f1625045000.jpg\"}', '2020-10-31 00:39:05', '2021-06-30 08:53:20'),
(33, 'feature.content', '{\"heading\":\"asdf\",\"sub_heading\":\"asdf\"}', '2021-01-03 23:40:54', '2021-01-03 23:40:55'),
(36, 'service.content', '{\"title\":\"Services\",\"heading\":\"What We Serve\",\"sub_heading\":\"Non iste sint temporibus, facilis praesentium at eius excepturi veritatis natus maxime veniam eum impedit minus pariatur architecto necessitatibus dolorum? Minima, nihil?\",\"has_image\":\"1\",\"background_image\":\"60dc0b67968501625033575.jpg\"}', '2021-03-06 01:27:34', '2021-06-30 05:42:55'),
(39, 'banner.content', '{\"heading\":\"Latest News\",\"sub_heading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Esse voluptatum eaque earum quos quia? Id aspernatur ratione, voluptas nulla rerum laudantium neque ipsam eaque\"}', '2021-05-02 06:09:30', '2021-05-02 06:09:30'),
(41, 'cookie.data', '{\"link\":\"#\",\"description\":\"<font face=\\\"Exo, sans-serif\\\"><span style=\\\"font-size: 18px;\\\">We may use cookies or any other tracking technologies when you visit our website, including any other media form, mobile website, or mobile application related or connected to help customize the Site and improve your experience.<\\/span><\\/font><br>\",\"status\":1}', '2020-07-04 23:42:52', '2021-06-06 09:43:37'),
(42, 'banner.element', '{\"heading\":\"Welcome to Courierlab\",\"sub_heading\":\"We Provide Best Dispatch and Parcel Services\",\"first_button_name\":\"Get Quotes\",\"first_button_url\":\"#\",\"second_button_name\":\"Contact Us\",\"second_button_url\":\"#\",\"has_image\":[\"1\"],\"background_image\":\"60dc022a800bb1625031210.jpg\"}', '2021-06-30 05:03:30', '2021-07-12 12:17:41'),
(43, 'banner.element', '{\"heading\":\"Welcome to Courierlab\",\"sub_heading\":\"We Provide Best Dispatch and Parcel Services\",\"first_button_name\":\"Get Quotes\",\"first_button_url\":\"#\",\"second_button_name\":\"Contact Us\",\"second_button_url\":\"#\",\"has_image\":[\"1\"],\"background_image\":\"60dc0259041701625031257.jpg\"}', '2021-06-30 05:04:17', '2021-07-12 12:17:58'),
(44, 'banner.element', '{\"heading\":\"Welcome to Courierlab\",\"sub_heading\":\"We Provide Best Dispatch and Parcel Services\",\"first_button_name\":\"Get Quotes\",\"first_button_url\":\"#\",\"second_button_name\":\"Contact Us\",\"second_button_url\":\"#\",\"has_image\":[\"1\"],\"background_image\":\"60dc027ee2dcf1625031294.jpg\"}', '2021-06-30 05:04:54', '2021-07-12 12:18:09'),
(45, 'about.element', '{\"title\":\"Fast Delivery\",\"sub_title\":\"Ut recusandae non veniam obcaecati, sunt earum atque cumque, alias quae molestiae quo, ad debitis saepe.\",\"about_icon\":\"<i class=\\\"lab la-delicious\\\"><\\/i>\"}', '2021-06-30 05:19:18', '2021-06-30 05:19:18'),
(46, 'about.element', '{\"title\":\"Lowest Cost\",\"sub_title\":\"Ut recusandae non veniam obcaecati, sunt earum atque cumque, alias quae molestiae quo, ad debitis saepe.\",\"about_icon\":\"<i class=\\\"fas fa-money-check-alt\\\"><\\/i>\"}', '2021-06-30 05:19:56', '2021-06-30 05:19:56'),
(47, 'about.element', '{\"title\":\"Secured Services\",\"sub_title\":\"Ut recusandae non veniam obcaecati, sunt earum atque cumque, alias quae molestiae quo, ad debitis saepe.\",\"about_icon\":\"<i class=\\\"lab la-servicestack\\\"><\\/i>\"}', '2021-06-30 05:20:25', '2021-06-30 05:20:25'),
(48, 'feature.element', '{\"heading\":\"Apply Online\",\"sub_heading\":\"Assumenda fugiat neque molestias recusandae nesciunt, ipsam porro expedita impedit tenetur dolorum sint error est natus ex harum.\",\"feature_icon\":\"<i class=\\\"fas fa-file-invoice\\\"><\\/i>\"}', '2021-06-30 05:32:59', '2021-06-30 05:38:12'),
(49, 'feature.element', '{\"heading\":\"Submit Documents\",\"sub_heading\":\"Assumenda fugiat neque molestias recusandae nesciunt, ipsam porro expedita impedit tenetur dolorum sint error est natus ex harum.\",\"feature_icon\":\"<i class=\\\"las la-file-invoice\\\"><\\/i>\"}', '2021-06-30 05:34:04', '2021-06-30 05:34:04'),
(50, 'feature.element', '{\"heading\":\"Receive Goods\",\"sub_heading\":\"Assumenda fugiat neque molestias recusandae nesciunt, ipsam porro expedita impedit tenetur dolorum sint error est natus ex harum.\",\"feature_icon\":\"<i class=\\\"las la-file-invoice\\\"><\\/i>\"}', '2021-06-30 05:34:31', '2021-06-30 05:34:31'),
(51, 'service.element', '{\"has_image\":[\"1\"],\"title\":\"Standard Courier\",\"description\":\"Beatae rem sapiente dolorum cumque consequuntur quae quasi dignissimos, deleniti, quia voluptas aliquam nobis sit atque asperiores, debitis accusantium voluptatibus illum esse.\",\"image\":\"60dc0b9189b811625033617.png\"}', '2021-06-30 05:43:37', '2021-06-30 05:43:37'),
(52, 'service.element', '{\"has_image\":[\"1\"],\"title\":\"Express Courier\",\"description\":\"Beatae rem sapiente dolorum cumque consequuntur quae quasi dignissimos, deleniti, quia voluptas aliquam nobis sit atque asperiores, debitis accusantium voluptatibus illum esse.\",\"image\":\"60dc0ba72cb961625033639.png\"}', '2021-06-30 05:43:59', '2021-06-30 05:43:59'),
(53, 'service.element', '{\"has_image\":[\"1\"],\"title\":\"Pallet Courier\",\"description\":\"Beatae rem sapiente dolorum cumque consequuntur quae quasi dignissimos, deleniti, quia voluptas aliquam nobis sit atque asperiores, debitis accusantium voluptatibus illum esse.\",\"image\":\"60dc0bbcdde2e1625033660.png\"}', '2021-06-30 05:44:20', '2021-06-30 05:44:20'),
(54, 'service.element', '{\"has_image\":[\"1\"],\"title\":\"Over Night Courier\",\"description\":\"Beatae rem sapiente dolorum cumque consequuntur quae quasi dignissimos, deleniti, quia voluptas aliquam nobis sit atque asperiores, debitis accusantium voluptatibus illum esse.\",\"image\":\"60dc0bd48c4141625033684.png\"}', '2021-06-30 05:44:44', '2021-06-30 05:44:44'),
(55, 'service.element', '{\"has_image\":[\"1\"],\"title\":\"International Courier\",\"description\":\"Beatae rem sapiente dolorum cumque consequuntur quae quasi dignissimos, deleniti, quia voluptas aliquam nobis sit atque asperiores, debitis accusantium voluptatibus illum esse.\",\"image\":\"60dc0bead1b1d1625033706.png\"}', '2021-06-30 05:45:06', '2021-06-30 05:45:06'),
(56, 'service.element', '{\"has_image\":[\"1\"],\"title\":\"Warehousing\",\"description\":\"Beatae rem sapiente dolorum cumque consequuntur quae quasi dignissimos, deleniti, quia voluptas aliquam nobis sit atque asperiores, debitis accusantium voluptatibus illum esse.\",\"image\":\"60dc0bfb290351625033723.png\"}', '2021-06-30 05:45:23', '2021-06-30 05:45:23'),
(59, 'team.content', '{\"title\":\"Expert Team\",\"heading\":\"Our Expert Team\",\"sub_heading\":\"Non iste sint temporibus, facilis praesentium at eius excepturi veritatis natus maxime veniam eum impedit minus pariatur architecto necessitatibus dolorum? Minima, nihil?\"}', '2021-06-30 06:26:33', '2021-06-30 06:26:33'),
(60, 'team.element', '{\"has_image\":[\"1\"],\"name\":\"Liana Harris\",\"designation\":\"Sales Expert\",\"total_delivery\":\"2323\",\"member\":\"60dc15dfd99061625036255.png\"}', '2021-06-30 06:27:35', '2021-07-12 12:21:18'),
(61, 'team.element', '{\"has_image\":[\"1\"],\"name\":\"Dew Brisk\",\"designation\":\"Delivery Boy\",\"total_delivery\":\"323\",\"member\":\"60dc15fde1d321625036285.png\"}', '2021-06-30 06:28:05', '2021-07-12 12:21:32'),
(62, 'team.element', '{\"has_image\":[\"1\"],\"name\":\"Harry Hardson\",\"designation\":\"Delivery Boy\",\"total_delivery\":\"656\",\"member\":\"60dc1609e44f11625036297.png\"}', '2021-06-30 06:28:17', '2021-07-12 12:21:50'),
(63, 'team.element', '{\"has_image\":[\"1\"],\"name\":\"John Dew\",\"designation\":\"Delivery Boy\",\"total_delivery\":\"6543\",\"member\":\"60dc161d73df71625036317.png\"}', '2021-06-30 06:28:37', '2021-07-12 12:20:53'),
(64, 'counter.element', '{\"title\":\"Satisfied Client\",\"counter_digit\":\"323\",\"counter_icon\":\"<i class=\\\"las la-users\\\"><\\/i>\"}', '2021-06-30 06:37:28', '2021-07-06 13:56:41'),
(65, 'counter.element', '{\"title\":\"Total Branches\",\"counter_digit\":\"43\",\"counter_icon\":\"<i class=\\\"las la-store-alt\\\"><\\/i>\"}', '2021-06-30 06:37:50', '2021-07-12 12:29:00'),
(66, 'counter.element', '{\"title\":\"Total Staffs\",\"counter_digit\":\"434\",\"counter_icon\":\"<i class=\\\"las la-user-friends\\\"><\\/i>\"}', '2021-06-30 06:38:15', '2021-07-12 12:29:15'),
(67, 'counter.element', '{\"title\":\"Total Member\",\"counter_digit\":\"34534\",\"counter_icon\":\"<i class=\\\"las la-people-carry\\\"><\\/i>\"}', '2021-06-30 06:38:58', '2021-07-12 12:29:41'),
(68, 'faq.content', '{\"title\":\"What we offer\",\"heading\":\"Explore Our Services and Solutions\",\"sub_heading\":\"Voluptatem, fugit, facilis iure eligendi doloremque nisi minima ipsam corrupti vero eaque quo aut voluptatibus! Necessitatibus minima\",\"has_image\":\"1\",\"faq_image\":\"60dc2f0fbef201625042703.png\"}', '2021-06-30 08:15:03', '2021-07-12 12:19:30'),
(69, 'faq.element', '{\"question\":\"Explore Our Services and Solutions\",\"answer\":\"Voluptatem, fugit, facilis iure eligendi doloremque nisi minima ipsam corrupti vero eaque quo aut voluptatibus! Necessitatibus minima\"}', '2021-06-30 08:15:56', '2021-07-12 12:19:40'),
(70, 'faq.element', '{\"question\":\"Cum molestias sequi dignissimos nemo?\",\"answer\":\"Quibusdam reprehenderit blanditiis adipisci facilis fugit, harum ab iste temporibus eveniet dolore porro ex excepturi consequatur.\"}', '2021-06-30 08:16:17', '2021-06-30 08:16:17'),
(71, 'faq.element', '{\"question\":\"Cum molestias sequi dignissimos nemo?\",\"answer\":\"Alias ducimus autem, laudantium rerum quas libero dolorem? Inventore, corrupti, nihil iste distinctio asperiores\"}', '2021-06-30 08:16:38', '2021-06-30 08:16:38'),
(72, 'faq.element', '{\"question\":\"harum ab iste temporibus eveniet dolore porro\",\"answer\":\"Alias ducimus autem, laudantium rerum quas libero dolorem? Inventore, corrupti, nihil iste distinctio asperiores\"}', '2021-06-30 08:17:10', '2021-06-30 08:17:10'),
(73, 'client.content', '{\"has_image\":\"1\",\"background_image\":\"60dc32431a1511625043523.jpg\"}', '2021-06-30 08:28:43', '2021-06-30 08:28:43'),
(74, 'client.element', '{\"name\":\"Abu Desnan\",\"designation\":\"Backend Developer\",\"rating\":\"5\",\"testimonial\":\"Quas, illo. A commodi officia, eos, laborum expedita aliquid culpa ipsa neque dignissimos tempore id sed iste odit optio natus deleniti assumenda qui labore totam nam? Impedit, doloribus odit. Quo?\",\"has_image\":\"1\",\"client_image\":\"60dc33a2132eb1625043874.jpg\"}', '2021-06-30 08:29:59', '2021-06-30 08:34:34'),
(75, 'client.element', '{\"name\":\"Md Jisan\",\"designation\":\"Delivery Boy\",\"rating\":\"4\",\"testimonial\":\"Quas, illo. A commodi officia, eos, laborum expedita aliquid culpa ipsa neque dignissimos tempore id sed iste odit optio natus deleniti assumenda qui labore totam nam? Impedit, doloribus odit. Quo?\",\"has_image\":\"1\",\"client_image\":\"60dc33a8b65141625043880.jpg\"}', '2021-06-30 08:30:55', '2021-06-30 08:34:40'),
(76, 'client.element', '{\"name\":\"Md Demo Sarker\",\"designation\":\"Courier Customer\",\"rating\":\"3\",\"testimonial\":\"Quas, illo. A commodi officia, eos, laborum expedita aliquid culpa ipsa neque dignissimos tempore id sed iste odit optio natus deleniti assumenda qui labore totam nam? Impedit, doloribus odit. Quo?\",\"has_image\":\"1\",\"client_image\":\"60dc33aeb2ced1625043886.jpg\"}', '2021-06-30 08:31:22', '2021-07-12 15:22:42'),
(77, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Recusandae provident praesentium at nobis unde quis aut quaerat autem libero in consequuntur\",\"description_nic\":\"<div class=\\\"post__header\\\" style=\\\"margin-bottom:45px;color:rgb(85,85,85);font-family:Nunito, sans-serif;\\\"><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Asperiores nisi voluptates enim numquam vel recusandae consequatur libero, laboriosam possimus hic officiis voluptatum reprehenderit placeat voluptatibus aspernatur tempore quisquam tempora eius incidunt dignissimos maxime praesentium veniam. Veniam, sapiente.<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Vitae optio minima nulla iusto, praesentium, natus exercitationem maiores qui temporibus consequatur, fuga repudiandae. Rem mollitia suscipit blanditiis, at porro recusandae vitae.<\\/p><\\/div><blockquote class=\\\"post__quote\\\" style=\\\"margin-bottom:30px;font-size:22px;line-height:1.5;font-family:Nunito, sans-serif;color:rgb(25,42,86);font-style:italic;text-align:center;padding:0px 30px;border-left:3px solid rgb(30,144,255);\\\">\\u201c Works together with striker consulting firms active in USA. Globally we work with more than 150 leading consulting firms and with a select number of partners. \\u201d<\\/blockquote><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Architecto quis nobis repudiandae porro perferendis quisquam, ut exercitationem quae aliquid eveniet. Recusandae officia alias sapiente ullam quae veniam optio exercitationem incidunt nisi totam reiciendis expedita harum vel debitis ad quam ut rem porro ratione voluptatem quod, laboriosam ducimus magni. Molestias, distinctio!<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Explicabo nobis dolorum, voluptates provident quasi harum optio nesciunt est accusantium eos soluta fugit illo vitae error numquam, sit ipsa quas nihil.<\\/p>\",\"blog_image\":\"60dc38433c7ea1625045059.jpg\"}', '2021-06-30 08:54:19', '2021-06-30 08:54:19'),
(78, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Unde quis aut quaerat autem libero in consequuntur recusandae provident raesentium at nobis\",\"description_nic\":\"<div class=\\\"post__header\\\" style=\\\"margin-bottom:45px;color:rgb(85,85,85);font-family:Nunito, sans-serif;\\\"><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Asperiores nisi voluptates enim numquam vel recusandae consequatur libero, laboriosam possimus hic officiis voluptatum reprehenderit placeat voluptatibus aspernatur tempore quisquam tempora eius incidunt dignissimos maxime praesentium veniam. Veniam, sapiente.<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Vitae optio minima nulla iusto, praesentium, natus exercitationem maiores qui temporibus consequatur, fuga repudiandae. Rem mollitia suscipit blanditiis, at porro recusandae vitae.<\\/p><\\/div><blockquote class=\\\"post__quote\\\" style=\\\"margin-bottom:30px;font-size:22px;line-height:1.5;font-family:Nunito, sans-serif;color:rgb(25,42,86);font-style:italic;text-align:center;padding:0px 30px;border-left:3px solid rgb(30,144,255);\\\">\\u201c Works together with striker consulting firms active in USA. Globally we work with more than 150 leading consulting firms and with a select number of partners. \\u201d<\\/blockquote><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Architecto quis nobis repudiandae porro perferendis quisquam, ut exercitationem quae aliquid eveniet. Recusandae officia alias sapiente ullam quae veniam optio exercitationem incidunt nisi totam reiciendis expedita harum vel debitis ad quam ut rem porro ratione voluptatem quod, laboriosam ducimus magni. Molestias, distinctio!<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Explicabo nobis dolorum, voluptates provident quasi harum optio nesciunt est accusantium eos soluta fugit illo vitae error numquam, sit ipsa quas nihil.<\\/p>\",\"blog_image\":\"60dc3870e3f3e1625045104.jpg\"}', '2021-06-30 08:55:04', '2021-06-30 08:55:04'),
(79, 'branch.content', '{\"title\":\"Branches\",\"heading\":\"Our Top Branches\",\"sub_heading\":\"Voluptatem, fugit, facilis iure eligendi doloremque nisi minima ipsam corrupti vero eaque quo aut voluptatibus! Necessitatibus minima\",\"has_image\":\"1\",\"background_image\":\"60dc3bf0113751625046000.png\"}', '2021-06-30 09:10:00', '2021-07-12 12:18:44'),
(80, 'partner.element', '{\"has_image\":\"1\",\"partner_image\":\"60ebfc21afc541626078241.png\"}', '2021-06-30 09:15:31', '2021-07-12 12:24:01'),
(81, 'partner.element', '{\"has_image\":\"1\",\"partner_image\":\"60ebfc2a7933f1626078250.png\"}', '2021-06-30 09:15:37', '2021-07-12 12:24:10'),
(82, 'partner.element', '{\"has_image\":\"1\",\"partner_image\":\"60ebfc349012f1626078260.png\"}', '2021-06-30 09:15:41', '2021-07-12 12:24:20'),
(83, 'partner.element', '{\"has_image\":\"1\",\"partner_image\":\"60ebfc3e20a081626078270.png\"}', '2021-06-30 09:15:48', '2021-07-12 12:24:30'),
(84, 'partner.element', '{\"has_image\":\"1\",\"partner_image\":\"60ebfc46c2e221626078278.png\"}', '2021-06-30 09:15:53', '2021-07-12 12:24:38'),
(85, 'partner.element', '{\"has_image\":\"1\",\"partner_image\":\"60ebfc5a1e6fb1626078298.png\"}', '2021-06-30 09:15:57', '2021-07-12 12:24:58'),
(86, 'partner.element', '{\"has_image\":\"1\",\"partner_image\":\"60ebfc6b90c3d1626078315.png\"}', '2021-06-30 09:16:03', '2021-07-12 12:25:15'),
(87, 'breadcrumb.content', '{\"has_image\":\"1\",\"background_image\":\"60dc41f1877eb1625047537.jpg\"}', '2021-06-30 09:34:46', '2021-06-30 09:35:37'),
(88, 'social_icon.element', '{\"social_icon\":\"<i class=\\\"lab la-facebook-f\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\\/\"}', '2021-06-30 09:48:27', '2021-06-30 09:48:27'),
(89, 'social_icon.element', '{\"social_icon\":\"<i class=\\\"fab fa-twitter\\\"><\\/i>\",\"url\":\"https:\\/\\/www.twitter.com\\/\"}', '2021-06-30 09:48:38', '2021-06-30 09:48:38'),
(90, 'social_icon.element', '{\"social_icon\":\"<i class=\\\"lab la-linkedin-in\\\"><\\/i>\",\"url\":\"https:\\/\\/www.linkedin.com\\/\"}', '2021-06-30 09:49:11', '2021-06-30 09:49:11'),
(91, 'social_icon.element', '{\"social_icon\":\"<i class=\\\"lab la-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}', '2021-06-30 09:49:27', '2021-06-30 09:49:27'),
(92, 'footer.content', '{\"heading\":\"Rem accusamus ducimus reiciendis corporis deserunt ipsum, fuga aut velit. Corporis vero atque sed. Dicta atque rerum, molestiae quidem sunt.\",\"address\":\"1205 Tipu Sultan Road Motijhil\",\"mobile\":\"123456789\",\"fax\":\"5235123456789\",\"email\":\"demo@gmail.com\",\"has_image\":\"1\",\"background_image\":\"60dc489dae42e1625049245.jpg\"}', '2021-06-30 10:04:05', '2021-07-12 12:19:53'),
(93, 'order_tracking.content', '{\"title\":\"Tracking Order\",\"heading\":\"Your Parcel Status\"}', '2021-07-06 08:21:39', '2021-07-06 08:21:39'),
(94, 'footer.element', '{\"menu\":\"Privacy and Policy\",\"description\":\"<div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', '2021-07-06 08:59:07', '2021-07-12 12:20:07'),
(95, 'footer.element', '{\"menu\":\"Refund Policy\",\"description\":\"<div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"color:rgb(111,111,111);font-family:Nunito, sans-serif;margin-bottom:3rem;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;font-family:Exo, sans-serif;color:rgb(54,54,54);\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', '2021-07-06 09:00:08', '2021-07-06 09:00:08'),
(96, 'footer.element', '{\"menu\":\"Terms of Service\",\"description\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">What information do we collect?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We gather data from you when you register on our site, submit a request, buy any services, react to an overview, or round out a structure. At the point when requesting any assistance or enrolling on our site, as suitable, you might be approached to enter your: name, email address, or telephone number. You may, nonetheless, visit our site anonymously.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">How do we protect your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">All provided delicate\\/credit data is sent through Stripe.<br \\/>After an exchange, your private data (credit cards, social security numbers, financials, and so on) won\'t be put away on our workers.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Do we disclose any information to outside parties?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t sell, exchange, or in any case move to outside gatherings by and by recognizable data. This does exclude confided in outsiders who help us in working our site, leading our business, or adjusting you, since those gatherings consent to keep this data private. We may likewise deliver your data when we accept discharge is suitable to follow the law, implement our site strategies, or ensure our own or others\' rights, property, or wellbeing.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Children\'s Online Privacy Protection Act Compliance<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We are consistent with the prerequisites of COPPA (Children\'s Online Privacy Protection Act), we don\'t gather any data from anybody under 13 years old. Our site, items, and administrations are completely coordinated to individuals who are in any event 13 years of age or more established.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">Changes to our Privacy Policy<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">If we decide to change our privacy policy, we will post those changes on this page.<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">How long we retain your information?<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">At the point when you register for our site, we cycle and keep your information we have about you however long you don\'t erase the record or withdraw yourself (subject to laws and guidelines).<\\/p><\\/div><div class=\\\"mb-5\\\" style=\\\"margin-bottom:3rem;color:rgb(111,111,111);font-family:Nunito, sans-serif;\\\"><h3 class=\\\"mb-3\\\" style=\\\"font-weight:600;line-height:1.3;font-size:24px;color:rgb(54,54,54);font-family:Exo, sans-serif;\\\">What we don\\u2019t do with your data<\\/h3><p class=\\\"font-18\\\" style=\\\"margin-right:0px;margin-left:0px;font-size:18px;\\\">We don\'t and will never share, unveil, sell, or in any case give your information to different organizations for the promoting of their items or administrations.<\\/p><\\/div>\"}', '2021-07-06 09:02:49', '2021-07-06 09:02:49'),
(97, 'client.element', '{\"name\":\"Alex Branda\",\"designation\":\"Merchant\",\"rating\":\"5\",\"testimonial\":\"Quas, illo. A commodi officia, eos, laborum expedita aliquid culpa ipsa neque dignissimos tempore id sed iste odit optio natus deleniti assumenda qui labore totam nam? Impedit, doloribus odit. Quo?\",\"has_image\":\"1\",\"client_image\":\"60ec269d8fe501626089117.png\"}', '2021-07-12 15:25:17', '2021-07-12 15:25:17'),
(98, 'client.element', '{\"name\":\"Carlos Rabanda\",\"designation\":\"Business Owner\",\"rating\":\"5\",\"testimonial\":\"Quas, illo. A commodi officia, eos, laborum expedita aliquid culpa ipsa neque dignissimos tempore id sed iste odit optio natus deleniti assumenda qui labore totam nam? Impedit, doloribus odit. Quo?\",\"has_image\":\"1\",\"client_image\":\"60ec26eb55ab11626089195.jpg\"}', '2021-07-12 15:26:35', '2021-07-12 15:26:35'),
(99, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Praesentium at nobis unde quis aut quaerat autem libero in consequuntur recusandae provident\",\"description_nic\":\"<div class=\\\"post__header\\\" style=\\\"margin-bottom:45px;color:rgb(85,85,85);font-family:Nunito, sans-serif;\\\"><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Asperiores nisi voluptates enim numquam vel recusandae consequatur libero, laboriosam possimus hic officiis voluptatum reprehenderit placeat voluptatibus aspernatur tempore quisquam tempora eius incidunt dignissimos maxime praesentium veniam. Veniam, sapiente.<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Vitae optio minima nulla iusto, praesentium, natus exercitationem maiores qui temporibus consequatur, fuga repudiandae. Rem mollitia suscipit blanditiis, at porro recusandae vitae.<\\/p><\\/div><blockquote class=\\\"post__quote\\\" style=\\\"margin-bottom:30px;font-size:22px;line-height:1.5;font-family:Nunito, sans-serif;color:rgb(25,42,86);font-style:italic;text-align:center;padding:0px 30px;border-left:3px solid rgb(30,144,255);\\\">\\u201c Works together with striker consulting firms active in USA. Globally we work with more than 150 leading consulting firms and with a select number of partners. \\u201d<\\/blockquote><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Architecto quis nobis repudiandae porro perferendis quisquam, ut exercitationem quae aliquid eveniet. Recusandae officia alias sapiente ullam quae veniam optio exercitationem incidunt nisi totam reiciendis expedita harum vel debitis ad quam ut rem porro ratione voluptatem quod, laboriosam ducimus magni. Molestias, distinctio!<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Explicabo nobis dolorum, voluptates provident quasi harum optio nesciunt est accusantium eos soluta fugit illo vitae error numquam, sit ipsa quas nihil.<\\/p>\",\"blog_image\":\"60dc37d9a41691625044953.jpg\"}', '2020-10-28 00:57:19', '2021-06-30 08:52:33'),
(100, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Nobis unde quis aut quaerat autem libero in consequuntur recusandae provident Praesentium at\",\"description_nic\":\"<div class=\\\"post__header\\\" style=\\\"margin-bottom:45px;color:rgb(85,85,85);font-family:Nunito, sans-serif;\\\"><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Asperiores nisi voluptates enim numquam vel recusandae consequatur libero, laboriosam possimus hic officiis voluptatum reprehenderit placeat voluptatibus aspernatur tempore quisquam tempora eius incidunt dignissimos maxime praesentium veniam. Veniam, sapiente.<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;\\\">Vitae optio minima nulla iusto, praesentium, natus exercitationem maiores qui temporibus consequatur, fuga repudiandae. Rem mollitia suscipit blanditiis, at porro recusandae vitae.<\\/p><\\/div><blockquote class=\\\"post__quote\\\" style=\\\"margin-bottom:30px;font-size:22px;line-height:1.5;font-family:Nunito, sans-serif;color:rgb(25,42,86);font-style:italic;text-align:center;padding:0px 30px;border-left:3px solid rgb(30,144,255);\\\">\\u201c Works together with striker consulting firms active in USA. Globally we work with more than 150 leading consulting firms and with a select number of partners. \\u201d<\\/blockquote><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Architecto quis nobis repudiandae porro perferendis quisquam, ut exercitationem quae aliquid eveniet. Recusandae officia alias sapiente ullam quae veniam optio exercitationem incidunt nisi totam reiciendis expedita harum vel debitis ad quam ut rem porro ratione voluptatem quod, laboriosam ducimus magni. Molestias, distinctio!<\\/p><p style=\\\"margin-top:-8px;margin-bottom:30px;color:rgb(85,85,85);font-family:Nunito, sans-serif;font-size:16px;\\\">Explicabo nobis dolorum, voluptates provident quasi harum optio nesciunt est accusantium eos soluta fugit illo vitae error numquam, sit ipsa quas nihil.<\\/p>\",\"blog_image\":\"60dc3808aaf1f1625045000.jpg\"}', '2020-10-31 00:39:05', '2021-06-30 08:53:20');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sitename` varchar(40) DEFAULT NULL,
  `cur_text` varchar(40) DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) DEFAULT NULL,
  `email_template` text DEFAULT NULL,
  `sms_api` varchar(255) DEFAULT NULL,
  `base_color` varchar(40) DEFAULT NULL,
  `mail_config` text DEFAULT NULL COMMENT 'email configuration',
  `sms_config` text DEFAULT NULL,
  `en` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'email notification, 0 - dont send, 1 - send',
  `sn` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'sms notification, 0 - dont send, 1 - send',
  `force_ssl` tinyint(1) NOT NULL DEFAULT 0,
  `active_template` varchar(40) DEFAULT NULL,
  `sys_version` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `sitename`, `cur_text`, `cur_sym`, `email_from`, `email_template`, `sms_api`, `base_color`, `mail_config`, `sms_config`, `en`, `sn`, `force_ssl`, `active_template`, `sys_version`, `created_at`, `updated_at`) VALUES
(1, 'MagicPost', 'USD', '$', 'trung1bmksnb@gmail.com', '<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n  <!--[if !mso]><!-->\r\n  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n  <!--<![endif]-->\r\n  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n  <title></title>\r\n  <style type=\"text/css\">\r\n.ReadMsgBody { width: 100%; background-color: #ffffff; }\r\n.ExternalClass { width: 100%; background-color: #ffffff; }\r\n.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }\r\nhtml { width: 100%; }\r\nbody { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; margin: 0; padding: 0; }\r\ntable { border-spacing: 0; table-layout: fixed; margin: 0 auto;border-collapse: collapse; }\r\ntable table table { table-layout: auto; }\r\n.yshortcuts a { border-bottom: none !important; }\r\nimg:hover { opacity: 0.9 !important; }\r\na { color: #0087ff; text-decoration: none; }\r\n.textbutton a { font-family: \'open sans\', arial, sans-serif !important;}\r\n.btn-link a { color:#FFFFFF !important;}\r\n\r\n@media only screen and (max-width: 480px) {\r\nbody { width: auto !important; }\r\n*[class=\"table-inner\"] { width: 90% !important; text-align: center !important; }\r\n*[class=\"table-full\"] { width: 100% !important; text-align: center !important; }\r\n/* image */\r\nimg[class=\"img1\"] { width: 100% !important; height: auto !important; }\r\n}\r\n</style>\r\n\r\n\r\n\r\n  <table bgcolor=\"#414a51\" width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n    <tbody><tr>\r\n      <td height=\"50\"></td>\r\n    </tr>\r\n    <tr>\r\n      <td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n        <table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n          <tbody><tr>\r\n            <td align=\"center\" width=\"600\">\r\n              <!--header-->\r\n              <table class=\"table-inner\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                <tbody><tr>\r\n                  <td bgcolor=\"#0087ff\" style=\"border-top-left-radius:6px; border-top-right-radius:6px;text-align:center;vertical-align:top;font-size:0;\" align=\"center\">\r\n                    <table width=\"90%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                      <tbody><tr>\r\n                        <td height=\"20\"></td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td align=\"center\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#FFFFFF; font-size:16px; font-weight: bold;\">This is a System Generated Email</td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td height=\"20\"></td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n              </tbody></table>\r\n              <!--end header-->\r\n              <table class=\"table-inner\" width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n                <tbody><tr>\r\n                  <td bgcolor=\"#FFFFFF\" align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n                    <table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n                      <tbody><tr>\r\n                        <td height=\"35\"></td>\r\n                      </tr>\r\n                      <!--logo-->\r\n                      <tr>\r\n                        <td align=\"center\" style=\"vertical-align:top;font-size:0;\">\r\n                          <a href=\"#\">\r\n                            <img style=\"display:block; line-height:0px; font-size:0px; border:0px;\" src=\"https://i.imgur.com/Z1qtvtV.png\" alt=\"img\">\r\n                          </a>\r\n                        </td>\r\n                      </tr>\r\n                      <!--end logo-->\r\n                      <tr>\r\n                        <td height=\"40\"></td>\r\n                      </tr>\r\n                      <!--headline-->\r\n                      <tr>\r\n                        <td align=\"center\" style=\"font-family: \'Open Sans\', Arial, sans-serif; font-size: 22px;color:#414a51;font-weight: bold;\">Hello {{fullname}} ({{username}})</td>\r\n                      </tr>\r\n                      <!--end headline-->\r\n                      <tr>\r\n                        <td align=\"center\" style=\"text-align:center;vertical-align:top;font-size:0;\">\r\n                          <table width=\"40\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\r\n                            <tbody><tr>\r\n                              <td height=\"20\" style=\" border-bottom:3px solid #0087ff;\"></td>\r\n                            </tr>\r\n                          </tbody></table>\r\n                        </td>\r\n                      </tr>\r\n                      <tr>\r\n                        <td height=\"20\"></td>\r\n                      </tr>\r\n                      <!--content-->\r\n                      <tr>\r\n                        <td align=\"left\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#7f8c8d; font-size:16px; line-height: 28px;\">{{message}}</td>\r\n                      </tr>\r\n                      <!--end content-->\r\n                      <tr>\r\n                        <td height=\"40\"></td>\r\n                      </tr>\r\n              \r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n                <tr>\r\n                  <td height=\"45\" align=\"center\" bgcolor=\"#f4f4f4\" style=\"border-bottom-left-radius:6px;border-bottom-right-radius:6px;\">\r\n                    <table align=\"center\" width=\"90%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n                      <tbody><tr>\r\n                        <td height=\"10\"></td>\r\n                      </tr>\r\n                      <!--preference-->\r\n                      <tr>\r\n                        <td class=\"preference-link\" align=\"center\" style=\"font-family: \'Open sans\', Arial, sans-serif; color:#95a5a6; font-size:14px;\">\r\n                          © 2023 <a href=\"#\">MagicPost</a> . All Rights Reserved. \r\n                        </td>\r\n                      </tr>\r\n                      <!--end preference-->\r\n                      <tr>\r\n                        <td height=\"10\"></td>\r\n                      </tr>\r\n                    </tbody></table>\r\n                  </td>\r\n                </tr>\r\n              </tbody></table>\r\n            </td>\r\n          </tr>\r\n        </tbody></table>\r\n      </td>\r\n    </tr>\r\n    <tr>\r\n      <td height=\"60\"></td>\r\n    </tr>\r\n  </tbody></table>', 'hi {{name}}, {{message}}', 'F37229', '{\"name\":\"php\"}', '{\"clickatell_api_key\":\"----------------------------\",\"infobip_username\":\"--------------\",\"infobip_password\":\"----------------------\",\"message_bird_api_key\":\"-------------------\",\"account_sid\":\"AC67afdacf2dacff5f163134883db92c24\",\"auth_token\":\"77726b242830fb28f52fb08c648dd7a6\",\"from\":\"+17739011523\",\"apiv2_key\":\"dfsfgdfgh\",\"name\":\"clickatell\"}', 1, 0, 0, 'basic', NULL, NULL, '2023-12-11 02:05:44');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL,
  `code` varchar(40) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `text_align` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: left to right text align, 1: right to left text align',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: not default language, 1: default language',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `icon`, `text_align`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', '5f15968db08911595250317.png', 0, 1, '2020-07-06 03:47:55', '2023-12-11 01:43:34');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `slug` varchar(40) DEFAULT NULL,
  `tempname` varchar(40) DEFAULT NULL COMMENT 'template name',
  `secs` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `tempname`, `secs`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'HOME', 'home', 'templates.basic.', '[\"feature\",\"about\",\"service\",\"team\",\"counter\",\"faq\",\"client\",\"blog\",\"branch\",\"partner\"]', 1, '2020-07-11 06:23:58', '2021-07-03 04:43:56'),
(2, 'About', 'about-us', 'templates.basic.', '[\"about\",\"branch\",\"team\"]', 0, '2020-07-11 06:35:35', '2021-07-07 05:43:27'),
(4, 'Team', 'team', 'templates.basic.', '[\"feature\",\"team\"]', 0, '2020-10-22 01:14:43', '2021-07-07 05:43:57'),
(5, 'Service', 'service', 'templates.basic.', '[\"faq\",\"service\",\"partner\"]', 0, '2020-10-22 01:14:53', '2021-07-07 05:45:17'),
(13, 'Blog', 'blog', 'templates.basic.', NULL, 1, '2021-07-06 08:43:50', '2021-07-06 08:43:50'),
(14, 'Contact', 'contact', 'templates.basic.', NULL, 1, '2021-07-06 08:44:28', '2021-07-06 08:44:28'),
(0, 'HOME', 'home', 'templates.frontend.', NULL, 0, '2023-12-09 08:40:19', '2023-12-09 08:40:19'),
(0, 'HOME', 'home', 'frontend.', NULL, 0, '2023-12-09 08:42:41', '2023-12-09 08:42:41');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(40) NOT NULL,
  `token` varchar(40) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('trung@gmail.com', '410813', '2023-12-11 11:56:57'),
('magicpost@gmail.com', '798551', '2023-12-12 11:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_attachments`
--

CREATE TABLE `support_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `support_message_id` int(10) UNSIGNED NOT NULL,
  `attachment` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supportticket_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `message` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) DEFAULT 0,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `ticket` varchar(40) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` int(11) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `price` decimal(28,8) NOT NULL DEFAULT 0.00000000,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Active : 1, Inactive: 0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`id`, `unit_id`, `name`, `price`, `status`, `created_at`, `updated_at`) VALUES
(2, 2, 'Hoa Quả', 1.00000000, 1, '2023-12-22 12:39:40', '2023-12-22 12:39:40');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Active : 1, Inactive : 0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(2, 'KG', 1, '2023-12-22 12:39:25', '2023-12-22 12:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `firstname` varchar(40) DEFAULT NULL,
  `lastname` varchar(40) DEFAULT NULL,
  `username` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `mobile` varchar(40) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_type` varchar(40) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: banned, 1: active',
  `ver_code` varchar(40) DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `warehouse_id`, `branch_id`, `firstname`, `lastname`, `username`, `email`, `mobile`, `password`, `image`, `user_type`, `status`, `ver_code`, `ver_code_send_at`, `ts`, `tv`, `tsc`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Super', 'Admin', 'admin', 'magicpost@gmail.com', '0355287940', '$2y$10$2qcOUKrDIUqyyCklvHp7IO8fGNcJ1gAXtxouTn1isZPHu6H8CfHPq', '60ebfa3cabf171626077756.jpg', 'admin', 1, NULL, NULL, 0, 1, NULL, NULL, NULL, NULL),
(13, NULL, 6, 'qlcn', 'Nguyễn Tử', 'caugiay_mbr', 'caugiay_mbr@gmail.com', '0123456787', '$2y$10$X9.5pDq210.0KYTxSc3NTuQ8DZbXJBFs3gcZFT1xpmsGmORsmmpB2', NULL, 'manager', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-22 10:19:45', '2023-12-22 10:19:45'),
(14, NULL, 6, 'nvcn', 'Nguyễn Tử', 'caugiay_sbr', 'caugiay_sbr@gmail.com', '0123456786', '$2y$10$ZMkgSkHk8BFDGlVkyP/vQu6glctniUdX96EXamhRDGPE75qeszke6', NULL, 'staff', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-22 10:21:55', '2023-12-22 10:21:55'),
(15, NULL, 6, 'gh', 'Nguyễn Tử', 'caugiay_dm', 'caugiay_dm@gmail.com', '0123456785', '$2y$10$zUVq2nizErAPisA5zHJEJeAMhVhsXRlkTq6zjoS4PvACm6vAXIVly', NULL, 'delivery_man', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-22 10:36:41', '2023-12-22 10:36:41'),
(16, 3, NULL, 'qlk', 'Nguyễn Tử', 'caugiay_wh', 'caugiay_wh@gmail.com', '0123456782', '$2y$10$lLZKABP8M15sGRhYcKdo2eM5tpBmaYrEIcocoaWml6vw2MDzSzIhW', NULL, 'manager_warehouse', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-23 06:20:32', '2023-12-23 06:20:32'),
(17, 3, NULL, 'nvk', 'Nguyễn Tử', 'caugiay_swh', 'caugiay_swh@gmail.com', '0123456781', '$2y$10$wVCugimW2iM2kAu/Tc14DuBzMDaJ4Lm9jyrHF02alRqPPLuNwdURm', NULL, 'staff_warehouse', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-23 08:55:50', '2023-12-23 08:55:50'),
(18, 4, NULL, 'qlk', 'Nguyễn Ngọc', 'mydinh_wh', 'mydinh_wh@gmail.com', '0123456798', '$2y$10$Ndm79KzzinnX9LUv1bJEvePZUxfF9RG867sj4MDGPU4ars3BRcNwC', NULL, 'manager_warehouse', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-24 09:02:08', '2023-12-24 09:02:08'),
(19, 4, NULL, 'nvk', 'Nguyễn Ngọc', 'mydinh_swh', 'mydinh_swh@gmail.com', '01234567890', '$2y$10$iE/ixxPTkYyEHsRni1YJzuAQei7IQW4vAkXkwcPf/i/NinNlsqB7e', NULL, 'staff_warehouse', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-24 09:03:02', '2023-12-24 09:03:02'),
(20, NULL, 7, 'qlcn', 'Nguyễn Ngọc', 'mydinh_mbr', 'mydinh_mbr@gmail.com', '01234567879', '$2y$10$p7L04fqRPia1U8oseJNt2.37dUejg.A2K18yKja4PshvuyOuBsjaa', NULL, 'manager', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-24 09:31:21', '2023-12-24 09:32:18'),
(21, NULL, 7, 'nvcn', 'Nguyễn Ngọc', 'mydinh_sbr', 'mydinh_sbr@gmail.com', '01234567868', '$2y$10$5dubZ33ZGcMR6KmV9m.EY.s0lU/44M9N.rjg0vaqHbWIER8Wmd.mm', NULL, 'staff', 1, NULL, NULL, 0, 1, NULL, NULL, '2023-12-24 09:33:01', '2023-12-24 09:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_ip` varchar(40) DEFAULT NULL,
  `city` varchar(40) DEFAULT NULL,
  `country` varchar(40) DEFAULT NULL,
  `country_code` varchar(40) DEFAULT NULL,
  `longitude` varchar(40) DEFAULT NULL,
  `latitude` varchar(40) DEFAULT NULL,
  `browser` varchar(40) DEFAULT NULL,
  `os` varchar(40) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_logins`
--

INSERT INTO `user_logins` (`id`, `user_id`, `user_ip`, `city`, `country`, `country_code`, `longitude`, `latitude`, `browser`, `os`, `created_at`, `updated_at`) VALUES
(61, 13, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-22 10:20:27', '2023-12-22 10:20:27'),
(62, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-22 10:39:05', '2023-12-22 10:39:05'),
(63, 16, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-23 08:46:44', '2023-12-23 08:46:44'),
(64, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-23 08:56:06', '2023-12-23 08:56:06'),
(65, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-23 09:09:04', '2023-12-23 09:09:04'),
(66, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-23 09:14:08', '2023-12-23 09:14:08'),
(67, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-23 09:25:38', '2023-12-23 09:25:38'),
(68, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-23 09:46:53', '2023-12-23 09:46:53'),
(69, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-23 11:40:16', '2023-12-23 11:40:16'),
(70, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 06:11:19', '2023-12-24 06:11:19'),
(71, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 07:15:12', '2023-12-24 07:15:12'),
(72, 16, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 08:58:20', '2023-12-24 08:58:20'),
(73, 18, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 09:02:19', '2023-12-24 09:02:19'),
(74, 19, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 09:03:11', '2023-12-24 09:03:11'),
(75, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 09:03:26', '2023-12-24 09:03:26'),
(76, 19, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 09:08:45', '2023-12-24 09:08:45'),
(77, 20, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 09:32:25', '2023-12-24 09:32:25'),
(78, 19, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 09:34:14', '2023-12-24 09:34:14'),
(79, 21, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 09:35:18', '2023-12-24 09:35:18'),
(80, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 10:05:39', '2023-12-24 10:05:39'),
(81, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 10:06:13', '2023-12-24 10:06:13'),
(82, 19, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 10:06:49', '2023-12-24 10:06:49'),
(83, 21, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 10:07:36', '2023-12-24 10:07:36'),
(84, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 10:21:12', '2023-12-24 10:21:12'),
(85, 13, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 11:18:12', '2023-12-24 11:18:12'),
(86, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 11:53:23', '2023-12-24 11:53:23'),
(87, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 12:10:21', '2023-12-24 12:10:21'),
(88, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-24 12:10:39', '2023-12-24 12:10:39'),
(89, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-25 00:05:44', '2023-12-25 00:05:44'),
(90, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-25 00:36:58', '2023-12-25 00:36:58'),
(91, 14, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-25 07:55:22', '2023-12-25 07:55:22'),
(92, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-25 08:20:41', '2023-12-25 08:20:41'),
(93, 17, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-25 08:32:26', '2023-12-25 08:32:26'),
(94, 16, '127.0.0.1', '', '', '', '', '', 'Firefox', 'Windows 10', '2023-12-25 08:40:01', '2023-12-25 08:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `phone` varchar(40) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'open: 1,\r\nclosed: 2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Kho Cầu Giấy', 'caugiay_wh@gmail.com', '0123456789', 'Cầu Giấy, Hà Nội', 1, '2023-12-22 10:16:29', '2023-12-22 10:16:29'),
(4, 'Kho Mỹ Đình', 'mydinh_wh@gmail.com', '0123456799', 'Mỹ Đình, Hà Nội', 1, '2023-12-24 09:00:53', '2023-12-24 09:00:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`email`,`phone`);

--
-- Indexes for table `courier_infos`
--
ALTER TABLE `courier_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courier_payments`
--
ALTER TABLE `courier_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `courier_products`
--
ALTER TABLE `courier_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_couriers`
--
ALTER TABLE `delivery_couriers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_sms_templates`
--
ALTER TABLE `email_sms_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frontends`
--
ALTER TABLE `frontends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `support_attachments`
--
ALTER TABLE `support_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courier_infos`
--
ALTER TABLE `courier_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `courier_payments`
--
ALTER TABLE `courier_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `courier_products`
--
ALTER TABLE `courier_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `delivery_couriers`
--
ALTER TABLE `delivery_couriers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `email_sms_templates`
--
ALTER TABLE `email_sms_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=219;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_attachments`
--
ALTER TABLE `support_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
