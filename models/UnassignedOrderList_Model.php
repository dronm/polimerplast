<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');

class UnassignedOrderList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_unassigned_orders_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'id'=>"id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_id);

		$f_number=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"number"
		,array(
		
			'alias'=>"Номер"
		,
			'id'=>"number"
				
		
		));
		$this->addField($f_number);

		$f_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time"
		,array(
		
			'alias'=>"Дата"
		,
			'id'=>"date_time"
				
		
		));
		$this->addField($f_date_time);

		$f_delivery_plan_date=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_plan_date"
		,array(
		
			'id'=>"delivery_plan_date"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_delivery_plan_date);

		$f_delivery_plan_date_descr=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_plan_date_descr"
		,array(
		
			'alias'=>"Плановая дата выпуска"
		,
			'id'=>"delivery_plan_date_descr"
				
		
		));
		$this->addField($f_delivery_plan_date_descr);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'id'=>"client_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_client_id);

		$f_client_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_descr"
		,array(
		
			'alias'=>"Клиент"
		,
			'id'=>"client_descr"
				
		
		));
		$this->addField($f_client_descr);

		$f_warehouse_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouse_descr"
		,array(
		
			'alias'=>"Склад"
		,
			'id'=>"warehouse_descr"
				
		
		));
		$this->addField($f_warehouse_descr);

		$f_client_dest_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_dest_descr"
		,array(
		
			'alias'=>"Адрес"
		,
			'id'=>"client_dest_descr"
				
		
		));
		$this->addField($f_client_dest_descr);

		$f_product_str=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_str"
		,array(
		
			'alias'=>"Продукция"
		,
			'id'=>"product_str"
				
		
		));
		$this->addField($f_product_str);

		$f_total_volume=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_volume"
		,array(
		
			'alias'=>"Объем"
		,
			'id'=>"total_volume"
				
		
		));
		$this->addField($f_total_volume);

		$f_total_weight=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_weight"
		,array(
		
			'alias'=>"Вес"
		,
			'id'=>"total_weight"
				
		
		));
		$this->addField($f_total_weight);

		
		
		
	}

}
?>
