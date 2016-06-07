<?php

require_once(FRAME_WORK_PATH.'basic_classes/ControllerSQL.php');

require_once(FRAME_WORK_PATH.'basic_classes/FieldExtInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtFloat.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtText.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtDateTime.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtDate.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtTime.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtPassword.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtBool.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtGeomPoint.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtGeomPolygon.php');

class Kladr_Controller extends ControllerSQL{
	const COMPLETE_RES_COUNT=5;
	
	public function __construct($dbLinkMaster=NULL){
		$kladr_link = new DB_Sql();
		$kladr_link->appname		= APP_NAME;
		$kladr_link->technicalemail = TECH_EMAIL;
		$kladr_link->reporterror	= DEBUG;
		$kladr_link->database		= 'kladr';
		$kladr_link->connect(DB_SERVER,DB_USER,DB_PASSWORD);
		//$kladr_link->set_error_verbosity((DEBUG)? PGSQL_ERRORS_VERBOSE:PGSQL_ERRORS_TERSE);
		
		parent::__construct($dbLinkMaster,$kladr_link);
			
		$pm = new PublicMethod('get_region_list');
		
				
	$opts=array();
	
		$opts['required']=TRUE;				
		$pm->addParam(new FieldExtString('pattern',$opts));
	
			
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('get_raion_list');
		
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('region_code',$opts));
	
				
	$opts=array();
	
		$opts['length']=40;
		$opts['required']=TRUE;				
		$pm->addParam(new FieldExtString('pattern',$opts));
	
			
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('get_naspunkt_list');
		
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('region_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('raion_code',$opts));
	
				
	$opts=array();
	
		$opts['length']=40;
		$opts['required']=TRUE;				
		$pm->addParam(new FieldExtString('pattern',$opts));
	
			
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('get_gorod_list');
		
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('region_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('raion_code',$opts));
	
				
	$opts=array();
	
		$opts['length']=40;
		$opts['required']=TRUE;				
		$pm->addParam(new FieldExtString('pattern',$opts));
	
			
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('get_ulitsa_list');
		
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('region_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('raion_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('naspunkt_code',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('gorod_code',$opts));
	
				
	$opts=array();
	
		$opts['length']=40;
		$opts['required']=TRUE;				
		$pm->addParam(new FieldExtString('pattern',$opts));
	
			
		$this->addPublicMethod($pm);
			
		
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
				AND code <> '%s00000000000'
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
				AND code <> '%s%s00000000'
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
				AND code <> '%s%s00000000'
				AND code <> '%s00000000000'
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
		if ((!$naspunkt_code||!is_numeric($naspunkt_code))&&(!$gorod_code||!is_numeric($gorod_code))){
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
	public function query_first($q,&$res){
		$res = $this->getDbLink()->query_first($q);
	}
}
?>
