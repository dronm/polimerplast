<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class DOCOrderHeadHistory_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_head_history");
		
		$f_doc_orders_states_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_orders_states_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"doc_orders_states_id"
				
		
		));
		$this->addField($f_doc_orders_states_id);

		$f_field=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"field"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"field"
				
		
		));
		$this->addField($f_field);

		$f_old_val=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"old_val"
		,array(
		
			'id'=>"old_val"
				
		
		));
		$this->addField($f_old_val);

		
		
		
	}

}
?>
