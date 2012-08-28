<?PHP

function reverse_strrchr($haystack, $needle)
{
	return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) +1 ) : false;
}



/**
 * Output - Deals with all html output 
 *
 */
class FNOutput{
	// handles the html output
	
	var $html;
	var $server;
	
	var $imgTypes;
	var $embedTypes;
	var $htmlTypes;
	var $phpTypes;
	var $miscTypes;
	
	var $ignoreFiles;
	var $ignoreFolders;
	
	
	function FNOutput(){
		global $flickr, $imgTypes, $embedTypes, $htmlTypes, $phpTypes, $miscTypes, $dateFormat, $ignoreFiles, $ignoreFolders, $pathToHere;
		// set up vars for class
		$this->imgTypes 	= $imgTypes;
		$this->embedTypes	= $embedTypes;
		$this->htmlTypes 	= $htmlTypes;
		$this->phpTypes 	= $phpTypes;
		$this->miscTypes 	= $miscTypes;
		$this->dateFormat	= $dateFormat;
		
		$this->ignoreFiles		= $ignoreFiles;
		$this->ignoreFolders	= $ignoreFolders;
		
		$this->pathToHere = $pathToHere;
		
		$this->flickr = $flickr;
		
		$this->html = "";
	}
	
	function sendOutput(){
		echo($this->html);
	}
	
	/**
	 * outputs html list of folders
	 *
	 * @param array $folders
	 */
	function folderList($folders){
		// do the folders
		$foldersret = "";
		for($i = 0; $i<count($folders);$i++){
			$file 	= $folders[$i][0];
			$path 	= $folders[$i][1];
			$isOpen = $folders[$i][2];
			if(substr($path,0,3) == ".//"){ $hPath = substr($path,3); }else{ $hPath = $path; };
			if(!in_array($hPath,$this->ignoreFolders)){
				if($isOpen){ $class1 = "open"; $class2 = "contents_open"; }else{ $class1 = "closed"; $class2 = "contents"; } 
				if(file_exists("$path/fComments.txt")){
					$comment = "<span class=\"folder_comment\"> - " . file_get_contents("$path/fComments.txt") . "</span>";
				}else{
					$comment = "";
				}
				$path = str_replace("//","/",$path);
				$foldersret .= "
				
				<!-- START FOLDER -->
	<li class=\"$class1\" id=\"".$this->stripID("li_$path/$file")."\">
		<!-- FOLDER TITLE -->
		<a href=\"/?dir=$path\" class=\"".$this->stripID("$path/$file")." $path\" title=\"click to open this folder\">$file</a> <span>$comment</span>
		<!-- START CONTENTS -->
		<ul class=\"$class2\" id=\"".$this->stripID("F_$path/$file")."\" >";
			if($isOpen){
				$list = new FNFileList;
				$rec_folders = $list->getFolderArray($path);
				$rec_files = $list->getFilesArray($path);
				$foldersret .= $this->folderList($rec_folders);
				$foldersret .= $this->fileList($rec_files);
			}else{
				$foldersret .= "<li>Empty</li>";	
			}
			
		$foldersret .= "</ul>
		<!-- END CONTENTS -->
	</li>
	<!-- END FOLDER -->";
				
				
			}
		}
		return $foldersret;
	}
	
	/**
	 * outputs html list of files
	 *
	 * @param array $files
	 */
	function fileList($files){
		// do the files;
		$filesret = "";
		for($i = 0; $i<count($files);$i++){
			$file = $files[$i][0];
			$path = $files[$i][1];
			$isOpen = $files[$i][2];
			if(substr($path,0,3) == ".//"){ $hPath = substr($path,3); }else{ $hPath = $path; };
			if(!in_array($hPath,$this->ignoreFiles) && $file != "fComments.txt"){
				if($isOpen){ $class1 = "file_open icon_".$this->getExt($file); $class2 = "props_open"; }else{ $class1 = "file icon_".$this->getExt($file); $class2 = "props"; } 
				// remove double slash
				$path = str_replace("//","/",$path);
				$filesret  .= "
				<!-- START FILE -->
				<li class=\"$class1\">
					<!-- FILE TITLE -->
					".$this->doFileLink($file,$path)." <a href=\"?dir=".$this->stripID("F_$path/$file")."\" class=\"properties ".$this->stripID("$path/$file")."\" title=\"show properties\"><span>ViewDetails</span></a>
					<!-- START PROPERTIES -->
					<dl class=\"$class2\" id=\"".$this->stripID("F_$path/$file")."\">
						".$this->doFileProps($file,$path) . "
					</dl>
					<!-- END PROPERTIES -->
				</li>
				<!-- END FILE -->";
			}	
		}
		return $filesret ;
	}
	
