<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelReportSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class RepPriceListTuning_Model extends ModelReportSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("price_list_tuning");
		
		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'alias'=>"Код клиента"
		,
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_client_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_descr"
		,array(
		
			'alias'=>"Наименование клиента"
		,
			'id'=>"client_descr"
				
		
		));
		$this->addField($f_client_descr);

		$f_production_city_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_id"
		,array(
		
			'alias'=>"Код города"
		,
			'id'=>"production_city_id"
				
		
		));
		$this->addField($f_production_city_id);

		$f_production_city_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_descr"
		,array(
		
			'alias'=>"Наименование города"
		,
			'id'=>"production_city_descr"
				
		
		));
		$this->addField($f_production_city_descr);

		$f_to_third_party_only=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"to_third_party_only"
		,array(
		
			'alias'=>"Для третьих лиц"
		,
			'id'=>"to_third_party_only"
				
		
		));
		$this->addField($f_to_third_party_only);

		$f_price_list_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price_list_descr"
		,array(
		
			'alias'=>"Наименование прайса"
		,
			'id'=>"price_list_descr"
				
		
		));
		$this->addField($f_price_list_descr);

		$f_product_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_descr"
		,array(
		
			'alias'=>"Наименование продукции"
		,
			'id'=>"product_descr"
				
		
		));
		$this->addField($f_product_descr);

		$f_product_descr=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_descr"
		,array(
		
			'alias'=>"Цена"
		,
			'length'=>15,
			'id'=>"product_descr"
				
		
		));
		$this->addField($f_product_descr);

		
		
		
	}

}
?>
