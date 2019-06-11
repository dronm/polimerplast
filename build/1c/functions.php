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
	
	/* Справочник ДополнительныеОтчетыИОбработки */
	define("CONST_1C_OBR_NAME",'Web CRM Functions');
	
	/* Справочник Номенклатура */
	define("CONST_1C_ITEM_NAME_PACK",'Упаковка');
	define("CONST_1C_ITEM_NAME_DELIV",'Доставка');
		
	/* Справочник Приоритеты */
	define("CONST_1C_PRIORITY",'Средний');
	
	/* Справочник СтруктураПредприятия */
	define("CONST_1C_DEP",'Дирекция');

	/* Дополнительное свойства счета */
	define('CONST_1C_ACC_ATTR','Расчетный');

	/* Дополнительное свойства реализации */
	define('CONST_1C_DOC_ATTR','НомерCRM');
	define('CONST_1C_DOC_ATTR_COMMENT','КомментарийCRM_e8570e240aaa4683bc57e596540f827d');
	define('CONST_1C_DOC_ATTR_DELIV_EXPENSES','ЗатратыНаДоставку_f15ed4e2a27c4a809aa1f1cb8e4db30b');
	
	/* Наименование для соглашения */
	define('CONST_1C_ACCORD_NAME','Типовое соглашение с клиентом');
	
	/* Группа финансового учета расчетов */
	define('CONST_1C_FIN_GROUP_CLIENT','Расчеты за товары, услуги и прочие активы (в рублях)');
	
	define('CONST_1C_ST_DDS_SALE','Выручка от продажи продукции,товаров, работ, услуг, сырья и иных оборотных активов');
	
	/* Дополнительное свойства кассы */
	define('CONST_1C_KASSA_ATTR','ОплатаПоТерминалу');

	
	
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
	
	function get_svoistvo($v8,$svoistvoType,$svoistvoVal){		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Ссылка  КАК  ref
		ИЗ Справочник.ЗначенияСвойствОбъектов
		ГДЕ Владелец=&ТипСвойства И Наименование=&ЗначениеСвойства';
		$q_obj->УстановитьПараметр('ТипСвойства',$svoistvoType);
		$q_obj->УстановитьПараметр('ЗначениеСвойства',$svoistvoVal);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			$ref = $sel->ref;
		}
		else{
			//создать новое значение
			$obj = $v8->Справочники->ЗначенияСвойствОбъектов->СоздатьЭлемент();
			$obj->Владелец = $svoistvoType;
			$obj->Наименование = $svoistvoVal;
			$obj->Записать();
			$ref = $obj->Ссылка;
		}
		return $ref;
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
	
	function get_person_attr_query(){
		return '
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.ФизическиеЛица.ДополнительныеРеквизиты КАК ДопВодУд
		ПО ДопВодУд.Ссылка=Спр.Ссылка И ДопВодУд.Свойство В (
					ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
					ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
					ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
					И Имя="ВодительскоеУдостоверение"		
		)	
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.ФизическиеЛица.ДополнительныеРеквизиты КАК ДопПеревоз
		ПО ДопПеревоз.Ссылка=Спр.Ссылка	И ДопПеревоз.Свойство В (
					ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
					ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
					ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
					И Имя="ЮрЛицоПеревозчик"		
		)	
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.ФизическиеЛица.ДополнительныеРеквизиты КАК ДопМарка
		ПО ДопМарка.Ссылка=Спр.Ссылка	И ДопМарка.Свойство В (
					ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
					ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
					ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
					И Имя="МаркаАвтомобиля"		
		)	
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.ФизическиеЛица.ДополнительныеРеквизиты КАК ДопПрицепНомер
		ПО ДопПрицепНомер.Ссылка=Спр.Ссылка	И ДопПрицепНомер.Свойство В (
					ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
					ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
					ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
					И Имя="ГосНомерПрицепа"		
		)	
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.ФизическиеЛица.ДополнительныеРеквизиты КАК ДопНомер
		ПО ДопНомер.Ссылка=Спр.Ссылка И ДопНомер.Свойство В (
					ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
					ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
					ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
					И Имя="Гос.НомерТранспортногоСредства_e1ed73600ef14dcbad02e4aa0f0a648d"		
		)';
	}
	
	function get_person_ref_sel($v8,$name,&$sel){
		$par = cyr_str_decode($name);
		$par = str_replace('"','""',$par);
		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Спр.Ссылка КАК ref,
		ДопВодУд.Значение КАК drive_perm,
		ДопПеревоз.Значение КАК carrier_ref,
		ДопМарка.Значение КАК model,
		ДопПрицепНомер.Значение КАК trailer_plate,
		ДопНомер.Значение КАК plate
		ИЗ Справочник.ФизическиеЛица КАК Спр
		'.get_person_attr_query().'		
		ГДЕ Спр.Наименование="'.$par.'"';

		$sel = $q_obj->Выполнить()->Выбрать();
		return ($sel->Следующий());
	}
	function getPersonRefCreate($params){
		if (!isset($params['name'])){
			throw new Exception("Не задано наименование");
		}
		
		$v8 = new COM(COM_OBJ_NAME);
		$sel = NULL;
		if (get_person_ref_sel($v8,$params['name'],$sel)){
			return sprintf('<ref>%s</ref>
				<drive_perm>%s</drive_perm>
				<carrier_ref>%s</carrier_ref>
				<model>%s</model>
				<trailer_plate>%s</trailer_plate>
				<plate>%s</plate>',
				$v8->String($sel->ref->УникальныйИдентификатор()),
				$v8->String($sel->drive_perm),
				//($sel->carrier_ref->Пустая())? '':$v8->String($sel->carrier_ref->УникальныйИдентификатор()),
				'',
				$v8->String($sel->model),
				$v8->String($sel->trailer_plate),
				$v8->String($sel->plate)
			);			
		}
		else{
			$name = cyr_str_decode($params['name']);
			$name_ar = explode(' ',$name);
			//throw new Exception('Name='.$name_ar[0].' '.((count($name_ar)>=2)? $name_ar[1]:'').' '.((count($name_ar)>=3)? $name_ar[2]:''));
			
			$person = $v8->Справочники->ФизическиеЛица->СоздатьЭлемент();
			$person->Наименование	= $name;
			$person->ФИО			= $name;
			$person->Фамилия		= $name_ar[0];
			$person->Имя			= (count($name_ar)>=2)? $name_ar[1]:'';
			$person->Отчество		= (count($name_ar)>=3)? $name_ar[2]:'';
			
			$svoistva = array(
				'plate'=>'Гос.НомерТранспортногоСредства_e1ed73600ef14dcbad02e4aa0f0a648d',
				'drive_perm'=>'ВодительскоеУдостоверение',
				'model'=>'МаркаАвтомобиля',
				'trailer_plate'=>'ГосНомерПрицепа',
				'carrier_ref'=>'ЮрЛицоПеревозчик'
			);
			foreach($svoistva as $id=>$qval){
				$v = cyr_str_decode($params[$id]);
				if($v){
					$q_obj = $v8->NewObject('Запрос');
					$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
					ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
					ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_ФизическиеЛица)
					И Имя="'.$qval.'"';
					$sel = $q_obj->Выполнить()->Выбрать();
					if ($sel->Следующий()){
						$extra_att = $person->ДополнительныеРеквизиты->Добавить();
						$extra_att->Свойство			= $sel->Ссылка;
						$extra_att->Значение			= get_svoistvo($v8,$sel->Ссылка,$v);
					}
				}
			}
			
			if($params['cel_phone']){
				$tel = $person->КонтактнаяИнформация->Добавить();
				$tel->Тип	= $v8->Перечисления->ТипыКонтактнойИнформации->Телефон;
				$tel->Вид	= $v8->Справочники->ВидыКонтактнойИнформации->ТелефонМобильныйФизическиеЛица;
				$tel->Представление	= $params['cel_phone']; 
			}
			
			$person->Записать();
			
			return sprintf('<ref>%s</ref>',
				$v8->String($person->Ссылка->УникальныйИдентификатор())
			);			
			
		}
	}
	
	function getPersonRefOnDescr(){
		$descr = $_REQUEST[PAR_NAME];
		if (!isset($descr)){
			throw new Exception("Не задано наименование");
		}
		$v8 = new COM(COM_OBJ_NAME);
		$sel = NULL;
		if (get_person_ref_sel($v8,$descr,$sel)){
			return sprintf('<ref>%s</ref>
				<drive_perm>%s</drive_perm>
				<carrier_ref>%s</carrier_ref>
				<model>%s</model>
				<trailer_plate>%s</trailer_plate>
				<plate>%s</plate>',
				$v8->String($sel->ref->УникальныйИдентификатор()),
				$v8->String($sel->drive_perm),
				$v8->String($sel->carrier_ref->УникальныйИдентификатор()),
				$v8->String($sel->model),
				$v8->String($sel->trailer_plate),
				$v8->String($sel->plate)
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
		ДопВодУд.Значение КАК drive_perm,
		ДопПеревоз.Значение КАК carrier_ref,
		ДопМарка.Значение КАК model,
		ДопПрицепНомер.Значение КАК trailer_plate,
		ДопНомер.Значение КАК plate
		
		ИЗ Справочник.ФизическиеЛица КАК Спр
		'.get_person_attr_query().'
		ГДЕ Спр.Ссылка=&ФЛСсылка';
		
		$driver_id = $v8->NewObject('УникальныйИдентификатор',$ref);
		$driver_ref = $v8->Справочники->ФизическиеЛица->ПолучитьСсылку($driver_id);
		$q_obj->УстановитьПараметр('ФЛСсылка',$driver_ref);
		
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
			return sprintf('<drive_perm>%s</drive_perm>
				<carrier_ref>%s</carrier_ref>
				<model>%s</model>
				<trailer_plate>%s</trailer_plate>
				<plate>%s</plate>',
				$v8->String($sel->drive_perm),
				$v8->String($sel->carrier_ref->УникальныйИдентификатор()),
				$v8->String($sel->model),
				$v8->String($sel->trailer_plate),
				$v8->String($sel->plate)
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
		//throw new Exception(sprintf('NewItem="%s"',$name));
		if (isset($item['fin_group'])){
			$fin_group = cyr_str_decode($item['fin_group']);
			$item_fin_grp_ref = $v8->Справочники->ГруппыФинансовогоУчетаНоменклатуры->НайтиПоНаименованию($fin_group,TRUE);			
			if ($item_fin_grp_ref->Пустая()){
				throw new Exception('Не найдена группа фин.контроля номенклатуры "'.$fin_group.'"!');
			}			
		}

		if (isset($item['analit_group'])){
			$analit_group = cyr_str_decode($item['analit_group']);
			$item_analit_grp_ref = $v8->Справочники->ГруппыАналитическогоУчетаНоменклатуры->НайтиПоНаименованию($analit_group,TRUE);
			if ($item_analit_grp_ref->Пустая()){
				throw new Exception('Не найдена группа аналит.учета номенклатуры "'.$analit_group.'"!');
			}						
		}
		
		$item_ref = $v8->Справочники->Номенклатура->НайтиПоНаименованию($name,TRUE);		
		if ($item_ref->Пустая()){
			//throw new Exception(sprintf('NewItem="%s"',$name));
			if (!isset($item['fin_group'])){
				throw new Exception('Не задана группа фин.контроля номенклатуры.');
			}
			if (!isset($item['analit_group'])){
				throw new Exception('Не задана группа аналит.учета номенклатуры.');
			}

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
		
			//новая номенклатура
			$new_item = $v8->Справочники->Номенклатура->СоздатьЭлемент();
			$new_item->ВариантОформленияПродажи			= $v8->Перечисления->ВариантыОформленияПродажи->РеализацияТоваровУслуг;
			$new_item->Наименование						= $name;
			$new_item->НаименованиеПолное				= $name;
			$new_item->ГруппаФинансовогоУчета			= $item_fin_grp_ref;
			$new_item->ГруппаАналитическогоУчета		= $item_analit_grp_ref;
			$new_item->Качество							= $v8->Перечисления->ГрадацииКачества->ОграниченноГоден;				
			$new_item->НаборУпаковок					= $v8->Справочники->НаборыУпаковок->ИндивидуальныйДляНоменклатуры;
			//$new_item->ГруппаДоступа					= $v8->Справочники->ГруппыДоступаНоменклатуры->;
			$new_item->ЕдиницаИзмерения					= $bmu_ref;
			$new_item->ИспользованиеХарактеристик		= $v8->Перечисления->ВариантыИспользованияХарактеристикНоменклатуры->НеИспользовать;				
			$new_item->ИспользоватьУпаковки				= TRUE;
			$new_item->СтавкаНДС						= $v8->Перечисления->СтавкиНДС->НДС20;				
			$new_item->ТипНоменклатуры					= $v8->Перечисления->ТипыНоменклатуры->Товар;
			$new_item->ВидНоменклатуры					= $item_kind_ref;
			$new_item->Родитель							= $group2_ref;
			$new_item->Записать();			
			
			$item_ref = $new_item->Ссылка;
		}
		/*
		else{
			if (isset($item['fin_group']) && isset($item['analit_group'])){
				if (
				$item_fin_grp_ref<>$item_ref->ГруппаФинансовогоУчета
				||$item_analit_grp_ref<>$item_ref->ГруппаАналитическогоУчета
				){
					//throw new Exception('Rewrite '.$item_ref->Код);
					$old_item = $item_ref->ПолучитьОбъект();
					$old_item->ГруппаФинансовогоУчета = $item_fin_grp_ref;
					$old_item->ГруппаАналитическогоУчета = $item_analit_grp_ref;
					$old_item->Записать();
				}
			}
		}
		*/
		return $item_ref;
	}

	function get_currency($v8){
		return $v8->Справочники->Валюты->НайтиПоКоду('643');
	}
	
	function get_nds($v8,$calcNDS){
		return $calcNDS? $v8->Перечисления->СтавкиНДС->НДС20 : $v8->Перечисления->СтавкиНДС->БезНДС;
	}
	
	function get_nds_percent($calcNDS){
		return $calcNDS? 20 : 0;
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

	function get_doc_comment($head,$orderNum=NULL){
		$COMMENT = 'web';
		if (isset($head['number'])){
			$COMMENT.= ($COMMENT=='')? '':' ';
			$COMMENT.= cyr_str_decode($head['number']);
			$COMMENT.= isset($orderNum)? $orderNum:'';
		}
		
		if (isset($head['client_comment'])){
			$COMMENT.= ($COMMENT=='')? '':' ';
			$COMMENT.= cyr_str_decode($head['client_comment']);
		}
		if (isset($head['sales_manager_comment'])){
			$COMMENT.= ($COMMENT=='')? '':' ';
			$COMMENT.= cyr_str_decode($head['sales_manager_comment']);
		}
		return $COMMENT;
	}
	
	function get_client_sogl($v8,$client_ref,$firm_ref,$attrs){
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка ИЗ Справочник.СоглашенияСКлиентами ГДЕ Наименование="Соглашение по умолчанию"';
		//ГДЕ Статус = ЗНАЧЕНИЕ(Перечисление.СтатусыСоглашенийСКлиентами.Действует)';
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
	
	function create_client_dog($v8,$clientRef,$firmRef,$attrs,$accRef){
		//Новый договор
		$dog = $v8->Справочники->ДоговорыКонтрагентов->СоздатьЭлемент();		
		$dog->Наименование							= 'Основной договор';
		$dog->ВалютаВзаиморасчетов					= get_currency($v8);		
		$dog->Организация							= $firmRef;
		$dog->Контрагент							= $clientRef;		
		$dog->Партнер								= $clientRef->Партнер;
		$dog->ПорядокОплаты							= $v8->Перечисления->ПорядокОплатыПоСоглашениям->РасчетыВРубляхОплатаВРублях;
		$dog->ПорядокРасчетов						= $v8->Перечисления->ПорядокРасчетов->ПоДоговорамКонтрагентов;//ПоНакладным
		$dog->Статус								= $v8->Перечисления->СтатусыДоговоровКонтрагентов->Действует;
		$dog->ХозяйственнаяОперация					= $v8->Перечисления->ХозяйственныеОперации->РеализацияКлиенту;
		$dog->ТипДоговора							= $v8->Перечисления->ТипыДоговоров->СПокупателем;		
		$dog->Комментарий							= 'web';
		$dog->БанковскийСчет						= $accRef? $accRef : get_org_acc($v8,$firmRef);
		$dog->СтавкаНДС								= $v8->Перечисления->СтавкиНДС->НДС20;
		$dog->СтатьяДвиженияДенежныхСредств			= $v8->Справочники->СтатьиДвиженияДенежныхСредств->НайтиПоНаименованию(CONST_1C_ST_DDS_SALE,TRUE);
		$dog->ГруппаФинансовогоУчета				= $v8->Справочники->ГруппыФинансовогоУчетаРасчетов->НайтиПоНаименованию(CONST_1C_FIN_GROUP_CLIENT,TRUE);
		$dog->НалогообложениеНДСОпределяетсяВДокументе = TRUE;
		//$dog->НалогообложениеНДС					= $v8->Перечисления->ТипыНалогообложенияНДС->;
		
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
	
	function get_client_dog($v8,$client_ref,$firm_ref,$attrs,$accRef){			
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка ИЗ Справочник.ДоговорыКонтрагентов
		ГДЕ Контрагент=&client И Организация=&firm И ТипДоговора=ЗНАЧЕНИЕ(Перечисление.ТипыДоговоров.СПокупателем) И Статус<>ЗНАЧЕНИЕ(Перечисление.СтатусыДоговоровКонтрагентов.Закрыт) И НЕ ПометкаУдаления';
		$q_obj->УстановитьПараметр('client',$client_ref);
		$q_obj->УстановитьПараметр('firm',$firm_ref);		
		$sel = $q_obj->Выполнить()->Выбрать();
		$dog_ref = NULL;
		if ($sel->Следующий()){
			return $sel->Ссылка;
		}
		
		return create_client_dog($v8,$client_ref,$firm_ref,$attrs,$accRef);
	}
	
	function get_item_pack($v8){
		return $v8->Справочники->Номенклатура->НайтиПоНаименованию(CONST_1C_ITEM_NAME_PACK,TRUE);
	}
	function get_item_deliv($v8){
		return $v8->Справочники->Номенклатура->НайтиПоНаименованию(CONST_1C_ITEM_NAME_DELIV,TRUE);
	}
	
	function get_kassa($v8,$firm_ref,$terminal=FALSE){		
		$q_obj = $v8->NewObject('Запрос');
		//касса терминал
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1
		Кассы.Ссылка
		ИЗ Справочник.Кассы КАК Кассы
		ЛЕВОЕ СОЕДИНЕНИЕ Справочник.Кассы.ДополнительныеРеквизиты КАК СвОплТерминал
		По СвОплТерминал.Ссылка=Кассы.Ссылка И СвОплТерминал.Свойство в (
			ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
			ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
			ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_Кассы)
			И Имя="'.CONST_1C_KASSA_ATTR.'"		
		)		
		ГДЕ Владелец=&ФирмаСсылка И НЕ ПометкаУдаления И ЕСТЬNULL(СвОплТерминал.Значение,Ложь)='.(!$terminal? 'Ложь':'Истина');			
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
			И Имя="'.CONST_1C_ACC_ATTR.'"		
		)
		ГДЕ Счет.Владелец = &firm И Доп.Значение=Истина';		
		$q_obj->УстановитьПараметр('firm',$firmRef);
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
				$acc_ref = $sel->Ссылка;
		}		
		return $acc_ref;
	}

	function get_client_acc($v8,$clientRef,$acc=NULL){
		$acc_ref = NULL;
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Счет.Ссылка ИЗ Справочник.БанковскиеСчетаКонтрагентов КАК Счет
		ГДЕ Счет.Владелец = &client И '.(is_null($acc)? 'НЕ Счет.Закрыт И НЕ Счет.ПометкаУдаления':'Счет.НомерСчета=&acc');		
		$q_obj->УстановитьПараметр('client',$clientRef);
		if(!is_null($acc)){
			$q_obj->УстановитьПараметр('acc',$acc);
		}	
		$sel = $q_obj->Выполнить()->Выбрать();
		if ($sel->Следующий()){
				$acc_ref = $sel->Ссылка;
		}		
		return $acc_ref;
	}

	function get_partner($v8,$attrs,$name,$nameFull,$managerRef=NULL){
		$partner_ref = $v8->Справочники->Партнеры->НайтиПоНаименованию($name,TRUE);
		if ($partner_ref->Пустая()){
			$obj = $v8->Справочники->Партнеры->СоздатьЭлемент();
			$obj->Клиент				= TRUE;
			$obj->Комментарий			= 'Web';
			$obj->НаименованиеПолное	= $nameFull;
			$obj->Наименование			= $name;
			$obj->ЮрФизЛицо				= (strlen($attrs['inn'])==10)? $v8->Перечисления->КомпанияЧастноеЛицо->Компания:$v8->Перечисления->КомпанияЧастноеЛицо->ЧастноеЛицо;
			$obj->ДатаРегистрации		= date('YmdHis');
			$obj->ОсновнойМенеджер		= $managerRef;
			$obj->Записать();
			
			$partner_ref = $obj->Ссылка;
		}
		return $partner_ref;
	}
	
	function set_1c_addr($v8,&$kiLine,$addrType,$addr){
		$kiLine->Тип				= $v8->Перечисления->ТипыКонтактнойИнформации->Адрес;
		$kiLine->Вид		 		= $addrType;
		$kiLine->Представление		= $addr;
		$kiLine->ВидДляСписка		= $kiLine->Вид;
		$xdto = $v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияXDTOПоПредставлению($addr,$addrType);
		$kiLine->ЗначенияПолей = $v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияXDTOВXML($xdto);
		$kiLine->Значение = $v8->УправлениеКонтактнойИнформациейСлужебный->СтруктураВСтрокуJSON($v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияВСтруктуруJSON($kiLine->ЗначенияПолей, $addrType));		
	}
	
	function set_1c_tel($v8,&$kiLine,$clientTel,$isClient){
		$kind = $isClient? $v8->Справочники->ВидыКонтактнойИнформации->ТелефонКонтрагента:$v8->Справочники->ВидыКонтактнойИнформации->ТелефонПартнера;
		
		$kiLine->Тип				= $v8->Перечисления->ТипыКонтактнойИнформации->Телефон;
		$kiLine->Вид		 		= $kind;
		$kiLine->Представление		= $clientTel;
		$kiLine->ВидДляСписка		= $kiLine->Вид;				
		$tel = str_replace('-','',$clientTel);
		$tel = str_replace('+','',$tel);
		$kiLine->НомерТелефона = $tel;
		$kiLine->НомерТелефонаБезКодов = $tel;
		$xdto = $v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияXDTOПоПредставлению($clientTel,$kind);
		$kiLine->ЗначенияПолей = $v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияXDTOВXML($xdto);
		$kiLine->Значение = $v8->УправлениеКонтактнойИнформациейСлужебный->СтруктураВСтрокуJSON($v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияВСтруктуруJSON($kiLine->ЗначенияПолей,$kind));
		
	}

	function set_1c_email($v8,&$kiLine,$clientEmail,$isClient){
		$at_p = strpos($clientEmail,'@');
		if($at_p!==FALSE){
			$dom = substr($clientEmail,$at_p+1);
		}
		else{
				$dom = $clientEmail;
		}
		$kind = $isClient? $v8->Справочники->ВидыКонтактнойИнформации->EmailКонтрагента:$v8->Справочники->ВидыКонтактнойИнформации->EmailПартнера;
		$kiLine->АдресЭП			= $clientEmail;
		$kiLine->ДоменноеИмяСервера	= $dom;
		$kiLine->Тип				= $v8->Перечисления->ТипыКонтактнойИнформации->АдресЭлектроннойПочты;
		$kiLine->Вид		 		= $kind;
		$kiLine->Представление		= $clientEmail;
		$kiLine->ВидДляСписка		= $kiLine->Вид;				
		$xdto = $v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияXDTOПоПредставлению($clientEmail,$kind);
		$kiLine->ЗначенияПолей = $v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияXDTOВXML($xdto);
		$kiLine->Значение = $v8->УправлениеКонтактнойИнформациейСлужебный->СтруктураВСтрокуJSON($v8->УправлениеКонтактнойИнформациейСлужебный->КонтактнаяИнформацияВСтруктуруJSON($kiLine->ЗначенияПолей, $kind));		
	}
	
	function get_client_contract_list($v8,$firmExtId,$clientExtId){
		$firm_id = $v8->NewObject('УникальныйИдентификатор',$firmExtId);
		$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);			
		
		$client_id = $v8->NewObject('УникальныйИдентификатор',$clientExtId);
		$client_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($client_id);			

		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст ='ВЫБРАТЬ Ссылка,Наименование ИЗ Справочник.ДоговорыКонтрагентов
		ГДЕ Контрагент=&client И Организация=&firm И ТипДоговора=ЗНАЧЕНИЕ(Перечисление.ТипыДоговоров.СПокупателем) И Статус<>ЗНАЧЕНИЕ(Перечисление.СтатусыДоговоровКонтрагентов.Закрыт) И НЕ ПометкаУдаления';
		$q_obj->УстановитьПараметр('client',$client_ref);
		$q_obj->УстановитьПараметр('firm',$firm_ref);		
		$sel = $q_obj->Выполнить()->Выбрать();
		$xml = '';
		while ($sel->Следующий()){
			$xml.= sprintf(
				'<contract>
				<ref>%s</ref>
				<name>%s</name>
				</contract>',		
				$v8->String($sel->Ссылка->УникальныйИдентификатор()),
				cyr_str_encode($sel->Наименование)
			);			
		}
		//throw new Exception($xml);
		return $xml;
	}
	
	function client($v8,$attrs){
		$client_ref = NULL;

		if(isset($attrs['ext_id'])){
			$client_id = $v8->NewObject('УникальныйИдентификатор',$attrs['ext_id']);
			$client_ref = $v8->Справочники->Контрагенты->ПолучитьСсылку($client_id);			
		}
		else if(isset($attrs['inn'])){
			$client_ref = get_client_ref_on_inn($v8,$attrs['inn']);
		}
		else{
			throw new Exception('В 1с не отправлен ни ИНН ни ссылка!');
		}

		$q = $v8->NewObject('Запрос');
		$q->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
		ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
		ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Справочник_Контрагенты)
		И Имя="ТипКлиента"';
		$sel = $q->Выполнить()->Выбрать();
		if (!$sel->Следующий()){
			throw new Exception('Не найдено доп.свойство контрагента ТипКлиента!');
		}
		$sv_cl_type = $sel->Ссылка;
		
		$q = $v8->NewObject('Запрос');
		$q->Текст ='ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
		ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
		ГДЕ Имя="ОГРН"';
		$sel = $q->Выполнить()->Выбрать();
		if (!$sel->Следующий()){
			throw new Exception('Не найдено доп.свойство контрагента ОГРН!');
		}
		$sv_cl_ogrn = $sel->Ссылка;
//throw new Exception('UserId='.$attrs['user_ext_id']);
		if (is_null($client_ref)){
			//Новый клиент
		
			//Менеджер
			$manager_ref = NULL;
			//throw new Exception('UserId='.$attrs['user_ext_id']);
			if (isset($attrs['user_ext_id'])){				
				$user_id = $v8->NewObject('УникальныйИдентификатор',$attrs['user_ext_id']);			
				$manager_ref = $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);							
			}
			else{
				$manager_ref = $v8->Справочники->Пользователи->ПустаяСсылка();
			}
		
			$obj = $v8->Справочники->Контрагенты->СоздатьЭлемент();
			$obj->Наименование					= stripslashes(cyr_str_decode($attrs['name']));
			$obj->НаименованиеПолное			= stripslashes(cyr_str_decode($attrs['name_full']));
			$obj->ЮрФизЛицо						= (strlen($attrs['inn'])==10)? $v8->Перечисления->ЮрФизЛицо->ЮрЛицо:$v8->Перечисления->ЮрФизЛицо->ФизЛицо;
			$obj->ЮридическоеФизическоеЛицо		= (strlen($attrs['inn'])==10)? $v8->Перечисления->ЮридическоеФизическоеЛицо->ЮридическоеЛицо:$v8->Перечисления->ЮридическоеФизическоеЛицо->ФизическоеЛицо;
			$obj->ИНН							= $attrs['inn'];
			$obj->КодПоОКПО						= $attrs['okpo'];
			$obj->КПП							= $attrs['kpp'];
			$obj->СтранаРегистрации				= $v8->Справочники->СтраныМира->Россия;
			$obj->Партнер						= get_partner($v8,$attrs,$obj->Наименование,$obj->НаименованиеПолное,$manager_ref);
			
			if(isset($attrs['addr_reg'])){
				$addr = $obj->КонтактнаяИнформация->Добавить();
				$addr_str = stripslashes(cyr_str_decode($attrs['addr_reg']));
				set_1c_addr($v8,$addr,$v8->Справочники->ВидыКонтактнойИнформации->ЮрАдресКонтрагента,$addr_str);
			}
			$addr_mail = NULL;
			if(isset($attrs['addr_mail_same_as_reg']) && $attrs['addr_mail_same_as_reg']){
					$addr_mail = stripslashes(cyr_str_decode($attrs['addr_reg']));
			}
			else if(isset($attrs['addr_mail'])){
					$addr_mail = stripslashes(cyr_str_decode($attrs['addr_mail']));
			}
			if(isset($addr_mail)){
				$addr = $obj->КонтактнаяИнформация->Добавить();
				set_1c_addr($v8,$addr,$v8->Справочники->ВидыКонтактнойИнформации->ФактАдресКонтрагента,$addr_mail);
			}
			
			if( isset($attrs['telephones']) ){
				$addr = $obj->КонтактнаяИнформация->Добавить();
				set_1c_tel($v8,$addr,$attrs['telephones'],TRUE);
				$obj->ДополнительнаяИнформация = $attrs['telephones'];
			}

			if( isset($attrs['email']) ){
				$email = stripslashes(cyr_str_decode($attrs['email']));
				$addr = $obj->КонтактнаяИнформация->Добавить();
				set_1c_email($v8,$addr,$email,TRUE);
			}
			
			$obj->Записать();
			$client_ref = $obj->Ссылка;
		
			$obj_p = NULL;
			if(isset($attrs['client_activity'])){
				$client_activity = stripslashes(cyr_str_decode($attrs['client_activity']));
			}
			if(isset($attrs['client_activity'])){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$p_attr = $obj_p->ДополнительныеРеквизиты->Добавить();
				$p_attr->Свойство = $sv_cl_type;
				$p_attr->Значение = get_svoistvo($v8,$sv_cl_type,$client_activity);
			}
			if( isset($attrs['telephones']) ){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}				
				$addr = $obj_p->КонтактнаяИнформация->Добавить();
				set_1c_tel($v8,$addr,$attrs['telephones'],FALSE);
				$obj_p->ДополнительнаяИнформация = $attrs['telephones'];
			}

			if( isset($attrs['email']) ){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}				
				$email = stripslashes(cyr_str_decode($attrs['email']));
				$addr = $obj_p->КонтактнаяИнформация->Добавить();
				set_1c_email($v8,$addr,$email,FALSE);
			}

			//ogrn			
			if(isset($attrs['ogrn'])){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$p_attr = $obj_p->ДополнительныеРеквизиты->Добавить();
				$p_attr->Свойство = $sv_cl_ogrn;
				$p_attr->Значение = $attrs['ogrn'];				
			}
			
			if(!is_null($obj_p)){
					$obj_p->Записать();
			}
		}
		else if(isset($attrs['inn']) && $client_ref->ИНН<>$attrs['inn']){
			throw new Exception('У данного клиента в 1с другой ИНН:'.$client_ref->ИНН);
		}
		else{
			//что-то изменилось

			$obj = NULL;
			if(isset($attrs['name'])){
				$name = stripslashes(cyr_str_decode($attrs['name']));
				if($client_ref->Наименование<>$name){
					if(is_null($obj)){
						$obj = $client_ref->ПолучитьОбъект();
					}
					$obj->Наименование=$name;
					
					$obj_part = $obj->Партнер->ПолучитьОбъект();
					$obj_part->Наименование=$name;
					$obj_part->Записать();
				}
			}
			if(isset($attrs['name_full'])){
				$name_full = stripslashes(cyr_str_decode($attrs['name_full']));
				if($client_ref->НаименованиеПолное<>$name_full){
					if(is_null($obj)){
						$obj = $client_ref->ПолучитьОбъект();
					}
					$obj->НаименованиеПолное=$name_full;
					
					$obj_part = $obj->Партнер->ПолучитьОбъект();
					$obj_part->НаименованиеПолное=$name_full;
					$obj_part->Записать();
					
				}
			}
			if($client_ref->КодПоОКПО<>$attrs['okpo']){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$obj->КодПоОКПО=$attrs['okpo'];
			}
			if($client_ref->КПП<>$attrs['kpp']){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$obj->КПП=$attrs['kpp'];
			}
			
			//Найдем адреса
			$addr_reg = (isset($attrs['addr_reg']))? stripslashes(cyr_str_decode($attrs['addr_reg'])):NULL;
			$mail_addr_1c = NULL;
			$reg_addr_1c = NULL;
			$mail_addr_1c_line = NULL;
			$reg_addr_1c_line = NULL;			
			$tel_1c_line = NULL;
			$tel_1c = NULL;
			$email_1c_line = NULL;
			$email_1c = NULL;						
			for($i=0;$i<$client_ref->КонтактнаяИнформация->Количество();$i++){				
				$addr = $client_ref->КонтактнаяИнформация->Получить($i);								
				if($v8->String($addr->Тип) == $v8->String($v8->Перечисления->ТипыКонтактнойИнформации->Адрес)){
					if($v8->String($addr->Вид) == $v8->String($v8->Справочники->ВидыКонтактнойИнформации->ФактАдресКонтрагента)){
						$mail_addr_1c = trim($addr->Представление);
						$mail_addr_1c_line = $i;
					}
					else if($v8->String($addr->Вид) == $v8->String($v8->Справочники->ВидыКонтактнойИнформации->ЮрАдресКонтрагента)){
						$reg_addr_1c = trim($addr->Представление);
						$reg_addr_1c_line = $i;
					}					
				}
				else if(
				$v8->String($addr->Тип) == $v8->String($v8->Перечисления->ТипыКонтактнойИнформации->АдресЭлектроннойПочты)
				&& $v8->String($addr->Вид) == $v8->String($v8->Справочники->ВидыКонтактнойИнформации->EmailКонтрагента)
				){
						$email_1c = trim($addr->Представление);
						$email_1c_line = $i;
				}
				else if($v8->String($addr->Тип) == $v8->String($v8->Перечисления->ТипыКонтактнойИнформации->Телефон)){
						$tel_1c = trim($addr->Представление);
						$tel_1c_line = $i;					
				}
			}				
			$obj_p = NULL;
			//Данные партнера
			$p_tel_1c_line = NULL;
			$p_tel_1c = NULL;
			$p_email_1c_line = NULL;
			$p_email_1c = NULL;			
			for($i=0;$i<$client_ref->Партнер->КонтактнаяИнформация->Количество();$i++){				
				$addr = $client_ref->Партнер->КонтактнаяИнформация->Получить($i);								
				if(
				$v8->String($addr->Тип) == $v8->String($v8->Перечисления->ТипыКонтактнойИнформации->АдресЭлектроннойПочты)
				&& $v8->String($addr->Вид) == $v8->String($v8->Справочники->ВидыКонтактнойИнформации->EmailКонтрагента)
				){
						$p_email_1c = trim($addr->Представление);
						$p_email_1c_line = $i;
				}
				else if($v8->String($addr->Тип) == $v8->String($v8->Перечисления->ТипыКонтактнойИнформации->Телефон)){
						$p_tel_1c = trim($addr->Представление);
						$p_tel_1c_line = $i;					
				}
			}
			
			//throw new Exception('ext_id='.$attrs['ext_id']);
			//throw new Exception('ИНН='.$client_ref->ИНН);
			//throw new Exception('!!!telephonesForSetting='.$attrs['telephones'].' Str='.$tel_1c_line.' Tel1c='.$tel_1c);
			if(isset($addr_reg)&& (!is_null($reg_addr_1c)&&$reg_addr_1c!=$addr_reg) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$addr = $obj->КонтактнаяИнформация->Получить($reg_addr_1c_line);
				set_1c_addr($v8,$addr,$v8->Справочники->ВидыКонтактнойИнформации->ЮрАдресКонтрагента,$addr_reg);
			}
			else if(isset($addr_reg)&& is_null($reg_addr_1c) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$addr = $obj->КонтактнаяИнформация->Добавить();
				set_1c_addr($v8,$addr,$v8->Справочники->ВидыКонтактнойИнформации->ЮрАдресКонтрагента,$addr_reg);
			}
			
			$addr_mail = NULL;
			if(isset($addr_reg) && isset($attrs['addr_mail_same_as_reg']) && $attrs['addr_mail_same_as_reg']===TRUE){
					$addr_mail = $addr_reg;
			}
			else if(isset($attrs['addr_mail'])){
					$addr_mail = stripslashes(cyr_str_decode($attrs['addr_mail']));
			}
						
			if(isset($addr_mail)&& (!is_null($mail_addr_1c)&&$mail_addr_1c!=$addr_mail) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$addr = $obj->КонтактнаяИнформация->Получить($mail_addr_1c_line);
				set_1c_addr($v8,$addr,$v8->Справочники->ВидыКонтактнойИнформации->ФактАдресКонтрагента,$addr_mail);
			}
			else if(isset($addr_mail)&& is_null($mail_addr_1c) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$addr = $obj->КонтактнаяИнформация->Добавить();
				set_1c_addr($v8,$addr,$v8->Справочники->ВидыКонтактнойИнформации->ФактАдресКонтрагента,$addr_mail);
			}
			
			//Telephones			
			if( isset($attrs['telephones']) && (!is_null($p_tel_1c)&&$p_tel_1c!=$attrs['telephones']) ){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$addr = $obj_p->КонтактнаяИнформация->Получить($p_tel_1c_line);
				set_1c_tel($v8,$addr,$attrs['telephones'],FALSE);
			}
			else if( isset($attrs['telephones']) && is_null($p_tel_1c) ){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$addr = $obj_p->КонтактнаяИнформация->Добавить();
				set_1c_tel($v8,$addr,$attrs['telephones'],FALSE);
			}

			//Email
			if( isset($attrs['email']) && (!is_null($p_email_1c)&&$p_email_1c!=$attrs['email']) ){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$email = stripslashes(cyr_str_decode($attrs['email']));
				$addr = $obj_p->КонтактнаяИнформация->Получить($p_email_1c_line);
				set_1c_email($v8,$addr,$email,FALSE);
			}
			else if( isset($attrs['email']) && is_null($p_email_1c) ){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$email = stripslashes(cyr_str_decode($attrs['email']));
				$addr = $obj_p->КонтактнаяИнформация->Добавить();
				set_1c_email($v8,$addr,$email,FALSE);
			}

			//Telephones			
			if( isset($attrs['telephones']) && (!is_null($tel_1c)&&$tel_1c!=$attrs['telephones']) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$addr = $obj->КонтактнаяИнформация->Получить($tel_1c_line);
				set_1c_tel($v8,$addr,$attrs['telephones'],TRUE);
			}
			else if( isset($attrs['telephones']) && is_null($tel_1c) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$addr = $obj->КонтактнаяИнформация->Добавить();
				set_1c_tel($v8,$addr,$attrs['telephones'],TRUE);
			}
//throw new Exception('!!!ИНН='.$client_ref->ИНН);
			//Email
			if( isset($attrs['email']) && (!is_null($email_1c)&&$email_1c!=$attrs['email']) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$email = stripslashes(cyr_str_decode($attrs['email']));
				$addr = $obj->КонтактнаяИнформация->Получить($email_1c_line);
				set_1c_email($v8,$addr,$email,TRUE);
			}
			else if( isset($attrs['email']) && is_null($email_1c) ){
				if(is_null($obj)){
					$obj = $client_ref->ПолучитьОбъект();
				}
				$email = stripslashes(cyr_str_decode($attrs['email']));
				$addr = $obj->КонтактнаяИнформация->Добавить();
				set_1c_email($v8,$addr,$email,TRUE);
			}
			
			
			$cl_type = NULL;
			$cl_type_line = 0;
			$cl_ogrn = NULL;
			$cl_ogrn_line = 0;
			if( (isset($attrs['client_activity'])||isset($attrs['ogrn'])) && !$client_ref->Партнер->Пустая() ){
				for($i=0;$i<$client_ref->Партнер->ДополнительныеРеквизиты->Количество();$i++){
					$extra_att = $client_ref->Партнер->ДополнительныеРеквизиты->Получить($i);
					if($v8->String($extra_att->Свойство) == $v8->String($sv_cl_type)){
						$cl_type = $v8->String($extra_att->Значение);
						$cl_type_line = $i;
					}
					else if($v8->String($extra_att->Свойство) == $v8->String($sv_cl_ogrn)){
						$cl_ogrn = $v8->String($extra_att->Значение);
						$cl_ogrn_line = $i;
					}					
				}
			}
			
			if(isset($attrs['client_activity'])){
				$client_activity = stripslashes(cyr_str_decode($attrs['client_activity']));
			}
			//throw new Exception('client_activity='.$client_activity);
			if(isset($attrs['client_activity']) && isset($cl_type) && $cl_type!=$attrs['client_activity']){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$p_attr = $obj_p->ДополнительныеРеквизиты->Получить($cl_type_line);
				$p_attr->Значение = get_svoistvo($v8,$sv_cl_type,$client_activity);
			}
			else if(isset($attrs['client_activity']) && !isset($cl_type)){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$p_attr = $obj_p->ДополнительныеРеквизиты->Добавить();
				$p_attr->Свойство = $sv_cl_type;
				$p_attr->Значение = get_svoistvo($v8,$sv_cl_type,$client_activity);
			}

			//ogrn
			
			if(isset($attrs['ogrn']) && isset($cl_ogrn) && $cl_ogrn!=$attrs['ogrn']){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$p_attr = $obj_p->ДополнительныеРеквизиты->Получить($cl_ogrn_line);
				$p_attr->Значение = $attrs['ogrn'];				
			}
			else if(isset($attrs['ogrn']) && !isset($cl_ogrn)){
				if(is_null($obj_p)){
					$obj_p = $client_ref->Партнер->ПолучитьОбъект();
				}
				$p_attr = $obj_p->ДополнительныеРеквизиты->Добавить();
				$p_attr->Свойство = $sv_cl_ogrn;
				$p_attr->Значение = $attrs['ogrn'];				
			}
			
			if(!is_null($obj_p)){
					$obj_p->Записать();
			}

			//throw new Exception('!!!');
			if(!is_null($obj)){
					$obj->Записать();
			}
		}
		
		$acc = get_client_acc($v8,$client_ref,$attrs['acc']);
		//throw new Exception(var_export($attrs,TRUE));
		
		if (is_null($acc) && isset($attrs['bank_code']) && isset($attrs['acc'])){
			$acc = $v8->Справочники->БанковскиеСчетаКонтрагентов->СоздатьЭлемент();
			$acc->Владелец				= $client_ref;
			$acc->Наименование			= 'Основной счет';
			$acc->НомерСчета			= $attrs['acc'];
			$acc->ВалютаДенежныхСредств	= get_currency($v8);
			$bank_ref = $v8->Справочники->КлассификаторБанков->НайтиПоКоду($attrs['bank_code']);
			if ($bank_ref->Пустая()){
				//нет такого банка
				throw new Exception('БИК банка '.$attrs['bank_code'].' в 1с не найден!');
			}
			$acc->Банк = $bank_ref;		
			$acc->Записать();			
		}
		
		return $v8->String($client_ref->УникальныйИдентификатор());
	}
	
	function order($v8,$head,$items){		
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
			//throw new Exception('OldFirmId='.$doc_firm_id.' NewFirmId='.$head['firm_ref']);
			if ($v8->String($order_ref->Организация->УникальныйИдентификатор())==$head['firm_ref']){
				//throw new Exception('Организация не изменилась - Тот же счет');
				//Организация не изменилась - Тот же счет
				$doc = $order_ref->ПолучитьОбъект();
				if ($doc->Проведен){
					$doc->Проведен = FALSE;
				}
				if ($doc->Товары->Количество()>0){
					$doc->Товары->Очистить();
				}
			}
			else{
				//throw new Exception('Другая организация - новый счет!');
				//Другая организация - новый счет!
				$doc = $v8->Документы->ЗаказКлиента->СоздатьДокумент();	
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
		$doc->Организация						= $firm_ref;
		/*
		if (isset($head['ext_order_id'])){
			$doc->УстановитьНовыйНомер($doc->Организация->Префикс);
		}
		*/
		$doc->Соглашение						= get_client_sogl($v8,$client_ref,$firm_ref,$attrs);
		$doc->Контрагент						= $client_ref;	
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
		$doc->Комментарий						= get_doc_comment($head);
		
		if (isset($head['client_contract_ext_id'])){		
			$client_contract_id = $v8->NewObject('УникальныйИдентификатор',$head['client_contract_ext_id']);
			$doc->Договор = $v8->Справочники->ДоговорыКонтрагентов->ПолучитьСсылку($client_contract_id);
			if($doc->Договор->Пустая()){
				$doc->Договор = get_client_dog($v8,$client_ref,$firm_ref,$attrs,$acc_ref);
			}
		}
		else{
			$doc->Договор = get_client_dog($v8,$client_ref,$firm_ref,$attrs,$acc_ref);
		}
		
		$doc->ПорядокРасчетов					= $doc->Договор->ПорядокРасчетов; //$v8->Перечисления->ПорядокРасчетов->ПоЗаказамНакладным;
		$doc->СпособДоставки					= $v8->Перечисления->СпособыДоставки->Самовывоз;		
		/*
												($deliv_total)?
														$v8->Перечисления->СпособыДоставки->ДоКлиента : 
													$v8->Перечисления->СпособыДоставки->Самовывоз;
		*/
		$doc->БанковскийСчет					= $acc_ref;
		
		//$doc->ПеревозчикПартнер
		$doc->ПорядокОплаты						= $v8->Перечисления->ПорядокОплатыПоСоглашениям->РасчетыВРубляхОплатаВРублях;
		$doc->Приоритет							= $v8->Справочники->Приоритеты->НайтиПоНаименованию(CONST_1C_PRIORITY,TRUE);
		$doc->НеОтгружатьЧастями				= TRUE;
		$doc->ДатаОтгрузки						= ($head['delivery_plan_date'])? get_1c_date($head['delivery_plan_date']) : $head['date'];
		
		if (isset($head['gruzopoluchatel_ref'])){
			$gruzopoluchatel_id = $v8->NewObject('УникальныйИдентификатор',$head['gruzopoluchatel_ref']);
			$doc->Грузополучатель = $v8->Справочники->Контрагенты->ПолучитьСсылку($gruzopoluchatel_id);			
		}
		
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
		
		$st_dds_ref	= $v8->Справочники->СтатьиДвиженияДенежныхСредств->НайтиПоНаименованию(CONST_1C_ST_DDS_SALE,TRUE);
		try{
			$v8->BeginTransaction();
			foreach($head as $firm_ar){			
				$sum = floatval($firm_ar['total']);
				if (!$sum) continue;
				
				$firm_ref = $firm_ar['firm_ref'];
				$firm_id = $v8->NewObject('УникальныйИдентификатор',$firm_ref);
				$firm_ref = $v8->Справочники->Организации->ПолучитьСсылку($firm_id);
				
				$firm_nds_rate = 0;//($firm_ar['firm_nds']='t')? 18:0;
				
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
				
				if ($client_ref->Партнер->Пустая()){
						throw new Exception('Контрагент: '.$client_ref->Наименование.', не заполнено поле "Партнер"!');
				}
				//Договор
				/*
				$attrs = array();
				$attrs['pay_debt_sum'] = 0;
				$attrs['pay_delay_days'] = 0;
				$attrs['pay_ban_on_debt_sum'] = FALSE;
				$attrs['pay_ban_on_debt_days'] = FALSE;			
				$dog_ref = get_client_dog($v8,$client_ref,$firm_ref,$attrs);
				*/
				//throw new Exception($client_ref->Партнер->Наименование);
				$doc = $v8->Документы->ПриходныйКассовыйОрдер->СоздатьДокумент();	
				$doc->Дата							= date('YmdHis');
				$doc->Касса							= get_kassa($v8,$firm_ref,$payTypeCash);
				$doc->Организация					= $firm_ref;
				$doc->СуммаДокумента				= $sum;			
				$doc->ХозяйственнаяОперация			= $v8->Перечисления->ХозяйственныеОперации->ПоступлениеОплатыОтКлиента;
				$doc->ПринятоОт						= 'Розничные покупатели';
				$doc->Основание						= '';
				$doc->Приложение					= '';
				$doc->Контрагент					= $client_ref;
				$dog->Партнер						= $client_ref->Партнер;
				$doc->Валюта						= get_currency($v8);
				$doc->СтатьяДвиженияДенежныхСредств	= $st_dds_ref;
				
				//строка
				$line = $doc->РасшифровкаПлатежа->Добавить();
				$line->СтатьяДвиженияДенежныхСредств	= $doc->СтатьяДвиженияДенежныхСредств;
				$line->Сумма							= $sum;
				$line->ВалютаВзаиморасчетов				= $doc->Валюта;
				$line->СуммаВзаиморасчетов				= $sum;
				$line->СтавкаНДС						= $firm_nds_rate? $v8->Перечисления->СтавкиНДС->НДС20 : $v8->Перечисления->СтавкиНДС->БезНДС;
				$nds = round($sum*$firm_nds_rate/(100+$firm_nds_rate),2);
				$line->Партнер							= $client_ref->Партнер;
				$line->Организация						= $doc->Организация;
				
				
				$doc->ВТомЧислеНДС						= $firm_nds_rate? sprintf('НДС (%d%%) %s руб.',$firm_nds_rate, number_format($nds,2,'-','')) : 'Без налога (НДС)';
				
				//Автор документа
				if ($firm_ar['user_ref']){
					$user_id = $v8->NewObject('УникальныйИдентификатор',$firm_ar['user_ref']);			
					$doc->Кассир						= $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);			
				}
				
				if ($payTypeCash){
					$pref='КАРТА ';
				}
				else{
					$pref='';
				}
				$doc->Комментарий = $pref.'Заявки покупателей: '.$firm_ar['numbers'].', клиент:'.cyr_str_decode($firm_ar['client_descr']);
				//$doc->Записать($v8->РежимЗаписиДокумента->Проведение);
				$doc->Записать($v8->РежимЗаписиДокумента->Запись);
			}
			
			$v8->CommitTransaction();
		}
		catch (Exception $e){
			$v8->RollbackTransaction();
			throw $e;
		}
	}
	
	function sale($v8,$head,$items){						
		if ($head['number']){
			$q_crm_num = 'ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
			ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
			ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Документ_РеализацияТоваровУслуг)
			И Имя="'.CONST_1C_DOC_ATTR.'"';
			
			//попробуем найти по номеру, вдруг документ уже есть в 1с?!
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст =$q_crm_num;
			$sel = $q_obj->Выполнить()->Выбрать();
			if (!$sel->Следующий()){
				throw new Exception('Не найдено доп.свойство реализации Номер CRM!');
			}
			$crm_num_ref = $sel->Ссылка;
			$crm_num_sv_val = get_svoistvo($v8,$crm_num_ref,$head['number']);			
			
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
			
			$q_obj->УстановитьПараметр('ЗначениеСвойстваНомерCRM',$crm_num_sv_val);
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
		/*else if ($head['deliv_type']=='by_supplier'){
			//Перевозчик по-умолчанию сама НАША фирма, которая отгружает
			$carrier_ref = get_client_ref_on_inn($v8,$firm_ref->ИНН);	
			if (is_null($carrier_ref)){
				throw new Exception('Не найдена организация '.$firm_ref->Наименование.' в справочнике контрагентов по ИНН для перевочика в ТТН!');
			}			
		}*/
		
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
		//$doc->ДатаПлатежа				= $doc->Дата;
		$doc->ДатаРаспоряжения			= get_1c_date(date('Y-m-d'),date('G'),removeLeadingZero(date('i')),removeLeadingZero(date('s')));
		$doc->Партнер					= $client_ref->Партнер;
		$doc->Подразделение				= $v8->Справочники->СтруктураПредприятия->НайтиПоНаименованию(CONST_1C_DEP,TRUE);
		$doc->Склад						= $warehouse_ref;
		$doc->Комментарий				= get_doc_comment($head,$order_num);
											
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
		
		//$doc->Договор					= get_client_dog($v8,$client_ref,$firm_ref,$attrs,NULL);		
		if (isset($head['client_contract_ext_id'])){		
			$client_contract_id = $v8->NewObject('УникальныйИдентификатор',$head['client_contract_ext_id']);
			$doc->Договор = $v8->Справочники->ДоговорыКонтрагентов->ПолучитьСсылку($client_contract_id);
			if($doc->Договор->Пустая()){
				$doc->Договор = get_client_dog($v8,$client_ref,$firm_ref,$attrs,NULL);
			}
		}
		else{
			$doc->Договор = get_client_dog($v8,$client_ref,$firm_ref,$attrs,NULL);
		}
		
		$doc->Основание					= $doc->Договор->НаименованиеДляПечати;
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
	
		if (isset($head['gruzopoluchatel_ref'])){
			$gruzopoluchatel_id = $v8->NewObject('УникальныйИдентификатор',$head['gruzopoluchatel_ref']);
			$doc->Грузополучатель = $v8->Справочники->Контрагенты->ПолучитьСсылку($gruzopoluchatel_id);			
		}

		$stavka = get_nds($v8,$calcNDS);
		$nds_percent = get_nds_percent($calcNDS);
		$total=0;
		
		foreach($items as $item){
			$quant = floatval($item['quant']) * floatval($item['measure_unit_k']);
			if (!$quant){
				//После деления заявки приходят пустые номенклатуры???
				continue;
			}
			
			//номенклатура
			$item_ref = get_item($v8,$item);
			
			$line = $doc->Товары->Добавить();
			$line->Номенклатура 			= $item_ref;
			$line->КоличествоУпаковок 		= floatval($item['quant']);			
			$line->Упаковка					= get_item_mu($v8,$item,$item_ref);
			$line->Сумма					= $item['total'];
			$line->Цена						= $line->Сумма/$line->КоличествоУпаковок;
			$line->Склад					= $doc->Склад;
			$line->Количество				= $quant;
			
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
		
		$line = $doc->ЭтапыГрафикаОплаты->Добавить();
		$line->СверхЗаказа		= FALSE;
		$line->ВариантОплаты	= $v8->Перечисления->ВариантыОплатыКлиентом->КредитПослеОтгрузки;
		$line->ДатаПлатежа		= $doc->Дата;
		$line->ПроцентПлатежа	= 100;
		$line->СуммаПлатежа		= $total;
		$line->ПроцентЗалогаЗаТару = 100;
		$line->СуммаВзаиморасчетов = $total;
		
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
				
		if ($head['number']){
			//Дополнительные реквизиты
			//CRM number
			$extra_att = $doc->ДополнительныеРеквизиты->Добавить();
			$extra_att->Свойство			= $crm_num_ref;
			$extra_att->Значение			= $crm_num_sv_val;
			$extra_att->ТекстоваяСтрока		= $head['number'];
			
			//Comment
			$q_obj_com = $v8->NewObject('Запрос');
			$q_obj_com->Текст = 'ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
			ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
			ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Документ_РеализацияТоваровУслуг)
			И Имя="'.CONST_1C_DOC_ATTR_COMMENT.'"';
			$sel_com = $q_obj_com->Выполнить()->Выбрать();
			if (!$sel_com->Следующий()){
				throw new Exception('Не найдено доп.свойство реализации '.CONST_1C_DOC_ATTR_COMMENT.'!');
			}			
			$crm_com_sv_val = get_svoistvo($v8,$sel_com->Ссылка,$doc->Комментарий);
			$extra_att = $doc->ДополнительныеРеквизиты->Добавить();
			$extra_att->Свойство			= $sel_com->Ссылка;
			//$extra_att->Значение			= $crm_com_sv_val;
			$extra_att->Значение			= $doc->Комментарий;			
		}

		//Delivery expenses
		if ($deliv_expenses = floatval($head['deliv_expenses'])){
			setDocDelivExpenses($v8,$doc,floatval($head['deliv_expenses']));
		}
		
		$doc->СуммаДокумента = $total;
		$doc->СуммаВзаиморасчетов = $total;
		$doc->Записать($v8->РежимЗаписиДокумента->Проведение);//Проведение
		/*
		if ($head['number']){
			$rec_set = $v8->РегистрыСведений->ДополнительныеСведения->СоздатьНаборЗаписей();
			$rec_set->Отбор->Объект->Установить($doc->Ссылка);
			$rec_set->Отбор->Свойство->Установить($sel_com->Ссылка);
			$rec = $rec_set->Добавить();
			$rec->Объект = $doc->Ссылка;
			$rec->Свойство = $sel_com->Ссылка;
			$rec->Значение = $crm_com_sv_val;
			$rec_set->Записать();
		}
		*/
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
			$doc_ttn->АвтомобильМарка							= cyr_str_decode($head['vh_model']);//$doc_transp->АвтомобильМарка;
			$doc_ttn->АвтомобильТип								= '';//$doc_transp->АвтомобильТип;
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
	
	function setDocDelivExpenses($v8,$doc,$delivExpenses){
		$q_obj_com = $v8->NewObject('Запрос');
		$q_obj_com->Текст = 'ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
		ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
		ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Документ_РеализацияТоваровУслуг)
		И Имя="'.CONST_1C_DOC_ATTR_DELIV_EXPENSES.'"';
		$sel_exp = $q_obj_com->Выполнить()->Выбрать();
		if (!$sel_exp->Следующий()){
			throw new Exception('Не найдено доп.свойство реализации '.CONST_1C_DOC_ATTR_DELIV_EXPENSES.'!');
		}			
		$extra_att = $doc->ДополнительныеРеквизиты->Найти($sel_exp->Ссылка,'Свойство');
		if (!$extra_att){
			$extra_att = $doc->ДополнительныеРеквизиты->Добавить();
			$extra_att->Свойство			= $sel_exp->Ссылка;
		}		
		$extra_att->Значение			= $delivExpenses;
	}
	
	function set_deliv_expenses($v8,$ext_ship_id,$delivExpenses){
		//throw new Exception('set_deliv_expenses='.$set_deliv_expenses);
		$ship_id = $v8->NewObject('УникальныйИдентификатор',$ext_ship_id);
		$ship_ref = $v8->Документы->РеализацияТоваровУслуг->ПолучитьСсылку($ship_id);	
		if ($ship_ref->Пустая()){
			throw new Exception('Накладная не найдена!');
		}
		
		$doc = $ship_ref->ПолучитьОбъект();
		/*
		if ($doc->Заблокирован()){
			throw new Exception('Документ окрыт для редактирования!');
		}
		*/
		try{
			$doc->Заблокировать();
		}
		catch (Exception $e){
			throw new Exception('Документ открыт для редактирования!');
		}
		setDocDelivExpenses($v8,$doc,$delivExpenses);
		$doc->Записать($v8->РежимЗаписиДокумента->Проведение);
			
	}
	
?>
