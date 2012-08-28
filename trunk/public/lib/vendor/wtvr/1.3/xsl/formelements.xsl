<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:variable name="HOSTNAME">
<xsl:choose>
  <xsl:when test="PAGE/@baseurl != ''">
    <xsl:value-of select="PAGE/@baseurl" />
  </xsl:when>
  <xsl:when test="module/@hostname != ''">
   <xsl:value-of select="module/@hostname" />
  </xsl:when>
  <xsl:when test="ROOT/@hostname != ''">
   <xsl:value-of select="ROOT/@hostname" />
  </xsl:when>
  <xsl:otherwise></xsl:otherwise>
</xsl:choose>
</xsl:variable>

<xsl:variable name="monthlocation"><xsl:value-of select="//@docroot" />src/xml/months.xml</xsl:variable>
<xsl:variable name="minutelocation"><xsl:value-of select="//@docroot" />src/xml/minutes.xml</xsl:variable>
<xsl:variable name="countrylocation"><xsl:value-of select="//@docroot" />src/xml/countries.xml</xsl:variable>
<xsl:variable name="statelocation"><xsl:value-of select="//@docroot" />src/xml/states.xml</xsl:variable>

<xsl:template match="REQUIRED">
	<xsl:variable name="REQWRITE" select="//REQS" />
	<xsl:if test="$REQWRITE = 'TRUE' and self::* = 'TRUE'">
		<span class="reqnotice">*</span>
	</xsl:if>
	<xsl:if test="$REQWRITE = 'TRUE' and self::* = 'FALSE'">
		<span class="reqnotice">&#160;</span>
	</xsl:if>
</xsl:template>

