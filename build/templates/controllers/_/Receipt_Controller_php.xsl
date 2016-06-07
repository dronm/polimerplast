<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'Receipt'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>
require_once('functions/SMS.php');
class <xsl:value-of select="@id"/>_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	public function insert($pm){
		//doc owner
		$pm->setParamValue('user_id',$_SESSION['user_id']);		
		parent::insert();		
	}
	public function get_list($pm){
		$pm->setParamValue('cond_fields','user_id');
		$pm->setParamValue('cond_vals',$_SESSION['user_id']);
		$pm->setParamValue('cond_sgns','e');
		parent::get_list();		
	}
	public function add_to_receipt($item_id,$doc_production_id,$item_type){
		$link = $this->getDbLinkMaster();
		$q = sprintf(
		"SELECT add_to_receipt(%d,%d,%d,%d)
		",$item_id,$doc_production_id,$item_type,$_SESSION['user_id']);
		$link->query($q);
	}
	public function add_material($pm){
		$this->add_to_receipt($pm->getParamValue('item_id'),0,1);
	}
	public function add_product($pm){
		$this->add_to_receipt($pm->getParamValue('item_id'),
			$pm->getParamValue('doc_production_id'),0);
	}
	public function clear($pm){
		$link = $this->getDbLinkMaster();
		$link->query(
			sprintf("DELETE FROM receipts WHERE user_id=%d",
			$_SESSION['user_id']));
	}
	public function close($pm){
		$store_id = $_SESSION['global_store_id'];
		if (!isset($store_id)){
			throw new Exception('Не задан салон!');
		}
		$link = $this->getDbLinkMaster();
		$link->query(
			sprintf("SELECT close_receipt(%d,%d,'%s'::payment_types)",
			$store_id,$_SESSION['user_id'],
			$pm->getParamValue('payment_type')));
		//SMS
		$ar = $this->getDbLink()->query_first('SELECT const_cel_phone_for_sms_val() AS val');
		send_service_sms($ar['val'],'Продажа');					
	}
	public function edit_item($pm){
		$doc_production_id = $pm->getParamValue('doc_production_id');
		$quant = $pm->getParamValue('quant');
		$price = $pm->getParamValue('price');
		$item_type = $pm->getParamValue('item_type');
		$item_id = $pm->getParamValue('item_id');
		$link = $this->getDbLinkMaster();
		$q = sprintf(
		"UPDATE receipts
		SET quant = %d,price=%f,total=ROUND(%d*%f,2)
		WHERE user_id = %d AND item_id = %d
		AND item_type = %d
		AND (%d=1 OR (%d=0 AND %d=doc_production_id))
		",$quant,$price,$quant,$price,
		$_SESSION['user_id'],$item_id,
		$item_type,
		$item_type,$item_type,$doc_production_id);
		$link->query($q);
	}
	
</xsl:template>

</xsl:stylesheet>