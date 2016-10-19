<?php
require_once(FRAME_WORK_PATH.'basic_classes/Model.php');

class MainMenu_Model_sales_manager extends Model{
	public function dataToXML(){
		return '<model id="MainMenu_Model">
		
		<item viewId="DOCOrderList_View" descr="Заявки" default="TRUE"/>
		
		<item viewId="ClientList_View" descr="Клиенты" default=""/>
		
		<item viewId="Delivery_View" descr="Доставки" default=""/>
		
		<item viewId="RepClientDebts_View" descr="Долги клиентов" default=""/>
		
		<item viewId="UserAccount_View" descr="Учетная запись" default=""/>
		
		</model>';
	}
}
?>
