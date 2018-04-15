DELIMITER $$

CREATE DEFINER=`p4039_koopkirov8`@`%` FUNCTION `pr_getBasePrice` (`idProduct` INT, `dDate` DATE, `fInBox` DOUBLE) RETURNS DOUBLE READS SQL DATA
BEGIN
DECLARE fBasePrice DOUBLE;
DECLARE idBaseShop INT default 2;

select min(ed_izm_price*(case product when idProduct then 1 else fInBox end))
into fBasePrice
	from (
	   select poLP.product, 
        min(poLP.price)/(case poLP.product when idProduct then 1 else ifnull(p.in_box, 1) end) ed_izm_price
		from pr_fact poLP, pr_products p
		where (poLP.product, poLP.shop, poLP.date_buy) in (
			select poMD.product, poMD.shop, max(poMD.date_buy)
			from pr_fact poMD
			where poMD.product in (
					select idProduct
					union
					select ep2.product_id
					from pr_equ_products ep1, pr_equ_products ep2
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
