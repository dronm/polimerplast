<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');

class ClientPriceListClient_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("client_price_list_clients");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_price_list_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"price_list_id"
		,array(
		'required'=>TRUE,
			'id'=>"price_list_id"
				
		
		));
		$this->addField($f_price_list_id);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		'required'=>TRUE,
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		
		
		
	}

}
?>
