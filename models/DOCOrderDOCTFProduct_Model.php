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
		
		$f_doc_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_id"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"doc_id"
				
		
		));
		$this->addField($f_doc_id);

		$f_line_number=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"line_number"
		,array(
		
			'primaryKey'=>TRUE,
			'id'=>"line_number"
				
		
		));
		$this->addField($f_line_number);

		$f_product_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_id"
		,array(
		'required'=>TRUE,
			'alias'=>"Продукция"
		,
			'id'=>"product_id"
				
		
		));
		$this->addField($f_product_id);

		$f_mes_length=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length"
		,array(
		
			'id'=>"mes_length"
				
		
		));
		$this->addField($f_mes_length);

		$f_mes_width=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width"
		,array(
		
			'id'=>"mes_width"
				
		
		));
		$this->addField($f_mes_width);

		$f_mes_height=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height"
		,array(
		
			'id'=>"mes_height"
				
		
		));
		$this->addField($f_mes_height);

		$f_measure_unit_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"measure_unit_id"
		,array(
		'required'=>TRUE,
			'id'=>"measure_unit_id"
				
		
		));
		$this->addField($f_measure_unit_id);

		$f_quant=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"quant"
		,array(
		
			'alias'=>"Количество"
		,
			'length'=>19,
			'id'=>"quant"
				
		
		));
		$this->addField($f_quant);

		$f_quant_confirmed=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"quant_confirmed"
		,array(
		
			'length'=>19,
			'id'=>"quant_confirmed"
				
		
		));
		$this->addField($f_quant_confirmed);

		$f_quant_base_measure_unit=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"quant_base_measure_unit"
		,array(
		
			'alias'=>"Количество"
		,
			'length'=>19,
			'id'=>"quant_base_measure_unit"
				
		
		));
		$this->addField($f_quant_base_measure_unit);

		$f_quant_confirmed_base_measure_unit=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"quant_confirmed_base_measure_unit"
		,array(
		
			'length'=>19,
			'id'=>"quant_confirmed_base_measure_unit"
				
		
		));
		$this->addField($f_quant_confirmed_base_measure_unit);

		$f_volume=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"volume"
		,array(
		
			'length'=>19,
			'id'=>"volume"
				
		
		));
		$this->addField($f_volume);

		$f_weight=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"weight"
		,array(
		
			'length'=>19,
			'id'=>"weight"
				
		
		));
		$this->addField($f_weight);

		$f_price=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price"
		,array(
		
			'alias'=>"Цена"
		,
			'length'=>15,
			'id'=>"price"
				
		
		));
		$this->addField($f_price);

		$f_price_no_deliv=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price_no_deliv"
		,array(
		
			'alias'=>"Цена без доставки"
		,
			'length'=>15,
			'id'=>"price_no_deliv"
				
		
		));
		$this->addField($f_price_no_deliv);

		$f_price_edit=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price_edit"
		,array(
		
			'id'=>"price_edit"
				
		
		));
		$this->addField($f_price_edit);

		$f_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total"
		,array(
		
			'alias'=>"Сумма"
		,
			'length'=>15,
			'id'=>"total"
				
		
		));
		$this->addField($f_total);

		$f_total_pack=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_pack"
		,array(
		
			'alias'=>"Упаковка"
		,
			'length'=>15,
			'id'=>"total_pack"
				
		
		));
		$this->addField($f_total_pack);

		$f_pack_exists=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pack_exists"
		,array(
		
			'id'=>"pack_exists"
				
		
		));
		$this->addField($f_pack_exists);

		$f_pack_in_price=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pack_in_price"
		,array(
		
			'id'=>"pack_in_price"
				
		
		));
		$this->addField($f_pack_in_price);

		
		
		
	}

}
?>
