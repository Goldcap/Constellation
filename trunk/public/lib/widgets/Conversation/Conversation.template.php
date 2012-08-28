<?php 
if (isset ($form) ) {echo $form;}
$isLoggedIn = false;
if (($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")))){
	$isLoggedIn = true;
}
$imageUrl = '/images/icon-custom.png';
if ($sf_user -> getAttribute('user_image') && (left($sf_user -> getAttribute('user_image'),4) == "http")) {
	$imageUrl = $sf_user -> getAttribute('user_image');
} elseif($sf_user -> getAttribute('user_image')) {
	$imageUrl = '/uploads/hosts/'. $sf_user -> getAttribute('user_id') .'/'. $sf_user -> getAttribute('user_image');
} 
?>
<div class="block">
	<h4>Conversation</h4>
	<div class="comment-box clearfix" id="comment-box">
	<?php if ($isLoggedIn) :?>
			<img src="<?php echo $imageUrl; ?>" width="50" height="50" class="comment-avatar comment-box-image"/>
		<textarea class="comment-box-field" placeholder="Add your voice to the conversation!"></textarea>
		<span class="button button_orange_medium uppercase">post</span>
	<?php else: ?>
		<a href="javascript:void(0)" onclick="login.showpopup()">You must be logged in to post.</a>
	<?php endif;?>
	</div>
	<ul class="comment-list comment-loading " id="comment-list"></ul>
	<div class="comment-pagination" id="pagination">
		<ul></ul>
	</div>
	</div>
	
<script> 
jQuery(function(){
    _.templateSettings = {
	  interpolate : /\{\{(.+?)\}\}/g
	};
	new CTV.Comments({
		list: $('#comment-list'),
		filmId: <?php echo $film['film_id']?>,
		rpp: 5,
		isLoggedIn: <?php echo $isLoggedIn ? 'true': 'false';?>,
		fbShareImage : 'http://www.constellation.tv/uploads/screeningResources/56/logo/small_poster<?php echo $film['film_logo']?>',
		fbShareCaptions: 'Constellation.tv - your online social movie theater',
		tShareCaption: ''
	});

});
</script>
