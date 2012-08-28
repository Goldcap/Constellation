//Pass through function
//For legacy Forms
var thisValidator = new FormValidator();

function checkForm(validator,theForm) {
  thisValidator.init( theForm );
  thisValidator.checkForm();
  if ( thisValidator.debug == 0 ) {
    return thisValidator.validated;
	} else {
		if (thisValidator.debug == 2) {
  		console.log("The form " + theForm + " has " + thisValidator.theElementsLength + " elements.");
  	}
    console.info("Total Errors: " + thisValidator.err);
		return false;
	}
}

function setAction(element,action) {
  var theform = element.form;
  var theelement = theform.elements['styroaction'];
  if (! theelement || theelement == 'undefined' ) {
    var formaction = document.createElement("input");
  	formaction.setAttribute("type","hidden");
  	formaction.setAttribute("name","styroaction");
  	formaction.setAttribute("value",action);
  	theform.appendChild(formaction);
	} else {
    theelement.value = action;
  }
}
		
function setEdit(theForm) {
	thisValidator.skipcheck = true;
}

function dropEdit(theForm) {
  //alert("DROPPING");
}

//Class Constructor
function FormValidator() {

}

FormValidator.prototype.init = function( theForm, event ) {
  if(theForm != undefined)
	{
		this.theForm = document.forms[theForm];
		this.theElements = this.theForm.elements;
		this.theElementsLength = this.theElements.length;
	}
	this.err=0;
	this.setValidators();
}

//Debug Levels
// 1 =  Error Every Field
// 2 = Show Name, VREQ, and VTIP

//Class Properties
FormValidator.prototype.skipcheck = false;
FormValidator.prototype.debug = 0;
FormValidator.prototype.vcqk = new Object();
FormValidator.prototype.vcqkloaded = false;
FormValidator.prototype.err = 0;
FormValidator.prototype.selset = 0;
FormValidator.prototype.firsterror = '';
FormValidator.prototype.currentElement = '';
FormValidator.prototype.required = '';
FormValidator.prototype.doval = '';
FormValidator.prototype.gotit = '';
FormValidator.prototype.useBeacons = true;
//This is a hack for the dojo Editor Item in Digit 1.2
FormValidator.prototype.editor = null;

FormValidator.prototype.validated = false;

//Class Methods
FormValidator.prototype.thisIE = function () {
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		return true;
	} else {
		return false;
	}
}

FormValidator.prototype.setValidators = function() {
	if (! this.vcqkloaded) {
	  thisParse = new XMLParser;
  	var vdoc = thisParse.getDoc('/xml/StyroformValidators.xml');
  	var validators = vdoc.getElementsByTagName("validator");
  	for(var i = 0; i < validators.length; i++) {
  		var thistype = validators[i].getElementsByTagName("type")[0].firstChild.data;
  		var thisexpr = validators[i].getElementsByTagName("expression")[0].firstChild.data;
  		eval('this.vcqk.' + thistype + '= eval(thisexpr)');
  		//if (this.debug == 2) console.log(thistype + '=' + thisexpr);
  	}
  	this.vcqkloaded = true;
	}
}

FormValidator.prototype.showerrors = function() {
	if (this.theElements['showerrors'].value == 'TRUE') {
		return true;
	} else {
		return false;
	}
}

FormValidator.prototype.checkForm = function() {
  if (this.skipcheck) {
    (FormValidator.prototype.useBeacons) ? this.sendBeacon() : null;
    FormValidator.prototype.validated = true;
  } else {
    //Loop over all form elements
  	for(var i = 0; i < this.theElementsLength; i++) {
  	  this.currentElement = this.theElements[i];
  		if (this.debug == 2) {
    		console.log("Debugging Form Item " + i + "::"+ this.currentElement.type + " -> " + this.currentElement.name);
    	}
  		this.validateItem();
  	}
  	this.setGlobalErrorNotice();
  }
}

