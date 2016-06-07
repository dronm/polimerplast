<?php
/*
http://79.122.200.182/API1c.php?cmd=get_client_on_inn&inn=7203240770
http://79.122.200.182/API1c.php?cmd=get_client_attrs_on_name&name=%D0%90%D0%B2%D1%82%D0%BE%D0%B3%D1%80%D0%B0%D0%B4-%D0%A2%D1%8E%D0%BC%D0%B5%D0%BD%D1%8C
http://79.122.200.182/API1c.php?cmd=complete_client&templ=%D0%90%D0%B2%D1%82%D0%BE%D0%B3%D1%80%D0%B0%D0%B4
http://79.122.200.182/API1c.php?cmd=get_firm_on_name&name=%D0%9E%D0%9E%D0%9E%20%22%D0%A3%D1%80%D0%B0%D0%BB%D1%81%D0%B8%D0%B1%22
*/

require_once('Config.php');
require_once('version.php');

	$log = ABSOLUTE_PATH.'updates/log';
	if (file_exists($log)){
		$log_cont = file_get_contents($log);
		$log_set_perm = FALSE;
	}
	else{
		$log_cont = '';
		$log_set_perm = TRUE;	
	}
	
	$log_cont.=date('d/m/Y h:m '.PHP_EOL);
	
	//**************** Упаковка всех скриптов **************************
	if (!file_exists(ABSOLUTE_PATH.'updates')){
		mkdir(ABSOLUTE_PATH.'updates');
		chgrp(ABSOLUTE_PATH.'updates',BUILD_GROUP);
		exec(sprintf('chmod %s %s',BUILD_DIR_PERMISSION,ABSOLUTE_PATH.'updates'));				
	}

	$dir = substr(ABSOLUTE_PATH,0,strlen(ABSOLUTE_PATH)-1);
	$dir_ar = explode('/',$dir);
	$proj_id = $dir_ar[count($dir_ar)-1];
	$par_dir = implode('/',array_slice($dir_ar,0,count($dir_ar)-1));

	$out_scripts = $dir.'/updates/project.tar.gz';
	if (file_exists($out_scripts)){
		unlink($out_scripts);
	}
	
	exec(sprintf('tar -zcf %s -C %s %s --exclude %s/updates',
		$out_scripts,
		$par_dir,
		$proj_id,
		$dir
	));
	chgrp($out_scripts,BUILD_GROUP);
	exec(sprintf('chmod %s %s',BUILD_DIR_PERMISSION,$out_scripts));				
	$log_cont.=date('d/m/Y h:m Упакованы скрипты проекта'.PHP_EOL);
	//************************************************************************
	
	//*************************Дамп базы данных********************************
	$out_dbdump = $dir.'/updates/database.dump.gz';
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
	$log_cont.=date('d/m/Y h:m Создан дамп базы данных'.PHP_EOL);
	//*************************************************************************
	
	
	//********************************Общий файл****************************
	$out_all = $dir.'/updates/'.str_replace('.','_',VERSION).'.tar';
	exec(sprintf('tar -cf %s -C %s %s -C %s %s',
		$out_all,
		$dir.'/updates',
		'project.tar.gz',
		$dir.'/updates',
		'database.dump.gz'		
	));

	chgrp($out_all,BUILD_GROUP);
	exec(sprintf('chmod %s %s',BUILD_DIR_PERMISSION,$out_all));				
	$log_cont.=date('d/m/Y h:m Создан общий файл архива'.PHP_EOL);
	//**************************************************************************

	$log_cont.=date('d/m/Y h:m Окончание установки обновления'.PHP_EOL);


	file_put_contents($log,$log_cont);
	if ($log_set_perm){
		chgrp($log,BUILD_GROUP);
		exec(sprintf('chmod %s %s',BUILD_DIR_PERMISSION,$log));					
	}
?>
