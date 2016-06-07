<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class DelivCostOpt_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_cost_opts");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_volume_m=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"volume_m"
		,array(
		'required'=>TRUE,
			'id'=>"volume_m"
				
		
		));
		$this->addField($f_volume_m);

		$f_weight_t=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"weight_t"
		,array(
		'required'=>TRUE,
			'length'=>19,
			'id'=>"weight_t"
				
		
		));
		$this->addField($f_weight_t);

		
		
		
	}

}
?>
