ALTER TABLE public.clients ADD COLUMN comment_text text;
	DROP INDEX IF EXISTS clients_name_index;
	CREATE UNIQUE INDEX clients_name_index
	ON clients(lower(name));
	DROP INDEX IF EXISTS clients_registered_index;
	CREATE INDEX clients_registered_index
	ON clients(registered);
--Refrerece type
CREATE OR REPLACE FUNCTION client_activity_types_ref(client_activity_types)
  RETURNS json AS
$$
	SELECT json_build_object(
		'keys',json_build_object(
			),	
		'descr',$1.,
		'dataType','client_activity_types'
	);
$$
  LANGUAGE sql VOLATILE COST 100;
ALTER FUNCTION client_activity_types_ref(client_activity_types) OWNER TO polimerplast;	
--Refrerece type
CREATE OR REPLACE FUNCTION firms_ref(firms)
  RETURNS json AS
$$
	SELECT json_build_object(
		'keys',json_build_object(
			'id',$1.id    
			),	
		'descr',$1.name,
		'dataType','firms'
	);
$$
  LANGUAGE sql VOLATILE COST 100;
ALTER FUNCTION firms_ref(firms) OWNER TO polimerplast;	
--Refrerece type
CREATE OR REPLACE FUNCTION warehouses_ref(warehouses)
  RETURNS json AS
$$
	SELECT json_build_object(
		'keys',json_build_object(
			'id',$1.id    
			),	
		'descr',$1.name,
		'dataType','warehouses'
	);
$$
  LANGUAGE sql VOLATILE COST 100;
ALTER FUNCTION warehouses_ref(warehouses) OWNER TO polimerplast;
-- ************* virtual table ****************
-- View: public.client_dialog
-- DROP VIEW public.client_dialog;
CREATE OR REPLACE VIEW public.client_dialog AS 
 SELECT cl.id,
    cl.name,
    cl.inn,
    cl.kpp,
    cl.addr_reg,
    cl.addr_mail,
    cl.addr_mail_same_as_reg,
    cl.telephones,
    cl.ogrn,
    cl.okpo,
    cl.acc,
    cl.bank_name,
    cl.bank_code,
    cl.bank_acc,
    cl.registered,
    cl.pay_type,
    cl.pay_delay_days,
    cl.pay_order,
    cl.pay_order_type,
    cl.pay_fix_to_dow,
    cl.pay_dow,
    cl.pay_ban_on_debt_days,
    cl.pay_debt_days,
    cl.pay_ban_on_debt_sum,
    cl.pay_debt_sum,
    cl.login_allowed,
    cl.sms_on_order_change,
    cl.email_sert,
    cl.show_delivery_tab,
    cl.ext_id,
    cl.name_full,
    cl.pay_dow_days,
    cl.client_activity_id,
    cl.def_firm_id,
    cl.def_warehouse_id,
    clac.name AS client_activity_descr,
    f.name AS def_firm_descr,
    w.name AS def_warehouse_descr,
    cl.deleted,
    cl.email,
    cl.deliv_add_cost_to_product,
    cl.is_supplier,
    cl.is_carrier
   FROM clients cl
     LEFT JOIN client_activities clac ON clac.id = cl.client_activity_id
     LEFT JOIN firms f ON f.id = cl.def_firm_id
     LEFT JOIN warehouses w ON w.id = cl.def_warehouse_id;
ALTER TABLE public.client_dialog
  OWNER TO polimerplast;