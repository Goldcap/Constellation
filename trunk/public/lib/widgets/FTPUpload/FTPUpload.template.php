<?php  
if (preg_match("/(konqueror|macintosh|opera)/i",$_SERVER['HTTP_USER_AGENT'])){ ?>
  <applet name="Rad Upload Plus" archive="dndplus.jar" code="com.radinks.dnd.DNDAppletPlus"  width="290" MAYSCRIPT="yes" id="rup" height="290">
  <param name="archive" value="dndplus.jar" />
  <param name="code" value="com.radinks.dnd.DNDAppletPlus" />
  <param name="name" value="Rad Upload Plus" />
  <param name = "max_upload" value="2000000" />
  <param name = "mayscript" value="yes" />
  <param name = "message" value="Drag and drop your Movie here." />
  <param name = "url" value="ftp://<?php echo $username;?>:<?php echo $password;?>@107.22.175.18/<?php echo $film_id;?>" />
  </applet>
<?php } elseif (preg_match("/MSIE/i",$_SERVER['HTTP_USER_AGENT'])) { ?>
   <script language="javascript">
		document.writeln('<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"');
		document.writeln('      width= "290" height= "290" id="rup"');
		document.writeln('      codebase="http://java.sun.com/update/1.5.0/jinstall-1_5-windows-i586.cab#version=1,4,1">');
		document.writeln('<param name="archive" value="dndplus.jar">');
		document.writeln('<param name="code" value="com.radinks.dnd.DNDAppletPlus">');
		document.writeln('<param name="name" value="Rad Upload Plus">');
		document.writeln('<param name = "max_upload" value="2000000" />');
		document.writeln('<param name = "mayscript" value="yes" />');
		document.writeln('<param name = "message" value="Drag and drop your Movie here." />');
		document.writeln('<param name = "url" value="ftp://<?php echo $username;?>:<?php echo $password;?>@107.22.175.18/<?php echo $film_id;?>" />');
	 </script>
<?php } else {?>
  <object type="application/x-java-applet;version=1.4.1" width= "290" height= "290" id="rup" name="rup">
  <param name="archive" value="dndplus.jar" />
  <param name="code" value="com.radinks.dnd.DNDAppletPlus" />
  <param name="name" value="Rad Upload Plus" />
  <param name="max_upload" value="2000000" />
  <param name = "mayscript" value="yes" />
  <param name = "message" value="Drag and drop your Movie here." />
  <param name = "url" value="ftp://<?php echo $username;?>:<?php echo $password;?>@107.22.175.18/<?php echo $film_id;?>" />
  </object>
<?php } ?>
