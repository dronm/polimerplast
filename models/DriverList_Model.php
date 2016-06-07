<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class DriverList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("drivers_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_drive_perm=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"drive_perm"
		,array(
		
			'id'=>"drive_perm"
				
		
		));
		$this->addField($f_drive_perm);

		$f_cel_phone=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cel_phone"
		,array(
		
			'id'=>"cel_phone"
				
		
		));
		$this->addField($f_cel_phone);

		$f_ext_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_id"
		,array(
		
			'id'=>"ext_id"
				
		
		));
		$this->addField($f_ext_id);

		$f_match_1c=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"match_1c"
		,array(
		
			'id'=>"match_1c"
				
		
		));
		$this->addField($f_match_1c);

		
		
		
	}

}
?>
