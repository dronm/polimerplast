-- View: doc_orders_ttn

--DROP VIEW doc_orders_ttn;

CREATE OR REPLACE VIEW doc_orders_ttn AS 
	SELECT
		h.id AS doc_id,
		h.ext_ship_id,
		''::text AS deliv_time,
		''::text AS deliv_kind,
		vh.model AS veh_model,
		vh.trailer_model AS veh_trailer_model,
		vh.plate AS vh_plate,
		vh.trailer_plate AS vh_trailer_plate,
		wh.address AS wareh_descr,
		
		--dest.address AS dest_descr,
		CASE
			WHEN coalesce(h.destination_to_ttn,FALSE) THEN
				dest.address
			ELSE cl.addr_reg
		END AS dest_descr,
		
		dr.name AS driver_name,
		cr.name AS carrier_descr,
		dr.drive_perm AS drive_perm,
		dr.ext_id AS driver_ext_id,
		coalesce(frm.name,'')||' '||coalesce(frm.sert_header,'') AS firm_descr
	FROM doc_orders AS h
	LEFT JOIN deliveries AS dlv ON dlv.doc_order_id=h.id
	LEFT JOIN firms AS frm ON frm.id=h.firm_id
	LEFT JOIN vehicles AS vh ON dlv.vehicle_id=vh.id
	LEFT JOIN carriers AS cr ON cr.id=vh.carrier_id
	LEFT JOIN drivers AS dr ON vh.driver_id=dr.id
	LEFT JOIN warehouses AS wh ON wh.id=h.warehouse_id
	LEFT JOIN clients AS cl ON cl.id=h.client_id
	LEFT JOIN client_destinations_list AS dest ON dest.id=h.deliv_destination_id
	;
ALTER TABLE doc_orders_ttn OWNER TO polimerplast;

