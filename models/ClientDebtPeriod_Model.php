<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');

class ClientDebtPeriod_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_debt_periods");
		
		$f_days_from=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"days_from"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"days_from"
				
		
		));
		$this->addField($f_days_from);

		$f_days_to=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"days_to"
		,array(
		'required'=>TRUE,
			'id'=>"days_to"
				
		
		));
		$this->addField($f_days_to);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_days_from);

		
		
		
	}

}
?>
