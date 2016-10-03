/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
 * @requires controls/ViewDialog.js
*/

/* constructor */
function DOCOrderDialog_View(id,options){
	options = options || {};
	options.tagName="div";
	
	var self = this;	
	this.m_beforeOpen = function(contr,isInsert,isCopy){
		var doc_id = 0;
		//&&!isCopy
		if (!isInsert){
			doc_id = self.getDataControl(self.getId()+"_id").control.getValue();
		}
		contr.run("before_open",{async:false,
			params:{"doc_id":doc_id}
		});
	}
	
	if (SERV_VARS.ROLE_ID=="sales_manager" || SERV_VARS.ROLE_ID=="admin"){
		this.m_downloadPrintCtrl = new ButtonCmd(id+"btnCancel",
				{"caption":"Счет",
				"enabled":false,
				"onClick":function(){
					self.onDownloadOrder();
				},
				"attrs":{
					"title":"сохранить печатную форму счета"}
			}
		);
		options.cmdControls = [this.m_downloadPrintCtrl];
	}
	else if (SERV_VARS.ROLE_ID=="production"){
		this.m_passToProdCtrl = new BtnPassToProduction({
			"grid":null,
			"className":"btn btn-primary btn-cmd",
			"enabled":false});
		options.cmdControls = [this.m_passToProdCtrl];	
	}
		
	DOCOrderDialog_View.superclass.constructor.call(this,
		id,options);
	
	var model_id = "DOCOrderDialog_Model";
	
	//events
	this.m_evClientUserSelected = function(){
		self.onClientUserSelected();
	};
	this.m_evWarehouseSelected = function(){
		self.onWarehouseSelected();
	};
	this.m_evFirmSelected = function(){
		self.onFirmSelected();
	};	
	this.m_evDelivDestSelected= function(){
		self.calcDelivCost();
	};	
	this.m_evDelivCostOptSelected= function(){
		self.calcDelivCost();
	};	
	
	this.addDataControl(
		new Edit(id+"_id",{"visible":false,"name":"id","tableLayout":false}),
		{"modelId":model_id,
		"valueFieldId":"id",
		"keyFieldIds":null},
		{"valueFieldId":"id","keyFieldIds":null}
	);
	
	var cont_r;
	var bs_col = get_bs_col();
	
	//статус+причина отмены+подчин
	cont_r = new ControlContainer(uuid(),"div",{"className":"row"});
	
	//статус
	var ctrl_cont = new ControlContainer(uuid(),"div",{"className":bs_col+12});
	var ctrl = new Control(uuid(),"span",{"value":"Статус:","className":bs_col+2});
	ctrl_cont.addElement(ctrl);	
	var ctrl = new Control(id+"_state","span",
		{"value":"Формирование новой заявки",
		"className":"old_state_descr","enabled":"false"
		}
	);		
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"state_descr","keyFieldIds":null},
		{"valueFieldId":null,"keyFieldIds":null});	
	ctrl_cont.addElement(ctrl);		
	cont_r.addElement(ctrl_cont);	

	//причина отмены
	var ctrl_cont = new ControlContainer(uuid(),"div",{"className":bs_col+4});
	this.m_cancelCauseCtrl = new DOCOrderCancelCause("DocOrderCancelCause");
	ctrl_cont.addElement(this.m_cancelCauseCtrl);	
	cont_r.addElement(ctrl_cont);	
	
	//подч
	var ctrl_cont = new ControlContainer(uuid(),"div",{"className":bs_col+4});
	this.m_childCtrl = new DocOrderChildren("DocOrderChildrenList");
	ctrl_cont.addElement(this.m_childCtrl);
	
	this.addElement(cont_r);	

	//Фирма + клиент
	cont_r = new ControlContainer(uuid(),"div",{"className":"row"});
	
	//****** Панель ФИРМА ***************
	var cont = new ControlContainer(id+"_firm_panel","div",{
		"className":(bs_col + ((SERV_VARS.ROLE_ID=="client")? "12":"6"))
		});
	
	if (SERV_VARS.ROLE_ID!="client"){
		var ctrl = new EditNum(id+"_number",
			{"labelCaption":"Номер:",
			"name":"number",
			"tableLayout":false,
			"buttonClear":false,
			"attrs":{"maxlength":5,"size":5,
			"disabled":"disabled","required":"required"}}
		);
		this.bindControl(ctrl,
			{"modelId":model_id,"valueFieldId":"number","keyFieldIds":null},
			{"valueFieldId":null,"keyFieldIds":null});	
		cont.addElement(ctrl);
	}		
	else{
		//Номер клиента
		var ctrl = new EditString(id+"_client_number",
			{"labelCaption":"№ внутренний:",
			"name":"client_number",
			"tableLayout":false,
			"buttonClear":false,
			"attrs":{"maxlength":12,"size":5}}
		);
		this.bindControl(ctrl,
			{"modelId":model_id,"valueFieldId":"client_number","keyFieldIds":null},
			{"valueFieldId":"client_number","keyFieldIds":null});	
		cont.addElement(ctrl);		
	}
	
	//Планируемая дата выполнения
	var ctrl = new EditDate(id+"_delivery_plan_date",
		{"labelCaption":"Планируемая дата выполнения:","name":"delivery_plan_date",
		"editContClassName":"input-group "+get_bs_col()+"6",
		"buttonClear":false,"tableLayout":false,
		"attrs":{"required":"required"}}
	);
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"delivery_plan_date_descr","keyFieldIds":null},
		{"valueFieldId":"delivery_plan_date","keyFieldIds":null});	
	cont.addElement(ctrl);

	var firm_wh_vis = (SERV_VARS.ROLE_ID!="client");
	//Организация
	this.m_FirmCtrl = new FirmEditObject("firm_id",id+"_firm",false,
		null,{visible:firm_wh_vis,required:firm_wh_vis});
	this.bindControl(this.m_FirmCtrl,
		{"modelId":model_id,"valueFieldId":"firm_descr","keyFieldIds":["firm_id"]},
		{"valueFieldId":null,"keyFieldIds":["firm_id"]});	
	cont.addElement(this.m_FirmCtrl);
		
	//Склад из списка складов продукции
	this.m_wareHCtrl = new OrderWarehouseEditObject("warehouse_id",id+"_warehouse",false,
		null,{visible:firm_wh_vis});
	this.bindControl(this.m_wareHCtrl,
		{"modelId":model_id,"valueFieldId":"warehouse_descr","keyFieldIds":["warehouse_id"]},
		{"valueFieldId":null,"keyFieldIds":["warehouse_id"]});	
	cont.addElement(this.m_wareHCtrl);
	
	cont_r.addElement(cont);
	
	
	//****** Панель Заказчик ***************			
	if (SERV_VARS.ROLE_ID!="client"){
		var cont = new ControlContainer(id+"_client_panel","div",{"className":bs_col+"6"});		
		//Заказчик
		this.m_clientCtrl = new ClientEditObject("client_id",id+"_client",false,{
			"noOpen":true,
			"onSelected":function(){
				self.onClientSelected();
			},
			"extraFields":["def_firm_id","def_warehouse_id","def_debt","debt_total"],
			"winObj":options.winObj
			});
		this.bindControl(this.m_clientCtrl,
			{"modelId":model_id,"valueFieldId":"client_descr","keyFieldIds":["client_id"]},
			{"valueFieldId":null,"keyFieldIds":["client_id"]});	
		cont.addElement(this.m_clientCtrl);
			
		//ответственный
		this.m_clientUserCtrl = new ClientUserEditObject("client_user_id",id+"_client_user",false);
		this.bindControl(this.m_clientUserCtrl,
			{"modelId":model_id,"valueFieldId":"client_user_descr","keyFieldIds":["client_user_id"]},
			{"valueFieldId":null,"keyFieldIds":["client_user_id"]});	
		cont.addElement(this.m_clientUserCtrl);	
	
		//Телефон ответственного	
		this.m_ClientUserTelCtrl = new EditString(id+"_client_user_tel",
			{"labelCaption":"Телефон:",
			"name":"client_user_tel",
			"buttonClear":false,
			"tableLayout":false,
			"attrs":{"disabled":"disabled","value":"<не задан сотрудник>"}}
		);		
		this.bindControl(this.m_ClientUserTelCtrl,
			{"modelId":model_id,"valueFieldId":"client_user_cel_phone","keyFieldIds":null},
			{"valueFieldId":null,"keyFieldIds":null});	
		cont.addElement(this.m_ClientUserTelCtrl);	
		
		//Долги
		this.m_debtInfCtrl = new Control(id+"_debt_inf","div",
			{"name":"debt_inf"}
		);		
		cont.addElement(this.m_debtInfCtrl);

		//Прсроч.долги
		this.m_defDebtInfCtrl = new Control(id+"_def_debt_inf","div",
			{"name":"debt_inf"}
		);		
		cont.addElement(this.m_defDebtInfCtrl);
		
		cont_r.addElement(cont);
	}
	
	this.addElement(cont_r);
	
	// *********** ПРОДУКЦИЯ *********
	this.m_details = new ControlContainer(uuid(),"div",{"className":"row"});
	
	this.m_productDetails = new DOCOrderDOCTProductList_View("DOCOrderDOCTProductList",
		{"connect":new ServConnector(HOST_NAME),
		"errorControl":this.getErrorControl(),
		"warehouseCtrl":this.m_wareHCtrl,
		"afterRefresh":function(){
			self.refreshProdTotals();
		}
		});
	this.m_details.addElement(this.m_productDetails);	
	this.addElement(this.m_details);
	
	//Итоговая строка
	var cont = new ControlContainer(uuid(),"div",{"className":"row DOCOrderTot"});
	
	var sub_cont = new ControlContainer(uuid(),"div",{className:bs_col+"6"});
	//Объем
	sub_cont.addElement(new Control(uuid(),"span",{"value":"Транспортировочный объем: "}));
	this.m_totVol = new Control(uuid(),"span");
	sub_cont.addElement(this.m_totVol);
	sub_cont.addElement(new Control(uuid(),"span",{"value":" м3"}));
	//масса
	sub_cont.addElement(new Control("prod_tot_w","span",{"value":", масса груза: "}));
	this.m_totWt = new Control("prod_tot_wt","span");
	sub_cont.addElement(this.m_totWt);
	sub_cont.addElement(new Control("prod_tot_wt2","span",{"value":" т."}));
	cont.addElement(sub_cont);
	
	//Сумма+++
	var sub_cont = new ControlContainer(uuid(),"div",{"className":bs_col+"6 text-right"});
	
	//Сумма
	var sub_s_cont = new ControlContainer(uuid(),"div");
	sub_s_cont.addElement(new Control("prod_tot_r1","span",{"value":"Итого: "}));
	this.m_totSum = new Control("prod_tot_r2","span");
	sub_s_cont.addElement(this.m_totSum);
	sub_s_cont.addElement(new Control("prod_tot_r3","span",{"value":" руб."}));
	sub_cont.addElement(sub_s_cont);
	
	//Упаковка
	var sub_s_cont = new ControlContainer(uuid(),"div");
	sub_s_cont.addElement(new Control("prod_totp_r1","span",{"value":"Итого упаковка: "}));
	this.m_totSumPack = new Control("prod_totp_r2","span");
	sub_s_cont.addElement(this.m_totSumPack);
	sub_s_cont.addElement(new Control("prod_totp_r3","span",{"value":" руб."}));
	sub_cont.addElement(sub_s_cont);	
	
	//Сумма+ТР+Упаковка
	var sub_s_cont = new ControlContainer(uuid(),"div");
	sub_s_cont.addElement(new Control("prod_tot_r4","span",{"value":"Итого с доставкой и упак.: "}));
	this.m_totTotal = new Control("prod_tot_r5","span");
	sub_s_cont.addElement(this.m_totTotal);
	sub_s_cont.addElement(new Control("prod_tot_r6","span",{"value":" руб."}));
	sub_cont.addElement(sub_s_cont);
	
	cont.addElement(sub_cont);
	
	this.addControl(cont);
	
	//Адрес доставки
	var cont = new ControlContainer(uuid(),"div",{"className":"row"});
	this.m_clientDestCtrl = new ClientDestinationEdit(id+"_deliv_destination",{
		"fieldId":"deliv_destination_id",
		"enabled":(SERV_VARS.ROLE_ID=="client"),
		"winObj":options.winObj});
	this.bindControl(this.m_clientDestCtrl,
		{"modelId":model_id,"valueFieldId":"deliv_destination_descr","keyFieldIds":["deliv_destination_id"]},
		{"valueFieldId":null,"keyFieldIds":["deliv_destination_id"]});	
	cont.addElement(this.m_clientDestCtrl);		
	
	//Адрес в ТТН
	var ctrl = new EditCheckBox(id+"_destination_to_ttn",
		{"labelCaption":"Переносить адрес доставки в ТТН","name":"destination_to_ttn",
		"buttonClear":false,"labelAlign":"left",
		"tableLayout":false,
		"attrs":{}}
	);		
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"destination_to_ttn","keyFieldIds":null},
		{"valueFieldId":"destination_to_ttn","keyFieldIds":null});	
	cont.addElement(ctrl);	
	
	
	this.addControl(cont);
	
	
	//****** Панель Доставка ***************
	var cont = new ControlContainer(id+"_deliv_panel","div",{"className":"row"});
	
	var sub_cont = new ControlContainer(uuid(),"div",{"className":bs_col+"6"});
	
	//Вид доставки
	this.m_delivTypeCtrl = new DelivTypeEditObject({
		"fieldId":"deliv_type",
		"controlId":id+"_deliv_type",
		"inLine":false,
		"options":{"events":{"change":function(){
			self.changeDelivType();
		}}},
	});
	this.bindControl(this.m_delivTypeCtrl,
		{"modelId":model_id,"valueFieldId":"deliv_type_descr","keyFieldIds":["deliv_type"]},
		{"valueFieldId":null,"keyFieldIds":["deliv_type"]});	
	sub_cont.addElement(this.m_delivTypeCtrl);	
	
	//Доставка третьему лицу
	this.m_toThirdPartyCtrl = new EditCheckBox(id+"_deliv_to_third_party",
		{"labelCaption":"Доставка третьему лицу","name":"deliv_to_third_party",
		"buttonClear":false,"labelAlign":"left",
		"tableLayout":false,
		"events":{"change":function(){
			self.setToThirdParty(
				self.m_toThirdPartyCtrl.getValue()
			);
			self.recalcProductPrices();
		}},
		"attrs":{}}
	);		
	this.bindControl(this.m_toThirdPartyCtrl,
		{"modelId":model_id,"valueFieldId":"deliv_to_third_party","keyFieldIds":null},
		{"valueFieldId":"deliv_to_third_party","keyFieldIds":null});	
	sub_cont.addElement(this.m_toThirdPartyCtrl);	
		
	//Период доставки
	this.m_DelivPeriodCtrl = new DeliveryPeriodEditObject("deliv_period",id+"_deliv_period",false,3);
	this.bindControl(this.m_DelivPeriodCtrl,
		{"modelId":model_id,"valueFieldId":"deliv_period_descr","keyFieldIds":["deliv_period_id"]},
		{"valueFieldId":null,"keyFieldIds":["deliv_period_id"]});	
	sub_cont.addElement(this.m_DelivPeriodCtrl);	
	
	//Ответственный
	var ctrl = new EditString(id+"_deliv_responsable",
		{"labelCaption":"Ответственный за прием товара:","name":"deliv_responsable",
		"buttonClear":false,"tableLayout":false,
		"attrs":{"maxlength:":"50","size":"25"}}
	);		
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"deliv_responsable","keyFieldIds":null},
		{"valueFieldId":"deliv_responsable","keyFieldIds":null});	
	sub_cont.addElement(ctrl);	
	
	//Ответственный телефон
	var ctrl = new EditCellPhone(id+"_deliv_responsable_tel",
		{"labelCaption":"Телефон:","name":"_deliv_responsable_tel",
		"tableLayout":false,"attrs":{}}
	);		
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"deliv_responsable_tel","keyFieldIds":null},
		{"valueFieldId":"deliv_responsable_tel","keyFieldIds":null});	
	sub_cont.addElement(ctrl);	

	//Городской телефон
	var ctrl = new EditString(id+"_tel",
		{"labelCaption":"Телефон:","name":"_tel",		
		"tableLayout":false,"attrs":{"maxlength":"15"}}
	);		
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"tel","keyFieldIds":null},
		{"valueFieldId":"tel","keyFieldIds":null});	
	sub_cont.addElement(ctrl);	
	
	cont.addElement(sub_cont);	
	
	var sub_cont = new ControlContainer(uuid(),"div",{"className":bs_col+"6"});
	
	//Уведомлять
	var ctrl = new EditCheckBox(id+"_deliv_send_sms",
		{"labelCaption":"Уведомлять перед отгрузкой","name":"deliv_send_sms",
		"tableLayout":false,"buttonClear":false,"labelAlign":"left",
		"attrs":{}}
	);		
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"deliv_send_sms","keyFieldIds":null},
		{"valueFieldId":"deliv_send_sms","keyFieldIds":null});	
	sub_cont.addElement(ctrl);	

	//АВТОТРАНСПОРТ
	this.m_delivCostOptCtrl = new DelivCostOptEdit({
		"fieldId":"deliv_cost_opt_id",
		"controlId":id+"_deliv_cost_opt",
		"inLine":false,
		"options":{"events":{
			"change":function(){
				self.calcVehicleCount();
			}
		}}
		});		
	this.bindControl(this.m_delivCostOptCtrl,
		{"modelId":model_id,"valueFieldId":"deliv_cost_opt_descr","keyFieldIds":["deliv_cost_opt_id"]},
		{"valueFieldId":null,"keyFieldIds":["deliv_cost_opt_id"]});	
	sub_cont.addElement(this.m_delivCostOptCtrl);	
	
	//расстояние
	this.m_cityRouteDistanceCtrl = new Control(id+"_city_route_distance",
			"div",{"name":"city_route_distance",
			"visible":false}
		);	
	this.bindControl(this.m_cityRouteDistanceCtrl,
		{"modelId":model_id,"valueFieldId":"city_route_distance","keyFieldIds":null},
		{"valueFieldId":"city_route_distance","keyFieldIds":null});	
	sub_cont.addElement(this.m_cityRouteDistanceCtrl);	
	this.m_countryRouteDistanceCtrl = new Control(id+"_country_route_distance",
			"div",{"name":"country_route_distance",
			"visible":false}
		);	
	this.bindControl(this.m_countryRouteDistanceCtrl,
		{"modelId":model_id,"valueFieldId":"country_route_distance","keyFieldIds":null},
		{"valueFieldId":"country_route_distance","keyFieldIds":null});	
	sub_cont.addElement(this.m_countryRouteDistanceCtrl);	
	
	this.m_delivVehicleCntCtrl = new EditNum(id+"_deliv_vehicle_count",
			{"labelCaption":"Кол-во автомоб.:",
			"name":"deliv_vehicle_count",
			"tableLayout":false,
			"buttonClear":false,
			"attrs":{"maxlength":5,"size":5,
			"required":"required"}}
		);	
	this.bindControl(this.m_delivVehicleCntCtrl,
		{"modelId":model_id,"valueFieldId":"deliv_vehicle_count","keyFieldIds":null},
		{"valueFieldId":"deliv_vehicle_count","keyFieldIds":null});	
	sub_cont.addElement(this.m_delivVehicleCntCtrl);	
	
	//Стоимость доставки
	this.m_delivCostComment = new Control(id+"_deliv_comment","div",{});
	sub_cont.addElement(this.m_delivCostComment);		
	
	var opts={"labelCaption":"Стоимость доставки (руб.):",
		"name":"deliv_total",
		"tableLayout":false};	
	var ctrl_class;
	if (SERV_VARS.ROLE_ID=="client"){
		ctrl_class = EditFloat;
		opts.enabled=false;
	}
	else{
		ctrl_class = EditMoneyEditable;
		var ctrl_edit = new Control(id+"_deliv_total_edit","div",{
			"name":"deliv_total_edit",
			"tableLayout":false,
			"visible":false,
			"value":"false"});
		this.bindControl(ctrl_edit,
			{"modelId":model_id,"valueFieldId":"deliv_total_edit","keyFieldIds":null},
			{"valueFieldId":"deliv_total_edit","keyFieldIds":null});	
		sub_cont.addElement(ctrl_edit);		
		opts.editAllowedFieldCtrl = ctrl_edit;
	}
	opts.events={
		"change":function(){
			/*Пересчет только если от этого зависит тз*/
			if (self.m_delivAddToCostCtrl.getValue()=="true"){
				self.recalcProductPrices();
			}
			self.refreshProdTotals();
		}
	};	
	this.m_delivCost = new ctrl_class(id+"_deliv_total",
		opts);		
	this.bindControl(this.m_delivCost,
		{"modelId":model_id,"valueFieldId":"deliv_total","keyFieldIds":null},
		{"valueFieldId":"deliv_total","keyFieldIds":null});	
	sub_cont.addElement(this.m_delivCost);	
	
	//Включать стоимость доставки
	this.m_delivAddToCostCtrl = new EditCheckBox(id+"_deliv_add_cost_to_product",
		{"labelCaption":"Включать стоимость доставки пропорцилнально в стоимость продукции","name":"deliv_add_cost_to_product",
		"buttonClear":false,"labelAlign":"left",
		"tableLayout":false,		
		"attrs":{},
		"events":{
			"change":function(){
				self.recalcProductPrices();
			}
		}
		}
	);		
	this.bindControl(this.m_delivAddToCostCtrl,
		{"modelId":model_id,"valueFieldId":"deliv_add_cost_to_product","keyFieldIds":null},
		{"valueFieldId":"deliv_add_cost_to_product","keyFieldIds":null});	
	sub_cont.addElement(this.m_delivAddToCostCtrl);	
	
	cont.addElement(sub_cont);
	
	this.addElement(cont);
	
	
	//****** Панель Комментариев ***************
	var com_p_id = uuid();
	var cont = new ControlContainer(com_p_id,"div",{"className":"collapse in"});
	//Комментарий наш
	if (SERV_VARS.ROLE_ID!="client"){
		this.addElement(new ButtonToggle(uuid(),{
			"caption":"комментарии",
			"dataTarget":com_p_id,
			"expanded":true,
			"attrs":{								
				"title":"показать/скрыть комментарий"				
				}
			}));
		
		var ctrl = new EditString(id+"_sales_manager_comment",
			{"labelCaption":"Комментарий внутр.:","name":"sales_manager_comment",
			"buttonClear":false,"tableLayout":false,
			"attrs":{"size":"80"}}
		);		
		this.bindControl(ctrl,
			{"modelId":model_id,"valueFieldId":"sales_manager_comment","keyFieldIds":null},
			{"valueFieldId":"sales_manager_comment","keyFieldIds":null});	
		cont.addElement(ctrl);		
	}
	
	//Комментарий
	var ctrl = new EditString(id+"_client_comment",
		{"labelCaption":"Комментарий:","name":"client_comment",
		"buttonClear":false,"tableLayout":false,
		"attrs":{"size":"80"}}
	);		
	this.bindControl(ctrl,
		{"modelId":model_id,"valueFieldId":"client_comment","keyFieldIds":null},
		{"valueFieldId":"client_comment","keyFieldIds":null});	
	cont.addElement(ctrl);	
	
	this.addControl(cont);
}
extend(DOCOrderDialog_View,ViewDialog);

