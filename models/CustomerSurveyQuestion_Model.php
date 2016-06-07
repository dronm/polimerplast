<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class CustomerSurveyQuestion_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("customer_survey_questions");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_question=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"question"
		,array(
		'required'=>TRUE,
			'id'=>"question"
				
		
		));
		$this->addField($f_question);

		$f_max_points=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"max_points"
		,array(
		'required'=>TRUE,
			'id'=>"max_points"
				
		
		));
		$this->addField($f_max_points);

		$f_in_use=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"in_use"
		,array(
		
			'id'=>"in_use"
				
		
		));
		$this->addField($f_in_use);

		
		
		
	}

}
?>
