INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('order','На организацию [client] выставлен счет.','Счет на оплату','',array['client','user']);
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('reset_pwd','Пользователю [user] изменен пароль. Новый пароль [pwd]','Новый пароль','',array['user','pwd'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('new_account','Создана новая учетная запись для клиента [client]. Параметры учетной записи: логин: [user] пароль: [pwd]','Новая учетная запись','',array['user','pwd','client'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('order_changed','Изменен заказ №[number] клиента [client]. Параметры учетной записи: логин: [user] пароль: [pwd]','Новая учетная запись','',array['number','client'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('shipment','Отгружен заказ №[number] клиента [client].','Отгрузка','',array['number','client'])
INSERT INTO email_templates (email_type,template,mes_subject,comment_text,fields) VALUES ('balance_to_client','Акт сверки для клиента: [client].','Акт сверки','',array['client'])


	SELECT
		sub.product_id,
		sub.price,
		sub.mes_length,
		sub.mes_width,
		sub.mes_height,
		sub.measure_unit_id,
		sub.pack_exists,
		sub.pack_in_price,
		sub.price_no_deliv,
		sub.price_edit,

		sum(sub.quant) AS quant,
		sum(sub.quant_base_measure_unit) AS quant_base_measure_unit,
		sum(sub.volume) AS volume,
		sum(sub.weight) AS weight,
		sum(sub.total) AS total,
		sum(sub.total_pack) AS total_pack
		
	FROM (
		(SELECT
			p_orig.product_id,
			p_orig.price,
			p_orig.mes_length,
			p_orig.mes_width,
			p_orig.mes_height,
			p_orig.measure_unit_id,
			p_orig.pack_exists,
			p_orig.pack_in_price,
			p_orig.price_no_deliv,
			p_orig.price_edit,
		
			p_orig.quant,
			p_orig.quant_base_measure_unit,
			p_orig.volume,
			p_orig.weight,
			p_orig.total,
			p_orig.total_pack
		FROM doc_orders_t_products AS p_orig
		WHERE p_orig.doc_id=344)

		UNION ALL

		(SELECT 
			p_new.product_id,
			p_new.price,
			p_new.mes_length,
			p_new.mes_width,
			p_new.mes_height,
			p_new.measure_unit_id,
			p_new.pack_exists,
			p_new.pack_in_price,
			p_new.price_no_deliv,
			p_new.price_edit,
			-p_new.quant,
			-p_new.quant_base_measure_unit,
			-p_new.volume,
			-p_new.weight,
			-p_new.total,
			-p_new.total_pack
		FROM doc_orders_t_products AS p_new
		WHERE p_new.doc_id=293)
	) AS sub
	GROUP BY
		sub.product_id,
		sub.price,
		sub.mes_length,
		sub.mes_width,
		sub.mes_height,
		sub.measure_unit_id,
		sub.pack_exists,
		sub.pack_in_price,
		sub.price_no_deliv,
		sub.price_edit
	
	