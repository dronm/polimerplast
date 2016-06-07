<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');

class DelivDistanceCache_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_distance_cache");
		
		$f_warehouse_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouse_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"warehouse_id"
				
		
		));
		$this->addField($f_warehouse_id);

		$f_client_destination_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_destination_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"client_destination_id"
				
		
		));
		$this->addField($f_client_destination_id);

		$f_city_route=new FieldSQlGeomPolygon($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"city_route"
		,array(
		
			'id'=>"city_route"
				
		
		));
		$this->addField($f_city_route);

		$f_city_route_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"city_route_distance"
		,array(
		
			'id'=>"city_route_distance"
				
		
		));
		$this->addField($f_city_route_distance);

		$f_country_route=new FieldSQlGeomPolygon($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"country_route"
		,array(
		
			'id'=>"country_route"
				
		
		));
		$this->addField($f_country_route);

		$f_country_route_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"country_route_distance"
		,array(
		
			'id'=>"country_route_distance"
				
		
		));
		$this->addField($f_country_route_distance);

		
		
		
	}

}
?>
