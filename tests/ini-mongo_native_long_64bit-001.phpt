--TEST--
Enabled "mongo.native_long" INI option allows reading and writing of 64-bit integers
--SKIPIF--
<?php if (8 !== PHP_INT_SIZE) { die('skip Only for 64-bit platform'); } ?>
--FILE--
<?php
$mongo = new Mongo('mongodb://localhost');
$coll = $mongo->selectCollection('test', 'mongo_native_long');
$coll->drop();

ini_set('mongo.native_long', true);

$coll->insert(array('int64' => 9223372036854775807));
$result = $coll->findOne();
var_dump($result['int64']);
?>
--EXPECT--
int(9223372036854775807)