DOCOrderDialog_View.prototype.m_detailContainer;
/*
DOCOrderDialog_View.prototype.addDetailControl = function(detailControl,panel){
	if (this.m_details==undefined){
		var detail_row = new ControlContainer(this.getId()+"_det_row","tr");
		var td = new ControlContainer(this.getId()+"_det_row","td",{"attrs":{"colspan":"2"}});
		detail_row.addElement(td);
		this.m_details = new ControlContainer(this.getId()+"_details","div",{"attrs":{"class":"tabber"}});
		td.addElement(this.m_details);
		panel.addElement(this.m_details);
	}
	this.m_details.addElement(detailControl);
}
*/
DOCOrderDialog_View.prototype.getDetailControl = function(controlId){
	return this.m_details.getElementById(controlId);
}
DOCOrderDialog_View.prototype.readData = function(async,isCopy){
	DOCOrderDialog_View.superclass.readData.call(this,false,isCopy);
}
DOCOrderDialog_View.prototype.onClickSave = function(){
	var cmd_insert = this.getIsNew();
	if (cmd_insert){
		//need return new serial id if any
		var contr = this.getWriteController();
		var meth_id = this.getWriteMethodId();
		var pm = contr.getPublicMethodById(meth_id);
		if (pm.paramExists(contr.PARAM_RET_ID)){
			pm.setParamValue(contr.PARAM_RET_ID,1);
		}
	}
	this.writeData();
	this.readData(false);
}

