<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<?php $version = sfConfig::get("app_js_version");?>
<?php $compressed = sfConfig::get("app_asset_compression");?>
<?php $env = (sfConfig::get("sf_environment") != "stage") ? sfConfig::get("sf_environment") : "dev";?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" href="/favicon.ico" />
  
	<?php if ($compressed) {?>
		<?php if ($_SERVER["SERVER_PORT"] == "443") { ?>
      <link href="https://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/css/constellation_ssl_gz.css?v=<?php echo $version;?>" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="https://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/js/jquery/jquery-1.6.4.min_gz.js"></script> 
	  <?php } else { ?>
      <link href="http://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/css/constellation_gz.css?v=<?php echo $version;?>" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="http://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/js/jquery/jquery-1.6.4.min_gz.js"></script> 
    <?php } ?>
    <link href="/css/font.css" rel="stylesheet" type="text/css" />
  <?php } else { ?>

		<link href="/css/styles.css" rel="stylesheet" type="text/css" />
    <link href="/css/conversation.css" rel="stylesheet" type="text/css" />
	  <link href="/js/jquery/ui/themes/smoothness/jquery-ui-1.8.1.custom.css" rel="stylesheet" type="text/css" />
		<link href="/js/jquery/jquery.fancybox-1.3.1/fancybox/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css" />
		<link href="/css/theater_side.css" rel="stylesheet" type="text/css" />
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

  <body class="one_column_alt">
  
  <?php echo $sf_content; ?>
  
  </body>
  
  <?php if ($compressed) {?>
    <?php if ($_SERVER["SERVER_PORT"] == "443") { ?>
      <script type="text/javascript" src="https://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/js/constellation_comp_gz.js?v=<?php echo $version;?>"></script>
      <script type="text/javascript" src="https://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/js/constellation_alt_comp_gz.js?v=<?php echo $version;?>"></script>
    <?php } else { ?>
      <script type="text/javascript" src="http://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/js/constellation_comp_gz.js?v=<?php echo $version;?>"></script>
      <script type="text/javascript" src="http://s3.amazonaws.com/cdn.constellation.tv/<?php echo $env;?>/js/constellation_alt_comp_gz.js?v=<?php echo $version;?>"></script>
    <?php } ?>
  <?php } else { ?>
  <script type="text/javascript" src="/js/underscore.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.ShowtimeDetail.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.Comments.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/CTV.Dialog.js?v=<?php echo $version;?>"></script>

  <script type="text/javascript" src="/js/swfobject.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/constellation_alt_comp_v30.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/purchase_alt.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery/jquery.dateFormat-1.0.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/flash/mediaplayer-5.7-licensed/jwplayer.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/modal.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/login_alt.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/trailer_alt.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/countdown_alt.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/allfilms.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="https://platform.twitter.com/widgets.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery/jeditable.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/jquery/jquery.inputlimiter.1.3.2.min.js?v=<?php echo $version;?>"></script>
  <script type="text/javascript" src="/js/header.js?v=<?php echo $version;?>"></script>    
  <script type="text/javascript" src="/js/bandwidth.js?v=<?php echo $version;?>"></script>
  <?php } ?>
  <?php include_javascripts() ?>
  
  </html>
