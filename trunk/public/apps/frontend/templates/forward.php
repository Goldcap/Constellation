<link href="/css/stylesheet.css" media="screen" rel="stylesheet">
<script src="/js/vendor/jquery.js"></script>
<style>
    body {
      background: #333!important;
      box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      -moz-box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      -webkit-box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      height: 100%;
    }
    .confirmation-dialog h1 {
           color: #000;
           font-size: 36px;
           line-height: 36px;
           font-family: "helveticaneuecondensed", helvetianeue, sans-serif;

    }
    .confirmation-dialog p {
      color: #555;
    }
    .confirmation-dialog p {margin: 10px 0;}
    .ajax-loader {margin: 10px auto;}
    .dialog-logo {position: relative; top: -20px; left: -5px; margin: 0 auto -20px;}
    .confirmation-dialog .hr {margin-bottom: 20px;}
    .button-block {display: block;}
</style>

<div class="dialog confirmation-dialog center">
    <img src="/images/checkout-logo.png" class="dialog-logo " />
      <div class="hr"></div>
            <h1>Thank You!</h1>
            <p>You've successfully RSVP'd to the event.</p>
            <p>If you registered via facebook or email, you will be receiving a confirmation email, as well as a reminder six hours before the event starts.</p>
            <img id="loader-theater" src="/images/bg/big-ajax-loader.gif" class="ajax-loader">
            <div id="button-theater" class="hide">
              <a href="<?php echo $sf_request->getAttribute('dest');?>" class="button button-green button-block uppercase">Enter Theater</a>
            </div>
            <p>We're preparing your theater. Please wait a couple of seconds.</p>
      <div class="hr"></div>
      <div class="button button-facebook button-block"><span class="icon-facebook"></span>Share on Facebook</div>   
      <div class="button button-twitter button-block"><span class="icon-twitter"></span>Share on Twitter</div>

</div>
<script type="text/javascript"> 
'window.location.href="<?php echo $sf_request->getAttribute('dest'); ?>";'
    setTimeout ( function(){
      $('#loader-theater').hide();
      $('#button-theater').fadeIn();
    }, 5000 );
</script>
