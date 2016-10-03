<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');

class Client_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("clients");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		'required'=>TRUE,
			'length'=>150,
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_name_full=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name_full"
		,array(
		
			'id'=>"name_full"
				
		
		));
		$this->addField($f_name_full);

		$f_inn=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"inn"
		,array(
		'required'=>TRUE,
			'length'=>12,
			'id'=>"inn"
				
		
		));
		$this->addField($f_inn);

		$f_kpp=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"kpp"
		,array(
		
			'length'=>10,
			'id'=>"kpp"
				
		
		));
		$this->addField($f_kpp);

		$f_addr_reg=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"addr_reg"
		,array(
		'required'=>TRUE,
			'id'=>"addr_reg"
				
		
		));
		$this->addField($f_addr_reg);

		$f_addr_mail=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"addr_mail"
		,array(
		
			'id'=>"addr_mail"
				
		
		));
		$this->addField($f_addr_mail);

		$f_addr_mail_same_as_reg=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"addr_mail_same_as_reg"
		,array(
		
			'id'=>"addr_mail_same_as_reg"
				
		
		));
		$this->addField($f_addr_mail_same_as_reg);

		$f_telephones=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"telephones"
		,array(
		
			'id'=>"telephones"
				
		
		));
		$this->addField($f_telephones);

		$f_ogrn=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ogrn"
		,array(
		
			'length'=>15,
			'id'=>"ogrn"
				
		
		));
		$this->addField($f_ogrn);

		$f_okpo=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"okpo"
		,array(
		
			'length'=>20,
			'id'=>"okpo"
				
		
		));
		$this->addField($f_okpo);

		$f_acc=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"acc"
		,array(
		
			'length'=>20,
			'id'=>"acc"
				
		
		));
		$this->addField($f_acc);

		$f_bank_name=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"bank_name"
		,array(
		
			'id'=>"bank_name"
				
		
		));
		$this->addField($f_bank_name);

		$f_bank_code=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"bank_code"
		,array(
		
			'length'=>9,
			'id'=>"bank_code"
				
		
		));
		$this->addField($f_bank_code);

		$f_bank_acc=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"bank_acc"
		,array(
		
			'length'=>20,
			'id'=>"bank_acc"
				
		
		));
		$this->addField($f_bank_acc);

		$f_registered=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"registered"
		,array(
		
			'defaultValue'=>"false"
		,
			'id'=>"registered"
				
		
		));
		$this->addField($f_registered);

		$f_pay_type=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_type"
		,array(
		
			'id'=>"pay_type"
				
		
		));
		$this->addField($f_pay_type);

		$f_pay_delay_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_delay_days"
		,array(
		
			'id'=>"pay_delay_days"
				
		
		));
		$this->addField($f_pay_delay_days);

		$f_pay_fix_to_dow=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_fix_to_dow"
		,array(
		
			'defaultValue'=>"true"
		,
			'id'=>"pay_fix_to_dow"
				
		
		));
		$this->addField($f_pay_fix_to_dow);

		$f_pay_dow_days=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_dow_days"
		,array(
		
			'id'=>"pay_dow_days"
				
		
		));
		$this->addField($f_pay_dow_days);

		$f_pay_ban_on_debt_days=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_ban_on_debt_days"
		,array(
		
			'defaultValue'=>"true"
		,
			'id'=>"pay_ban_on_debt_days"
				
		
		));
		$this->addField($f_pay_ban_on_debt_days);

		$f_pay_debt_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_debt_days"
		,array(
		
			'defaultValue'=>"5"
		,
			'id'=>"pay_debt_days"
				
		
		));
		$this->addField($f_pay_debt_days);

		$f_pay_ban_on_debt_sum=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_ban_on_debt_sum"
		,array(
		
			'defaultValue'=>"false"
		,
			'id'=>"pay_ban_on_debt_sum"
				
		
		));
		$this->addField($f_pay_ban_on_debt_sum);

		$f_pay_debt_sum=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_debt_sum"
		,array(
		
			'length'=>15,
			'id'=>"pay_debt_sum"
				
		
		));
		$this->addField($f_pay_debt_sum);

		$f_login_allowed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"login_allowed"
		,array(
		
			'defaultValue'=>"true"
		,
			'id'=>"login_allowed"
				
		
		));
		$this->addField($f_login_allowed);

		$f_sms_on_order_change=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sms_on_order_change"
		,array(
		
			'defaultValue'=>"true"
		,
			'id'=>"sms_on_order_change"
				
		
		));
		$this->addField($f_sms_on_order_change);

		$f_email_sert=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"email_sert"
		,array(
		
			'defaultValue'=>"true"
		,
			'id'=>"email_sert"
				
		
		));
		$this->addField($f_email_sert);

		$f_show_delivery_tab=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"show_delivery_tab"
		,array(
		
			'defaultValue'=>"true"
		,
			'id'=>"show_delivery_tab"
				
		
		));
		$this->addField($f_show_delivery_tab);

		$f_ext_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_id"
		,array(
		
			'length'=>36,
			'id'=>"ext_id"
				
		
		));
		$this->addField($f_ext_id);

		$f_client_activity_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_activity_id"
		,array(
		
			'id'=>"client_activity_id"
				
		
		));
		$this->addField($f_client_activity_id);

		$f_def_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"def_firm_id"
		,array(
		
			'id'=>"def_firm_id"
				
		
		));
		$this->addField($f_def_firm_id);

		$f_def_warehouse_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"def_warehouse_id"
		,array(
		
			'id'=>"def_warehouse_id"
				
		
		));
		$this->addField($f_def_warehouse_id);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_name);

		
		
		
	}

}
?>
