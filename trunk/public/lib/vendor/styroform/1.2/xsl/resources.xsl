<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="resource_list">
  
  <div class="{@name}_wrapper">
    
    <xsl:if test="count(LISTITEM) > 0">
    
    <h2><xsl:value-of select="@title" /></h2>
    
    <xsl:apply-templates select="LISTITEM" mode="resource_listitem" />
    
    </xsl:if>
    
  </div>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="resource_listitem">
  <div class="resource-listing">
  	
    <table border="0" cellpadding="0" cellspacing="0" width="550">
  		<tr>
  			<td valign="bottom">
          <div class="site-plain"><xsl:value-of select="resource_website_name" /></div>
        </td>
  			<td width="75">
        	<div class="ratingblock">
            <div id="unit_long_{resource_id}" class="unit_long">
          		<ul id="unit_ul_{resource_id}" class="unit-rating">
          		<li class="current-rating" style="width: {(number(resource_total_value) div number(resource_total_votes)) * 15 }px">Currently <xsl:value-of select="number(resource_total_value) div number(resource_total_votes)" />/5</li>
          		
          		<li><a href="/resources/vote/1/{resource_id}" title="1 out of 5" class="r1-unit rater">1</a></li>
          		<li><a href="/resources/vote/2/{resource_id}" title="2 out of 5" class="r2-unit rater">2</a></li>
          		<li><a href="/resources/vote/3/{resource_id}" title="3 out of 5" class="r3-unit rater">3</a></li>
          		<li><a href="/resources/vote/4/{resource_id}" title="4 out of 5" class="r4-unit rater">4</a></li>
          		<li><a href="/resources/vote/5/{resource_id}" title="5 out of 5" class="r5-unit rater">5</a></li>
          		
          		</ul>
            </div>
          </div>
        </td>
  		</tr>
  	</table>
		
    <div class="resource-description">
      <xsl:value-of select="resource_website_description"  disable-output-escaping="yes" />
    </div>
	
		<div align="center" class="resource-banner-img">
      <xsl:choose>
      <xsl:when test="resource_banner_style=0">
      	<div align="center" class="resource-banner-img"><a href="/resources/forward/{resource_id}" target="_New"><img src="http://wiki.tattoojohnny.com/images/Banners/client/banner{resource_id}.jpg" border="0" alt="{resource_website_name}" /></a></div>
		  </xsl:when>
      <xsl:when test="resource_banner_style=1">
      	<div class="resource-url"><a href="/resources/forward/{resource_id}" target="_New"><img src="{resource_banner_url}" border="0" alt="{resource_website_name}" /></a></div>
		  </xsl:when>
      <xsl:when test="resource_banner_style=2">
      	<xsl:choose>
        <xsl:when test="resource_HTML != ''">
          <div class="resource-url"><xsl:value-of select="resource_HTML" disable-output-escaping="yes" /></div>
			  </xsl:when>
			  <xsl:otherwise>
			   <div class="resource-url"><a href="/resources/forward/{resource_id}" target="_New"><xsl:value-of select="resource_website_url" /></a></div>
			  </xsl:otherwise>
			  </xsl:choose>
      </xsl:when>
      </xsl:choose>
    </div>
		
	</div>
  
</xsl:template>

</xsl:stylesheet>
