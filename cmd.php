<?php
require_once('Config.php');
require_once(FRAME_WORK_PATH.'Constants.php');
require_once(FRAME_WORK_PATH.'db/SessionManager.php');
require_once(FRAME_WORK_PATH.'db/db_pgsql.php');

require_once(FRAME_WORK_PATH.'basic_classes/Controller.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelServResponse.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldExtString.php');

try{
	//master connection for writing
	$dbLinkMaster = new DB_Sql();
	$dbLinkMaster->persistent=true;
	$dbLinkMaster->appname = APP_NAME;
	$dbLinkMaster->technicalemail = TECH_EMAIL;
	$dbLinkMaster->reportError = DEBUG;
	$dbLinkMaster->database	= DB_NAME;			
	$dbLinkMaster->detailedError = DEBUG;
	$port = (defined('DB_PORT_MASTER'))? DB_PORT:NULL;
	$dbLinkMaster->connect(DB_SERVER_MASTER,DB_USER,DB_PASSWORD,$port);
	//$dbLinkMaster->set_error_verbosity((DEBUG)? PGSQL_ERRORS_VERBOSE:PGSQL_ERRORS_TERSE);
	
	if (DB_SERVER_MASTER==DB_SERVER){
		$dbLink = $dbLinkMaster;
	}
	else{
		// connection for reading
		$dbLink = new DB_Sql();
		$dbLink->persistent=true;
		$dbLink->appname = APP_NAME;
		$dbLink->technicalemail = TECH_EMAIL;
		$dbLink->reportError = DEBUG;
		$dbLink->database= DB_NAME;			
		$port = (defined('DB_PORT'))? DB_PORT:NULL;
		$dbLink->connect(DB_SERVER,DB_USER,DB_PASSWORD,$port);		
	}
	
	/* ******************** Token Authorization ************************* */
	if (defined('PARAM_TOKEN') && isset($_REQUEST[PARAM_TOKEN])){		
	
		$access_p = strpos($_REQUEST[PARAM_TOKEN],':');
		if ($access_p===FALSE){
			throw new Exception(ERR_AUTH_NOT_LOGGED);
		}
		$access_salt = substr($_REQUEST[PARAM_TOKEN],0,$access_p);
		$access_hash = substr($_REQUEST[PARAM_TOKEN],$access_p+1);
		
		$access_salt_db = NULL;
		$f = new FieldExtString('salt');
		FieldSQLString::formatForDb($dbLink,$f->validate($access_salt),$access_salt_db);
		
		$session_ar = $dbLink->query_first(
		"SELECT
			trim(l.session_id) session_id,
			(EXTRACT(EPOCH FROM now()::timestamp-l.set_date_time)>=".SESSION_EXP_SEC."
			) AS expired
		FROM logins l
		WHERE l.date_time_out IS NULL
			AND l.pub_key=".$access_salt_db);
			
		if ( !$session_ar['session_id'] || $access_hash!=md5($access_salt.$session_ar['session_id']) ){
			throw new Exception(ERR_AUTH_NOT_LOGGED);
		}	

		session_id($session_ar['session_id']);

	}
	
	/*no token and no method or method is not in NO_TOKEN_METHODS
	if (!isset($_REQUEST[PARAM_TOKEN]) && (!isset($_REQUEST[PARAM_METHOD]) || !in_array($_REQUEST[PARAM_METHOD],explode(',',TOKEN_NO_TOKEN_METHODS))) ){
		throw new Exception(ERR_AUTH_NOT_LOGGED);
	}
	*/
	//*************************
	$session = new SessionManager();
	$session->start_session('_s', $dbLinkMaster,$dbLink);
	//*************************

	if (defined('PARAM_TOKEN')
	&& defined('TOKEN_AFTER_EXPIR_METHODS')
	&& isset($_REQUEST[PARAM_TOKEN])
	&& $session_ar['expired']=='t'
	&& !in_array($_REQUEST[PARAM_METHOD],explode(',',TOKEN_AFTER_EXPIR_METHODS))
	){		
		//session_destroy();
		//$_SESSION = array();		
		throw new Exception(ERR_AUTH_EXP);
	}		
		
	//setting locale
	if (isset($_SESSION['user_time_locale'])){			
		$q = sprintf("SET TIME ZONE '%s'",
			$_SESSION['user_time_locale']
		);
		$dbLink->query($q);
		if (DB_SERVER_MASTER!=DB_SERVER){
			$dbLinkMaster->query($q);
		}
		
		//php locale		
		date_default_timezone_set($_SESSION['user_time_locale']);
	}
		
	if (!isset($_SESSION['scriptId'])){
		$_SESSION['scriptId'] = md5(session_id());
	}
	
	//*****************************
	//default page params
	if (!isset($_SESSION['LOGGED'])){			
		if (!isset($_REQUEST[PARAM_CONTROLLER])){
			$_REQUEST[PARAM_CONTROLLER] = UNLOGGED_DEF_CONTROLLER;
		}
		if (!isset($_REQUEST[PARAM_VIEW])){
			$_REQUEST[PARAM_VIEW] = UNLOGGED_DEF_VIEW;
			unset($_REQUEST[PARAM_METHOD]);
		}
	}
	
	$contr = (isset($_REQUEST[PARAM_CONTROLLER]) && strlen($_REQUEST[PARAM_CONTROLLER]))? $_REQUEST[PARAM_CONTROLLER]:null;
	$meth = (isset($_REQUEST[PARAM_METHOD]) && strlen($_REQUEST[PARAM_METHOD]))? $_REQUEST[PARAM_METHOD]:null;	
	$view = (isset($_REQUEST[PARAM_VIEW]) && strlen($_REQUEST[PARAM_VIEW]))? $_REQUEST[PARAM_VIEW]:DEF_VIEW;
	//throw new Exception("contr=".$contr.' meth='.$meth.' view='.$view);
	/* controller checking*/
	
	if (!is_null($contr) && !file_exists($script=USER_CONTROLLERS_PATH.$contr.'.php')){	
		if (!isset($_SESSION['LOGGED'])){
			throw new Exception(ERR_AUTH_NOT_LOGGED);
		}
		else{		
			throw new Exception(ERR_COM_NO_CONTROLLER);
		}
	}
	else if (is_null($contr) && defined('CUSTOM_CONTROLLER') && file_exists($script=USER_CONTROLLERS_PATH.CUSTOM_CONTROLLER.'.php')){	
		$contr = CUSTOM_CONTROLLER;
	}
	else if (is_null($contr)){
		$contr = 'Controller';
		$script=FRAME_WORK_PATH.'basic_classes/Controller.php'; 
	}
	//checking if method is allowed
	if (!is_null($meth)){
		if (!isset($_SESSION['LOGGED']) || !isset($_SESSION['role_id'])){
			$role_id = 'guest';
		}
		else{
			$role_id = $_SESSION['role_id'];
		}
		
		//require(FUNC_PATH.'role_permissions.php');
		require('permissions/permission_'.$role_id.'.php');
		//throw new Exception($contr.'__'.$meth.'__'.$role_id);
		if (!method_allowed($contr,$meth,$role_id)){
			if (!isset($_SESSION['LOGGED'])){
				throw new Exception(ERR_AUTH_NOT_LOGGED);
			}
			else{		
				throw new Exception(ERR_COM_METH_PROHIB);
			}
		}
		
	}
	
	/* including controller */	
	require_once($script);
	$contrObj = new $contr($dbLinkMaster,$dbLink);

	/* view checking*/
	if (is_null($view)){
		$def_view = $contrObj->getDefaultView();
		$view = (isset($def_view))? $def_view:DEF_VIEW;
		if (!isset($view)){
			throw new Exception(ERR_COM_NO_VIEW);
		}	
	}

	$view_class = $view;
	if (!file_exists($v_script=USER_VIEWS_PATH.$view.'.php')){	
		$pathArray = explode(PATH_SEPARATOR, get_include_path());	
		$v_script = (count($pathArray)>=1)?
			$pathArray[1].'/'.FRAME_WORK_PATH.'basic_classes/'.$view.'.php' :
			USER_VIEWS_PATH.$view.'.php';
		
		if (!file_exists($v_script)){	
			if (file_exists($v_script=USER_VIEWS_PATH.DEF_VIEW.'.php')){
				$view_class = DEF_VIEW;
			}
			else{
				throw new Exception(ERR_COM_NO_VIEW);
			}
		}
	}
	
	require_once($v_script);

	$contrObj->runPublicMethod($meth,$_REQUEST);	
	$contrObj->write($view_class,$view);
}
catch (Exception $e){
	if (defined('PARAM_TEMPLATE')){
		unset($_REQUEST[PARAM_TEMPLATE]);
	}
	$contrObj = new Controller();	
	$resp = new ModelServResponse();				
	$contrObj->addModel($resp);	
	$ar = explode('@',$e->getMessage());
	$er_code=(count($ar)>1)? $ar[1]:1;
	$resp->result = $er_code;
	if (count($ar)){
		$resp->descr = htmlspecialchars($ar[0]);	
	}
	$view = (isset($_REQUEST[PARAM_VIEW]))? $_REQUEST[PARAM_VIEW]:DEF_VIEW;
	//throw new Exception("v=".USER_VIEWS_PATH.$view.'.php');
	if (!isset($v_script)){
		//not included yet
		if (!file_exists($v_script=USER_VIEWS_PATH.$view.'.php')){	
			$pathArray = explode(PATH_SEPARATOR, get_include_path());	
			$v_script = (count($pathArray)>=1)?
				$pathArray[1].'/'.FRAME_WORK_PATH.'basic_classes/'.$view.'.php' :
				USER_VIEWS_PATH.$view.'.php';
		}
		require_once($v_script);		
	}
	
	$contrObj->write($view,$view,$er_code);
}

?>
