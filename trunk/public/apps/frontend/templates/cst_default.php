<?php $version = sfConfig::get("app_js_version");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" href="/favicon.ico" />
  <link href="/css/stylesheet.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="/js/vendor/jquery.js"></script> 
	 <script src="/js/vendor/require.js"></script>
    <script>
    __CONFIG = {
      assetUrl : '<?php echo sfConfig::get("app_clean_output") ? "//".sfConfig::get("app_image_server") : '' ?>'
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
        'CTV/View/Dialog',
        'CTV/View/Alert'
      ]
    });
    </script>
	<?php include_stylesheets() ?>
  
  <!--[if lte IE 6]>
  <link rel="stylesheet" href="/css/ie6.css" type="text/css">
  <![endif]-->
  <!--[if IE 7]>
  <link rel="stylesheet" href="/css/ie7.css" type="text/css">
  <![endif]-->
  </head>

  <body>
  
  <?php echo $sf_content; ?>
  
  <?php include_javascripts() ?>
  </body>
</html>
