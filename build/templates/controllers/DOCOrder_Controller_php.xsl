<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'DOCOrder'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>

require_once(FRAME_WORK_PATH.'basic_classes/ControllerSQLDOCPl.php');

require_once(FRAME_WORK_PATH.'basic_classes/Model.php');
require_once(FRAME_WORK_PATH.'basic_classes/ParamsSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelWhereSQL.php');

require_once('models/DOCOrderCustSurveyDialog_Model.php');
require_once('models/DOCOrderShipmentDialog_Model.php');
require_once('models/DOCOrderDivisDialog_Model.php');
require_once('functions/PPEmailSender.php');
require_once('functions/ExtProg.php');

require_once('common/OSRMV5.php');
require_once('common/decodePolylineToArray.php');
require_once('common/downloader.php');
require_once('common/PDFReport.php');

require_once('common/barcode.php');
require_once('common/barcodegen.1d-php5.v5.2.1/class/BCGFontFile.php');
require_once('common/barcodegen.1d-php5.v5.2.1/class/BCGColor.php');
require_once('common/barcodegen.1d-php5.v5.2.1/class/BCGDrawing.php');
//require_once('common/barcodegen.1d-php5.v5.2.1/class/BCGean13.barcode.php');
//require_once('common/barcodegen.1d-php5.v5.2.1/class/BCGcodabar.barcode.php');
require_once('common/barcodegen.1d-php5.v5.2.1/class/BCGcode128.barcode.php');

require_once('common/money2str.php');

class <xsl:value-of select="@id"/>_Controller extends ControllerSQLDOCPl{
	const ER_WRONG_STATE = 'Заявка в неверном статусе!'; 
	
	const ER_OSRM_ROUTE_QUERY = 'Ошибка получения данных с сервера OSRM!';
	
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	public function get_new_list($pm){
		$this->setListModelId('DOCOrderNewList_Model');
		parent::get_list($pm);
	}
	public function get_current_list($pm){
		if ($_SESSION['role_id']=='representative'){
			$model = new DOCOrderCurrentList_Model($this->getDbLink());
		
			$order = $this->orderFromParams($pm,$model);
			$where = $this->conditionFromParams($pm,$model);
			if (!$where){
				$where = new ModelWhereSQL();
			}
			$from = null; $count = null;
			$limit = $this->limitFromParams($pm,$from,$count);
			$calc_total = ($count>0);
			if ($from){
				$model->setListFrom($from);
			}
			if ($count){
				$model->setRowsPerPage($count);
			}		
		
			//Фильтр по списку складов		
			$field = clone $model->getFieldById('warehouse_id');
			$field->setValue('('.$_SESSION['warehouse_id_list'].')');
			$where->addField($field,'IN',NULL,NULL);
		
			$model->select(FALSE,$where,$order,
				$limit,NULL,NULL,NULL,
				$calc_total,TRUE);
			//
			$this->addModel($model);
		
		}
		else{
			$this->setListModelId('DOCOrderCurrentList_Model');
			parent::get_list($pm);	
		}
	}	
	
	public function get_current_for_representative_list($pm){
		$model = new DOCOrderCurrentForProductionList_Model($this->getDbLink());
		
		$order = $this->orderFromParams($pm,$model);
		$where = $this->conditionFromParams($pm,$model);
		if (!$where){
			$where = new ModelWhereSQL();
		}
		$from = null; $count = null;
		$limit = $this->limitFromParams($pm,$from,$count);
		$calc_total = ($count>0);
		if ($from){
			$model->setListFrom($from);
		}
		if ($count){
			$model->setRowsPerPage($count);
		}		
		
		//Фильтр по списку складов		
		$field = clone $model->getFieldById('warehouse_id');
		$field->setValue('('.$_SESSION['warehouse_id_list'].')');
		$where->addField($field,'IN',NULL,NULL);
		
		$model->select(FALSE,$where,$order,
			$limit,NULL,NULL,NULL,
			$calc_total,TRUE);
		//
		$this->addModel($model);
		
	}	
	
	public function get_current_for_client_list($pm){
		$this->setListModelId('DOCOrderCurrentForClientList_Model');
		parent::get_list($pm);	
	}		
	public function get_current_for_production_list($pm){
		$model = new DOCOrderCurrentForProductionList_Model($this->getDbLink());
		
		$order = $this->orderFromParams($pm,$model);
		$where = $this->conditionFromParams($pm,$model);
		if (!$where){
			$where = new ModelWhereSQL();
		}
		$from = null; $count = null;
		$limit = $this->limitFromParams($pm,$from,$count);
		$calc_total = ($count>0);
		if ($from){
			$model->setListFrom($from);
		}
		if ($count){
			$model->setRowsPerPage($count);
		}		
		
		//Фильтр по списку складов		
		if (!isset($_SESSION['warehouse_id_list'])){
			throw new Exception("У пользователя не задан список складов!");
		}
		$field = clone $model->getFieldById('warehouse_id');
		$field->setValue('('.$_SESSION['warehouse_id_list'].')');
		$where->addField($field,'IN',NULL,NULL);
		
		$model->select(FALSE,$where,$order,
			$limit,NULL,NULL,NULL,
			$calc_total,TRUE);
		//
		$this->addModel($model);
		
	}			
	public function get_closed_list($pm){
		$this->setListModelId('DOCOrderClosedList_Model');
		parent::get_list($pm);		
	}		
	public function insert($pm){
		$pm->setParamValue('user_id',$_SESSION['user_id']);
		if ($_SESSION['role_id']=='client'){
			$pm->setParamValue('client_user_id',$_SESSION['user_id']);
		}
		parent::insert($pm);		
	}	
	private function add_state($doc_id, $state){		
		$this->getDbLinkMaster()->query(sprintf(
		"INSERT INTO doc_orders_states
		(doc_orders_id, date_time, state, user_id)
		VALUES (
			%d,
			now()::timestamp without time zone,
			'%s'::order_states,
			%d)",
		$doc_id,
		$state,
		$_SESSION['user_id'])
		);
	}
	
	private function state_in_list($docId,$state_list){
	
		//get_order_states_descr(state) AS state_descr
		
		$ar = $this->getDbLink()->query_first(sprintf(
		"SELECT
			state IN (".$state_list.") AS check			
		FROM doc_orders_states
		WHERE doc_orders_id=%d
		ORDER BY date_time DESC
		LIMIT 1",
		$docId)
		);
		return !(!is_array($ar) || count($ar)==0 || $ar['check']=='f');
	}
	
	private function check_state($docId,$state_list){
		if (!$this->state_in_list($docId,$state_list)){
			throw new Exception(self::ER_WRONG_STATE);
		}	
	}
	
	public function get_products_descr($pm){
		$link = $this->getDbLink();		
		$params = new ParamsSQL($pm,$link);
		$params->setValidated("doc_id",DT_INT);
		$this->addNewModel(
		sprintf("SELECT		
			products_descr(p,t.mes_length,t.mes_width,t.mes_height)
				AS product_descr,
			m.name AS measure_unit_descr,
			bm.name AS base_measure_unit_descr,
			t.quant_base_measure_unit AS base_quant,
			t.quant AS quant,
			t.pack_exists,
			t.pack_in_price,
			vhl.driver_descr,
			vhl.vl_wt AS vehicle_descr
		FROM doc_orders_t_products AS t
		LEFT JOIN products AS p ON t.product_id = p.id
		LEFT JOIN measure_units AS bm ON bm.id = p.base_measure_unit_id
		LEFT JOIN measure_units AS m ON m.id = t.measure_unit_id
		LEFT JOIN deliveries AS dlv ON dlv.doc_order_id = t.doc_id
		LEFT JOIN vehicles_list AS vhl ON vhl.id = dlv.vehicle_id
		WHERE doc_id=%d
		ORDER BY t.line_number",
		$params->getParamById('doc_id'))
		);
	}
	
