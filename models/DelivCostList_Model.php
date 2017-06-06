<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class DelivCostList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("deliv_costs_list");
			
		//*** Field id ***
		$f_opts = array();
		$f_opts['primaryKey'] = TRUE;
		$f_opts['sysCol']=TRUE;
		$f_opts['id']="id";
		
		$f_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"id",$f_opts);
		$this->addField($f_id);
		//********************
	
		//*** Field production_city_id ***
		$f_opts = array();
		$f_opts['sysCol']=TRUE;
		$f_opts['id']="production_city_id";
		
		$f_production_city_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"production_city_id",$f_opts);
		$this->addField($f_production_city_id);
		//********************
	
		//*** Field production_city_descr ***
		$f_opts = array();
		$f_opts['id']="production_city_descr";
		
		$f_production_city_descr=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"production_city_descr",$f_opts);
		$this->addField($f_production_city_descr);
		//********************
	
		//*** Field deliv_cost_opt ***
		$f_opts = array();
		$f_opts['sysCol']=TRUE;
		$f_opts['id']="deliv_cost_opt";
		
		$f_deliv_cost_opt=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"deliv_cost_opt",$f_opts);
		$this->addField($f_deliv_cost_opt);
		//********************
	
		//*** Field deliv_cost_opt_descr ***
		$f_opts = array();
		$f_opts['id']="deliv_cost_opt_descr";
		
		$f_deliv_cost_opt_descr=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"deliv_cost_opt_descr",$f_opts);
		$this->addField($f_deliv_cost_opt_descr);
		//********************
	
		//*** Field deliv_cost_type ***
		$f_opts = array();
		$f_opts['sysCol']=TRUE;
		$f_opts['id']="deliv_cost_type";
		
		$f_deliv_cost_type=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"deliv_cost_type",$f_opts);
		$this->addField($f_deliv_cost_type);
		//********************
	
		//*** Field deliv_cost_type_descr ***
		$f_opts = array();
		$f_opts['id']="deliv_cost_type_descr";
		
		$f_deliv_cost_type_descr=new FieldSQLString($this->getDbLink(),$this->getDbName(),$this->getTableName(),"deliv_cost_type_descr",$f_opts);
		$this->addField($f_deliv_cost_type_descr);
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
