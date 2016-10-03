-- View: client_dialog

--DROP VIEW client_dialog;

CREATE OR REPLACE VIEW client_dialog AS 
	SELECT
		cl.*,
		clac.name AS client_activity_descr,
		f.name AS def_firm_descr,
		w.name AS def_warehouse_descr
		/*,
		cld.debt_total,
		cld.def_debt
		*/
		
	FROM clients AS cl
	LEFT JOIN client_activities clac ON clac.id=cl.client_activity_id
	LEFT JOIN firms f ON f.id=cl.def_firm_id
	LEFT JOIN warehouses w ON w.id=cl.def_warehouse_id
	/*
	LEFT JOIN (
		SELECT
			t.client_id,
			sum(t.debt_total) AS debt_total,
			sum(t.def_debt) AS def_debt
		FROM client_debts AS t
		GROUP BY t.client_id
		) cld ON cld.client_id=cl.id
	*/
	;

ALTER TABLE client_dialog OWNER TO polimerplast;

