<?php
/**
 *
 * THIS FILE IS GENERATED FROM TEMPLATE build/templates/models/Model_php.xsl
 * ALL DIRECT MODIFICATIONS WILL BE LOST WITH THE NEXT BUILD PROCESS!!!
 *
 */

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
 
class DriverList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		
		$this->setDbName('public');
		
		$this->setTableName("drivers_list");
			
		//*** Field id ***
		$f_opts = array();
		$f_opts['primaryKey'] = TRUE;
		$f_opts['id']="id";
						
		$f_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"id",$f_opts);
		$this->addField($f_id);
		//********************
		
		//*** Field name ***
		$f_opts = array();
		$f_opts['id']="name";
						
		$f_name=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"name",$f_opts);
		$this->addField($f_name);
		//********************
		
		//*** Field drive_perm ***
		$f_opts = array();
		$f_opts['id']="drive_perm";
						
		$f_drive_perm=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"drive_perm",$f_opts);
		$this->addField($f_drive_perm);
		//********************
		
		//*** Field cel_phone ***
		$f_opts = array();
		$f_opts['id']="cel_phone";
						
		$f_cel_phone=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"cel_phone",$f_opts);
		$this->addField($f_cel_phone);
		//********************
		
		//*** Field ext_id ***
		$f_opts = array();
		$f_opts['id']="ext_id";
						
		$f_ext_id=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"ext_id",$f_opts);
		$this->addField($f_ext_id);
		//********************
		
		//*** Field match_1c ***
		$f_opts = array();
		$f_opts['id']="match_1c";
						
		$f_match_1c=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"match_1c",$f_opts);
		$this->addField($f_match_1c);
		//********************
	
	}

}
?>
