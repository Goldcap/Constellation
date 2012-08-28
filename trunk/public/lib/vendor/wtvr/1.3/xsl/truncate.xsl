<?xml version="1.0" encoding='UTF-8'?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml" xmlns:doc="http://xsltsl.org/xsl/documentation/1.0" exclude-result-prefixes="doc" version = "1.0">

     <xsl:template name="truncate_phrase">
      <xsl:param name="phrase" />
      <xsl:param name="length" />
      <xsl:param name="trailing_string" select="'...'" />
      <xsl:param name="truncate_to_word_boundary" select="0" />

      <xsl:choose>
       <xsl:when test="string-length($phrase)>number($length)">
        <xsl:choose>
	 <xsl:when test="$truncate_to_word_boundary">
          <xsl:call-template name="truncate_to_word_boundary">
	   <xsl:with-param name="str">
	    <xsl:value-of select="substring($phrase,0,number($length))" />
	   </xsl:with-param>
	  </xsl:call-template>
	 </xsl:when>
	 <xsl:otherwise>
	  <xsl:value-of select="substring($phrase,0,number($length))" />
	 </xsl:otherwise>
	</xsl:choose>
	<xsl:value-of select="$trailing_string" />
       </xsl:when>
       <xsl:otherwise>
        <xsl:value-of select="$phrase" />
       </xsl:otherwise>
      </xsl:choose>
     </xsl:template>

     <xsl:template name="truncate_to_word_boundary">
      <xsl:param name="str" />
      <xsl:variable name="ret" select="substring($str,0,string-length($str))" />

      <xsl:choose>
       <xsl:when test="$str=''" />
       <xsl:when test="substring($str,string-length($str),1)=' '">
	<xsl:value-of select="$ret" />
       </xsl:when>
       <xsl:otherwise>
        <xsl:call-template name="truncate_to_word_boundary">
         <xsl:with-param name="str">
          <xsl:value-of select="$ret" />
         </xsl:with-param>
        </xsl:call-template>
       </xsl:otherwise>
      </xsl:choose>
     </xsl:template>

</xsl:stylesheet>
