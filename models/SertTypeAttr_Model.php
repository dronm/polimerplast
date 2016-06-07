<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');

class SertTypeAttr_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("sert_types_attrs");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_sert_type_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sert_type_id"
		,array(
		
			'id'=>"sert_type_id"
				
		
		));
		$this->addField($f_sert_type_id);

		$f_attr_text=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"attr_text"
		,array(
		
			'length'=>250,
			'id'=>"attr_text"
				
		
		));
		$this->addField($f_attr_text);

		$f_attr_val_norm=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"attr_val_norm"
		,array(
		
			'length'=>50,
			'id'=>"attr_val_norm"
				
		
		));
		$this->addField($f_attr_val_norm);

		$f_attr_val=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"attr_val"
		,array(
		
			'length'=>50,
			'id'=>"attr_val"
				
		
		));
		$this->addField($f_attr_val);

		$f_attr_val_min=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"attr_val_min"
		,array(
		
			'length'=>19,
			'id'=>"attr_val_min"
				
		
		));
		$this->addField($f_attr_val_min);

		$f_attr_val_max=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"attr_val_max"
		,array(
		
			'length'=>19,
			'id'=>"attr_val_max"
				
		
		));
		$this->addField($f_attr_val_max);

		
		
		
	}

}
?>
