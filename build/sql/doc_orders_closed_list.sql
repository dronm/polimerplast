-- View: doc_orders_closed_list

--DROP VIEW doc_orders_closed_list;

CREATE OR REPLACE VIEW doc_orders_closed_list AS 
	SELECT *		
	FROM doc_orders_list AS d
	WHERE d.state='closed'::order_states
	OR d.state='canceled'::order_states
	OR d.state='canceled_by_sales_manager'::order_states
	OR d.state='canceled_by_client'::order_states
	AND NOT (d.pay_type='cash' AND d.paid=FALSE)
	;
ALTER TABLE doc_orders_closed_list OWNER TO polimerplast;

