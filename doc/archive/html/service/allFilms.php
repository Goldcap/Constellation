<?php
$page = isSet($_GET["page"]) ? $_GET["page"] : 1;
$sort = isSet($_GET["sort"]) ? $_GET["sort"] : 'film+start+date';
$records = isSet($_GET["records"]) ? $_GET["records"] : '12';


header('Content-Type: application/json');
$url='http://stage.constellation.tv/services/AllFilms?page='. $page .'&sort='. $sort .'&records=' . $records;
echo(get_data($url)); //dumps the content, you can manipulate as you wish to

 
/* gets the data from a URL */
 
function get_data($url)
{
$ch = curl_init();
$timeout = 5;
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
?>