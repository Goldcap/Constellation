<?php

/**
 * Provides support for session storage using a MySQL brand database.
 *
 * <b>parameters:</b> see sfDatabaseSessionStorage
 *
 * @package    ttj
 * @subpackage none
 * @author     Andy madsen<andy.madsen@tattoojohnny.com>
 * @version    SVN: $Id: MongoSessionStorage.class.php 10589 2008-08-01 16:00:48Z nicolas $
 */
class MongoDatabase extends sfDatabase
{
  /**
   * Destroys a session.
   *
   * @param  string $id  A session ID
   *
   * @return bool true, if the session was destroyed, otherwise an exception is thrown
   *
   * @throws <b>sfDatabaseException</b> If the session cannot be destroyed.
   */
  
  function connect() {
    return true;
  }
  
  function shutdown() {
    return true;
  }

}