	/**
	 * returns html properties of given file
	 *
	 * @param string $file
	 * @param string $path
	 */
	function doFileProps($file,$path){
		// get the file extension for checking file type:
		$ext = substr(strrchr($file, '.'), 1);
		if(substr($path,0,3) == ".//"){
			$absolute = $this->pathToHere . substr($path,3);
		}else{
			$absolute = $this->pathToHere . $path;	
		}
			$dmz=(strstr($path,"/dmz/"))?strstr($path,"/dmz/"):0;
			
			/*-- for rebates and promotions --*/
			if($_SESSION['REBATE_ID']){
				$file_type = 'file_'.$_SESSION['REBATE_ID'];
				$dmz_type = 'dmz_'.$_SESSION['REBATE_ID'];
			} else {
				$file_type = ($_SESSION['IS_THUMB']=="thumb")?'_ThumbName':'_FileName';
				$dmz_type = ($_SESSION['IS_THUMB']=="thumb")?'_DmzThumbPath':'_DmzFilePath';
			}
			
			$ret =  "
			<dt>Last Changed:</dt>
			<dd>" . date($this->dateFormat, filectime($path)) . "</dd>
			<dt>Size:</dt>
			<dd>" . $this->returnFileSize(filesize($path)) . "</dd>
			
			<dt>Options:</dt>
            <dd>&nbsp;&#8226;&nbsp;<a href=\"$path\" title=\"download $file\" style=\"color: #DE0818;\">Download for Preview</a></dd>
			<dd>&nbsp;&#8226;&nbsp;<a href=\"javascript:window.opener.document.getElementById('".$dmz_type."').value='$dmz';window.opener.document.getElementById('".$file_type."').value='$file';self.close();\" title=\"Set as Canon Product File\" style=\"color: #DE0818;\">Set as Canon Product File</a></dd>";
			return $ret;
	}
	
	/**
	 * returns html link of given file - modified by DWN for OnIt
	 *
	 * @param string $file
	 * @param string $path
	 */
	function doFileLink($file,$path){
		/*-- sniff for dmz links, set additional js flag accordingly --*/
		$dmz=(strstr($path,"/dmz/"))?strstr($path,"/dmz/"):0;
		/*-- for rebates and promotions --*/
		if($_SESSION['REBATE_ID']){
			$file_type = 'file_'.$_SESSION['REBATE_ID'];
			$dmz_type = 'dmz_'.$_SESSION['REBATE_ID'];
		} else {
			$file_type = ($_SESSION['IS_THUMB']=="thumb")?'_ThumbName':'_FileName';
			$dmz_type = ($_SESSION['IS_THUMB']=="thumb")?'_DmzThumbPath':'_DmzFilePath';
		}
		
		return "<a href=\"javascript:window.opener.document.getElementById('".$dmz_type."').value='$dmz';window.opener.document.getElementById('".$file_type."').value='$file';self.close();\" title=\"Set as Canon Product File\">$file</a>";

	} 	
	

	function doFileLinkOLD($file,$path){
		// get the file extension for checking file type:
		$ext = substr(strrchr($file, '.'), 1);
		if(in_array(strtolower($ext),$this->imgTypes)){
			return "<a href=\"$PHP_SELF?view=$path\" title=\"view $file\">$file</a>";
		}else if(in_array(strtolower($ext),$this->embedTypes)){
			return "<a href=\"$PHP_SELF?view=$path\" title=\"view $file\">$file</a>";
		}else if(in_array(strtolower($ext),$this->phpTypes) || in_array(strtolower($ext),$this->htmlTypes)){
			return "<a href=\"$path\" title=\"open $file\">$file</a>";
		}else if(in_array(strtolower($ext),$this->miscTypes)){
			return "<a href=\"$path\" title=\"download $file\">$file</a>";
		}
	}
	


