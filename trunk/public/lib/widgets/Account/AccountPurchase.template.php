<?php //dump($purchases["data"]) ;?>
<div class="inner_container history_inner_container clearfix">
    <h1 class="content_title">Event History</h1> 
    <div class="film_block_list">
	<?php if ($purchases["meta"]["totalresults"] > 0):?>
		<?php foreach($purchases["data"] as $purchase):?>
		<?php include_component('default', 'History', array("purchase"=>$purchase))?>
		<?php endforeach;?>
	<?php else:?>
		<div class="events-no-result"><h2>You currently have not attended any events</h2></div>
	<?php endif;?>
	</div>
</div>
