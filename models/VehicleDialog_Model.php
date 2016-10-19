<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class VehicleDialog_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("vehicles_dialog");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'alias'=>"Код"
		,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_plate=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"plate"
		,array(
		
			'alias'=>"Гос.номер"
		,
			'id'=>"plate"
				
		
		));
		$this->addField($f_plate);

		$f_model=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"model"
		,array(
		
			'alias'=>"Модель"
		,
			'id'=>"model"
				
		
		));
		$this->addField($f_model);

		$f_production_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		
			'alias'=>"Город код"
		,
			'id'=>"production_city_id"
				
		
		));
		$this->addField($f_production_city_id);

		$f_production_city_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_descr"
		,array(
		
			'alias'=>"Город"
		,
			'id'=>"production_city_descr"
				
		
		));
		$this->addField($f_production_city_descr);

		$f_driver_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"driver_id"
		,array(
		
			'alias'=>"Водитель код"
		,
			'id'=>"driver_id"
				
		
		));
		$this->addField($f_driver_id);

		$f_driver_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"driver_descr"
		,array(
		
			'alias'=>"Водитель"
		,
			'id'=>"driver_descr"
				
		
		));
		$this->addField($f_driver_descr);

		$f_employed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"employed"
		,array(
		
			'alias'=>"Постоянный"
		,
			'id'=>"employed"
				
		
		));
		$this->addField($f_employed);

		$f_vol=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vol"
		,array(
		
			'alias'=>"Объем"
		,
			'id'=>"vol"
				
		
		));
		$this->addField($f_vol);

		$f_load_weight_t=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"load_weight_t"
		,array(
		
			'alias'=>"Грузоподъемность"
		,
			'id'=>"load_weight_t"
				
		
		));
		$this->addField($f_load_weight_t);

		$f_vl_wt=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vl_wt"
		,array(
		
			'alias'=>"объем/грузоподъемность"
		,
			'id'=>"vl_wt"
				
		
		));
		$this->addField($f_vl_wt);

		$f_carrier_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"carrier_id"
		,array(
		
			'alias'=>"Перевозчник"
		,
			'id'=>"carrier_id"
				
		
		));
		$this->addField($f_carrier_id);

		$f_carrier_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"carrier_descr"
		,array(
		
			'alias'=>"Перевозчник"
		,
			'id'=>"carrier_descr"
				
		
		));
		$this->addField($f_carrier_descr);

		$f_trailer_model=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"trailer_model"
		,array(
		
			'alias'=>"Модель прицепа"
		,
			'id'=>"trailer_model"
				
		
		));
		$this->addField($f_trailer_model);

		$f_trailer_plate=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"trailer_plate"
		,array(
		
			'alias'=>"Номер прицепа"
		,
			'id'=>"trailer_plate"
				
		
		));
		$this->addField($f_trailer_plate);

		$f_tracker_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tracker_id"
		,array(
		
			'alias'=>"Трекер"
		,
			'id'=>"tracker_id"
				
		
		));
		$this->addField($f_tracker_id);

		$f_last_tracker_data=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"last_tracker_data"
		,array(
		
			'alias'=>"Данные с трекера"
		,
			'id'=>"last_tracker_data"
				
		
		));
		$this->addField($f_last_tracker_data);

		$f_match_1c=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"match_1c"
		,array(
		
			'id'=>"match_1c"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_match_1c);

		
		
		
	}

}
?>
