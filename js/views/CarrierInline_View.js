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
function CarrierInline_View(id,options){
	options = options || {};
	CarrierInline_View.superclass.constructor.call(this,
		id,options);	
		
	var model = "Carrier_Model";
		
	this.addDataControl(
		new Edit(id+"_id",{"name":"id","visible":false}),
		{"modelId":model,
		"valueFieldId":"id",
		"keyFieldIds":null},
		{"valueFieldId":"id","keyFieldIds":null},
		{"autoFillOnInsert":true}
	);
	this.addDataControl(
		new EditString(id+"_name",
		{"attrs":{"maxlength":100,"size":50,"required":"required"}}),
		{"modelId":model,
		"valueFieldId":"name",
		"keyFieldIds":null},
		{"valueFieldId":"name","keyFieldIds":null}
	);
}
extend(CarrierInline_View,ViewInlineGridEdit);