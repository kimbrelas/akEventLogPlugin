How to manually log an event to the database (this will NOT bypass the doctrine listeners (insert, update, delete), use customLog() for that)

$loggableObj->logEvent('Some description');

How to use customLog():

$loggableObj->customLog('someLogMethod', array('message' => 'Custom logging ftw'), $optional_invoker);

The above call to customLog assumes that your method, "someLogMethod", takes an argument "message". An optional invoker can be passed as a third argument if you want to add the method to another class (ie to catch the form's save).

How to retrieve (a Doctrine_Collection of) all associated EventLog records:

$logs = $this->getEventLogs($optional_from, $optional_to);

The from and to arguments are expected to be instances of DateTime.

How to retrieve the object associated with some event log record:

$log->getObject();

It's important to remember that sometimes a log record won't have an associated object (if it was deleted).

How to retrieve all logged events for a given model:

Doctrine_Core::getTable('EventLog')->getEventLogsForModel($model, $optional_from, $optional_to);

Again, the from and to arguments are expected to be instances of DateTime.

More to come later.