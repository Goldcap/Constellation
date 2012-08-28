<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output
method="html"
version="1.0"
encoding="iso-8859-1"
omit-xml-declaration="yes"
standalone="no"
doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN"
doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
indent="yes"/>

<xsl:template match="PAGE">
  <xsl:apply-templates select="//AFORM" />
  <xsl:apply-templates select="//LIST" />
</xsl:template>

<xsl:include href="title_result.xsl" />
<xsl:include href="search_result.xsl" />
<xsl:include href="press.xsl" />
<xsl:include href="wiki.xsl" />
<xsl:include href="design_guide.xsl" />
<xsl:include href="cart.xsl" />
<xsl:include href="product.xsl" />
<xsl:include href="category.xsl" />
<xsl:include href="design.xsl" />
<xsl:include href="store.xsl" />
<xsl:include href="stats.xsl" />
<xsl:include href="faq.xsl" />
<xsl:include href="resources.xsl" />
<xsl:include href="dojo.xsl" />
<xsl:include href="styroform.xsl" />
<xsl:include href="formelementgroups.xsl" />
<xsl:include href="formelements.xsl" />
<xsl:include href="formelements_output.xsl" />
<xsl:include href="formelements_dojo.xsl" />
<xsl:include href="formelements_jquery.xsl" />
<xsl:include href="htmlentities.xsl" />
<xsl:include href="listitems.xsl" />
<xsl:include href="listitems_anchor.xsl" />
<xsl:include href="split.xsl" />
<xsl:include href="truncate.xsl" />
<xsl:include href="substitute.xsl" />
<xsl:include href="paddedvalues.xsl" />
<xsl:include href="pagination.xsl" />
<xsl:include href="initcap.xsl" />
<xsl:include href="payflow_report.xsl" />
<xsl:include href="wt_image.xsl" />
<xsl:include href="wt_album.xsl" />

</xsl:stylesheet>
