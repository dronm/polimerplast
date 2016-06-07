<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class ClientPriceList_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_price_lists");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		
			'length'=>100,
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_production_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		'required'=>TRUE,
			'id'=>"production_city_id"
				
		
		));
		$this->addField($f_production_city_id);

		$f_to_third_party_only=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"to_third_party_only"
		,array(
		
			'id'=>"to_third_party_only"
				
		
		));
		$this->addField($f_to_third_party_only);

		$f_part_ship_do_not_change_price=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"part_ship_do_not_change_price"
		,array(
		
			'id'=>"part_ship_do_not_change_price"
				
		
		));
		$this->addField($f_part_ship_do_not_change_price);

		$f_min_order_quant=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"min_order_quant"
		,array(
		
			'length'=>19,
			'id'=>"min_order_quant"
				
		
		));
		$this->addField($f_min_order_quant);

		$f_default_price_list=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"default_price_list"
		,array(
		
			'id'=>"default_price_list"
				
		
		));
		$this->addField($f_default_price_list);

		
		
		
	}

}
?>
