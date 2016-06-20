<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelReportSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLTime.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');

class RepSale_Model extends ModelReportSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("rep_sales");
		
		$f_doc_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_id"
		,array(
		
			'alias'=>"Код заказа"
		,
			'id'=>"doc_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_doc_id);

		$f_doc_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_date_time"
		,array(
		
			'alias'=>"Дата подачи"
		,
			'id'=>"doc_date_time"
				
		
		));
		$this->addField($f_doc_date_time);

		$f_doc_delivery_fact_date=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_delivery_fact_date"
		,array(
		
			'alias'=>"Дата отгрузки"
		,
			'id'=>"doc_delivery_fact_date"
				
		
		));
		$this->addField($f_doc_delivery_fact_date);

		$f_doc_order_delivered=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_order_delivered"
		,array(
		
			'id'=>"doc_order_delivered"
				
		
		));
		$this->addField($f_doc_order_delivered);

		$f_doc_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_client_id"
		,array(
		
			'id'=>"doc_client_id"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_doc_client_id);

		$f_client_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_descr"
		,array(
		
			'alias'=>"Заказчик"
		,
			'id'=>"client_descr"
				
		
		));
		$this->addField($f_client_descr);

		$f_client_login_allowed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_login_allowed"
		,array(
		
			'id'=>"client_login_allowed"
				
		
		));
		$this->addField($f_client_login_allowed);

		$f_client_activity_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_activity_descr"
		,array(
		
			'id'=>"client_activity_descr"
				
		
		));
		$this->addField($f_client_activity_descr);

		$f_client_pay_type=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_pay_type"
		,array(
		
			'id'=>"client_pay_type"
				
		
		));
		$this->addField($f_client_pay_type);

		$f_client_pay_debt_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_pay_debt_days"
		,array(
		
			'id'=>"client_pay_debt_days"
				
		
		));
		$this->addField($f_client_pay_debt_days);

		$f_client_pay_ban_on_debt_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_pay_ban_on_debt_days"
		,array(
		
			'id'=>"client_pay_ban_on_debt_days"
				
		
		));
		$this->addField($f_client_pay_ban_on_debt_days);

		$f_client_pay_debt_sum=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_pay_debt_sum"
		,array(
		
			'length'=>15,
			'id'=>"client_pay_debt_sum"
				
		
		));
		$this->addField($f_client_pay_debt_sum);

		$f_client_debt_period_days_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_period_days_descr"
		,array(
		
			'id'=>"client_debt_period_days_descr"
				
		
		));
		$this->addField($f_client_debt_period_days_descr);

		$f_client_debt_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_days"
		,array(
		
			'id'=>"client_debt_days"
				
		
		));
		$this->addField($f_client_debt_days);

		$f_client_debt_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_total"
		,array(
		
			'length'=>15,
			'id'=>"client_debt_total"
				
		
		));
		$this->addField($f_client_debt_total);

		$f_client_contracts_exist=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_contracts_exist"
		,array(
		
			'id'=>"client_contracts_exist"
				
		
		));
		$this->addField($f_client_contracts_exist);

		$f_client_contract_end=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_contract_end"
		,array(
		
			'id'=>"client_contract_end"
				
		
		));
		$this->addField($f_client_contract_end);

		$f_doc_number=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_number"
		,array(
		
			'alias'=>"Номер заказа"
		,
			'id'=>"doc_number"
				
		
		));
		$this->addField($f_doc_number);

		$f_firm_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_descr"
		,array(
		
			'alias'=>"Фирма"
		,
			'id'=>"firm_descr"
				
		
		));
		$this->addField($f_firm_descr);

		$f_production_city_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"production_city_descr"
		,array(
		
			'alias'=>"Город отгрузки"
		,
			'id'=>"production_city_descr"
				
		
		));
		$this->addField($f_production_city_descr);

		$f_doc_delivery_plan_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_delivery_plan_date"
		,array(
		
			'id'=>"doc_delivery_plan_date"
				
		
		));
		$this->addField($f_doc_delivery_plan_date);

		$f_doc_in_time=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_in_time"
		,array(
		
			'id'=>"doc_in_time"
				
		
		));
		$this->addField($f_doc_in_time);

		$f_doc_user_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_user_id"
		,array(
		
			'id'=>"doc_user_id"
				
		
		));
		$this->addField($f_doc_user_id);

		$f_doc_user_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_user_descr"
		,array(
		
			'alias'=>"Пользователь"
		,
			'id'=>"doc_user_descr"
				
		
		));
		$this->addField($f_doc_user_descr);

		$f_doc_overlimit=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_overlimit"
		,array(
		
			'id'=>"doc_overlimit"
				
		
		));
		$this->addField($f_doc_overlimit);

		$f_doc_delivery_type_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_delivery_type_descr"
		,array(
		
			'id'=>"doc_delivery_type_descr"
				
		
		));
		$this->addField($f_doc_delivery_type_descr);

		$f_doc_deliv_to_third_party=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_deliv_to_third_party"
		,array(
		
			'id'=>"doc_deliv_to_third_party"
				
		
		));
		$this->addField($f_doc_deliv_to_third_party);

		$f_doc_deliv_destination_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_deliv_destination_descr"
		,array(
		
			'id'=>"doc_deliv_destination_descr"
				
		
		));
		$this->addField($f_doc_deliv_destination_descr);

		$f_doc_deliv_period=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_deliv_period"
		,array(
		
			'id'=>"doc_deliv_period"
				
		
		));
		$this->addField($f_doc_deliv_period);

		$f_doc_warehouse_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_warehouse_id"
		,array(
		
			'id'=>"doc_warehouse_id"
				
		
		));
		$this->addField($f_doc_warehouse_id);

		$f_doc_warehouse_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_warehouse_descr"
		,array(
		
			'id'=>"doc_warehouse_descr"
				
		
		));
		$this->addField($f_doc_warehouse_descr);

		$f_doc_driver_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_driver_id"
		,array(
		
			'id'=>"doc_driver_id"
				
		
		));
		$this->addField($f_doc_driver_id);

		$f_doc_driver_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_driver_descr"
		,array(
		
			'id'=>"doc_driver_descr"
				
		
		));
		$this->addField($f_doc_driver_descr);

		$f_doc_vehicle_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_vehicle_id"
		,array(
		
			'id'=>"doc_vehicle_id"
				
		
		));
		$this->addField($f_doc_vehicle_id);

		$f_doc_vehicle_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_vehicle_descr"
		,array(
		
			'id'=>"doc_vehicle_descr"
				
		
		));
		$this->addField($f_doc_vehicle_descr);

		$f_doc_ext_order_num=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_ext_order_num"
		,array(
		
			'id'=>"doc_ext_order_num"
				
		
		));
		$this->addField($f_doc_ext_order_num);

		$f_doc_ext_ship_num=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_ext_ship_num"
		,array(
		
			'id'=>"doc_ext_ship_num"
				
		
		));
		$this->addField($f_doc_ext_ship_num);

		$f_doc_state=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_state"
		,array(
		
			'id'=>"doc_state"
				
		
		));
		$this->addField($f_doc_state);

		$f_doc_state_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_state_id"
		,array(
		
			'id'=>"doc_state_id"
				
		
		));
		$this->addField($f_doc_state_id);

		$f_doc_state_date_time=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_state_date_time"
		,array(
		
			'id'=>"doc_state_date_time"
				
		
		));
		$this->addField($f_doc_state_date_time);

		$f_doc_state_user=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_state_user"
		,array(
		
			'id'=>"doc_state_user"
				
		
		));
		$this->addField($f_doc_state_user);

		$f_doc_sales_manager_comment=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_sales_manager_comment"
		,array(
		
			'id'=>"doc_sales_manager_comment"
				
		
		));
		$this->addField($f_doc_sales_manager_comment);

		$f_doc_client_comment=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_client_comment"
		,array(
		
			'id'=>"doc_client_comment"
				
		
		));
		$this->addField($f_doc_client_comment);

		$f_doc_customer_survey_question=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_customer_survey_question"
		,array(
		
			'id'=>"doc_customer_survey_question"
				
		
		));
		$this->addField($f_doc_customer_survey_question);

		$f_doc_deliv_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_deliv_total"
		,array(
		
			'alias'=>"Стоимость доставки"
		,
			'length'=>19,
			'id'=>"doc_deliv_total"
				
		
		));
		$this->addField($f_doc_deliv_total);

		$f_doct_product_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_product_descr"
		,array(
		
			'id'=>"doct_product_descr"
				
		
		));
		$this->addField($f_doct_product_descr);

		$f_doct_product_mes=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_product_mes"
		,array(
		
			'id'=>"doct_product_mes"
				
		
		));
		$this->addField($f_doct_product_mes);

		$f_doct_product_mes_length=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_product_mes_length"
		,array(
		
			'id'=>"doct_product_mes_length"
				
		
		));
		$this->addField($f_doct_product_mes_length);

		$f_doct_product_mes_width=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_product_mes_width"
		,array(
		
			'id'=>"doct_product_mes_width"
				
		
		));
		$this->addField($f_doct_product_mes_width);

		$f_doct_product_mes_height=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_product_mes_height"
		,array(
		
			'id'=>"doct_product_mes_height"
				
		
		));
		$this->addField($f_doct_product_mes_height);

		$f_doct_base_measure_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_base_measure_id"
		,array(
		
			'id'=>"doct_base_measure_id"
				
		
		));
		$this->addField($f_doct_base_measure_id);

		$f_doct_base_measure_descr=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_base_measure_descr"
		,array(
		
			'id'=>"doct_base_measure_descr"
				
		
		));
		$this->addField($f_doct_base_measure_descr);

		$f_doct_pack_exists=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_pack_exists"
		,array(
		
			'id'=>"doct_pack_exists"
				
		
		));
		$this->addField($f_doct_pack_exists);

		$f_doc_city_route_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_city_route_distance"
		,array(
		
			'alias'=>"Киллометраж доставки город"
		,
			'id'=>"doc_city_route_distance"
				
		
		));
		$this->addField($f_doc_city_route_distance);

		$f_doc_country_route_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_country_route_distance"
		,array(
		
			'alias'=>"Киллометраж доставки межгород"
		,
			'id'=>"doc_country_route_distance"
				
		
		));
		$this->addField($f_doc_country_route_distance);

		$f_doc_coord_count=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_coord_count"
		,array(
		
			'alias'=>"Количество согласований"
		,
			'id'=>"doc_coord_count"
				
		
		));
		$this->addField($f_doc_coord_count);

		$f_doc_coord_time_sale_dep=new FieldSQlTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_coord_time_sale_dep"
		,array(
		
			'alias'=>"Время согласования ОП"
		,
			'id'=>"doc_coord_time_sale_dep"
				
		
		));
		$this->addField($f_doc_coord_time_sale_dep);

		$f_doc_coord_time_client=new FieldSQlTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_coord_time_client"
		,array(
		
			'alias'=>"Время согласования ОП"
		,
			'id'=>"doc_coord_time_client"
				
		
		));
		$this->addField($f_doc_coord_time_client);

		$f_doct_quant=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_quant"
		,array(
		
			'alias'=>"Кол-во в базовых единицах"
		,
			'length'=>19,
			'id'=>"doct_quant"
				
		
		));
		$this->addField($f_doct_quant);

		$f_doct_weight=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_weight"
		,array(
		
			'alias'=>"Масса"
		,
			'length'=>19,
			'id'=>"doct_weight"
				
		
		));
		$this->addField($f_doct_weight);

		$f_doct_volume=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_volume"
		,array(
		
			'alias'=>"Объем"
		,
			'length'=>19,
			'id'=>"doct_volume"
				
		
		));
		$this->addField($f_doct_volume);

		$f_doct_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doct_total"
		,array(
		
			'alias'=>"Стоимость продукции"
		,
			'length'=>19,
			'id'=>"doct_total"
				
		
		));
		$this->addField($f_doct_total);

		$f_client_debt_days=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_days"
		,array(
		
			'alias'=>"Просроченная дебиторская задолженность (дни)"
		,
			'id'=>"client_debt_days"
				
		
		));
		$this->addField($f_client_debt_days);

		$f_client_debt_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_debt_total"
		,array(
		
			'alias'=>"Просроченная дебиторская задолженность (стоимость)"
		,
			'length'=>19,
			'id'=>"client_debt_total"
				
		
		));
		$this->addField($f_client_debt_total);

		$f_doc_customer_survey_points=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"doc_customer_survey_points"
		,array(
		
			'alias'=>"Баллы опроса"
		,
			'id'=>"doc_customer_survey_points"
				
		
		));
		$this->addField($f_doc_customer_survey_points);

		$f_sys_level_val=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sys_level_val"
		,array(
		
			'id'=>"sys_level_val"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_sys_level_val);

		$f_sys_level_count=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sys_level_count"
		,array(
		
			'id'=>"sys_level_count"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_sys_level_count);

		$f_sys_level_col_count=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sys_level_col_count"
		,array(
		
			'id'=>"sys_level_col_count"
		,
			'sysCol'=>TRUE
				
		
		));
		$this->addField($f_sys_level_col_count);

		
		
		
	}

}
?>
