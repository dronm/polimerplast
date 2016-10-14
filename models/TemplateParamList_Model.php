<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class TemplateParamList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'id'=>"id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_id);

		$f_template=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"template"
		,array(
		
			'id'=>"template"
				
		
		));
		$this->addField($f_template);

		$f_param=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"param"
		,array(
		
			'id'=>"param"
				
		
		));
		$this->addField($f_param);

		$f_val=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"val"
		,array(
		
			'id'=>"val"
				
		
		));
		$this->addField($f_val);

		
		
		
	}

}
?>
