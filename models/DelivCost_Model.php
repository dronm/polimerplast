<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');

class DelivCost_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_costs");
			
		//*** Field id ***
		$f_opts = array();
		$f_opts['primaryKey'] = TRUE;
		$f_opts['autoInc']=TRUE;
		$f_opts['id']="id";
		
		$f_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"id",$f_opts);
		$this->addField($f_id);
		//********************
	
		//*** Field production_city_id ***
		$f_opts = array();
		$f_opts['id']="production_city_id";
		
		$f_production_city_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"production_city_id",$f_opts);
		$this->addField($f_production_city_id);
		//********************
	
		//*** Field deliv_cost_opt_id ***
		$f_opts = array();
		$f_opts['id']="deliv_cost_opt_id";
		
		$f_deliv_cost_opt_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"deliv_cost_opt_id",$f_opts);
		$this->addField($f_deliv_cost_opt_id);
		//********************
	
		//*** Field deliv_cost_type ***
		$f_opts = array();
		$f_opts['primaryKey'] = TRUE;
		$f_opts['id']="deliv_cost_type";
		
		$f_deliv_cost_type=new FieldSQLEnum($this->getDbLink(),$this->getDbName(),$this->getTableName(),"deliv_cost_type",$f_opts);
		$this->addField($f_deliv_cost_type);
		//********************
	
		//*** Field cost ***
		$f_opts = array();
		$f_opts['length']=15;
		$f_opts['id']="cost";
		
		$f_cost=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"cost",$f_opts);
		$this->addField($f_cost);
		//********************

	}

}
?>
