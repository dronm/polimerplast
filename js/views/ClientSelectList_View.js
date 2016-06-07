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
function ClientSelectList_View(id,options){
	options = options || {};
	ClientSelectList_View.superclass.constructor.call(this,
		id,options);
	
	var controller = new Client_Controller(options.connect);
	
	var cont=new ControlContainer("p_cl","div",{"className":"panel"});
	
	//filter
	var filter = new GridFilter(uuid(),{"tagName":"div"});
	filter.addFilterControl(new EditObject("ClientListFilter_name",
		{"labelCaption":"Наименование:",
		"methodId":"complete",
		"tableLayout":false,
		"modelId":"ClientComplete_Model",
		"objectView":ClientDialog_View,
		"lookupValueFieldId":"name",
		"lookupKeyFieldIds":["id"],
		"keyFieldIds":["id"],
		"controller":controller,
		"minLengthForQuery":1,
		"objectView":ClientDialog_View,
		"noSelect":true,
		"winObj":this.m_winObj
		})
	,{"sign":"lk","valueFieldId":"name",
	"icase":true,"l_wcards":true,"r_wcards":true});
		
	var head = new GridHead();
	var row = new GridRow(id+"_row1");
	row.addElement(new GridDbHeadCell(id+"_col_id",{
		"readBind":{"valueFieldId":"id"},"keyCol":true,
		"visible":false
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_name",{"value":"Наименование",
		"readBind":{"valueFieldId":"name"},"descrCol":true,
		"sortable":true,"sort":"asc"
		}));
	row.addElement(new GridDbHeadCell(id+"_col_client_activity_descr",{"value":"Вид деят.",
		"readBind":{"valueFieldId":"client_activity_descr"},
		"sortable":true
		}));
	row.addElement(new GridDbHeadCell(id+"_col_inn",{"value":"ИНН",
		"readBind":{"valueFieldId":"inn"}
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_contract_descr",{"value":"Договор",
		"readBind":{"valueFieldId":"contract_descr"}
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_contract_period",{"value":"Срок дог.",
		"readBind":{"valueFieldId":"contract_period"},
		"sortable":true
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_terms",{"value":"Условия",
		"readBind":{"valueFieldId":"terms"},
		"sortable":true
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_debt",{"value":"Деб.задолж.",
		"readBind":{"valueFieldId":"debt"},
		"colAttrs":{"align":"right"},"sortable":true
		}));		
	row.addElement(new GridDbHeadCell(id+"_col_login_allowed",{"value":"Личн.каб.",
		"readBind":{"valueFieldId":"login_allowed"},
		"assocImageArray":{"true":"img/bool/true.png",
			"false":"img/bool/false.png"},
		"colAttrs":{"align":"center"},			
		"sortable":true
		}));		
		
	head.addElement(row);
	
	cont.addElement(new GridDb(id+"_grid",
		{"head":head,
		"body":new GridBody(),
		"controller":controller,
		"readModelId":"ClientList_Model",
		"editViewClass":null,
		"editInline":null,
		"pagination":new GridPagination("page_ClientList",
			{"countPerPage":30}),
		
		"commandPanel":
			new GridCommands(id+"_grid_cmd",{
				"noInsert":true,"noEdit":true,
				"noCopy":true,"noPrint":true,"noDelete":true
			}),
		"rowCommandPanelClass":null,
		"filter":filter,
		"refreshInterval":0,
		"onSelect":options.onSelect,
		"rowSelect":true
		}
	));
	
	this.addElement(cont);
}
extend(ClientSelectList_View,ViewList);

ClientSelectList_View.prototype.getFormWidth = function(){
	return "1250";
}
ClientSelectList_View.prototype.getFormHeight = function(){
	return "600";
}