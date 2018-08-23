/*
DROP function doc_order_calc_quant_in_mu(
		product_id integer,
		measure_unit_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,		
		quant numeric,
		measure_unit_id_from integer		
);
*/
/*
Пересчитывает количество в любой единице
в количество в другой единице
*/
CREATE or REPLACE function doc_order_calc_quant_in_mu(
		product_id integer,
		measure_unit_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,		
		quant numeric,
		measure_unit_id_from integer		
)
	RETURNS numeric
AS $body$
	SELECT doc_order_calc_quant(
		$1,--prod id		
		$2, --mu to
		$3,$4,$5,
		
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
					WHERE pmu.product_id=$1
						AND pmu.measure_unit_id=$7
					),
					$3,$4,$5
				)
			)
		END
		*$6
	)	
	;
$body$
language sql;
ALTER function doc_order_calc_quant_in_mu(
		product_id integer,
		measure_unit_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,		
		quant numeric,
		measure_unit_id_from integer		
) OWNER TO polimerplast;
