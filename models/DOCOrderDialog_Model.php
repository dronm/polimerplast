<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class DOCOrderDialog_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_dialog");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_def_debt=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"def_debt"
		,array(
		
			'length'=>19,
			'id'=>"def_debt"
				
		
		));
		$this->addField($f_def_debt);

		$f_debt_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"debt_total"
		,array(
		
			'length'=>19,
			'id'=>"debt_total"
				
		
		));
		$this->addField($f_debt_total);

		
		
		
	}

}
?>
