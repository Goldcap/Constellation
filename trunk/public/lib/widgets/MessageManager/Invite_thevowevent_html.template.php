<?php
$url = "http://".sfConfig::get("app_domain")."/theater/thevowevent".$beacon;
$furl = "http://".sfConfig::get("app_domain")."/thevow".$beacon;
?>
<table width="800" height="400" border="0" cellspacing="5">
	<tr>
		<td>
			<img src="http://www.constellation.tv/images/alt1/constellation-logo-white.png" width="100">
		</td>
		<td>
			Hi from Constellation.tv.
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<strong><?php if ($sf_user -> getAttribute("user_username") != "") {echo $sf_user -> getAttribute("user_username"); } else { echo "A friend"; }?> said: </strong> <span style="color: #666"><?php echo $message;?></span>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php if ($sf_user -> getAttribute("user_username") != "") { 
				echo $sf_user -> getAttribute("user_username")." has invited you to 'An Evening of Vows'"; 
			} else { 
				echo "You are invited to 'An Evening of Vows'"; 
			} ?>
 			on <a href="http://www.constellation.tv<?php echo $beacon;?>">Constellation.tv</a>, 
    	at 8:00 PM EST on Thursday, February 9, 2012.
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<a href="<?php echo $url;?>">Click here to get your ticket</a>.
		</td>
	</tr>

	<tr>
		<td valign="top">
			<a href="<?php echo $furl;?>"><img src="http://s3.amazonaws.com/cdn.constellation.tv/prod/images/pages/thevow/channing-tatum-film-profile.png" border="0" /></a>
		</td>
		<td valign="top">
			An Evening Of Vows|The Vow star Channing Tatum will host a live interactive online event that may include your story of your vow. Channing will be live online via web-cam presenting an online movie screening of fan-submitted "Vows" along with with special clips from the movie. Tickets to this online event are free. The Vow is only in movie theaters, beginning 2/10/12.
		</td>
	</tr>
</table>

<table>
  <tr>
    <td style="color:#eee">TS-AST-<?php echo time();?>-UTC::F21</td>
  </tr>
</table>
