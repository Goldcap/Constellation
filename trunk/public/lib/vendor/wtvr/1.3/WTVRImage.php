<?php
  
class WTVRImage extends WTVRFile {
    
    var $magickwand;
    var $magickwand_template;
    
    var $sizearray;
    var $source_size;
    var $dest_size;
    var $overlay;
    var $caption;
    var $output_location;    
    
    function __construct( $fieldname = "wtvr_image",$styro=true ) {
      
      parent::__construct( $fieldname, $styro );
      $this -> destination_name = "wtvr_image_source";
      $this -> initSizes();
      $this -> magickwand = new Imagick();
      $this -> magickwand_template = new Imagick();
       
    }
    
    function initSizes() {
      
      $this -> sizes = new DOMDocument();
      $this -> sizes -> load(sfConfig::get('sf_config_dir')."/wtvr_image_sizes.xml");
      $nodes = $this -> sizes -> getElementsByTagname("imagesize");
      $this -> sizearray = array();
     
      foreach ($nodes as $node) {
        $this -> sizearray[$node -> getAttribute("name")] = array($node -> getAttribute("width"),$node -> getAttribute("height"),$node -> getAttribute("scale"));
      }
      
    }
    
    function rotate( $angle, $name=false, $type="gif" ) {
      
      try {
        
        if ($this -> destination_fullname == null) {
          die("File wasn't 'moved' yet, source not available");
        }
        
        if (! $name) {
          $name = $this -> destination_name;
        }
        //dump($this -> destination_fullname);
        //dump($this -> destination_dir."/".$name.".".$type);
        $this -> doRotate( $angle, $this -> destination_fullname, $this -> destination_dir."/".$name.".".$type );
        
        
      } catch(Exception $e) {
      
        echo $e->getMessage();
      
      } 
    }
    
    function convert( $name=false, $type="jpg" ) {
      
      try {
        
        if ($this -> destination_fullname == null) {
          die("File wasn't 'moved' yet, source not available");
        }
        
        /*** read the image ***/
        $this -> magickwand -> readImage( $this -> destination_fullname );
        
        $this -> doWrite( $this -> destination_dir, $name, $type);
        
        $this -> magickwand_template -> clear();
        
      } catch(Exception $e) {
      
        echo $e->getMessage();
      
      } 
    }
    
    /*
    This function will scale the image based on the aspect ratio and largest
    available absolute image size, either height or width, then crop to the 
    sizes allowed in "thesize". This results in images of a specific size, 
    centered and cropped.
    **
    Example: If "thesize" is 800x800, and the image size is 1000 x 600,
    the image will be scaled 80% ( 800 / 1000 ) to 800 x 480, then padded 
    by 320 (160 each), resulting in 800 x 800,
    */
    function resize( $thesize, $name=false, $type="gif", $border=false, $bgcolor="white" ) {
      
      try {
        
        if ($this -> destination_fullname == null) {
          die("File wasn't 'moved' yet, source not available");
        }
        
        /*** read the image ***/
        $this -> magickwand -> readImage( $this -> destination_fullname );
        
        if (! $name) {
          $name = $this -> destination_name;
        }
        
        $this -> doResize( $thesize, $bgcolor );
        
        if ($border) {
          //$this -> doBorder( $border[0],$border[1] );
        }
       
				//putLog("RESIZING IMAGE: " . "::" . $this -> destination_dir . $name . "::" . $type);
        $this -> doWrite( $this -> destination_dir, $name, $type);
        
        $this -> magickwand_template -> clear();
        
      } catch(Exception $e) {
      
        echo $e->getMessage();
      
      } 
    }
    
