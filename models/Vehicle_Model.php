<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class Vehicle_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("vehicles");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_model=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"model"
		,array(
		
			'length'=>50,
			'id'=>"model"
				
		
		));
		$this->addField($f_model);

		$f_plate=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"plate"
		,array(
		
			'length'=>20,
			'id'=>"plate"
				
		
		));
		$this->addField($f_plate);

		$f_vol=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vol"
		,array(
		
			'id'=>"vol"
				
		
		));
		$this->addField($f_vol);

		$f_employed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"employed"
		,array(
		
			'id'=>"employed"
				
		
		));
		$this->addField($f_employed);

		$f_load_weight_t=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"load_weight_t"
		,array(
		
			'length'=>10,
			'id'=>"load_weight_t"
				
		
		));
		$this->addField($f_load_weight_t);

		$f_production_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		'required'=>TRUE,
			'id'=>"production_city_id"
				
		
		));
		$this->addField($f_production_city_id);

		$f_carrier_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"carrier_id"
		,array(
		
			'id'=>"carrier_id"
				
		
		));
		$this->addField($f_carrier_id);

		$f_driver_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"driver_id"
		,array(
		'required'=>TRUE,
			'id'=>"driver_id"
				
		
		));
		$this->addField($f_driver_id);

		$f_deliv_cost_opt_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt_id"
		,array(
		
			'id'=>"deliv_cost_opt_id"
				
		
		));
		$this->addField($f_deliv_cost_opt_id);

		$f_trailer_model=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"trailer_model"
		,array(
		
			'length'=>50,
			'id'=>"trailer_model"
				
		
		));
		$this->addField($f_trailer_model);

		$f_trailer_plate=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"trailer_plate"
		,array(
		
			'length'=>20,
			'id'=>"trailer_plate"
				
		
		));
		$this->addField($f_trailer_plate);

		$f_tracker_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tracker_id"
		,array(
		
			'length'=>15,
			'id'=>"tracker_id"
				
		
		));
		$this->addField($f_tracker_id);

		
		
		
	}

}
?>
