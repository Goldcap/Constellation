<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <?php include_title() ?>
  <link rel="shortcut icon" href="/favicon.ico" />
  
  <script type="text/javascript" src="/js/jquery/jquery-1.4.2.min.js"></script>
  
  <?php include_stylesheets() ?>
  <!--[if lte IE 6]>
  <link rel="stylesheet" href="/css/ie6.css" type="text/css">
  <![endif]-->
  <!--[if IE]>
  <style media="screen">
   @font-face{
  font-family:'helveticaneue';
  src: url('helveticaneue-webfont.eot');
  }
  </style>
  <![endif]-->
  
  </head>

  <body class="one_column blank">
  
  <?php echo $sf_content; ?>
  
  </body>
  
  <script type="text/javascript" src="/js/jquery/jquery.watermark-3.0.5.min.js"></script>
  
  <script type="text/javascript" src="/js/jquery/ui/jquery-ui-1.8.4.custom.min.js"></script>
  <script type="text/javascript" src="/js/jquery/jquery.blockUI-2.33.min.js"></script>
  <script type="text/javascript" src="/js/error.js"></script>
  <script type="text/javascript" src="/js/preuser.js"></script>
  
  <?php include_javascripts() ?>
  
  </html>
