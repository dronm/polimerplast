<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class ClientDebt_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_debts");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		
			'id'=>"firm_id"
				
		
		));
		$this->addField($f_firm_id);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_client_debt_period_days_from=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_period_days_from"
		,array(
		
			'id'=>"client_debt_period_days_from"
				
		
		));
		$this->addField($f_client_debt_period_days_from);

		$f_def_debt=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"def_debt"
		,array(
		
			'length'=>15,
			'id'=>"def_debt"
				
		
		));
		$this->addField($f_def_debt);

		$f_debt_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"debt_total"
		,array(
		
			'length'=>15,
			'id'=>"debt_total"
				
		
		));
		$this->addField($f_debt_total);

		$f_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"days"
		,array(
		
			'id'=>"days"
				
		
		));
		$this->addField($f_days);

		$f_update_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"update_date"
		,array(
		
			'id'=>"update_date"
				
		
		));
		$this->addField($f_update_date);

		
		
		
	}

}
?>
