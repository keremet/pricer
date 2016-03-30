SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `pr_ed_izm` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_network` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_town` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_products_main_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `pr_products_main_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `pr_products_main_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_shops_main_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `pr_shops_main_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `pr_shops_main_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `pr_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci,
  `text` text COLLATE utf8_unicode_ci,
  `ed_izm_id` int(11),
  `in_box` double,
  `min_kolvo` double,
  `main_clsf_id` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `pr_products_ed_izm_id` FOREIGN KEY (`ed_izm_id`) REFERENCES `pr_ed_izm` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_products_main_clsf_id` FOREIGN KEY (`main_clsf_id`) REFERENCES `pr_products_main_clsf` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_products_creator` FOREIGN KEY (`creator`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci,
  `text` text COLLATE utf8_unicode_ci,
  `address` text COLLATE utf8_unicode_ci,
  `town_id` int(11),
  `network_id` int(11),
  `main_clsf_id` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `pr_shops_town_id` FOREIGN KEY (`town_id`) REFERENCES `pr_town` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_shops_network_id` FOREIGN KEY (`network_id`) REFERENCES `pr_network` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_shops_main_clsf_id` FOREIGN KEY (`main_clsf_id`) REFERENCES `pr_shops_main_clsf` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_shops_creator` FOREIGN KEY (`creator`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_product_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product` int(11) NOT NULL,
  `shop` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_buy` date NOT NULL,
  `price` double NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   UNIQUE KEY `product` (`product`,`shop`,`creator`,`date_buy`),
   CONSTRAINT `pr_product_offers_creator` FOREIGN KEY (`creator`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `pr_product_offers_product` FOREIGN KEY (`product`) REFERENCES `pr_products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `pr_product_offers_shop` FOREIGN KEY (`shop`) REFERENCES `pr_shops` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;






/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
