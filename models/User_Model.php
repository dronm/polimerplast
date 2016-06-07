<?php

require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLInt.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLString.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLEnum.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQLPassword.php');
require_once(FRAME_WORK_PATH.'basic_classes/ModelOrderSQL.php');


class User_Model extends ModelSQL{
	
	public function __construct($dbLink){
		parent::__construct($dbLink);
		
		$this->setDbName("public");
		
		$this->setTableName("users");
		
		$f_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"id"
		,array(
		'required'=>TRUE,
			'primaryKey'=>TRUE,
			'autoInc'=>TRUE,
			'id'=>"id"
				
		
		));
		$this->addField($f_id);

		$f_name=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name"
		,array(
		'required'=>TRUE,
			'alias'=>"логин"
		,
			'length'=>50,
			'id'=>"name"
				
		
		));
		$this->addField($f_name);

		$f_name_full=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"name_full"
		,array(
		'required'=>TRUE,
			'alias'=>"ФИО"
		,
			'length'=>150,
			'id'=>"name_full"
				
		
		));
		$this->addField($f_name_full);

		$f_sign_order=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"sign_order"
		,array(
		
			'alias'=>"Приказ"
		,
			'length'=>100,
			'id'=>"sign_order"
				
		
		));
		$this->addField($f_sign_order);

		$f_role_id=new FieldSQlEnum($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"role_id"
		,array(
		
			'alias'=>"роль"
		,
			'id'=>"role_id"
				
		
		));
		$this->addField($f_role_id);

		$f_email=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"email"
		,array(
		'required'=>FALSE,
			'alias'=>"эл.почта"
		,
			'length'=>50,
			'id'=>"email"
				
		
		));
		$this->addField($f_email);

		$f_pwd=new FieldSQlPassword($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"pwd"
		,array(
		
			'alias'=>"пароль"
		,
			'length'=>32,
			'id'=>"pwd"
				
		
		));
		$this->addField($f_pwd);

		$f_cel_phone=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"cel_phone"
		,array(
		'required'=>FALSE,
			'length'=>15,
			'id'=>"cel_phone"
				
		
		));
		$this->addField($f_cel_phone);

		$f_client_id=new FieldSQlInt($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"client_id"
		,array(
		
			'id'=>"client_id"
				
		
		));
		$this->addField($f_client_id);

		$f_tel_ext=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"tel_ext"
		,array(
		
			'length'=>5,
			'id'=>"tel_ext"
				
		
		));
		$this->addField($f_tel_ext);

		$f_ext_id=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_id"
		,array(
		
			'length'=>36,
			'id'=>"ext_id"
				
		
		));
		$this->addField($f_ext_id);

		$f_ext_login=new FieldSQlString($this->getDbLink(),$this->getDbName(),$this->getTableName()
		,"ext_login"
		,array(
		
			'length'=>100,
			'id'=>"ext_login"
				
		
		));
		$this->addField($f_ext_login);

		$order = new ModelOrderSQL();		
		$this->setDefaultModelOrder($order);		
		
		$order->addField($f_name);

		
		
		
	}

	
	public function insert($needId=FALSE){
		try{
			parent::insert($needId);
		}
		catch(Exception $e){
			if (strpos($e->getMessage(),'23505')>0){
				throw new Exception('Логин занят!');
			}			
		}
	}
	public function update(){
		try{
			parent::update();
		}
		catch(Exception $e){
			if (strpos($e->getMessage(),'23505')>0){
				throw new Exception('Логин занят!');
			}			
		}		
	}	
}
?>