	public function cancel($pm){		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$doc_id = $params->getParamById('doc_id');
		
		$this->check_state($doc_id,"'canceled_by_client','canceled_by_sales_manager','canceled','closed','unloading','on_way','shipped','loading','produced','producing'");
		$this->add_state(
			$doc_id,
			($_SESSION['role_id']=='client')? 'canceled_by_client':'canceled_by_sales_manager'
		);
	}
	public function pass_to_production($pm){		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();

		$doc_id = $params->getDbVal('doc_id');

		//Проверка статуса
		$ar = $this->getDbLink()->query_first(sprintf(
		"SELECT
			st.state IN ('new','waiting_for_us','waiting_for_client','waiting_for_payment') AS check,
			(SELECT substr(t.number::text,1,length(const_new_order_prefix_val()))=const_new_order_prefix_val() FROM doc_orders t WHERE t.id=%d) AS number_prefixed
		FROM doc_orders_states AS st		
		WHERE st.doc_orders_id=%d
		ORDER BY st.date_time DESC
		LIMIT 1",
		$doc_id,$doc_id
		));
		
		if (!is_array($ar) || count($ar)==0 || $ar['check']=='f'){
			throw new Exception(self::ER_WRONG_STATE);
		}
			
		$this->getDbLinkMaster()->query('BEGIN');
		try{			
			$this->add_state($doc_id, 'producing');
			
			//скорректируем дату отгрузки
			/* КОРРЕКТИРОВКУ ОТМЕНИЛИ
			$ar = $this->getDbLinkMaster()->query_first(sprintf(
			"select * FROM deliv_date_calc(%d,now()::date) AS (new_date date,correct boolean)",
			$doc_id
			));
			if (is_array($ar) &amp;&amp; count($ar) &amp;&amp; $ar['correct']=='f'){
				//обновление даты отгрузки
				$this->getDbLinkMaster()->query(sprintf(
				"UPDATE doc_orders SET delivery_plan_date = '%s'::date
				WHERE id=%d",
				$ar['new_date'],
				$doc_id
				));
			}
			*/
			
			//Присвоим новую нумерацию ТОЛЬКО если текущая с Н
			if ($ar['number_prefixed'] == 't'){
				$this->getDbLinkMaster()->query(sprintf(
				"UPDATE doc_orders
					SET number = (SELECT coalesce(MAX(t.number::int),0)+1 FROM doc_orders AS t WHERE substr(t.number::varchar,1,1)&lt;&gt;'Н')
				WHERE id=%d",
				$doc_id
				));
			}			
			
			$this->getDbLinkMaster()->query('COMMIT');
		}
		catch(Exception $e){
			$this->getDbLinkMaster()->query('ROLLBACK');
			throw $e;
		}		
			
	}	
	public function cancel_last_state($pm){		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		
		$this->check_state($params->getParamById('doc_id'),"'waiting_for_us','waiting_for_client','waiting_for_payment','producing','produced','closed','canceled','canceled_by_sales_manager','canceled_by_client'");
		
		//проверка числа статусов
		$link = $this->getDbLink();
		$ar = $link->query_first(sprintf(
			"SELECT COUNT(*) AS cnt
			FROM doc_orders_states
			WHERE doc_orders_id=%d",
		$params->getParamById('doc_id'))
		);
	
		if (!is_array($ar)||!count($ar)){
			throw new Exception("У документа нет статусов!");
		}
	
		if ($ar['cnt']==1){
			throw new Exception("Статус документа не может быть отменен!");
		}
	
		$link = $this->getDbLinkMaster();
		$link->query(sprintf(
			"DELETE FROM doc_orders_states
			WHERE id=(SELECT id FROM doc_orders_states
				WHERE doc_orders_id=%d
				ORDER BY date_time DESC
				LIMIT 1
				)",
		$params->getParamById('doc_id'))
		);
	}	
	private function printDoc($docIdForDb){		
		$this->addNewModel(sprintf(
			"SELECT * FROM doc_orders_print_h
			WHERE id=%d",
			$docIdForDb),
			"head"
		);
		
		$this->addNewModel(sprintf(
			"SELECT * FROM doc_orders_print_products
			WHERE doc_id=%d",
			$docIdForDb),
			"products"
		);		
		
		//только графические типы!!!
		$this->addNewModel(
			sprintf("SELECT	
					encode(file_data, 'base64') AS file_data
				FROM doc_order_attachments
				WHERE doc_order_id=%d AND file_inf->>'mime' = 'image/jpeg'"
				,$docIdForDb
			)			
			,'FileList_Model'
		);					

		//Отметка о печати если напечатал производство
		if ($_SESSION['role_id']=='production' || $_SESSION['role_id']=='representative'){
			$this->getDbLinkMaster()->query(sprintf(
				"UPDATE doc_orders SET printed=true
				WHERE id=%d",
				$docIdForDb)
			);
		}	
	}
	public function get_print($pm){		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$this->printDoc($params->getParamById('doc_id'));
	}		
	public function get_print_check($pm){		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$param_doc_id = $params->getParamById('doc_id');
		
		$ar = $this->getDbLink()->query_first(sprintf(
			"SELECT
				CASE
					WHEN substring(h.number,1,length(const_new_order_prefix_val()))=const_new_order_prefix_val() THEN
						'0'|| substring(h.number,length(const_new_order_prefix_val())+1)
					ELSE
						h.number					
				END AS number_for_bar_code,
				h.*,
				d.date_time,
				d.total_weight,
				d.total_volume,
				d.total,
				get_date_str_rus(d.date_time::date) AS date_descr
			FROM doc_orders_print_h AS h
			LEFT JOIN doc_orders d ON d.id=h.id
			WHERE h.id=%d",
			$param_doc_id)
		);
		
		$this->addNewModel(sprintf(
			"SELECT * FROM doc_orders_print_products
			WHERE doc_id=%d",
			$param_doc_id),
			"products"
		);
		
		/*
		Номер документа в баркод всего 13 знаков
		*/
		//$barcode_descr = substr('000000000000',1,12-strlen($ar['number_for_bar_code'])).$ar['number_for_bar_code'];
		//$barcode_descr = $barcode_descr.EAN_check_sum($barcode_descr,13);
		
		//$barcode_descr = 'A'.$ar['number_for_bar_code'].'A';
		$barcode_descr = $ar['number_for_bar_code'];
		
		//**** Генерация баркода ****
		$colorFont = new BCGColor(0, 0, 0);
		$colorBack = new BCGColor(255, 255, 255);		
		
		$code = new BCGcode128(); // Or another class name from the manual
		//$code = new BCGcodabar();
		
		$code->setScale(1); // Resolution
		$code->setThickness(30); // Thickness
		$code->setForegroundColor($colorFont); // Color of bars
		$code->setBackgroundColor($colorBack); // Color of spaces
		$code->setFont(0); // Font (or 0) $font
		$code->parse($barcode_descr); // Text
		$drawing = new BCGDrawing('', $colorBack);
		$drawing->setBarcode($code);
		$drawing->draw();
		ob_start();
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
		$contents = ob_get_contents();
		ob_end_clean();
		//**** Генерация баркода ****
		
		//всю шапку в модель		
		$fields = array();
		foreach($ar as $fid=>$fval){
			array_push($fields,new Field($fid,DT_STRING,array('value'=>$fval)));
		}
		
		array_push($fields,new Field('barcode_descr',DT_STRING,array('value'=>$ar['number_for_bar_code'])));
		array_push($fields,new Field('barcode_img_mime',DT_STRING,array('value'=>'image/png')));
		array_push($fields,new Field('barcode_img',DT_STRING,array('value'=>base64_encode($contents))));
		
		//Сумма строкой
		$total = floatval($ar['total']) + floatval($ar['total_pack']);
		$total += ($ar['deliv_type']=='by_supplier' &amp;&amp; $ar['deliv_add_cost_to_product']!='t')?
			 $ar['deliv_total']  : 0;
		array_push($fields,new Field('total_str',DT_STRING,array('value'=>lcfirst(money2str_ru($total)))));
		
		$this->addModel(new ModelVars(
			array('id'=>'head',
				'values'=>$fields)
			)
		);
		
	}		
	
