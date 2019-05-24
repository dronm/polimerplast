<?php
/**
 *
 * THIS FILE IS GENERATED FROM TEMPLATE build/templates/models/Model_php.xsl
 * ALL DIRECT MODIFICATIONS WILL BE LOST WITH THE NEXT BUILD PROCESS!!!
 *
 */

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
 
class ClientDestination_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_destinations");
			
		//*** Field id ***
		$f_opts = array();
		$f_opts['primaryKey'] = TRUE;
		$f_opts['autoInc']=TRUE;
		$f_opts['id']="id";
				
		$f_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"id",$f_opts);
		$this->addField($f_id);
		//********************
		
		//*** Field client_id ***
		$f_opts = array();
		$f_opts['id']="client_id";
				
		$f_client_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"client_id",$f_opts);
		$this->addField($f_client_id);
		//********************
		
		//*** Field value ***
		$f_opts = array();
		$f_opts['id']="value";
				
		$f_value=new FieldSQLText($this->getDbLink(),$this->getDbName(),$this->getTableName(),"value",$f_opts);
		$this->addField($f_value);
		//********************
		
		//*** Field zone_center ***
		$f_opts = array();
		$f_opts['id']="zone_center";
				
		$f_zone_center=new FieldSQLGeomPoint($this->getDbLink(),$this->getDbName(),$this->getTableName(),"zone_center",$f_opts);
		$this->addField($f_zone_center);
		//********************
		
		//*** Field near_road_lon ***
		$f_opts = array();
		$f_opts['length']=15;
		$f_opts['id']="near_road_lon";
				
		$f_near_road_lon=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"near_road_lon",$f_opts);
		$this->addField($f_near_road_lon);
		//********************
		
		//*** Field near_road_lat ***
		$f_opts = array();
		$f_opts['length']=15;
		$f_opts['id']="near_road_lat";
				
		$f_near_road_lat=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"near_road_lat",$f_opts);
		$this->addField($f_near_road_lat);
		//********************
		
		//*** Field value ***
		$f_opts = array();
		$f_opts['id']="value";
				
		$f_value=new FieldSQLText($this->getDbLink(),$this->getDbName(),$this->getTableName(),"value",$f_opts);
		$this->addField($f_value);
		//********************
	
	}

}
?>
