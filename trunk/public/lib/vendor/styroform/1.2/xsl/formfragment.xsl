<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="//ROOT">
  
  <xsl:apply-templates select="FORMELEMENTGROUP"/>
	
</xsl:template>

<xsl:include href="formelementgroups.xsl" />
<xsl:include href="formelements.xsl" />
<xsl:include href="formelements_output.xsl" />
<xsl:include href="formelements_dojo.xsl" />

</xsl:stylesheet>