<xsl:template match="DISPLAY">
	<xsl:choose>
		<xsl:when test="@for != ''">
			<label for="{@for}">
			<xsl:apply-templates select="self::*" mode="VALUE" />
			</label>
		</xsl:when>
		<xsl:otherwise>
			<xsl:apply-templates select="self::*" mode="VALUE" />
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template match="DISPLAY" mode="VALUE">
	<xsl:choose>
		<xsl:when test="@url != ''"><a href="{@url}"><xsl:value-of select="self::*" disable-output-escaping="yes" /></a></xsl:when>
		<xsl:otherwise><xsl:value-of select="self::*" disable-output-escaping="yes" /></xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="REQS">
	<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreq{NAME}" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="vskip{NAME}" class="reqs">false</span>
			<span id="error{NAME}" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='display']">
	<span class="{DISPLAYCLASS}">
	 <xsl:choose>
		 <!-- What the heck is this? Doesn't look finished, eh? -->
     <xsl:when test="../TYPE/@nullomit = 'true'">
      <xsl:apply-templates select="DISPLAY" />
	   </xsl:when>
	   <xsl:otherwise>
      <xsl:apply-templates select="DISPLAY" />
	   </xsl:otherwise>
   </xsl:choose>
  </span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='displayselect']">
	<xsl:variable name="THEDEF" select="DEFAULT" />
  <span class="{DISPLAYCLASS}">
	 <xsl:choose>
		<xsl:when test="DEFAULT/@url != ''"><a href="{DEFAULT/@url}"><xsl:value-of select="VALUES/VALUE[@id=$THEDEF]" disable-output-escaping="yes" /></a></xsl:when>
		<xsl:otherwise><xsl:value-of select="VALUES/VALUE[@id=$THEDEF]" disable-output-escaping="yes" /></xsl:otherwise>
	 </xsl:choose>
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='name']">
	<a name="/NAME"></a>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='text']">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	<xsl:apply-templates select="DISPLAY" />
		<xsl:element name="input">
			<xsl:attribute name="type">text</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="NAME" /></xsl:attribute>
			<xsl:if test="DEFAULT/@onblur != ''">
				<xsl:attribute name="onBlur"><xsl:value-of select="DEFAULT/@onblur" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="DEFAULT/@onchange != ''">
				<xsl:attribute name="onChange"><xsl:value-of select="DEFAULT/@onchange" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="DEFAULT/@onselect != ''">
				<xsl:attribute name="onSelect"><xsl:value-of select="DEFAULT/@onselect" /></xsl:attribute>
			</xsl:if>
			<xsl:if test="DEFAULT/@onkeyup != ''">
				<xsl:attribute name="onKeyUp"><xsl:value-of select="DEFAULT/@onkeyup" /></xsl:attribute>
			</xsl:if>
			<xsl:attribute name="class"><xsl:value-of select="$THECLASS" /><xsl:if test="DISPLAYCLASS/@error = 'TRUE'">_error</xsl:if></xsl:attribute>
			<xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="DEFAULT" disable-output-escaping="yes" /></xsl:attribute>
			<xsl:attribute name="title"><xsl:value-of select="TITLE" /></xsl:attribute>
			<xsl:attribute name="maxlength"><xsl:value-of select="SIZE" /></xsl:attribute>
		</xsl:element>
		<!--<input type="text" name="{NAME}" onBlur="{DEFAULT/@js};" class="{$THECLASS}" id="{NAME}" value="{DEFAULT}" title="{TITLE}" maxlength="{SIZE}" />-->
		<xsl:call-template name="REQS" />
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='file']">
		<span class="{DISPLAYCLASS}">
			<input type="file" name="FILE_{NAME}" class="{CLASS}" border="0" />
			<input type="hidden" name="FILE_{NAME}_DEST" value="{//ITEMTYPE}" />
			<input type="hidden" name="FILE_{NAME}_PREFIX" value="{DISPLAY}" />
		</span>
		
		<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtipFILE_{NAME}" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreqFILE_{NAME}" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="errorFILE_{NAME}" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='password']">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	<xsl:apply-templates select="DISPLAY" />
		<input type="password" name="{NAME}" class="{$THECLASS}" MAXLENGTH="{SIZE}" id="{NAME}" value="{DEFAULT}" vtip="{VTIP}" vreq="{REQUIRED}" />
		<span id="error{NAME}" class="errorhidden"></span>
	</span>
	
	<xsl:call-template name="REQS" />
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='textarea']">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:variable name="THECLASS"><xsl:value-of select="CLASS" /><xsl:if test="DISPLAYCLASS/@error = 'TRUE'">_error</xsl:if></xsl:variable>
	<xsl:apply-templates select="DISPLAY" />
	  <xsl:choose>
	  <xsl:when test="SIZE != ''">
		<textarea name="{NAME}" class="{$THECLASS}" id="{NAME}" title="{TITLE}" rows="{SIZE/@rows}" cols="{SIZE/@cols}" onKeyPress="return thisValidator.maxLength(event, this,'{SIZE}','{SIZE/@alert}','{SIZE/@wipealert}');"><xsl:value-of select="DEFAULT"  disable-output-escaping="yes"/></textarea>
		<span id="size{NAME}" class="reqs"><xsl:value-of select="SIZE" /></span>
    </xsl:when>
		<xsl:otherwise>
		<textarea name="{NAME}" class="{$THECLASS}" id="{NAME}" title="{TITLE}" rows="{SIZE/@rows}" cols="{SIZE/@cols}" ><xsl:value-of select="DEFAULT" disable-output-escaping="yes"/></textarea>
		<span id="size{NAME}" class="reqs">0</span>
    </xsl:otherwise>
		</xsl:choose>
    <xsl:call-template name="REQS" />
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='selectnumber']">
  
  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<xsl:element name="select">
	 <xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute>
	 <xsl:attribute name="class"><xsl:value-of select="$THECLASS" /></xsl:attribute>
	 <xsl:attribute name="title"><xsl:value-of select="TITLE" /></xsl:attribute>
	 <xsl:attribute name="name"><xsl:value-of select="NAME" /></xsl:attribute>
	 <xsl:if test="TYPE/@multiple>0">
     <xsl:attribute name="multiple">multiple</xsl:attribute>
     <xsl:attribute name="size"><xsl:value-of select="TYPE/@multiple" /></xsl:attribute>
   </xsl:if>
  <xsl:call-template name="OPTIONLOOP">
    <xsl:with-param name="start" select="DEFAULT/@start"/>
    <xsl:with-param name="repeat" select="DEFAULT/@end"/>
    <xsl:with-param name="step" select="DEFAULT/@step"/>
    <xsl:with-param name="SELECTED" select="number(DEFAULT)"/>
  </xsl:call-template>
	</xsl:element>
	
	
	</span>
	
	<xsl:call-template name="REQS" />
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='datetime']">
	<xsl:variable name="MONTHS" select="document($monthlocation)/VALUES" />
  <xsl:variable name="MINUTES" select="document($minutelocation)/VALUES" />
  
  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:variable name="THECLASS">
	 <xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<select id="{NAME}|month|comp" class="{$THECLASS}month" name="{NAME}|month|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDVALUE" select="DEFAULT/@month" />
		<option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		<xsl:for-each select="$MONTHS/VALUE">
        	<xsl:choose>
				  <xsl:when test="number($SELECTEDVALUE) = number(@id)">
        			<option value="{@id}" selected="selected"><xsl:value-of select="@data" /></option>
        		</xsl:when>
        		<xsl:otherwise>
        			<option value="{@id}"><xsl:value-of select="@data" /></option>
        		</xsl:otherwise>
        	</xsl:choose>
        </xsl:for-each>
	</select>
	
	<select id="{NAME}|day|comp" class="{$THECLASS}day" name="{NAME}|day|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDDAY" select="DEFAULT/@day" />
    <option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		<xsl:call-template name="OPTIONLOOP">
        <xsl:with-param name="start" select="number(1)"/>
        <xsl:with-param name="repeat" select="number(31)"/>
        <xsl:with-param name="SELECTED" select="number($SELECTEDDAY)"/>
      </xsl:call-template>
	</select>
	
	<select id="{NAME}|year|comp" class="{$THECLASS}year" name="{NAME}|year|comp"  title="{TITLE}">
		  <xsl:variable name="SELECTEDYEAR" select="DEFAULT/@year" />
		  <option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
      <xsl:call-template name="OPTIONLOOP">
        <xsl:with-param name="start" select="VTIP/@startyear"/>
        <xsl:with-param name="repeat" select="number(2009)"/>
        <xsl:with-param name="SELECTED" select="number($SELECTEDYEAR)"/>
      </xsl:call-template>
	</select>
	
	<select id="{NAME}|hour|comp" class="{$THECLASS}hour" name="{NAME}|hour|comp"  title="{TITLE}">
		  <xsl:variable name="SELECTEDHOUR" select="DEFAULT/@hour" />
		  <option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
      <xsl:call-template name="HOUR_OPTIONLOOP">
        <xsl:with-param name="start" select="1"/>
        <xsl:with-param name="repeat" select="number(24)"/>
        <xsl:with-param name="SELECTED" select="number($SELECTEDHOUR)"/>
      </xsl:call-template>
	</select>
	
	<select id="{NAME}|min|comp" class="{$THECLASS}min" name="{NAME}|min|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDMIN" select="DEFAULT/@min" />
		<option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
    <xsl:for-each select="$MINUTES/VALUE">
        	<xsl:choose>
				  <xsl:when test="number($SELECTEDMIN) = number(@id)">
        			<option value="{@id}" selected="selected"><xsl:value-of select="@data" /></option>
        		</xsl:when>
        		<xsl:otherwise>
        			<option value="{@id}"><xsl:value-of select="@data" /></option>
        		</xsl:otherwise>
        	</xsl:choose>
        </xsl:for-each>
	</select>
	
  </span>
  
  <xsl:if test="//JSVALIDATE = 'TRUE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}|month|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|month|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|month|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|day|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|day|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|day|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|year|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|year|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|year|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|hour|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|hour|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|hour|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|min|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|min|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|min|comp" class="errorhidden"></span>
    </xsl:if>
	</xsl:if>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='date']">
	<xsl:variable name="MONTHS" select="document($monthlocation)/VALUES" />
  
  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:variable name="THECLASS">
	 <xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<select id="{NAME}|month|comp" class="{$THECLASS}month" name="{NAME}|month|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDVALUE" select="DEFAULT/@month" />
		<xsl:choose>
		  <xsl:when test="DISPLAY/@month != ''">
		    <option value="" selected="selected"><xsl:value-of select="DISPLAY/@month" /></option>
		  </xsl:when>
		  <xsl:otherwise>
		    <option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:otherwise>
		</xsl:choose>
    <xsl:for-each select="$MONTHS/VALUE">
    	<xsl:choose>
		  <xsl:when test="number($SELECTEDVALUE) = number(@id)">
    			<option value="{@id}" selected="selected"><xsl:value-of select="@data" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{@id}"><xsl:value-of select="@data" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</select>
	
	<select id="{NAME}|day|comp" class="{$THECLASS}day" name="{NAME}|day|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDDAY" select="DEFAULT/@day" />
		<xsl:choose>
		  <xsl:when test="DISPLAY/@day != ''">
		    <option value="" selected="selected"><xsl:value-of select="DISPLAY/@day" /></option>
		  </xsl:when>
		  <xsl:otherwise>
		    <option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:otherwise>
		</xsl:choose>
    <xsl:call-template name="OPTIONLOOP">
        <xsl:with-param name="start" select="number(1)"/>
        <xsl:with-param name="repeat" select="number(31)"/>
        <xsl:with-param name="SELECTED" select="number($SELECTEDDAY)"/>
      </xsl:call-template>
	</select>
	
  <select id="{NAME}|year|comp" class="{$THECLASS}year" name="{NAME}|year|comp"  title="{TITLE}">
		 <xsl:variable name="SELECTEDYEAR" select="DEFAULT/@year" />
		 <xsl:choose>
		  <xsl:when test="DISPLAY/@year != ''">
		    <option value="" selected="selected"><xsl:value-of select="DISPLAY/@year" /></option>
		  </xsl:when>
		  <xsl:otherwise>
		    <option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:otherwise>
		</xsl:choose>
      <xsl:call-template name="OPTIONLOOP">
        <xsl:with-param name="start" select="number(SIZE/@start)"/>
        <xsl:with-param name="repeat" select="number(SIZE/@end)"/>
        <xsl:with-param name="SELECTED" select="number($SELECTEDYEAR)"/>
      </xsl:call-template>
	</select>
	
  </span>
  
  <xsl:if test="//JSVALIDATE = 'TRUE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}|month|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|month|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|month|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|day|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|day|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|day|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|year|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|year|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|year|comp" class="errorhidden"></span>
    </xsl:if>
	</xsl:if>
	
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='monthyear']">
	<xsl:variable name="MONTHS" select="document($monthlocation)/VALUES" />
  
  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:variable name="THECLASS">
	 <xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<select id="{NAME}|month|comp" class="{$THECLASS}month" name="{NAME}|month|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDVALUE" select="DEFAULT/@month" />
		  <xsl:choose>
        <xsl:when test="$SELECTEDVALUE = ''"><option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option></xsl:when>
        <xsl:otherwise><option value=""><xsl:value-of select="DEFAULT" /></option></xsl:otherwise>
		  </xsl:choose>
      <xsl:for-each select="$MONTHS/VALUE">
    	<xsl:choose>
		  <xsl:when test="$SELECTEDVALUE = @data">
    			<option value="{@id}" selected="selected"><xsl:value-of select="@data" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{@id}"><xsl:value-of select="@data" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</select>
	
  <select id="{NAME}|year|comp" class="{$THECLASS}year" name="{NAME}|year|comp"  title="{TITLE}">
		  <xsl:variable name="SELECTEDYEAR" select="DEFAULT/@year" />
		  <xsl:choose>
        <xsl:when test="$SELECTEDYEAR = ''"><option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option></xsl:when>
        <xsl:otherwise><option value=""><xsl:value-of select="DEFAULT" /></option></xsl:otherwise>
		  </xsl:choose>
      <xsl:call-template name="OPTIONLOOP">
        <xsl:with-param name="start" select="number(SIZE/@start)"/>
        <xsl:with-param name="repeat" select="number(SIZE/@end)"/>
        <xsl:with-param name="SELECTED" select="number($SELECTEDYEAR)"/>
      </xsl:call-template>
	</select>
	
  </span>
  
  <xsl:if test="//JSVALIDATE = 'TRUE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}|month|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|month|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|month|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|year|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|year|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|year|comp" class="errorhidden"></span>
    </xsl:if>
	</xsl:if>
	
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='monthday']">
	<xsl:variable name="MONTHS" select="document($monthlocation)/VALUES" />
  
  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	
	<xsl:variable name="THECLASS">
	 <xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<xsl:variable name="MONTHERROR">
	 <xsl:if test="CLASS/@error_month = 'TRUE'">_error</xsl:if>
	</xsl:variable>
	
	<select id="{NAME}|month|comp" class="{$THECLASS}month{$MONTHERROR}" name="{NAME}|month|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDVALUE" select="DEFAULT/@month" />
		<xsl:if test="$SELECTEDVALUE != ''"><option value=""><xsl:value-of select="DEFAULT" /></option></xsl:if>
		<xsl:if test="$SELECTEDVALUE = ''"><option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option></xsl:if>
    <xsl:for-each select="$MONTHS/VALUE">
    	<xsl:choose>
		  <xsl:when test="number($SELECTEDVALUE) = number(@id)">
    			<option value="{@id}" selected="selected"><xsl:value-of select="@data" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{@id}"><xsl:value-of select="@data" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</select>
	
	<xsl:variable name="DAYERROR">
	 <xsl:if test="CLASS/@error_day = 'TRUE'">_error</xsl:if>
	</xsl:variable>
	
	<select id="{NAME}|day|comp" class="{$THECLASS}day{$DAYERROR}" name="{NAME}|day|comp"  title="{TITLE}">
		<xsl:variable name="SELECTEDDAY" select="DEFAULT/@day" />
    <xsl:if test="$SELECTEDDAY != ''"><option value=""><xsl:value-of select="DEFAULT" /></option></xsl:if>
		<xsl:if test="$SELECTEDDAY = ''"><option value="" selected="selected"><xsl:value-of select="DEFAULT" /></option></xsl:if>
    <xsl:call-template name="OPTIONLOOP">
        <xsl:with-param name="start" select="number(1)"/>
        <xsl:with-param name="repeat" select="number(31)"/>
        <xsl:with-param name="SELECTED" select="number($SELECTEDDAY)"/>
      </xsl:call-template>
	</select>
	
  </span>
  
  <xsl:if test="//JSVALIDATE = 'TRUE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}|month|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|month|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|month|comp" class="errorhidden"></span>
      
      <span id="vtip{NAME}|day|comp" class="reqs"><xsl:value-of select="VTIP" /></span>
      <span id="vreq{NAME}|day|comp" class="reqs"><xsl:value-of select="REQUIRED" /></span>
      <span id="error{NAME}|day|comp" class="errorhidden"></span>
  
    </xsl:if>
	</xsl:if>
	
