<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');

class Warehouse_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("warehouses");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'length'=>50,
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_ext_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_id"
		,array(
		
			'length'=>36,
			'id'=>"ext_id"
				
		
		));
		$this->addField($f_ext_id);

		$f_zone=new FieldSQlGeomPolygon($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"zone"
		,array(
		
			'id'=>"zone"
				
		
		));
		$this->addField($f_zone);

		$f_production_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		'required'=>TRUE,
			'id'=>"production_city_id"
				
		
		));
		$this->addField($f_production_city_id);

		$f_default_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"default_firm_id"
		,array(
		'required'=>TRUE,
			'id'=>"default_firm_id"
				
		
		));
		$this->addField($f_default_firm_id);

		$f_near_road_lon=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"near_road_lon"
		,array(
		
			'length'=>15,
			'id'=>"near_road_lon"
				
		
		));
		$this->addField($f_near_road_lon);

		$f_near_road_lat=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"near_road_lat"
		,array(
		
			'length'=>15,
			'id'=>"near_road_lat"
				
		
		));
		$this->addField($f_near_road_lat);

		$f_address=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"address"
		,array(
		
			'id'=>"address"
				
		
		));
		$this->addField($f_address);

		$f_tel=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tel"
		,array(
		
			'id'=>"tel"
				
		
		));
		$this->addField($f_tel);

		$f_email=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"email"
		,array(
		
			'length'=>50,
			'id'=>"email"
				
		
		));
		$this->addField($f_email);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_name);

		
		
		
	}

}
?>
