<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class ClientDebtList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_debt_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		
			'id'=>"firm_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_firm_id);

		$f_firm_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_descr"
		,array(
		
			'alias'=>"Организация"
		,
			'id'=>"firm_descr"
				
		
		));
		$this->addField($f_firm_descr);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'id'=>"client_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_client_id);

		$f_client_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_descr"
		,array(
		
			'alias'=>"Контрагент"
		,
			'id'=>"client_descr"
				
		
		));
		$this->addField($f_client_descr);

		$f_client_debt_period_days_from=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_period_days_from"
		,array(
		
			'id'=>"client_debt_period_days_from"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_client_debt_period_days_from);

		$f_client_debt_period_days_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_period_days_descr"
		,array(
		
			'alias'=>"Период задолженности"
		,
			'id'=>"client_debt_period_days_descr"
				
		
		));
		$this->addField($f_client_debt_period_days_descr);

		$f_def_debt=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"def_debt"
		,array(
		
			'alias'=>"Просроченная задолженность"
		,
			'length'=>15,
			'id'=>"def_debt"
				
		
		));
		$this->addField($f_def_debt);

		$f_debt_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"debt_total"
		,array(
		
			'alias'=>"Долг всего"
		,
			'length'=>15,
			'id'=>"debt_total"
				
		
		));
		$this->addField($f_debt_total);

		$f_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"days"
		,array(
		
			'alias'=>"Дней просроченного долга"
		,
			'id'=>"days"
				
		
		));
		$this->addField($f_days);

		
		
		
	}

}
?>