</xsl:template>

<xsl:template name="HOUR_OPTIONLOOP">
  <xsl:param name="start">0</xsl:param>
  <xsl:param name="repeat">0</xsl:param>
  <xsl:param name="step">1</xsl:param>
  <xsl:param name="SELECTED">1</xsl:param>
  <xsl:variable name="display_start">
  <xsl:choose>
    <xsl:when test="(12 > $start)"><xsl:value-of select="$start" /> am</xsl:when>
    <xsl:when test="($start = 12)">12 pm</xsl:when>
    <xsl:when test="($start = 24)">12 am</xsl:when>
    <xsl:otherwise><xsl:value-of select="$start - 12" /> pm</xsl:otherwise>
  </xsl:choose>
  </xsl:variable>
  <xsl:if test="number($repeat) >= number($start)">
    <xsl:choose>
      <xsl:when test="number($SELECTED) = number($start)">
        <option value="{$start}" selected="selected"><xsl:value-of select="$display_start" /></option>
      </xsl:when>
      <xsl:otherwise>
        <option value="{$start}"><xsl:value-of select="$display_start" /></option>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:call-template name="HOUR_OPTIONLOOP">
      <xsl:with-param name="start" select="$start + $step"/>
      <xsl:with-param name="repeat" select="$repeat"/>
      <xsl:with-param name="step" select="$step"/>
      <xsl:with-param name="SELECTED" select="$SELECTED"/>
    </xsl:call-template>
  </xsl:if>
