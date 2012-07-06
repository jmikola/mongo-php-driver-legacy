--TEST--
MongoInt64 constructed with 64-bit integer
--SKIPIF--
<?php if (8 !== PHP_INT_SIZE) { die('skip Only for 64-bit platform'); } ?>
--FILE--
<?php
$mongo = new Mongo('mongodb://localhost');
$coll = $mongo->selectCollection('test', 'mongoint64');
$coll->drop();

$coll->insert(array('int64' => new MongoInt64(123456789012345)));

ini_set('mongo.native_long', false);
ini_set('mongo.long_as_object', false);
$result = $coll->findOne();
var_dump($result['int64']);

ini_set('mongo.native_long', true);
ini_set('mongo.long_as_object', false);
$result = $coll->findOne();
var_dump($result['int64']);

ini_set('mongo.native_long', false);
ini_set('mongo.long_as_object', true);
$result = $coll->findOne();
printf("%s(%s)\n", get_class($result['int64']), $result['int64']);

ini_set('mongo.native_long', true);
ini_set('mongo.long_as_object', true);
$result = $coll->findOne();
printf("%s(%s)\n", get_class($result['int64']), $result['int64']);
?>
--EXPECT--
float(1.2345678901234E+14)
int(123456789012345)
MongoInt64(123456789012345)
MongoInt64(123456789012345)
