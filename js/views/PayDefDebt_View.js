/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
 * @requires controls/ViewList.js
*/

/* constructor */
function PayDefDebt_View(id,options){
	options = options || {};
	options.title = "Просроченная задолженность";
	options.controller = Payment_Controller;
	options.methodId = "get_def_debt_details";
	options.viewId = "ClientDefDebt";
	options.connect = options.connect;
	options.reportControl = new Control("PayDefDebt","div");
	
	PayDefDebt_View.superclass.constructor.call(this,
		id,options);
	this.m_waitControl = new WaitControl(id+"_wait");
	this.m_filter=null;	
	this.makeReport(true);
}
extend(PayDefDebt_View,ViewReport);

PayDefDebt_View.prototype.toDOM = function(parent){
	this.m_cont = new ControlContainer(this.getId()+"_panel","div",{"className":"panel"});
	this.m_cont.toDOM(parent);
	PayDefDebt_View.superclass.toDOM.call(this,this.m_cont.m_node);
}
PayDefDebt_View.prototype.removeDOM = function(){
	PayDefDebt_View.superclass.removeDOM.call(this);
	this.m_cont.removeDOM();
}