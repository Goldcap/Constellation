<?php 
if (isset ($form) ) {echo $form;}

if (count($next) > 0) {
foreach($next as $screening) {
	echo ($screening["screening_date"])."<br />";
	echo strtotime($screening["screening_date"])."<br />";
	echo "countdown_alt.init('".$screening["screening_unique_key"]."_A','".date("Y|m|d|H|i|s",strtotime($screening["screening_date"]))."');<br />";	        
}}

if (count($carousel) > 0) {
foreach($carousel as $screening) {
	echo ($screening["screening_date"])."<br />";
	echo strtotime($screening["screening_date"])."<br />";
	echo "countdown_alt.init('".$screening["screening_unique_key"]."_P','".date("Y|m|d|H|i|s",strtotime($screening["screening_date"]))."');<br />";
}}

?>
