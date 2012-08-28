<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="FORMELEMENT[TYPE='creditcard']">
	
	<div class="row">
    <span class="col1b display white">.</span>
    <span class="col2">
      <img src="/images/cards.gif" />
    </span>
  </div>
  <div class="row">
    <span class="col1b display">
      <input autocomplete="off" type="radio" name="billing_payment_type" value="tattoojohnny" id="checkout.tattoojohnny_set" checked="checked" />
    </span>
    <span class="col2 bold">
      Credit Card Number
    </span>
    <span class="col2b">
      Security Code <a href="#" class="small-link" id="checkout.security_code">what is this?</a>
    </span>
  </div>
  
  <div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2">
      <input name="{NAME}_cc_number" class="input" id="{NAME}_cc_number" value="" title="" maxlength="100" type="text" />
      <span id="vtip{NAME}_cc_number" class="reqs">CreditCard</span>
      <span id="vreq{NAME}_cc_number" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_number" class="reqs">false</span>
      <span id="error{NAME}_cc_number" class="errorhidden"></span>
    </span>
    <span class="col2b bold">
      <input name="{NAME}_cc_cvv2" class="inputshort" id="{NAME}_cc_cvv2" value="" title="" maxlength="100" type="text" />
      <span id="vtip{NAME}_cc_cvv2" class="reqs">notEmpty</span>
      <span id="vreq{NAME}_cc_cvv2" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_cvv2" class="reqs">false</span>
      <span id="error{NAME}_cc_cvv2" class="errorhidden"></span>
    </span>
  </div>
	
	<div class="row">
    <span class="col1b display">
      .
    </span>
    <span class="col2b bold">
      Expiration Date
    </span>
  </div>
  
	<div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2d">
      <select id="{NAME}_cc_exp|month|comp" name="{NAME}_cc_exp|month|comp" class="inputtiny">		   
        <option value="Select">Select
        </option>
        <option value="01">01 - Jan
        </option>
        <option value="02">02 - Feb
        </option>
        <option value="03">03 - Mar
        </option>
        <option value="04">04 - Apr
        </option>
        <option value="05">05 - May
        </option>
        <option value="06">06 - Jun
        </option>
        <option value="07">07 - Jul
        </option>
        <option value="08">08 - Aug
        </option>
        <option value="09">09 - Sep
        </option>
        <option value="10">10 - Oct
        </option>
        <option value="11">11 - Nov
        </option>
        <option value="12">12 - Dec
        </option>			  
      </select>
      <span id="vtip{NAME}_cc_exp|month|comp" class="reqs">0</span>
      <span id="vreq{NAME}_cc_exp|month|comp" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_exp|month|comp" class="reqs">false</span>
      <span id="error{NAME}_cc_exp|month|comp" class="errorhidden"></span>
    </span>
    <span class="col2b bold">
      <select id="{NAME}_cc_exp|year|comp" name="{NAME}_cc_exp|year|comp" class="inputtiny">			
        <option value="Select">Select
        </option>
        <option value="09">2009
        </option>
        <option  value="10">2010
        </option>
        <option  value="11">2011
        </option>
        <option  value="12">2012
        </option>
        <option  value="13">2013
        </option>
        <option  value="14">2014
        </option>
        <option  value="15">2015
        </option>
        <option  value="16">2016
        </option>
        <option  value="17">2017
        </option>
        <option  value="18">2018
        </option>
        <option  value="19">2019
        </option>			  
      </select>
      <span id="vtip{NAME}_cc_exp|year|comp" class="reqs">0</span>
      <span id="vreq{NAME}_cc_exp|year|comp" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_exp|year|comp" class="reqs">false</span>
      <span id="error{NAME}_cc_exp|year|comp" class="errorhidden"></span>
    </span>
  </div>
  
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='creditcard_paypal']">
	
	<div class="row">
    <span class="col1b display white">.</span>
    <span class="col2">
      <img src="/images/cards.gif" />
    </span>
  </div>
  <div class="row">
    <span class="col1b display">
      <input autocomplete="off" type="radio" name="billing_payment_type" value="tattoojohnny" id="checkout.tattoojohnny_set" checked="checked" />
    </span>
    <span class="col2 bold">
      Credit Card Number
    </span>
    <span class="col2b">
      Security Code <a href="#" class="small-link" id="checkout.security_code">what is this?</a>
    </span>
  </div>
  
  <div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2">
      <input name="{NAME}_cc_number" class="input" id="{NAME}_cc_number" value="" title="" maxlength="100" type="text" />
      <span id="vtip{NAME}_cc_number" class="reqs">CreditCard</span>
      <span id="vreq{NAME}_cc_number" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_number" class="reqs">false</span>
      <span id="error{NAME}_cc_number" class="errorhidden"></span>
    </span>
    <span class="col2b bold">
      <input name="{NAME}_cc_cvv2" class="inputshort" id="{NAME}_cc_cvv2" value="" title="" maxlength="100" type="text" />
      <span id="vtip{NAME}_cc_cvv2" class="reqs">notEmpty</span>
      <span id="vreq{NAME}_cc_cvv2" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_cvv2" class="reqs">false</span>
      <span id="error{NAME}_cc_cvv2" class="errorhidden"></span>
    </span>
  </div>
	
	<div class="row">
    <span class="col1b display">
      .
    </span>
    <span class="col2b bold">
      Expiration Date
    </span>
  </div>
  
	<div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2d">
      <select id="{NAME}_cc_exp|month|comp" name="{NAME}_cc_exp|month|comp" class="inputtiny">		   
        <option value="Select">Select
        </option>
        <option value="01">01 - Jan
        </option>
        <option value="02">02 - Feb
        </option>
        <option value="03">03 - Mar
        </option>
        <option value="04">04 - Apr
        </option>
        <option value="05">05 - May
        </option>
        <option value="06">06 - Jun
        </option>
        <option value="07">07 - Jul
        </option>
        <option value="08">08 - Aug
        </option>
        <option value="09">09 - Sep
        </option>
        <option value="10">10 - Oct
        </option>
        <option value="11">11 - Nov
        </option>
        <option value="12">12 - Dec
        </option>			  
      </select>
      <span id="vtip{NAME}_cc_exp|month|comp" class="reqs">0</span>
      <span id="vreq{NAME}_cc_exp|month|comp" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_exp|month|comp" class="reqs">false</span>
      <span id="error{NAME}_cc_exp|month|comp" class="errorhidden"></span>
    </span>
    <span class="col2b bold">
      <select id="{NAME}_cc_exp|year|comp" name="{NAME}_cc_exp|year|comp" class="inputtiny">			
        <option value="Select">Select
        </option>
        <option  value="10">2010
        </option>
        <option  value="11">2011
        </option>
        <option  value="12">2012
        </option>
        <option  value="13">2013
        </option>
        <option  value="14">2014
        </option>
        <option  value="15">2015
        </option>
        <option  value="16">2016
        </option>
        <option  value="17">2017
        </option>
        <option  value="18">2018
        </option>
        <option  value="19">2019
        </option>
        <option  value="19">2020
        </option>	
        <option  value="19">2021
        </option>	  
      </select>
      <span id="vtip{NAME}_cc_exp|year|comp" class="reqs">0</span>
      <span id="vreq{NAME}_cc_exp|year|comp" class="reqs">TRUE</span>
      <span id="vskip{NAME}_cc_exp|year|comp" class="reqs">false</span>
      <span id="error{NAME}_cc_exp|year|comp" class="errorhidden"></span>
    </span>
  </div>
  
  <div class="row">
    <span class="col1b display">
      <input autocomplete="off" type="radio" name="billing_payment_type" value="paypal" id="checkout.paypal_set" />
    </span>
    <span class="col2 bold">
      Paypal <img type="image" src="/images/paypal.gif" border="0" />
    </span>
  </div>
  
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='creditcard_flat']">
	
	<div class="row">
    <span class="col1b display white">.</span>
    <span class="col2">
      <img src="/images/cards.gif" />
    </span>
  </div>
  <div class="row">
    <span class="col1b display">
      <input autocomplete="off" type="radio" name="billing_payment_type" value="tattoojohnny" id="checkout.tattoojohnny_set" checked="checked" />
    </span>
    <span class="col2 bold">
      Credit Card Number
    </span>
    <span class="col2b">
      Security Code <a href="#" class="small-link" id="checkout.security_code">what is this?</a>
    </span>
  </div>
  
  <div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2">
      <input name="{NAME}_cc_number" class="input" id="{NAME}_cc_number" value="" title="" maxlength="100" type="text" />
    </span>
    <span class="col2b bold">
      <input name="{NAME}_cc_cvv2" class="inputshort" id="{NAME}_cc_cvv2" value="" title="" maxlength="100" type="text" />
    </span>
  </div>
	
	<div class="row">
    <span class="col1b display">
      .
    </span>
    <span class="col2b bold">
      Expiration Date
    </span>
  </div>
  
	<div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2d">
      <select id="{NAME}_cc_exp_month" name="{NAME}_cc_exp_month" class="inputtiny">		   
        <option value="Select">Select
        </option>
        <option value="01">01 - Jan
        </option>
        <option value="02">02 - Feb
        </option>
        <option value="03">03 - Mar
        </option>
        <option value="04">04 - Apr
        </option>
        <option value="05">05 - May
        </option>
        <option value="06">06 - Jun
        </option>
        <option value="07">07 - Jul
        </option>
        <option value="08">08 - Aug
        </option>
        <option value="09">09 - Sep
        </option>
        <option value="10">10 - Oct
        </option>
        <option value="11">11 - Nov
        </option>
        <option value="12">12 - Dec
        </option>			  
      </select>
    </span>
    <span class="col2b bold">
      <select id="{NAME}_cc_exp_year" name="{NAME}_cc_exp_year" class="inputtiny">			
        <option value="Select">Select
        </option>
        <option  value="10">2010
        </option>
        <option  value="11">2011
        </option>
        <option  value="12">2012
        </option>
        <option  value="13">2013
        </option>
        <option  value="14">2014
        </option>
        <option  value="15">2015
        </option>
        <option  value="16">2016
        </option>
        <option  value="17">2017
        </option>
        <option  value="18">2018
        </option>
        <option  value="19">2019
        </option>
        <option  value="19">2020
        </option>	
        <option  value="19">2021
        </option>	  
      </select>
    </span>
  </div>
  
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='creditcard_flat_paypal']">
	
	<div class="row">
    <span class="col1b display white">.</span>
    <span class="col2">
      <img src="/images/cards.gif" />
      <br /><br />
      
    </span>
  </div>
  
  <xsl:if test="count(VALUES/VALUE) > 0">
  <xsl:for-each select="VALUES/VALUE">
   	<div class="row">
      <span class="col1b display">
        <input autocomplete="off" type="radio" name="billing_payment_type" value="{@id}" id="checkout.tattoojohnny_set" checked="checked" />
      </span>
      <span class="col3 bold">
        <span style="color:grey"><xsl:value-of select="@data" disable-output-escaping="yes" /></span><br />
        <br />
        
      </span>
    </div>
    <div class="row">
      <span class="col1b display">
      .</span>
      <span class="col3 bold">
        Security Code <a href="#" class="small-link" id="checkout.security_code">(What is this?)</a>
      </span>
    </div>
    <div class="row">
      <span class="col1b display white">
      .</span>
      <span class="col3 bold">
        <input name="{@id}_user_order_cc_cvv2_prior" class="inputshort" id="{@id}_user_order_cc_cvv2" value="" title="" maxlength="100" type="text" />
      </span>
    </div>
    <div class="row">
      <hr class="twocol" />
    </div>
  </xsl:for-each>
  </xsl:if>
  
  <div class="row">
    <span class="col1b display">
      <input autocomplete="off" type="radio" name="billing_payment_type" value="tattoojohnny" id="checkout.tattoojohnny_set" checked="checked" />
    </span>
    <span class="col2 bold">
      Credit Card Number
    </span>
    <span class="col2e bold">
      Security Code  <a href="#" class="small-link" id="checkout.security_code">(What is this?)</a>
    </span>
  </div>
  
  <div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2">
      <input name="{NAME}_cc_number" class="inputcc" id="{NAME}_cc_number" value="" title="" maxlength="100" type="text" />
    </span>
    <span class="col2b bold">
      <input name="{NAME}_cc_cvv2" class="inputshort" id="{NAME}_cc_cvv2" value="" title="" maxlength="100" type="text" />
    </span>
  </div>
	
	<div class="row">
    <span class="col1b display">
      .
    </span>
    <span class="col2b bold">
      Expiration Date
    </span>
  </div>
  
	<div class="row">
    <span class="col1b display white">
      .
    </span>
    <span class="col2d">
      <select id="{NAME}_cc_exp_month" name="{NAME}_cc_exp_month" class="inputtiny">		   
        <option value="Select">Select
        </option>
        <option value="01">01 - Jan
        </option>
        <option value="02">02 - Feb
        </option>
        <option value="03">03 - Mar
        </option>
        <option value="04">04 - Apr
        </option>
        <option value="05">05 - May
        </option>
        <option value="06">06 - Jun
        </option>
        <option value="07">07 - Jul
        </option>
        <option value="08">08 - Aug
        </option>
        <option value="09">09 - Sep
        </option>
        <option value="10">10 - Oct
        </option>
        <option value="11">11 - Nov
        </option>
        <option value="12">12 - Dec
        </option>			  
      </select>
    </span>
    <span class="col2b bold">
      <select id="{NAME}_cc_exp_year" name="{NAME}_cc_exp_year" class="inputtiny">			
        <option value="Select">Select
        </option>
        <option  value="10">2010
        </option>
        <option  value="11">2011
        </option>
        <option  value="12">2012
        </option>
        <option  value="13">2013
        </option>
        <option  value="14">2014
        </option>
        <option  value="15">2015
        </option>
        <option  value="16">2016
        </option>
        <option  value="17">2017
        </option>
        <option  value="18">2018
        </option>
        <option  value="19">2019
        </option>
        <option  value="19">2020
        </option>	
        <option  value="19">2021
        </option>	  
      </select><br />
      <br />
      
    </span>
  </div>
  
  <div class="row">
    <hr class="twocol" />
  </div>
  
  <div class="row">
    <span class="col1b display">
      <input autocomplete="off" type="radio" name="billing_payment_type" value="paypal" id="checkout.paypal_set" />
    </span>
    <span class="col2 bold">
      Paypal <img type="image" src="/images/paypal.gif" border="0" /><br />
      <br />
      
    </span>
  </div>
  
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='video_preview']">
   <xsl:variable name="preview_url">
    <xsl:choose>
      <xsl:when test="DEFAULT != ''">
        <xsl:value-of select="DEFAULT " />
      </xsl:when>
      <xsl:otherwise>0</xsl:otherwise>
    </xsl:choose>
  </xsl:variable>
    
  <xsl:if test="//FORMELEMENT[NAME=$preview_url]//DEFAULT != ''">
   <span class="{DISPLAYCLASS}">
   <div id="{NAME}_url_wrap">
    <div id="moviewrapper">
        <div id="themovie"><a href="http://www.adobe.com/go/getflashplayer">You might need to get the Flash Player</a> to see this player.</div>
        <script type="text/javascript">
    		var mainmov = new SWFObject("/swf/player.swf","mediaplayer","320","240","8");
    		mainmov.addParam("scale", "noscale");
    	  mainmov.addParam("allowfullscreen","true");
    		mainmov.addParam('flashvars','file=<xsl:value-of select="//FORMELEMENT[NAME=$preview_url]//DEFAULT" />');
    		mainmov.addVariable("width","320");
    		mainmov.addVariable("height","240");
    		mainmov.addVariable("skin","/swf/snel.swf");
    		mainmov.write("themovie");
    	  </script>
      </div>
    <a href="#" onclick="deleteIcon('{NAME}_url')"><img id="delete_{NAME}_url" src="/images/delete_image.gif" border="0" /></a>
  </div>
  <input type="hidden" value="{DEFAULT}" name="{NAME}_url" class="{CLASS}" border="0" />
  </span>
  </xsl:if>
  
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='video_player']">
  
  <xsl:if test="DEFAULT != ''">
  <span class="{DISPLAYCLASS}">
    <div id="moviewrapper">
      <div id="themovie"><a href="http://www.adobe.com/go/getflashplayer">You might need to get the Flash Player</a> to see this player.</div>
    
      <script type="text/javascript">
  		var mainmov = new SWFObject("/swf/player.swf","mediaplayer","320","240","8");
  		mainmov.addParam("scale", "noscale");
  	  mainmov.addParam("allowfullscreen","true");
  		mainmov.addParam('flashvars','file=<xsl:value-of select="DEFAULT" />');
  		mainmov.addVariable("width","320");
  		mainmov.addVariable("height","240");
  		mainmov.addVariable("skin","/swf/snel.swf");
  		mainmov.write("themovie");
  	  </script>
  	
    </div>
  </span>
  </xsl:if>
  
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='image_icon']">
    
    <span class="{DISPLAYCLASS}">
      <div id="{NAME}_url_wrap">
        <input type="hidden" name="delete_{DISPLAY}" value="false" />
        <img src="{DEFAULT}" name="{NAME}" id="{NAME}" class="{CLASS}" onclick="{DEFAULT/@onclick}" border="0" />
        <a href="#" onclick="deleteIcon( this.previousSibling, '{//FORMNAME}' ), 'delete_{DISPLAY}'"><img id="delete_{NAME}_url" class="delete_icon" src="/images/delete_image.gif" border="0" /></a>
        <xsl:call-template name="dojo_tooltip">
          <xsl:with-param name="name">delete_<xsl:value-of select="NAME" />_url</xsl:with-param>
          <xsl:with-param name="title" select="'delete this image'" />
          <xsl:with-param name="class" select="'tooltip'" />
        </xsl:call-template>
      </div>
    </span>
		<!--<input type="hidden" value="{DEFAULT}" name="{NAME}_url" class="{CLASS}" border="0" />-->
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='image_image']">
    <span class="{DISPLAYCLASS}">
      <div id="{NAME}_url_wrap">
        <img src="{DEFAULT}" name="{NAME}" id="{NAME}" class="{CLASS}" onclick="{DEFAULT/@onclick}" border="0" />
      </div>
    </span>
