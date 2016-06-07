<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

class UserList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("user_list_view");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name_full=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name_full"
		,array(
		
			'id'=>"name_full"
				
		
		));
		$this->addField($f_name_full);

		$f_email=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"email"
		,array(
		
			'id'=>"email"
				
		
		));
		$this->addField($f_email);

		$f_role_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"role_descr"
		,array(
		
			'id'=>"role_descr"
				
		
		));
		$this->addField($f_role_descr);

		
		
		
	}

}
?>
