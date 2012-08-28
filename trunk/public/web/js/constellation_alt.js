/**
 * SWFUpload: http://www.swfupload.org, http://swfupload.googlecode.com
 *
 * mmSWFUpload 1.0: Flash upload dialog - http://profandesign.se/swfupload/,  http://www.vinterwebb.se/
 *
 * SWFUpload is (c) 2006-2007 Lars Huring, Olov Nilzén and Mammon Media and is released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * SWFUpload 2 is (c) 2007-2008 Jake Roberts and is released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */


/* ******************* */
/* Constructor & Init  */
/* ******************* */
var SWFUpload;

if (SWFUpload == undefined) {
	SWFUpload = function (settings) {
		this.initSWFUpload(settings);
	};
}

SWFUpload.prototype.initSWFUpload = function (settings) {
	try {
		this.customSettings = {};	// A container where developers can place their own settings associated with this instance.
		this.settings = settings;
		this.eventQueue = [];
		this.movieName = "SWFUpload_" + SWFUpload.movieCount++;
		this.movieElement = null;


		// Setup global control tracking
		SWFUpload.instances[this.movieName] = this;

		// Load the settings.  Load the Flash movie.
		this.initSettings();
		this.loadFlash();
		this.displayDebugInfo();
	} catch (ex) {
		delete SWFUpload.instances[this.movieName];
		throw ex;
	}
};

/* *************** */
/* Static Members  */
/* *************** */
SWFUpload.instances = {};
SWFUpload.movieCount = 0;
SWFUpload.version = "2.2.0 2009-03-25";
SWFUpload.QUEUE_ERROR = {
	QUEUE_LIMIT_EXCEEDED	  		: -100,
	FILE_EXCEEDS_SIZE_LIMIT  		: -110,
	ZERO_BYTE_FILE			  		: -120,
	INVALID_FILETYPE		  		: -130
};
SWFUpload.UPLOAD_ERROR = {
	HTTP_ERROR				  		: -200,
	MISSING_UPLOAD_URL	      		: -210,
	IO_ERROR				  		: -220,
	SECURITY_ERROR			  		: -230,
	UPLOAD_LIMIT_EXCEEDED	  		: -240,
	UPLOAD_FAILED			  		: -250,
	SPECIFIED_FILE_ID_NOT_FOUND		: -260,
	FILE_VALIDATION_FAILED	  		: -270,
	FILE_CANCELLED			  		: -280,
	UPLOAD_STOPPED					: -290
};
SWFUpload.FILE_STATUS = {
	QUEUED		 : -1,
	IN_PROGRESS	 : -2,
	ERROR		 : -3,
	COMPLETE	 : -4,
	CANCELLED	 : -5
};
SWFUpload.BUTTON_ACTION = {
	SELECT_FILE  : -100,
	SELECT_FILES : -110,
	START_UPLOAD : -120
};
SWFUpload.CURSOR = {
	ARROW : -1,
	HAND : -2
};
SWFUpload.WINDOW_MODE = {
	WINDOW : "window",
	TRANSPARENT : "transparent",
	OPAQUE : "opaque"
};

// Private: takes a URL, determines if it is relative and converts to an absolute URL
// using the current site. Only processes the URL if it can, otherwise returns the URL untouched
SWFUpload.completeURL = function(url) {
	if (typeof(url) !== "string" || url.match(/^https?:\/\//i) || url.match(/^\//)) {
		return url;
	}
	
	var currentURL = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ":" + window.location.port : "");
	
	var indexSlash = window.location.pathname.lastIndexOf("/");
	if (indexSlash <= 0) {
		path = "/";
	} else {
		path = window.location.pathname.substr(0, indexSlash) + "/";
	}
	
	return /*currentURL +*/ path + url;
	
};


/* ******************** */
/* Instance Members  */
/* ******************** */

// Private: initSettings ensures that all the
// settings are set, getting a default value if one was not assigned.
SWFUpload.prototype.initSettings = function () {
	this.ensureDefault = function (settingName, defaultValue) {
		this.settings[settingName] = (this.settings[settingName] == undefined) ? defaultValue : this.settings[settingName];
	};
	
	// Upload backend settings
	this.ensureDefault("upload_url", "");
	this.ensureDefault("preserve_relative_urls", false);
	this.ensureDefault("file_post_name", "Filedata");
	this.ensureDefault("post_params", {});
	this.ensureDefault("use_query_string", false);
	this.ensureDefault("requeue_on_error", false);
	this.ensureDefault("http_success", []);
	this.ensureDefault("assume_success_timeout", 0);
	
	// File Settings
	this.ensureDefault("file_types", "*.*");
	this.ensureDefault("file_types_description", "All Files");
	this.ensureDefault("file_size_limit", 0);	// Default zero means "unlimited"
	this.ensureDefault("file_upload_limit", 0);
	this.ensureDefault("file_queue_limit", 0);

	// Flash Settings
	this.ensureDefault("flash_url", "swfupload.swf");
	this.ensureDefault("prevent_swf_caching", true);
	
	// Button Settings
	this.ensureDefault("button_image_url", "");
	this.ensureDefault("button_width", 1);
	this.ensureDefault("button_height", 1);
	this.ensureDefault("button_text", "");
	this.ensureDefault("button_text_style", "color: #000000; font-size: 16pt;");
	this.ensureDefault("button_text_top_padding", 0);
	this.ensureDefault("button_text_left_padding", 0);
	this.ensureDefault("button_action", SWFUpload.BUTTON_ACTION.SELECT_FILES);
	this.ensureDefault("button_disabled", false);
	this.ensureDefault("button_placeholder_id", "");
	this.ensureDefault("button_placeholder", null);
	this.ensureDefault("button_cursor", SWFUpload.CURSOR.ARROW);
	this.ensureDefault("button_window_mode", SWFUpload.WINDOW_MODE.WINDOW);
	
	// Debug Settings
	this.ensureDefault("debug", false);
	this.settings.debug_enabled = this.settings.debug;	// Here to maintain v2 API
	
	// Event Handlers
	this.settings.return_upload_start_handler = this.returnUploadStart;
	this.ensureDefault("swfupload_loaded_handler", null);
	this.ensureDefault("file_dialog_start_handler", null);
	this.ensureDefault("file_queued_handler", null);
	this.ensureDefault("file_queue_error_handler", null);
	this.ensureDefault("file_dialog_complete_handler", null);
	
	this.ensureDefault("upload_start_handler", null);
	this.ensureDefault("upload_progress_handler", null);
	this.ensureDefault("upload_error_handler", null);
	this.ensureDefault("upload_success_handler", null);
	this.ensureDefault("upload_complete_handler", null);
	
	this.ensureDefault("debug_handler", this.debugMessage);

	this.ensureDefault("custom_settings", {});

	// Other settings
	this.customSettings = this.settings.custom_settings;
	
	// Update the flash url if needed
	if (!!this.settings.prevent_swf_caching) {
		this.settings.flash_url = this.settings.flash_url + (this.settings.flash_url.indexOf("?") < 0 ? "?" : "&") + "preventswfcaching=" + new Date().getTime();
	}
	
	if (!this.settings.preserve_relative_urls) {
		//this.settings.flash_url = SWFUpload.completeURL(this.settings.flash_url);	// Don't need to do this one since flash doesn't look at it
		this.settings.upload_url = SWFUpload.completeURL(this.settings.upload_url);
		this.settings.button_image_url = SWFUpload.completeURL(this.settings.button_image_url);
	}
	
	delete this.ensureDefault;
};

// Private: loadFlash replaces the button_placeholder element with the flash movie.
SWFUpload.prototype.loadFlash = function () {
	var targetElement, tempParent;

	// Make sure an element with the ID we are going to use doesn't already exist
	if (document.getElementById(this.movieName) !== null) {
		throw "ID " + this.movieName + " is already in use. The Flash Object could not be added";
	}

	// Get the element where we will be placing the flash movie
	targetElement = document.getElementById(this.settings.button_placeholder_id) || this.settings.button_placeholder;

	if (targetElement == undefined) {
		throw "Could not find the placeholder element: " + this.settings.button_placeholder_id;
	}

	// Append the container and load the flash
	tempParent = document.createElement("div");
	tempParent.innerHTML = this.getFlashHTML();	// Using innerHTML is non-standard but the only sensible way to dynamically add Flash in IE (and maybe other browsers)
	targetElement.parentNode.replaceChild(tempParent.firstChild, targetElement);

	// Fix IE Flash/Form bug
	if (window[this.movieName] == undefined) {
		window[this.movieName] = this.getMovieElement();
	}
	
};

// Private: getFlashHTML generates the object tag needed to embed the flash in to the document
SWFUpload.prototype.getFlashHTML = function () {
	// Flash Satay object syntax: http://www.alistapart.com/articles/flashsatay
	return ['<object id="', this.movieName, '" type="application/x-shockwave-flash" data="', this.settings.flash_url, '" width="', this.settings.button_width, '" height="', this.settings.button_height, '" class="swfupload">',
				'<param name="wmode" value="', this.settings.button_window_mode, '" />',
				'<param name="movie" value="', this.settings.flash_url, '" />',
				'<param name="quality" value="high" />',
				'<param name="menu" value="false" />',
				'<param name="allowScriptAccess" value="always" />',
				'<param name="flashvars" value="' + this.getFlashVars() + '" />',
				'</object>'].join("");
};

// Private: getFlashVars builds the parameter string that will be passed
// to flash in the flashvars param.
SWFUpload.prototype.getFlashVars = function () {
	// Build a string from the post param object
	var paramString = this.buildParamString();
	var httpSuccessString = this.settings.http_success.join(",");
	
	// Build the parameter string
	return ["movieName=", encodeURIComponent(this.movieName),
			"&amp;uploadURL=", encodeURIComponent(this.settings.upload_url),
			"&amp;useQueryString=", encodeURIComponent(this.settings.use_query_string),
			"&amp;requeueOnError=", encodeURIComponent(this.settings.requeue_on_error),
			"&amp;httpSuccess=", encodeURIComponent(httpSuccessString),
			"&amp;assumeSuccessTimeout=", encodeURIComponent(this.settings.assume_success_timeout),
			"&amp;params=", encodeURIComponent(paramString),
			"&amp;filePostName=", encodeURIComponent(this.settings.file_post_name),
			"&amp;fileTypes=", encodeURIComponent(this.settings.file_types),
			"&amp;fileTypesDescription=", encodeURIComponent(this.settings.file_types_description),
			"&amp;fileSizeLimit=", encodeURIComponent(this.settings.file_size_limit),
			"&amp;fileUploadLimit=", encodeURIComponent(this.settings.file_upload_limit),
			"&amp;fileQueueLimit=", encodeURIComponent(this.settings.file_queue_limit),
			"&amp;debugEnabled=", encodeURIComponent(this.settings.debug_enabled),
			"&amp;buttonImageURL=", encodeURIComponent(this.settings.button_image_url),
			"&amp;buttonWidth=", encodeURIComponent(this.settings.button_width),
			"&amp;buttonHeight=", encodeURIComponent(this.settings.button_height),
			"&amp;buttonText=", encodeURIComponent(this.settings.button_text),
			"&amp;buttonTextTopPadding=", encodeURIComponent(this.settings.button_text_top_padding),
			"&amp;buttonTextLeftPadding=", encodeURIComponent(this.settings.button_text_left_padding),
			"&amp;buttonTextStyle=", encodeURIComponent(this.settings.button_text_style),
			"&amp;buttonAction=", encodeURIComponent(this.settings.button_action),
			"&amp;buttonDisabled=", encodeURIComponent(this.settings.button_disabled),
			"&amp;buttonCursor=", encodeURIComponent(this.settings.button_cursor)
		].join("");
};

// Public: getMovieElement retrieves the DOM reference to the Flash element added by SWFUpload
// The element is cached after the first lookup
SWFUpload.prototype.getMovieElement = function () {
	if (this.movieElement == undefined) {
		this.movieElement = document.getElementById(this.movieName);
	}

	if (this.movieElement === null) {
		throw "Could not find Flash element";
	}
	
	return this.movieElement;
};

// Private: buildParamString takes the name/value pairs in the post_params setting object
// and joins them up in to a string formatted "name=value&amp;name=value"
SWFUpload.prototype.buildParamString = function () {
	var postParams = this.settings.post_params; 
	var paramStringPairs = [];

	if (typeof(postParams) === "object") {
		for (var name in postParams) {
			if (postParams.hasOwnProperty(name)) {
				paramStringPairs.push(encodeURIComponent(name.toString()) + "=" + encodeURIComponent(postParams[name].toString()));
			}
		}
	}

	return paramStringPairs.join("&amp;");
};

// Public: Used to remove a SWFUpload instance from the page. This method strives to remove
// all references to the SWF, and other objects so memory is properly freed.
// Returns true if everything was destroyed. Returns a false if a failure occurs leaving SWFUpload in an inconsistant state.
// Credits: Major improvements provided by steffen
SWFUpload.prototype.destroy = function () {
	try {
		// Make sure Flash is done before we try to remove it
		this.cancelUpload(null, false);
		

		// Remove the SWFUpload DOM nodes
		var movieElement = null;
		movieElement = this.getMovieElement();
		
		if (movieElement && typeof(movieElement.CallFunction) === "unknown") { // We only want to do this in IE
			// Loop through all the movie's properties and remove all function references (DOM/JS IE 6/7 memory leak workaround)
			for (var i in movieElement) {
				try {
					if (typeof(movieElement[i]) === "function") {
						movieElement[i] = null;
					}
				} catch (ex1) {}
			}

			// Remove the Movie Element from the page
			try {
				movieElement.parentNode.removeChild(movieElement);
			} catch (ex) {}
		}
		
		// Remove IE form fix reference
		window[this.movieName] = null;

		// Destroy other references
		SWFUpload.instances[this.movieName] = null;
		delete SWFUpload.instances[this.movieName];

		this.movieElement = null;
		this.settings = null;
		this.customSettings = null;
		this.eventQueue = null;
		this.movieName = null;
		
		
		return true;
	} catch (ex2) {
		return false;
	}
};


// Public: displayDebugInfo prints out settings and configuration
// information about this SWFUpload instance.
// This function (and any references to it) can be deleted when placing
// SWFUpload in production.
SWFUpload.prototype.displayDebugInfo = function () {
	this.debug(
		[
			"---SWFUpload Instance Info---\n",
			"Version: ", SWFUpload.version, "\n",
			"Movie Name: ", this.movieName, "\n",
			"Settings:\n",
			"\t", "upload_url:               ", this.settings.upload_url, "\n",
			"\t", "flash_url:                ", this.settings.flash_url, "\n",
			"\t", "use_query_string:         ", this.settings.use_query_string.toString(), "\n",
			"\t", "requeue_on_error:         ", this.settings.requeue_on_error.toString(), "\n",
			"\t", "http_success:             ", this.settings.http_success.join(", "), "\n",
			"\t", "assume_success_timeout:   ", this.settings.assume_success_timeout, "\n",
			"\t", "file_post_name:           ", this.settings.file_post_name, "\n",
			"\t", "post_params:              ", this.settings.post_params.toString(), "\n",
			"\t", "file_types:               ", this.settings.file_types, "\n",
			"\t", "file_types_description:   ", this.settings.file_types_description, "\n",
			"\t", "file_size_limit:          ", this.settings.file_size_limit, "\n",
			"\t", "file_upload_limit:        ", this.settings.file_upload_limit, "\n",
			"\t", "file_queue_limit:         ", this.settings.file_queue_limit, "\n",
			"\t", "debug:                    ", this.settings.debug.toString(), "\n",

			"\t", "prevent_swf_caching:      ", this.settings.prevent_swf_caching.toString(), "\n",

			"\t", "button_placeholder_id:    ", this.settings.button_placeholder_id.toString(), "\n",
			"\t", "button_placeholder:       ", (this.settings.button_placeholder ? "Set" : "Not Set"), "\n",
			"\t", "button_image_url:         ", this.settings.button_image_url.toString(), "\n",
			"\t", "button_width:             ", this.settings.button_width.toString(), "\n",
			"\t", "button_height:            ", this.settings.button_height.toString(), "\n",
			"\t", "button_text:              ", this.settings.button_text.toString(), "\n",
			"\t", "button_text_style:        ", this.settings.button_text_style.toString(), "\n",
			"\t", "button_text_top_padding:  ", this.settings.button_text_top_padding.toString(), "\n",
			"\t", "button_text_left_padding: ", this.settings.button_text_left_padding.toString(), "\n",
			"\t", "button_action:            ", this.settings.button_action.toString(), "\n",
			"\t", "button_disabled:          ", this.settings.button_disabled.toString(), "\n",

			"\t", "custom_settings:          ", this.settings.custom_settings.toString(), "\n",
			"Event Handlers:\n",
			"\t", "swfupload_loaded_handler assigned:  ", (typeof this.settings.swfupload_loaded_handler === "function").toString(), "\n",
			"\t", "file_dialog_start_handler assigned: ", (typeof this.settings.file_dialog_start_handler === "function").toString(), "\n",
			"\t", "file_queued_handler assigned:       ", (typeof this.settings.file_queued_handler === "function").toString(), "\n",
			"\t", "file_queue_error_handler assigned:  ", (typeof this.settings.file_queue_error_handler === "function").toString(), "\n",
			"\t", "upload_start_handler assigned:      ", (typeof this.settings.upload_start_handler === "function").toString(), "\n",
			"\t", "upload_progress_handler assigned:   ", (typeof this.settings.upload_progress_handler === "function").toString(), "\n",
			"\t", "upload_error_handler assigned:      ", (typeof this.settings.upload_error_handler === "function").toString(), "\n",
			"\t", "upload_success_handler assigned:    ", (typeof this.settings.upload_success_handler === "function").toString(), "\n",
			"\t", "upload_complete_handler assigned:   ", (typeof this.settings.upload_complete_handler === "function").toString(), "\n",
			"\t", "debug_handler assigned:             ", (typeof this.settings.debug_handler === "function").toString(), "\n"
		].join("")
	);
};

/* Note: addSetting and getSetting are no longer used by SWFUpload but are included
	the maintain v2 API compatibility
*/
// Public: (Deprecated) addSetting adds a setting value. If the value given is undefined or null then the default_value is used.
SWFUpload.prototype.addSetting = function (name, value, default_value) {
    if (value == undefined) {
        return (this.settings[name] = default_value);
    } else {
        return (this.settings[name] = value);
	}
};

// Public: (Deprecated) getSetting gets a setting. Returns an empty string if the setting was not found.
SWFUpload.prototype.getSetting = function (name) {
    if (this.settings[name] != undefined) {
        return this.settings[name];
	}

    return "";
};



// Private: callFlash handles function calls made to the Flash element.
// Calls are made with a setTimeout for some functions to work around
// bugs in the ExternalInterface library.
SWFUpload.prototype.callFlash = function (functionName, argumentArray) {
	argumentArray = argumentArray || [];
	
	var movieElement = this.getMovieElement();
	var returnValue, returnString;

	// Flash's method if calling ExternalInterface methods (code adapted from MooTools).
	try {
		returnString = movieElement.CallFunction('<invoke name="' + functionName + '" returntype="javascript">' + __flash__argumentsToXML(argumentArray, 0) + '</invoke>');
		returnValue = eval(returnString);
	} catch (ex) {
		throw "Call to " + functionName + " failed";
	}
	
	// Unescape file post param values
	if (returnValue != undefined && typeof returnValue.post === "object") {
		returnValue = this.unescapeFilePostParams(returnValue);
	}

	return returnValue;
};

/* *****************************
	-- Flash control methods --
	Your UI should use these
	to operate SWFUpload
   ***************************** */

// WARNING: this function does not work in Flash Player 10
// Public: selectFile causes a File Selection Dialog window to appear.  This
// dialog only allows 1 file to be selected.
SWFUpload.prototype.selectFile = function () {
	this.callFlash("SelectFile");
};

// WARNING: this function does not work in Flash Player 10
// Public: selectFiles causes a File Selection Dialog window to appear/ This
// dialog allows the user to select any number of files
// Flash Bug Warning: Flash limits the number of selectable files based on the combined length of the file names.
// If the selection name length is too long the dialog will fail in an unpredictable manner.  There is no work-around
// for this bug.
SWFUpload.prototype.selectFiles = function () {
	this.callFlash("SelectFiles");
};


// Public: startUpload starts uploading the first file in the queue unless
// the optional parameter 'fileID' specifies the ID 
SWFUpload.prototype.startUpload = function (fileID) {
	this.callFlash("StartUpload", [fileID]);
};

// Public: cancelUpload cancels any queued file.  The fileID parameter may be the file ID or index.
// If you do not specify a fileID the current uploading file or first file in the queue is cancelled.
// If you do not want the uploadError event to trigger you can specify false for the triggerErrorEvent parameter.
SWFUpload.prototype.cancelUpload = function (fileID, triggerErrorEvent) {
	if (triggerErrorEvent !== false) {
		triggerErrorEvent = true;
	}
	this.callFlash("CancelUpload", [fileID, triggerErrorEvent]);
};

// Public: stopUpload stops the current upload and requeues the file at the beginning of the queue.
// If nothing is currently uploading then nothing happens.
SWFUpload.prototype.stopUpload = function () {
	this.callFlash("StopUpload");
};

/* ************************
 * Settings methods
 *   These methods change the SWFUpload settings.
 *   SWFUpload settings should not be changed directly on the settings object
 *   since many of the settings need to be passed to Flash in order to take
 *   effect.
 * *********************** */

// Public: getStats gets the file statistics object.
SWFUpload.prototype.getStats = function () {
	return this.callFlash("GetStats");
};

// Public: setStats changes the SWFUpload statistics.  You shouldn't need to 
// change the statistics but you can.  Changing the statistics does not
// affect SWFUpload accept for the successful_uploads count which is used
// by the upload_limit setting to determine how many files the user may upload.
SWFUpload.prototype.setStats = function (statsObject) {
	this.callFlash("SetStats", [statsObject]);
};

// Public: getFile retrieves a File object by ID or Index.  If the file is
// not found then 'null' is returned.
SWFUpload.prototype.getFile = function (fileID) {
	if (typeof(fileID) === "number") {
		return this.callFlash("GetFileByIndex", [fileID]);
	} else {
		return this.callFlash("GetFile", [fileID]);
	}
};

// Public: addFileParam sets a name/value pair that will be posted with the
// file specified by the Files ID.  If the name already exists then the
// exiting value will be overwritten.
SWFUpload.prototype.addFileParam = function (fileID, name, value) {
	return this.callFlash("AddFileParam", [fileID, name, value]);
};

// Public: removeFileParam removes a previously set (by addFileParam) name/value
// pair from the specified file.
SWFUpload.prototype.removeFileParam = function (fileID, name) {
	this.callFlash("RemoveFileParam", [fileID, name]);
};

// Public: setUploadUrl changes the upload_url setting.
SWFUpload.prototype.setUploadURL = function (url) {
	this.settings.upload_url = url.toString();
	this.callFlash("SetUploadURL", [url]);
};

// Public: setPostParams changes the post_params setting
SWFUpload.prototype.setPostParams = function (paramsObject) {
	this.settings.post_params = paramsObject;
	this.callFlash("SetPostParams", [paramsObject]);
};

// Public: addPostParam adds post name/value pair.  Each name can have only one value.
SWFUpload.prototype.addPostParam = function (name, value) {
	this.settings.post_params[name] = value;
	this.callFlash("SetPostParams", [this.settings.post_params]);
};

// Public: removePostParam deletes post name/value pair.
SWFUpload.prototype.removePostParam = function (name) {
	delete this.settings.post_params[name];
	this.callFlash("SetPostParams", [this.settings.post_params]);
};

// Public: setFileTypes changes the file_types setting and the file_types_description setting
SWFUpload.prototype.setFileTypes = function (types, description) {
	this.settings.file_types = types;
	this.settings.file_types_description = description;
	this.callFlash("SetFileTypes", [types, description]);
};

// Public: setFileSizeLimit changes the file_size_limit setting
SWFUpload.prototype.setFileSizeLimit = function (fileSizeLimit) {
	this.settings.file_size_limit = fileSizeLimit;
	this.callFlash("SetFileSizeLimit", [fileSizeLimit]);
};

// Public: setFileUploadLimit changes the file_upload_limit setting
SWFUpload.prototype.setFileUploadLimit = function (fileUploadLimit) {
	this.settings.file_upload_limit = fileUploadLimit;
	this.callFlash("SetFileUploadLimit", [fileUploadLimit]);
};

// Public: setFileQueueLimit changes the file_queue_limit setting
SWFUpload.prototype.setFileQueueLimit = function (fileQueueLimit) {
	this.settings.file_queue_limit = fileQueueLimit;
	this.callFlash("SetFileQueueLimit", [fileQueueLimit]);
};

// Public: setFilePostName changes the file_post_name setting
SWFUpload.prototype.setFilePostName = function (filePostName) {
	this.settings.file_post_name = filePostName;
	this.callFlash("SetFilePostName", [filePostName]);
};

// Public: setUseQueryString changes the use_query_string setting
SWFUpload.prototype.setUseQueryString = function (useQueryString) {
	this.settings.use_query_string = useQueryString;
	this.callFlash("SetUseQueryString", [useQueryString]);
};

// Public: setRequeueOnError changes the requeue_on_error setting
SWFUpload.prototype.setRequeueOnError = function (requeueOnError) {
	this.settings.requeue_on_error = requeueOnError;
	this.callFlash("SetRequeueOnError", [requeueOnError]);
};

// Public: setHTTPSuccess changes the http_success setting
SWFUpload.prototype.setHTTPSuccess = function (http_status_codes) {
	if (typeof http_status_codes === "string") {
		http_status_codes = http_status_codes.replace(" ", "").split(",");
	}
	
	this.settings.http_success = http_status_codes;
	this.callFlash("SetHTTPSuccess", [http_status_codes]);
};

// Public: setHTTPSuccess changes the http_success setting
SWFUpload.prototype.setAssumeSuccessTimeout = function (timeout_seconds) {
	this.settings.assume_success_timeout = timeout_seconds;
	this.callFlash("SetAssumeSuccessTimeout", [timeout_seconds]);
};

// Public: setDebugEnabled changes the debug_enabled setting
SWFUpload.prototype.setDebugEnabled = function (debugEnabled) {
	this.settings.debug_enabled = debugEnabled;
	this.callFlash("SetDebugEnabled", [debugEnabled]);
};

// Public: setButtonImageURL loads a button image sprite
SWFUpload.prototype.setButtonImageURL = function (buttonImageURL) {
	if (buttonImageURL == undefined) {
		buttonImageURL = "";
	}
	
	this.settings.button_image_url = buttonImageURL;
	this.callFlash("SetButtonImageURL", [buttonImageURL]);
};

// Public: setButtonDimensions resizes the Flash Movie and button
SWFUpload.prototype.setButtonDimensions = function (width, height) {
	this.settings.button_width = width;
	this.settings.button_height = height;
	
	var movie = this.getMovieElement();
	if (movie != undefined) {
		movie.style.width = width + "px";
		movie.style.height = height + "px";
	}
	
	this.callFlash("SetButtonDimensions", [width, height]);
};
// Public: setButtonText Changes the text overlaid on the button
SWFUpload.prototype.setButtonText = function (html) {
	this.settings.button_text = html;
	this.callFlash("SetButtonText", [html]);
};
// Public: setButtonTextPadding changes the top and left padding of the text overlay
SWFUpload.prototype.setButtonTextPadding = function (left, top) {
	this.settings.button_text_top_padding = top;
	this.settings.button_text_left_padding = left;
	this.callFlash("SetButtonTextPadding", [left, top]);
};

// Public: setButtonTextStyle changes the CSS used to style the HTML/Text overlaid on the button
SWFUpload.prototype.setButtonTextStyle = function (css) {
	this.settings.button_text_style = css;
	this.callFlash("SetButtonTextStyle", [css]);
};
// Public: setButtonDisabled disables/enables the button
SWFUpload.prototype.setButtonDisabled = function (isDisabled) {
	this.settings.button_disabled = isDisabled;
	this.callFlash("SetButtonDisabled", [isDisabled]);
};
// Public: setButtonAction sets the action that occurs when the button is clicked
SWFUpload.prototype.setButtonAction = function (buttonAction) {
	this.settings.button_action = buttonAction;
	this.callFlash("SetButtonAction", [buttonAction]);
};

// Public: setButtonCursor changes the mouse cursor displayed when hovering over the button
SWFUpload.prototype.setButtonCursor = function (cursor) {
	this.settings.button_cursor = cursor;
	this.callFlash("SetButtonCursor", [cursor]);
};

/* *******************************
	Flash Event Interfaces
	These functions are used by Flash to trigger the various
	events.
	
	All these functions a Private.
	
	Because the ExternalInterface library is buggy the event calls
	are added to a queue and the queue then executed by a setTimeout.
	This ensures that events are executed in a determinate order and that
	the ExternalInterface bugs are avoided.
******************************* */

SWFUpload.prototype.queueEvent = function (handlerName, argumentArray) {
	// Warning: Don't call this.debug inside here or you'll create an infinite loop
	
	if (argumentArray == undefined) {
		argumentArray = [];
	} else if (!(argumentArray instanceof Array)) {
		argumentArray = [argumentArray];
	}
	
	var self = this;
	if (typeof this.settings[handlerName] === "function") {
		// Queue the event
		this.eventQueue.push(function () {
			this.settings[handlerName].apply(this, argumentArray);
		});
		
		// Execute the next queued event
		setTimeout(function () {
			self.executeNextEvent();
		}, 0);
		
	} else if (this.settings[handlerName] !== null) {
		throw "Event handler " + handlerName + " is unknown or is not a function";
	}
};

// Private: Causes the next event in the queue to be executed.  Since events are queued using a setTimeout
// we must queue them in order to garentee that they are executed in order.
SWFUpload.prototype.executeNextEvent = function () {
	// Warning: Don't call this.debug inside here or you'll create an infinite loop

	var  f = this.eventQueue ? this.eventQueue.shift() : null;
	if (typeof(f) === "function") {
		f.apply(this);
	}
};

// Private: unescapeFileParams is part of a workaround for a flash bug where objects passed through ExternalInterface cannot have
// properties that contain characters that are not valid for JavaScript identifiers. To work around this
// the Flash Component escapes the parameter names and we must unescape again before passing them along.
SWFUpload.prototype.unescapeFilePostParams = function (file) {
	var reg = /[$]([0-9a-f]{4})/i;
	var unescapedPost = {};
	var uk;

	if (file != undefined) {
		for (var k in file.post) {
			if (file.post.hasOwnProperty(k)) {
				uk = k;
				var match;
				while ((match = reg.exec(uk)) !== null) {
					uk = uk.replace(match[0], String.fromCharCode(parseInt("0x" + match[1], 16)));
				}
				unescapedPost[uk] = file.post[k];
			}
		}

		file.post = unescapedPost;
	}

	return file;
};

// Private: Called by Flash to see if JS can call in to Flash (test if External Interface is working)
SWFUpload.prototype.testExternalInterface = function () {
	try {
		return this.callFlash("TestExternalInterface");
	} catch (ex) {
		return false;
	}
};

// Private: This event is called by Flash when it has finished loading. Don't modify this.
// Use the swfupload_loaded_handler event setting to execute custom code when SWFUpload has loaded.
SWFUpload.prototype.flashReady = function () {
	// Check that the movie element is loaded correctly with its ExternalInterface methods defined
	var movieElement = this.getMovieElement();

	if (!movieElement) {
		this.debug("Flash called back ready but the flash movie can't be found.");
		return;
	}

	this.cleanUp(movieElement);
	
	this.queueEvent("swfupload_loaded_handler");
};

// Private: removes Flash added fuctions to the DOM node to prevent memory leaks in IE.
// This function is called by Flash each time the ExternalInterface functions are created.
SWFUpload.prototype.cleanUp = function (movieElement) {
	// Pro-actively unhook all the Flash functions
	try {
		if (this.movieElement && typeof(movieElement.CallFunction) === "unknown") { // We only want to do this in IE
			this.debug("Removing Flash functions hooks (this should only run in IE and should prevent memory leaks)");
			for (var key in movieElement) {
				try {
					if (typeof(movieElement[key]) === "function") {
						movieElement[key] = null;
					}
				} catch (ex) {
				}
			}
		}
	} catch (ex1) {
	
	}

	// Fix Flashes own cleanup code so if the SWFMovie was removed from the page
	// it doesn't display errors.
	window["__flash__removeCallback"] = function (instance, name) {
		try {
			if (instance) {
				instance[name] = null;
			}
		} catch (flashEx) {
		
		}
	};

};


/* This is a chance to do something before the browse window opens */
SWFUpload.prototype.fileDialogStart = function () {
	this.queueEvent("file_dialog_start_handler");
};


/* Called when a file is successfully added to the queue. */
SWFUpload.prototype.fileQueued = function (file) {
	file = this.unescapeFilePostParams(file);
	this.queueEvent("file_queued_handler", file);
};


/* Handle errors that occur when an attempt to queue a file fails. */
SWFUpload.prototype.fileQueueError = function (file, errorCode, message) {
	file = this.unescapeFilePostParams(file);
	this.queueEvent("file_queue_error_handler", [file, errorCode, message]);
};

/* Called after the file dialog has closed and the selected files have been queued.
	You could call startUpload here if you want the queued files to begin uploading immediately. */
SWFUpload.prototype.fileDialogComplete = function (numFilesSelected, numFilesQueued, numFilesInQueue) {
	this.queueEvent("file_dialog_complete_handler", [numFilesSelected, numFilesQueued, numFilesInQueue]);
};

SWFUpload.prototype.uploadStart = function (file) {
	file = this.unescapeFilePostParams(file);
	this.queueEvent("return_upload_start_handler", file);
};

SWFUpload.prototype.returnUploadStart = function (file) {
	var returnValue;
	if (typeof this.settings.upload_start_handler === "function") {
		file = this.unescapeFilePostParams(file);
		returnValue = this.settings.upload_start_handler.call(this, file);
	} else if (this.settings.upload_start_handler != undefined) {
		throw "upload_start_handler must be a function";
	}

	// Convert undefined to true so if nothing is returned from the upload_start_handler it is
	// interpretted as 'true'.
	if (returnValue === undefined) {
		returnValue = true;
	}
	
	returnValue = !!returnValue;
	
	this.callFlash("ReturnUploadStart", [returnValue]);
};



SWFUpload.prototype.uploadProgress = function (file, bytesComplete, bytesTotal) {
	file = this.unescapeFilePostParams(file);
	this.queueEvent("upload_progress_handler", [file, bytesComplete, bytesTotal]);
};

SWFUpload.prototype.uploadError = function (file, errorCode, message) {
	file = this.unescapeFilePostParams(file);
	this.queueEvent("upload_error_handler", [file, errorCode, message]);
};

SWFUpload.prototype.uploadSuccess = function (file, serverData, responseReceived) {
	file = this.unescapeFilePostParams(file);
	this.queueEvent("upload_success_handler", [file, serverData, responseReceived]);
};

SWFUpload.prototype.uploadComplete = function (file) {
	file = this.unescapeFilePostParams(file);
	this.queueEvent("upload_complete_handler", file);
};

