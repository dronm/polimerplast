<?php

require_once(FRAME_WORK_PATH.'basic_classes/ControllerSQL.php');

require_once(FRAME_WORK_PATH.'basic_classes/FieldExtInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtDateTime.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtDate.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtPassword.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtBool.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtGeomPoint.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtGeomPolygon.php');

require_once('common/OSRM.php');
require_once('common/geo/yandex.php');
require_once('common/geo/YndxReverseCode.php');
require_once(FRAME_WORK_PATH.'basic_classes/ParamsSQL.php');
require_once(USER_CONTROLLERS_PATH.'Kladr_Controller.php');

class ClientDestination_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);
			
		/* insert */
		$pm = new PublicMethod('insert');
		$param = new FieldExtInt('client_id'
				,array('required'=>TRUE));
		$pm->addParam($param);
		$param = new FieldExtGeomPoint('zone_center'
				,array());
		$pm->addParam($param);
		$param = new FieldExtFloat('near_road_lon'
				,array());
		$pm->addParam($param);
		$param = new FieldExtFloat('near_road_lat'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('region'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('region_code'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('raion'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('raion_code'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('gorod'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('gorod_code'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('naspunkt'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('naspunkt_code'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('ulitza'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('ulitza_code'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('dom'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('korpus'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('kvartira'
				,array());
		$pm->addParam($param);
		$param = new FieldExtText('addr_index'
				,array());
		$pm->addParam($param);
		
		$pm->addParam(new FieldExtInt('ret_id'));
		
		
		$this->addPublicMethod($pm);
		$this->setInsertModelId('ClientDestination_Model');

			
		/* update */		
		$pm = new PublicMethod('update');
		
		$pm->addParam(new FieldExtInt('old_id',array('required'=>TRUE)));
		
		$pm->addParam(new FieldExtInt('obj_mode'));
		$param = new FieldExtInt('id'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtInt('client_id'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtGeomPoint('zone_center'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtFloat('near_road_lon'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtFloat('near_road_lat'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('region'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('region_code'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('raion'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('raion_code'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('gorod'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('gorod_code'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('naspunkt'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('naspunkt_code'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('ulitza'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('ulitza_code'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('dom'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('korpus'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('kvartira'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtText('addr_index'
				,array(
			));
			$pm->addParam($param);
		
			$param = new FieldExtInt('id',array(
			));
			$pm->addParam($param);
		
		
			$this->addPublicMethod($pm);
			$this->setUpdateModelId('ClientDestination_Model');

			
		/* delete */
		$pm = new PublicMethod('delete');
		
		$pm->addParam(new FieldExtInt('id'
		));		
		
		$pm->addParam(new FieldExtInt('count'));
		$pm->addParam(new FieldExtInt('from'));				
		$this->addPublicMethod($pm);					
		$this->setDeleteModelId('ClientDestination_Model');

			
		/* get_list */
		$pm = new PublicMethod('get_list');
		$pm->addParam(new FieldExtInt('browse_mode'));
		$pm->addParam(new FieldExtInt('browse_id'));		
		$pm->addParam(new FieldExtInt('count'));
		$pm->addParam(new FieldExtInt('from'));
		$pm->addParam(new FieldExtString('cond_fields'));
		$pm->addParam(new FieldExtString('cond_sgns'));
		$pm->addParam(new FieldExtString('cond_vals'));
		$pm->addParam(new FieldExtString('cond_ic'));
		$pm->addParam(new FieldExtString('ord_fields'));
		$pm->addParam(new FieldExtString('ord_directs'));
		$pm->addParam(new FieldExtString('field_sep'));
		
		$this->addPublicMethod($pm);
		
		$this->setListModelId('ClientDestinationList_Model');
		
			
		/* get_object */
		$pm = new PublicMethod('get_object');
		$pm->addParam(new FieldExtInt('browse_mode'));
		
		$pm->addParam(new FieldExtInt('id'
		));
		
		$this->addPublicMethod($pm);
		$this->setObjectModelId('ClientDestinationDialog_Model');		

			
		$pm = new PublicMethod('get_address_gps');
		
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('region',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('region_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('raion',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('raion_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('gorod',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('gorod_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('naspunkt',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('naspunkt_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('ulitza',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('ulitza_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('dom',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtText('korpus',$opts));
	
			
		$this->addPublicMethod($pm);

		
	}	
	
	private function set_addr_from_gps($lat,$lon,&$pm){
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
			if (count($points)>=2){
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
			
			if ($res['lon_pos']&&$res['lat_pos']){
				$pm->setParamValue('zone_center',
					$res['lon_pos'].' '.$res['lat_pos']
					);
			}
		}
		else if(!$addr_exists){
			//есть маркер но нет адреса
			$points = explode(' ',$zone_center);
			if (count($points)>=2){
				$this->set_addr_from_gps($points[1],$points[0],$pm);
			
			}			
		}
		else if(!$zone_center&&!$addr_exists){
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
				
				if ($res['lon_pos']&&$res['lat_pos']){
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
				if (count($points)>=2){
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

}
?>
