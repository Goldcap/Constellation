var req;
var picpath;


//*********************************************************
if(!com) var com = new Object();
if(!com.filenice) com.filenice = new Object();

com.filenice.actions = function(){
	setOverDiv();
	// class scoped functions
	this.doAlert = function(){
		alert("li: " + this.className);
		this.actions.divToggle(this.className);
	}
	this.setFunctions = function(area){
		// add the folder actions
		if(!area) area = document;
		var elements = area.getElementsByTagName("a");
		for(var i =0; i<elements.length; i++){
			var a = elements[i];
			if(a.parentNode.tagName.toLowerCase() == "li"){
				var li = a.parentNode;
				if(li.className == "open" || li.className == "closed"){ // li is a folder
					//document.write("<br />found li.a : " + li.id);
					a.actions = this;
					a.href = "javascript:void(0);"; 
					a.onclick = this.getFolder;
				}else if(a.firstChild.tagName){
					// we've found the properties link
					a.actions = this;
					a.href = "javascript:void(0);";
					a.onclick = this.divToggle;
				}
			}else{
				var params = a.className.split(" ");
				if(params[1] == "about_filenice" || params[1] == "preferences"){
					a.nof = true;
					a.onclick = this.divToggle;
				}	
			}
		}
	}
	
	// element scoped functions
	this.divToggle = function(){
		var params = this.className.split(" ");
		if(this.nof){
			var id = params[1];
		}else{
			var id = "F_" + params[1];
		}
		var element = document.getElementById(id); 
		if(element.style.display == "none" || !element.style.display) {
			element.style.display = "block"; 
		}else if(element.style.display == "block") { 
			element.style.display = "none"; 
		} 
	}
	
	// getFolder
	this.getFolder = function(){
		var params = this.className.split(" ");
		var dir = params[1];
		var id  = "F_" 	+ params[0];
		var li  = "li_" + params[0];
		var element = document.getElementById(id);
		var clicked = document.getElementById(li);
		if(element.className == "contents") {
			startBusy();
			var url="index.php?action=getFolderContents&dir="+dir;
			var ret = com.filenice.getStuff(url,id,clicked);
			
		}else if(element.className == "contents_open") { 
			element.className = "contents";
			clicked.className = "closed";
		}
	}

}

//*********************************************************

com.filenice.getStuff = function(url,div,clicked){
	if (window.XMLHttpRequest){ // moz
		request = new XMLHttpRequest();
		
	}else if (window.ActiveXObject){ // IE
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			if (request.status != 200){
				// commented out becuase of bloody safari
				// alert("There was a problem retrieving the data:\n" + request.statusText);
				endBusy();
				delete request;
			}else{
				// everything worked
				var element = document.getElementById(div);
				element.innerHTML = request.responseText;
				// apply js triggers to new content
				if(!o) var o = new com.filenice.actions;
				o.setFunctions(element);
				element.className = "contents_open";
				clicked.className = "open";
				endBusy();
			}
		}
	}
	request.open("GET", url, true);
	request.send(null);
}




function getNextImage(currentPic){
	var url="index.php?action=nextImage&pic="+currentPic;
	if (window.XMLHttpRequest){ // moz
		request = new XMLHttpRequest();
	}else if (window.ActiveXObject){ // IE
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			if (request.status != 200){
				alert("There was a problem retrieving the data:\n" + request.statusText);
				delete request;
				endBusy();
			}else{
				var picinfo = request.responseText;
				// everything worked
				var element = document.getElementById("imgPreview");
				var tmp = picinfo.split("|");
				picpath = tmp[0];
				var w = tmp[1];
				var h = tmp[2];
				// preload the following image
				var tmpImg = new Image;
				tmpImg.src = tmp[3];
				element.innerHTML = "<img src=\""+picpath+"\" width=\""+w+"\" height=\""+h+"\" /><br />&nbsp;";
				intVar = setInterval("getNextImage('"+picpath+"')",ssSpeed);
			}
		}
	}
	request.open("GET", url, true);
	request.send(null);
	clearInterval(intVar);
}

function startSlideshow(currentPic){
	document.getElementById("picinfo").innerHTML = "&nbsp;";
	var element = document.getElementById("imgPreview");
	element.innerHTML = "<img src=\""+currentPic+"\" /><br />&nbsp;";
	var link = document.getElementById("slidelink");
	link.innerHTML = "<a href=\"javascript:stopSlideshow();\" title=\"stop slideshow\">stop slide show</a>";
	intVar = setInterval("getNextImage('"+currentPic+"')",ssSpeed);
}

function stopSlideshow(){
	var link = document.getElementById("slidelink");
	link.innerHTML = "<a href=\"javascript:startSlideshow('"+picpath+"');\" title=\"restart slideshow\">restart slide show</a>";
	clearInterval(intVar);
}


function sendToFlickr(url){
	document.forms['flickr'].url.value = url;
	document.forms['flickr'].submit();	
}

setOverDiv = function(){
	if (window.innerWidth){
		var w = window.innerWidth;
		var h = window.innerHeight;
		var ph = document.height;
		var t = window.pageYOffset;
	}else if (document.all){
		var w = document.body.clientWidth;
		var h = document.body.clientHeight;
		var ph = h = document.body.scrollHeight;
		var t = document.body.scrollTop;
	}
	var div = document.getElementById('overDiv');
	div.style.width = w + "px";
	div.style.height = ph + "px";
	// do the busy div
	var div = document.getElementById("busy");
	div.style.position = "absolute";
	div.style.top = (((h/2)+t) - 23) + "px";
	div.style.left = ((w/2) - 23) + "px";
}


startBusy = function(){
	setOverDiv();
	var overDiv = document.getElementById('overDiv');
	var busy = document.getElementById('busy'); 
	overDiv.style.display = "block"; 
	busy.style.display = "block"; 
}

endBusy = function(){
	var overDiv = document.getElementById('overDiv');
	var busy = document.getElementById('busy'); 
	overDiv.style.display = "none"; 
	busy.style.display = "none";
}


// search functions
function postSearch(url){
	if (window.XMLHttpRequest){ // moz
		request = new XMLHttpRequest();
		
	}else if (window.ActiveXObject){ // IE
		request = new ActiveXObject("Microsoft.XMLHTTP");
	}
	request.onreadystatechange = function(){
		if (request.readyState == 4){
			if (request.status != 200){
				alert("There was a problem retrieving the data:\n" + request.statusText);
				delete request;
				endBusy();
			}else{
				// everything worked
				var element = document.getElementById("searchResults");
				element.innerHTML = request.responseText; 
				element.style.display = "block";
				endBusy();
			}
		}
	}
	request.open("POST", url, true);
	var ss = document.forms['search'].sstring.value;
	request.send("sstring="+ss);
}

function validateSearch(url){
	var frm = document.forms['search'];
	if(frm.sstring.value != ""){
		frm.submit();
	}else{
		frm.sstring.style.border = "1px solid red;"; 
	}
	return false;
}


window.onresize = setOverDiv;