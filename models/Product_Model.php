<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');

class Product_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("products");
		
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

		$f_mes_length_exists=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_exists"
		,array(
		
			'id'=>"mes_length_exists"
				
		
		));
		$this->addField($f_mes_length_exists);

		$f_mes_length_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_name"
		,array(
		
			'length'=>50,
			'id'=>"mes_length_name"
				
		
		));
		$this->addField($f_mes_length_name);

		$f_mes_length_fix=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_fix"
		,array(
		
			'id'=>"mes_length_fix"
				
		
		));
		$this->addField($f_mes_length_fix);

		$f_mes_length_fix_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_fix_val"
		,array(
		
			'id'=>"mes_length_fix_val"
				
		
		));
		$this->addField($f_mes_length_fix_val);

		$f_mes_length_min_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_min_val"
		,array(
		
			'id'=>"mes_length_min_val"
				
		
		));
		$this->addField($f_mes_length_min_val);

		$f_mes_length_max_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_max_val"
		,array(
		
			'id'=>"mes_length_max_val"
				
		
		));
		$this->addField($f_mes_length_max_val);

		$f_mes_length_def_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_def_val"
		,array(
		
			'id'=>"mes_length_def_val"
				
		
		));
		$this->addField($f_mes_length_def_val);

		$f_mes_length_seq=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_seq"
		,array(
		
			'id'=>"mes_length_seq"
				
		
		));
		$this->addField($f_mes_length_seq);

		$f_mes_length_vals=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_length_vals"
		,array(
		
			'id'=>"mes_length_vals"
				
		
		));
		$this->addField($f_mes_length_vals);

		$f_mes_width_exists=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_exists"
		,array(
		
			'id'=>"mes_width_exists"
				
		
		));
		$this->addField($f_mes_width_exists);

		$f_mes_width_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_name"
		,array(
		
			'length'=>50,
			'id'=>"mes_width_name"
				
		
		));
		$this->addField($f_mes_width_name);

		$f_mes_width_fix=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_fix"
		,array(
		
			'id'=>"mes_width_fix"
				
		
		));
		$this->addField($f_mes_width_fix);

		$f_mes_width_fix_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_fix_val"
		,array(
		
			'id'=>"mes_width_fix_val"
				
		
		));
		$this->addField($f_mes_width_fix_val);

		$f_mes_width_min_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_min_val"
		,array(
		
			'id'=>"mes_width_min_val"
				
		
		));
		$this->addField($f_mes_width_min_val);

		$f_mes_width_max_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_max_val"
		,array(
		
			'id'=>"mes_width_max_val"
				
		
		));
		$this->addField($f_mes_width_max_val);

		$f_mes_width_def_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_def_val"
		,array(
		
			'id'=>"mes_width_def_val"
				
		
		));
		$this->addField($f_mes_width_def_val);

		$f_mes_width_seq=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_seq"
		,array(
		
			'id'=>"mes_width_seq"
				
		
		));
		$this->addField($f_mes_width_seq);

		$f_mes_width_vals=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_width_vals"
		,array(
		
			'id'=>"mes_width_vals"
				
		
		));
		$this->addField($f_mes_width_vals);

		$f_mes_height_exists=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_exists"
		,array(
		
			'id'=>"mes_height_exists"
				
		
		));
		$this->addField($f_mes_height_exists);

		$f_mes_height_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_name"
		,array(
		
			'length'=>50,
			'id'=>"mes_height_name"
				
		
		));
		$this->addField($f_mes_height_name);

		$f_mes_height_fix=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_fix"
		,array(
		
			'id'=>"mes_height_fix"
				
		
		));
		$this->addField($f_mes_height_fix);

		$f_mes_height_fix_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_fix_val"
		,array(
		
			'id'=>"mes_height_fix_val"
				
		
		));
		$this->addField($f_mes_height_fix_val);

		$f_mes_height_min_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_min_val"
		,array(
		
			'id'=>"mes_height_min_val"
				
		
		));
		$this->addField($f_mes_height_min_val);

		$f_mes_height_max_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_max_val"
		,array(
		
			'id'=>"mes_height_max_val"
				
		
		));
		$this->addField($f_mes_height_max_val);

		$f_mes_height_def_val=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_def_val"
		,array(
		
			'id'=>"mes_height_def_val"
				
		
		));
		$this->addField($f_mes_height_def_val);

		$f_mes_height_seq=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_seq"
		,array(
		
			'id'=>"mes_height_seq"
				
		
		));
		$this->addField($f_mes_height_seq);

		$f_mes_height_vals=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"mes_height_vals"
		,array(
		
			'id'=>"mes_height_vals"
				
		
		));
		$this->addField($f_mes_height_vals);

		$f_base_measure_unit_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"base_measure_unit_id"
		,array(
		'required'=>TRUE,
			'id'=>"base_measure_unit_id"
				
		
		));
		$this->addField($f_base_measure_unit_id);

		$f_order_measure_unit_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"order_measure_unit_id"
		,array(
		
			'id'=>"order_measure_unit_id"
				
		
		));
		$this->addField($f_order_measure_unit_id);

		$f_base_measure_unit_vol_m=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"base_measure_unit_vol_m"
		,array(
		
			'length'=>10,
			'id'=>"base_measure_unit_vol_m"
				
		
		));
		$this->addField($f_base_measure_unit_vol_m);

		$f_base_measure_unit_weight_t=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"base_measure_unit_weight_t"
		,array(
		
			'length'=>10,
			'id'=>"base_measure_unit_weight_t"
				
		
		));
		$this->addField($f_base_measure_unit_weight_t);

		$f_pack_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pack_name"
		,array(
		
			'length'=>50,
			'id'=>"pack_name"
				
		
		));
		$this->addField($f_pack_name);

		$f_pack_default=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pack_default"
		,array(
		
			'id'=>"pack_default"
				
		
		));
		$this->addField($f_pack_default);

		$f_pack_not_free=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pack_not_free"
		,array(
		
			'id'=>"pack_not_free"
				
		
		));
		$this->addField($f_pack_not_free);

		$f_pack_full_package_only=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pack_full_package_only"
		,array(
		
			'id'=>"pack_full_package_only"
				
		
		));
		$this->addField($f_pack_full_package_only);

		$f_extra_pay_for_abnormal_size=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"extra_pay_for_abnormal_size"
		,array(
		
			'id'=>"extra_pay_for_abnormal_size"
				
		
		));
		$this->addField($f_extra_pay_for_abnormal_size);

		$f_extra_pay_for_abn_size_always=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"extra_pay_for_abn_size_always"
		,array(
		
			'defaultValue'=>"false"
		,
			'id'=>"extra_pay_for_abn_size_always"
				
		
		));
		$this->addField($f_extra_pay_for_abn_size_always);

		$f_extra_pay_calc_formula=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"extra_pay_calc_formula"
		,array(
		
			'id'=>"extra_pay_calc_formula"
				
		
		));
		$this->addField($f_extra_pay_calc_formula);

		$f_warehouses_str=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouses_str"
		,array(
		
			'defaultValue'=>"''"
		,
			'id'=>"warehouses_str"
				
		
		));
		$this->addField($f_warehouses_str);

		$f_lot_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"lot_id"
		,array(
		
			'length'=>10,
			'id'=>"lot_id"
				
		
		));
		$this->addField($f_lot_id);

		$f_lot_volume=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"lot_volume"
		,array(
		
			'length'=>30,
			'id'=>"lot_volume"
				
		
		));
		$this->addField($f_lot_volume);

		$f_sert_type_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sert_type_id"
		,array(
		
			'id'=>"sert_type_id"
				
		
		));
		$this->addField($f_sert_type_id);

		$f_name_for_1c=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name_for_1c"
		,array(
		
			'length'=>100,
			'id'=>"name_for_1c"
				
		
		));
		$this->addField($f_name_for_1c);

		$f_product_group_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_group_id"
		,array(
		
			'id'=>"product_group_id"
				
		
		));
		$this->addField($f_product_group_id);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_name);

		
		
		
	}

}
?>
