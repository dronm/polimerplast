<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');

class ProductMeasureUnitList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("product_measure_units_list");
		
		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_product_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_descr"
		,array(
		
			'id'=>"product_descr"
				
		
		));
		$this->addField($f_product_descr);

		$f_measure_unit_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"measure_unit_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"measure_unit_id"
				
		
		));
		$this->addField($f_measure_unit_id);

		$f_measure_unit_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"measure_unit_descr"
		,array(
		
			'id'=>"measure_unit_descr"
				
		
		));
		$this->addField($f_measure_unit_descr);

		$f_calc_formula=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"calc_formula"
		,array(
		
			'id'=>"calc_formula"
				
		
		));
		$this->addField($f_calc_formula);

		
		
		
	}

}
?>
