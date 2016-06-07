<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'RGProductOrder'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>
class <xsl:value-of select="@id"/>_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	public function get_balance($pm){
		if (isset($_SESSION['user_store_id'])){
			$store_filter = $_SESSION['user_store_id'];
		}
		else{
			$store_filter = "";
		}
		$this->addNewModel(
		"SELECT b.store_id,s.name AS store_descr,
		b.product_id,p.name AS product_descr,
		b.quant,
		b.product_order_type,
		get_product_order_types_descr(b.product_order_type) AS product_order_type_descr
		FROM rg_product_orders_balance('{".$store_filter."}','{}','{}') AS b
		LEFT JOIN stores AS s ON s.id=b.store_id
		LEFT JOIN products AS p ON p.id=b.product_id
		",
		'get_balance');
	}	
</xsl:template>

</xsl:stylesheet>