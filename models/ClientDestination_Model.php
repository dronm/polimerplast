<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class ClientDestination_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_destinations");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		'required'=>TRUE,
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_zone_center=new FieldSQlGeomPoint($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"zone_center"
		,array(
		
			'id'=>"zone_center"
				
		
		));
		$this->addField($f_zone_center);

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

		$f_region=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"region"
		,array(
		
			'id'=>"region"
				
		
		));
		$this->addField($f_region);

		$f_region_code=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"region_code"
		,array(
		
			'id'=>"region_code"
				
		
		));
		$this->addField($f_region_code);

		$f_raion=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"raion"
		,array(
		
			'id'=>"raion"
				
		
		));
		$this->addField($f_raion);

		$f_raion_code=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"raion_code"
		,array(
		
			'id'=>"raion_code"
				
		
		));
		$this->addField($f_raion_code);

		$f_gorod=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"gorod"
		,array(
		
			'id'=>"gorod"
				
		
		));
		$this->addField($f_gorod);

		$f_gorod_code=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"gorod_code"
		,array(
		
			'id'=>"gorod_code"
				
		
		));
		$this->addField($f_gorod_code);

		$f_naspunkt=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"naspunkt"
		,array(
		
			'id'=>"naspunkt"
				
		
		));
		$this->addField($f_naspunkt);

		$f_naspunkt_code=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"naspunkt_code"
		,array(
		
			'id'=>"naspunkt_code"
				
		
		));
		$this->addField($f_naspunkt_code);

		$f_ulitza=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ulitza"
		,array(
		
			'id'=>"ulitza"
				
		
		));
		$this->addField($f_ulitza);

		$f_ulitza_code=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ulitza_code"
		,array(
		
			'id'=>"ulitza_code"
				
		
		));
		$this->addField($f_ulitza_code);

		$f_dom=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"dom"
		,array(
		
			'id'=>"dom"
				
		
		));
		$this->addField($f_dom);

		$f_korpus=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"korpus"
		,array(
		
			'id'=>"korpus"
				
		
		));
		$this->addField($f_korpus);

		$f_kvartira=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"kvartira"
		,array(
		
			'id'=>"kvartira"
				
		
		));
		$this->addField($f_kvartira);

		$f_addr_index=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"addr_index"
		,array(
		
			'id'=>"addr_index"
				
		
		));
		$this->addField($f_addr_index);

		
		
		
	}

}
?>
