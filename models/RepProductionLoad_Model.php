<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelReportSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class RepProductionLoad_Model extends ModelReportSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("production_load");
		
		$f_delivery_plan_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_plan_date"
		,array(
		
			'alias'=>"Дата отгрузки"
		,
			'id'=>"delivery_plan_date"
				
		
		));
		$this->addField($f_delivery_plan_date);

		$f_delivery_plan_date_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_plan_date_descr"
		,array(
		
			'alias'=>"Дата отгрузки"
		,
			'id'=>"delivery_plan_date_descr"
				
		
		));
		$this->addField($f_delivery_plan_date_descr);

		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		
			'id'=>"product_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_product_id);

		$f_product_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_descr"
		,array(
		
			'alias'=>"Продукция"
		,
			'id'=>"product_descr"
				
		
		));
		$this->addField($f_product_descr);

		$f_quant=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"quant"
		,array(
		
			'alias'=>"Количество"
		,
			'id'=>"quant"
				
		
		));
		$this->addField($f_quant);

		$f_warehouse_id_list=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouse_id_list"
		,array(
		
			'id'=>"warehouse_id_list"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_warehouse_id_list);

		
		
		
	}

}
?>
