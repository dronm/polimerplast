/* Copyright (c) 2014	Andrey Mikhalevich, Katren ltd.*//*		Description*///ф/** Requirements*//* constructor */function BtnPassToProduction(options){		var id = "btn_order_pass_to_production";	options.caption = "В производство";	options.attrs={"title":"передать заявку в производство"};		var self = this;	options.onClick = function(){		var keys = options.grid.getSelectedNodeKeys();		if (keys){			self.setEnabled(false);			WindowQuestion.show({				"text":"Передать заявку в производство?",				"callBack":function(r){					if (r==WindowQuestion.RES_YES){						var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));						contr.run("pass_to_production",{							"async":true,							"params":{"doc_id":keys["id"]},							"func":function(){													options.grid.getErrorControl().setValue("Заявка передана в производство!");								options.grid.onRefresh();								self.setEnabled(true);							},							"cont":this,							"err":function(resp,errCode,errStr){								self.setEnabled(true);								options.grid.getErrorControl().setValue(errStr);															}						});											}				}			});		}	};			BtnPassToProduction.superclass.constructor.call(this,		id,options);}extend(BtnPassToProduction,Button);