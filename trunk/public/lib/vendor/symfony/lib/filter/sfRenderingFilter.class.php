<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfRenderingFilter is the last filter registered for each filter chain. This
 * filter does the rendering.
 *
 * @package    symfony
 * @subpackage filter
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfRenderingFilter.class.php 29524 2010-05-19 12:55:30Z fabien $
 */
class sfRenderingFilter extends sfFilter
{
  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain The filter chain.
   *
   * @throws <b>sfInitializeException</b> If an error occurs during view initialization
   * @throws <b>sfViewException</b>       If an error occurs while executing the view
   */
  public function execute($filterChain)
  {
    // execute next filter
    $filterChain->execute();

    // get response object
    $response = $this->context->getResponse();

    // hack to rethrow sfForm and|or sfFormField __toString() exceptions (see sfForm and sfFormField)
    if (sfForm::hasToStringException())
    {
      throw sfForm::getToStringException();
    }
    else if (sfFormField::hasToStringException())
    {
      throw sfFormField::getToStringException();
    }
    
    //Replace remote secure images with this!
    if (sfConfig::get("app_clean_output")) {
      $output = new Output_PageWidget( $this -> context );
      $response = $output -> cleanResponse( $response );
    }
  
    //Replace remote secure images with this!
    if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")) {
      $cache = new SSL_PageWidget( $this -> context );
      $response = $cache -> clearNonSSLImages( $response );
    }
    
    //Note, force_cache is set in the config
    //since there is no alternate way to set values
    //into the filter chain
    //One drawback of using Symfony
    if ((sfConfig::get("app_pagewidget_cache")) || (sfConfig::get("force_cache"))) {
      $cache = new Cache_PageWidget( $this -> context );
      $cache -> createCache();
    }
    
    // send headers + content
    if (sfView::RENDER_VAR != $this->context->getController()->getRenderMode())
    {
        $response->send();
    }
  }
}
