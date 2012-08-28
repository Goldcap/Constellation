<?php

if(isset($_REQUEST['token'])) {
    $secrets['token'] = $_REQUEST['token'];
    $flickr = new Flickr($secrets);
} else {
    $flickr = new Flickr( $secrets );
    if(!isset($_REQUEST['frob'])) {
        $url = $flickr->getAuthUrl('delete');
        header("Location: $url");
        exit;
    } else {
        $auth = $flickr->getFrobToken($_REQUEST['frob']);
        $flickr->token = $auth['token'];
    }
}

?>
