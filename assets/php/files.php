<?php

	header('Content-Type: application/json');

	error_reporting(E_ALL);

	session_start();

	define('CACHE_FILE', __DIR__ . '/data/files.json');
	define('SQLITE_FILE', __DIR__ . '/data/files.sqlite');

	$nocache = false;
	$doCreate = false;

	if (!file_exists(CACHE_FILE)) {
		$nocache = true;
	}



	$rows = 0;


	$reply = array('DEBUG' => array(), 'files' => array());


	// $localhost = $_SERVER['SERVER_ADDR'];

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



  if (!$request) {
  	$request = array("action" => "read");
  	$reply['DEBUG'][] = "No request received : set to default action READ";
  }
  elseif (!isset($request['action'])) {
  	// default action is to list entire table
  	$reply['DEBUG'][] = "No action given in request : set to default action READ";
  	$request["action"] = "read";
  }


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

	  if ($doCreate) {
	  	$reply['DEBUG'][] = "Creating missing DB: " . SQLITE_FILE;

		  $query = "
		  CREATE TABLE IF NOT EXISTS oya_files (
			  id INT PRIMARY KEY NOT NULL,
			  filename TEXT NOT NULL,
			  filetype TEXT NOT NULL,
			  name TEXT NOT NULL,
			  uri TEXT,
			  tags TEXT,
			  uuid char(40),
			  duration FLOAT NOT NULL DEFAULT '0',
			  width INT NOT NULL DEFAULT '1',
			  height INT NOT NULL DEFAULT '1',
			  title TEXT NOT NULL,
			  description TEXT,
			  user TEXT NOT NULL,
			  updated DATETIME DEFAULT CURRENT_TIMESTAMP
			);";

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


			$query = "CREATE INDEX filename ON oya_files (filename); CREATE INDEX updated ON oya_files (updated); CREATE INDEX filename ON oya_files (filename); ";

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
  	case 'create':
  	case 'add':
  	case 'new':
		  $query = "INSERT INTO oya_files 
		  				( filename, 
		  					filetype, 
		  					duration, 
		  					width, 
		  					height, 
		  					title, 
		  					description, 
		  					user, 
		  					tags, 
		  					uuid, 
		  					name, 
		  					uri
		  					)
		  VALUES ( 	{$request['filename']}, 
		  					{$request['filetype']}, 
		  					{$request['duration']}, 
		  					{$request['width']}, 
		  					{$request['height']}, 
		  					{$request['title']}, 
		  					{$request['description']},
		  					{$request['user']},
		  					{$request['tags']},
		  					{$request['uuid']},
		  					{$request['name']},
		  					{$request['uri']} 
		  					)";
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
		  $query = "UPDATE oya_files SET 
		  						filename ='{$request['filename']}', 
		  						filetype ='{$request['filetype']}',
		  						duration ='{$request['duration']}', 
		  						width ='{$request['width']}', 
		  						height ='{$request['height']}',
		  						title ='{$request['title']}', 
		  						description ='{$request['description']}', 
		  						user ='{$request['user']}', 
		  						data ='{$request['data']}'
				WHERE id = {$request['id']}";
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

		  $query = "DELETE FROM oya_files WHERE id ='{$request['id']}'";

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

  	case 'query':
  		if (!isset($request['query'])) {
  			$reply['error'] = "No query specified for action QUERY";
  			break;
  		}
  		$queryStr = $request['query'];
		  $query = "SELECT * FROM oya_files WHERE {$queryStr}";
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
  	
  	default:
		  // default action

		  // create a query that should return a single record
		  $query = 'SELECT * FROM oya_files ORDER BY updated DESC';
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
					array_push($reply['files'], $row);
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
		file_put_contents(CACHE_FILE, json_encode($reply, JSON_PRETTY_PRINT));
	}
	echo json_encode($reply, JSON_PRETTY_PRINT);

?>