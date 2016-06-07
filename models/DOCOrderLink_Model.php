<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');

class DOCOrderLink_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_links");
		
		$f_main_doc_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"main_doc_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"main_doc_id"
				
		
		));
		$this->addField($f_main_doc_id);

		$f_child_doc_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"child_doc_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"child_doc_id"
				
		
		));
		$this->addField($f_child_doc_id);

		
		
		
	}

}
?>
