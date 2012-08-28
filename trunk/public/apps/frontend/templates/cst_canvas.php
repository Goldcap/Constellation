<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<?php $version = sfConfig::get("app_js_version");?>
<?php $compressed = sfConfig::get("app_asset_compression");?>
<?php $env = (sfConfig::get("sf_environment") != "stage") ? sfConfig::get("sf_environment") : "dev";?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" href="/favicon.ico" />
  <link href="/css/embed.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="/js/jquery/jquery-1.6.4.min.js"></script> 
  <script type="text/javascript" src="/js/underscore.js"></script> 

  <?php include_stylesheets() ?>
  <body id="canvas-page">
  <?php echo $sf_content; ?>
  <div id="fb-root"></div>
  <script src="http://connect.facebook.net/en_US/all.js"></script>
  <script>
   FB.init({
    appId      : '185162594831374', // App ID
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    oauth      : true, // enable OAuth 2.0
    xfbml      : true  // parse XFBML
  });
  window.fbAsyncInit = function() {
    FB.Canvas.setAutoGrow();
  }
  </script>
  </body>
</html>