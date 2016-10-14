<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class TemplateParam_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("template_params");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_template=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"template"
		,array(
		
			'length'=>100,
			'id'=>"template"
				
		
		));
		$this->addField($f_template);

		$f_user_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"user_id"
		,array(
		
			'alias'=>"Пользователь"
		,
			'id'=>"user_id"
				
		
		));
		$this->addField($f_user_id);

		$f_param=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"param"
		,array(
		
			'length'=>100,
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
