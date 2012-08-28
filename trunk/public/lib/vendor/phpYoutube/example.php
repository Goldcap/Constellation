<?php
/* phpYoutube Class 1.1 example
 * This page shows how the Youtube API is used
 */

include("phpYoutube.php");

$dev_id 	= "YOUR_DEV_ID";											// request your DEV_ID at http://www.youtube.com/signup?next=my_profile_dev
$account 	= "erikdevries";											// this demo uses my Youtube account, replace this with your own account name

$phpYoutube = new phpYoutube($dev_id);

//$phpYoutube->enableCache("db", "mysql://USERNAME:PASSWORD@localhost/DBNAME");

/////////////////////////////////////////////////////////////////

echo "<pre>";

echo "<strong>Profile:</strong>\n";
print_r($phpYoutube->users_getprofile($account));						// Retrieves the public parts of a user profile.

echo "\n\n";

echo "<strong>Favorite videos:</strong>\n";
print_r($phpYoutube->users_listfavoritevideos($account));				// Lists a user's favorite videos.

echo "\n\n";

echo "<strong>Friends:</strong>\n";
print_r($phpYoutube->users_listfriends($account));						// Lists a user's friends.

echo "\n\n";

echo "<strong>Video details:</strong>\n";
print_r($phpYoutube->videos_getdetails("5qK9ZZPdPrM"));					// Displays the details for a video.

echo "\n\n";

echo "<strong>Videos with tag:</strong>\n";
print_r($phpYoutube->videos_listbytag("holland",1,5)); 					// Lists all videos that have the specified tag.

echo "\n\n";

echo "<strong>User videos:</strong>\n";
print_r($phpYoutube->videos_listbyuser($account,1,5)); 					// Lists all videos that were uploaded by the specified user

echo "\n\n";

echo "<strong>Featured videos:</strong>\n";
print_r($phpYoutube->videos_listfeatured()); 							// Lists the most recent 25 videos that have been featured on the front page of the YouTube site.

echo "\n\n";

echo "<strong>Videos with one of several tags:</strong>\n";
print_r($phpYoutube->videos_listbyrelated("tag1 tag2 tag3"));			// Lists all videos that match any of the specified tags (seperate with a space)

echo "\n\n";

echo "<strong>Videos in playlist:</strong>\n";
print_r($phpYoutube->videos_listbyplaylist("6365C62AC6AA22EE"));		// Lists all videos in the specified playlist.

echo "\n\n";

echo "<strong>Popular videos:</strong>\n";
print_r($phpYoutube->videos_listpopular("all"));						// Lists all videos in the specified time_range.

echo "\n\n";

echo "<strong>Videos with category:</strong>\n";
print_r($phpYoutube->videos_listbycategory(23)); 						// Lists videos in a particular category (23 = comedy)

echo "\n\n";

echo "<strong>Videos with category and tag:</strong>\n";
print_r($phpYoutube->videos_listbycategoryandtag(23,"blooper"));		// Lists all videos that have the specified category id and tag

echo "</pre>";

/////////////////////////////////////////////////////////////////

?>