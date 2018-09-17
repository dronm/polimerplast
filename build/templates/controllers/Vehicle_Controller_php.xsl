<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'Vehicle'"/>
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

class <xsl:value-of select="@id"/>_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	public function get_select_list($pm){
		$this->setListModelId('VehicleSelectList_Model');
		parent::get_list($pm);
	}
	
	private function get_driver_attrs($pm,$p,&amp;$fields){
		if ($pm->getParamValue('driver_cel_phone')){
			$fields = 'cel_phone='.$p->getDbVal('driver_cel_phone');
		}
		if ($pm->getParamValue('driver_drive_perm')){
			$fields.= ($fields=='')? '':', ';
			$fields.= 'drive_perm='.$p->getDbVal('driver_drive_perm');
		}	
	}
	
	private function update_driver($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();
	
		if ($pm->getParamValue('driver_descr')){
			//изменили ФИО водителя
			
			//1c
			$res = array();
			$ext_ref = ExtProg::getPersonRefOnName($pm->getParamValue('driver_descr'),$res);
			if ($ext_ref){
				if (
				count($res)
				&amp;&amp; $res['drive_perm'] &amp;&amp; strlen($res['drive_perm'])
				&amp;&amp; !$pm->getParamValue('driver_drive_perm')
				){
					$pm->setParamValue('driver_drive_perm',$res['drive_perm']);
				}
			}		
			if (!$ext_ref){
				throw new Exception(sprintf("Физ.лицо '%s' не найдено в 1с!",$pm->getParamValue('driver_descr')));
			}
			
			$ar = $this->getDbLinkMaster()->query_first(sprintf(
			"SELECT * FROM drivers WHERE name=%s LIMIT 1",
			$p->getDbVal('driver_descr')
			));
			if (count($ar) &amp;&amp; isset($ar['id'])){
				//есть такой водитель - изменяем
				$fields = '';
				$this->get_driver_attrs($pm,$p,$fields);
				
				if (strlen($fields)){
					if ($ext_ref){
						$fields.= sprintf(",ext_id='%s'",$ext_ref);
					}
					$this->getDbLinkMaster()->query(sprintf(
					"UPDATE drivers SET %s WHERE id=%d",
					$fields,
					$ar['id']
					));				
				}
			}
			else{
				//нет такого - заводим								
				$ar = $this->getDbLinkMaster()->query_first(sprintf(
				"INSERT INTO drivers (name,cel_phone,drive_perm,ext_id)
				VALUES (%s,%s,%s,'%s')
				RETURNING id",
				$p->getDbVal('driver_descr'),
				($pm->getParamValue('driver_cel_phone'))? $p->getDbVal('driver_cel_phone'):'NULL',
				($pm->getParamValue('driver_drive_perm'))? $p->getDbVal('driver_drive_perm'):'NULL',
				$ext_ref? $ext_ref:'NULL'
				));
				
				
			}
			$pm->setParamValue('driver_id',$ar['id']);
		}
		else if ($pm->getParamValue('old_id')){
			//возможно изменили данные старого водителя
			$fields = '';
			$this->get_driver_attrs($pm,$p,$fields);
			if (strlen($fields)){
				$this->getDbLinkMaster()->query(sprintf(
				"UPDATE drivers SET %s WHERE id=(SELECT v.driver_id FROM vehicles v WHERE v.id=%d)",
				$fields,
				$p->getDbVal('old_id')
				));				
			}
		}		
	}
	
	public function update($pm){
		try{
			$this->getDbLinkMaster()->query("BEGIN");
			$this->update_driver($pm);
			parent::update($pm);
			$this->getDbLinkMaster()->query("COMMIT");
		}
		catch(Exception $e){
			$this->getDbLinkMaster()->query("ROLLBACK");
			throw $e;
		}
	}
	
	public function insert($pm){
		try{
			$this->getDbLinkMaster()->query("BEGIN");
			$this->update_driver($pm);
			parent::insert($pm);
			$this->getDbLinkMaster()->query("COMMIT");
		}
		catch(Exception $e){
			$this->getDbLinkMaster()->query("ROLLBACK");
			throw $e;
		}			
	}
	
	
</xsl:template>

</xsl:stylesheet>