    /*
    This function simply executes the "resize" method above
    */
    private function doResize( $thesize, $bgcolor="white" ) {
        
        $w = $this -> sizearray[$thesize][0];
        $h = $this -> sizearray[$thesize][1];
        $thisheight = $this -> magickwand -> getImageHeight();
        $thiswidth = $this -> magickwand ->  getImageWidth();
        $left=0;
        $top=0;
        
        /*
        Scale based on the largest available pixel size
        */
        //Landscape
        if ($thisheight < $thiswidth) {
          $scale = $w / $thiswidth * 100;
  		  //Portrait
        } else {
  		    $scale = $h / $thisheight * 100;
  		  }
  		  
  		  $newsize = $this -> doScale( $thesize, $scale );
  		  $thisheight = $newsize[0];
  		  $thiswidth = $newsize[1];
  		  
        //dump($newsize);
  		  //Image is too tall, crop vertically
        if (floor($thisheight) > $h) {
          $thew = $thiswidth;
  				$theh = $h;
  				//New height minus total height, divided by two
  				$top = ($thisheight - $h) / 2;
  				$this -> magickwand -> cropImage( $thew, $theh, $left, $top );
  		  //Image is too wide, crop horizontally
        } elseif (floor($thiswidth) > $w){
  		    $thew = $w;
  				$theh = $thisheight;
          //New width minus total width, divided by two			
  				$left = ($thiswidth - $w) / 2;
  				$this -> magickwand -> cropImage( $thew, $theh, $left, $top );
  			//Image is too short, pad vertially
  		  } elseif (floor($thisheight) < $h) {
  		    $thew = $w;
  		    $theh = $h;
          $top = ($h - $thisheight) / 2;
          $this -> padImage( $theh,$thew,$left,$top,$bgcolor );
        //Image is too skinny, pad horizontally
  		  } elseif (floor($thiswidth) < $w) {
  		    //if ($thesize == "user_small_icon") kickdump("TOO NARROW");
          $thew = $w;
  		    $theh = $h;
          $left = ($w - $thiswidth) / 2;
          $this -> padImage( $theh,$thew,$left,$top,$bgcolor );
        } else {
					$thew = $w;
  		    $theh = $h;
          $left = 0;
          $top = 0;
          $this -> padImage( $theh,$thew,$left,$top,$bgcolor );
				}
        
        $this -> dest_size["height"] = $h;
  		  $this -> dest_size["width"] = $w;
  		 
    }
    
    /*
    This function will scale the image based on the minimum
    of the sizes allowed in "thesize". No cropping takes place, 
    resulting in variable width and height within a bounding size.
    **
    Example, if "thesize" is 800x800, and the image size is 1000 x 600
    The image will be scaled 80%, ( 800 / 1000 ), resulting in 800 x 480
    */
    function limit( $thesize, $name=false, $type="gif", $border=false, $pad=false, $safety=false ) {
      
      try {
        
        if ($this -> destination_fullname == null) {
          $this -> destination_fullname = $this -> destination_dir."/".$this -> destination_name .".".$this -> source_type;
          //die("File wasn't 'moved' yet, source not available");
        }
        
        /*** read the image ***/
        $this -> magickwand -> readImage( $this -> destination_fullname );
        
        if (! $name) {
          $name = $this -> destination_name;
        }
        
        $this -> doLimit( $thesize, $safety );
        
        if ($pad) {
          $this -> padImage( $this -> sizearray[$thesize][1],$this -> sizearray[$thesize][0] );
        }
        
        if ($border) {
          $this -> doBorder( $border[0],$border[1] );
        }
        
        $this -> doWrite( $this -> destination_dir, $name, $type);
        
      } catch(Exception $e) {
      
        echo $e->getMessage();
        
      } 
    }
    
