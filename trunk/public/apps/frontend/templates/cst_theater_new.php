<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<?php $version = sfConfig::get("app_js_version");?>
<?php $compressed = sfConfig::get("app_asset_compression");?>
<?php $env = (sfConfig::get("sf_environment") != "stage" && sfConfig::get("sf_environment") != "mlauprete") ? sfConfig::get("sf_environment") : "dev";?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" href="/favicon.ico" />
  
	<?php if ($compressed) {?>


  <link href="/css/reset.css" rel="stylesheet" type="text/css"/>
  <link href="/css/buttons.css" rel="stylesheet" type="text/css"/>
  <link href="/css/font.css" rel="stylesheet" type="text/css"/>
  <link href="/css/lightbox.css" rel="stylesheet" type="text/css"/>
  <link href="/css/form.css" rel="stylesheet" type="text/css"/>

  <link href="/css/theater-new.css" rel="stylesheet" type="text/css"/>
       <script type="text/javascript" src="/js/jquery/jquery-1.6.4.min.js"></script> 
  <?php } else { ?>


  <link href="/css/reset.css" rel="stylesheet" type="text/css"/>
  <link href="/css/buttons.css" rel="stylesheet" type="text/css"/>
  <link href="/css/font.css" rel="stylesheet" type="text/css"/>
  <link href="/css/lightbox.css" rel="stylesheet" type="text/css"/>
  <link href="/css/form.css" rel="stylesheet" type="text/css"/>

	<link href="/css/theater-new.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="/js/jquery/jquery-1.6.4.min.js"></script> 
	<?php } ?>
	
  <?php include_stylesheets() ?>
  <!--[if lte IE 6]>
  <link rel="stylesheet" href="/css/ie6.css" type="text/css">
  <![endif]-->
  <!--[if IE 7]>
  <link rel="stylesheet" href="/css/ie7.css" type="text/css">
  <![endif]-->
    <script>
  __CONFIG = {
    assetUrl : '<?php echo sfConfig::get("app_clean_output") ? '' : '' ?>'
  }
  </script>
  </head>
  <body class="theater">
  
  <?php echo $sf_content; ?>
  
  <?php if ($compressed) {?>
  <script type="text/javascript" src="/js/underscore.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery/jquery.cookie.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery.head.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery/jquery.countdown/jquery.countdown.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery.nanoscroller.min.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/swfobject.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/bootstrap-tooltip.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/modal.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/login_alt.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/error.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery.blockUi.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/vendor/jquery.nouislider.js?v=<?php echo $version;?>"></script>

  
  <script type="text/javascript" src="/js/CTV.TheaterController.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterDetails.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterChat.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterActivity.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterColor.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterColorDisplay.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterVideoPlayer.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterOoyalaPlayer.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterQa.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterHost.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterHostPublisher.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterHostSubscriber.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterCredit.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.Dialog.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.Connection.js?v=<?php echo $version;?>"></script>
 <script src="http://static.opentok.com/v0.91/js/TB.min.js" type="text/javascript"></script>
<?php } else { ?>
  <script type="text/javascript" src="/js/underscore.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery/jquery.cookie.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery.head.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery/jquery.countdown/jquery.countdown.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery.nanoscroller.min.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/swfobject.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/bootstrap-tooltip.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/login_alt.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/modal.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/error.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery.blockUi.js?v=<?php echo $version;?>"></script>

  <script type="text/javascript" src="/js/vendor/jquery.nouislider.js?v=<?php echo $version;?>"></script>
  
  <script type="text/javascript" src="/js/CTV.TheaterController.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterDetails.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterChat.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterActivity.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterColor.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterColorDisplay.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterVideoPlayer.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterOoyalaPlayer.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterQa.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterHost.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterHostPublisher.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterHostSubscriber.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.TheaterCredit.js?v=<?php echo $version;?>"></script>
<!--  <script type="text/javascript" src="/js/CTV.Dialog.js?v=<?php echo $version;?>"></script>-->
  <script type="text/javascript" src="/js/CTV.Connection.js?v=<?php echo $version;?>"></script>
  <script src="http://static.opentok.com/v0.91/js/TB.min.js" type="text/javascript"></script>

	<?php } ?>

  <?php include_javascripts() ?>

  <!--[if lt IE9]>
  <script src="/js/excanvas-modified.js" type="text/javascript"></script>
  <![endif]-->

    </body>

  </html>
