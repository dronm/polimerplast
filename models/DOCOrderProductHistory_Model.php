<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class DOCOrderProductHistory_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_products_history");
		
		$f_doc_orders_states_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_orders_states_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"doc_orders_states_id"
				
		
		));
		$this->addField($f_doc_orders_states_id);

		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_fields=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"fields"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"fields"
				
		
		));
		$this->addField($f_fields);

		$f_oper=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"oper"
		,array(
		
			'length'=>8,
			'id'=>"oper"
				
		
		));
		$this->addField($f_oper);

		$f_old_vals=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"old_vals"
		,array(
		
			'id'=>"old_vals"
				
		
		));
		$this->addField($f_old_vals);

		
		
		
	}

}
?>
