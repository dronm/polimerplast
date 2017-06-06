<?xml version="1.0" encoding="UTF-8"?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"><xsl:output method="text" indent="yes"			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/><xsl:template match="controller"><![CDATA[]]>/* Copyright (c) 2012 	Andrey Mikhalevich, Katren ltd.*//*		Description*//** Requirements * @requires common/functions.js * @requires core/ControllerDb.js*///ф/* constructor */<xsl:variable name="contr_id" select="concat(@id,'_Controller')"/>function <xsl:value-of select="$contr_id"/>(servConnector){	options = {};	<xsl:if test="publicMethod[@id='get_list']/@modelId">options["listModelId"] = "<xsl:value-of select="publicMethod[@id='get_list']/@modelId"/>_Model";	</xsl:if>	<xsl:if test="publicMethod[@id='get_object']/@modelId">options["objModelId"] = "<xsl:value-of select="publicMethod[@id='get_object']/@modelId"/>_Model";	</xsl:if>	<xsl:value-of select="$contr_id"/>.superclass.constructor.call(this,"<xsl:value-of select="$contr_id"/>",servConnector,options);			//methods	<xsl:for-each select="publicMethod">	<xsl:choose>	<xsl:when test="@id='insert'">this.addInsert();	</xsl:when>	<xsl:when test="@id='update'">this.addUpdate();	</xsl:when>	<xsl:when test="@id='delete'">this.addDelete();	</xsl:when>	<xsl:when test="@id='get_list'">this.addGetList();	</xsl:when>	<xsl:when test="@id='get_object'">this.addGetObject();	</xsl:when>	<xsl:when test="@id='complete'">this.addComplete();	</xsl:when>		<xsl:otherwise>this.add_<xsl:value-of select="@id"/>();	</xsl:otherwise>		</xsl:choose>	</xsl:for-each>}extend(<xsl:value-of select="$contr_id"/>,ControllerDb);<xsl:apply-templates/><![CDATA[]]></xsl:template><xsl:template match="publicMethod[@id='insert']"><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:variable name="model_id" select="@modelId"/><xsl:value-of select="$contr_id"/>.prototype.addInsert = function(){	<xsl:value-of select="$contr_id"/>.superclass.addInsert.call(this);	var param;	var options;	var pm = this.getInsert();<xsl:for-each select="/metadata/models/model[@id=$model_id]/field[not(@primaryKey='TRUE' and @autoInc='TRUE')]">	options = {};	<xsl:if test="@alias">options["alias"]="<xsl:value-of select="@alias"/>";</xsl:if>	<xsl:choose>	<xsl:when test="@dataType='Enum'">	<xsl:variable name="enum_id" select="@enumId"/>	param = new FieldEnum("<xsl:value-of select="@id"/>",options);	options["values"] = '<xsl:for-each select="/metadata/enums/enum[@id=$enum_id]/value"><xsl:if test="position() &gt; 1">,</xsl:if><xsl:value-of select="@id"/></xsl:for-each>';	</xsl:when>	<xsl:otherwise>	var param = new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options);	</xsl:otherwise>	</xsl:choose>	pm.addParam(param);	</xsl:for-each>	<!-- if there is a SERIAL field might need return new id -->	<xsl:if test="/metadata/models/model[@id=$model_id]/field[@autoInc='TRUE']">	pm.addParam(new FieldInt("ret_id",{}));	</xsl:if>	<!-- EXTRA PARAMS -->	<xsl:for-each select="field">		var options = {};		<xsl:if test="@alias">			options["alias"]="<xsl:value-of select="@alias"/>";		</xsl:if>		<xsl:if test="@required">			options["required"]=true;		</xsl:if>						pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options));	</xsl:for-each>	}</xsl:template><xsl:template match="publicMethod[@id='update']"><xsl:variable name="model_id" select="@modelId"/><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:value-of select="$contr_id"/>.prototype.addUpdate = function(){	<xsl:value-of select="$contr_id"/>.superclass.addUpdate.call(this);	var param;	var options;		var pm = this.getUpdate();<xsl:for-each select="/metadata/models/model[@id=$model_id]/field">	options = {};	<xsl:if test="@alias">options["alias"]="<xsl:value-of select="@alias"/>";</xsl:if>	<xsl:choose>	<xsl:when test="@dataType='Enum'">	<xsl:variable name="enum_id" select="@enumId"/>	param = new FieldEnum("<xsl:value-of select="@id"/>",options);	options["values"] = '<xsl:for-each select="/metadata/enums/enum[@id=$enum_id]/value"><xsl:if test="position() &gt; 1">,</xsl:if><xsl:value-of select="@id"/></xsl:for-each>';	</xsl:when>	<xsl:otherwise>	var param = new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options);	</xsl:otherwise>	</xsl:choose>	pm.addParam(param);	<!-- old key -->	<xsl:if test="@primaryKey='TRUE'">	param = new Field<xsl:value-of select="@dataType"/>("old_<xsl:value-of select="@id"/>",{});	pm.addParam(param);	</xsl:if>	</xsl:for-each>	<!-- EXTRA PARAMS -->	<xsl:for-each select="field">		var options = {};		<xsl:if test="@alias">			options["alias"]="<xsl:value-of select="@alias"/>";		</xsl:if>		<xsl:if test="@required">			options["required"]=true;		</xsl:if>						pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options));	</xsl:for-each>	}</xsl:template><xsl:template match="publicMethod[@id='delete']"><xsl:variable name="model_id" select="@modelId"/><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:value-of select="$contr_id"/>.prototype.addDelete = function(){	<xsl:value-of select="$contr_id"/>.superclass.addDelete.call(this);	var options = {"required":true};	<xsl:if test="@alias">options["alias"]="<xsl:value-of select="@alias"/>";</xsl:if>	var pm = this.getDelete();<xsl:for-each select="/metadata/models/model[@id=$model_id]/field[@primaryKey='TRUE']">	pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options));</xsl:for-each>}</xsl:template><xsl:template match="publicMethod[@id='complete']"><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:variable name="model_id" select="@modelId"/><xsl:value-of select="$contr_id"/>.prototype.addComplete = function(){	<xsl:value-of select="$contr_id"/>.superclass.addComplete.call(this);	<xsl:variable name="pattern_field_id" select="@patternFieldId"/>	<xsl:variable name="pattern_field" select="/metadata/models/model[@id=$model_id]/field[@id=$pattern_field_id]"/>	var options = {};	<xsl:if test="@alias">options["alias"]="<xsl:value-of select="@alias"/>";</xsl:if>	var pm = this.getComplete();	pm.addParam(new Field<xsl:value-of select="$pattern_field/@dataType"/>("<xsl:value-of select="$pattern_field_id"/>",options));	pm.getParamById(this.PARAM_ORD_FIELDS).setValue("<xsl:value-of select="$pattern_field_id"/>");}</xsl:template><xsl:template match="publicMethod[@id='get_object']"><xsl:variable name="model_id" select="@modelId"/><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:value-of select="$contr_id"/>.prototype.addGetObject = function(){	<xsl:value-of select="$contr_id"/>.superclass.addGetObject.call(this);	var options = {};	<xsl:if test="@alias">options["alias"]="<xsl:value-of select="@alias"/>";</xsl:if>	var pm = this.getGetObject();<xsl:for-each select="/metadata/models/model[@id=$model_id]/field[@primaryKey='TRUE']">	pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options));</xsl:for-each>}</xsl:template><xsl:template match="controller[@parentId='ControllerSQLMasterDetail']/publicMethod[@id='get_object']"><xsl:variable name="model_id" select="@modelId"/><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:value-of select="$contr_id"/>.prototype.addGetObject = function(){	<xsl:value-of select="$contr_id"/>.superclass.addGetObject.call(this);	var options = {};	<xsl:if test="@alias">options["alias"]="<xsl:value-of select="@alias"/>";</xsl:if>	var pm = this.getGetObject();<xsl:for-each select="/metadata/models/model[@id=$model_id]/field[@primaryKey='TRUE']">	pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options));</xsl:for-each>}</xsl:template><xsl:template match="publicMethod[@id='get_list']"><xsl:variable name="model_id" select="@modelId"/><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:value-of select="$contr_id"/>.prototype.addGetList = function(){	<xsl:value-of select="$contr_id"/>.superclass.addGetList.call(this);	var options = {};	<xsl:if test="@alias">options["alias"]="<xsl:value-of select="@alias"/>";</xsl:if>	var pm = this.getGetList();<xsl:for-each select="/metadata/models/model[@id=$model_id]/field">	pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options));</xsl:for-each>	<xsl:for-each select="field">		var options = {};		<xsl:if test="@alias">			options["alias"]="<xsl:value-of select="@alias"/>";		</xsl:if>		<xsl:if test="@required">			options["required"]=true;		</xsl:if>						pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>",options));	</xsl:for-each>	<xsl:variable name="order" select="/metadata/models/model[@id=$model_id]/defaultOrder"/>	<xsl:if test="$order">	pm.getParamById(this.PARAM_ORD_FIELDS).setValue("<xsl:for-each select="$order/field"><xsl:if test="position() &gt; 1">,</xsl:if><xsl:value-of select="@id"/></xsl:for-each>");	</xsl:if>}</xsl:template><xsl:template match="publicMethod"><xsl:variable name="contr_id" select="concat(../@id,'_Controller')"/><xsl:value-of select="$contr_id"/>.prototype.add_<xsl:value-of select="@id"/> = function(){	var pm = this.addMethodById('<xsl:value-of select="@id"/>');	<xsl:apply-templates/>}</xsl:template><xsl:template match="publicMethod/field">	<xsl:variable name="model_field" select="/metadata/models/model[@id=./../@modelId]/field[@id=./@id]"/>	<xsl:choose>	<xsl:when test="$model_field">		pm.addParam(new Field<xsl:value-of select="$model_field/@dataType"/>("<xsl:value-of select="@id"/>"));		</xsl:when>	<xsl:otherwise>		pm.addParam(new Field<xsl:value-of select="@dataType"/>("<xsl:value-of select="@id"/>"));	</xsl:otherwise>	</xsl:choose></xsl:template></xsl:stylesheet>