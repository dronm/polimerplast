<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_php.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'ClientDestination'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
</xsl:template>

<xsl:template match="controller"><![CDATA[<?php]]>
<xsl:call-template name="add_requirements"/>

require_once('common/OSRM.php');
require_once('common/geo/yandex.php');
require_once('common/geo/YndxReverseCode.php');
require_once(FRAME_WORK_PATH.'basic_classes/ParamsSQL.php');
require_once(USER_CONTROLLERS_PATH.'Kladr_Controller.php');

class <xsl:value-of select="@id"/>_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);<xsl:apply-templates/>
	}	
	<xsl:call-template name="extra_methods"/>
}
<![CDATA[?>]]>
</xsl:template>

<xsl:template name="extra_methods">
	private function set_addr_from_gps($lat,$lon,&amp;$pm){
		$rev_coder = new YndxReverseCode();
		$rev_coder->getAddressForCoords($lat,$lon);			
		if ($rev_coder->state){
			$pm->setParamValue('region',$rev_coder->state);
		}
		if ($rev_coder->city){
			$pm->setParamValue('gorod',$rev_coder->city);
		}
		if ($rev_coder->street){
			$pm->setParamValue('ulitza',$rev_coder->street);
		}
		if ($rev_coder->house){
			$pm->setParamValue('dom',$rev_coder->house);
		}
	
	}
	
	public function set_near_road($pm){
		$zone_center = $pm->getParamValue('zone_center');
		if ($zone_center){			
			$points = explode(' ',$zone_center);
			if (count($points)&gt;=2){
				$osrm = new OSRM(OSRM_PROTOCOLE,OSRM_HOST,OSRM_PORT);
				$road_lat=NULL;$road_lon=NULL;
				$osrm->getNearestRoadCoord(
					$points[1],$points[0],
					$road_lat,$road_lon
					);
				$pm->setParamValue('near_road_lon',$road_lon);
				$pm->setParamValue('near_road_lat',$road_lat);
			}
		}
	}

	public function insert($pm){
		$zone_center = $pm->getParamValue('zone_center');
		$region = $pm->getParamValue('region');
		$raion = $pm->getParamValue('raion');
		$gorod = $pm->getParamValue('gorod');
		$naspunkt = $pm->getParamValue('naspunkt');
		$ulitza = $pm->getParamValue('ulitza');
		$dom = $pm->getParamValue('dom');
		$korpus = $pm->getParamValue('korpus');
		
		$addr_exists = strlen($region.$raion.$gorod.$naspunkt.$ulitza.$dom.$korpus);
		
		if (!$zone_center){
			//нет маркера зоны
			if (!strlen($region.$raion.$gorod.$naspunkt.$ulitza.$korpus.$dom)){
				throw new Exception("Не задан адрес!");
			}
			//Обратное геокодирование
			$res = array();
			$addr = array('region'=>$region,
					'raion'=>$raion,
					'city'=>$gorod,
					'naspunkt'=>$naspunkt,
					'street'=>$ulitza,
					'building'=>$dom,
					'korpus'=>$korpus
			);
			
			get_inf_on_address($addr,$res);
			
			if ($res['lon_pos']&amp;&amp;$res['lat_pos']){
				$pm->setParamValue('zone_center',
					$res['lon_pos'].' '.$res['lat_pos']
					);
			}
		}
		else if(!$addr_exists){
			//есть маркер но нет адреса
			$points = explode(' ',$zone_center);
			if (count($points)&gt;=2){
				$this->set_addr_from_gps($points[1],$points[0],$pm);
			
			}			
		}
		else if(!$zone_center&amp;&amp;!$addr_exists){
			throw new Exception("Не задан ни адрес, ни координаты объекта!");
		}
		$this->set_near_road($pm);
		parent::insert($pm);
	}
	public function update($pm){
		/* если адрес изменился а маркера нет*/
		$zone_center = $pm->getParamValue('zone_center');
		$region = $pm->getParamValue('region');
		$raion = $pm->getParamValue('raion');
		$gorod = $pm->getParamValue('gorod');
		$naspunkt = $pm->getParamValue('naspunkt');
		$ulitza = $pm->getParamValue('ulitza');
		$dom = $pm->getParamValue('dom');
		$korpus = $pm->getParamValue('korpus');
		
		$addr_exists = strlen($region.$raion.$gorod.$naspunkt.$ulitza.$korpus.$dom);
		
		if (!$zone_center){
			//нет маркера зоны
			
			if ($addr_exists){
				/* ИЗМЕНИЛСЯ АДРЕС
				Обратное геокодирование
				*/
				
				$old_id = intval($pm->getParamValue('old_id'));
				$db_addr_res = $this->getDbLink()->query_first(sprintf(
				"SELECT
					region,
					raion,
					gorod,
					naspunkt,
					ulitza,
					dom,
					korpus
				FROM client_destinations
				WHERE id=%d",
				$old_id
				));
				if (!is_array($db_addr_res)||!count($db_addr_res)){
					throw new Exception("Неверный id!");
				}
				
				$region = (isset($region))? $region:$db_addr_res['region'];
				$raion = (isset($raion))? $raion:$db_addr_res['raion'];
				$gorod = (isset($gorod))? $gorod:$db_addr_res['gorod'];
				$naspunkt = (isset($naspunkt))? $naspunkt:$db_addr_res['naspunkt'];
				$ulitza = (isset($ulitza))? $ulitza:$db_addr_res['ulitza'];
				$dom = (isset($dom))? $dom:$db_addr_res['dom'];
				$korpus = (isset($korpus))? $korpus:$db_addr_res['korpus'];
				
				$res = array();
				$addr = array('region'=>$region,
						'raion'=>$raion,
						'city'=>$gorod,
						'naspunkt'=>$naspunkt,
						'street'=>$ulitza,
						'building'=>$dom,
						'korpus'=>$korpus
				);
				
				get_inf_on_address($addr,$res);
				
				if ($res['lon_pos']&amp;&amp;$res['lat_pos']){
					$pm->setParamValue('zone_center',
						$res['lon_pos'].' '.$res['lat_pos']
						);
				}
			}
		}
		else if(!$addr_exists){
			/*есть маркет но нет адреса
			проверим адрес
			*/
			$ar = $this->getDbLink()->query_first(sprintf(
			"SELECT
				region||raion||gorod||naspunkt||ulitza||dom||korpus AS addr
			FROM client_destinations
			WHERE id=%d",
			intval($pm->getParamValue('old_id'))
			));
			if (!is_array($ar)||!count($ar)){
				throw new Exception("Неверный id!");
			}
			if (!strlen($ar['addr'])){
				//все таки нет адреса - определим по GPS
				$points = explode(' ',$zone_center);
				if (count($points)&gt;=2){
					$this->set_addr_from_gps($points[1],$points[0],$pm);
				}				
			}
		}
		$this->set_near_road($pm);
		parent::update($pm);
	}	
	public function get_address_gps($pm){
		$p = new ParamsSQL($pm,$this->getDbLink());
		$p->addAll();
		
		$q = "";
		if ($code=$p->getParamById('region_code')){
			$q.=($q=="")? "":", ";
			$q.=sprintf("(SELECT k.name FROM kladr k WHERE k.code=%s) AS region",$code);
		}
		if ($code=$p->getParamById('raion_code')){
			$q.=($q=="")? "":", ";
			$q.=sprintf("(SELECT k.name FROM kladr k WHERE k.code=%s) AS raion",$code);
		}
		if ($code=$p->getParamById('naspunkt_code')){
			$q.=($q=="")? "":", ";
			$q.=sprintf("(SELECT k.name FROM kladr k WHERE k.code=%s) AS naspunkt",$code);
		}
		if ($code=$p->getParamById('gorod_code')){
			$q.=($q=="")? "":", ";
			$q.=sprintf("(SELECT k.name FROM kladr k WHERE k.code=%s) AS gorod",$code);
		}		
		if ($code=$p->getParamById('ulitza_code')){
			$q.=($q=="")? "":", ";
			$q.=sprintf("(SELECT k.name FROM street k WHERE k.code=%s) AS ulitza",$code);
		}
	
		$kl_ar = array();
		if (strlen($q)){
			$kladr = new Kladr_Controller();
			$kladr->query_first("SELECT ".$q,$kl_ar);
		}
		
		$res = array();
		$addr = array('region'=>(array_key_exists('region',$kl_ar))? $kl_ar['region']:$pm->getParamValue('region'),
				'raion'=>(array_key_exists('raion',$kl_ar))? $kl_ar['raion']:$pm->getParamValue('raion'),
				'city'=>(array_key_exists('gorod',$kl_ar))? $kl_ar['gorod']:$pm->getParamValue('gorod'),
				'naspunkt'=>(array_key_exists('naspunkt',$kl_ar))? $kl_ar['naspunkt']:$pm->getParamValue('naspunkt'),
				'street'=>(array_key_exists('ulitza',$kl_ar))? $kl_ar['ulitza']:$pm->getParamValue('ulitza'),
				'building'=>$pm->getParamValue('dom'),
				'korpus'=>$pm->getParamValue('korpus')
		);
	
		get_inf_on_address($addr,$res);
		
		$this->addNewModel(sprintf(
		"SELECT %f lon,%f lat",
		$res['lon_pos'],$res['lat_pos']
		),'gps');
	}
</xsl:template>

</xsl:stylesheet>