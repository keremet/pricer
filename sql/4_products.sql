CREATE TABLE `pr_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `prod_id` int(11) NOT NULL,
  `inn` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `name_inn` (`inn`,`name`) USING BTREE,
  CONSTRAINT `pr_ids_ibfk_1` FOREIGN KEY (`prod_id`) REFERENCES `pr_consumption_clsf` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE VIEW `pr_items_unknown`  AS  
select distinct 
	`i`.`name` AS `name`,
	`r`.`userInn` AS `inn`,
	`r`.`dateTime` AS `date`,
	`r`.`retailPlaceAddress` AS `address` 
from `rcp_item` `i` 
	join `rcp_receipt` `r` on`i`.`receipt_id` = `r`.`id`
	left join `pr_ids` `p` on `r`.`userInn` = `p`.`inn` and `i`.`name` = `p`.`name`
where `p`.`inn` IS NULL;

CREATE VIEW `pr_purchases`  AS  
select 
	`a`.`sum` AS `sum`,
	`b`.`dateTime` AS `date`,
	`c`.`id` AS `buyer_id`,
	`d`.`prod_id` AS `item_id` 
from `rcp_item` `a` 
	join `rcp_receipt` `b` on `a`.`receipt_id` = `b`.`id`
	join `pr_users` `c` on `b`.`user_id` = `c`.`id`
	join `pr_ids` `d` on `b`.`userInn` = `d`.`inn` and `a`.`name` = `d`.`name`;