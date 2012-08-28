<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="guide_search">
  
  <div id="{@name}_wrapper">
    
    <div id="results">
      
      <table>
        <tr>
          <td>
        <xsl:choose>
          <xsl:when test="count(LISTITEM) = 0">
            <table cellpadding="4">
              <tr>
                <td colspan="2" align="right" class="wiki_bluerule"><img src="http://ll.tattoojohnny.com/images/horizontal_rule.jpg" /></td>
              </tr>
              <tr>
                <td><img src="http://ll.tattoojohnny.com/images/ttj_smallicon.jpg" width="100" height="100" /></td>
                <td>No Items Available!</td>
              </tr>
              <tr>
                <td colspan="2" align="right" class="wiki_bluerule"><img src="http://ll.tattoojohnny.com/images/horizontal_rule.jpg" /></td>
              </tr>
            </table>
          </xsl:when>
          <xsl:otherwise>
            <xsl:apply-templates select="self::*" mode="guide_search_table" />
          </xsl:otherwise>
        </xsl:choose>
            </td>
          </tr>
      </table>
          
    </div>
    
  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="guide_search_table">
  
  <table cellpadding="4">
    <xsl:for-each select="LISTITEM">
    <tr>
      <td><a href="/design_guide/{cms_object_link}"><xsl:value-of select="cms_object_title" disable-output-escaping="yes" /></a></td>
    </tr>
    </xsl:for-each>
  </table>
  
</xsl:template>

</xsl:stylesheet>
