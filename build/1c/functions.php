<?php
	/**
	 * Константы, должны быть в Инф Базе!!!
	 */
	/* Справочник УпаковкиЕдиницыИзмерения */
	define("CONST_1C_MU_MM",'м');
	
	/* Справочник Номенклатура */
	define("CONST_1C_ITEM_GROUP_NAME",'Продукция');
	
	/* Справочник ВидыНоменклатуры */
	define("CONST_1C_ITEM_KIND",'Продукция (б/х)');
	
	/* Справочник ГруппыФинансовогоУчетаНоменклатуры */
	define("CONST_1C_FIN_GRP",'Пенопласт');
	
	/* Справочник ДополнительныеОтчетыИОбработки */
	define("CONST_1C_OBR_NAME",'Web CRM Functions');
	
	/* Справочник Номенклатура */
	define("CONST_1C_ITEM_NAME_PACK",'Упаковка');
	define("CONST_1C_ITEM_NAME_DELIV",'Доставка');
		
	/* Справочник Приоритеты */
	define("CONST_1C_PRIORITY",'Средний');
	
	/* Справочник СтруктураПредприятия */
	define("CONST_1C_DEP",'Дирекция');

	/* Дополнительное свойства реализации */
	define('CONST_1C_ACC_ATTR','Расчетный');

	/* Дополнительное свойства реализации */
	define('CONST_1C_DOC_ATTR','Номер CRM');
	
	/* Наименование для соглашения */
	define('CONST_1C_ACCORD_NAME','Типовое соглашение с клиентом');
	
	
