<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');

class SMSTemplate_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("sms_templates");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>FALSE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_sms_type=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sms_type"
		,array(
		'required'=>TRUE,
			'primaryKey'=>FALSE,
			'autoInc'=>FALSE,
			'alias'=>"Тип SMS"
		,
			'id'=>"sms_type"
				
		
		));
		$this->addField($f_sms_type);

		$f_template=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"template"
		,array(
		'required'=>TRUE,
			'alias'=>"Шаблон"
		,
			'id'=>"template"
				
		
		));
		$this->addField($f_template);

		$f_comment_text=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"comment_text"
		,array(
		'required'=>TRUE,
			'alias'=>"Комментарий"
		,
			'id'=>"comment_text"
				
		
		));
		$this->addField($f_comment_text);

		$f_fields=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"fields"
		,array(
		'required'=>TRUE,
			'alias'=>"Поля"
		,
			'id'=>"fields"
				
		
		));
		$this->addField($f_fields);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_sms_type);

		
		
		
	}

}
?>
