<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class HolidayList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("holidays_list");
		
		$f_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"date"
				
		
		));
		$this->addField($f_date);

		$f_date_str=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_str"
		,array(
		
			'id'=>"date_str"
				
		
		));
		$this->addField($f_date_str);

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
