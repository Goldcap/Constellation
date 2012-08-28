<?php
function printVideoEntry($videoEntry) 
{
  // the videoEntry object contains many helper functions
  // that access the underlying mediaGroup object
  echo 'Video: ' . $videoEntry->getVideoTitle() . "<br />";
  echo 'Video ID: ' . $videoEntry->getVideoId() . "<br />";
  echo 'Updated: ' . $videoEntry->getUpdated() . "<br />";
  echo 'Description: ' . $videoEntry->getVideoDescription() . "<br />";
  echo 'Category: ' . $videoEntry->getVideoCategory() . "<br />";
  echo 'Tags: ' . implode(", ", $videoEntry->getVideoTags()) . "<br />";
  echo 'Watch page: ' . $videoEntry->getVideoWatchPageUrl() . "<br />";
  echo 'Flash Player Url: ' . $videoEntry->getFlashPlayerUrl() . "<br />";
  echo 'Duration: ' . $videoEntry->getVideoDuration() . "<br />";
  echo 'View count: ' . $videoEntry->getVideoViewCount() . "<br />";
  echo 'Rating: ' . $videoEntry->getVideoRatingInfo() . "<br />";
  echo 'Geo Location: ' . $videoEntry->getVideoGeoLocation() . "<br />";
  echo 'Recorded on: ' . $videoEntry->getVideoRecorded() . "<br />";
  
  // see the paragraph above this function for more information on the 
  // 'mediaGroup' object. in the following code, we use the mediaGroup
  // object directly to retrieve its 'Mobile RSTP link' child
  foreach ($videoEntry->mediaGroup->content as $content) {
    if ($content->type === "video/3gpp") {
      echo 'Mobile RTSP link: ' . $content->url . "<br />";
    }
  }
  
  echo "Thumbnails:<br />";
  $videoThumbnails = $videoEntry->getVideoThumbnails();

  foreach($videoThumbnails as $videoThumbnail) {
    echo $videoThumbnail['time'] . ' - ' . $videoThumbnail['url'];
    echo ' height=' . $videoThumbnail['height'];
    echo ' width=' . $videoThumbnail['width'] . "<br />";
  }
}

function getAndPrintVideoFeed($location = Zend_Gdata_YouTube::VIDEO_URI)
{
  $yt = new Zend_Gdata_YouTube();
  // set the version to 2 to receive a version 2 feed of entries
  $yt->setMajorProtocolVersion(2);
  $videoFeed = $yt->getVideoFeed($location);
  printVideoFeed($videoFeed);
}
 
function printVideoFeed($videoFeed)
{
  $count = 1;
  foreach ($videoFeed as $videoEntry) {
    echo "Entry # " . $count . "<br />";
    printVideoEntry($videoEntry);
    echo "<br />";
    $count++;
  }
}

?>
