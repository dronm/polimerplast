<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'Kladr'"/>
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
	const COMPLETE_RES_COUNT=5;
	
	public function __construct($dbLinkMaster=NULL){
		$kladr_link = new DB_Sql();
		$kladr_link->appname		= APP_NAME;
		$kladr_link->technicalemail = TECH_EMAIL;
		$kladr_link->reporterror	= DEBUG;
		$kladr_link->database		= 'kladr';
		$kladr_link->connect(DB_SERVER,DB_USER,DB_PASSWORD);
		//$kladr_link->set_error_verbosity((DEBUG)? PGSQL_ERRORS_VERBOSE:PGSQL_ERRORS_TERSE);
		
		parent::__construct($dbLinkMaster,$kladr_link);<xsl:apply-templates/>
	}	
	public function get_region_list($pm){
		$dbLink = $this->getDbLink();
		$pattern = $dbLink->escape_string($pm->getParamValue('pattern'));
		$q = sprintf("SELECT 
				code AS region_code,
				name,
				name||' '||socr AS full_name
			FROM kladr
			WHERE code LIKE '__000000000__'
				AND lower(name) LIKE lower('%s%%')
			ORDER BY name LIMIT %d",
			$pattern,
			Kladr_Controller::COMPLETE_RES_COUNT);
		$this->addNewModel($q);
	}	
	public function get_raion_list($pm){
		$dbLink = $this->getDbLink();
		$pattern = $dbLink->escape_string($pm->getParamValue('pattern'));
		$region_code = substr($dbLink->escape_string($pm->getParamValue('region_code')),0,2);
	
		$q = sprintf("SELECT 
				code AS raion_code,
				name,
				name||' '||socr AS full_name
			FROM kladr
			WHERE
				code LIKE '%s___00000000'
				AND code &lt;&gt; '%s00000000000'
				AND lower(name) LIKE lower('%s%%')
			ORDER BY name LIMIT %d",
			$region_code,
			$region_code,
			$pattern,
			Kladr_Controller::COMPLETE_RES_COUNT);
		$this->addNewModel($q);
	}		
	public function get_naspunkt_list($pm){
		$dbLink = $this->getDbLink();
		$pattern = $dbLink->escape_string($pm->getParamValue('pattern'));
		$region_code = substr($dbLink->escape_string($pm->getParamValue('region_code')),0,2);
		$raion_code_str = $dbLink->escape_string($pm->getParamValue('raion_code'));
		$raion_code = substr($raion_code_str,2,3);
		if (!strlen($raion_code)||$raion_code_str=='null'){
			$raion_code = '000';
		}
		
		$q = sprintf("SELECT 
				code AS naspunkt_code,
				name,
				name||' '||socr AS full_name
			FROM kladr
			WHERE code LIKE '%s%s________'
				AND code &lt;&gt; '%s%s00000000'
				AND lower(name) LIKE lower('%s%%')
			ORDER BY name LIMIT %d",
			$region_code,
			$raion_code,
			$region_code,
			$raion_code,
			$pattern,
			Kladr_Controller::COMPLETE_RES_COUNT);
		$this->addNewModel($q);
	}			
	public function get_gorod_list($pm){
		$dbLink = $this->getDbLink();
		$pattern = $dbLink->escape_string($pm->getParamValue('pattern'));
		$region_code = substr($dbLink->escape_string($pm->getParamValue('region_code')),0,2);
		$raion_code_str = $dbLink->escape_string($pm->getParamValue('raion_code'));
		$raion_code = substr($raion_code_str,2,3);
		
		if (!strlen($raion_code)||$raion_code_str=='null'){
			$raion_code = '000';
		}
		
		$q = sprintf("SELECT 
				code AS gorod_code,
				name,
				name||' '||socr AS full_name
			FROM kladr
			WHERE code LIKE '%s%s___00000'
				AND code &lt;&gt; '%s%s00000000'
				AND code &lt;&gt; '%s00000000000'
				AND lower(name) LIKE lower('%s%%')
			ORDER BY name LIMIT %d",
			$region_code,
			$raion_code,
			$region_code,
			$raion_code,
			$region_code,
			$pattern,
			Kladr_Controller::COMPLETE_RES_COUNT);
		$this->addNewModel($q);
	}				
	public function get_ulitsa_list($pm){
		$dbLink = $this->getDbLink();
		$pattern = $dbLink->escape_string($pm->getParamValue('pattern'));
		$region_code = substr($dbLink->escape_string($pm->getParamValue('region_code')),0,2);		
		if (!$region_code||!is_numeric($region_code)){
			throw new Exception('Не задан регион!');
		}		
		
		$raion_code_str = $dbLink->escape_string($pm->getParamValue('raion_code'));
		$raion_code = substr($raion_code_str,2,3);
		if (!strlen($raion_code)||$raion_code_str=='null'){
			$raion_code = '000';
		}		
		$naspunkt_code = substr($dbLink->escape_string($pm->getParamValue('naspunkt_code')),5,3);
		$gorod_code = substr($dbLink->escape_string($pm->getParamValue('gorod_code')),5,3);
		if ((!$naspunkt_code||!is_numeric($naspunkt_code))&amp;&amp;(!$gorod_code||!is_numeric($gorod_code))){
			throw new Exception('Не задан ни город ни населенный пункт!');
		}
		else if (!$naspunkt_code||!is_numeric($naspunkt_code)){
			$naspunkt_code='000';
		}
		else if (!$gorod_code||!is_numeric($gorod_code)){
			$gorod_code='000';
		}		
		$q = sprintf("SELECT 
				code AS ulitza_code,
				name,
				name||' '||socr AS full_name
			FROM street
			WHERE code LIKE '%s%s%s%s%%'
				AND lower(name) LIKE lower('%s%%')
			ORDER BY name LIMIT %d",
			$region_code,
			$raion_code,
			$gorod_code,
			$naspunkt_code,
			$pattern,
			Kladr_Controller::COMPLETE_RES_COUNT);
		//throw new Exception($q);
		$this->addNewModel($q);
	}				
	public function query_first($q,&amp;$res){
		$res = $this->getDbLink()->query_first($q);
	}
}
<![CDATA[?>]]>
</xsl:template>

</xsl:stylesheet>