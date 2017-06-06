<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class DOCOrderDOCTFProduct_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders_t_products");
			
		//*** Field doc_id ***
		$f_opts = array();
		$f_opts['primaryKey'] = TRUE;
		$f_opts['id']="doc_id";
		
		$f_doc_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"doc_id",$f_opts);
		$this->addField($f_doc_id);
		//********************
	
		//*** Field line_number ***
		$f_opts = array();
		$f_opts['primaryKey'] = TRUE;
		$f_opts['id']="line_number";
		
		$f_line_number=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"line_number",$f_opts);
		$this->addField($f_line_number);
		//********************
	
		//*** Field product_id ***
		$f_opts = array();
		$f_opts['id']="product_id";
		
		$f_product_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"product_id",$f_opts);
		$this->addField($f_product_id);
		//********************
	
		//*** Field mes_length ***
		$f_opts = array();
		$f_opts['id']="mes_length";
		
		$f_mes_length=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"mes_length",$f_opts);
		$this->addField($f_mes_length);
		//********************
	
		//*** Field mes_width ***
		$f_opts = array();
		$f_opts['id']="mes_width";
		
		$f_mes_width=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"mes_width",$f_opts);
		$this->addField($f_mes_width);
		//********************
	
		//*** Field mes_height ***
		$f_opts = array();
		$f_opts['id']="mes_height";
		
		$f_mes_height=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"mes_height",$f_opts);
		$this->addField($f_mes_height);
		//********************
	
		//*** Field measure_unit_id ***
		$f_opts = array();
		$f_opts['id']="measure_unit_id";
		
		$f_measure_unit_id=new FieldSQLInt($this->getDbLink(),$this->getDbName(),$this->getTableName(),"measure_unit_id",$f_opts);
		$this->addField($f_measure_unit_id);
		//********************
	
		//*** Field quant ***
		$f_opts = array();
		$f_opts['length']=19;
		$f_opts['id']="quant";
		
		$f_quant=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"quant",$f_opts);
		$this->addField($f_quant);
		//********************
	
		//*** Field quant_confirmed ***
		$f_opts = array();
		$f_opts['length']=19;
		$f_opts['id']="quant_confirmed";
		
		$f_quant_confirmed=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"quant_confirmed",$f_opts);
		$this->addField($f_quant_confirmed);
		//********************
	
		//*** Field quant_base_measure_unit ***
		$f_opts = array();
		$f_opts['length']=19;
		$f_opts['id']="quant_base_measure_unit";
		
		$f_quant_base_measure_unit=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"quant_base_measure_unit",$f_opts);
		$this->addField($f_quant_base_measure_unit);
		//********************
	
		//*** Field quant_confirmed_base_measure_unit ***
		$f_opts = array();
		$f_opts['length']=19;
		$f_opts['id']="quant_confirmed_base_measure_unit";
		
		$f_quant_confirmed_base_measure_unit=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"quant_confirmed_base_measure_unit",$f_opts);
		$this->addField($f_quant_confirmed_base_measure_unit);
		//********************
	
		//*** Field volume ***
		$f_opts = array();
		$f_opts['length']=19;
		$f_opts['id']="volume";
		
		$f_volume=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"volume",$f_opts);
		$this->addField($f_volume);
		//********************
	
		//*** Field weight ***
		$f_opts = array();
		$f_opts['length']=19;
		$f_opts['id']="weight";
		
		$f_weight=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"weight",$f_opts);
		$this->addField($f_weight);
		//********************
	
		//*** Field price ***
		$f_opts = array();
		$f_opts['length']=15;
		$f_opts['id']="price";
		
		$f_price=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"price",$f_opts);
		$this->addField($f_price);
		//********************
	
		//*** Field price_no_deliv ***
		$f_opts = array();
		$f_opts['length']=15;
		$f_opts['id']="price_no_deliv";
		
		$f_price_no_deliv=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"price_no_deliv",$f_opts);
		$this->addField($f_price_no_deliv);
		//********************
	
		//*** Field price_edit ***
		$f_opts = array();
		$f_opts['id']="price_edit";
		
		$f_price_edit=new FieldSQLBool($this->getDbLink(),$this->getDbName(),$this->getTableName(),"price_edit",$f_opts);
		$this->addField($f_price_edit);
		//********************
	
		//*** Field total ***
		$f_opts = array();
		$f_opts['length']=15;
		$f_opts['id']="total";
		
		$f_total=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"total",$f_opts);
		$this->addField($f_total);
		//********************
	
		//*** Field total_pack ***
		$f_opts = array();
		$f_opts['length']=15;
		$f_opts['id']="total_pack";
		
		$f_total_pack=new FieldSQLFloat($this->getDbLink(),$this->getDbName(),$this->getTableName(),"total_pack",$f_opts);
		$this->addField($f_total_pack);
		//********************
	
		//*** Field pack_exists ***
		$f_opts = array();
		$f_opts['id']="pack_exists";
		
		$f_pack_exists=new FieldSQLBool($this->getDbLink(),$this->getDbName(),$this->getTableName(),"pack_exists",$f_opts);
		$this->addField($f_pack_exists);
		//********************
	
		//*** Field pack_in_price ***
		$f_opts = array();
		$f_opts['id']="pack_in_price";
		
		$f_pack_in_price=new FieldSQLBool($this->getDbLink(),$this->getDbName(),$this->getTableName(),"pack_in_price",$f_opts);
		$this->addField($f_pack_in_price);
		//********************

	}

}
?>
