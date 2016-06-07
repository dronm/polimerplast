<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class DelivCostOptList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_cost_opts_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_volume_m=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"volume_m"
		,array(
		
			'id'=>"volume_m"
				
		
		));
		$this->addField($f_volume_m);

		$f_weight_t=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"weight_t"
		,array(
		
			'length'=>19,
			'id'=>"weight_t"
				
		
		));
		$this->addField($f_weight_t);

		$f_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"descr"
		,array(
		
			'id'=>"descr"
				
		
		));
		$this->addField($f_descr);

		
		
		
	}

}
?>
