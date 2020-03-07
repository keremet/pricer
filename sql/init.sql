SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `ed_izm` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `network` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `town` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `user_group` (
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
  `download_backup` integer DEFAULT 0 NOT NULL,
  `show_login` integer DEFAULT 0 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `login` text COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `photo` text COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `group_id` int(11) NOT NULL,
  CONSTRAINT `users__group_id` FOREIGN KEY (`group_id`) REFERENCES `user_group` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `products_main_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `products_main_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `products_main_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `products_equ_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `products_equ_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `products_equ_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `shops_main_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `shops_main_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `shops_main_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `products` (
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
  CONSTRAINT `products_ed_izm_id` FOREIGN KEY (`ed_izm_id`) REFERENCES `ed_izm` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `products_main_clsf_id` FOREIGN KEY (`main_clsf_id`) REFERENCES `products_main_clsf` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `products_creator` FOREIGN KEY (`creator`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `equ_products` (
  `product_id` int(11) NOT NULL,
  `equ_clsf_id` int(11) NOT NULL,
  CONSTRAINT `equ_products_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `equ_products_equ_clsf_id` FOREIGN KEY (`equ_clsf_id`) REFERENCES `products_equ_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `shops` (
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
  CONSTRAINT `shops_town_id` FOREIGN KEY (`town_id`) REFERENCES `town` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `shops_network_id` FOREIGN KEY (`network_id`) REFERENCES `network` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `shops_main_clsf_id` FOREIGN KEY (`main_clsf_id`) REFERENCES `shops_main_clsf` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `shops_creator` FOREIGN KEY (`creator`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `product_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product` int(11) NOT NULL,
  `shop` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `date_buy` date NOT NULL,
  `price` double NOT NULL,
  `amount` double DEFAULT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   UNIQUE KEY `product` (`product`,`shop`,`creator`,`date_buy`),
   CONSTRAINT `product_offers_creator` FOREIGN KEY (`creator`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `product_offers_product` FOREIGN KEY (`product`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `product_offers_shop` FOREIGN KEY (`shop`) REFERENCES `shops` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `consumption_clsf` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `creator` int(11),
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `id_hi` int(11),
  CONSTRAINT `consumption_clsf_creator` FOREIGN KEY (`creator`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `consumption_clsf_id_hi` FOREIGN KEY (`id_hi`) REFERENCES `consumption_clsf` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `consumption` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date_buy` date NOT NULL,
  `clsf_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `date_change` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   CONSTRAINT `consumption_clsf_id` FOREIGN KEY (`clsf_id`) REFERENCES `consumption_clsf` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `tmp_consumption_sums` (
  `index` text COLLATE utf8_unicode_ci,
  `name` text COLLATE utf8_unicode_ci,
  `sum_own` double,
  `sum_child` double
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `receipt` (
  `id`  int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `buyerAddress` text,
  `totalSum` int(11) NOT NULL,
  `addressToCheckFiscalSign` text,
  `fiscalDriveNumber` bigint NOT NULL,
  `rawData` text,
  `kktRegId` text,
  `user` text,
  `operationType` int(11) DEFAULT NULL,
  `shiftNumber` int(11) DEFAULT NULL,
  `ecashTotalSum` int(11) DEFAULT NULL,
  `nds18` int(11) DEFAULT NULL,
  `retailPlaceAddress` text,
  `userInn` text,
  `taxationType` int(11) DEFAULT NULL,
  `cashTotalSum` int(11) DEFAULT NULL,
  `operator` text,
  `senderAddress` text,
  `receiptCode` int(11) DEFAULT NULL,
  `fiscalSign` bigint(20) NOT NULL,
  `nds10` int(11) DEFAULT NULL,
  `fiscalDocumentNumber` int(11) NOT NULL,
  `requestNumber` int(11) DEFAULT NULL,
  `dateTime` DATETIME NOT NULL,
  `ndsNo` int(11) DEFAULT NULL,
  `user_id`  int(11) NOT NULL,
  `ins_user_id`  int(11) NOT NULL,
  `dtInsert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rawReceipt` text,
  `checked` BOOLEAN NOT NULL DEFAULT FALSE,
   CONSTRAINT `receipt_uk` UNIQUE KEY (`dateTime`, `totalSum`, `fiscalDriveNumber`, `fiscalDocumentNumber`, `fiscalSign`),
   CONSTRAINT `receipt_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
   CONSTRAINT `receipt_ins_user_id` FOREIGN KEY (`ins_user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `receipt_item` (
  `id`  int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `receipt_id` int(11) DEFAULT NULL,
  `sum` int(11) DEFAULT NULL,
  `nds10` int(11) DEFAULT NULL,
  `name` text,
  `price` int(11) DEFAULT NULL,
  `nds18` int(11) DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `ndsNo` int(11) DEFAULT NULL,
   CONSTRAINT `receipt_item_receipt_id` FOREIGN KEY (`receipt_id`) REFERENCES `receipt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `receipt_modifier` (
  `item_id` int(11) NOT NULL PRIMARY KEY,
  `discountName` text,
  `markupName` text,
  `discountSum` int(11) DEFAULT NULL,
  CONSTRAINT `receipt_modifier_item_id` FOREIGN KEY (`item_id`) REFERENCES `receipt_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `fdn_to_shop` (
  `fiscalDriveNumber` bigint PRIMARY KEY,
  `shop_id` int(11) NOT NULL,
  CONSTRAINT `fdn_to_shop__shop_id` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `receipt_item_name_to_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `receipt_item_name_to_product__name` (`name`) USING BTREE,
  CONSTRAINT `receipt_item_name_to_product__product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `receipt_user` (
  `fns_userpwd` varchar(30) NOT NULL PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `dtLastLimit` DATETIME NOT NULL default '2001-01-01',
  CONSTRAINT `pr_receipt_user__user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE VIEW `fact`  AS
SELECT id, product, shop, creator, date_buy, price, amount
FROM product_offers
UNION ALL
SELECT null, i2p.product_id, r2s.shop_id, r.user_id, r.dateTime, i.price/100, i.quantity
FROM receipt r
  JOIN fdn_to_shop r2s on r.fiscalDriveNumber = r2s.fiscalDriveNumber
  JOIN receipt_item i ON i.receipt_id = r.id
  JOIN receipt_item_name_to_product i2p ON i.name = i2p.name;

DELIMITER $$

CREATE FUNCTION `getBasePrice` (`idProduct` INT, `dDate` DATE, `fInBox` DOUBLE) RETURNS DOUBLE READS SQL DATA
BEGIN
DECLARE fBasePrice DOUBLE;
DECLARE idBaseShop INT default 2;

select min(ed_izm_price*(case product when idProduct then 1 else fInBox end))
into fBasePrice
	from (
	   select poLP.product, 
        min(poLP.price)/(case poLP.product when idProduct then 1 else ifnull(p.in_box, 1) end) ed_izm_price
		from fact poLP, products p
		where (poLP.product, poLP.shop, poLP.date_buy) in (
			select poMD.product, poMD.shop, max(poMD.date_buy)
			from fact poMD
			where poMD.product in (
					select idProduct
					union
					select ep2.product_id
					from equ_products ep1, equ_products ep2
					where ep1.product_id = idProduct and ep1.equ_clsf_id = ep2.equ_clsf_id
				)
				and poMD.shop = idBaseShop
				and poMD.date_buy <= dDate
			group by poMD.product
		)
		and poLP.product = p.id
		group by poLP.product
	) as eips;
    
	RETURN fBasePrice;
END$$

DELIMITER ;

INSERT INTO ed_izm (name) VALUES
('кг'),
('л'),
('шт'),
('м');

INSERT INTO products_main_clsf (name) VALUES
('Главный классификатор товаров');

INSERT INTO shops_main_clsf (name) VALUES
('Главный классификатор магазинов');

INSERT INTO user_group (id, name, del_anothers_receipts, del_anothers_consumptions, del_anothers_shop_links
                        , del_anothers_product_links, del_anothers_shops, del_anothers_products
                        , edt_anothers_shops, edt_anothers_products, upload_receipts_from_file, download_backup, show_login) VALUES
(1, 'Администратор', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

INSERT INTO user_group (id, name, del_anothers_receipts, del_anothers_consumptions, del_anothers_shop_links
                        , del_anothers_product_links, del_anothers_shops, del_anothers_products
                        , edt_anothers_shops, edt_anothers_products, upload_receipts_from_file, download_backup, show_login) VALUES
(2, 'Пользователь', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
