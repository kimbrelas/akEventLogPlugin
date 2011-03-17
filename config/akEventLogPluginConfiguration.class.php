<?php

class akEventLogPluginConfiguration extends sfPluginConfiguration
{
  protected $_event_log_manager;
  
  /**
   * Lazy load the event log manager
   *
   * @return EventLogManager
   */
  public function getEventLogManager()
  {
    if(!$this->_event_log_manager)
    {
      $this->_event_log_manager = new EventLogManager();
    }
    
    return $this->_event_log_manager;
  }
}