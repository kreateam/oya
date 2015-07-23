<?php

	header('Content-Type: application/json');

	error_reporting(E_ALL);

	session_start();

	define('CACHE_FILE', __DIR__ . '/data/screens.json');

	// cache update interval, in seconds
	define('CACHE_INTERVAL', 600);
	define('SQLITE_FILE', __DIR__ . '/data/screens.sqlite');

	$reply = array('DEBUG' => array(), 'screens' => array());
	$nocache = false;
	$doCreate = false;

	if (!file_exists(CACHE_FILE)) {
		$nocache = true;
	}
	elseif (time() - filemtime(CACHE_FILE) > CACHE_INTERVAL) {
		$reply['DEBUG'][] = "Updating cached, time diff: " . (time() - filemtime(CACHE_FILE));
		$nocache = true;
	}



	$rows = 0;

	if (!function_exists('getallheaders'))  {
	  function getallheaders()
	  {
	    if (!is_array($_SERVER)) {
	      return array();
	    }

	    $headers = array();
	    foreach ($_SERVER as $name => $value) {
	      if (substr($name, 0, 5) == 'HTTP_') {
	        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
	      }
	    }
	    return $headers;
	  }
	}

	$isFormData = false;
	$headers = getallheaders();

	foreach ($headers as $header => $value) {
		if (strtolower($header) == "content-type") {
			if (strpos(strtolower($value), "multipart/form-data") > -1) {
				$isFormData = true;
		    $reply['DEBUG'][] = "That's a multipart form!";
			}
		}
    // $reply['DEBUG'][] = "$header: $value";
	}

	if ($isFormData) {
		$request = $_REQUEST;
	}
	else {
		$request = file_get_contents("php://input");	
		if ($request) {
			$request = json_decode($request, true);
		}
	}


	if (isset($request['submit'])) {
		unset($request['submit']);
	}

	$reply['request'] = $request;



	if ($isFormData && isset($request['action']) && $request['action'] == "save") {
		if (isset($request['id']) && is_numeric($request['id'])) {
			$request['action'] = "update";
		}
		else {
			$request['action'] = "create";
		}
	}


	if ($isFormData && !isset($request['action'])) {
  	if (!isset($request['id']) || !is_numeric($request['id'])) {
			$request['action'] = "create";
  	}
  	else {
			$request['action'] = "update";
  	}
	}


	if (!file_exists(SQLITE_FILE)) {
		$doCreate = true;
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


  if (!$request) {
  	$request = array("action" => "read");
  	$reply['DEBUG'][] = "No request received : set to default action READ";
  }
  elseif (!isset($request['action'])) {
  	// default action is to list entire table
  	$reply['DEBUG'][] = "No action given in request : set to default action READ";
  	$request["action"] = "read";
  }

  if (!isset($request['data'])) {
	  $data = array();
	  foreach ($request as $key => $value) {
		  if ($key == "title" || $key == "template" || $key == "action") {
		  	continue;
		  }
		  $data[$key] = $value;
	  }
	  if (count($data)) {
			$request['data'] = SQLite3::escapeString(json_encode($data, JSON_PRETTY_PRINT));
	  }
  }
  elseif (!is_string($request['data'])) {
  	$request['data'] = SQLite3::escapeString(json_encode($request['data'], JSON_PRETTY_PRINT));
  }

try {

  if ($doCreate) {
  	$reply['DEBUG'][] = "Creating missing DB: " . SQLITE_FILE;
	  $query = "CREATE TABLE IF NOT EXISTS oya_screens (
			id INTEGER PRIMARY KEY,
		  title char(127) NOT NULL,
		  data text NOT NULL,
		  template char(255) NOT NULL,
		  updated timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
			)";
		// create table
	  $sqliteResult = $sqlite->query($query);

	  if (!$sqliteResult) {
	    $reply['error'] = $sqlite->lastErrorMsg();
	    $reply['DEBUG'][] = "There was an error in query: $query";
	    $reply['DEBUG'][] =  $reply['error'];
	  }
	  if ($sqliteResult) {
	    // the query was successful
	    $reply['status'] = "ok";
	    $sqliteResult->finalize();
	  }

	  $reply['DEBUG'][] = "Created table : " . (bool) $sqliteResult;

	  $query = "CREATE INDEX updated ON oya_screens (updated);
			CREATE INDEX title ON oya_screens (title);
			CREATE INDEX template ON oya_screens (template);
		";
		// create table
	  $sqliteResult = $sqlite->query($query);

	  if (!$sqliteResult) {
	    $reply['error'] = $sqlite->lastErrorMsg();
	    $reply['DEBUG'][] = "There was an error in query: $query";
	    $reply['DEBUG'][] =  $reply['error'];
	  }
	  if ($sqliteResult) {
	    // the query was successful
	    $reply['status'] = "ok";
	    $sqliteResult->finalize();
		  $reply['DEBUG'][] = "Added indexes : " . (bool) $sqliteResult;
	  }

  }
}
catch(Exception $e) {
	$reply['error'] = get_class($e) . " : " . $e->getMessage();
}



  switch ($request['action']) {
  	case 'add':
  	case 'new':
  	case 'create':
		  // create a query that should return a single record
		  $query = "INSERT INTO oya_screens 
		  				(id, title, template, data) 
		  VALUES 	(NULL,'{$request['title']}', '{$request['template']}', '{$request['data']}')";
		  // execute the query
		  // query returns FALSE on error, and a result object on success
		  $sqliteResult = $sqlite->query($query);

		  if (!$sqliteResult) {
		    // the query failed and debugging is enabled
		    $reply['DEBUG'][] = "There was an error in query: $query";
		    $reply['DEBUG'][] =  $sqlite->lastErrorMsg();
		  }
		  if ($sqliteResult) {
		  		$reply['id'] = $sqlite->lastInsertRowID();
		  		$reply['status'] = "ok";
		      $reply['DEBUG'][] = "Created row";
		    // when you are done with the result, finalize it
		    $sqliteResult->finalize();
		  }
  		break;
  	case 'update':
  		if (!isset($request['id']) || !is_numeric($request['id'])) {
  			$reply['error'] = "No id specified for action UPDATE";
  			break;
  		}
		  // create a query that should return a single record
		  $query = "UPDATE oya_screens SET title ='{$request['title']}', template ='{$request['template']}',data ='{$request['data']}',updated = CURRENT_TIMESTAMP) WHERE id = {$request['id']}";
		  // execute the query
		  // query returns FALSE on error, and a result object on success
		  $sqliteResult = $sqlite->query($query);

		  if (!$sqliteResult) {
		    // the query failed and debugging is enabled
		    $reply['error'] = $sqlite->lastErrorMsg();
		    $reply['DEBUG'][] = "There was an error in query: $query";
		    $reply['DEBUG'][] =  $reply['error'];
		  }
		  if ($sqliteResult) {
		    // the query was successful
		    $reply['status'] = "ok";

		    $sqliteResult->finalize();
		  }
  		break;
  	case 'delete':

  		if (!isset($request['id']) || !is_numeric($request['id'])) {
  			$reply['error'] = "No id specified for action DELETE";
  			break;
  		}

		  $query = "DELETE FROM oya_screens WHERE id ='{$request['id']}'";

		  $sqliteResult = $sqlite->query($query);

		  if (!$sqliteResult) {
		    // the query failed and debugging is enabled
		    $reply['DEBUG'][] = "There was an error in query: $query";
		    $reply['DEBUG'][] =  $sqlite->lastErrorMsg();
		  }
		  if ($sqliteResult) {
		    // the query was successful
		    $reply['status'] = "ok";
		    $sqliteResult->finalize();
		  }
  		break;
  	case 'read':
  		if (isset($request['id']) && is_numeric($request['id'])) {
			  // create a query that should return a single record
			  $query = "SELECT * FROM oya_screens WHERE id = {$request['id']};";
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
  		break;
  	
  	default:
		  // default action

		  // create a query that should return a single record
		  $query = 'SELECT * FROM oya_screens ORDER BY updated DESC';
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
  		break;
  } // switch 



  // clean up any objects
  $sqlite->close();



	$reply['DEBUG'][] = "Read $rows rows"; 


	if ($nocache) {
		$reply['DEBUG'][] = "Updating cached JSON : " . CACHE_FILE; 
		file_put_contents(CACHE_FILE, json_encode($reply['screens'], JSON_PRETTY_PRINT));
	}
	echo json_encode($reply, JSON_PRETTY_PRINT);

?>