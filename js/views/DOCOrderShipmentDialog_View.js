/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
 * @requires controls/ViewDialog.js
*/

/* constructor */
function DOCOrderShipmentDialog_View(id,options){
	options = options || {};
	options.tagName="div";
	options.readMethodId = "get_shipment";
	options.writeMethodId = "set_shipped";
	options.customWriteMethod = true;
	
	DOCOrderShipmentDialog_View.superclass.constructor.call(this,
		id,options);
		
	var self = this;	
	this.m_beforeOpen = function(contr,isInsert){
		var doc_id = 0;
		
		self.m_items.getGridControl().setViewId(self.m_viewId);
		
		if (!isInsert){
			doc_id = self.getDataControl(self.getId()+"_id").control.getValue();
		}
		contr.run("before_open",{async:false,params:{
			"doc_id":doc_id,
			"view_id":self.m_viewId
			}});
	}
	
	var model_id = "DOCOrderShipmentDialog_Model";
	
	this.m_viewId = hex_md5(uuid());
	
	var cont=new ControlContainer(id+"_panel","div",{"className":"row"});
	
	this.m_idCtrl = new Edit(id+"_id",{
			"visible":false,
			"name":"id",
			"tableLayout":false});
	this.bindControl(this.m_idCtrl,
		{"modelId":model_id,
		"valueFieldId":"id",
		"keyFieldIds":null},
		{"valueFieldId":"id","keyFieldIds":null}
	);
	cont.addElement(this.m_idCtrl);	
	
	//Номер
	var ctrl = new EditNum(id+"_number",{
			"enabled":false,
			"editContClassName":"input-group "+get_bs_col()+"2",
			"name":"number",
			"labelCaption":"№ заявки:",
			"tableLayout":false}
	);	
	this.bindControl(ctrl,
		{"modelId":model_id,
		"valueFieldId":"number",
		"keyFieldIds":null},
		{}
	);
	cont.addElement(ctrl);	
	
	//Заказчик
	var ctrl = new Edit(id+"_client_descr",{
			"enabled":false,
			"editContClassName":"input-group "+get_bs_col()+"4",
			"name":"client_descr",
			"labelCaption":"Заказчик:",
			"tableLayout":false}
	);
	this.bindControl(ctrl,
		{"modelId":model_id,
		"valueFieldId":"client_descr",
		"keyFieldIds":null},
		{}
	);
	cont.addElement(ctrl);	
	this.addElement(cont);	
	
	//Табличная часть
	this.m_details = new ControlContainer(uuid(),"div",{"className":"row"});
	
	this.m_items = new DOCOrderDOCTShipmentList_View("DOCOrderDOCTShipmentList_View",
		{"connect":new ServConnector(HOST_NAME),
		"errorControl":this.getErrorControl(),
		"winObj":options.winObj
		});	
	this.m_details.addElement(this.m_items);	
	this.addElement(this.m_details);	
	
}
extend(DOCOrderShipmentDialog_View,ViewDialog);

DOCOrderShipmentDialog_View.prototype.m_detailContainer;
/*
DOCOrderShipmentDialog_View.prototype.addDetailControl = function(detailControl,panel){
	if (this.m_details==undefined){
		var detail_row = new ControlContainer(this.getId()+"_det_row","tr");
		var td = new ControlContainer(this.getId()+"_det_row","div");
		detail_row.addElement(td);
		this.m_details = new ControlContainer(this.getId()+"_details","div",{"attrs":{"class":"tabber"}});
		td.addElement(this.m_details);
		panel.addElement(td);
	}
	this.m_details.addElement(detailControl);
}
*/
DOCOrderShipmentDialog_View.prototype.getDetailControl = function(controlId){
	return this.m_details.getElementById(controlId);
}
DOCOrderShipmentDialog_View.prototype.readData = function(async,isCopy){
	DOCOrderShipmentDialog_View.superclass.readData.call(this,false,isCopy);
}
DOCOrderShipmentDialog_View.prototype.onClickSave = function(){
	var cmd_insert = this.getIsNew();
	if (cmd_insert){
		//need return new serial id if any
		var contr = this.getWriteController();
		var meth_id = this.getWriteMethodId();
		var pm = contr.getPublicMethodById(meth_id);
		if (pm.paramExists(contr.PARAM_RET_ID)){
			pm.setParamValue(contr.PARAM_RET_ID,1);
		}
	}
	this.writeData();
	this.readData(false);
}

DOCOrderShipmentDialog_View.prototype.writeData = function(){	
	var contr = this.getWriteController();
	if (!contr)return;
	contr.getPublicMethodById(this.getWriteMethodId()).setParamValue("view_id",this.m_viewId);
	
	DOCOrderShipmentDialog_View.superclass.writeData.call(this);
}

DOCOrderShipmentDialog_View.prototype.setMethodParams = function(pm,checkRes){
	var contr = this.getWriteController();
	var m = contr.getPublicMethodById("set_shipped");
	m.setParamValue("doc_id",this.m_idCtrl.getValue());
	//this.setWriteRespXML(false);
	DOCOrderShipmentDialog_View.superclass.setMethodParams.call(this,pm,checkRes);
	checkRes.modif = true;
}

DOCOrderShipmentDialog_View.prototype.onGetData= function(resp){
	DOCOrderShipmentDialog_View.superclass.onGetData.call(this,resp);
	var m = resp.getModelById("DOCOrderShipmentDialog_Model",true);	
	if (m.getNextRow()){
		this.m_payType = m.getFieldValue("pay_type");
	}
}

DOCOrderShipmentDialog_View.prototype.onWriteOk = function(resp){
	DOCOrderShipmentDialog_View.superclass.onWriteOk.call(this,resp);
	
	var contr = new DOCOrder_Controller(new ServConnector(HOST_NAME));
	if (this.m_payType=="cash"){
		contr.run("get_print_check",{
			"xml":false,
			"params":{"doc_id":this.m_idCtrl.getValue(),
				"v":"DOCOrderCheck"},
			"func":function(resp){					
				WindowPrint.show({"content":resp,"print":true});
			},
			"cont":this
		});		
	}
	else{
		top.location.href = HOST_NAME+"index.php?"+
			contr.getQueryString(
				contr.getPublicMethodById("get_ship_docs")
			)+"&doc_id="+this.m_idCtrl.getValue();	
	}
}
