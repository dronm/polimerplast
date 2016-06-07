<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Controller_js.xsl"/>

<!-- -->
<xsl:variable name="CONTROLLER_ID" select="'Constant'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
			
<xsl:template match="/">
	<xsl:apply-templates select="metadata/controllers/controller[@id=$CONTROLLER_ID]"/>
Constant_Controller.prototype.value_cache;
Constant_Controller.prototype.readValues = function(struc){
	this.value_cache=this.value_cache || {};
	var pm = this.getPublicMethodById("get_values");
	var id_list = "";
	for (var id in struc){
		if (this.value_cache[id]){
			struc[id]=this.value_cache[id];
		}
		else{
			id_list+=(id_list=="")? "":",";
			id_list+=id;
		}
	}
	if (id_list.length){
		pm.setParamValue("id_list",id_list);
		this.runPublicMethod("get_values",{},false,
		function(resp){
			var model = resp.getModelById("ConstantList_Model");
			model.setActive(true);
			while (model.getNextRow()){
				struc[model.getFieldValue("id")] = model.getFieldValue("val");
			}
		},this,null);
	}
}	
</xsl:template>

</xsl:stylesheet>