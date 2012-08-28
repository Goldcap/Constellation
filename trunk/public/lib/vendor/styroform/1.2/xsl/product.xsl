<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="product_search">
  <div id="{@name}_wrapper">
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
    
    <xsl:variable name="TERMDISPLAY">
      <xsl:for-each select="TERMS/TERMITEM">
        <xsl:value-of select="@value" />
      </xsl:for-each>
    </xsl:variable>
    
    <div id="titlebar" class="pagenav">
      
      <xsl:if test="//PROCESS/VAR[@name='op_rev']/@value != ''">
        <h2>
        <xsl:if test="//PROCESS/VAR[@name='op_rev']/@value = 'artist'">
        <xsl:call-template name="initCapSpace"><xsl:with-param name="str" select="//PARAMS/VAR[1]/@name" /></xsl:call-template> 
        </xsl:if>
        <xsl:if test="//PARAMS/VAR[@name='artist']/@value != ''">
        <xsl:call-template name="initCapSpace"><xsl:with-param name="str" select="//PARAMS/VAR[@name='artist']/@value" /></xsl:call-template><xsl:text> </xsl:text>
        </xsl:if>
        <xsl:if test="//PROCESS/VAR[@name='op_rev']/@value != 'artist'">
        <xsl:call-template name="initCapSpace"><xsl:with-param name="str" select="//PROCESS/VAR[@name='op_rev']/@value" /></xsl:call-template> 
        </xsl:if>
        Tattoo Designs</h2>
      </xsl:if>
   </div>
   
   <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
   
   <div class="navbar pagenav">  
   
      <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
      
      <span class="pagination">
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
      </span>
     
    </div>   
    
    </xsl:if>
    
    <xsl:if test="count(SORTITEMS/SORTITEM) > 0">
    <form action="#" method="get">
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
      
    <xsl:variable name="theskus">
      <xsl:for-each select="LISTITEM"><xsl:value-of select="product_sku" />,</xsl:for-each>
    </xsl:variable>
    <div id="results">
      <table class="product_search" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="550px;">	
        <xsl:choose>
          <xsl:when test="count(LISTITEM) = 0">
            <tr>
            <td>
            <table cellpadding="4">
              <tr>
                <td colspan="2" align="right" class="wiki_bluerule"><img src="/images/horizontal_rule.jpg" /></td>
              </tr>
              <tr>
                <td><img src="/images/ttj_smallicon.jpg" width="100" height="100" /></td>
                <td>No Items Available!</td>
              </tr>
            </table>
            </td>
            </tr>
          </xsl:when>
          <xsl:otherwise>
            <xsl:for-each select="LISTITEM[position() mod 3 = 1]">
              <tr>
              <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 3]" mode="product_search_table">
                <xsl:with-param name="skus"><xsl:value-of select="$theskus" /></xsl:with-param>
                <xsl:with-param name="position"><xsl:value-of select="position()" /></xsl:with-param>
              </xsl:apply-templates>
              </tr>
           </xsl:for-each>
          </xsl:otherwise>
        </xsl:choose>
      </table>
          
    </div>
    
    <div class="navbar pagenav">
      
      <xsl:if test="@docount != 'false' and ceiling(@totalResults div @rpp) > 1">
        
        <span class="count">Showing Items <xsl:value-of select="$startrecord" /> to <xsl:value-of select="$maxcount" /> of <xsl:value-of select="@totalResults" /></span>
        
        <span class="pagination">
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
        </span>
        
      </xsl:if>
    
    </div>
    
  </div>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="product_search_table">
    <xsl:param name="position">null</xsl:param>
    <xsl:param name="skus">null</xsl:param>
    <xsl:variable name="thepos"><xsl:value-of select="($position - 1) * 3 + position()" /></xsl:variable>
    <td width="33%">	
      <div class="design_thumb">
        <a href="/product/{product_sku}/{../..//LIST/@result_id}/searchpage/{../@page}/image/{$thepos}/prods/{$skus}">
          <img src="{product_image_path}/{product_sku}/{product_sku}_thumb.jpg" border="0" alt="Tattoo Design" />
        </a>
      </div>
      <div class="wishlist_add">
        <!--<span class="makeWish" sku="{product_sku}">Add to WishList</span>-->
      </div>
      <div class="download_design">
        <!--<a href="/download/{product_sku}">Download</a>-->
      </div>
    </td>
    <xsl:if test="(position() = last()) and (position() &lt; 3)">
      <xsl:call-template name="FillerCells">
         <xsl:with-param name="cellCount" select="3 - position()"/>
         <xsl:with-param name="fillSize" select="'33'"/>
      </xsl:call-template>
    </xsl:if>
</xsl:template>

<xsl:template name="FillerCells">
   <xsl:param name="cellCount"/>
   <xsl:param name="fillSize">33</xsl:param>
   <td width="{$fillSize}%"> </td>
   <xsl:if test="$cellCount > 1">
      <xsl:call-template name="FillerCells">
         <xsl:with-param name="cellCount" select="$cellCount - 1"/>
      </xsl:call-template>
   </xsl:if>
</xsl:template>

</xsl:stylesheet>