FormValidator.prototype.setGlobalErrorNotice = function () {
  if (this.err > 0) {
    if (document.getElementById('errornotice_'+this.theForm.name).className == 'errornotice') {
	  		document.getElementById('errornotice_'+this.theForm.name).className = 'errornotice_visible';
  	}
  	if (document.getElementById('errornotice_'+this.theForm.name).style.display == 'none') {
  		document.getElementById('errornotice_'+this.theForm.name).style.display = 'block';
  	}
  	(FormValidator.prototype.useBeacons) ? this.sendBeacon( false ) : null;
  	FormValidator.prototype.validated = false;
	} else {
	  (FormValidator.prototype.useBeacons) ? this.sendBeacon( true ) : null;
		FormValidator.prototype.validated = true;
	}
}

FormValidator.prototype.sendBeacon = function ( value ) {
    if (this.theElements["beacon_sig"]) {
      thisParse = new XMLParser;
    	var val = this.theElements["beacon_sig"].value + "," + this.theElements["beacon_data"].value;
    	thisParse.touchUrl("/services/Beacon/form/"+ val + "/"+value);
  	}
}

FormValidator.prototype.initItem = function () {
  this.required = false;
	this.doval = false;
	this.currentElement.vtip = this.getDocValue("vtip");
	this.currentElement.vskip = this.getDocValue("vskip");
	this.currentElement.vreq = this.getDocValue("vreq");
  //This is a value set PER item for showing CSS Error messages
  this.currentElement.spec = (this.getDocValue("spec") != '') ? this.getDocValue("spec") : "FALSE";
  //This is the Size limitation for textarea
  this.currentElement.asize = this.getDocValue("size");
  if ((this.currentElement.type != 'select-multiple') && (this.currentElement.type != 'select-one')) {
    //this.currentElement.value = this.currentElement.value.trim();
  }
  
  this.currentElement.thisclass = this.getaclass(this.currentElement.className);
	this.currentElement.parentclass = this.getaclass(this.currentElement.parentNode.className);
	this.currentElement.parentparentclass = this.getaclass(this.currentElement.parentNode.parentNode.className);
	this.required = this.currentElement.vreq;
	this.editor = null;
}

FormValidator.prototype.validateItem = function () {
	
  //try {
    this.initItem();
  	
  	if (this.currentElement.vtip != null) {
  		if (this.currentElement.vtip.length > 0) {
  			this.doval = true;
  		}
  	}
  	
  	if (this.debug == 2) {
  		console.info("Name:" + this.currentElement.name + " Required: " + this.currentElement.vreq + " Validation:" + this.currentElement.vtip)
  	}
  	
  	var elementType = this.currentElement.type.replace(/[-]/,"");
  	if (elementType == 'hidden') {
        this.required = 'FALSE';
  		  this.doval = false;
    } else if ((this.currentElement.vreq == 'VNN') && (this.currentElement.value.length > 0)) {
  		  this.required = 'TRUE';
  	} else if ((this.currentElement.vreq == 'VNN') && (this.currentElement.value.length == 0)) {
  	  	this.required = 'TRUE';
  		  this.doval = false;
  	} else if (elementType == 'reset') {
        console.info("Name:" + this.currentElement.name + "( " + this.currentElement.type + " ) has no validation method, skipping...");
        this.required = 'FALSE';
        this.doval = false;
    }
    
  	//If we've told the form to temporarily skip the validation by setting this to other than "false"
  	//Just move past the element, but note this doesn't work for server-side validation
  	if (this.currentElement.vskip != "false") {
      this.required = 'FALSE';
    }
  	
    if ((this.required == 'TRUE') && (! this.currentElement.vtip.substring(0,2) == 'js') && (this.currentElement.type != 'select-multiple') && (this.currentElement.type != 'select-one') && (!this.vcqk[this.currentElement.vtip])) {
  	 if (this.debug == 2) {
      console.error ("No validator for " + this.currentElement.vtip);
  	 }
     this.err++;
     return false;
    }
  	if (this.doval != false) {
  	 
  	 if (this.currentElement.vtip.substring(0,2) == 'js') {
  	    var theres = eval(this.currentElement.vtip.replace("js:", ""));
  	    if (this.debug == 2) {
      		console.info("eval of " + this.currentElement.name + " is " + theres);
      	}
  	    if (! theres) {
  			  if (this.currentElement.type == 'checkbox') {
            this.err++;
            this.putErrors();
          } else {
    		    this.createError();
    		  }
        } else {
          this.resetField();
        }
  		} else {
  		  
  		  var thefunction = 'this.validate_'+elementType+'()';
        
        try {
        return eval(thefunction);
        } catch( err ) {
        return true;
        }
      }
  	}
  //} catch(e) {
  //  if (this.debug == 2)console.log('error with js validators');
  //  return false;
  //}
}

