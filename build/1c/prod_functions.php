<?php
	/**
	 * Константы, должны быть в Инф Базе!!!
	 */
	 
	/* Дополнительное свойства реализации */
	
	//ПередачаМатериаловВПроизводство
	define('SV_PASS_TO_PROD','НомерCRM');
	
	define('SRORE_NAME','Склад материалов УП');
	define('FIRM_NAME','Управленческая организация');
	define('DEP_NAME','Основное производство');
	
	define('CONST_1C_ITEM_KIND_FOR_MATERIAL','Сырье  и материалы (б/х)');
	define('CONST_1C_ITEM_KIND_FOR_PROD','Полуфабрикат (б/х)');
	define('CONST_1C_ITEM_MU_NAME','кг');

	function cyr_str_decode($str){
		return iconv('UTF-8','Windows-1251',$str);
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
	
	function get_material_ref($v8,$doc,$isMaterial){
		$name = cyr_str_decode($doc['material_descr']);
		$material_ref = $v8->Справочники->Номенклатура->НайтиПоНаименованию($name,TRUE);
		if($material_ref->Пустая()){			
		
			//Вид
			$kind_name = $isMaterial? CONST_1C_ITEM_KIND_FOR_MATERIAL:CONST_1C_ITEM_KIND_FOR_PROD;
			$item_kind_ref = $v8->Справочники->ВидыНоменклатуры->НайтиПоНаименованию($kind_name,TRUE);
			if ($item_kind_ref->Пустая()){
				throw new Exception('Не найден вид номенклатуры "'.$kind_name.'"!');
			}
			
			//Единица
			$bmu_id = $v8->NewObject('УникальныйИдентификатор',CONST_1C_ITEM_MU_NAME);
			$bmu_ref = $v8->Справочники->УпаковкиЕдиницыИзмерения->ПолучитьСсылку($bmu_id);
			
			//новая номенклатура
			$material_obj = $material_obj = $v8->Справочники->Номенклатура->СоздатьЭлемент();
			$material_obj->ВариантОформленияПродажи			= $v8->Перечисления->ВариантыОформленияПродажи->РеализацияТоваровУслуг;
			$material_obj->Наименование						= $name;
			$material_obj->НаименованиеПолное				= $name;
			//$material_obj->ГруппаФинансовогоУчета			= $item_fin_grp_ref;
			//$material_obj->ГруппаАналитическогоУчета		= $item_analit_grp_ref;
			$material_obj->Качество							= $v8->Перечисления->ГрадацииКачества->ОграниченноГоден;				
			$material_obj->НаборУпаковок					= $v8->Справочники->НаборыУпаковок->ИндивидуальныйДляНоменклатуры;
			//$material_obj->ГруппаДоступа					= $v8->Справочники->ГруппыДоступаНоменклатуры->;
			$material_obj->ЕдиницаИзмерения					= $bmu_ref;
			$material_obj->ИспользованиеХарактеристик		= $v8->Перечисления->ВариантыИспользованияХарактеристикНоменклатуры->НеИспользовать;				
			$material_obj->ИспользоватьУпаковки				= FALSE;
			$material_obj->СтавкаНДС						= $v8->Перечисления->СтавкиНДС->НДС20;				
			$material_obj->ТипНоменклатуры					= $v8->Перечисления->ТипыНоменклатуры->Товар;
			$material_obj->ВидНоменклатуры					= $item_kind_ref;
			//$material_obj->Родитель							= $group2_ref;
			$material_obj->Записать();			
			
			$material_ref = $material_obj->Ссылка;
		}
		return $material_ref;
	}
	
	/**
	 * ПередачаМатериаловВПроизводство
	 */
	function passMaterialToProduction($v8,$docs){
		$res_xml = '<docs>';
		
		$q_crm_num = 'ВЫБРАТЬ ПЕРВЫЕ 1 Ссылка
		ИЗ ПланВидовХарактеристик.ДополнительныеРеквизитыИСведения
		ГДЕ НаборСвойств = ЗНАЧЕНИЕ(Справочник.НаборыДополнительныхРеквизитовИСведений.Документ_ПередачаМатериаловВПроизводство)
		И Имя="'.SV_PASS_TO_PROD.'"';				
		
		$q_obj = $v8->NewObject('Запрос');
		$q_obj->Текст =	$q_crm_num;
		$sel = $q_obj->Выполнить()->Выбрать();
		if (!$sel->Следующий()){
			throw new Exception('Не найдено доп.свойство ПередачаМатериаловВПроизводство '.SV_PASS_TO_PROD);
		}		
		$crm_num_ref = $sel->Ссылка;
		
		foreach($docs as $doc){
			
			if (!$doc['material_cons_id']){
					throw new Exception('passMaterialToProduction: material_cons_id not found.');
			}
			
			$crm_num_sv_val = get_svoistvo($v8,$crm_num_ref,$head['material_cons_id']);			
			
			if ($doc['doc_ext_ref']){
				//document reference passed
				$doc_id = $v8->NewObject('УникальныйИдентификатор',$doc['doc_ext_ref']);
				$doc_ref = $v8->Документы->ПередачаМатериаловВПроизводство->ПолучитьСсылку($doc_id);
				$doc_obj = $doc_ref->ПолучитьОбъект();
				$doc_obj->Товары->Очистить();
				$doc_obj->ВидыЗапасов->Очистить();
				$doc_obj->Серии->Очистить();
				$doc_obj->ДополнительныеРеквизиты->Очистить();
			}
			else{
				//web number
				//попробуем найти по номеру, вдруг документ уже есть в 1с?!
				
				$q_obj = $v8->NewObject('Запрос');
				$q_obj->Текст ='ВЫБРАТЬ Док.Ссылка
				ИЗ Документ.ПередачаМатериаловВПроизводство КАК Док			
				ЛЕВОЕ СОЕДИНЕНИЕ РегистрСведений.ДополнительныеСведения КАК ДопРек
				ПО ДопРек.Объект = Док.Ссылка И ДопРек.Свойство в ('.$q_crm_num.')
				ГДЕ ДопРек.Значение=&ЗначениеСвойстваНомерCRM
				';
				
				$q_obj->УстановитьПараметр('ЗначениеСвойстваНомерCRM',$crm_num_sv_val);
				$sel = $q_obj->Выполнить()->Выбрать();
				if ($sel->Следующий()){
					$doc_obj = $sel->Ссылка->ПолучитьОбъект();
				}
				else{
					//new document
					$doc_obj = $v8->Документы->ПередачаМатериаловВПроизводство->СоздатьДокумент();	
				}
			}
			
			if ($doc['user_ext_ref']){
				$user_id = $v8->NewObject('УникальныйИдентификатор',$doc['user_ext_ref']);
				$user_ref = $v8->Справочники->Пользователи->ПолучитьСсылку($user_id);
				
			}
			else{
				$user_ref = $v8->Справочники->Пользователи->ПустаяСсылка();
			}
			
			$firm_ref = $v8->Справочники->Организации->НайтиПоНаименованию(FIRM_NAME,TRUE);
			$store_ref = $v8->Справочники->Склады->НайтиПоНаименованию(STORE_NAME,TRUE);
			$dep_ref = $v8->Справочники->ПодразделенияОрганизаций->НайтиПоНаименованию(DEP_NAME,TRUE);
			
			//document attributes
			$doc_obj->Организация				= $firm_ref;
			$doc_obj->Склад						= $store_ref;
			//$doc_obj->Распоряжение
			$doc_obj->Подразделение				= $dep_ref;
			$doc_obj->Ответственный				= $user_ref;
			$doc_obj->Комментарий				= 'Web смена №'.$doc['work_shift_id'].', документ '.$doc['material_cons_id'];
			$doc_obj->ПередачаПоРаспоряжениям	= FALSE;
			//$doc_obj->ХозяйственнаяОперация	= NULL;
			$doc_obj->Статус					= $v8->Перечисления->СтатусыПередачМатериаловВПроизводство->Отгружено;
			//$doc_obj->ПотреблениеДляДеятельности
			//$doc_obj->СостояниеЗаполненияМногооборотнойТары
			$doc_obj->ВидыЗапасовУказаныВручную = FALSE;
			//$doc_obj->ВидЦены
			//$doc_obj->НаправлениеДеятельности
			
			$line = $doc_obj->Товары->Добавить();
			$line->Номенклатура			= get_material_ref($v8,$doc,TRUE);
			//$line->Характеристика		= 
			$line->КоличествоУпаковок	= $doc['weight'];
			$line->Количество			= $doc['weight'];
			
			//Дополнительные реквизиты
			//CRM number
			$extra_att = $doc_obj->ДополнительныеРеквизиты->Добавить();
			$extra_att->Свойство			= $crm_num_ref;
			$extra_att->Значение			= $crm_num_sv_val;
			$extra_att->ТекстоваяСтрока		= $doc['material_cons_id'];
			
			$doc_obj->Записать($v8->РежимЗаписиДокумента->Запись);//Проведение
			
			$res_xml.= sprintf(
				'<doc>
					<doc_ext_ref>%s</doc_ext_ref>
					<doc_ext_num>%s</doc_ext_num>
					<material_cons_id>%d</material_cons_id>
				</doc>',		
				$v8->String($doc_obj->Ссылка->УникальныйИдентификатор()),
				cyr_str_encode($doc_obj->Номер),
				$doc['material_cons_id']
			);
			
		}
		$res_xml.= '</docs>';
		
		return $res_xml;
	}
	
	/**
	 * ВыпускПродукции
	 */
	function formProduction($v8,$docs){
		//Товары
		//Серии
		//ДополнительныеРеквизиты
	}
	
?>