SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `pr_images` (
  `id` int(11) NOT NULL,
  `path` text COLLATE utf8_unicode_ci NOT NULL,
  `creator` int(11) NOT NULL,
  `alt` text COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_products` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `ed_izm_id` int(11) NOT NULL,
  `in_box` double,
  `min_kolvo` double,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_product_images` (
  `id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `alt` text COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `image` int(11) NOT NULL,
  `main` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_product_offers` (
  `id` int(11) NOT NULL,
  `product` int(11) NOT NULL,
  `shop` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `price` float NOT NULL,
  `date_buy` date NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_shops` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `town` text COLLATE utf8_unicode_ci NOT NULL,
  `network` text COLLATE utf8_unicode_ci NOT NULL,
  `owner` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_shop_images` (
  `id` int(11) NOT NULL,
  `shop` int(11) NOT NULL,
  `image` int(11) NOT NULL,
  `alt` text COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `main` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_users` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_user_images` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `alt` text COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `image` int(11) NOT NULL,
  `main` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_ed_izm` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `pr_images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pr_products`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pr_product_images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pr_product_offers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product` (`product`,`shop`,`creator`,`date_buy`);

ALTER TABLE `pr_shops`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pr_shop_images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pr_users`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pr_user_images`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `pr_ed_izm`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `pr_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_product_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_shop_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_user_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `pr_ed_izm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
