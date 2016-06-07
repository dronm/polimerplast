/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
 * @requires controls/View.js
*/

/* constructor */
function VehicleDialog_View(id,options){
	options = options || {};
	VehicleDialog_View.superclass.constructor.call(this,
		id,options);	
	
	var model = "VehicleDialog_Model";
	
	var cont_m=new ControlContainer(uuid(),"div",{className:"row"});
	
	var cont=new ControlContainer(uuid(),"div",{className:get_bs_col()+"6"});
	
	var ctrl = new EditNum(id+"_id",
		{"attrs":{"disabled":"disabled"},
		"labelCaption":"Код:"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"id",
		"keyFieldIds":null},
		{"valueFieldId":"id","keyFieldIds":null});	
	cont.addElement(ctrl);
	
	ctrl = new EditString(id+"_plate",
		{"attrs":{"maxlength":11,"size":8,"required":"required"},
		"labelCaption":"Рег.номер:","name":"plate"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"plate",
		"keyFieldIds":null},
		{"valueFieldId":"plate","keyFieldIds":null});	
	cont.addElement(ctrl);
	
	//
	ctrl = new EditString(id+"_model",
		{"attrs":{"maxlength":50},
		"labelCaption":"Модель:",
		"name":"model"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"model",
		"keyFieldIds":null},
		{"valueFieldId":"model","keyFieldIds":null});	
	cont.addElement(ctrl);
	
	//
	ctrl = new EditNum(id+"_vol",
		{"attrs":{"maxlength":5},
		"labelCaption":"Объем:",
		"name":"vol"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"vol",
		"keyFieldIds":null},
		{"valueFieldId":"vol","keyFieldIds":null});	
	cont.addElement(ctrl);
	
	//
	ctrl = new EditFloat(id+"_load_weight_t",
		{"precision":"2","attrs":{"maxlength":8,"size":4},
		"labelCaption":"Грузоподъемность:",
		"name":"load_weight_t"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"load_weight_t",
		"keyFieldIds":null},
		{"valueFieldId":"load_weight_t","keyFieldIds":null});	
	cont.addElement(ctrl);
	
	//
	ctrl = new ProductionCityEditObject("production_city_id","production_city",false);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"production_city_descr",
		"keyFieldIds":["production_city_id"]},
		{"modelId":model,
		"valueFieldId":null,"keyFieldIds":["production_city_id"]}
	);	
	cont.addElement(ctrl);

	this.addControl(cont);
	
	//
	var cont=new ControlContainer(uuid(),"div",{className:get_bs_col()+"6"});
	
	//основной	
	ctrl = new EditCheckBox(id+"_employed",
		{"labelCaption":"Постоянное ТС:",
		"name":"employed",
		"labelAlign":"left"
		}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"employed",
		"keyFieldIds":null},
		{"valueFieldId":"employed","keyFieldIds":null}
	);	
	cont.addElement(ctrl);
	
	ctrl = new CarrierEditObject("carrier_id","carrier",false);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"carrier_descr",
		"keyFieldIds":["carrier_id"]},
		{"modelId":model,
		"valueFieldId":null,"keyFieldIds":["carrier_id"]}
	);	
	cont.addElement(ctrl);
	
	ctrl = new DriverEditObject("driver_id","driver",false);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"driver_descr",
		"keyFieldIds":["driver_id"]},
		{"modelId":model,
		"valueFieldId":null,"keyFieldIds":["driver_id"]}
	);	
	cont.addElement(ctrl);
		
	//
	ctrl = new DelivCostOptEdit({
			"fieldId":"deliv_cost_opt_id",
			"controlId":"deliv_cost_opt",
			"inLine":false}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"deliv_cost_opt_descr",
		"keyFieldIds":["deliv_cost_opt_id"]},
		{"modelId":model,
		"valueFieldId":null,"keyFieldIds":["deliv_cost_opt_id"]}
	);	
	cont.addElement(ctrl);	
	
	//прицеп
	ctrl = new EditString(id+"_trailer_plate",
		{"attrs":{"maxlength":11,"size":8},
		"labelCaption":"Рег.номер прицепа:","name":"trailer_plate"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"trailer_plate",
		"keyFieldIds":null},
		{"valueFieldId":"trailer_plate","keyFieldIds":null}
	);	
	cont.addElement(ctrl);
	
	//
	ctrl = new EditString(id+"_trailer_model",
		{"attrs":{"maxlength":50,"size":20},
		"labelCaption":"Модель прицепа:","name":"trailer_model"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"trailer_model",
		"keyFieldIds":null},
		{"valueFieldId":"trailer_model","keyFieldIds":null}
	);	
	cont.addElement(ctrl);
	
	cont_m.addElement(cont);
	this.addControl(cont_m);
	
	var cont_m=new ControlContainer(uuid(),"div",{className:"row"});
	var cont=new ControlContainer("tracker_cont","div",{className:get_bs_col()+"6"});
	var ctrl = new TrackerEdit({
			fieldId:"tracker_id",
			"controlId":id+"_tracker",
			"inline":false
		}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"tracker_id",
		"keyFieldIds":["tracker_id"]},
	{"valueFieldId":null,"keyFieldIds":["tracker_id"]});	
	cont.addElement(ctrl);

	ctrl = new EditDateTime(id+"_last_tracker_data",
		{"enabled":false,
		"labelCaption":"Последние данные:"}
	);
	this.bindControl(ctrl,
		{"modelId":model,
		"valueFieldId":"last_tracker_data",
		"keyFieldIds":null},
	{"valueFieldId":null,"keyFieldIds":null});	
	cont.addElement(ctrl);
	
	cont_m.addElement(cont);
	this.addElement(cont_m);
}
extend(VehicleDialog_View,ViewDialog);