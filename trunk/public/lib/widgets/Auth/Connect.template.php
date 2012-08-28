<style>
/*a.button { width: 220px;}*/
.or span {background: #fff;}
#login-form {
	width: 382px;
	margin: 40px auto;
}
.block {
	padding: 20px;
}
</style>
<div class="inner_container clearfix">
	<div class="block" id="login-form">

	    <h4>Connect Network</h4>

    	<div class="center">
    		Facebook Connected as Matthew Lauprete 
	    </div>
    	<div class="center">
    		Twitter <a href="/services/Twitter/login?dest=http://<?php echo $_SERVER["SERVER_NAME"];?><?php echo $_SERVER["REQUEST_URI"];?>" class="button button_blue button-small">Connect</a>
	    </div>


		<form id="login_form" name="login_form" action="/services/Login" method="POST" class="login_form clearfix">

			<div class="form-fieldset-full">

			<div class="form-row clearfix">
 				<button class="button button_orange uppercase right">Skip</button>
	 		</div>
			</div>

		</form>        

	</div>
</div>
