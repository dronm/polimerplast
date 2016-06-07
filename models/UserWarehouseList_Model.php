<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class UserWarehouseList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("user_warehouses_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_id);

		$f_user_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"user_id"
		,array(
		
			'id'=>"user_id"
				
		
		));
		$this->addField($f_user_id);

		$f_warehouse_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouse_id"
		,array(
		
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
