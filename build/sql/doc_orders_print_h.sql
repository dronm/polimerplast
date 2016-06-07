-- View: doc_orders_print_h

--DROP VIEW doc_orders_print_h;

CREATE OR REPLACE VIEW doc_orders_print_h AS 
	SELECT
		d.*,		
		date8_descr(
			coalesce(h.delivery_fact_date::date,
				h.delivery_plan_date::date)
		) AS delivery_fact_date_descr,
		
		date8_descr(
			(SELECT st.date_time::date FROM doc_orders_states st WHERE st.doc_orders_id=d.id AND st.state='producing')
		) AS to_production_date_descr,
		
		(SELECT sum(t.quant_base_measure_unit)::numeric(19,3)
		FROM doc_orders_t_products t
		WHERE t.doc_id=d.id
		) AS total_quant,
				
		(coalesce(d.deliv_responsable_tel,coalesce(d.tel,d.client_user_cel_phone)))::text AS tels
	FROM doc_orders_dialog AS d
	LEFT JOIN clients AS cl ON cl.id=d.client_id
	LEFT JOIN doc_orders AS h ON h.id=d.id
	;
ALTER TABLE doc_orders_print_h OWNER TO polimerplast;

