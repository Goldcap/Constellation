<?php

$response = array(
    'meta' => array(
        'status' => 200,
        'msg' => 'OK'
    ),
    'response' => array(
        'total_showtimes' => 10,
        'showtimes'=> array(
            array(
                'id' => 10,
                'showtime_title' => 'Wonderful Show',
                'showtime_start' => '2011|09|22|22|00|00',
                'host' => array(
                    'username' => 'Matthew',
                    'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt1/screening_no_image.png'
                ),
                'attendees' => array(
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                )
            ),
            array(
                'id' => 10,
                'showtime_title' => 'Wonderful Show',
                'showtime_start' => '2011|09|22|22|00|00',
                'host' => array(
                    'username' => 'Matthew',
                    'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt1/screening_no_image.png'
                ),
                'attendees' => array(
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                )
            ),
            array(
                'id' => 10,
                'showtime_title' => 'Wonderful Show',
                'showtime_start' => '2011|09|22|9|00|00',
                'host' => array(
                    'username' => 'Matthew',
                    'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/dev/images/alt1/screening_no_image.png'
                ),
                'attendees' => array(
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                    array(
                        'username' => 'James',
                        'avatar' => 'https://s3.amazonaws.com/cdn.constellation.tv/prod/images/icon-custom.png'
                    ),
                )
            )
        )
    )
);

header('Content-Type: application/json');
echo json_encode($response);
