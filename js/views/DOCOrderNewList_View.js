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
function DOCOrderNewList_View(id,options){
	options = options || {};
	options.title = "Новые заявки";
	options.readModelId = "DOCOrderNewList_Model";
	options.readMethodId = "get_new_list";
	
	options.state = true;
	
	options.commands = new GridCommands(id+"_grid_cmd",{
		"noPrint":true,"noInsert":true,"noDelete":true
		});	
	DOCOrderNewList_View.superclass.constructor.call(this,
		id,options);
		
	if (SERV_VARS.ROLE_ID=="sales_manager"||
	SERV_VARS.ROLE_ID=="admin"){
		var btn;
		
		var popup_menu = new PopUpMenu();
		
		//doc cancel state
		btn = new BtnCancelLastState({"grid":this.m_grid});
		//this.m_customCommands.addElement(btn);
		options.commands.addElement(btn);
		popup_menu.addButton(btn);
		
		//Закрыть
		btn = new BtnSetClosed({"grid":this.m_grid});
		//this.m_customCommands.addElement(btn);
		options.commands.addElement(btn);
		popup_menu.addButton(btn);		
		
		popup_menu.addSeparator();
		options.commands.commandsToPopUp(popup_menu);
		
		this.m_grid.setPopUpMenu(popup_menu);
	}
}
extend(DOCOrderNewList_View,DOCOrderBaseList_View);
