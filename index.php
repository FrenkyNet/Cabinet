<?php

use Cabinet\Database\Db;

class MyObject
{

}

echo 1;

require './vendor/autoload.php';

echo 1;

$conn = Db::connection(array(
	'driver' => 'mysql',
	'username' => 'root',
	'password' => 'root',
	'database' => 'louter',
));

$conn2 = Db::connection(array(
	'driver' => 'mysql',
	'username' => 'root',
	'password' => 'root',
	'database' => 'test_database',
));



$conn->schema()
	->createDatabase('unknown_database')
	->execute();

$conn->schema()
	->database('unknown_database')
	->drop()
	->execute();

$query = Db::query('SELECT * from `:table`', Db::SELECT, array(
	'table' => 'blocks',
))->asObject(true);

$compiled = $query->execute($conn);

$result = $conn->select(array(Db::fn('max', 'id'), 'max_id'))
	->from('containers')
	->asObject('MyObject')
	->execute();

print_r($result);

echo 1;

$subquery = Db::select('container_id')
	->from('blocks')
	->where('id', 'in', array(1, 2, 3));

$query = Db::select()->from('containers')
	->where('id', 'in', $subquery)
	->bind('id' , '%1%')
	->asObject(true);

// a query can compile with a connection
$compiled = $query->compile($conn);

// and a connection can compile a query
$compiled = $conn->compile($query);

// works the same for executing

//var_dump($compiled);

//var_dump($update);



$result = $conn->select('*')
	->from('blocks')
	->execute();

var_dump($result);

$pg = Db::connection(array(
	'driver' => 'pgsql',
	'username' => 'postgres',
	'password' => 'password',
	'database' => 'mydb',
	'port' => 5432,
));

$pg->delete('my_table')
	//->where('id', '<', 100)
	->execute();

$pg->insert('my_table')
	->values(array(
		'name' => 'John: '.time(),
	))
	->execute();


var_dump($pg->select()
	->where('id', '>', 0)
	->orderBy('id', 'desc')
	->from('my_table')
	->limit(2)
	->offset(2)
	->asObject()
	->execute());

var_dump($pg->lastQuery());

$sqlite = Db::connection(array(
	'driver' => 'sqlite',
	'dsn' => 'sqlite:/Applications/MAMP/db/sqlite/mysqlite',
));

var_dump($sqlite->select()->from('my_table')->asObject()->execute());

print_r($conn->profilerQueries());
