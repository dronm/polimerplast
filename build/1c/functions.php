<?php
	function get_doc_comment($head){
		$COMMENT = 'web';
		if (isset($head['client_comment'])){
			$COMMENT.= ' '.cyr_str_decode($head['client_comment']);
		}
		if (isset($head['sales_manager_comment'])){
			$COMMENT.= ' '.cyr_str_decode($head['sales_manager_comment']);
		}
		return $COMMENT;
	}
	function fill_struc_for_ttn($v8,$head){
		$attrs = $v8->NewObject('Структура');
		$attrs->Вставить("СрокДоставки",cyr_str_decode($head["deliv_time"]));
		$attrs->Вставить("МаркаАвтомобиля",cyr_str_decode($head["veh_model"]));
		$attrs->Вставить("МаркаПрицепа",cyr_str_decode($head["veh_trailer_model"]));
		$attrs->Вставить("ГосНомерАвтомобиля",cyr_str_decode($head["vh_plate"]));
		$attrs->Вставить("ГосНомерПрицепа",cyr_str_decode($head["vh_trailer_plate"]));
		$attrs->Вставить("ПунктПогрузки",cyr_str_decode($head["wareh_descr"]));
		$attrs->Вставить("ПунктРазгрузки",cyr_str_decode($head["dest_descr"]));
		$attrs->Вставить("Водитель",cyr_str_decode($head["driver_name"]));
		
		$driver_ref = 0;
		if ($head["driver_ext_id"]){
			$driver_id = $v8->NewObject('УникальныйИдентификатор',$head['driver_ext_id']);
			$driver_ref = $v8->Справочники->ФизическиеЛица->ПолучитьСсылку($driver_id);			
		}
		$attrs->Вставить("ВодительФЛ",$driver_ref);
		
		$carrier = cyr_str_decode($head["carrier_descr"]);
		$firm = cyr_str_decode($head["firm_descr"]);
		if ($carrier){
			$attrs->Вставить("Перевозчик",$carrier);
			$attrs->Вставить("Заказчик",$firm);
		}
		else{
			$attrs->Вставить("Перевозчик",$firm);
			$attrs->Вставить("Заказчик",$firm);			
		}
		
		
		$attrs->Вставить("ВидПеревозки",cyr_str_decode($head["deliv_kind"]));
		$attrs->Вставить("ЛицензионнаяКарточка",FALSE);
		$attrs->Вставить("ВодительскоеУдостоверение",cyr_str_decode($head["drive_perm"]));
		return $attrs;
	}
	
	function cyr_str_decode($str){
		return iconv('UTF-8','Windows-1251',$str);
	}
	function cyr_str_encode($str){
		//Ответ отправляем в ANSI
		return $str;//iconv('Windows-1251','UTF-8',$str);
	}
	function get_1c_date($d,$h=0,$m=0,$s=0){
		$parts = explode('-',str_replace("\'",'',$d));
		var_dump($parts);
		if (count($parts)>=3){
			$_d = mktime($h,$m,$s,$parts[1],$parts[2],$parts[0]);
			if ($h==0&&$m==0&&$s==0){
				$format = 'Ymd';
			}
			else{
				$format = 'YmdHis';
			}
		return date($format,$_d);
		}
	}
	function check_client_buyer($v8,$client_ref){
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ
		Спр.Покупатель,
		Спр.Наименование
		ИЗ Справочник.Контрагенты КАК Спр
		ГДЕ Спр.Ссылка=&КлиентСсылка
		';
		$q_obj->УстановитьПараметр('КлиентСсылка',$client_ref);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			if (!$sel->Покупатель){
				throw new Exception('Контрагент '.$sel->Наименование.' не отмечен как покупатель в 1с!');
			}
		}
	
	}
	
	function getSprRefOnDescr($sprKind){
		$descr = $_REQUEST[PAR_NAME];
		if (!isset($descr)){
			throw new Exception("Не задано наименование");
		}
		$v8 = new COM(COM_OBJ_NAME);
		$par = cyr_str_decode($descr);
		$par = str_replace('"','""',$par);
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ Спр.Ссылка КАК ref
		ИЗ Справочник.'.$sprKind.' КАК Спр
		ГДЕ Спр.Наименование="'.$par.'"';
		//throw new Exception($q_obj->Текст);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return sprintf('<ref>%s</ref>',
				$v8->String($sel->ref->УникальныйИдентификатор()));
		}	
	}

	function completeSprOnDescr($sprKind){
		if (!isset($_REQUEST[PAR_TEMPL])){
			throw new Exception("Не задан шаблон");
		}
		$count = (isset($_REQUEST[PAR_COUNT]))? $_REQUEST[PAR_COUNT]:PAR_DEF_COUNT;
		$par = str_replace('\"','""',$_REQUEST[PAR_TEMPL]);
		$v8 = new COM(COM_OBJ_NAME);
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ '.$count.' 
			Спр.Ссылка КАК ref,
			Спр.Наименование КАК name
			ИЗ Справочник.'.$sprKind.' КАК Спр
			ГДЕ Спр.Наименование ПОДОБНО "'.cyr_str_decode($par).'%"';
		$sel = $q_obj->Выполнить()->Выбрать();
		while ($sel->Следующий()){
			$xml_body.= sprintf("<ref name='%s'>%s</ref>",
				cyr_str_encode($sel->name),
				$v8->String($sel->ref->УникальныйИдентификатор()));
		}
	}
	
	function get_currency($v8){
		return $v8->Справочники->Валюты->НайтиПоКоду('643');
	}
	function get_nds($v8){
		return $v8->Перечисления->СтавкиНДС->НДС18;
	}
	function get_nds_percent($v8,$stavka){
		return 18;
	}
	function get_ext_obr($v8){
		$ext_form = $v8->Справочники->ВнешниеОбработки->НайтиПоНаименованию("API1C");
		$f = $v8->ПолучитьИмяВременногоФайла();
		$d = $ext_form->ХранилищеВнешнейОбработки->Получить();
		$d->Записать($f);
		return $v8->ВнешниеОбработки->Создать($f,FALSE);
	}
	function create_client_dog($v8,$clientRef,$firm_ref,$attrs){
		//Новый договор
		$dog = $v8->Справочники->ДоговорыКонтрагентов->СоздатьЭлемент();
		$dog->Владелец								= $clientRef;
		$dog->Наименование							= 'Основной договор';
		$dog->ВалютаВзаиморасчетов					= get_currency($v8);		
		$dog->ВедениеВзаиморасчетов					= $v8->Перечисления->ВедениеВзаиморасчетовПоДоговорам->ПоДоговоруВЦелом;
		$dog->Организация							= $firm_ref;
		$dog->Комментарий							= 'web';
		$dog->ВидУсловийДоговора					= $v8->Перечисления->ВидыУсловийДоговоровВзаиморасчетов->БезДополнительныхУсловий;
		$dog->ВидДоговора 							= $v8->Перечисления->ВидыДоговоровКонтрагентов->СПокупателем;
		
		if ($attrs){
			if ($attrs['pay_debt_sum']){
				$dog->ДопустимаяСуммаЗадолженности			= floatval($attrs['pay_debt_sum']);
			}
			if ($attrs['pay_delay_days']){
				$dog->ДопустимоеЧислоДнейЗадолженности		= intval($attrs['pay_delay_days']);			
			}
			if ($attrs['pay_ban_on_debt_sum']){
				$dog->КонтролироватьСуммуЗадолженности		= ($attrs['pay_ban_on_debt_sum']=='t');
			}
			if ($attrs['pay_ban_on_debt_days']){
				$dog->КонтролироватьЧислоДнейЗадолженности	= ($attrs['pay_ban_on_debt_days']=='t');
			}
			$dog->ВидДоговора	 						= $v8->Перечисления->ВидыДоговоровКонтрагентов->СПокупателем;
			if (isset($attrs['contract_date_from'])){
				$dog->Дата									= $attrs['contract_date_from'];
			}
			if (isset($attrs['contract_number'])){
				$dog->Номер									= $attrs['contract_number'];
			}
			if (isset($attrs['contract_date_to'])){
				$dog->СрокДействия							= $attrs['contract_date_to'];
			}
		}
		//ВидВзаиморасчетов
		$dog->ВестиПоДокументамРасчетовСКонтрагентом = TRUE;
		
		$dog->Записать();		
		return $dog->Ссылка;
	}
	function get_kassa($v8,$firm_ref){		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Касса.Ссылка КАК ref
		ИЗ Справочник.Кассы КАК Касса
		ГДЕ Касса.Владелец=&ФирмаСсылка
		';
		$q_obj->УстановитьПараметр('ФирмаСсылка',$firm_ref);
		
		$sel = $q_obj->Выполнить()->Выбрать();
		$kassa_ref = NULL;
		if ($sel->Следующий()){
			$kassa_ref = $sel->ref;
		}
		
		if (is_null($kassa_ref)){
			$kassa_ob = $v8->Справочники->Кассы->СоздатьЭлемент();
			$kassa_ob->Владелец = $firm_ref;
			$kassa_ob->Наименование = 'Основная касса';
			$kassa_ob->Записать();
			$kassa_ref = $kassa_ob->Ссылка;
		}
		
		return $kassa_ref;
	}
	
	function get_client_dog($v8,$client_ref,$firm_ref,$attrs){			
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Дог.Ссылка КАК ref
		ИЗ Справочник.ДоговорыКонтрагентов КАК Дог
		ГДЕ Дог.Владелец=&КлиентСсылка
			И Дог.Организация=&ФирмаСсылка
			И Дог.ВидДоговора=ЗНАЧЕНИЕ(Перечисление.ВидыДоговоровКонтрагентов.СПокупателем)
			И Дог.ВедениеВзаиморасчетов=ЗНАЧЕНИЕ(Перечисление.ВедениеВзаиморасчетовПоДоговорам.ПоДоговоруВЦелом)
		';
		$q_obj->УстановитьПараметр('КлиентСсылка',$client_ref);
		$q_obj->УстановитьПараметр('ФирмаСсылка',$firm_ref);
		
		$sel = $q_obj->Выполнить()->Выбрать();
		$dog_ref = NULL;
		if ($sel->Следующий()){
			$dog_ref = $sel->ref;
		}
		
		if (is_null($dog_ref)){
			$dog_ref = create_client_dog($v8,$client_ref,$firm_ref,$attrs);
		}
		
		return $dog_ref;
	}
	
	function get_item_mu($v8,$item,$item_ref){
		$mu_id = $v8->NewObject('УникальныйИдентификатор',$item['measure_unit_ref']);
		$mu_ref = $v8->Справочники->КлассификаторЕдиницИзмерения->ПолучитьСсылку($mu_id);
		$item_mu_ref = $v8->Справочники->ЕдиницыИзмерения->НайтиПоНаименованию($mu_ref->Наименование,TRUE,0,$item_ref);
		if ($item_mu_ref->Пустая()){
			//новая единица
			$k = floatval($item['measure_unit_k']);
			$k = ($k>0)? $k:1;
			$new_item_mu = $v8->Справочники->ЕдиницыИзмерения->СоздатьЭлемент();
			$new_item_mu->Владелец = $item_ref;
			$new_item_mu->Наименование = $mu_ref->Наименование;
			$new_item_mu->ЕдиницаПоКлассификатору = $mu_ref;
			$new_item_mu->Коэффициент = $k;
			$new_item_mu->Записать();
			$item_mu_ref = $new_item_mu->Ссылка;
		}	
		return $item_mu_ref;
	}
	function get_item($v8,$item){
		$ITEM_GROUP_NAME = 'Продукция';
		$group1_ref = $v8->Справочники->Номенклатура->НайтиПоНаименованию($ITEM_GROUP_NAME);
		if ($group1_ref->Пустая()){
			//новая группа
			$new_grp = $v8->Справочники->Номенклатура->СоздатьГруппу();
			$new_grp->Наименование = $ITEM_GROUP_NAME;
			$new_grp->Записать();
			$group1_ref = $new_grp->Ссылка;			
		}

		$group_name = cyr_str_decode($item['group_name']);
		$group2_ref = $v8->Справочники->Номенклатура->НайтиПоНаименованию($group_name,TRUE,$group1_ref);
		if ($group2_ref->Пустая()){
			//новая группа
			$new_grp = $v8->Справочники->Номенклатура->СоздатьГруппу();
			$new_grp->Наименование = $group_name;
			$new_grp->Родитель = $group1_ref;
			$new_grp->Записать();
			$group2_ref = $new_grp->Ссылка;
		}
		
		$name = cyr_str_decode($item['product_name']);
		//.' '.$item['mes_length'].'x'.$item['mes_width'].'x'.$item['mes_height'];
		$item_ref = $v8->Справочники->Номенклатура->НайтиПоНаименованию($name,TRUE,$group2_ref);
		
		if ($item_ref->Пустая()){				
			//новая номенклатура
			$new_item = $v8->Справочники->Номенклатура->СоздатьЭлемент();
			$new_item->Наименование = $name;
			$new_item->НаименованиеПолное = $name;
			$new_item->ВидНоменклатуры = $v8->Справочники->ВидыНоменклатуры->НайтиПоНаименованию('Продукция');
			$new_item->Родитель = $group2_ref;
			
			//Номенклатурная группа
			if (isset($item['product_group_ref'])){
				$pg_id = $v8->NewObject('УникальныйИдентификатор',$item['product_group_ref']);
				$pg_ref = $v8->Справочники->НоменклатурныеГруппы->ПолучитьСсылку($pg_id);			
				$new_item->НоменклатурнаяГруппа = $pg_ref;
				$new_item->НоменклатурнаяГруппаЗатрат = $pg_ref;
			}
			
			//Базовая единица web
			$bmu_id = $v8->NewObject('УникальныйИдентификатор',$item['base_measure_unit_ref']);
			$bmu_ref = $v8->Справочники->КлассификаторЕдиницИзмерения->ПолучитьСсылку($bmu_id);

			//Единица документа web
			//$dmu_id = $v8->NewObject('УникальныйИдентификатор',$item['measure_unit_ref']);
			//$dmu_ref = $v8->Справочники->КлассификаторЕдиницИзмерения->ПолучитьСсылку($dmu_id);
			//$k = floatval($item['measure_unit_k']);
			//$k = ($k>0)? $k:1;			
			
			//Базовая 1с = базовая единица из web
			$new_item->БазоваяЕдиницаИзмерения = $bmu_ref;				
			$new_item->СтавкаНДС = $v8->Перечисления->СтавкиНДС->НДС18;				
			$new_item->Записать();			
			
			//новая единица
			$new_item_mu = $v8->Справочники->ЕдиницыИзмерения->СоздатьЭлемент();			
			$new_item_mu->Владелец = $new_item->Ссылка;
			$new_item_mu->Наименование = $bmu_ref->Наименование;
			$new_item_mu->ЕдиницаПоКлассификатору = $bmu_ref;
			$new_item_mu->Коэффициент = 1;
			$new_item_mu->Записать();
			
			$new_item->ЕдиницаХраненияОстатков = $new_item_mu->Ссылка;
			$new_item->ЕдиницаДляОтчетов = $new_item_mu->Ссылка;				
			$new_item->Записать();
			
			$item_ref = $new_item->Ссылка;
		}
		return $item_ref;
	}
	
	function get_item_deliv($v8){
		return $v8->Справочники->Номенклатура->НайтиПоНаименованию('Доставка',TRUE);
	}

	function get_item_pack($v8){
		return $v8->Справочники->Номенклатура->НайтиПоНаименованию('Упаковка',TRUE);
	}
	
	function get_svoistvo($v8,$svoistvo_type,$svoistvo_val){		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Спр.Ссылка КАК ref
		ИЗ Справочник.ЗначенияСвойствОбъектов КАК Спр
		ГДЕ Спр.Владелец=&ВидСвойства
			И Спр.Наименование=&ЗначСвойства
		';
		$q_obj->УстановитьПараметр('ВидСвойства',$svoistvo_type);
		$q_obj->УстановитьПараметр('ЗначСвойства',$svoistvo_val);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			$ref = $sel->ref;
		}
		else{
			//создать новой значений
			$obj = $v8->Справочники->ЗначенияСвойствОбъектов->СоздатьЭлемент();
			$obj->Владелец = $svoistvo_type;
			$obj->Наименование = $svoistvo_val;
			$obj->Записать();
			$ref = $obj->Ссылка;
		}
		return $ref;
	}
	
	function sale($v8,$head,$items){		
		$COMMENT = get_doc_comment($head);
		
		$firm_id = $v8->NewObject('УникальныйИдентификатор',$head['firm_ref']);
		$warehouse_id = $v8->NewObject('УникальныйИдентификатор',$head['warehouse_ref']);
		$client_id = $v8->NewObject('УникальныйИдентификатор',$head['client_ref']);
		
		$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
		$client_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($client_id);
		$warehouse_ref = $v8->Справочники->Склады->ПолучитьСсылку($warehouse_id);		
	
		$deliv_total = floatval($head['deliv_total']);
		$pack_total = floatval($head['pack_total']);
		
		check_client_buyer($v8,$client_ref);
		
		$doc = $v8->Документы->РеализацияТоваровУслуг->СоздатьДокумент();	
		$doc->Дата						= $head['date'];
		
		$doc->Организация						= $firm_ref;
		$doc->ОтражатьВБухгалтерскомУчете 		= TRUE;
		$doc->ОтражатьВНалоговомУчете			= TRUE;
		$doc->ОтражатьВУправленческомУчете		= TRUE;
		$doc->Склад								= $warehouse_ref;
		$doc->УчитыватьНДС						= TRUE;
		$doc->СуммаВключаетНДС					= TRUE;
		
		$doc->АдресДоставки				= cyr_str_decode($head['deliv_address']);
		$doc->ВалютаДокумента			= get_currency($v8);
		$doc->КратностьВзаиморасчетов	= 1;
		$doc->КурсВзаиморасчетов		= 1;
		$doc->ВидОперации				= $v8->Перечисления->ВидыОперацийРеализацияТоваров->ПродажаКомиссия;
		$doc->ВидПередачи				= $v8->Перечисления->ВидыПередачиТоваров->СоСклада;
		$doc->Комментарий				= $COMMENT;
		
		//$doc->Грузоотправитель = ФирмаСсылка;
		//$doc->Грузополучатель = КлиентСсылка;		
		$attrs = array();
		$attrs['pay_debt_sum'] = 0;
		$attrs['pay_delay_days'] = 0;
		$attrs['pay_ban_on_debt_sum'] = FALSE;
		$attrs['pay_ban_on_debt_days'] = FALSE;			
		$doc->Контрагент						= $client_ref;
		$doc->ДоговорКонтрагента				= get_client_dog($v8,$client_ref,$firm_ref,$attrs);
		
		$stavka = get_nds($v8);
		$nds_percent = get_nds_percent($v8,$stavka);
		$total=0;
		foreach($items as $item){
			//номенклатура
			$item_ref = get_item($v8,$item);
			
			$line = $doc->Товары->Добавить();
			$line->ЕдиницаИзмерения	= get_item_mu($v8,$item,$item_ref);
			$line->Количество 		= $item['quant'];
			$line->Коэффициент 		= $line->ЕдиницаИзмерения->Коэффициент;
			$line->Номенклатура 	= $item_ref;
			$line->Цена				= $item['price'];
			$line->Сумма			= $item['total'];
			$line->СтавкаНДС		= $stavka;
			$line->СуммаНДС			= round(floatval($item['total'])*$nds_percent/(100+$nds_percent),2);
			
			$line->СпособСписанияОстаткаТоваров = $v8->Перечисления->СпособыСписанияОстаткаТоваров->СоСклада;
			$line->СчетДоходовБУ	= $v8->ПланыСчетов->Хозрасчетный->ВыручкаНеОблагаемаяЕНВД;
			$line->СчетРасходовБУ	= $v8->ПланыСчетов->Хозрасчетный->ПрочиеРасходыНеОблагаемыеЕНВД;
			$line->СчетУчетаБУ		= $v8->ПланыСчетов->Хозрасчетный->ГотоваяПродукция;
			//$line->ПринадлежностьНоменклатуры = $v8->Перечисления->ПринадлежностьНоменклатуры->Принятый;
			
			$total+= $item['total'];
		}
	
		if ($deliv_total){
			$q = intval($head['deliv_vehicle_count']);
			$q = (!$q)? 1:$q;
		
			$item_ref = get_item_deliv($v8);
			$line = $doc->Услуги->Добавить();			
			$line->Количество 		= $q;
			$line->Содержание		= $item_ref->НаименованиеПолное;
			$line->Номенклатура 	= $item_ref;
			$line->Цена				= round($deliv_total/$q,2);
			$line->Сумма			= $deliv_total;
			$line->СтавкаНДС		= $stavka;
			$line->СуммаНДС			= round($deliv_total*$nds_percent/(100+$nds_percent),2);
			
			$total+= $deliv_total;
		}

		if ($pack_total){
			$item_ref = get_item_pack($v8);
			$line = $doc->Услуги->Добавить();
			$line->Содержание		= $item_ref->НаименованиеПолное;
			$line->Количество 		= 1;
			$line->Номенклатура 	= $item_ref;
			$line->Цена				= $pack_total;
			$line->Сумма			= $pack_total;
			$line->СтавкаНДС		= $stavka;
			$line->СуммаНДС			= round($pack_total*$nds_percent/(100+$nds_percent),2);
			
			$total+= $pack_total;
		}
		
		//Автор документа
		if ($head['user_ref']){
			$user_id = $v8->NewObject('УникальныйИдентификатор',$head['user_ref']);			
			$user_ref = $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);
			$doc->Ответственный = $user_ref;
			
			$empl_ref = $user_ref->ФизЛицо;			
			$obr = get_ext_obr($v8);
			$user_order_str = $obr->ПолучитьЗначенияСвойстваСправочника($empl_ref,'Приказ');
			
			
			$doc->ОтпускРазрешил = $empl_ref;
			$doc->ОтпускПроизвел = $empl_ref;
			$doc->ГлавныйБухгалтер = $empl_ref;
			$doc->ЗаГлавногоБухгалтераПоПриказу = $user_order_str;
			$doc->ЗаРуководителяПоПриказу = $user_order_str;
		}
		
		$doc->СуммаДокумента = $total;
		$doc->Записать($v8->РежимЗаписиДокумента->Проведение);
		
		//Счет фактура
		$inv_id = '';
		$inv_num = '';		
		if ($head['pay_cash']!='t'){
			$doc_inv = $v8->Документы->СчетФактураВыданный->СоздатьДокумент();
			$doc_inv->Заполнить($doc->Ссылка);
			$doc_inv->Записать($v8->РежимЗаписиДокумента->Проведение);	
			
			//Информация по отгрузке водитель авто
			$vh_trailer_model_ref = NULL;
			$vh_trailer_plate_ref = NULL;
			$driver_ref = NULL;
			if ($head['vh_trailer_model']||$head['vh_trailer_plate']||$head['driver_ref']){
				$rec_set = $v8->РегистрыСведений->ЗначенияСвойствОбъектов->СоздатьНаборЗаписей();
			}
			
			if (isset($head['vh_trailer_model'])){
				$svoistvo_vh_trailer_model = $v8->ПланыВидовХарактеристик->СвойстваОбъектов->НайтиПоКоду("00000000003");
				$rec = $rec_set->Добавить();
				$rec->Объект = $doc->Ссылка;
				$rec->Свойство = $svoistvo_vh_trailer_model;
				$rec->Значение = get_svoistvo($v8,$svoistvo_vh_trailer_model,$head['vh_trailer_model']);
				//$rec->Записать();			
			}
			if (isset($head['vh_trailer_plate'])){
				$svoistvo_vh_trailer_plate = $v8->ПланыВидовХарактеристик->СвойстваОбъектов->НайтиПоКоду("00000000009");
				$rec = $rec_set->Добавить();
				$rec->Объект = $doc->Ссылка;
				$rec->Свойство = $svoistvo_vh_trailer_plate;
				$rec->Значение = get_svoistvo($v8,$svoistvo_vh_trailer_model,$head['vh_trailer_plate']);
				//$rec->Записать();						
			}
			if (isset($head['driver_ref'])){
				$driver_id = $v8->NewObject('УникальныйИдентификатор',$head['driver_ref']);
				$rec = $rec_set->Добавить();
				$rec->Объект = $doc->Ссылка;
				$rec->Свойство = $v8->ПланыВидовХарактеристик->СвойстваОбъектов->НайтиПоКоду("00000000008");
				$rec->Значение = $v8->Справочники->ФизическиеЛица->ПолучитьСсылку($driver_id);
				//$rec->Записать();									
			}
			if (isset($head['vh_trailer_model'])||isset($head['vh_trailer_plate'])||isset($head['driver_ref'])){
				$rec_set->Записать();
			}
			$inv_id = $v8->String($doc_inv->Ссылка->УникальныйИдентификатор());
			$inv_num = cyr_str_encode($doc_inv->Номер);
		}
		
		return sprintf(
			'<naklRef>%s</naklRef>
			<naklNum>%s</naklNum>
			<invRef>%s</invRef>
			<invNum>%s</invNum>',		
			$v8->String($doc->Ссылка->УникальныйИдентификатор()),
			cyr_str_encode($doc->Номер),
			$inv_id,
			$inv_num
		);
	}
	function order($v8,$head,$items){		
		$COMMENT = get_doc_comment($head);
		
		$firm_id = $v8->NewObject('УникальныйИдентификатор',$head['firm_ref']);
		
		$warehouse_id = $v8->NewObject('УникальныйИдентификатор',$head['warehouse_ref']);
		$client_id = $v8->NewObject('УникальныйИдентификатор',$head['client_ref']);
		
		$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
		$client_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($client_id);
		check_client_buyer($v8,$client_ref);
		
		$warehouse_ref = $v8->Справочники->Склады->ПолучитьСсылку($warehouse_id);
		
		if ($firm_ref->ОсновнойБанковскийСчет->Пустая()){
			throw new Exception('Не задан основной банковский счет!');
		}
	
		$deliv_total = floatval($head['deliv_total']);
		$pack_total = floatval($head['pack_total']);
		
		if (isset($head['ext_order_id'])){
			//модификация документа
			$order_id = $v8->NewObject('УникальныйИдентификатор',$head['ext_order_id']);
			$order_ref = $v8->Документы->СчетНаОплатуПокупателю->ПолучитьСсылку($order_id);
			$doc = $order_ref->ПолучитьОбъект();
			if ($doc->Проведен){
				$doc->Проведен = FALSE;
			}
			if ($doc->Товары->Количество()>0){
				$doc->Товары->Очистить();
			}			
			if ($doc->Услуги->Количество()>0){
				$doc->Услуги->Очистить();
			}						
		}
		else{
			$doc = $v8->Документы->СчетНаОплатуПокупателю->СоздатьДокумент();	
		}
		
		$doc->Дата						= $head['date'];
		
		$doc->Организация				= $firm_ref;
		$doc->УчитыватьНДС				= TRUE;
		$doc->СуммаВключаетНДС			= TRUE;
		
		$doc->АдресДоставки				= cyr_str_decode($head['deliv_address']);
		$doc->ВалютаДокумента			= get_currency($v8);
		$doc->КратностьВзаиморасчетов	= 1;
		$doc->КурсВзаиморасчетов		= 1;
		$doc->Комментарий				= $COMMENT;
		$doc->СтруктурнаяЕдиница		= $firm_ref->ОсновнойБанковскийСчет;
		$doc->Склад						= $warehouse_ref;		
		
		//$doc->Грузоотправитель = ФирмаСсылка;
		//$doc->Грузополучатель = КлиентСсылка;		
		$attrs = array();
		$attrs['pay_debt_sum'] = 0;
		$attrs['pay_delay_days'] = 0;
		$attrs['pay_ban_on_debt_sum'] = FALSE;
		$attrs['pay_ban_on_debt_days'] = FALSE;			
		$doc->Контрагент						= $client_ref;
		$doc->ДоговорКонтрагента				= get_client_dog($v8,$client_ref,$firm_ref,$attrs);
		
		$stavka = get_nds($v8);
		$nds_percent = get_nds_percent($v8,$stavka);
		$total = 0;
		foreach($items as $item){
			//номенклатура
			$item_ref = get_item($v8,$item);
			
			$line = $doc->Товары->Добавить();
			$line->ЕдиницаИзмерения	= get_item_mu($v8,$item,$item_ref);
			if ($line->ЕдиницаИзмерения->Пустая()){
				throw new Exception("1c server exception ЕдиницаИзмерения->Пустая()");
			}
			$line->Количество 		= $item['quant'];
			$line->Коэффициент 		= $line->ЕдиницаИзмерения->Коэффициент;
			$line->Номенклатура 	= $item_ref;
			$line->Цена				= $item['price'];
			$line->Сумма			= $item['total'];
			$line->СтавкаНДС		= $stavka;
			$line->СуммаНДС			= round(floatval($item['total'])*$nds_percent/(100+$nds_percent),2);
			
			$total+= $item['total'];
		}
	
		if ($deliv_total){
			$q = intval($head['deliv_vehicle_count']);
			$q = (!$q)? 1:$q;
			
			$item_ref = get_item_deliv($v8);
			
			$line = $doc->Услуги->Добавить();
			$line->Количество 		= $q;
			$line->Содержание		= $item_ref->НаименованиеПолное;
			$line->Номенклатура 	= $item_ref;
			$line->Сумма			= $deliv_total;
			$line->Цена				= round($deliv_total/$q,2);
			
			$line->СтавкаНДС		= $stavka;
			$line->СуммаНДС			= round($deliv_total*$nds_percent/(100+$nds_percent),2);
		
			$total+= $deliv_total;
		}

		if ($pack_total){
			$item_ref = get_item_pack($v8);
			
			$line = $doc->Услуги->Добавить();
			$line->Количество 		= 1;
			$line->Содержание		= $item_ref->НаименованиеПолное;
			$line->Номенклатура 	= $item_ref;
			$line->Цена				= $pack_total;
			$line->Сумма			= $pack_total;			
			$line->СтавкаНДС		= $stavka;
			$line->СуммаНДС			= round($pack_total*$nds_percent/(100+$nds_percent),2);
		
			$total+= $pack_total;
		}
		
		$doc->СуммаДокумента = $total;
		
		//Автор документа
		if ($head['user_ref']){
			$user_id = $v8->NewObject('УникальныйИдентификатор',$head['user_ref']);			
			$doc->Ответственный = $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);			
		}
		
		$doc->Записать($v8->РежимЗаписиДокумента->Запись);
		
		return sprintf(
		'<orderRef>%s</orderRef>
		<orderNum>%s</orderNum>',
		$v8->String($doc->Ссылка->УникальныйИдентификатор()),
		cyr_str_encode($doc->Номер)
		);
	}
	function get_clienton_on_inn($v8,$inn){
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ Клиент.Ссылка КАК ref ИЗ Справочник.Контрагенты КАК Клиент
		ГДЕ Клиент.ИНН="'.$inn.'"';
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return $sel->ref;
		}
	
	}
	function client($v8,$attrs){
		$obj = NULL;
		$client_ref = get_clienton_on_inn($v8,$attrs['inn']);
		if (is_null($client_ref)){
			$obj = $v8->Справочники->Контрагенты->СоздатьЭлемент();
			$obj->Наименование					= stripslashes(cyr_str_decode($attrs['name']));
			$obj->НаименованиеПолное			= stripslashes(cyr_str_decode($attrs['name_full']));
			$obj->Комментарий					= 'Создан из web';
			$obj->ЮрФизЛицо						= (strlen($attrs['inn'])==10)? $v8->Перечисления->ЮрФизЛицо->ЮрЛицо:$v8->Перечисления->ЮрФизЛицо->ФизЛицо;
			$obj->ИНН							= $attrs['inn'];
			$obj->КодПоОКПО						= $attrs['okpo'];
			$obj->КПП							= $attrs['kpp'];
			$obj->Покупатель					= TRUE;
			$obj->Записать();
			
			$client_ref = $obj->Ссылка;
		}
		
		$write = FALSE;
		
		if ($client_ref->ОсновнойБанковскийСчет->Пустая()){
			//Счет
			$acc = $v8->Справочники->БанковскиеСчета->СоздатьЭлемент();
			$acc->Владелец				= $client_ref;
			$acc->Наименование			= 'Основной счет';
			$acc->НомерСчета			= $attrs['acc'];
			$acc->ВалютаДенежныхСредств	= get_currency($v8);
			$bank_ref = $v8->Справочники->Банки->НайтиПоКоду($attrs['bank_code']);
			if ($bank_ref->Пустая()){
				//нет такого банка
				throw new Exception('Банк БИК '.$attrs['bank_code'].' в 1с не найден!');
			}
			$acc->Банк = $bank_ref;		
			$acc->Записать();
			
			if (is_null($obj)){
				$obj = $client_ref->ПолучитьОбъект();
			}		
			$obj->ОсновнойБанковскийСчет = $acc->Ссылка;
			$write = TRUE;			
		}
		
		if (isset($attrs['contract_firm_ext_id'])
		&& $client_ref->ОсновнойДоговорКонтрагента->Пустая()){
			//Есть договор контрагента
			//Договор
			$firm_id = $v8->NewObject('УникальныйИдентификатор',$attrs['contract_firm_ext_id']);
			$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
			
			$dog = create_client_dog($v8,$client_ref,$firm_ref,$attrs);
			if (is_null($obj)){
				$obj = $client_ref->ПолучитьОбъект();
			}
			$obj->ОсновнойДоговорКонтрагента = $dog->Ссылка;
			$write = TRUE;			
		}
		
		if ($write){
			$obj->Записать();
			$client_ref = $obj->Ссылка;
		}
		
		return $v8->String($client_ref->УникальныйИдентификатор());
	}
	function pko($v8,$head){
		$COMMENT = '#web';
		//$CLIENT_NAME = 'Физическое лицо';
		
		foreach($head as $firm_ar){			
			$sum = floatval($firm_ar['total']);
			if (!$sum) continue;
			
			$firm_ref = $firm_ar['firm_ref'];
			$firm_id = $v8->NewObject('УникальныйИдентификатор',$firm_ref);
			$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
			if ($firm_ref->Наименование=='ПФ ПолимерПласт Распопов С.С'){
				$CLIENT_NAME = 'ИП Распопов С.С. ПФ ПолимерПласт';
			}
			else if ($firm_ref->Наименование=='ПолимерБлок Распопов С.С'){
				$CLIENT_NAME = 'ИП Распопов С.С. Полимерблок';
			}			
			else{
				$CLIENT_NAME = 'Физическое лицо';
			}
			$client_ref = $v8->Справочники->Контрагенты->НайтиПоНаименованию($CLIENT_NAME);
			
			//Договор
			$attrs = array();
			$attrs['pay_debt_sum'] = 0;
			$attrs['pay_delay_days'] = 0;
			$attrs['pay_ban_on_debt_sum'] = FALSE;
			$attrs['pay_ban_on_debt_days'] = FALSE;			
			$dog_ref = get_client_dog($v8,$client_ref,$firm_ref,$attrs);
			
			$dds_ref = $v8->Справочники->СтатьиДвиженияДенежныхСредств->НайтиПоНаименованию('Выручка от продажи продукции,товаров, работ, услуг, сырья и иных оборотных активов');
			
			$doc = $v8->Документы->ПриходныйКассовыйОрдер->СоздатьДокумент();	
			$doc->Дата						= date('YmdHis');
			$doc->Касса						= get_kassa($v8,$firm_ref);
			//$doc->Подразделение
			$doc->Организация				= $firm_ref;
			$doc->ВидОперации				= $v8->Перечисления->ВидыОперацийПКО->ОплатаПокупателя;
			$doc->Контрагент				= $client_ref;
			$doc->ДоговорКонтрагента		= $dog_ref;
			$doc->ВалютаДокумента			= get_currency($v8);
			$doc->СуммаДокумента			= $sum;
			$doc->ПринятоОт					= 'Розничные покупатели';
			$doc->Основание					= '';
			$doc->Приложение				= '';
			$doc->Оплачено					= TRUE;
			$doc->ОтраженоВОперУчете		= TRUE;
			$doc->Комментарий				= $COMMENT;
			$doc->ОтражатьВУправленческомУчете	= TRUE;
			$doc->ОтражатьВБухгалтерскомУчете	= TRUE;
			$doc->ОтражатьВНалоговомУчете		= TRUE;
			$doc->СчетУчетаРасчетовСКонтрагентом=$v8->ПланыСчетов->Хозрасчетный->РасчетыСПокупателями;
			$doc->СубконтоКт1					= $client_ref;
			$doc->СубконтоКт2					= $dog_ref;
			$doc->СтатьяДвиженияДенежныхСредств	= $dds_ref;
			$doc->СтавкаНДС						= $v8->Перечисления->СтавкиНДС->НДС18;
			
			//строка
			$line = $doc->РасшифровкаПлатежа->Добавить();
			$line->ДоговорКонтрагента				= $dog_ref;
			$line->КурсВзаиморасчетов				= 1;
			$line->СуммаПлатежа						= $sum;
			$line->КратностьВзаиморасчетов			= 1;
			$line->СуммаВзаиморасчетов				= $sum;
			$line->СуммаНДС							= $v8->Перечисления->СтавкиНДС->НДС18;
			$line->СтатьяДвиженияДенежныхСредств	= $dds_ref;
			$line->СчетУчетаРасчетовСКонтрагентом	= $v8->ПланыСчетов->Хозрасчетный->РасчетыСПокупателями;
			$line->СчетУчетаРасчетовПоАвансам		= $v8->ПланыСчетов->Хозрасчетный->РасчетыПоАвансамПолученным;			
			
			//Автор документа
			if ($firm_ar['user_ref']){
				$user_id = $v8->NewObject('УникальныйИдентификатор',$firm_ar['user_ref']);			
				$doc->Ответственный = $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);			
			}
			
			$doc->Комментарий = 'Заявки покупателей: '.$firm_ar['numbers'].', клиент:'.cyr_str_decode($firm_ar['client_descr']);
			$doc->Записать($v8->РежимЗаписиДокумента->Запись);
		}
	}
?>