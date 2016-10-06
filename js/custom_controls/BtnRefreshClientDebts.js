/* Copyright (c) 2016
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
*/

/* constructor */
function BtnRefreshClientDebts(options){	
	var id = uuid();
	options.caption = "Обновить из 1с";
	options.attrs={"title":"Обновить долги из базы 1с"};
	options.onClick = function(){
		var keys = options.grid.getSelectedNodeKeys();
		if (keys){
			var contr = new Client_Controller(new ServConnector(HOST_NAME));
			contr.run("refresh_debts",{
				"async":true,
				"xml":true,
				"func":function(resp){					
					WindowMessage.show({
						"text":"Данные по долгам обновлены.",
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
	BtnRefreshClientDebts.superclass.constructor.call(this,
		id,options);
}
extend(BtnRefreshClientDebts,Button);
