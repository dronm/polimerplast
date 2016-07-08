/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
/** Requirements
 * @requires common/functions.js
 * @requires core/ControllerDb.js
*/
//ф
/* constructor */

function Constant_Controller(servConnector){
	options = {};
	options["listModelId"] = "ConstantList_Model";
	options["objModelId"] = "ConstantList_Model";
	Constant_Controller.superclass.constructor.call(this,"Constant_Controller",servConnector,options);	
	
	//methods
	this.add_set_value();
	this.addGetList();
	this.addGetObject();
	this.add_get_values();
	
}
extend(Constant_Controller,ControllerDb);

			Constant_Controller.prototype.add_set_value = function(){
	var pm = this.addMethodById('set_value');
	
				
		pm.addParam(new FieldString("id"));
	
				
		pm.addParam(new FieldString("val"));
	
			
}

			Constant_Controller.prototype.addGetList = function(){
	Constant_Controller.superclass.addGetList.call(this);
	var options = {};
	
	var pm = this.getGetList();
	pm.addParam(new FieldString("id",options));
	pm.addParam(new FieldString("name",options));
	pm.addParam(new FieldText("descr",options));
	pm.addParam(new FieldText("val_descr",options));
	pm.getParamById(this.PARAM_ORD_FIELDS).setValue("name");
	
}

			Constant_Controller.prototype.addGetObject = function(){
	Constant_Controller.superclass.addGetObject.call(this);
	var options = {};
	
	var pm = this.getGetObject();
	pm.addParam(new FieldString("id",options));
}

			Constant_Controller.prototype.add_get_values = function(){
	var pm = this.addMethodById('get_values');
	
				
		pm.addParam(new FieldString("id_list"));
	
			
}

		