DOCOrderDialog_View.prototype.setDebts = function(debtTotal,defDebt){	
	if (!isNaN(debtTotal) && debtTotal!=0){		
		this.m_debtInfCtrl.setValue(( (defDebt>0)? "Долг контрагента ":"Наш долг ") + numberFormat( debtTotal,2,",", "'")+" руб.");
		DOMHandler.setAttr(this.m_debtInfCtrl.getNode(),"class", (defDebt>0)? "text-danger":"text-info");
		
		if (!isNaN(defDebt) && defDebt){
			this.m_defDebtInfCtrl.setValue("Просроченный долг "+numberFormat( defDebt,2,",", "'")+" руб.");
			DOMHandler.setAttr(this.m_defDebtInfCtrl.getNode(),"class","text-danger");
		}
		else{
			DOMHandler.setAttr(this.m_defDebtInfCtrl.getNode(),"class","hidden");
		}
	}
	else{
		DOMHandler.setAttr(this.m_defDebtInfCtrl.getNode(),"class","hidden");
		DOMHandler.setAttr(this.m_debtInfCtrl.getNode(),"class","hidden");
	}
}

DOCOrderDialog_View.prototype.setClientId = function(clientId,setPopFirm){	
	//client user
	this.setClientUserTel("");	
	if (this.m_clientUserCtrl){
		this.m_clientUserCtrl.setClientId(clientId);
		this.m_clientUserCtrl.onRefresh();
	}
	
	//Адреса клиента
	this.m_clientDestCtrl.setClientId(clientId);
	this.m_clientDestCtrl.onRefresh();
	
	//Табличная часть
	this.m_productDetails.setClientId(clientId);
	
	
	//Взаиморасчеты
	this.setDebts(parseFloat(this.m_clientCtrl.getAttr("debt_total")), parseFloat(this.m_clientCtrl.getAttr("def_debt")));
		
	//значения по умолчанию
	var def_firm = this.m_clientCtrl.getAttr("def_firm_id");
	var def_warehouse = this.m_clientCtrl.getAttr("def_warehouse_id");
	if (def_firm && def_firm!="null"){
		this.m_FirmCtrl.setFieldId("firm_id",def_firm);
	}
	if (def_warehouse && def_warehouse!="null"){
		this.m_wareHCtrl.setFieldId("warehouse_id",def_warehouse);
	}
	
	//организация
	if (setPopFirm && this.m_FirmCtrl.getFieldValue()){	
		//console.log("setPopFirm="+setPopFirm);
		var self = this;
		var contr = new Client_Controller(new ServConnector(HOST_NAME));
		contr.run("get_pop_firm",{
			"params":{"client_id":clientId},
			"errControl":this.getErrorControl(),
			"func":function(resp){
				var m = resp.getModelById("get_pop_firm");
				m.setActive(true);
				if (m.getNextRow()){
					self.m_FirmCtrl.setValue(m.getFieldValue('firm_descr'));
					self.m_FirmCtrl.setFieldValue("id",m.getFieldValue('firm_id'));
					self.onFirmSelected();
				}
			}
		});
	}
}
DOCOrderDialog_View.prototype.onClientSelected = function(setPopFirm){	
	var id = 0;
	if (this.m_clientCtrl){
		id = this.m_clientCtrl.getAttr("fkey_client_id");
		//Доступность тч
		//this.m_productDetails.setEnabled(true);
	}
	this.setClientId(id,setPopFirm);
}
DOCOrderDialog_View.prototype.setClientUserTel = function(tel){
	if (this.m_ClientUserTelCtrl){
		this.m_ClientUserTelCtrl.setValue(tel);
	}
}
DOCOrderDialog_View.prototype.onClientUserSelected = function(){
	var ctrl = this.getDataControl(this.getId()+"_client_user").control;	
	this.setClientUserTel(ctrl.getFieldAttr("cel_phone"));
}
DOCOrderDialog_View.prototype.setToThirdParty = function(v){
	this.m_productDetails.setToThirdParty(v);
}
/*
DOCOrderDialog_View.prototype.setDelivAddToCost = function(v){
	this.m_productDetails.setDelivAddToCost(v);
}
DOCOrderDialog_View.prototype.setDelivTotal = function(v){
	this.m_productDetails.setDelivTotal(v);
}
*/
DOCOrderDialog_View.prototype.setWarehouseId = function(id){
	this.m_productDetails.setWarehouseId(id);
}
DOCOrderDialog_View.prototype.onWarehouseSelected = function(){
	this.setWarehouseId(this.m_wareHCtrl.getFieldValue());
	this.calcDelivCost();
}
DOCOrderDialog_View.prototype.onFirmSelected = function(){
	var wh = this.m_wareHCtrl.getFieldValue();
	if (!wh){
		var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
		var self = this;
		contr.run("get_pop_warehouse",{
			"params":{"firm_id":this.m_FirmCtrl.getFieldValue()},
			"errControl":this.getErrorControl(),
			"func":function(resp){
				var m = resp.getModelById("get_pop_warehouse");
				m.setActive(true);
				if (m.getNextRow()){
					self.m_wareHCtrl.setValue(m.getFieldValue('warehouse_descr'));
					self.m_wareHCtrl.setFieldValue("id",m.getFieldValue('warehouse_id'));
					self.onWarehouseSelected();
				}
			}
		});
	}
}

