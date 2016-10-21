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
function BtnPaidByBankToAcc(options){	
	id = uuid();
	
	//options.glyph = "glyphicon-piggy-bank";
	options.caption = "ПКО (карта)";
	options.attrs = options.attrs||{};
	options.attrs.title = "Создать ПКО по оплаченным заявкам (карта)";
	
	var self = this;
	options.onClick = function(){
		self.setEnabled(false);
		
		var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
		contr.run("paid_by_bank_to_acc",{
			"async":true,
			"xml":true,
			"func":function(resp){					
				var m = resp.getModelById("paid_to_acc",true);
				if (m.getNextRow()){
					WindowMessage.show({
						"text":m.getFieldValue("mes"),
						"type":WindowMessage.TP_NOTE,
						"callBack":function(){
							self.setEnabled(true);
						}
						}
					);					
				}
			},
			"cont":this,
			"errControl":options.grid.getErrorControl()
		});
	};		
	BtnPaidByBankToAcc.superclass.constructor.call(this,
		id,options);
}
extend(BtnPaidByBankToAcc,Button);