FormValidator.prototype.validate_ = function() {
   return true;
}

FormValidator.prototype.validate_file = function() {
   if (this.currentElement.vtip.substring(0,2) != 'js') {
		if ((this.currentElement.vtip == 'phoneNumber') || (this.currentElement.vtip == 'phone')){
			this.currentElement.value = this.currentElement.value.replace(/[^\d]/g, "");
			if ((this.currentElement.value.substring(0,1) == 0) || (this.currentElement.value.substring(0,1) == 1)) {
				this.currentElement.value = this.currentElement.value.substring(1,this.currentElement.value.length);
			  }
			}
		var gotIt = this.vcqk[this.currentElement.vtip].exec(this.currentElement.value);
	} else {
		if (eval(this.currentElement.vtip.replace("js:", "")) == 'true') {
			gotIt = true;
		} else {
			gotIt = false;
		}
	}
	if ((! gotIt) || (gotIt == null)) {
		this.createError();
	} else {
		this.resetField();
	}
}

FormValidator.prototype.validate_submit = function() {
   return true;
}

FormValidator.prototype.validate_text = function() {
  
  if (this.currentElement.vtip == 'truncURL') {
	  var initStart = /^http(s)?/.exec(this.currentElement.value);
	  if (initStart != null) {
	    var theval = this.currentElement.value.replace("https://", "");
  	  theval = theval.replace("http://", "");
  	  if (theval == "") {
  	    var gotIt = true;
      } else {
        var gotIt = this.vcqk[this.currentElement.vtip].exec(theval);
      }
    } else {
      var gotIt = false;
    }
  } else if (this.currentElement.vtip.substring(0,2) != 'js') {
    if ((this.currentElement.vtip == 'phoneNumber') || (this.currentElement.vtip == 'phone')){
  		this.currentElement.value = this.currentElement.value.replace(/[^\d]/g, "");
  		if ((this.currentElement.value.substring(0,1) == 0) || (this.currentElement.value.substring(0,1) == 1)) {
			 this.currentElement.value = this.currentElement.value.substring(1,this.currentElement.value.length);
		  }
		}
		var gotIt = this.vcqk[this.currentElement.vtip].exec(this.currentElement.value);
	} else {
		if (eval(this.currentElement.vtip.replace("js:", "")) == 'true') {
			gotIt = true;
		} else {
			gotIt = false;
		}
	}
	if ((! gotIt) || (gotIt == null)) {
		this.createError();
	} else {
	  this.resetField();
	}
}

