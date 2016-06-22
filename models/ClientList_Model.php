<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class ClientList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_list");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_occupation=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"occupation"
		,array(
		
			'id'=>"occupation"
				
		
		));
		$this->addField($f_occupation);

		$f_inn=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"inn"
		,array(
		
			'id'=>"inn"
				
		
		));
		$this->addField($f_inn);

		$f_contract_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"contract_descr"
		,array(
		
			'id'=>"contract_descr"
				
		
		));
		$this->addField($f_contract_descr);

		$f_contract_period=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"contract_period"
		,array(
		
			'id'=>"contract_period"
				
		
		));
		$this->addField($f_contract_period);

		$f_terms=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"terms"
		,array(
		
			'id'=>"terms"
				
		
		));
		$this->addField($f_terms);

		$f_banned=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"banned"
		,array(
		
			'id'=>"banned"
				
		
		));
		$this->addField($f_banned);

		$f_client_activity_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_activity_descr"
		,array(
		
			'id'=>"client_activity_descr"
				
		
		));
		$this->addField($f_client_activity_descr);

		$f_login_allowed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"login_allowed"
		,array(
		
			'id'=>"login_allowed"
				
		
		));
		$this->addField($f_login_allowed);

		
		
		
	}

}
?>
