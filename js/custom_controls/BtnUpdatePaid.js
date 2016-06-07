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
function BtnUpdatePaid(id,options){	
	options.onClick = function(){
		var keys = options.grid.getSelectedNodeKeys();
		if (keys){
			var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
			contr.run(options.methodId,{
				"async":true,
				"xml":true,
				"params":{"doc_id":keys["id"]},
				"func":function(resp){					
					WindowMessage.show({
						"text":options.resultText,
						"type":WindowMessage.TP_NOTE,
						"callBack":function(){
							options.grid.onRefresh();
						}
						});					
				},
				"cont":this,
				"errControl":options.grid.getErrorControl()
			});
		}
	};		
	BtnUpdatePaid.superclass.constructor.call(this,
		id,options);
}
extend(BtnUpdatePaid,Button);