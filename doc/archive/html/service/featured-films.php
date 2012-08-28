<?php

$response = array(
    'meta' => array(
        'status' => 200,
        'msg' => 'OK'
    ),
    'response' => array(
        'page_count' => 3,
        'films'=> array(
            array(
                'id' => 10,
                'title' => 'sdffdssdfsdf Show',
                'poster' => 'http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg',
                'time' => '9:30 PM'
            ),
            array(
                'id' => 10,
                'title' => 'Wonderful Show',
                'poster' => 'http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg',
                'time' => '9:30 PM'
            ),
            array(
                'id' => 10,
                'title' => 'Wonderful Show',
                'poster' => 'http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg',
                'time' => '9:30 PM'
            ),
            array(
                'id' => 10,
                'title' => 'Wonderful Show',
                'poster' => 'http://constellation.tv/uploads/screeningResources/15/logo/small_poster4c768ad02b33e.jpg',
                'time' => '9:30 PM'
            ),
       )
    )
);

header('Content-Type: application/json');
echo json_encode($response);
