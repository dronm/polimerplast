<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');

class VehicleState_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("vehicle_states_data");
		
		$f_vehicle_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vehicle_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"vehicle_id"
				
		
		));
		$this->addField($f_vehicle_id);

		$f_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"date_time"
				
		
		));
		$this->addField($f_date_time);

		$f_state=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"state"
		,array(
		
			'id'=>"state"
				
		
		));
		$this->addField($f_state);

		$f_tracker=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tracker"
		,array(
		
			'length'=>15,
			'id'=>"tracker"
				
		
		));
		$this->addField($f_tracker);

		$f_to_client_zone=new FieldSQlGeometry($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"to_client_zone"
		,array(
		
			'id'=>"to_client_zone"
				
		
		));
		$this->addField($f_to_client_zone);

		
		
		
	}

}
?>