</xsl:template>

<xsl:template name="OPTIONLOOP">
  <xsl:param name="start">0</xsl:param>
  <xsl:param name="repeat">0</xsl:param>
  <xsl:param name="step">1</xsl:param>
  <xsl:param name="SELECTED">1</xsl:param>
  <xsl:if test="number($repeat) >= number($start)">
    <xsl:choose>
      <xsl:when test="number($SELECTED) = number($start)">
        <option value="{$start}" selected="selected"><xsl:value-of select="$start" /></option>
      </xsl:when>
      <xsl:otherwise>
        <option value="{$start}"><xsl:value-of select="$start" /></option>
      </xsl:otherwise>
    </xsl:choose>
    <xsl:call-template name="OPTIONLOOP">
      <xsl:with-param name="start" select="$start + $step"/>
      <xsl:with-param name="repeat" select="$repeat"/>
      <xsl:with-param name="step" select="$step"/>
      <xsl:with-param name="SELECTED" select="$SELECTED"/>
    </xsl:call-template>
  </xsl:if>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='country']">
	<xsl:variable name="THECOUNTRIES" select="document($countrylocation)/THECOUNTRIES"/>

	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<select id="{NAME}" class="{$THECLASS}" name="{NAME}"  title="{TITLE}">
		<option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		<xsl:variable name="SELECTEDVALUE" select="DEFAULT/@selected" />
		<xsl:for-each select="$THECOUNTRIES/ACOUNTRY">
        	<xsl:choose>
				<xsl:when test="$SELECTEDVALUE = @abbr">
        			<option value="{@abbr}" selected="selected"><xsl:value-of select="@abbr" /></option>
        		</xsl:when>
        		<xsl:otherwise>
        			<option value="{@abbr}"><xsl:value-of select="@abbr" /></option>
        		</xsl:otherwise>
        	</xsl:choose>
        </xsl:for-each>
	</select>
	<xsl:call-template name="REQS" />
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='state']">
	<xsl:variable name="THESTATES" select="document($statelocation)/THESTATES"/>

	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<xsl:element name="select">
	 <xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute>
	 <xsl:attribute name="class"><xsl:value-of select="$THECLASS" /></xsl:attribute>
	 <xsl:attribute name="title"><xsl:value-of select="TITLE" /></xsl:attribute>
	 <xsl:attribute name="name"><xsl:value-of select="NAME" /></xsl:attribute>
    <xsl:if test="DEFAULT/@onblur != ''">
    <xsl:attribute name="onBlur"><xsl:value-of select="DEFAULT/@onblur" /></xsl:attribute>
    </xsl:if>
   <xsl:if test="DEFAULT/@onselect != ''">
    <xsl:attribute name="onSelect"><xsl:value-of select="DEFAULT/@onselect" /></xsl:attribute>
    </xsl:if>
    <xsl:if test="DEFAULT/@onchange != ''">
    <xsl:attribute name="onchange"><xsl:value-of select="DEFAULT/@onchange" /></xsl:attribute>
    </xsl:if>
   <xsl:if test="TYPE/@multiple>0">
     <xsl:attribute name="multiple">multiple</xsl:attribute>
     <xsl:attribute name="size"><xsl:value-of select="TYPE/@multiple" /></xsl:attribute>
   </xsl:if>
   <xsl:choose>
      <xsl:when test="((count(DEFAULTVALUES/VALUE) = 0) and (TYPE/@multiple>0))">
   	    <option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:when>
		  <xsl:when test="DEFAULT/@selected = ''">
   	    <option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:when>
		  <xsl:otherwise>
		    <option value="0"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:otherwise>
		</xsl:choose>
    <xsl:variable name="SELECTEDVALUE" select="DEFAULT/@selected" />
		
    <xsl:for-each select="$THESTATES/ASTATE">
      <xsl:sort select="@abbr" order="ascending" data-type="text"/>
    
      <xsl:choose>
				<xsl:when test="(count(../../DEFAULTVALUES[VALUE=@abbr]) = 1) or ($SELECTEDVALUE = @abbr)">
        	<option value="{@abbr}" selected="selected"><xsl:value-of select="@abbr" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{@abbr}"><xsl:value-of select="@abbr" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</xsl:element>
	
	<!--<select id="{NAME}" class="{$THECLASS}" name="{NAME}"  title="{TITLE}">
		<option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		<xsl:variable name="SELECTEDVALUE" select="DEFAULT/@selected" />
		<xsl:for-each select="$THESTATES/ASTATE">
      <xsl:sort select="@abbr" order="ascending" data-type="text"/>
    
      <xsl:choose>
				<xsl:when test="$SELECTEDVALUE = @abbr">
    			<option value="{@abbr}" selected="selected"><xsl:value-of select="@abbr" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{@abbr}"><xsl:value-of select="@abbr" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</select>-->
	<xsl:call-template name="REQS" />
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='select']">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<xsl:element name="select">
	 <xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute>
	 <xsl:attribute name="class"><xsl:value-of select="$THECLASS" /></xsl:attribute>
	 <xsl:attribute name="title"><xsl:value-of select="TITLE" /></xsl:attribute>
	 <xsl:attribute name="name"><xsl:value-of select="NAME" /></xsl:attribute>
    <xsl:if test="DEFAULT/@onblur != ''">
    <xsl:attribute name="onBlur"><xsl:value-of select="DEFAULT/@onblur" /></xsl:attribute>
    </xsl:if>
   <xsl:if test="DEFAULT/@onselect != ''">
    <xsl:attribute name="onSelect"><xsl:value-of select="DEFAULT/@onselect" /></xsl:attribute>
    </xsl:if>
    <xsl:if test="DEFAULT/@onchange != ''">
    <xsl:attribute name="onchange"><xsl:value-of select="DEFAULT/@onchange" /></xsl:attribute>
    </xsl:if>
   <xsl:if test="TYPE/@multiple>0">
     <xsl:attribute name="multiple">multiple</xsl:attribute>
     <xsl:attribute name="size"><xsl:value-of select="TYPE/@multiple" /></xsl:attribute>
   </xsl:if>
   <xsl:choose>
      <xsl:when test="((count(DEFAULTVALUES/VALUE) = 0) and (TYPE/@multiple>0))">
   	    <option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:when>
		  <xsl:when test="DEFAULT/@selected = ''">
   	    <option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:when>
		  <xsl:otherwise>
		    <option value="0"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:otherwise>
		</xsl:choose>
    <xsl:for-each select="VALUE">
      <xsl:variable name="THEVALUE">
    		<xsl:choose>
    			<xsl:when test="@value != ''"><xsl:value-of select="@value" /></xsl:when>
    			<xsl:otherwise><xsl:value-of select="self::*" /></xsl:otherwise>
    		</xsl:choose>
    	</xsl:variable>
      <xsl:variable name="ONCLICK"><xsl:value-of select="@onclick" /></xsl:variable>
	    <xsl:choose>
				<xsl:when test="@separator != ''">
				  <option value="" disabled="true"><xsl:value-of select="@separator" /></option>
				</xsl:when>
        <xsl:when test="(count(../../DEFAULTVALUES[VALUE=self::*]) = 1) or (../DEFAULT/@selected = $THEVALUE)">
        	<option value="{$THEVALUE}" selected="selected" onclick="{$ONCLICK}"><xsl:value-of select="self::*" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{$THEVALUE}" onclick="{$ONCLICK}"><xsl:value-of select="self::*" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</xsl:element>
	
	
	<!--<select id="{NAME}" class="{$THECLASS}" name="{NAME}" title="{TITLE}">
		<option value="{DEFAULT}" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		<xsl:variable name="SELECTEDVALUE" select="DEFAULT/@selected" />
		<xsl:for-each select="VALUE">
	    <xsl:choose>
				<xsl:when test="$SELECTEDVALUE = self::*">
        	<option value="{self::*}" selected="selected"><xsl:value-of select="self::*" /><xsl:value-of select="$SELECTEDVALUE" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{self::*}"><xsl:value-of select="self::*" /><xsl:value-of select="$SELECTEDVALUE" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</select>-->
	<xsl:call-template name="REQS" />
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='selectdb']">
  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<xsl:element name="select">
	 <xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute>
	 <xsl:attribute name="class"><xsl:value-of select="$THECLASS" /></xsl:attribute>
	 <xsl:attribute name="title"><xsl:value-of select="TITLE" /></xsl:attribute>
	 <xsl:attribute name="name"><xsl:value-of select="NAME" /></xsl:attribute>
	  <xsl:if test="DEFAULT/@onblur != ''">
    <xsl:attribute name="onBlur"><xsl:value-of select="DEFAULT/@onblur" /></xsl:attribute>
    </xsl:if>
   <xsl:if test="DEFAULT/@onselect != ''">
    <xsl:attribute name="onSelect"><xsl:value-of select="DEFAULT/@onselect" /></xsl:attribute>
    </xsl:if>
    <xsl:if test="DEFAULT/@onchange != ''">
    <xsl:attribute name="onchange"><xsl:value-of select="DEFAULT/@onchange" /></xsl:attribute>
    </xsl:if>
   <xsl:if test="TYPE/@onchange !=''">
    <xsl:attribute name="onchange"><xsl:value-of select="TYPE/@onchange" /></xsl:attribute>
	 </xsl:if>
   <xsl:if test="TYPE/@multiple>0">
     <xsl:attribute name="multiple">multiple</xsl:attribute>
     <xsl:attribute name="size"><xsl:value-of select="TYPE/@multiple" /></xsl:attribute>
   </xsl:if>
   <xsl:choose>
      <xsl:when test="((count(DEFAULTVALUES/VALUE) = 0) and (TYPE/@multiple>0))">
   	    <option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:when>
		  <xsl:when test="DEFAULT/@selected = ''">
   	    <option value="0" selected="selected"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:when>
		  <xsl:otherwise>
		    <option value="0"><xsl:value-of select="DEFAULT" /></option>
		  </xsl:otherwise>
		</xsl:choose>
    <xsl:for-each select="VALUES/VALUE">
		  <xsl:variable name="DATA"><xsl:value-of select="@data" /></xsl:variable>
		  <xsl:variable name="ID"><xsl:value-of select="@id" /></xsl:variable>
		  <xsl:variable name="ONCLICK"><xsl:value-of select="@onclick" /></xsl:variable>
	    <xsl:choose>
				<xsl:when test="(count(../../DEFAULTVALUES[VALUE=$DATA]) = 1) or ((../../DEFAULT/@selected = $DATA) or (../../DEFAULT/@selected = $ID))">
        	<option value="{@id}" selected="selected" onclick="{$ONCLICK}"><xsl:value-of select="@data"  disable-output-escaping="yes" /></option>
    		</xsl:when>
    		<xsl:otherwise>
    			<option value="{@id}" onclick="{$ONCLICK}"><xsl:value-of select="@data" disable-output-escaping="yes" /></option>
    		</xsl:otherwise>
    	</xsl:choose>
    </xsl:for-each>
	</xsl:element>
	<xsl:call-template name="REQS" />
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='radiodb']">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:apply-templates select="DISPLAYGROUP" />
	</span>
	<xsl:for-each select="VALUES/VALUE">
		<div class="{../CLASS/@wrapper}">
			<xsl:apply-templates select="self::*" mode="checkradio">
			 <xsl:with-param name="THETYPE" select="'radio'"/>
      </xsl:apply-templates>
		</div>
	</xsl:for-each>
	<xsl:call-template name="REQS" />
