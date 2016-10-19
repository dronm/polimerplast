/* Copyright (c) 2016
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/

/** Requirements
*/

/* constructor */
function BtnSetPaidByBank(options){	

	options.glyph = "glyphicon-piggy-bank";
	options.methodId = "set_paid_by_bank";
	options.caption = "Оплата (безнал)";
	options.attrs = options.attrs||{};
	options.attrs.title="Установить признак оплаты (безналичный расчет)";
	options.resultText = "Установлен признак оплаты по безналичному расчету.";
	
	BtnSetPaidByBank.superclass.constructor.call(this,
		"btn_set_paid_by_bank",options);
}
extend(BtnSetPaidByBank,BtnUpdatePaid);
