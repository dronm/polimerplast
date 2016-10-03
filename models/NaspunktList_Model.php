<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class NaspunktList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("naspunkt_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_id);

		$f_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"city_id"
		,array(
		
			'id'=>"city_id"
		,
			'sysCol'=>TRUE
				
		
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

		$f_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"distance"
		,array(
		
			'alias'=>"Расстояние (км.)"
		,
			'id'=>"distance"
				
		
		));
		$this->addField($f_distance);

		
		
		
	}

}
?>
