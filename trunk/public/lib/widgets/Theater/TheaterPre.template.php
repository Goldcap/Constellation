<!-- <link href="/css/styles.css" rel="stylesheet" /> -->
<?php 
$isLoggedIn = ($sf_user -> isAuthenticated()) && ((is_null($sf_user -> getAttribute("temp"))) || (! $sf_user -> getAttribute("temp")));
?>
<script src="/js/vendor/require.js"></script>
    <script>
    __CONFIG = {
      assetUrl : ''
    }
    require.config({
      baseUrl: "/js",
      paths: {
        text: 'vendor/require-text-1.0.7'

      },
      priority: [
        'vendor/jquery',
        'vendor/underscore',
        'vendor/backbone',
        'vendor/handlebar',
        'CTV/View/Dialog'
      ]
    });
    </script>
<style>
    body {
      background: #333!important;
      box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      -moz-box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      -webkit-box-shadow: inset 0 0 30px rgba(0,0,0,0.4);
      height: 100%;
    }
    .dialog { text-shadow: none;}
    .confirmation-dialog {
      background: #444;
      border: solid 1px #000;
      -webkit-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
      -moz-box-shadow: inset 0 0 5px rgba(0,0,0,0.2);
      box-shadow: inset 0 0 10px rgba(0,0,0,0.2);
    }
    .confirmation-dialog h1 {
           font-size: 36px;
           line-height: 36px;
           font-family: "helveticaneuecondensed", helvetianeue, sans-serif;
           color: #fff;

    }
    .confirmation-dialog p {
      color: #c0c0c0;
      text-shadow: none;
    }
    .confirmation-dialog p {margin: 10px 0;}
    .ajax-loader {margin: 10px auto;}
    .dialog-logo {position: relative; top: -20px; left: -5px; margin: 0 auto -20px;}
    .confirmation-dialog .hr {margin-bottom: 20px;}
    .button-block {display: block;}
    .hr {border-bottom: solid 1px #111; margin: 20px 0;}
    .link {cursor: pointer;}
</style>

<div class="dialog confirmation-dialog center">
    <img src="/images/checkout-logo.png" class="dialog-logo " />
      <div class="hr" style="margin-top:0"></div>
            <h1>You need a ticket to access the theater!</h1>
    <?php if (!$isLoggedIn) :?>
      <div class="hr"></div>
        <p>If you already have a ticket, <span class="link" onclick="$(window).trigger('auth:login')">Login &rarr;<span></p>
      <?php endif;?>
      <div class="hr"></div>
      <a href="/boxoffice/screening/<?php echo $film["screening_unique_key"];?>" class="button button-green button-block">RSVP TO EVENT</a>  
      <div class="hr"></div>
            <p><a href="/event/<?php echo $film["screening_unique_key"];?>" class="link"> &larr; Go to event page  <span></p>

</div>
<script type="text/javascript"> 
// 'window.location.href="<?php echo $sf_request->getAttribute('dest'); ?>";'
//     setTimeout ( function(){
//       $('#loader-theater').hide();
//       $('#button-theater').fadeIn();
//     }, 5000 );
</script>
<?php if (!$isLoggedIn) :?>
<script>
require(['CTV/Controller/User'], function(User){
    new User({
        isLoggedIn: <?php echo $isLoggedIn ? 'true':'false';?>,
        hasLogginError: false,
        hasSignupError: false
    });
});
</script>
<?php endif;?>