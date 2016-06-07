<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class ClientPayScheduleList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_pay_schedule_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_id);

		$f_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date"
		,array(
		
			'id'=>"date"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_date);

		$f_date_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_descr"
		,array(
		
			'alias'=>"Дата"
		,
			'id'=>"date_descr"
				
		
		));
		$this->addField($f_date_descr);

		$f_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		
			'id'=>"firm_id"
		,
			'sysCol'=>TRUE
				
		
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

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'id'=>"client_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_client_id);

		$f_client_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_descr"
		,array(
		
			'alias'=>"Клиент"
		,
			'id'=>"client_descr"
				
		
		));
		$this->addField($f_client_descr);

		
		
		
	}

}
?>