FormValidator.prototype.validate_textarea = function() {
  //this is a trigger textarea for the validator
  
  if (this.currentElement.name.substring(0,6) == 'editor') {
    //this is the hidden field
    result = this.currentElement.name.replace('editor','');
    if(this.debug == 2) {
		  console.info("hidden field name is "+result);
	  } 
    //this is the dojo element
    editordojo = this.currentElement.name.replace('editor','editordojo');
    
    if(this.debug == 2) {
		  console.log("dojo editor name is "+editordojo);
	  }
    
    this.editor = this.currentElement.name.replace('editor','editorwrapper');
    
    if(this.debug == 2) {
		  console.log("this.editor is "+this.editor + " from " + editordojo);
	  }
	  
    //Get the data from dojo's editor
    var editorObj = dijit.byId(editordojo);
    var vals = editorObj.getValue(false);
    vals = vals.replace("<br>","");
    if(this.debug == 2) {
      console.log('value='+vals);
    }
    //pass the dojo value to the "hidden" but correctly named formelement
    this.theForm.elements[result].value = vals;
  } else {
    vals = this.currentElement.value;
  }
  
  var gotIt = this.vcqk[this.currentElement.vtip].exec(vals.replace(/\n/g,"")); 
  if ((this.currentElement.asize > 0) && (this.currentElement.value.length > this.currentElement.asize)) {
    gotIt = false;
  }
  if ((! gotIt) || (gotIt == null)) {
    this.createError();
	} else {
    this.resetField();
	}
}

FormValidator.prototype.validate_checkbox = function() {
   if (((this.currentElement.vreq == 'TRUE') && (this.fieldCheckCount(this.theElements[this.currentElement.name]) == 0)) || (this.fieldCheckCount(this.theElements[this.currentElement.name]) > this.currentElement.vtip))  {
 		this.err++;
		this.putErrors();
	 } else {
	 	this.resetField();
	}
}

FormValidator.prototype.validate_radio = function() {
	//if (((! this.currentElement.vtip == 0) && (this.fieldChecked(this.theElements[this.currentElement.name]) == this.theElements.vreq)) || (this.theElements.vtip == 0)) {
	if ((this.required == 'TRUE') && (! this.fieldChecked(this.theElements[this.currentElement.name]))){
		this.err++;
		this.putErrors();
	} else {
		this.resetField();
	}
}

FormValidator.prototype.validate_hidden = function() {
	return true;
}

FormValidator.prototype.validate_password = function() {
  //same as Text Field
  this.validate_text();
}

FormValidator.prototype.validate_button = function() {
  return true;
}

FormValidator.prototype.validate_selectone = function() {
	if (this.currentElement.selectedIndex == this.currentElement.vtip) {
		this.createError();
  } else {
  	//this.currentElement.thevalue = document.forms[theForm.name].elements[name].options[document.forms[theForm.name].elements[name].selectedIndex].value
    this.resetField();
  }
}

FormValidator.prototype.validate_selectmultiple = function() {
	var tmp = this.currentElement.vtip.split("|");
  var thevtip = tmp[0];
  var thevtot = tmp[1];
  
  var erra = false;
  var i;
  var count = 0;
  var thesevalues = new Array();
  
  for (i=0; i<this.currentElement.options.length; i++) {
    if (this.currentElement.options[i].selected) {
      thesevalues[count] = this.currentElement.options[i].value;
      count++;
    }
  }
  
  //if one of the selected values = the restricted value
  if (thevtip != 'null') {
    for (value in thesevalues) {
      if ((thesevalues[value] == thevtip) && (this.required == "TRUE")) {
       erra = true;
       break;
      }
    }
  }
  //if the total selected is greater than the total allowed
  //if zero, there is no limit
  if (thevtot != 0) {
    if ((thesevalues.length > thevtot) && (this.required == "TRUE")) {
      erra = true;
    }
  }
  
  if (erra) {
		this.createError();
  } else {
  	//this.currentElement.thevalue = document.forms[theForm.name].elements[name].options[document.forms[theForm.name].elements[name].selectedIndex].value
    this.resetField();
  }
}

FormValidator.prototype.setPhoneValue = function() {
	if (this.currentElement.vtip == 'phoneNumber') {
  		this.currentElement.value = this.currentElement.value.substring(0,3)+ "-" + this.currentElement.value.substring(3,6) + "-" + this.currentElement.value.substring(6,10);
  	}
}

