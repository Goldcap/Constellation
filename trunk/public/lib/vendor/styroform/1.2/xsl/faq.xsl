<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="LIST" mode="faq_search">
  <div class="{@name}_wrapper">
    
    <xsl:if test="count(LISTITEM) > 0">
    <div class="faqHeadline"><xsl:value-of select="@title" /></div>
    
    <ul class="faqUL">
      <xsl:for-each select="LISTITEM">
      <li class="faqLI">
        <a href="/faq/{../@title}#{cms_object_link}" class="faqLink"><xsl:value-of select="cms_object_title" /></a></li>
      </xsl:for-each>
    </ul>
    <div class="faqMore">
      <a href="/faq/{@title}">more </a>
    </div>
    </xsl:if>
    
  </div>
  
</xsl:template>

<xsl:template match="LIST" mode="faq_category">
  <div class="{@name}_wrapper">
    
    <xsl:if test="count(LISTITEM) > 0">
    <div class="faqHeadline"><xsl:value-of select="@title" /></div>
    
    <ul class="faqUL">
      <xsl:for-each select="LISTITEM">
      <li class="faqLI">
        <a name="{cms_object_link}" class="reveal faqLink"><xsl:value-of select="cms_object_title" /></a>
        <div id="{cms_object_link}" class="reqs faqBody"><xsl:value-of select="cms_object_text" disable-output-escaping="yes" /></div>
      </li>
      </xsl:for-each>
    </ul>
    </xsl:if>
    <div class="faqMore">
      <a href="/faq">back </a>
    </div>
    
  </div>
  <div dojoType="dojo_widgets.faq.FAQ"></div>
  
</xsl:template>

</xsl:stylesheet>