</xsl:template>

<xsl:template match="FORMELEMENT[TYPE='simile_timeline']">
   <xsl:variable name="TYPENAME"><xsl:value-of select="NAME" />_type_id</xsl:variable>
   <xsl:variable name="IDNAME"><xsl:value-of select="NAME" />_item_id</xsl:variable>
  
   <span class="{DISPLAYCLASS}">
      <div id="{NAME}_url_wrap">
       <div class="label">
          <span style="color: #00cc00;"><xsl:value-of select="DEFAULT" /></span> 
          <span style="color: #91AA9D;"><xsl:value-of select="DISPLAY" /></span></div>
          
          <div id="my-timeplot" style="height: 150px;"></div>
          <xsl:if test="count(OPTION) > 1">
          <div class="plotcontrols">
              <xsl:for-each select="OPTION">
                <span id="timePlot_label_{position()}"><input type="checkbox" onclick="setPlot({position()})" checked="checked"/><xsl:value-of select="self::*" /></span> | 
              </xsl:for-each>
          </div>
          </xsl:if>
          
          <script type="text/javascript">
          dojo.addOnLoad( function() 
            { 
            timeplot_type = <xsl:value-of select="//FORMELEMENT[NAME=$TYPENAME]/DEFAULT" />;
            timeplot_id = <xsl:value-of select="//FORMELEMENT[NAME=$IDNAME]/DEFAULT" />;
            timeplot_service = "<xsl:value-of select="DEFAULT/@service" />";
            timeplot_tracks = <xsl:value-of select="count(OPTION)" />;
            timeplot_min = <xsl:value-of select="DEFAULT/@min" />;
            timeplot_max = <xsl:value-of select="DEFAULT/@max" />;
            initPlot();
            loadPlot(); 
            }
          );
          </script>
      </div>
    </span>
</xsl:template>

</xsl:stylesheet>
