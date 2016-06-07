<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class SertType_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("sert_types");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'length'=>50,
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_xslt_pattern=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"xslt_pattern"
		,array(
		
			'length'=>50,
			'id'=>"xslt_pattern"
				
		
		));
		$this->addField($f_xslt_pattern);

		
		
		
	}

}
?>
