<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:import href="Model_js.xsl"/>

<!-- -->
<xsl:variable name="MODEL_ID" select="'DocOrderAttachment'"/>
<!-- -->

<xsl:output method="text" indent="yes"
			doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
			doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
<xsl:template match="/">
	<xsl:apply-templates select="metadata/models/model[@id=$MODEL_ID]"/>
</xsl:template>
			
</xsl:stylesheet>
