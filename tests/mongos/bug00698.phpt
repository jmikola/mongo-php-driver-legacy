--TEST--
Test for PHP-698: Segmentation Fault - in mongo_deregister_callback_from_connection when calling MongoClient->close()
--SKIPIF--
<?php require_once "tests/utils/mongos.inc" ?>
--FILE--
<?php
require_once "tests/utils/server.inc";

$cfg = MongoShellServer::getShardInfo();
$mc = new MongoClient($cfg[0]);

$mc->selectCollection("test", "PHP-698")->drop();
for($i = 0; $i< 512; $i++) {
    $mc->selectCollection("test", "PHP-698")->insert(array("foo" => "bar", "i" => $i));
}

$c = $mc->selectCollection("test", "PHP-698")->find();
$n = 0;
// Leave an unfinished cursor open
foreach($c as $doc) {
    if ($n++ > 100) break;
}

$mc->selectCollection("test", "PHP-698")->drop();

// Closing a connection with a cursor open segfaults
$mc->close();
echo "I should still be alive!\n";
?>
--EXPECT--
I should still be alive!