    /*
    This function simply executes the "limit" method above
    */
    private function doLimit ( $thesize, $safety=false ) {
        
        $w = $this -> sizearray[$thesize][0];
        $h = $this -> sizearray[$thesize][1];
        
	      $thisheight = $this -> magickwand -> getImageHeight();
        $thiswidth = $this -> magickwand ->  getImageWidth();
        $wscale = $w / $thiswidth * 100;
        $hscale = $h / $thisheight * 100;
        
        //Image is Wide
        if ($wscale < $hscale) {
		  		$scale = $wscale;
  		  } else {
  		  	$scale = $hscale;
  		  }

  		   if ($safety) {
          $scale = $scale + $safety;
         }
  		  //dump($thisheight);
  		  //dump($thiswidth);
  		  $asize = $this -> doScale( $thesize, $scale );
  		  
        $this -> dest_size["height"] = $asize[0];
  		  $this -> dest_size["width"] = $asize[1];
    }
    
    /*
    This function will scale the image based on the minimum
    of the sizes allowed in "thesize". No cropping takes place, 
    resulting in variable width and height within a bounding size.
    **
    Example, if "thesize" is 800x800, and the image size is 1000 x 600
    The image will be scaled 80%, ( 800 / 1000 ), resulting in 800 x 480
    */
    function set( $thesize, $name=false, $type="gif", $border=false, $pad=false, $safety=false ) {
      
      try {
        
        if ($this -> destination_fullname == null) {
          $this -> destination_fullname = $this -> destination_dir."/".$this -> destination_name .".".$this -> source_type;
          //die("File wasn't 'moved' yet, source not available");
        }
        
        /*** read the image ***/
        $this -> magickwand -> readImage( $this -> destination_fullname );
        
        if (! $name) {
          $name = $this -> destination_name;
        }
        
        $this -> doSet( $thesize, $safety );
        
        if ($pad) {
          $this -> padImage( $this -> sizearray[$thesize][1],$this -> sizearray[$thesize][0] );
        }
        
        if ($border) {
          $this -> doBorder( $border[0],$border[1] );
        }
        
        $this -> doWrite( $this -> destination_dir, $name, $type);
        
      } catch(Exception $e) {
      
        echo $e->getMessage();
        
      } 
    }
    
    /*
    This function simply executes the "limit" method above
    */
    private function doSet ( $thesize, $safety=false ) {
        
        $w = $this -> sizearray[$thesize][0];
        $h = $this -> sizearray[$thesize][1];
        
	      $thisheight = $this -> magickwand -> getImageHeight();
        $thiswidth = $this -> magickwand ->  getImageWidth();
        $wscale = $w / $thiswidth * 100;
        $hscale = $h / $thisheight * 100;
        
        //Image is Wide
        if ($hscale == 0) {
		  		$scale = $wscale;
  		  } else {
  		  	$scale = $hscale;
  		  }

  		   if ($safety) {
          $scale = $scale + $safety;
         }
  		  //dump($thisheight);
  		  //dump($thiswidth);
  		  $asize = $this -> doScale( $thesize, $scale );
  		  
        $this -> dest_size["height"] = $asize[0];
  		  $this -> dest_size["width"] = $asize[1];
    }
    
    /*
    This function will scale the image based on the smaller
    of the sizes allowed in "thesize". No cropping takes place, 
    resulting in variable width and height within a bounding size.
    **
    Example, if "thesize" is 800x600, and the image size is 1000 x 600
    The image will not be scaled, ( 1000 / 600 ), resulting in 1000 x 600
    */
    function minimum( $thesize, $name=false, $type="gif", $border=false, $pad=false, $safety=false ) {
      
      try {
        
        if ($this -> destination_fullname == null) {
          $this -> destination_fullname = $this -> destination_dir."/".$this -> destination_name .".".$this -> source_type;
          //die("File wasn't 'moved' yet, source not available");
        }
        
        /*** read the image ***/
        $this -> magickwand -> readImage( $this -> destination_fullname );
        
        if (! $name) {
          $name = $this -> destination_name;
        }
        
        $this -> doMinimum( $thesize, $safety );
        
        if ($border) {
          $this -> doBorder( $border[0],$border[1] );
        }
        
        $this -> doWrite( $this -> destination_dir, $name, $type);
        
      } catch(Exception $e) {
      
        echo $e->getMessage();
      
      } 
    }
    
