<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'Report'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>
require_once(FRAME_WORK_PATH.'basic_classes/ParamsSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');

require_once('models/RepProductionLoad_Model.php');
require_once('models/RepSale_Model.php');
require_once('functions/ExtProg.php');
require_once('common/downloader.php');

class <xsl:value-of select="@id"/>_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	public function production_load($pm){
		$link = $this->getDbLink();
		$model = new RepProductionLoad_Model($link);
		$where = $this->conditionFromParams($pm,$model);
		
		$q = sprintf("SELECT
				date8_descr(d.delivery_plan_date) AS delivery_plan_date_descr,
				d.delivery_plan_date,
				t.product_id,
				p.name AS product_descr,
				/*
				products_descr(p,
					t.mes_length,
					t.mes_width,
					t.mes_height
				) AS product_descr,
				*/
				SUM(round(t.quant_base_measure_unit*p.base_measure_unit_vol_m,4)) AS quant,
				d.warehouse_id
			FROM doc_orders_t_products AS t
			LEFT JOIN doc_orders AS d ON d.id=t.doc_id
			LEFT JOIN products AS p ON p.id=t.product_id
			LEFT JOIN warehouses AS w ON w.id=d.warehouse_id
			WHERE (delivery_plan_date BETWEEN %s AND %s)
			AND (%d=0 OR (%d>0 AND w.id=%d))
			AND (SELECT st.state FROM doc_orders_states st
				WHERE st.doc_orders_id=t.doc_id
				ORDER BY st.date_time DESC LIMIT 1)='producing'::order_states
			GROUP BY
				delivery_plan_date_descr,
				d.delivery_plan_date,
				product_id,
				product_descr,
				d.warehouse_id",
		$where->getFieldValueForDb('delivery_plan_date','&gt;='),
		$where->getFieldValueForDb('delivery_plan_date','&lt;='),
		$where->getFieldValueForDb('warehouse_id','='),
		$where->getFieldValueForDb('warehouse_id','='),
		$where->getFieldValueForDb('warehouse_id','=')
		);
		//throw new Exception($q);
		
