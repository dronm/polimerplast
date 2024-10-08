-- View: sms_driver_new_deliv

--DROP VIEW sms_driver_new_deliv;

CREATE OR REPLACE VIEW sms_driver_new_deliv AS 
	SELECT DISTINCT ON (drv.id)
		dlv.doc_order_id,
		dlv.deliv_date,
		drv.cel_phone,
		sms_templates_text(
			ARRAY[
				ROW('time',(dh.h_from||'-'||dh.h_to)::text)::template_value,
				ROW('client',cl.name_full::text)::template_value,
				ROW('dest',dest.address::text)::template_value,				
				ROW('order',o.number::text)::template_value,
				ROW('vm',o.total_volume::text)::template_value,
				ROW('wt',o.total_weight::text)::template_value
			],
			(SELECT t.template FROM sms_templates t
			WHERE t.sms_type='driver_first_deliv'::sms_types)
		) AS message
		
	FROM deliveries dlv
	LEFT JOIN vehicles v ON v.id=dlv.vehicle_id
	LEFT JOIN drivers drv ON drv.id=v.driver_id
	LEFT JOIN delivery_hours AS dh ON dh.id= dlv.delivery_hour_id
	LEFT JOIN doc_orders o ON o.id=dlv.doc_order_id	
	LEFT JOIN clients cl ON cl.id=o.client_id	
	LEFT JOIN warehouses w ON w.id=o.warehouse_id	
	LEFT JOIN client_destinations_list dest ON
		dest.id=o.deliv_destination_id
	WHERE v.employed
		AND tel_cel_correct(drv.cel_phone::text)
	ORDER BY drv.id,dh.h_from
	;
ALTER TABLE sms_driver_new_deliv OWNER TO polimerplast;
