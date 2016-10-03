-- View: clients_complete

--DROP VIEW clients_complete;


CREATE OR REPLACE VIEW clients_complete AS 
	SELECT 
		cl.id,
		cl.name,
		cl.def_firm_id,
		cl.def_warehouse_id,
		
		cld.debt_total,
		cld.def_debt
		
	FROM clients AS cl
	
	LEFT JOIN (
		SELECT
			t.client_id,
			sum(t.debt_total) AS debt_total,
			sum(t.def_debt) AS def_debt
		FROM client_debts AS t
		GROUP BY t.client_id
		) cld ON cld.client_id=cl.id
	
	ORDER BY cl.name;
ALTER TABLE clients_complete OWNER TO polimerplast;

