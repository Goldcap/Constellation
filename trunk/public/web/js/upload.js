/**
 * this is a sample javascript that demonstrates how the Rad UPload Plus
 * applet can interact with a javascript.
 *
 * If you set the jsnotify configuration property, the applet will call 
 * the upload completed method when the file upload has been completed.
 *
 * You can call the getUploadStatus() method to determine whether the
 * upload was successfull. Possible values are 1, for success, 0 when the 
 * user has cancelled the upload and -1 when the upload failed (error).
 * 
 */

/* a usefull variable */
var upload=0;

/**
 * the response returned by the server will be passed as a parameter (s) to this
 * function. However in the case of Netscape the parameter will be empty. When using
 * netscape call the getResponse()method of the applet to access this information.
 *
 */
function uploadCompleted()
{
	upload = document.rup.getUploadStatus();
	if(upload==1)
	{
	 /*
	 <html><head><title>File Upload Handler</title></head><body><table border=1 width='99%'><tr><td colspan='2' class='th'>Directory listing</td></tr><tr><td class='th2'><nobr>File Name</nobr></td><td class='th2'><nobr>File size</nobr></td></tr><tr class='t1'><td>andreaalesetline.pdf</td><td>27900717</td></tr><tr class='t2'><td>some_test_pdf.pdf</td><td>10891</td></tr></table></html>
	 */
	  response = document.rup.getResponse();
	  alert('Woo Hoo');
	}
	else
	{
		confirm("The upload seems to have failed, please try again.");
	}
	
	return true;
}