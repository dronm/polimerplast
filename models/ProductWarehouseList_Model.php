<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class ProductWarehouseList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("product_warehouses_list");
		
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

		$f_warehouse_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouse_descr"
		,array(
		
			'id'=>"warehouse_descr"
				
		
		));
		$this->addField($f_warehouse_descr);

		
		
		
	}

}
?>
