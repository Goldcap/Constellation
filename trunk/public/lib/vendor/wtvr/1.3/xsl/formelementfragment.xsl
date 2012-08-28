<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" version="1.0" encoding="UTF-8" omit-xml-declaration="no" standalone="yes" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" doctype-system="http://www.w3.org/TR/html4/loose.dtd"/>

<xsl:template match="//ROOT">
  
  <xsl:apply-templates select="FORMELEMENTGROUP"/>
  <xsl:apply-templates select="FORMELEMENT"/>
	
</xsl:template>

</xsl:stylesheet>
