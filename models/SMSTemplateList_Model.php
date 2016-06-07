<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class SMSTemplateList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("sms_templates_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_sms_type=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sms_type"
		,array(
		
			'id'=>"sms_type"
				
		
		));
		$this->addField($f_sms_type);

		$f_sms_type_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sms_type_descr"
		,array(
		
			'id'=>"sms_type_descr"
				
		
		));
		$this->addField($f_sms_type_descr);

		$f_template=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"template"
		,array(
		
			'id'=>"template"
				
		
		));
		$this->addField($f_template);

		$f_fields=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"fields"
		,array(
		
			'id'=>"fields"
				
		
		));
		$this->addField($f_fields);

		
		
		
	}

}
?>
