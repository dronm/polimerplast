<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class NaspunktCostList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("naspunkt_costs");
		
		$f_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"city_id"
		,array(
		
			'id'=>"city_id"
				
		
		));
		$this->addField($f_city_id);

		$f_city_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"city_descr"
		,array(
		
			'alias'=>"Город"
		,
			'id'=>"city_descr"
				
		
		));
		$this->addField($f_city_descr);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'alias'=>"Наименование"
		,
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_deliv_cost_opt_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt_id"
		,array(
		
			'id'=>"deliv_cost_opt_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_deliv_cost_opt_id);

		$f_deliv_cost_opt_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt_descr"
		,array(
		
			'alias'=>"Категория"
		,
			'id'=>"deliv_cost_opt_descr"
				
		
		));
		$this->addField($f_deliv_cost_opt_descr);

		$f_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"distance"
		,array(
		
			'alias'=>"Расстояние (км.)"
		,
			'id'=>"distance"
				
		
		));
		$this->addField($f_distance);

		$f_cost=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cost"
		,array(
		
			'alias'=>"Стоимость"
		,
			'length'=>19,
			'id'=>"cost"
				
		
		));
		$this->addField($f_cost);

		
		
		
	}

}
?>
