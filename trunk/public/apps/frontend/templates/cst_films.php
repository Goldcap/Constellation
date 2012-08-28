<?php $env = (sfConfig::get("sf_environment") != "stage" && sfConfig::get("sf_environment") != "mlauprete") ? sfConfig::get("sf_environment") : "dev";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" href="/favicon.ico" />
  
  <link href="/css/styles.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="/js/jquery/jquery-1.6.4.min.js"></script> 

<!--   <link href="/css/main_styles.css" rel="stylesheet" type="text/css" />
  <link href="/css/main_styles_alt.css" rel="stylesheet" type="text/css" />
  <link href="/css/alt_popup.css" rel="stylesheet" type="text/css" /> -->

	<link href="/js/jquery/ui/themes/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" type="text/css" />
	<link href="/js/jquery/jquery.fancybox-1.3.1/fancybox/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css" />
	<link href="/css/theater_side.css" rel="stylesheet" type="text/css" />
	
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

  <body class="one_column_alt">
  
  <?php echo $sf_content; ?>
  
  </body>
    <script type="text/javascript" src="/js/constellation_alt_comp_v30.js?v=<?php echo $version;?>"></script>

	<script type="text/javascript" src="/js/jquery/jquery.cookie.js"></script>
	<script type="text/javascript" src="/js/jquery/jquery.blockUI-2.33.min.js"></script>
	<script type="text/javascript" src="/js/error_alt.js"></script>
	<script type="text/javascript" src="/js/keyevent.js"></script>
	<script type="text/javascript" src="/js/timezones.js"></script>
	
  <script type="text/javascript" src="/js/login_alt.js"></script>
  <script type="text/javascript" src="/flash/mediaplayer-5.7-licensed/jwplayer.js"></script>
  <script type="text/javascript" src="/js/modal.js"></script>
  <script type="text/javascript" src="https://platform.twitter.com/widgets.js"></script>
  <script type="text/javascript" src="/js/jquery/jeditable.js"></script>
  <script type="text/javascript" src="/js/jquery/jquery.inputlimiter.1.3.2.min.js"></script>
  <script type="text/javascript" src="/js/header.js"></script>
  
  <?php include_javascripts() ?>
  
  </html>
