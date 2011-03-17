<?php

class Doctrine_Template_Loggable extends Doctrine_Template
{
  protected $_options = array(
    'auto_log' => true
  );
  
  /**
   * add the doctrine listener
   */
  public function setTableDefinition()
  {
    $this->addListener(new Doctrine_Template_Listener_Loggable($this->_options), 'loggable');
  }
  
  /**
   * Retrieves all associated log entries for the invoker
   *
   * @param DateTime $from only get events logged after this date/time
   * @param DateTime $to only get events logged before this date/time
   *
   * @return Doctrine_Collection
   */
  public function getEventLogs($from = null, $to = null)
  {
    $obj = $this->getInvoker();
    
    if($obj->isNew())
    {
      return array();
    }
    
    $q = Doctrine_Query::create()
      ->from('EventLog')
      ->where('object_model = ?', get_class($obj))
      ->andWhere('object_id = ?', $obj->id)
      ->orderBy('created_at DESC');
      
    if($from)
    {
      if(!($from instanceof DateTime))
      {
        throw new sfException('"From" value passed to getEventLog must be an instance of DateTime');
      }
      
      $q->andWhere('created_at >= ?', $from->format('Y-m-d H:i:s'));
    }
    
    if($to)
    {
      if(!($to instanceof DateTime))
      {
        throw new sfException('"To" value passed to getEventLog must be an instance of DateTime');
      }
      
      $q->andWhere('created_at <= ?', $to->format('Y-m-d H:i:s'));
    }
    
    return $q->execute();
  }
  
  /**
   * Overrides the default doctrine event hooks and generate your own log
   * entry using a custom method
   *
   * @param $method the custom method to be called
   * @param $params optional array of params to be passed to the custom method
   * @param $invoker optional custom invoker (ie the Loggable object's form)
   *
   * @return $ret the custom method's return value
   */
  public function customLog($method, $params = array(), $invoker = null)
  {
    $obj = ($invoker) ? $invoker : $this->getInvoker();
    
    $this->getListener()->get('loggable')->disableAutoLog();
    
    $ret = call_user_func_array(array($obj, $method), $params);
    
    $this->getListener()->get('loggable')->enableAutoLog();
    
    return $ret;
  }
  
  /**
   * Log an event from object context
   *
   * @return LogEvent
   */
  public function logEvent($description)
  {
    $manager = sfContext::getInstance()->getConfiguration()->getPluginConfiguration('akEventLogPlugin')->getEventLogManager();
    return $manager->logEvent($this->getInvoker(), $description);
  }
}