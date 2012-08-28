<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

 <xsl:template name="pad-string">
   <xsl:param name="length"/>
   <xsl:param name="value"/>
   <xsl:param name="pos" select="string-length($value)"/>
   <xsl:choose>
     <xsl:when test="$pos &lt; $length">
       <xsl:call-template name="pad-string">
         <xsl:with-param name="length" select="$length"/>
         <xsl:with-param name="value" select="concat($value, ' ')"/>
         <xsl:with-param name="pos" select="$pos + 1"/>
       </xsl:call-template>
     </xsl:when>
     <xsl:otherwise>
       <xsl:value-of select="$value"/>
     </xsl:otherwise>
   </xsl:choose>
 </xsl:template>

 <xsl:template name="pad-number">
   <xsl:param name="length"/>
   <xsl:param name="value"/>
   <xsl:param name="pos" select="string-length($value)"/>
   <xsl:choose>
     <xsl:when test="$pos &lt; $length">
       <xsl:call-template name="pad-number">
         <xsl:with-param name="length" select="$length"/>
         <xsl:with-param name="value" select="concat('0', $value)"/>
         <xsl:with-param name="pos" select="$pos + 1"/>
       </xsl:call-template>
     </xsl:when>
     <xsl:otherwise>
       <xsl:value-of select="$value"/>
     </xsl:otherwise>
   </xsl:choose>
 </xsl:template>

</xsl:stylesheet>
