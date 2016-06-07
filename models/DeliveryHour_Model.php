<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');

class DeliveryHour_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("delivery_hours");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_h_from=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"h_from"
		,array(
		
			'id'=>"h_from"
				
		
		));
		$this->addField($f_h_from);

		$f_h_to=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"h_to"
		,array(
		
			'id'=>"h_to"
				
		
		));
		$this->addField($f_h_to);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_h_from);

		
		
		
	}

}
?>
