<script src="http://dev.constellation.tv/js/upload.js"></script>
<div class="bo-panel clear">

	<div class="form-fieldset">
		<h6>Browser Upload</h6>
		<div class="form-row clearfix">

		<p class="p">To upload a file, simply drag and drop the file from your computer into the file upload window below. Alternatively, you can use the "Browse" button to select a file from your computer file system.</p>

			<label for="film_name ">Upload</label>
			<div class="input">
			<?php  
		      $user_agent = $_SERVER['HTTP_USER_AGENT'];
		      // echo $user_agent;
		      if (preg_match("/(konqueror|macintosh|opera)/i",$user_agent)){ ?>
		        <applet name="Rad Upload Plus" archive="dndplus.jar" code="com.radinks.dnd.DNDAppletPlus"  width="290" MAYSCRIPT="yes" id="rup" height="290">
		        <param name="archive" value="dndplus.jar" />
		        <param name="code" value="com.radinks.dnd.DNDAppletPlus" />
		        <param name="name" value="Rad Upload Plus" />
		        <param name="max_upload" value="2000000" />
		        <param name = "mayscript" value="yes" />
		        <param name = "message" value="Drag and drop your .mov or .mp4 Files here." />
		        <param name = "url" value="ftp://testuser:secret@107.22.175.18" />
		        </applet>
		      <?php } elseif (preg_match("/MSIE/",$user_agent)) { ?>
		         <script src="/js/embed.js" type="text/javascript"></script>
		         <script>IELoader();</script>
		      <?php } else {?>
		        <object type="application/x-java-applet;version=1.4.1" width= "290" height= "290" id="rup" name="rup">
		        <param name="archive" value="dndplus.jar" />
		        <param name="code" value="com.radinks.dnd.DNDAppletPlus" />
		        <param name="name" value="Rad Upload Plus" />
		        <param name="max_upload" value="2000000" />
		        <param name = "mayscript" value="yes" />
		        <param name = "message" value="Drag and drop your PDF Files here." />
		        <param name = "url" value="ftp://testuser:secret@107.22.175.18" />
		        </object>
		      <?php } ?>


			</div>
		</div>
		<div class="form-row clearfix">
			<div class="or"><span class="uppercase">Or</span></div>
		</div>
		<h6>FTP CLIENT</h6>
		<p class="p">You can also upload your film using an FTP client. Use the setting below in your favorite client. Once the film is finish uploading, we will automatically process your film.</p>
		<div class="form-row clearfix">
			<label for="film_synopsis ">FTP</label>
			<div class="input input-static">ftp://upload.constellation.tv</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_synopsis ">Username</label>
			<div class="input input-static">mElsfj3</div>
		</div>
		<div class="form-row clearfix">
			<label for="film_synopsis ">Password</label>
			<div class="input input-static">JML-Lmvc+xlD</div>
		</div>
	</div>

</div>  
