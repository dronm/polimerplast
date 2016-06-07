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
function BtnPrintDoc(id,options){	
	options.attrs={"title":options.title};
	options.onClick = function(){
		var keys = options.grid.getSelectedNodeKeys();
		if (keys){
			var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
			contr.run(options.checkMethodId,{
				"params":{"doc_id":keys["id"]},
				"func":function(){
					
					var q_params = contr.getQueryString(contr.getPublicMethodById(options.methodId))+
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
	BtnPrintDoc.superclass.constructor.call(this,
		id,options);
}
extend(BtnPrintDoc,Button);