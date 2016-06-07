<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class DelivCostList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_costs_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_id);

		$f_production_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		
			'id'=>"production_city_id"
		,
			'sysCol'=>TRUE
				
		
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

		$f_deliv_cost_opt=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt"
		,array(
		
			'id'=>"deliv_cost_opt"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_deliv_cost_opt);

		$f_deliv_cost_opt_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt_descr"
		,array(
		
			'alias'=>"Ценовая категория"
		,
			'id'=>"deliv_cost_opt_descr"
				
		
		));
		$this->addField($f_deliv_cost_opt_descr);

		$f_deliv_cost_type=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_type"
		,array(
		
			'id'=>"deliv_cost_type"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_deliv_cost_type);

		$f_deliv_cost_type_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_type_descr"
		,array(
		
			'alias'=>"Город/межгород"
		,
			'id'=>"deliv_cost_type_descr"
				
		
		));
		$this->addField($f_deliv_cost_type_descr);

		$f_cost=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cost"
		,array(
		
			'alias'=>"Цена"
		,
			'length'=>15,
			'id'=>"cost"
				
		
		));
		$this->addField($f_cost);

		
		
		
	}

}
?>
