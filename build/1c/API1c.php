<?php
	require_once('downloader.php');
	require_once('functions.php');
	define('COMMAND', 'cmd');
	
	//********* команды *************
	set_time_limit(300);
	/*
	Возвращает ссылку клиента по ИНН
	Параметры: inn
	Возврат: ссылка
	*/
	define('CMD_GET_CLIENT_ON_INN', 'get_client_on_inn');	

	/*
	Возвращает атрибуты клиента по имени
	Параметры: name
	Возврат: атрибуты контрагента
	*/
	define('CMD_GET_CLIENT_ATTRS', 'get_client_attrs_on_name');	
	
	/*
	Возвращает ссылку на организацию по имени
	Параметры: name
	Возврат: ссылка
	*/	
	define('CMD_GET_FIRM_ON_NAME', 'get_firm_on_name');

	/*
	Возвращает ссылку на номенклатурную группу по имени
	Параметры: name
	Возврат: ссылка
	*/	
	define('CMD_GET_PRODUCT_GROUP_ON_NAME', 'get_product_group_on_name');
	
	/*
	Возвращает ссылку на физ лицо по имени
	Параметры: name
	Возврат: ссылка
	*/	
	define('CMD_GET_PERSON_ON_NAME', 'get_person_on_name');

/*
	Возвращает атрибуты по врдителю
	Параметры: ref
	Возврат: набор атрибутов
	*/	
	define('CMD_GET_DRIVER_ATTRS', 'get_driver_attrs');
	
	/*
	Возвращает ссылку на пользователя по имени
	Параметры: name
	Возврат: ссылка
	*/	
	define('CMD_GET_USER_ON_NAME', 'get_user_on_name');
	
	/*
	Возвращает ссылку на вид деятельности
	Параметры: name
	Возврат: ссылка
	*/	
	define('CMD_GET_CLIENT_ACTIVITY_ON_NAME', 'get_client_activity_on_name');
	
	/*
	Возвращает ссылку склада по имени
	Параметры: name
	Возврат: ссылка
	*/	
	define('CMD_GET_WAREHOUSE_ON_NAME', 'get_warehouse_on_name');

	/*
	Возвращает ссылку единицы измерения по имени
	Параметры: name
	Возврат: ссылка
	*/	
	define('CMD_GET_MEASURE_ON_NAME', 'get_measure_on_name');
	
	/*
	Добавляет нового клиента
	Параметры: все реквизиты
	Возврат:
	*/		
	define('CMD_ADD_CLIENT', 'add_client');

	/*
	Возвращает список контрагентов по маске
	Параметры: templ
	Возврат: ссылка
	*/			
	define('CMD_COMPLETE_CLIENT', 'complete_client');

	/*
	Возвращает список пользователей по маске
	Параметры: templ
	Возврат: ссылка
	*/			
	define('CMD_COMPLETE_USER', 'complete_user');
	
	/*
	Возвращает строку с шапкой реквизитами фирмы
	Параметры: firm_ref
	Возврат: строка
	*/			
	define('CMD_FIRM_DATA', 'firm_data');
	
	/*
	Создает новую продажу
	Параметры:
		head serialized строка,			
		items - serialized строка
		
	Возврат: значения из 1с
	*/			
	define('CMD_SALE', 'sale');

	/*
	Печатать счет
	Параметры:
		doc_ref строка,			
		stamp - 1/0 не обязательный, по умолчанию 0
	Возврат: печатная форма PDF
	*/			
	define('CMD_PRINT_ORDER', 'print_order');
	
	/*
	Создает новый счет
	Параметры:
		head serialized строка,			
		items - serialized строка
		
	Возврат: значения из 1с
	*/			
	define('CMD_ORDER', 'order');
	
	/*
	Параметры:
		doc_ref ссылка документ реализации
		stamp - 1/0 не обязательный, по умолчанию 0
	Возврат: печатная форма
	*/			
	define('CMD_TORG12', 'print_torg12');

	/*
	Параметры:
		doc_ref ссылка документ счф
	Возврат: печатная форма
	*/			
	define('CMD_INVOICE', 'print_invoice');

	/*
	Создать АКТ сверки
	Параметры
		from date
		to date
		client_ref
		firm_ref
	ВОЗВРАТ печатная форма
	*/
	define('CMD_BALANCE','print_balance');
	
	/*
	Параметры:
		doc_ref ссылка документ реализации
		stamp - 1/0 не обязательный, по умолчанию 0
	Возврат: печатная форма УПД
	*/			
	define('CMD_UPD', 'print_upd');

	/*
	Параметры:
		doc_ref ссылка документ реализации
		stamp - 1/0 не обязательный, по умолчанию 0
	Возврат: печатная форма УПД+ТТН
	*/			
	define('CMD_SHIP', 'print_shipment');
	
	/*
	Параметры:
		doc_ref ссылка документ реализации
		stamp - 1/0 не обязательный, по умолчанию 0
		head - сериализованная строка
	Возврат: печатная форма ТТН
	*/			
	define('CMD_TTN', 'print_ttn');
	
	/*
	Параметры:
		head serialized строка,			
	*/	
	define('CMD_PAID_TO_ACC', 'paid_to_acc');
	
	/*
	Возвращает список долгов клиентов
	*/		
	define('CMD_GET_DEBT_LIST', 'get_debt_list');

	/*
	Удаляет документы
	Параметры:
		ext_order_id,ext_ship_id
	*/		
	define('CMD_DEL_DOCS', 'del_docs');
	
	//********* команды *************

	
	
	//***** параметры команд ************
	define('PAR_ID', 'id');
	define('PAR_NAME', 'name');
	define('PAR_INN', 'inn');
	define('PAR_TEMPL', 'templ');
	define('PAR_COUNT', 'count');
	define('PAR_DAYS', 'days');
	define('PAR_DATE', 'date');
	define('PAR_FIRM', 'firm_ref');
	define('PAR_DRIVER', 'driver_ref');
	define('PAR_CLIENT', 'client_ref');
	define('PAR_WAREHOUSE', 'warehouse_ref');
	define('PAR_DOC', 'doc_ref');
	define('PAR_HEAD', 'head');
	define('PAR_ITEMS', 'items');
	define('PAR_FROM', 'from');
	define('PAR_TO', 'to');
	define('PAR_STAMP', 'stamp');	
	//***** параметры команд ************

	//***** значения по умолчанию ************
	define('PAR_DEF_COUNT', '5');	
	define('PAR_DEF_DAYS', '0');
	define('PAR_DEF_STAMP', '0');
	//***** значения по умолчанию ************
	
	define('COM_OBJ_NAME', 'v82Server.Connection');
	define('API_EPF', dirname(__FILE__).'\API1C.epf');
	
	$xml_status = 'true';
	$xml_body = '';
	$SENT_FILE = FALSE;
	try{		
		if (!isset($_REQUEST[COMMAND])){
			//error
			throw new Exception('No command');
		}
		$com = $_REQUEST[COMMAND];
		if ($com==CMD_ADD_CLIENT){
			$v8 = new COM(COM_OBJ_NAME);
			$xml_body = sprintf('<ref>%s</ref>',client($v8,$_REQUEST));
		}
		else if ($com==CMD_GET_CLIENT_ON_INN){
			if (!strlen($_REQUEST[PAR_INN])){
				throw new Exception("Не задан ИНН");
			}
			$v8 = new COM(COM_OBJ_NAME);
			$ref = get_clienton_on_inn($v8,$_REQUEST[PAR_INN]);
			if (!is_null($ref)){
				$xml_body = sprintf('<ref>%s</ref>',
					$v8->String($ref->УникальныйИдентификатор()));
			}
		}
		else if ($com==CMD_GET_CLIENT_ATTRS){
			
			if (!isset($_REQUEST[PAR_NAME])){
				throw new Exception("Не задано наименование");
			}
			$name = cyr_str_decode($_REQUEST[PAR_NAME]);
			$name = str_replace('\"','""',$name);
			
			$v8 = new COM(COM_OBJ_NAME);
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
			
			ЛЕВОЕ СОЕДИНЕНИЕ РегистрСведений.КонтактнаяИнформация КАК КИТелефон
			ПО КИТелефон.Объект=Клиент.Ссылка И КИТелефон.Тип=ЗНАЧЕНИЕ(Перечисление.ТипыКонтактнойИнформации.Телефон)
			И КИТелефон.Вид=ЗНАЧЕНИЕ(Справочник.ВидыКонтактнойИнформации.ТелефонКонтрагента)
			
			ЛЕВОЕ СОЕДИНЕНИЕ РегистрСведений.КонтактнаяИнформация КАК КИАдресРег
			ПО КИАдресРег.Объект=Клиент.Ссылка И КИАдресРег.Тип=ЗНАЧЕНИЕ(Перечисление.ТипыКонтактнойИнформации.Адрес)
			И КИАдресРег.Вид=ЗНАЧЕНИЕ(Справочник.ВидыКонтактнойИнформации.ЮрАдресКонтрагента)
			
			ЛЕВОЕ СОЕДИНЕНИЕ РегистрСведений.КонтактнаяИнформация КАК КИАдресПочт
			ПО КИАдресПочт.Объект=Клиент.Ссылка И КИАдресПочт.Тип=ЗНАЧЕНИЕ(Перечисление.ТипыКонтактнойИнформации.Адрес)
			И КИАдресПочт.Вид =ЗНАЧЕНИЕ(Справочник.ВидыКонтактнойИнформации.ФактАдресКонтрагента)
			
			ЛЕВОЕ СОЕДИНЕНИЕ Справочник.БанковскиеСчета КАК РС
			ПО РС.Ссылка=Клиент.ОсновнойБанковскийСчет
			
			ЛЕВОЕ СОЕДИНЕНИЕ Справочник.Банки КАК Банки
			ПО Банки.Ссылка=РС.Банк
			
			ГДЕ Клиент.Наименование="'.$name.'"
			
			';
			
			$sel = $q_obj->Выполнить()->Выбрать();
			if ($sel->Следующий()){
				$xml_body.= sprintf('<ref>%s</ref>',
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
			}
		}
		
		else if ($com==CMD_COMPLETE_CLIENT){
			/*
			if (!isset($_REQUEST[PAR_TEMPL])){
				throw new Exception("Не задан шаблон");
			}
			$count = (isset($_REQUEST[PAR_COUNT]))? $_REQUEST[PAR_COUNT]:PAR_DEF_COUNT;
			$par = str_replace('\"','""',$_REQUEST[PAR_TEMPL]);
			$v8 = new COM(COM_OBJ_NAME);
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст ='ВЫБРАТЬ ПЕРВЫЕ '.$count.' 
				Клиент.Ссылка КАК ref,
				Клиент.Наименование КАК name
				ИЗ Справочник.Контрагенты КАК Клиент
				ГДЕ Клиент.Наименование ПОДОБНО "'.cyr_str_decode($par).'%"';
			$sel = $q_obj->Выполнить()->Выбрать();
			while ($sel->Следующий()){
				$xml_body.= sprintf("<ref name='%s'>%s</ref>",
					cyr_str_encode($sel->name),
					$v8->String($sel->ref->УникальныйИдентификатор()));
			}
			*/
			$xml_body = completeSprOnDescr('Контрагенты');
		}
		else if ($com==CMD_COMPLETE_USER){
			$xml_body = completeSprOnDescr('Пользователи');
		}		
		else if ($com==CMD_GET_FIRM_ON_NAME){
			$xml_body = getSprRefOnDescr('Организации');
		}		
		else if ($com==CMD_GET_PRODUCT_GROUP_ON_NAME){
			$xml_body = getSprRefOnDescr('НоменклатурныеГруппы');
		}		
		else if ($com==CMD_GET_PERSON_ON_NAME){
			//$xml_body = getSprRefOnDescr('ФизическиеЛица');
			$xml_body = getPersonRefOnDescr();
		}		
		else if ($com==CMD_GET_DRIVER_ATTRS){
			$xml_body = getPersonAttrs();
		}		
		
		else if ($com==CMD_GET_USER_ON_NAME){
			$xml_body = getSprRefOnDescr('Пользователи');
		}		
		
		else if ($com==CMD_GET_CLIENT_ACTIVITY_ON_NAME){
			$xml_body = getSprRefOnDescr('ВидыДеятельностиКонтрагентов');
		}		
		
		else if ($com==CMD_GET_WAREHOUSE_ON_NAME){
			$xml_body = getSprRefOnDescr('Склады');
		}
		else if ($com==CMD_GET_MEASURE_ON_NAME){
			if (!isset($_REQUEST[PAR_NAME])){
				throw new Exception("Не задано наименование");
			}
			$par = stripslashes(cyr_str_decode($_REQUEST[PAR_NAME]));
			$par = str_replace('\"','""',$par);
			$v8 = new COM(COM_OBJ_NAME);
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст ='ВЫБРАТЬ Спр.Ссылка КАК ref,
			Спр.НаименованиеПолное КАК name_full
			ИЗ Справочник.КлассификаторЕдиницИзмерения КАК Спр
			ГДЕ Спр.Наименование="'.$par.'"';
			$sel = $q_obj->Выполнить()->Выбрать();
			if ($sel->Следующий()){
				$xml_body = sprintf('<ref>%s</ref><name_full>%s</name_full>',
					$v8->String($sel->ref->УникальныйИдентификатор()),
					$v8->String(cyr_str_encode($sel->name_full))
					);
			}
		}						
		else if ($com==CMD_GET_DEBT_LIST){
			$par_date = date("Y,m,d,23,59,59");
			$v8 = new COM(COM_OBJ_NAME);
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст ="
			ВЫБРАТЬ
			Расчеты.Организация AS firmRef,
			Расчеты.Контрагент AS clientRef,
			ВЫРАЗИТЬ(СУММА(ЕСТЬNULL(Расчеты.СуммаВзаиморасчетовОстаток,0)) КАК ЧИСЛО(15,2)) КАК Сумма
			ИЗ
			    РегистрНакопления.ВзаиморасчетыСКонтрагентами.Остатки(
			        ДАТАВРЕМЯ(".$par_date.")) КАК Расчеты
			СГРУППИРОВАТЬ ПО Расчеты.Организация,Расчеты.Контрагент
			";
			$sel = $q_obj->Выполнить()->Выбрать();
			while ($sel->Следующий()){
				$sm = str_replace(' ','',$sel->Сумма);
				$sm = str_replace(',','.',$sm);
				$sm = floatval($sm);
				//$v8->String($sel->Сумма)
				if ($sm<>0){
					$xml_body.='<rec>'.
						sprintf('<firmRef>%s</firmRef>',
							$v8->String($sel->firmRef->УникальныйИдентификатор())
						).
						sprintf('<clientRef>%s</clientRef>',
							$v8->String($sel->clientRef->УникальныйИдентификатор())
						).					
						sprintf('<debt>%f</debt>',
							$sm
						).
						'</rec>';
				}
			}						
		}
		/* не рабочий метод*/
		/*
		else if ($com==CMD_GET_CLIENT_DEBT){
			if (!isset($_REQUEST[PAR_ID])){
				throw new Exception("Не задан идентификатор клиента");
			}
			//$par_date = date("Y,m,d,23,59,59");
			//ДАТАВРЕМЯ(".$par_date.")
			$par_date = date("Y,m,d,23,59,59");
			
			$v8 = new COM(COM_OBJ_NAME);
			$q_obj = $v8->NewObject('Запрос');
			$q_obj->Текст ="
			ВЫБРАТЬ
			Расчеты.Организация AS ref,
			СУММА(ЕСТЬNULL(Расчеты.СуммаВзаиморасчетовОстаток,0)) КАК Сумма
			ИЗ
			    РегистрНакопления.ВзаиморасчетыСКонтрагентами.Остатки(
			        ДАТАВРЕМЯ(".$par_date."), Контрагент=&Контрагент) КАК Расчеты
			СГРУППИРОВАТЬ ПО Расчеты.Организация
			";
			$uid = $v8->NewObject('УникальныйИдентификатор',$_REQUEST[PAR_ID]);
			$q_obj->УстановитьПараметр('Контрагент',
				$v8->Справочники->Контрагенты->ПолучитьСсылку($uid));
			$sel = $q_obj->Выполнить()->Выбрать();
			while ($sel->Следующий()){
				$xml_body.='<org>'.sprintf('<ref>%s</ref>',
					$v8->String($sel->ref->УникальныйИдентификатор())
					).
					sprintf('<debt>%s</debt>',
						$v8->String($sel->Сумма)
					).
					'</org>';
			}			
		}
		*/
		else if ($com==CMD_SALE){
			$head = unserialize(stripslashes($_REQUEST[PAR_HEAD]));
			$items = unserialize(stripslashes($_REQUEST[PAR_ITEMS]));
			$v8 = new COM(COM_OBJ_NAME);
			$xml_body = sale($v8,$head,$items);
		}		
		else if ($com==CMD_ORDER){
			$head = unserialize(stripslashes($_REQUEST[PAR_HEAD]));
			$items = unserialize(stripslashes($_REQUEST[PAR_ITEMS]));
			$v8 = new COM(COM_OBJ_NAME);
			$xml_body = order($v8,$head,$items);
		}		
		else if ($com==CMD_PRINT_ORDER){
			$doc_ref = $_REQUEST[PAR_DOC];
			if (!$doc_ref){
				throw new Exception("Не задан идентификатор документа!");
			}
			$stamp = ($_REQUEST[PAR_STAMP])? intval($_REQUEST[PAR_STAMP]):PAR_DEF_STAMP;
			$user_ref = ($_REQUEST['user_ref'])? $_REQUEST['user_ref']:'';
			$v8 = new COM(COM_OBJ_NAME);
			$obr = get_ext_obr($v8);
			$file = $obr->ПечатьСчетаВФайл($doc_ref,$user_ref,$stamp);			
			downloadfile($file);
			unlink($file);
			$SENT_FILE = TRUE;
		}		
		
		else if ($com==CMD_TORG12){
			$doc_ref = $_REQUEST[PAR_DOC];
			if (!$doc_ref){
				throw new Exception("Не задан идентификатор документа!");
			}
			$stamp = ($_REQUEST[PAR_STAMP])? intval($_REQUEST[PAR_STAMP]):PAR_DEF_STAMP;
			$user_ref = ($_REQUEST['user_ref'])? $_REQUEST['user_ref']:'';
			$v8 = new COM(COM_OBJ_NAME);
			$obr = get_ext_obr($v8);
			$file = $obr->ПечатьТорг12ВФайл($doc_ref,$user_ref,$stamp);
			downloadfile($file);
			unlink($file);
			$SENT_FILE = TRUE;
		}	
		else if ($com==CMD_SHIP){
			$doc_ref = $_REQUEST[PAR_DOC];
			if (!$doc_ref){
				throw new Exception("Не задан идентификатор документа!");
			}
			$stamp = ($_REQUEST[PAR_STAMP])? intval($_REQUEST[PAR_STAMP]):PAR_DEF_STAMP;
			$user_ref = ($_REQUEST['user_ref'])? $_REQUEST['user_ref']:'';
			
			$head = unserialize(stripslashes($_REQUEST[PAR_HEAD]));
			
			$v8 = new COM(COM_OBJ_NAME);
			
			$attrs = fill_struc_for_ttn($v8,$head);
			
			$obr = get_ext_obr($v8);
			
			$file1 = $obr->ПечатьУПДВФайл($doc_ref,$user_ref,$stamp);			
			$file2 = $obr->ПечатьТТНВФайл($doc_ref,$attrs,$user_ref,$stamp);
			
			$zip = new ZipArchive();
			$file_zip = dirname(__FILE__).'/'.md5(uniqid()).'.zip';
			if ($zip->open($file_zip, ZIPARCHIVE::CREATE)!==TRUE) {
				throw new Exception("cannot open <$file_zip>\n");
			}

			$zip->addFile($file1,'upd.pdf');
			$zip->addFile($file2,'ttn.pdf');
			$zip->close();
			
			ob_clean();
			downloadfile($file_zip);//$file_zip
			unlink($file_zip);
			
			/*
			$file = $obr->ПечатьПакетаОтгрузВФайл($doc_ref,$attrs,$user_ref,$stamp);
			
			downloadfile($file);
			unlink($file);
			*/
			$SENT_FILE = TRUE;
		}						
		else if ($com==CMD_UPD){
			$doc_ref = $_REQUEST[PAR_DOC];
			if (!$doc_ref){
				throw new Exception("Не задан идентификатор документа!");
			}
			$stamp = ($_REQUEST[PAR_STAMP])? intval($_REQUEST[PAR_STAMP]):PAR_DEF_STAMP;
			$user_ref = ($_REQUEST['user_ref'])? $_REQUEST['user_ref']:'';
			$v8 = new COM(COM_OBJ_NAME);
			$obr = get_ext_obr($v8);
			$file = $obr->ПечатьУПДВФайл($doc_ref,$user_ref,$stamp);
			downloadfile($file);
			unlink($file);
			$SENT_FILE = TRUE;
		}				
		else if ($com==CMD_TTN){
			$doc_ref = $_REQUEST[PAR_DOC];
			if (!$doc_ref){
				throw new Exception("Не задан идентификатор документа!");
			}
			$stamp = ($_REQUEST[PAR_STAMP])? intval($_REQUEST[PAR_STAMP]):PAR_DEF_STAMP;
			$head = unserialize(stripslashes($_REQUEST[PAR_HEAD]));
			$user_ref = ($_REQUEST['user_ref'])? $_REQUEST['user_ref']:'';
			//throw new Exception('driver_name='.cyr_str_decode($head["driver_name"]));
			$v8 = new COM(COM_OBJ_NAME);			
			
			$attrs = fill_struc_for_ttn($v8,$head);
			
			$obr = get_ext_obr($v8);
			$file = $obr->ПечатьТТНВФайл($doc_ref,$attrs,$user_ref,$stamp);
			ob_clean();
			downloadfile($file);
			unlink($file);
			$SENT_FILE = TRUE;
		}				
		
		else if ($com==CMD_INVOICE){
			$doc_ref = $_REQUEST[PAR_DOC];
			if (!$doc_ref){
				throw new Exception("Не задан идентификатор документа!");
			}
			$v8 = new COM(COM_OBJ_NAME);
			$obr = get_ext_obr($v8);
			$user_ref = ($_REQUEST['user_ref'])? $_REQUEST['user_ref']:'';
			$file = $obr->ПечатьСЧФВФайл($doc_ref,$user_ref);
			downloadfile($file);
			unlink($file);
			$SENT_FILE = TRUE;
		}
		else if	($com==CMD_BALANCE){
			if (!isset($_REQUEST[PAR_CLIENT])){
				throw new Exception("Не задан идентификатор клиента!");
			}						
			if (!isset($_REQUEST[PAR_FIRM])){
				throw new Exception("Не задан идентификатор фирмы!");
			}									
			if (!isset($_REQUEST[PAR_FROM])){
				throw new Exception("Не задана дата!");
			}												
			if (!isset($_REQUEST[PAR_TO])){
				throw new Exception("Не задана дата!");
			}
			//throw new Exception('PAR_FIRM='.$_REQUEST[PAR_FIRM]);
			$from = date('Ymd',$_REQUEST[PAR_FROM]);
			$to = date('Ymd',$_REQUEST[PAR_TO]);
			$v8 = new COM(COM_OBJ_NAME);
			$obr = get_ext_obr($v8);
			$user_ref = ($_REQUEST['user_ref'])? $_REQUEST['user_ref']:'';
			$file_type = ($_REQUEST['file_type'])? $_REQUEST['file_type']:'pdf';
			
			$file = $obr->НайтиСоздатьНапечататьАктСверки(
				$_REQUEST[PAR_FIRM],
				$_REQUEST[PAR_CLIENT],
				$from,
				$to,
				$user_ref,
				$file_type
				);
			downloadfile($file);
			unlink($file);
			$SENT_FILE = TRUE;
			
		}
		else if ($com==CMD_FIRM_DATA){
			if (!isset($_REQUEST[PAR_FIRM])){
				throw new Exception("Не задан идентификатор документа!");
			}				
			$v8 = new COM(COM_OBJ_NAME);
			$obr = get_ext_obr($v8);
			$str = cyr_str_encode($obr->ШапкаФирмы($_REQUEST[PAR_FIRM]));
			$xml_body.='<org_data>'.$str.'</org_data>';			
		}
		else if ($com==CMD_PAID_TO_ACC){
			$head = unserialize(stripslashes($_REQUEST[PAR_HEAD]));
			$v8 = new COM(COM_OBJ_NAME);
			pko($v8,$head);
		}
		else if ($com==CMD_DEL_DOCS){
			$v8 = new COM(COM_OBJ_NAME);
			del_docs($v8,$_REQUEST['ext_order_id'],$_REQUEST['ext_ship_id']);
		}
	}	
	catch (Exception $e){
		//error
		$xml_status = 'false';		
		$xml_body.='<error><![CDATA['.cyr_str_encode($e->getMessage()).']]></error>';		
		//$xml_body.='<error>'.cyr_str_encode($e->getMessage()).'</error>';		
	}
	if (!$SENT_FILE){
		$res_xml = '<?xml version="1.0" encoding="UTF-8"?>';
		$res_xml .= '<response status="'.$xml_status.'">';
		$res_xml .= $xml_body.'</response>';
		
		echo $res_xml;
	}
?>
