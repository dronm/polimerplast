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
function FirmList_View(id,options){
	options = options || {};
	options.title = "Организации";
	FirmList_View.superclass.constructor.call(this,
		id,options);
	
	var controller = new Firm_Controller(options.connect);
	
	var head = new GridHead();
	var row = new GridRow(id+"_row1");	
	row.addElement(new GridDbHeadCell(id+"_col_id",{
		"readBind":{"valueFieldId":"id"},"keyCol":true,
		"visible":false
		}));
	row.addElement(new GridDbHeadCell(id+"_col_name",{"value":"Наименование",
		"readBind":{"valueFieldId":"name"},"descrCol":true
		}));
		
	row.addElement(new GridDbHeadCellBool(id+"_col_nds",{"value":"Есть НДС",
		"readBind":{"valueFieldId":"nds"}
		}));
	row.addElement(new GridDbHeadCellBool(id+"_col_cash",{"value":"За нал.расчет",
		"readBind":{"valueFieldId":"cash"}
		}));
		
		
	row.addElement(new GridDbHeadCellBool(id+"_col_deleted",{"value":"Удален",
		"readBind":{"valueFieldId":"deleted"}
		}));

	row.addElement(new GridDbHeadCellBool(id+"_col_match_1c",{"value":"Соответствует 1с",
		"readBind":{"valueFieldId":"match_1c"}
		}));

		
	head.addElement(row);
	
	this.addElement(new GridDb(id+"_grid",
		{"head":head,
		"body":new GridBody(),
		"controller":controller,
		"readModelId":"FirmList_Model",
		"editViewClass":FirmInline_View,
		"editInline":true,
		"pagination":null,
		"commandPanel":new GridCommands(id+"_cmd",{"controller":controller,
			"noPrint":true}),
		"rowCommandPanelClass":null,
		"filter":null,
		"refreshInterval":0,
		"onSelect":options.onSelect,
		"winObj":options.winObj
		}
	));
}
extend(FirmList_View,ViewList);