DOCOrderDialog_View.prototype.onGetData = function(resp){
	DOCOrderDialog_View.superclass.onGetData.call(this,resp,this.m_isCopy);
	var id = this.getId();
	
	if (SERV_VARS.ROLE_ID!="client"){
		this.onClientSelected(false);
		var data_ctrl = this.getDataControl(id+"_client_user");
		if (data_ctrl){
			var ctrl = data_ctrl.control;
			ctrl.setFieldValue("client_user_id",ctrl.getAttr("fkey_client_user_id"));
			this.onClientUserSelected();
		}
	}
	else{
		this.setClientId(0,false);
	}
	//расстояние
	this.updateDistanceInf();
	
	//История изменений
	
	this.changeDelivType();
	
	//голова
	if (this.m_isCopy){
		//копирование		
	}
	else{
		var m = resp.getModelById("head_history");
		m.setActive(true);
		while (m.getNextRow()){
			var f_id = m.getFieldValue("field");
			var p = f_id.indexOf("_id");
			if (p>=0){
				f_id = f_id.substring(0,p)
			}
			var ctrl = this.getDataControl(id+"_"+f_id);
			if (ctrl && ctrl.control && ctrl.control.m_node){
				DOMHandler.addClass(ctrl.control.m_node,"field_changed");
				ctrl.control.setAttr("old_field_val",m.getFieldValue("old_val"));
			}
			
		}
		/*Проверим статус и закроем доступность на редактирование*/
		var m = resp.getModelById("DOCOrderDialog_Model",true);
		if (m.getNextRow()){
			this.m_curState = m.getFieldValue("state");
			var read_only_states=[
				"producing",
				"produced",
				"shipped",
				"loading",
				"on_way",
				"unloading",
				"closed",
				"canceled",
				"canceled_by_sales_manager",
				"canceled_by_client"
				];
			if (SERV_VARS.ROLE_ID=="client"){
				read_only_states.push("waiting_for_us");
			}
			else{
				read_only_states.push("waiting_for_client");
			}
			
			//if (read_only_states.indexOf(this.m_curState)>=0){
			if ($.inArray(this.m_curState,read_only_states)>=0){
				this.setEnabled(false);
				this.m_ctrlCancel.setEnabled(true);
				this.m_ctrlOk.setEnabled(true);								
				this.getViewControl(this.getId()+"_delivery_plan_date").setEnabled(true);
				if (SERV_VARS.ROLE_ID!="client"){
					this.getViewControl(this.getId()+"_sales_manager_comment").setEnabled(true);
				}
				this.getViewControl(this.getId()+"_deliv_responsable_tel").setEnabled(true);
				this.getViewControl(this.getId()+"_tel").setEnabled(true);				
			}
			else{
				
				var new_cap;
				if (SERV_VARS.ROLE_ID=="client"){
					if (this.m_curState=="waiting_for_client"){
						new_cap = "Согласовать";
					}
					else{
						new_cap = "Изменить";
					}
				}
				else{
					if (this.m_curState=="waiting_for_us"
					||this.m_curState=="new"){
						new_cap = "Согласовать";
					}
					else{
						new_cap = "Изменить";
					}				
				}
				
				if (new_cap){
					this.m_ctrlOk.setCaption(new_cap);
				}
				
			}
			if (this.m_curState=="shipped"
			&&m.getFieldValue("has_children")=="true"){
				this.m_childCtrl.setDocId(m.getFieldValue("id"));
			}
			else if (this.m_curState=="canceled"
			||this.m_curState=="canceled_by_sales_manager"
			||this.m_curState=="canceled_by_client"){
				this.m_cancelCauseCtrl.setDocId(m.getFieldValue("id"));
			}

			if (m.getFieldValue("deliv_total_edit")!="true"){
				this.m_delivCost.setEnabled(false);
			}
			this.m_savedVehCount = toInt(m.getFieldValue("deliv_vehicle_count"));
			
			this.setDebts(parseFloat(m.getFieldValue("debt_total")), parseFloat(m.getFieldValue("def_debt")));
		}
		
		if (this.m_downloadPrintCtrl){
			this.m_downloadPrintCtrl.setEnabled(true);
		}
		if (this.m_passToProdCtrl){
			this.m_passToProdCtrl.setEnabled(true);
			this.m_passToProdCtrl.m_grid = this.m_productDetails.getGridControl()
		}
	}
}
DOCOrderDialog_View.prototype.toDOM = function(parent){
	DOCOrderDialog_View.superclass.toDOM.call(this,parent);
	
	if (this.m_clientUserCtrl){
		EventHandler.addEvent(
			this.m_clientUserCtrl.getNode(),
			"change",this.m_evClientUserSelected);
	}
	EventHandler.addEvent(
		this.m_wareHCtrl.getNode(),
		"change",this.m_evWarehouseSelected);		
	EventHandler.addEvent(
		this.m_FirmCtrl.getNode(),
		"change",this.m_evFirmSelected);		
		
	EventHandler.addEvent(
		this.m_clientDestCtrl.getNode(),
		"change",this.m_evDelivDestSelected);
	EventHandler.addEvent(
		this.m_delivCostOptCtrl.getNode(),
		"change",this.m_evDelivCostOptSelected);

	if (this.getIsNew()&&SERV_VARS.ROLE_ID=="client"){
		this.setClientId(0,true);
	}
	
	this.setWarehouseId(this.m_wareHCtrl.getFieldValue());
	this.setToThirdParty(this.m_toThirdPartyCtrl.getValue());
	/*
	this.setDelivAddToCost(this.m_delivAddToCostCtrl.getValue());
	this.setDelivTotal(this.m_delivCost.getValue());
	*/
}
DOCOrderDialog_View.prototype.removeDOM = function(){
	DOCOrderDialog_View.superclass.removeDOM.call(this);
	var self = this;
	if (this.m_clientUserCtrl){
		EventHandler.removeEvent(
			this.m_clientUserCtrl.getNode(),
			"change",this.m_evClientUserSelected);
	}
	EventHandler.removeEvent(
		this.m_wareHCtrl.getNode(),
		"change",this.m_evWarehouseSelected);		
	EventHandler.removeEvent(
		this.m_FirmCtrl.getNode(),
		"change",this.m_evFirmSelected);		
		
	EventHandler.removeEvent(
		this.m_clientDestCtrl.getNode(),
		"change",this.m_evDelivDestSelected);
	EventHandler.removeEvent(
		this.m_delivCostOptCtrl.getNode(),
		"change",this.m_evDelivCostOptSelected);		
}
DOCOrderDialog_View.prototype.afterCopyData = function(){
	if (SERV_VARS.ROLE_ID!="client"){
		this.getDataControl(this.getId()+"_number").control.setValue("");
	}
}
DOCOrderDialog_View.prototype.calcDelivCost = function(){
	if (SERV_VARS.ROLE_ID!="client"
	&&this.getViewControlValue(this.getId()+"_deliv_total_edit")=="true"){
		return;
	}	
	this.m_delivCostComment.setValue("");
	this.m_delivCost.setValue("");
	var wh_id = this.m_wareHCtrl.getFieldValue();
	var dest_id = this.m_clientDestCtrl.getFieldValue();
	var cost_opt_id = this.m_delivCostOptCtrl.getFieldValue();
	if (wh_id && dest_id && cost_opt_id){
		this.m_wareHCtrl.setEnabled(false);
		this.m_clientDestCtrl.setEnabled(false);
		this.m_delivCostOptCtrl.setEnabled(false);
		this.getErrorControl().setValue("");
		
		var self = this;
		var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
		contr.run("calc_deliv_cost",{
			"params":{"warehouse_id":wh_id,
				"cost_opt_id":cost_opt_id,
				"client_destination_id":dest_id,
				"include_route":"0"},
			"err":function(resp,errCode,errStr){
				self.getErrorControl().setValue(errStr);
				self.m_wareHCtrl.setEnabled(true);
				self.m_clientDestCtrl.setEnabled(true);
				self.m_delivCostOptCtrl.setEnabled(true);				
			},
			"func":function(resp){
				var m = resp.getModelById("calc_deliv_cost");
				m.setActive(true);
				if (m.getNextRow()){
					self.m_cityRouteDistanceCtrl.setValue(
						parseFloat(m.getFieldValue("city_route_distance"))
					);
					self.m_countryRouteDistanceCtrl.setValue(
						parseFloat(m.getFieldValue("country_route_distance"))
					);
					var v_cnt = self.m_delivVehicleCntCtrl.getValue();					
					self.m_delivCost.setValue(parseFloat(m.getFieldValue("total_cost")).toFixed(2)*v_cnt);
					self.updateDistanceInf();
					self.recalcProductPrices();
					
					self.m_wareHCtrl.setEnabled(true);
					self.m_clientDestCtrl.setEnabled(true);
					self.m_delivCostOptCtrl.setEnabled(true);
					
				}
			}
		});
	}
}
DOCOrderDialog_View.prototype.recalcProductPrices = function(){
	var wh_id = this.m_wareHCtrl.getFieldValue();
	var cl_id;
	if (this.m_clientCtrl){
		cl_id = this.m_clientCtrl.getFieldValue();
	}
	if (wh_id && (!this.m_clientCtrl || (this.m_clientCtrl&&cl_id) )
	){
		this.m_wareHCtrl.setEnabled(false);
		if (this.m_clientCtrl){
			this.m_clientCtrl.setEnabled(false);
		}
		this.getErrorControl().setValue("");
		
		var self = this;
		var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
		contr.run("recalc_product_prices",{
			"params":{
				"warehouse_id":wh_id,
				"client_id":cl_id,
				"deliv_cost":this.m_delivCost.getValue(),
				"deliv_to_third_party":this.m_toThirdPartyCtrl.getValue(),
				"deliv_add_cost_to_product":this.m_delivAddToCostCtrl.getValue()
			},
			"err":function(resp,errCode,errStr){
				self.getErrorControl().setValue(errStr);
				self.m_wareHCtrl.setEnabled(true);
				if (self.m_clientCtrl){
					self.m_clientCtrl.setEnabled(true);
				}
			},
			"func":function(resp){
				if (self.m_productDetails.onRefresh){
					self.m_productDetails.onRefresh();
				}
				self.m_wareHCtrl.setEnabled(true);
				if (self.m_clientCtrl){
					self.m_clientCtrl.setEnabled(true);				
				}
				self.refreshProdTotals();
			}
		});
	}
}
DOCOrderDialog_View.prototype.getModified = function(){
	var f_m = DOCOrderDialog_View.superclass.getModified.call(this);
	var t_m = this.m_productDetails.getGridControl().m_modified;
	return (f_m||t_m);	
}
DOCOrderDialog_View.prototype.setMethodParams = function(pm,checkRes){	
	DOCOrderDialog_View.superclass.setMethodParams.call(this,pm,checkRes);
	
	/*Если клиент и ждет клиента
	Если мы и ждет нас
	то ВСЕГДА update!!!
	*/
	if (this.m_productDetails.getGridControl().m_modified
		||(SERV_VARS.ROLE_ID=="client"
		&&this.m_curState=="waiting_for_client"	
		)
		||(SERV_VARS.ROLE_ID!="client"
		&&this.m_curState=="waiting_for_us"	
		)		
	){
		checkRes.modif = true;
	}
}

