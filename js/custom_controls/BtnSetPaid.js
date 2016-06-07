/* Copyright (c) 2015
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
//ф
/** Requirements
*/

/* constructor */
function BtnSetPaid(options){	
	options.methodId = "set_paid";
	options.caption = "Оплата";
	options.attrs = options.attrs||{};
	options.attrs.title="Установить признак оплаты";
	options.resultText = "Установлен признак оплаты";
	BtnSetPaid.superclass.constructor.call(this,
		"btn_set_paid",options);
}
extend(BtnSetPaid,BtnUpdatePaid);