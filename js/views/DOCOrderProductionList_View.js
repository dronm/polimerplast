/* Copyright (c) 2016 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
	
	*** ДЛЯ Представителя ***
*/
/** Requirements
 * @requires 
 * @requires core/extend.js  
*/

/* constructor
@param string id
@param object options{

}
*/
function DOCOrderProductionList_View(id,options){
	options = options || {};	
	
	options.name = "DOCOrderCurrenForReprtList_View";
	options.readMethodId = "get_current_for_representative_list";
	
	options.title = "Заявки в производстве";
	
	options.client = true;
	options.products = true;
	options.warehouse = true;
	options.ext_ship_num = true;
	options.delivery_fact_date = true;
	options.number = true;
	options.client_number = true;
	
	options.paid = true;
	
	options.printed = true;
	
	options.customer_survey_date = false;
	
	options.filter = true;
	
	options.submit_user = true;
	
	options.className = "";
	options.noAutoRefresh = CONSTANT_VALS.ordersCallapsed;
	options.refreshInterval = (CONSTANT_VALS.ordersCallapsed)? 0:CONSTANT_VALS.db_controls_refresh_sec*1000;
	
	options.elements = [new ControlContainer(id+"_warehouse","span",{"value":SERV_VARS.WAREHOUSE_DESCR,
		className:"text-right text-info"})
	];
	
	DOCOrderProductionList_View.superclass.constructor.call(this,id,options);
}
extend(DOCOrderProductionList_View,DOCOrderCurrentList_View);

/* Constants */


/* private members */

/* protected*/


/* public methods */

