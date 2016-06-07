<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'DOCSale'"/>
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
class <xsl:value-of select="@id"/>_Controller extends ControllerSQLDOC{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	public function insert($pm){
		//doc owner
		$pm->setParamValue('user_id',$_SESSION['user_id']);		
		parent::insert();		
		
		//SMS
		$ar = $this->getDbLink()->query_first('SELECT const_cel_phone_for_sms_val() AS val');
		send_service_sms($ar['val'],'Продажа');		
	}
	public function delete(){
		$link = $this->getDbLinkMaster();
		$link->query(
		sprintf("DELETE FROM doc_sales_t_tmp_materials WHERE login_id=%s",
		$_SESSION['LOGIN_ID']));		
		$link->query(
		sprintf("DELETE FROM doc_sales_t_tmp_products WHERE login_id=%s",
		$_SESSION['LOGIN_ID']));				
		parent::delete();		
	}
	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	public function get_print($pm){
		$this->addNewModel(
			sprintf(
			'SELECT number,
			get_date_str_rus(date_time::date) AS date_time_descr,
			store_descr,
			user_descr,
			payment_type_descr,
			format_money(total) AS total_descr
			FROM doc_sales_list_view
			WHERE id=%d',
			$pm->getParamValue('doc_id')),
		'head');
		$this->addNewModel(
			sprintf(
			'SELECT line_number,
			material_descr,
			quant,
			format_money(price) AS price_descr,
			format_money(total) AS total_descr,
			total
			FROM doc_sales_t_materials_list_view
			WHERE doc_id=%d
			ORDER BY line_number',
			$pm->getParamValue('doc_id')),
		'materials');		
		$this->addNewModel(
			sprintf(
			'SELECT line_number,
			product_descr,
			quant,
			format_money(price) AS price_descr,
			format_money(total) AS total_descr
			FROM doc_sales_t_products_list_view
			WHERE doc_id=%d
			ORDER BY line_number',
			$pm->getParamValue('doc_id')),
		'products');				
	}
	public function get_prod_details($pm){		
		$model = new DOCSaleProductList_Model($this->getDbLink());	
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
		
		$model->select(FALSE,$where,$order,
			$limit,$fields,NULL,NULL,
			$calc_total,TRUE);
		$this->addModel($model);
	}
	public function get_mat_details($pm){		
		$model = new DOCSaleMaterialList_Model($this->getDbLink());	
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
		
		$model->select(FALSE,$where,$order,
			$limit,$fields,NULL,NULL,
			$calc_total,TRUE);
		$this->addModel($model);
	}
	
</xsl:template>

</xsl:stylesheet>