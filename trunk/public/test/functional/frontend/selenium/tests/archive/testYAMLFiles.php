<?php
/*class testYAMLFiles extends SeleniumUtil_PageWidget
{
		var $yaml_path = "/var/www/html/sites/dev.constellation.tv/public/test/unit/selenium/auto";
		public function testYAML() {
		
		}
}*/

$cases = $argv[1];
$yaml_path = "/var/www/html/sites/dev.constellation.tv/public/test/unit/selenium/auto/yml/";
//test case is given
if (!empty($cases) and $cases != 'all') {
	$handle = fopen($yaml_path.$cases.'.yml', 'r');
	while (($data = fgetcsv($handle, 1000, ":")) !== FALSE) {
		//parse YAML and run selenium commands here
	}
	fclose($handle);
} else { //no argument or 'all', run all test cases
	if ($dh = opendir($yaml_path)) {
	     while (($file = readdir($dh)) !== false) {
	        echo "filename: $file\n";
					$handle = fopen($yaml_path.$file, 'r');
					while (($data = fgetcsv($handle, 1000, ":")) !== FALSE) {
						//parse YAML and run selenium commands here
					}
					fclose($handle);
	     }
	     closedir($dh);
	}
}

?>