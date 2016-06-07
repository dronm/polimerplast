/* Copyright (c) 2015
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
*/

/* constructor */
function BtnPrintShipDocs(options){
	options = options||{};
	options.caption = "УПД+ТТН";
	options.title = "распечатать отгрузочные документы (УПД,ТТН)";
	
	options.onClick = function(){
		var keys = options.grid.getSelectedNodeKeys();
		if (keys){
			var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
			contr.run("ext_ship_exists",{
				"params":{"doc_id":keys["id"]},
				"func":function(){					
					var q_params = contr.getQueryString(contr.getPublicMethodById("print_upd"))+
								"&doc_id="+keys["id"];
					window.open(HOST_NAME+"index.php?"+q_params,
					"_blank","location=0,menubar=0,status=0,titlebar=0"); 
					
					//печать второй формы ТТН
					var q_params = contr.getQueryString(contr.getPublicMethodById("print_ttn"))+
								"&doc_id="+keys["id"];
					window.open(HOST_NAME+"index.php?"+q_params,
					"_blank","location=0,menubar=0,status=0,titlebar=0"); 
				},
				"err":function(resp,errCode,str){
					WindowMessage.show({
						"type":WindowMessage.TP_ER,
						"text":str
					})
				}
			});
		}
	};	
	
	BtnPrintShipDocs.superclass.constructor.call(this,
		"btn_print_ship_docs",options);
}
extend(BtnPrintShipDocs,Button);