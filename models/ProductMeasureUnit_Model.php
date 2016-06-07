<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class ProductMeasureUnit_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("product_measure_units");
		
		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_measure_unit_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"measure_unit_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"measure_unit_id"
				
		
		));
		$this->addField($f_measure_unit_id);

		$f_calc_formula=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"calc_formula"
		,array(
		
			'id'=>"calc_formula"
				
		
		));
		$this->addField($f_calc_formula);

		$f_in_use=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"in_use"
		,array(
		
			'id'=>"in_use"
				
		
		));
		$this->addField($f_in_use);

		
		
		
	}

}
?>
