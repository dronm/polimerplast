<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class ClientPriceListProduct_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_price_list_products");
		
		$f_price_list_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price_list_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"price_list_id"
				
		
		));
		$this->addField($f_price_list_id);

		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_price=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price"
		,array(
		
			'length'=>15,
			'id'=>"price"
				
		
		));
		$this->addField($f_price);

		$f_discount_volume=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"discount_volume"
		,array(
		
			'length'=>19,
			'id'=>"discount_volume"
				
		
		));
		$this->addField($f_discount_volume);

		$f_discount_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"discount_total"
		,array(
		
			'length'=>19,
			'id'=>"discount_total"
				
		
		));
		$this->addField($f_discount_total);

		$f_pack_price=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pack_price"
		,array(
		
			'length'=>15,
			'id'=>"pack_price"
				
		
		));
		$this->addField($f_pack_price);

		
		
		
	}

}
?>
