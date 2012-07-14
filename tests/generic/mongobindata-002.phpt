--TEST--
MongoBinData insertion with default type
--SKIPIF--
<?php require __DIR__ . "/skipif.inc";?>
--FILE--
<?php
require_once __DIR__ . "/../utils.inc";
$mongo = mongo();
$coll = $mongo->selectCollection(dbname(), 'mongobindata');
$coll->drop();

$coll->insert(array('_id' => 1, 'bin' => new MongoBinData('abcdefg')));

$result = $coll->findOne(array('_id' => 1));

echo get_class($result['bin']) . "\n";
echo $result['bin']->bin . "\n";
echo $result['bin']->type . "\n";
?>
--EXPECTF--
Deprecated: MongoBinData::__construct(): The default value for type will change to 0 in the future. Please pass in '0' explicitly. in %s on line %d
MongoBinData
abcdefg
2
