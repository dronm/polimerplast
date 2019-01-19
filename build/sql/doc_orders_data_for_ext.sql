-- View: doc_orders_data_for_ext

--DROP VIEW doc_orders_data_for_ext;

/*
 * АТРИБУТЫ ВОДИТЕЛЬ - ДЛЯ ЗАПИСИ В СВОЙСТВА!!!
*/
CREATE OR REPLACE VIEW doc_orders_data_for_ext AS 
	/*
	WITH
	ttn_data AS (
		SELECT
			t_cl.ext_id AS carrier_ref,
			t_dr.ext_id AS driver_ref,
			t_dr.name AS driver_name,
			t_dr.drive_perm AS driver_drive_perm,
			t_dr.cel_phone AS driver_cel_phone,
			t_vh.model AS vh_model,
			t_vh.plate AS vh_plate,
			t_vh.load_weight_t AS vh_load_weight_t,
			t_vh.vol AS vh_vol,
			t_vh.trailer_plate AS vh_trailer_plate,
			t_vh.trailer_model As vh_trailer_model
		FROM carrier_orders t
		LEFT JOIN carriers AS t_cr ON t_cr.id=t.carrier_id
		LEFT JOIN clients AS t_cl ON t_cl.id=t_cr.client_id
		LEFT JOIN drivers AS t_dr ON t_dr.id=t.driver_id
		LEFT JOIN vehicles AS t_vh ON t_vh.id=t.vehicle_id
		WHERE t.ord=carrier_orders_today_ord(now()::timestamp without time zone)
	)
	*/
	SELECT
		--t.doc_id,
		h.id AS doc_id,
		
		--ШАПКА
		f.ext_id AS firm_ref,
		w.ext_id AS warehouse_ref,
		w.address AS warehouse_address,
		cl.ext_id AS client_ref,
		(cl.pay_type='cash'::payment_types) AS pay_cash,
		
		CASE
		WHEN h.deliv_type='by_supplier'::delivery_types
			AND coalesce(h.deliv_add_cost_to_product,FALSE)=FALSE THEN
				h.deliv_total
		ELSE 0
		END AS deliv_total,
		h.deliv_type,
		
		h.total_pack AS pack_total,
		h.number,
		
		--dest.address AS deliv_address,
		CASE
			WHEN coalesce(h.destination_to_ttn,FALSE) THEN
				dest.address
			ELSE cl.addr_reg
		END AS deliv_address,
		
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.driver_ref FROM ttn_data)
			WHEN order_dr IS NOT NULL THEN order_dr.ext_id
			ELSE dr.ext_id
		END AS driver_ref,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.driver_name FROM ttn_data)
			WHEN order_dr IS NOT NULL THEN order_dr.name
			ELSE dr.name
		END AS driver_name,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.driver_drive_perm FROM ttn_data)
			WHEN order_dr IS NOT NULL THEN order_dr.drive_perm
			ELSE dr.drive_perm
		END AS driver_drive_perm,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.driver_cel_phone FROM ttn_data)
			WHEN order_dr IS NOT NULL THEN order_dr.cel_phone
			ELSE dr.cel_phone
		END AS driver_cel_phone,

		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.vh_trailer_plate FROM ttn_data)
			WHEN order_vh IS NOT NULL THEN order_vh.trailer_plate
			ELSE vh.trailer_plate
		END AS vh_trailer_plate,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.vh_trailer_model FROM ttn_data)
			WHEN order_vh IS NOT NULL THEN order_vh.trailer_model
			ELSE vh.trailer_model
		END AS vh_trailer_model,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.carrier_ref FROM ttn_data)
			WHEN order_dr IS NOT NULL THEN order_carr_cl.ext_id
			ELSE carr_cl.ext_id
		END AS carrier_ref,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.vh_model FROM ttn_data)
			WHEN order_vh IS NOT NULL THEN order_vh.model
			ELSE vh.model
		END AS vh_model,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.vh_plate FROM ttn_data)
			WHEN order_vh IS NOT NULL THEN order_vh.plate
			ELSE vh.plate
		END AS vh_plate,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.vh_vol FROM ttn_data)
			WHEN order_vh IS NOT NULL THEN order_vh.vol
			ELSE vh.vol
		END AS vh_vol,
		CASE
			--WHEN ttn_pairs.firm_id IS NOT NULL THEN (SELECT ttn_data.vh_load_weight_t FROM ttn_data)
			WHEN order_vh IS NOT NULL THEN order_vh.load_weight_t
			ELSE vh.load_weight_t
		END AS vh_load_weight_t,
		
		--ТАБЛИЦА
		
		p.name AS group_name,
		pg.ext_id AS product_group_ref,
		
		p.fin_group,
		p.analit_group,
		
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
		--t.quant_confirmed AS quant_confirmed,
		
		CASE
			WHEN COALESCE(t.quant)=0 THEN 0
			ELSE ROUND(t.total/t.quant,2)
		END AS price,
		t.total AS total,
		
		h.ext_order_id,
		h.ext_ship_id,
		h.client_comment,
		h.sales_manager_comment,
		h.deliv_vehicle_count,
		
		f.nds AS firm_nds,
		
		h.total_volume AS total_volume,
		h.total_weight AS total_weight,
		
		h.deliv_expenses,
		
		cl2.ext_id AS gruzopoluchatel_ref
		
	--FROM doc_orders_t_products t
	--LEFT JOIN doc_orders h ON h.id=t.doc_id
	FROM doc_orders h
	LEFT JOIN doc_orders_t_products t ON t.doc_id=h.id
	
	LEFT JOIN products p ON p.id=t.product_id
	LEFT JOIN firms f ON f.id=h.firm_id
	LEFT JOIN warehouses w ON w.id=h.warehouse_id
	LEFT JOIN clients cl ON cl.id=h.client_id
	LEFT JOIN clients cl2 ON cl2.id=h.gruzopoluchatel_id
	LEFT JOIN client_destinations_list dest ON dest.id=h.deliv_destination_id
	LEFT JOIN measure_units mu ON mu.id=t.measure_unit_id
	LEFT JOIN product_measure_units AS pmu
			ON pmu.product_id=p.id AND pmu.measure_unit_id=t.measure_unit_id
	LEFT JOIN measure_units bmu ON bmu.id=p.base_measure_unit_id
	LEFT JOIN deliveries dlv ON dlv.doc_order_id=h.id
	LEFT JOIN vehicles vh ON vh.id=dlv.vehicle_id
	LEFT JOIN drivers dr ON dr.id=vh.driver_id
	LEFT JOIN product_groups pg ON pg.id=p.product_group_id
	LEFT JOIN drivers AS order_dr ON order_dr.id=h.driver_id
	LEFT JOIN vehicles AS order_vh ON order_vh.driver_id=h.driver_id
	LEFT JOIN carriers ON carriers.id=vh.carrier_id
	LEFT JOIN clients AS carr_cl ON carr_cl.id=carriers.client_id
	LEFT JOIN carriers AS order_carr ON order_carr.id=order_vh.carrier_id
	LEFT JOIN clients AS order_carr_cl ON order_carr_cl.id=order_carr.client_id
	/*
	LEFT JOIN 
		(SELECT
			ttn_attr_pairs.warehouse_id,
			ttn_attr_pairs.firm_id
		FROM ttn_attr_pairs
		) AS ttn_pairs ON ttn_pairs.firm_id=h.firm_id AND ttn_pairs.warehouse_id=h.warehouse_id
	*/
	;
ALTER TABLE doc_orders_data_for_ext OWNER TO polimerplast;

