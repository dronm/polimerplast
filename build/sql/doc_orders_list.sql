-- View: doc_orders_list

DROP VIEW doc_orders_new_list;
DROP VIEW doc_orders_current_list;
DROP VIEW doc_orders_current_for_client_list;
DROP VIEW doc_orders_current_for_production_list;
DROP VIEW doc_orders_closed_list;
DROP VIEW doc_orders_list;

CREATE OR REPLACE VIEW doc_orders_list AS 
	SELECT 
		d.id,
		d.number,
		d.date_time,
		date8_time5_descr(d.date_time) AS date_time_descr,

		d.delivery_plan_date,
		date8_descr(d.delivery_plan_date) AS delivery_plan_date_descr,
		CASE
			WHEN
				(d.delivery_plan_date::date<d.delivery_fact_date::date OR d.delivery_plan_date<now()::date)
			THEN 'behind_plan'
			ELSE ''
		END AS behind_plan,
		
		d.product_plan_date,
		date8_descr(d.product_plan_date) AS product_plan_date_descr,
		dest.address AS address_descr,
		
		d.client_id,
		cl.name AS client_descr,
		cl.pay_type,
		
		d.warehouse_id,
		w.name AS warehouse_descr,
		
		d.product_str AS products_descr,
		d.product_ids AS product_ids,
		
		d.total,
		format_money(d.total) AS total_descr,
		
		--ROUND(d.total_quant,0) AS total_quant,
		ROUND(t_prod.quant,3) AS total_quant,
		ROUND(d.total_volume,3) AS total_volume,
		
		(
		SELECT s1.state FROM doc_orders_states AS s1
		WHERE s1.doc_orders_id=d.id
		ORDER BY s1.date_time DESC
		LIMIT 1
		) AS state,
		
		get_order_states_descr(
		(
			SELECT s1.state FROM doc_orders_states AS s1
			WHERE s1.doc_orders_id=d.id
			ORDER BY s1.date_time DESC
			LIMIT 1
		)		
		) AS state_descr,

		d.ext_ship_num,
		d.ext_order_num,
		d.delivery_fact_date AS delivery_fact_date,
		date8_time5_descr(d.delivery_fact_date) AS delivery_fact_date_descr,
		d.client_number,
		d.printed,
		d.cust_surv_date_time AS cust_surv_date_time,
		date8_time5_descr(d.cust_surv_date_time) AS cust_surv_date_time_descr,
		su.name AS submit_user_descr,
		d.paid
		
	FROM doc_orders AS d
	LEFT JOIN client_destinations_list AS dest ON dest.id=d.deliv_destination_id
	LEFT JOIN (
		SELECT
			t.doc_id,
			SUM(t.quant_base_measure_unit) AS quant		
		FROM doc_orders_t_products t
		GROUP BY t.doc_id) AS t_prod
	ON t_prod.doc_id=d.id
	LEFT JOIN clients AS cl ON cl.id=d.client_id
	LEFT JOIN warehouses AS w ON w.id=d.warehouse_id
	LEFT JOIN users AS su ON su.id=d.submit_user_id
	ORDER BY d.date_time,d.number;
ALTER TABLE doc_orders_list OWNER TO polimerplast;
