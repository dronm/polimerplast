<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class ClientDialog_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_dialog");
		
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

		$f_name_full=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name_full"
		,array(
		
			'id'=>"name_full"
				
		
		));
		$this->addField($f_name_full);

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

		$f_banned=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"banned"
		,array(
		
			'id'=>"banned"
				
		
		));
		$this->addField($f_banned);

		$f_def_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"def_firm_id"
		,array(
		
			'id'=>"def_firm_id"
				
		
		));
		$this->addField($f_def_firm_id);

		$f_def_warehouse_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"def_warehouse_id"
		,array(
		
			'id'=>"def_warehouse_id"
				
		
		));
		$this->addField($f_def_warehouse_id);

		
		
		
	}

}
?>