    /*
    This function simply executes the "minimum" method above
    */
    private function doMinimum ( $thesize, $safety=false ) {
        
        $w = $this -> sizearray[$thesize][0];
        $h = $this -> sizearray[$thesize][1];
        
        $thisheight = $this -> magickwand -> getImageHeight();
        $thiswidth = $this -> magickwand ->  getImageWidth();
        
        //Image is Wide
        if ($h==0) {
		  		$scale = $w / $thiswidth * 100;
  		  } else {
  		  	$scale = $h / $thisheight * 100;
  		  }
  		  
		    if ($safety) {
          $scale = $scale + $safety;
        }
  		  //dump($thisheight);
  		  //dump($thiswidth);
  		  $asize = $this -> doScale( $thesize, $scale );
  		  
        $this -> dest_size["height"] = $asize[0];
  		  $this -> dest_size["width"] = $asize[1];
    }
    
    /*
    This function rotates an image by any specific angle, padding with white?.
    */
   function doRotate( $angle, $source, $image ) {
      /*** create  thumbnail ***/
      //dump("/usr/bin/convert ".$source." -rotate ".$angle." ".$image);
      exec("/usr/bin/convert ".$source." -rotate ".$angle." ".$image);
   }
    
    /*
    This function will scale the image based on the scale parameter in the
    image size array. You may override this using the "altscale" parameter.
    **
    Example: If "thesize" is 800x800, and the image size is 1000 x 600,
    the image will be scaled 80% ( 800 / 1000 ) to 800 x 480.
    */
   function scale( $thesize, $name=false, $type="gif", $altscale=false, $origin=false, $border=false ) {
      
        if ($this -> destination_fullname == null) {
          die("File wasn't 'moved' yet, source not available");
        }
        
        /*** read the image ***/
        $this -> magickwand -> readImage( $this -> destination_fullname );
        
        if (! $name) {
          $name = $this -> destination_name;
        }
        
        $size = $this -> doScale ( $thesize, $altscale );
        
        if ($origin) {
          $this -> doCrop($thesize, $origin );
        }
        
        if ($border) {
          $this -> doBorder( $border[0],$border[1] );
        }
        
        $this -> doWrite( $this -> destination_dir, $name, $type);
        
    }
    
    /*
    This function simply executes the "scale" method above
    */
    private function doScale( $thesize, $altscale=false ) {
        
        $scale = ($altscale) ? $altscale : $this -> sizearray[$thesize][2];
        $thisheight = $this -> magickwand -> getImageHeight();
        $thiswidth = $this -> magickwand -> getImageWidth();
        
      	$thew = floor($thiswidth * ($scale / 100));
				$theh = floor($thisheight * ($scale / 100));
				
        $this -> magickwand -> resizeImage( $thew, $theh, Imagick::FILTER_LANCZOS, 1 );
  		  
  		  $thisheight = $this -> magickwand -> getImageHeight();
  		  $thiswidth = $this -> magickwand -> getImageWidth();
  		  
  		  return array($thisheight,$thiswidth);
    }
    
    /*
    Unsure where this needs to be used, deprecate?
    */
    function doCrop( $thesize, $origin=null ) {
      $top = 0;
      $left = 0;
      
      if ($origin) {
        $this -> magickwand -> cropImage( $this -> sizearray[$thesize][0],$this -> sizearray[$thesize][1],$origin[0],$origin[1]);
        $image = new Imagick();
        $image->newImage($this -> sizearray[$thesize][0],$this -> sizearray[$thesize][1], new ImagickPixel('white'));
        if ($origin[0] < 0) {
          $offx = abs($origin[0]);
        } else {
          $offx = 0;
        }
        if ($origin[1] < 0) {
          $offy = abs($origin[1]);
        } else {
          $offy = 0;
        }
        
        $image -> compositeImage($this -> magickwand, Imagick::COMPOSITE_DEFAULT, $offx, $offy);
        $this -> magickwand = $image;
     } else {
        /*** create  thumbnail ***/
        $this -> magickwand -> resizeImage( $this -> sizearray[$thesize][0], $this -> sizearray[$thesize][1], Imagick::FILTER_CUBIC, 0 );
        $this -> magickwand -> setImagePage( $this -> sizearray[$thesize][0],$this -> sizearray[$thesize][1],0,0);
      }
    }
    
