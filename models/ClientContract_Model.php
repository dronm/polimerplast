<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');

class ClientContract_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_contracts");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		'required'=>TRUE,
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		'required'=>TRUE,
			'id'=>"firm_id"
				
		
		));
		$this->addField($f_firm_id);

		$f_state=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"state"
		,array(
		'required'=>TRUE,
			'alias'=>"Состояние"
		,
			'id'=>"state"
				
		
		));
		$this->addField($f_state);

		$f_date_from=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_from"
		,array(
		
			'alias'=>"Дата с"
		,
			'id'=>"date_from"
				
		
		));
		$this->addField($f_date_from);

		$f_date_to=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_to"
		,array(
		
			'alias'=>"Дата по"
		,
			'id'=>"date_to"
				
		
		));
		$this->addField($f_date_to);

		$f_number=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"number"
		,array(
		
			'alias'=>"номер"
		,
			'length'=>50,
			'id'=>"number"
				
		
		));
		$this->addField($f_number);

		
		
		
	}

}
?>
