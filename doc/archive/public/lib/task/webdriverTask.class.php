<?php

class webdriverTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       //new sfCommandArgument('op', sfCommandArgument::REQUIRED, 'The action to be performed'),
       new sfCommandArgument('test', sfCommandArgument::REQUIRED, 'The test script, or "ALL"'),
       new sfCommandArgument('config', sfCommandArgument::OPTIONAL, 'The test script, or "ALL"'),
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
    
    $this->aliases = array('WebDriver');
    $this->namespace        = '';
    $this->name             = 'webdriver';
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
    
    $this -> runTest($arguments, $options);
    
    /*
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
    */
    // add your code here
  }
  
  
  protected function runTest($arguments = array(), $options = array()) 
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
    $test = new Webdriver_PageWidget( $context, $arguments );

  }

}
