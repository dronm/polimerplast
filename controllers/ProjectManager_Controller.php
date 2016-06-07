<?php


require_once(FRAME_WORK_PATH.'basic_classes/Controller.php');

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

require_once(ABSOLUTE_PATH.'version.php');
require_once('common/Logger.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelVars.php');
require_once(FRAME_WORK_PATH.'build/proj_file_func.php');
require_once(FRAME_WORK_PATH.'build/build_js.php');
require_once(FRAME_WORK_PATH.'build/build_css.php');
require_once('common/Git.php-master/Git.php');	

class ProjectManager_Controller extends Controller{

	const UPDATES_DIR = 'updates';
	const PROJ_FILE = 'project.tar.gz';
	const DUMP_FILE = 'database.dump.gz';
	const INVALID_UPDATE = 'Нет обновлений для данного проекта!';
	
	public function __construct(){
		
			
		$pm = new PublicMethod('install_update');
		
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('build_project');
		
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('create_symlinks');
		
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('pull');
		
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('unify_js');
		
		$this->addPublicMethod($pm);

			
		$pm = new PublicMethod('get_version');
		
		$this->addPublicMethod($pm);
			
		
	}
	
	private function do_update($logger){
		$logger->add('Начало установки обновления ');
	}

	private function zip_project($logger){
		$logger->add('Архивация файлов проекта');

		$dir = substr(ABSOLUTE_PATH,0,strlen(ABSOLUTE_PATH)-1);
		$dir_ar = explode('/',$dir);
		$proj_id = $dir_ar[count($dir_ar)-1];
		$par_dir = implode('/',array_slice($dir_ar,0,count($dir_ar)-1));
		
		if (!file_exists($dir.'/'.self::UPDATES_DIR)){
			mkdir($dir.'/'.self::UPDATES_DIR);
			chgrp($dir.'/'.self::UPDATES_DIR,BUILD_GROUP);
			exec(sprintf('chmod %s %s',BUILD_DIR_PERMISSION,$dir.'/'.self::UPDATES_DIR));				
		}

		$out_scripts = $dir.'/'.self::UPDATES_DIR.'/'.self::PROJ_FILE;
		if (file_exists($out_scripts)){
			unlink($out_scripts);
		}
	
		exec(sprintf('tar -zcf %s -C %s %s --exclude %s',
			$out_scripts,
			$par_dir,
			$proj_id,
			$dir.'/'.self::UPDATES_DIR
		));
		chgrp($out_scripts,BUILD_GROUP);
		exec(sprintf('chmod %s %s',BUILD_DIR_PERMISSION,$out_scripts));				
	}

	private function dump_db($logger){
		$logger->add('Архивация базы данных');
		$dir = substr(ABSOLUTE_PATH,0,strlen(ABSOLUTE_PATH)-1);
		$out_dbdump = $dir.'/'.self::UPDATES_DIR.'/'.self::DUMP_FILE;;
		$cmd = sprintf(
		'pg_dump -h %s -U %s -Fc -b postgresql://%s:%s@%s:%d/%s | gzip > %s',
			DB_SERVER,
			DB_USER,
			DB_USER,
			DB_PASSWORD,
			DB_SERVER,
			5432,
			DB_NAME,
			$out_dbdump
		);
	
		exec($cmd);
	}

	private function tar_zips($logger){
		$logger->add('Сборка архивов в один файл');
		$dir = substr(ABSOLUTE_PATH,0,strlen(ABSOLUTE_PATH)-1);
	
		$out_all = $dir.'/'.self::UPDATES_DIR.'/'.str_replace('.','_',VERSION).'.tar';
		exec(sprintf('tar -cf %s -C %s %s -C %s %s',
			$out_all,
			$dir.'/updates',
			self::PROJ_FILE,
			$dir.'/'.self::UPDATES_DIR,
			self::DUMP_FILE		
		));

		chgrp($out_all,BUILD_GROUP);
		exec(sprintf('chmod %s %s',BUILD_DIR_PERMISSION,$out_all));				
	}

	private function get_update_version(){
	
	}

	public function install_update($pm){
		$new_vers = $this->get_update_version();
		if ($new_vers <= VERSION){
			throw new Exception(self::INVALID_UPDATE);
		}
	
		$logger = new Logger(ABSOLUTE_PATH.'updates/log');
		
		$logger->add('Начало установки обновления ',$new_vers);
		
		$this->zip_project($logger);
		$this->dump_db($logger);
		$this->tar_zips($logger);
		$this->do_update($logger);
		
		$logger->close();
	}
	public function build_project($pm){
		require_once(FRAME_WORK_PATH.'build/build.php');
	}
	public function get_version($pm){
		$struc = array();
		get_version_inf($struc);
		
		$this->addModel(new ModelVars(
			array('name'=>'Vars',
				'id'=>'Version_Model',
				'values'=>array(
					new Field('version',DT_STRING,
						array('value'=>$struc['version'])),
					new Field('dateOpen',DT_STRING,
						array('value'=>$struc['dateOpen'])),
					new Field('dateClose',DT_INT,
						array('value'=>$struc['dateClose'])),
					new Field('lastBuild',DT_STRING,
						array('value'=>$struc['lastBuild']))						
				)
			)
		));		
			
	}
}
?>