//******************************************************************************	
	function cyr_str_decode($str){
		return iconv('UTF-8','Windows-1251',$str);
	}
	
	function cyr_str_encode($str){
		//Ответ отправляем в ANSI
		return $str;//iconv('Windows-1251','UTF-8',$str);
	}
	
	function removeLeadingZero($nStr){
		if (strlen($nStr)==2 && substr($nStr,0,1)=='0'){
			return intval(substr($nStr,1,1));
		}
		else{
			return intval($nStr);
		}
	}
	
	function get_1c_date($d,$h=0,$m=0,$s=0){
		$parts = explode('-',str_replace("\'",'',$d));
		//var_dump($parts);
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

	/*
	 * Надо переделать!!!
	 * Вызывается перед печатью ТТН!!!
	 */
	function fill_struc_for_ttn($v8,$head){
		$attrs = $v8->NewObject('Структура');
		$attrs->Вставить("СрокДоставки",cyr_str_decode($head["deliv_time"]));
		$attrs->Вставить("МаркаАвтомобиля",cyr_str_decode($head["vh_model"]));
		$attrs->Вставить("МаркаПрицепа",cyr_str_decode($head["vh_trailer_model"]));
		$attrs->Вставить("ГосНомерАвтомобиля",cyr_str_decode($head["vh_plate"]));
		$attrs->Вставить("ГосНомерПрицепа",cyr_str_decode($head["vh_trailer_plate"]));
		
		$attrs->Вставить("ПунктПогрузки",cyr_str_decode($head["wareh_descr"]));
		$attrs->Вставить("ПунктРазгрузки",cyr_str_decode($head["dest_descr"]));
		
		$driver_ref = 0;
		if ($head["driver_ref"]){
			$driver_id = $v8->NewObject('УникальныйИдентификатор',$head['driver_ref']);
			$driver_ref = $v8->Справочники->ФизическиеЛица->ПолучитьСсылку($driver_id);			
		}
		$attrs->Вставить("ВодительФЛ",$driver_ref);

		$carrier_ref = 0;
		if ($head["carrier_ref"]){
			$carrier_id = $v8->NewObject('УникальныйИдентификатор',$head['carrier_ref']);
			$carrier_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($carrier_id);			
		}
		$attrs->Вставить("Перевозчик",$carrier_ref);

		$attrs->Вставить("ВидПеревозки",cyr_str_decode($head["deliv_kind"]));
		$attrs->Вставить("ЛицензионнаяКарточка",FALSE);
		$attrs->Вставить("ВодительскоеУдостоверение",cyr_str_decode($head["drive_perm"]));
		return $attrs;
	}
	
	function get_client_on_inn($v8,$inn){		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Клиент.Ссылка КАК ref ИЗ Справочник.Контрагенты КАК Клиент ГДЕ Клиент.ИНН="'.$inn.'"';
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
				return $v8->String($sel->ref->УникальныйИдентификатор());			
		}	
	}

	function get_client_ref_on_inn($v8,$inn){		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Клиент.Ссылка КАК ref ИЗ Справочник.Контрагенты КАК Клиент ГДЕ Клиент.ИНН="'.$inn.'"';
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
				return $sel->ref;			
		}	
	}

	function get_client_attrs($v8,$name){		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='
		ВЫБРАТЬ ПЕРВЫЕ 1
			Клиент.Ссылка КАК ref,
			Клиент.НаименованиеПолное КАК name_full,
			Клиент.ИНН КАК inn,
			Клиент.КПП КАК kpp,
			Клиент.КодПоОКПО КАК okpo,
			ЕстьNULL(КИТелефон.Представление,"""") КАК telephones,
			ЕстьNULL(КИАдресРег.Представление,"""") КАК addr_reg,
			ЕстьNULL(КИАдресПочт.Представление,"""") КАК addr_mail,
			РС.НомерСчета КАК acc,
			Банк.Наименование КАК bank_name,
			Банк.Код КАК bank_code,
			Банк.КоррСчет КАК bank_acc
		
		ИЗ Справочник.Контрагенты КАК Клиент
		
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.Контрагенты.КонтактнаяИнформация КАК КИТелефон
		ПО КИТелефон.Ссылка=Клиент.Ссылка И КИТелефон.Тип=ЗНАЧЕНИЕ(Перечисление.ТипыКонтактнойИнформации.Телефон)
		И КИТелефон.Вид=ЗНАЧЕНИЕ(Справочник.ВидыКонтактнойИнформации.ТелефонКонтрагента)
		
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.Контрагенты.КонтактнаяИнформация КАК КИАдресРег
		ПО КИАдресРег.Ссылка=Клиент.Ссылка И КИАдресРег.Тип=ЗНАЧЕНИЕ(Перечисление.ТипыКонтактнойИнформации.Адрес)
		И КИАдресРег.Вид=ЗНАЧЕНИЕ(Справочник.ВидыКонтактнойИнформации.ЮрАдресКонтрагента)
		
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.Контрагенты.КонтактнаяИнформация КАК КИАдресПочт
		ПО КИАдресПочт.Ссылка=Клиент.Ссылка И КИАдресПочт.Тип=ЗНАЧЕНИЕ(Перечисление.ТипыКонтактнойИнформации.Адрес)
		И КИАдресПочт.Вид =ЗНАЧЕНИЕ(Справочник.ВидыКонтактнойИнформации.ФактАдресКонтрагента)
		
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.БанковскиеСчетаКонтрагентов КАК РС
		ПО НЕ РС.Закрыт И РС.Владелец=Клиент.Ссылка
		
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.КлассификаторБанков КАК Банки
		ПО Банки.Ссылка=РС.Банк
		
		ГДЕ Клиент.Наименование="'.$name.'"		
		';
		
		$sel = $q_obj->Выполнить()->Выбрать();
	
		if ($sel->Следующий()){
			$xml_body = sprintf('<ref>%s</ref>',
				$v8->String($sel->ref->УникальныйИдентификатор()));
			$xml_body.= '<attrs>';
			$xml_body.= sprintf('<name_full>%s</name_full>',
				cyr_str_encode($sel->name_full));
			$xml_body.= sprintf('<inn>%s</inn>',$sel->inn);
			$xml_body.= sprintf('<kpp>%s</kpp>',$sel->kpp);
			$xml_body.= sprintf('<okpo>%s</okpo>',$sel->okpo);
			$xml_body.= sprintf('<telephones>%s</telephones>',
				cyr_str_encode($sel->telephones));
			$xml_body.= sprintf('<addr_reg>%s</addr_reg>',
				cyr_str_encode($sel->addr_reg));
			$xml_body.= sprintf('<addr_mail>%s</addr_mail>',
				cyr_str_encode($sel->addr_mail));
			$xml_body.= sprintf('<acc>%s</acc>',$sel->acc);
			$xml_body.= sprintf('<bank_name>%s</bank_name>',
				cyr_str_encode($sel->bank_name));
			$xml_body.= sprintf('<bank_code>%s</bank_code>',$sel->bank_code);
			$xml_body.= sprintf('<bank_acc>%s</bank_acc>',$sel->bank_acc);
			$xml_body.= '</attrs>';
			
			return $xml_body;
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
		$xml = '';
		while ($sel->Следующий()){
			$xml.= sprintf("<ref name='%s'>%s</ref>",
				cyr_str_encode($sel->name),
				$v8->String($sel->ref->УникальныйИдентификатор()));
		}
		return $xml;
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
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Спр.Ссылка КАК ref
		ИЗ Справочник.'.$sprKind.' КАК Спр
		ГДЕ Спр.Наименование="'.$par.'"';
		//throw new Exception($q_obj->Текст);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return sprintf('<ref>%s</ref>',
				$v8->String($sel->ref->УникальныйИдентификатор()));
		}	
	}
	
	function getPersonRefOnDescr(){
		$descr = $_REQUEST[PAR_NAME];
		if (!isset($descr)){
			throw new Exception("Не задано наименование");
		}
		$v8 = new COM(COM_OBJ_NAME);
		$par = cyr_str_decode($descr);
		$par = str_replace('"','""',$par);
		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Спр.Ссылка КАК ref,
		ДопРек.Значение КАК drive_perm
		ИЗ Справочник.ФизическиеЛица КАК Спр
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.ФизическиеЛица.ДополнительныеРеквизиты КАК ДопРек
		ПО ДопРек.Ссылка=Спр.Ссылка	И ДопРек.Свойство В (
					ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
					ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
					ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
					И Заголовок="Водительское удостоверение"		
		)	
		ГДЕ Спр.Наименование="'.$par.'"';
		
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return sprintf('<ref>%s</ref><drive_perm>%s</drive_perm>',
				$v8->String($sel->ref->УникальныйИдентификатор()),
				$v8->String($sel->drive_perm)
			);
		}	
	}
	
	function getPersonAttrs(){	
		$ref = $_REQUEST[PAR_DRIVER];
		if (!isset($ref)){
			throw new Exception("Не задан водитель");
		}
		$v8 = new COM(COM_OBJ_NAME);
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Знач_plate.Значение КАК plate,
		"" КАК trailer_plate,
		"" КАК trailer_model,
		Знач_carrier.Значение КАК carrier_descr
		
		ИЗ Справочник.ФизическиеЛица КАК Спр
		
		ЛЕВОЕ СОЕДИНЕНИЕ РегистрСведений.ДополнительныеСведения КАК Знач_plate
		ПО Знач_plate.Объект = Спр.Ссылка И Знач_plate.Свойство в (
				ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
				ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
				ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
				И Заголовок="Гос номер автомобиля"		
		)		

		ЛЕВОЕ СОЕДИНЕНИЕ РегистрСведений.ЗначенияСвойствОбъектов КАК Знач_carrier
		ПО Знач_carrier.Объект = Спр.Ссылка И Знач_carrier.Свойство в (
				ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
				ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
				ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
				И Заголовок="Юр. лицо перевозчик"		
		)		
		
		ГДЕ Спр.Ссылка=&ФЛСсылка';
		
		$driver_id = $v8->NewObject('УникальныйИдентификатор',$ref);
		$driver_ref = $v8->Справочники->ФизическиеЛица->ПолучитьСсылку($driver_id);
		$q_obj->УстановитьПараметр('ФЛСсылка',$driver_ref);
		
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return sprintf('<plate>%s</plate><trailer_plate>%s</trailer_plate><trailer_model>%s</trailer_model><carrier_descr>%s</carrier_descr>',
				$v8->String($sel->plate),
				$v8->String($sel->trailer_plate),
				$v8->String($sel->trailer_model),
				$v8->String($sel->carrier_descr)				
			);
		}	
	}
	
	function get_item_mu($v8,$item,$item_ref){		
		$mu_id = $v8->NewObject('УникальныйИдентификатор',$item['measure_unit_ref']);
		$mu_ref = $v8->Справочники->УпаковкиЕдиницыИзмерения->ПолучитьСсылку($mu_id);
		//throw new Exception('MU='.$mu_ref->Наименование.' len='.$item['mes_length']);
		$q = 'ВЫБРАТЬ Ссылка ИЗ Справочник.УпаковкиЕдиницыИзмерения
		ГДЕ Владелец = &Номенклатура И ЕдиницаИзмерения = &ЕдиницаИзмерения';
		$q_obj = $v8->NewObject('Запрос',$q);
		$q_obj->УстановитьПараметр('Номенклатура',$item_ref);
		$q_obj->УстановитьПараметр('ЕдиницаИзмерения',$mu_ref);		
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return $sel->Ссылка;
		}
		
		$k = floatval($item['measure_unit_k']);
		$k = ($k>0)? $k:1;
		
		//миллиметры для д/ш/в
		$mu_ref_mm = $v8->Справочники->УпаковкиЕдиницыИзмерения->НайтиПоНаименованию(CONST_1C_MU_MM,TRUE);
		
		$new_item_mu = $v8->Справочники->УпаковкиЕдиницыИзмерения->СоздатьЭлемент();
		$new_item_mu->Наименование				= sprintf('%s (%f %s)',trim($mu_ref->Наименование),$k,trim($item_ref->ЕдиницаИзмерения->Наименование));
		$new_item_mu->Владелец					= $item_ref;
		$new_item_mu->ЕдиницаИзмерения			= $mu_ref;
		$new_item_mu->ТипИзмеряемойВеличины		= $v8->Перечисления->ТипыИзмеряемыхВеличин->Упаковка;				
		$new_item_mu->ТипУпаковки				= $v8->Перечисления->ТипыУпаковокНоменклатуры->Конечная;
		$new_item_mu->Числитель					= $k;
		$new_item_mu->Глубина					= $item['mes_length'];
		$new_item_mu->ГлубинаЕдиницаИзмерения	= (isset($item['mes_length']))? $mu_ref_mm : NULL;
		$new_item_mu->Ширина					= $item['mes_width'];
		$new_item_mu->ШиринаЕдиницаИзмерения	= (isset($item['mes_length']))? $mu_ref_mm : NULL;
		$new_item_mu->Высота					= $item['mes_height'];
		$new_item_mu->ВысотаЕдиницаИзмерения	= (isset($item['mes_height']))? $mu_ref_mm : NULL;
		$new_item_mu->Записать();
		return $new_item_mu->Ссылка;
	}
	
	function get_item($v8,$item){
		$name = cyr_str_decode($item['product_name']);
		//.' '.$item['mes_length'].'x'.$item['mes_width'].'x'.$item['mes_height'];
		$item_ref = $v8->Справочники->Номенклатура->НайтиПоНаименованию($name,TRUE);		
		if ($item_ref->Пустая()){
			$group1_ref = $v8->Справочники->Номенклатура->НайтиПоНаименованию(CONST_1C_ITEM_GROUP_NAME,TRUE);
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
			
			
			//Базовая единица web
			$bmu_id = $v8->NewObject('УникальныйИдентификатор',$item['base_measure_unit_ref']);
			$bmu_ref = $v8->Справочники->УпаковкиЕдиницыИзмерения->ПолучитьСсылку($bmu_id);
			$item_kind_ref = $v8->Справочники->ВидыНоменклатуры->НайтиПоНаименованию(CONST_1C_ITEM_KIND,TRUE);
			if ($item_kind_ref->Пустая()){
				throw new Exception('Не найден вид номенклатуры "'.CONST_1C_ITEM_KIND.'"!');
				/*
				$item_kind						= $v8->Справочники->ВидыНоменклатуры->СоздатьЭлемент();
				$item_kind->Наименование		= 'Продукция (б/х)';
				$item_kind->ТипНоменклатуры		= $v8->Перечисления->ТипыНоменклатуры->Товар;
				$item_kind_ref = $item_kind->Ссылка;
				*/
			}
		
			$item_fin_grp_ref = $v8->Справочники->ГруппыФинансовогоУчетаНоменклатуры->НайтиПоНаименованию(CONST_1C_FIN_GRP,TRUE);
			if ($item_fin_grp_ref->Пустая()){
				throw new Exception('Не найдена группа фин.контроля номенклатуры "'.CONST_1C_FIN_GRP.'"!');
			}
			//новая номенклатура
			$new_item = $v8->Справочники->Номенклатура->СоздатьЭлемент();
			$new_item->ВариантОформленияПродажи			= $v8->Перечисления->ВариантыОформленияПродажи->РеализацияТоваровУслуг;
			$new_item->Наименование						= $name;
			$new_item->НаименованиеПолное				= $name;
			$new_item->ГруппаФинансовогоУчета			= $item_fin_grp_ref;
			$new_item->Качество							= $v8->Перечисления->ГрадацииКачества->ОграниченноГоден;				
			$new_item->НаборУпаковок					= $v8->Справочники->НаборыУпаковок->ИндивидуальныйДляНоменклатуры;
			//$new_item->ГруппаДоступа					= $v8->Справочники->ГруппыДоступаНоменклатуры->;
			$new_item->ЕдиницаИзмерения					= $bmu_ref;
			$new_item->ИспользованиеХарактеристик		= $v8->Перечисления->ВариантыИспользованияХарактеристикНоменклатуры->НеИспользовать;				
			$new_item->ИспользоватьУпаковки				= TRUE;
			$new_item->СтавкаНДС						= $v8->Перечисления->СтавкиНДС->НДС18;				
			$new_item->ТипНоменклатуры					= $v8->Перечисления->ТипыНоменклатуры->Товар;
			$new_item->ВидНоменклатуры					= $item_kind_ref;
			$new_item->Родитель							= $group2_ref;
			$new_item->Записать();			
			
			$item_ref = $new_item->Ссылка;
		}
		return $item_ref;
	}

	function get_currency($v8){
		return $v8->Справочники->Валюты->НайтиПоКоду('643');
	}
	
	function get_nds($v8,$calcNDS){
		return $calcNDS? $v8->Перечисления->СтавкиНДС->НДС18 : $v8->Перечисления->СтавкиНДС->БезНДС;
	}
	
	function get_nds_percent($calcNDS){
		return $calcNDS? 18 : 0;
	}
	
	function get_ext_obr($v8){
		$ext_form = $v8->Справочники->ДополнительныеОтчетыИОбработки->НайтиПоНаименованию(CONST_1C_OBR_NAME,TRUE);
		if ($ext_form->Пустая()){
			throw new Exception('Не найдена внешняя обработка "'.CONST_1C_OBR_NAME.'"');
		}
		$f = $v8->ПолучитьИмяВременногоФайла();
		$d = $ext_form->ХранилищеОбработки->Получить();
		$d->Записать($f);
		return $v8->ВнешниеОбработки->Создать($f,FALSE);
	}

	function get_doc_comment($head){
		$COMMENT = '';
		if (isset($head['client_comment'])){
			$COMMENT = cyr_str_decode($head['client_comment']);
		}
		if (isset($head['sales_manager_comment'])){
			$COMMENT.= ($COMMENT=='')? '':' ';
			$COMMENT.= cyr_str_decode($head['sales_manager_comment']);
		}
		return $COMMENT;
	}
	
	function get_client_sogl($v8,$client_ref,$firm_ref,$attrs){
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка ИЗ Справочник.СоглашенияСКлиентами
		ГДЕ Статус = ЗНАЧЕНИЕ(Перечисление.СтатусыСоглашенийСКлиентами.Действует)';
		//Организация=&firm И Контрагент=&client И Типовое';		
		//$q_obj->УстановитьПараметр('firm',$firm_ref);
		//$q_obj->УстановитьПараметр('client',$client_ref);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return $sel->Ссылка;
		}
		throw new Exception("Не найдено соглашение с клиентом!");
		//новое соглашение
		$dog = $v8->Справочники->СоглашенияСКлиентами->СоздатьЭлемент();		
		$dog->Наименование							= CONST_1C_ACCORD_NAME;			
		//$dog->Контрагент							= $client_ref;
		$dog->Партнер								= $client_ref->Партнер;
		$dog->Организация							= $firm_ref;
		$dog->Валюта								= get_currency($v8);
		$dog->Типовое								= TRUE;
		$dog->Статус								= $v8->Перечисления->СтатусыСоглашенийСКлиентами->Действует;
		$dog->ХозяйственнаяОперация					= $v8->Перечисления->ХозяйственныеОперации->РеализацияКлиенту;
		$dog->Комментарий							= 'web';
		/*
		if (isset($attrs['contract_number'])){
			$dog->Номер								= $attrs['contract_number'];
		}
		*/
		$dog->Записать();
		return $dog->Ссылка;
	}
	
	function create_client_dog($v8,$clientRef,$firm_ref,$attrs){
		//Новый договор
		$dog = $v8->Справочники->ДоговорыКонтрагентов->СоздатьЭлемент();		
		$dog->Наименование							= 'Основной договор';
		$dog->ВалютаВзаиморасчетов					= get_currency($v8);		
		$dog->Организация							= $firm_ref;
		$dog->Контрагент							= $clientRef;		
		$dog->Партнер								= $clientRef->Партнер;
		$dog->ПорядокОплаты							= $v8->Перечисления->ПорядокОплатыПоСоглашениям->РасчетыВРубляхОплатаВРублях;
		$dog->ПорядокРасчетов						= $v8->Перечисления->ПорядокРасчетов->ПоДоговорамКонтрагентов;//ПоНакладным
		$dog->Статус								= $v8->Перечисления->СтатусыДоговоровКонтрагентов->Действует;
		$dog->ХозяйственнаяОперация					= $v8->Перечисления->ХозяйственныеОперации->РеализацияКлиенту;
		$dog->ТипДоговора							= $v8->Перечисления->ТипыДоговоров->СПокупателем;		
		$dog->Комментарий							= 'web';
		//$dog->НаправлениеДеятельности				= $v8->;
		//$dog->ВидАгентскогоДоговора					= $v8->Перечисления->ВидыАгентскихДоговоров->СПокупателем;
		
		if ($attrs){
			if ($attrs['pay_debt_sum']){
				$dog->ДопустимаяСуммаЗадолженности			= floatval($attrs['pay_debt_sum']);
			}
			if ($attrs['pay_delay_days']){
				//$dog->ДопустимоеЧислоДнейЗадолженности		= intval($attrs['pay_delay_days']);			
			}
			if ($attrs['pay_ban_on_debt_sum']){
				$dog->ЗапрещаетсяПросроченнаяЗадолженность		= ($attrs['pay_ban_on_debt_sum']=='t');
			}
			if ($attrs['pay_ban_on_debt_days']){
				//$dog->КонтролироватьЧислоДнейЗадолженности	= ($attrs['pay_ban_on_debt_days']=='t');
			}
			if (isset($attrs['contract_date_from'])){
				$dog->Дата									= $attrs['contract_date_from'];
				$dog->ДатаНачалаДействия					= $attrs['contract_date_from'];
			}
			if (isset($attrs['contract_number'])){
				$dog->Номер									= $attrs['contract_number'];
			}
			if (isset($attrs['contract_date_to'])){
				$dog->ДатаОкончанияДействия							= $attrs['contract_date_to'];
			}
		}
		
		$dog->Записать();		
		return $dog->Ссылка;
	}
	
	function get_client_dog($v8,$client_ref,$firm_ref,$attrs){			
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка ИЗ Справочник.ДоговорыКонтрагентов
		ГДЕ Контрагент=&client И Организация=&firm И ТипДоговора=ЗНАЧЕНИЕ(Перечисление.ТипыДоговоров.СПокупателем)';
		$q_obj->УстановитьПараметр('client',$client_ref);
		$q_obj->УстановитьПараметр('firm',$firm_ref);		
		$sel = $q_obj->Выполнить()->Выбрать();
		$dog_ref = NULL;
		if ($sel->Следующий()){
			return $sel->Ссылка;
		}
		
		return create_client_dog($v8,$client_ref,$firm_ref,$attrs);
	}
	
	function get_item_pack($v8){
		return $v8->Справочники->Номенклатура->НайтиПоНаименованию(CONST_1C_ITEM_NAME_PACK,TRUE);
	}
	function get_item_deliv($v8){
		return $v8->Справочники->Номенклатура->НайтиПоНаименованию(CONST_1C_ITEM_NAME_DELIV,TRUE);
	}
	
	function get_kassa($v8,$firm_ref){		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Ссылка
		ИЗ Справочник.Кассы
		ГДЕ Владелец=&ФирмаСсылка
		';
		$q_obj->УстановитьПараметр('ФирмаСсылка',$firm_ref);
		
		$sel = $q_obj->Выполнить()->Выбрать();
		$kassa_ref = NULL;
		if ($sel->Следующий()){
			$kassa_ref = $sel->Ссылка;
		}
		
		if (is_null($kassa_ref)){
			$kassa_ob = $v8->Справочники->Кассы->СоздатьЭлемент();
			$kassa_ob->Владелец					= $firm_ref;
			$kassa_ob->Наименование				= 'Основная касса';
			$kassa_ob->ВалютаДенежныхСредств	= get_currency($v8);
			$kassa_ob->Записать();
			$kassa_ref = $kassa_ob->Ссылка;
		}
		
		return $kassa_ref;
	}
	
	function fill_otvetstv($v8,$firm_ref,$head_user_ref,$otvType,&$user_ref,&$otv_ref,&$otv_post){
		$user_id = $v8->NewObject('УникальныйИдентификатор',$head_user_ref);			
		$user_ref = $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);
		
		if (!$user_ref->ФизическоеЛицо->Пустая()){
			$par_date = date("Y,m,d,23,59,59");
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
			Отв.Ссылка КАК ОтвЛицо,
			Отв.Должность КАК Должность
			ИЗ Справочник.ОтветственныеЛицаОрганизаций КАК Отв
			ГДЕ			
			(Отв.Владелец=&Организация)
			И (Отв.ФизическоеЛицо=&ФизическоеЛицо)
			И (Отв.ДатаОкончания = ДАТАВРЕМЯ(1,1,1,0,0,0)
			ИЛИ ( (Отв.ДатаОкончания <> ДАТАВРЕМЯ(1,1,1,0,0,0))
				И (Отв.ДатаОкончания < ДАТАВРЕМЯ('.date('Y,m,d,23,59,59').'))
				)
			)
			И (Отв.ОтветственноеЛицо=&ОтветственноеЛицо)
			';
			$q_obj->УстановитьПараметр('Организация',$firm_ref);
			$q_obj->УстановитьПараметр('ФизическоеЛицо',$user_ref->ФизическоеЛицо);
			$q_obj->УстановитьПараметр('ОтветственноеЛицо',$otvType);
			$sel = $q_obj->Выполнить()->Выбрать();
			if ($sel->Следующий()){
				$otv_post = $sel->Должность;
				$otv_ref = $sel->ОтвЛицо;
			}				
		}
	}
	
	function get_org_acc($v8,$firmRef){
		$acc_ref = NULL;
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Счет.Ссылка ИЗ Справочник.БанковскиеСчетаОрганизаций КАК Счет
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.БанковскиеСчетаОрганизаций.ДополнительныеРеквизиты КАК Доп
		По Доп.Ссылка=Счет.Ссылка И Доп.Свойство в (
			ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
			ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
			ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_БанковскиеСчетаОрганизаций)
			И Заголовок="'.CONST_1C_ACC_ATTR.'"		
		)
		ГДЕ Счет.Владелец = &firm И Доп.Значение=Истина';		
		$q_obj->УстановитьПараметр('firm',$firmRef);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
				$acc_ref = $sel->Ссылка;
		}		
		return $acc_ref;
	}

	function get_client_acc($v8,$clientRef){
		$acc_ref = NULL;
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Счет.Ссылка ИЗ Справочник.БанковскиеСчетаКонтрагентов КАК Счет
		ГДЕ Счет.Владелец = &client И НЕ Счет.Закрыт И НЕ Счет.ПометкаУдаления';		
		$q_obj->УстановитьПараметр('client',$clientRef);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
				$acc_ref = $sel->Ссылка;
		}		
		return $acc_ref;
	}

	function get_partner($v8,$attrs,$name,$nameFull){
		$partner_ref = $v8->Справочники->Партнеры->НайтиПоНаименованию($name,TRUE());
		if ($partner_ref->Пустая()){
			$obj = $v8->Справочники->Партнеры->СоздатьЭлемент();
			$obj->Клиент				= TRUE;
			$obj->Комментарий			= 'Web';
			$obj->НаименованиеПолное	= $nameFull;
			$obj->ЮрФизЛицо				= (strlen($attrs['inn'])==10)? $v8->Перечисления->КомпанияЧастноеЛицо->Компания:$v8->Перечисления->КомпанияЧастноеЛицо->ЧастноеЛицо;
			$obj->Записать();
			
			$partner_ref = $obj->Ссылка;
		}
		return $partner_ref;
	}
	
	function client($v8,$attrs){
		$obj = NULL;
		$client_ref = get_client_ref_on_inn($v8,$attrs['inn']);
		if (is_null($client_ref)){
			$obj = $v8->Справочники->Контрагенты->СоздатьЭлемент();
			$obj->Наименование					= stripslashes(cyr_str_decode($attrs['name']));
			$obj->НаименованиеПолное			= stripslashes(cyr_str_decode($attrs['name_full']));
			$obj->ЮрФизЛицо						= (strlen($attrs['inn'])==10)? $v8->Перечисления->ЮрФизЛицо->ЮрЛицо:$v8->Перечисления->ЮрФизЛицо->ФизЛицо;
			$obj->ЮридическоеФизическоеЛицо		= (strlen($attrs['inn'])==10)? $v8->Перечисления->ЮридическоеФизическоеЛицо->ЮридическоеЛицо:$v8->Перечисления->ЮридическоеФизическоеЛицо->ФизическоеЛицо;
			$obj->ИНН							= $attrs['inn'];
			$obj->КодПоОКПО						= $attrs['okpo'];
			$obj->КПП							= $attrs['kpp'];
			$obj->Партнер						= get_partner($v8,$attrs,$obj->Наименование,$obj->НаименованиеПолное);
			$obj->Записать();
			
			$client_ref = $obj->Ссылка;
		}
		
		$acc = get_client_acc($v8,$client_ref);
		
		if (is_null($acc)){
			$acc = $v8->Справочники->БанковскиеСчетаКонтрагентов->СоздатьЭлемент();
			$acc->Владелец				= $client_ref;
			$acc->Наименование			= 'Основной счет';
			$acc->НомерСчета			= $attrs['acc'];
			$acc->ВалютаДенежныхСредств	= get_currency($v8);
			$bank_ref = $v8->Справочники->КлассификаторБанков->НайтиПоКоду($attrs['bank_code']);
			if ($bank_ref->Пустая()){
				//нет такого банка
				throw new Exception('Банк БИК '.$attrs['bank_code'].' в 1с не найден!');
			}
			$acc->Банк = $bank_ref;		
			$acc->Записать();			
		}
		
		return $v8->String($client_ref->УникальныйИдентификатор());
	}
	
	function order($v8,$head,$items){		
		$COMMENT = 'web '.get_doc_comment($head);
		
		$firm_id = $v8->NewObject('УникальныйИдентификатор',$head['firm_ref']);
		
		$warehouse_id = $v8->NewObject('УникальныйИдентификатор',$head['warehouse_ref']);
		$client_id = $v8->NewObject('УникальныйИдентификатор',$head['client_ref']);
		
		$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
		$client_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($client_id);
		
		$warehouse_ref = $v8->Справочники->Склады->ПолучитьСсылку($warehouse_id);
		
		$deliv_total = floatval($head['deliv_total']);
		$pack_total = floatval($head['pack_total']);
		
		$acc_ref = get_org_acc($v8,$firm_ref);
		
		if (isset($head['ext_order_id'])){
			//модификация документа
			$order_id = $v8->NewObject('УникальныйИдентификатор',$head['ext_order_id']);
			$order_ref = $v8->Документы->ЗаказКлиента->ПолучитьСсылку($order_id);
			$doc = $order_ref->ПолучитьОбъект();
			if ($doc->Проведен){
				$doc->Проведен = FALSE;
			}
			if ($doc->Товары->Количество()>0){
				$doc->Товары->Очистить();
			}			
		}
		else{
			$doc = $v8->Документы->ЗаказКлиента->СоздатьДокумент();	
		}
		
		$calcNDS = ($head['firm_nds']=='t' || $head['firm_nds']=='true');
		
		$attrs = array();
		$attrs['pay_debt_sum'] = 0;
		$attrs['pay_delay_days'] = 0;
		$attrs['pay_ban_on_debt_sum'] = FALSE;
		$attrs['pay_ban_on_debt_days'] = FALSE;			
		
		$doc->Дата								= $head['date'];
		$doc->Партнер							= $client_ref->Партнер;
		$doc->Контрагент						= $client_ref;	
		$doc->Организация						= $firm_ref;
		$doc->Соглашение						= get_client_sogl($v8,$client_ref,$firm_ref,$attrs);
		$doc->Валюта							= get_currency($v8);
		$doc->ЖелаемаяДатаОтгрузки				= ($head['delivery_plan_date'])? get_1c_date($head['delivery_plan_date']) : NULL;
		$doc->Склад								= $warehouse_ref;
		$doc->ЦенаВключаетНДС					= TRUE;
		$doc->Статус							= $v8->Перечисления->СтатусыЗаказовКлиентов->НеСогласован; //$v8->Перечисления->СтатусыЗаказовКлиентов->КОтгрузке :
		$doc->Согласован						= TRUE;
		$doc->ДатаСогласования					= $doc->Дата;
		$doc->ФормаОплаты						= ($head['pay_cash']=='t')?
														$v8->Перечисления->ФормыОплаты->Наличная : 
														$v8->Перечисления->ФормыОплаты->Безналичная;
		$doc->БанковскийСчет					= $acc_ref;
		//$doc->БанковскийСчетКонтрагента
		if ($head['pay_cash']=='t'){
			$doc->Касса = get_kassa($v8,$firm_ref);
		}
		//$doc->ДатаОтгрузки						= $doc->ЖелаемаяДатаОтгрузки;
		$doc->АдресДоставки						= cyr_str_decode($head['deliv_address']);
		$doc->НалогообложениеНДС				= ($calcNDS)?
													$v8->Перечисления->ТипыНалогообложенияНДС->ПродажаОблагаетсяНДС :
													$v8->Перечисления->ТипыНалогообложенияНДС->ПродажаНеОблагаетсяНДС;
		$doc->ХозяйственнаяОперация				= $v8->Перечисления->ХозяйственныеОперации->РеализацияКлиенту;
		$doc->Комментарий						= $COMMENT;
		$doc->Договор							= get_client_dog($v8,$client_ref,$firm_ref,$attrs);
		
		$doc->ПорядокРасчетов					= $doc->Договор->ПорядокРасчетов; //$v8->Перечисления->ПорядокРасчетов->ПоЗаказамНакладным;
		$doc->СпособДоставки					= $v8->Перечисления->СпособыДоставки->Самовывоз;		
		/*
												($deliv_total)?
														$v8->Перечисления->СпособыДоставки->ДоКлиента : 
													$v8->Перечисления->СпособыДоставки->Самовывоз;
		*/
		//$doc->ПеревозчикПартнер
		$doc->ПорядокОплаты						= $v8->Перечисления->ПорядокОплатыПоСоглашениям->РасчетыВРубляхОплатаВРублях;
		$doc->Приоритет							= $v8->Справочники->Приоритеты->НайтиПоНаименованию(CONST_1C_PRIORITY,TRUE);
		$doc->НеОтгружатьЧастями				= TRUE;
		$doc->ДатаОтгрузки						= ($head['delivery_plan_date'])? get_1c_date($head['delivery_plan_date']) : $head['date'];
		
		$stavka = get_nds($v8,$calcNDS);
		$nds_percent = get_nds_percent($calcNDS);
		$total = (float) 0.0;
		foreach($items as $item){
			//номенклатура
			$item_ref = get_item($v8,$item);
			
			$line = $doc->Товары->Добавить();
			$line->Номенклатура 			= $item_ref;
			$line->КоличествоУпаковок 		= floatval($item['quant']);
			$line->Упаковка					= get_item_mu($v8,$item,$item_ref);
			$line->Сумма					= floatval($item['total']);
			$line->Цена						= $line->Сумма/$line->КоличествоУпаковок;
			$line->ВариантОбеспечения		= $v8->Перечисления->ВариантыОбеспечения->Требуется; //Отгрузить;
			$line->Склад					= $doc->Склад;
			$line->Количество				= $line->КоличествоУпаковок * floatval($item['measure_unit_k']);
			//$line->ДатаОтгрузки			= 
			
			$line->СтавкаНДС				= $stavka;
			if ($calcNDS){				
				$line->СуммаНДС				= round(floatval($item['total'])*$nds_percent/(100+$nds_percent),2);
			}
			$line->СуммаСНДС 				= $line->Сумма;
			
			$total+= floatval($item['total']);
		}
	
		if ($deliv_total){
			$q = intval($head['deliv_vehicle_count']);
			$q = (!$q)? 1:$q;
			
			$item_ref = get_item_deliv($v8);
			$line = $doc->Товары->Добавить();			
			$line->Номенклатура 			= $item_ref;
			$line->КоличествоУпаковок 		= $q;
			$line->Упаковка					= $item_ref->ЕдиницаИзмерения;
			$line->Цена						= round($deliv_total/floatval($q),2);
			$line->Сумма					= $deliv_total;
			$line->ВариантОбеспечения		= $v8->Перечисления->ВариантыОбеспечения->Требуется; //Отгрузить;						
			$line->Склад					= $doc->Склад;
			$line->Количество				= 1;
			
			$line->СтавкаНДС				= $stavka;
			if ($calcNDS){				
				$line->СуммаНДС				= round($deliv_total*$nds_percent/(100+$nds_percent),2);
			}
			$line->СуммаСНДС = $line->Сумма;
			
			$total+= $deliv_total;
		}

		if ($pack_total){
			$item_ref = get_item_pack($v8);
			$line = $doc->Товары->Добавить();
			$line->КоличествоУпаковок 		= 1;
			$line->Упаковка					= $item_ref->ЕдиницаИзмерения;//get_item_mu($v8,$item,$item_ref);
			$line->Номенклатура 			= $item_ref;
			$line->Цена						= $pack_total;
			$line->Сумма					= $pack_total;
			$line->Количество				= 1;
			
			$line->СтавкаНДС				= $stavka;
			if ($calcNDS){				
				$line->СуммаНДС				= round($pack_total*$nds_percent/(100+$nds_percent),2);
			}
			$line->СуммаСНДС = $line->Сумма;
			
			$total+= $pack_total;
		}
		
		/*
		$line = $doc->ЭтапыГрафикаОплаты->Добавить();
		$line->ВариантОплаты	= $v8->Перечисления->ВариантыОплатыКлиентом->ПредоплатаДоОтгрузки;
		$line->ДатаПлатежа		= $doc->Дата;
		$line->ПроцентПлатежа	= 100;
		$line->СуммаПлатежа		= $total;
		*/
		//КредитПослеОтгрузки
		//АвансДоОбеспечения

		//Автор документа
		if ($head['user_ref']){
			$user_ref = $v8->Справочники->Пользователи->ПустаяСсылка();
			
			$otv1_ref = $v8->Справочники->ОтветственныеЛицаОрганизаций->ПустаяСсылка();
			$otv1_post = '';
			fill_otvetstv($v8,$firm_ref,$head['user_ref'],$v8->Перечисления->ОтветственныеЛицаОрганизаций->Руководитель,$user_ref,$otv1_ref,$otv1_post);

			$otv2_ref = $v8->Справочники->ОтветственныеЛицаОрганизаций->ПустаяСсылка();
			$otv2_post = '';
			fill_otvetstv($v8,$firm_ref,$head['user_ref'],$v8->Перечисления->ОтветственныеЛицаОрганизаций->ГлавныйБухгалтер,$user_ref,$otv2_ref,$otv2_post);
			
			$doc->Автор				= $user_ref;			
			$doc->Менеджер			= $user_ref;						
			$doc->Руководитель		= $otv1_ref;					
			$doc->ГлавныйБухгалтер	= $otv2_ref;
		}

		$doc->СуммаДокумента		= $total;
		$doc->Записать($v8->РежимЗаписиДокумента->Проведение);		
		
		return sprintf(
		'<orderRef>%s</orderRef>
		<orderNum>%s</orderNum>',
		$v8->String($doc->Ссылка->УникальныйИдентификатор()),
		cyr_str_encode($doc->Номер)
		);
	}

	function pko($v8,$head,$payTypeCash){
		$COMMENT = '#web';
		//$CLIENT_NAME = 'Физическое лицо';
		
		foreach($head as $firm_ar){			
			$sum = floatval($firm_ar['total']);
			if (!$sum) continue;
			
			$firm_ref = $firm_ar['firm_ref'];
			$firm_id = $v8->NewObject('УникальныйИдентификатор',$firm_ref);
			$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
			/*
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
			*/
			$client_ref = $firm_ar['client_ref'];
			$client_id = $v8->NewObject('УникальныйИдентификатор',$client_ref);			
			$client_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($client_id);
			
			//Договор
			/*
			$attrs = array();
			$attrs['pay_debt_sum'] = 0;
			$attrs['pay_delay_days'] = 0;
			$attrs['pay_ban_on_debt_sum'] = FALSE;
			$attrs['pay_ban_on_debt_days'] = FALSE;			
			$dog_ref = get_client_dog($v8,$client_ref,$firm_ref,$attrs);
			*/
			
			$doc = $v8->Документы->ПриходныйКассовыйОрдер->СоздатьДокумент();	
			$doc->Дата							= date('YmdHis');
			$doc->Касса							= get_kassa($v8,$firm_ref);
			$doc->Организация					= $firm_ref;
			$doc->СуммаДокумента				= $sum;			
			$doc->ХозяйственнаяОперация			= $v8->Перечисления->ХозяйственныеОперации->ПоступлениеОплатыОтКлиента;
			$doc->ПринятоОт						= 'Розничные покупатели';
			$doc->Основание						= '';
			$doc->Приложение					= '';
			$doc->Контрагент					= $client_ref;
			$doc->Валюта						= get_currency($v8);
			$doc->СтатьяДвиженияДенежныхСредств	= $v8->Справочники->СтатьиДвиженияДенежныхСредств->ПоступлениеОплатыОтКлиента;
			
			//строка
			$line = $doc->РасшифровкаПлатежа->Добавить();
			$line->СтатьяДвиженияДенежныхСредств	= $doc->СтатьяДвиженияДенежныхСредств;
			$line->Сумма							= $sum;
			$line->ВалютаВзаиморасчетов				= $doc->Валюта;
			$line->СуммаВзаиморасчетов				= $sum;
			$line->СтавкаНДС						= $v8->Перечисления->СтавкиНДС->НДС18;
			$line->СуммаНДС							= round($sum*18/118,2);
			
			//Автор документа
			/*
			if ($firm_ar['user_ref']){
				$user_id = $v8->NewObject('УникальныйИдентификатор',$firm_ar['user_ref']);			
				$doc->Ответственный = $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);			
			}
			*/
			if ($payTypeCash){
				$pref='КАРТА ';
			}
			else{
				$pref='';
			}
			$doc->Комментарий = $pref.'Заявки покупателей: '.$firm_ar['numbers'].', клиент:'.cyr_str_decode($firm_ar['client_descr']);
			$doc->Записать($v8->РежимЗаписиДокумента->Запись);
		}
	}
	
	function sale($v8,$head,$items){		
		$q_crm_num = 'ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
		ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
		ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Документ_РеализацияТоваровУслуг)
		И Заголовок="'.CONST_1C_DOC_ATTR.'"';
				
		if ($head['number']){
			//попробуем найти по номеру, вдруг документ уже есть в 1с?!
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст ='ВЫБРАТЬ
			Док.Ссылка,
			Док.Номер,
			Счф.Ссылка КАК СчфСсылка,
			Счф.Номер КАК СчфНомер
			ИЗ Документ.РеализацияТоваровУслуг КАК Док			
			ЛЕВОЕ СОЕДИНЕНИЕ Документ.СчетФактураВыданный КАК Счф
			ПО Счф.ДокументОснование = Док.Ссылка			
			ЛЕВОЕ СОЕДИНЕНИЕ РегистрСведений.ДополнительныеСведения КАК ДопРек
			ПО ДопРек.Объект = Док.Ссылка И ДопРек.Свойство в ('.$q_crm_num.')
			ГДЕ ДопРек.Значение=&ЗначениеСвойстваНомерCRM
			';
			$q_obj->УстановитьПараметр('ЗначениеСвойстваНомерCRM',$head['number']);
			$sel = $q_obj->Выполнить()->Выбрать();
			if ($sel->Следующий()){
				if ($sel->СчфСсылка){
					$inv_num = cyr_str_encode($sel->СчфНомер);
					$inv_id = $v8->String($sel->СчфСсылка->УникальныйИдентификатор());
				}
				else{
					$inv_num = '';
					$inv_id = '';
				}
				return sprintf(
					'<naklRef>%s</naklRef>
					<naklNum>%s</naklNum>
					<invRef>%s</invRef>
					<invNum>%s</invNum>',		
					$v8->String($sel->Ссылка->УникальныйИдентификатор()),
					cyr_str_encode($sel->Номер),
					$inv_id,
					$inv_num
				);
			}
		}
	
		$COMMENT = get_doc_comment($head);
		
		$firm_id = $v8->NewObject('УникальныйИдентификатор',$head['firm_ref']);
		$warehouse_id = $v8->NewObject('УникальныйИдентификатор',$head['warehouse_ref']);
		$client_id = $v8->NewObject('УникальныйИдентификатор',$head['client_ref']);
		
		$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
		$client_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($client_id);
		$warehouse_ref = $v8->Справочники->Склады->ПолучитьСсылку($warehouse_id);		
	
		$deliv_total = floatval($head['deliv_total']);
		$pack_total = floatval($head['pack_total']);
		
		$doc = $v8->Документы->РеализацияТоваровУслуг->СоздатьДокумент();	
		
		/* Расширенный комментарий с указанием счета*/
		$order_num = '';
		if (isset($head['ext_order_id'])){
			$order_id = $v8->NewObject('УникальныйИдентификатор',$head['ext_order_id']);
			$order_ref = $v8->Документы->ЗаказКлиента->ПолучитьСсылку($order_id);
			$order_num = '/'.substr($order_ref->Номер,strlen($order_ref->Номер)-5).' ';
			$doc->ЗаказКлиента = $order_ref;
			
			//$order_obj = $order_ref->ПолучитьОбъект();
			//$order_obj->Статус = $v8->Перечисления->СтатусыЗаказовКлиентов->КОбеспечению;
			//$order_obj->Записать();
		}
		
		if ($head['carrier_ref']){
			$carrier_id = $v8->NewObject('УникальныйИдентификатор',$head['carrier_ref']);
			$carrier_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($carrier_id);							
		}
		else if ($head['deliv_type']=='by_supplier'){
			//org->>client
			$carrier_ref = get_client_ref_on_inn($v8,$firm_ref->ИНН);	
			if (is_null($carrier_ref)){
				throw new Exception('Не найдена организация '.$firm_ref->Наименование.' в справочнике контрагентов по ИНН для перевочика в ТТН!');
			}			
		}
		
		$doc->Дата						= $head['date'];
		
		$calcNDS = ($head['firm_nds']=='t' || $head['firm_nds']=='true');
		
		$doc->АдресДоставки				= cyr_str_decode($head['deliv_address']);
		$doc->Валюта					= get_currency($v8);
		$doc->ВалютаВзаиморасчетов		= $doc->Валюта;		
		$doc->Организация				= $firm_ref;
		$doc->Контрагент				= $client_ref;
		$doc->НалогообложениеНДС		= ($calcNDS)?
											$v8->Перечисления->ТипыНалогообложенияНДС->ПродажаОблагаетсяНДС :
											$v8->Перечисления->ТипыНалогообложенияНДС->ПродажаНеОблагаетсяНДС;
		$doc->ДатаПлатежа				= $doc->Дата;
		$doc->ДатаРаспоряжения			= get_1c_date(date('Y-m-d'),date('G'),removeLeadingZero(date('i')),removeLeadingZero(date('s')));
		$doc->Партнер					= $client_ref->Партнер;
		$doc->Подразделение				= $v8->Справочники->СтруктураПредприятия->НайтиПоНаименованию(CONST_1C_DEP,TRUE);
		$doc->Склад						= $warehouse_ref;
		$doc->Комментарий				= 'web '.
											( ($head['number'])? $head['number'] : '').
											$order_num.$COMMENT;
		$doc->ФормаОплаты				= ($head['pay_cash']=='t')? $v8->Перечисления->ФормыОплаты->Наличная:$v8->Перечисления->ФормыОплаты->Безналичная;
		$doc->ХозяйственнаяОперация		= $v8->Перечисления->ХозяйственныеОперации->РеализацияКлиенту;
		$doc->ЦенаВключаетНДС			= $calcNDS;
		$doc->СкидкиРассчитаны			= FALSE;
		if ($head['pay_cash']=='t'){
			$doc->Касса = get_kassa($v8,$firm_ref);
		}
		
		$attrs = array();
		$attrs['pay_debt_sum'] = 0;
		$attrs['pay_delay_days'] = 0;
		$attrs['pay_ban_on_debt_sum'] = FALSE;
		$attrs['pay_ban_on_debt_days'] = FALSE;					
		$doc->Договор					= get_client_dog($v8,$client_ref,$firm_ref,$attrs);		
		$doc->Основание					= $doc->Договор->Наименование;
		$doc->ОснованиеДата	 			= $doc->Договор->Дата;
		$doc->ОснованиеНомер			= $doc->Договор->Номер;
		$doc->Соглашение				= get_client_sogl($v8,$client_ref,$firm_ref,$attrs);
		$doc->Статус					= $v8->Перечисления->СтатусыРеализацийТоваровУслуг->Отгружено;
		
		$doc->СпособДоставки			= $v8->Перечисления->СпособыДоставки->Самовывоз;
											/*($deliv_total)?
												$v8->Перечисления->СпособыДоставки->ДоКлиента : 
												$v8->Перечисления->СпособыДоставки->Самовывоз;
											*/
		if ($head['carrier_ref']){
			$doc->ПеревозчикПартнер		= $carrier_ref->Партнер;
		}		
											
		$doc->Кратность					= 1;
		$doc->Курс						= 1;
		$doc->РеализацияПоЗаказам		= FALSE;
		$doc->ПорядокРасчетов			= $doc->Договор->ПорядокРасчетов; //$v8->Перечисления->ПорядокРасчетов->ПоЗаказамНакладным;
		$doc->ВернутьМногооборотнуюТару	= FALSE;
		$doc->ВидыЗапасовУказаныВручную	= FALSE;
		$doc->ТребуетсяЗалогЗаТару		= FALSE;
		$doc->ВариантОформленияПродажи	= $v8->Перечисления->ВариантыОформленияПродажи->РеализацияТоваровУслуг;
		$doc->ОсобыеУсловияПеревозки	= FALSE;
		$doc->ПорядокОплаты				= $v8->Перечисления->ПорядокОплатыПоСоглашениям->РасчетыВРубляхОплатаВРублях;
		$doc->ЕстьМаркируемаяПродукцияГИСМ = FALSE;		
				
		$stavka = get_nds($v8,$calcNDS);
		$nds_percent = get_nds_percent($calcNDS);
		$total=0;
		
		foreach($items as $item){
			if (!$item['quant']){
				//После деления заявки приходят пустые номенклатуры???
				continue;
			}
			//номенклатура
			$item_ref = get_item($v8,$item);
			
			$line = $doc->Товары->Добавить();
			$line->Номенклатура 			= $item_ref;
			$line->КоличествоУпаковок 		= $item['quant'];			
			$line->Упаковка					= get_item_mu($v8,$item,$item_ref);
			$line->Сумма					= $item['total'];
			$line->Цена						= $line->Сумма/$line->КоличествоУпаковок;
			$line->Склад					= $doc->Склад;
			$line->Количество				= $line->КоличествоУпаковок * floatval($item['measure_unit_k']);
			
			$line->СтавкаНДС				= $stavka;
			if ($calcNDS){				
				$line->СуммаНДС				= round(floatval($item['total'])*$nds_percent/(100+$nds_percent),2);
			}
			$line->СуммаСНДС				= $line->Сумма;
			$line->СуммаВзаиморасчетов		= $line->Сумма;
			
			$total+= floatval($item['total']);
			
		}
	
		if ($deliv_total){
			$q = intval($head['deliv_vehicle_count']);
			$q = (!$q)? 1:$q;
		
			$item_ref = get_item_deliv($v8);
			$line = $doc->Товары->Добавить();			
			$line->Номенклатура 			= $item_ref;			
			$line->Цена						= round($deliv_total/floatval($q),2);
			$line->Сумма					= $deliv_total;
			$line->Количество				= $q;
			$line->КоличествоУпаковок 		= $q;
			
			$line->СтавкаНДС				= $stavka;
			if ($calcNDS){				
				$line->СуммаНДС				= round($deliv_total*$nds_percent/(100+$nds_percent),2);
			}
			$line->СуммаСНДС				= $line->Сумма;
			$line->СуммаВзаиморасчетов		= $line->Сумма;
			
			$total+= $deliv_total;
		}

		if ($pack_total){
			$item_ref = get_item_pack($v8);
			$line = $doc->Товары->Добавить();			
			//$line->Упаковка				= $item_ref->ЕдиницаИзмерения;
			$line->Номенклатура 			= $item_ref;
			$line->Цена						= $pack_total;
			$line->Сумма					= $pack_total;
			$line->Количество				= 1;
			$line->КоличествоУпаковок 		= 1;
			
			$line->СтавкаНДС				= $stavka;
			if ($calcNDS){				
				$line->СуммаНДС				= round($pack_total*$nds_percent/(100+$nds_percent),2);
			}
			$line->СуммаСНДС				= $line->Сумма;
			$line->СуммаВзаиморасчетов		= $line->Сумма;
			
			$total+= $pack_total;
		}
		
		//Автор документа
		if ($head['user_ref']){
			$user_ref = $v8->Справочники->Пользователи->ПустаяСсылка();
			
			$otv1_post = '';
			fill_otvetstv($v8,$firm_ref,$head['user_ref'],$v8->Перечисления->ОтветственныеЛицаОрганизаций->Руководитель,$user_ref,$otv1_ref,$otv1_post);

			$otv2_ref = $v8->Справочники->ОтветственныеЛицаОрганизаций->ПустаяСсылка();
			$otv2_post = '';
			fill_otvetstv($v8,$firm_ref,$head['user_ref'],$v8->Перечисления->ОтветственныеЛицаОрганизаций->ГлавныйБухгалтер,$user_ref,$otv2_ref,$otv2_post);
			
			$doc->Автор = $user_ref;			
			$doc->Менеджер = $user_ref;			
			$doc->Отпустил = $user_ref->ФизическоеЛицо;			
			$doc->ОтпустилДолжность = $otv1_post;
			$doc->ГлавныйБухгалтер = $otv2_ref;
			$doc->Руководитель = $otv1_ref;					
		}
		
		$doc->СуммаДокумента = $total;
		$doc->СуммаВзаиморасчетов = $total;
		$doc->Записать($v8->РежимЗаписиДокумента->Проведение);//Проведение
		
		if ($head['number']){
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст =$q_crm_num;
			$sel = $q_obj->Выполнить()->Выбрать();
			if (!$sel->Следующий()){
				throw new Exception('Не найдено доп.свойство реализации Номер CRM!');
			}
			$rec_set = $v8->РегистрыСведений->ДополнительныеСведения->СоздатьНаборЗаписей();
			$rec_set->Отбор->Объект->Установить($doc->Ссылка);
			$rec = $rec_set->Добавить();
			$rec->Объект = $doc->Ссылка;
			$rec->Свойство = $sel->Ссылка;
			$rec->Значение = $head['number'];			
			$rec_set->Записать();
		}		
		
		//Счет фактура
		$inv_id = '';
		$inv_num = '';
				
		if ($head['pay_cash']!='t' && $calcNDS){
			$doc_inv = $v8->Документы->СчетФактураВыданный->СоздатьДокумент();			
			$doc_inv->Заполнить($doc->Ссылка);
			$doc_inv->Дата 				= $doc->Дата;
			$doc_inv->Организация 		= $doc->Организация;
			$doc_inv->Контрагент		= $doc->Контрагент;
			$doc_inv->ДокументОснование	= $doc->Ссылка;
			$doc_inv->ДатаВыставления	= $doc->Дата;
			$doc_inv->Подразделение		= $doc->Подразделение;
			
			$line = $doc_inv->ДокументыОснования->Добавить();
			$line->ДокументОснование = $doc->Ссылка;
			
			$doc_inv->Записать($v8->РежимЗаписиДокумента->Проведение);	//Запись Проведение
			
			$inv_id = $v8->String($doc_inv->Ссылка->УникальныйИдентификатор());
			$inv_num = cyr_str_encode($doc_inv->Номер);
		}
		
		//TTN
		if ($head['deliv_type']=='by_supplier'){
			/*
			$doc_transp = $v8->Документы->ЗаданиеНаПеревозку->СоздатьДокумент();			
			$doc_transp->Дата 									= $doc->Дата;
			$doc_transp->Статус									= $v8->Перечисления->СтатусыЗаданийНаПеревозку->КПогрузке;
			$doc_transp->Комментарий							= 'Web';
			$doc_transp->Ответственный							= ($head['user_ref'])? $user_ref:NULL;
			//$doc_transp->ТранспортноеСредство					= 
			if ($head['driver_ref']){
				$driver_id = $v8->NewObject('УникальныйИдентификатор',$head['driver_ref']);
				$driver_ref = $v8->Справочники->ФизическиеЛица->ПолучитьСсылку($driver_id);							
				$doc_transp->Водитель							= $driver_ref;
			}
			$doc_transp->Склад									= $doc->Склад;
			$doc_transp->Вес									= $head['total_weight'];
			$doc_transp->Объем									= $head['total_volume'];
			$doc_transp->КоличествоПунктов						= 1;
			//$doc_transp->Приоритет								= 
			$doc_transp->Операция								= $v8->Перечисления->ВидыДоставки->СоСклада;
			$doc_transp->Перевозчик								= ($head['carrier_ref'])? $carrier_ref->Партнер : $doc->Контрагент->Партнер;
			$doc_transp->Контрагент								= $doc->Контрагент;
			$doc_transp->БанковскийСчетПеревозчика			 	= ($head['carrier_ref'])? get_client_acc($v8,$carrier_ref) : get_client_acc($v8,$doc->Контрагент);
			$doc_transp->ВодительФИО							= cyr_str_decode($head['driver_name']). (($head['driver_cel_phone'])? ', тел.'.$head['driver_cel_phone']:'');
			$doc_transp->УдостоверениеНомер						= cyr_str_decode($head['driver_drive_perm']);
			$doc_transp->АвтомобильГосударственныйНомер			= cyr_str_decode($head['vh_plate']);
			$doc_transp->АвтомобильМарка						= '';
			$doc_transp->АвтомобильТип							= cyr_str_decode($head['vh_model']);
			$doc_transp->АвтомобильВместимостьВКубическихМетрах	= $head['vh_vol'];
			$doc_transp->АвтомобильГрузоподъемностьВТоннах		= $head['vh_load_weight_t'];
			$doc_transp->Прицеп									= cyr_str_decode($head['vh_trailer_model']);
			$doc_transp->ГосударственныйНомерПрицепа			= cyr_str_decode($head['vh_trailer_plate']);
			//$doc_transp->Записать($v8->РежимЗаписиДокумента->Проведение);
			*/
			
			$doc_ttn = $v8->Документы->ТранспортнаяНакладная->СоздатьДокумент();			
			$doc_ttn->Заполнить($doc->Ссылка);
			$doc_ttn->Дата 										= $doc->Дата;
			$doc_ttn->АвтомобильВместимостьВКубическихМетрах	= $head['vh_vol'];//$doc_transp->АвтомобильВместимостьВКубическихМетрах;
			$doc_ttn->АвтомобильГосударственныйНомер			= cyr_str_decode($head['vh_plate']);//$doc_transp->АвтомобильГосударственныйНомер;
			$doc_ttn->АвтомобильГрузоподъемностьВТоннах			= $head['vh_load_weight_t'];//$doc_transp->АвтомобильГрузоподъемностьВТоннах;
			$doc_ttn->АвтомобильМарка							= '';//$doc_transp->АвтомобильМарка;
			$doc_ttn->АвтомобильТип								= cyr_str_decode($head['vh_model']);//$doc_transp->АвтомобильТип;
			$doc_ttn->АдресДоставки								= $doc->АдресДоставки;
			$doc_ttn->АдресПогрузки								= cyr_str_decode($head['warehouse_address']);
			$doc_ttn->Водитель									= cyr_str_decode($head['driver_name']);//$doc_transp->ВодительФИО;
			$doc_ttn->БанковскийСчетЗаказчикаПеревозки			= get_client_acc($v8,$doc->Контрагент);
			$doc_ttn->БанковскийСчетПлательщика					= $doc_ttn->БанковскийСчетЗаказчикаПеревозки;			
			$doc_ttn->ВыводДанныхОТоварномСоставе				= $v8->Перечисления->ВариантыВыводаДанныхОТоварномСоставе->ТоварныйСостав;
			$doc_ttn->ГосударственныйНомерПрицепа				= cyr_str_decode($head['vh_trailer_plate']);//$doc_transp->ГосударственныйНомерПрицепа;
			$doc_ttn->Грузоотправитель							= $doc->Организация;
			$doc_ttn->Грузополучатель							= $doc->Контрагент;
			$doc_ttn->ЗаказчикПеревозки							= $doc->Контрагент;
			$doc_ttn->МассаБрутто								= $head['total_weight']*1000;
			//$doc_ttn->МассаНетто								= ;
			$doc_ttn->Организация 								= $doc->Организация;
			if ($head['user_ref']){
				$doc_ttn->Отпустил									= $user_ref->ФизическоеЛицо;			
				$doc_ttn->ОтпустилДолжность							= $otv1_post;
				$doc_ttn->ГлавныйБухгалтер							= $otv2_ref;
				$doc_ttn->Руководитель								= $otv1_ref;									
			}
			$doc_ttn->Плательщик								= $doc->Контрагент;
			
			$doc_ttn->Перевозчик								= $carrier_ref;
			$doc_ttn->БанковскийСчетПеревозчика					= get_client_acc($v8,$carrier_ref);
			
			$doc_ttn->Прицеп									= cyr_str_decode($head['vh_trailer_model']);//$doc_transp->Прицеп;
			$doc_ttn->УдостоверениеНомер						= cyr_str_decode($head['driver_drive_perm']);//$doc_transp->УдостоверениеНомер;
			$doc_ttn->Комментарий								= 'web '.cyr_str_decode($head['number']);
			//$doc_ttn->ЗаданиеНаПеревозку						= $doc_transp->Ссылка;
			
			$line = $doc_ttn->ДокументыОснования->Добавить();
			$line->ДокументОснование = $doc->Ссылка;
			$doc_ttn->Записать($v8->РежимЗаписиДокумента->Проведение);	//Запись Проведение
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
	
	function del_docs($v8,$ext_order_id,$ext_ship_id){
		if ($ext_ship_id){
		}
		$order_id = $v8->NewObject('УникальныйИдентификатор',$ext_order_id);
		$order_ref = $v8->Документы->ЗаказКлиента->ПолучитьСсылку($order_id);	
		if (!$order_ref->Пустая()){
			$ob = $order_ref->ПолучитьОбъект();
			$ob->Удалить();
		}
	}
	
?>