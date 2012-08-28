<?php
$cases_arr = array();
$yaml_file = '';
$head = "sleep: 0
manager: [dev.constellation.tv,127.0.0.1,20000]
group: test1
test:
";

if (($handle = fopen("cases.csv", "r")) !== FALSE) {
		
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				if ($data[0] != $yaml_file) {
					//fclose($yml_handle);					
					$yml_handle = fopen($data[0].".yml", "a");
					fwrite($yml_handle, $head);
					$yaml_file = $data[0];
				}
				$yml_str = " - {type:".$data[1].',locator: '.$data[2];
				if(!empty($data[3])) {
					$yml_str .= ', value: '.$data[3];					
				}
				$yml_str .= "}\n";
				fwrite($yml_handle, $yml_str);
    }
    fclose($handle);
}?>