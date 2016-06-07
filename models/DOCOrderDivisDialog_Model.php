<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class DOCOrderDivisDialog_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_divis_dialog");
		
		$f_main_doc_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"main_doc_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"main_doc_id"
				
		
		));
		$this->addField($f_main_doc_id);

		$f_delivery_plan_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_plan_date"
		,array(
		
			'id'=>"delivery_plan_date"
				
		
		));
		$this->addField($f_delivery_plan_date);

		$f_sales_manager_comment=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sales_manager_comment"
		,array(
		
			'id'=>"sales_manager_comment"
				
		
		));
		$this->addField($f_sales_manager_comment);

		$f_deliv_period_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_period_id"
		,array(
		
			'id'=>"deliv_period_id"
				
		
		));
		$this->addField($f_deliv_period_id);

		$f_deliv_vehicle_count=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_vehicle_count"
		,array(
		
			'id'=>"deliv_vehicle_count"
				
		
		));
		$this->addField($f_deliv_vehicle_count);

		$f_deliv_cost_opt_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt_id"
		,array(
		
			'id'=>"deliv_cost_opt_id"
				
		
		));
		$this->addField($f_deliv_cost_opt_id);

		$f_state=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"state"
		,array(
		
			'id'=>"state"
				
		
		));
		$this->addField($f_state);

		$f_deliv_total=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_total"
		,array(
		
			'id'=>"deliv_total"
				
		
		));
		$this->addField($f_deliv_total);

		$f_deliv_total_edit=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_total_edit"
		,array(
		
			'id'=>"deliv_total_edit"
				
		
		));
		$this->addField($f_deliv_total_edit);

		
		
		
	}

}
?>
