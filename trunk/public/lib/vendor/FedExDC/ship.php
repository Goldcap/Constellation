<?php

include("FedExDC.php");

$server = 'test'; // which fedex server to use: test or production
$request_referer = 'casinopierstore.com'; // base url for site, e.g.: 'yoursite.com'

$fx = new FedExDC("339944520","9692110");

$ship_data = array(
     75=>   'LBS'
    ,16=>   'Ma'
    ,13=>   '44 Main street'
    ,5=>    '312 stuart st'
    ,1273=> '01'
    ,1274=> '01'
    ,18=>   '6173335555'
    ,15=>   'Boston'
    ,23=>   '1'
    ,9=>    '02134'
    ,183=>  '6175556985'
    ,8=>    'MA'
    ,117=>  'US'
    ,17=>   '02116'
    ,50=>   'US'
    ,4=>    'Vermonster LLC'
    ,7=>    'Boston'
    ,1369=> '1'
    ,12=>   'Jay Powers'
    ,1333=> '1'
    ,1401=> '1.0'
    ,116 => 1
    ,68 =>  'USD'
    ,1368 => 1
    ,1369 => 1
    ,1370 => 5
);

// Ship example
$ship_Ret = $fed->ship_express($ship_data);

if ($error = $fed->getError()) {
    echo "ERROR :". $error;
} else {
    // Save the label to disk
    $fed->label('mylabel.png');

}

/* tracking example

$track_Ret = $fed->track(
array(
    29 => 790344664540,
));

*/


echo $fed->debug_str. "\n<BR>";
echo "Price ".$ship_Ret[1419];

?>
