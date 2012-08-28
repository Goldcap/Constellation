<?php

function getRandomServicePort() {
    $d = new WTVRData( null );
    $psql = "select service_ports_service_host,service_ports_service_port_base from service_ports order by RAND() limit 1;";
    $res = $d -> propelQuery($psql);
    $result = $res->fetchAll();
    return $result[0];
}

?>
