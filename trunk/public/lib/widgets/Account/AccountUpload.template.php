<link rel="stylesheet" href="/css/form.css" />
<link rel="stylesheet" href="/css/upload.css" />
<div class="inner_container clearfix" id="upload-content">

	<h3 class="uppercase host_show_header">Add New Film</h3>
	<ol class="tab-list clearfix left">
		<li class="active"><span>1</span>Details</li>
		<?php if(!empty($post['film_ticket_price'])):?>
		<li><span>2</span>Settings</li>
		<li><span>3</span>Screenings</li>
		<li><span>4</span>Upload</li>
		<?php elseif(!empty($post['film_id'])):?>
		<li><span>2</span>Settings</li>
		<li class="disabled"><span>3</span>Screenings</li>
		<li class="disabled"><span>4</span>Upload</li>
		<?php else:?>
		<li class="disabled"><span>2</span>Settings</li>
		<li class="disabled"><span>3</span>Screenings</li>
		<li class="disabled"><span>4</span>Upload</li>
		<?php endif;?>
	</ol>

	<div class="bo-panelset left">

	<?php
		include_component('default','UploadFilmDetails', array(
			'post'  => $post,
			'genre'	=> $genre 
		));
		include_component('default','UploadSettings', array(
			'post'      => $post 
		));
		include_component('default','UploadScreenings', array(
			'post'      => $post
		));
		include_component('default','UploadFile', array(
			'post'      => $post
		));
	?>

	</div>
</div>
<pre>
<?php kickdump($post);?>
</pre>
<script>

	$(function(){
		new CTV.UploadController({
			domNode: $('#upload-content'),
			filmId: <?php echo !empty($post['film_id']) ? $post['film_id'] : 'null' ;?>
		});
	});



</script>


<div id="session_id" class="reqs"><?php echo session_id();?></div>
