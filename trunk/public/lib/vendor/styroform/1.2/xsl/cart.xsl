<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- This result is used in the Admin Cart Widget, formatting search results for "add" -->
<xsl:template match="LIST" mode="cart_search_result">
  
  <div id="{@name}_wrapper">
    <div id="titlebar">
      
      <xsl:if test="@title != ''">
        <span class="row"><span id="formhead" class="headline display"><xsl:value-of select="@title" /></span></span>
      </xsl:if>
      
      <xsl:if test="textbody != ''">
        <p><xsl:value-of select="textbody" disable-output-escaping="yes" /></p>
      </xsl:if>
      
      <xsl:if test="@allow_add != 'false'">
      <p>
      <a href="{@script}/detail/0">Add a new <xsl:value-of select="@name" /></a>
      </p>
      </xsl:if>
        
      <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
        <xsl:variable name="startrecord"><xsl:value-of select="(@page - 1) * @rpp + 1" /></xsl:variable>
        <xsl:variable name="maxcount">
        <xsl:choose>
          <xsl:when test="($startrecord + @rpp - 1) > @totalResults">
            <xsl:value-of select="@totalResults" />
          </xsl:when>
          <xsl:otherwise>
            <xsl:value-of select="$startrecord + @rpp - 1" />
          </xsl:otherwise>
        </xsl:choose>
      </xsl:variable>
      
      <xsl:call-template name="SINGLE_PAGELOOP">
          <xsl:with-param name="page" select="@page" />
          <xsl:with-param name="rpp" select="@rpp" />
          <xsl:with-param name="ppp" select="@ppp" />
          <xsl:with-param name="totalResults" select="@totalResults" />
          <xsl:with-param name="start" select="@page" />
          <xsl:with-param name="repeat" select="ceiling(@totalResults div @rpp)"/>
          <xsl:with-param name="SELECTED" select="@page" />
          <xsl:with-param name="TYPE" select="'STATIC'" />
          <xsl:with-param name="BASEFUNC" select="'nextPage'" />
          <xsl:with-param name="ATTRIBS"></xsl:with-param>
          <xsl:with-param name="BASEURL"><xsl:value-of select="@url" /></xsl:with-param>
          <xsl:with-param name="BASEQS"><xsl:value-of select="@query_string" /></xsl:with-param>
          <xsl:with-param name="SITEMS" select="SORTITEMS" />
        </xsl:call-template>
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
      </xsl:if>
      
      <xsl:if test="count(SORTITEMS/SORTITEM) > 0">
        <form action="{@url}" method="get">
        <span class="sort">
          Sort by: <select name="sort" class="sortselect" onChange="this.form.submit();">
            <xsl:for-each select="SORTITEMS/SORTITEM">
            <xsl:choose>
            <xsl:when test="@selected='true' and (count(preceding-sibling::*[@selected='true']) = 0)">
            <option value="{@value}" selected="selected"><xsl:value-of select="@description" /></option>
            </xsl:when>
            <xsl:otherwise>
            <option value="{@value}"><xsl:value-of select="@description" /></option>
            </xsl:otherwise>
            </xsl:choose>
            </xsl:for-each>
          </select>
        </span>
        </form>
      </xsl:if>
        
    </div>
    
    <div id="list">
  
      <xsl:choose>
        <xsl:when test="count(LISTITEM) = 0">
          <div class="listitem listitem_1">
           <span class="entry">
            <span class="column_Entry">
            No products available, please try again.
            </span>
           </span>
        </div>
        </xsl:when>
        <xsl:otherwise>
          <xsl:apply-templates select="LISTITEM" mode="cart_search_result_table" />
        </xsl:otherwise>
      </xsl:choose>
        
      </div>
    
  </div>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="cart_search_result_table">
  <xsl:if test="(position() = 1) and (../@header!='false')">
  <div class="listitem name">
    <xsl:apply-templates select="self::*[1]/@*" mode="css_name" />
  </div>
  </xsl:if>
  
  <div class="listitem listitem_{position() mod 2}">
    <xsl:apply-templates select="self::*" mode="cart_search_result_table_data" />
  </div>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="cart_search_result_table_data">
  
  <span class="entry"><span class="column_Image"><img src="http://wiki.tattoojohnny.com{@product_image_path}/{@Sku}/{@Sku}_color.jpg" /></span></span>
  <span class="entry"><span class="column_Sku"><a href="/cart/add/{@Sku}"><xsl:value-of select="@Sku" /></a></span></span>
  <span class="entry"><span class="column_Artist"><xsl:value-of select="@Artist" /></span></span>
  <span class="entry"><span class="column_Name"><xsl:value-of select="@Name" /></span></span>
  <span class="entry"><span class="column_Color"><xsl:value-of select="@Color" /></span></span>
  <span class="entry"><span class="column_Download"><xsl:value-of select="@Download" /></span></span>
  <span class="entry"><span class="column_Size"><xsl:value-of select="@Size" /></span></span>
  <span class="entry"><span class="column_Price">$<xsl:value-of select="@Flat_Price" /><xsl:value-of select="@Price" /></span></span>
    
</xsl:template>


</xsl:stylesheet>
