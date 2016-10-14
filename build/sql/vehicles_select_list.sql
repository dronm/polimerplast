-- View: vehicles_select_list


--DROP VIEW doc_orders_dialog;
--DROP VIEW doc_orders_print_h;
--DROP VIEW vehicles_select_list;

CREATE OR REPLACE VIEW vehicles_select_list AS 
	SELECT 
		v.id,
		v.vol,
		v.plate,
		v.load_weight_t,
		v.model||' '||v.vol::text || 'м3/' ||v.load_weight_t::text||'т' AS descr
		
	FROM vehicles AS v
	ORDER BY v.model,v.vol;
ALTER TABLE vehicles_select_list OWNER TO polimerplast;