    function loadOverlay( $image ) {
      $this -> overlay = new Imagick();
      $this -> overlay -> readImage ( $image );
    }
    
    /*
    This function will add another image to the currently loaded image
    */
    function doComposite( $overlay=false,$left=0, $top=0 ) {
      if ($overlay) {
        $this -> loadOverlay( $overlay );
      }
      $this -> magickwand -> compositeImage( $this -> overlay, Imagick::COMPOSITE_OVER, $left, $top );
    }
    
    function createImage ( $width, $height, $color="white", $type = "jpg" ) {
      $this -> magickwand ->clear();
      $this -> magickwand ->newImage( $width,$height, new ImagickPixel($color),$type);
    }
       
    /*
    Utility for setting canvas size
    */
    function setCanvas( $height, $width, $left=0, $top=0 ) {
      /*** read the image ***/
      $this -> magickwand -> setImagePage( $width,$height,$left,$top);
    }
    
    function padImage( $height, $width, $left=0, $top=0, $color="white" ) {
      $padded = new Imagick();
      $padded ->newImage( $width,$height, new ImagickPixel($color),"jpg");
      
      $thisheight = $this -> magickwand -> getImageHeight();
      $thiswidth = $this -> magickwand ->  getImageWidth();
      
      //dump($newsize);
		  //Image is too skinny, pad horizontally
		  if (floor($thisheight) == $thiswidth) {
		  	return;
      } else if (floor($thisheight) > $thiswidth) {
        //New height minus total height, divided by two
				$left = ($width - $thiswidth) / 2;
		  //Image is too short, pad vertially
      } elseif (floor($thiswidth) > $thisheight){
        //New width minus total width, divided by two			
				$top = ($height - $thisheight) / 2;
			
		  }
		  
      $padded -> compositeImage( $this -> magickwand, Imagick::COMPOSITE_OVER, $left, $top );
      //$padded -> writeImage( "/var/www/html/sites/share/prod/temp/test.jpg" );
      
      $this -> magickwand = $padded;
    }
    /*
    Utility for reading the image
    */
    function doRead( $image ) {
      /*** read the image ***/
      $this -> magickwand -> readImage( $image );
    }
    
    /*
    Utility for reading the image
    If we've used the "setFile" method
    */
    function loadImage() {
      /*** read the image ***/
      $this -> magickwand -> readImage( $this -> thefile["tmp_name"] );
      
      $this -> dest_size["height"] = $this -> magickwand -> getImageHeight();
  		$this -> dest_size["width"] = $this -> magickwand ->  getImageWidth();
    }
    
    //Both of these examples are taken from here:
    //http://valokuva.org/?cat=1
    /*
    Add a caption
    */
    function doCaption( $width, $height, $text, $left=0, $top=0 ) {
      $im = new Imagick();
      $im ->newPseudoImage( $width, $height, "caption:" . $text );
      $this -> magickwand -> compositeImage($im, Imagick::COMPOSITE_DEFAULT, $left, $top);
    }
    
