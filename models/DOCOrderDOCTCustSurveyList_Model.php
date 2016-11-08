<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQLDOCT20.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class DOCOrderDOCTCustSurveyList_Model extends ModelSQLDOCT20{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_t_tmp_cust_surveys_list");
		
		$f_view_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"view_id"
		,array(
		
			'primaryKey'=>TRUE,
			'length'=>32,
			'id'=>"view_id"
				
		
		));
		$this->addField($f_view_id);

		$f_line_number=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"line_number"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"line_number"
				
		
		));
		$this->addField($f_line_number);

		
		
		
	}

}
?>
