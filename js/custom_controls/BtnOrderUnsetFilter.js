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
function BtnOrderUnsetFilter(options){	
	var id = "btn_order_unset_filter";
	options.caption = "Сбросить";
	options.attrs={"title":"сбросить фильтр"};
	options.onClick = function(){
		var f = options.grid.getFilter();
		f.unset();
	};		
	BtnOrderUnsetFilter.superclass.constructor.call(this,
		id,options);
}
extend(BtnOrderUnsetFilter,Button);