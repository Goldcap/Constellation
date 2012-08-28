var CTV = CTV || {};
CTV.Uploader = function(options){
	this.uniqueId = (+(new Date))
	this.uniqueId  ++;
	this.uniqueId  = this.uniqueId.toString(36);

	this.options = $.extend({
		thumbWidth: 'auto',
        fieldName: null,
		upload_url: "/services/ImageManager",
        file_size_limit: "20 MB",
        file_types: "*.*",
        file_types_description: "All Files",
        button_placeholder_id: this.uniqueId + "_swf",
        flash_url: '/js/swfupload/swfupload.swf',
        button_image_url: '/images/upload-button-blue.png',
        button_width: '180',
        button_height: '34',
        button_text_left_padding: 0,
        button_text_top_padding: 8,
        button_text: '<span class="buttonText">Choose File</span>',
        button_text_style: '.buttonText { width: 180px; text-align:center; color: #ffffff; font-size: 14px; font-family: helvetica, arial, sans-serif; } .buttonAnother { width: 180px; text-align:center; margin-top: 6px; color: #ffffff; font-size: 14px; font-family: helvetica, arial, sans-serif;}', 

        file_queued_handler: _.bind(this.onFileQueue, this),
        file_queue_error_handler: _.bind(this.onFileQueueError, this),
        upload_error_handler: _.bind(this.onUploadError, this),
        upload_start_handler: _.bind(this.onUploadStart, this),
        upload_success_handler: _.bind(this.onUploadSuccess, this),
        upload_complete_handler: _.bind(this.onUploadComplete, this),
        upload_progress_handler: _.bind(this.onUploadProgess, this),
        debug: false,
        isFilm: true,
        dialogTitle : 'Select an Image'
    }, options);
    this.init();
}


CTV.Uploader.prototype = {
	template: '',
	init: function(){
		this.domNode = this.options.domNode;
		this.render();
	},
	render: function(){
		
		this.wrap = $('<div class="uploder-wrap"></div>').insertAfter(this.domNode);

        this.image = $('<img />').appendTo(this.wrap);
        this.image.wrap($('<span class="image-container"></span>'));
        if(this.domNode.val().length > 0){
            this.image.attr('src', this.domNode.val() )
        } else {
            this.image.parent().hide();
        }
        this.fileSelectText = $('<p class="uploader-no-file">No File Selected</p>').appendTo(this.wrap);

        this.addButton = $('<span class="button button_blue button-medium">Select File</span>')
        	.bind('click', _.bind(this.onAdd, this))
        	.appendTo(this.wrap);
		this.addButton.wrap($('<div class="button-wrap"></div>'));

        this.domNode.remove();
	},
	onAdd: function(){
		var options = this.getDialogOptions();
		Dialog.open(options);
        this.swfu = new SWFUpload(_.clone(this.options));
	},
	getDialogOptions: function(attendees, screeningData){

		this.container = $("<span class='asyncUploader'/>");
        this.container.append($("<span class='progressContainer'/>"));
        this.container.append($("<div class='progressbar'><div></div></div>"));
        this.container.append($("<span id='" + this.options.fieldName + "_completedMessage' class='messageContainer'/>"));
        this.container.append($("<span id='" + this.options.fieldName + "_uploading'><span class='uploadmessage'></span><span class='cancelContainer'><input type='button' value='Cancel' class='cancelButton' /></span></span>"));
        this.container.append($("<span id='" + this.uniqueId + "_swf'/>"));

        $(".progressContainer", this.container).show();
        $("div.progressbar", this.container).hide();
        $("span[id$=_uploading]", this.container).hide();

		options = {};
		options.klass = 'dialog-upload';
		options.title = this.options.dialogTitle;
		options.body = this.container;

		return options;
	},

	onFileQueue: function(file) { 
		this.swfu.startUpload(); 
	},
	onFileQueueError: function(file, code, msg) { 
		alert("Sorry, your file wasn't uploaded: " + msg); 
	},
	onUploadError: function(file, code, msg) { 
		alert("Sorry, your file wasn't uploaded: " + msg); 
	},
	onUploadStart: function() {
        // $("#placeHolder", this.container).hide();
        this.swfu.setButtonDimensions(0, 0);
        if(!!this.fileNameInput){

            this.fileNameInput.val("");
            this.guidInput.val("");
        }
        $("div.progressbar div", this.container).css("width", "0px");
        $("div.progressbar", this.container).show();
        $("span[id$=_uploading]", this.container).show();
        $("span[id$=_completedMessage]", this.container).html("").hide();

    },
    onUploadSuccess: function(file, response) {

        // console.log(file, response)

    	var filmId = this.options.filmId || 'temp'

        if(!this.fileNameInput){
            this.fileNameInput = $("<input type='hidden' name='" + this.options.fieldName + "_filename'/>").appendTo(this.wrap);
            this.guidInput = $("<input type='hidden' name='" + this.options.fieldName + "_guid'/>").appendTo(this.wrap);
        } 
        this.fileNameInput.val(file.name);
        this.guidInput.val(response + '.jpg');            
                
        /*$("span[id$=_completedMessage]", this.container).html(
	        '<p class="completeMessageSuccess">Successfully Uploaded:</p><p><b>{0}</b> ({1} KB)</p>'
            .replace("{0}", file.name)
            .replace("{1}", Math.round(file.size / 1024))
        );
        this.swfu.setButtonText('<span class="buttonAnother">Choose Another</span>');*/
        if(/mov|mp4|f4v|flv|avi/.test(file.name)){

            this.image.attr('src', '/images/sample-video.jpg' ).attr('width', this.options.thumbWidth).parent().fadeIn();
            this.guidInput.val(response + '.' +file.name.split('.')[1]);

            this.addButton.hide();
            this.fileSelectText.hide();            
        } else if(this.options.isFilm){
            this.image.attr('src', '/uploads/screeningResources/' + response + '.jpg' ).attr('width', this.options.thumbWidth).parent().fadeIn();;
        } else {
            console.log('f')
            this.image.attr('src', '/uploads/' + this.options.uploadFolder + '/' + response + '.jpg' ).attr('width', this.options.thumbWidth).parent().fadeIn();;
            this.addButton.hide();
            this.fileSelectText.hide();
            console.log(this.image);
        }
        Dialog.close();

    },
    onUploadComplete: function() {
        if ($("input[name$=_filename]", this.container).val() != ""){ // Success
            $("div.progressbar div", this.container).animate(
	            {
	            	width: "100%"
	            },
	            {
	            	duration: "fast",
	            	queue: false,
	            	complete: _.bind(this.clearUp,this)
		        }
		   	);
        } else {
            this.clearUp();
        }

    },
    onUploadProgess:function(file, bytes, total) {
        var percent = 100 * bytes / total;
        $("div.progressbar div", this.container).animate({width: percent + "%" }, { duration: 500, queue: false });
    },
    clearUp: function(){
        $("div.progressbar", this.container).hide();
        $("span[id$=_completedMessage]", this.container).show();
        $("span[id$=_uploading]", this.container).hide();
        // this.swfu.setButtonDimensions(this.options.button_width, this.options.button_height);
    }
}
