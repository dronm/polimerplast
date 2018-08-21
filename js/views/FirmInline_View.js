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
function FirmInline_View(id,options){
	options = options || {};
	FirmInline_View.superclass.constructor.call(this,
		id,options);	
		
	this.addDataControl(
		new EditCheckBox(id+"_deleted",
		{
		}),
		{"modelId":"FirmList_Model",
		"valueFieldId":"deleted",
		"keyFieldIds":null},
		{"valueFieldId":"deleted","keyFieldIds":null}
	);
	
		
	this.addDataControl(
		new Edit(id+"_id",{"name":"id","visible":false}),
		{"modelId":"FirmList_Model",
		"valueFieldId":"id",
		"keyFieldIds":null},
		{"valueFieldId":"id","keyFieldIds":null},
		{"autoFillOnInsert":true}
	);
	this.addDataControl(
		new EditString(id+"_name",
		{"attrs":{
				"maxlength":100,
				"size":50,
				"required":"required"
				},
		"alwaysUpdate":true
		}),
		{"modelId":"FirmList_Model",
		"valueFieldId":"name",
		"keyFieldIds":null},
		{"valueFieldId":"name","keyFieldIds":null}
	);
	
	this.addDataControl(
		new EditCheckBox(id+"_nds",
		{
		}),
		{"modelId":"FirmList_Model",
		"valueFieldId":"nds",
		"keyFieldIds":null},
		{"valueFieldId":"nds","keyFieldIds":null}
	);
	this.addDataControl(
		new EditCheckBox(id+"_cash",
		{
		}),
		{"modelId":"FirmList_Model",
		"valueFieldId":"cash",
		"keyFieldIds":null},
		{"valueFieldId":"cash","keyFieldIds":null}
	);

}
extend(FirmInline_View,ViewInlineGridEdit);