</xsl:template>

<xsl:template match="FORMELEMENT" mode="LIST">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<ul>
		<xsl:for-each select="VALUE">
			<li><xsl:value-of select="self::*" /></li>
        </xsl:for-each>
	</ul>
	<span id="error{NAME}" class="errorhidden"></span>
	</span>
</xsl:template>

<xsl:template match="DISPLAYGROUP">
	<xsl:for-each select="DISPLAY">
		<span class="{@class}">
			<xsl:apply-templates select="self::*" />
		</span>
	</xsl:for-each>
</xsl:template>

<xsl:template match="FORMELEMENT[(TYPE='checkbox') or (TYPE='radio')]">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	<xsl:apply-templates select="DISPLAYGROUP" />
	</span>
	<xsl:variable name="DEFAULT">
		
	</xsl:variable>
	<xsl:for-each select="VALUEGROUP">
		<div class="{../CLASS/@wrapper}">
			<xsl:apply-templates select="VALUE" mode="checkradio"></xsl:apply-templates>
		</div>
	</xsl:for-each>
	<xsl:call-template name="REQS" />
</xsl:template>

<xsl:template match="VALUE" mode="checkradio">
  <xsl:variable name="THENAME">
		<xsl:value-of select="../../NAME" />
	</xsl:variable>
	<xsl:variable name="VALUECOUNT">
		<xsl:value-of select="count(../../../preceding-sibling::FORMELEMENTGROUP/FORMELEMENT[NAME=$THENAME]//VALUE)" />
	</xsl:variable>
	<xsl:variable name="SELECTEDVALUECOUNT">
		<xsl:value-of select="count(../VALUE[@selected='TRUE'])" />
	</xsl:variable>
  <xsl:variable name="SELECTED">
	   <xsl:choose>
			<xsl:when test="($SELECTEDVALUECOUNT = 0) and (((@value != '') and (../../DEFAULT = @value)) or ((@value = '') and (../../DEFAULT = self::*))) ">TRUE</xsl:when>
			<xsl:when test="($SELECTEDVALUECOUNT &gt; 0) and (@selected = 'TRUE')">TRUE</xsl:when>
			<xsl:otherwise>FALSE</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
  <xsl:variable name="THEVALUE">
  	 <xsl:choose>
			<xsl:when test="@value != ''"><xsl:value-of select="@value" /></xsl:when>
			<xsl:otherwise><xsl:value-of select="self::*" /></xsl:otherwise>
		</xsl:choose>
  </xsl:variable>
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCHECKCLASS"/>
	</xsl:variable>
	<xsl:variable name="thisname"><xsl:value-of select="$THENAME" />_<xsl:value-of select="$VALUECOUNT + count(preceding-sibling::VALUE)" /></xsl:variable>
	
	<span class="{$THECLASS}">
		<xsl:if test="$THEVALUE != 'null'">
			<xsl:element name="input">
				<xsl:attribute name="type"><xsl:value-of select="../../TYPE" /></xsl:attribute>
				<xsl:attribute name="id"><xsl:value-of select="$thisname" /></xsl:attribute>
				<xsl:attribute name="name"><xsl:value-of select="../../NAME" /></xsl:attribute>
				<xsl:if test="@onclick != ''"><xsl:attribute name="onclick"><xsl:value-of select="@onclick" /></xsl:attribute></xsl:if>
				<xsl:attribute name="class"><xsl:value-of select="../@class" /></xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="$THEVALUE" /></xsl:attribute>
				<xsl:attribute name="title"><xsl:value-of select="../../TITLE" /></xsl:attribute>
				<xsl:if test="$SELECTED = 'TRUE'"><xsl:attribute name="checked">true</xsl:attribute><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
			</xsl:element>
		</xsl:if>
		<xsl:choose>
    <xsl:when test="../../DEFAULT/@omit_label = 'true'">
      <xsl:value-of select="self::*"  disable-output-escaping="yes" />
    </xsl:when>
    <xsl:otherwise>
    <label for="{$thisname}"><xsl:value-of select="self::*"  disable-output-escaping="yes" /></label>
		</xsl:otherwise>
		</xsl:choose>
	</span>
	<xsl:if test="position() mod ../SIZE = 0 or @forceret = 'TRUE'"><br /></xsl:if>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='submitimage']">
		<span class="{DISPLAYCLASS}">
			<button class="custombutton {CLASS}" type="submit" name="SUBMIT_{NAME}" onclick="setAction(this,'{NAME}');{DEFAULT/@onclick}" value="{NAME}" ><img src="{DEFAULT}" alt="{TITLE}" class="{DISPLAYCLASS}"/></button>
   	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='resetimage']">
		<span class="{DISPLAYCLASS}">
			<button class="custombutton {CLASS}" type="reset" name="SUBMIT_{NAME}" onclick="document.forms[0].reset();return false;" value="{NAME}" ><img src="{DEFAULT}" alt="reset" class="{DISPLAYCLASS}"/></button>
   	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='submit']">
		<span class="{DISPLAYCLASS}">
			<input type="submit" name="SUBMIT_{NAME}" class="{CLASS}" value="{DEFAULT}" />
		</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='image']">
    <xsl:choose>
    <xsl:when test="DEFAULT/@uri != ''">
      <span class="{DISPLAYCLASS}">
			 <a href="{DEFAULT/@uri}"><img src="{DEFAULT}" onclick="{DEFAULT/@onclick}" onmouseover="{DEFAULT/@onmouseover}" name="{NAME}" id="{NAME}" class="{CLASS}" border="0" /></a>
		  </span>
    </xsl:when>
    <xsl:otherwise>
      <span class="{DISPLAYCLASS}">
			<img src="{DEFAULT}" name="{NAME}" id="{NAME}" class="{CLASS}" onclick="{DEFAULT/@onclick}" onmouseover="{DEFAULT/@onmouseover}" border="0" />
		</span>
    </xsl:otherwise>
    </xsl:choose>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='errornotice']">
  <xsl:choose>
    <xsl:when test="DEFAULT/@visible = 'true'">
      <span id="errornotice_{../../FORMNAME}" class="errornotice_visible"><xsl:value-of select="DEFAULT" /></span>
    </xsl:when>
    <xsl:otherwise>
		  <span id="errornotice_{../../FORMNAME}" class="errornotice"><xsl:value-of select="DEFAULT" /></span>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

