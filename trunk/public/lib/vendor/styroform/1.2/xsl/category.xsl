<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="category_search">
  
  <div id="{@name}_wrapper">
    
    <div id="results">
        <xsl:choose>
          <xsl:when test="count(LISTITEM) = 0">
            <table cellpadding="4">
              <tr>
                <td colspan="2" align="right" class="wiki_bluerule"><img src="/images/horizontal_rule.jpg" /></td>
              </tr>
              <tr>
                <td><img src="/prod/images/ttj_smallicon.jpg" width="100" height="100" /></td>
                <td>No Categories Available!</td>
              </tr>
            </table>
          </xsl:when>
          <xsl:otherwise>
            <xsl:for-each select="LISTITEM[position() mod 5 = 1]">
              <div class="category_table">	
              <xsl:apply-templates select=".|following-sibling::LISTITEM[position() &lt; 5]" mode="category_search_table">
                <xsl:with-param name="pos" select="position()" />
              </xsl:apply-templates>
              </div>
           </xsl:for-each>
          </xsl:otherwise>
        </xsl:choose>
    </div>
    
  </div>
  
</xsl:template>

<xsl:template match="LISTITEM" mode="category_search_table">
  <xsl:param name="pos" />
    <span class="category_cell">
      <xsl:choose>
        <xsl:when test="$pos = 1 and position() = 1">
          <span class="category_cell category_search">
            <form action="/search" method="get">
              <input id="DojoSearchField" class="catsearch_input" type="text" value="" />
              <input id="DojoSearchSubmit" class="catsearch_submit" type="image" src="/images/icons/find_my_tattoo.jpg" value="" border="0" />
            </form>
          </span>
        </xsl:when>
        <xsl:otherwise>
          <span class="category_thumb">
            <a href="/search/{@category_name}">
              <img src="/images/icons/{@category_image}" border="0" alt="{@category_name} Tattoo" title="{@category_name} Tattoo" />
            </a>
          </span>
          <span class="category_name">
            <a href="/search/{@category_name}"><xsl:value-of select="@category_name" />
            </a>
          </span>
        </xsl:otherwise>
      </xsl:choose>
      
    </span>
    
</xsl:template>

</xsl:stylesheet>
