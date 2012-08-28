<?php

/**
 * @version $Id: ByColor.php 516 2006-03-29 03:56:51Z drewish $
 * @author  Andrew Morton <drewish@katherinehouse.com>
 * @license http://opensource.org/licenses/lgpl-license.php
 *          GNU Lesser General Public License, Version 2.1
 * @package Phlickr
 */


/**
 * Phlickr_Api includes the core classes.
 */
require_once 'Phlickr/Api.php';
/**
 * This class implements IPhotoSortStrategy.
 */
require_once 'Phlickr/Framework/IPhotoSortStrategy.php';


/**
 * An object to compare the color of two photos allowing them to be sorted.
 *
 * This class uses the GD library to resample the image to a single 1x1 pixel
 * image. This pixel should be the average color of the image. Take that RGB
 * value, convert it to it's HSV color-model value which can be easily sorted.
 *
 * A crude attempt is made at caching the comparison results.
 *
 * @author      Andrew Morton <drewish@katherinehouse.com>
 * @package     Phlickr
 * @subpackage  PhotoSortStrategy
 * @since       0.2.4
 */
class Phlickr_PhotoSortStrategy_ByColor implements Phlickr_Framework_IPhotoSortStrategy {
    /**
     * Cache so that the photos don't need to be downloaded repeatedly.
     *
     * @var object Phlickr_Cache
     */
    private $_cache = null;

    /**
     * Constructor
     *
     * Because the process of downloading the photo to find it's average color
     * takes a while the Phlickr_Cache is used to preserve the results across
     * sorts.
     *
     * @param   object Phlickr_Cache $cache
     */
    function __construct(Phlickr_Cache $cache) {
        $this->_cache = $cache;
    }

    /**
     * Get the average RBG color of a JPEG image.
     *
     * This function uses the GD library to resize the image to a single pixel
     * and then returns its RBG value. In the even that the file cannot be
     * opened the color black will be returned.
     *
     * @param   string $jpgFile JPEG file name. If the fopen wrappers are
     *          enabled this can be a URL.
     * @return  array PEAR style RGB array, or false on error.
     * @since   0.2.4
    */
    static public function getAverageRgbColor($jpgFile) {
        $ret = false;

        // open the file
        $imgFull = imagecreatefromjpeg($jpgFile);
        if ($imgFull) {
            // create a new 1x1 image
            $imgPixel = imagecreatetruecolor(1, 1);

            // Resample to 1x1
            $h = imagesy($imgFull);
            $w = imagesx($imgFull);
            imagecopyresampled($imgPixel, $imgFull, 0, 0, 0, 0, 1, 1, $w, $h);

            // find the rgb value of the single pixel
            $color = imagecolorat($imgPixel, 0, 0);
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8)  & 0xFF;
            $b = ($color)       & 0xFF;
            $ret = array($r, $g, $b, 'type' => 'rgb');

            // clean up
            imagedestroy($imgPixel);
            imagedestroy($imgFull);
        }

        return $ret;
    }

    /**
     * Convert an RGB color to an HSV.
     *
     * @param   array PEAR color array (0=>r,1=>g,2=>b) values 0-255.
     * @return  array An array with the following values: H integer 0-360,
     *          S float 0.0-1.0, V float 0.0-1.0
     * @link    http://en.wikipedia.org/wiki/HSV_color_space
     * @since   0.2.4
     */
    static function HsvFromRgb($rgb) {
        assert(is_array($rgb) && count($rgb >= 3));

        $r = $rgb[0] / 255;
        $g = $rgb[1] / 255;
        $b = $rgb[2] / 255;

        assert(is_float($r) && $r >= 0 && $r <= 1);
        assert(is_float($g) && $g >= 0 && $g <= 1);
        assert(is_float($b) && $b >= 0 && $b <= 1);

        $min = min($r, $g, $b);
        $max = max($r, $g, $b);

        switch ($max) {
        case 0: // it's black like my soul.
            // value = 0, hue and saturation are undefined
            $h = $s = $v = 0;
            break;

        case $min: // grey
            // saturation = 0, hue is undefined
            $h = $s = 0;
            $v = $max;
            break;

        default: // normal color color
            $delta = $max - $min;

            // hue
            if( $r == $max ) {
                // between yellow & magenta
                $h = 0 + ( $g - $b ) / $delta;
            } else if( $g == $max ) {
                // between cyan & yellow
                $h = 2 + ( $b - $r ) / $delta;
            } else {
                // between magenta & cyan
                $h = 4 + ( $r - $g ) / $delta;
            }
            // convert hue to degrees
            $h *= 60;
            if($h < 0 ) {
                $h += 360;
            }
            // saturation
            $s = $delta / $max;
            // value
            $v = $max;
        }

        return array((integer) $h, (float) $s, (float) $v, 'type' => 'hsv');
    }

    /**
     * Return a sortable string of the photos average color.
     *
     * Steps:
     * -  Resample the image to single pixel.
     * -  Convert the pixel's RGB value to an HSV value,
     * -  Convert the HSV to a string.
     *
     * @param   object Phlickr_Photo $photo
     * @return  string or false in case of error.
     */
    public function stringFromPhoto(Phlickr_Photo $photo) {
        // cache the RGB value so that multiple color sort strategies can
        // share the average color.
        $key = 'avg_color:' . $photo->getId();

        if ($this->_cache->has($key)) {
            $rgb = $this->_cache->get($key);
        } else {
            $url = $photo->buildImgUrl(Phlickr_Photo::SIZE_100PX);
            if ($rgb = self::getAverageRgbColor($url)) {
                // add the average color to the cache and mark it as
                // non-expiring because the photo can't be changed.
                $this->_cache->set($key, $rgb, -1);
            } else {
                // if there's an error getting the average value don't add it
                // to the cache, just bail.
                return false;
            }
        }
        $hsv = self::HsvFromRgb($rgb);

        return sprintf("%02d,%02d,%02d",
            round($hsv[0] / 30), round($hsv[1] * 10), round($hsv[2] * 10));
    }
}
