<?php 
if (isset ($form) ) {echo $form;}
?>
<ul class="home-tab clearfix">
	<li class="active">Latest</li>
	<li class="home-tab-happening">What's Happening</li>
	<li class="home-tab-hit">How it Works</li>
</ul>

<script>
$(document).ready(function(){
	var tabs = $('.home-tab li'),
		panels = $('.ctv-panel');

	tabs.bind('click', function(){
		tabs.removeClass('active');
		panels.removeClass('active');
		$(this).addClass('active');
		var index = tabs.index($(this))
		panels.eq(index).addClass('active')
	})
});
</script>