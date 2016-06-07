/* Copyright (c) 2015 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
  * @requires controls/EditFloat.js
*/

/* constructor */
function DOCOrderQuantEdit(id,options){
	options = options || {};
	this.m_isInt = options.is_int;
	this.m_precision=4;
	DOCOrderQuantEdit.superclass.constructor.call(this,id,options);
}
extend(DOCOrderQuantEdit,EditFloat);

DOCOrderQuantEdit.prototype.validate = function(val){	
	if (this.m_isInt&&Math.round(val)!=val){
		this.setNotValid("Только целое значение!");
	}			
	
	return DOCOrderQuantEdit.superclass.validate.call(this,val);
}