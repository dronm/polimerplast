-- View: doc_orders_t_tmp_products_dialog

--DROP VIEW doc_orders_t_tmp_products_dialog;

CREATE OR REPLACE VIEW doc_orders_t_tmp_products_dialog AS 
	SELECT 
		t.login_id,
		t.view_id,
		t.line_number,
		t.product_id,
		p.name AS product_descr,
		t.quant,
		ROUND(t.quant,0) AS quant_descr,
		t.quant_base_measure_unit,
		t.quant_confirmed_base_measure_unit,
		t.volume,
		t.weight,
		t.price,
		format_money(t.price) AS price_descr,
		t.total,
		format_money(t.total) AS total_descr,
		t.mes_length,
		t.mes_width,
		t.mes_height,
		
		--document measure unit
		t.measure_unit_id,
		mu.name AS measure_unit_descr,
		mu.is_int AS measure_unit_is_int,
		
		t.pack_exists,
		t.pack_in_price,
		t.price_edit,
		
		--base measure unit
		p.base_measure_unit_id,
		base_mu.name AS base_measure_unit_descr,
		base_mu.is_int AS base_measure_unit_is_int,
		
		CASE
			WHEN t.quant_confirmed_base_measure_unit>0 THEN
				(SELECT doc_order_calc_quant(
					t.product_id,
					t.measure_unit_id,
					t.mes_length,
					t.mes_width,
					t.mes_height,
					t.quant_confirmed_base_measure_unit
					)
				)
			
			ELSE 0
		END AS quant_confirmed_measure_unit
		
	FROM doc_orders_t_tmp_products AS t
	LEFT JOIN products AS p ON p.id=t.product_id
	LEFT JOIN measure_units AS mu ON mu.id=t.measure_unit_id
	LEFT JOIN measure_units AS base_mu ON base_mu.id=p.base_measure_unit_id
	ORDER BY t.line_number;
ALTER TABLE doc_orders_t_tmp_products_dialog OWNER TO polimerplast;

