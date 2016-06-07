<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class ClientDebtPeriodList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_debt_periods_list");
		
		$f_days_from=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"days_from"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"days_from"
				
		
		));
		$this->addField($f_days_from);

		$f_days_to=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"days_to"
		,array(
		
			'id'=>"days_to"
				
		
		));
		$this->addField($f_days_to);

		$f_days_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"days_descr"
		,array(
		
			'id'=>"days_descr"
				
		
		));
		$this->addField($f_days_descr);

		
		
		
	}

}
?>
