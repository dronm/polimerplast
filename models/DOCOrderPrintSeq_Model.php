<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');

class DOCOrderPrintSeq_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_order_prints_seq");
		
		$f_doc_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_id"
		,array(
		
			'id'=>"doc_id"
				
		
		));
		$this->addField($f_doc_id);

		$f_cnt=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cnt"
		,array(
		
			'id'=>"cnt"
				
		
		));
		$this->addField($f_cnt);

		
		
		
	}

}
?>