/* Called by SWFUpload JavaScript and Flash functions when debug is enabled. By default it writes messages to the
   internal debug console.  You can override this event and have messages written where you want. */
SWFUpload.prototype.debug = function (message) {
	this.queueEvent("debug_handler", message);
};


/* **********************************
	Debug Console
	The debug console is a self contained, in page location
	for debug message to be sent.  The Debug Console adds
	itself to the body if necessary.

	The console is automatically scrolled as messages appear.
	
	If you are using your own debug handler or when you deploy to production and
	have debug disabled you can remove these functions to reduce the file size
	and complexity.
********************************** */
   
// Private: debugMessage is the default debug_handler.  If you want to print debug messages
// call the debug() function.  When overriding the function your own function should
// check to see if the debug setting is true before outputting debug information.
SWFUpload.prototype.debugMessage = function (message) {
	if (this.settings.debug) {
		var exceptionMessage, exceptionValues = [];

		// Check for an exception object and print it nicely
		if (typeof message === "object" && typeof message.name === "string" && typeof message.message === "string") {
			for (var key in message) {
				if (message.hasOwnProperty(key)) {
					exceptionValues.push(key + ": " + message[key]);
				}
			}
			exceptionMessage = exceptionValues.join("\n") || "";
			exceptionValues = exceptionMessage.split("\n");
			exceptionMessage = "EXCEPTION: " + exceptionValues.join("\nEXCEPTION: ");
			SWFUpload.Console.writeLine(exceptionMessage);
		} else {
			SWFUpload.Console.writeLine(message);
		}
	}
};

SWFUpload.Console = {};
SWFUpload.Console.writeLine = function (message) {
	var console, documentForm;

	try {
		console = document.getElementById("SWFUpload_Console");

		if (!console) {
			documentForm = document.createElement("form");
			document.getElementsByTagName("body")[0].appendChild(documentForm);

			console = document.createElement("textarea");
			console.id = "SWFUpload_Console";
			console.style.fontFamily = "monospace";
			console.setAttribute("wrap", "off");
			console.wrap = "off";
			console.style.overflow = "auto";
			console.style.width = "700px";
			console.style.height = "350px";
			console.style.margin = "5px";
			documentForm.appendChild(console);
		}

		console.value += message + "\n";

		console.scrollTop = console.scrollHeight - console.clientHeight;
	} catch (ex) {
		alert("Exception: " + ex.name + " Message: " + ex.message);
	}
};
var how = {
    
    //This pulls the current list of users from the room
    init: function() {
        $(".main_works").click(function(e){
          e.preventDefault();
          $.blockUI({ 
              message: $("#main-how-popup").html(), 
              css: {
                position: 'absolute',
                top: '0px',
                left: '200px',
                textAlign: 'left',
                border: '0px'
              }
          });
          $(".popuplg-close a").click(function(e){
            e.preventDefault();
            $.unblockUI(); 
            //error.showError("error",$("#main-how-popup").html());
          });
          //error.showError("error",$("#main-how-popup").html());
        });
        
    }
    
};

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    //Set our constants
    how.init();
    
});
/*
 * Accepts a date, a mask, or a date and a mask.
 * Returns a formatted version of the given date.
 * The date defaults to the current date/time.
 * The mask defaults to dateFormat.masks.default.
 */
var timezones = {
	
	init: function() {
   $("#timezone-select").click(function(e){
  		if (e != undefined)
  			e.stopPropagation();
			
			$("#timezone-select-popup").fadeIn(100);
			
			modal.modalIn( timezones.hidepopup );
			
   });
   
   $("#timezone-popup-close").click(function(){
      $("#timezone-select-popup").fadeOut(100);
   });
   
   
   $("#timezone-button").click(function(e) {
     $("#timezone_form").submit();
   });
  
	},
	
  hidepopup : function(e) {
  	$("#timezone-select-popup").fadeOut(100);
		//$('.pops').fadeOut(100);
  }
  
}

$(window).load(function(){
	 if (!window.console) window.console = {};
   if (!window.console.log) window.console.log = function() {};
 
		timezones.init();
   /*DO THIS AT SOME POINT
   $.ajax({
		   type: "POST",
		   url: changeTimezoneUrl,
		   data: "tz_option="+tzId
		 });
	*/
});
function newPurchase(form,screening) {
    //console.log("Submitting Purchase");
    $("#screening_purchase").fadeOut();
    setTop("#process");
    $("#process").fadeIn();
    var vals = form.PurchaseFormToDict();
    if ($("input[name='dohbr']").val() == "true") {
      //console.log("Do HBR");
      theurl = "/screening/"+$("#film").html()+"/purchase/none";
    } else {
      //console.log("Normal Purchase of "+screening);
      theurl = "/screening/"+$("#film").html()+"/purchase/"+screening;
    }
    $.postPurchaseJSON(
      theurl, 
      vals
    );
}

jQuery.postPurchaseJSON = function(url, args, callback) {
    $.ajax({url: url, 
            data: $.param(args), 
            dataType: "text", 
            type: "POST",
            success: function(response) {
             screening_room.purchaseResult(response);
            }, 
            error: function(response) {
             //console.log("ERROR:", response)
            }
    });
};

jQuery.fn.PurchaseFormToDict = function() {
   var fields = this.serializeArray();
    var json = {}
    for (var i = 0; i < fields.length; i++) {
  json[fields[i].name] = fields[i].value;
    }
    if (json.next) delete json.next;
    return json;
}

