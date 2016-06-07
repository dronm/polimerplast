<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'DOCProduction'"/>
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
		send_service_sms($ar['val'],'Комплектация');				
	}
	public function fill_on_spec($pm){
		if (isset($_SESSION['global_store_id'])){
			$store_id = $_SESSION['global_store_id'];
		}
		else{
			$store_id = $pm->getParamValue('store_id');
		}
		if (!isset($store_id)){
			throw new Exception("Не задан салон!");
		}
		
		$link = $this->getDbLinkMaster();
		$link->query(
		sprintf("SELECT doc_productions_fill_on_spec(%d,%d,%d,%f)",
		$_SESSION['LOGIN_ID'],$store_id,$pm->getParamValue('product_id'),
		$pm->getParamValue('product_quant'))
		);
	}	
	public function get_balance_list($pm){
		if (isset($_SESSION['global_store_id'])){
			$store_id = $_SESSION['global_store_id'];
		}
		else{
			$store_id = $pm->getParamValue('store_id');
		}
		if (!isset($store_id)){
			throw new Exception("Не задан салон!");
		}	
		$this->addNewModel(
			sprintf("SELECT * FROM doc_productions_list_with_balance(%d)",
			$store_id)
		,'get_balance_list');			
	}
	public function get_current_doc_cost($pm){
		if (isset($_SESSION['global_store_id'])){
			$store_id = $_SESSION['global_store_id'];
		}
		else{
			$store_id = $pm->getParamValue('store_id');
		}
		if (!isset($store_id)){
			throw new Exception("Не задан салон!");
		}	
		$this->addNewModel(
			sprintf("SELECT * FROM doc_productions_open_doc_cost(%d,%d)",
			$store_id,$_SESSION['LOGIN_ID'])
		,'get_current_doc_cost');			
	}
	public function add_to_open_doc($pm){
		if (isset($_SESSION['LOGIN_ID'])){
			$login_id = $_SESSION['LOGIN_ID'];
		}
		else{
			throw new Exception("Не задан логин!");
		}
		$product_id = $pm->getParamValue('product_id');
		$material_id = $pm->getParamValue('material_id');
		
		$link = $this->getDbLinkMaster();
		$link->query(sprintf('
		WITH mat_on_norm AS (
			SELECT
				material_quant*product_quant AS quant
			FROM specifications
			WHERE product_id=%d AND material_id=%d
			)
		INSERT INTO doc_productions_t_tmp_materials
		(login_id,material_id,quant_norm,quant,quant_waste)
		VALUES (%d,%d,COALESCE((SELECT quant FROM mat_on_norm),0),
			COALESCE(
			(SELECT CASE
					WHEN quant=0 THEN 1
					ELSE quant
				END
			FROM mat_on_norm
			),1),
			0)',
		$product_id,
		$material_id,
		$login_id,
		$material_id
		));
	}
	public function get_print($pm){
		$this->addNewModel(
			sprintf(
			'SELECT number,
			get_date_str_rus(date_time::date) AS date_time_descr,
			store_descr,
			user_descr,
			product_id,
			product_descr,
			format_quant(quant) AS quant,
			price_descr,
			sum_descr,
			mat_sum_descr,
			on_norm
			FROM doc_productions_list_view
			WHERE id=%d',
			$pm->getParamValue('doc_id')),
		'head');
		$this->addNewModel(
			sprintf(
			'SELECT 
			t.line_number,
			m.name AS material_descr,
			format_quant(t.quant) AS quant,
			format_quant(t.quant_norm) AS quant_norm,
			format_money(m.price) AS price_descr,
			format_money(m.price*t.quant) AS total_descr
			FROM doc_productions_t_materials AS t
			LEFT JOIN materials AS m ON m.id=t.material_id
			WHERE doc_id=%d
			ORDER BY line_number',
			$pm->getParamValue('doc_id')),
		'materials');		
	}
	public function get_details($pm){		
		$model = new DOCProductionMaterialList_Model($this->getDbLink());	
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