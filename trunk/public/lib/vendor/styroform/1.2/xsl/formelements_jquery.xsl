<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FORMELEMENT[TYPE='jquery_tooltip']">
	   
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
    
	   <!-- tooltip element -->
    <div class="tooltip">
    	<xsl:value-of select="DISPLAY" disable-output-escaping="yes" />
    </div>
    
		<script type="text/javascript">
		$(document).ready(function() {
    	$( "#<xsl:value-of select="NAME" />" ).tooltip({ effect: 'slide', relative: true, position: 'center, right' });
    });
		</script>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_anytime']">
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
		</xsl:element>
		<img id="{NAME}_clear" src="/images/Neu/16x16/actions/undo.png" alt="clear" />
		<!--<input type="text" name="{NAME}" onBlur="{DEFAULT/@js};" class="{$THECLASS}" id="{NAME}" value="{DEFAULT}" title="{TITLE}" maxlength="{SIZE}" />-->
		<xsl:call-template name="REQS" />
		<script type="text/javascript">
		$(document).ready(function() {
		  $("#<xsl:value-of select="NAME" />_clear").click(function() {
		    $("#<xsl:value-of select="NAME" />").val("");
      });
    	AnyTime.picker( "<xsl:value-of select="NAME" />",
      { format: <xsl:value-of select="SIZE" disable-output-escaping="yes" /> } );
    });
		</script>
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_datepicker']">
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
		<script type="text/javascript">
		$(document).ready(function() {
    	$( "#<xsl:value-of select="NAME" />" ).datepicker();
    });
		</script>
	</span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_imageupload']">
		<xsl:variable name="WIDTH"><xsl:if test="SIZE/@width != ''"><xsl:value-of select="SIZE/@width" /></xsl:if></xsl:variable>
    <xsl:variable name="HEIGHT"><xsl:if test="SIZE/@height != ''"><xsl:value-of select="SIZE/@height" /></xsl:if></xsl:variable>
    <xsl:variable name="PREVIEW"><xsl:if test="SIZE/@preview != ''"><xsl:value-of select="SIZE/@preview" /></xsl:if></xsl:variable>
    <xsl:variable name="DEBUG"><xsl:if test="REQUIRED/@debug!= ''"><xsl:value-of select="REQUIRED/@debug" /></xsl:if><xsl:if test="REQUIRED/@debug=''">false</xsl:if></xsl:variable>
    <span class="{DISPLAYCLASS}">
			<a id="REF_{NAME}" href="#FORM_{NAME}"><button type="button" name="BUTTON_{NAME}" id="" border="0"><xsl:value-of select="DISPLAY" /></button></a>
		</span>
  	<script type="text/javascript">
  	$(document).ready(function() {
      $("#REF_<xsl:value-of select="NAME" />").fancybox({
        'autoScale'		: false,
  			'transitionIn'	: 'none',
  			'transitionOut'	: 'none',
  			'width'		: 140,
  			'height'		: 120,
  			overlayShow: true,
	      hideOnContentClick: false,
	      'scrolling'		: 'no',
      	'titleShow'		: false,
      	'onComplete': function() {
	        // set up the form for ajax submission
	        <xsl:choose>
            <xsl:when test="DEFAULT/@suffix != ''">
              if ($("<xsl:value-of select="DEFAULT/@suffix" />").val() != 0){
                var uploadUrl = '<xsl:value-of select="DEFAULT/@destination" />/'+$("<xsl:value-of select="DEFAULT/@suffix" />").val()+'?<xsl:value-of select="//@session_name" />=<xsl:value-of select="//@session" />'; 
              } else {
                var uploadUrl = '<xsl:value-of select="DEFAULT/@destination" />?<xsl:value-of select="//@session_name" />=<xsl:value-of select="//@session" />';
              }
            </xsl:when>
  	        <xsl:otherwise>
  	         var uploadUrl = '<xsl:value-of select="DEFAULT/@destination" />?<xsl:value-of select="//@session_name" />=<xsl:value-of select="//@session" />';
  	        </xsl:otherwise>
	        </xsl:choose>
	        var thid = $("<xsl:value-of select="NAME" />")
	        $("#FILE_<xsl:value-of select="NAME" />").makeAsyncUploader({
            upload_url: uploadUrl,
            flash_url: '/js/swfupload/swfupload.swf',
            button_image_url: '/js/swfupload/blankButton.png',
            debug    : <xsl:value-of select="$DEBUG" />,
    	      <xsl:if test="SIZE/@limit != ''">
            file_size_limit: '<xsl:value-of select="SIZE/@limit" />',
            </xsl:if>
          });
	      },
      	'onClosed'		: function() {
        <xsl:choose>
          <xsl:when test="REQUIRED/@static != ''">
            var pic = '<xsl:value-of select="DISPLAY/@static" />';
            $("#<xsl:value-of select="NAME" />_preview").attr("src",pic);
            <xsl:if test="SIZE/@width != ''">$("#<xsl:value-of select="NAME" />_preview").attr("width",<xsl:value-of select="$WIDTH" />);</xsl:if>
            <xsl:if test="SIZE/@height != ''">$("#<xsl:value-of select="NAME" />_preview").attr("height",<xsl:value-of select="$HEIGHT" />);</xsl:if>
            $("#<xsl:value-of select="NAME" />_preview_wrapper").show();
          </xsl:when>
          <xsl:when test="DEFAULT/@suffix != ''">
            console.log('<xsl:value-of select="DEFAULT/@suffix" />');
            console.log($("<xsl:value-of select="DEFAULT/@suffix" />").val());
            var thelement = "<xsl:value-of select="DEFAULT/@suffix" />".replace('#','');
          	if (document.forms["<xsl:value-of select="//FORMNAME" />"].elements[thelement].selectedIndex != undefined) {
							var theval = document.forms["<xsl:value-of select="//FORMNAME" />"].elements[thelement].options[document.forms["<xsl:value-of select="//FORMNAME" />"].elements[thelement].selectedIndex].value;
						} else {
							var theval = $("<xsl:value-of select="DEFAULT/@suffix" />").val();
						}
            if (theval != 0){
						//if ($("<xsl:value-of select="DEFAULT/@suffix" />").val() != 0){
              var npath = '<xsl:value-of select="DISPLAY/@path" />';
              npath = npath.replace(/<xsl:value-of select="DEFAULT/@suffix" />.+/,$("input[name=FILE_<xsl:value-of select="NAME" />_guid]").val());
              console.log(npath);
              var pic=npath+'.jpg';
              $("#<xsl:value-of select="NAME" />_preview").attr("src",pic);
              <xsl:if test="SIZE/@width != ''">$("#<xsl:value-of select="NAME" />_preview").attr("width",<xsl:value-of select="$WIDTH" />);</xsl:if>
              <xsl:if test="SIZE/@height != ''">$("#<xsl:value-of select="NAME" />_preview").attr("height",<xsl:value-of select="$HEIGHT" />);</xsl:if>
              $("#<xsl:value-of select="NAME" />_preview_wrapper").show();
            }
          </xsl:when>
          <xsl:otherwise>
            var pic = '<xsl:value-of select="DISPLAY/@path" />'+$("input[name=FILE_<xsl:value-of select="NAME" />_guid]").val();
            if (pic.length > 0) {
              pic=pic+"<xsl:value-of select="$PREVIEW" />.jpg";
              console.log("Picture Location is" + pic);
              $("#<xsl:value-of select="NAME" />_preview").attr("src",pic);
              <xsl:if test="SIZE/@width != ''">$("#<xsl:value-of select="NAME" />_preview").attr("width",<xsl:value-of select="$WIDTH" />);</xsl:if>
              <xsl:if test="SIZE/@height != ''">$("#<xsl:value-of select="NAME" />_preview").attr("height",<xsl:value-of select="$HEIGHT" />);</xsl:if>
              $("#<xsl:value-of select="NAME" />_preview_wrapper").show();
            }
          </xsl:otherwise>
        </xsl:choose>
      	}
      });
      <xsl:if test="DEFAULT != ''">
        <xsl:choose>
          <xsl:when test="REQUIRED/@static != ''">
      	   var pic = '<xsl:value-of select="DISPLAY/@static" />';
      	  </xsl:when>
          <xsl:when test="DEFAULT/@suffix != ''">
          	var thelement = "<xsl:value-of select="DEFAULT/@suffix" />".replace('#','');
          	if (document.forms["<xsl:value-of select="//FORMNAME" />"].elements[thelement].selectedIndex != undefined) {
							var theval = document.forms["<xsl:value-of select="//FORMNAME" />"].elements[thelement].options[document.forms["<xsl:value-of select="//FORMNAME" />"].elements[thelement].selectedIndex].value;
						} else {
							var theval = $("<xsl:value-of select="DEFAULT/@suffix" />").val();
						}
						if ('<xsl:value-of select="DEFAULT" />' != '') {
						if (theval != 0){
						  var npath = '<xsl:value-of select="DISPLAY/@path" />';
              npath = npath.replace(/<xsl:value-of select="DEFAULT/@suffix" />/,theval);
              var pic=npath+'<xsl:value-of select="DEFAULT" />';
            }}
          </xsl:when>
	        <xsl:otherwise>
	         var pic='<xsl:value-of select="DISPLAY/@path" />/<xsl:value-of select="DEFAULT" />';
	        </xsl:otherwise>
        </xsl:choose>
        $("#<xsl:value-of select="NAME" />_preview").attr("src",pic);
        $("#<xsl:value-of select="NAME" />_preview_wrapper").show();
      </xsl:if>
    });
    </script>
    <div style="display:none">
    	<div id="FORM_{NAME}" class="swfuploader">
    	   <input type="file" id="FILE_{NAME}" name="FILE_{NAME}" />
    	</div>
    </div>
    <span id="{NAME}_preview_wrapper" style="display:none; text-align: center; width: 100%; float: left; padding-top: 10px; padding-bottom: 10px;">
      <img width="{$WIDTH}" height="{$HEIGHT}" border="0" onmouseover="" onclick="" class="" id="{NAME}_preview" name="{NAME}_preview" src="" />
    </span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_youtubeupload']">
	<div id="FORM_{NAME}" class="swfuploader">
	   <input type="file" id="FILE_{NAME}" name="FILE_{NAME}" />
	</div>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_image']">
    <xsl:variable name="PREPATH"><xsl:if test="DISPLAY/@path != ''"><xsl:value-of select="DISPLAY/@path" /></xsl:if></xsl:variable>
    <xsl:variable name="WIDTH"><xsl:if test="SIZE/@width != ''"><xsl:value-of select="SIZE/@width" /></xsl:if></xsl:variable>
    <xsl:variable name="HEIGHT"><xsl:if test="SIZE/@height != ''"><xsl:value-of select="SIZE/@height" /></xsl:if></xsl:variable>
    
    <xsl:choose>
    <xsl:when test="DEFAULT/@uri != ''">
      <span class="{DISPLAYCLASS}">
			 <a href="{DEFAULT/@uri}"><img src="/images/spacer.gif" onclick="{DEFAULT/@onclick}" onmouseover="{DEFAULT/@onmouseover}" name="{NAME}" id="{NAME}" class="{CLASS}" border="0" width="{$WIDTH}" height="{$HEIGHT}" /></a>
		  </span>
    </xsl:when>
    <xsl:otherwise>
      <span class="{DISPLAYCLASS}">
			<img src="/images/spacer.gif" name="{NAME}" id="{NAME}" class="{CLASS}" onclick="{DEFAULT/@onclick}" onmouseover="{DEFAULT/@onmouseover}" border="0" width="{$WIDTH}" height="{$HEIGHT}" />
		</span>
    </xsl:otherwise>
    </xsl:choose>
    
    <script type="text/javascript">
  	$(document).ready(function() {
    <xsl:choose>
      <xsl:when test="REQUIRED/@static != ''">
       var pic = '<xsl:value-of select="DISPLAY/@static" />';
      </xsl:when>
      <xsl:when test="DEFAULT/@suffix != ''">
        if ($("<xsl:value-of select="DEFAULT/@suffix" />").val() != 0){
          var npath = '<xsl:value-of select="DISPLAY/@path" />';
          npath = npath.replace(/<xsl:value-of select="DEFAULT/@suffix" />/,$("<xsl:value-of select="DEFAULT/@suffix" />").val());
          var pic=npath+'<xsl:value-of select="DEFAULT" />';
        }
      </xsl:when>
      <xsl:otherwise>
       var pic='<xsl:value-of select="DISPLAY/@path" />/<xsl:value-of select="DEFAULT" />';
      </xsl:otherwise>
    </xsl:choose>
    $("#<xsl:value-of select="NAME" />").attr("src",pic);
    });
    </script>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_autocomplete']">
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
	
  <script type="text/javascript">
	$(document).ready(function() {
    $( "#<xsl:value-of select="NAME" />" ).autocomplete({
      source: '<xsl:value-of select="DEFAULT/@string" />',
      select: function( event, ui ) {
				$("#<xsl:value-of select="DEFAULT/@suffix" />").val(ui.item.id);
				console.log("set value of "+ui.item.id+" to fk_host_id");
			}
		});
  });
  </script>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_combobox']">
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
	
  <script type="text/javascript">
	$(document).ready(function() {
    $( "#<xsl:value-of select="NAME" />" ).combobox();
  });
  </script>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_inputlimiter']">
  <span class="{DISPLAYCLASS}">
	<xsl:apply-templates select="REQUIRED" />
	<xsl:variable name="THECLASS"><xsl:value-of select="CLASS" /><xsl:if test="DISPLAYCLASS/@error = 'TRUE'">_error</xsl:if></xsl:variable>
	<xsl:apply-templates select="DISPLAY" />
	  <textarea name="{NAME}" class="{$THECLASS}" id="{NAME}" title="{TITLE}" rows="{SIZE/@rows}" cols="{SIZE/@cols}" ><xsl:value-of select="DEFAULT"  disable-output-escaping="yes"/></textarea>
		<span id="size{NAME}" class="reqs"><xsl:value-of select="SIZE" /></span>
    <xsl:call-template name="REQS" />
	</span>
	<script type="text/javascript">
	$(document).ready(function() {
    $( "#<xsl:value-of select="NAME" />" ).inputlimiter({limit: <xsl:value-of select="SIZE" />});
  });
  </script>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='jquery_colorpicker']">
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
		<span class="{NAME}_sample_color" style="color: #{DEFAULT}">Sample Text</span>
		<!--<input type="text" name="{NAME}" onBlur="{DEFAULT/@js};" class="{$THECLASS}" id="{NAME}" value="{DEFAULT}" title="{TITLE}" maxlength="{SIZE}" />-->
		<xsl:call-template name="REQS" />
	</span>
	<script type="text/javascript">
	$(document).ready(function() {
    $( "#<xsl:value-of select="NAME" />" ).ColorPicker({
      color: '#<xsl:value-of select="DEFAULT" />',
      onSubmit: function(hsb, hex, rgb, el) {
    		$(el).val(hex);
    		$(el).ColorPickerHide();
    	},
    	onChange: function (hsb, hex, rgb) {
    		$( ".<xsl:value-of select="NAME" />_sample_color" ).css('color', '#' + hex);
    	},
    	onBeforeShow: function () {
    		$(this).ColorPickerSetColor(this.value);
    	}})});
  </script>
</xsl:template>

</xsl:stylesheet>
