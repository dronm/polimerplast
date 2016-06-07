<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');

class ProductWarehouse_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("product_warehouses");
		
		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_warehouse_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouse_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"warehouse_id"
				
		
		));
		$this->addField($f_warehouse_id);

		
		
		
	}

}
?>
