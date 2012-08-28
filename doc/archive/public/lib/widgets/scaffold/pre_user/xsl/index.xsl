<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" version="1.0" encoding="UTF-8" omit-xml-declaration="no" standalone="yes" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" />

<xsl:template match="widget[@name='pre_user']/content">
  <xsl:apply-templates select="AFORM" />
  <xsl:apply-templates select="LIST" />
</xsl:template>

<xsl:include href="../../../vendor/styroform/1.2/xsl/includes.xsl" />

</xsl:stylesheet>
