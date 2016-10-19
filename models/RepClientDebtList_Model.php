<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class RepClientDebtList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("rep_debts");
		
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

		$f_shipped_not_payed=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"shipped_not_payed"
		,array(
		
			'alias'=>"Отгружено, не оплачено"
		,
			'length'=>15,
			'id'=>"shipped_not_payed"
				
		
		));
		$this->addField($f_shipped_not_payed);

		$f_not_shipped_payed=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"not_shipped_payed"
		,array(
		
			'alias'=>"Оплачено, не отгружено"
		,
			'length'=>15,
			'id'=>"not_shipped_payed"
				
		
		));
		$this->addField($f_not_shipped_payed);

		$f_balance=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"balance"
		,array(
		
			'alias'=>"Сумма"
		,
			'length'=>15,
			'id'=>"balance"
				
		
		));
		$this->addField($f_balance);

		
		
		
	}

}
?>
