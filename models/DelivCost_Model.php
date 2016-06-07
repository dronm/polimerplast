<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');

class DelivCost_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_costs");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_production_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		'required'=>TRUE,
			'id'=>"production_city_id"
				
		
		));
		$this->addField($f_production_city_id);

		$f_deliv_cost_opt_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt_id"
		,array(
		'required'=>TRUE,
			'id'=>"deliv_cost_opt_id"
				
		
		));
		$this->addField($f_deliv_cost_opt_id);

		$f_deliv_cost_type=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_type"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"deliv_cost_type"
				
		
		));
		$this->addField($f_deliv_cost_type);

		$f_cost=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cost"
		,array(
		
			'length'=>15,
			'id'=>"cost"
				
		
		));
		$this->addField($f_cost);

		
		
		
	}

}
?>
