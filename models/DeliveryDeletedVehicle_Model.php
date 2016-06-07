<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class DeliveryDeletedVehicle_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("delivery_deleted_vehicles");
		
		$f_vehicle_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vehicle_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"vehicle_id"
				
		
		));
		$this->addField($f_vehicle_id);

		$f_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"date"
				
		
		));
		$this->addField($f_date);

		
		
		
	}

}
?>
