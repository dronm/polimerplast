-- View: doc_orders_data_for_ext

DROP VIEW doc_orders_data_for_ext;

CREATE OR REPLACE VIEW doc_orders_data_for_ext AS 
	SELECT
		t.doc_id,
		--ШАПКА
		f.ext_id AS firm_ref,
		w.ext_id AS warehouse_ref,
		c.ext_id AS client_ref,
		(c.pay_type='cash'::payment_types) AS pay_cash,
		
		CASE
		WHEN h.deliv_type='by_supplier'::delivery_types
			AND coalesce(h.deliv_add_cost_to_product,FALSE)=FALSE THEN
				h.deliv_total
		ELSE 0
		END AS deliv_total,
		
		h.total_pack AS pack_total,
		h.number,
		
		--dest.address AS deliv_address,
		CASE
			WHEN coalesce(h.destination_to_ttn,FALSE) THEN
				dest.address
			ELSE c.addr_reg
		END AS deliv_address,
		
		dr.ext_id AS driver_ref,
		vh.plate AS vh_plate,
		vh.trailer_plate AS vh_trailer_plate,
		vh.trailer_model AS vh_trailer_model,
		--ТАБЛИЦА
		
		p.name AS group_name,
		pg.ext_id AS product_group_ref,
		
		products_descr(p,
			t.mes_length,t.mes_width,t.mes_height,
			TRUE
			) AS product_name,
		
		t.mes_length AS mes_length,
		t.mes_width AS mes_width,
		t.mes_height AS mes_height,
		mu.name AS measure_unit,
		mu.ext_id AS measure_unit_ref,
		bmu.ext_id AS base_measure_unit_ref,

		eval(
			eval_params(
				pmu.calc_formula,
				t.mes_length,t.mes_width,t.mes_height
			)
		) AS measure_unit_k,
		/*
		CASE WHEN t.quant_base_measure_unit>0 THEN
			t.quant/t.quant_base_measure_unit
		ELSE 0::numeric
		END AS measure_unit_k,
		*/
		
		t.quant AS quant,
		CASE
			WHEN COALESCE(t.quant)=0 THEN 0
			ELSE ROUND(t.total/t.quant,2)
		END AS price,
		t.total AS total,
		
		h.ext_order_id,
		h.ext_ship_id,
		h.client_comment,
		h.sales_manager_comment,
		h.deliv_vehicle_count
		
	FROM doc_orders_t_products t
	LEFT JOIN doc_orders h ON h.id=t.doc_id
	LEFT JOIN products p ON p.id=t.product_id
	LEFT JOIN firms f ON f.id=h.firm_id
	LEFT JOIN warehouses w ON w.id=h.warehouse_id
	LEFT JOIN clients c ON c.id=h.client_id
	LEFT JOIN client_destinations_list dest ON dest.id=h.deliv_destination_id
	LEFT JOIN measure_units mu ON mu.id=t.measure_unit_id
	LEFT JOIN product_measure_units AS pmu
			ON pmu.product_id=p.id AND pmu.measure_unit_id=t.measure_unit_id
	LEFT JOIN measure_units bmu ON bmu.id=p.base_measure_unit_id
	LEFT JOIN deliveries dlv ON dlv.doc_order_id=h.id
	LEFT JOIN vehicles vh ON vh.id=dlv.vehicle_id
	LEFT JOIN drivers dr ON dr.id=vh.driver_id
	LEFT JOIN product_groups pg ON pg.id=p.product_group_id
	;
ALTER TABLE doc_orders_data_for_ext OWNER TO polimerplast;

