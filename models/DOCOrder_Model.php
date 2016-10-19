<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQLDOC.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDateTime.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLDate.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLBool.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');

require_once('functions/ExtProg.php');
require_once('functions/PPEmailSender.php');

class DOCOrder_Model extends ModelSQLDOC{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("doc_orders");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>FALSE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"date_time"
		,array(
		
			'alias'=>"Дата пдч."
		,
			'id'=>"date_time"
				
		
		));
		$this->addField($f_date_time);

		$f_number=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"number"
		,array(
		
			'alias'=>"Номер"
		,
			'length'=>10,
			'id'=>"number"
				
		
		));
		$this->addField($f_number);

		$f_processed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"processed"
		,array(
		
			'alias'=>"Проведен"
		,
			'id'=>"processed"
				
		
		));
		$this->addField($f_processed);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_client_number=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_number"
		,array(
		
			'length'=>12,
			'id'=>"client_number"
				
		
		));
		$this->addField($f_client_number);

		$f_delivery_plan_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_plan_date"
		,array(
		
			'id'=>"delivery_plan_date"
				
		
		));
		$this->addField($f_delivery_plan_date);

		$f_delivery_fact_date=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"delivery_fact_date"
		,array(
		
			'id'=>"delivery_fact_date"
				
		
		));
		$this->addField($f_delivery_fact_date);

		$f_product_plan_date=new FieldSQlDate($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_plan_date"
		,array(
		
			'id'=>"product_plan_date"
				
		
		));
		$this->addField($f_product_plan_date);

		$f_client_user_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_user_id"
		,array(
		
			'id'=>"client_user_id"
				
		
		));
		$this->addField($f_client_user_id);

		$f_firm_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"firm_id"
		,array(
		
			'id'=>"firm_id"
				
		
		));
		$this->addField($f_firm_id);

		$f_user_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"user_id"
		,array(
		
			'id'=>"user_id"
				
		
		));
		$this->addField($f_user_id);

		$f_sales_manager_comment=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sales_manager_comment"
		,array(
		
			'id'=>"sales_manager_comment"
				
		
		));
		$this->addField($f_sales_manager_comment);

		$f_client_comment=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_comment"
		,array(
		
			'id'=>"client_comment"
				
		
		));
		$this->addField($f_client_comment);

		$f_marketing_comment=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"marketing_comment"
		,array(
		
			'id'=>"marketing_comment"
				
		
		));
		$this->addField($f_marketing_comment);

		$f_warehouse_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"warehouse_id"
		,array(
		
			'id'=>"warehouse_id"
				
		
		));
		$this->addField($f_warehouse_id);

		$f_deliv_destination_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_destination_id"
		,array(
		
			'id'=>"deliv_destination_id"
				
		
		));
		$this->addField($f_deliv_destination_id);

		$f_deliv_type=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_type"
		,array(
		'required'=>TRUE,
			'id'=>"deliv_type"
				
		
		));
		$this->addField($f_deliv_type);

		$f_deliv_to_third_party=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_to_third_party"
		,array(
		
			'id'=>"deliv_to_third_party"
				
		
		));
		$this->addField($f_deliv_to_third_party);

		$f_deliv_send_sms=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_send_sms"
		,array(
		
			'id'=>"deliv_send_sms"
				
		
		));
		$this->addField($f_deliv_send_sms);

		$f_deliv_add_cost_to_product=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_add_cost_to_product"
		,array(
		
			'id'=>"deliv_add_cost_to_product"
				
		
		));
		$this->addField($f_deliv_add_cost_to_product);

		$f_deliv_period_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_period_id"
		,array(
		
			'id'=>"deliv_period_id"
				
		
		));
		$this->addField($f_deliv_period_id);

		$f_deliv_responsable=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_responsable"
		,array(
		
			'length'=>50,
			'id'=>"deliv_responsable"
				
		
		));
		$this->addField($f_deliv_responsable);

		$f_deliv_responsable_tel=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_responsable_tel"
		,array(
		
			'length'=>15,
			'id'=>"deliv_responsable_tel"
				
		
		));
		$this->addField($f_deliv_responsable_tel);

		$f_tel=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tel"
		,array(
		
			'length'=>15,
			'id'=>"tel"
				
		
		));
		$this->addField($f_tel);

		$f_deliv_vehicle_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_vehicle_id"
		,array(
		
			'id'=>"deliv_vehicle_id"
				
		
		));
		$this->addField($f_deliv_vehicle_id);

		$f_deliv_cost_opt_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_cost_opt_id"
		,array(
		
			'id'=>"deliv_cost_opt_id"
				
		
		));
		$this->addField($f_deliv_cost_opt_id);

		$f_deliv_vehicle_count=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_vehicle_count"
		,array(
		
			'id'=>"deliv_vehicle_count"
				
		
		));
		$this->addField($f_deliv_vehicle_count);

		$f_deliv_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_total"
		,array(
		
			'length'=>15,
			'id'=>"deliv_total"
				
		
		));
		$this->addField($f_deliv_total);

		$f_deliv_total_edit=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_total_edit"
		,array(
		
			'id'=>"deliv_total_edit"
				
		
		));
		$this->addField($f_deliv_total_edit);

		$f_total=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total"
		,array(
		
			'length'=>15,
			'id'=>"total"
				
		
		));
		$this->addField($f_total);

		$f_total_pack=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_pack"
		,array(
		
			'length'=>15,
			'defaultValue'=>"0"
		,
			'id'=>"total_pack"
				
		
		));
		$this->addField($f_total_pack);

		$f_total_quant=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_quant"
		,array(
		
			'length'=>19,
			'id'=>"total_quant"
				
		
		));
		$this->addField($f_total_quant);

		$f_total_volume=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_volume"
		,array(
		
			'length'=>19,
			'id'=>"total_volume"
				
		
		));
		$this->addField($f_total_volume);

		$f_total_weight=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"total_weight"
		,array(
		
			'length'=>19,
			'id'=>"total_weight"
				
		
		));
		$this->addField($f_total_weight);

		$f_printed=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"printed"
		,array(
		
			'id'=>"printed"
				
		
		));
		$this->addField($f_printed);

		$f_ext_order_num=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_order_num"
		,array(
		
			'length'=>11,
			'id'=>"ext_order_num"
				
		
		));
		$this->addField($f_ext_order_num);

		$f_ext_order_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_order_id"
		,array(
		
			'length'=>36,
			'id'=>"ext_order_id"
				
		
		));
		$this->addField($f_ext_order_id);

		$f_ext_ship_num=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_ship_num"
		,array(
		
			'length'=>11,
			'id'=>"ext_ship_num"
				
		
		));
		$this->addField($f_ext_ship_num);

		$f_ext_ship_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_ship_id"
		,array(
		
			'length'=>36,
			'id'=>"ext_ship_id"
				
		
		));
		$this->addField($f_ext_ship_id);

		$f_ext_invoice_num=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_invoice_num"
		,array(
		
			'length'=>11,
			'id'=>"ext_invoice_num"
				
		
		));
		$this->addField($f_ext_invoice_num);

		$f_ext_invoice_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_invoice_id"
		,array(
		
			'length'=>36,
			'id'=>"ext_invoice_id"
				
		
		));
		$this->addField($f_ext_invoice_id);

		$f_ext_invoice_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_invoice_date_time"
		,array(
		
			'id'=>"ext_invoice_date_time"
				
		
		));
		$this->addField($f_ext_invoice_date_time);

		$f_cust_surv_date_time=new FieldSQlDateTime($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cust_surv_date_time"
		,array(
		
			'id'=>"cust_surv_date_time"
				
		
		));
		$this->addField($f_cust_surv_date_time);

		$f_cust_surv_comment=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cust_surv_comment"
		,array(
		
			'id'=>"cust_surv_comment"
				
		
		));
		$this->addField($f_cust_surv_comment);

		$f_product_str=new FieldSQlText($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_str"
		,array(
		
			'id'=>"product_str"
				
		
		));
		$this->addField($f_product_str);

		$f_product_ids=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"product_ids"
		,array(
		
			'id'=>"product_ids"
				
		
		));
		$this->addField($f_product_ids);

		$f_submit_user_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"submit_user_id"
		,array(
		
			'id'=>"submit_user_id"
				
		
		));
		$this->addField($f_submit_user_id);

		$f_paid=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"paid"
		,array(
		
			'defaultValue'=>"FALSE"
		,
			'id'=>"paid"
				
		
		));
		$this->addField($f_paid);

		$f_acc_pko=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"acc_pko"
		,array(
		
			'defaultValue'=>"FALSE"
		,
			'id'=>"acc_pko"
				
		
		));
		$this->addField($f_acc_pko);

		$f_city_route_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"city_route_distance"
		,array(
		
			'id'=>"city_route_distance"
				
		
		));
		$this->addField($f_city_route_distance);

		$f_country_route_distance=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"country_route_distance"
		,array(
		
			'id'=>"country_route_distance"
				
		
		));
		$this->addField($f_country_route_distance);

		$f_destination_to_ttn=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"destination_to_ttn"
		,array(
		
			'defaultValue'=>"FALSE"
		,
			'id'=>"destination_to_ttn"
				
		
		));
		$this->addField($f_destination_to_ttn);

		$f_deliv_expenses=new FieldSQlFloat($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_expenses"
		,array(
		
			'length'=>15,
			'id'=>"deliv_expenses"
				
		
		));
		$this->addField($f_deliv_expenses);

		$f_deliv_pay_bank=new FieldSQlBool($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"deliv_pay_bank"
		,array(
		
			'id'=>"deliv_pay_bank"
				
		
		));
		$this->addField($f_deliv_pay_bank);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_date_time);

		
		
		
	}


	public function create_alter_order($docId){
		$link = $this->getDbLink();
		$head=NULL;
		$items=NULL;
		DOCOrder_Controller::docDataForExt(
			$link,$docId,$head,$items);
		$res = array();
		ExtProg::order($head,$items,$res);
		
		if (!isset($head['ext_order_id']) && isset($res['orderRef'])){
			//Отметка о счете
			$link->query(sprintf(
			"UPDATE doc_orders
			SET
				ext_order_id='%s',
				ext_order_num='%s'
			WHERE id=%d",
			$res['orderRef'],
			$res['orderNum'],
			$docId
			));
		}
		
		//Печатная форма счета из 1с, если сеть eamil
		$ar = $link->query_first(sprintf(
		"SELECT
			h.ext_order_id,
			(u.email IS NOT NULL) AS email_exists
		FROM doc_orders h
		LEFT JOIN users AS u ON u.id=h.client_user_id
		WHERE h.id=%d",
		$docId
		));
		if (!is_array($ar) || !count($ar)){
			throw new Exception("Документ не найден!");
		}
		
		if (!is_null($ref) && $ar['email_exists']=='t'){			
			$tmp_file = ExtProg::print_order($ref,$_SESSION['user_ext_id'],1);
		
			//отправить по мылу счет
			$mail_id = PPEmailSender::addEMail(
				$link,
				sprintf("email_text_order(%d)",$docId),
				array($tmp_file),
				'order'
				);
		}	
	}
	
	public function insert($needId){
		$link = $this->getDbLink();
		$link->query('BEGIN');
		try{	
			if ($_SESSION['role_id']=='client'){
				$state = 'waiting_for_us';
				
				//Префикс нумерации
				$num_q = 
				"SELECT
					const_new_order_prefix_val() || coalesce(MAX( substr(t.number::varchar,length(const_new_order_prefix_val())+1)::int ),0)+1
				FROM doc_orders AS t
				WHERE
					substr(t.number::varchar,1,length(const_new_order_prefix_val()))=const_new_order_prefix_val()";
				
			}
			else{
				//Просто номер
				$num_q = 
				"SELECT
					coalesce( MAX(t.number::int),0)+1
				FROM doc_orders AS t
				WHERE
					substr(t.number::varchar,1,length(const_new_order_prefix_val()))<>const_new_order_prefix_val()";
			
				//По Новому ТЗ от ноября 2016 в производство ТОЛЬКО через кнопку!!!
				$state = 'waiting_for_payment';
			
				/*ТАК БЫЛО РАНЬШЕ: статус зависит от вида работы
				с клиентом - нал/предоплата/отсрочка
				*/
				/*
				$ar = $link->query_first(sprintf(
					"SELECT pay_type FROM clients WHERE id=%d",
					$this->getFieldById('client_id')->getValueForDb()
				));
				if (is_array($ar)&&count($ar)>0){
					$state = ($ar['pay_type']=='with_delay')? 'producing':'waiting_for_payment';
				}
				*/
			}
		
			//ВСТАВКА ДОКУМЕНТА
			$ids_ar = $link->query_first($this->getInsertQuery(TRUE));
			$doc_id = $ids_ar['id'];
			$link->query(
				sprintf("SELECT %s_before_write(%d,%d)",
				$this->getTableName(),$_SESSION['LOGIN_ID'],$doc_id)
			);
			
			$link->query(
				sprintf("UPDATE %s
				SET
					processed = %s,
					number = (%s)
				WHERE id=%d",
				$this->getTableName(),$this->getFieldById('processed')->getValueForDb(),$num_q,
				$doc_id));			
			
			$link->query(
				sprintf("INSERT INTO doc_orders_states
				(
					doc_orders_id,
					date_time,
					state,
					user_id)
				VALUES (
					%d,
					now()::timestamp without time zone,
					'%s'::order_states,
					'%s')",
				$doc_id,
				$state,
				$_SESSION['user_id'])
			);
			if ($state != 'waiting_for_us'){
				//счет
				$this->create_alter_order($doc_id);
			}
			
			$link->query('COMMIT');
		}
		catch(Exception $e){
			$link->query('ROLLBACK');
			throw $e;
		}
		
		return array('id'=>$doc_id);
	}
	public function update(){		
		if ($this->getFieldById('cust_surv_date_time')->getValue()){
			//Маркетолог проводит опрос			
			parent::update();
		}
		else{			
			$doc_id = $this->getFieldById('id')->getOldValueForDb();
			$link = $this->getDbLink();
			
			//Проверка статуса документа
			
			$ar = $link->query_first(sprintf(
			"SELECT
				st.id AS state_id,
				st.state,
				st.state IN (
					'produced',
					'loading',
					'on_way',
					'unloading',
					'closed',
					'canceled',
					'canceled_by_sales_manager',
					'canceled_by_client'
				) AS check,
				(SELECT COALESCE(cl.login_allowed,FALSE)
				FROM clients cl
				WHERE cl.id=o.client_id
				) AS client_login_allowed
			FROM doc_orders_states AS st
			LEFT JOIN doc_orders AS o ON o.id=st.doc_orders_id
			WHERE st.doc_orders_id=%d
			ORDER BY st.date_time DESC
			LIMIT 1
			",$doc_id));
			
			$closed_state = (is_array($ar)&&count($ar)&&$ar['check']=='t');
			/*
			if (is_array($ar)&&count($ar)&&$ar['check']=='t'){
				throw new Exception('Не верный статус документа!');
			}
			*/
			
			/*может поменятся в тек.модуле!!!!*/
			if ($_SESSION['role_id']=='client'){
				$new_state = 'waiting_for_us';
			}
			else if ($_SESSION['role_id']!='client'&&$closed_state){
				//мы изменняем в закрытом статусе: коммент+телефон+дата
				$new_state = NULL;
			}
			else if ($_SESSION['role_id']!='client'
				&&$ar['client_login_allowed']=='f'){
				/* статус НЕ надо менять
				Т.К у клиента нет личного кабинета
				*/
				$new_state = NULL;
			}
			else{
				//может и не надо менять если поменяли только наш коммент
				$new_state = 'waiting_for_client';
			}
			
			$link->query('BEGIN');
			try{
				$create_alter_order = FALSE;
				
				if (isset($new_state)){
					$res = $link->query_first(
						sprintf("INSERT INTO doc_orders_states
						(
							doc_orders_id,
							date_time,
							state,
							user_id)
						VALUES (
							%d,
							now()::timestamp without time zone,
							'%s',
							'%s')
						RETURNING id",
						$doc_id,
						$new_state,
						$_SESSION['user_id'])
					);
				}
				else{
					$res = array('id'=>$ar['state_id']);
				}
				
				$modif = FALSE;
				
				if (is_array($res)&&count($res)){
					//all differances
					
					/* список полей изменение которых
					не передается клиенту
					*/
					$fields_us_only = array('sales_manager_comment');
					$fields_us_modif = FALSE;

					/* список полей изменение которых
					не влияет на счет
					*/
					$fields_order_no_modif = array('delivery_plan_date','deliv_responsable_tel','tel');
					
					$sel = '';
					$sel_no_modif = '';
					
					$fields = $this->getFieldIterator();				
					while($fields->valid()) {
						$field = $fields->current();
						$f_id = $field->getId();
						
						if (!is_null($field->getValue())){
							//значение изменилось
							$f_val = $field->getValueForDb();
							
							if (array_key_exists($f_id,$fields_us_only)){
								//изменилось наше поле - не фиксируем
								$fields_us_modif = TRUE;
							}
							else if (array_key_exists($f_id,$fields_order_no_modif)){
								//изменилось поле которое не изменит счет, но сменит статус
								$sel_no_modif.= ($sel_no_modif=='')? '':' UNION ';
								$sel_no_modif.= sprintf("(SELECT
									%d,
									'%s',
									d.%s::text,
									%s::text
								FROM doc_orders AS d
								WHERE d.id=%d AND d.%s<>%s)",
								$res['id'], $f_id, $f_id,$f_val,
								$doc_id, $f_id, $f_val);							
							}							
							else{							
								$sel.= ($sel=='')? '':' UNION ';
								$sel.= sprintf("(SELECT
									%d,
									'%s',
									d.%s::text,
									%s::text
								FROM doc_orders AS d
								WHERE d.id=%d AND d.%s<>%s)",
								$res['id'], $f_id, $f_id,$f_val,
								$doc_id, $f_id, $f_val);							
							}
						}
						$fields->next();
					}
					if (strlen($sel)){
						$q = sprintf("INSERT INTO doc_orders_head_history
						(doc_orders_states_id, field, old_val, new_val)
						(%s)",$sel);
						$qres = $link->query($q);
						$modif = $modif||($link->affected_rows($qres)>0);
					}
					if (strlen($sel_no_modif)){
						$link->query(
						sprintf("INSERT INTO doc_orders_head_history
						(doc_orders_states_id, field, old_val, new_val)
						(%s)",$sel_no_modif)
						);
					}
									
					//products
					$t_attrs = array('quant','mes_length','mes_width','mes_height','price',
						'pack_exists','pack_in_price');
					$fld = '';
					$old_vals='';
					$cond='';
					foreach($t_attrs as $f){
						$fld.= ($fld=='')? "":"||','||";
						$fld.=sprintf(
						"CASE
							WHEN not_equal(t.%s,t_tmp.%s) THEN '%s'
							ELSE ''
						END",$f,$f,$f);

						$old_vals.= ($old_vals=='')? "":"||','||";
						$old_vals.=sprintf(
						"CASE
							WHEN not_equal(t.%s,t_tmp.%s) THEN t.%s::text
							ELSE ''							
						END",$f,$f,$f);
						
						$cond.= ($cond=='')? "":" OR ";
						$cond.=sprintf("not_equal(t.%s,t_tmp.%s)",$f,$f);						
					}
					$sel = sprintf(
					"SELECT
						%d,
						COALESCE(t_tmp.product_id,t.product_id),
						CASE
							WHEN t_tmp.product_id IS NOT NULL
								AND t.product_id IS NOT NULL THEN
								'modif'
							WHEN t_tmp.product_id IS NULL
								AND t.product_id IS NOT NULL THEN
								'delete'
							WHEN t_tmp.product_id IS NOT NULL
								AND t.product_id IS NULL THEN
								'add'									
						END AS oper,
						%s AS fields,
						%s AS old_vals
						
					FROM doc_orders_t_tmp_products AS t_tmp
					FULL JOIN doc_orders_t_products AS t
						ON t.product_id=t_tmp.product_id AND t.doc_id=%d
							AND t.mes_height=t_tmp.mes_height
							AND t.mes_length=t_tmp.mes_length
							AND t.mes_width=t_tmp.mes_width
					WHERE t_tmp.login_id=%d AND (%s)
					",
					$res['id'],
					$fld,
					$old_vals,
					$doc_id,$_SESSION['LOGIN_ID'],$cond
					);
					
					$q = sprintf("INSERT INTO doc_orders_products_history
					(doc_orders_states_id,
					product_id,oper,fields,old_vals)
					(%s)",$sel);
					$qres = $link->query($q);
					$modif = $modif||($link->affected_rows($qres)>0);
					//throw new Exception($q);				
					
					if (!$modif&&isset($new_state)&&$fields_us_modif){
						/*если изменились только наши атрибуты
						- удалим новый статус*/						
						$link->query(sprintf(
						"DELETE FROM doc_orders_states
							WHERE id=%d",
						$res['id'])
						);
						$create_alter_order = TRUE;
					}
					else if (!$modif&&isset($new_state)){
						/*если подтверждаем без изменений
						то выписываем счет на оплату!*/					
						//сменим статус
						$link->query(sprintf(
						"UPDATE doc_orders_states
							SET state='waiting_for_payment'
							WHERE id=%d",
						$res['id']));
					
						$create_alter_order = TRUE;
					}					
					/*Мы изменили заявку  и не надо отдавать клиенту
					*/
					else if ($modif&&!isset($new_state)){
						$create_alter_order = TRUE;
					}
				}
				/*
				if ($_SESSION['role_id']!='client'){
					//Последний редактировавший
					$this->getFieldById('submit_user_id')->setValue($_SESSION['user_id']);
				}
				*/
				
				//ОБНОВЛЕНИЕ ДОКУМЕНТА
				$link->query(
					sprintf("SELECT %s_before_write(%d,%d)",
					$this->getTableName(),$_SESSION['LOGIN_ID'],
					$this->getFieldById('id')->getOldValue())
				);
				$q = $this->getUpdateQuery();
				if ($q!=''){
					$link->query($q);
				}
				
				if ($new_state=='waiting_for_client'){
					//отправим собщение
					PPEmailSender::addEMail(
						$link,
						sprintf("email_text_order_changed(%d)",$doc_id),
						NULL,
						'order_changed'
						);
				}				
				
				//Выписка - изменение счета
				if ($create_alter_order){
					$this->create_alter_order($doc_id);
				}
				
				$link->query('COMMIT');
				
			}
			catch(Exception $e){
				$link->query('ROLLBACK');
				throw $e;
			}
		}
	}		
}
?>
