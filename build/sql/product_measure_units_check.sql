/*
DROP FUNCTION product_measure_units_check(
		product_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,
		measure_unit_id int,
		quant numeric
);
*/		
/*
Возвращается единица с неверным пересчетом
*/
CREATE OR REPLACE FUNCTION product_measure_units_check(
		product_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,
		measure_unit_id int,
		quant numeric
) RETURNS TABLE (
	measure_unit_id int,
	measure_unit_descr text,
	quant numeric
)
AS $body$

	WITH
	-- пересчет в базовое количество
	base_quant AS (SELECT 
		eval(
			eval_params(
				(SELECT t.calc_formula
				FROM product_measure_units t
				WHERE t.product_id=$1
				AND t.measure_unit_id=$5)
				,$2,$3,$4
			)
		)*$6 AS q
	)
	SELECT
			mu.id,
			mu.name_full::text,
			ROUND(
				( (SELECT t.q FROM base_quant t) / eval( eval_params(pmu.calc_formula,$2,$3,$4) ) )
			,4) AS quant
		FROM product_measure_units AS pmu
		LEFT JOIN measure_units AS mu ON mu.id=pmu.measure_unit_id
		WHERE
			pmu.product_id=$1
			AND pmu.in_use AND mu.is_int
			AND mu.id<>$5
			AND 
				@(
				((SELECT t.q FROM base_quant t) / eval( eval_params(pmu.calc_formula,$2,$3,$4) ) ) -
				ROUND((SELECT t.q FROM base_quant t) / eval( eval_params(pmu.calc_formula,$2,$3,$4) ))
				)>const_product_measure_unit_check_deviat_val()
		;
		
$body$
language sql;
	
ALTER function product_measure_units_check(
		product_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,
		measure_unit_id int,
		quant numeric
) OWNER TO polimerplast;