<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="store_search">
  
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
    
    <div id="titlebar" class="pagenav">
      
      <h2><xsl:value-of select="@title" /></h2>
    
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
      
    <div id="results">
      <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="550px;">	
        <xsl:choose>
          <xsl:when test="count(LISTITEM) = 0">
            <tr>
            <td>
            <table cellpadding="4">
              <tr>
                <td colspan="2" align="right" class="wiki_bluerule"><img src="http://ll.tattoojohnny.com/images/horizontal_rule.jpg" /></td>
              </tr>
              <tr>
                <td><img src="http://ll.tattoojohnny.com/images/ttj_smallicon.jpg" width="100" height="100" /></td>
                <td>No Items Available!</td>
              </tr>
            </table>
            </td>
            </tr>
          </xsl:when>
          <xsl:otherwise>
            <xsl:for-each select="LISTITEM[position() mod 3 = 1]">
              <tr>
              <xsl:choose>
              <xsl:when test="../@name='super_flash'">
              <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 3]" mode="flash_super_table" />
              </xsl:when>
              <xsl:when test="../@name='fine_art'">
              <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 3]" mode="fine_art_table" />
              </xsl:when>
              <xsl:when test="../@name='door_size'">
              <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 3]" mode="door_table" />
              </xsl:when>
              <xsl:when test="../@name='dvd'">
              <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 3]" mode="dvd_table" />
              </xsl:when>
              <xsl:otherwise>
              <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 3]" mode="flash_set_table" />
              </xsl:otherwise>
              </xsl:choose>
              </tr>
           </xsl:for-each>
          </xsl:otherwise>
        </xsl:choose>
      </table>
          
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
    
  </div>
  
</xsl:template>

<!--Super Value Flash Sets!-->
<xsl:template match="LISTITEM" mode="flash_super_table">
    <td width="33%">	
      <div class="design_thumb">
        <a href="/store/flashsets/{product_sku}">
          <img src="http://ll.tattoojohnny.com{product_image_path}/{product_sku}/{product_sku}_flash.jpg" border="0" width="160" alt="Tattoo Design" />
        </a>
      </div>
      <div>
        <a href="/store/flashsets/{product_sku}" class="store-list">
        <br /><xsl:value-of select="product_name" /></a> <!--(<strong><xsl:value-of select="product_sku" /></strong>)-->
        <br /><xsl:value-of select="product_number_sheets" /> Sheets - $<xsl:value-of select="product_price" />
      </div>
      <br />
    </td>
    <xsl:if test="(position() = last()) and (position() &lt; 3)">
      <xsl:call-template name="FillerCells">
         <xsl:with-param name="cellCount" select="3 - position()"/>
      </xsl:call-template>
    </xsl:if>
</xsl:template>

<!--Standard Flash Sets!-->
<xsl:template match="LISTITEM" mode="flash_set_table">
    <td width="33%">	
      <div class="design_thumb">
        <a href="/store/flashsets/{product_sku}">
          <img src="http://ll.tattoojohnny.com{product_image_path}/{product_sku}/{product_sku}_thumb.jpg" border="0" width="120" alt="Tattoo Design" />
        </a>
      </div>
      <div>
        <a href="/store/flashsets/{product_sku}" class="store-list">
        <br /><xsl:value-of select="product_artist_fname" /><xsl:text> </xsl:text><xsl:value-of select="product_artist_lname" /></a>
        <br /><xsl:value-of select="product_name" /> <!--(<strong><xsl:value-of select="product_sku" /></strong>)-->
        <br /><xsl:value-of select="product_number_sheets" /> Sheets - $<xsl:value-of select="product_price" />
      </div>
      <br />
    </td>
    <xsl:if test="(position() = last()) and (position() &lt; 3)">
      <xsl:call-template name="FillerCells">
         <xsl:with-param name="cellCount" select="3 - position()"/>
      </xsl:call-template>
    </xsl:if>
</xsl:template>

<!--Fine Art Prints!-->
<xsl:template match="LISTITEM" mode="fine_art_table">
    <td width="33%">	
      <div class="design_thumb">
        <a href="/store/fineart/{product_sku}">
          <img src="{product_image_path}/{product_sku}/{product_sku}_thumb.jpg" border="0" alt="Tattoo Design" />
        </a>
      </div>
      <div>
        <a href="/store/fineart/{product_sku}" class="store-list">
        <br /><xsl:value-of select="product_name" /> <!--(<strong><xsl:value-of select="product_sku" /></strong>)-->
        <br /><xsl:value-of select="product_artist_fname" /><xsl:text> </xsl:text><xsl:value-of select="product_artist_lname" /></a>
        <br />$<xsl:value-of select="product_price" />
      </div>
      <br />
    </td>
    <xsl:if test="(position() = last()) and (position() &lt; 3)">
      <xsl:call-template name="FillerCells">
         <xsl:with-param name="cellCount" select="3 - position()"/>
      </xsl:call-template>
    </xsl:if>
</xsl:template>

<!--Door Size Poster Prints!-->
<xsl:template match="LISTITEM" mode="door_table">
    <td width="33%">	
      <div class="design_thumb">
        <a href="/store/doorprints/{product_sku}">
          <img src="{product_image_path}/{product_sku}/{product_sku}_thumb.jpg" border="0" alt="Tattoo Design" />
        </a>
      </div>
      <div>
        <a href="/store/doorprints/{product_sku}" class="store-list">
        <br /><xsl:value-of select="product_name" /> <!--(<strong><xsl:value-of select="product_sku" /></strong>)-->
        <br /><xsl:value-of select="product_artist_fname" /><xsl:text> </xsl:text><xsl:value-of select="product_artist_lname" /></a>
        <br />$<xsl:value-of select="product_price" />
      </div>
      <br />
    </td>
    <xsl:if test="(position() = last()) and (position() &lt; 3)">
      <xsl:call-template name="FillerCells">
         <xsl:with-param name="cellCount" select="3 - position()"/>
      </xsl:call-template>
    </xsl:if>
</xsl:template>

<!--DVD's!-->
<xsl:template match="LISTITEM" mode="dvd_table">
    <td width="33%">	
      <div class="design_thumb">
        <a href="/store/dvd/{product_sku}">
          <img src="{product_image_path}/{product_sku}/{product_sku}_thumb.jpg" border="0" width="85" alt="Tattoo Design" />
        </a>
      </div>
      <div>
        <a href="/store/dvd/{product_sku}" class="store-list"><xsl:value-of select="product_name" disable-output-escaping="yes" /></a>
        <!--(<strong><xsl:value-of select="product_sku" /></strong>)--><br />
        $<xsl:value-of select="product_price" />
      </div>
      <br />
    </td>
    <xsl:if test="(position() = last()) and (position() &lt; 3)">
      <xsl:call-template name="FillerCells">
         <xsl:with-param name="cellCount" select="3 - position()"/>
      </xsl:call-template>
    </xsl:if>
</xsl:template>

</xsl:stylesheet>
