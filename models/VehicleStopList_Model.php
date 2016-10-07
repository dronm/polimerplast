<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');

class VehicleStopList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("");
		
		$f_vh_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vh_id"
		,array(
		
			'id'=>"vh_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_vh_id);

		$f_vh_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"vh_descr"
		,array(
		
			'alias'=>"ТС"
		,
			'id'=>"vh_descr"
				
		
		));
		$this->addField($f_vh_descr);

		$f_date_time_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time_descr"
		,array(
		
			'alias'=>"Дата"
		,
			'id'=>"date_time_descr"
				
		
		));
		$this->addField($f_date_time_descr);

		$f_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time"
		,array(
		
			'id'=>"date_time"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_date_time);

		$f_duration=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"duration"
		,array(
		
			'alias'=>"Продолжит.стоянки"
		,
			'id'=>"duration"
				
		
		));
		$this->addField($f_duration);

		$f_address=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"address"
		,array(
		
			'alias'=>"Адрес"
		,
			'id'=>"address"
				
		
		));
		$this->addField($f_address);

		
		
		
	}

}
?>