	/**
	 * returns fileNice view link of given file
	 *
	 * @param string $file
	 * @param string $path
	 */
	function doFileLinkInt($file,$path){
		// get the file extension for checking file type:
		$ext = substr(strrchr($file, '.'), 1);
		if(in_array(strtolower($ext),$this->imgTypes)){
			return "<a href=\"$PHP_SELF?view=$path\" title=\"view $file\">$file</a>";
		}else if(in_array(strtolower($ext),$this->embedTypes)){
			return "<a href=\"$PHP_SELF?view=$path\" title=\"view $file\">$file</a>";
		}else if(in_array(strtolower($ext),$this->phpTypes) || in_array(strtolower($ext),$this->htmlTypes)){
			return "<a href=\"$PHP_SELF?src=$path\" title=\"view $file source code\">$file</a>";
		}else if(in_array(strtolower($ext),$this->miscTypes)){
			return "<a href=\"$path\" title=\"download $file\">$file</a>";
		}
	}
	
	
	function searchResults($arr,$sstring){
		//echo("<pre>");
		//print_r($arr);
		//echo("</pre>");
		$output = "<div id=\"searchResults\"><h3>Search results for '$sstring':</h3>";
		//$output = "<h3>Search results for '$sstring':</h3>";
		if(count($arr) > 0){
			for($i = 0; $i < count($arr); $i++){
				$folderOK = true;
				$fileOK = false;
				if(substr($arr[$i][1],0,3) == ".//"){ $hPath = substr($arr[$i][1],3); }else{ $hPath = $arr[$i][1]; };
				// make a folders array to check that none are in the ignore list
				$folders = explode("/",$this->getFolder($hPath));
				while(count($folders) > 0){
					$tempPath = implode("/",$folders);
					//$output .= "<br>Checking: " .$tempPath;
					if(in_array($tempPath,$this->ignoreFolders)){
						//$output .= "<br />" . $tempPath . " is in ignoreFolders<br />";
						$folderOK = false;
					}
					array_pop($folders);
				}
				// check file is not private
				if(substr($arr[$i][1],0,3) == ".//"){ $hPath = substr($arr[$i][1],3); }else{ $hPath = $arr[$i][1]; };
				if(!in_array($hPath,$this->ignoreFiles) && $arr[$i][0] != "fComments.txt"){
					$fileOK = true;
				}
				if($folderOK == true && $fileOK == true){
					$output .= "<dt>" . $this->doFileLinkInt($arr[$i][0],$arr[$i][1]) . "</dt>";
					$output .= "<dd>(" . $arr[$i][1] . ")</dd>";
				}
			}
		}else{
			$output .= "Sorry, the search term '$sstring' returned no results.";	
		}
		$output .= "</div>";
		
		echo($output);
	}
	
	
	/**
	 * returns human readable file size
	 *
	 * @param int $sizeInBytes
	 * @param int $precision
	 */
	function returnFileSize($sizeInBytes,$precision=2){
		if($sizeInBytes < 1024){
			return "$sizeInBytes bytes";
		}else{
			$k = intval($sizeInBytes/1024);
			if($k < 1024){
				return $k . "k";
			}else{
				$m = number_format((($sizeInBytes/1024) / 1024),2);
				return $m . "mb";
			}
		}
	}
	
	function showSource($file){
		echo "<!-- BEGIN SOURCE OUTPUT -->";
		echo $this->get_sourcecode($file);
		echo "<!-- END SOURCE OUTPUT -->";
	}
	
