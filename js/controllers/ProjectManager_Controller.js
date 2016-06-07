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
	this.add_open_version();
	this.add_close_version();
	this.add_minify_js();
	this.add_build_all();
	this.add_create_symlinks();
	this.add_pull();
	this.add_push();
	this.add_zip_project();
	this.add_zip_db();
	this.add_get_version();
	
}
extend(ProjectManager_Controller,ControllerDb);

			ProjectManager_Controller.prototype.add_open_version = function(){
	var pm = this.addMethodById('open_version');
	
				
		pm.addParam(new FieldString("version"));
	
			
}

			ProjectManager_Controller.prototype.add_close_version = function(){
	var pm = this.addMethodById('close_version');
	
				
		pm.addParam(new FieldText("commit_description"));
	
			
}

			ProjectManager_Controller.prototype.add_minify_js = function(){
	var pm = this.addMethodById('minify_js');
	
}

			ProjectManager_Controller.prototype.add_build_all = function(){
	var pm = this.addMethodById('build_all');
	
}
			
			ProjectManager_Controller.prototype.add_create_symlinks = function(){
	var pm = this.addMethodById('create_symlinks');
	
}

			ProjectManager_Controller.prototype.add_pull = function(){
	var pm = this.addMethodById('pull');
	
}

			ProjectManager_Controller.prototype.add_push = function(){
	var pm = this.addMethodById('push');
	
				
		pm.addParam(new FieldText("commit_description"));
	
			
}
			
			ProjectManager_Controller.prototype.add_zip_project = function(){
	var pm = this.addMethodById('zip_project');
	
}

			ProjectManager_Controller.prototype.add_zip_db = function(){
	var pm = this.addMethodById('zip_db');
	
}

			ProjectManager_Controller.prototype.add_get_version = function(){
	var pm = this.addMethodById('get_version');
	
}
			
		