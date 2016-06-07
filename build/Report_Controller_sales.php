	public function sales($pm){	
		$link = $this->getDbLink();
		$params = new ParamsSQL($pm,$link);
		$params->setValidated('groups',DT_STRING);
		$params->setValidated('agg_fields',DT_STRING);
		$params->setValidated('agg_types',DT_STRING);
		
		$model = new RepSale_Model($link);
		$base_where = $this->conditionFromParams($pm,$model);
		if (!$base_where){
			throw new Exception('Не заданы даты!');
		}
		
		/* структуры отчета для удобства отделены*/
		require_once('functions/RepSalesStruc.php');
		
		$base_dimen_ar = array();
		$base_fact_ar = array();
		$base_joins_ar = array();
		$base_where_q = '';
		
		/*Функция добавления join рекурсией*/
		function add_join($joinTable,&$usedJoins,&$joins){
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
			
			if (array_key_exists('fieldExpr',$field_resolver[$f_id])){
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
		$agg_fields = explode(',',$params->getVal('agg_fields'));
		$agg_types = explode(',',$params->getVal('agg_types'));
		if (count($agg_fields) <> count($agg_types)){
			throw new Exception('Количество полей агрегатов не равно количеству функций агрегатов!');
		}
				
		$grp_ar = array();
		$grp_with_ar = array();
		$grp_sel_ar = array();
		$grp_ind = 0;
		$grp_cnt = count($grp_fields);
		
		//создадим массив группировок
		$grp_empty_ar = array();
		foreach($grp_fields as $grp){
			//array_push($grp_empty_ar,"''::text AS ".'"'.$grp->descr.'"');
			array_push($grp_empty_ar,"''::text");
		}
		
		//Сборка агрегатов
		$agg_ar = array();				
		for ($i=0;$i < count($agg_fields);$i++){			
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
				$base_fact_ar[$agg_fields[$i]] = sprintf('%s(%s) AS %s',
					$agg_types[$i],
					$agg_sel_str,
					$agg_fields[$i]
				);
				array_push($agg_ar,
					sprintf('%s(detail.%s) AS %s',
						$agg_types[$i],
						//$field_resolver[$agg_fields[$i]]['alias'],
						//$field_resolver[$agg_fields[$i]]['alias']
						$agg_fields[$i],
						$agg_fields[$i]
					)				
				);
			}
			
		}
		
		//Сборка группировок
		foreach($grp_fields as $grp){
			$grp_select = '';
			$grp_ind++;
			
			foreach(explode(',',$grp->fields) as $grp_f){
				if (!array_key_exists($grp_f,$field_resolver)){
					throw new Exception('Field '.$grp_f.' is not resolved!');
				}
				//add join			
				add_join($field_resolver[$grp_f]['table'],$base_joins_ar,$joins);				
			
				//add to select
				$grp_select.= ($grp_select=='')? '':"||', '||";
				/* Если есть выражение - берем его
				если fieldSelect то его
				иначе таблица.поле
				*/
				if (array_key_exists('fieldExpr',$field_resolver[$grp_f])){
					$grp_select.= $field_resolver[$grp_f]['fieldExpr'];
				}
				else if (array_key_exists('fieldSelect',$field_resolver[$grp_f])){
					$grp_select.= $field_resolver[$grp_f]['table'].'.'.$field_resolver[$grp_f]['fieldSelect'];
				}				
				else{
					$grp_select.= $field_resolver[$grp_f]['table'].'.'.$field_resolver[$grp_f]['field'];
				}
				
			}
			$dimen_id = 'dimen_'.$grp_ind;
			
			$base_dimen_ar[$grp->descr] = $grp_select.'::text AS '.$dimen_id;
			array_push($grp_ar,$dimen_id);
			
			//Новая колонка с группировкой
			$model->addField(new FieldSQLString($this->getDbLink(),NULL,NULL,$dimen_id,array('alias'=>$grp->descr)));
			
			if ($grp_ind < $grp_cnt){
				//пустые группировки до + , + группировка + , + пустые группировки после
				$grp_before = implode(',',array_slice($grp_empty_ar,0,$grp_ind-1,TRUE));
				$grp_after = implode(',',array_slice($grp_empty_ar,$grp_ind,NULL,TRUE));
				$grp_str = $grp_before . ( ($grp_before=='')? '':','). $dimen_id . ( ($grp_after=='')? '':',') .$grp_after;
				 
				
				//часть запроса с WITH				
				array_push($grp_with_ar,					
					sprintf("grp_%d AS (
						SELECT
							%s,
		
							%s
						FROM detail
						GROUP BY
							%s
						)",
					$grp_ind,
					$grp_str,
					implode(',',$agg_ar),
					$dimen_id
					)
				);
				
				//часть запроса с выборкой
				array_push($grp_sel_ar,
					sprintf("SELECT
							grp_%d.*,
							%d AS sys_level_val,
							%d AS sys_level_count	
						FROM grp_%d",
					$grp_ind,
					$grp_ind,
					$grp_cnt,
					$grp_ind
					)
				);
			}						
		}
				
		
		//Текст основного запроса
		$base_q = sprintf('SELECT %s,%s FROM doc_orders_t_products %s %s GROUP BY %s',
			implode(',',$base_dimen_ar),
			implode(',',$base_fact_ar),
			implode(' ',$base_joins_ar),
			$base_where_q,
			implode(',',$grp_ar)
		);
		
		$all_query = sprintf("WITH
			detail AS (
				%s
			)
		
			%s
		
			SELECT
				detail.*,
				0 AS sys_level_val,
				%d AS sys_level_count
			FROM detail

			%s
		
			UNION 	
			SELECT 
				%s,
			
				%s,
			
				%d AS sys_level_val,
				%d AS sys_level_count		
			FROM detail
			
			ORDER BY %s",
		//основной запрос
		$base_q,
		
		//группировки with
		((count($grp_with_ar)==0)? '':',') .implode(',',$grp_with_ar),
		
		//выборка из основного запроса, количество группировок
		$grp_cnt,
		
		
		//выборка из группировок
		((count($grp_sel_ar)==0)? '':' UNION ') .implode(' UNION ',$grp_sel_ar),
		
		//все группировки пустые
		implode(',',$grp_empty_ar),
		
		//все агрегаты из detail
		implode(',',$agg_ar),
		
		$grp_cnt,
		$grp_cnt,
		
		//все группировки
		implode(',',$grp_ar)
		);
		
		throw new Exception($all_query);
		
		$model->query($all_query,TRUE);
		$this->addModel($model);		
	}

