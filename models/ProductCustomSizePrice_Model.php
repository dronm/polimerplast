<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class ProductCustomSizePrice_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("product_custom_size_prices");
		
		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_category=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"category"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"category"
				
		
		));
		$this->addField($f_category);

		$f_price=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price"
		,array(
		
			'length'=>15,
			'id'=>"price"
				
		
		));
		$this->addField($f_price);

		$f_quant=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"quant"
		,array(
		
			'length'=>19,
			'id'=>"quant"
				
		
		));
		$this->addField($f_quant);

		
		
		
	}

}
?>
