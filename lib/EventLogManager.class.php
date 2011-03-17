<?php

class EventLogManager
{
  /**
   * Log an event to the database
   *
   * @param Doctrine_Record $obj the object the event is affecting
   * @param $description a short description of the event being logged
   *
   * @return EventLog
   */
  public function logEvent(Doctrine_Record $obj, $description)
  {
    if($obj->isNew())
    {
      throw new sfException('The record must be saved before changes can be logged.');
    }
    
    $log = new EventLog();
    $log->description = $description;
    $log->object_model = get_class($obj);
    $log->object_id = $obj->id;
    
    return $log->save();
  }
}