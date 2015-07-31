<?php

	header('Content-Type: text/html');

	error_reporting(E_ALL);


	session_start();

	define('SQLITE_FILE', __DIR__ . '/data/screens.sqlite');
	define('TEMPLATE_PATH', __DIR__ . '/data/templates/');

	$reply = array('DEBUG' => array(), 'screens' => array());

	$rows = 0;

	$id = $_REQUEST['id'];

	$reply['request'] = $_REQUEST;

	if (!file_exists(SQLITE_FILE)) {
		die("Sqlite db file not found: ".SQLITE_FILE);
	}


  try {
		$sqlite = new SQLite3(SQLITE_FILE);
		if (!$sqlite) {
			$reply['DEBUG'][] = "no SQLite!";
		}
  }
  catch (Exception $e) {
    // sqlite3 throws an exception when it is unable to connect
    $reply['DEBUG'][] = "Error connecting to the database : " . $e->getMessage();
  }


	if (isset($id) && is_numeric($id)) {
	  // create a query that should return a single record
	  $query = "SELECT * FROM oya_screens WHERE id = $id;";
	}
	else {
	  // create a query that should return all records
	  $query = 'SELECT * FROM oya_screens ORDER BY updated DESC';
	}

  // execute the query
  // query returns FALSE on error, and a result object on success
  $sqliteResult = $sqlite->query($query);

  if (!$sqliteResult) {
    // the query failed and debugging is enabled
    $reply['DEBUG'][] = "There was an error in query: $query";
    $reply['DEBUG'][] =  $sqlite->lastErrorMsg();
  }
  if ($sqliteResult) {
    // the query was successful
    // get the result (if any)
    // fetchArray returns FALSE if there is no record
    while ($row = $sqliteResult->fetchArray(1)) {
			array_push($reply['screens'], $row);
      $reply['DEBUG'][] = "Retrieved row " . $rows++;
    }
    // when you are done with the result, finalize it
    $sqliteResult->finalize();
  }



  // clean up any objects
  $sqlite->close();

  if ($rows) {
  	$row = $reply['screens'][$rows-1];
  }

  if (isset($row) && isset($row['data']) && is_string($row['data'])) {
  	$row['data'] = json_decode($row['data'], true);
  	if (isset($row['data']) && isset($row['data']['filename'])) {
  		$row['template'] = $row['data']['filename'];
  	}
  }
  else {
  	die("No row");
  }

  if ($row['template'] && file_exists(TEMPLATE_PATH.$row['template'])) {
  	$template = file_get_contents(TEMPLATE_PATH.$row['template']);
  }
  else {
  	die("Template does not exist: '{$row['template']}': " . print_r($row, true));
  }

  if ($row['data'] && count($row['data'])) {
  	foreach ($row['data'] as $key => $value) {
  		if (!isset($row[$key])) {
  			$row[$key] = $value;
  		}
  	}
  }

	$reply['DEBUG'][] = "Read $rows rows";

	$row['reply'] = $reply;

	//register mustache library
	require 'Mustache/Autoloader.php';
	Mustache_Autoloader::register();

	$m = new Mustache_Engine;

	echo $m->render($template, $row);

?>
