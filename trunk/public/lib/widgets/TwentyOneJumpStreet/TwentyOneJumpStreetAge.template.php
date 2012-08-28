<style>
#masterhead, #footer{
	display: none;
}
body, #content {
	background: #b71323;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}
.inner_container {
	width: 660px;
	text-align:center;
}
.age-header {
	background: black;
	padding: 30px 0;
	text-align:center;
}
.tojs-extreme { padding: 40px 0; border-bottom: solid 1px #710c16;}
.tojs-action {padding: 40px 0;  border-top: solid 1px #cf616b;}
.tojs-extreme h3 { font-size: 24px; line-height: 28px; font-weight: bold; text-shadow: 2px 2px 5px rgba(0,0,0,0.3) ;margin: 0 0 30px; }
.form-row label {color: white; font-size: 16px; text-align: right; float: none; padding-right: 20px; width: auto;}
.button-wrap {margin-top: 20px ;}
.button-wrap .button { width: 200px;}
.hide {display: none;}
h4 {
	color: #fff;
	font-size: 18px;
}
</style>


		<div class="age-header">
			<img src="/images/pages/21jumpstreet/age-header.png" />
		</div>
		<div class="inner_container">

		<div class="tojs-extreme">
			<h3>ACCESS TO EVENT IS RESTRICTED</h3>
			<img src="/images/pages/21jumpstreet/extreme.png" />
		</div>
		<div class="tojs-action">
			<div id="age-test" class="<?php echo $cookie == 0 ? '': 'hide'?>">
				<div class="form-row clear">
					<label>Birthday</label>
					<select name="month" id="age-month">
						<option value="">Month</option>
						<?php for ($i = 1 ; $i < 13 ; $i++):?>
						<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php endfor;?>
					</select>
					<select name="day" id="age-day">
						<option value="">Day</option>
						<?php for ($i = 1 ; $i < 31 ; $i++):?>
						<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php endfor;?>
					</select>
					<select name="year" id="age-year">
						<option value="">Year</option>
						<?php for ($i = 1910 ; $i < 2012 ; $i++):?>
						<option value="<?php echo $i?>"><?php echo $i?></option>
						<?php endfor;?>
					</select>			
				</div>
				<div  class="center button-wrap"><span class="button button-black uppercase" id="submit-age">Enter</span></div>
			</div>
			<div id="age-failure" class="<?php echo $cookie == -1 ? '': 'hide'?>">
				<h4>You are not old enough to attend to this event.</h4>
			</div>
	</div>
</div>
<script src="/js/jquery/jquery.cookie.min.js"></script>
<script>

jQuery(function(){
	$('#submit-age').bind('click', checkAge);
});

var checkAge = function(){
		var day = $('#age-day'),
			month = $('#age-month'),
			year = $('#age-year'),
			year = parseInt(year.val()),
			month = parseInt(month.val()),
			day = parseInt(day.val()),
			minAge = 17;

		var theirDate = new Date((year + minAge), month, day);

		var eventDate = new Date('2012', '03', '15');

		if(theirDate.toString() == 'Invalid Date'){
			alert("Please Enter a valid Birthday");
		} else if ( (eventDate.getTime() - theirDate.getTime()) < 0) {
			$('#age-test').hide();
			$('#age-failure').show();
			$.cookie('ctv_21js_age', '-1', { expires: 7, path: '/', domain: 'constellation.tv' });
			return false;
		}
		else {
			$.cookie('ctv_21js_age', '1', { expires: 7, path: '/', domain: 'constellation.tv' });
			window.location = '/21jumpstreet/unlocked';
			return true;
		}


}
</script>


