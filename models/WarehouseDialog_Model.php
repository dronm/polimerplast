<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class WarehouseDialog_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("warehouses_dialog");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_firm_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		
			'id'=>"firm_id"
				
		
		));
		$this->addField($f_firm_id);

		$f_firm_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_descr"
		,array(
		
			'id'=>"firm_descr"
				
		
		));
		$this->addField($f_firm_descr);

		$f_production_city_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		
			'id'=>"production_city_id"
				
		
		));
		$this->addField($f_production_city_id);

		$f_production_city_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_descr"
		,array(
		
			'id'=>"production_city_descr"
				
		
		));
		$this->addField($f_production_city_descr);

		$f_zone_str=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"zone_str"
		,array(
		
			'id'=>"zone_str"
				
		
		));
		$this->addField($f_zone_str);

		$f_zone_center_str=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"zone_center_str"
		,array(
		
			'id'=>"zone_center_str"
				
		
		));
		$this->addField($f_zone_center_str);

		
		
		
	}

}
?>
