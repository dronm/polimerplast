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
function VehicleList_View(id,options){
	options = options || {};
	//options.title = "Автомобили";
	VehicleList_View.superclass.constructor.call(this,
		id,options);
	var head = new GridHead();
	var row = new GridRow(id+"_row1");	
	row.addElement(new GridDbHeadCell(id+"_col_id",{
		"readBind":{"valueFieldId":"id"},"keyCol":true,
		"visible":false
		}));
	row.addElement(new GridDbHeadCell(id+"_col_plate",{"value":"Гос.номер",
		"readBind":{"valueFieldId":"plate"},"descrCol":true,
		"sortable":true}));
	row.addElement(new GridDbHeadCell(id+"_col_vl_wt",{"value":"V/M",
		"readBind":{"valueFieldId":"vl_wt"}
		}));
	row.addElement(new GridDbHeadCell(id+"_col_production_city_descr",{"value":"Город",
		"readBind":{"valueFieldId":"production_city_descr",
		"sortable":true,"sort":"asc"}
		}));
	row.addElement(new GridDbHeadCellBool(id+"_col_driver_descr",{"value":"Водитель",
		"readBind":{"valueFieldId":"driver_descr"}
		}));		
	row.addElement(new GridDbHeadCellBool(id+"_col_employed",{"value":"Пост.",
		"readBind":{"valueFieldId":"employed",
		"sortable":true}
		}));
	row.addElement(new GridDbHeadCellBool(id+"_col_carrier_descr",{"value":"Перевозчик",
		"readBind":{"valueFieldId":"carrier_descr",
		"sortable":true}
		}));
		
	head.addElement(row);
	
	this.addElement(new GridDb(id+"_grid",
		{"head":head,
		"body":new GridBody(),
		"controller":options.controller ||
			new Vehicle_Controller(new ServConnector(HOST_NAME)),
		"readModelId":options.model||"VehicleList_Model",
		"readMethodId":options.method||"get_list",
		"editViewClass":VehicleDialog_View,
		"editInline":false,
		"pagination":null,
		"commandPanel":new GridCommands(id+"_cmd",{"noPrint":true}),
		"rowCommandPanelClass":null,
		"filter":null,
		"refreshInterval":0,
		"onSelect":options.onSelect,
		"winObj":options.winObj
		}
	));
}
extend(VehicleList_View,ViewList);

VehicleList_View.prototype.getFormWidth = function(){
	return "1000";
}

