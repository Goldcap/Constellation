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
class MongoSessionStorage extends sfDatabaseSessionStorage
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
  
  public function sessionOpen($path = null, $name = null) {
  
    // what database are we using?
    $serverdata = $this->options['database'] -> getParameter("dsn");
    $opts = explode(":",$serverdata);
    if ($opts[0] == '')
      return false;
      
    $this -> m = new Mongo($opts[0]);
    
    $this -> collection = $this -> m->selectDB( $opts[1] )->selectCollection( $this->options['db_table'] );
    $this -> collection-> ensureIndex( $this->options['db_id_col'] );  // same
    
    //Removed Temporarily for Performance
    //$this -> hitCount();
    
    return true;
  }
  
  
  public function sessionDestroy($id)
  {
    
    if ($this -> collection -> remove( array($this->options['db_id_col'] => $id ) ))
    {
      return true;
    }

    // failed to destroy session
    throw new sfDatabaseException(sprintf('%s cannot destroy session id "%s" (%s).', get_class($this), $id, "Mongo DB Error" ));
  }

  /**
   * Cleans up old sessions.
   *
   * @param  int $lifetime  The lifetime of a session
   *
   * @return bool true, if old sessions have been cleaned, otherwise an exception is thrown
   *
   * @throws <b>sfDatabaseException</b> If any old sessions cannot be cleaned
   */
  public function sessionGC($lifetime)
  {
    
    $rangeQuery = array($this->options['db_time_col'] => array( '$lt' => time() - $lifetime ));
    
    if (!$this -> collection -> remove( $rangeQuery ))
    {
      throw new sfDatabaseException(sprintf('%s cannot delete old sessions (%s).', get_class($this), "Mongo DB Error"));
    }

    return true;
  }

  /**
   * Reads a session.
   *
   * @param  string $id  A session ID
   *
   * @return string      The session data if the session was read or created, otherwise an exception is thrown
   *
   * @throws <b>sfDatabaseException</b> If the session cannot be read
   */
  public function sessionRead( $id )
  {
    if (isset($_GET[session_name()])) {
      $id = $_GET[session_name()];
    }
    
    $query = array( $this->options['db_id_col'] => $id );
    $result = $this -> collection->findOne( $query );
    
    // return the record associated with this id
    if ($result){
      // found the session
      //Removed Temporarily for Performance
      /*
        if (preg_match('/user_id";i:(\d+)/',$result[$this->options['db_data_col']],$matches)) 
          $this -> userCount( $id, $matches[1] );
      */
      return $result[$this->options['db_data_col']];
    
    }
    else
    {
      // session does not exist, create it
      $doc = array (
          $this->options['db_id_col']=>$id,
          $this->options['db_data_col']=>"",
          $this->options['db_time_col']=>time()
        );
      
      if ($this -> collection->insert( $doc ))
      {
        //Record User in Session Tracker
        //Removed Temporarily for Performance
        //$this -> userCount( $id, 0 );
        
        return '';
      }

      // can't create record
      throw new sfDatabaseException(sprintf('%s cannot create new record for id "%s" (%s).', get_class($this), $id, "Mongo DB Error"));
    }
  }

  /**
   * Writes session data.
   *
   * @param  string $id    A session ID
   * @param  string $data  A serialized chunk of session data
   *
   * @return bool true, if the session was written, otherwise an exception is thrown
   *
   * @throws <b>sfDatabaseException</b> If the session data cannot be written
   */
  public function sessionWrite($id, $data)
  {
    $query = array( $this->options['db_id_col'] => $id );
    $doc = array (
          $this->options['db_id_col']=>$id,
          $this->options['db_data_col']=>$data,
          $this->options['db_time_col']=>time()
        );
    
    if ($this -> collection)
    {
      //Record User in Session Tracker
      //Removed Temporarily for Performance
      //$this -> userCount( $id );
      $this -> collection-> update($query,$doc,true);
      
      return true;
    }

    // failed to write session data
    throw new sfDatabaseException(sprintf('%s cannot write session data for id "%s" (%s).', get_class($this), $id, "Mongo DB Error" ));
  }
  
  public function listSessions() {
    $cursor = $this -> collection -> find();
    while( $cursor->hasNext() ) {
      $data =  $cursor->getNext();
      print ($data[$this->options['db_id_col']]."<br />");
      print ($data[$this->options['db_data_col']]."<br />");
      print ($data[$this->options['db_time_col']]."<br />");
    }
  }
  
  public function truncateSessions() {
    $this -> collection->drop();
    return true;
  }

}
