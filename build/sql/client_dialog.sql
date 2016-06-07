-- View: client_dialog

--DROP VIEW client_dialog;

CREATE OR REPLACE VIEW client_dialog AS 
	SELECT
		cl.*,
		clac.name AS client_activity_descr
	FROM clients AS cl
	LEFT JOIN client_activities clac ON clac.id=cl.client_activity_id;

ALTER TABLE client_dialog OWNER TO polimerplast;

