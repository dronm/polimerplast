/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
 * @requires controls/ViewList.js
*/

/* constructor */
function RepSales_View(id,options){
	options = options || {};
	options.title = "Формирование отчета";
	options.controller = Report_Controller;
	options.methodId = "sales";
	options.reportType = "sales";
	options.viewId = "ViewHTMLXSLT";
	options.connect = options.connect;
	
	RepSales_View.superclass.constructor.call(this,
		id,options);
	
//Fields
	var fields = [
			//*** Клиенты ***
			{"id":"client_descr",
			"name":"Клиент",
			"agg":false,
			"filtered":false,
			"filterControl":new ClientEditObject("doc_client_id","client",true)
			},
			{"id":"client_login_allowed",
			"name":"Работа через личный кабинет",
			"agg":false,
			"filtered":false,
			"filterControl":new EditCheckBox(uuid(),{})
			},
			{"id":"client_activity_descr",
			"name":"Вид деятельности",
			"agg":false,
			"filtered":false,
			"filterControl":new ClientActivityEdit({
				fieldId:"client_activity_descr",
				controlId:"client_activity_descr",
				inLine:true})				
			},
			{"id":"client_pay_type",
			"name":"Условия оплаты",
			"agg":false,
			"filtered":false,
			"filterControl":new PayTypeEditObject(
				"client_pay_type",
				"client_pay_type",
				true)				
			},
			{"id":"client_pay_debt_days",
			"name":"Срок отсрочки платежа",
			"agg":false,
			"filtered":false,
			"filterControl":new EditNum(uuid(),{})
			},
			{"id":"client_pay_debt_days",
			"name":"Максимальный допустипый срок просрочки",
			"agg":false,
			"filtered":false,
			"filterControl":new EditNum(uuid(),{})
			},
			{"id":"client_pay_debt_sum",
			"name":"Максимальная сумма просрочки",
			"agg":false,
			"filtered":false,
			"filterControl":new EditFloat(uuid(),{})
			},
			
			{"id":"client_debt_period_days_descr",
			"name":"Срок дебиторской задолженности",
			"agg":false,
			"filtered":false,
			"filterControl":new ClientDebtPeriodEdit({
				fieldId:"client_debt_period_days_descr",
				controlId:"client_debt_period_days_descr",
				inLine:true})
			},
			
			{"id":"client_contracts_exist",
			"name":"Наличие договоров",
			"agg":false,
			"filtered":false,
			"filterControl":new EditCheckBox(uuid(),{})
			},
			{"id":"client_contract_end",
			"name":"Ближайший срок окончания договоров",
			"agg":false,
			"filtered":false,
			"filterControl":new EditDate(uuid(),{})
			},
			//*** Заявки ***
			{"id":"doc_number",
			"name":"№ заявки",
			"agg":false,
			"filtered":false,
			"filterControl":new EditNum("number",{})
			},
			{"id":"doc_date_time_descr",
			"name":"Дата подачи",
			"agg":false,
			"filtered":null,
			"filterControl":null
			},			
			{"id":"doc_delivery_fact_date_descr",
			"name":"Дата фактической отгрузки",
			"agg":false,
			"filtered":null,
			"filterControl":null
			},			
			
			{"id":"firm_descr",
			"name":"Организация",
			"agg":false,
			"filtered":false,
			"filterControl":new FirmEditObject("firm_descr","firm",true)
			},
		
			{"id":"production_city_descr",
			"name":"Город отгрузки",
			"agg":false,
			"filtered":false,
			"filterControl":new ProductionCityEditObject("production_city_descr","production_city",true)
			},							
			{"id":"doc_delivery_plan_date",
			"name":"Планируемая дата выполнения",
			"agg":false,
			"filtered":false,
			"filterControl":new EditDate(uuid(),{})
			},				
			{"id":"doc_in_time",
			"name":"Своевременное выполнение",
			"agg":false,
			"filtered":false,
			"filterControl":new EditCheckBox(uuid(),{})
			},				
			{"id":"doc_user_descr",
			"name":"Ответственный",
			"agg":false,
			"filtered":false,
			"filterControl":new UserEditObject("doc_user_id","user",true)
			},
			/*
			Убрал молча мутное поле				
			{"id":"doc_overlimit",
			"name":"Согласование сверхлимитной заявки",
			"agg":false,
			"filtered":false,
			"filterControl":new EditCheckBox(uuid(),{})
			},
			*/				
			{"id":"doc_delivery_type_descr",
			"name":"Тип доставки",
			"agg":false,
			"filtered":false,
			"filterControl":new DelivTypeEditObject({
				fieldId:"doc_delivery_type_descr",
				controlId:"delivery_type",
				inLine:true})
			},
			{"id":"doc_deliv_to_third_party",
			"name":"Доставка третьим лицам",
			"agg":false,
			"filtered":false,
			"filterControl":new EditCheckBox(uuid(),{})
			},				
			{"id":"doc_deliv_destination_descr",
			"name":"Адрес доставки",
			"agg":false,
			"filtered":false,
			"filterControl":new EditString(uuid())
			},				
			{"id":"doc_deliv_period",
			"name":"Назначенное время доставки",
			"agg":false,
			"filtered":null,
			"filterControl":new DeliveryPeriodEditObject(
				"doc_deliv_period","doc_deliv_period",true)
			},				
			{"id":"doc_warehouse_descr",
			"name":"Склад отгрузки",
			"agg":false,
			"filtered":false,
			"filterControl":new WarehouseEditObject("doc_warehouse_descr","warehouse",true)
			},				
			{"id":"doc_driver_descr",
			"name":"Водитель",
			"agg":false,
			"filtered":false,
			"filterControl":new DriverEditObject(
				"doc_driver_id","doc_driver",true)
			},				
			{"id":"doc_vehicle_descr",
			"name":"ТС",
			"agg":false,
			"filtered":false,
			"filterControl":new VehicleEditObject({
				fieldId:"doc_vehicle_descr",
				controlId:"vehicle",
				inLine:true
				})
			},				
			
			{"id":"doc_ext_order_num",
			"name":"Номер счета",
			"agg":false,
			"filtered":false,
			"filterControl":new EditString(uuid())
			},				
			{"id":"doc_ext_ship_num",
			"name":"Номер реализации",
			"agg":false,
			"filtered":false,
			"filterControl":new EditString(uuid())
			},				
			{"id":"doc_state_date_time",
			"name":"Дата статуса заявки",
			"agg":false,
			"filtered":false,
			"filterControl":new EditDate(uuid())
			},				
			
			{"id":"doc_state",
			"name":"Статус заявки",
			"agg":false,
			"filtered":false,
			"filterControl":new OrderStateEditObject('doc_state_id','doc_state_id',true)
			},				
			{"id":"doc_state_user",
			"name":"Пользователь статуса заявки",
			"agg":false,
			"filtered":false,
			"filterControl":new UserEditObject('doc_state_user','doc_state_user',true)
			},				
			
			{"id":"doc_sales_manager_comment",
			"name":"Комментарий внутренний",
			"agg":false,
			"filtered":false,
			"filterControl":new EditString(uuid())
			},				
			{"id":"doc_client_comment",
			"name":"Комментарий общий",
			"filtered":null,
			"agg":false,
			"filterControl":new EditString(uuid())
			},				
			
			//продукция
			{"id":"doct_product_descr",
			"name":"Продукция",
			"agg":false,
			"filtered":false,
			"filterControl":new ProductEditObject("doct_product_descr","product",true)
			},
			{"id":"doct_product_mes",
			"name":"Размеры продукции (одно поле)",
			"agg":false,
			"filtered":false,
			"filterControl":false
			},						
			{"id":"doct_product_mes_length",
			"name":"Длина продукции",
			"agg":false,
			"filtered":false,
			"filterControl":new EditNum(uuid())
			},
			{"id":"doct_product_mes_width",
			"name":"Ширина продукции ",
			"agg":false,
			"filtered":false,
			"filterControl":new EditNum(uuid())
			},
			{"id":"doct_product_mes_height",
			"name":"Высота продукции ",
			"agg":false,
			"filtered":false,
			"filterControl":new EditNum(uuid())
			},
							
			{"id":"doct_base_measure_descr",
			"name":"Базовая единица",
			"agg":false,
			"filtered":null,
			"filterControl":new MeasureUnitEditObject(
				"doct_base_measure_descr","base_measure_unit",true
			)
			},				

			{"id":"doct_pack_exists",
			"name":"Наличие упаковки",
			"agg":false,
			"filtered":null,
			"filterControl":new EditCheckBox(uuid())
			},
			//facts
			{"id":"doc_city_route_distance",
			"name":"Километраж доставки город",
			"filtered":false,
			"filterControl":new EditNum(uuid(),{}),
			"agg":true
			},				
			{"id":"doc_country_route_distance",
			"name":"Километраж доставки межгород",
			"filtered":false,
			"filterControl":new EditNum(uuid(),{}),
			"agg":true
			},				
			{"id":"doc_coord_count",
			"name":"Количество согласований",
			"filterControl":new EditNum(uuid(),{}),
			"agg":true
			},				
			{"id":"doc_coord_time_sale_dep",
			"name":"Время согласования ОП",
			"filterControl":new EditNum(uuid(),{}),
			"agg":true
			},				
			{"id":"doc_coord_time_client",
			"name":"Время согласования клиентом",
			"filterControl":new EditNum(uuid(),{}),
			"agg":true
			},				
			{"id":"doct_quant",
			"name":"Кол-во в базовых единицах",
			"filtered":null,
			"filterControl":new EditFloat(uuid(),{}),
			"agg":true			
			},				
			{"id":"doct_weight",
			"name":"Масса",
			"filtered":null,
			"filterControl":new EditFloat(uuid(),{}),
			"agg":true			
			},				
			{"id":"doct_volume",
			"name":"Объем",
			"filtered":null,
			"filterControl":new EditFloat(uuid(),{}),
			"agg":true			
			},							
			{"id":"doct_total",
			"name":"Стоимость продукции",
			"filtered":null,
			"filterControl":new EditMoney(uuid(),{}),
			"agg":true			
			},
			{"id":"client_debt_days",
			"name":"Просроченная дебиторская задолженность (дни)",
			"filtered":null,
			"filterControl":new EditNum(uuid(),{}),
			"agg":true
			},			
			{"id":"client_debt_total",
			"name":"Просроченная дебиторская задолженность (стоимость)",
			"filtered":null,
			"filterControl":new EditFloat(uuid(),{}),
			"agg":true
			},
			{"id":"doc_customer_survey_points",
			"name":"Баллы опроса",
			"agg":true,
			"filtered":false,
			"filterControl":new EditNum(uuid(),{})
			}			
						
	];	
	
	this.m_condFieldContainer = new RepCondFields(id+"_fields",{
		"fields":fields});
	
	this.m_groupFieldContainer = new RepGroupFields(id+"_groups",{
			"fields":fields});
	
	this.m_aggFieldContainer = new RepAggFields(id+"_agg_fields",{
		"fields":fields});
	
	var self = this;
	//вид даты
	this.m_ctrlDataType = new EditSelect(id+"_date_type",{
		labelCaption:"Вид даты:",
		contClassName:"form-group",
		editContClassName:"input-group "+get_bs_col()+"4",
		events:{
			change:function(){
				var f = self.m_ctrlDataType.getValue();				
				self.m_filter.setParamValue(self.m_ctrlPeriodFrom.getId(),"valueFieldId",f);
				self.m_filter.setParamValue(self.m_ctrlPeriodTo.getId(),"valueFieldId",f);
			}
		}
	});
	this.m_ctrlDataType.addElement(new EditSelectOption(id+"_date_type_date",{optionSelected:true,optionId:"doc_date_time",optionDescr:"Дата размещения"}));
	this.m_ctrlDataType.addElement(new EditSelectOption(id+"_date_type_deliv_date",{optionSelected:false,optionId:"doc_delivery_fact_date",optionDescr:"Дата отгрузки"}));
	
	//дата
	var period = new EditPeriodDateTime(id+"_period");
	var d_mask = "dd/mm/y hh:mmin:ss";
	this.m_ctrlPeriodFrom = period.getControlFrom();
	this.m_ctrlPeriodTo = period.getControlTo();
	
	this.m_ctrlPeriodFrom.setValue(DateHandler.dateToStr(DateHandler.getStartOfDate(new Date()),d_mask));
	this.m_ctrlPeriodTo.setValue(DateHandler.dateToStr(DateHandler.getEndOfDate(new Date()),d_mask));	
	
	//все фильтры
	this.addFilterContainer([
		//!!!*** поле зависит ОТ ТИПА ДАТЫ ***!!!!
		{"control":this.m_ctrlPeriodFrom,
		"filter":{"sign":"ge","valueFieldId":"doc_date_time"}
		},
		{"control":this.m_ctrlPeriodTo,
		"filter":{"sign":"le","valueFieldId":"doc_date_time"}
		}		
	]);
	
	
	this.m_filterContainer.addElement(this.m_ctrlDataType);
		
	this.addCmdFilter();
	this.addCmdMakeReport();
	this.addCmdPrint();
	this.addCmdExcel();
	
	this.addStoringControl(this.m_condFieldContainer);
	this.addStoringControl(this.m_groupFieldContainer);
	this.addStoringControl(this.m_aggFieldContainer);
}
extend(RepSales_View,PPViewReport);

RepSales_View.prototype.addExtraParams = function(struc){
	RepSales_View.superclass.addExtraParams.call(this,struc);	
	struc.templ="RepSales";
	//вид даты
}
