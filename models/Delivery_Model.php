<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class Delivery_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliveries");
		
		$f_vehicle_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vehicle_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"vehicle_id"
				
		
		));
		$this->addField($f_vehicle_id);

		$f_doc_order_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_order_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"doc_order_id"
				
		
		));
		$this->addField($f_doc_order_id);

		$f_delivery_hour_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_hour_id"
		,array(
		
			'id'=>"delivery_hour_id"
				
		
		));
		$this->addField($f_delivery_hour_id);

		$f_closed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"closed"
		,array(
		
			'defaultValue'=>"false"
		,
			'id'=>"closed"
				
		
		));
		$this->addField($f_closed);

		$f_deliv_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_date"
		,array(
		
			'id'=>"deliv_date"
				
		
		));
		$this->addField($f_deliv_date);

		$f_added_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"added_date_time"
		,array(
		
			'id'=>"added_date_time"
				
		
		));
		$this->addField($f_added_date_time);

		
		
		
	}

}
?>