var screening_room = {
  
  itRetrievedContacts: 0,
  collectedEmails : new Array(),
  inviteSubmitted : false,
  purchaseSubmitted : false,
  totalPrice: 0,
  currentPrice: 0,
  priceDiscount: .10,
  price_threshold: .75,
  total_invites: 0,
  facebook_discount: false,
  gbip: false,
  step_size: 500,
                      
  init: function() {
    
    $("#invite_count").val( 0 );
      
    $("#ticket_discount").html(screening_room.priceDiscount);
    screening_room.totalPrice = $("#ticket_cost").html();
    screening_room.currentPrice = $("#ticket_cost").html();
    //console.log(screening_room.currentPrice);
    
    //console.log("Total Price is "+screening_room.totalPrice);
    
    if ($("#gbip").html() == 1) {
      screening_room.gbip = true;
    }
    
    $("#hbr_request_button").click(function(e){
      e.preventDefault();
      screening_room.hostbyrequest();
      return false;
    });
    
    $("#watch_request_button").click(function(e){
      e.preventDefault();
      screening_room.request();
      return false;
    });
    
    $('.popup-close a').click(function(){
      $("#dohbr").val("false");
    });
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/purchase/)) {
      var obj = window.location.pathname.split("/");
      //console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      screening_room.pay();
      //host_screening.detail();
    } else
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/confirm/)) {
      var obj = window.location.pathname.split("/");
      //console.log("Found "+obj[4]+" in the path");
      $("#screening").html( obj[4] );
      screening_room.confirm();
      //host_screening.detail();
    } else 
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/theater\/(.+)\/detail/)) {
    	var obj = window.location.pathname.split("/");
			if (obj[2] != undefined) {
				console.log("Found "+obj[2]+" in the path");
	      //$("#screening").html( obj[2] );
        screening_room.pay();
      }
    }
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/detail/)) {
    	var obj = window.location.pathname.split("/");
      if (obj[4] != undefined) {
				console.log("Found "+obj[4]+" in the path");
	      $("#screening").html( obj[4] );
      }
      if ($(".screening_link").length > 0) {
        //MODIFIED 07/27/2011
        //screening_room.invite();
        screening_room.pay();
      } else if ($(".purchase_link").length > 0) {
        screening_room.pay();
      }
      //host_screening.detail();
    }
    
    //If we're in the hosting step, show the popup
    if (window.location.pathname.match(/\/request/)) {
      screening_room.hostbyrequest();
      //host_screening.detail();
    }
    
    screening_room.linkInvite();
    screening_room.linkPurchase();
    
  },
  
  linkInvite: function() {
  
    //If we're in the hosting step, show the popup
    //NOTE:: This link is unused if the "upcoming.js" has been triggered
    //Look at "upcoming.js" in the "init()" function for a screening_room.pay(); call.
    $(".screening_link").click( function(e) {
      e.preventDefault();
      if ($('#screening_invite').length > 0) {
        //console.log("Showing Screening Invite");
        $("#screening").html( $(this).attr("title") );
        //MODIFIED 07/27/2011
        //screening_room.invite();
        screening_room.pay();
      } else {
        $("#login_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        $("#signup_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        login.showpopup();
      }
    });
    
  },
  
  linkPurchase: function() {
    
    //If we're in the hosting step, show the popup
    //NOTE:: This link is unused if the "upcoming.js" has been triggered
    //Look at "upcoming.js" in the "init()" function for a screening_room.pay(); call.
    $(".purchase_link").click( function(e) {
      e.preventDefault();
      if ($('#screening_invite').length > 0) {
        //console.log("Showing Screening Invite");
        $("#screening").html( $(this).attr("title") );
        screening_room.pay();
      } else {
        $("#login_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        $("#signup_destination").val($(this).attr('href')+'?currentDate='+$("#current_date").html());
        login.showpopup();
      }
    });
    
  },
  
  sentInvites: function ( count ) {
    screening_room.total_invites = screening_room.total_invites + count;
    $(".count_friends").html(screening_room.total_invites);
    $("#invite_count").val(screening_room.total_invites);
    var dcnt = new Number(screening_room.priceDiscount);
    var aprice = dcnt * count;
    screening_room.applyInviteDiscount();
		screening_room.updatePrice( screening_room.currentPrice - aprice );
  },
  
  updatePrice : function ( price ) {
   var tot = new Number(screening_room.totalPrice);
   var thaprice = tot * screening_room.price_threshold;
   if (screening_room.currentPrice == 0) return;
   if ((price > 0) && (price >= thaprice)) {
   		//console.log("updating price");
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      if (price > 0) {
       $(".ticket_price").html("$"+num.toFixed(2));
      } else {
        screening_room.setScreeningFree();
      }
      $("#ticket_price").val(num.toFixed(2));
    } else if (price < thaprice) {
			screening_room.currentPrice = thaprice;
			var num = new Number(screening_room.currentPrice);
      if (thaprice > 0) {
       $(".ticket_price").html("$"+num.toFixed(2));
      } else {
        screening_room.setScreeningFree();
      }
      $("#ticket_price").val(num.toFixed(2));
		}
  },
  
  applyInviteDiscount: function () {
   if (screening_room.currentPrice == 0) return;
   var tefic = new Number($("#fic").html());
   console.log(tefic);
   if (tefic < 1) return;
   if (screening_room.total_invites >= tefic) {
      console.log("updating total invite discount");
      screening_room.currentPrice = 0;
      var num = new Number(screening_room.currentPrice);
      screening_room.setScreeningFree();
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  applyDiscount: function ( price ) {
   console.log(price);
   if (screening_room.currentPrice == 0) return;
   if (price >= 0) {
      console.log("updating discount");
      screening_room.currentPrice = price;
      var num = new Number(screening_room.currentPrice);
      if (price > 0) {
       $(".ticket_price").html("$"+num.toFixed(2));
      } else {
        screening_room.setScreeningFree();
      }
      $("#ticket_price").val(num.toFixed(2));
    }
  },
  
  setScreeningFree: function () {
  	
    //console.log("Screening is: '"+$("#screening").html()+"'");
    var this_screening = $("#screening").html();
    
		$(".ticket_price").html("FREE");
		$(".purchase_next").hide();
    $(".cc_items").hide();
    var node = '<input id="purchase_submit_free" type="image" src="/images/alt1/purchase_final.png" value="get ticket" />';
    if ($(".step_one_purchase").length > 0) {
      $("#purchase_submit").remove();
      $(".step_one_purchase").append(node);
    } else if ($(".cc-submit").length > 0) {
		  $("#purchase_submit").remove();
		  $(".cc-submit").append(node);
		}    
    
		$("#purchase_submit_free").click(function(){
      //console.log("Purchase Click of "+this_screening);
      if (! screening_room.purchaseSubmitted) {
        if (screening_room.validatePurchase()) {
          screening_room.purchaseSubmitted = true;
          newPurchase($("#purchase_form"),this_screening);

        }
        //$("#purchase_form").submit();
      } else {
        //console.log("Already Submitted");
      }
    });
	},
	
  updateCount : function () {
    //IC are previously sent Invites
    var ic = $("#invite_count").val();
    $("#number_invites").html( parseInt(ic) + parseInt($('#accepted-invites-container li').length) );
  },
  
  geoblock : function() {
   	error.showError("error","Viewing this film is restricted.","We are not allowed to play this film in your geographical area.",0);
  },
  
  showErrors : function( step ) {
    if (! $("#" + step + ' .error-panel').is(':visible')) {
      $("#" + step + ' .error-panel').animate({
        width: 'toggle'
      }, 500);
    }
  },
  
  goPaypal : function() {
    //console.log($("#screening").html());
    //console.log($("#film").html());
    console.log("go paypal!");
    if ((($("#screening").html() != "") || ($("#dohbr").val() == "true")) && ($("#film").html() != "")) {
      var theurl = $("#ticket_price").val();
      var theprice = theurl.replace(".","_");
      if ($("#dohbr").val() == "true") {
        screening = "hbr";
      } else {
        screening = $("#screening").html();
      }
      window.location.href = "/services/Paypal/express/screening?vars="+screening+"-"+$("#film").html()+"-"+theprice;
    }
  },
  
  pay : function() {
    
		if ($("#userid").html() == "0") {
    	if (window.location.pathname.match(/\/theater/)) {
      	$("#login_destination").val(window.location.href.replace("#","")+"/detail");
				$("#signup_destination").val(window.location.href.replace("#","")+"/detail");
      	$("#login_fb_link").attr("href","/services/Facebook/login?dest="+window.location.href.replace("#","")+"/detail");
      } else {
				$("#login_destination").val(window.location.href.replace("#","")+"/"+$("#screening").html()+"/detail");
				$("#signup_destination").val(window.location.href.replace("#","")+"/"+$("#screening").html()+"/detail");
      	$("#login_fb_link").attr("href","/services/Facebook/login?dest="+window.location.href.replace("#","")+"/"+$("#screening").html()+"/detail");
			}
      login.showpopup();
      return;
    }
    
    /*
    if ($("#screening_invite").length == 0) {
      login.showpopup();
      return;
    }
    */
    
    $(".special_offer").fadeIn();
    $(".purchase_form_coupon").fadeIn();
    
    //If we've already detected the bandwidth, and it's too low...
    if (typeof bandwidth != undefined) {
      if ((bandwidth.bandwidth > -1) && (bandwidth.bandwidth < bandwidth.threshold)){
        $(".current_bandwidth").html(bandwidth.bandwidth);
        $(".bandwidth_warning").fadeIn(100);
      }
    }
    
    //Note, this was moved from the #invite() feature
    //And should be moved back when invite is re-integrated
    if (screening_room.gbip) {
      screening_room.geoblock();
      return false;
    }
    
    $("#facebook_share").click(function(e){
      e.preventDefault();
      screening_room.facebookShare();
    });
    
    if ($("select.cc-country-drop").val() != "US") {
      screening_room.swapState();
    }
    
    $("select.cc-country-drop").change(function () {
      screening_room.swapState();
    });
    
    //console.log("Payment Step");
    $("#process").fadeOut();
    
    $('#fld-code').removeAttr("disabled");
    $("#promo_code").val("")
    $("#enter-code").show();
    
    //console.log("Screening is: '"+$("#screening").html()+"'");
    var this_screening = $("#screening").html();
    
    if ($("#dohbr").val() == "true") {
      $(".current_screening_time").html("Now");
    } else {
      $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
    }
    
    //console.log("Host is: '"+$("#host_"+$("#screening").html()).html()+"'");
    if ($("#dohbr").val() == "true") {
      $(".current_screening_host").html("You");
    } else if ($("#host_"+$("#screening").html()).html() != '') {
      $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    } else {
      $(".current_screening_hb").hide();
    }
    
    $(".paypal_icon").click(function(e){
      e.preventDefault();
      screening_room.goPaypal();
    });
    
    $("#purchase_submit").click(function(e){
      //console.log("Purchase Click of "+this_screening);
      if (! screening_room.purchaseSubmitted) {
      	if (screening_room.validatePurchase()) {
          screening_room.purchaseSubmitted = true;
          newPurchase($("#purchase_form"),this_screening);
        }
        //$("#purchase_form").submit();
      } else {
        //console.log("Already Submitted");
      }
    });
    
    if ($(".purchase_next").length > 0) {
      $(".purchase_next").click(function(){
        if (screening_room.validatePurchaseOne()) {
        $(".step_one").animate({
          left: '-='+screening_room.step_size
        }, 100, 'linear', function() {
          // Animation complete.
        });
        $(".step_two").animate({
          left: '-='+screening_room.step_size
        }, 100, 'linear', function() {
          // Animation complete.
        });
        }
      });
      
      $(".purchase_last").click(function(){
        $(".step_one").animate({
          left: '+='+screening_room.step_size
        }, 100, 'linear', function() {
          // Animation complete.
        });
        
        $(".step_two").animate({
          left: '+='+screening_room.step_size
        }, 100, 'linear', function() {
          // Animation complete.
        });
      });
    }
    
    //setTop("#screening_purchase");
    $(".pop_up").fadeOut();
    $(".pre_content").fadeOut();
    $(".prescreen_notice").fadeOut();
    $(".prescreen_purchase").fadeOut();
    
    $("#screening_purchase").fadeIn();
    
    $('#btn-ticket-code').click(function(e){
      e.preventDefault();
      if ($("#ticket-code").val() == "false") {
        $("#all-payment-info").fadeOut("slow").queue(function() {
          $("#ticket-code").val("code");
          $("#fieldsets-ticket-code").fadeIn("slow");
          $('#all-payment-info').clearQueue();
         });
      } else if ($("#ticket-code").val() == "code") {
         $("#fieldsets-ticket-code").fadeOut("slow").queue(function() {
          $("#ticket-code").val("false");
          $("#all-payment-info").fadeIn("slow");
          $('#fieldsets-ticket-code').clearQueue();
         });
      }
    });
    
    $('#btn-ticket-code-next').click(function(e){
      e.preventDefault();
      if (screening_room.validateTicket()) {
        $("#step-redeem-form").submit();
      }
    });
    
    // code for z-index
    $('#footer').fadeOut();
    $('#chat_panel').fadeOut();
  },
  
  validateTicket: function() {
    var err = 0;
    if ($("#fld-ticket_code").val() == '') { $("#fld-ticket_code").css('background','#ff6666'); err++; } else { $("#fld-ticket_code").css('background','#A5D0ED'); }
    if ($("#fld-email_recipient").val() == '') { $("#fld-email_recipient").css('background','#ff6666'); err++; } else { $("#fld-email_recipient").css('background','#A5D0ED'); }
    
    if (err > 0) {
      error.showError("error","Your ticket information is invalid.");
      return false;
    }
      return true;
  },
  
  validatePurchase: function() {
  	
    if ($("#film_free_screening").val() == 1) {
			return true;
		}
		var err = 0;
    var price = $("#ticket_price").val();
    if ($("#fld-cc_first_name").val() == '') { $("#fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#fld-cc_last_name").val() == '') { $("#fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#fld-cc_last_name").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_email").val())) { $("#fld-cc_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) { $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (screening_room.isValidEmailAddress($("#fld-cc_email").val()) && screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) {
      if ($("#fld-cc_email").val() != $("#fld-cc_confirm_email").val()) { $("#fld-cc_email").css('background','#ff6666'); $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#fld-cc_city").val() == '') { $("#fld-cc_city").css('background','#ff6666'); err++; } else { $("#fld-cc_city").css('background','#A5D0ED'); }
    //console.log("PRICE SHOULD SKIP!" + price );
    if (price != 0) {
      //console.log("PRICE DIDN'T SKIP!" + price );
      if ($("#fld-cc_number").val() == '') { $("#fld-cc_number").css('background','#ff6666'); err++; } else { $("#fld-cc_number").css('background','#A5D0ED'); }
      if ($("#fld-cc_security_number").val() == '') { $("#fld-cc_security_number").css('background','#ff6666'); err++; } else { $("#fld-cc_security_number").css('background','#A5D0ED'); }
      if ($("#fld-cc_address1").val() == '') { $("#fld-cc_address1").css('background','#ff6666'); err++; } else { $("#fld-cc_address1").css('background','#A5D0ED'); }
      if ($("#fld-cc_zip").val() == '') { $("#fld-cc_zip").css('background','#ff6666'); err++; } else { $("#fld-cc_zip").css('background','#A5D0ED'); }
    }
    console.log(err);
    if (err > 0) {
      console.log("price: " + price);
      if(price == 0) {
        error.showError("error","Please complete the required fields.");
      }
      else {
        error.showError("error","Your payment information is invalid.");
      }
      return false;
    }
      return true;
  },
  
  validatePurchaseOne: function() {
    var err = 0;
    var price = $("#ticket_price").val();
    if ($("#fld-cc_first_name").val() == '') { $("#fld-cc_first_name").css('background','#ff6666'); err++; } else { $("#fld-cc_first_name").css('background','#A5D0ED'); }
    if ($("#fld-cc_last_name").val() == '') { $("#fld-cc_last_name").css('background','#ff6666'); err++; } else { $("#fld-cc_last_name").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_email").val())) { $("#fld-cc_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); }
    if (! screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) { $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    if (screening_room.isValidEmailAddress($("#fld-cc_email").val()) && screening_room.isValidEmailAddress($("#fld-cc_confirm_email").val())) {
      if ($("#fld-cc_email").val() != $("#fld-cc_confirm_email").val()) { $("#fld-cc_email").css('background','#ff6666'); $("#fld-cc_confirm_email").css('background','#ff6666'); err++; } else { $("#fld-cc_email").css('background','#A5D0ED'); $("#fld-cc_confirm_email").css('background','#A5D0ED'); }
    }
    if ($("#fld-cc_address1").val() == '') { $("#fld-cc_address1").css('background','#ff6666'); err++; } else { $("#fld-cc_address1").css('background','#A5D0ED'); }
    if ($("#fld-cc_city").val() == '') { $("#fld-cc_city").css('background','#ff6666'); err++; } else { $("#fld-cc_city").css('background','#A5D0ED'); }
    if (err > 0) {
      console.log("price: " + price);
      if(price == 0) {
        error.showError("error","Please complete the required fields.");
      }
      else {
        error.showError("error","Your payment information is invalid.");
      }
      return false;
    }
      return true;
  },
  
  purchaseResult: function (response) {
    //console.log("Purchase Result");
    var response = eval("(" + response + ")");
    if (response.purchaseResponse.status == "success") {
      $("#host-accepted-invites-container li").remove();
      $("#invite_count").val( 0 );
      $("#screening").html(response.purchaseResponse.screening);
      $("#purchase_result").html(response.purchaseResponse.result);
      // $("#purchase_result").html(response.purchaseResponse.result);
      screening_room.confirm();
    } else {
      error.showError("error",response.purchaseResponse.result,response.purchaseResponse.message);
      screening_room.purchaseSubmitted = false;
      screening_room.pay();
    }
  },
  
  code : function() {
  
    ////console.log("Doing the Code");
    /* ENTER CODE */
    $('#enter-code').click(function(e){ 
      e.preventDefault();
      screening_room.sendCode(e); 
    });
    /* END ENTER CODE */
    
  },
  
  sendCode : function(e) {
    
    ////console.log("Sending Code");
    if($('#fld-code').val() == '')
    {       
      error.showError("error","Your code is empty. Please enter a code");
      return false;
    }
    
    var args = {'ticket': $('#fld-code').val(),
                'film': $('#film').html(),
                'screening': $('#screening').html()}
    $.ajax({
      url: '/services/Exchange', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
        if (response.exchangeResponse.result == "promo") {
          if ($("#promo_code").val() == '') {
            $("#promo_code").val($('#fld-code').val());
            $("#fld-code").attr("disabled","disabled");
            $("#enter-code").hide();
            if (response.exchangeResponse.type == 1) {
              screening_room.applyDiscount( screening_room.currentPrice * (1 - (response.exchangeResponse.discount/100) ) );
            } else if (response.exchangeResponse.type == 2) {
              //console.log("Current Price:" + screening_room.currentPrice);
              //console.log("Discount Price:" + response.exchangeResponse.discount);
              //console.log("New Price:" + screening_room.currentPrice - response.exchangeResponse.discount);
              screening_room.applyDiscount( screening_room.currentPrice - response.exchangeResponse.discount );
            }
            error.showError("error",response.exchangeResponse.message);
          } else {
            error.showError("error","You've already used a discount code.");
          }
        }else if (response.exchangeResponse.result == "success") {
          $("#screening").html(response.exchangeResponse.theurl);
          screening_room.confirm();
        } else {
          error.showError("error",response.exchangeResponse.message);
        }
      }, error: function(response) {
          ////console.log("ERROR:", response);
          error.showError("error",response.exchangeResponse.message);
      }
    });
  },
  
  isValidEmailAddress: function (emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
  },
  
  //HBR involves someone hosting their own show to see a screening immediately
  //So we need to clear the "Screening" Value
  hostbyrequest: function() {
    
    //console.log("HBR!");
    //Since we HBR, there is no time or host
    $("#screening").html("");
    
    if ($('#screening_purchase').length == 0) {
      var theloc = window.location.href;
			$("#login_destination").val(theloc.replace(/#p=(\d)+/,"")+"/request");
      $("#signup_destination").val(theloc.replace(/#p=(\d)+/,"")+"/request");
      $("#login_fb_link").attr('href',$("#login_fb_link").attr('href')+'/request');
      $("#login_twitter_link").attr('href',$("#login_twitter_link").attr('href')+'/request');
      
      login.showpopup();
      return;
    }
    
    modal.modalIn( screening_room.hideWindow );
    
    $("#dohbr").val("true");
    $(".current_screening_time").html("Now");
    $(".current_screening_host").html("You");
    
		screening_room.updatePrice( $("#dohbr_ticket_price").html() );
    
    setTop("#screening_purchase");
    $("#screening_purchase").fadeIn();
    
    //console.log($("#screening").html());
    
    screening_room.pay();
    
  },
  
  request : function() {
    
    $("#dohbr").val("true");
    
    $(".current_screening_time").html("Now");
    $(".current_screening_host").html("You");
    
    setTop("#watch_by_request");
    $("#watch_by_request").fadeIn();
    
    ////console.log("Doing the Code");
    /* ENTER CODE */
    $('#watch-enter-code').click(function(e){ 
      e.preventDefault();
      screening_room.sendWBR(e); 
    });
    /* END ENTER CODE */
    
  },
  
  //WBR involves someone using a "CODE" to see a screening immediately
  sendWBR : function(e) {
    
    ////console.log("Sending Code");
    if($('#watch-fld-code').val() == '')
    {       
      error.showError("error","Your code is empty. Please enter a code");
      return false;
    }
    
    $("#watch_by_request").fadeOut();
    setTop("#process");
    $("#process").fadeIn();
    
    var args = {'ticket': $('#watch-fld-code').val(),
                'screening': $('#screening').html(),
                'film': $('#film').html()}
    $.ajax({
      url: '/services/Code', 
      data: $.param(args), 
      type: "POST", 
      cache: false, 
      dataType: "json", 
      success: function(response) {
        if (response.codeResponse.result == "success") {
          $("#screening").html(response.codeResponse.theurl);
          screening_room.confirmWBR();
        } else {
          error.showError("error",response.codeResponse.message);
        }
      }, error: function(response) {
          ////console.log("ERROR:", response);
          error.showError("error",response.codeResponse.message);
      }
    });
  },
  
  confirm : function() {
    console.log("Purchse Confirm 2");
    
    $("#screening_full_link").html('/theater/'+$("#screening").html());
    $("#screening_click_link").attr('href','/theater/'+$("#screening").html());
    if ($("#dohbr").val() == "true") {
      $(".current_screening_time").html("Now");
      $(".current_screening_host").html("You");
    } else {
      $(".current_screening_time").html($("#time_"+$("#screening").html()).html());
      $(".current_screening_host").html($("#host_"+$("#screening").html()).html());
    }
    $('#screening_click_link').click(function(e){
      e.preventDefault();
      window.location.href = '/theater/'+$("#screening").html();
    });
    screening_room.purchaseSubmitted = false;
    $("#process").fadeOut("slow").queue(function() {
      setTop("#confirm");
      $("#confirm").fadeIn("slow");
      $("#process").clearQueue();
    });
    $('.pre_host_message').delay(10000).fadeOut();
    
    console.log("Confirm Redirect to "+'/theater/'+$("#screening").html());
    window.location = '/forward?dest='+$("#screening").html();
    //window.location = '/theater/'+$("#screening").html();
  },
  
  confirmWBR : function() {
    console.log("Purchse WBR Confirm");
    $("#wbr_screening_full_link").html('/forward?dest='+$("#screening").html());
    $("#wbr_screening_click_link").attr('href','/forward?dest='+$("#screening").html());
    $(".current_screening_time").html($("#time_"+$("#screening").html()));
    $('#wbr_screening_click_link').click(function(e){
      e.preventDefault();
      window.location = '/forward?dest='+$("#screening").html();
    });
    screening_room.purchaseSubmitted = false;
    $("#process").fadeOut("slow").queue(function() {
      setTop("#watch_by_request_confirm");
      $("#watch_by_request_confirm").fadeIn("slow");
      $("#process").clearQueue();
    });
    console.log("Confirm WBR Redirect to "+'/theater/'+$("#screening").html());
    window.location = '/forward?dest='+$("#screening").html();
    //window.location.href = '/forward?dest='+$("#screening").html();
  },
  
  facebookShare : function () {
    window.open("/facebook/"+$("#film").html(), "facebookShare", "width=600,height=300,scrollbars=yes");
  }, 
  
  facebookDiscount: function() {
    
    console.log("Applying Discount");
    if (! screening_room.facebook_discount) {
      console.log("Applying Discount Really");
      screening_room.facebook_discount = true;
      screening_room.applyDiscount( screening_room.currentPrice - 1 );
      $('#facebook_share').attr('checked','checked');
    }
  },
  
  remoteDiscount : function() {
    console.log("Remove Discount");

    if(window.opener.screening_room){
      window.opener.screening_room.facebookDiscount();
    } else {
      window.opener.onFacebookDiscount();
    }

    window.close();
  },
  
  swapState : function() {
    var code = $("select.cc-country-drop").val();
    var country = $("option:selected", this).text();
    
    if(code == "US") {
      $("select.cc-state-drop").show();
      $("input.cc-state-text").hide();
    } else {
      $("select.cc-state-drop").hide();
      $("input.cc-state-text").show();
      $("input.cc-state-text").val(country);
    }
  },
  
  hideWindow : function() {
  	$(".pops").fadeOut(100);
  }
  
}

$(document).ready(function(){
  if (!window.console) window.console = {};
  if (!window.console.log) window.console.log = function() {};
  
  screening_room.init();
  screening_room.code();
  
});
(function ($) {
		
		var daysInWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
		var shortMonthsInYear = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
		var longMonthsInYear = ["January", "February", "March", "April", "May", "June", 
														"July", "August", "September", "October", "November", "December"];
		var shortMonthsToNumber = [];
		shortMonthsToNumber["Jan"] = "01";
		shortMonthsToNumber["Feb"] = "02";
		shortMonthsToNumber["Mar"] = "03";
		shortMonthsToNumber["Apr"] = "04";
		shortMonthsToNumber["May"] = "05";
		shortMonthsToNumber["Jun"] = "06";
		shortMonthsToNumber["Jul"] = "07";
		shortMonthsToNumber["Aug"] = "08";
		shortMonthsToNumber["Sep"] = "09";
		shortMonthsToNumber["Oct"] = "10";
		shortMonthsToNumber["Nov"] = "11";
		shortMonthsToNumber["Dec"] = "12";
	
    $.format = (function () {
        function strDay(value) {
 						return daysInWeek[parseInt(value, 10)] || value;
        }

        function strMonth(value) {
						var monthArrayIndex = parseInt(value, 10) - 1;
 						return shortMonthsInYear[monthArrayIndex] || value;
        }

        function strLongMonth(value) {
					var monthArrayIndex = parseInt(value, 10) - 1;
					return longMonthsInYear[monthArrayIndex] || value;					
        }

        var parseMonth = function (value) {
					return shortMonthsToNumber[value] || value;
        };

        var parseTime = function (value) {
                var retValue = value;
                var millis = "";
                if (retValue.indexOf(".") !== -1) {
                    var delimited = retValue.split('.');
                    retValue = delimited[0];
                    millis = delimited[1];
                }

                var values3 = retValue.split(":");

                if (values3.length === 3) {
                    hour = values3[0];
                    minute = values3[1];
                    second = values3[2];

                    return {
                        time: retValue,
                        hour: hour,
                        minute: minute,
                        second: second,
                        millis: millis
                    };
                } else {
                    return {
                        time: "",
                        hour: "",
                        minute: "",
                        second: "",
                        millis: ""
                    };
                }
            };

        return {
            date: function (value, format) {
                /* 
					value = new java.util.Date()
                 	2009-12-18 10:54:50.546 
				*/
                try {
                    var date = null;
                    var year = null;
                    var month = null;
                    var dayOfMonth = null;
                    var dayOfWeek = null;
                    var time = null;
                    if (typeof value.getFullYear === "function") {
                        year = value.getFullYear();
                        month = value.getMonth() + 1;
                        dayOfMonth = value.getDate();
                        dayOfWeek = value.getDay();
                        time = parseTime(value.toTimeString());
										} else if (value.search(/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.?\d{0,3}[-+]?\d{2}:\d{2}/) != -1) { /* 2009-04-19T16:11:05+02:00 */
                        var values = value.split(/[T\+-]/);
                        year = values[0];
                        month = values[1];
                        dayOfMonth = values[2];
                        time = parseTime(values[3].split(".")[0]);
                        date = new Date(year, month - 1, dayOfMonth);
                        dayOfWeek = date.getDay();
                    } else {
                        var values = value.split(" ");
                        switch (values.length) {
                        case 6:
                            /* Wed Jan 13 10:43:41 CET 2010 */
                            year = values[5];
                            month = parseMonth(values[1]);
                            dayOfMonth = values[2];
                            time = parseTime(values[3]);
                            date = new Date(year, month - 1, dayOfMonth);
                            dayOfWeek = date.getDay();
                            break;
                        case 2:
                            /* 2009-12-18 10:54:50.546 */
                            var values2 = values[0].split("-");
                            year = values2[0];
                            month = values2[1];
                            dayOfMonth = values2[2];
                            time = parseTime(values[1]);
                            date = new Date(year, month - 1, dayOfMonth);
                            dayOfWeek = date.getDay();
                            break;
                        case 7:
                            /* Tue Mar 01 2011 12:01:42 GMT-0800 (PST) */
                        case 9:
                            /*added by Larry, for Fri Apr 08 2011 00:00:00 GMT+0800 (China Standard Time) */
                        case 10:
                            /* added by Larry, for Fri Apr 08 2011 00:00:00 GMT+0200 (W. Europe Daylight Time) */
                            year = values[3];
                            month = parseMonth(values[1]);
                            dayOfMonth = values[2];
                            time = parseTime(values[4]);
                            date = new Date(year, month - 1, dayOfMonth);
                            dayOfWeek = date.getDay();
                            break;
                        default:
                            return value;
                        }
                    }

                    var pattern = "";
                    var retValue = "";
                    /*
						Issue 1 - variable scope issue in format.date 
                    	Thanks jakemonO
					*/
                    for (var i = 0; i < format.length; i++) {
                        var currentPattern = format.charAt(i);
                        pattern += currentPattern;
                        switch (pattern) {
                        case "ddd":
                            retValue += strDay(dayOfWeek);
                            pattern = "";
                            break;
                        case "dd":
                            if (format.charAt(i + 1) == "d") {
                                break;
                            }
                            if (String(dayOfMonth).length === 1) {
                                dayOfMonth = '0' + dayOfMonth;
                            }
                            retValue += dayOfMonth;
                            pattern = "";
                            break;
                        case "MMMM":
                            retValue += strLongMonth(month);
                            pattern = "";
                            break;
                        case "MMM":
                            if (format.charAt(i + 1) === "M") {
                                break;
                            }
                            retValue += strMonth(month);
                            pattern = "";
                            break;
                        case "MM":
                            if (format.charAt(i + 1) == "M") {
                                break;
                            }
                            if (String(month).length === 1) {
                                month = '0' + month;
                            }
                            retValue += month;
                            pattern = "";
                            break;
                        case "yyyy":
                            retValue += year;
                            pattern = "";
                            break;
                        case "yy":
                            if (format.charAt(i + 1) == "y" &&
                           	format.charAt(i + 2) == "y") {
                            	break;
                      	    }
                            retValue += String(year).slice(-2);
                            pattern = "";
                            break;
                        case "HH":
                            retValue += time.hour;
                            pattern = "";
                            break;
                        case "hh":
                            /* time.hour is "00" as string == is used instead of === */
                            var hour = (time.hour == 0 ? 12 : time.hour < 13 ? time.hour : time.hour - 12);
                            hour = String(hour).length == 1 ? '0'+hour : hour;
                            retValue += hour;
                            pattern = "";
                            break;
                        case "mm":
                            retValue += time.minute;
                            pattern = "";
                            break;
                        case "ss":
                            /* ensure only seconds are added to the return string */
                            retValue += time.second.substring(0, 2);
                            pattern = "";
                            break;
                        case "SSS":
                            retValue += time.millis.substring(0, 3);
                            pattern = "";
                            break;
                        case "a":
                            retValue += time.hour >= 12 ? "PM" : "AM";
                            pattern = "";
                            break;
                        case " ":
                            retValue += currentPattern;
                            pattern = "";
                            break;
                        case "/":
                            retValue += currentPattern;
                            pattern = "";
                            break;
                        case ":":
                            retValue += currentPattern;
                            pattern = "";
                            break;
                        default:
                            if (pattern.length === 2 && pattern.indexOf("y") !== 0 && pattern != "SS") {
                                retValue += pattern.substring(0, 1);
                                pattern = pattern.substring(1, 2);
                            } else if ((pattern.length === 3 && pattern.indexOf("yyy") === -1)) {
                                pattern = "";
                            }
                        }
                    }
                    return retValue;
                } catch (e) {
                    console.log(e);
                    return value;
                }
            }
        };
    }());
}(jQuery));


$(document).ready(function () {
    $(".shortDateFormat").each(function (idx, elem) {
        if ($(elem).is(":input")) {
            $(elem).val($.format.date($(elem).val(), "dd/MM/yyyy"));
        } else {
            $(elem).text($.format.date($(elem).text(), "dd/MM/yyyy"));
        }
    });
    $(".longDateFormat").each(function (idx, elem) {
        if ($(elem).is(":input")) {
            $(elem).val($.format.date($(elem).val(), "dd/MM/yyyy hh:mm:ss"));
        } else {
            $(elem).text($.format.date($(elem).text(), "dd/MM/yyyy hh:mm:ss"));
        }
    });
});
if(typeof jwplayer=="undefined"){var jwplayer=function(a){if(jwplayer.api){return jwplayer.api.selectPlayer(a)}};var $jw=jwplayer;jwplayer.version="5.7.1896 (Licensed version)";jwplayer.vid=document.createElement("video");jwplayer.audio=document.createElement("audio");jwplayer.source=document.createElement("source");(function(b){b.utils=function(){};b.utils.typeOf=function(d){var c=typeof d;if(c==="object"){if(d){if(d instanceof Array){c="array"}}else{c="null"}}return c};b.utils.extend=function(){var c=b.utils.extend["arguments"];if(c.length>1){for(var e=1;e<c.length;e++){for(var d in c[e]){c[0][d]=c[e][d]}}return c[0]}return null};b.utils.clone=function(f){var c;var d=b.utils.clone["arguments"];if(d.length==1){switch(b.utils.typeOf(d[0])){case"object":c={};for(var e in d[0]){c[e]=b.utils.clone(d[0][e])}break;case"array":c=[];for(var e in d[0]){c[e]=b.utils.clone(d[0][e])}break;default:return d[0];break}}return c};b.utils.extension=function(c){if(!c){return""}c=c.substring(c.lastIndexOf("/")+1,c.length);c=c.split("?")[0];if(c.lastIndexOf(".")>-1){return c.substr(c.lastIndexOf(".")+1,c.length).toLowerCase()}return};b.utils.html=function(c,d){c.innerHTML=d};b.utils.wrap=function(c,d){if(c.parentNode){c.parentNode.replaceChild(d,c)}d.appendChild(c)};b.utils.ajax=function(g,f,c){var e;if(window.XMLHttpRequest){e=new XMLHttpRequest()}else{e=new ActiveXObject("Microsoft.XMLHTTP")}e.onreadystatechange=function(){if(e.readyState===4){if(e.status===200){if(f){f(e)}}else{if(c){c(g)}}}};try{e.open("GET",g,true);e.send(null)}catch(d){if(c){c(g)}}return e};b.utils.load=function(d,e,c){d.onreadystatechange=function(){if(d.readyState===4){if(d.status===200){if(e){e()}}else{if(c){c()}}}}};b.utils.find=function(d,c){return d.getElementsByTagName(c)};b.utils.append=function(c,d){c.appendChild(d)};b.utils.isIE=function(){return((!+"\v1")||(typeof window.ActiveXObject!="undefined"))};b.utils.isLegacyAndroid=function(){var c=navigator.userAgent.toLowerCase();return(c.match(/android 2.[012]/i)!==null)};b.utils.isIOS=function(d){if(typeof d=="undefined"){d=/iP(hone|ad|od)/i}var c=navigator.userAgent.toLowerCase();return(c.match(d)!==null)};b.utils.isIPad=function(){return b.utils.isIOS(/iPad/i)};b.utils.isIPod=function(){return b.utils.isIOS(/iP(hone|od)/i)};b.utils.getFirstPlaylistItemFromConfig=function(c){var d={};var e;if(c.playlist&&c.playlist.length){e=c.playlist[0]}else{e=c}d.file=e.file;d.levels=e.levels;d.streamer=e.streamer;d.playlistfile=e.playlistfile;d.provider=e.provider;if(!d.provider){if(d.file&&(d.file.toLowerCase().indexOf("youtube.com")>-1||d.file.toLowerCase().indexOf("youtu.be")>-1)){d.provider="youtube"}if(d.streamer&&d.streamer.toLowerCase().indexOf("rtmp://")==0){d.provider="rtmp"}if(e.type){d.provider=e.type.toLowerCase()}}if(d.provider=="audio"){d.provider="sound"}return d};b.utils.getOuterHTML=function(c){if(c.outerHTML){return c.outerHTML}else{try{return new XMLSerializer().serializeToString(c)}catch(d){return""}}};b.utils.setOuterHTML=function(f,e){if(f.outerHTML){f.outerHTML=e}else{var g=document.createElement("div");g.innerHTML=e;var c=document.createRange();c.selectNodeContents(g);var d=c.extractContents();f.parentNode.insertBefore(d,f);f.parentNode.removeChild(f)}};b.utils.hasFlash=function(){if(typeof navigator.plugins!="undefined"&&typeof navigator.plugins["Shockwave Flash"]!="undefined"){return true}if(typeof window.ActiveXObject!="undefined"){try{new ActiveXObject("ShockwaveFlash.ShockwaveFlash");return true}catch(c){}}return false};b.utils.getPluginName=function(c){if(c.lastIndexOf("/")>=0){c=c.substring(c.lastIndexOf("/")+1,c.length)}if(c.lastIndexOf("-")>=0){c=c.substring(0,c.lastIndexOf("-"))}if(c.lastIndexOf(".swf")>=0){c=c.substring(0,c.lastIndexOf(".swf"))}if(c.lastIndexOf(".js")>=0){c=c.substring(0,c.lastIndexOf(".js"))}return c};b.utils.getPluginVersion=function(c){if(c.lastIndexOf("-")>=0){if(c.lastIndexOf(".js")>=0){return c.substring(c.lastIndexOf("-")+1,c.lastIndexOf(".js"))}else{if(c.lastIndexOf(".swf")>=0){return c.substring(c.lastIndexOf("-")+1,c.lastIndexOf(".swf"))}else{return c.substring(c.lastIndexOf("-")+1)}}}return""};b.utils.getAbsolutePath=function(j,h){if(!b.utils.exists(h)){h=document.location.href}if(!b.utils.exists(j)){return undefined}if(a(j)){return j}var k=h.substring(0,h.indexOf("://")+3);var g=h.substring(k.length,h.indexOf("/",k.length+1));var d;if(j.indexOf("/")===0){d=j.split("/")}else{var e=h.split("?")[0];e=e.substring(k.length+g.length+1,e.lastIndexOf("/"));d=e.split("/").concat(j.split("/"))}var c=[];for(var f=0;f<d.length;f++){if(!d[f]||!b.utils.exists(d[f])||d[f]=="."){continue}else{if(d[f]==".."){c.pop()}else{c.push(d[f])}}}return k+g+"/"+c.join("/")};function a(d){if(!b.utils.exists(d)){return}var e=d.indexOf("://");var c=d.indexOf("?");return(e>0&&(c<0||(c>e)))}b.utils.pluginPathType={ABSOLUTE:"ABSOLUTE",RELATIVE:"RELATIVE",CDN:"CDN"};b.utils.getPluginPathType=function(d){if(typeof d!="string"){return}d=d.split("?")[0];var e=d.indexOf("://");if(e>0){return b.utils.pluginPathType.ABSOLUTE}var c=d.indexOf("/");var f=b.utils.extension(d);if(e<0&&c<0&&(!f||!isNaN(f))){return b.utils.pluginPathType.CDN}return b.utils.pluginPathType.RELATIVE};b.utils.mapEmpty=function(c){for(var d in c){return false}return true};b.utils.mapLength=function(d){var c=0;for(var e in d){c++}return c};b.utils.log=function(d,c){if(typeof console!="undefined"&&typeof console.log!="undefined"){if(c){console.log(d,c)}else{console.log(d)}}};b.utils.css=function(d,g,c){if(b.utils.exists(d)){for(var e in g){try{if(typeof g[e]==="undefined"){continue}else{if(typeof g[e]=="number"&&!(e=="zIndex"||e=="opacity")){if(isNaN(g[e])){continue}if(e.match(/color/i)){g[e]="#"+b.utils.strings.pad(g[e].toString(16),6)}else{g[e]=Math.ceil(g[e])+"px"}}}d.style[e]=g[e]}catch(f){}}}};b.utils.isYouTube=function(c){return(c.indexOf("youtube.com")>-1||c.indexOf("youtu.be")>-1)};b.utils.transform=function(c,d){c.style.webkitTransform=d;c.style.MozTransform=d;c.style.OTransform=d};b.utils.stretch=function(h,n,m,f,l,g){if(typeof m=="undefined"||typeof f=="undefined"||typeof l=="undefined"||typeof g=="undefined"){return}var d=m/l;var e=f/g;var k=0;var j=0;n.style.overflow="hidden";b.utils.transform(n,"");var c={};switch(h.toUpperCase()){case b.utils.stretching.NONE:c.width=l;c.height=g;break;case b.utils.stretching.UNIFORM:if(d>e){c.width=l*e;c.height=g*e}else{c.width=l*d;c.height=g*d}break;case b.utils.stretching.FILL:if(d>e){c.width=l*d;c.height=g*d}else{c.width=l*e;c.height=g*e}break;case b.utils.stretching.EXACTFIT:b.utils.transform(n,["scale(",d,",",e,")"," translate(0px,0px)"].join(""));c.width=l;c.height=g;break;default:break}c.top=(f-c.height)/2;c.left=(m-c.width)/2;b.utils.css(n,c)};b.utils.stretching={NONE:"NONE",FILL:"FILL",UNIFORM:"UNIFORM",EXACTFIT:"EXACTFIT"};b.utils.deepReplaceKeyName=function(h,e,c){switch(b.utils.typeOf(h)){case"array":for(var g=0;g<h.length;g++){h[g]=b.utils.deepReplaceKeyName(h[g],e,c)}break;case"object":for(var f in h){var d=f.replace(new RegExp(e,"g"),c);h[d]=b.utils.deepReplaceKeyName(h[f],e,c);if(f!=d){delete h[f]}}break}return h};b.utils.isInArray=function(e,d){if(!(e)||!(e instanceof Array)){return false}for(var c=0;c<e.length;c++){if(d===e[c]){return true}}return false};b.utils.exists=function(c){switch(typeof(c)){case"string":return(c.length>0);break;case"object":return(c!==null);case"undefined":return false}return true};b.utils.empty=function(c){if(typeof c.hasChildNodes=="function"){while(c.hasChildNodes()){c.removeChild(c.firstChild)}}};b.utils.parseDimension=function(c){if(typeof c=="string"){if(c===""){return 0}else{if(c.lastIndexOf("%")>-1){return c}else{return parseInt(c.replace("px",""),10)}}}return c};b.utils.getDimensions=function(c){if(c&&c.style){return{x:b.utils.parseDimension(c.style.left),y:b.utils.parseDimension(c.style.top),width:b.utils.parseDimension(c.style.width),height:b.utils.parseDimension(c.style.height)}}else{return{}}};b.utils.timeFormat=function(c){str="00:00";if(c>0){str=Math.floor(c/60)<10?"0"+Math.floor(c/60)+":":Math.floor(c/60)+":";str+=Math.floor(c%60)<10?"0"+Math.floor(c%60):Math.floor(c%60)}return str}})(jwplayer);(function(a){a.events=function(){};a.events.COMPLETE="COMPLETE";a.events.ERROR="ERROR"})(jwplayer);(function(jwplayer){jwplayer.events.eventdispatcher=function(debug){var _debug=debug;var _listeners;var _globallisteners;this.resetEventListeners=function(){_listeners={};_globallisteners=[]};this.resetEventListeners();this.addEventListener=function(type,listener,count){try{if(!jwplayer.utils.exists(_listeners[type])){_listeners[type]=[]}if(typeof(listener)=="string"){eval("listener = "+listener)}_listeners[type].push({listener:listener,count:count})}catch(err){jwplayer.utils.log("error",err)}return false};this.removeEventListener=function(type,listener){if(!_listeners[type]){return}try{for(var listenerIndex=0;listenerIndex<_listeners[type].length;listenerIndex++){if(_listeners[type][listenerIndex].listener.toString()==listener.toString()){_listeners[type].splice(listenerIndex,1);break}}}catch(err){jwplayer.utils.log("error",err)}return false};this.addGlobalListener=function(listener,count){try{if(typeof(listener)=="string"){eval("listener = "+listener)}_globallisteners.push({listener:listener,count:count})}catch(err){jwplayer.utils.log("error",err)}return false};this.removeGlobalListener=function(listener){if(!_globallisteners[type]){return}try{for(var globalListenerIndex=0;globalListenerIndex<_globallisteners.length;globalListenerIndex++){if(_globallisteners[globalListenerIndex].listener.toString()==listener.toString()){_globallisteners.splice(globalListenerIndex,1);break}}}catch(err){jwplayer.utils.log("error",err)}return false};this.sendEvent=function(type,data){if(!jwplayer.utils.exists(data)){data={}}if(_debug){jwplayer.utils.log(type,data)}if(typeof _listeners[type]!="undefined"){for(var listenerIndex=0;listenerIndex<_listeners[type].length;listenerIndex++){try{_listeners[type][listenerIndex].listener(data)}catch(err){jwplayer.utils.log("There was an error while handling a listener: "+err.toString(),_listeners[type][listenerIndex].listener)}if(_listeners[type][listenerIndex]){if(_listeners[type][listenerIndex].count===1){delete _listeners[type][listenerIndex]}else{if(_listeners[type][listenerIndex].count>0){_listeners[type][listenerIndex].count=_listeners[type][listenerIndex].count-1}}}}}for(var globalListenerIndex=0;globalListenerIndex<_globallisteners.length;globalListenerIndex++){try{_globallisteners[globalListenerIndex].listener(data)}catch(err){jwplayer.utils.log("There was an error while handling a listener: "+err.toString(),_globallisteners[globalListenerIndex].listener)}if(_globallisteners[globalListenerIndex]){if(_globallisteners[globalListenerIndex].count===1){delete _globallisteners[globalListenerIndex]}else{if(_globallisteners[globalListenerIndex].count>0){_globallisteners[globalListenerIndex].count=_globallisteners[globalListenerIndex].count-1}}}}}}})(jwplayer);(function(a){var b={};a.utils.animations=function(){};a.utils.animations.transform=function(c,d){c.style.webkitTransform=d;c.style.MozTransform=d;c.style.OTransform=d;c.style.msTransform=d};a.utils.animations.transformOrigin=function(c,d){c.style.webkitTransformOrigin=d;c.style.MozTransformOrigin=d;c.style.OTransformOrigin=d;c.style.msTransformOrigin=d};a.utils.animations.rotate=function(c,d){a.utils.animations.transform(c,["rotate(",d,"deg)"].join(""))};a.utils.cancelAnimation=function(c){delete b[c.id]};a.utils.fadeTo=function(m,f,e,j,h,d){if(b[m.id]!=d&&a.utils.exists(d)){return}if(m.style.opacity==f){return}var c=new Date().getTime();if(d>c){setTimeout(function(){a.utils.fadeTo(m,f,e,j,0,d)},d-c)}if(m.style.display=="none"){m.style.display="block"}if(!a.utils.exists(j)){j=m.style.opacity===""?1:m.style.opacity}if(m.style.opacity==f&&m.style.opacity!==""&&a.utils.exists(d)){if(f===0){m.style.display="none"}return}if(!a.utils.exists(d)){d=c;b[m.id]=d}if(!a.utils.exists(h)){h=0}var k=(e>0)?((c-d)/(e*1000)):0;k=k>1?1:k;var l=f-j;var g=j+(k*l);if(g>1){g=1}else{if(g<0){g=0}}m.style.opacity=g;if(h>0){b[m.id]=d+h*1000;a.utils.fadeTo(m,f,e,j,0,b[m.id]);return}setTimeout(function(){a.utils.fadeTo(m,f,e,j,0,d)},10)}})(jwplayer);(function(a){a.utils.arrays=function(){};a.utils.arrays.indexOf=function(c,d){for(var b=0;b<c.length;b++){if(c[b]==d){return b}}return -1};a.utils.arrays.remove=function(c,d){var b=a.utils.arrays.indexOf(c,d);if(b>-1){c.splice(b,1)}}})(jwplayer);(function(a){a.utils.extensionmap={"3gp":{html5:"video/3gpp",flash:"video"},"3gpp":{html5:"video/3gpp"},"3g2":{html5:"video/3gpp2",flash:"video"},"3gpp2":{html5:"video/3gpp2"},flv:{flash:"video"},f4a:{html5:"audio/mp4"},f4b:{html5:"audio/mp4",flash:"video"},f4v:{html5:"video/mp4",flash:"video"},mov:{html5:"video/quicktime",flash:"video"},m4a:{html5:"audio/mp4",flash:"video"},m4b:{html5:"audio/mp4"},m4p:{html5:"audio/mp4"},m4v:{html5:"video/mp4",flash:"video"},mp4:{html5:"video/mp4",flash:"video"},rbs:{flash:"sound"},aac:{html5:"audio/aac",flash:"video"},mp3:{html5:"audio/mp3",flash:"sound"},ogg:{html5:"audio/ogg"},oga:{html5:"audio/ogg"},ogv:{html5:"video/ogg"},webm:{html5:"video/webm"},m3u8:{html5:"audio/x-mpegurl"},gif:{flash:"image"},jpeg:{flash:"image"},jpg:{flash:"image"},swf:{flash:"image"},png:{flash:"image"},wav:{html5:"audio/x-wav"}}})(jwplayer);(function(e){e.utils.mediaparser=function(){};var g={element:{width:"width",height:"height",id:"id","class":"className",name:"name"},media:{src:"file",preload:"preload",autoplay:"autostart",loop:"repeat",controls:"controls"},source:{src:"file",type:"type",media:"media","data-jw-width":"width","data-jw-bitrate":"bitrate"},video:{poster:"image"}};var f={};e.utils.mediaparser.parseMedia=function(j){return d(j)};function c(k,j){if(!e.utils.exists(j)){j=g[k]}else{e.utils.extend(j,g[k])}return j}function d(n,j){if(f[n.tagName.toLowerCase()]&&!e.utils.exists(j)){return f[n.tagName.toLowerCase()](n)}else{j=c("element",j);var o={};for(var k in j){if(k!="length"){var m=n.getAttribute(k);if(e.utils.exists(m)){o[j[k]]=m}}}var l=n.style["#background-color"];if(l&&!(l=="transparent"||l=="rgba(0, 0, 0, 0)")){o.screencolor=l}return o}}function h(n,k){k=c("media",k);var l=[];var j=e.utils.selectors("source",n);for(var m in j){if(!isNaN(m)){l.push(a(j[m]))}}var o=d(n,k);if(e.utils.exists(o.file)){l[0]={file:o.file}}o.levels=l;return o}function a(l,k){k=c("source",k);var j=d(l,k);j.width=j.width?j.width:0;j.bitrate=j.bitrate?j.bitrate:0;return j}function b(l,k){k=c("video",k);var j=h(l,k);return j}f.media=h;f.audio=h;f.source=a;f.video=b})(jwplayer);(function(a){a.utils.loaderstatus={NEW:"NEW",LOADING:"LOADING",ERROR:"ERROR",COMPLETE:"COMPLETE"};a.utils.scriptloader=function(c){var d=a.utils.loaderstatus.NEW;var b=new a.events.eventdispatcher();a.utils.extend(this,b);this.load=function(){if(d==a.utils.loaderstatus.NEW){d=a.utils.loaderstatus.LOADING;var e=document.createElement("script");e.onload=function(f){d=a.utils.loaderstatus.COMPLETE;b.sendEvent(a.events.COMPLETE)};e.onerror=function(f){d=a.utils.loaderstatus.ERROR;b.sendEvent(a.events.ERROR)};e.onreadystatechange=function(){if(e.readyState=="loaded"||e.readyState=="complete"){d=a.utils.loaderstatus.COMPLETE;b.sendEvent(a.events.COMPLETE)}};document.getElementsByTagName("head")[0].appendChild(e);e.src=c}};this.getStatus=function(){return d}}})(jwplayer);(function(a){a.utils.selectors=function(b,e){if(!a.utils.exists(e)){e=document}b=a.utils.strings.trim(b);var c=b.charAt(0);if(c=="#"){return e.getElementById(b.substr(1))}else{if(c=="."){if(e.getElementsByClassName){return e.getElementsByClassName(b.substr(1))}else{return a.utils.selectors.getElementsByTagAndClass("*",b.substr(1))}}else{if(b.indexOf(".")>0){var d=b.split(".");return a.utils.selectors.getElementsByTagAndClass(d[0],d[1])}else{return e.getElementsByTagName(b)}}}return null};a.utils.selectors.getElementsByTagAndClass=function(e,h,g){var j=[];if(!a.utils.exists(g)){g=document}var f=g.getElementsByTagName(e);for(var d=0;d<f.length;d++){if(a.utils.exists(f[d].className)){var c=f[d].className.split(" ");for(var b=0;b<c.length;b++){if(c[b]==h){j.push(f[d])}}}}return j}})(jwplayer);(function(a){a.utils.strings=function(){};a.utils.strings.trim=function(b){return b.replace(/^\s*/,"").replace(/\s*$/,"")};a.utils.strings.pad=function(c,d,b){if(!b){b="0"}while(c.length<d){c=b+c}return c};a.utils.strings.serialize=function(b){if(b==null){return null}else{if(b=="true"){return true}else{if(b=="false"){return false}else{if(isNaN(Number(b))||b.length>5||b.length==0){return b}else{return Number(b)}}}}};a.utils.strings.seconds=function(d){d=d.replace(",",".");var b=d.split(":");var c=0;if(d.substr(-1)=="s"){c=Number(d.substr(0,d.length-1))}else{if(d.substr(-1)=="m"){c=Number(d.substr(0,d.length-1))*60}else{if(d.substr(-1)=="h"){c=Number(d.substr(0,d.length-1))*3600}else{if(b.length>1){c=Number(b[b.length-1]);c+=Number(b[b.length-2])*60;if(b.length==3){c+=Number(b[b.length-3])*3600}}else{c=Number(d)}}}}return c};a.utils.strings.xmlAttribute=function(b,c){for(var d=0;d<b.attributes.length;d++){if(b.attributes[d].name&&b.attributes[d].name.toLowerCase()==c.toLowerCase()){return b.attributes[d].value.toString()}}return""};a.utils.strings.jsonToString=function(f){var h=h||{};if(h&&h.stringify){return h.stringify(f)}var c=typeof(f);if(c!="object"||f===null){if(c=="string"){f='"'+f+'"'}else{return String(f)}}else{var g=[],b=(f&&f.constructor==Array);for(var d in f){var e=f[d];switch(typeof(e)){case"string":e='"'+e+'"';break;case"object":if(a.utils.exists(e)){e=a.utils.strings.jsonToString(e)}break}if(b){if(typeof(e)!="function"){g.push(String(e))}}else{if(typeof(e)!="function"){g.push('"'+d+'":'+String(e))}}}if(b){return"["+String(g)+"]"}else{return"{"+String(g)+"}"}}}})(jwplayer);(function(c){var d=new RegExp(/^(#|0x)[0-9a-fA-F]{3,6}/);c.utils.typechecker=function(g,f){f=!c.utils.exists(f)?b(g):f;return e(g,f)};function b(f){var g=["true","false","t","f"];if(g.toString().indexOf(f.toLowerCase().replace(" ",""))>=0){return"boolean"}else{if(d.test(f)){return"color"}else{if(!isNaN(parseInt(f,10))&&parseInt(f,10).toString().length==f.length){return"integer"}else{if(!isNaN(parseFloat(f))&&parseFloat(f).toString().length==f.length){return"float"}}}}return"string"}function e(g,f){if(!c.utils.exists(f)){return g}switch(f){case"color":if(g.length>0){return a(g)}return null;case"integer":return parseInt(g,10);case"float":return parseFloat(g);case"boolean":if(g.toLowerCase()=="true"){return true}else{if(g=="1"){return true}}return false}return g}function a(f){switch(f.toLowerCase()){case"blue":return parseInt("0000FF",16);case"green":return parseInt("00FF00",16);case"red":return parseInt("FF0000",16);case"cyan":return parseInt("00FFFF",16);case"magenta":return parseInt("FF00FF",16);case"yellow":return parseInt("FFFF00",16);case"black":return parseInt("000000",16);case"white":return parseInt("FFFFFF",16);default:f=f.replace(/(#|0x)?([0-9A-F]{3,6})$/gi,"$2");if(f.length==3){f=f.charAt(0)+f.charAt(0)+f.charAt(1)+f.charAt(1)+f.charAt(2)+f.charAt(2)}return parseInt(f,16)}return parseInt("000000",16)}})(jwplayer);(function(a){a.utils.parsers=function(){};a.utils.parsers.localName=function(b){if(!b){return""}else{if(b.localName){return b.localName}else{if(b.baseName){return b.baseName}else{return""}}}};a.utils.parsers.textContent=function(b){if(!b){return""}else{if(b.textContent){return b.textContent}else{if(b.text){return b.text}else{return""}}}}})(jwplayer);(function(a){a.utils.parsers.jwparser=function(){};a.utils.parsers.jwparser.PREFIX="jwplayer";a.utils.parsers.jwparser.parseEntry=function(c,d){for(var b=0;b<c.childNodes.length;b++){if(c.childNodes[b].prefix==a.utils.parsers.jwparser.PREFIX){d[a.utils.parsers.localName(c.childNodes[b])]=a.utils.strings.serialize(a.utils.parsers.textContent(c.childNodes[b]))}if(!d.file&&String(d.link).toLowerCase().indexOf("youtube")>-1){d.file=d.link}}return d};a.utils.parsers.jwparser.getProvider=function(c){if(c.type){return c.type}else{if(c.file.indexOf("youtube.com/w")>-1||c.file.indexOf("youtube.com/v")>-1||c.file.indexOf("youtu.be/")>-1){return"youtube"}else{if(c.streamer&&c.streamer.indexOf("rtmp")==0){return"rtmp"}else{if(c.streamer&&c.streamer.indexOf("http")==0){return"http"}else{var b=a.utils.strings.extension(c.file);if(extensions.hasOwnProperty(b)){return extensions[b]}}}}}return""}})(jwplayer);(function(a){a.utils.parsers.mediaparser=function(){};a.utils.parsers.mediaparser.PREFIX="media";a.utils.parsers.mediaparser.parseGroup=function(d,f){var e=false;for(var c=0;c<d.childNodes.length;c++){if(d.childNodes[c].prefix==a.utils.parsers.mediaparser.PREFIX){if(!a.utils.parsers.localName(d.childNodes[c])){continue}switch(a.utils.parsers.localName(d.childNodes[c]).toLowerCase()){case"content":if(!e){f.file=a.utils.strings.xmlAttribute(d.childNodes[c],"url")}if(a.utils.strings.xmlAttribute(d.childNodes[c],"duration")){f.duration=a.utils.strings.seconds(a.utils.strings.xmlAttribute(d.childNodes[c],"duration"))}if(a.utils.strings.xmlAttribute(d.childNodes[c],"start")){f.start=a.utils.strings.seconds(a.utils.strings.xmlAttribute(d.childNodes[c],"start"))}if(d.childNodes[c].childNodes&&d.childNodes[c].childNodes.length>0){f=a.utils.parsers.mediaparser.parseGroup(d.childNodes[c],f)}if(a.utils.strings.xmlAttribute(d.childNodes[c],"width")||a.utils.strings.xmlAttribute(d.childNodes[c],"bitrate")||a.utils.strings.xmlAttribute(d.childNodes[c],"url")){if(!f.levels){f.levels=[]}f.levels.push({width:a.utils.strings.xmlAttribute(d.childNodes[c],"width"),bitrate:a.utils.strings.xmlAttribute(d.childNodes[c],"bitrate"),file:a.utils.strings.xmlAttribute(d.childNodes[c],"url")})}break;case"title":f.title=a.utils.parsers.textContent(d.childNodes[c]);break;case"description":f.description=a.utils.parsers.textContent(d.childNodes[c]);break;case"keywords":f.tags=a.utils.parsers.textContent(d.childNodes[c]);break;case"thumbnail":f.image=a.utils.strings.xmlAttribute(d.childNodes[c],"url");break;case"credit":f.author=a.utils.parsers.textContent(d.childNodes[c]);break;case"player":var b=d.childNodes[c].url;if(b.indexOf("youtube.com")>=0||b.indexOf("youtu.be")>=0){e=true;f.file=a.utils.strings.xmlAttribute(d.childNodes[c],"url")}break;case"group":a.utils.parsers.mediaparser.parseGroup(d.childNodes[c],f);break}}}return f}})(jwplayer);(function(b){b.utils.parsers.rssparser=function(){};b.utils.parsers.rssparser.parse=function(f){var c=[];for(var e=0;e<f.childNodes.length;e++){if(b.utils.parsers.localName(f.childNodes[e]).toLowerCase()=="channel"){for(var d=0;d<f.childNodes[e].childNodes.length;d++){if(b.utils.parsers.localName(f.childNodes[e].childNodes[d]).toLowerCase()=="item"){c.push(a(f.childNodes[e].childNodes[d]))}}}}return c};function a(d){var e={};for(var c=0;c<d.childNodes.length;c++){if(!b.utils.parsers.localName(d.childNodes[c])){continue}switch(b.utils.parsers.localName(d.childNodes[c]).toLowerCase()){case"enclosure":e.file=b.utils.strings.xmlAttribute(d.childNodes[c],"url");break;case"title":e.title=b.utils.parsers.textContent(d.childNodes[c]);break;case"pubdate":e.date=b.utils.parsers.textContent(d.childNodes[c]);break;case"description":e.description=b.utils.parsers.textContent(d.childNodes[c]);break;case"link":e.link=b.utils.parsers.textContent(d.childNodes[c]);break;case"category":if(e.tags){e.tags+=b.utils.parsers.textContent(d.childNodes[c])}else{e.tags=b.utils.parsers.textContent(d.childNodes[c])}break}}e=b.utils.parsers.mediaparser.parseGroup(d,e);e=b.utils.parsers.jwparser.parseEntry(d,e);return new b.html5.playlistitem(e)}})(jwplayer);(function(a){var c={};var b={};a.plugins=function(){};a.plugins.loadPlugins=function(e,d){b[e]=new a.plugins.pluginloader(new a.plugins.model(c),d);return b[e]};a.plugins.registerPlugin=function(h,f,e){var d=a.utils.getPluginName(h);if(c[d]){c[d].registerPlugin(h,f,e)}else{a.utils.log("A plugin ("+h+") was registered with the player that was not loaded. Please check your configuration.");for(var g in b){b[g].pluginFailed()}}}})(jwplayer);(function(a){a.plugins.model=function(b){this.addPlugin=function(c){var d=a.utils.getPluginName(c);if(!b[d]){b[d]=new a.plugins.plugin(c)}return b[d]}}})(jwplayer);(function(a){a.plugins.pluginmodes={FLASH:"FLASH",JAVASCRIPT:"JAVASCRIPT",HYBRID:"HYBRID"};a.plugins.plugin=function(b){var d="http://lp.longtailvideo.com";var j=a.utils.loaderstatus.NEW;var k;var h;var l;var c=new a.events.eventdispatcher();a.utils.extend(this,c);function e(){switch(a.utils.getPluginPathType(b)){case a.utils.pluginPathType.ABSOLUTE:return b;case a.utils.pluginPathType.RELATIVE:return a.utils.getAbsolutePath(b,window.location.href);case a.utils.pluginPathType.CDN:var n=a.utils.getPluginName(b);var m=a.utils.getPluginVersion(b);return d+"/"+a.version.split(".")[0]+"/"+n+"/"+n+(m!==""?("-"+m):"")+".js"}}function g(m){l=setTimeout(function(){j=a.utils.loaderstatus.COMPLETE;c.sendEvent(a.events.COMPLETE)},1000)}function f(m){j=a.utils.loaderstatus.ERROR;c.sendEvent(a.events.ERROR)}this.load=function(){if(j==a.utils.loaderstatus.NEW){if(b.lastIndexOf(".swf")>0){k=b;j=a.utils.loaderstatus.COMPLETE;c.sendEvent(a.events.COMPLETE);return}j=a.utils.loaderstatus.LOADING;var m=new a.utils.scriptloader(e());m.addEventListener(a.events.COMPLETE,g);m.addEventListener(a.events.ERROR,f);m.load()}};this.registerPlugin=function(o,n,m){if(l){clearTimeout(l);l=undefined}if(n&&m){k=m;h=n}else{if(typeof n=="string"){k=n}else{if(typeof n=="function"){h=n}else{if(!n&&!m){k=o}}}}j=a.utils.loaderstatus.COMPLETE;c.sendEvent(a.events.COMPLETE)};this.getStatus=function(){return j};this.getPluginName=function(){return a.utils.getPluginName(b)};this.getFlashPath=function(){if(k){switch(a.utils.getPluginPathType(k)){case a.utils.pluginPathType.ABSOLUTE:return k;case a.utils.pluginPathType.RELATIVE:if(b.lastIndexOf(".swf")>0){return a.utils.getAbsolutePath(k,window.location.href)}return a.utils.getAbsolutePath(k,e());case a.utils.pluginPathType.CDN:if(k.indexOf("-")>-1){return k+"h"}return k+"-h"}}return null};this.getJS=function(){return h};this.getPluginmode=function(){if(typeof k!="undefined"&&typeof h!="undefined"){return a.plugins.pluginmodes.HYBRID}else{if(typeof k!="undefined"){return a.plugins.pluginmodes.FLASH}else{if(typeof h!="undefined"){return a.plugins.pluginmodes.JAVASCRIPT}}}};this.getNewInstance=function(n,m,o){return new h(n,m,o)};this.getURL=function(){return b}}})(jwplayer);(function(a){a.plugins.pluginloader=function(h,e){var g={};var k=a.utils.loaderstatus.NEW;var d=false;var b=false;var c=new a.events.eventdispatcher();a.utils.extend(this,c);function f(){if(!b){b=true;k=a.utils.loaderstatus.COMPLETE;c.sendEvent(a.events.COMPLETE)}}function j(){if(!b){var m=0;for(plugin in g){var l=g[plugin].getStatus();if(l==a.utils.loaderstatus.LOADING||l==a.utils.loaderstatus.NEW){m++}}if(m==0){f()}}}this.setupPlugins=function(n,l,s){var m={length:0,plugins:{}};var p={length:0,plugins:{}};for(var o in g){var q=g[o].getPluginName();if(g[o].getFlashPath()){m.plugins[g[o].getFlashPath()]=l.plugins[o];m.plugins[g[o].getFlashPath()].pluginmode=g[o].getPluginmode();m.length++}if(g[o].getJS()){var r=document.createElement("div");r.id=n.id+"_"+q;r.style.position="absolute";r.style.zIndex=p.length+10;p.plugins[q]=g[o].getNewInstance(n,l.plugins[o],r);p.length++;if(typeof p.plugins[q].resize!="undefined"){n.onReady(s(p.plugins[q],r,true));n.onResize(s(p.plugins[q],r))}}}n.plugins=p.plugins;return m};this.load=function(){k=a.utils.loaderstatus.LOADING;d=true;for(var l in e){if(a.utils.exists(l)){g[l]=h.addPlugin(l);g[l].addEventListener(a.events.COMPLETE,j);g[l].addEventListener(a.events.ERROR,j)}}for(l in g){g[l].load()}d=false;j()};this.pluginFailed=function(){f()};this.getStatus=function(){return k}}})(jwplayer);(function(b){var a=[];b.api=function(d){this.container=d;this.id=d.id;var n={};var s={};var q={};var c=[];var h=undefined;var l=false;var j=[];var p=b.utils.getOuterHTML(d);var r={};var k={};this.getBuffer=function(){return this.callInternal("jwGetBuffer")};this.getContainer=function(){return this.container};function e(u,t){return function(z,v,w,x){if(u.renderingMode=="flash"||u.renderingMode=="html5"){var y;if(v){k[z]=v;y="jwplayer('"+u.id+"').callback('"+z+"')"}else{if(!v&&k[z]){delete k[z]}}h.jwDockSetButton(z,y,w,x)}return t}}this.getPlugin=function(t){var v=this;var u={};if(t=="dock"){return b.utils.extend(u,{setButton:e(v,u),show:function(){v.callInternal("jwDockShow");return u},hide:function(){v.callInternal("jwDockHide");return u},onShow:function(w){v.componentListener("dock",b.api.events.JWPLAYER_COMPONENT_SHOW,w);return u},onHide:function(w){v.componentListener("dock",b.api.events.JWPLAYER_COMPONENT_HIDE,w);return u}})}else{if(t=="controlbar"){return b.utils.extend(u,{show:function(){v.callInternal("jwControlbarShow");return u},hide:function(){v.callInternal("jwControlbarHide");return u},onShow:function(w){v.componentListener("controlbar",b.api.events.JWPLAYER_COMPONENT_SHOW,w);return u},onHide:function(w){v.componentListener("controlbar",b.api.events.JWPLAYER_COMPONENT_HIDE,w);return u}})}else{if(t=="display"){return b.utils.extend(u,{show:function(){v.callInternal("jwDisplayShow");return u},hide:function(){v.callInternal("jwDisplayHide");return u},onShow:function(w){v.componentListener("display",b.api.events.JWPLAYER_COMPONENT_SHOW,w);return u},onHide:function(w){v.componentListener("display",b.api.events.JWPLAYER_COMPONENT_HIDE,w);return u}})}else{return this.plugins[t]}}}};this.callback=function(t){if(k[t]){return k[t]()}};this.getDuration=function(){return this.callInternal("jwGetDuration")};this.getFullscreen=function(){return this.callInternal("jwGetFullscreen")};this.getHeight=function(){return this.callInternal("jwGetHeight")};this.getLockState=function(){return this.callInternal("jwGetLockState")};this.getMeta=function(){return this.getItemMeta()};this.getMute=function(){return this.callInternal("jwGetMute")};this.getPlaylist=function(){var u=this.callInternal("jwGetPlaylist");if(this.renderingMode=="flash"){b.utils.deepReplaceKeyName(u,"__dot__",".")}for(var t=0;t<u.length;t++){if(!b.utils.exists(u[t].index)){u[t].index=t}}return u};this.getPlaylistItem=function(t){if(!b.utils.exists(t)){t=this.getCurrentItem()}return this.getPlaylist()[t]};this.getPosition=function(){return this.callInternal("jwGetPosition")};this.getRenderingMode=function(){return this.renderingMode};this.getState=function(){return this.callInternal("jwGetState")};this.getVolume=function(){return this.callInternal("jwGetVolume")};this.getWidth=function(){return this.callInternal("jwGetWidth")};this.setFullscreen=function(t){if(!b.utils.exists(t)){this.callInternal("jwSetFullscreen",!this.callInternal("jwGetFullscreen"))}else{this.callInternal("jwSetFullscreen",t)}return this};this.setMute=function(t){if(!b.utils.exists(t)){this.callInternal("jwSetMute",!this.callInternal("jwGetMute"))}else{this.callInternal("jwSetMute",t)}return this};this.lock=function(){return this};this.unlock=function(){return this};this.load=function(t){this.callInternal("jwLoad",t);return this};this.playlistItem=function(t){this.callInternal("jwPlaylistItem",t);return this};this.playlistPrev=function(){this.callInternal("jwPlaylistPrev");return this};this.playlistNext=function(){this.callInternal("jwPlaylistNext");return this};this.resize=function(u,t){if(this.renderingMode=="html5"){h.jwResize(u,t)}else{this.container.width=u;this.container.height=t}return this};this.play=function(t){if(typeof t=="undefined"){t=this.getState();if(t==b.api.events.state.PLAYING||t==b.api.events.state.BUFFERING){this.callInternal("jwPause")}else{this.callInternal("jwPlay")}}else{this.callInternal("jwPlay",t)}return this};this.pause=function(t){if(typeof t=="undefined"){t=this.getState();if(t==b.api.events.state.PLAYING||t==b.api.events.state.BUFFERING){this.callInternal("jwPause")}else{this.callInternal("jwPlay")}}else{this.callInternal("jwPause",t)}return this};this.stop=function(){this.callInternal("jwStop");return this};this.seek=function(t){this.callInternal("jwSeek",t);return this};this.setVolume=function(t){this.callInternal("jwSetVolume",t);return this};this.onBufferChange=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_BUFFER,t)};this.onBufferFull=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_BUFFER_FULL,t)};this.onError=function(t){return this.eventListener(b.api.events.JWPLAYER_ERROR,t)};this.onFullscreen=function(t){return this.eventListener(b.api.events.JWPLAYER_FULLSCREEN,t)};this.onMeta=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_META,t)};this.onMute=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_MUTE,t)};this.onPlaylist=function(t){return this.eventListener(b.api.events.JWPLAYER_PLAYLIST_LOADED,t)};this.onPlaylistItem=function(t){return this.eventListener(b.api.events.JWPLAYER_PLAYLIST_ITEM,t)};this.onReady=function(t){return this.eventListener(b.api.events.API_READY,t)};this.onResize=function(t){return this.eventListener(b.api.events.JWPLAYER_RESIZE,t)};this.onComplete=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_COMPLETE,t)};this.onSeek=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_SEEK,t)};this.onTime=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_TIME,t)};this.onVolume=function(t){return this.eventListener(b.api.events.JWPLAYER_MEDIA_VOLUME,t)};this.onBuffer=function(t){return this.stateListener(b.api.events.state.BUFFERING,t)};
this.onPause=function(t){
	return this.stateListener(b.api.events.state.PAUSED,t)
};
this.onPlay=function(t){return this.stateListener(b.api.events.state.PLAYING,t)};
this.onIdle=function(t){return this.stateListener(b.api.events.state.IDLE,t)};
this.remove=function(){
	n={};
	j=[];
	if(b.utils.getOuterHTML(this.container)!=p){
		b.api.destroyPlayer(this.id,p)
	}
};
this.setup=function(u){if(b.embed){var t=this.id;this.remove();var v=b(t);v.config=u;return new b.embed(v)}return this};this.registerPlugin=function(v,u,t){b.plugins.registerPlugin(v,u,t)};this.setPlayer=function(t,u){h=t;this.renderingMode=u};
this.clearListener=function(t) {
	delete s[t];
}
this.stateListener=function(t,u){
	if(!s[t]){
		s[t]=[];
		this.eventListener(b.api.events.JWPLAYER_PLAYER_STATE,g(t))
	}
	s[t].push(u);
	return this
};

function g(t){
	return function(v){
		var u=v.newstate,x=v.oldstate;
		if(u==t){
			var w=s[u];
			if(w){
				for(var y=0;y<w.length;y++){
					if(typeof w[y]=="function"){w[y].call(this,{oldstate:x,newstate:u})}
				}
			}
		}
	}
}
this.componentListener=function(t,u,v){
	if(!q[t]){
		q[t]={}
	}
	if(!q[t][u]){
		q[t][u]=[];
		this.eventListener(u,m(t,u))
	}
	q[t][u].push(v);
	return this
};
function m(t,u){return function(w){if(t==w.component){var v=q[t][u];if(v){for(var x=0;x<v.length;x++){if(typeof v[x]=="function"){v[x].call(this,w)}}}}}}
this.addInternalListener=function(t,u){
	t.jwAddEventListener(u,'function(dat) { jwplayer("'+this.id+'").dispatchEvent("'+u+'", dat); }')
};

this.eventListener=function(t,u){
	if(!n[t]){
		n[t]=[];
		if(h&&l){
			this.addInternalListener(h,t)
		}
	}
	n[t].push(u);
	return this
};

this.dispatchEvent=function(v){if(n[v]){var u=f(v,arguments[1]);for(var t=0;t<n[v].length;t++){if(typeof n[v][t]=="function"){n[v][t].call(this,u)}}}};function f(v,t){var x=b.utils.extend({},t);if(v==b.api.events.JWPLAYER_FULLSCREEN&&!x.fullscreen){x.fullscreen=x.message=="true"?true:false;delete x.message}else{if(typeof x.data=="object"){x=b.utils.extend(x,x.data);delete x.data}}var u=["position","duration","offset"];for(var w in u){if(x[u[w]]){x[u[w]]=Math.round(x[u[w]]*1000)/1000}}return x}this.callInternal=function(u,t){if(l){if(typeof h!="undefined"&&typeof h[u]=="function"){if(b.utils.exists(t)){return(h[u])(t)}else{return(h[u])()}}return null}else{j.push({method:u,parameters:t})}};this.playerReady=function(v){l=true;if(!h){this.setPlayer(document.getElementById(v.id))}this.container=document.getElementById(this.id);for(var t in n){this.addInternalListener(h,t)}this.eventListener(b.api.events.JWPLAYER_PLAYLIST_ITEM,function(w){r={}});this.eventListener(b.api.events.JWPLAYER_MEDIA_META,function(w){b.utils.extend(r,w.metadata)});this.dispatchEvent(b.api.events.API_READY);while(j.length>0){var u=j.shift();this.callInternal(u.method,u.parameters)}};this.getItemMeta=function(){return r};this.getCurrentItem=function(){return this.callInternal("jwGetPlaylistIndex")};function o(v,x,w){var t=[];if(!x){x=0}if(!w){w=v.length-1}for(var u=x;u<=w;u++){t.push(v[u])}return t}return this};b.api.selectPlayer=function(d){var c;if(!b.utils.exists(d)){d=0}if(d.nodeType){c=d}else{if(typeof d=="string"){c=document.getElementById(d)}}if(c){var e=b.api.playerById(c.id);if(e){return e}else{return b.api.addPlayer(new b.api(c))}}else{if(typeof d=="number"){return b.getPlayers()[d]}}return null};b.api.events={API_READY:"jwplayerAPIReady",JWPLAYER_READY:"jwplayerReady",JWPLAYER_FULLSCREEN:"jwplayerFullscreen",JWPLAYER_RESIZE:"jwplayerResize",JWPLAYER_ERROR:"jwplayerError",JWPLAYER_COMPONENT_SHOW:"jwplayerComponentShow",JWPLAYER_COMPONENT_HIDE:"jwplayerComponentHide",JWPLAYER_MEDIA_BUFFER:"jwplayerMediaBuffer",JWPLAYER_MEDIA_BUFFER_FULL:"jwplayerMediaBufferFull",JWPLAYER_MEDIA_ERROR:"jwplayerMediaError",JWPLAYER_MEDIA_LOADED:"jwplayerMediaLoaded",JWPLAYER_MEDIA_COMPLETE:"jwplayerMediaComplete",JWPLAYER_MEDIA_SEEK:"jwplayerMediaSeek",JWPLAYER_MEDIA_TIME:"jwplayerMediaTime",JWPLAYER_MEDIA_VOLUME:"jwplayerMediaVolume",JWPLAYER_MEDIA_META:"jwplayerMediaMeta",JWPLAYER_MEDIA_MUTE:"jwplayerMediaMute",JWPLAYER_PLAYER_STATE:"jwplayerPlayerState",JWPLAYER_PLAYLIST_LOADED:"jwplayerPlaylistLoaded",JWPLAYER_PLAYLIST_ITEM:"jwplayerPlaylistItem"};b.api.events.state={BUFFERING:"BUFFERING",IDLE:"IDLE",PAUSED:"PAUSED",PLAYING:"PLAYING"};b.api.playerById=function(d){for(var c=0;c<a.length;c++){if(a[c].id==d){return a[c]}}return null};b.api.addPlayer=function(c){for(var d=0;d<a.length;d++){if(a[d]==c){return c}}a.push(c);return c};b.api.destroyPlayer=function(g,d){var f=-1;for(var j=0;j<a.length;j++){if(a[j].id==g){f=j;continue}}if(f>=0){var c=document.getElementById(a[f].id);if(document.getElementById(a[f].id+"_wrapper")){c=document.getElementById(a[f].id+"_wrapper")}if(c){if(d){b.utils.setOuterHTML(c,d)}else{var h=document.createElement("div");var e=c.id;if(c.id.indexOf("_wrapper")==c.id.length-8){newID=c.id.substring(0,c.id.length-8)}h.setAttribute("id",e);c.parentNode.replaceChild(h,c)}}a.splice(f,1)}return null};b.getPlayers=function(){return a.slice(0)}})(jwplayer);var _userPlayerReady=(typeof playerReady=="function")?playerReady:undefined;playerReady=function(b){var a=jwplayer.api.playerById(b.id);if(a){a.playerReady(b)}else{jwplayer.api.selectPlayer(b.id).playerReady(b)}if(_userPlayerReady){_userPlayerReady.call(this,b)}};(function(a){a.embed=function(g){var j={width:400,height:300,components:{controlbar:{position:"over"}}};var f=a.utils.mediaparser.parseMedia(g.container);var e=new a.embed.config(a.utils.extend(j,f,g.config),this);var h=a.plugins.loadPlugins(g.id,e.plugins);function c(m,l){for(var k in l){if(typeof m[k]=="function"){(m[k]).call(m,l[k])}}}function d(){if(h.getStatus()==a.utils.loaderstatus.COMPLETE){for(var m=0;m<e.modes.length;m++){if(e.modes[m].type&&a.embed[e.modes[m].type]){var k=e;if(e.modes[m].config){k=a.utils.extend(a.utils.clone(e),e.modes[m].config)}var l=new a.embed[e.modes[m].type](document.getElementById(g.id),e.modes[m],k,h,g);if(l.supportsConfig()){l.embed();c(g,e.events);return g}}}a.utils.log("No suitable players found");new a.embed.logo(a.utils.extend({hide:true},e.components.logo),"none",g.id)}}h.addEventListener(a.events.COMPLETE,d);h.addEventListener(a.events.ERROR,d);h.load();return g};function b(){if(!document.body){return setTimeout(b,15)}var c=a.utils.selectors.getElementsByTagAndClass("video","jwplayer");for(var d=0;d<c.length;d++){var e=c[d];a(e.id).setup({})}}b()})(jwplayer);(function(e){function h(){return[{type:"flash",src:"/jwplayer/player.swf"},{type:"html5"},{type:"download"}]}var a={players:"modes",autoplay:"autostart"};function b(n){var m=n.toLowerCase();var l=["left","right","top","bottom"];for(var k=0;k<l.length;k++){if(m==l[k]){return true}}return false}function c(l){var k=false;k=(l instanceof Array)||(typeof l=="object"&&!l.position&&!l.size);return k}function j(k){if(typeof k=="string"){if(parseInt(k).toString()==k||k.toLowerCase().indexOf("px")>-1){return parseInt(k)}}return k}var g=["playlist","dock","controlbar","logo","display"];function f(k){var n={};switch(e.utils.typeOf(k.plugins)){case"object":for(var m in k.plugins){n[e.utils.getPluginName(m)]=m}break;case"string":var o=k.plugins.split(",");for(var l=0;l<o.length;l++){n[e.utils.getPluginName(o[l])]=o[l]}break}return n}function d(o,n,m,k){if(e.utils.typeOf(o[n])!="object"){o[n]={}}var l=o[n][m];if(e.utils.typeOf(l)!="object"){o[n][m]=l={}}if(k){if(n=="plugins"){var p=e.utils.getPluginName(m);l[k]=o[p+"."+k];delete o[p+"."+k]}else{l[k]=o[m+"."+k];delete o[m+"."+k]}}}e.embed.deserialize=function(l){var m=f(l);for(var k in m){d(l,"plugins",m[k])}for(var p in l){if(p.indexOf(".")>-1){var o=p.split(".");var n=o[0];var p=o[1];if(e.utils.isInArray(g,n)){d(l,"components",n,p)}else{if(m[n]){d(l,"plugins",m[n],p)}}}}return l};e.embed.config=function(k,u){var t=e.utils.extend({},k);var r;if(c(t.playlist)){r=t.playlist;delete t.playlist}t=e.embed.deserialize(t);t.height=j(t.height);t.width=j(t.width);if(typeof t.plugins=="string"){var l=t.plugins.split(",");if(typeof t.plugins!="object"){t.plugins={}}for(var p=0;p<l.length;p++){var q=e.utils.getPluginName(l[p]);if(typeof t[q]=="object"){t.plugins[l[p]]=t[q];delete t[q]}else{t.plugins[l[p]]={}}}}for(var s=0;s<g.length;s++){var o=g[s];if(e.utils.exists(t[o])){if(typeof t[o]!="object"){if(!t.components[o]){t.components[o]={}}if(o=="logo"){t.components[o].file=t[o]}else{t.components[o].position=t[o]}delete t[o]}else{if(!t.components[o]){t.components[o]={}}e.utils.extend(t.components[o],t[o]);delete t[o]}}if(typeof t[o+"size"]!="undefined"){if(!t.components[o]){t.components[o]={}}t.components[o].size=t[o+"size"];delete t[o+"size"]}}if(typeof t.icons!="undefined"){if(!t.components.display){t.components.display={}}t.components.display.icons=t.icons;delete t.icons}for(var n in a){if(t[n]){if(!t[a[n]]){t[a[n]]=t[n]}delete t[n]}}var m;if(t.flashplayer&&!t.modes){m=h();m[0].src=t.flashplayer;delete t.flashplayer}else{if(t.modes){if(typeof t.modes=="string"){m=h();m[0].src=t.modes}else{if(t.modes instanceof Array){m=t.modes}else{if(typeof t.modes=="object"&&t.modes.type){m=[t.modes]}}}delete t.modes}else{m=h()}}t.modes=m;if(r){t.playlist=r}return t}})(jwplayer);(function(a){a.embed.download=function(c,g,b,d,f){this.embed=function(){var k=a.utils.extend({},b);var q={};var j=b.width?b.width:480;if(typeof j!="number"){j=parseInt(j,10)}var m=b.height?b.height:320;if(typeof m!="number"){m=parseInt(m,10)}var u,o,n;var s={};if(b.playlist&&b.playlist.length){s.file=b.playlist[0].file;o=b.playlist[0].image;s.levels=b.playlist[0].levels}else{s.file=b.file;o=b.image;s.levels=b.levels}if(s.file){u=s.file}else{if(s.levels&&s.levels.length){u=s.levels[0].file}}n=u?"pointer":"auto";var l={display:{style:{cursor:n,width:j,height:m,backgroundColor:"#000",position:"relative",textDecoration:"none",border:"none",display:"block"}},display_icon:{style:{cursor:n,position:"absolute",display:u?"block":"none",top:0,left:0,border:0,margin:0,padding:0,zIndex:3,width:50,height:50,backgroundImage:"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALdJREFUeNrs18ENgjAYhmFouDOCcQJGcARHgE10BDcgTOIosAGwQOuPwaQeuFRi2p/3Sb6EC5L3QCxZBgAAAOCorLW1zMn65TrlkH4NcV7QNcUQt7Gn7KIhxA+qNIR81spOGkL8oFJDyLJRdosqKDDkK+iX5+d7huzwM40xptMQMkjIOeRGo+VkEVvIPfTGIpKASfYIfT9iCHkHrBEzf4gcUQ56aEzuGK/mw0rHpy4AAACAf3kJMACBxjAQNRckhwAAAABJRU5ErkJggg==)"}},display_iconBackground:{style:{cursor:n,position:"absolute",display:u?"block":"none",top:((m-50)/2),left:((j-50)/2),border:0,width:50,height:50,margin:0,padding:0,zIndex:2,backgroundImage:"url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEpJREFUeNrszwENADAIA7DhX8ENoBMZ5KR10EryckCJiIiIiIiIiIiIiIiIiIiIiIh8GmkRERERERERERERERERERERERGRHSPAAPlXH1phYpYaAAAAAElFTkSuQmCC)"}},display_image:{style:{width:j,height:m,display:o?"block":"none",position:"absolute",cursor:n,left:0,top:0,margin:0,padding:0,textDecoration:"none",zIndex:1,border:"none"}}};var h=function(v,x,y){var w=document.createElement(v);if(y){w.id=y}else{w.id=c.id+"_jwplayer_"+x}a.utils.css(w,l[x].style);return w};q.display=h("a","display",c.id);if(u){q.display.setAttribute("href",a.utils.getAbsolutePath(u))}q.display_image=h("img","display_image");q.display_image.setAttribute("alt","Click to download...");if(o){q.display_image.setAttribute("src",a.utils.getAbsolutePath(o))}if(true){q.display_icon=h("div","display_icon");q.display_iconBackground=h("div","display_iconBackground");q.display.appendChild(q.display_image);q.display_iconBackground.appendChild(q.display_icon);q.display.appendChild(q.display_iconBackground)}_css=a.utils.css;_hide=function(v){_css(v,{display:"none"})};function r(v){_imageWidth=q.display_image.naturalWidth;_imageHeight=q.display_image.naturalHeight;t()}function t(){a.utils.stretch(a.utils.stretching.UNIFORM,q.display_image,j,m,_imageWidth,_imageHeight)}q.display_image.onerror=function(v){_hide(q.display_image)};q.display_image.onload=r;c.parentNode.replaceChild(q.display,c);var p=(b.plugins&&b.plugins.logo)?b.plugins.logo:{};q.display.appendChild(new a.embed.logo(b.components.logo,"download",c.id));f.container=document.getElementById(f.id);f.setPlayer(q.display,"download")};this.supportsConfig=function(){if(b){var j=a.utils.getFirstPlaylistItemFromConfig(b);if(typeof j.file=="undefined"&&typeof j.levels=="undefined"){return true}else{if(j.file){return e(j.file,j.provider,j.playlistfile)}else{if(j.levels&&j.levels.length){for(var h=0;h<j.levels.length;h++){if(j.levels[h].file&&e(j.levels[h].file,j.provider,j.playlistfile)){return true}}}}}}else{return true}};function e(j,l,h){if(h){return false}var k=["image","sound","youtube","http"];if(l&&(k.toString().indexOf(l)>-1)){return true}if(!l||(l&&l=="video")){var m=a.utils.extension(j);if(m&&a.utils.extensionmap[m]){return true}}return false}}})(jwplayer);(function(a){a.embed.flash=function(f,g,l,e,j){function m(o,n,p){var q=document.createElement("param");q.setAttribute("name",n);q.setAttribute("value",p);o.appendChild(q)}function k(o,p,n){return function(q){if(n){document.getElementById(j.id+"_wrapper").appendChild(p)}var s=document.getElementById(j.id).getPluginConfig("display");o.resize(s.width,s.height);var r={left:s.x,top:s.y};a.utils.css(p,r)}}function d(p){if(!p){return{}}var r={};for(var o in p){var n=p[o];for(var q in n){r[o+"."+q]=n[q]}}return r}function h(q,p){if(q[p]){var s=q[p];for(var o in s){var n=s[o];if(typeof n=="string"){if(!q[o]){q[o]=n}}else{for(var r in n){if(!q[o+"."+r]){q[o+"."+r]=n[r]}}}}delete q[p]}}function b(q){if(!q){return{}}var t={},s=[];for(var n in q){var p=a.utils.getPluginName(n);var o=q[n];s.push(n);for(var r in o){t[p+"."+r]=o[r]}}t.plugins=s.join(",");return t}function c(p){var n=p.netstreambasepath?"":"netstreambasepath="+encodeURIComponent(window.location.href.split("#")[0])+"&";for(var o in p){if(typeof(p[o])=="object"){n+=o+"="+encodeURIComponent("[[JSON]]"+a.utils.strings.jsonToString(p[o]))+"&"}else{n+=o+"="+encodeURIComponent(p[o])+"&"}}return n.substring(0,n.length-1)}this.embed=function(){l.id=j.id;var y;var q=a.utils.extend({},l);var n=q.width;var w=q.height;if(f.id+"_wrapper"==f.parentNode.id){y=document.getElementById(f.id+"_wrapper")}else{y=document.createElement("div");y.id=f.id+"_wrapper";a.utils.wrap(f,y);a.utils.css(y,{position:"relative",width:n,height:w})}var o=e.setupPlugins(j,q,k);if(o.length>0){a.utils.extend(q,b(o.plugins))}else{delete q.plugins}var r=["height","width","modes","events"];for(var u=0;u<r.length;u++){delete q[r[u]]}var p="opaque";if(q.wmode){p=q.wmode}h(q,"components");h(q,"providers");if(typeof q["dock.position"]!="undefined"){if(q["dock.position"].toString().toLowerCase()=="false"){q.dock=q["dock.position"];delete q["dock.position"]}}var x="#000000";var t;if(a.utils.isIE()){var v='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" bgcolor="'+x+'" width="100%" height="100%" id="'+f.id+'" name="'+f.id+'" tabindex=0"">';v+='<param name="movie" value="'+g.src+'">';v+='<param name="allowfullscreen" value="true">';v+='<param name="allowscriptaccess" value="always">';v+='<param name="seamlesstabbing" value="true">';v+='<param name="wmode" value="'+p+'">';v+='<param name="flashvars" value="'+c(q)+'">';v+="</object>";a.utils.setOuterHTML(f,v);t=document.getElementById(f.id)}else{var s=document.createElement("object");s.setAttribute("type","application/x-shockwave-flash");s.setAttribute("data",g.src);s.setAttribute("width","100%");s.setAttribute("height","100%");s.setAttribute("bgcolor","#000000");s.setAttribute("id",f.id);s.setAttribute("name",f.id);s.setAttribute("tabindex",0);m(s,"allowfullscreen","true");m(s,"allowscriptaccess","always");m(s,"seamlesstabbing","true");m(s,"wmode",p);m(s,"flashvars",c(q));f.parentNode.replaceChild(s,f);t=s}j.container=t;j.setPlayer(t,"flash")};this.supportsConfig=function(){if(a.utils.hasFlash()){if(l){var o=a.utils.getFirstPlaylistItemFromConfig(l);if(typeof o.file=="undefined"&&typeof o.levels=="undefined"){return true}else{if(o.file){return flashCanPlay(o.file,o.provider)}else{if(o.levels&&o.levels.length){for(var n=0;n<o.levels.length;n++){if(o.levels[n].file&&flashCanPlay(o.levels[n].file,o.provider)){return true}}}}}}else{return true}}return false};flashCanPlay=function(n,p){var o=["video","http","sound","image"];if(p&&(o.toString().indexOf(p<0))){return true}var q=a.utils.extension(n);if(!q){return true}if(a.utils.exists(a.utils.extensionmap[q])&&!a.utils.exists(a.utils.extensionmap[q].flash)){return false}return true}}})(jwplayer);(function(a){a.embed.html5=function(c,g,b,d,f){function e(j,k,h){return function(l){var m=document.getElementById(c.id+"_displayarea");if(h){m.appendChild(k)}var n=m.style;j.resize(parseInt(n.width.replace("px","")),parseInt(n.height.replace("px","")));k.left=n.left;k.top=n.top}}this.embed=function(){if(a.html5){d.setupPlugins(f,b,e);c.innerHTML="";var j=a.utils.extend({screencolor:"0x000000"},b);var h=["plugins","modes","events"];for(var k=0;k<h.length;k++){delete j[h[k]]}if(j.levels&&!j.sources){j.sources=b.levels}if(j.skin&&j.skin.toLowerCase().indexOf(".zip")>0){j.skin=j.skin.replace(/\.zip/i,".xml")}var l=new (a.html5(c)).setup(j);f.container=document.getElementById(f.id);f.setPlayer(l,"html5")}else{return null}};this.supportsConfig=function(){if(!!a.vid.canPlayType){if(b){var j=a.utils.getFirstPlaylistItemFromConfig(b);if(typeof j.file=="undefined"&&typeof j.levels=="undefined"){return true}else{if(j.file){return html5CanPlay(a.vid,j.file,j.provider,j.playlistfile)}else{if(j.levels&&j.levels.length){for(var h=0;h<j.levels.length;h++){if(j.levels[h].file&&html5CanPlay(a.vid,j.levels[h].file,j.provider,j.playlistfile)){return true}}}}}}else{return true}}return false};html5CanPlay=function(k,j,l,h){if(h){return false}if(l&&l=="youtube"){return true}if(l&&l!="video"&&l!="http"&&l!="sound"){return false}var m=a.utils.extension(j);if(!a.utils.exists(m)||!a.utils.exists(a.utils.extensionmap[m])){return true}if(!a.utils.exists(a.utils.extensionmap[m].html5)){return false}if(a.utils.isLegacyAndroid()&&m.match(/m4v|mp4/)){return true}return browserCanPlay(k,a.utils.extensionmap[m].html5)};browserCanPlay=function(j,h){if(!h){return true}if(j.canPlayType(h)){return true}else{if(h=="audio/mp3"&&navigator.userAgent.match(/safari/i)){return j.canPlayType("audio/mpeg")}else{return false}}}}})(jwplayer);(function(a){a.embed.logo=function(m,l,d){var j={prefix:"http://l.longtailvideo.com/"+l+"/",file:"",link:"",margin:8,out:0.5,over:1,timeout:5,hide:false,position:"bottom-left"};_css=a.utils.css;var b;var h;k();function k(){o();c();f()}function o(){if(j.prefix){var q=a.version.split(/\W/).splice(0,2).join("/");if(j.prefix.indexOf(q)<0){j.prefix+=q+"/"}}h=a.utils.extend({},j,m)}function p(){var s={border:"none",textDecoration:"none",position:"absolute",cursor:"pointer",zIndex:10};s.display=h.hide?"none":"block";var r=h.position.toLowerCase().split("-");for(var q in r){s[r[q]]=h.margin}return s}function c(){b=document.createElement("img");b.id=d+"_jwplayer_logo";b.style.display="none";b.onload=function(q){_css(b,p());e()};if(!h.file){return}if(h.file.indexOf("http://")===0){b.src=h.file}else{b.src=h.prefix+h.file}}if(!h.file){return}function f(){if(h.link){b.onmouseover=g;b.onmouseout=e;b.onclick=n}else{this.mouseEnabled=false}}function n(q){if(typeof q!="undefined"){q.preventDefault();q.stopPropagation()}if(h.link){window.open(h.link,"_blank")}return}function e(q){if(h.link){b.style.opacity=h.out}return}function g(q){if(h.hide){b.style.opacity=h.over}return}return b}})(jwplayer);(function(a){a.html5=function(b){var c=b;this.setup=function(d){a.utils.extend(this,new a.html5.api(c,d));return this};return this}})(jwplayer);(function(b){var d=b.utils;var c=d.css;b.html5.view=function(r,q,f){var u=r;var n=q;var x=f;var w;var g;var C;var s;var D;var p;var A;function z(){w=document.createElement("div");w.id=n.id;w.className=n.className;_videowrapper=document.createElement("div");_videowrapper.id=w.id+"_video_wrapper";n.id=w.id+"_video";c(w,{position:"relative",height:x.height,width:x.width,padding:0,backgroundColor:E(),zIndex:0});function E(){if(u.skin.getComponentSettings("display")&&u.skin.getComponentSettings("display").backgroundcolor){return u.skin.getComponentSettings("display").backgroundcolor}return parseInt("000000",16)}c(n,{width:x.width,height:x.height,top:0,left:0,zIndex:1,margin:"auto",display:"block"});c(_videowrapper,{overflow:"hidden",position:"absolute",top:0,left:0,bottom:0,right:0});d.wrap(n,w);d.wrap(n,_videowrapper);s=document.createElement("div");s.id=w.id+"_displayarea";w.appendChild(s)}function k(){for(var E=0;E<x.plugins.order.length;E++){var F=x.plugins.order[E];if(d.exists(x.plugins.object[F].getDisplayElement)){x.plugins.object[F].height=d.parseDimension(x.plugins.object[F].getDisplayElement().style.height);x.plugins.object[F].width=d.parseDimension(x.plugins.object[F].getDisplayElement().style.width);x.plugins.config[F].currentPosition=x.plugins.config[F].position}}v()}function m(E){c(s,{display:x.getMedia().hasChrome()?"none":"block"})}function v(F){var H=x.getMedia()?x.getMedia().getDisplayElement():null;if(d.exists(H)){if(A!=H){if(A&&A.parentNode){A.parentNode.replaceChild(H,A)}A=H}for(var E=0;E<x.plugins.order.length;E++){var G=x.plugins.order[E];if(d.exists(x.plugins.object[G].getDisplayElement)){x.plugins.config[G].currentPosition=x.plugins.config[G].position}}}j(x.width,x.height)}this.setup=function(){if(x&&x.getMedia()){n=x.getMedia().getDisplayElement()}z();k();u.jwAddEventListener(b.api.events.JWPLAYER_PLAYER_STATE,m);u.jwAddEventListener(b.api.events.JWPLAYER_MEDIA_LOADED,v);u.jwAddEventListener(b.api.events.JWPLAYER_MEDIA_META,function(){y()});var E;if(d.exists(window.onresize)){E=window.onresize}window.onresize=function(F){if(d.exists(E)){try{E(F)}catch(H){}}if(u.jwGetFullscreen()){var G=document.body.getBoundingClientRect();x.width=Math.abs(G.left)+Math.abs(G.right);x.height=window.innerHeight}j(x.width,x.height)}};function h(E){switch(E.keyCode){case 27:if(u.jwGetFullscreen()){u.jwSetFullscreen(false)}break;case 32:if(u.jwGetState()!=b.api.events.state.IDLE&&u.jwGetState()!=b.api.events.state.PAUSED){u.jwPause()}else{u.jwPlay()}break}}function j(H,E){if(w.style.display=="none"){return}var G=[].concat(x.plugins.order);G.reverse();D=G.length+2;if(!x.fullscreen){x.width=H;x.height=E;g=H;C=E;c(s,{top:0,bottom:0,left:0,right:0,width:H,height:E,position:"relative"});c(w,{height:C,width:g});var F=o(t,G);if(F.length>0){D+=F.length;var J=F.indexOf("playlist"),I=F.indexOf("controlbar");if(J>=0&&I>=0){F[J]=F.splice(I,1,F[J])[0]}o(l,F,true)}}else{if(!(navigator&&navigator.vendor&&navigator.vendor.indexOf("Apple")==0)){o(B,G,true)}}y()}function o(J,G,H){var F=[];for(var E=0;E<G.length;E++){var K=G[E];if(d.exists(x.plugins.object[K].getDisplayElement)){if(x.plugins.config[K].currentPosition!=b.html5.view.positions.NONE){var I=J(K,D--);if(!I){F.push(K)}else{x.plugins.object[K].resize(I.width,I.height);if(H){delete I.width;delete I.height}c(x.plugins.object[K].getDisplayElement(),I)}}else{c(x.plugins.object[K].getDisplayElement(),{display:"none"})}}}return F}function t(F,G){if(d.exists(x.plugins.object[F].getDisplayElement)){if(x.plugins.config[F].position&&a(x.plugins.config[F].position)){if(!d.exists(x.plugins.object[F].getDisplayElement().parentNode)){w.appendChild(x.plugins.object[F].getDisplayElement())}var E=e(F);E.zIndex=G;return E}}return false}function l(G,H){if(!d.exists(x.plugins.object[G].getDisplayElement().parentNode)){s.appendChild(x.plugins.object[G].getDisplayElement())}var E=x.width,F=x.height;if(typeof x.width=="string"&&x.width.lastIndexOf("%")>-1){percentage=parseFloat(x.width.substring(0,x.width.lastIndexOf("%")))/100;E=Math.round(window.innerWidth*percentage)}if(typeof x.height=="string"&&x.height.lastIndexOf("%")>-1){percentage=parseFloat(x.height.substring(0,x.height.lastIndexOf("%")))/100;F=Math.round(window.innerHeight*percentage)}return{position:"absolute",width:(E-d.parseDimension(s.style.left)-d.parseDimension(s.style.right)),height:(F-d.parseDimension(s.style.top)-d.parseDimension(s.style.bottom)),zIndex:H}}function B(E,F){return{position:"fixed",width:x.width,height:x.height,zIndex:F}}function y(){if(!d.exists(x.getMedia())){return}s.style.position="absolute";var H=x.getMedia().getDisplayElement();if(H&&H.tagName.toLowerCase()=="video"){H.style.position="absolute";var E,I;if(s.style.width.toString().lastIndexOf("%")>-1||s.style.width.toString().lastIndexOf("%")>-1){var F=s.getBoundingClientRect();E=Math.abs(F.left)+Math.abs(F.right);I=Math.abs(F.top)+Math.abs(F.bottom)}else{E=d.parseDimension(s.style.width);I=d.parseDimension(s.style.height)}if(H.parentNode){H.parentNode.style.left=s.style.left;H.parentNode.style.top=s.style.top}d.stretch(u.jwGetStretching(),H,E,I,H.videoWidth?H.videoWidth:400,H.videoHeight?H.videoHeight:300)}else{var G=x.plugins.object.display.getDisplayElement();if(G){x.getMedia().resize(d.parseDimension(G.style.width),d.parseDimension(G.style.height))}else{x.getMedia().resize(d.parseDimension(s.style.width),d.parseDimension(s.style.height))}}}function e(F){var G={position:"absolute",margin:0,padding:0,top:null};var E=x.plugins.config[F].currentPosition.toLowerCase();switch(E.toUpperCase()){case b.html5.view.positions.TOP:G.top=d.parseDimension(s.style.top);G.left=d.parseDimension(s.style.left);G.width=g-d.parseDimension(s.style.left)-d.parseDimension(s.style.right);G.height=x.plugins.object[F].height;s.style[E]=d.parseDimension(s.style[E])+x.plugins.object[F].height+"px";s.style.height=d.parseDimension(s.style.height)-G.height+"px";break;case b.html5.view.positions.RIGHT:G.top=d.parseDimension(s.style.top);G.right=d.parseDimension(s.style.right);G.width=x.plugins.object[F].width;G.height=C-d.parseDimension(s.style.top)-d.parseDimension(s.style.bottom);s.style[E]=d.parseDimension(s.style[E])+x.plugins.object[F].width+"px";s.style.width=d.parseDimension(s.style.width)-G.width+"px";break;case b.html5.view.positions.BOTTOM:G.bottom=d.parseDimension(s.style.bottom);G.left=d.parseDimension(s.style.left);G.width=g-d.parseDimension(s.style.left)-d.parseDimension(s.style.right);G.height=x.plugins.object[F].height;s.style[E]=d.parseDimension(s.style[E])+x.plugins.object[F].height+"px";s.style.height=d.parseDimension(s.style.height)-G.height+"px";break;case b.html5.view.positions.LEFT:G.top=d.parseDimension(s.style.top);G.left=d.parseDimension(s.style.left);G.width=x.plugins.object[F].width;G.height=C-d.parseDimension(s.style.top)-d.parseDimension(s.style.bottom);s.style[E]=d.parseDimension(s.style[E])+x.plugins.object[F].width+"px";s.style.width=d.parseDimension(s.style.width)-G.width+"px";break;default:break}return G}this.resize=j;this.fullscreen=function(H){if(navigator&&navigator.vendor&&navigator.vendor.indexOf("Apple")===0){if(x.getMedia().getDisplayElement().webkitSupportsFullscreen){if(H){try{x.getMedia().getDisplayElement().webkitEnterFullscreen()}catch(G){}}else{try{x.getMedia().getDisplayElement().webkitExitFullscreen()}catch(G){}}}}else{if(H){document.onkeydown=h;clearInterval(p);var F=document.body.getBoundingClientRect();x.width=Math.abs(F.left)+Math.abs(F.right);x.height=window.innerHeight;var E={position:"fixed",width:"100%",height:"100%",top:0,left:0,zIndex:2147483000};c(w,E);E.zIndex=1;if(x.getMedia()&&x.getMedia().getDisplayElement()){c(x.getMedia().getDisplayElement(),E)}E.zIndex=2;c(s,E)}else{document.onkeydown="";x.width=g;x.height=C;c(w,{position:"relative",height:x.height,width:x.width,zIndex:0})}j(x.width,x.height)}}};function a(e){return([b.html5.view.positions.TOP,b.html5.view.positions.RIGHT,b.html5.view.positions.BOTTOM,b.html5.view.positions.LEFT].toString().indexOf(e.toUpperCase())>-1)}b.html5.view.positions={TOP:"TOP",RIGHT:"RIGHT",BOTTOM:"BOTTOM",LEFT:"LEFT",OVER:"OVER",NONE:"NONE"}})(jwplayer);(function(a){var b={backgroundcolor:"",margin:10,font:"Arial,sans-serif",fontsize:10,fontcolor:parseInt("000000",16),fontstyle:"normal",fontweight:"bold",buttoncolor:parseInt("ffffff",16),position:a.html5.view.positions.BOTTOM,idlehide:false,layout:{left:{position:"left",elements:[{name:"play",type:"button"},{name:"divider",type:"divider"},{name:"prev",type:"button"},{name:"divider",type:"divider"},{name:"next",type:"button"},{name:"divider",type:"divider"},{name:"elapsed",type:"text"}]},center:{position:"center",elements:[{name:"time",type:"slider"}]},right:{position:"right",elements:[{name:"duration",type:"text"},{name:"blank",type:"button"},{name:"divider",type:"divider"},{name:"mute",type:"button"},{name:"volume",type:"slider"},{name:"divider",type:"divider"},{name:"fullscreen",type:"button"}]}}};_utils=a.utils;_css=_utils.css;_hide=function(c){_css(c,{display:"none"})};_show=function(c){_css(c,{display:"block"})};a.html5.controlbar=function(l,V){var k=l;var D=_utils.extend({},b,k.skin.getComponentSettings("controlbar"),V);if(D.position==a.html5.view.positions.NONE||typeof a.html5.view.positions[D.position]=="undefined"){return}if(_utils.mapLength(k.skin.getComponentLayout("controlbar"))>0){D.layout=k.skin.getComponentLayout("controlbar")}var ac;var P;var ab;var E;var v="none";var g;var j;var ad;var f;var e;var y;var Q={};var p=false;var c={};var Y;var h=false;var o;var d;var S=false;var G=false;var W=new a.html5.eventdispatcher();_utils.extend(this,W);function J(){if(!Y){Y=k.skin.getSkinElement("controlbar","background");if(!Y){Y={width:0,height:0,src:null}}}return Y}function N(){ab=0;E=0;P=0;if(!p){var ak={height:J().height,backgroundColor:D.backgroundcolor};ac=document.createElement("div");ac.id=k.id+"_jwplayer_controlbar";_css(ac,ak)}var aj=(k.skin.getSkinElement("controlbar","capLeft"));var ai=(k.skin.getSkinElement("controlbar","capRight"));if(aj){x("capLeft","left",false,ac)}var al={position:"absolute",height:J().height,left:(aj?aj.width:0),zIndex:0};Z("background",ac,al,"img");if(J().src){Q.background.src=J().src}al.zIndex=1;Z("elements",ac,al);if(ai){x("capRight","right",false,ac)}}this.getDisplayElement=function(){return ac};this.resize=function(ak,ai){_utils.cancelAnimation(ac);document.getElementById(k.id).onmousemove=A;e=ak;y=ai;if(G!=k.jwGetFullscreen()){G=k.jwGetFullscreen();d=undefined}var aj=w();A();I({id:k.id,duration:ad,position:j});u({id:k.id,bufferPercent:f});return aj};this.show=function(){if(h){h=false;_show(ac);T()}};this.hide=function(){if(!h){h=true;_hide(ac);aa()}};function q(){var aj=["timeSlider","volumeSlider","timeSliderRail","volumeSliderRail"];for(var ak in aj){var ai=aj[ak];if(typeof Q[ai]!="undefined"){c[ai]=Q[ai].getBoundingClientRect()}}}function A(ai){if(h){return}if(D.position==a.html5.view.positions.OVER||k.jwGetFullscreen()){clearTimeout(o);switch(k.jwGetState()){case a.api.events.state.PAUSED:case a.api.events.state.IDLE:if(!D.idlehide||_utils.exists(ai)){U()}if(D.idlehide){o=setTimeout(function(){z()},2000)}break;default:if(ai){U()}o=setTimeout(function(){z()},2000);break}}}function z(ai){aa();_utils.cancelAnimation(ac);_utils.fadeTo(ac,0,0.1,1,0)}function U(){T();_utils.cancelAnimation(ac);_utils.fadeTo(ac,1,0,1,0)}function H(ai){return function(){if(S&&d!=ai){d=ai;W.sendEvent(ai,{component:"controlbar",boundingRect:O()})}}}var T=H(a.api.events.JWPLAYER_COMPONENT_SHOW);var aa=H(a.api.events.JWPLAYER_COMPONENT_HIDE);function O(){if(D.position==a.html5.view.positions.OVER||k.jwGetFullscreen()){return _utils.getDimensions(ac)}else{return{x:0,y:0,width:0,height:0}}}function Z(am,al,ak,ai){var aj;if(!p){if(!ai){ai="div"}aj=document.createElement(ai);Q[am]=aj;aj.id=ac.id+"_"+am;al.appendChild(aj)}else{aj=document.getElementById(ac.id+"_"+am)}if(_utils.exists(ak)){_css(aj,ak)}return aj}function M(){ah(D.layout.left);ah(D.layout.right,-1);ah(D.layout.center)}function ah(al,ai){var am=al.position=="right"?"right":"left";var ak=_utils.extend([],al.elements);if(_utils.exists(ai)){ak.reverse()}for(var aj=0;aj<ak.length;aj++){C(ak[aj],am)}}function K(){return P++}function C(am,ao){var al,aj,ak,ai,aq;if(am.type=="divider"){x("divider"+K(),ao,true,undefined,undefined,am.width,am.element);return}switch(am.name){case"play":x("playButton",ao,false);x("pauseButton",ao,true);R("playButton","jwPlay");R("pauseButton","jwPause");break;case"prev":x("prevButton",ao,true);R("prevButton","jwPlaylistPrev");break;case"stop":x("stopButton",ao,true);R("stopButton","jwStop");break;case"next":x("nextButton",ao,true);R("nextButton","jwPlaylistNext");break;case"elapsed":x("elapsedText",ao,true);break;case"time":aj=!_utils.exists(k.skin.getSkinElement("controlbar","timeSliderCapLeft"))?0:k.skin.getSkinElement("controlbar","timeSliderCapLeft").width;ak=!_utils.exists(k.skin.getSkinElement("controlbar","timeSliderCapRight"))?0:k.skin.getSkinElement("controlbar","timeSliderCapRight").width;al=ao=="left"?aj:ak;ai=k.skin.getSkinElement("controlbar","timeSliderRail").width+aj+ak;aq={height:J().height,position:"absolute",top:0,width:ai};aq[ao]=ao=="left"?ab:E;var an=Z("timeSlider",Q.elements,aq);x("timeSliderCapLeft",ao,true,an,ao=="left"?0:al);x("timeSliderRail",ao,false,an,al);x("timeSliderBuffer",ao,false,an,al);x("timeSliderProgress",ao,false,an,al);x("timeSliderThumb",ao,false,an,al);x("timeSliderCapRight",ao,true,an,ao=="right"?0:al);X("time");break;case"fullscreen":x("fullscreenButton",ao,false);x("normalscreenButton",ao,true);R("fullscreenButton","jwSetFullscreen",true);R("normalscreenButton","jwSetFullscreen",false);break;case"volume":aj=!_utils.exists(k.skin.getSkinElement("controlbar","volumeSliderCapLeft"))?0:k.skin.getSkinElement("controlbar","volumeSliderCapLeft").width;ak=!_utils.exists(k.skin.getSkinElement("controlbar","volumeSliderCapRight"))?0:k.skin.getSkinElement("controlbar","volumeSliderCapRight").width;al=ao=="left"?aj:ak;ai=k.skin.getSkinElement("controlbar","volumeSliderRail").width+aj+ak;aq={height:J().height,position:"absolute",top:0,width:ai};aq[ao]=ao=="left"?ab:E;var ap=Z("volumeSlider",Q.elements,aq);x("volumeSliderCapLeft",ao,true,ap,ao=="left"?0:al);x("volumeSliderRail",ao,true,ap,al);x("volumeSliderProgress",ao,false,ap,al);x("volumeSliderCapRight",ao,true,ap,ao=="right"?0:al);X("volume");break;case"mute":x("muteButton",ao,false);x("unmuteButton",ao,true);R("muteButton","jwSetMute",true);R("unmuteButton","jwSetMute",false);break;case"duration":x("durationText",ao,true);break}}function x(al,ao,aj,ar,am,ai,ak){if(_utils.exists(k.skin.getSkinElement("controlbar",al))||al.indexOf("Text")>0||al.indexOf("divider")===0){var an={height:J().height,position:"absolute",display:"block",top:0};if((al.indexOf("next")===0||al.indexOf("prev")===0)&&k.jwGetPlaylist().length<2){aj=false;an.display="none"}var at;if(al.indexOf("Text")>0){al.innerhtml="00:00";an.font=D.fontsize+"px/"+(J().height+1)+"px "+D.font;an.color=D.fontcolor;an.textAlign="center";an.fontWeight=D.fontweight;an.fontStyle=D.fontstyle;an.cursor="default";at=14+3*D.fontsize}else{if(al.indexOf("divider")===0){if(ai){if(!isNaN(parseInt(ai))){at=parseInt(ai)}}else{if(ak){var ap=k.skin.getSkinElement("controlbar",ak);if(ap){an.background="url("+ap.src+") repeat-x center left";at=ap.width}}else{an.background="url("+k.skin.getSkinElement("controlbar","divider").src+") repeat-x center left";at=k.skin.getSkinElement("controlbar","divider").width}}}else{an.background="url("+k.skin.getSkinElement("controlbar",al).src+") repeat-x center left";at=k.skin.getSkinElement("controlbar",al).width}}if(ao=="left"){an.left=isNaN(am)?ab:am;if(aj){ab+=at}}else{if(ao=="right"){an.right=isNaN(am)?E:am;if(aj){E+=at}}}if(_utils.typeOf(ar)=="undefined"){ar=Q.elements}an.width=at;if(p){_css(Q[al],an)}else{var aq=Z(al,ar,an);if(_utils.exists(k.skin.getSkinElement("controlbar",al+"Over"))){aq.onmouseover=function(au){aq.style.backgroundImage=["url(",k.skin.getSkinElement("controlbar",al+"Over").src,")"].join("")};aq.onmouseout=function(au){aq.style.backgroundImage=["url(",k.skin.getSkinElement("controlbar",al).src,")"].join("")}}}}}function F(){k.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED,B);k.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_ITEM,s);k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_BUFFER,u);k.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE,r);k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_TIME,I);k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_MUTE,ag);k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_VOLUME,m);k.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_COMPLETE,L)}function B(){N();M();w();ae()}function s(ai){ad=k.jwGetPlaylist()[ai.index].duration;I({id:k.id,duration:ad,position:0});u({id:k.id,bufferProgress:0})}function ae(){I({id:k.id,duration:k.jwGetDuration(),position:0});u({id:k.id,bufferProgress:0});ag({id:k.id,mute:k.jwGetMute()});r({id:k.id,newstate:a.api.events.state.IDLE});m({id:k.id,volume:k.jwGetVolume()})}function R(ak,al,aj){if(p){return}if(_utils.exists(k.skin.getSkinElement("controlbar",ak))){var ai=Q[ak];if(_utils.exists(ai)){_css(ai,{cursor:"pointer"});if(al=="fullscreen"){ai.onmouseup=function(am){am.stopPropagation();k.jwSetFullscreen(!k.jwGetFullscreen())}}else{ai.onmouseup=function(am){am.stopPropagation();if(_utils.exists(aj)){k[al](aj)}else{k[al]()}}}}}}function X(ai){if(p){return}var aj=Q[ai+"Slider"];_css(Q.elements,{cursor:"pointer"});_css(aj,{cursor:"pointer"});aj.onmousedown=function(ak){v=ai};aj.onmouseup=function(ak){ak.stopPropagation();af(ak.pageX)};aj.onmousemove=function(ak){if(v=="time"){g=true;var al=ak.pageX-c[ai+"Slider"].left-window.pageXOffset;_css(Q.timeSliderThumb,{left:al})}}}function af(aj){g=false;var ai;if(v=="time"){ai=aj-c.timeSliderRail.left+window.pageXOffset;var al=ai/c.timeSliderRail.width*ad;if(al<0){al=0}else{if(al>ad){al=ad-3}}if(k.jwGetState()==a.api.events.state.PAUSED||k.jwGetState()==a.api.events.state.IDLE){k.jwPlay()}k.jwSeek(al)}else{if(v=="volume"){ai=aj-c.volumeSliderRail.left-window.pageXOffset;var ak=Math.round(ai/c.volumeSliderRail.width*100);if(ak<0){ak=0}else{if(ak>100){ak=100}}if(k.jwGetMute()){k.jwSetMute(false)}k.jwSetVolume(ak)}}v="none"}function u(aj){if(_utils.exists(aj.bufferPercent)){f=aj.bufferPercent}if(c.timeSliderRail){var ak=c.timeSliderRail.width;var ai=isNaN(Math.round(ak*f/100))?0:Math.round(ak*f/100);_css(Q.timeSliderBuffer,{width:ai})}}function ag(ai){if(ai.mute){_hide(Q.muteButton);_show(Q.unmuteButton);_hide(Q.volumeSliderProgress)}else{_show(Q.muteButton);_hide(Q.unmuteButton);_show(Q.volumeSliderProgress)}}function r(ai){if(ai.newstate==a.api.events.state.BUFFERING||ai.newstate==a.api.events.state.PLAYING){_show(Q.pauseButton);_hide(Q.playButton)}else{_hide(Q.pauseButton);_show(Q.playButton)}A();if(ai.newstate==a.api.events.state.IDLE){_hide(Q.timeSliderBuffer);_hide(Q.timeSliderProgress);_hide(Q.timeSliderThumb);I({id:k.id,duration:k.jwGetDuration(),position:0})}else{_show(Q.timeSliderBuffer);if(ai.newstate!=a.api.events.state.BUFFERING){_show(Q.timeSliderProgress);_show(Q.timeSliderThumb)}}}function L(ai){u({bufferPercent:0});I(_utils.extend(ai,{position:0,duration:ad}))}function I(al){if(_utils.exists(al.position)){j=al.position}if(_utils.exists(al.duration)){ad=al.duration}var aj=(j===ad===0)?0:j/ad;var am=c.timeSliderRail;if(am){var ai=isNaN(Math.round(am.width*aj))?0:Math.round(am.width*aj);var ak=ai;if(Q.timeSliderProgress){Q.timeSliderProgress.style.width=ai+"px";if(!g){if(Q.timeSliderThumb){Q.timeSliderThumb.style.left=ak+"px"}}}}if(Q.durationText){Q.durationText.innerHTML=_utils.timeFormat(ad)}if(Q.elapsedText){Q.elapsedText.innerHTML=_utils.timeFormat(j)}}function n(){var am,aj;var ak=document.getElementById(ac.id+"_elements");if(!ak){return}var al=ak.childNodes;for(var ai in ak.childNodes){if(isNaN(parseInt(ai,10))){continue}if(al[ai].id.indexOf(ac.id+"_divider")===0&&aj&&aj.id.indexOf(ac.id+"_divider")===0&&al[ai].style.backgroundImage==aj.style.backgroundImage){al[ai].style.display="none"}else{if(al[ai].id.indexOf(ac.id+"_divider")===0&&am&&am.style.display!="none"){al[ai].style.display="block"}}if(al[ai].style.display!="none"){aj=al[ai]}am=al[ai]}}function w(){n();if(k.jwGetFullscreen()){_show(Q.normalscreenButton);_hide(Q.fullscreenButton)}else{_hide(Q.normalscreenButton);_show(Q.fullscreenButton)}var aj={width:e};var ai={};if(D.position==a.html5.view.positions.OVER||k.jwGetFullscreen()){aj.left=D.margin;aj.width-=2*D.margin;aj.top=y-J().height-D.margin;aj.height=J().height}var al=k.skin.getSkinElement("controlbar","capLeft");var ak=k.skin.getSkinElement("controlbar","capRight");ai.left=al?al.width:0;ai.width=aj.width-ai.left-(ak?ak.width:0);var am=!_utils.exists(k.skin.getSkinElement("controlbar","timeSliderCapLeft"))?0:k.skin.getSkinElement("controlbar","timeSliderCapLeft").width;_css(Q.timeSliderRail,{width:(ai.width-ab-E),left:am});if(_utils.exists(Q.timeSliderCapRight)){_css(Q.timeSliderCapRight,{left:am+(ai.width-ab-E)})}_css(ac,aj);_css(Q.elements,ai);_css(Q.background,ai);q();return aj}function m(am){if(_utils.exists(Q.volumeSliderRail)){var ak=isNaN(am.volume/100)?1:am.volume/100;var al=_utils.parseDimension(Q.volumeSliderRail.style.width);var ai=isNaN(Math.round(al*ak))?0:Math.round(al*ak);var an=_utils.parseDimension(Q.volumeSliderRail.style.right);var aj=(!_utils.exists(k.skin.getSkinElement("controlbar","volumeSliderCapLeft")))?0:k.skin.getSkinElement("controlbar","volumeSliderCapLeft").width;_css(Q.volumeSliderProgress,{width:ai,left:aj});if(_utils.exists(Q.volumeSliderCapLeft)){_css(Q.volumeSliderCapLeft,{left:0})}}}function t(){N();M();q();p=true;F();D.idlehide=(D.idlehide.toString().toLowerCase()=="true");if(D.position==a.html5.view.positions.OVER&&D.idlehide){ac.style.opacity=0;S=true}else{setTimeout((function(){S=true;T()}),1)}ae()}t();return this}})(jwplayer);(function(b){var a=["width","height","state","playlist","item","position","buffer","duration","volume","mute","fullscreen"];var c=b.utils;b.html5.controller=function(z,w,h,v){var C=z;var G=h;var g=v;var o=w;var J=true;var e=-1;var A=c.exists(G.config.debug)&&(G.config.debug.toString().toLowerCase()=="console");var m=new b.html5.eventdispatcher(o.id,A);c.extend(this,m);var E=[];var d=false;function r(M){if(d){m.sendEvent(M.type,M)}else{E.push(M)}}function K(M){if(!d){m.sendEvent(b.api.events.JWPLAYER_READY,M);if(b.utils.exists(window.playerReady)){playerReady(M)}if(b.utils.exists(window[h.config.playerReady])){window[h.config.playerReady](M)}while(E.length>0){var O=E.shift();m.sendEvent(O.type,O)}if(h.config.autostart&&!b.utils.isIOS()){t(G.item)}while(p.length>0){var N=p.shift();x(N.method,N.arguments)}d=true}}G.addGlobalListener(r);G.addEventListener(b.api.events.JWPLAYER_MEDIA_BUFFER_FULL,function(){G.getMedia().play()});G.addEventListener(b.api.events.JWPLAYER_MEDIA_TIME,function(M){if(M.position>=G.playlist[G.item].start&&e>=0){G.playlist[G.item].start=e;e=-1}});G.addEventListener(b.api.events.JWPLAYER_MEDIA_COMPLETE,function(M){setTimeout(s,25)});function u(){try{f(G.item);if(G.playlist[G.item].levels[0].file.length>0){if(J||G.state==b.api.events.state.IDLE){G.getMedia().load(G.playlist[G.item]);J=false}else{if(G.state==b.api.events.state.PAUSED){G.getMedia().play()}}}return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function I(){try{if(G.playlist[G.item].levels[0].file.length>0){switch(G.state){case b.api.events.state.PLAYING:case b.api.events.state.BUFFERING:G.getMedia().pause();break}}return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function D(M){try{if(G.playlist[G.item].levels[0].file.length>0){if(typeof M!="number"){M=parseFloat(M)}switch(G.state){case b.api.events.state.IDLE:if(e<0){e=G.playlist[G.item].start;G.playlist[G.item].start=M}u();break;case b.api.events.state.PLAYING:case b.api.events.state.PAUSED:case b.api.events.state.BUFFERING:G.seek(M);break}}return true}catch(N){m.sendEvent(b.api.events.JWPLAYER_ERROR,N)}return false}function n(M){if(!c.exists(M)){M=true}try{G.getMedia().stop(M);return true}catch(N){m.sendEvent(b.api.events.JWPLAYER_ERROR,N)}return false}function k(){try{if(G.playlist[G.item].levels[0].file.length>0){if(G.config.shuffle){f(y())}else{if(G.item+1==G.playlist.length){f(0)}else{f(G.item+1)}}}if(G.state!=b.api.events.state.IDLE){var N=G.state;G.state=b.api.events.state.IDLE;m.sendEvent(b.api.events.JWPLAYER_PLAYER_STATE,{oldstate:N,newstate:b.api.events.state.IDLE})}u();return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function j(){try{if(G.playlist[G.item].levels[0].file.length>0){if(G.config.shuffle){f(y())}else{if(G.item===0){f(G.playlist.length-1)}else{f(G.item-1)}}}if(G.state!=b.api.events.state.IDLE){var N=G.state;G.state=b.api.events.state.IDLE;m.sendEvent(b.api.events.JWPLAYER_PLAYER_STATE,{oldstate:N,newstate:b.api.events.state.IDLE})}u();return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function y(){var M=null;if(G.playlist.length>1){while(!c.exists(M)){M=Math.floor(Math.random()*G.playlist.length);if(M==G.item){M=null}}}else{M=0}return M}function t(N){if(!G.playlist||!G.playlist[N]){return false}try{if(G.playlist[N].levels[0].file.length>0){var O=G.state;if(O!==b.api.events.state.IDLE){if(G.playlist[G.item].provider==G.playlist[N].provider){n(false)}else{n()}}f(N);u()}return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function f(M){if(!G.playlist[M]){return}G.setActiveMediaProvider(G.playlist[M]);if(G.item!=M){G.item=M;J=true;m.sendEvent(b.api.events.JWPLAYER_PLAYLIST_ITEM,{index:M})}}function H(N){try{f(G.item);var O=G.getMedia();switch(typeof(N)){case"number":O.volume(N);break;case"string":O.volume(parseInt(N,10));break}return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function q(N){try{f(G.item);var O=G.getMedia();if(typeof N=="undefined"){O.mute(!G.mute)}else{if(N.toString().toLowerCase()=="true"){O.mute(true)}else{O.mute(false)}}return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function l(N,M){try{G.width=N;G.height=M;g.resize(N,M);m.sendEvent(b.api.events.JWPLAYER_RESIZE,{width:G.width,height:G.height});return true}catch(O){m.sendEvent(b.api.events.JWPLAYER_ERROR,O)}return false}function B(N){try{if(typeof N=="undefined"){G.fullscreen=!G.fullscreen;g.fullscreen(!G.fullscreen)}else{if(N.toString().toLowerCase()=="true"){G.fullscreen=true;g.fullscreen(true)}else{G.fullscreen=false;g.fullscreen(false)}}m.sendEvent(b.api.events.JWPLAYER_RESIZE,{width:G.width,height:G.height});m.sendEvent(b.api.events.JWPLAYER_FULLSCREEN,{fullscreen:N});return true}catch(M){m.sendEvent(b.api.events.JWPLAYER_ERROR,M)}return false}function L(M){try{n();G.loadPlaylist(M);f(G.item);return true}catch(N){m.sendEvent(b.api.events.JWPLAYER_ERROR,N)}return false}b.html5.controller.repeatoptions={LIST:"LIST",ALWAYS:"ALWAYS",SINGLE:"SINGLE",NONE:"NONE"};function s(){switch(G.config.repeat.toUpperCase()){case b.html5.controller.repeatoptions.SINGLE:u();break;case b.html5.controller.repeatoptions.ALWAYS:if(G.item==G.playlist.length-1&&!G.config.shuffle){t(0)}else{k()}break;case b.html5.controller.repeatoptions.LIST:if(G.item==G.playlist.length-1&&!G.config.shuffle){n();f(0)}else{k()}break;default:n();break}}var p=[];function F(M){return function(){if(d){x(M,arguments)}else{p.push({method:M,arguments:arguments})}}}function x(O,N){var M=[];for(i=0;i<N.length;i++){M.push(N[i])}O.apply(this,M)}this.play=F(u);this.pause=F(I);this.seek=F(D);this.stop=F(n);this.next=F(k);this.prev=F(j);this.item=F(t);this.setVolume=F(H);this.setMute=F(q);this.resize=F(l);this.setFullscreen=F(B);this.load=F(L);this.playerReady=K}})(jwplayer);(function(a){a.html5.defaultSkin=function(){this.text='<?xml version="1.0" ?><skin author="LongTail Video" name="Five" version="1.0"><settings><setting name="backcolor" value="0xFFFFFF"/><setting name="frontcolor" value="0x000000"/><setting name="lightcolor" value="0x000000"/><setting name="screencolor" value="0x000000"/></settings><components><component name="controlbar"><settings><setting name="margin" value="20"/><setting name="fontsize" value="11"/></settings><elements><element name="background" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAIAAABvFaqvAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFJJREFUeNrslLENwAAIwxLU/09j5AiOgD5hVQzNAVY8JK4qEfHMIKBnd2+BQlBINaiRtL/aV2rdzYBsM6CIONbI1NZENTr3RwdB2PlnJgJ6BRgA4hwu5Qg5iswAAAAASUVORK5CYII="/><element name="capLeft" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAYCAIAAAC0rgCNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD5JREFUeNosi8ENACAMAgnuv14H0Z8asI19XEjhOiKCMmibVgJTUt7V6fe9KXOtSQCfctJHu2q3/ot79hNgANc2OTz9uTCCAAAAAElFTkSuQmCC"/><element name="capRight" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAYCAIAAAC0rgCNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD5JREFUeNosi8ENACAMAgnuv14H0Z8asI19XEjhOiKCMmibVgJTUt7V6fe9KXOtSQCfctJHu2q3/ot79hNgANc2OTz9uTCCAAAAAElFTkSuQmCC"/><element name="divider" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAYCAIAAAC0rgCNAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD5JREFUeNosi8ENACAMAgnuv14H0Z8asI19XEjhOiKCMmibVgJTUt7V6fe9KXOtSQCfctJHu2q3/ot79hNgANc2OTz9uTCCAAAAAElFTkSuQmCC"/><element name="playButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEhJREFUeNpiYqABYBo1dNRQ+hr6H4jvA3E8NS39j4SpZvh/LJig4YxEGEqy3kET+w+AOGFQRhTJhrEQkGcczfujhg4CQwECDADpTRWU/B3wHQAAAABJRU5ErkJggg=="/><element name="pauseButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAChJREFUeNpiYBgFo2DwA0YC8v/R1P4nRu+ooaOGUtnQUTAKhgIACDAAFCwQCfAJ4gwAAAAASUVORK5CYII="/><element name="prevButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEtJREFUeNpiYBgFo2Dog/9QDAPyQHweTYwiQ/2B+D0Wi8g2tB+JTdBQRiIMJVkvEy0iglhDF9Aq9uOpHVEwoE+NJDUKRsFgAAABBgDe2hqZcNNL0AAAAABJRU5ErkJggg=="/><element name="nextButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABUAAAAYCAYAAAAVibZIAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAElJREFUeNpiYBgFo2Dog/9AfB6I5dHE/lNqKAi/B2J/ahsKw/3EGMpIhKEk66WJoaR6fz61IyqemhEFSlL61ExSo2AUDAYAEGAAiG4hj+5t7M8AAAAASUVORK5CYII="/><element name="timeSliderRail" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADxJREFUeNpiYBgFo2AU0Bwwzluw+D8tLWARFhKiqQ9YuLg4aWsBGxs7bS1gZ6e5BWyjSX0UjIKhDgACDABlYQOGh5pYywAAAABJRU5ErkJggg=="/><element name="timeSliderBuffer" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD1JREFUeNpiYBgFo2AU0Bww1jc0/aelBSz8/Pw09QELOzs7bS1gY2OjrQWsrKy09gHraFIfBaNgqAOAAAMAvy0DChXHsZMAAAAASUVORK5CYII="/><element name="timeSliderProgress" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAClJREFUeNpiYBgFo2AU0BwwAvF/WlrARGsfjFow8BaMglEwCugAAAIMAOHfAQunR+XzAAAAAElFTkSuQmCC"/><element name="timeSliderThumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAMAAAAICAYAAAA870V8AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAABZJREFUeNpiZICA/yCCiQEJUJcDEGAAY0gBD1/m7Q0AAAAASUVORK5CYII="/><element name="muteButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAYCAYAAADKx8xXAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADFJREFUeNpiYBgFIw3MB+L/5Gj8j6yRiRTFyICJXHfTXyMLAXlGati4YDRFDj8AEGAABk8GSqqS4CoAAAAASUVORK5CYII="/><element name="unmuteButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAYCAYAAADKx8xXAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAD1JREFUeNpiYBgFgxz8p7bm+cQa+h8LHy7GhEcjIz4bmAjYykiun/8j0fakGPIfTfPgiSr6aB4FVAcAAQYAWdwR1G1Wd2gAAAAASUVORK5CYII="/><element name="volumeSliderRail" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAYCAYAAADkgu3FAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAGpJREFUeNpi/P//PwM9ABMDncCoRYPfIqqDZcuW1UPp/6AUDcNM1DQYKtRAlaAj1mCSLSLXYIIWUctgDItoZfDA5aOoqKhGEANIM9LVR7SymGDQUctikuOIXkFNdhHEOFrDjlpEd4sAAgwAriRMub95fu8AAAAASUVORK5CYII="/><element name="volumeSliderProgress" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAYCAYAAADkgu3FAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFtJREFUeNpi/P//PwM9ABMDncCoRYPfIlqAeij9H5SiYZiqBqPTlFqE02BKLSLaYFItIttgQhZRzWB8FjENiuRJ7aAbsMQwYMl7wDIsWUUQ42gNO2oR3S0CCDAAKhKq6MLLn8oAAAAASUVORK5CYII="/><element name="fullscreenButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAE5JREFUeNpiYBgFo2DQA0YC8v/xqP1PjDlMRDrEgUgxkgHIlfZoriVGjmzLsLFHAW2D6D8eA/9Tw7L/BAwgJE90PvhPpNgoGAVDEQAEGAAMdhTyXcPKcAAAAABJRU5ErkJggg=="/><element name="normalscreenButton" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEZJREFUeNpiYBgFo2DIg/9UUkOUAf8JiFFsyX88fJyAkcQgYMQjNkzBoAgiezyRbE+tFGSPxQJ7auYBmma0UTAKBhgABBgAJAEY6zON61sAAAAASUVORK5CYII="/></elements></component><component name="display"><elements><element name="background" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAEpJREFUeNrszwENADAIA7DhX8ENoBMZ5KR10EryckCJiIiIiIiIiIiIiIiIiIiIiIh8GmkRERERERERERERERERERERERGRHSPAAPlXH1phYpYaAAAAAElFTkSuQmCC"/><element name="playIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALdJREFUeNrs18ENgjAYhmFouDOCcQJGcARHgE10BDcgTOIosAGwQOuPwaQeuFRi2p/3Sb6EC5L3QCxZBgAAAOCorLW1zMn65TrlkH4NcV7QNcUQt7Gn7KIhxA+qNIR81spOGkL8oFJDyLJRdosqKDDkK+iX5+d7huzwM40xptMQMkjIOeRGo+VkEVvIPfTGIpKASfYIfT9iCHkHrBEzf4gcUQ56aEzuGK/mw0rHpy4AAACAf3kJMACBxjAQNRckhwAAAABJRU5ErkJggg=="/><element name="muteIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHJJREFUeNrs1jEOgCAMBVAg7t5/8qaoIy4uoobyXsLCxA+0NCUAAADGUWvdQoQ41x4ixNBB2hBvBskdD3w5ZCkl3+33VqI0kjBBlh9rp+uTcyOP33TnolfsU85XX3yIRpQph8ZQY3wTZtU5AACASA4BBgDHoVuY1/fvOQAAAABJRU5ErkJggg=="/><element name="errorIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAWlJREFUeNrsl+1twjAQhsHq/7BBYQLYIBmBDcoGMAIjtBPQTcII2SDtBDBBwrU6pGsUO7YbO470PtKJkz9iH++d4ywWAAAAAABgljRNsyWr2bZzDuJG1rLdZhcMbTjrBCGDyUKsqQLFciJb9bSvuG/WagRVRUVUI6gqy5HVeKWfSgRyJruKIU//TrZTSn2nmlaXThrloi/v9F2STC1W4+Aw5cBzkquRc09bofFNc6YLxEON0VUZS5FPTftO49vMjRsIF3RhOGr7/D/pJw+FKU+q0vDyq8W42jCunDqI3LC5XxNj2wHLU1XjaRnb0Lhykhqhhd8MtSF5J9tbjCv4mXGvKJz/65FF/qJryyaaIvzP2QRxZTX2nTuXjvV/VPFSwyLnW7mpH99yTh1FEVro6JBSd40/pMrRdV8vPtcKl28T2pT8TnFZ4yNosct3Q0io6JfBiz1FlGdqVQH3VHnepAEAAAAAADDzEGAAcTwB10jWgxcAAAAASUVORK5CYII="/><element name="bufferIcon" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAuhJREFUeNrsWr9rU1EUznuNGqvFQh1ULOhiBx0KDtIuioO4pJuik3FxFfUPaAV1FTdx0Q5d2g4FFxehTnEpZHFoBy20tCIWtGq0TZP4HfkeHB5N8m6Sl/sa74XDybvv3vvOd8/Pe4lXrVZT3dD8VJc0B8QBcUAcEAfESktHGeR5XtMfqFQq/f92zPe/NbtGlKTdCY30kuxrpMGO94BlQCXs+rbh3ONgA6BlzP1p20d80gEI5hmA2A92Qua1Q2PtAFISM+bvjMG8U+Q7oA3rQGASwrYCU6WpNdLGYbA+Pq5jjXIiwi8EEa2UDbQSaKOIuV+SlkcCrfjY8XTI9EpKGwP0C2kru2hLtHqa4zoXtZRWyvi4CLwv9Opr6Hkn6A9HKgEANsQ1iqC3Ub/vRUk2JgmRkatK36kVrnt0qObunwUdUUMXMWYpakJsO5Am8tAw2GBIgwWA+G2S2dMpiw0gDioQRQJoKhRb1QiDwlHZUABYbaXWsm5ae6loTE4ZDxN4CZar8foVzOJ2iyZ2kWF3t7YIevffaMT5yJ70kQb2fQ1sE5SHr2wazs2wgMxgbsEKEAgxAvZUJbQLBGTSBMgNrncJbA6AljtS/eKDJ0Ez+DmrQEzXS2h1Ck25kAg0IZcUOaydCy4sYnN2fOA+2AP16gNoHALlQ+fwH7XO4CxLenUpgj4xr6ugY2roPMbMx+Xs18m/E8CVEIhxsNeg83XWOAN6grG3lGbk8uE5fr4B/WH3cJw+co/l9nTYsSGYCJ/lY5/qv0thn6nrIWmjeJcPSnWOeY++AkF8tpJHIMAUs/MaBBpj3znZfQo5psY+ZrG4gv5HickjEOymKjEeRpgyST6IuZcTcWbnjcgdPi5ghxciRKsl1lDSsgwA1i8fssonJgzmTSqfGUkCENndNdAL7PS6QQ7ZYISTo+1qq0LEWjTWcvY4isa4z+yfQB+7ooyHVg5RI7/i1Ijn/vnggDggDogD4oC00P4KMACd/juEHOrS4AAAAABJRU5ErkJggg=="/></elements></component><component name="dock"><elements><element name="button" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAFBJREFUeNrs0cEJACAQA8Eofu0fu/W6EM5ZSAFDRpKTBs00CQQEBAQEBAQEBAQEBAQEBATkK8iqbY+AgICAgICAgICAgICAgICAgIC86QowAG5PAQzEJ0lKAAAAAElFTkSuQmCC"/></elements></component><component name="playlist"><elements><element name="item" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADwAAAA8CAIAAAC1nk4lAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAHhJREFUeNrs2NEJwCAMBcBYuv/CFuIE9VN47WWCR7iocXR3pdWdGPqqwIoMjYfQeAiNh9B4JHc6MHQVHnjggQceeOCBBx77TifyeOY0iHi8DqIdEY8dD5cL094eePzINB5CO/LwcOTptNB4CP25L4TIbZzpU7UEGAA5wz1uF5rF9AAAAABJRU5ErkJggg=="/><element name="sliderRail" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAA8CAIAAADpFA0BAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADhJREFUeNrsy6ENACAMAMHClp2wYxZLAg5Fcu9e3OjuOKqqfTMzbs14CIZhGIZhGIZhGP4VLwEGAK/BBnVFpB0oAAAAAElFTkSuQmCC"/><element name="sliderThumb" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAA8CAIAAADpFA0BAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAADRJREFUeNrsy7ENACAMBLE8++8caFFKKiRffU53112SGs3ttOohGIZhGIZhGIZh+Fe8BRgAiaUGde6NOSEAAAAASUVORK5CYII="/></elements></component></components></skin>';this.xml=null;if(window.DOMParser){parser=new DOMParser();this.xml=parser.parseFromString(this.text,"text/xml")}else{this.xml=new ActiveXObject("Microsoft.XMLDOM");this.xml.async="false";this.xml.loadXML(this.text)}return this}})(jwplayer);(function(a){_utils=a.utils;_css=_utils.css;_hide=function(b){_css(b,{display:"none"})};_show=function(b){_css(b,{display:"block"})};a.html5.display=function(k,G){var j={icons:true,showmute:false};var Q=_utils.extend({},j,G);var h=k;var P={};var e;var u;var w;var N;var s;var I;var A;var J=!_utils.exists(h.skin.getComponentSettings("display").bufferrotation)?15:parseInt(h.skin.getComponentSettings("display").bufferrotation,10);var q=!_utils.exists(h.skin.getComponentSettings("display").bufferinterval)?100:parseInt(h.skin.getComponentSettings("display").bufferinterval,10);var z=-1;var t="";var K=true;var d;var g=false;var n=false;var H=new a.html5.eventdispatcher();_utils.extend(this,H);var D={display:{style:{cursor:"pointer",top:0,left:0,overflow:"hidden"},click:m},display_icon:{style:{cursor:"pointer",position:"absolute",top:((h.skin.getSkinElement("display","background").height-h.skin.getSkinElement("display","playIcon").height)/2),left:((h.skin.getSkinElement("display","background").width-h.skin.getSkinElement("display","playIcon").width)/2),border:0,margin:0,padding:0,zIndex:3,display:"none"}},display_iconBackground:{style:{cursor:"pointer",position:"absolute",top:((u-h.skin.getSkinElement("display","background").height)/2),left:((e-h.skin.getSkinElement("display","background").width)/2),border:0,backgroundImage:(["url(",h.skin.getSkinElement("display","background").src,")"]).join(""),width:h.skin.getSkinElement("display","background").width,height:h.skin.getSkinElement("display","background").height,margin:0,padding:0,zIndex:2,display:"none"}},display_image:{style:{display:"none",width:e,height:u,position:"absolute",cursor:"pointer",left:0,top:0,margin:0,padding:0,textDecoration:"none",zIndex:1}},display_text:{style:{zIndex:4,position:"relative",opacity:0.8,backgroundColor:parseInt("000000",16),color:parseInt("ffffff",16),textAlign:"center",fontFamily:"Arial,sans-serif",padding:"0 5px",fontSize:14}}};h.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE,p);h.jwAddEventListener(a.api.events.JWPLAYER_MEDIA_MUTE,p);h.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_ITEM,p);h.jwAddEventListener(a.api.events.JWPLAYER_ERROR,o);L();function L(){P.display=C("div","display");P.display_text=C("div","display_text");P.display.appendChild(P.display_text);P.display_image=C("img","display_image");P.display_image.onerror=function(R){_hide(P.display_image)};P.display_image.onload=y;P.display_icon=C("div","display_icon");P.display_iconBackground=C("div","display_iconBackground");P.display.appendChild(P.display_image);P.display_iconBackground.appendChild(P.display_icon);P.display.appendChild(P.display_iconBackground);f();setTimeout((function(){n=true;if(Q.icons.toString()=="true"){F()}}),1)}this.getDisplayElement=function(){return P.display};this.resize=function(S,R){_css(P.display,{width:S,height:R});_css(P.display_text,{width:(S-10),top:((R-P.display_text.getBoundingClientRect().height)/2)});_css(P.display_iconBackground,{top:((R-h.skin.getSkinElement("display","background").height)/2),left:((S-h.skin.getSkinElement("display","background").width)/2)});if(e!=S||u!=R){e=S;u=R;d=undefined;F()}c();p({})};this.show=function(){if(g){g=false;r(h.jwGetState())}};this.hide=function(){if(!g){B();g=true}};function y(R){w=P.display_image.naturalWidth;N=P.display_image.naturalHeight;c()}function c(){_utils.stretch(h.jwGetStretching(),P.display_image,e,u,w,N)}function C(R,T){var S=document.createElement(R);S.id=h.id+"_jwplayer_"+T;_css(S,D[T].style);return S}function f(){for(var R in P){if(_utils.exists(D[R].click)){P[R].onclick=D[R].click}}}function m(R){if(typeof R.preventDefault!="undefined"){R.preventDefault()}else{R.returnValue=false}if(h.jwGetState()!=a.api.events.state.PLAYING){h.jwPlay()}else{h.jwPause()}}function O(R){if(A){B();return}P.display_icon.style.backgroundImage=(["url(",h.skin.getSkinElement("display",R).src,")"]).join("");_css(P.display_icon,{width:h.skin.getSkinElement("display",R).width,height:h.skin.getSkinElement("display",R).height,top:(h.skin.getSkinElement("display","background").height-h.skin.getSkinElement("display",R).height)/2,left:(h.skin.getSkinElement("display","background").width-h.skin.getSkinElement("display",R).width)/2});b();if(_utils.exists(h.skin.getSkinElement("display",R+"Over"))){P.display_icon.onmouseover=function(S){P.display_icon.style.backgroundImage=["url(",h.skin.getSkinElement("display",R+"Over").src,")"].join("")};P.display_icon.onmouseout=function(S){P.display_icon.style.backgroundImage=["url(",h.skin.getSkinElement("display",R).src,")"].join("")}}else{P.display_icon.onmouseover=null;P.display_icon.onmouseout=null}}function B(){if(Q.icons.toString()=="true"){_hide(P.display_icon);_hide(P.display_iconBackground);M()}}function b(){if(!g&&Q.icons.toString()=="true"){_show(P.display_icon);_show(P.display_iconBackground);F()}}function o(R){A=true;B();P.display_text.innerHTML=R.error;_show(P.display_text);P.display_text.style.top=((u-P.display_text.getBoundingClientRect().height)/2)+"px"}function E(){P.display_image.style.display="none"}function p(R){if((R.type==a.api.events.JWPLAYER_PLAYER_STATE||R.type==a.api.events.JWPLAYER_PLAYLIST_ITEM)&&A){A=false;_hide(P.display_text)}var S=h.jwGetState();if(S==t){return}t=S;if(z>=0){clearTimeout(z)}if(K||h.jwGetState()==a.api.events.state.PLAYING||h.jwGetState()==a.api.events.state.PAUSED){r(h.jwGetState())}else{z=setTimeout(l(h.jwGetState()),500)}}function l(R){return(function(){r(R)})}function r(R){if(_utils.exists(I)){clearInterval(I);I=null;_utils.animations.rotate(P.display_icon,0)}switch(R){case a.api.events.state.BUFFERING:if(_utils.isIOS()){E();B()}else{if(h.jwGetPlaylist()[h.jwGetItem()].provider=="sound"){v()}s=0;I=setInterval(function(){s+=J;_utils.animations.rotate(P.display_icon,s%360)},q);O("bufferIcon");K=true}break;case a.api.events.state.PAUSED:if(!_utils.isIOS()){if(h.jwGetPlaylist()[h.jwGetItem()].provider!="sound"){_css(P.display_image,{background:"transparent no-repeat center center"})}O("playIcon");K=true}break;case a.api.events.state.IDLE:if(h.jwGetPlaylist()[h.jwGetItem()]&&h.jwGetPlaylist()[h.jwGetItem()].image){v()}else{E()}O("playIcon");K=true;break;default:if(h.jwGetPlaylist()[h.jwGetItem()]&&h.jwGetPlaylist()[h.jwGetItem()].provider=="sound"){if(_utils.isIOS()){E();K=false}else{v()}}else{E();K=false}if(h.jwGetMute()&&Q.showmute){O("muteIcon")}else{B()}break}z=-1}function v(){if(h.jwGetPlaylist()[h.jwGetItem()]&&h.jwGetPlaylist()[h.jwGetItem()].image){_css(P.display_image,{display:"block"});P.display_image.src=_utils.getAbsolutePath(h.jwGetPlaylist()[h.jwGetItem()].image)}}function x(R){return function(){if(!n){return}if(!g&&d!=R){d=R;H.sendEvent(R,{component:"display",boundingRect:_utils.getDimensions(P.display_iconBackground)})}}}var F=x(a.api.events.JWPLAYER_COMPONENT_SHOW);var M=x(a.api.events.JWPLAYER_COMPONENT_HIDE);return this}})(jwplayer);(function(a){_css=a.utils.css;a.html5.dock=function(p,u){function q(){return{align:a.html5.view.positions.RIGHT}}var k=a.utils.extend({},q(),u);if(k.align=="FALSE"){return}var f={};var s=[];var g;var v;var d=false;var t=false;var e={x:0,y:0,width:0,height:0};var r;var j=new a.html5.eventdispatcher();_utils.extend(this,j);var m=document.createElement("div");m.id=p.id+"_jwplayer_dock";p.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE,l);this.getDisplayElement=function(){return m};this.setButton=function(A,x,y,z){if(!x&&f[A]){a.utils.arrays.remove(s,A);m.removeChild(f[A].div);delete f[A]}else{if(x){if(!f[A]){f[A]={}}f[A].handler=x;f[A].outGraphic=y;f[A].overGraphic=z;if(!f[A].div){s.push(A);f[A].div=document.createElement("div");f[A].div.style.position="relative";m.appendChild(f[A].div);f[A].div.appendChild(document.createElement("img"));f[A].div.childNodes[0].style.position="absolute";f[A].div.childNodes[0].style.left=0;f[A].div.childNodes[0].style.top=0;f[A].div.childNodes[0].style.zIndex=10;f[A].div.childNodes[0].style.cursor="pointer";f[A].div.appendChild(document.createElement("img"));f[A].div.childNodes[1].style.position="absolute";f[A].div.childNodes[1].style.left=0;f[A].div.childNodes[1].style.top=0;if(p.skin.getSkinElement("dock","button")){f[A].div.childNodes[1].src=p.skin.getSkinElement("dock","button").src}f[A].div.childNodes[1].style.zIndex=9;f[A].div.childNodes[1].style.cursor="pointer";f[A].div.onmouseover=function(){if(f[A].overGraphic){f[A].div.childNodes[0].src=f[A].overGraphic}if(p.skin.getSkinElement("dock","buttonOver")){f[A].div.childNodes[1].src=p.skin.getSkinElement("dock","buttonOver").src}};f[A].div.onmouseout=function(){if(f[A].outGraphic){f[A].div.childNodes[0].src=f[A].outGraphic}if(p.skin.getSkinElement("dock","button")){f[A].div.childNodes[1].src=p.skin.getSkinElement("dock","button").src}};if(f[A].overGraphic){f[A].div.childNodes[0].src=f[A].overGraphic}if(f[A].outGraphic){f[A].div.childNodes[0].src=f[A].outGraphic}if(p.skin.getSkinElement("dock","button")){f[A].div.childNodes[1].src=p.skin.getSkinElement("dock","button").src}}if(x){f[A].div.onclick=function(B){B.preventDefault();a(p.id).callback(A);if(f[A].overGraphic){f[A].div.childNodes[0].src=f[A].overGraphic}if(p.skin.getSkinElement("dock","button")){f[A].div.childNodes[1].src=p.skin.getSkinElement("dock","button").src}}}}}h(g,v)};function h(x,J){if(s.length>0){var y=10;var I=y;var F=-1;var G=p.skin.getSkinElement("dock","button").height;var E=p.skin.getSkinElement("dock","button").width;var C=x-E-y;var H,B;if(k.align==a.html5.view.positions.LEFT){F=1;C=y}for(var z=0;z<s.length;z++){var K=Math.floor(I/J);if((I+G+y)>((K+1)*J)){I=((K+1)*J)+y;K=Math.floor(I/J)}var A=f[s[z]].div;A.style.top=(I%J)+"px";A.style.left=(C+(p.skin.getSkinElement("dock","button").width+y)*K*F)+"px";var D={x:a.utils.parseDimension(A.style.left),y:a.utils.parseDimension(A.style.top),width:E,height:G};if(!H||(D.x<=H.x&&D.y<=H.y)){H=D}if(!B||(D.x>=B.x&&D.y>=B.y)){B=D}I+=p.skin.getSkinElement("dock","button").height+y}e={x:H.x,y:H.y,width:B.x-H.x+B.width,height:H.y-B.y+B.height}}if(t!=p.jwGetFullscreen()||g!=x||v!=J){g=x;v=J;t=p.jwGetFullscreen();r=undefined;setTimeout(n,1)}}function b(x){return function(){if(!d&&r!=x&&s.length>0){r=x;j.sendEvent(x,{component:"dock",boundingRect:e})}}}function l(x){if(a.utils.isIOS()){switch(x.newstate){case a.api.events.state.IDLE:o();break;default:c();break}}}var n=b(a.api.events.JWPLAYER_COMPONENT_SHOW);var w=b(a.api.events.JWPLAYER_COMPONENT_HIDE);this.resize=h;var o=function(){_css(m,{display:"block"});if(d){d=false;n()}};var c=function(){_css(m,{display:"none"});if(!d){w();d=true}};this.hide=c;this.show=o;return this}})(jwplayer);(function(a){a.html5.eventdispatcher=function(d,b){var c=new a.events.eventdispatcher(b);a.utils.extend(this,c);this.sendEvent=function(e,f){if(!a.utils.exists(f)){f={}}a.utils.extend(f,{id:d,version:a.version,type:e});c.sendEvent(e,f)}}})(jwplayer);(function(a){var b={prefix:"",file:"",link:"",margin:8,out:0.5,over:1,timeout:5,hide:true,position:"bottom-left"};_css=a.utils.css;a.html5.logo=function(n,r){var q=n;var u;var d;var t;var h=false;g();function g(){o();c();l()}function o(){if(b.prefix){var v=n.version.split(/\W/).splice(0,2).join("/");if(b.prefix.indexOf(v)<0){b.prefix+=v+"/"}}if(r.position==a.html5.view.positions.OVER){r.position=b.position}d=a.utils.extend({},b,r)}function c(){t=document.createElement("img");t.id=q.id+"_jwplayer_logo";t.style.display="none";t.onload=function(v){_css(t,k());q.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE,j);p()};if(!d.file){return}if(d.file.indexOf("http://")===0){t.src=d.file}else{t.src=d.prefix+d.file}}if(!d.file){return}this.resize=function(w,v){};this.getDisplayElement=function(){return t};function l(){if(d.link){t.onmouseover=f;t.onmouseout=p;t.onclick=s}else{this.mouseEnabled=false}}function s(v){if(typeof v!="undefined"){v.stopPropagation()}if(!h){return}q.jwPause();q.jwSetFullscreen(false);if(d.link){window.open(d.link,"_top")}return}function p(v){if(d.link&&h){t.style.opacity=d.out}return}function f(v){if(d.hide.toString()=="true"&&h){t.style.opacity=d.over}return}function k(){var x={textDecoration:"none",position:"absolute",cursor:"pointer"};x.display=(d.hide.toString()=="true")?"none":"block";var w=d.position.toLowerCase().split("-");for(var v in w){x[w[v]]=d.margin}return x}function m(){if(d.hide.toString()=="true"){t.style.display="block";t.style.opacity=0;a.utils.fadeTo(t,d.out,0.1,parseFloat(t.style.opacity));u=setTimeout(function(){e()},d.timeout*1000)}h=true}function e(){h=false;if(d.hide.toString()=="true"){a.utils.fadeTo(t,0,0.1,parseFloat(t.style.opacity))}}function j(v){if(v.newstate==a.api.events.state.BUFFERING){clearTimeout(u);m()}}return this}})(jwplayer);(function(a){var c={ended:a.api.events.state.IDLE,playing:a.api.events.state.PLAYING,pause:a.api.events.state.PAUSED,buffering:a.api.events.state.BUFFERING};var e=a.utils;var b=e.css;var d=e.isIOS();a.html5.mediavideo=function(h,s){var r={abort:n,canplay:k,canplaythrough:k,durationchange:G,emptied:n,ended:k,error:u,loadeddata:G,loadedmetadata:G,loadstart:k,pause:k,play:n,playing:k,progress:v,ratechange:n,seeked:k,seeking:k,stalled:k,suspend:k,timeupdate:D,volumechange:n,waiting:k,canshowcurrentframe:n,dataunavailable:n,empty:n,load:z,loadedfirstframe:n};var j=new a.html5.eventdispatcher();e.extend(this,j);var y=h,l=s,m,B,A,x,f,H=false,C,p,q;o();this.load=function(J,K){if(typeof K=="undefined"){K=true}x=J;e.empty(m);q=0;if(J.levels&&J.levels.length>0){if(J.levels.length==1){m.src=J.levels[0].file}else{if(m.src){m.removeAttribute("src")}for(var I=0;I<J.levels.length;I++){var L=m.ownerDocument.createElement("source");L.src=J.levels[I].file;m.appendChild(L);q++}}}else{m.src=J.file}if(d){if(J.image){m.poster=J.image}m.controls="controls";m.style.display="block"}C=p=A=false;y.buffer=0;if(!e.exists(J.start)){J.start=0}y.duration=J.duration;j.sendEvent(a.api.events.JWPLAYER_MEDIA_LOADED);if((!d&&J.levels.length==1)||!H){m.load()}H=false;if(K){E(a.api.events.state.BUFFERING);j.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER,{bufferPercent:0});this.play()}};this.play=function(){if(B!=a.api.events.state.PLAYING){t();if(p){E(a.api.events.state.PLAYING)}else{E(a.api.events.state.BUFFERING)}m.play()}};this.pause=function(){m.pause();E(a.api.events.state.PAUSED)};this.seek=function(I){if(!(y.duration<=0||isNaN(y.duration))&&!(y.position<=0||isNaN(y.position))){m.currentTime=I;m.play()}};_stop=this.stop=function(I){if(!e.exists(I)){I=true}g();if(I){m.style.display="none";p=false;var J=navigator.userAgent;if(J.match(/chrome/i)){m.src=undefined}else{if(J.match(/safari/i)){m.removeAttribute("src")}else{m.src=""}}m.removeAttribute("controls");m.removeAttribute("poster");e.empty(m);m.load();H=true;if(m.webkitSupportsFullscreen){try{m.webkitExitFullscreen()}catch(K){}}}E(a.api.events.state.IDLE)};this.fullscreen=function(I){if(I===true){this.resize("100%","100%")}else{this.resize(y.config.width,y.config.height)}};this.resize=function(J,I){if(false){b(l,{width:J,height:I})}j.sendEvent(a.api.events.JWPLAYER_MEDIA_RESIZE,{fullscreen:y.fullscreen,width:J,hieght:I})};this.volume=function(I){if(!d){m.volume=I/100;y.volume=I;j.sendEvent(a.api.events.JWPLAYER_MEDIA_VOLUME,{volume:Math.round(I)})}};this.mute=function(I){if(!d){m.muted=I;y.mute=I;j.sendEvent(a.api.events.JWPLAYER_MEDIA_MUTE,{mute:I})}};this.getDisplayElement=function(){return m};this.hasChrome=function(){return false};function o(){m=document.createElement("video");B=a.api.events.state.IDLE;for(var I in r){m.addEventListener(I,function(J){if(e.exists(J.target.parentNode)){r[J.type](J)}},true)}m.setAttribute("x-webkit-airplay","allow");if(l.parentNode){l.parentNode.replaceChild(m,l)}if(!m.id){m.id=l.id}}function E(I){if(I==a.api.events.state.PAUSED&&B==a.api.events.state.IDLE){return}if(B!=I){var J=B;y.state=B=I;j.sendEvent(a.api.events.JWPLAYER_PLAYER_STATE,{oldstate:J,newstate:I})}}function n(I){}function v(K){var J;if(e.exists(K)&&K.lengthComputable&&K.total){J=K.loaded/K.total*100}else{if(e.exists(m.buffered)&&(m.buffered.length>0)){var I=m.buffered.length-1;if(I>=0){J=m.buffered.end(I)/m.duration*100}}}if(p===false&&B==a.api.events.state.BUFFERING){j.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER_FULL);p=true}if(!C){if(J==100){C=true}if(e.exists(J)&&(J>y.buffer)){y.buffer=Math.round(J);j.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER,{bufferPercent:Math.round(J)})}}}function D(J){if(e.exists(J)&&e.exists(J.target)){if(!isNaN(J.target.duration)&&(isNaN(y.duration)||y.duration<1)){if(J.target.duration==Infinity){y.duration=0}else{y.duration=Math.round(J.target.duration*10)/10}}if(!A&&m.readyState>0){m.style.display="block";E(a.api.events.state.PLAYING)}if(B==a.api.events.state.PLAYING){if(!A&&m.readyState>0){A=true;try{if(m.currentTime<x.start){m.currentTime=x.start}}catch(I){}m.volume=y.volume/100;m.muted=y.mute}y.position=y.duration>0?(Math.round(J.target.currentTime*10)/10):0;j.sendEvent(a.api.events.JWPLAYER_MEDIA_TIME,{position:y.position,duration:y.duration});if(y.position>=y.duration&&(y.position>0||y.duration>0)){w()}}}v(J)}function z(I){}function k(I){if(c[I.type]){if(I.type=="ended"){w()}else{E(c[I.type])}}}function G(I){var J={height:I.target.videoHeight,width:I.target.videoWidth,duration:Math.round(I.target.duration*10)/10};if((y.duration===0||isNaN(y.duration))&&I.target.duration!=Infinity){y.duration=Math.round(I.target.duration*10)/10}j.sendEvent(a.api.events.JWPLAYER_MEDIA_META,{metadata:J})}function u(K){if(B==a.api.events.state.IDLE){return}var J="There was an error: ";if((K.target.error&&K.target.tagName.toLowerCase()=="video")||K.target.parentNode.error&&K.target.parentNode.tagName.toLowerCase()=="video"){var I=!e.exists(K.target.error)?K.target.parentNode.error:K.target.error;switch(I.code){case I.MEDIA_ERR_ABORTED:J="You aborted the video playback: ";break;case I.MEDIA_ERR_NETWORK:J="A network error caused the video download to fail part-way: ";break;case I.MEDIA_ERR_DECODE:J="The video playback was aborted due to a corruption problem or because the video used features your browser did not support: ";break;case I.MEDIA_ERR_SRC_NOT_SUPPORTED:J="The video could not be loaded, either because the server or network failed or because the format is not supported: ";break;default:J="An unknown error occurred: ";break}}else{if(K.target.tagName.toLowerCase()=="source"){q--;if(q>0){return}J="The video could not be loaded, either because the server or network failed or because the format is not supported: "}else{e.log("An unknown error occurred.  Continuing...");return}}_stop(false);J+=F();_error=true;j.sendEvent(a.api.events.JWPLAYER_ERROR,{error:J});return}function F(){var K="";for(var J in x.levels){var I=x.levels[J];var L=l.ownerDocument.createElement("source");K+=a.utils.getAbsolutePath(I.file);if(J<(x.levels.length-1)){K+=", "}}return K}function t(){if(!e.exists(f)){f=setInterval(function(){v()},100)}}function g(){clearInterval(f);f=null}function w(){if(B!=a.api.events.state.IDLE){_stop(false);j.sendEvent(a.api.events.JWPLAYER_MEDIA_COMPLETE)}}}})(jwplayer);(function(a){var c={ended:a.api.events.state.IDLE,playing:a.api.events.state.PLAYING,pause:a.api.events.state.PAUSED,buffering:a.api.events.state.BUFFERING};var b=a.utils.css;a.html5.mediayoutube=function(j,e){var f=new a.html5.eventdispatcher();a.utils.extend(this,f);var l=j;var h=document.getElementById(e.id);var g=a.api.events.state.IDLE;var n,m;function k(p){if(g!=p){var q=g;l.state=p;g=p;f.sendEvent(a.api.events.JWPLAYER_PLAYER_STATE,{oldstate:q,newstate:p})}}this.getDisplayElement=function(){return h};this.play=function(){if(g==a.api.events.state.IDLE){f.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER,{bufferPercent:100});f.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER_FULL);k(a.api.events.state.PLAYING)}else{if(g==a.api.events.state.PAUSED){k(a.api.events.state.PLAYING)}}};this.pause=function(){k(a.api.events.state.PAUSED)};this.seek=function(p){};this.stop=function(p){if(!_utils.exists(p)){p=true}l.position=0;k(a.api.events.state.IDLE);if(p){b(h,{display:"none"})}};this.volume=function(p){l.volume=p;f.sendEvent(a.api.events.JWPLAYER_MEDIA_VOLUME,{volume:Math.round(p)})};this.mute=function(p){h.muted=p;l.mute=p;f.sendEvent(a.api.events.JWPLAYER_MEDIA_MUTE,{mute:p})};this.resize=function(q,p){if(q*p>0&&n){n.width=m.width=q;n.height=m.height=p}f.sendEvent(a.api.events.JWPLAYER_MEDIA_RESIZE,{fullscreen:l.fullscreen,width:q,height:p})};this.fullscreen=function(p){if(p===true){this.resize("100%","100%")}else{this.resize(l.config.width,l.config.height)}};this.load=function(p){o(p);b(n,{display:"block"});k(a.api.events.state.BUFFERING);f.sendEvent(a.api.events.JWPLAYER_MEDIA_BUFFER,{bufferPercent:0});f.sendEvent(a.api.events.JWPLAYER_MEDIA_LOADED);this.play()};this.hasChrome=function(){return(g!=a.api.events.state.IDLE)};function o(v){var s=v.levels[0].file;s=["http://www.youtube.com/v/",d(s),"&amp;hl=en_US&amp;fs=1&autoplay=1"].join("");n=document.createElement("object");n.id=h.id;n.style.position="absolute";var u={movie:s,allowfullscreen:"true",allowscriptaccess:"always"};for(var p in u){var t=document.createElement("param");t.name=p;t.value=u[p];n.appendChild(t)}m=document.createElement("embed");n.appendChild(m);var q={src:s,type:"application/x-shockwave-flash",allowfullscreen:"true",allowscriptaccess:"always",width:n.width,height:n.height};for(var r in q){m.setAttribute(r,q[r])}n.appendChild(m);n.style.zIndex=2147483000;if(h!=n&&h.parentNode){h.parentNode.replaceChild(n,h)}h=n}function d(q){var p=q.split(/\?|\#\!/);var s="";for(var r=0;r<p.length;r++){if(p[r].substr(0,2)=="v="){s=p[r].substr(2)}}if(s==""){if(q.indexOf("/v/")>=0){s=q.substr(q.indexOf("/v/")+3)}else{if(q.indexOf("youtu.be")>=0){s=q.substr(q.indexOf("youtu.be/")+9)}else{s=q}}}if(s.indexOf("?")>-1){s=s.substr(0,s.indexOf("?"))}if(s.indexOf("&")>-1){s=s.substr(0,s.indexOf("&"))}return s}this.embed=m;return this}})(jwplayer);(function(jwplayer){var _configurableStateVariables=["width","height","start","duration","volume","mute","fullscreen","item","plugins","stretching"];jwplayer.html5.model=function(api,container,options){var _api=api;var _container=container;var _model={id:_container.id,playlist:[],state:jwplayer.api.events.state.IDLE,position:0,buffer:0,config:{width:480,height:320,item:-1,skin:undefined,file:undefined,image:undefined,start:0,duration:0,bufferlength:5,volume:90,mute:false,fullscreen:false,repeat:"",stretching:jwplayer.utils.stretching.UNIFORM,autostart:false,debug:undefined,screencolor:undefined}};var _media;var _eventDispatcher=new jwplayer.html5.eventdispatcher();var _components=["display","logo","controlbar","playlist","dock"];jwplayer.utils.extend(_model,_eventDispatcher);for(var option in options){if(typeof options[option]=="string"){var type=/color$/.test(option)?"color":null;options[option]=jwplayer.utils.typechecker(options[option],type)}var config=_model.config;var path=option.split(".");for(var edge in path){if(edge==path.length-1){config[path[edge]]=options[option]}else{if(!jwplayer.utils.exists(config[path[edge]])){config[path[edge]]={}}config=config[path[edge]]}}}for(var index in _configurableStateVariables){var configurableStateVariable=_configurableStateVariables[index];_model[configurableStateVariable]=_model.config[configurableStateVariable]}var pluginorder=_components.concat([]);if(jwplayer.utils.exists(_model.plugins)){if(typeof _model.plugins=="string"){var userplugins=_model.plugins.split(",");for(var userplugin in userplugins){if(typeof userplugins[userplugin]=="string"){pluginorder.push(userplugins[userplugin].replace(/^\s+|\s+$/g,""))}}}}if(jwplayer.utils.isIOS()){pluginorder=["display","logo","dock","playlist"];if(!jwplayer.utils.exists(_model.config.repeat)){_model.config.repeat="list"}}else{if(_model.config.chromeless){pluginorder=["logo","dock","playlist"];if(!jwplayer.utils.exists(_model.config.repeat)){_model.config.repeat="list"}}}_model.plugins={order:pluginorder,config:{},object:{}};if(typeof _model.config.components!="undefined"){for(var component in _model.config.components){_model.plugins.config[component]=_model.config.components[component]}}for(var pluginIndex in _model.plugins.order){var pluginName=_model.plugins.order[pluginIndex];var pluginConfig=!jwplayer.utils.exists(_model.plugins.config[pluginName])?{}:_model.plugins.config[pluginName];_model.plugins.config[pluginName]=!jwplayer.utils.exists(_model.plugins.config[pluginName])?pluginConfig:jwplayer.utils.extend(_model.plugins.config[pluginName],pluginConfig);if(!jwplayer.utils.exists(_model.plugins.config[pluginName].position)){if(pluginName=="playlist"){_model.plugins.config[pluginName].position=jwplayer.html5.view.positions.NONE}else{_model.plugins.config[pluginName].position=jwplayer.html5.view.positions.OVER}}else{_model.plugins.config[pluginName].position=_model.plugins.config[pluginName].position.toString().toUpperCase()}}if(typeof _model.plugins.config.dock!="undefined"){if(typeof _model.plugins.config.dock!="object"){var position=_model.plugins.config.dock.toString().toUpperCase();_model.plugins.config.dock={position:position}}if(typeof _model.plugins.config.dock.position!="undefined"){_model.plugins.config.dock.align=_model.plugins.config.dock.position;_model.plugins.config.dock.position=jwplayer.html5.view.positions.OVER}}function _loadExternal(playlistfile){var loader=new jwplayer.html5.playlistloader();loader.addEventListener(jwplayer.api.events.JWPLAYER_PLAYLIST_LOADED,function(evt){_model.playlist=new jwplayer.html5.playlist(evt);_loadComplete(true)});loader.addEventListener(jwplayer.api.events.JWPLAYER_ERROR,function(evt){_model.playlist=new jwplayer.html5.playlist({playlist:[]});_loadComplete(false)});loader.load(playlistfile)}function _loadComplete(){if(_model.config.shuffle){_model.item=_getShuffleItem()}else{if(_model.config.item>=_model.playlist.length){_model.config.item=_model.playlist.length-1}else{if(_model.config.item<0){_model.config.item=0}}_model.item=_model.config.item}_eventDispatcher.sendEvent(jwplayer.api.events.JWPLAYER_PLAYLIST_LOADED,{playlist:_model.playlist});_eventDispatcher.sendEvent(jwplayer.api.events.JWPLAYER_PLAYLIST_ITEM,{index:_model.item})}_model.loadPlaylist=function(arg){var input;if(typeof arg=="string"){if(arg.indexOf("[")==0||arg.indexOf("{")=="0"){try{input=eval(arg)}catch(err){input=arg}}else{input=arg}}else{input=arg}var config;switch(jwplayer.utils.typeOf(input)){case"object":config=input;break;case"array":config={playlist:input};break;default:_loadExternal(input);return;break}_model.playlist=new jwplayer.html5.playlist(config);if(jwplayer.utils.extension(_model.playlist[0].file)=="xml"){_loadExternal(_model.playlist[0].file)}else{_loadComplete()}};function _getShuffleItem(){var result=null;if(_model.playlist.length>1){while(!jwplayer.utils.exists(result)){result=Math.floor(Math.random()*_model.playlist.length);if(result==_model.item){result=null}}}else{result=0}return result}function forward(evt){if(evt.type==jwplayer.api.events.JWPLAYER_MEDIA_LOADED){_container=_media.getDisplayElement()}_eventDispatcher.sendEvent(evt.type,evt)}var _mediaProviders={};_model.setActiveMediaProvider=function(playlistItem){if(playlistItem.provider=="audio"){playlistItem.provider="sound"}var provider=playlistItem.provider;var current=_media?_media.getDisplayElement():null;if(provider=="sound"||provider=="http"||provider==""){provider="video"}if(!jwplayer.utils.exists(_mediaProviders[provider])){switch(provider){case"video":_media=new jwplayer.html5.mediavideo(_model,current?current:_container);break;case"youtube":_media=new jwplayer.html5.mediayoutube(_model,current?current:_container);break}if(!jwplayer.utils.exists(_media)){return false}_media.addGlobalListener(forward);_mediaProviders[provider]=_media}else{if(_media!=_mediaProviders[provider]){if(_media){_media.stop()}_media=_mediaProviders[provider]}}return true};_model.getMedia=function(){return _media};_model.seek=function(pos){_eventDispatcher.sendEvent(jwplayer.api.events.JWPLAYER_MEDIA_SEEK,{position:_model.position,offset:pos});return _media.seek(pos)};_model.setupPlugins=function(){if(!jwplayer.utils.exists(_model.plugins)||!jwplayer.utils.exists(_model.plugins.order)||_model.plugins.order.length==0){jwplayer.utils.log("No plugins to set up");return _model}for(var i=0;i<_model.plugins.order.length;i++){try{var pluginName=_model.plugins.order[i];if(jwplayer.utils.exists(jwplayer.html5[pluginName])){if(pluginName=="playlist"){_model.plugins.object[pluginName]=new jwplayer.html5.playlistcomponent(_api,_model.plugins.config[pluginName])}else{_model.plugins.object[pluginName]=new jwplayer.html5[pluginName](_api,_model.plugins.config[pluginName])}}else{_model.plugins.order.splice(plugin,plugin+1)}if(typeof _model.plugins.object[pluginName].addGlobalListener=="function"){_model.plugins.object[pluginName].addGlobalListener(forward)}}catch(err){jwplayer.utils.log("Could not setup "+pluginName)}}};return _model}})(jwplayer);(function(a){a.html5.playlist=function(b){var d=[];if(b.playlist&&b.playlist instanceof Array&&b.playlist.length>0){for(var c in b.playlist){if(!isNaN(parseInt(c))){d.push(new a.html5.playlistitem(b.playlist[c]))}}}else{d.push(new a.html5.playlistitem(b))}return d}})(jwplayer);(function(a){var c={size:180,position:a.html5.view.positions.NONE,itemheight:60,thumbs:true,fontcolor:"#000000",overcolor:"",activecolor:"",backgroundcolor:"#f8f8f8",font:"_sans",fontsize:"",fontstyle:"",fontweight:""};var b={_sans:"Arial, Helvetica, sans-serif",_serif:"Times, Times New Roman, serif",_typewriter:"Courier New, Courier, monospace"};_utils=a.utils;_css=_utils.css;_hide=function(d){_css(d,{display:"none"})};_show=function(d){_css(d,{display:"block"})};a.html5.playlistcomponent=function(r,B){var w=r;var e=a.utils.extend({},c,w.skin.getComponentSettings("playlist"),B);if(e.position==a.html5.view.positions.NONE||typeof a.html5.view.positions[e.position]=="undefined"){return}var x;var l;var C;var d;var g;var f;var k=-1;var h={background:undefined,item:undefined,itemOver:undefined,itemImage:undefined,itemActive:undefined};this.getDisplayElement=function(){return x};this.resize=function(F,D){l=F;C=D;if(w.jwGetFullscreen()){_hide(x)}else{var E={display:"block",width:l,height:C};_css(x,E)}};this.show=function(){_show(x)};this.hide=function(){_hide(x)};function j(){x=document.createElement("div");x.id=w.id+"_jwplayer_playlistcomponent";switch(e.position){case a.html5.view.positions.RIGHT:case a.html5.view.positions.LEFT:x.style.width=e.size+"px";break;case a.html5.view.positions.TOP:case a.html5.view.positions.BOTTOM:x.style.height=e.size+"px";break}A();if(h.item){e.itemheight=h.item.height}x.style.backgroundColor="#C6C6C6";w.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED,s);w.jwAddEventListener(a.api.events.JWPLAYER_PLAYLIST_ITEM,u);w.jwAddEventListener(a.api.events.JWPLAYER_PLAYER_STATE,m)}function p(){var D=document.createElement("ul");_css(D,{width:x.style.width,minWidth:x.style.width,height:x.style.height,backgroundColor:e.backgroundcolor,backgroundImage:h.background?"url("+h.background.src+")":"",color:e.fontcolor,listStyle:"none",margin:0,padding:0,fontFamily:b[e.font]?b[e.font]:b._sans,fontSize:(e.fontsize?e.fontsize:11)+"px",fontStyle:e.fontstyle,fontWeight:e.fontweight,overflowY:"auto"});return D}function y(D){return function(){var E=f.getElementsByClassName("item")[D];var F=e.fontcolor;var G=h.item?"url("+h.item.src+")":"";if(D==w.jwGetPlaylistIndex()){if(e.activecolor!==""){F=e.activecolor}if(h.itemActive){G="url("+h.itemActive.src+")"}}_css(E,{color:e.overcolor!==""?e.overcolor:F,backgroundImage:h.itemOver?"url("+h.itemOver.src+")":G})}}function o(D){return function(){var E=f.getElementsByClassName("item")[D];var F=e.fontcolor;var G=h.item?"url("+h.item.src+")":"";if(D==w.jwGetPlaylistIndex()){if(e.activecolor!==""){F=e.activecolor}if(h.itemActive){G="url("+h.itemActive.src+")"}}_css(E,{color:F,backgroundImage:G})}}function q(I){var P=d[I];var O=document.createElement("li");O.className="item";_css(O,{height:e.itemheight,display:"block",cursor:"pointer",backgroundImage:h.item?"url("+h.item.src+")":"",backgroundSize:"100% "+e.itemheight+"px"});O.onmouseover=y(I);O.onmouseout=o(I);var J=document.createElement("div");var F=new Image();var K=0;var L=0;var M=0;if(v()&&(P.image||P["playlist.image"]||h.itemImage)){F.className="image";if(h.itemImage){K=(e.itemheight-h.itemImage.height)/2;L=h.itemImage.width;M=h.itemImage.height}else{L=e.itemheight*4/3;M=e.itemheight}_css(J,{height:M,width:L,"float":"left",styleFloat:"left",cssFloat:"left",margin:"0 5px 0 0",background:"black",overflow:"hidden",margin:K+"px",position:"relative"});_css(F,{position:"relative"});J.appendChild(F);F.onload=function(){a.utils.stretch(a.utils.stretching.FILL,F,L,M,this.naturalWidth,this.naturalHeight)};if(P["playlist.image"]){F.src=P["playlist.image"]}else{if(P.image){F.src=P.image}else{if(h.itemImage){F.src=h.itemImage.src}}}O.appendChild(J)}var E=l-L-K*2;if(C<e.itemheight*d.length){E-=15}var D=document.createElement("div");_css(D,{position:"relative",height:"100%",overflow:"hidden"});var G=document.createElement("span");if(P.duration>0){G.className="duration";_css(G,{fontSize:(e.fontsize?e.fontsize:11)+"px",fontWeight:(e.fontweight?e.fontweight:"bold"),width:"40px",height:e.fontsize?e.fontsize+10:20,lineHeight:24,"float":"right",styleFloat:"right",cssFloat:"right"});G.innerHTML=_utils.timeFormat(P.duration);D.appendChild(G)}var N=document.createElement("span");N.className="title";_css(N,{padding:"5px 5px 0 "+(K?0:"5px"),height:e.fontsize?e.fontsize+10:20,lineHeight:e.fontsize?e.fontsize+10:20,overflow:"hidden","float":"left",styleFloat:"left",cssFloat:"left",width:((P.duration>0)?E-50:E)-10+"px",fontSize:(e.fontsize?e.fontsize:13)+"px",fontWeight:(e.fontweight?e.fontweight:"bold")});N.innerHTML=P?P.title:"";D.appendChild(N);if(P.description){var H=document.createElement("span");H.className="description";_css(H,{display:"block","float":"left",styleFloat:"left",cssFloat:"left",margin:0,paddingLeft:N.style.paddingLeft,paddingRight:N.style.paddingRight,lineHeight:(e.fontsize?e.fontsize+4:16)+"px",overflow:"hidden",position:"relative"});H.innerHTML=P.description;D.appendChild(H)}O.appendChild(D);return O}function s(E){x.innerHTML="";d=w.jwGetPlaylist();if(!d){return}items=[];f=p();for(var F=0;F<d.length;F++){var D=q(F);D.onclick=z(F);f.appendChild(D);items.push(D)}k=w.jwGetPlaylistIndex();o(k)();x.appendChild(f);if(_utils.isIOS()&&window.iScroll){f.style.height=e.itemheight*d.length+"px";var G=new iScroll(x.id)}}function z(D){return function(){w.jwPlaylistItem(D);w.jwPlay(true)}}function n(){f.scrollTop=w.jwGetPlaylistIndex()*e.itemheight}function v(){return e.thumbs.toString().toLowerCase()=="true"}function u(D){if(k>=0){o(k)();k=D.index}o(D.index)();n()}function m(){if(e.position==a.html5.view.positions.OVER){switch(w.jwGetState()){case a.api.events.state.IDLE:_show(x);break;default:_hide(x);break}}}function A(){for(var D in h){h[D]=t(D)}}function t(D){return w.skin.getSkinElement("playlist",D)}j();return this}})(jwplayer);(function(b){b.html5.playlistitem=function(d){var e={author:"",date:"",description:"",image:"",link:"",mediaid:"",tags:"",title:"",provider:"",file:"",streamer:"",duration:-1,start:0,currentLevel:-1,levels:[]};var c=b.utils.extend({},e,d);if(c.type){c.provider=c.type;delete c.type}if(c.levels.length===0){c.levels[0]=new b.html5.playlistitemlevel(c)}if(!c.provider){c.provider=a(c.levels[0])}else{c.provider=c.provider.toLowerCase()}return c};function a(e){if(b.utils.isYouTube(e.file)){return"youtube"}else{var f=b.utils.extension(e.file);var c;if(f&&b.utils.extensionmap[f]){if(f=="m3u8"){return"video"}c=b.utils.extensionmap[f].html5}else{if(e.type){c=e.type}}if(c){var d=c.split("/")[0];if(d=="audio"){return"sound"}else{if(d=="video"){return d}}}}return""}})(jwplayer);(function(a){a.html5.playlistitemlevel=function(b){var d={file:"",streamer:"",bitrate:0,width:0};for(var c in d){if(a.utils.exists(b[c])){d[c]=b[c]}}return d}})(jwplayer);(function(a){a.html5.playlistloader=function(){var c=new a.html5.eventdispatcher();a.utils.extend(this,c);this.load=function(e){a.utils.ajax(e,d,b)};function d(g){var f=[];try{var f=a.utils.parsers.rssparser.parse(g.responseXML.firstChild);c.sendEvent(a.api.events.JWPLAYER_PLAYLIST_LOADED,{playlist:new a.html5.playlist({playlist:f})})}catch(h){b("Could not parse the playlist")}}function b(e){c.sendEvent(a.api.events.JWPLAYER_ERROR,{error:e?e:"could not load playlist for whatever reason.  too bad"})}}})(jwplayer);(function(a){a.html5.skin=function(){var b={};var c=false;this.load=function(d,e){new a.html5.skinloader(d,function(f){c=true;b=f;e()},function(){new a.html5.skinloader("",function(f){c=true;b=f;e()})})};this.getSkinElement=function(d,e){if(c){try{return b[d].elements[e]}catch(f){a.utils.log("No such skin component / element: ",[d,e])}}return null};this.getComponentSettings=function(d){if(c){return b[d].settings}return null};this.getComponentLayout=function(d){if(c){return b[d].layout}return null}}})(jwplayer);(function(a){a.html5.skinloader=function(f,p,k){var o={};var c=p;var l=k;var e=true;var j;var n=f;var s=false;function m(){if(typeof n!="string"||n===""){d(a.html5.defaultSkin().xml)}else{a.utils.ajax(a.utils.getAbsolutePath(n),function(t){try{if(a.utils.exists(t.responseXML)){d(t.responseXML);return}}catch(u){h()}d(a.html5.defaultSkin().xml)},function(t){d(a.html5.defaultSkin().xml)})}}function d(y){var E=y.getElementsByTagName("component");if(E.length===0){return}for(var H=0;H<E.length;H++){var C=E[H].getAttribute("name");var B={settings:{},elements:{},layout:{}};o[C]=B;var G=E[H].getElementsByTagName("elements")[0].getElementsByTagName("element");for(var F=0;F<G.length;F++){b(G[F],C)}var z=E[H].getElementsByTagName("settings")[0];if(z&&z.childNodes.length>0){var K=z.getElementsByTagName("setting");for(var P=0;P<K.length;P++){var Q=K[P].getAttribute("name");var I=K[P].getAttribute("value");var x=/color$/.test(Q)?"color":null;o[C].settings[Q]=a.utils.typechecker(I,x)}}var L=E[H].getElementsByTagName("layout")[0];if(L&&L.childNodes.length>0){var M=L.getElementsByTagName("group");for(var w=0;w<M.length;w++){var A=M[w];o[C].layout[A.getAttribute("position")]={elements:[]};for(var O=0;O<A.attributes.length;O++){var D=A.attributes[O];o[C].layout[A.getAttribute("position")][D.name]=D.value}var N=A.getElementsByTagName("*");for(var v=0;v<N.length;v++){var t=N[v];o[C].layout[A.getAttribute("position")].elements.push({type:t.tagName});for(var u=0;u<t.attributes.length;u++){var J=t.attributes[u];o[C].layout[A.getAttribute("position")].elements[v][J.name]=J.value}if(!a.utils.exists(o[C].layout[A.getAttribute("position")].elements[v].name)){o[C].layout[A.getAttribute("position")].elements[v].name=t.tagName}}}}e=false;r()}}function r(){clearInterval(j);if(!s){j=setInterval(function(){q()},100)}}function b(y,x){var w=new Image();var t=y.getAttribute("name");var v=y.getAttribute("src");var A;if(v.indexOf("data:image/png;base64,")===0){A=v}else{var u=a.utils.getAbsolutePath(n);var z=u.substr(0,u.lastIndexOf("/"));A=[z,x,v].join("/")}o[x].elements[t]={height:0,width:0,src:"",ready:false,image:w};w.onload=function(B){g(w,t,x)};w.onerror=function(B){s=true;r();l()};w.src=A}function h(){for(var u in o){var w=o[u];for(var t in w.elements){var x=w.elements[t];var v=x.image;v.onload=null;v.onerror=null;delete x.image;delete w.elements[t]}delete o[u]}}function q(){for(var t in o){if(t!="properties"){for(var u in o[t].elements){if(!o[t].elements[u].ready){return}}}}if(e===false){clearInterval(j);c(o)}}function g(t,v,u){if(o[u]&&o[u].elements[v]){o[u].elements[v].height=t.height;o[u].elements[v].width=t.width;o[u].elements[v].src=t.src;o[u].elements[v].ready=true;r()}else{a.utils.log("Loaded an image for a missing element: "+u+"."+v)}}m()}})(jwplayer);(function(a){a.html5.api=function(c,n){var m={};var f=document.createElement("div");c.parentNode.replaceChild(f,c);f.id=c.id;m.version=a.version;m.id=f.id;var l=new a.html5.model(m,f,n);var j=new a.html5.view(m,f,l);var k=new a.html5.controller(m,f,l,j);m.skin=new a.html5.skin();m.jwPlay=function(o){if(typeof o=="undefined"){e()}else{if(o.toString().toLowerCase()=="true"){k.play()}else{k.pause()}}};m.jwPause=function(o){if(typeof o=="undefined"){e()}else{if(o.toString().toLowerCase()=="true"){k.pause()}else{k.play()}}};function e(){if(l.state==a.api.events.state.PLAYING||l.state==a.api.events.state.BUFFERING){k.pause()}else{k.play()}}m.jwStop=k.stop;m.jwSeek=k.seek;m.jwPlaylistItem=k.item;m.jwPlaylistNext=k.next;m.jwPlaylistPrev=k.prev;m.jwResize=k.resize;m.jwLoad=k.load;function h(o){return function(){return l[o]}}function d(o,q,p){return function(){var r=l.plugins.object[o];if(r&&r[q]&&typeof r[q]=="function"){r[q].apply(r,p)}}}m.jwGetItem=h("item");m.jwGetPosition=h("position");m.jwGetDuration=h("duration");m.jwGetBuffer=h("buffer");m.jwGetWidth=h("width");m.jwGetHeight=h("height");m.jwGetFullscreen=h("fullscreen");m.jwSetFullscreen=k.setFullscreen;m.jwGetVolume=h("volume");m.jwSetVolume=k.setVolume;m.jwGetMute=h("mute");m.jwSetMute=k.setMute;m.jwGetStretching=h("stretching");m.jwGetState=h("state");m.jwGetVersion=function(){return m.version};m.jwGetPlaylist=function(){return l.playlist};m.jwGetPlaylistIndex=m.jwGetItem;m.jwAddEventListener=k.addEventListener;m.jwRemoveEventListener=k.removeEventListener;m.jwSendEvent=k.sendEvent;m.jwDockSetButton=function(r,o,p,q){if(l.plugins.object.dock&&l.plugins.object.dock.setButton){l.plugins.object.dock.setButton(r,o,p,q)}};m.jwControlbarShow=d("controlbar","show");m.jwControlbarHide=d("controlbar","hide");m.jwDockShow=d("dock","show");m.jwDockHide=d("dock","hide");m.jwDisplayShow=d("display","show");m.jwDisplayHide=d("display","hide");m.jwGetLevel=function(){};m.jwGetBandwidth=function(){};m.jwGetLockState=function(){};m.jwLock=function(){};m.jwUnlock=function(){};function b(){if(l.config.playlistfile){l.addEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED,g);l.loadPlaylist(l.config.playlistfile)}else{if(typeof l.config.playlist=="string"){l.addEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED,g);l.loadPlaylist(l.config.playlist)}else{l.loadPlaylist(l.config);setTimeout(g,25)}}}function g(o){l.removeEventListener(a.api.events.JWPLAYER_PLAYLIST_LOADED,g);l.setupPlugins();j.setup();var o={id:m.id,version:m.version};k.playerReady(o)}if(l.config.chromeless&&!a.utils.isIOS()){b()}else{m.skin.load(l.config.skin,b)}return m}})(jwplayer)};
function flashLoaded(e) {
 // e.ref is a reference to the Flash object. We'll pass it to jwplayer() so the API knows where the player is.
 //console.log("FlashLoaded");
 // Add event listeners
 
 trailer.startTimers(jwplayer(trailer.elementId));
 
 // Interact with the player
 //jwplayer(e.ref).play();
}

var trailer = {
  
  trailerFilm : $('#film').html(),
  trailerStream : $('#video').html(),
  trailerImage : $('#trailer-image').html(),
  trailerClass: '.watch_trailer',
	div: null,
	dohide: false,
	elementId: null,
	slider: null,
	
	init: function() {
		
		$( trailer.trailerClass).click(function(){
			trailer.linkclick( trailer.trailerClass );
		});
		
	},
	
	clickStart: function( element, film, stream, image ) {
		trailer.trailerFilm = film;
		trailer.trailerStream = stream;
		trailer.trailerImage = image;
		trailer.trailerClass = "#"+$(element).attr('id');
		trailer.linkclick();
	},
	
	linkclick: function() {
		$(trailer.trailerClass).fadeOut(100).queue(function(){
    	// $('.get_info').fadeOut(100); Remove fade from homepage and film page.
			trailer.startTrailer();
    	if (trailer.slider) {
    		trailer.slider.halten();
			}
			$(trailer.trailerClass).clearQueue();
		});
	},
	
  //A convencience method
  //If you don't want to add other controls to this
  startTrailer: function() {
    //console.log("Starting from Trailer");
  	$('#movie_trailer_container').css({zIndex: 100});
    trailer.populateVideo( trailer.trailerFilm, trailer.trailerStream , trailer.trailerImage);
  },
  
  populateVideo : function( film, stream, image ) {
  
    $('#video').html(stream);
    trailer.div = "movie_trailer_" + film;
    trailer.elementId = 'myPlayer_'+Math.floor(Math.random()*101)
		
		//console.log("Setting div to: " + trailer.div);
    //console.log("Stream is: " + stream);
    var auth = stream.split("%3F");
    html = '<div id="'+ trailer.div + '"><img src="'+image+'" class="widget_video_still"  border="0"/></div>';
    $('#movie_trailer_container').append(html);
    $('#movie_trailer_container').css({zIndex: 10});
    
    ///mp4:constellation/uploads/screeningResources/56/trailerFile/JIG TRAILER.mov?auth=da.b3aNadcddyd8bZdKcDaKdhcXbtbRdEcr-bopF58-uP4-HnKC1CArvxn-l8q9tUqer0tVtdnRkvrZpW%26aifp=v0006%26slist=constellation%2Fuploads%2FscreeningResources%2F56%2FtrailerFile%2FJIG+TRAILER
    var flashvars = 
        {file: auth[0]+'%3F'+ auth[1],
        'image':  $("#video_still").html(),
        streamer: 'rtmp://cp113558.edgefcs.net/ondemand'+'%3F'+auth[1],
        'skin':   '/flash/glow/glow.zip',
        'autostart': true
    		};
    		
      var params = 
        {
          allowFullScreen: 'true',
          allowScriptAccess: 'always',
          wmode: 'opaque',
					bgcolor:"#000000"
        };
      var attributes = 
        {
          id: trailer.elementId,
          name: trailer.elementId
        };
      
      //console.log('Loading Trailer');
      swfobject.embedSWF('/flash/mediaplayer-5.7-licensed/player.swf', trailer.div, '100%', '100%', '9.0.0', '/flash/expressInstall.swf', flashvars, params, attributes, flashLoaded);
      
      $(document).click(function(e){
    		trailer.dohide = true;
				trailer.hideVideo();
	    });
  },
  
  stopVideo : function() {
		//$('#video').html('');
		//$('#movie_trailer_container').html('');
		if (jwplayer(trailer.elementId) != null) {
			jwplayer(trailer.elementId).remove();
		}
		swfobject.removeSWF(trailer.elementId);
    //console.log('Removed Player');
  },
  
  startTimer : function() {
  	trailer.dohide = true;
		window.setTimeout(trailer.hideVideo, 2000);
	},
	
	endTimer : function() {
  	trailer.dohide = false;
	},
	
  hideVideo : function() {
		if (trailer.dohide) {
			trailer.stopVideo();
			// $('.get_info').fadeIn(100);
			$(trailer.trailerClass).fadeIn(100);
	  	if (trailer.slider) {
	  		trailer.slider.anfagen();
	
			}
		}
  	$('#movie_trailer_container').css({zIndex: -2});
	},
	
	startTimers : function(player) {
		//console.log("Starting Timers");
		
 		player.onReady(function() { 
		 	//console.log('Player is ready'); 
		});
 		player.onPlay(function() { 
			//console.log('Player is playing'); 
			trailer.endTimer()
		});
		player.onPause(function(){
			//console.log('Player is paused');
			trailer.startTimer()
		});
		player.onComplete(function(){
			//console.log('Player is done');
			trailer.startTimer()
		});

	}
  
}

$(document).ready(function() {
		
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
    if (window.location.pathname.match(/film/)) {
      
      //trailer.init();
      //trailer.startTrailer();
      
    }

});

// JavaScript Document
var countdown_alt = {
  
  init: function(id,adate) {
    //console.log("Init " + id + " with " + adate);
    var times = adate.split('|');
    var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
    
    //try {
	  $('#timekeeper_'+id).countdown({
        layout: '<span style ="text-transform:lowercase">{dnn}<span class="shorty">DAYS</span>{hnn}<span class="shorty">HRS</span>{mnn}<span class="shorty">MIN</span>{snn}<span class="shorty">s</span></span>', 
        until: td,
        serverSync: getCurrentTime,
        format: 'DHMS'
        //,timezone: $("#tz_offset").html()
    });
  }
}

// JavaScript Document
var remaining_alt = {
  
  init: function() {
    
    $(".time_remaining").each(function() {
    	var times = $(this).html().split('|');
			
    	var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
    	
			//try {
		  $($(this)).countdown({
	        layout: '{hnn}:{mnn}:{snn} Elapsed', 
	        since: td,
        	serverSync: getCurrentTime,
	        format: 'HMS'
	        //onTick: remaining_alt.tickin
	        //,timezone: $("#tz_offset").html()
	    });
	    $(this).fadeIn(100);
	    
		});
    
  },
  
  run: function( id, atime ) {
    //console.log(id + " " + atime)
  	var times = atime.split('|');

  	var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
  	//console.log(td);
  	 
  	//try {
	  $("#time_remaining_"+id).countdown({
        layout: '{hnn}:{mnn}:{snn} Elapsed', 
        since: td,
      	serverSync: getCurrentTime,
        format: 'HMS'
        //onTick: remaining_alt.tickin
        //,timezone: $("#tz_offset").html()
    });
  },
  
  runHrMin: function( id, atime ) {
    //console.log(id + " " + atime)
    var times = atime.split('|');

    var td = new Date(times[0], times[1]-1, times[2], times[3], times[4], times[5]);
    //console.log(td);
    
    //try {
    $("#time_remaining_"+id).countdown({
        layout: '<span>{hnn}<span class="shorty">HRS</span>{mnn}<span class="shorty">MIN</span></span>', 
        since: td,
        serverSync: getCurrentTime,
        format: 'HMS'
        //onTick: remaining_alt.tickin
        //,timezone: $("#tz_offset").html()
    });
  },
  
	//To enable this, we need to add the end time, and calculate each time
  tickin: function(periods) {
  	var id = $(this).attr("id").replace("time_remaining_","");
  	//console.log(id);
		//console.log(periods[4]+" hours " + periods[5]+" minutes " + periods[6]+" seconds");
		if ((periods[4] == 0) && (periods[5] == 0) && (periods[6] == 0)) {
  		$(this).fadeOut(100);
		}
	}
}


$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
		remaining_alt.init();
});
// JavaScript Document

var allfilms = {
	
	showing: false,
	
	init: function() {
		$(".main-films a").click(function(e){
			e.stopPropagation();
			$(".allfilms").toggle();
			if (allfilms.showing) {
				allfilms.showing = false;
			} else {
				allfilms.showing = true;
			}
			//
		});
		
		$(document).click(function(e){
			if (allfilms.showing) {
				$(".allfilms").hide();
				allfilms.showing = false;
			}
		});
	}
	
}

$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
    
		allfilms.init();
});

(function($){$.fn.editable=function(target,options){if('disable'==target){$(this).data('disabled.editable',true);return;}
if('enable'==target){$(this).data('disabled.editable',false);return;}
if('destroy'==target){$(this).unbind($(this).data('event.editable')).removeData('disabled.editable').removeData('event.editable');return;}
var settings=$.extend({},$.fn.editable.defaults,{target:target},options);var plugin=$.editable.types[settings.type].plugin||function(){};var submit=$.editable.types[settings.type].submit||function(){};var buttons=$.editable.types[settings.type].buttons||$.editable.types['defaults'].buttons;var content=$.editable.types[settings.type].content||$.editable.types['defaults'].content;var element=$.editable.types[settings.type].element||$.editable.types['defaults'].element;var reset=$.editable.types[settings.type].reset||$.editable.types['defaults'].reset;var callback=settings.callback||function(){};var onedit=settings.onedit||function(){};var onsubmit=settings.onsubmit||function(){};var onreset=settings.onreset||function(){};var onerror=settings.onerror||reset;if(settings.tooltip){$(this).attr('title',settings.tooltip);}
settings.autowidth='auto'==settings.width;settings.autoheight='auto'==settings.height;return this.each(function(){var self=this;var savedwidth=$(self).width();var savedheight=$(self).height();$(this).data('event.editable',settings.event);if(!$.trim($(this).html())){$(this).html(settings.placeholder);}
$(this).bind(settings.event,function(e){if(true===$(this).data('disabled.editable')){return;}
if(self.editing){return;}
if(false===onedit.apply(this,[settings,self])){return;}
e.preventDefault();e.stopPropagation();if(settings.tooltip){$(self).removeAttr('title');}
if(0==$(self).width()){settings.width=savedwidth;settings.height=savedheight;}else{if(settings.width!='none'){settings.width=settings.autowidth?$(self).width():settings.width;}
if(settings.height!='none'){settings.height=settings.autoheight?$(self).height():settings.height;}}
if($(this).html().toLowerCase().replace(/(;|")/g,'')==settings.placeholder.toLowerCase().replace(/(;|")/g,'')){$(this).html('');}
self.editing=true;self.revert=$(self).html();$(self).html('');var form=$('<form />');if(settings.cssclass){if('inherit'==settings.cssclass){form.attr('class',$(self).attr('class'));}else{form.attr('class',settings.cssclass);}}
if(settings.style){if('inherit'==settings.style){form.attr('style',$(self).attr('style'));form.css('display',$(self).css('display'));}else{form.attr('style',settings.style);}}
var input=element.apply(form,[settings,self]);var input_content;if(settings.loadurl){var t=setTimeout(function(){input.disabled=true;content.apply(form,[settings.loadtext,settings,self]);},100);var loaddata={};loaddata[settings.id]=self.id;if($.isFunction(settings.loaddata)){$.extend(loaddata,settings.loaddata.apply(self,[self.revert,settings]));}else{$.extend(loaddata,settings.loaddata);}
$.ajax({type:settings.loadtype,url:settings.loadurl,data:loaddata,async:false,success:function(result){window.clearTimeout(t);input_content=result;input.disabled=false;}});}else if(settings.data){input_content=settings.data;if($.isFunction(settings.data)){input_content=settings.data.apply(self,[self.revert,settings]);}}else{input_content=self.revert;}
content.apply(form,[input_content,settings,self]);input.attr('name',settings.name);buttons.apply(form,[settings,self]);$(self).append(form);plugin.apply(form,[settings,self]);$(':input:visible:enabled:first',form).focus();if(settings.select){input.select();}
input.keydown(function(e){if(e.keyCode==27){e.preventDefault();reset.apply(form,[settings,self]);}});var t;if('cancel'==settings.onblur){input.blur(function(e){t=setTimeout(function(){reset.apply(form,[settings,self]);},500);});}else if('submit'==settings.onblur){input.blur(function(e){t=setTimeout(function(){form.submit();},200);});}else if($.isFunction(settings.onblur)){input.blur(function(e){settings.onblur.apply(self,[input.val(),settings]);});}else{input.blur(function(e){});}
form.submit(function(e){if(t){clearTimeout(t);}
e.preventDefault();if(false!==onsubmit.apply(form,[settings,self])){if(false!==submit.apply(form,[settings,self])){if($.isFunction(settings.target)){var str=settings.target.apply(self,[input.val(),settings]);$(self).html(str);self.editing=false;callback.apply(self,[self.innerHTML,settings]);if(!$.trim($(self).html())){$(self).html(settings.placeholder);}}else{var submitdata={};submitdata[settings.name]=input.val();submitdata[settings.id]=self.id;if($.isFunction(settings.submitdata)){$.extend(submitdata,settings.submitdata.apply(self,[self.revert,settings]));}else{$.extend(submitdata,settings.submitdata);}
if('PUT'==settings.method){submitdata['_method']='put';}
$(self).html(settings.indicator);var ajaxoptions={type:'POST',data:submitdata,dataType:'html',url:settings.target,success:function(result,status){if(ajaxoptions.dataType=='html'){$(self).html(result);}
self.editing=false;callback.apply(self,[result,settings]);if(!$.trim($(self).html())){$(self).html(settings.placeholder);}},error:function(xhr,status,error){onerror.apply(form,[settings,self,xhr]);}};$.extend(ajaxoptions,settings.ajaxoptions);$.ajax(ajaxoptions);}}}
$(self).attr('title',settings.tooltip);return false;});});this.reset=function(form){if(this.editing){if(false!==onreset.apply(form,[settings,self])){$(self).html(self.revert);self.editing=false;if(!$.trim($(self).html())){$(self).html(settings.placeholder);}
if(settings.tooltip){$(self).attr('title',settings.tooltip);}}}};});};$.editable={types:{defaults:{element:function(settings,original){var input=$('<input type="hidden"></input>');$(this).append(input);return(input);},content:function(string,settings,original){$(':input:first',this).val(string);},reset:function(settings,original){original.reset(this);},buttons:function(settings,original){var form=this;if(settings.submit){if(settings.submit.match(/>$/)){var submit=$(settings.submit).click(function(){if(submit.attr("type")!="submit"){form.submit();}});}else{var submit=$('<button type="submit" />');submit.html(settings.submit);}
$(this).append(submit);}
if(settings.cancel){if(settings.cancel.match(/>$/)){var cancel=$(settings.cancel);}else{var cancel=$('<button type="cancel" />');cancel.html(settings.cancel);}
$(this).append(cancel);$(cancel).click(function(event){if($.isFunction($.editable.types[settings.type].reset)){var reset=$.editable.types[settings.type].reset;}else{var reset=$.editable.types['defaults'].reset;}
reset.apply(form,[settings,original]);return false;});}}},text:{element:function(settings,original){var input=$('<input />');if(settings.width!='none'){input.width(settings.width);}
if(settings.height!='none'){input.height(settings.height);}
input.attr('autocomplete','off');$(this).append(input);return(input);}},textarea:{element:function(settings,original){var textarea=$('<textarea />');if(settings.rows){textarea.attr('rows',settings.rows);}else if(settings.height!="none"){textarea.height(settings.height);}
if(settings.cols){textarea.attr('cols',settings.cols);}else if(settings.width!="none"){textarea.width(settings.width);}
$(this).append(textarea);return(textarea);}},select:{element:function(settings,original){var select=$('<select />');$(this).append(select);return(select);},content:function(data,settings,original){if(String==data.constructor){eval('var json = '+data);}else{var json=data;}
for(var key in json){if(!json.hasOwnProperty(key)){continue;}
if('selected'==key){continue;}
var option=$('<option />').val(key).append(json[key]);$('select',this).append(option);}
$('select',this).children().each(function(){if($(this).val()==json['selected']||$(this).text()==$.trim(original.revert)){$(this).attr('selected','selected');}});}}},addInputType:function(name,input){$.editable.types[name]=input;}};$.fn.editable.defaults={name:'value',id:'id',type:'text',width:'auto',height:'auto',event:'click.editable',onblur:'cancel',loadtype:'GET',loadtext:'Loading...',placeholder:'Click to edit',loaddata:{},submitdata:{},ajaxoptions:{}};})(jQuery);
(function($){$.fn.inputlimiter=function(options){var opts=$.extend({},$.fn.inputlimiter.defaults,options);if(opts.boxAttach&&!$('#'+opts.boxId).length)
{$('<div/>').appendTo("body").attr({id:opts.boxId,'class':opts.boxClass}).css({'position':'absolute'}).hide();if($.fn.bgiframe)
$('#'+opts.boxId).bgiframe();}
$(this).each(function(i){$(this).keyup(function(e){if($(this).val().length>opts.limit)
$(this).val($(this).val().substring(0,opts.limit));if(opts.boxAttach)
{$('#'+opts.boxId).css({'width':$(this).outerWidth()-($('#'+opts.boxId).outerWidth()-$('#'+opts.boxId).width())+'px','left':$(this).offset().left+'px','top':($(this).offset().top+$(this).outerHeight())-1+'px','z-index':2000});}
var charsRemaining=opts.limit-$(this).val().length;var remText=opts.remTextFilter(opts,charsRemaining);var limitText=opts.limitTextFilter(opts);if(opts.limitTextShow)
{$('#'+opts.boxId).html(remText+' '+limitText);var textWidth=$("<span/>").appendTo("body").attr({id:'19cc9195583bfae1fad88e19d443be7a','class':opts.boxClass}).html(remText+' '+limitText).innerWidth();$("#19cc9195583bfae1fad88e19d443be7a").remove();if(textWidth>$('#'+opts.boxId).innerWidth()){$('#'+opts.boxId).html(remText+'<br />'+limitText);}
$('#'+opts.boxId).show();}
else
$('#'+opts.boxId).html(remText).show();});$(this).keypress(function(e){if((!e.keyCode||(e.keyCode>46&&e.keyCode<90))&&$(this).val().length>=opts.limit)
return false;});$(this).blur(function(){if(opts.boxAttach)
{$('#'+opts.boxId).fadeOut('fast');}
else if(opts.remTextHideOnBlur)
{var limitText=opts.limitText;limitText=limitText.replace(/\%n/g,opts.limit);limitText=limitText.replace(/\%s/g,(opts.limit==1?'':'s'));$('#'+opts.boxId).html(limitText);}});});};$.fn.inputlimiter.remtextfilter=function(opts,charsRemaining){var remText=opts.remText;if(charsRemaining==0&&opts.remFullText!=null){remText=opts.remFullText;}
remText=remText.replace(/\%n/g,charsRemaining);remText=remText.replace(/\%s/g,(opts.zeroPlural?(charsRemaining==1?'':'s'):(charsRemaining<=1?'':'s')));return remText;};$.fn.inputlimiter.limittextfilter=function(opts){var limitText=opts.limitText;limitText=limitText.replace(/\%n/g,opts.limit);limitText=limitText.replace(/\%s/g,(opts.limit<=1?'':'s'));return limitText;};$.fn.inputlimiter.defaults={limit:255,boxAttach:true,boxId:'limiterBox',boxClass:'limiterBox',remText:'%n character%s remaining.',remTextFilter:$.fn.inputlimiter.remtextfilter,remTextHideOnBlur:true,remFullText:null,limitTextShow:true,limitText:'Field limited to %n character%s.',limitTextFilter:$.fn.inputlimiter.limittextfilter,zeroPlural:true};})(jQuery);
var header = {

    options:{

    },

    init: function(){
        this.loginButton = $('#loggedUser');
        if(this.loginButton){
            this.parentContainer = $(this.loginButton.parent());
            this.dropDown = $(this.loginButton.next()).css({height: 0, display: 'block'});;
            this.isOpen = false;
            this.attachEvents();
        }
    },
    attachEvents: function(){
        var self = this;
        this.loginButton.bind('click', jQuery.proxy(self, 'toggle'));
    },
    toggle: function(e){
        e.stopPropagation()
        if(!this.isOpen){
            this.open();
        } else {
            this.close();
        }
    },
    open: function(){
        this.isOpen = true;
        this.parentContainer.addClass('active');
        this.dropDown.animate({'height': 92});
        $('body').bind('click', jQuery.proxy(this, 'close'));
    },
    close: function(){
        this.isOpen = false;
        this.parentContainer.removeClass('active');
        this.dropDown.animate({'height': 0});
        $('body').unbind('click', jQuery.proxy(this, 'close'));
    }
};

(function(){
    header.init();
})();var CTV = CTV || {};

CTV.Comments = function(options) {
	this.options = $.extend({
		list: null,
		filmId: null,
		screeningId: 0,		
		userId: 0,
		isLoggedIn: false,
		fbShareImage: 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png',
		fbShareCaption: window.document.title,
		tShareCaption: '',
		rpp: 5,
		showFilmTemplate: false,
		noCommentText: 'Be the first to post',
		noCommentTag: 'li',
		sort: 'recent'
	}, options);
	this.init();

}

CTV.Comments.prototype = {
	template: '<li> \
	<div class="comment-item">\
		<a href="/profile/{{authorid}}"><img src="{{avatar}}" width="50" height="50" class="comment-avatar comment-item-avatar"/></a>\
		<div class="comment-item-content">\
			<div class="comment-item-details">\
				<span class="comment-item-user"><a href="/profile/{{authorid}}">{{authorname}}</a></span>\
				<span class="comment-item-time">{{timestamp}}</span>\
			</div>\
			<div class="comment-item-comment">{{comment}}</div>\
			<p class="comment-item-tools"><a href="javascript:void(0)" class="comment-item-reply"></a><a href="" target="_blank" class="comment-item-share-fb"></a><a href="" target="_blank" class="comment-item-share-twitter"></a><a href="javascript:void(0)" class="comment-item-flag">Flag as inappropriate</a></p>\
		</div>\
		<div class="comment-item-vote">\
			<span class="comment-item-vote-icon"></span> <span class="comment-item-vote-count"></span>\
		</div>\
	</div>\
	<ul></ul>\
</li>',

	filmTemplate: '<div class="ctv-panel-happening-conversation-block clearfix">\
				<div class="conversation-poster">\
					<a href="/film/{{film}}"><img width="90" src="'+ __CONFIG.assetUrl + '/uploads/screeningResources/{{film}}/logo/small_poster{{film_logo}}"></a>\
				</div>\
				<div class="title"><a href="/film/{{film}}">{{film_name}}</a></div>\
				<ul class="comment-list"></ul>\
				\
		        <p class="clear"><a class="button_inset" href="/film/{{film}}"><span class="comment-more"></span><span>More Discussions</span></a></p>\
			</div>',

	commentBox: '<div class="comment-box clearfix">\
					<textarea placeholder="Add your voice to the conversation!" class="comment-box-field"></textarea>\
					<span class="button button_orange_medium uppercase">post</span>\
				</div>',

	urls: {
		list: '/services/conversation/init',
		vote: '/services/conversation/vote',
		flag: '/services/conversation/flag'
	},
	init: function() {
		this.domNode = this.options.list;
		this.commentBox = $(this.commentBox);
		this.currentPage = 1;

		this.getComments();
		this.attachEvents();
		if ( !! $('#comment-box').length) {
			this.CommentBox = new CTV.CommentBox({
				filmId: this.options.filmId,
				screeningId: this.options.screeningId
			}, '#comment-box');
			$(this.CommentBox).bind('addComment', _.bind(this.onAddComment, this));
		}
		this.ReplyBox = new CTV.CommentBox({
			isReply: true,
			filmId: this.options.filmId,
			screeningId: this.options.screeningId
		}, this.commentBox);
		$(this.ReplyBox).bind('addComment', _.bind(this.onAddComment, this));
		if ( !! $('#pagination').length) {
			this.pagination = new CTV.Pagination({}, '#pagination');
			$(this.pagination).bind('paginate', _.bind(this.onPaginate, this));
		}
	},
	attachEvents: function() {

		if (this.options.isLoggedIn) {
			$('.comment-item-vote-icon', this.domNode).live('click', _.bind(this.onVoteClick, this))
			$('.comment-item-reply', this.domNode).live('click', _.bind(this.onReplyClick, this))
			$('.comment-item-flag', this.domNode).live('click', _.bind(this.onFlagClick, this))
		} else {
			$('.comment-item-vote-icon', this.domNode).live('click', _.bind(this.showLogin, this))
			$('.comment-item-reply', this.domNode).live('click', _.bind(this.showLogin, this))
			$('.comment-item-flag', this.domNode).live('click', _.bind(this.showLogin, this))
		}
	},
	getComments: function() {
		$.ajax({
			type: 'GET',
			url: this.urls.list,
			data: {
				f: this.options.filmId,
				e: this.options.screeningId,
				p: this.currentPage,
				r: this.options.rpp,
				u: this.options.userId,
				s: this.options.sort
			},
			dataType: 'json',
			success: _.bind(this.showComments, this)
		});
	},
	showComments: function(response) {
		if (response == null) {
			return;
		}
		this.domNode.removeClass('comment-loading');
		if (response.meta.success) {
			this.ReplyBox.detach();
			this.domNode.empty();
			if (response.meta.totalresults > 0) {
				_.each(response.response.data, _.bind(this.addComment, this, this.domNode), this);
				response.meta.currentPage = this.currentPage;
				if (this.pagination) {
					this.pagination.render(response.meta);
				}
			} else {
				this.addNoComments();
				this.currentPage = 1;
			}
		} else {
			// console.warn('bummer!');
		}
	},
	addComment: function(container, data, prepend) {
		if (this.noComments) {
			this.noComments.remove();
			this.noComments = null;
		}
		
		if(!/http/.test(data.avatar)){
 			data.avatar = __CONFIG.assetUrl + data.avatar
 		}

		var comment = $(_.template(this.template, data));

		$('.comment-item-vote-icon', comment).data('data', data);

		$('.comment-item-vote-count', comment).html(data.favorite_count ? '+' + data.favorite_count : '');
		if (container == this.domNode) {
			$('.comment-item-reply', comment).data('data', data);
		} else {
			$('.comment-item-reply', comment).remove();
		}
		$('.comment-item-flag', comment).data('data', data);

		$('.comment-item-share-fb', comment).attr('href', this.buildFacebookShareLink(data))
		$('.comment-item-share-twitter', comment).attr('href', this.buildTwitterShareLink(data))

		if (data.replies && !! data.replies.length) {
			var replyContainer = $('ul', comment);
			replyContainer.appendTo(comment)
			_.each(data.replies, _.bind(this.addComment, this, replyContainer), this);
		}

		if (this.options.showFilmTemplate && data.film) {
			var filmTemplate = $(_.template(this.filmTemplate, data));
			$(filmTemplate.find('ul')).append(comment);
			filmTemplate.appendTo(this.domNode);
		} else if (typeof prepend == 'boolean' && prepend) {
			comment.prependTo(container ? container : this.domNode);
		} else {
			comment.appendTo(container ? container : this.domNode);
		}
	},
	addNoComments: function() {
		this.noComments = $('<' + this.options.noCommentTag + ' class="comment-item-no-comment">' + this.options.noCommentText + '</' + this.options.noCommentTag + '>').appendTo(this.domNode);
	},
	showLogin: function(e) {
		// $("#login_destination").val();
		// $("#signup_destination").val();
		// login.showpopup();
		$(window).trigger('auth:login');
	},
	buildFacebookShareLink: function(data) {

		var string = 'http://www.facebook.com/dialog/feed';
		string += '?app_id=185162594831374'
		string += '&link=' + encodeURIComponent(window.location.href)
		// string += '&message=' + encodeURIComponent( data.authorname + ' posted: ' + data.comment);
		string += '&picture=' + encodeURIComponent(this.options.fbShareImage);
		string += '&caption=' + encodeURIComponent(this.options.fbShareCaption);
		string += '&description=' + encodeURIComponent(data.authorname + ' posted: ' + data.comment);
		string += '&redirect_uri=' + encodeURIComponent(window.location.href)
		return string;
	},
	buildTwitterShareLink: function(data) {
		return 'http://twitter.com/share?text=' + encodeURIComponent(this.options.tShareCaption + ' ' + data.authorname + ' posted "' + data.comment + '"') + '&url=' + encodeURIComponent(window.location.href)
	},
	onVoteClick: function(e) {
		var target = $(e.target);
		var isDisabled = target.data('isDisabled');
		if (!isDisabled) {
			target.data('isDisabled', true);
			target.addClass('comment-item-vote-submitted');
			var data = target.data('data'),
				html = target.next().html(),
				count = parseInt( !! html ? html : 0);

			target.next().html('+' + (++count));
			$.ajax({
				type: 'GET',
				url: this.urls.vote,
				data: {
					c: data.id
				},
				dataType: 'json'
			});

		}
	},
	onFlagClick: function(e) {
		var target = $(e.target);
		var isDisabled = target.data('isDisabled');
		if (!isDisabled) {
			target.data('isDisabled', true);

			$.ajax({
				type: 'GET',
				url: this.urls.flag,
				data: {
					c: target.data('data').id
				},
				dataType: 'json',
				success: _.bind(this.onFlagSuccess, this, target)
			});

		}
	},
	onFlagSuccess: function(target, response) {
		if (response.meta.success && response.meta.success != 'false') {
			var parent = target.parent().parent().find('.comment-item-comment').html('This post has been flagged as inappropriate!')
		}
	},
	onReplyClick: function(e) {
		var isCurrent = $(e.target).data('isCurrent');
		if (isCurrent) {
			this.commentBox.detach();
			$(e.target).data('isCurrent', false);
		} else {
			$('.comment-item-reply', this.domNode).data('isCurrent', false);
			$(e.target).data('isCurrent', true);
			var target = $(e.target).parents('.comment-item-content');
			var data = jQuery.data($(e.target), 'data');
			target.after(this.commentBox);
			this.ReplyBox.setId($(e.target).data('data').id);
		}
	},
	onPaginate: function(e, page) {
		this.currentPage = page;
		this.getComments();
	},
	onAddComment: function(e, response, container) {
		this.addComment(container ? container : this.domNode, response.response.data[0], container ? false : true);
	}
}


CTV.CommentBox = function(options, domNode) {
	this.options = $.extend({
		urls: {
			post: '/services/conversation/post',
			isReply: false
		},
		screeningId: 0,
		filmId: null
	}, options);

	this.domNode = $(domNode)
	this.init();
}
CTV.CommentBox.prototype = {
	init: function() {
		this.submitButton = $('.button', this.domNode);
		this.textarea = $('textarea', this.domNode);
		this.attachEvents();

		if (this.options.isReply) {
			this.idInput = $('<input type="hidden" />').appendTo(this.domNode);
		}
	},
	attachEvents: function() {
		this.submitButton.bind('click', _.bind(this.onSubmitClick, this));
	},
	onSubmitClick: function() {
		var value = this.textarea.val();
		var image = $('textarea', this.domNode);
		if ( !! value) {
			this.postComment();
		} else {

		}
	},
	postComment: function() {
		var data = {
			c: this.idInput ? this.idInput.val() : undefined,
			f: this.options.filmId,
			e: this.options.screeningId,
			b: this.textarea.val()
		}
		this.textarea.val('')

		$.ajax({
			type: 'GET',
			url: this.options.urls.post,
			data: data,
			success: _.bind(this.onSuccess, this),
			dataType: 'json'
		});
	},
	onSuccess: function(response) {
		if (response.meta.success) {
			$(this).trigger('addComment', [response, this.options.isReply ? this.domNode.parents('.comment-item').next('ul') : null])

			if (this.options.isReply) {
				this.domNode.detach()
			}
		}
	},
	detach: function() {
		this.domNode.detach()
	},
	setId: function(id) {
		this.idInput.val(id)
	}
}


CTV.Pagination = function(options, domNode) {
	this.options = $.extend({
		list: null,
		urls: {
			post: '/services/conversation/post'
		}
	}, options);
	this.domNode = $(domNode);
	this.pagingContainer = $('ul', this.domNode);
	this.init();
}
CTV.Pagination.prototype = {
	templatePage: '<li><span class="button-pagination">{{page}}</span></li>',
	init: function() {
		this.isRunning = false;
		this.attachEvents();
	},

	attachEvents: function() {
		$('.button-pagination', this.domNode).live('click', _.bind(this.onButtonClick, this))
	},
	render: function(meta) {

		this.currentPage = parseInt(meta.currentPage);
		// window.location.hash = 'p=' + this.currentPage;
		var totalPages = Math.ceil(meta.totalresults / meta.rpp);
		this.pagingContainer.empty();


		if (totalPages > 1) this.addPrevious();

		if (totalPages > 4) {
			var pageFloor, 
				pageCeil, 
				extendMin = false,
				extendMax = false;

			if (this.currentPage <= 3 && totalPages > 6) {
				pageFloor = 1;
				pageCeil = pageFloor + 4;
				extendMax = true;
			} else if (this.currentPage <= 3) {
				pageFloor = 1;
				pageCeil = pageFloor + 5;
			} else if ((this.currentPage - 3) < 0) {
				pageFloor = 1;
				pageCeil = pageFloor + 5;
			} else if ((this.currentPage - 2) >= 0 && (this.currentPage + 3) <= totalPages) {
				extendMin = true;
				extendMax = true;
				pageFloor = this.currentPage - 1;
				pageCeil = this.currentPage + 1;
			} else if ((this.currentPage - 2) >= 0 && (this.currentPage + 3) >= totalPages) {
				extendMin = true;
				pageCeil = totalPages;
				pageFloor = pageCeil - 4;
			} else {
				pageCeil = totalPages;
				pageFloor = pageCeil - 5;
			}

			if (extendMin) {
				this.addPage(1);
				this.addExtend();
			}
			for (var i = pageFloor; i <= pageCeil; i++) {
				this.addPage(i);
			}
			if (extendMax) {
				this.addExtend();
				this.addPage(totalPages);
			}

		} else {
			for (var i = 1; i <= totalPages; i++) {
				this.addPage(i);
			}
		}
		if (totalPages > 1) this.addNext(totalPages);
		this.isRunning = false;
	},
	addPage: function(index) {
		var data = {
			page: index
		}
		var page = $(_.template(this.templatePage, data));
		$('span', page).data('index', (index));
		if ((index) == this.currentPage) {
			$('span', page).addClass('current')
		}
		page.appendTo(this.pagingContainer);

	},
	addNext: function(totalPages) {
		var data = {
			page: '&raquo;'
		}
		var button = $(_.template(this.templatePage, data));
		$('span', button).data('index', (this.currentPage + 1));
		if (this.currentPage == totalPages) $('span', button).addClass('disabled');
		button.appendTo(this.pagingContainer);
	},
	addPrevious: function(totalPages) {
		var data = {
			page: '&laquo;'
		}
		var button = $(_.template(this.templatePage, data));
		$('span', button).data('index', (this.currentPage - 1));
		if (this.currentPage == 1) $('span', button).addClass('disabled');
		button.appendTo(this.pagingContainer);
	},
	addExtend: function() {
		$('<li class="extend">...</li>').appendTo(this.pagingContainer);
	},
	onButtonClick: function(e) {
		var button = $(e.target);
		if (!button.hasClass('current') && !button.hasClass('disabled') && !this.isRunning) {
			this.isRunning = true;
			$(this).trigger('paginate', button.data('index'))
		}
	}
};var CTV = CTV || {};
CTV.Dialog = function(options){
	 this.options = $.extend({
	 	pad: 40,
	 	isIframe: false
    }, options);
    this.init();
 
}

CTV.Dialog.prototype = {
	template: '<div id="dialog" class="pops dialog"></div>',
	init: function(){

		this.domNode = $(this.template).appendTo($('body'));
		this.overlay = $('<div class="overlay"></div>').appendTo($('body'));
		this.overlay.bind('click', _.bind(this.close, this))
	},
	open: function(options){
		this.setBody(options);
		if(this.options.isIframe) {
			FB.Canvas.scrollTo(0,0);
		}
		this.domNode.fadeIn();
		this.overlay.fadeIn();
	},
	setBody: function(options){
		this.domNode.empty();
		this.domNode.attr('class','').addClass('dialog');
		if(options.klass){
			this.domNode.addClass(options.klass)
		}
		if(options.title){
			$('<h4>'+options.title+'</h4>').appendTo(this.domNode);
		}
		if(options.body){
			var content = $('<div class="dialog-content"></div>').appendTo(this.domNode);
			if(typeof options.body == 'string'){
				content.html(options.body)
			} else {
				$(options.body).appendTo(content)
			}
		}
		if(options.buttons){
			var buttonWrap = $('<div class="dialog-buttons"></div>').appendTo(this.domNode);
			_.each(options.buttons, _.bind(this.appendButton,this, buttonWrap));
			// $('<h4>'+options.title+'</h4>').appendTo(this.domNode);
		}
		if(options.image){
			this.appendImage(options.image)
		}
		if(options.video){
			
		}
	},
	appendButton: function(buttonWrap, button){
		$('<span class="button button_blue button-medium">' + button.text + '</span>')
		.bind('click', _.bind(function(){
			if(button.callback) button.callback();
			this.close();
		},this))
		.appendTo(buttonWrap)
	},
	appendImage: function(imageSrc){
		this.domNode.css({width: 40, height: 40}).addClass('loading');
		this.image = $('<img src="'+ imageSrc +'"/>')
			.bind('load', _.bind(this.onImageLoaded, this)); //.html(content.html()).appendTo($('body'));
			// var width = temp.width();
			// var height = temp.height();
			// // console.log(width, height);

			// var wWidth =  $(window).width();
			// var wHeight =  $(window).height();

			// this.domNode.css({
			// 	height: height,
			// 	width: width,
			// 	left: - (width / 2) -20
			// })
			// content.html(temp.html());
			// temp.remove();
	},
	onImageLoaded: function(){
		var content = $('<div class="dialog-content"></div>').appendTo(this.domNode);
		this.image.appendTo(content);
		var maxHeight = $(window).height() - this.options.pad;
		var maxWidth = $(window).width() - this.options.pad;

			var width = this.image.width();
			var height = this.image.height();

		//if image is taller than window...
		if(height > maxHeight + 40) {
			// console.log('here')
			this.image.height(maxHeight);
			this.image.css({
				height: maxHeight - 40,
				width: (width * (maxHeight / height))
			});
			var width = this.image.width()
			this.domNode.css({
				height: maxHeight - 40,
				width: width,
				left: - (width / 2) - 20,
				top: 20
			});
		} else  if(width > maxWidth + 40){

			this.image.width = maxWidth - 40;
			this.image.css({
				width: maxWidth - 40
			});
			this.domNode.css({
				height: this.image.height(),
				width: maxWidth - 40,
				left: - (maxWidth / 2)
			});
		} else {
			this.domNode.css({
				height: height,
				width: width,
				left: - (width / 2) -20
			});
		}

		// this.domNode.
		//get rid of styles
		// this.messageBox.setStyles({ height: '', width: '' });

	},
	close: function(){
		this.domNode.fadeOut(300, _.bind(this.empty, this))
		this.overlay.fadeOut(300);		
	},
	empty: function(){
		if(this.image) {
			this.image.remove(); this.image = null;
		}
		this.domNode.empty().attr('style', '');
		this.overlay.hide();
	}
}
var Dialog;
$(document).ready(function() {
    if (!window.console) window.console = {};
    if (!window.console.log) window.console.log = function() {};
 		
	Dialog = new CTV.Dialog();
});
var CTV = CTV || {};
CTV.ShowtimeDetail = function(options){
	this.options = $.extend({
		triggers: '.showtime-pop',
		text: 'Youâ€™ll be buying a ticket to an online screening where you can chat with your fellow attendees while you watch.'
    }, options);
    this.init();
} 

CTV.ShowtimeDetail.prototype = {
	template: '<div class="pop-showtime-detail"><div class="pop-showtime-screening">{{details}}<div class="">{{text}}</div><div class="pop-showtime-attendees"><p class="uppercase">Attendees</p><ul class="clearfix">{{attendees}}</ul></p></div><div class="pop-showtime-buttonwrap">{{button}}</div></div>',
	templateTime: '<div class="date">{{date}}</div>',
	templateHost: '<div class="hosted clearfix"><img src="{{avatar}}" width="48"  /><p class="host">Hosted by {{screening_user_full_name}}</p><p class="date">{{date}}</p></div>',
	init: function(){
		this.triggers = $(this.options.triggers).live('click', _.bind(this.open, this));
	},

	open: function(event){
		var target = $(event.target);

		if(!target.hasClass('showtime-pop')){
			target = target.parents(this.options.triggers);
		}
		var screeningData = target.data('screening')

		if($(event.target).is('img')) {
			if($(event.target).hasClass('showtime-film-logo')){
				window.location = '/film/'+ screeningData.screening_film_id;
				return false;
			} else if($(event.target).hasClass('showtime-profile-avatar')) {
				window.location = '/profile/'+ screeningData.screening_user_id;
				return false;
			}
		} else {
			this.getAttendees(screeningData);
		}
	},
	getDialogOptions: function(attendees, screeningData, isAttending){
		var templateData = {}
		templateData.attendees = attendees;
		templateData.text = screeningData.screening_description || this.options.text
		templateData.screening_unique_id = screeningData.screening_id;
		if(!!screeningData.screening_user_full_name){
			screeningData.avatar = this.getUserAvatar(screeningData);
			templateData.details = _.template(this.templateHost, screeningData)
		} else {
			templateData.details = _.template(this.templateTime, screeningData)
		}
		if(screeningData.screening_film_geoblocking_enabled == '1' || screeningData.screening_film_geoblocking_enabled == 'true' ){
			templateData.button = '<p class="error-block error-block-small">We\'re sorry, this film cannot be streamed in your current location.</p>';
		} else if(isAttending){
			templateData.button = _.template('<a href="/theater/{{screening_unique_key}}" class="button button_orange uppercase">Enter Theater</a>', screeningData);			
		} else {
			templateData.button = _.template('<a href="/boxoffice/screening/{{screening_unique_key}}" class="button button_green uppercase">RSVP</a>', screeningData);
		} 

		options = {};
		options.klass = 'dialog-showtime';
		options.title = 'Attend the "' + screeningData.screening_film_name + '" Screening';
		options.body = $(_.template(this.template, templateData));

		return options;
	},
	getAttendees: function(screeningData){
		$.ajax({url:'/services/Screenings/users?screening='+ screeningData.screening_id , 
		    type: "GET", 
		    cache: false, 
		    dataType: "json", 
		    success: _.bind(this.onGetAttendeesSucess, this, screeningData)
		});
	}, 
	onGetAttendeesSucess: function(screeningData, response){

		var attendees = '',
			isAttending = false;
		if(parseInt(response.totalresults)>0){
			_.each(response.users, function(user, index){
				if(index < 200){
					attendees += '<li><a href="/profile/'+ user.id +'"><img src="' + user.image + '" height="48" width="48"><p>'+ user.username+'</p></a></li>';
				}
				if (user.id == this.options.userId) isAttending = true;
			}, this);
		} else {
			attendees = '<li class="be-first">Be the first to Join!</li>'
		}

		var options = this.getDialogOptions(attendees, screeningData, isAttending);
		Dialog.open(options);
	},
	getUserAvatar: function(showtime){
		var avatar = 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png';
		if (showtime.screening_user_photo_url != '') {
			if (showtime.screening_user_photo_url.substr(0,4) == 'http') {
				avatar = showtime.screening_user_photo_url;
			} else {
				avatar = __CONFIG.assetUrl+ '/uploads/hosts/'+showtime.screening_user_id+'/'+showtime.screening_user_photo_url;
			}
		} else if (showtime.screening_user_image != '') {
			if (showtime.screening_user_image.substr(0,4) == 'http') {
				avatar = showtime.screening_user_image;
			} else {
				avatar = __CONFIG.assetUrl + '/uploads/hosts/'+showtime.screening_user_id+'/'+showtime.screening_user_image;
			}
		}
		return avatar;
	}
}