	public function set_printed($pm){		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$this->getDbLinkMaster()->query(sprintf(
			"UPDATE doc_orders SET printed=true
			WHERE id=%d",
			$params->getDbVal('doc_id'))
		);
	}			
	public function get_print_cnt($pm){
		$print_states = "'produced','producing'";
		$this->addNewModel(
		"SELECT COUNT(*) AS cnt
		FROM doc_orders_print_h h
		WHERE (COALESCE(h.printed,FALSE)=FALSE)
			AND (
			SELECT st.state FROM doc_orders_states st
			WHERE st.doc_orders_id=h.id
			ORDER BY st.date_time DESC LIMIT 1
			) IN (".$print_states.")",
		"get_print_cnt");
	}
	public function get_print_all($pm){
		$link = $this->getDbLinkMaster();
		$link->query('BEGIN');
		try{
			$print_states = "'produced','producing'";
			$h_q = "SELECT * FROM doc_orders_print_h h
			WHERE (COALESCE(h.printed,FALSE)=FALSE)
				AND (
				SELECT st.state FROM doc_orders_states st
				WHERE st.doc_orders_id=h.id
				ORDER BY st.date_time DESC LIMIT 1
				) IN (".$print_states.")";
				
			$model = new ModelSQL($link,array('id'=>'head'));
			$model->query($h_q,TRUE);
			$this->addModel($model);			
			
			$q = "SELECT t.*
				FROM doc_orders_print_products t
				LEFT JOIN doc_orders AS h ON h.id=t.doc_id
				WHERE COALESCE(h.printed,FALSE)=FALSE
				AND
				(SELECT st.state FROM doc_orders_states st
				WHERE st.doc_orders_id=h.id
				ORDER BY st.date_time DESC LIMIT 1
				) IN (".$print_states.")";
			$model = new ModelSQL($link,array('id'=>'products'));
			$model->query($q,TRUE);
			$this->addModel($model);			
							
			//Отметка о печати если напечатал производство
			if ($_SESSION['role_id']=='production' || $_SESSION['role_id']=='representative'){
				
				$link->query(
				"UPDATE doc_orders SET printed=TRUE
				WHERE id IN (SELECT h.id FROM (".$h_q.") AS h)"
				);
				
			}
			$link->query('COMMIT');
		}
		catch(Exception $e){
			$link->query('ROLLBACK');
			throw $e;
		}		
	}		
	public function pay_orders_list($pm){
		$model = new PayOrderList_Model($this->getDbLink());
		$where = $this->conditionFromParams($pm,$model);
		$fields = $this->fieldsFromParams($pm);
		$from = null; $count = null;
		$limit = $this->limitFromParams($pm,$from,$count);
		$calc_total = ($count>0);
		if ($from){
			$model->setListFrom($from);
		}
		if ($count){
			$model->setRowsPerPage($count);
		}
		$order = $this->orderFromParams($pm,$model);
		if (!$where){
			$where = new ModelWhereSQL();
		}
		$f = clone $model->getFieldById('client_id');
		$f->setValue($_SESSION['global_client_id']);
		$where->addField($f);
		$model->select(FALSE,
					$where,$order,$limit,$fields,
					NULL,NULL,
					TRUE,TRUE);
		$this->addModel($model);
	}
	public function fill_cust_surv($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
	
		//throw new Exception(sprintf(
		$this->getDbLinkMaster()->query(sprintf(		
		"SELECT doc_orders_fill_cust_survey(%s,%d)",
		$params->getDbVal('view_id'),
		$_SESSION['LOGIN_ID']
		));
	}
	public function get_cust_survey($pm){
		$model = new DOCOrderCustSurveyDialog_Model($this->getDbLink());
		
		$where = new ModelWhereSQL();
		$f = clone $model->getFieldById('id');
		$f->setValue($pm->getParamValue('id'));
		$where->addField($f);
				
		$model->select(FALSE,
					$where,NULL,NULL,NULL,
					NULL,NULL,
					TRUE,TRUE);
		$this->addModel($model);		
	}
	public function get_divis($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$doc_id = $params->getDbVal('id');
	
		$model = new DOCOrderDivisDialog_Model($this->getDbLink());
		
		$where = new ModelWhereSQL();
		$f = clone $model->getFieldById('main_doc_id');
		$f->setValue($doc_id);
		$where->addField($f);
				
		$model->select(FALSE,
					$where,NULL,NULL,NULL,
					NULL,NULL,
					TRUE,TRUE);
		$this->addModel($model);		
		
		$this->getDbLinkMaster()->query(sprintf(
		"SELECT doc_orders_before_open(%s,%d,%d)",
		$params->getDbVal('view_id'),$_SESSION['LOGIN_ID'],$doc_id
		));
		
		$_SESSION['doc_order_id'] = $doc_id;
	}	
	public function get_shipment($pm){
		$model = new DOCOrderShipmentDialog_Model($this->getDbLink());
		
		$where = new ModelWhereSQL();
		$f = clone $model->getFieldById('id');
		$f->setValue($pm->getParamValue('id'));
		$where->addField($f);
				
		$model->select(FALSE,
					$where,NULL,NULL,NULL,
					NULL,NULL,
					TRUE,TRUE);
		$this->addModel($model);		
	}	
	public function set_ready($pm){	
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$doc_id = $params->getDbVal('doc_id');
		
		$this->check_state(
			$doc_id,
			"'producing'"
			);
		
		$link = $this->getDbLinkMaster();
		$link->query('BEGIN');
		try{			
			//Добавляем статус
			$this->add_state($doc_id,'produced');	
			
			//Копируем количество в отгруженное		
			$link->query(sprintf(
			"UPDATE doc_orders_t_products
			SET quant_confirmed_base_measure_unit=quant_base_measure_unit
			WHERE doc_id=%d",
			$doc_id));
			
			$ar = $link->query_first(sprintf(
				"SELECT (deliv_type='by_client') AS self_deliv
				FROM doc_orders
				WHERE id=%d",
			$doc_id
			));
			if (is_array($ar)&amp;&amp;count($ar)&amp;&amp;$ar['self_deliv']='t'){
				//SMS для снабженца по самовывозу
				$link->query(sprintf(
				"INSERT INTO sms_for_sending
				(tel,body,sms_type)
					(SELECT
						t.cel_phone,
						t.message,
						'client_on_produced'::sms_types
					FROM sms_client_on_produced t
					WHERE t.doc_order_id=%d
					)",$doc_id));

				//Email для снабженца по самовывозу
				PPEmailSender::addEMail(
					$link,
					sprintf("email_text_order_on_produced(%d)",$doc_id),
					NULL,
					'order_on_produced'
				);
			}
			$link->query('COMMIT');
		}
		catch(Exception $e){
			$link->query('ROLLBACK');
			throw $e;
		}		
	}
	public function set_cancel_cause($pm){	
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$doc_id = $params->getParamById('doc_id');
		$link = $this->getDbLinkMaster();
		
		$link->query(sprintf("DELETE FROM doc_orders_cancel_causes
		WHERE doc_id=%d",$doc_id));
		
		$link->query_first(sprintf(
		"INSERT INTO doc_orders_cancel_causes
			(doc_id,cause)
			VALUES (%d,%s)",
		$doc_id,
		$params->getParamById('cause')
		));
		
		$this->add_state($doc_id,
			($_SESSION['role_id']=='client')? 'canceled_by_client':'canceled_by_sales_manager'
		);		
	}
	
	/* расчет количества по количеству в любой единице
	 * из любой единицы, если measure_unit_id_from=0
	 * то пересчет из базовой единицы продукции
	 */
	public function calc_quant($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();	
		
		$mu_from = $params->getParamById('measure_unit_id_from');
		$prod_id = $params->getParamById('product_id');
		if (!strlen($mu_from)||$mu_from=='0'||$mu_from=='null'){
			$ar = $this->getDbLink()->query_first(sprintf(
			"SELECT p.base_measure_unit_id AS id
			FROM products p WHERE p.id=%d",$prod_id)
			);
			if (is_array($ar)&amp;&amp;count($ar)){
				$mu_from = $ar['id'];
			}					
		}
		if ($mu_from=='0'){
			throw new Exception("Base measure unit not found!");
		}
		
		$ar = $this->getDbLink()->query_first(sprintf(
		"SELECT doc_order_calc_quant_in_mu(%d,%d,%d,%d,%d,%f,%d)
			AS quant",
			$prod_id,
			$params->getParamById('measure_unit_id'),
			$params->getParamById('mes_length'),
			$params->getParamById('mes_width'),
			$params->getParamById('mes_height'),			
			$params->getParamById('quant'),
			$mu_from
		));
		$quant = 0;
		if (is_array($ar)&amp;&amp;count($ar)){
			$quant = $ar['quant'];
		}		
		
		$this->addNewModel(sprintf(
		"SELECT %s::numeric AS quant",
		$quant		
		),"calc_quant");
		
		$this->product_measure_units_check(
			$params->getParamById('product_id'),
			$mu_from,
			$params->getParamById('mes_length'),
			$params->getParamById('mes_width'),
			$params->getParamById('mes_height'),		
			$params->getParamById('quant')
		);
		
	}
	
	private function product_measure_units_check(
			$product_id,
			$measure_unit_id,
			$mes_length,
			$mes_width,
			$mes_height,
			$quant
			){	
		$this->addNewModel(sprintf(
		"SELECT * FROM product_measure_units_check(%d,%d,%d,%d,%d,%f)",
			$product_id,
			$mes_length,
			$mes_width,
			$mes_height,
			$measure_unit_id,
			$quant		
		),"product_measure_units_check");	
	}

	public function calc_totals($pm){	
		if ($_SESSION['role_id']=='client'){
			//Установим клиента
			$pm->setParamValue('client_id',$_SESSION['global_client_id']);
		}
		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		
		$this->product_measure_units_check(
			$params->getParamById('product_id'),
			$params->getParamById('measure_unit_id'),
			$params->getParamById('mes_length'),
			$params->getParamById('mes_width'),
			$params->getParamById('mes_height'),		
			$params->getParamById('quant')				
		);
		
		$this->addNewModel(
		sprintf("SELECT * FROM doc_order_totals(
			%d,%d,%d,%d,%d,%d,%f,%d,%s,%s,%s,%s,%s,%f)
		AS (
			base_quant numeric,
			volume_m numeric,
			weight_t numeric,
			price numeric,
			total numeric,
			total_pack numeric)",
		$params->getParamById('warehouse_id'),
		$params->getParamById('client_id'),
		$params->getParamById('product_id'),
		$params->getParamById('mes_length'),
		$params->getParamById('mes_width'),
		$params->getParamById('mes_height'),
		$params->getParamById('quant'),
		$params->getParamById('measure_unit_id'),
		$params->getParamById('pack'),
		$params->getParamById('pack_in_price'),
		$params->getParamById('deliv_to_third_party'),
		$params->getParamById('price_edit'),
		$params->getParamById('price_round'),
		$params->getParamById('price')
		));
	}
	public function calc_deliv_cost($pm){	
		$link = $this->getDbLink();
		$params = new ParamsSQL($pm,$link);
		$params->addAll();
		
		$include_route = ($params->getParamById('include_route')=='1')? 'TRUE':'FALSE';
		
		//Определим склад и категорию ТС
		$const = $link->query_first(sprintf(
		"SELECT
			%d AS deliv_cost_opt_id,
			(SELECT w.production_city_id
			FROM warehouses w
			WHERE w.id=%d) AS production_city_id",
		$params->getParamById('cost_opt_id'),
		$params->getParamById('warehouse_id')
		));
		if (!is_array($const)||!count($const)){
			throw new Exception("Ошибка в запросе.");
		}
		
		//Берем центр зон и если есть то кэш
		$ar = $link->query_first(sprintf(
		"SELECT * FROM doc_order_deliv_cache(%d,%d,%s,%d,%d)",
		$params->getParamById('warehouse_id'),
		$params->getParamById('client_destination_id'),		
		$include_route,
		$const['deliv_cost_opt_id'],
		$const['production_city_id']
		));
		
		/** выходные данные
		 * берем либо из кэша либо заново
		 * прокладываем маршрут
		 */
		$out_city_route='NULL';
		$out_city_route_distance=0;
		$out_city_cost=0;
		$out_city_cost2=0;
		$out_country_route='NULL';
		$out_country_route_distance=0;
		$out_country_cost=0;
		$out_country_cost2=0;
		
		if ($ar['city_route_distance']||$ar['country_route_distance']){
			//!!!cache data!!!
			$out_city_route = $ar['city_route'];
			$out_city_route_distance = $ar['city_route_distance'];
			$out_city_cost = $ar['city_cost'];
			$out_country_route = $ar['country_route'];
			$out_country_route_distance = $ar['country_route_distance'];
			$out_country_cost = $ar['country_cost'];		
			$out_city_cost2 = $ar['city_cost2'];
			$out_country_cost2 = $ar['country_cost2'];
		}
		else if (is_array($ar)&amp;&amp;count($ar)){
			if (!$ar['wh_near_road_lon']
			||!$ar['wh_near_road_lat']){
				throw new Exception('Не определена производственная зона!');
			}
			if (!$ar['dest_near_road_lon']
			||!$ar['dest_near_road_lat']){
				throw new Exception('Не определена зона клиента!');
			}
			
			$osrm = new OSRMV5(OSRM_PROTOCOLE,OSRM_HOST,OSRM_PORT);
				
			//Маршрут от склада до клиента
			$route = $osrm->getRoute(
				array(
					$ar['wh_near_road_lon'].','.$ar['wh_near_road_lat'],
					$ar['dest_near_road_lon'].','.$ar['dest_near_road_lat']
				),
				'json',
				NULL,
				array("geometries=polyline6")									
			);
			
			if (!$route->routes || !count($route->routes) || !$route->routes[0]->geometry){
				throw new Exception(self::ER_OSRM_ROUTE_QUERY);
			}
			
			/* Собираем точки маршрута в линию */
			$q_points = '';
			$points = decodePolylineToArray($route->routes[0]->geometry);
			foreach($points as $p){
				$q_points.=($q_points=='')? '':',';
				$q_points.=sprintf("ST_PointFromText('POINT(%s %s)',4326)",$p[1],$p[0]);
			}
			
			/** Рсакладываем маршрут на город/не город
			 * и берем тарифы
			 */
			$ar = $link->query_first(sprintf(
			"WITH
				line AS (
					SELECT ST_MakeLine(ARRAY[%s]) AS v
				),
				costs AS(
					SELECT
						t.deliv_cost_type,						
						t.cost,
						t.cost2
					FROM deliv_costs t
					WHERE t.production_city_id=%d
						AND t.deliv_cost_opt_id=%d
				)
			SELECT
				--city route
				replace(replace(ST_AsText(ST_Intersection((SELECT t.v FROM line t),pc.zone)),'LINESTRING(',''),')','')
				AS city_route,
				
				--Тариф city
				(SELECT t.cost
				FROM costs t
				WHERE t.deliv_cost_type='city'
				) AS city_cost,

				--country route
				replace(replace(ST_AsText(ST_Difference((SELECT t.v FROM line t),pc.zone)),'LINESTRING(',''),')','')
				AS country_route,
						
				--Тариф country
				(SELECT t.cost
				FROM costs t
				WHERE t.deliv_cost_type='country'
				) AS country_cost,
				
				--Тариф city себест
				(SELECT t.cost2
				FROM costs t
				WHERE t.deliv_cost_type='city'
				) AS city_cost2,
				--Тариф country себест
				(SELECT t.cost2
				FROM costs t
				WHERE t.deliv_cost_type='country'
				) AS country_cost2
				
				
			FROM warehouses AS w
			LEFT JOIN production_cities AS pc ON pc.id=w.production_city_id
			WHERE w.id=%d			
			",
			$q_points,
			$const['production_city_id'],
			$const['deliv_cost_opt_id'],			
			$params->getParamById('warehouse_id')
			));
			
			/** Берем маршрут от начала до конца города
			 * для расчета расстояния
			 */
			if ($ar['city_route']!='GEOMETRYCOLLECTION EMPTY'){
				$ar['city_route'] = str_replace('MULTI(','',$ar['city_route']);
				$ar['city_route'] = str_replace('(','',$ar['city_route']);
			
				$city_route_ar = explode(',',$ar['city_route']);
				$city_route_lonlat_from = explode(' ',$city_route_ar[0]);
				$city_route_lonlat_to = explode(' ',$city_route_ar[count($city_route_ar)-1]);
				$route = $osrm->getRoute(
					array(
						$city_route_lonlat_from[0].','.$city_route_lonlat_from[1],
						$city_route_lonlat_to[0].','.$city_route_lonlat_to[1]
					),
					'json',
					NULL,
					array("geometries=polyline6")					
				);
				if (!$route->routes || !count($route->routes) || !$route->routes[0]->distance){
					throw new Exception(self::ER_OSRM_ROUTE_QUERY);
				}
				$out_city_route	= $ar['city_route'];
				$out_city_route_distance = $route->routes[0]->distance;
			}
			/** Берем маршрут от конца города до зоны клиента
			 * для расчета расстояния
			 */
			if ($ar['country_route']!='GEOMETRYCOLLECTION EMPTY'){
				$ar['country_route'] = str_replace('MULTI(','',$ar['country_route']);
				$ar['country_route'] = str_replace('(','',$ar['country_route']);
				$country_route_ar = explode(',',$ar['country_route']);
				$country_route_lonlat_from = explode(' ',$country_route_ar[0]);
				$country_route_lonlat_to = explode(' ',$country_route_ar[count($country_route_ar)-1]);
				$route = $osrm->getRoute(
					array(
						$country_route_lonlat_from[0].','.$country_route_lonlat_from[1],
						$country_route_lonlat_to[0].','.$country_route_lonlat_to[1]
					),
					'json',
					NULL,
					array("geometries=polyline6")					
				);
				if (!$route->routes || !count($route->routes) || !$route->routes[0]->distance){
					throw new Exception(self::ER_OSRM_ROUTE_QUERY);
				}
				$out_country_route = $ar['country_route'];
				$out_country_route_distance = $route->routes[0]->distance;
			}
			
			/** Cache
			 * Возможна коллизия: кто-то уже вставил
			 * такие-же данные в кэш!!!
			 * !!!РАБОТАЕТ ПРАВИЛО!!!
			*/
			$this->getDbLinkMaster()->query(sprintf(
			"INSERT INTO deliv_distance_cache
			(warehouse_id,client_destination_id,
			city_route,city_route_distance,
			country_route,country_route_distance)
			VALUES (%d,%d,
				%s,%d,
				%s,%d)
			",
			$params->getParamById('warehouse_id'),
			$params->getParamById('client_destination_id'),
			($out_city_route=='NULL')? $out_city_route:sprintf("St_GeomFromText('LINESTRING(%s)',4326)",$out_city_route),
			$out_city_route_distance,
			($out_country_route=='NULL')? $out_country_route:sprintf("St_GeomFromText('LINESTRING(%s)',4326)",$out_country_route),
			$out_country_route_distance
			));
			
			if ($include_route!='TRUE'){
				$out_city_route='';
				$out_country_route='';
			}
			$out_city_cost = $ar['city_cost'];
			$out_city_cost2 = $ar['city_cost2'];
		}
		
		//Стоимость межгород за КМ
		$out_country_cost = $ar['country_cost']*round($out_country_route_distance/1000);
		$out_country_cost2 = $ar['country_cost2']*round($out_country_route_distance/1000);
		
		$m = new Model(array('id'=>'calc_deliv_cost'));
		$m->addField(new Field('city_route',DT_STRING,array('value'=>$out_city_route)));
		$m->addField(new Field('city_route_distance',DT_INT,array('value'=>$out_city_route_distance)));
		$m->addField(new Field('city_cost',DT_FLOAT,array('value'=>$out_city_cost)));
		$m->addField(new Field('city_cost2',DT_FLOAT,array('value'=>$out_city_cost2)));
		$m->addField(new Field('country_route',DT_STRING,array('value'=>$out_country_route)));
		$m->addField(new Field('country_route_distance',DT_INT,array('value'=>$out_country_route_distance)));
		$m->addField(new Field('country_cost',DT_FLOAT,array('value'=>$out_country_cost)));
		$m->addField(new Field('country_cost2',DT_FLOAT,array('value'=>$out_country_cost2)));
		$m->addField(new Field('total_cost',DT_FLOAT,array('value'=>$out_country_cost+$out_city_cost)));
		$m->addField(new Field('total_cost2',DT_FLOAT,array('value'=>$out_country_cost2+$out_city_cost2)));
		$m->insert();
		$this->addModel($m);
	}
	public function get_object($pm){
		$link = $this->getDbLink();
		$par = new ParamsSQL($pm,$link);
		$par->setValidated('id');
	
		$this->addNewModel(sprintf(
		"SELECT
			h.field,			
			CASE
				WHEN h.field='client_id' THEN cl.name::text
				WHEN h.field='client_user_id' THEN cl_u.name::text
				WHEN h.field='user_id' THEN u.name::text
				WHEN h.field='firm_id' THEN f.name::text
				WHEN h.field='warehouse_id' THEN w.name::text
				WHEN h.field='deliv_period_id' THEN dvp.name::text
				WHEN h.field='deliv_type' THEN get_delivery_types_descr(h.old_val::delivery_types)::text
				WHEN h.field='deliv_destination_id' THEN dest.address::text
				WHEN h.field='deliv_vehicle_id' THEN v.plate::text
				WHEN h.field='delivery_plan_date' THEN date8_descr(h.old_val::date)
				ELSE h.old_val
			END AS old_val
			
		FROM doc_orders_head_history AS h
		LEFT JOIN clients AS cl ON cl.id=text_to_int(h.old_val,0) AND h.field='client_id'
		LEFT JOIN users AS cl_u ON cl_u.id=text_to_int(h.old_val,0) AND h.field='client_user_id'
		LEFT JOIN users AS u ON u.id=text_to_int(h.old_val,0) AND h.field='user_id'
		LEFT JOIN firms AS f ON f.id=text_to_int(h.old_val,0) AND h.field='firm_id'
		LEFT JOIN warehouses AS w ON w.id=text_to_int(h.old_val,0) AND h.field='warehouse_id'
		LEFT JOIN delivery_periods AS dvp ON dvp.id=text_to_int(h.old_val,0) AND h.field='deliv_period_id'
		LEFT JOIN client_destinations_list AS dest ON dest.id=text_to_int(h.old_val,0) AND h.field='deliv_destination_id'
		LEFT JOIN vehicles AS v ON v.id=text_to_int(h.old_val,0) AND h.field='deliv_vehicle_id'		
		WHERE h.doc_orders_states_id=
				(SELECT st.id
				FROM doc_orders_states st
				WHERE st.doc_orders_id=%d
				ORDER BY st.date_time DESC
				LIMIT 1)",
		$par->getParamById('id')
		),'head_history');
		/*
		$this->addNewModel(sprintf("
		SELECT
			product_id,
			oper,
			string_to_array(fields,',','') AS fileds,
			string_to_array(old_vals,',','') AS old_vals
		FROM doc_orders_products_history ph WHERE ph.doc_orders_states_id=(SELECT st.id
						FROM doc_orders_states st
						WHERE st.doc_orders_id=%d
						ORDER BY st.date_time DESC
						LIMIT 1)		
		",$par->getParamById('id')),'prod_history');
		*/
		parent::get_object($pm);	
	}
	
	public function recalc_product_prices($pm){
		if ($_SESSION['role_id']=='client'){
			//Установим клиента
			$pm->setParamValue('client_id',$_SESSION['global_client_id']);
		}	
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		
		$deliv_add_cost_to_product = ($pm->getParamValue('deliv_add_cost_to_product')=='true');
		
		$lmast = $this->getDbLinkMaster();
		$lmast->query(sprintf(
		"UPDATE doc_orders_t_tmp_products tmp
		SET
			quant_base_measure_unit = data.base_quant,
			volume = data.volume_m,
			weight = data.weight_t,			
			total = tmp.total_no_deliv,
			price = tmp.price_no_deliv,
			total_pack = data.total_pack,
			total_deliv = 0
		FROM 
			(SELECT * FROM doc_order_totals_all(%d,%d,%s,%s,%s) ) AS data		
		WHERE tmp.view_id=%s AND tmp.line_number = data.line_number",
		$params->getDbVal('warehouse_id'),
		$params->getDbVal('client_id'),
		$params->getDbVal('view_id'),
		$params->getDbVal('deliv_to_third_party'),
		($_SESSION['role_id']=='client')? 'TRUE':'FALSE',
		$params->getDbVal('view_id')		
		));
		
		if ($deliv_add_cost_to_product){
			//Распределить пропорционально
			$deliv_cost = floatval($pm->getParamValue('deliv_cost'));
			if ($deliv_cost &gt; 0){
				$l = $this->getDbLink();
				$id = $l->query(sprintf(
				"SELECT
					line_number,
					quant_base_measure_unit AS quant,
					price,
					coalesce(total,0)-coalesce(total_deliv,0) AS total,
					
					(SELECT
						MAX(line_number)
					FROM doc_orders_t_tmp_products
					WHERE view_id=%s
					) AS last_line,
					
					(SELECT
						SUM(coalesce(total,0)-coalesce(total_deliv,0))
					FROM doc_orders_t_tmp_products
					WHERE view_id=%s
					) AS total_total
					
				FROM doc_orders_t_tmp_products
				WHERE view_id=%s
				ORDER BY line_number",
				$params->getDbVal('view_id'),
				$params->getDbVal('view_id'),
				$params->getDbVal('view_id')
				));
				$lmast->query("BEGIN");
				try{
					$deliv_cost_tot = 0;
					while ($ar = $l->fetch_array($id)){
						$deliv_line_cost = round($ar['total']/$ar['total_total'] * $deliv_cost,2);
						$deliv_cost_tot+= $deliv_line_cost;
						//throw new Exception(' line_cost='.$deliv_line_cost.' total='.$ar['total']);
						$total = $ar['total'] + $deliv_line_cost;
						
						if ($ar['line_number']==$ar['last_line']
						&amp;&amp; $deliv_cost_tot!=$deliv_cost){
							//последняя строка
							//throw new Exception('Correcting last line! total='.$total.' line_cost='.$deliv_line_cost.' deliv_cost_tot='.$deliv_cost_tot);
							$total = $total - $deliv_cost_tot + $deliv_cost;
						}						
						$price = round($total/$ar['quant'],2);
						//throw new Exception('price='.$price.' total='.$total.' line_cost='.$deliv_line_cost.' tot_no_deliv='.$ar['total']);
						$lmast->query(sprintf(
						//throw new Exception(sprintf(
						"UPDATE doc_orders_t_tmp_products
						SET
							price=%f,
							total=%f,
							total_deliv=%f
						WHERE view_id=%s AND line_number=%d
						",
						$price,$total,$deliv_line_cost,
						$params->getDbVal('view_id'),$ar['line_number']
						));
					}
					$lmast->query("COMMIT");
				}
				catch (Exception $e){
					$lmast->query("ROLLBACK");
					throw $e;
				}			
			}			
		}
	}
	public function before_open($pm){
		$_SESSION['doc_order_id'] = $pm->getParamValue('doc_id');
		parent::before_open($pm);
	}
	
	public static function docDataForExt($link,$docId,&amp;$head,&amp;$items){
		$q_id = $link->query(sprintf(
		"SELECT
			t.*,
			(SELECT u.ext_id FROM users u
			WHERE u.id=%d
			) AS user_ref
		FROM doc_orders_data_for_ext t
		WHERE doc_id=%d",
		$_SESSION['user_id'],$docId));
		
		$head = NULL;
		$items = array();
		while ($ar = $link->fetch_array($q_id)){
			if (is_null($head)){
				$head = array(
					'date'=>date('YmdHis'),
					'firm_ref'=>$ar['firm_ref'],
					'user_ref'=>$ar['user_ref'],
					'client_ref'=>$ar['client_ref'],
					'gruzopoluchatel_ref'=>$ar['gruzopoluchatel_ref'],
					'warehouse_ref'=>$ar['warehouse_ref'],
					'warehouse_address'=>$ar['warehouse_address'],
					'deliv_total'=>$ar['deliv_total'],
					'deliv_type'=>$ar['deliv_type'],
					'pack_total'=>$ar['pack_total'],
					'deliv_address'=>$ar['deliv_address'],
					'driver_ref'=>$ar['driver_ref'],
					'driver_name'=>$ar['driver_name'],
					'driver_cel_phone'=>$ar['driver_cel_phone'],
					'driver_drive_perm'=>$ar['driver_drive_perm'],
					'carrier_ref'=>$ar['carrier_ref'],
					'vh_plate'=>$ar['vh_plate'],
					'vh_model'=>$ar['vh_model'],
					'vh_vol'=>$ar['vh_vol'],
					'vh_load_weight_t'=>$ar['vh_load_weight_t'],
					'vh_trailer_plate'=>$ar['vh_trailer_plate'],
					'vh_trailer_model'=>$ar['vh_trailer_model'],
					'ext_order_id'=>$ar['ext_order_id'],
					'ext_order_num'=> isset($ar['ext_order_num'])? $ar['ext_order_num'] : null,
					'ext_ship_id'=>$ar['ext_ship_id'],
					'client_comment'=>$ar['client_comment'],
					'sales_manager_comment'=>$ar['sales_manager_comment'],
					'pay_cash'=>$ar['pay_cash'],
					'deliv_vehicle_count'=>$ar['deliv_vehicle_count'],
					'number'=>$ar['number'],
					'firm_nds'=>$ar['firm_nds'],
					'delivery_plan_date'=>isset($ar['delivery_plan_date'])? $ar['delivery_plan_date'] : null,
					'total_volume'=>$ar['total_volume'],
					'total_weight'=>$ar['total_weight'],
					'deliv_expenses'=>$ar['deliv_expenses'],
					'client_contract_ext_id'=>$ar['client_contract_ext_id'],
					'firm_bank_acc_ext_id'=>$ar['firm_bank_acc_ext_id'],
					'deliv_add_cost_to_product'=>$ar['deliv_add_cost_to_product'],
					'deliv_add_cost_to_product_total'=>$ar['deliv_add_cost_to_product_total'],
					'order_num'=>$ar['order_num'],
					'sale_store_address_code'=>$ar['sale_store_address_code'],
					'batch_num'=>$ar['batch_num'],
					'prod_batches'=>$ar['prod_batches']
					);
			}
			if ($ar['product_name']){
				//throw new Exception("quant_confirmed=".$ar['quant_confirmed'].' quant='.$ar['quant']);
				array_push($items,array(
					'product_name'=>$ar['product_name'],
					'group_name'=>$ar['group_name'],
					'product_group_ref'=>$ar['product_group_ref'],
					'fin_group'=>$ar['fin_group'],
					'analit_group'=>$ar['analit_group'],
					'mes_length'=>$ar['mes_length'],
					'mes_width'=>$ar['mes_width'],
					'mes_height'=>$ar['mes_height'],
					'measure_unit_ref'=>$ar['measure_unit_ref'],
					'measure_unit_k'=>$ar['measure_unit_k'],
					'base_measure_unit_ref'=>$ar['base_measure_unit_ref'],					
					'quant'=>$ar['quant'],
					'price'=>$ar['price'],
					'total'=>$ar['total']
					));
			}
		}
		$link->free_result($q_id);
	}
	
	public function cancel_order($orderId){
		$ar = $this->getDbLink()->query_first(sprintf(
		"SELECT
			CASE
				WHEN state IN ('new','waiting_for_us','waiting_for_client','waiting_for_payment','producing','produced') THEN
					'canceled_by_sales_manager'::order_states
				WHEN state IN ('closed','canceled','canceled_by_sales_manager','canceled_by_client') THEN
					NULL
				ELSE 'closed'::order_states
			END AS new_state
		FROM doc_orders_states WHERE doc_orders_id=%d ORDER BY date_time DESC LIMIT 1", $orderId
		));
		
		if (is_array($ar) &amp;&amp; count($ar) &amp;&amp; isset($ar['new_state'])){
			$this->add_state($orderId, $ar['new_state']);
			//throw new Exception("Setting new state:".$ar['new_state']);
		}
		else{
			throw new Exception(self::ER_WRONG_STATE);
		}
	
	}
	
	public function set_closed($pm){		
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();		
		
		$this->cancel_order($p->getDbVal('doc_id'));
		
		/*
		$this->add_state(
			$params->getParamById('doc_id'),
			'closed'
		);
		*/
	}
	public function update_paid($pm,$field,$paid){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		
		$this->check_for_pko($params->getDbVal('doc_id'));
		
		//Проверять будет не тип клиента cl.pay_type а атрибут фирмы f.cash
		$ar = $this->getDbLink()->query_first(sprintf(
		"SELECT
			coalesce(f.cash,FALSE) AS cash,
			f.name
		FROM firms f
		WHERE f.id=(SELECT o.firm_id
				FROM doc_orders o
				WHERE o.id=%d)",
		$params->getDbVal('doc_id')
		));
		if (!is_array($ar) || !count($ar)){
			throw new Exception("Не нашли организацию!");
		}
		if ($ar['cash'] != 't'){
			throw new Exception(sprintf('Фирма %s, указанная в заявке, не работат по наличному расчету!',
				$ar['name']));
		}
		
		$this->getDbLinkMaster()->query(sprintf(
		"UPDATE doc_orders SET %s=%s
		WHERE id=%d",
		$field,
		$paid,
		$params->getDbVal('doc_id')
		));
	}
	public function set_paid($pm){
		$this->update_paid($pm,'paid','TRUE');
	}
	
	private function check_for_pko($docId){
		$ar = $this->getDbLinkMaster()->query_first(sprintf(
		"SELECT coalesce(acc_pko,FALSE) AS acc_pko FROM doc_orders WHERE id=%d",
		$docId
		));
	
		if (count($ar) &amp;&amp; $ar['acc_pko']=='t'){
			throw new Exception('По документу уже выписан ПКО!');
		}	
	}
	
	public function set_not_paid($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();

		$this->check_for_pko($params->getDbVal('doc_id'));

		$this->getDbLinkMaster()->query(sprintf(
		"UPDATE doc_orders
		SET paid=FALSE,paid_by_bank=FALSE,acc_pko=FALSE,acc_pko_total=0
		WHERE id=%d",
		$params->getDbVal('doc_id')
		));
	
	}

	public function set_paid_by_bank($pm){
		$this->update_paid($pm,'paid_by_bank','TRUE');
	}
	
	public function set_shipped($pm){
		$l = $this->getDbLink();//read-only
		$params = new ParamsSQL($pm,$l);
		$params->addAll();
		
		$doc_id = $params->getDbVal('doc_id');
		$view_id = $params->getDbVal('view_id');
		
		$driver_id = (strtolower($params->getDbVal('driver_id'))=='null'||!$params->getDbVal('driver_id'))? 0:$params->getDbVal('driver_id');
		$deliv_vehicle_count = (strtolower($params->getDbVal('deliv_vehicle_count'))=='null'||!$params->getDbVal('deliv_vehicle_count'))? 'NULL':$params->getDbVal('deliv_vehicle_count');
		$vehicle_id = (strtolower($params->getDbVal('vehicle_id'))=='null'||!$params->getDbVal('vehicle_id'))? 0:$params->getDbVal('vehicle_id');
		$deliv_destination_id = (strtolower($params->getDbVal('deliv_destination_id'))=='null'||!$params->getDbVal('deliv_destination_id'))? 0:$params->getDbVal('deliv_destination_id');
		$destination_to_ttn = (strtolower($params->getDbVal('destination_to_ttn'))=='null')? 'NULL':$params->getDbVal('destination_to_ttn');
		$batch_num = (strtolower($params->getDbVal('batch_num'))=='null')? 'NULL' : $params->getDbVal('batch_num');
		
		$this->check_state($doc_id,"'produced'");
		
		//проверка на запрет
		if ($_SESSION['client_ship_not_allowed']){
			throw new Exception("Отгрузка клиенту запрещена!");
		}
	
		$link = $this->getDbLinkMaster();
		$link->query('BEGIN');		
		try{
			//batches
			$link->query(sprintf("DELETE FROM doc_orders_t_prod_batches WHERE doc_id=%d", $doc_id));
			$link->query(sprintf(
			"INSERT INTO doc_orders_t_prod_batches
				(doc_id,line_number, ext_id, batch_descr)
				(SELECT
					%d,line_number,
					ext_id, batch_descr
				FROM doc_orders_t_tmp_prod_batches
				WHERE view_id=%s)",
			$doc_id,
			$view_id));

			//Отгрузка в БД
			$link->query(sprintf(
				"SELECT doc_orders_set_shipped(%d, %s, %d, %s, %d, %d, %s, %s)",
				$doc_id,
				$params->getDbVal('view_id'),
				$driver_id,
				$deliv_vehicle_count,
				$vehicle_id,
				$deliv_destination_id,
				$destination_to_ttn,
				$batch_num
			));
			
			//ДАННЫЕ ДЛЯ 1С
			$head=NULL;
			$items=NULL;
			DOCOrder_Controller::docDataForExt($link,$doc_id,$head,$items);				

			$ar = $link->query_first(sprintf(
			"SELECT
				cl.email_sert,
				cl.pay_type,
				h.deliv_type,
				u.email,
				vh.tracker_id,
				dest.zone_center AS client_zone,
				w.zone AS production_zone
			FROM doc_orders AS h
			LEFT JOIN clients cl ON cl.id=h.client_id
			LEFT JOIN users u ON u.id=h.client_user_id
			LEFT JOIN deliveries dlv ON dlv.doc_order_id = h.id
			LEFT JOIN client_destinations dest ON dest.id = h.deliv_destination_id
			LEFT JOIN warehouses w ON w.id = h.warehouse_id			
			LEFT JOIN vehicles vh ON vh.id = dlv.vehicle_id
			WHERE h.id=%d",$doc_id)
			);		
			
			/*по самовывозу статус - closed
			Если нет трэкера (нет распределения по авто) или нет зоны клиента или нет нашей зоны завода - closed
			во всех остальных случаях - shipped
			*/	
			if ($ar['deliv_type']=='by_client'){
				$new_state = 'closed';
			}		
			else if ($ar['deliv_type']!='by_client'
			&amp;&amp; (!$ar['tracker_id'] || !$ar['client_zone'] || !$ar['production_zone'])){
				$new_state = 'closed';
			}
			else{
				$new_state = 'closed';//было shipped
			}
			$this->add_state($doc_id,$new_state);
			
			/* Если в безе уже есть отметка о реализации из 1с - значит отгрузка уже была!!!
			и все смс/емайлы отправлялись, просто был обрыв и браузер об этом не знает,
			в этом случае ничего не делаем!!!
			*/
			if (is_null($head['ext_ship_id']) || !strlen($head['ext_ship_id'])){
				//Паспорт качества
				$sert_tmp_file = NULL;
				$this->makePassport($link,$doc_id);
				if ($ar['email_sert']=='t'){
					$sert_tmp_file = $this->dump_passport_to_file($doc_id);
				}
			
				//SMS снабженцу
				$link->query(sprintf(
				"INSERT INTO sms_for_sending
				(tel,body,sms_type)
					(SELECT
						t.cel_phone,
						t.message,
						'client_on_deliv'::sms_types
					FROM sms_client_on_deliv t
					WHERE t.doc_order_id=%d
					)",
				$doc_id
				));
			
				//Выписка документов в 1с			
				$res = array();
				ExtProg::sale($head,$items,$res);
			
				$link->query(sprintf(
				"UPDATE doc_orders
				SET
					delivery_fact_date = now()::timestamp,
					ext_ship_id='%s',
					ext_ship_num='%s',
					ext_invoice_id='%s',
					ext_invoice_num='%s',
					ext_invoice_date_time='%s'
				WHERE id=%d",
				$res['naklRef'],
				$res['naklNum'],
				$res['invRef'],
				$res['invNum'],
				date('Y-m-d H:i:s'),
				$doc_id
				));
			
			}			
			
			if ($ar['pay_type']!='cash'){
				//документа на email с печатями
				$ttn_attrs = $l->query_first(sprintf(
					"SELECT * FROM doc_orders_ttn
					WHERE doc_id=%d",
				$doc_id
				));		
		
				//zip file with print forms.
				$tmp_file = ExtProg::print_shipment(
						$ttn_attrs['ext_ship_id'],
						$ttn_attrs,
						$_SESSION['user_ext_id'],
						0,
						array('toFile'=>true,'name'=>uniqid().'.zip')
				);
				$_SESSION['ship_doc_'.$doc_id] = $tmp_file;
			
				//Только если еще не отправляли
				if (is_null($head['ext_ship_id']) || !strlen($head['ext_ship_id'])){
					$attch = array($tmp_file);
					if (!is_null($sert_tmp_file)){
						array_push($attch,$sert_tmp_file);
					}
					$mail_id = PPEmailSender::addEMail(
						$link,
						sprintf("email_text_shipment(%d)",$doc_id),
						$attch,
						'shipment'
					);
				}
			}
			
			$link->query('COMMIT');
		}
		catch(Exception $e){
			$link->query('ROLLBACK');
			throw $e;
		}		
	}
	
	public function get_ship_docs($pm){
		$l = $this->getDbLink();//read-only
		$params = new ParamsSQL($pm,$l);
		$params->addAll();
		
		$doc_id = $params->getParamById('doc_id');
		if (isset($_SESSION['ship_doc_'.$doc_id])){
			$tmp_file = $_SESSION['ship_doc_'.$doc_id];
			//need to parse file $tmp_file if its length less then X bytes as XML
			//as it is not a regular zip file, but xml which may contain an error.
			//If so, throw exception.
			if(filesize($tmp_tile)&lt;500){
				$contents = file_get_contents($tmp_file);
				$contents = @iconv('Windows-1251','UTF-8',$contents);
				try{
					$xml = new SimpleXMLElement($contents);
					if ($xml['status']=='false'){
						$e = (string) $xml->error;
						throw new Exception($e);
					}							
				}catch(Exception $e){
					//do nothing here, just let the file be downloaded.
					<!-- throw new Exception('Ошибка парсинга ответа 1с:'.$e->getMessage().' Строка: '.$contents); -->
				}
			}
			ob_clean();
			downloadFile($tmp_file,'application/zip','attachment;','Отгрузочные документы.zip');
			unlink($tmp_file);
			unset($_SESSION['ship_doc_'.$doc_id]);
		}
		else{
			throw new Exception("Печатные формы не сформированы!");
		}
	}
	public function print_ship_docs($pm){
		$l = $this->getDbLink();
		$params = new ParamsSQL($pm,$l);
		$params->setValidated('doc_id',DT_INT);
		$doc_id = $params->getParamById('doc_id');
		
		$ar = $l->query_first(sprintf(
			"SELECT * FROM doc_orders_ttn
			WHERE doc_id=%d",
		$doc_id
		));		
		
		if (!is_array($ar)||!count($ar)){
			throw new Exception("Накладная не найдена!");
		}
		if (!$ar['ext_ship_id']){
			throw new Exception("Накладная не выписана!");
		}
		ExtProg::print_shipment($ar['ext_ship_id'],
			$ar,
			$_SESSION['user_ext_id'],
			0,
			array('name'=>'Отгрузочные документы.pdf','disposition'=>'inline')
		);
	}
	
	public static function getExtRef($link,$docId,$field){
		$ar = $link->query_first(sprintf(
		"SELECT %s FROM doc_orders WHERE id=%d",
		$field,$docId
		));
		if (!is_array($ar)||!count($ar)){
			throw new Exception("Документ не найден!");
		}
		return $ar[$field];
	}
	
	public function print_order($pm){
		$l = $this->getDbLink();
		$params = new ParamsSQL($pm,$l);
		$params->setValidated("doc_id",DT_INT);
		
		$doc_id = $params->getParamById('doc_id');
		
		$ref = self::getExtRef($l,$doc_id,'ext_order_id');
		if (is_null($ref)){
			throw new Exception("Счет не выписан!");
		}
		ExtProg::print_order($ref, $_SESSION['user_ext_id'],0,
			array('name'=>'Счет покупателю.pdf','disposition'=>'inline')
		);
	}
	
	public function download_print($pm){
		$l = $this->getDbLink();
		$params = new ParamsSQL($pm,$l);
		$params->setValidated("doc_id",DT_INT);
		
		$doc_id = $params->getDbVal('doc_id');
		
		$ar = $l->query_first(sprintf("SELECT
				'№'||o.number || ' ' ||
					replace(
						replace(f.name,'".'"'."',''),'''',''
					)
				||' '|| o.ext_order_num AS file_name,
				o.ext_order_id
			FROM doc_orders AS o
			LEFT JOIN firms AS f ON f.id = o.firm_id
			WHERE o.id=%d",
			$doc_id
		));
		
		if (is_null($ar['ext_order_id'])){
			throw new Exception("Счет не выписан!");
		}
		//Наименование индексом
		$ar_seq = $this->getDbLinkMaster()->query_first(sprintf("SELECT doc_orders_inc_print(%d) AS ind",$doc_id));
		//$tmp_file = 'toFile'=>TRUE,
		ExtProg::print_order($ar['ext_order_id'], $_SESSION['user_ext_id'],1,
			array('name'=>$ar['file_name'].'_'.$ar_seq['ind'].'.pdf')
		);
		//no actual download here
	}
	
	public function print_torg12($pm){
		$l = $this->getDbLink();
		$params = new ParamsSQL($pm,$l);
		$params->setValidated("doc_id",DT_INT);		
		$ref = self::getExtRef($l,$params->getParamById('doc_id'),'ext_ship_id');
		if (is_null($ref)){
			throw new Exception("Накладная не выписана!");
		}
		ExtProg::print_torg12($ref,$_SESSION['user_ext_id'],0,
			array('name'=>'Торг12.pdf','disposition'=>'inline')
		);
	}
	public function print_invoice($pm){
		$l = $this->getDbLink();
		$params = new ParamsSQL($pm,$l);
		$params->setValidated("doc_id",DT_INT);
		$ref = self::getExtRef($l,$params->getParamById('doc_id'),'ext_invoice_id');
		if (is_null($ref)){
			throw new Exception("Счет-фактура не выписан!");
		}
		ExtProg::print_invoice($ref,$_SESSION['user_ext_id'],
			array('name'=>'Торг12.pdf','disposition'=>'inline')
		);
	}
	public function print_upd($pm){
		$l = $this->getDbLink();
		$params = new ParamsSQL($pm,$l);
		$params->setValidated("doc_id",DT_INT);
		$ref = self::getExtRef($l,$params->getParamById('doc_id'),'ext_ship_id');
		if (is_null($ref)){
			throw new Exception("Накладная не выписана!");
		}
		ExtProg::print_upd($ref,$_SESSION['user_ext_id'],0,
			array('name'=>'УПД.pdf','disposition'=>'inline')
		);
	}
	public function print_ttn($pm){
		$l = $this->getDbLink();
		$params = new ParamsSQL($pm,$l);
		$params->setValidated('doc_id',DT_INT);
		$doc_id = $params->getParamById('doc_id');
		
		$ar = $l->query_first(sprintf(
			"SELECT * FROM doc_orders_ttn
			WHERE doc_id=%d",
		$doc_id
		));		
		
		if (!is_array($ar)||!count($ar)){
			throw new Exception("Накладная не найдена!");
		}
		if (!$ar['ext_ship_id']){
			throw new Exception("Накладная не выписана!");
		}
		if ($ar['deliv_type']!='by_supplier'){
			throw new Exception("Нет ТТН!");
		}
		
		ExtProg::print_ttn($ar['ext_ship_id'],
			$ar,
			$_SESSION['user_ext_id'],
			0,
			array('name'=>'ТТН.pdf','disposition'=>'inline')
		);
	}
	
	private function dump_passport_to_file($docId){
		$ar = $this->getDbLink()->query_first(sprintf(
			"SELECT content
			FROM doc_orders_products_passports
			WHERE doc_order_id=%d",
			$docId
		));
		if (is_array($ar)&amp;&amp;count($ar)){
			$tmp_file = OUTPUT_PATH.uniqid().".pdf";
			file_put_contents(ABSOLUTE_PATH.$tmp_file,pg_unescape_bytea($ar['content']));
			return $tmp_file;		
		}
	}
	
	public function dowloadPassport($docId){
		ob_clean();
		$tmp_file = $this->dump_passport_to_file($docId);
		downloadFile($tmp_file);
		unlink($tmp_file);
	}
	
	public function print_passport($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$docId = $params->getParamById('doc_id');
		
		$ar = $this->getDbLink()->query_first(sprintf(
			"SELECT 1
			FROM doc_orders_products_passports
			WHERE doc_order_id=%d",
		$docId));
		
		if (!is_array($ar)||!count($ar)){
			$this->makePassport($this->getDbLinkMaster(),
				$docId
			);
		}
		$this->dowloadPassport($params->getParamById('doc_id'));
	}
	
	/*
	Либо файл складывается в кэш и удаляется из temp
	либо возвращает имя файла
	и файл не удаляется из temp
	*/
	public function makePassport($link,$docId,$doCache=TRUE){		
		$ar_head = $link->query_first(sprintf(
		"SELECT
			f.id AS firm_id,
			f.name AS firm_descr,
			f.sert_header AS firm_sert_header,
			f.ext_id AS firm_ext_id,
			p.id AS product_id,
			p.name AS product_descr,
			p.sert_type_id,
			stp.xslt_pattern
		FROM doc_orders_t_products t
		LEFT JOIN doc_orders h ON h.id=t.doc_id
		LEFT JOIN products p ON p.id=t.product_id
		LEFT JOIN firms f ON f.id=h.firm_id
		LEFT JOIN sert_types stp ON stp.id=p.sert_type_id
		WHERE h.id=%d",
		$docId
		));
		if (!is_array($ar_head)||!count($ar_head)){
			throw new Exception("makePassport Не найден документ!");
		}
		
		$firm_data = $ar_head['firm_sert_header']; 		
		if (!strlen($firm_data)){
			$firm_data = ExtProg::firm_data($ar_head['firm_ext_id']);
			$link->query(sprintf(
			"UPDATE firms SET sert_header='%s'
			WHERE id=%d",
			$firm_data,$ar_head['firm_id']));
		}
		
		$q_attrs_id = $link->query(sprintf(
			"SELECT *
			FROM sert_types_attrs
			WHERE sert_type_id=%d",
		$ar_head['sert_type_id']
		));
		
		//header
		$xml = '&lt;?xml version="1.0" encoding="UTF-8"?>';
		
		//root node
		$xml.= '&lt;document>';
			//head data
			$xml.= '&lt;firm_data>'.$firm_data.'&lt;/firm_data>';
			$xml.= '&lt;product_descr>'.$ar_head['product_descr'].'&lt;/product_descr>';
			$xml.= '&lt;firm_descr>'.$ar_head['firm_descr'].'&lt;/firm_descr>';
			$xml.= '&lt;date_descr>'.date('d/m/y').'&lt;/date_descr>';
			
			//attr data
			$attrs = '';
			while ($ar = $link->fetch_array($q_attrs_id)){
				$attrs.=
					'&lt;attr>'.
						'&lt;attr_text>'.htmlspecialchars($ar['attr_text']).'&lt;/attr_text>'.
						'&lt;attr_val_norm>'.htmlspecialchars($ar['attr_val_norm']).'&lt;/attr_val_norm>'.
						'&lt;attr_val>'.$ar['attr_val'].'&lt;/attr_val>'.
					'&lt;/attr>';
			}
			$xml.= '&lt;attrs>'.$attrs.'&lt;/attrs>';
		$xml.= '&lt;/document>';
		
		$link->free_result($q_attrs_id);
		
		$xml_file = ABSOLUTE_PATH.OUTPUT_PATH.uniqid().".xml";
		file_put_contents($xml_file,$xml);
		
		$xslt_file = ABSOLUTE_PATH.'views/'.$ar_head['xslt_pattern'];
		if (!file_exists($xslt_file)){
			//throw new Exception('Шаблон не найден!');
			return;
		}
		
		$out_file = ABSOLUTE_PATH.OUTPUT_PATH.uniqid().".pdf";
		$stored_exc = NULL;
		try{
			PDFReport::createFromFile($xml_file,
							$xslt_file,$out_file
				);
			
			if ($doCache&amp;&amp;file_exists($out_file)){
				$link->query(sprintf(
				"DELETE FROM doc_orders_products_passports
				WHERE doc_order_id=%d",$docId));
				
				$link->query(sprintf(
				"INSERT INTO doc_orders_products_passports
				(doc_order_id,product_id,content)
				VALUES (%d,%d,'%s')",
				$docId,$ar_head['product_id'],pg_escape_bytea(file_get_contents($out_file))
				));
			}
		}
		catch (Exception $exc) {
			$stored_exc = $exc;
		}
		
		if (file_exists($xml_file)){
			unlink($xml_file);
		}
		if ($doCache&amp;&amp;file_exists($out_file)){
			unlink($out_file);
		}
		if ($stored_exc) {
			throw($stored_exc);
		}		
		if (!$doCache){
			return $out_file;
		}
	}
	public function get_pop_warehouse($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$this->addNewModel(sprintf(
			"SELECT
				o.warehouse_id,
				wh.name AS warehouse_descr,
				COUNT(*) AS cnt	
			FROM doc_orders AS o
			LEFT JOIN warehouses As wh ON wh.id=o.warehouse_id
			WHERE o.firm_id=%d
			GROUP BY o.warehouse_id,wh.name
			ORDER BY cnt DESC LIMIT 1",
		$params->getParamById('firm_id')
		),'get_pop_warehouse');
	}
	
	public function get_children($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$this->addNewModel(sprintf(
			"SELECT
				d.child_doc_id AS id,
				o.number AS number
			FROM doc_orders_links AS d
			LEFT JOIN doc_orders AS o ON d.child_doc_id=o.id
			WHERE main_doc_id=%d",
		$params->getParamById('doc_id')
		),'get_children');		
	}
	public function get_cancel_cause($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		$this->addNewModel(sprintf(
			"SELECT cause
			FROM doc_orders_cancel_causes
			WHERE doc_id=%d",
		$params->getParamById('doc_id')
		),'get_cancel_cause');		
	}
	
	private function paidOnPayTypeToAcc($paidField,$accPKOType){
		$lmast = $this->getDbLinkMaster();
		$lmast->query("BEGIN");
		try{
			$q_id = $lmast->query(
			"WITH u AS (SELECT users.ext_id
				FROM users
				WHERE users.id=".$_SESSION['user_id']."
				)
			SELECT 
				string_agg(o.id::text,',') AS order_ids,
				string_agg(o.number::text,',') AS order_numbers,
				f.id AS firm_id,
				f.ext_id AS firm_ref,
				cl.id AS client_id,
				cl.ext_id AS client_ref,
				cl.name AS client_descr,
				f.nds AS firm_nds,
				SUM(
					COALESCE(o.total,0)+
					CASE
					WHEN o.deliv_type='by_supplier'::delivery_types
					AND coalesce(o.deliv_add_cost_to_product,FALSE)=FALSE THEN
						COALESCE(o.deliv_total,0)
					ELSE 0
					END+
					COALESCE(o.total_pack,0)
				) AS total,
				
				(SELECT u.ext_id FROM u) AS user_ref
				
			FROM doc_orders AS o
			LEFT JOIN firms AS f ON f.id=o.firm_id
			LEFT JOIN clients AS cl ON cl.id=o.client_id
			WHERE o.".$paidField."=TRUE AND coalesce(o.acc_pko,FALSE)=FALSE
			GROUP BY f.id,f.ext_id,cl.id,cl.ext_id,cl.name,user_ref"
			);
			
			$firm_client_ar = array();
			$ids = '';
			$numbers = '';
			
			while ($ar = $lmast->fetch_array($q_id)){
				$ids.=(($ids=='')? '':',').$ar['order_ids'];
				$numbers.=(($numbers=='')? '':', ').$ar['order_numbers'];
				
				array_push($firm_client_ar,
					array(
						'total'=>$ar['total'],
						'numbers'=>$ar['order_numbers'],
						'ids'=>$ar['order_ids'],
						'user_ref'=>$ar['user_ref'],
						'firm_ref'=>$ar['firm_ref'],
						'firm_nds'=>$ar['firm_nds'],
						'client_ref'=>$ar['client_ref'],
						'client_descr'=>$ar['client_descr']
					)
				);				
			}
			
			if (strlen($ids)){
				//1c
				ExtProg::paid_to_acc($firm_client_ar,$accPKOType);
								
				$lmast->query(sprintf("UPDATE doc_orders
				SET
					acc_pko=TRUE,
					acc_pko_total = COALESCE(total,0)+
						CASE
							WHEN deliv_type='by_supplier'::delivery_types THEN COALESCE(deliv_total,0)
						ELSE 0
						END,
					acc_pko_date = now()
				WHERE id IN (%s)",$ids));				
				
				$res_to_client = 'Сформирован ПКО';
				if ($accPKOType=='cash'){
					$res_to_client.=' по наличному расчету, ';
				}
				else{
					$res_to_client.=' по картам, ';
				}
				$res_to_client.= 'по следующим заявкам: '.$numbers;
				
				
				$ins_q = '';
				foreach($firm_client_ar as $firm_client){
					$ins_q.= ($ins_q=='')? '':',';
					$ins_q.= sprintf(
						"('".$accPKOType."'::acc_pko_types,%f,'%s',ARRAY[%s])",
						$firm_client['total'],$firm_client['numbers'],$firm_client['ids']
					);
				}
				
				$lmast->query(
					"INSERT INTO acc_pkos
					(acc_pko_type,total,order_list,order_ids) VALUES ".$ins_q
				);
				
			}
			else{
				$res_to_client = 'Нет заявок для формирования ПКО';
				if ($accPKOType=='cash'){
					$res_to_client.=' по наличному расчету.';
				}
				else{
					$res_to_client.=' по картам.';
				}
			}
			$lmast->query("COMMIT");
		}
		catch (Exception $e){
			$lmast->query("ROLLBACK");
			throw $e;
		}			
		//возврат
		$this->addNewModel(
			sprintf("SELECT '%s' AS mes",$res_to_client),
			'paid_to_acc'
		);		
	}
	
	public function paid_to_acc($pm){
		$this->paidOnPayTypeToAcc('paid','cash');
	}
	
	public function paid_by_bank_to_acc($pm){
		$this->paidOnPayTypeToAcc('paid_by_bank','bank');
	}
	
	public function divide($pm){
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		
		$this->getDbLinkMaster()->query(sprintf(
		"SELECT doc_orders_divide(%s,%d,%d,%s,%s,%d,%d,%d,%f,%s)",
		$params->getDbVal('view_id'),
		$_SESSION['LOGIN_ID'],
		$params->getDbVal('main_doc_id'),
		$params->getDbVal('delivery_plan_date'),
		$params->getDbVal('sales_manager_comment'),
		$params->getDbVal('deliv_period_id'),
		$params->getDbVal('deliv_vehicle_count'),
		$params->getDbVal('deliv_cost_opt_id'),
		$params->getDbVal('deliv_total'),
		$params->getDbVal('deliv_total_edit')
		));		
	}	
	private function ext_doc_exists($docId,$field){
		$ar = $this->getDbLink()->query_first(sprintf(
		"SELECT %s IS NOT NULL AS exists
		FROM doc_orders
		WHERE id=%d",
		$field,
		$docId
		));
		return (is_array($ar)&amp;&amp;count($ar)&amp;&amp;$ar['exists']=='t');
	}

	public function ext_ship_exists($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();
		if (!$this->ext_doc_exists($p->getParamById('doc_id'),'ext_ship_id')){
			throw new Exception('Документ реализации не выписан!');
		}
	}
	public function ext_invoice_exists($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();
		if (!$this->ext_doc_exists($p->getParamById('doc_id'),'ext_invoice_id')){
			throw new Exception('Документ счет-фактура не выписан!');
		}
	}
	public function ext_order_exists($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();
		if (!$this->ext_doc_exists($p->getParamById('doc_id'),'ext_order_id')){
			throw new Exception('Документ счет не выписан!');
		}
	}
	
	public function get_append_list($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();	
		
		$this->addNewModel(sprintf(
			"SELECT * FROM doc_orders_append_list(%d)",
			$p->getDbVal('doc_id')
		),'DOCOrderAppendList_Model');		
		
	}

	public function append($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();	
		
		$doc_id_list = explode(',',$p->getVal('source_doc_id_list'));
		$doc_id_list_db = '';
		foreach($doc_id_list as $id){
			$doc_id_list_db.= ($doc_id_list_db=='')? '':',';
			$doc_id_list_db.= intval($id);
		}
		
		$q = sprintf(
			"SELECT doc_orders_append(%d,ARRAY[%s],%d)",
			$p->getDbVal('target_doc_id'),
			$doc_id_list_db,
			$_SESSION['LOGIN_ID']
		);
		
		//throw new Exception($q);
		
		$this->addNewModel($q,'DOCOrderAppendList_Model');
	}
	
	public function delete($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();
	
		/*!!! ДЛЯ ТЕСТИРОВАНИЯ !!!	
		$ar = $this->getDbLink()->query_first(sprintf(
		"SELECT ext_order_id,ext_ship_id FROM doc_orders WHERE id=%d",
		$p->getDbVal('id')
		));
		
		if (!$ar || !count($ar)){
			throw new Exception('Документ не найден!');	
		}
	
		if ($ar['ext_order_id']){
			ExtProg::del_docs($ar['ext_order_id'],$ar['ext_ship_id']);
		}				
		*/
	
		//parent::delete($pm);
		
		//Перенос в архив
		$this->add_state($p->getDbVal('id'), 'canceled_by_sales_manager');
	}
	
	
	private static function get_data_file_path(){
		return OUTPUT_PATH;
	}

	private static function get_data_file_pref($docId){
		return self::get_data_file_path().'ord_'.$docId.'_';
	}
	
	private static function delete_data_file($fl){
		unlink($fl);
	}
	
	private static function delete_data_files($docId){
		$files = glob(self::get_data_file_pref($docId).'*');
		foreach($files as $fl){
			self::delete_data_file($fl);
		}
	}

	private static function get_thumbnail($fl,$mimeType){
		$thbn_pref = OUTPUT_PATH.uniqid();		
		if($mimeType=='application/pdf'){
			$cmd = sprintf("pdftoppm -q -l 1 -scale-to 150 -jpeg '%s' '%s'",$fl,$thbn_pref);
			exec($cmd);
			if(file_exists($thbn_pref.'-1.jpg')){
				$thbn_fl = $thbn_pref.'-1.jpg';
			}
			else if(file_exists($thbn_pref.'-01.jpg')){
				$thbn_fl = $thbn_pref.'-01.jpg';
			}
			
		}
		else if(substr($mimeType,0,5)=='image'){
			$thbn_fl = $thbn_pref.'.gif';
			$cmd = sprintf("convert -define jpeg:size=500x180 '%s' -auto-orient -thumbnail 250x100 -unsharp 0x.5 '%s'",$fl,$thbn_fl);
			exec($cmd);
		}
		
		return (file_exists($thbn_fl)? $thbn_fl:NULL);
	}
	
	public function add_file($pm){	
		$fl = self::get_data_file_pref($this->getExtVal($pm,"doc_order_id")).$this->getExtVal($pm,"file_id");
		if(file_exists($fl)){
			self::delete_data_file($fl);
		}
		
		if(isset($_FILES) &amp;&amp; isset($_FILES['file_data'])){
			if(!isset($_SESSION['allowed_extesions'])){
				$ar = $this->getDbLink()->query_first(("SELECT const_allowed_extesions_val() AS v"));
				$_SESSION['allowed_extesions'] = explode(',',$ar['v']);
			}
			$ext = pathinfo($_FILES['file_data']['name'][0], PATHINFO_EXTENSION);
			if (!in_array(strtolower($ext), $_SESSION['allowed_extesions'])) {
				throw new Exception('Файлы с данным расширением загружать запрещено!');
			}		
			
			if(!move_uploaded_file($_FILES['file_data']['tmp_name'][0],$fl)){
				throw new Exception('Ошибка загрузки файла!');
			}
			
			$link = $this->getDbLinkMaster();
			try{
				$link->query("BEGIN");
			
				$fl_mime = getMimeTypeOnExt($_FILES['file_data']['name'][0]);//mime_content_type($fl)
				$prev_fl = self::get_thumbnail($fl,$fl_mime);
				if($prev_fl){
					$prev_data = file_get_contents($prev_fl);
				}
				try{
					$link->query(sprintf(
						"DELETE FROM doc_order_attachments WHERE id=%s"
						,$this->getExtDbVal($pm,"file_id")
					));

					$link->query(sprintf(
						"INSERT INTO doc_order_attachments
						(id,doc_order_id,file_inf,file_data,preview_data)
						VALUES
						(%s,
						%d,
						jsonb_build_object(
							'id',%s
							,'name','%s'
							,'size',%s
							,'mime','%s'
						),
						'%s',
						%s
						)"
						,$this->getExtDbVal($pm,"file_id")
						,$this->getExtDbVal($pm,"doc_order_id")
						,$this->getExtDbVal($pm,"file_id")
						,$_FILES['file_data']['name'][0]
						,filesize($fl)
						,$fl_mime
						,pg_escape_bytea(file_get_contents($fl))
						,$prev_fl? "'".pg_escape_bytea($prev_data)."'":'NULL'
					));
			
					$link->query("COMMIT");
					
					//self::clear_print_cache($this->getExtDbVal($pm,"doc_order_id"));
				}
				finally{
					if(file_exists($prev_fl)){
						unlink($prev_fl);
					}
				}
					
			}
			catch(Exception $e){
				$link->query("ROLLBACK");
				unlink($fl);
				throw $e;
			}
		}
		
		if($prev_data){
			$this->addModel(new ModelVars(
				array('name'=>'Vars',
					'id'=>'Preview_Model',
					'values'=>array(
							new Field('value',DT_STRING,array('value'=>base64_encode($prev_data) ))
						)
					)
				)
			);		
		}		
	}

	public function delete_file($pm){
		$this->getDbLinkMaster()->query(sprintf(
			"DELETE FROM doc_order_attachments WHERE id=%s AND doc_order_id=%d"
			,$this->getExtDbVal($pm,"file_id")
			,$this->getExtDbVal($pm,"doc_order_id")
		));
		
		$fl = self::get_data_file_pref($this->getExtVal($pm,"doc_order_id")).$this->getExtVal($pm,"file_id");
		if(file_exists($fl)){
			self::delete_data_file($fl);
		}		
	}
	
	public function get_file($pm){
		$fl = self::get_data_file_pref($this->getExtVal($pm,"doc_order_id")).$this->getExtVal($pm,"file_id");
		$need_data = !file_exists($fl);

		$ar = $this->getDbLink()->query_first(sprintf(
			"SELECT
				file_inf%s
			FROM doc_order_attachments
			WHERE id=%s AND doc_order_id=%d"
			,$need_data? ",file_data":""
			,$this->getExtDbVal($pm,"file_id")
			,$this->getExtDbVal($pm,"doc_order_id")
		));

		if(!is_array($ar) || !count($ar)){
			throw new Exception('Файл не найден!');
		}

		if($need_data){
			file_put_contents($fl,pg_unescape_bytea($ar['file_data']));
		}

		$fl_n = json_decode($ar['file_inf'])->name;
		$fl_mime = getMimeTypeOnExt($fl_n);
		/*json_decode($ar['file_inf'])->mime;
		if(!$fl_mime){
			$fl_mime = mime_content_type($fl_n);//getMimeTypeOnExt($fl_n);
		}
		*/
		ob_clean();
		downloadFile(
			$fl,
			$fl_mime,
			($this->getExtVal($pm,"inline")==1)? 'inline;':'attachment;',
			$fl_n
		);
		return TRUE;
		
	}


	private static function get_files_list_query($orderId, $nonegraphic){
		return sprintf(
			"SELECT
				file_inf
			FROM doc_order_attachments
			WHERE doc_order_id=%d".(($nonegraphic==TRUE)? " AND file_inf->>'mime' &lt;&gt; 'image/jpeg'" : "")
			,$orderId
		);
	}

	public function get_files_list($pm){
		$this->addNewModel(
			self::get_files_list_query($this->getExtDbVal($pm,"doc_order_id"), FALSE)
			,'FileList_Model'
		);					
	}

	public function get_nonegraphic_files_list($pm){
		$this->addNewModel(
			self::get_files_list_query($this->getExtDbVal($pm,"doc_order_id"), TRUE)
			,'FileList_Model'
		);					
	}
	
	
</xsl:template>

</xsl:stylesheet>
