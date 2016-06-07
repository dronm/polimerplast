/* Copyright (c) 2012 
	Andrey Mikhalevich, Katren ltd.
*/
/*	
	Description
*/
/** Requirements
 * @requires common/functions.js
 * @requires core/ControllerDb.js
*/
//ф
/* constructor */

function ProjectManager_Controller(servConnector){
	options = {};
	ProjectManager_Controller.superclass.constructor.call(this,"ProjectManager_Controller",servConnector,options);	
	
	//methods
	this.add_install_update();
	this.add_build_project();
	this.add_create_symlinks();
	this.add_pull();
	this.add_unify_js();
	this.add_get_version();
	
}
extend(ProjectManager_Controller,ControllerDb);

			ProjectManager_Controller.prototype.add_install_update = function(){
	var pm = this.addMethodById('install_update');
	
}

			ProjectManager_Controller.prototype.add_build_project = function(){
	var pm = this.addMethodById('build_project');
	
}

			ProjectManager_Controller.prototype.add_create_symlinks = function(){
	var pm = this.addMethodById('create_symlinks');
	
}

			ProjectManager_Controller.prototype.add_pull = function(){
	var pm = this.addMethodById('pull');
	
}

			ProjectManager_Controller.prototype.add_unify_js = function(){
	var pm = this.addMethodById('unify_js');
	
}

			ProjectManager_Controller.prototype.add_get_version = function(){
	var pm = this.addMethodById('get_version');
	
}
			
		