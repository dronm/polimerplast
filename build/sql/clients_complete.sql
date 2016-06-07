-- View: clients_complete

--DROP VIEW clients_complete;


CREATE OR REPLACE VIEW clients_complete AS 
	SELECT 
			cl.id,
			cl.name
	FROM clients AS cl
	ORDER BY cl.name
ALTER TABLE clients_complete OWNER TO polimerplast;