	function nextAndPrev($currentPic){
		$fileNum = 0;
		$fileArray = array();
		$dir = $this->getFolder($currentPic);
		$hook = @opendir($dir);
		
		while (false !== ($file = readdir($hook))) { 
			array_push($fileArray,$file);
		}

		// order the file list the same as in the dir listing
		// ignorecasesort($fileArray);
		for($i = 0; $i < count($fileArray); $i++){
			// look for current pic
			if($dir."/".$fileArray[$i] == $currentPic){
				$currentFileNum = $i;
			}
		}
		// loop through fileArray to find previous and next images
		for($i = $currentFileNum-1; $i>=0; $i--){
			$type=$this->getExt($fileArray[$i]);
			if(in_array(strtolower($type),$this->imgTypes)){
				$prev = $dir."/".$fileArray[$i];
				break;
			}
		}
		for($i = $currentFileNum+1; $i<count($fileArray); $i++){
			$type=$this->getExt($fileArray[$i]);
			if(in_array(strtolower($type),$this->imgTypes)){
				$next = $dir."/".$fileArray[$i];
				break;
			}
		}
		for($i = 0; $i<=count($fileArray); $i++){
			$type=$this->getExt($fileArray[$i]);
			if(in_array(strtolower($type),$this->imgTypes)){
				$first = $dir."/".$fileArray[$i];
				break;
			}
		}
		return array($prev,$next,$first);
		closedir($hook);
	}
	
	
	function viewFile($file){

		$path = $_GET['view'];
		$ext = substr(strrchr($_GET['view'], '.'), 1);
		if(substr($path,0,3) == ".//"){
			$absolute = $this->pathToHere . substr($path,3);
		}else{
			$absolute = $this->pathToHere . $path;	
		}
		if(in_array(strtolower($ext),$this->imgTypes)){
			// we're showing an image
			$imageDim = @getimagesize($file);
			$preNext = $this->nextAndPrev($_GET['view']);
			echo("\n<div id=\"imgWrapper\">\n<div id=\"imgPreview\">");
			if($preNext[0] != ""){
				echo("\n<a href=\"$PHP_SELF?view=".$preNext[0]."\">Prev</a>");
			}
			if($preNext[0] != "" && $preNext[1] != ""){
				echo(" | ");
			}
			if($preNext[1] != ""){
				echo("\n<a href=\"$PHP_SELF?view=".$preNext[1]."\">Next</a>");
			}
			echo "\n<br /><br />";
			if($preNext[1] != ""){
				echo("\n<a href=\"$PHP_SELF?view=".$preNext[1]."\"><img src=\"".$_GET['view']."\" width=\"".$imageDim[0]."\" height=\"".$imageDim[1]."\" alt=\"".$_GET['view']."\" /></a>\n<br /><br />");
			}else{
				echo("\n<img src=\"".$_GET['view']."\" /><br /><br />");
			}
			echo("\n<a href=\"$PHP_SELF?view=".$this->getFolder($file)."\" title=\"close image\">close</a>");
			echo("\n</div>\n</div>\n<br /><br />
			\n<span id=\"slidelink\"><a href=\"javascript:startSlideshow('$file');\" title=\"start slideshow\">start slideshow</a></span><br /><br />
			\n<div id=\"picinfo\"><strong>".basename($_GET['view'])."</strong><br />
			Last Changed: " . date($this->dateFormat, filectime($_GET['view'])) . "<br />
			dimensions: ".$imageDim[0]."x".$imageDim[1]."<br />
			Size: " . $this->returnFileSize(filesize($_GET['view'])) . "
			\n<br />");
			if($this->flickr == true) echo("\n<a href=\"javascript:sendToFlickr('$absolute');\" title=\"send this image to Flickr\">Send to Flickr</a><br />");
			echo("\n<br />\n</div>");
		}else if(in_array($ext,$this->embedTypes)){
			// we're embedding
			$dimensiones=getimagesize($_GET['view']);
			echo("<div id=\"imgWrapper\"><div id=\"imgPreview\"><embed autoplay=\"false\" src=\"".$_GET['view']."\" ".$dimensiones[3]."></embed></div></div><br /><br />".$_GET['view']."<br />".$_GET['view']."<br />
			Last Changed: " . date($this->dateFormat, filectime($_GET['view'])) . "<br />
			Size: " . $this->returnFileSize(filesize($_GET['view'])) . "
			<br /><br />");
		}
	}
	
	function getExt($file){
		return substr(strrchr($file, '.'), 1);
	}
	
	function getFolder($filePath){
		$temp = explode("/",$filePath);
		array_pop($temp);
		return implode("/",$temp);
	}
	
	/**
	 * returns syntax hi-lited html / php
	 *
	 * @author  unknown
	 * @param string $filename
	 * 
	 */
	function get_sourcecode($filename) {
	    // Get highlighted code
	    $html_code = highlight_file($filename, TRUE);
	    // Remove the first "<code>" tag from "$html_code" (if any)
	    if (substr($html_code, 0, 6) == "<code>") {
	        $html_code = substr($html_code, 6, strlen($html_code));
	    }
	    // Replacement-map to replace deprecated "<font>" tag with "<span>"
	    $xhtml_convmap = array(
	        '<font' => '<span',
	        '</font>' => '</span>',
	        'color="' => 'style="color:'
	    );
	    // Replace "<font>" tags with "<span>" tags, to generate a valid XHTML code
	    $html_code = strtr($html_code, $xhtml_convmap);
	    ### Okay, Now we have a valid XHTML code
	    $retval = "<code>" . $html_code;    // Why? remember Bookmark #1, that I removed the tag "<code>"
	    return $retval;
	}
	
	function stripID($str){
		$pattern = '/[^\d\w]/';
		$replace = '_';
		return preg_replace($pattern, $replace, $str); 
	}
	
}

/**
 * FNFileList - Handles directory/file listings 
 *
 */
class FNFileList{
	
	var $allowHTML;
	var $allowScripts;
	var $allowImages;
	var $allowEmbed;
	var $allowMisc;
	
	var $hook;
	var $folders = array();
	var $files = array();
	var $file;
	var $path;
	
	var $openPath;
	
	var $allowedTypes = array();
	
	//default sort
	var $sortBy;
	var $sortDir;
	var $searchArray = array();
	
	/**
	 * Sets up initial variables for the FileList class
	 *
	 * @return FileList
	 */
	function FNFileList(){
		// init the file list and set up necessary variables
		global $sortBy, $sortDir, $showImg, $showEmbed, $showHtml, $showScript, $showMisc, $imgTypes, $embedTypes, $htmlTypes, $phpTypes, $miscTypes;
		// set up allowed types
		if($showImg == "show"){
			for($i=0; $i<count($imgTypes); $i++){
				array_push($this->allowedTypes,$imgTypes[$i]);	
			}	
		}
		if($showEmbed == "show"){
			for($i=0; $i<count($embedTypes); $i++){
				array_push($this->allowedTypes,$embedTypes[$i]);	
			}	
		}
		if($showHtml == "show"){
			for($i=0; $i<count($htmlTypes); $i++){
				array_push($this->allowedTypes,$htmlTypes[$i]);	
			}	
		}
		if($showScript == "show"){
			for($i=0; $i<count($phpTypes); $i++){
				array_push($this->allowedTypes,$phpTypes[$i]);	
			}	
		}
		if($showMisc == "show"){
			for($i=0; $i<count($miscTypes); $i++){
				array_push($this->allowedTypes,$miscTypes[$i]);	
			}	
		}
		// get openPath
		if(isset($_GET['src'])){
			$this->openPath = $_GET['src'];
		}else if(isset($_GET['view'])){
			$this->openPath = $_GET['view'];
		}else{
			$this->openPath = false;	
		}
		$this->sortBy = $sortBy;
		$this->sortDir = $sortDir;
	}
	
	function getFolderArray($dir){
		$folders = array();
		$hook = @opendir($dir);
		while (($file = @readdir($hook))!==false){
			if (substr($file,0,1) != "."){
				$path = $dir."/".$file;
				if(is_dir($path)){
					// get last modified time for date sorting
					$mod = filectime($path);
					if(substr($this->openPath,0,strlen($path)) == $path){
						array_push($folders,array($file,$path,true,$mod));
					}else{
						array_push($folders,array($file,$path,false,$mod));
					}
				}
			}
		}
		// sort the array before passing it on
		// make the sort by arrays
		foreach ($folders as $key => $row) {
			$namesTemp[$key]  = strtolower($row[1]);
			$timesTemp[$key] = $row[3];
		}
		// do the sort
		if($this->sortBy == "name"){
			if($this->sortDir == "ascending"){
				@array_multisort($folders, SORT_ASC, SORT_STRING, $namesTemp, SORT_ASC, SORT_STRING);
			}else{
				@array_multisort($folders, SORT_DESC, SORT_STRING, $namesTemp, SORT_DESC, SORT_STRING);
			}
		}else{
			if($this->sortDir == "ascending"){
				@array_multisort($folders, SORT_ASC, SORT_NUMERIC, $timesTemp, SORT_ASC, SORT_NUMERIC);
			}else{
				@array_multisort($folders, SORT_DESC, SORT_NUMERIC, $timesTemp, SORT_DESC, SORT_NUMERIC);
			}
		}
		return $folders;
	}
	
	function getFilesArray($dir){
		$files = array();
		$hook = @opendir($dir);
		while (($file = @readdir($hook))!==false){
			if (substr($file,0,1) != "."){
				$path = $dir."/".$file;
				if(!is_dir($path) && in_array(strtolower($this->getExt($file)),$this->allowedTypes)){
				    
					// get last modified time for date sorting
					$mod = filectime($path);
					if($path == $this->openPath){
						array_push($files,array($file,$path,true,$mod));
					}else{
						array_push($files,array($file,$path,false,$mod));	
					}
				}
			}
		}
		// sort the array before passing it on
		// make the sort by arrays
		foreach ($files as $key => $row) {
			$namesTemp[$key]  = strtolower($row[1]);
			$timesTemp[$key] = $row[3];
		}
		// do the sort
		if($this->sortBy == "name"){
			if($this->sortDir == "ascending"){
				@array_multisort($files, SORT_ASC, SORT_STRING, $namesTemp, SORT_ASC, SORT_STRING);
			}else{
				@array_multisort($files, SORT_DESC, SORT_STRING, $namesTemp, SORT_DESC, SORT_STRING);
			}
		}else{
			if($this->sortDir == "ascending"){
				@array_multisort($files, SORT_ASC, SORT_NUMERIC, $timesTemp, SORT_ASC, SORT_NUMERIC);
			}else{
				@array_multisort($files, SORT_DESC, SORT_NUMERIC, $timesTemp, SORT_DESC, SORT_NUMERIC);
			}
		}

		return $files;
	}
	
	function getFilesRecursive($files,$dir,$noreturn = false){
		if(!is_dir($dir)){
			return false;
		}
		$hook = @opendir($dir);
		while (($file = readdir($hook))!==false){
			if (substr($file,0,1) != "."){
				$path = $dir."/".$file;
				if(!is_dir($path) && in_array($this->getExt($file),$this->allowedTypes)){
					// get last modified time for date sorting
					$mod = filectime($path);
					if($path == $this->openPath){
						array_push($this->searchArray ,array($file,$path,true,$mod));
					}else{
						array_push($this->searchArray ,array($file,$path,false,$mod));	
					}
				}else{
					if(is_dir($path)){
						$this->getFilesRecursive($this->searchArray ,$path,true);
					}	
				}
			}
		}
		// sort the array before passing it on
		// make the sort by arrays
		foreach ($this->searchArray  as $key => $row) {
			$namesTemp[$key]  = strtolower($row[1]);
			$timesTemp[$key] = $row[3];
		}
		// do the sort
		if($this->sortBy == "name"){
			if($this->sortDir == "ascending"){
				@array_multisort($this->searchArray , SORT_ASC, SORT_STRING, $namesTemp, SORT_ASC, SORT_STRING);
			}else{
				@array_multisort($this->searchArray , SORT_DESC, SORT_STRING, $namesTemp, SORT_DESC, SORT_STRING);
			}
		}else{
			if($this->sortDir == "ascending"){
				@array_multisort($this->searchArray , SORT_ASC, SORT_NUMERIC, $timesTemp, SORT_ASC, SORT_NUMERIC);
			}else{
				@array_multisort($this->searchArray , SORT_DESC, SORT_NUMERIC, $timesTemp, SORT_DESC, SORT_NUMERIC);
			}
		}

		if($noreturn != true){
			return $this->searchArray ;
		}
	}
	
	
	function search($sstring){
		if(strlen($sstring)>0){
			$this->searchArray = array();
			$f = $this->getFilesRecursive($array,"./");
			$found = array();
			for($i = 0; $i< count($this->searchArray);$i++){
				if(strstr(strtolower($this->searchArray[$i][0]),strtolower($sstring))){
					array_push($found,$this->searchArray[$i]);
				}
			}
			$out = new FNOutput;
			$out->searchResults($found,$sstring);
		}
	}
	
	
	function getDirList($dir){
		$this->folders = $this->getFolderArray($dir);
		$this->files = $this->getFilesArray($dir);
		$out = new FNOutput;
		$out->html .= $out->folderList($this->folders);
		$out->html .= $out->fileList($this->files);
		$out->sendOutput();
	}
	
	function getExt($file){
		return substr(strrchr($file, '.'), 1);
	}
	function namesort($a, $b) {
       return strnatcasecmp($a["name"], $b["name"]);
	}
	
	function unset_by_val($needle,&$haystack) {
		while(($gotcha = array_search($needle,$haystack)) > -1)
		unset($haystack[$gotcha]);
	}
	
}



/**
 * UserInfo - Handles display of error and debug messages
 *
 */
class UserInfo{
	var $info;
	
	function info($str){
		$this->info .= "$str<br />";
	}
	
	function warn($str){
		$this->info .= "<span class=\"warning\">$str</span><br />";
	}
	
	function output(){
		if($this->info != ""){
			?>
			<div style="width:600px; border:0px; background-color:#ffffcc; padding:5px">
			<?PHP echo($this->info); ?>
			</div>
			<?PHP
			
		}
	}
}




?>