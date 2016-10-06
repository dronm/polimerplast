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
function DOCOrderBaseList_View(id,options){
	options = options || {};
	
	DOCOrderBaseList_View.superclass.constructor.call(this,
		id,options);
	
	var controller = new DOCOrder_Controller(new ServConnector(HOST_NAME));
	
	//filter
	var filter;
	if (options.filter){
		filter = new GridFilter(id+"_filter",{noUnsetControl:false});
		//CustomGridFilter
		
		//Типы заявок: текущий или все		
		filter.addFilterControl(new EditRadioGroup(id+"_filter_current_all",
			{//"labelCaption":"Показывать заявки:",
			"editContClassName":"input-group "+get_bs_col()+"3",
			"elements":[
				new EditRadio("current_all:current",{
					"descr":"Только текущие заявки",
					"value":"true",
					"name":"orders_type_filter",
					"editContClassName":"input-group "+get_bs_col()+"1",
					"attrs":{"checked":"checked"}}),
				new EditRadio("current_all:all",{
					"descr":"Все заявки",
					"value":"undefined",
					"name":"orders_type_filter",
					"editContClassName":"input-group "+get_bs_col()+"1"
					})
				]
			})
		,{"sign":"e","valueFieldId":"is_current"});
		
		
		//Номер наш
		filter.addFilterControl(new EditString(id+"_filter_number",
			{"labelCaption":"№:",
			"editContClassName":"input-group "+get_bs_col()+"1",
			"winObj":this.m_winObj,
			"tableLayout":false
			})
		,{"sign":"e","valueFieldId":"number"});
		
		//Дата подачи
		var date_time_period_ctrl = new EditPeriodDateTime(id+"_filter_date_time",{
			"labelCaptionFrom":"Период подачи:",
			"labelCaptionTo":"-",
			"tableLayout":false
			});
		filter.addFilterControl(date_time_period_ctrl.getControlFrom(),
		{"sign":"ge","valueFieldId":"date_time"});
		filter.addFilterControl(date_time_period_ctrl.getControlTo(),
		{"sign":"le","valueFieldId":"date_time"});
		
		//Количество
		filter.addFilterControl(new EditFloat(id+"_filter_total_quant",
			{"labelCaption":"Количество:",
			"editContClassName":"input-group "+get_bs_col()+"1",
			"winObj":this.m_winObj,
			"tableLayout":false,
			"precision":"4","attrs":{"maxlength":"19"}
			})
		,{"sign":"e","valueFieldId":"total_quant"});
		
		//Завод
		if (options.warehouse){			
			filter.addFilterControl(new WarehouseEditObject(
				"warehouse_id",id+"_filter_warehouse",false,null,
				{"editContClassName":"input-group "+get_bs_col()+"3"}),
			{"sign":"e","keyFieldIds":["warehouse_id"]});
		}
		
		//Статус
		if (options.state){
			filter.addFilterControl(new OrderStateEditObject("state",id+"_filter_state",false,null,
				{"editContClassName":"input-group "+get_bs_col()+"3"})
			,{"sign":"e","valueFieldId":"state"});
		}
		//Клиент
		if (options.client){			
			filter.addFilterControl(new ClientEditObject("client_id",id+"_filter_client",false,
			{"editContClassName":"input-group "+get_bs_col()+"3"})
			,{"sign":"e","keyFieldIds":["client_id"]});
		}

		//продукция
		if (options.products){			
			//ProductForFilterEditObject
			filter.addFilterControl(new ProductEditObject("product_ids",id+"_filter_products",false)
			,{"sign":"any","valueFieldId":"product_ids"});
		
			/*
			filter.addFilterControl(new ProductEditObject("product_id",id+"_filter_product",false)
			,{"sign":"e","valueFieldId":"product_id"});
			*/
		}
		
		//Номер реализ
		if (options.ext_ship_num){			
			filter.addFilterControl(new EditString(id+"_filter_ext_ship_num",
				{"labelCaption":"№ реализ.:",
				"editContClassName":"input-group "+get_bs_col()+"1",
				"winObj":this.m_winObj,
				"tableLayout":false,
				"attrs":{"maxlength":"11"}
				})
			,{"sign":"e","valueFieldId":"ext_ship_num"});
		}
		
		//Период отгрузки
		if (options.delivery_fact_date){
			var delivery_fact_date_period_ctrl = new EditPeriodDateTime(id+"_filter_delivery_fact_date",{
				"labelCaptionFrom":"Период отгрузки:",
				"labelCaptionTo":"-",
				"tableLayout":false
				});
			filter.addFilterControl(delivery_fact_date_period_ctrl.getControlFrom(),
			{"sign":"ge","valueFieldId":"delivery_fact_date"});
			filter.addFilterControl(delivery_fact_date_period_ctrl.getControlTo(),
			{"sign":"le","valueFieldId":"delivery_fact_date"});		
		}
		
		//Номер клиента
		if (options.client_number){
		}
		
		//Напечатан
		if (options.printed){
		}
		
		//Период опроса
		if (options.customer_survey_date){
			var customer_survey_date_period_ctrl = new EditPeriodDateTime(id+"_filter_cust_surv_date_time",{
				"labelCaptionFrom":"Период опроса:",
				"labelCaptionTo":"-",
				"tableLayout":false
				});
			filter.addFilterControl(customer_survey_date_period_ctrl.getControlFrom(),
			{"sign":"ge","valueFieldId":"cust_surv_date_time"});
			filter.addFilterControl(customer_survey_date_period_ctrl.getControlTo(),
			{"sign":"le","valueFieldId":"cust_surv_date_time"});				
		}

		//планируемая дата выполнения
		var plan_date_period_ctrl = new EditPeriodDateTime(id+"_filter_plan_date",{
			"labelCaptionFrom":"Период планируемых дат выполнения:",
			"labelCaptionTo":"-",
			"tableLayout":false
			});
		filter.addFilterControl(plan_date_period_ctrl.getControlFrom(),
		{"sign":"ge","valueFieldId":"delivery_plan_date"});
		filter.addFilterControl(plan_date_period_ctrl.getControlTo(),
		{"sign":"le","valueFieldId":"delivery_plan_date"});				
		
	}
		
	var head = new GridHead();
	var row = new GridRow(id+"_row1");
	row.addElement(new GridDbHeadCell(id+"_col_id",{
		"readBind":{"valueFieldId":"id"},"keyCol":true,
		"visible":false
		}));
	if (options.number){
		row.addElement(new GridDbHeadCell(id+"_col_number",{
			"value":"№","readBind":{"valueFieldId":"number"},
			"sortable":true,"colAttrs":{"align":"center"}
			}));		
	}
	row.addElement(new GridDbHeadCell(id+"_col_date_time_descr",{
		"value":"Дата пдч.","readBind":{"valueFieldId":"date_time_descr"},
		"sortable":true,"sort":"desc","sortCol":"date_time,number",
		"colAttrs":{"align":"center","nowrap":"nowrap"}
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_client_id",{
		"readBind":{"valueFieldId":"client_id"},
		"visible":false
		}));
		
	if (options.client){
		row.addElement(new GridDbHeadCell(id+"_col_client_descr",{
			"value":"Клиент","readBind":{"valueFieldId":"client_descr"},
			"sortable":true,
			"colAttrs":{"nowrap":"nowrap"}
			}));		
	}
	row.addElement(new GridDbHeadCell(id+"_col_delivery_plan_date_descr",{
		"value":"Дата вып.","readBind":{"valueFieldId":"delivery_plan_date_descr"},
		"sortable":true,"sortCol":"delivery_plan_date,number",
		"colAttrs":{"align":"center","nowrap":"nowrap"}
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_products_descr",{
		"value":"Продукция","readBind":{"valueFieldId":"products_descr"},
		"colAttrs":{"style":"max-width:5px;overflow:hidden;","nowrap":"nowrap"}
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_total_volume",{
		"value":"Кол-во,м3","readBind":{"valueFieldId":"total_quant"},
		"sortable":true,
		"colAttrs":{"align":"center"}
		}));
	if (options.warehouse){
		row.addElement(new GridDbHeadCell(id+"_col_warehouse_descr",{
			"value":"Завод","readBind":{"valueFieldId":"warehouse_descr"},
			"sortable":true,"sortCol":"warehouse_id,number",
			"colAttrs":{"nowrap":"nowrap"}
			}));				
	}
	row.addElement(new GridDbHeadCell(id+"_col_address_descr",{
		"value":"Адрес доставки","readBind":{"valueFieldId":"address_descr"},
		"sortable":true,"sortCol":"address_descr",
		"colAttrs":{"nowrap":"nowrap"}
		}));
		
	if (options.total){	
		row.addElement(new GridDbHeadCell(id+"_col_total",{
			"value":"Сумма","readBind":{"valueFieldId":"total"},
			"colAttrs":{"align":"right"}
			}));				
	}
		
	if (options.state){		
		row.addElement(new GridDbHeadCell(id+"_col_state_descr",{
			"value":"Статус","readBind":{"valueFieldId":"state_descr"},
			"sortable":true,"sortCol":"state,number",
			"colAttrs":{"align":"center","style":"max-width:5px;overflow:hidden;","nowrap":"nowrap"}
			}));				
	}
	if (options.paid){		
		row.addElement(new GridDbHeadCellBool(id+"_col_paid",{
			"value":"Оплач.","readBind":{"valueFieldId":"paid"}
			}));				
	}
	row.addElement(new GridDbHeadCell(id+"_col_state",{
		"readBind":{"valueFieldId":"state"},
		"fieldValueToRowClass":true,"visible":false
		}));				
	row.addElement(new GridDbHeadCell(id+"_col_behind_plan",{
		"readBind":{"valueFieldId":"behind_plan"},
		"fieldValueToRowClass":true,"visible":false
		}));				
	
	if (options.ext_ship_num){
		row.addElement(new GridDbHeadCell(id+"_col_ext_order_num",{
			"value":"№ сч.","readBind":{"valueFieldId":"ext_order_num"},
			"sortable":true,
			"colAttrs":{"align":"center"}
			}));					
		
		row.addElement(new GridDbHeadCell(id+"_col_ext_ship_num",{
			"value":"№ реал.","readBind":{"valueFieldId":"ext_ship_num"},"sortable":true,
			"colAttrs":{"align":"center"}
			}));					
	}
	if (options.delivery_fact_date){
		row.addElement(new GridDbHeadCell(id+"_col_delivery_fact_date_descr",{
			"value":"Дата факт.отгр.","readBind":{"valueFieldId":"delivery_fact_date_descr"},
			"sortable":true,
			"colAttrs":{"align":"center","nowrap":"nowrap"}
			}));					
	}
	if (options.client_number){
		row.addElement(new GridDbHeadCell(id+"_col_client_number",{
			"value":"№ внутр.","readBind":{"valueFieldId":"client_number"},
			"sortable":true,
			"colAttrs":{"align":"center"}
			}));					
	}
	if (options.printed){
		row.addElement(new GridDbHeadCellBool(id+"_col_printed",{
			"value":"Печ.","readBind":{"valueFieldId":"printed"},
			"sortable":true,
			"colAttrs":{"align":"center"}
			}));					
	}
	if (options.customer_survey_date){
		row.addElement(new GridDbHeadCellBool(id+"_col_cust_surv_date_time_descr",{
			"value":"Опрос","readBind":{"valueFieldId":"cust_surv_date_time_descr"},
			"sortable":true,"sortCol":"cust_surv_date_time",
			"colAttrs":{"align":"center"}
			}));					
	}
	if (options.submit_user){
		row.addElement(new GridDbHeadCellBool(id+"_col_submit_user_descr",{
			"value":"Ответств.","readBind":{"valueFieldId":"submit_user_descr"}			
			}));					
	}
	
	head.addElement(row);
	
	this.m_grid=new DOCOrderGridDb(id+"_grid",
		{"head":head,
		"body":new GridBody(),
		"className":"orders",
		"controller":controller,
		"readMethodId":options.readMethodId,
		"readModelId":options.readModelId,
		"editViewClass":DOCOrderDialog_View,
		"editInline":false,
		
		"pagination":options.pagination,
		
		"commandPanel":options.commands,
		"rowCommandPanelClass":null,
		"filter":filter,
		"refreshInterval":options.refreshInterval,
		"noAutoRefresh":options.noAutoRefresh,
		"onSelect":options.onSelect,
		"rowSelect":true,
		"fieldValueToRowClass":"state",
		//"editWinClass":WIN_CLASS,
		"fixedHeader":true
		}
	);
	
	//this.m_customCommands = new ControlContainer(uuid(),"div");
	//this.addElement(this.m_customCommands);	
	this.addElement(this.m_grid);
}
extend(DOCOrderBaseList_View,ViewList);
