/*
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('order','На организацию [client] выставлен счет.','Счет на оплату','',array['client','user']);
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('reset_pwd','Пользователю [user] изменен пароль. Новый пароль [pwd]','Новый пароль','',array['user','pwd'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('new_account','Создана новая учетная запись для клиента [client]. Параметры учетной записи: логин: [user] пароль: [pwd]','Новая учетная запись','',array['user','pwd','client'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('order_changed','Изменен заказ №[number] клиента [client]. Параметры учетной записи: логин: [user] пароль: [pwd]','Новая учетная запись','',array['number','client'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('shipment','Отгружен заказ №[number] клиента [client].','Отгрузка','',array['number','client'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('balance_to_client','Акт сверки для клиента: [client].','Акт сверки','',array['client'])
*/

--select Find_SRID('public','client_destinations','zone_center')

/*
DROP view client_destinations_dialog;
ALTER TABLE client_destinations
 ALTER COLUMN zone_center TYPE geometry(Point,4326) 
  USING ST_SetSRID(zone_center,4326);
  */
  
  
DROP VIEW doc_orders_print_h;
DROP VIEW doc_orders_dialog;

DROP VIEW doc_orders_new_list;
DROP VIEW doc_orders_current_list;
DROP VIEW doc_orders_current_for_client_list;
DROP VIEW doc_orders_current_for_production_list;
DROP VIEW doc_orders_closed_list;

DROP VIEW doc_orders_all_list;
DROP VIEW doc_orders_all_for_production_list;
DROP VIEW doc_orders_all_for_client_list;

DROP VIEW doc_orders_list;

DROP VIEW sms_client_on_produced;
DROP VIEW sms_client_remind;
DROP VIEW sms_client_on_deliv;
DROP VIEW sms_client_on_leave_prod;
DROP VIEW public.acc_pko_inf;
DROP VIEW doc_orders_data_for_ext;
DROP VIEW email_text_order_remind;
DROP VIEW sms_driver_first_deliv;
DROP VIEW sms_driver_new_deliv;
DROP VIEW sms_undeliv_orders_remind;
DROP VIEW rep_sales;
DROP VIEW regl_undeliv_orders_remind;
DROP VIEW pay_orders_list;
DROP VIEW doc_orders_shipment_dialog;
DROP VIEW doc_orders_cust_survey_dialog;
DROP VIEW deliv_unassigned_orders_list;
DROP VIEW deliv_assigned_orders_for_client;

ALTER TABLE doc_orders ALTER COLUMN "number" TYPE varchar(10)


SELECT
	d.firm_id,
	d.client_id,
	
	(SELECT s1.state FROM doc_orders_states AS s1
	WHERE s1.doc_orders_id=d.id
	ORDER BY s1.date_time DESC
	LIMIT 1
	) AS state,

	coalesce((SELECT t.debt_total FROM client_debts t WHERE t.client_id=d.client_id AND t.firm_id=d.firm_id LIMIT 1),0)
	AS debt_total,
	
	--ОТГРУЖЕНО, НО НЕ ОПЛАЧЕНО 
	CASE
		WHEN (SELECT s1.state FROM doc_orders_states AS s1
			WHERE s1.doc_orders_id=d.id
			ORDER BY s1.date_time DESC
			LIMIT 1
		) NOT IN ('producing','waiting_for_payment')
		AND coalesce((SELECT t.debt_total FROM client_debts t WHERE t.client_id=d.client_id AND t.firm_id=d.firm_id LIMIT 1),0)>0
		THEN
			-(d.total+
				CASE
					WHEN d.deliv_type='by_supplier'::delivery_types
						AND coalesce(d.deliv_add_cost_to_product,FALSE)=FALSE THEN
							d.deliv_total
					ELSE 0
				END+
				coalesce(d.total_pack,0)
	
			)		
		ELSE 0::numeric
	END AS shipped_not_payed,
	
	--НЕ ОТГРУЖЕНО, НО ОПЛАЧЕНО 
	CASE
		WHEN (SELECT s1.state FROM doc_orders_states AS s1
			WHERE s1.doc_orders_id=d.id
			ORDER BY s1.date_time DESC
			LIMIT 1
		) IN ('producing','waiting_for_payment')
		AND coalesce((SELECT t.debt_total FROM client_debts t WHERE t.client_id=d.client_id AND t.firm_id=d.firm_id LIMIT 1),0)<=0
		THEN
			(d.total+
				CASE
					WHEN d.deliv_type='by_supplier'::delivery_types
						AND coalesce(d.deliv_add_cost_to_product,FALSE)=FALSE THEN
							d.deliv_total
					ELSE 0
				END+
				coalesce(d.total_pack,0)
	
			)		
		ELSE 0::numeric
	END AS not_shipped_payed,
	
	--РАЗНИЦА (ОТГРУЖЕНО, НО НЕ ОПЛАЧЕНО) - (НЕ ОТГРУЖЕНО, НО ОПЛАЧЕНО)
	(--ОТГРУЖЕНО, НО НЕ ОПЛАЧЕНО 
	CASE
		WHEN (SELECT s1.state FROM doc_orders_states AS s1
			WHERE s1.doc_orders_id=d.id
			ORDER BY s1.date_time DESC
			LIMIT 1
		) NOT IN ('producing','waiting_for_payment')
		AND coalesce((SELECT t.debt_total FROM client_debts t WHERE t.client_id=d.client_id AND t.firm_id=d.firm_id LIMIT 1),0)>0
		THEN
			-(d.total+
				CASE
					WHEN d.deliv_type='by_supplier'::delivery_types
						AND coalesce(d.deliv_add_cost_to_product,FALSE)=FALSE THEN
							d.deliv_total
					ELSE 0
				END+
				coalesce(d.total_pack,0)
	
			)		
		ELSE 0::numeric
	END)
	-
	(--НЕ ОТГРУЖЕНО, НО ОПЛАЧЕНО 
	CASE
		WHEN (SELECT s1.state FROM doc_orders_states AS s1
			WHERE s1.doc_orders_id=d.id
			ORDER BY s1.date_time DESC
			LIMIT 1
		) IN ('producing','waiting_for_payment')
		AND coalesce((SELECT t.debt_total FROM client_debts t WHERE t.client_id=d.client_id AND t.firm_id=d.firm_id LIMIT 1),0)<=0
		THEN
			(d.total+
				CASE
					WHEN d.deliv_type='by_supplier'::delivery_types
						AND coalesce(d.deliv_add_cost_to_product,FALSE)=FALSE THEN
							d.deliv_total
					ELSE 0
				END+
				coalesce(d.total_pack,0)
	
			)		
		ELSE 0::numeric
	END
	)	
	AS balance
	
FROM doc_orders AS d
LEFT JOIN clients AS cl ON cl.id=d.client_id
WHERE 
	(cl.pay_type='cash' AND d.paid=FALSE)
	OR
	((SELECT s1.state FROM doc_orders_states AS s1
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
