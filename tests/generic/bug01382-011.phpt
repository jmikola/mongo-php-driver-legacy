--TEST--
Test for PHP-1382: looping with just getNext() (with getmore)
--SKIPIF--
<?php require_once "tests/utils/standalone.inc" ?>
--FILE--
<?php
require_once "tests/utils/server.inc";

function log_getmore($server, $info) {
	echo "Issuing getmore\n";
}

$ctx = stream_context_create(array(
	'mongodb' => array(
		'log_getmore' => 'log_getmore',
	),
));

$host = MongoShellServer::getStandaloneInfo();
$m = new MongoClient($host, array(), array('context' => $ctx));

$c = $m->selectCollection(dbname(), collname(__FILE__));
$c->drop();

$c->save(array('_id' => 'test1'));
$c->save(array('_id' => 'test2'));
$c->save(array('_id' => 'test3'));
$c->save(array('_id' => 'test4'));
$cur = $c->find(array(), array('_id'))->batchSize(2);
while (true) {
	$info = $cur->info(); echo 'a: ', @$info['at'], ' - ', @$info['numReturned'], ' - ', @$info['id'], "\n";
	$arr = $cur->getNext();
	$info = $cur->info(); echo 'b: ', @$info['at'], ' - ', @$info['numReturned'], ' - ', @$info['id'], "\n";
	if ($arr === NULL) {
		break;
	}
	var_dump($arr['_id']);
}

?>
--EXPECTF--
a:  -  - 
b: 0 - 2 - %d
string(5) "test1"
a: 0 - 2 - %d
b: 1 - 2 - %d
string(5) "test2"
a: 1 - 2 - %d
Issuing getmore
b: 2 - 4 - %d
string(5) "test3"
a: 2 - 4 - %d
b: 3 - 4 - %d
string(5) "test4"
a: 3 - 4 - %d
Issuing getmore
b: 4 - 4 - 0