/*
DOCOrderDialog_View.prototype.writeData = function(){	
	if (this.m_productDetails.getLineCount()==0){
		this.getErrorControl().setValue("Список продукции пустой!");
	}
	else{
		DOCOrderDialog_View.superclass.writeData.call(this);
	}
}
*/

DOCOrderDialog_View.prototype.onWriteOk = function(resp){	
	DOCOrderDialog_View.superclass.onWriteOk.call(this,resp);
	if (this.m_currentGrid&&this.m_currentGrid.m_rendered){
		this.m_currentGrid.onRefresh();
	}
}

DOCOrderDialog_View.prototype.refreshProdTotals = function(){	
	var gr = this.m_productDetails.getGridControl();
	var rows = gr.getBody().getNode().getElementsByTagName("tr");
	var cells;
	var tot_sum = 0;
	var tot_sum_pack = 0;
	var tot_vol = 0;
	var tot_wt = 0;
	for (var ri=0;ri<rows.length;ri++){
		if (!cells){
			cells = {};
			for (var ci=0;ci<rows[ri].cells.length;ci++){
				var id = DOMHandler.getAttr(rows[ri].cells[ci],"field_id");				
				cells[id]=ci;
			}
		}
		tot_sum+=toFloat(rows[ri].cells[cells.total].textContent);
		tot_sum_pack+=toFloat(rows[ri].cells[cells.total_pack].textContent);
		tot_vol+=toFloat(rows[ri].cells[cells.volume].textContent);
		tot_wt+=toFloat(rows[ri].cells[cells.weight].textContent);
	}
	this.m_totVol.setValue(tot_vol.toFixed(3));
	this.m_totWt.setValue(tot_wt.toFixed(3));
	this.m_totSumPack.setValue(tot_sum_pack.toFixed(2));
	this.m_totSum.setValue(tot_sum.toFixed(2));
	if (this.m_delivAddToCostCtrl.getValue()=="false"){
		tot_sum+=toFloat(this.m_delivCost.getValue());
	}
	tot_sum+=tot_sum_pack;
	this.m_totTotal.setValue(tot_sum.toFixed(2));	
	
	if (!this.m_savedVehCount){
		this.calcVehicleCount();
	}	
}
DOCOrderDialog_View.prototype.getFormCaption = function(){
	return "Заявка";
}
DOCOrderDialog_View.prototype.calcVehicleCount = function(){
	var ind = this.m_delivCostOptCtrl.m_node.selectedIndex;
	var sel_n = this.m_delivCostOptCtrl.m_node.options[ind];
	var v_max_vol = parseFloat(DOMHandler.getAttr(sel_n,"volume_m"));
	var v_max_wt = parseFloat(DOMHandler.getAttr(sel_n,"weight_t"));
	var vol = parseFloat(this.m_totVol.getValue());
	var wt = parseFloat(this.m_totWt.getValue());
	var vol_cnt = Math.ceil(vol/v_max_vol);
	var wt_cnt = Math.ceil(wt/v_max_wt);
	var cnt = (vol_cnt>wt_cnt)? vol_cnt:wt_cnt;
	cnt = toInt(cnt);
	this.m_delivVehicleCntCtrl.setValue(cnt);
	if (cnt>1){
		DOMHandler.addClass(this.m_delivVehicleCntCtrl.m_node,"veh_cnt_warn");
	}
	else{
		DOMHandler.removeClass(this.m_delivVehicleCntCtrl.m_node,"veh_cnt_warn");
	}
	this.m_savedVehCount = 0;
}
DOCOrderDialog_View.prototype.updateDistanceInf = function(){
	var d1_m = parseFloat(this.m_cityRouteDistanceCtrl.getValue());
	var d1 = Math.round(d1_m/1000);	
	var d2_m = parseFloat(this.m_countryRouteDistanceCtrl.getValue());	
	var d2 = Math.round(d2_m/1000);
	var com="";
	if (d1>0){
		com="город ("+d1+"км.)";
	}
	if (d2>0){
		com+=(com=="")? "":", ";
		com+="пригород ("+d2+"км.)";
	}	
	this.m_delivCostComment.setValue(com);
}
DOCOrderDialog_View.prototype.changeDelivType = function(){
	var vis=(this.m_delivTypeCtrl.getValue()=="by_supplier");
	this.m_toThirdPartyCtrl.setEnabled(vis);
	this.m_delivCostOptCtrl.setEnabled(vis);
	this.m_DelivPeriodCtrl.setEnabled(vis);
	this.m_clientDestCtrl.setEnabled(vis);
	if (vis){
		DOMHandler.addAttr(this.m_delivCostOptCtrl.getNode(),"required","required");
		DOMHandler.addAttr(this.m_clientDestCtrl.getNode(),"required","required");
	}
	else{
		DOMHandler.removeAttr(this.m_delivCostOptCtrl.getNode(),"required");
		DOMHandler.removeAttr(this.m_clientDestCtrl.getNode(),"required");				
		this.m_clientDestCtrl.resetValue();
		this.m_delivCostOptCtrl.resetValue();
	}
	
}
DOCOrderDialog_View.prototype.getFormWidth = function(){
	return "800";
}
DOCOrderDialog_View.prototype.getFormHeight = function(){
	return ( (SERV_VARS.ROLE_ID=="client")? "700":"1010");
}

DOCOrderDialog_View.prototype.onDownloadOrder = function(){
	var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
	var meth = contr.getPublicMethodById("download_print");
	meth.setParamValue("doc_id",this.getDataControl(this.getId()+"_id").control.getValue());

	var form = $('<form></form>').attr('action', "index.php").attr('method', 'post');
	for (var id in meth.m_params){
		form.append($("<input></input>").attr('type', 'hidden').attr('name', id).attr('value', meth.m_params[id].getValue()));
	}
	form.appendTo('body').submit().remove();
}
