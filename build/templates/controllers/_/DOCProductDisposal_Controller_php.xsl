<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'DOCProductDisposal'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>
class <xsl:value-of select="@id"/>_Controller extends ControllerSQLDOC{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}
	
	public function insert($pm){
		//doc owner
		$pm->setParamValue('user_id',$_SESSION['user_id']);		
		parent::insert();		
	}
	public function fill_on_spec($pm){
		$link = $this->getDbLinkMaster();		
		$link->query(
		sprintf("SELECT doc_product_disposals_fill_on_spec(%d,%d,%f)",
		$_SESSION['LOGIN_ID'],$pm->getParamValue('product_id'),
		$pm->getParamValue('product_quant'))
		);
	}	
	public function before_open($pm){
	}	
	
	public function get_print($pm){
		$this->addNewModel(
			sprintf(
			'SELECT number,
			product_descr,
			doc_production_descr,
			get_date_str_rus(date_time::date) AS date_time_descr,
			store_descr,
			user_descr,
			explanation
			FROM doc_product_disposals_list_view
			WHERE id=%d',
			$pm->getParamValue('doc_id')),
		'head');
		$this->addNewModel(
			sprintf(
			'SELECT 
			m.name AS material_descr,
			format_quant(ra.quant) AS quant
			FROM ra_materials AS ra
			LEFT JOIN materials AS m ON m.id=ra.material_id
			WHERE doc_id=%d
			ORDER BY material_descr',
			$pm->getParamValue('doc_id')),
		'materials');		
	}
	public function get_details($pm){		
		$model = new DOCProductDisposalMaterialList_Model($this->getDbLink());	
		$from = null; $count = null;
		$limit = $this->limitFromParams($pm,$from,$count);
		$calc_total = ($count>0);
		if ($from){
			$model->setListFrom($from);
		}
		if ($count){
			$model->setRowsPerPage($count);
		}		
		$order = $this->orderFromParams($pm);
		$where = $this->conditionFromParams($pm,$model);
		$fields = $this->fieldsFromParams($pm);		
		$material_group_id = $where->getFieldValueForDb('material_group_id','=',0,0);
		if ($material_group_id==0){
			//throw new Exception($material_group_id);
			$where->deleteField('material_group_id','=');
		}
		
		$model->select(FALSE,$where,$order,
			$limit,$fields,NULL,NULL,
			$calc_total,TRUE);
		//
		$this->addModel($model);
	}		
}
<![CDATA[?>]]>
</xsl:template>
</xsl:stylesheet>