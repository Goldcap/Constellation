<div class="bo-panel <?php echo $isConfirmation ? 'active': ''?>">

	<h5 class="success" >Your purchase was confirmed</h5>

	<p class="p">Thank you for your purchase. <br/> To ensure that you have appropriate bandwidth and software to enjoy a smooth streaming experience, please <a href="/help?uid=<?php echo $uid;?>"  onclick="window.open('/help?uid=<?php echo $uid;?>? ','_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=640'); return false" target="_blank">Test My System</a>.</p>
    <!-- <p class="p center"><a href="/help?uid=<?php echo $uid;?>" class="button button_orange" onclick="window.open('/help?uid=<?php echo $uid;?>? ','_blank','location=0,menubar=0,resizable=0,scrollbars=1,width=850,height=640'); return false" target="_blank" id="help">
  		 Test My System
  	</a></p> -->
<style>
.button-social { width: 180px; text-align: center;  white-space: nowrap;}
</style>
	<div class="form-row clear clearfix">
		<span class="button button-twitter button-social right" id="share-twitter">
			<span class="login-social-icon login-social-icon-twitter"></span><span class="button-social-text">Share on Twitter</span>
		</span>
		<span class="button button-facebook button-social left" id="share-facebook">
			<span class="login-social-icon login-social-icon-facebook"></span><span class="button-social-text">Share on Facebook</span>
		</span>
	</div>
	<div class="form-row button-full">
		<a href="/forward?dest=<?php echo  $film['screening_unique_key']?>" class="button button-green uppercase" id="go-to-theater" style="padding:0; width:456px"><span style=" display: block; padding: 20px; font-size: 24px; background: url(/images/bg/showtime-select.png) 100% 50% no-repeat">Go to Theater</span></a>
	</div>

</div>


<script>
$(function(){
	$('#share-twitter').bind('click', function(){
		var params =[];
			params.push('text=' + encodeURIComponent('I just got a ticket for <?php echo $film['screening_film_name']?>. Join me with me at #constellationtv'));
			params.push('url=' + encodeURIComponent('http://www.constellation/theater/' + screeningUniqueKey));
		window.open('https://twitter.com/intent/tweet?' +params.join('&'),'_share_twitter','width=450,height=250')
	});


	$('#share-facebook').bind('click', function(){
        var obj = {
          method: 'feed',
          link: 'http://www.constellation/theater/' + screeningUniqueKey,
          picture: 'http://www.constellation.tv//uploads/screeningResources/<?php echo $film['screening_film_id'];?>/logo/screening_poster<?php echo $film['screening_film_logo'];?>',
          name: '<?php echo $film['screening_film_name']?>',
          caption: 'I just got a ticket for <?php echo $film['screening_film_name']?>',
          description: 'Join me on Constellation.'
        };

		FB.ui(obj);
	});
})

</script>