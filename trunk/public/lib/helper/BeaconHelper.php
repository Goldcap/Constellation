<?php
function beacon( $id, $action=false, $value=false ) {
  $beacon = new Beacon_PageWidget( $id, $action, $value );
  $beacon -> parse();
}
?>
