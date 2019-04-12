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

CREATE TABLE `pr_user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci,
  `del_anothers_receipts` integer DEFAULT 0 NOT NULL,
  `del_anothers_consumptions` integer DEFAULT 0 NOT NULL,
  `del_anothers_shop_links` integer DEFAULT 0 NOT NULL,
  `del_anothers_product_links` integer DEFAULT 0 NOT NULL,
  `del_anothers_shops` integer DEFAULT 0 NOT NULL,
  `del_anothers_products` integer DEFAULT 0 NOT NULL,
  `edt_anothers_shops` integer DEFAULT 0 NOT NULL,
  `edt_anothers_products` integer DEFAULT 0 NOT NULL,
  `upload_receipts_from_file` integer DEFAULT 0 NOT NULL,
  `download_backup` integer DEFAULT 0 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `group_id` int(11) NOT NULL,
  CONSTRAINT `pr_users__group_id` FOREIGN KEY (`group_id`) REFERENCES `pr_user_group` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_products_main_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `pr_products_main_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `pr_products_main_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_products_equ_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `pr_products_equ_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `pr_products_equ_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `barcode` bigint(13),
  `main_clsf_id` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY (`barcode`),
  CONSTRAINT `pr_products_ed_izm_id` FOREIGN KEY (`ed_izm_id`) REFERENCES `pr_ed_izm` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_products_main_clsf_id` FOREIGN KEY (`main_clsf_id`) REFERENCES `pr_products_main_clsf` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_products_creator` FOREIGN KEY (`creator`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_equ_products` (
  `product_id` int(11) NOT NULL,
  `equ_clsf_id` int(11) NOT NULL,
  CONSTRAINT `pr_equ_products_product_id` FOREIGN KEY (`product_id`) REFERENCES `pr_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pr_equ_products_equ_clsf_id` FOREIGN KEY (`equ_clsf_id`) REFERENCES `pr_products_equ_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `amount` double DEFAULT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   UNIQUE KEY `product` (`product`,`shop`,`creator`,`date_buy`),
   CONSTRAINT `pr_product_offers_creator` FOREIGN KEY (`creator`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `pr_product_offers_product` FOREIGN KEY (`product`) REFERENCES `pr_products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `pr_product_offers_shop` FOREIGN KEY (`shop`) REFERENCES `pr_shops` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_consumption_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `creator` int(11),
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `pr_consumption_clsf_creator` FOREIGN KEY (`creator`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `pr_consumption_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `pr_consumption_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_consumption` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date_buy` date NOT NULL,
  `clsf_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   CONSTRAINT `pr_consumption_clsf_id` FOREIGN KEY (`clsf_id`) REFERENCES `pr_consumption_clsf` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `pr_tmp_consumption_sums` (
  `index` text COLLATE utf8_unicode_ci,
  `name` text COLLATE utf8_unicode_ci,
  `sum_own` double,
  `sum_child` double
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
