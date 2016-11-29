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
require_once(FRAME_WORK_PATH.'basic_classes/ModelSQL.php');
require_once(FRAME_WORK_PATH.'basic_classes/FieldSQL.php');
class Constant_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);
			
		$pm = new PublicMethod('set_value');
		
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('id',$opts));
	
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('val',$opts));
	
			
		$this->addPublicMethod($pm);

			
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
		
		$this->setListModelId('ConstantList_Model');
		
			
		/* get_object */
		$pm = new PublicMethod('get_object');
		$pm->addParam(new FieldExtInt('browse_mode'));
		
		$pm->addParam(new FieldExtString('id'
		));
		
		$this->addPublicMethod($pm);
		$this->setObjectModelId('ConstantList_Model');		

			
		$pm = new PublicMethod('get_values');
		
				
	$opts=array();
					
		$pm->addParam(new FieldExtString('id_list',$opts));
	
			
		$this->addPublicMethod($pm);

		
	}
	
	/*
	@param array $idList
	returns ModelSQL
	*/
	public function getConstantValueModel(&$idList){
		$link = $this->getDbLink();
		$model = new ModelSQL($link,array('id'=>'ConstantValueList_Model'));
		$model->addField(new FieldSQL($link,null,null,'id',DT_STRING));
		$model->addField(new FieldSQL($link,null,null,'val',DT_STRING));		

		$q = '';
		foreach($idList as $id) {
			$q.= ($q!='')? ' UNION ALL ':'';
			$q.= sprintf("SELECT
				'%s' AS id,
				const_%s_val()::text AS val,
				(SELECT c.val_type FROM const_%s c) AS val_type", $id,$id,$id);
		}
		
		$model->setSelectQueryText($q);
		$model->select(false,null,null,
			null,null,null,null,null,TRUE);
			
		return $model;
		//
	}
	
	public function get_values($pm){
		$id_list = $pm->getParamValue('id_list');
		if (isset($id_list)){
			$model = $this->getConstantValueModel(explode(',',$id_list));
			$this->addModel($model);
		}
	}
	
	public function set_value($pm){
		$link = $this->getDbLinkMaster();
		$link->query(sprintf(
		"SELECT const_%s_set_val('%s')",
		$pm->getParamValue('id'),$pm->getParamValue('val')));
	}
}
?>
