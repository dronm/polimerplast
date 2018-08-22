<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'Firm'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>
require_once('functions/ExtProg.php');

require_once(FRAME_WORK_PATH.'basic_classes/ParamsSQL.php');
class <xsl:value-of select="@id"/>_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	private function check_firm($pm){
		$ext_ref = ExtProg::getFirmRefOnName($pm->getParamValue('name'));
		if (!$ext_ref){
			throw new Exception('Соответствие в 1с не найдено!');
		}
		$pm->setParamValue('ext_id',$ext_ref);
	}
	public function insert($pm){
		$this->check_firm($pm);
		parent::insert($pm);
	}
	public function update($pm){
		if (!$pm->getParamValue('deleted')){
			$this->check_firm($pm);
		}
		parent::update($pm);
	}	
</xsl:template>

</xsl:stylesheet>
