CREATE TABLE `pr_receipt` (
  `id`  int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `buyerAddress` text,
  `totalSum` int(11) DEFAULT NULL,
  `addressToCheckFiscalSign` text,
  `fiscalDriveNumber` int(20),
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
  `fiscalSign` bigint(20) DEFAULT NULL,
  `nds10` int(11) DEFAULT NULL,
  `fiscalDocumentNumber` int(11) DEFAULT NULL,
  `requestNumber` int(11) DEFAULT NULL,
  `dateTime` DATETIME DEFAULT NULL,
  `ndsNo` int(11) DEFAULT NULL,
  `user_id`  int(11) NOT NULL,
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


CREATE TABLE `pr_receipt_item_to_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `product_id` int(11) NOT NULL,
  `inn` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `name_inn` (`inn`,`name`) USING BTREE,
  CONSTRAINT `pr_receipt_item_to_product__product_id` FOREIGN KEY (`product_id`) REFERENCES `pr_products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE VIEW `pr_receipt_item_unknown`  AS  
select distinct 
	`i`.`name`,
	`r`.`userInn` AS `inn`,
	`r`.`dateTime`,
	`r`.`retailPlaceAddress` AS `address` 
from `pr_receipt_item` `i` 
	join `pr_receipt` `r` on`i`.`receipt_id` = `r`.`id`
	left join `pr_receipt_item_to_product` `p` on `r`.`userInn` = `p`.`inn` and `i`.`name` = `p`.`name`
where `p`.`inn` IS NULL;


CREATE VIEW `pr_receipt_purchases`  AS  
select 
	`a`.`sum`,
	`b`.`dateTime`,
	`b`.`user_id`,
	`d`.`product_id`
from `pr_receipt_item` `a` 
	join `pr_receipt` `b` on `a`.`receipt_id` = `b`.`id`
	join `pr_receipt_item_to_product` `d` on `b`.`userInn` = `d`.`inn` and `a`.`name` = `d`.`name`;

