SELECT
	d.firm_id,
	d.client_id,
	
	(
	SELECT s1.state FROM doc_orders_states AS s1
	WHERE s1.doc_orders_id=d.id
	ORDER BY s1.date_time DESC
	LIMIT 1
	) AS state,

	d.total,

	coalesce(
		(SELECT t.debt_total FROM client_debts t WHERE t.client_id=d.client_id AND t.firm_id=d.firm_id LIMIT 1),0
	) AS debt_total,

	CASE
		WHEN (SELECT s1.state FROM doc_orders_states AS s1
			WHERE s1.doc_orders_id=d.id
			ORDER BY s1.date_time DESC
			LIMIT 1
		) IN ('producing','waiting_for_payment') THEN FALSE
		ELSE TRUE
	END AS shipped
	
FROM doc_orders AS d
LEFT JOIN clients AS cl ON cl.id=d.client_id
WHERE 
	(cl.pay_type='cash' AND d.paid=FALSE)
	OR
	(
	(
	SELECT s1.state FROM doc_orders_states AS s1
	WHERE s1.doc_orders_id=d.id
	ORDER BY s1.date_time DESC
	LIMIT 1
	) NOT IN ('new',
		'waiting_for_client',
		'waiting_for_us',
		'shipped',
		'loading',
		'on_way',
		'unloading',
		'closed',
		'canceled_by_sales_manager',
		'canceled_by_client'					
		)
	)
ORDER BY d.firm_id,d.client_id,d.delivery_fact_date
