/*DROP VIEW doc_orders_print_products;
DROP function doc_order_calc_quant_in_mu(
		in_product_id integer,
		in_in_measure_unit_id integer,
		mes_l integer,
		in_mes_w integer,
		in_mes_h integer,		
		in_quant numeric,
		in_measure_unit_id_from integer		
);
*/
/**
 * Пересчитывает количество в любой единице
 * в количество в другой единице
 */
CREATE or REPLACE function doc_order_calc_quant_in_mu(
		in_product_id integer,
		in_measure_unit_id integer,
		in_mes_l integer,
		in_mes_w integer,
		in_mes_h integer,		
		in_quant numeric,
		in_measure_unit_id_from integer		
)
	RETURNS numeric
AS $body$
	
	SELECT
		CASE
			WHEN coalesce((SELECT mu.is_int FROM measure_units AS mu WHERE mu.id=in_measure_unit_id),FALSE) THEN ceil(val * in_quant)
			ELSE round(val * in_quant,9)
		END
	FROM
	(
		SELECT
			eval(
				eval_params(
					(SELECT
						pmu.calc_formula
					FROM product_measure_units AS pmu
					WHERE pmu.product_id=in_product_id AND pmu.measure_unit_id=in_measure_unit_id_from
					),
					in_mes_l,in_mes_w,in_mes_h
				)
			)
			/eval(
				eval_params(
					(SELECT
						pmu.calc_formula
					FROM product_measure_units AS pmu
					WHERE pmu.product_id=in_product_id AND pmu.measure_unit_id=in_measure_unit_id
					),
					in_mes_l,in_mes_w,in_mes_h
				)
			)
			AS val
	) AS q
	
	/*
	SELECT doc_order_calc_quant(
		in_product_id,--prod id		
		in_measure_unit_id, --mu to
		in_mes_l,in_mes_w,in_mes_h,
		
		CASE
		WHEN 
			(--базовая единица продукции
			SELECT p.base_measure_unit_id AS id
			FROM products AS p
			WHERE p.id=$1
			)=$7 THEN
			--перевод из базовой единицы
			1
		ELSE
			--перевод НЕ из базовой единицы
			--базовое кол-во по входящей ед-це
			eval(
				eval_params(
					(SELECT
						pmu.calc_formula
					FROM product_measure_units AS pmu
					WHERE pmu.product_id=in_product_id AND pmu.measure_unit_id=in_measure_unit_id_from
					),
					in_mes_l,in_mes_w,in_mes_h
				)
			)			
		END
		*in_quant
	)
	*/
	;
$body$
language sql;
ALTER function doc_order_calc_quant_in_mu(
		in_product_id integer,
		in_measure_unit_id integer,
		in_mes_l integer,
		in_mes_w integer,
		in_mes_h integer,		
		in_quant numeric,
		in_measure_unit_id_from integer		
) OWNER TO polimerplast;
