--TEST--
MongoDate insertion
--FILE--
<?php
$mongo = new Mongo('mongodb://localhost');
$coll = $mongo->selectCollection('test', 'mongodate');
$coll->drop();

$date = new MongoDate();

$coll->insert(array('_id' => 1, 'date' => $date));
$result = $coll->findOne(array('_id' => 1));
echo get_class($result['date']) . "\n";
var_dump($result['date']->sec === $date->sec);
var_dump($result['date']->usec === $date->usec);
?>
--EXPECT--
MongoDate
bool(true)
bool(true)
