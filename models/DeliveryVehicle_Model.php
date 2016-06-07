<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class DeliveryVehicle_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("delivery_vehicles");
		
		$f_deliv_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_date"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"deliv_date"
				
		
		));
		$this->addField($f_deliv_date);

		$f_vehicle_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vehicle_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"vehicle_id"
				
		
		));
		$this->addField($f_vehicle_id);

		
		
		
	}

}
?>