<xsl:template name="SETCLASS">
    <xsl:value-of select="CLASS" /><xsl:if test="CLASS/@error = 'TRUE'">_error</xsl:if>
</xsl:template>

<xsl:template name="SETDISPLAYCLASS">
		<xsl:value-of select="DISPLAYCLASS" /><xsl:if test="DISPLAYCLASS/@error = 'TRUE'">_error</xsl:if>
</xsl:template>

<xsl:template name="SETCHECKCLASS">
	<xsl:choose>
		<xsl:when test="@class != ''">
			<xsl:value-of select="@class" /><xsl:if test="../../CLASS/@error = 'TRUE'">_error</xsl:if>
		</xsl:when>
    	<xsl:otherwise>
    		<xsl:value-of select="../../CLASS" /><xsl:if test="../../CLASS/@error = 'TRUE'">_error</xsl:if>
    	</xsl:otherwise>
    </xsl:choose>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='hidden']">
  <xsl:variable name="thevalue" disable-output-escaping="yes"><xsl:value-of select="DEFAULT" /></xsl:variable>
		<span class="{DISPLAYCLASS}">
			<input type="hidden" value="{$thevalue}" name="{NAME}" class="{CLASS}" border="0" />
		</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='container']">
  <xsl:variable name="thevalue" disable-output-escaping="yes"><xsl:value-of select="DEFAULT" /></xsl:variable>
	<div id="styro_container">
    	<textarea id="container_{NAME}" name="container_{NAME}" class="reqs" style="display: none;" border="0"><xsl:value-of select="$thevalue" /></textarea>
	</div>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='div']">
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETDISPLAYCLASS"/>
	</xsl:variable>
	<xsl:element name="div">
		<xsl:if test="NAME != ''"><xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute></xsl:if>
		<xsl:if test="$THECLASS != ''"><xsl:attribute name="class"><xsl:value-of select="$THECLASS" /></xsl:attribute></xsl:if>
		<xsl:value-of select="DEFAULT" />
	</xsl:element>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='span']">
		<span id="{NAME}" class="{DISPLAYCLASS}"></span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='hr']">
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETDISPLAYCLASS"/>
	</xsl:variable>
	<xsl:element name="hr">
		<xsl:if test="NAME != ''"><xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute></xsl:if>
		<xsl:if test="$THECLASS != ''"><xsl:attribute name="class"><xsl:value-of select="$THECLASS" /></xsl:attribute></xsl:if>
	</xsl:element>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='html']">
  <xsl:value-of select="DISPLAY" disable-output-escaping="yes"/>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='error']">
  <div class="pageerror">
    <span class="alert"><xsl:value-of select="DEFAULT" /></span>
  </div>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='anchor']">
  <span>
			<a id="{NAME}"></a>
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='iframe']">
	<span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	<xsl:element name="iframe">
		<xsl:attribute name="src"><xsl:value-of select="DEFAULT" /></xsl:attribute>
		<xsl:attribute name="name"><xsl:value-of select="NAME" /></xsl:attribute>
	  <xsl:attribute name="class"><xsl:value-of select="$THECLASS" /><xsl:if test="DISPLAYCLASS/@error = 'TRUE'">_error</xsl:if></xsl:attribute>
		<xsl:attribute name="id"><xsl:value-of select="NAME" /></xsl:attribute>
		<xsl:attribute name="scrolling"><xsl:value-of select="DISPLAY/@scrolling" /></xsl:attribute>
		<xsl:attribute name="width"><xsl:value-of select="DISPLAY/@width" /></xsl:attribute>
		<xsl:attribute name="height"><xsl:value-of select="DISPLAY/@height" /></xsl:attribute>
	</xsl:element>
	<!--<input type="text" name="{NAME}" onBlur="{DEFAULT/@js};" class="{$THECLASS}" id="{NAME}" value="{DEFAULT}" title="{TITLE}" maxlength="{SIZE}" />-->
	<xsl:call-template name="REQS" />
	
	</span>
</xsl:template>

</xsl:stylesheet>
