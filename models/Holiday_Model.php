<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class Holiday_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("holidays");
		
		$f_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"date"
				
		
		));
		$this->addField($f_date);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'length'=>50,
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		
		
		
	}

}
?>
