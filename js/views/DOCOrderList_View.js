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
function DOCOrderList_View(id,options){
	options = options || {};
	//options.title = "Заявки";
	
	DOCOrderList_View.superclass.constructor.call(this,
		id,options);
	
	var cont = new ControlContainer("Orders_cmd","div",{"className":"panel_cmd"});
	
	//Справочники
	/*
	if (SERV_VARS.ROLE_ID=="sales_manager"
	||SERV_VARS.ROLE_ID=="boss"
	||SERV_VARS.ROLE_ID=="admin"
	){
		cont.addElement(new BtnCatalogs({}));
	}
	*/
	
	//date&&time
	cont.addElement(new CurrentDateTime("current_date",{
		"sync":true,
		"className":"text-right text-info"}));
	
	this.addElement(cont);
	
	//Завод
	if (SERV_VARS.ROLE_ID=="production"){
		this.addElement(new ControlContainer(id+"_warehouse","span",{"value":SERV_VARS.WAREHOUSE_DESCR,
		className:"text-right text-info"}));
	}
	
	//new DOCOrders
	if (SERV_VARS.ROLE_ID=="sales_manager"
	||SERV_VARS.ROLE_ID=="boss"
	||SERV_VARS.ROLE_ID=="admin"
	){
		var cont = new ControlContainer(id+"_cont_new_orders","div",{"className":"panel panel-default"});
		
		//сами заявки
		var view_new = new DOCOrderNewList_View(id+"_DOCOrderNewList_View",
			{"client":(SERV_VARS.ROLE_ID!="client"),
			"warehouse":true,
			"total":true
			}
		);		
		cont.addElement(view_new);
		
		this.addElement(cont);
	}
	
	//current DOCOrders
	if (SERV_VARS.ROLE_ID=="client"
	||SERV_VARS.ROLE_ID=="sales_manager"
	||SERV_VARS.ROLE_ID=="boss"
	||SERV_VARS.ROLE_ID=="admin"
	||SERV_VARS.ROLE_ID=="production"
	||SERV_VARS.ROLE_ID=="marketing"
	){
		var view = new DOCOrderCurrentList_View(id+"_DOCOrderCurrentList_View",
			{
				"client":(SERV_VARS.ROLE_ID!="client"),
				"products":true,
				"warehouse":true,				
				"ext_ship_num":true,
				"delivery_fact_date":true,
				"number":(SERV_VARS.ROLE_ID!="client"),
				"client_number":(SERV_VARS.ROLE_ID=="client"),
				
				"paid":true,
				
				"printed":(SERV_VARS.ROLE_ID!="client"),
				
				"customer_survey_date":
				(SERV_VARS.ROLE_ID=="boss"
				||SERV_VARS.ROLE_ID=="admin"),
				
				"filter":true,
				
				"submit_user":(
					SERV_VARS.ROLE_ID=="sales_manager"
					||SERV_VARS.ROLE_ID=="boss"
					||SERV_VARS.ROLE_ID=="admin"
					||SERV_VARS.ROLE_ID=="production"
				)

			}
		);	
		var cont = new ControlContainer(id+"_cont_current_orders","div",{"className":"panel panel-default"});
		
		if (view_new){
			view_new.m_grid.m_currentGrid = view.m_grid;
		}
		
		cont.addElement(view);
		this.addElement(cont);
	}
	
	//closed DOCOrders
	if (SERV_VARS.ROLE_ID=="client"
	||SERV_VARS.ROLE_ID=="sales_manager"
	||SERV_VARS.ROLE_ID=="marketing"
	||SERV_VARS.ROLE_ID=="boss"
	||SERV_VARS.ROLE_ID=="admin"
	){
		this.addElement(new ButtonToggle(uuid(),{
			"caption":"Архив заявок",
			"dataTarget":id+"_cont_closed_orders",
			"attrs":{								
				"title":"показать/скрыть архивные заявки"
				}		
		}));
		
		var cont = new ControlContainer(id+"_cont_closed_orders","div",{"className":"panel panel-default collapse"});
		var view = new DOCOrderClosedList_View(id+"_DOCOrderClosedList_View",
			{"client":(SERV_VARS.ROLE_ID!="client"),
			"warehouse":true,
			"ext_ship_num":true,
			"delivery_fact_date":true,
			
			"client_number":(SERV_VARS.ROLE_ID=="client"),
			
			"printed":(SERV_VARS.ROLE_ID!="client"),
			
			"customer_survey_date":
			(SERV_VARS.ROLE_ID=="boss"
			||SERV_VARS.ROLE_ID=="admin"),			
			
			"pagination":new GridPagination(id+"_cont_closed_orders_grid_pag",{
				"countPerPage":CONSTANT_VALS.grid_rows_per_page_count}
				),
			"filter":true,
			
			"submit_user":(
				SERV_VARS.ROLE_ID=="sales_manager"
				||SERV_VARS.ROLE_ID=="boss"
				||SERV_VARS.ROLE_ID=="admin"
				||SERV_VARS.ROLE_ID=="production"
			)
			
			}			
		);
		cont.addElement(view);
		this.addElement(cont);
	}
}
extend(DOCOrderList_View,ViewList);