		$model->query($q);
		$this->addModel($model);
		
	}
	public function sales($pm){	
		$link = $this->getDbLink();
		$params = new ParamsSQL($pm,$link);
		$params->setValidated('groups',DT_STRING);
		$params->setValidated('agg_fields',DT_STRING);
		$params->setValidated('agg_types',DT_STRING);
		$params->setValidated('field_sep',DT_STRING);
		
		$model = new RepSale_Model($link);
		$base_where = $this->conditionFromParams($pm,$model);
		if (!$base_where){
			throw new Exception('Не заданы даты!');
		}
		
		$FIELD_SEP = ',';
		
		/* структуры отчета для удобства отделены*/
		require_once('functions/RepSalesStruc.php');
		
		$base_col_ar = array();
		$base_grp_ar = array();
		$base_fact_ar = array();
		$base_joins_ar = array();
		$base_where_q = '';
		
		/*Функция добавления join рекурсией*/
		function add_join($joinTable,&amp;$usedJoins,&amp;$joins){
			if (!array_key_exists($joinTable,$usedJoins)){
				if (array_key_exists($joinTable,$joins)){
					//adding subjoins
					foreach($joins[$joinTable]['sub_joins'] as $sub_join_table){
						add_join($sub_join_table,$usedJoins,$joins);
					}
					$usedJoins[$joinTable] = 'LEFT JOIN '.$joins[$joinTable]['clause'];
				}
			}
		}
		
		//Сборка условий в SQL		
		$it = $base_where->getFieldIterator();
		while ($it->valid()){
			$field = $it->current();
			$base_where_q.=($base_where_q=='')? 'WHERE ':' '.$field['cond'];
			
			$f_id = $field['field']->getId();
			if (!array_key_exists($f_id,$field_resolver)){
				throw new Exception('Field '.$f_id.' is not resolved!');
			}
			
			$pat = ($field['caseInsen'])?
				ModelWhereSQL::PAT_CASE_INSEN:ModelWhereSQL::PAT_CASE_SEN;

			if (array_key_exists('fieldExprWhere',$field_resolver[$f_id])){
				$f_full_id = $field_resolver[$f_id]['fieldExprWhere'];			
				
			}				
			else if (array_key_exists('fieldWhere',$field_resolver[$f_id])){
				$f_full_id = $field_resolver[$f_id]['table'].'.'.$field_resolver[$f_id]['fieldWhere'];			
				
			}	
			else if (array_key_exists('fieldExpr',$field_resolver[$f_id])){
				$f_full_id = $field_resolver[$f_id]['fieldExpr'];
			}
			else{
				$f_full_id = $field_resolver[$f_id]['table'].'.'.$field_resolver[$f_id]['field'];
			}				
			
			$base_where_q.=sprintf($pat,
				$f_full_id,
				$field['signe'],
				$field['field']->getValueForDb()
			);
			
			//add join
			add_join($field_resolver[$f_id]['table'],$base_joins_ar,$joins);
			
			$it->next();
		}
		
		// !!!*******  УСЛОВИЕ ТОЛЬКО ОТГРУЖЕННЫЕ ЗАЯВКИ *************!!!
		$base_where_q.=' AND doc_orders.delivery_fact_date IS NOT NULL';
		
		//******** Сборка полей ************
		
		//Группы в json
		$grp_fields = json_decode($params->getVal('groups'));
		
		//Агрегаты и типы функций - просто строки через запятую
		$agg_fields = explode($FIELD_SEP,$params->getVal('agg_fields'));
		$agg_types = explode($FIELD_SEP,$params->getVal('agg_types'));
		if (count($agg_fields) &lt;> count($agg_types)){
			throw new Exception('Количество полей агрегатов не равно количеству функций агрегатов!');
		}
		
		$grp_query_ar = array();
		$main_ord_ar = array();
		$tot_col_str = '';
		$tot_agg_ar = array();		
		$dimen_grp_ar = array();				
		$grp_cnt = count($grp_fields);
		
		//Сборка агрегатов
		$agg_ar = array();				
		for ($i=0;$i &lt; count($agg_fields);$i++){			
			//$agg_types[$i]
			if (!array_key_exists($agg_fields[$i],$base_fact_ar)){
				if (!array_key_exists($agg_fields[$i],$field_resolver)){
					throw new Exception('Field '.$agg_fields[$i].' is not resolved!');
				}
				//add join			
				add_join($field_resolver[$agg_fields[$i]]['table'],$base_joins_ar,$joins);				
			
				//add to select				
				if (array_key_exists('fieldExpr',$field_resolver[$agg_fields[$i]])){
					$agg_sel_str = $field_resolver[$agg_fields[$i]]['fieldExpr'];
				}
				else{
					$agg_sel_str = $field_resolver[$agg_fields[$i]]['table'].'.'.$field_resolver[$agg_fields[$i]]['field'];
				}
				/*
				$base_fact_ar[$agg_fields[$i]] = sprintf('%s(%s) AS %s',
					$agg_types[$i],
					$agg_sel_str,
					$agg_fields[$i]
				);
				*/
				if ($field_resolver[$agg_fields[$i]]['table']!='doc_orders_t_products'){
					//Если таблица выборки не базовая - не надо использовать функцию в базовом запросе!!!
					$base_fact_ar[$agg_fields[$i]] = sprintf('%s AS %s',
						$agg_sel_str,
						$agg_fields[$i]
					);
					array_push($base_grp_ar,$agg_fields[$i]);//группировка основного запроса				
				}
				else{
					//используем функцию
					$base_fact_ar[$agg_fields[$i]] = sprintf('%s(%s) AS %s',
						$agg_types[$i],
						$agg_sel_str,
						$agg_fields[$i]
					);					
				}
				
				array_push($agg_ar,
					sprintf('%s(detail.%s) AS %s',
						$agg_types[$i],
						$agg_fields[$i],
						$agg_fields[$i]
					)				
				);
				array_push($tot_agg_ar,
					sprintf('%s(sub.%s) AS %s',
						$agg_types[$i],
						$agg_fields[$i],
						$agg_fields[$i]
					)				
				);
				
			}
			
		}

		$fld_col_cnt_str = '';
		
		//Собираем все колонки по уровням группировок
		$grp_ind = 0;
		foreach($grp_fields as $grp){			
		
			$field_descrs_ar = explode(',',$grp->fieldDescrs);
			$dimen_grp_ar['g_'.$grp_ind] = array();
			$fld_ind = 0;
			
			foreach(explode(',',$grp->fields) as $grp_f){
				if (!array_key_exists($grp_f,$field_resolver)){
					throw new Exception('Field '.$grp_f.' is not resolved!');
				}
			
				/*Все колонки по уровням группировок*/				
				array_push($dimen_grp_ar['g_'.$grp_ind],$grp_f);			
				
				//add join			
				add_join($field_resolver[$grp_f]['table'],$base_joins_ar,$joins);				
			
				/* Если есть выражение - берем его
				если fieldSelect то его
				иначе таблица.поле
				*/
				if (array_key_exists('fieldExpr',$field_resolver[$grp_f])){
					$field_select = $field_resolver[$grp_f]['fieldExpr'];
				}
				else if (array_key_exists('fieldSelect',$field_resolver[$grp_f])){
					$field_select = $field_resolver[$grp_f]['table'].'.'.$field_resolver[$grp_f]['fieldSelect'];
				}				
				else{
					$field_select = $field_resolver[$grp_f]['table'].'.'.$field_resolver[$grp_f]['field'];
				}
				
				/*Базовый запрос*/
				$base_col_ar[$grp_f] = $field_select.'::text AS '.$grp_f;
				
				/*Просто названия колонок основного запроса для group by*/
				array_push($base_grp_ar,$grp_f);

				/*ВСЕ КОЛОНКИ для основного ORDER BY*/
				//if ($fld_ind==0){
					array_push($main_ord_ar,$grp_f);
				//}				
				
				//Новая колонка с полем
				$model->addField(new FieldSQLString($this->getDbLink(),NULL,NULL,$grp_f,array('alias'=>$field_descrs_ar[$fld_ind])));
				
				/*Список пустых колонок для итога*/
				$tot_col_str.= ($tot_col_str=='')? '':',';
				$tot_col_str.= ($grp_ind==0&amp;&amp;$fld_ind==0)? "'Итого'":"''::text";
				
				$fld_ind++;
				
			}
			
			//кол колонок в группировке
			$fld_col_cnt_str.= ($fld_col_cnt_str=='')? '':',';
			$fld_col_cnt_str.= count($dimen_grp_ar['g_'.$grp_ind]);
			
			$grp_ind++;
		}
				
		//Сборка группировок
		$grp_ind = 0;
		foreach($grp_fields as $grp){			
			$fld_str = '';
			$fld_after_str = '';
			//$fld_ord_str = '';			
			
			//группировки до ткущей и текущая и первые поля 
			for ($i=0;$i &lt;= $grp_ind;$i++){
				$fld_str.= ($fld_str=='')? '':',';
				$fld_str.= 'detail.'.implode(',detail.',$dimen_grp_ar['g_'.$i]);
				
				//первые поля для сортировки
				//$fld_ord_str.= ($fld_ord_str=='')? '':',';
				//$fld_ord_str.= 'detail.'.$dimen_grp_ar['g_'.$i][0];				
			}
			
			//группировки после текущей
			for ($i=$grp_ind+1;$i &lt; $grp_cnt;$i++){
				//$fld_after_str.= ($fld_after_str=='')? '':',';
				$fld_after_str.= ",''::text AS ".implode(",''::text AS ",$dimen_grp_ar['g_'.$i]);
				
			}
			
			//часть подзапроса с группировкой				
			array_push($grp_query_ar,					
				sprintf("(SELECT
						%s%s,
						%s,
						%d AS sys_level_val,
						%d AS sys_level_count,
						%s AS sys_level_col_count
					FROM detail
					GROUP BY %s)",
				$fld_str,$fld_after_str,
				implode(',',$agg_ar),
				$grp_ind,					
				$grp_cnt,
				"ARRAY[".$fld_col_cnt_str."]",
				$fld_str
				)
			);
			
			//ORDER BY %s
			//$fld_ord_str
			
			$grp_ind++;						
		}
				
		
		//Текст основного запроса
		$detail_q = sprintf('SELECT %s,%s FROM doc_orders_t_products %s %s GROUP BY %s',
			implode(',',$base_col_ar),
			implode(',',$base_fact_ar),
			implode(' ',$base_joins_ar),
			$base_where_q,
			implode(',',$base_grp_ar)
		);
		
		$all_query = sprintf("WITH
			detail AS (
				%s
			),
		
			sub AS (%s)
			
			(SELECT * FROM sub
			ORDER BY %s)
			
			UNION ALL
			SELECT
				%s,
				%s,
				%d AS sys_level_val,
				%d AS sys_level_count,
				%s AS sys_level_col_count
			FROM sub",
		//основной запрос
		$detail_q,
		implode(' UNION ',$grp_query_ar),
		'sub.'.implode(',sub.',$main_ord_ar),
		$tot_col_str,
		implode(',',$tot_agg_ar),
		0,
		$grp_cnt,
		"ARRAY[".$fld_col_cnt_str."]"
		);
		
		//throw new Exception($all_query);
		
		$model->query($all_query,TRUE);
		$this->addModel($model);		
	}
	
	public function client_balance($pm){
		$l = $this->getDbLink();//read-only
		$params = new ParamsSQL($pm,$l);
		$params->addAll();
	
		$ar = $l->query_first(sprintf(
		"SELECT
		(SELECT t.ext_id FROM clients t WHERE t.id=%d) AS client_ref,
		(SELECT t.ext_id FROM firms t WHERE t.id=%d) AS firm_ref",
		$_SESSION['global_client_id'],
		$params->getParamById('firm_id')
		));
	
		$tmp_file = ExtProg::print_balance(
			$params->getVal('date_from'),
			$params->getVal('date_to'),
			$ar['client_ref'],
			$ar['firm_ref']
		);
		ob_clean();
		downloadFile($tmp_file);
		unlink($tmp_file);
	}
</xsl:template>

</xsl:stylesheet>
