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
function BtnPaidToAcc(options){	
	id = "btn_paid_to_acc";
	options.caption = "==>>ПКО";
	options.attrs = options.attrs||{};
	options.attrs.title = "Создать ПКО в бухгалтерии по оплаченным заявкам.";
	
	var self = this;
	options.onClick = function(){
		self.setEnabled(false);
		
		var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
		contr.run("paid_to_acc",{
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
	BtnPaidToAcc.superclass.constructor.call(this,
		id,options);
}
extend(BtnPaidToAcc,Button);
