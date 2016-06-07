<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class DOCOrderProdPassport_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_products_passports");
		
		$f_doc_order_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_order_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"doc_order_id"
				
		
		));
		$this->addField($f_doc_order_id);

		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_content=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"content"
		,array(
		
			'id'=>"content"
				
		
		));
		$this->addField($f_content);

		
		
		
	}

}
?>
