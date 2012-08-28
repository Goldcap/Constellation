<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="RESULTS_ANCHOR">
  <div id="{@name}_results">
    <div id="resultcount">
        <span class="headline"><xsl:value-of select="@title" /></span>
      <xsl:if test="@docount != 'false'">
        <xsl:call-template name="PAGINATION" />
      </xsl:if>
      <xsl:apply-templates select="HEADER_MENU_ANCHOR" />
    </div>
    
    <div id="results">
      <a name="top"></a>
      <xsl:apply-templates select="LIST" mode="tableofcontents"/>
    </div>
    
    <div id="results">
      <xsl:apply-templates select="LIST" mode="anchor"/>
    </div>
  </div>
</xsl:template>

<xsl:template match="HEADER_MENU_ANCHOR">
  <span class="{../@name}_menu">
    <ul>
    <xsl:for-each select="HEADER_MENU_ITEM">
      <li><a href="{@href}"><xsl:value-of select="self::*" /></a><xsl:if test="position() != 1"> |</xsl:if></li>
    </xsl:for-each>
    </ul>
  </span>
</xsl:template>
<!-- TABLE LISTS -->

<xsl:template match="LIST[@type='table']" mode="anchor">
  <div id="{@classid}">
  <table border="1">
  
  <xsl:apply-templates select="LIST_ITEM" mode="table_anchor" />
  
  </table>
	</div>
	
  <xsl:call-template name="PAGINATION" />
  
</xsl:template>

<xsl:template match="LIST_ITEM" mode="table_anchor">
  <xsl:if test="(position() = 1) and (../@header!='false')">
  <tr>
    <xsl:apply-templates select="self::*[1]/@*" mode="table_name" />  
  </tr>
  </xsl:if>
  <tr>
    <xsl:apply-templates select="@*" mode="table_data" />  
  </tr>
</xsl:template>

<!-- CSS LISTS -->

<xsl:template match="LIST[@type='css']" mode="tableofcontents">
  <div id="list">
  
  <xsl:apply-templates select="LIST_ITEM" mode="tableofcontents" />
  
  </div>
  
</xsl:template>

<xsl:template match="LIST_ITEM" mode="tableofcontents">
  
  <div class="listitem listitem_{position() mod 2}">
    <a href="#item_{position()}">
    <xsl:apply-templates select="@subhead" mode="css_data" />
    <xsl:apply-templates select="@title" mode="css_data" />
    </a>
  </div>
  
</xsl:template>

<xsl:template match="LIST[@type='css']" mode="anchor">
  <div id="list">
  
  <xsl:apply-templates select="LIST_ITEM" mode="css_anchor" />
  
  </div>
  
</xsl:template>

<xsl:template match="LIST_ITEM" mode="css_anchor">
  
  <xsl:if test="(position() = 1) and (../@header!='false')">
  <div class="listitem">
    <a name="item_{position()}"></a>
    <xsl:apply-templates select="self::*[1]/@*" mode="css_name" />
  </div>
  </xsl:if>
  
  <div class="listitem listitem_{position() mod 2}">
    <a name="item_{position()}"></a>
    <xsl:apply-templates select="@*" mode="css_data_anchor" />
  </div>
  
</xsl:template>

<xsl:template match="@*" mode="css_data_anchor">  
    <xsl:if test="name() = 'subhead'">
      <span class="entry">
        <span class="toplink">
          <a href="#top">back to top</a><br /><br /><hr />
        </span>
      </span>
    </xsl:if>
    <span class="entry">
      <span class="{name()}">
         <xsl:call-template name="split">
          <xsl:with-param name="str" select="."/>
          <xsl:with-param name="pat" select="'|'"/>
        </xsl:call-template>
      </span>
     
    </span>
</xsl:template>

</xsl:stylesheet>
