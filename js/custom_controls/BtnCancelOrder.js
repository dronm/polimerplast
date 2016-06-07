/* Copyright (c) 2014
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
*/

/* constructor */
function BtnCancelOrder(options){	
	var id = "btn_order_cancel";
	options.caption = "Отменить";
	options.attrs={"title":"отменить заявку"};
	var self = this;
	options.onClick = function(){
		var keys = options.grid.getSelectedNodeKeys();
		if (keys&&WindowQuestion.show({"text":"Отменить заявку?"})==WindowQuestion.RES_YES){
			//запросим причину отмены
			self.m_extForm = new WindowFormModalDD({"caption":"Отмена заявки"});
			self.m_extCtrl = new DOCOrderCancel_View("DOCOrderCancel",
				{"doc_id":keys["id"],"winObj":self.m_extForm,
				"onClose":function(res){
					if (res==1){
						//OK
						self.m_grid.onRefresh();
					}
					self.m_extForm.close();
					delete self.m_extForm;					
				},
				"winObj":self.m_extForm
				});
			self.m_extForm.open();
			self.m_extCtrl.toDOM(self.m_extForm.getContentParent());
			self.m_extForm.setFocus();
			
		}
	};
	this.m_grid = options.grid;
	BtnCancelOrder.superclass.constructor.call(this,
		id,options);
}
extend(BtnCancelOrder,Button);