    /*
    Add an annotation
    */
    function doAnnotation( $left, $top, $text, $size=14, $color="white", $font="Helvetica", $center=false ) {
      
      $im = new Imagick();
      $draw = new ImagickDraw();
      $pixel = new ImagickPixel( 'transparent' );
      
      /* New image */
      $im->newImage($this -> magickwand -> getImageWidth(), $this -> magickwand -> getImageHeight(), $pixel);
      
      /* Font properties */
      $draw->setFont( $font );
      $draw->setFontSize( $size );
      $draw->setFillColor( $color );
      
      if ($center) {
        $fm = $im->queryFontMetrics($draw, $text, false);
        $left = ($this -> dest_size["width"] - $fm["textWidth"]) / 2;
        $im->annotateImage($draw, $left, $top + $size, 0, $text);
      } else {
        $im->annotateImage($draw, $left + $size, $top + $size, 0, $text);
      }
      /* Create text */
      $this -> magickwand -> compositeImage($im, Imagick::COMPOSITE_DEFAULT, 0, 0);
      
    }
    
    /*
    Write the image
    */
    function doWrite( $destination_dir=false, $name=false, $type="gif") {
      
      if ((! $destination_dir) && ($this -> destination_dir == null)) {
        die("File ".$destination_dir." wasn't written, source not available");
      }
      
      if (! $destination_dir) {
        $destination_dir = $this -> destination_dir;
      }
      
      if ((! $name) && ($this -> destination_name == null)) {
        die("No Destination Name");
      }
      
      if (! $name) {
        $name = $this -> destination_name;
      }
      
      try {
        /*** write the thumbnail ***/
        //putLog("WRITING IMAGE: " . $destination_dir."/".$name.".".$type);
        $this -> magickwand -> writeImage( $destination_dir."/".$name.".".$type );
        $this -> output_location = $destination_dir."/".$name.".".$type;
      } catch (Exception $e)  {
        
      }
    }
    
    function doDelete( $file ) {
      if (file_exists( $this -> destination_dir."/".$file )) {
       unlink ( $this -> destination_dir."/".$file );
  		}
    }
    
    function doClear() {
      $this -> magickwand -> clear();
    }
    
    function doBorder( $size=1, $color="black") {
      $this -> magickwand ->borderImage( $color, $size, $size );
    }
    
    /* Legacy Feature for applying a "Mask" overlay */
    function doMask( $directory, $thesize, $source, $image ) {
      
      try {
        //Pulled from the following Website
        //http://www.rubblewebs.co.uk/imagemagick/other.php?info=round_border.gif
        exec("/usr/bin/convert ".$source." -fill black -colorize 1% ".$directory."/gamma_".$thesize.".png");
        
        // Creating a canvas with a colour of peachpuff, drawing a cicle filled lightblue with a 10pixel black border, turning the light blue center transparent.
        exec("/usr/bin/convert -size ".$thesize."x".$thesize." xc:White -fill 'rgb(240,240,240)' -draw \"circle ".($thesize/2).",".($thesize/2)." ".($thesize/2).",2\" -transparent 'rgb(240,240,240)' ".$directory."/mask_".$thesize.".png");
        //exec("/usr/bin/convert -size 100x100 xc:PeachPuff -fill LightBlue -stroke white -strokewidth 4 -draw \"circle 50,50 50,3\" -transparent LightBlue mask.png");
        
        // Overlaying the mask made above over the photo
        exec("/usr/bin/convert ".$directory."/gamma_".$thesize.".png -composite ".$directory."/mask_".$thesize.".png -composite ".$directory."/temp.png");
        
        // Converting the peachpuff canvas to transparent.
        exec("/usr/bin/convert ".$directory."/temp.png -transparent White ".$directory."/temp.gif");
        
        // crop execss picture
        exec("/usr/bin/convert ".$directory."/temp.gif -crop ".$thesize."x".$thesize."+0+0 ".$image);
        
      } catch(Exception $e) {
      
        echo $e->getMessage();
      
      }
    }
    
    function listFonts() {
      echo("<strong>Fonts in this system are:</strong><br /><br />");
      foreach ($this -> magickwand -> queryFonts() as $fontname) {
        echo($fontname."<br />");
      }
      die();
    
    }
    
    function showImage() {
       
       $this -> magickwand -> setImageFormat( 'png' );
       header( "Content-Type: image/png" );
       echo $this -> magickwand;
       die();
    }
  }
  
?>
