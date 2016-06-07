<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class ClientPaySchedule_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_pay_schedules");
		
		$f_doc_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"doc_id"
				
		
		));
		$this->addField($f_doc_id);

		$f_pay_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pay_date"
		,array(
		'required'=>TRUE,
			'id'=>"pay_date"
				
		
		));
		$this->addField($f_pay_date);

		$f_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		'required'=>TRUE,
			'id'=>"firm_id"
				
		
		));
		$this->addField($f_firm_id);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		'required'=>TRUE,
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total"
		,array(
		
			'length'=>19,
			'id'=>"total"
				
		
		));
		$this->addField($f_total);

		
		
		
	}

}
?>
