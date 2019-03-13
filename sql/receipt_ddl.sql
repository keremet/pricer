CREATE TABLE `pr_receipt` (
  `id`  int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `buyerAddress` text,
  `totalSum` int(11) NOT NULL,
  `addressToCheckFiscalSign` text,
  `fiscalDriveNumber` varchar(100) NOT NULL,
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
  `rawReceipt` text,
  `checked` BOOLEAN NOT NULL DEFAULT FALSE,
   CONSTRAINT `pr_receipt_uk` UNIQUE KEY (`dateTime`, `totalSum`, `fiscalDriveNumber`, `fiscalDocumentNumber`, `fiscalSign`),
   CONSTRAINT `pr_receipt_user_id` FOREIGN KEY (`user_id`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `pr_receipt_item` (
  `id`  int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `receipt_id` int(11) DEFAULT NULL,
  `sum` int(11) DEFAULT NULL,
  `nds10` int(11) DEFAULT NULL,
  `name` text,
  `price` int(11) DEFAULT NULL,
  `nds18` int(11) DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `ndsNo` int(11) DEFAULT NULL,
   CONSTRAINT `pr_receipt_item_receipt_id` FOREIGN KEY (`receipt_id`) REFERENCES `pr_receipt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `pr_receipt_modifier` (
  `item_id` int(11) NOT NULL PRIMARY KEY,
  `discountName` text,
  `markupName` text,
  `discountSum` int(11) DEFAULT NULL,
  CONSTRAINT `pr_receipt_modifier_item_id` FOREIGN KEY (`item_id`) REFERENCES `pr_receipt_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `pr_receipt_to_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `shop_id` int(11) NOT NULL,
  `inn` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci,
  `address` varchar(100) COLLATE utf8_unicode_ci,
  `user_id`  int(11) NOT NULL,
  UNIQUE KEY `name_inn` (`inn`, `name`, `address`) USING BTREE,
  CONSTRAINT `pr_receipt_to_shop__shop_id` FOREIGN KEY (`shop_id`) REFERENCES `pr_shops` (`id`),
  CONSTRAINT `pr_receipt_to_shop__user_id` FOREIGN KEY (`user_id`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `pr_receipt_item_to_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product_id` int(11) NOT NULL,
  `inn` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id`  int(11) NOT NULL,
  UNIQUE KEY `name_inn` (`inn`,`name`) USING BTREE,
  CONSTRAINT `pr_receipt_item_to_product__product_id` FOREIGN KEY (`product_id`) REFERENCES `pr_products` (`id`),
  CONSTRAINT `pr_receipt_item_to_product__user_id` FOREIGN KEY (`user_id`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `pr_receipt_user` (
  `fns_userpwd` varchar(30) NOT NULL PRIMARY KEY,
  `user_id` int(11) NOT NULL,
  `dtLastLimit` DATETIME NOT NULL default '2001-01-01',
  CONSTRAINT `pr_receipt_user__user_id` FOREIGN KEY (`user_id`) REFERENCES `pr_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE VIEW `pr_receipt_purchases`  AS  
select 
	`a`.`sum`,
	`b`.`dateTime`,
	`b`.`user_id`,
	`d`.`product_id`
from `pr_receipt_item` `a` 
	join `pr_receipt` `b` on `a`.`receipt_id` = `b`.`id`
	join `pr_receipt_item_to_product` `d` on `b`.`userInn` = `d`.`inn` and `a`.`name` = `d`.`name`;


CREATE VIEW `pr_fact`  AS
SELECT id, product, shop, creator, date_buy, price, amount
FROM pr_product_offers
UNION ALL
SELECT null, i2p.product_id, r2s.shop_id, r.user_id, r.dateTime, i.price/100, i.quantity
FROM pr_receipt r
  JOIN pr_receipt_to_shop r2s ON r2s.inn = r.userInn 
	AND ((r2s.name = r.user) OR ( r2s.name is null AND r.user is null ))
	AND ((r2s.address = r.retailPlaceAddress) OR ( r2s.address is null AND r.retailPlaceAddress is null ))
  JOIN pr_receipt_item i ON i.receipt_id = r.id
  JOIN pr_receipt_item_to_product i2p ON r.userInn = i2p.inn and i.name = i2p.name;
