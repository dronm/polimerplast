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
  
  
select login_id
FROM doc_orders_t_tmp_products AS tmp
LEFT JOIN logins ON tmp.login_id=logins.id
WHERE logins.date_time_out IS NOT NULL OR (logins.date_time_out IS NULL AND (now()-logins.date_time_in)>'24 hours')

DROP VIEW deliv_assigned_orders_for_client;
DROP VIEW deliv_current_pos_all;
DROP VIEW doc_orders_data_for_ext;
DROP VIEW doc_orders_ttn;
DROP VIEW email_text_order_remind;
DROP VIEW sms_client_change_time;
DROP VIEW sms_client_on_leave_prod;
DROP VIEW sms_client_remind;
DROP VIEW sms_driver_first_deliv;
DROP VIEW vehicles_dialog;
DROP VIEW doc_orders_print_h;
DROP VIEW doc_orders_dialog;
DROP VIEW vehicles_list;
DROP VIEW vehicles_select_list;

ALTER TABLE vehicles ALTER COLUMN plate TYPE varchar(20);
ALTER TABLE vehicles ALTER COLUMN trailer_plate TYPE varchar(20);
