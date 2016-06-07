/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
 * @requires common/functions.js
*/

function DelivUnassignedOrderGridRow(id,options){
	options = options || {};	
	DelivUnassignedOrderGridRow.superclass.constructor.call(this,
		id,options);

}
extend(DelivUnassignedOrderGridRow,GridRow);

DelivUnassignedOrderGridRow.prototype.toDOM = function(parent){
	DelivUnassignedOrderGridRow.superclass.toDOM.call(this,parent);
	//draggable
	var self = this;
	this.m_dragObject = new DragObject(this.m_node);
	this.m_dragObject.getFieldValue = function(fieldId){
		var res = DOMHandler.getElementsByAttr(fieldId,self.getNode(),"field_id",true,"td");
		return (res.length&&res[0].childNodes.length)? res[0].childNodes[0].nodeValue:null;
	}
	this.m_dragObject.onDragStart = function(offset){				
		DragObject.prototype.onDragStart.call(this,offset);				
		DOMHandler.addClass(this.element,"dragging_order");
	}
	this.m_dragObject.onDragFail = function(dropObject){
		DragObject.prototype.onDragFail.call(this);				
		DOMHandler.removeClass(this.element,"dragging_order");
		if (dropObject){
			DOMHandler.removeClass(dropObject.element,"uponMe");
		}
	}				
}

/* constructor */
function DelivUnassignedOrderGridDb(id,options){
	options = options || {};
	
	this.m_filterAllOrders = options.filterAllOrders;
	
	DelivUnassignedOrderGridDb.superclass.constructor.call(this,
		id,options);		
}
extend(DelivUnassignedOrderGridDb,GridDbDOC);

DelivUnassignedOrderGridDb.prototype.filterToDOM = function(parent){
}
DelivUnassignedOrderGridDb.prototype.getGridRowClass = function(row,model){
	return DelivUnassignedOrderGridRow;
}
DelivUnassignedOrderGridDb.prototype.onRefresh = function(){
	var pm = this.getController().getPublicMethodById(this.getReadMethodId());
	pm.setParamValue("all_orders",(this.m_filterAllOrders.getValue()=="true")? "1":"0");
	
	DelivUnassignedOrderGridDb.superclass.onRefresh.call(this);
}
