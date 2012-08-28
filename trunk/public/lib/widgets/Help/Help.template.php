<?php 
if (isset ($form) ) {echo $form;}
?>
<link href="/css/checkout.css" media="screen" type="text/css" rel="stylesheet">

<style>
#header{text-align:center}
h4 {
 border-bottom: 1px solid #E0E0E0;
    font-size: 24px;
    margin: 20px 0 10px;
    padding-bottom: 10px;
}
</style>

<div id="header">
	<img src="/images/checkout-logo.png" />
</div>
<div id="content">
	<div class="help-block">
<?php if(!$isSuccess):?>
	<h4>Diagnosis Page</h4>
	<p class="p">
		Please run the test below to confirm you have the necessary bandwidth and flash player installed on your machine.  If not, streaming quality may be compromised.
	</p>
	<p class="p">
		Please also note that Constellation does not currently support <em>iPad</em> or <em>iPhone</em> playback (but we're working on it!) 
	</p>

  <div class="form-row center testbutton" style="margin-bottom: 10px">
		<span class="button button-blue uppercase" onclick="test.init()">Run Test</span>
	</div>
  <?php endif;?>

	<div class="form-fieldset testResults" style="display:none" >
		<div class="form-row clearfix">
			<label for="b_address1">Testing Javascript</label>
			<div class="input jscript">
				<img src="/images/progress_new.gif" />
			</div>
		</div>
    <div class="form-row clearfix">
			<label for="b_address1">Testing Flash</label>
			<div class="input fversion">
				<img src="/images/progress_new.gif" />
			</div>
		</div>
    <div class="form-row clearfix">
			<label for="b_address1">Testing Bandwith</label>
			<div class="input bandwidth">
				<img src="/images/progress_new.gif" />
			</div>
		</div>
	<p class="final error-text"></p>
    
	</div>

<?php if(!$isSuccess):?>
	<form method="post" action="/help" onsubmit="return test.submitForm()">
		
	<div class="form-fieldset">
	  <p class="p">Please describe any problems or questions you have.  Our staff sees every submitted message and we'll respond as quickly as we can.  
		<div class="form-row clearfix"></div>
		<div class="form-row clearfix">
			<label for="b_address1">Email</label>
			<div class="input">
				<input value="" name="email" class="text-input span4 post-data">
			</div>
		</div>

		<div class="form-row clearfix">
			<label for="b_city">Message</label>
			<div class="input">
				<textarea class="text-input span4" name="message"></textarea>
		    <input type="hidden" name="sid" value="<?php echo $sid;?>" />
		    <input type="hidden" name="uid" value="<?php echo $uid;?>" /> 
		    <input type="hidden" name="tid" value="<?php echo $tid;?>" /> 
		    <input type="hidden" name="javascript" value="false" />
		    <input type="hidden" name="flash" value="0" /> 
		    <input type="hidden" name="bandwidth" value="0" />
			</div>
		</div>

	</div>

	<div class="form-row clearfix">
		<button class="button button-blue uppercase right">Submit</button>
	</div>
	</form>
  
<?php else:?>
	<h5 class="success">Your message was sent! </h5>
	<p class="p">We're reviewing your message and will get back to you as soon as possible.  </h5>
<?php endif;?>

	</div>
</div>

<div id="bwresult" style="display:none"></div>


<!-- HOME INVITE -->
<?php include_component('default', 'GrowlerAlt')?>
<!-- HOME INVITE -->
