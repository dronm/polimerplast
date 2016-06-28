<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'DOCOrderDOCTProduct'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>

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
	public function update($pm){
		if ($_SESSION['role_id']=='client'){
			$pm->setParamValue('client_id',$_SESSION['global_client_id']);
		}		
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
		
		/* все кроме производства и разделения
		там по-другому*/
		if ($_SESSION['role_id']!='production'
		&amp;&amp;isset($_REQUEST['warehouse_id'])
		&amp;&amp;isset($_REQUEST['client_id'])
		&amp;&amp;isset($_REQUEST['deliv_to_third_party'])
		){
			$product_id = (!is_null($pm->getParamValue('product_id')))?
				$params->getDbVal('product_id'):'NULL';
			$mes_length = (!is_null($pm->getParamValue('mes_length')))?
				$params->getDbVal('mes_length'):'NULL';
			$mes_width = (!is_null($pm->getParamValue('mes_width')))?
				$params->getDbVal('mes_width'):'NULL';
			$mes_height = (!is_null($pm->getParamValue('mes_height')))?
				$params->getDbVal('mes_height'):'NULL';
			$quant = (!is_null($pm->getParamValue('quant')))?
				$params->getDbVal('quant'):'NULL';
			$measure_unit_id = (!is_null($pm->getParamValue('measure_unit_id')))?
				$params->getDbVal('measure_unit_id'):'NULL';
			$pack_exists = (!is_null($pm->getParamValue('pack_exists')))?
				$params->getDbVal('pack_exists'):'NULL';
			$pack_in_price = (!is_null($pm->getParamValue('pack_in_price')))?
				$params->getDbVal('pack_in_price'):'NULL';
			$price_edit = (!is_null($pm->getParamValue('price_edit')))?
				$params->getDbVal('price_edit'):'NULL';
			$price = (!is_null($pm->getParamValue('price')))?
				$params->getDbVal('price'):'NULL';
		
			$ar = $this->getDbLink()->query_first(
			sprintf(
			"WITH prod AS (
				SELECT
					coalesce(%s,p.product_id) AS product_id,
					coalesce(%s,p.mes_length) AS mes_length,
					coalesce(%s,p.mes_width) AS mes_width,
					coalesce(%s,p.mes_height) AS mes_height,
					coalesce(%s,p.quant) AS quant,
					coalesce(%s,p.measure_unit_id) AS measure_unit_id,
					coalesce(%s,p.pack_exists) AS pack_exists,
					coalesce(%s,p.pack_in_price) AS pack_in_price,
					coalesce(%s,p.price_edit) AS price_edit,
					coalesce(%s,p.price) AS price
				FROM doc_orders_t_tmp_products p
				WHERE p.login_id=%d AND p.line_number=%d
			)
			SELECT * FROM doc_order_totals(
				%d,%d,
				(SELECT t.product_id FROM prod t),
				(SELECT t.mes_length FROM prod t),
				(SELECT t.mes_width FROM prod t),
				(SELECT t.mes_height FROM prod t),
				(SELECT t.quant FROM prod t),
				(SELECT t.measure_unit_id FROM prod t),
				(SELECT t.pack_exists FROM prod t),
				(SELECT t.pack_in_price FROM prod t),
				%s,
				(SELECT t.price_edit FROM prod t),
				(SELECT t.price FROM prod t)
				)
			AS (
				base_quant numeric,
				volume_m numeric,
				weight_t numeric,
				price numeric,
				total numeric,
				total_pack numeric)",
			$product_id,
			$mes_length,
			$mes_width,
			$mes_height,
			$quant,
			$measure_unit_id,
			$pack_exists,
			$pack_in_price,
			$price_edit,
			$price,
			$_SESSION['LOGIN_ID'],
			$params->getDbVal('old_line_number'),
			$params->getDbVal('warehouse_id'),
			$params->getDbVal('client_id'),
			$params->getDbVal('deliv_to_third_party')
			));
		
			if (is_array($ar)&amp;&amp;count($ar)){
				$pm->setParamValue('quant_base_measure_unit',$ar['base_quant']);
				$pm->setParamValue('volume',$ar['volume_m']);
				$pm->setParamValue('weight',$ar['weight_t']);
				$pm->setParamValue('price',$ar['price']);
				$pm->setParamValue('total',$ar['total']);
				$pm->setParamValue('total_pack',$ar['total_pack']);
			}					
		}
		else if (isset($_REQUEST['quant_base_measure_unit'])){
			/*изменилось базовое кол-во
			!!!ЦЕНА НЕ ДОЛЖНА МЕНЯТЬСЯ!!!
			*/
			$ar = $this->getDbLink()->query_first(sprintf(
				"SELECT
					tp.price,
					p.base_measure_unit_vol_m AS vm,
					p.base_measure_unit_weight_t AS wt,
					(tp.pack_exists AND tp.pack_in_price=FALSE) AS need_pack_price
				FROM doc_orders_t_tmp_products tp
				LEFT JOIN products p ON p.id=tp.product_id
				WHERE tp.login_id=%d AND tp.line_number=%d",
				$_SESSION['LOGIN_ID'],
				$params->getParamById('old_line_number')
			));
			if (is_array($ar)&amp;&amp;count($ar)){
				$quant_bmu = $params->getParamById('quant_base_measure_unit');
				$pm->setParamValue('total',
					round($ar['price']*$quant_bmu,2)
				);
				$pm->setParamValue('volume',
					round($ar['vm']*$quant_bmu,3)
				);
				$pm->setParamValue('weight',
					round($ar['wt']*$quant_bmu,2)
				);
				if ($ar['need_pack_price']=='t'){
					$ar = $this->getDbLink()->query_first(sprintf(
					"WITH
					tmp_t AS (
						SELECT
							t.product_id,
							t.mes_length,
							t.mes_width,
							t.mes_height,
							t.quant
						FROM doc_orders_t_tmp_products AS t
						WHERE t.login_id=%d
							AND t.line_number=%d
					)				
					SELECT
						CASE WHEN tp.quant_base_measure_unit>0 THEN
							ROUND(tp.total_pack/tp.quant_base_measure_unit,2)
						ELSE 0
						END AS pack_price						
					FROM doc_orders_t_products AS tp
					WHERE
						tp.doc_id=%d
						AND tp.product_id=(SELECT t.product_id FROM tmp_t t)
						AND tp.mes_length=(SELECT t.mes_length FROM tmp_t t)
						AND tp.mes_width=(SELECT t.mes_width FROM tmp_t t)
						AND tp.mes_height=(SELECT t.mes_height FROM tmp_t t)",
					$_SESSION['LOGIN_ID'],
					$params->getParamById('old_line_number'),
					$_SESSION['doc_order_id']
					));
					
					$pm->setParamValue('total_pack',
						round(
							$ar['pack_price']*
							$quant_bmu*
							$params->getParamById('quant')
						,2)
					);
				}
			}
		}
		//***************
		parent::update($pm);
	}
	public function insert($pm){
		if ($_SESSION['role_id']=='client'){
			$pm->setParamValue('client_id',$_SESSION['global_client_id']);
		}		
	
		$params = new ParamsSQL($pm,$this->getDbLink());
		$params->addAll();
	
		$ar = $this->getDbLink()->query_first(
		sprintf("SELECT * FROM doc_order_totals(
			%d,%d,%d,%d,%d,%d,%f,%d,%s,%s,%s,%s,%f)
		AS (
			base_quant numeric,
			volume_m numeric,
			weight_t numeric,
			price numeric,
			total numeric,
			total_pack numeric)",
		$params->getParamById('warehouse_id'),
		$params->getParamById('client_id'),
		$params->getParamById('product_id'),
		$params->getParamById('mes_length'),
		$params->getParamById('mes_width'),
		$params->getParamById('mes_height'),
		$params->getParamById('quant'),
		$params->getParamById('measure_unit_id'),
		$params->getParamById('pack_exists'),
		$params->getParamById('pack_in_price'),
		$params->getParamById('deliv_to_third_party'),
		$params->getParamById('price_edit'),
		$params->getParamById('price')		
		));
	
		if (is_array($ar)&amp;&amp;count($ar)){
			$pm->setParamValue('quant_base_measure_unit',$ar['base_quant']);
			$pm->setParamValue('volume',$ar['volume_m']);
			$pm->setParamValue('weight',$ar['weight_t']);
			$pm->setParamValue('price',$ar['price']);
			$pm->setParamValue('total',$ar['total']);
			$pm->setParamValue('total_pack',$ar['total_pack']);
		}						
		//**************
		parent::insert($pm);
	}
	public function get_list($pm){
		$this->addNewModel(sprintf(
		"SELECT
			t.*,
			(SELECT ph.oper
			FROM doc_orders_products_history AS ph
			WHERE ph.doc_orders_states_id=
				(SELECT st.id
				FROM doc_orders_states st
				WHERE st.doc_orders_id=%d
				ORDER BY st.date_time DESC
				LIMIT 1)
				AND ph.product_id=t.product_id
			LIMIT 1
			) AS oper
			--string_to_array(ph.fields,',','') AS fileds,
			--string_to_array(ph.old_vals,',','') AS old_vals			
			
		FROM doc_orders_t_tmp_products_list AS t
		WHERE login_id=%d",
		$_SESSION['doc_order_id'],
		$_SESSION['LOGIN_ID']
		),
		'DOCOrderDOCTProductList_Model');
	}	
	public function get_object_for_divis($pm){
		$this->addNewModel(sprintf(
		"SELECT
			t.*,
			--!!!для деления!!!
			d_orig.quant AS doc_quant_orig,
			d_orig.quant_base_measure_unit AS quant_orig
		FROM doc_orders_t_tmp_products_dialog AS t
		LEFT JOIN doc_orders_t_products d_orig
			ON d_orig.doc_id=%d
			AND d_orig.product_id=t.product_id
			AND d_orig.mes_length=t.mes_length
			AND d_orig.mes_width=t.mes_width
			AND d_orig.mes_height=t.mes_height
		WHERE t.login_id=%d AND t.line_number=%d",
		$_SESSION['doc_order_id'],
		$_SESSION['LOGIN_ID'],
		$pm->getParamValue('line_number')
		
		),
		'DOCOrderDOCTProductDialog_Model');
	}	
	
</xsl:template>

</xsl:stylesheet>
