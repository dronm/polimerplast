function ClientDestinationEdit2(id,opts){
	var contr = new ClientDestination_Controller(new ServConnector(HOST_NAME));
	
	this.m_pm = contr.getPublicMethodById("complete_address");
	this.m_paramId = "client_id";
	this.m_pm.setParamValue(this.m_paramId,options.clientId);	
	
	options =
		{"attrs":{"required":"required"},
		"noSelect":true,
		"noOpen":true,
		"tableLayout":false,
		"methodId":"complete_address",
		"modelId":"DelivAddress_Model",
		"lookupValueFieldId":"address",
		"lookupKeyFieldIds":["id"],
		"keyFieldIds":[opts.fieldId],
		"resultFieldId":"address",
		"controller":contr,
		"minLengthForQuery":"1",
		"patternParamId":"address",
		"ic":null,
		"mid":null,
		"onSelected":opts.options.onSelected,
		"extraFields":null,
		"resultFieldIdsToAttr":["is_old"],
		"queryDelay":500,
		"fullTextSearch":true,
		"className":"form-control addressEdit",
		
		"objectView":null,
		"noOpen":true,
		"winObj":opts.winObj
	};
	if (opts.inLine==undefined||(opts.inLine!=undefined&&!inLine)){
		options["labelCaption"] = "Адрес доставки:";
	}
	
	var self = this;	
	
	this.m_btnEdit = new ButtonCtrl(id+"_edit_btn",{
	"glyph":"glyphicon-pencil",
	"attrs":{"title":"изменить адрес"},
	"enabled":false,
	"onClick":function(){	
		var id = self.getFieldValue();
		self.openForm( (id && id!="0")? {"id":id}:null );
	}
	});
	this.m_btnDelete = new ButtonCtrl(id+"_del_btn",{
	"glyph":"glyphicon-trash",
	"attrs":{"title":"удалить адрес"},
	"enabled":false,
	"onClick":function(){
		var id = self.getAttr("fkey_deliv_destination_id");
		if (id && id!="undefined"){
			WindowQuestion.show({
			"winObj":self.m_winObj,
			"text":"Удалить адрес клиента?",
			"callBack":function(r){
				if (r==WindowQuestion.RES_YES){
					var contr = new ClientDestination_Controller(new ServConnector(HOST_NAME));
					contr.getPublicMethodById("delete").setParamValue("id",id);
					contr.run("delete",{
						"func":function(){
							self.setAttr("fkey_deliv_destination_id","");
							self.setValue("");
							self.setFocus();
						}
					});					
				}
			}
			})
		}
	}
	});
	
	ClientDestinationEdit2.superclass.constructor.call(this,id,options);	
		
	this.m_buttons.addElement(this.m_btnEdit);
	this.m_buttons.addElement(this.m_btnDelete);		
}
extend(ClientDestinationEdit2,EditObject);

ClientDestinationEdit2.prototype.setClientId = function(clientId){
	this.m_pm.setParamValue(this.m_paramId,clientId);
	this.setEnabled(true);
}

ClientDestinationEdit2.prototype.openForm = function(keys){
	var self = this;
	//
	//WindowFormModalBS
	this.m_extForm = new WIN_CLASS(this.getId()+"_dest_edit",{"caption":"Адрес клиента",
		"top":0,"left":0,
		"width":getViewportWidth()-10,"height":getViewportHeight(),
		"noMinimize":true,"resizable":false});
	
	this.m_extView = new ClientDestinationDialog_View(this.m_id+"_ClientDestinationDialog",
		{"winObj":this.m_extForm,
		"readController":new ClientDestination_Controller(new ServConnector(HOST_NAME)),
		"clientId":this.m_pm.getParamValue(this.m_paramId),
		"addressValue":this.m_node.value,
		"delivDestinationId":(keys)? keys.id:"0",
		"onClose":function(res){
			var n_val = self.m_extView.m_ctrlValue.getValue();
			self.setValue(n_val);
			//console.log("onClose lastInsertedId="+self.m_extView.m_lastInsertedId)
			if (self.m_extView.m_lastInsertedId){
				self.setAttr("fkey_deliv_destination_id",self.m_extView.m_lastInsertedId);
				DOMHandler.removeClass(self.m_node,"error");
			}
			
			self.m_extView.removeDOM();
			delete self.m_extView;

			self.m_extForm.m_closeMode = res;		
			self.m_extForm.close();
			delete self.m_extForm;
			
			self.setFocus();		
		}
		});
	this.m_extView.setWinObj(this.m_extForm);
	this.m_extForm.m_view = this.m_extView;
	this.m_extForm.open();	
	
	this.m_extView.toDOM(this.m_extForm.getContentParent());
	this.m_extForm.setFocus();		
		
	var vwrite_m = "insert";
	if (keys && keys.id){
		this.m_extView.setReadIdValue("id",keys.id);		
		vwrite_m = "update";
		this.m_extView.readData(true);					
	}
	//console.log("Write mwthod="+vwrite_m)
	this.m_extView.setWriteMethodId(vwrite_m);
	
}
ClientDestinationEdit2.prototype.setEnabled = function(val){
	ClientDestinationEdit2.superclass.setEnabled.call(this,val);
	this.m_btnEdit.setEnabled(val);
	this.m_btnDelete.setEnabled(val);
}
