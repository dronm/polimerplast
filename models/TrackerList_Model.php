<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class TrackerList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("trackers_list");
		
		$f_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'alias'=>"Идентификатор"
		,
			'length'=>15,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_tracker_server_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tracker_server_id"
		,array(
		
			'id'=>"tracker_server_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_tracker_server_id);

		$f_tracker_server_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tracker_server_descr"
		,array(
		
			'alias'=>"Сервер"
		,
			'id'=>"tracker_server_descr"
				
		
		));
		$this->addField($f_tracker_server_descr);

		$f_sim_number=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sim_number"
		,array(
		
			'alias'=>"Номер SIM"
		,
			'id'=>"sim_number"
				
		
		));
		$this->addField($f_sim_number);

		$f_sim_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sim_id"
		,array(
		
			'alias'=>"Идентификатор SIM"
		,
			'id'=>"sim_id"
				
		
		));
		$this->addField($f_sim_id);

		
		
		
	}

}
?>
