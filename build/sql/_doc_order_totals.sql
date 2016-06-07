/*
DROP TYPE doc_order_totals_param;
CREATE TYPE doc_order_totals_param AS (
		warehouse_id integer,
		client_id integer,
		product_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,
		quant numeric,				
		measure_unit_id integer,
		pack boolean,
		pack_in_price boolean,
		to_third_party_only boolean
	);
*/	
/*DROP function doc_order_totals(
		warehouse_id integer,
		client_id integer,
		product_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,
		quant numeric,				
		measure_unit_id integer,
		pack boolean,
		pack_in_price boolean,
		to_third_party_only boolean
);
*/
CREATE or REPLACE function doc_order_totals(
		warehouse_id integer,
		client_id integer,
		product_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,
		quant numeric,				
		measure_unit_id integer,
		pack boolean,
		pack_in_price boolean,
		to_third_party_only boolean
)
	RETURNS RECORD
		/*(
		base_quant numeric,
		volume_m numeric,
		weight_t numeric,
		price numeric,
		total numeric
		)
		*/
AS $body$
	WITH
		product_params AS (
		SELECT
			p.base_measure_unit_vol_m,
			p.base_measure_unit_weight_t,
			
			eval(
				eval_params(
					pmu.calc_formula,
					$4,
					$5,
					$6
				)
			) AS base_quant,
			CASE
				WHEN p.extra_pay_for_abnormal_size=TRUE
					AND (
						p.mes_length_def_val<>$4
						OR
						p.mes_width_def_val<>$5
						)
				THEN 
					eval(eval_params(
						p.extra_pay_calc_formula,
						$4,
						$5,
						$6
					))
					
				ELSE 0
			END AS extra_price
		FROM products p
		LEFT JOIN product_measure_units AS pmu
			ON pmu.product_id=p.id AND pmu.measure_unit_id=$8
		WHERE p.id=$3
		),
		
		price_list AS (
			SELECT 
				cplp.discount_volume,
				cplp.price,
				cplp.pack_price,
				cplp.discount_percent
			FROM client_price_lists AS cpl
			LEFT JOIN warehouses AS w
				ON w.production_city_id=cpl.production_city_id
			LEFT JOIN client_price_list_products AS cplp
				ON cplp.price_list_id=cpl.id
			LEFT JOIN products p ON p.id=cplp.product_id
			WHERE cpl.client_id = $2
				AND $11=cpl.to_third_party_only
				AND w.id = $1
				AND cplp.product_id = $3
				AND (cpl.min_order_quant=0
					OR
						(	cpl.min_order_quant>0
							AND cpl.min_order_quant <= (
								SELECT t.base_quant
								FROM product_params t)
						)
				)		
		)
	SELECT			
		--БАЗОВОЕ КОЛИЧЕСТВО
		round(
		(SELECT t.base_quant FROM product_params t)*
			$7
		,3) AS base_quant,
		
		--ОБЪЕМ
		round(
		(SELECT t.base_measure_unit_vol_m FROM product_params t)*
		(SELECT t.base_quant FROM product_params t)*
		$7
		,2) AS volume_m,

		--МАССА
		round(
		(SELECT t.base_measure_unit_weight_t FROM product_params t)*
		(SELECT t.base_quant FROM product_params t)*
		$7
		,3) AS weight_t,
		
		--ЦЕНА БЕЗ ТР - чистая
		CASE
			WHEN (SELECT t.base_quant FROM product_params t)<(SELECT t.discount_volume FROM price_list t) THEN
				(--Цена на продукцию + доп.цена за нестандартные размеры + упаковка
					(SELECT t.price FROM price_list t)+
					(SELECT t.extra_price FROM product_params t) +
					CASE
						WHEN $9 AND $10 THEN
							(SELECT t.pack_price FROM price_list t)
						ELSE 0
					END 
				)
			ELSE
				--со скидкой за объем
				round(
				(--Цена на продукцию + доп.цена за нестандартные размеры + упаковка
					(SELECT t.price FROM price_list t) +
					(SELECT t.extra_price FROM product_params t) +
					CASE
						WHEN $9 AND $10 THEN
							(SELECT t.pack_price FROM price_list t)
						ELSE 0
					END 
				) - 
				(--Цена на продукцию + доп.цена за нестандартные размеры + упаковка
					(SELECT t.price FROM price_list t)+					
					(SELECT t.extra_price FROM product_params t) +
					CASE
						WHEN $9 AND $10 THEN
							(SELECT t.pack_price FROM price_list t)
						ELSE 0
					END 
				)*(SELECT t.discount_percent FROM price_list t)/100
				,2)

		END,
		
		--СУММА
		round(
		CASE
			WHEN (SELECT t.base_quant FROM product_params t)<(SELECT t.discount_volume FROM price_list t) THEN
				(--Цена на продукцию + доп.цена за нестандартные размеры + упаковка
					(SELECT t.price FROM price_list t)+
					(SELECT t.extra_price FROM product_params t) +
					CASE
						WHEN
							$10 THEN 
								(SELECT t.pack_price FROM price_list t)
						ELSE 0
					END 
				)
			ELSE
				--со скидкой за объем
				round(
				(--Цена на продукцию + доп.цена за нестандартные размеры + упаковка
					(SELECT t.price FROM price_list t) +
					(SELECT t.extra_price FROM product_params t) +
					CASE
						WHEN $9 AND $10 THEN
							(SELECT t.pack_price FROM price_list t)
						ELSE 0
					END 
				) - 
				(--Цена на продукцию + доп.цена за нестандартные размеры + упаковка
					(SELECT t.price FROM price_list t) +
					(SELECT t.extra_price FROM product_params t) +
					CASE
						WHEN $9 AND $10 THEN
							(SELECT t.pack_price FROM price_list t)
						ELSE 0
					END 
				)*(SELECT t.discount_percent FROM price_list t)/100				
				,2)

		END * (SELECT t.base_quant FROM product_params t)*$7
		,2) AS total
		
	;
$body$
language sql;
ALTER function doc_order_totals(
		warehouse_id integer,
		client_id integer,
		product_id integer,
		mes_l integer,
		mes_w integer,
		mes_h integer,
		quant numeric,				
		measure_unit_id integer,
		pack boolean,
		pack_in_price boolean,
		to_third_party_only boolean
) OWNER TO polimerplast;