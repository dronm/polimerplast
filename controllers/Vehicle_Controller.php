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
class Vehicle_Controller extends ControllerSQL{
	public function __construct($dbLinkMaster=NULL){
		parent::__construct($dbLinkMaster);
			
		/* insert */
		$pm = new PublicMethod('insert');
		$param = new FieldExtString('model'
				,array());
		$pm->addParam($param);
		$param = new FieldExtString('plate'
				,array());
		$pm->addParam($param);
		$param = new FieldExtInt('vol'
				,array());
		$pm->addParam($param);
		$param = new FieldExtBool('employed'
				,array());
		$pm->addParam($param);
		$param = new FieldExtFloat('load_weight_t'
				,array());
		$pm->addParam($param);
		$param = new FieldExtInt('production_city_id'
				,array('required'=>TRUE));
		$pm->addParam($param);
		$param = new FieldExtInt('carrier_id'
				,array());
		$pm->addParam($param);
		$param = new FieldExtInt('driver_id'
				,array('required'=>TRUE));
		$pm->addParam($param);
		$param = new FieldExtInt('deliv_cost_opt_id'
				,array());
		$pm->addParam($param);
		$param = new FieldExtString('trailer_model'
				,array());
		$pm->addParam($param);
		$param = new FieldExtString('trailer_plate'
				,array());
		$pm->addParam($param);
		$param = new FieldExtString('tracker_id'
				,array());
		$pm->addParam($param);
		
		$pm->addParam(new FieldExtInt('ret_id'));
		
		
		$this->addPublicMethod($pm);
		$this->setInsertModelId('Vehicle_Model');

			
		/* update */		
		$pm = new PublicMethod('update');
		
		$pm->addParam(new FieldExtInt('old_id',array('required'=>TRUE)));
		
		$pm->addParam(new FieldExtInt('obj_mode'));
		$param = new FieldExtInt('id'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtString('model'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtString('plate'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtInt('vol'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtBool('employed'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtFloat('load_weight_t'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtInt('production_city_id'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtInt('carrier_id'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtInt('driver_id'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtInt('deliv_cost_opt_id'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtString('trailer_model'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtString('trailer_plate'
				,array(
			));
			$pm->addParam($param);
		$param = new FieldExtString('tracker_id'
				,array(
			));
			$pm->addParam($param);
		
			$param = new FieldExtInt('id',array(
			));
			$pm->addParam($param);
		
		
			$this->addPublicMethod($pm);
			$this->setUpdateModelId('Vehicle_Model');

			
		/* delete */
		$pm = new PublicMethod('delete');
		
		$pm->addParam(new FieldExtInt('id'
		));		
		
		$pm->addParam(new FieldExtInt('count'));
		$pm->addParam(new FieldExtInt('from'));				
		$this->addPublicMethod($pm);					
		$this->setDeleteModelId('Vehicle_Model');

			
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
		
		$this->setListModelId('VehicleList_Model');
		
			
		$pm = new PublicMethod('get_select_list');
		
		$this->addPublicMethod($pm);

			
		/* get_object */
		$pm = new PublicMethod('get_object');
		$pm->addParam(new FieldExtInt('browse_mode'));
		
		$pm->addParam(new FieldExtInt('id'
		));
		
		$this->addPublicMethod($pm);
		$this->setObjectModelId('VehicleDialog_Model');		

			
		/* complete  */
		$pm = new PublicMethod('complete');
		$pm->addParam(new FieldExtString('pattern'));
		$pm->addParam(new FieldExtInt('count'));
		$pm->addParam(new FieldExtInt('ic'));
		$pm->addParam(new FieldExtInt('mid'));
		$pm->addParam(new FieldExtString('plate'));		
		$this->addPublicMethod($pm);					
		$this->setCompleteModelId('VehicleSelectList_Model');

		
	}	
	
	public function get_select_list($pm){
		$this->setListModelId('VehicleSelectList_Model');
		parent::get_list($pm);
	}

}
?>