FormValidator.prototype.resetField = function() {
	this.setPhoneValue();
	
  if ((this.currentElement.type == 'checkbox') || (this.currentElement.type == 'radio')) {
		this.currentElement.parentNode.className=this.currentElement.parentclass;
	}
	if (document.getElementById(this.currentElement.name)) {
	  document.getElementById(this.currentElement.name).className = this.currentElement.thisclass;
	}
	if (document.getElementById('error'+this.currentElement.name)) {
    document.getElementById('error'+this.currentElement.name).className = 'errorhidden';
		if (document.getElementById('error'+this.currentElement.name).innerHTML.length > 0) {
			 document.getElementById('error'+this.currentElement.name).innerHTML = '';
		}
	}
	  
	if (this.editor != null) {
	  var theeditor = document.getElementById(this.editor);
    theeditor.style.border = "1px solid #ccc";
    theeditor.style.background = "#fff";
  }
}

FormValidator.prototype.createError = function () {
	// note significant exemption for checkbox type... Not using this function
	if (this.editor != null) {
    var theeditor = document.getElementById(this.editor);
    theeditor.style.border = "1px solid #cc0000";
    theeditor.style.background = "#ffcccc";
  } else {
    this.currentElement.className = this.currentElement.thisclass+'_error';
	}
	
  if (this.required == 'TRUE') {
		this.err++;
		this.selectElement();
		this.putErrors();
	}
}

FormValidator.prototype.pushError = function () {
	this.currentElement.thisclass = this.getaclass(this.currentElement.className);
	this.createError();
	this.err++;
}


FormValidator.prototype.selectElement = function () {
	if (this.selset == 0) {
		if (this.currentElement.type == 'text') {
			this.currentElement.select();
		};
		this.currentElement.focus();
		this.selset = 1;
	}
}

FormValidator.prototype.putErrors = function () {
  if (this.currentElement.value.length == 0) {
		if (this.err == 1) { this.firsterror = this.currentElement.name + ' (required)'; }
		if (this.showerrors() || (this.currentElement.spec != "FALSE")) {document.getElementById('error'+this.currentElement.name).className = 'errormsg_'+this.currentElement.name;}
		if (this.showerrors() || (this.currentElement.spec != "FALSE")) {document.getElementById('error'+this.currentElement.name).innerHTML = (this.currentElement.spec == '') ? '&#160;(required)' : this.currentElement.spec;}
	} else {
    if ((this.currentElement.type == 'checkbox')  || (this.currentElement.type == 'radio')) {
			console.log(this.currentElement.parentNode.className  +' is set to ' +  this.currentElement.parentNode.className+'_error');
			console.log(this.currentElement.parentNode.parentNode.className +' is set to ' + this.currentElement.parentparentclass+'_error');
      this.currentElement.parentNode.className=this.currentElement.parentclass+'_error';
			this.currentElement.parentNode.parentNode.className=this.currentElement.parentparentclass+'_error';
		}
		if (this.err == 1) { this.firsterror = this.currentElement.name + ' (invalid)'; }
		if (this.showerrors() || (this.currentElement.spec != "FALSE")) {document.getElementById('error'+this.currentElement.name).className = 'errormsg_'+this.currentElement.name;}
		if (this.showerrors() || (this.currentElement.spec != "FALSE")) {document.getElementById('error'+this.currentElement.name).innerHTML = (this.currentElement.spec == '') ? '&#160;(check your entry)' : this.currentElement.spec;}
	}
	console.error("Error checking " + this.currentElement.name + " Total Errors:" + this.err);
}

FormValidator.prototype.fieldChecked = function (myradio) {
    isChecked = false;
    if (! myradio.length) {
      if (myradio.checked){ isChecked = myradio.value; } 
    } else {
      for (var i = 0; i < myradio.length; i++) { 
      	if (myradio[i].checked){ isChecked = myradio[i].value; break; } 
      } 
    }
    return isChecked;
}

