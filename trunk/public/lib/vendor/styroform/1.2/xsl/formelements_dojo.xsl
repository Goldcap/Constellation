<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='comboBox']">

  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	
  <xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
	<div dojoType="dojo.data.ItemFileReadStore" jsId="{NAME}_DataStore" url="{DEFAULT/@dataUrl}"></div>
              
  <input dojoType="dijit.form.ComboBox" store="{NAME}_DataStore" value="{DEFAULT}" searchAttr="{DEFAULT/@searchAttr}" name="{NAME}" onChange="{DEFAULT/@onchange}" />
  
	<xsl:call-template name="REQS" />
	
	<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}_selected" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreq{NAME}_selected" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="error{NAME}_selected" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
	
	</span>
	
</xsl:template>


<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='Generic']">
  <div dojoType="dojo_widgets.{NAME}"></div>
</xsl:template>


<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='filteringSelect']">

  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	
  <xsl:variable name="LABELATTR">
    <xsl:choose>
		<xsl:when test="DEFAULT/@labelAttr != ''"><xsl:value-of select="DEFAULT/@labelAttr"/></xsl:when>
		<xsl:when test="DEFAULT/@searchAttr != ''"><xsl:value-of select="DEFAULT/@searchAttr"/></xsl:when>
	  </xsl:choose>
  </xsl:variable>
	
	<div dojoType="dojo.data.ItemFileReadStore" jsId="{NAME}_DataStore" url="{DEFAULT/@dataUrl}"></div>
              
  <input dojoType="dijit.form.FilteringSelect" id="{NAME}" store="{NAME}_DataStore" value="{DEFAULT}" labelAttr="{$LABELATTR}" searchAttr="{DEFAULT/@searchAttr}" name="{NAME}" />
  
	<xsl:call-template name="REQS" />
	
	<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}_selected" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreq{NAME}_selected" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="error{NAME}_selected" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
	
	</span>
	
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='select']">

  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	
  <xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
  <xsl:variable name="DataURL"><xsl:value-of select="DEFAULT/@dataUrl" /></xsl:variable>
	<SELECT id="{NAME}" dojoType="select" style="{CLASS}" name="{NAME}" dataUrl="{$DataURL}" autocomplete="true" forceValidOption="true" maxListLength="{SIZE}" onValueChanged="{DEFAULT/@onchange}" />
	
	<xsl:call-template name="REQS" />
	
	<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}_selected" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreq{NAME}_selected" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="error{NAME}_selected" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
	
	</span>
	
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='filterselect']">

  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:apply-templates select="DISPLAY" />
	
  <xsl:variable name="THECLASS">
		<xsl:call-template name="SETCLASS"/>
	</xsl:variable>
	
  <div jsId="{NAME}_store" dojoType="dojo.data.ItemFileReadStore" url="{DEFAULT/@dataUrl}"></div>
	
   <select dojoType="dijit.form.FilteringSelect"
        id="{NAME}"
        name="{NAME}"
        autoComplete="false"
        invalidMessage="Invalid input"
        store="{NAME}_store"
    		searchAttr="sa"
    		value="defaultvalue"
        class="{CLASS}"
        onValueChanged="{DEFAULT/@onchange}">
  	</select>
	
  <xsl:call-template name="REQS" />
	
	<xsl:if test="//JSVALIDATE != 'FALSE'">
		<xsl:if test="VTIP != 'omit'">
			<span id="vtip{NAME}_selected" class="reqs"><xsl:value-of select="VTIP" /></span>
			<span id="vreq{NAME}_selected" class="reqs"><xsl:value-of select="REQUIRED" /></span>
			<span id="error{NAME}_selected" class="errorhidden"></span>
		</xsl:if>
	</xsl:if>
	
	</span>
	
</xsl:template>
  
<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='contentPane']">
  <div dojoType="dijit.layout.ContentPane" id="{NAME}_{TYPE/@species}" resizable="{DISPLAY/@resizable}" toggle="{DEFAULT/@toggle}" toggleDuration="{DEFAULT/@toggleDuration}" class="{NAME}_{TYPE/@species}" refreshOnShow="true" style="display: {DISPLAY};" href="{DEFAULT}" preload="false" ></div>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='toolTip']">
  <span dojoType="dijit.Tooltip" connectId="{NAME}" toggle="explode" toggleDuration="100" class="{CLASS}" style="display: none; {CLASS/@style}">
    <xsl:value-of select="DISPLAY" disable-output-escaping="yes" />
  </span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='Editor']">
  <xsl:variable name="style"><xsl:choose><xsl:when test="CLASS/@style != ''"><xsl:value-of select="CLASS/@style" /></xsl:when><xsl:otherwise>border: 1px solid #dfdfdf; height: 17em;</xsl:otherwise></xsl:choose></xsl:variable>
	<xsl:variable name="height"><xsl:choose><xsl:when test="CLASS/@height != ''"><xsl:value-of select="CLASS/@height" /></xsl:when><xsl:otherwise>15em</xsl:otherwise></xsl:choose></xsl:variable>
	<xsl:variable name="plugins"><xsl:choose><xsl:when test="SIZE != ''"><xsl:value-of select="SIZE" /></xsl:when><xsl:otherwise>['|','createLink','|','foreColor','hiliteColor','|','fontName']</xsl:otherwise></xsl:choose></xsl:variable>
	  	<span class="{DISPLAYCLASS}">
    	<xsl:apply-templates select="REQUIRED" />
	    
	    <div id="editorwrapper{NAME}" class="{CLASS}">
				<textarea dojoType="dijit.Editor" name="editordojo{NAME}" id="editordojo{NAME}" height="{$height}" style="{$style}" extraPlugins="{$plugins}">
					<xsl:value-of select="DEFAULT" />
				</textarea>
			</div>
			
      <textarea name="editor{NAME}" id="editor{NAME}" class="reqs"></textarea>
  		<input type="hidden" name="{NAME}" id="{NAME}" class="reqs" value="" />
      <xsl:if test="//JSVALIDATE != 'FALSE'">
    		<xsl:if test="VTIP != 'omit'">
    			<span id="vtipeditor2{NAME}" class="reqs"><xsl:value-of select="VTIP" /></span>
    			<span id="vreqeditor2{NAME}" class="reqs"><xsl:value-of select="REQUIRED" /></span>
    			<span id="erroreditor2{NAME}" class="errorhidden"></span>
    			<span id="vtip{NAME}" class="reqs">notEmpty</span>
    			<span id="vreq{NAME}" class="reqs">FALSE</span>
    			<span id="error{NAME}" class="errorhidden"></span>
    		</xsl:if>
    	 </xsl:if>
	     </span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='DateTextBox']">
  <input id="q2" type="text" name="{NAME}" value="{DISPLAY}"
      dojoType="dijit.form.DateTextBox"
      trim="true"
      promptMessage="mm/dd/yyyy"
      class="{CLASS}" 
      invalidMessage="Invalid date. Use mm/dd/yyyy format." />
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='DynamicDialog']">
  <div dojoType="dojo_widgets.layout.DynamicDialog" id="DynamicDialog_{NAME}" content="'{DEFAULT}'" button="'{DISPLAY}'" theId="'{NAME}'" title="'{VTIP}'" destination="'{SIZE}'"></div>
  
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='dojo'][TYPE/@species='BannerPicker']">
  <div dojoType="dojo_widgets.banners.BannerPicker" id="BannerPicker_{NAME}" contentHref="'{DEFAULT}'" button="'{DISPLAY}'" theId="'{NAME}'" title="'{VTIP}'"></div>
</xsl:template>

</xsl:stylesheet>
