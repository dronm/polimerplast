<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQLDOCT.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class DOCOrderDOCTCustSurvey_Model extends ModelSQLDOCT{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_t_tmp_cust_surveys");
		
		$f_login_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"login_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"login_id"
				
		
		));
		$this->addField($f_login_id);

		$f_line_number=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"line_number"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"line_number"
				
		
		));
		$this->addField($f_line_number);

		$f_customer_survey_question_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"customer_survey_question_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"customer_survey_question_id"
				
		
		));
		$this->addField($f_customer_survey_question_id);

		$f_points=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"points"
		,array(
		
			'id'=>"points"
				
		
		));
		$this->addField($f_points);

		$f_answer_comment=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"answer_comment"
		,array(
		
			'id'=>"answer_comment"
				
		
		));
		$this->addField($f_answer_comment);

		
		
		
	}

}
?>
