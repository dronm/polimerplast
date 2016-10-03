<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');

class AccPKO_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("acc_pkos");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time"
		,array(
		
			'id'=>"date_time"
				
		
		));
		$this->addField($f_date_time);

		$f_acc_pko_type=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"acc_pko_type"
		,array(
		
			'id'=>"acc_pko_type"
				
		
		));
		$this->addField($f_acc_pko_type);

		$f_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total"
		,array(
		
			'length'=>15,
			'id'=>"total"
				
		
		));
		$this->addField($f_total);

		$f_order_list=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"order_list"
		,array(
		
			'id'=>"order_list"
				
		
		));
		$this->addField($f_order_list);

		$f_order_ids=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"order_ids"
		,array(
		
			'id'=>"order_ids"
				
		
		));
		$this->addField($f_order_ids);

		
		
		
	}

}
?>
