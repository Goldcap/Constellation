/// jQuery plugin to add support for SwfUpload
/// (c) 2008 Steven Sanderson

(function($) {
    $.fn.makeAsyncUploader = function(options) {
        return this.each(function() {
            // Put in place a new container with a unique ID
            var id = $(this).attr("id");
            var container = $("<span class='asyncUploader'/>");
            container.append($("<span id='placeHolder' class='progressContainer'/>"));
            container.append($("<div class='progressbar'> <div>&nbsp;</div> </div>"));
            container.append($("<span id='" + id + "_completedMessage' class='messageContainer'/>"));
            container.append($("<span id='" + id + "_uploading'><span class='uploadmessage'></span><span class='cancelContainer'><input type='button' value='Cancel' class='cancelButton' /></span></span>"));
            container.append($("<span id='" + id + "_swf'/>"));
            container.append($("<input type='hidden' name='" + id + "_filename'/>"));
            container.append($("<input type='hidden' name='" + id + "_guid'/>"));
            $(this).before(container).remove();
            $("#placeHolder", container).show();
            $("div.progressbar", container).hide();
            $("span[id$=_uploading]", container).hide();

            // Instantiate the uploader SWF
            var swfu;
            var width = 160, height = 22;
            
            if (options) {
                width = options.width || width;
                height = options.height || height;
            }
            
            var defaults = {
                flash_url: "swfupload.swf",
                upload_url: "/services/ImageManager",
                file_size_limit: "20 MB",
                file_types: "*.*",
                file_types_description: "All Files",

                button_image_url: "blankButton.png",
                button_width: width,
                button_height: height,
                button_placeholder_id: id + "_swf",
                button_text: '<span class="buttonText">Choose file</span>',
                button_text_style: '.buttonText { font-size: 13pt; font-family: arial; } .buttonAnother { color: #000000; font-size: 13pt; font-family: arial; }',
                button_text_left_padding: (width - 70) / 2,
                button_text_top_padding: options.button_text_top_padding || 1,

                // Called when the user chooses a new file from the file browser prompt (begins the upload)
                file_queued_handler: function(file) { swfu.startUpload(); },

                // Called when a file doesn't even begin to upload, because of some error
                file_queue_error_handler: function(file, code, msg) { alert("Sorry, your file wasn't uploaded: " + msg); },

                // Called when an error occurs during upload
                upload_error_handler: function(file, code, msg) { alert("Sorry, your file wasn't uploaded: " + msg); },

                // Called when upload is beginning (switches controls to uploading state)
                upload_start_handler: function() {
                    $("#placeHolder", container).hide();
                    swfu.setButtonDimensions(0, height);
                    $("input[name$=_filename]", container).val("");
                    $("input[name$=_guid]", container).val("");
                    $("div.progressbar div", container).css("width", "0px");
                    $("div.progressbar", container).show();
                    $("span[id$=_uploading]", container).show();
                    $("span[id$=_completedMessage]", container).html("").hide();

                    if (options.disableDuringUpload)
                        $(options.disableDuringUpload).attr("disabled", "disabled");
                },

                // Called when upload completed successfully (puts success details into hidden fields)
                upload_success_handler: function(file, response) {
                    $("input[name$=_filename]", container).val(file.name);
                    $("input[name$=_guid]", container).val(response);
                    console.log("Completed Upload");
                    
                    $("span[id$=_completedMessage]", container).html('<p class="completeMessageSuccess">Successfully Uploaded:</p><p><b>{0}</b> ({1} KB)</p>'
                                .replace("{0}", file.name)
                                .replace("{1}", Math.round(file.size / 1024))
                            );
                    swfu.setButtonText('<span class="buttonAnother">Choose Another</span>');
                },

                // Called when upload is finished (either success or failure - reverts controls to non-uploading state)
                upload_complete_handler: function() {
                    var clearup = function() {
                        $("div.progressbar", container).hide();
                        $("span[id$=_completedMessage]", container).show();
                        $("span[id$=_uploading]", container).hide();
                        swfu.setButtonDimensions(width, height);
                    };
                    if ($("input[name$=_filename]", container).val() != "") // Success
                        $("div.progressbar div", container).animate({ width: "100%" }, { duration: "fast", queue: false, complete: clearup });
                    else // Fail
                        clearup();

                    if (options.disableDuringUpload)
                        $(options.disableDuringUpload).removeAttr("disabled");
                },

                // Called periodically during upload (moves the progess bar along)
                upload_progress_handler: function(file, bytes, total) {
                    var percent = 100 * bytes / total;
                    $("div.progressbar div", container).animate({ width: percent + "%" }, { duration: 500, queue: false });
                }
            };
            swfu = new SWFUpload($.extend(defaults, options || {}));
            //swfu.setButtonText("<font face='Arial' size='13pt'>Choose File</font>");
            
            // Called when user clicks "cancel" (forces the upload to end, and eliminates progress bar immediately)
            $("span[id$=_uploading] input[type='button']", container).click(function() {
                swfu.cancelUpload(null, false);
            });

            // Give the effect of preserving state, if requested
            if (options.existingFilename || "" != "") {
                $("span[id$=_completedMessage]", container).html("Successfully Uploaded:<br /><b>{0}</b> ({1} KB)"
                                .replace("{0}", options.existingFilename)
                                .replace("{1}", options.existingFileSize ? Math.round(options.existingFileSize / 1024) : "?")
                            ).show();
                $("input[name$=_filename]", container).val(options.existingFilename);
            }
            if (options.existingGuid || "" != "")
                $("input[name$=_guid]", container).val(options.existingGuid);
        });
    }
})(jQuery);
