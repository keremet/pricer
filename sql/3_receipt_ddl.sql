CREATE TABLE `rcp_receipt` (
  `id`  int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `buyerAddress` text,
  `totalSum` int(11) DEFAULT NULL,
  `addressToCheckFiscalSign` text,
  `fiscalDriveNumber` varchar(100),
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
   CONSTRAINT `rcp_receipt_uk` UNIQUE KEY (`dateTime`, `totalSum`, `fiscalDocumentNumber`, `fiscalSign`, `fiscalDriveNumber`),
   CONSTRAINT `rcp_receipt_user_id` FOREIGN KEY (`user_id`) REFERENCES `pr_users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `rcp_item` (
  `id`  int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `receipt_id` int(11) DEFAULT NULL,
  `sum` int(11) DEFAULT NULL,
  `nds10` int(11) DEFAULT NULL,
  `name` text,
  `price` int(11) DEFAULT NULL,
  `nds18` int(11) DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `ndsNo` int(11) DEFAULT NULL,
   CONSTRAINT `rcp_item_receipt_id` FOREIGN KEY (`receipt_id`) REFERENCES `rcp_receipt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `rcp_modifier` (
  `item_id` int(11) NOT NULL PRIMARY KEY,
  `discountName` text,
  `markupName` text,
  `discountSum` int(11) DEFAULT NULL,
  CONSTRAINT `rcp_modifier_item_id` FOREIGN KEY (`item_id`) REFERENCES `rcp_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
