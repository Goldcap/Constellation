<?php

class mailTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));
    
    $this->namespace        = '';
    $this->name             = 'mail';
    $this->briefDescription = 'Trigger the Mail Queue';
    $this->detailedDescription = <<<EOF
The [mail] task does things.
Call it with:

  [php symfony mail|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {

    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
    
    $configuration = sfApplicationConfiguration::getApplicationConfiguration($options['application'],$options['environment'], true);
    $context = sfContext::createInstance($this->configuration);

    $mail = new WTVRBaseMailTemplate( $context );
    $mail -> processLocalMessageQueue();

    $mail = new WTVRBaseMail( $context );
    $mail -> processGlobalMessageQueue();
    die("DONE");
    
    // add your code here
  }
}
