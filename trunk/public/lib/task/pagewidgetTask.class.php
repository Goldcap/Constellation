<?php

require_once(dirname(__FILE__).'/../vendor/wtvr/1.3/admin/clsWTVRItemCreator.php');
require_once(dirname(__FILE__).'/../vendor/wtvr/1.3/admin/clsWTVRCrudCreator.php');
require_once(dirname(__FILE__).'/../vendor/wtvr/1.3/admin/clsWTVRScaffolding.php');
require_once(dirname(__FILE__).'/../vendor/wtvr/1.3/admin/clsWTVRPHPCreator.php');
require_once(dirname(__FILE__).'/../vendor/WideXML/XML.php');
require_once(dirname(__FILE__).'/../vendor/WideXML/XSL.php');

class pagewidgetTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('op', sfCommandArgument::REQUIRED, 'The Specific Action To Take'),
       new sfCommandArgument('widget', sfCommandArgument::OPTIONAL, 'The widget name'),
       new sfCommandArgument('application', sfCommandArgument::OPTIONAL, 'Application','frontend'),
       new sfCommandArgument('env', sfCommandArgument::OPTIONAL, 'The environment','dev'),
       new sfCommandArgument('args', sfCommandArgument::OPTIONAL, 'Things to do with the widget'),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'propel'),
      new sfCommandOption('args', null, sfCommandOption::PARAMETER_REQUIRED, 'Various Extra Arguments'),
      // add your own options here
    ));
    /*
    object(sfCommandArgument)#258 (4) {
      ["name":protected]=>
      string(3) "env"
      ["mode":protected]=>
      int(2)
      ["default":protected]=>
      string(3) "dev"
      ["help":protected]=>
      string(15) "The environment"
    }
    */
    
    $this->aliases = array('pagewidget');
    $this->namespace        = '';
    $this->name             = 'widget';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [pagewidget:module|INFO] task creates the basic directory structure
for a new widget in this symfony build:

  [./symfony pagewidget:module widget|INFO]

The task can will create a new module.

  [symfony]
    name=pagewidget
    author=Andy Madsen <amadsen@operislabs.com>

If a module with the same name already exists in the application,
it throws a [sfCommandException|COMMENT].
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    foreach ($arguments as $key => $value) {
    	if (isset($options[$key])) {
        $options[$key] = $arguments[$key];
      }
    }
    $this -> op = $arguments['op'];
    
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    switch ($this -> op) {
      case "exec":
        $this -> execWidget($arguments, $options);
        break;
      case "widget":
        $this -> createWidget($arguments, $options);
        break;
      case "crud":
        $this -> createCruds($arguments, $options);
        break;
      case "scaffold":
        $this -> createScaffold($arguments, $options);
        break;
    }
    // add your code here
  }
  
  
  protected function execWidget($arguments = array(), $options = array()) 
  {
    $configuration = sfApplicationConfiguration::getApplicationConfiguration($options['application'],$options['env'], true);
    $context = sfContext::createInstance($configuration);
    
    $theargs = array();
    (isset($arguments['args'])) ? $arguments['args']=explode(",",$arguments['args']):null;
    
    if (count($arguments['args']) > 0) {
      foreach($arguments['args'] as $arg) {
        $theargs['args'][] = $arg;
      }
    }
    
		require_once(sfConfig::get('sf_lib_dir').'/vendor/PageWidgets/CacheWidget.class.php');
    require_once(sfConfig::get('sf_lib_dir').'/vendor/PageWidgets/PageWidget.class.php');
    require_once(sfConfig::get('sf_lib_dir').'/vendor/PageWidgets/UtilWidget.class.php');
    require_once(sfConfig::get('sf_lib_dir').'/vendor/PageWidgets/WidgetWidget.class.php');
    require_once(sfConfig::get('sf_lib_dir').'/vendor/styroform/1.2/clsXMLFormUtils.php');
    require_once(sfConfig::get('sf_lib_dir').'/vendor/styroform/1.2/clsXMLForm.php');
    require_once(sfConfig::get('sf_lib_dir').'/vendor/styroform/1.2/clsFormValidator.php');
    
    $this -> widget = $arguments['widget'];
    require_once(sfConfig::get('sf_lib_dir').'/widgets/'.$this -> widget.'/'.$this -> widget.'.class.php');
    
    $this->page_widget = new PageWidget($context->getRequest(),false,$context);
    $this->page_widget->op=$arguments['widget'];
    $this->page_widget->variables[$this -> widget][0]=$theargs;
    $this->page_widget->renderCli();
    $content = $this->page_widget->result;
    print($content);
    die();
  }
  
  protected function createWidget($arguments = array(), $options = array())
  {
    $this -> widget = $arguments['widget'];
    
    if (! $this -> widget) {
      $this->log('Need to specify a name for your widget.');
      return;
    }
    
    $trans = new WTVRItemCreator();
    $trans -> templateloc = sfConfig::get('sf_lib_dir').'/vendor/PageWidgets/templates/';
    $trans -> loc = sfConfig::get('sf_lib_dir').'/widgets/'. $this -> widget;
    $trans -> type = 'widget';
    $trans -> name = $this -> widget;
    $trans -> createTree();
    $trans -> createClass();
    $trans -> postProcess();
    
    // your code here
    $this->log('Created widget "'. $this -> widget.'"');
  }
  
  protected function createCruds($arguments = array(), $options = array())
  {
    $trans = new WTVRCrudCreator();
    $trans -> doCrud();
    
    // your code here
    $this->log('Created cruds.');
  }
  
   protected function createScaffold($arguments = array(), $options = array())
  {
    $trans = new WTVRScaffolding();
    $trans -> doScaffold();
    
    // your code here
    $this->log('Created cruds.');
  }

}
