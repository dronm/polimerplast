<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');

class PayOrderList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("pay_orders_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'alias'=>"Код заявки"
		,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'alias'=>"Клиент код"
		,
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_number=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"number"
		,array(
		
			'alias'=>"Номер заявки"
		,
			'id'=>"number"
				
		
		));
		$this->addField($f_number);

		$f_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time"
		,array(
		
			'alias'=>"Дата заявки"
		,
			'id'=>"date_time"
				
		
		));
		$this->addField($f_date_time);

		$f_date_time_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time_descr"
		,array(
		
			'alias'=>"Дата заявки"
		,
			'id'=>"date_time_descr"
				
		
		));
		$this->addField($f_date_time_descr);

		$f_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total"
		,array(
		
			'alias'=>"Сумма"
		,
			'length'=>15,
			'id'=>"total"
				
		
		));
		$this->addField($f_total);

		$f_total_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_descr"
		,array(
		
			'alias'=>"Сумма"
		,
			'id'=>"total_descr"
				
		
		));
		$this->addField($f_total_descr);

		$f_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		
			'alias'=>"Организация код"
		,
			'id'=>"firm_id"
				
		
		));
		$this->addField($f_firm_id);

		$f_firm_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_descr"
		,array(
		
			'alias'=>"Организация"
		,
			'id'=>"firm_descr"
				
		
		));
		$this->addField($f_firm_descr);

		$f_state=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"state"
		,array(
		
			'alias'=>"Статус код"
		,
			'id'=>"state"
				
		
		));
		$this->addField($f_state);

		$f_state_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"state_descr"
		,array(
		
			'alias'=>"Статус"
		,
			'id'=>"state_descr"
				
		
		));
		$this->addField($f_state_descr);

		
		
		
	}

}
?>
