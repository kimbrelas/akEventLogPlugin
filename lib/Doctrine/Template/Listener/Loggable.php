<?php

class Doctrine_Template_Listener_Loggable extends Doctrine_Record_Listener
{
	protected $_options = array();
  
	public function __construct(array $options)
	{
		$this->_options = array_merge($options, $this->_options);
	}
	
	/**
	 * On insert, write to the log table unless auto_log is disabled
	 */
	public function postInsert(Doctrine_Event $event)
	{
    if(sfContext::hasInstance() && $this->getOption('auto_log'))
    {
      $event->getInvoker()->logEvent('Created');
    }
	}
	
	/**
	 * On update, write to the log table unless auto_log is disabled
	 */
	public function preUpdate(Doctrine_Event $event)
	{
    if(sfContext::hasInstance() && $this->getOption('auto_log'))
    {
      $event->getInvoker()->logEvent('Updated');
    }
	}
	
	/**
	 * On delete, write to the log table unless auto_log is disabled
	 */
	public function preDelete(Doctrine_Event $event)
	{
    if(sfContext::hasInstance() && $this->getOption('auto_log'))
    {
      $event->getInvoker()->logEvent('Deleted');
  	}
	}
	
	/**
	 * Enable the auto_log option
	 */
	public function enableAutoLog()
	{
    $this->setOption('auto_log', true);
	}
	
	/**
	 * Disable the auto_log option
	 */
	public function disableAutoLog()
	{
    $this->setOption('auto_log', false);
	}
}