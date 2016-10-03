-- View: clients_complete

--DROP VIEW clients_complete;


CREATE OR REPLACE VIEW clients_complete AS 
	SELECT 
			cl.id,
			cl.name,
			cl.def_firm_id,
			cl.def_warehouse_id
	FROM clients AS cl
	ORDER BY cl.name;
ALTER TABLE clients_complete OWNER TO polimerplast;

