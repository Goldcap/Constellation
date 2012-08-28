<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="//module/attributes/assets/JSCRIPT">
  <xsl:variable name="gz"><xsl:if test="@gz='TRUE' and //@browsername != 'Safari'">.gz</xsl:if></xsl:variable>
	<xsl:choose>
  	<xsl:when test="@resourcebase!=''">
  	 <script type="text/JavaScript" src="{@resourcebase}{self::*}{$gz}"></script>
  	</xsl:when>
    <xsl:when test="../../../@resourcebase!=''">
  	 <script type="text/JavaScript" src="{../../../@resourcebase}{self::*}{$gz}"></script>
  	</xsl:when>
  	<xsl:otherwise>
  	 <script type="text/JavaScript" src="{../../../@baseurl}html/js/{self::*}{$gz}"></script>
  	</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="JSCRIPT">
	<xsl:variable name="gz"><xsl:if test="@gz='TRUE' and //@browsername != 'Safari'">.gz</xsl:if></xsl:variable>
	<xsl:choose>
    <xsl:when test="@baseurl!=''">
  	<script type="text/JavaScript" src="{@baseurl}{self::*}{$gz}"></script>
  	</xsl:when>
  	<xsl:otherwise>
  	<script type="text/JavaScript" src="{self::*}{$gz}"></script>
  	</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="PAGEJSCRIPT">
  <xsl:param name="gzip_js">FALSE</xsl:param>
  <xsl:param name="name"></xsl:param>
  <xsl:variable name="gz"><xsl:if test="$gzip_js='TRUE' and //@browsername != 'Safari'">.gz</xsl:if></xsl:variable>
	<script type="text/JavaScript" src="/pages/{$name}/html/js/{$name}.js{$gz}"></script>
</xsl:template>

<!--Dojo elements are in the formelements_dojo XSL file -->

</xsl:stylesheet>
