<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');

class OrderChange_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("order_changes");
		
		$f_doc_order_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_order_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"doc_order_id"
				
		
		));
		$this->addField($f_doc_order_id);

		$f_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"date_time"
				
		
		));
		$this->addField($f_date_time);

		$f_field=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"field"
		,array(
		
			'primaryKey'=>TRUE,
			'length'=>50,
			'id'=>"field"
				
		
		));
		$this->addField($f_field);

		$f_old_value=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"old_value"
		,array(
		
			'id'=>"old_value"
				
		
		));
		$this->addField($f_old_value);

		
		
		
	}

}
?>