FormValidator.prototype.fieldCheckCount = function (myradio) {
    var numCheck = 0;
    if (myradio.length) {
	 for (var i = 0; i < myradio.length; i++) { 
    	if (myradio[i].checked){ numCheck++; } } 
    } else if (myradio.checked)  {
		numCheck++;
	}
	return numCheck;
}

FormValidator.prototype.getaclass = function(aclass) {
	parts = aclass.split("_");
	return parts[0];
}

FormValidator.prototype.getDocValue = function(id) {
	if (document.getElementById(id+this.currentElement.name)) {
		return document.getElementById(id+this.currentElement.name).innerHTML;
	} else {
		return "FALSE";
	}
}

FormValidator.prototype.maxChars = function(id) {
  if (this.currentElement.value.length <= id) {
		return true;
	} else {
	  return false;
  }
}

FormValidator.prototype.maxLength = function(event,field,maxChars,text,wipetext)
 {
 event = event || window.event;
 if(window.event)  {keynum = event.keyCode} else if(event.which)  {keynum = event.which}
 if ((keynum != 8) && (field.value.length >= parseInt(maxChars))) {
    text = text.replace(/\$count/, maxChars);
    console.log(text);
    if (field.value.length > parseInt(maxChars)) {
      if (confirm(wipetext)) {
        field.value = '';
      }
    }
    event.returnValue=false;
    return false;
 }
}
//This is a utility function for testing the maxlength of textareas
FormValidator.prototype.miniValidate = function(thefield) {
   this.setValidators();
   this.currentElement = thefield;
   this.initItem();
   this.validate_textarea();
}

// JavaScript Document

function XMLParser(theXMLDoc) {
  try { document.execCommand("BackgroundImageCache",false,true); } catch(e) {}; 
  
	if(theXMLDoc != undefined)
	{
		this.theXMLDoc = theXMLDoc;
	} else {
		return false;
	}
}

XMLParser.prototype.thisIE = function () {
	var browser = navigator.appName;
	if(browser == "Microsoft Internet Explorer"){
		return true;
	} else {
		return false;
	}
}

XMLParser.prototype.thisNetscape = function () {
	var browser = navigator.appName;
	if(browser == "Netscape"){
		return true;
	} else {
		return false;
	}
}

XMLParser.prototype.getDoc = function (Doc,returnType,params,method,async) {
	returnType = returnType || "xml";
	method = method || "GET";
	params = params || null;
	async = async || false;
	if(this.thisIE()){
	  var myXMLHTTPRequest = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		var myXMLHTTPRequest = new XMLHttpRequest();
	}
	myXMLHTTPRequest.open(method, Doc, async);
	if (method == "POST") {
		  myXMLHTTPRequest.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	    myXMLHTTPRequest.setRequestHeader("Content-length", params.length);
	    myXMLHTTPRequest.setRequestHeader("Connection", "close"); 
	}
	myXMLHTTPRequest.send(params);
	if (myXMLHTTPRequest.status==200) {
		if (returnType == "xml") {
			return myXMLHTTPRequest.responseXML;
		} else {
			return myXMLHTTPRequest.responseText;
		}
	} else if(myXMLHTTPRequest.status==404) {
		alert("Incorrect URL at" + Doc );
		return false;
	} else {
		alert("Error Loading XML");
		return false;
	}
}

XMLParser.prototype.touchUrl = function (URL) {
	if(this.thisIE()){
	  var myXMLHTTPRequest = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		var myXMLHTTPRequest = new XMLHttpRequest();
	}
	myXMLHTTPRequest.open("GET", URL, false);
	myXMLHTTPRequest.send("");
	return true;
}

function validEmail ( email_val ) {
  var gotIt = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.exec(email_val);
  if ((! gotIt) || (gotIt == null)) {
		return false;
	} else {
	  return true;
	}
}

String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}
