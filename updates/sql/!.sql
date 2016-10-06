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
  
  
					ALTER TYPE role_types ADD VALUE 'representative';
					
	CREATE OR REPLACE FUNCTION enum_role_types_descr(role_types)
	RETURNS varchar AS $$
		SELECT
		CASE $1
			
			WHEN 'admin'::role_types THEN 'Администратор'
			
			WHEN 'client'::role_types THEN 'Клиент'
			
			WHEN 'sales_manager'::role_types THEN 'Отдел продаж'
			
			WHEN 'production'::role_types THEN 'Производственный отдел'
			
			WHEN 'marketing'::role_types THEN 'Маркетолог'
			
			WHEN 'boss'::role_types THEN 'Руководитель'
			
			WHEN 'representative'::role_types THEN 'Представитель'
			
			ELSE '---'
		END;		
	$$ LANGUAGE sql;	
	ALTER FUNCTION enum_role_types_descr(role_types) OWNER TO polimerplast;
	
	--list view
	CREATE OR REPLACE VIEW enum_list_role_types AS
	
		SELECT 'admin'::role_types AS id, enum_role_types_descr('admin'::role_types) AS descr
	
		UNION ALL
		
		SELECT 'client'::role_types AS id, enum_role_types_descr('client'::role_types) AS descr
	
		UNION ALL
		
		SELECT 'sales_manager'::role_types AS id, enum_role_types_descr('sales_manager'::role_types) AS descr
	
		UNION ALL
		
		SELECT 'production'::role_types AS id, enum_role_types_descr('production'::role_types) AS descr
	
		UNION ALL
		
		SELECT 'marketing'::role_types AS id, enum_role_types_descr('marketing'::role_types) AS descr
	
		UNION ALL
		
		SELECT 'boss'::role_types AS id, enum_role_types_descr('boss'::role_types) AS descr
	
		UNION ALL
		
		SELECT 'representative'::role_types AS id, enum_role_types_descr('representative'::role_types) AS descr
	;
	ALTER VIEW enum_list_role_types OWNER TO polimerplast;
	

