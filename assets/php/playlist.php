<?php

	header('Content-Type: application/json');

	define('PLAYLIST', __DIR__ . '/data/playlist.json');
	define('SQLITE_FILE', __DIR__ . '/data/screens.sqlite');
	define('SETTINGS_FILE', __DIR__ . '/data/settings.json');
	define('LOOP_FILE', __DIR__ . '/data/loop.json');
	
	define('NEXT_COUNT', 3);


 	error_reporting(E_ALL);

 	$reply = array();

 	$method = $_SERVER['REQUEST_METHOD'];

	$reply['method'] = $method;	
	$reply['DEBUG'] = array();

	$reply['status'] = null;

	// used for individual actions applied to individual screens/ playlist items
	$id 		= null;
	$uuid 	= null;
	$action = null;
	$data 	= null;


	$now = floor(1000* microtime(true)); // millisecs
	$rotate = false;
	$firstRun	= false;

	$filemodified = filemtime(PLAYLIST);
	$lastupdated = 0;

	$json = file_get_contents(PLAYLIST);
	$playlist = json_decode($json, true);

	if (!is_array($playlist['next'])) {
		$playlist['next'] = array();
	}
	if (!is_array($playlist['queue'])) {
		$playlist['queue'] = array();
	}

	if (isset($playlist['info']) && isset($playlist['info']['updated'])) {
		if (!$playlist['info']['updated']) {
			// first run
			$reply['DEBUG'][] = "NOT playlist updated : " . $playlist['info']['updated'];
			$playlist['info']['updated'] = $now;
		}
		else {
			// remember time until later
			$reply['DEBUG'][] = "playlist updated : " . $playlist['info']['updated'];
			$lastupdated = $playlist['info']['updated'];
		}
		if ($playlist['info']['updated'] === null) {
			$playlist['info']['updated'] = $now;
		}

	}
	else {
		$playlist['info']['updated'] = $now;
		$reply['DEBUG'][] = "playlist.info.updated is not set: " . json_encode($playlist['info']);
	}





	function getScreens($number = 1) {
		global $reply;

		$rows = 0;

		$result = array();

	  try {
			$sqlite = new SQLite3(SQLITE_FILE);
			if (!$sqlite) {
				$reply['DEBUG'][] = "no SQLite!";
				return false;
			}
		}
		catch (Exception $e) {
		  // sqlite3 throws an exception when it is unable to connect
		  $reply['DEBUG'][] = "Error connecting to the database : " . $e->getMessage();
		  return false;
		}

			  // create a query that should return a single record
	  $query = "SELECT * FROM oya_screens ORDER BY updated DESC LIMIT $number";
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
	    while ($number && $row = $sqliteResult->fetchArray(1)) {
	    	$number--;
				array_push($result, $row);
	      $reply['DEBUG'][] = "Retrieved row " . $rows++ . " of $number";
	    }
	    // when you are done with the result, finalize it
	    $sqliteResult->finalize();
	  }
	  // clean up any objects
	  $sqlite->close();
	  return $result;
  } // switch 


	function getScreen($id = null) {

		$rows = 0;
		$result = null;
		if (!is_numeric($id)) {
			return false;
		}

	  try {
			$sqlite = new SQLite3(SQLITE_FILE);
			if (!$sqlite) {
				$reply['DEBUG'][] = "no SQLite!";
				return false;
			}
		}
		catch (Exception $e) {
		  // sqlite3 throws an exception when it is unable to connect
		  $reply['DEBUG'][] = "Error connecting to the database : " . $e->getMessage();
		  return false;
		}

			  // create a query that should return a single record
	  $query = "SELECT * FROM oya_screens WHERE id = $id";
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
	    if (false !== ($row = $sqliteResult->fetchArray(1))) {
				$result = $row;
				if (!isset($result['uuid'])) {
					$result['uuid'] = uuid();
				}
	      $reply['DEBUG'][] = "Retrieved row " . $rows++ . " of $number";
	    }
	    // when you are done with the result, finalize it
	    $sqliteResult->finalize();
	  }
	  // clean up any objects
	  $sqlite->close();
	  return $result;
  } // switch 



  function getFromHistory() {
  	global $playlist;

		if (count($playlist['history'])) {
			$playlist['info']['updated'] = floor(1000* microtime(true));
			$item = array_shift($playlist['history']);
			$item['uuid'] = uuid();
			return $item;
		}
		else {
			$reply['DEBUG'][] = "nothing in history!";
			return null;
		}

  }


  function addFromLoop($where = "next") {
  	global $playlist, $reply;

  	$add = 0;
  	$loopItems = 0;
  	if (isset($playlist['next'])) {
	  	$tracks = count($playlist['next']);
	  	$add = NEXT_COUNT - $tracks;
  	}
  	$idx = null;

  	$loopContent = json_decode(file_get_contents(LOOP_FILE), true);
  	$loopItems = count($loopContent['items']);
  	$current = $loopContent['current'];

  	$reply['DEBUG'][] = "Add " . $add . " items to $tracks existing";
  	$reply['DEBUG'][] = "Current: $current";
  	$reply['DEBUG'][] = "Loaded loop: " . $loopItems . " items";
  	if (isset($playlist['next'])) {
  		for ($i = 0; $i < $add; $i++) {
  			$idx = ($i + $current) % $loopItems;
  			$reply['DEBUG'][] = "Pushing item $idx from loop : " . print_r($loopContent['items'][$idx], true);
  			array_push($playlist['next'], $loopContent['items'][$idx]);
  		}
  		$playlist['info']['updated'] = floor(1000* microtime(true));
  	}
  	else {
  		$reply['DEBUG'][] = "playlist.next is not set";
  	}

  	if (!is_null($idx)) {
	  	$loopContent['current'] = ++$idx;
	  	$reply['DEBUG'][] = "setting current to $idx";
	  	if (!file_put_contents(LOOP_FILE, json_encode($loopContent))) {
	  		$reply['DEBUG'][] = "Unable to save updated file!";
	  	}
  	}

  }


  /**
   * Fill playlist in a semi-intelligent way.
   * Just good enough to help a human curate the feed,
   * is what we are aiming for.
   */
	function addNextTracks() {
		global $playlist, $reply;

		$settings = json_decode(file_get_contents(SETTINGS_FILE), true);

		$reply['DEBUG'][] = "now in addNextTracks!";
		$tracks = count($playlist['next']);
		if ($tracks < NEXT_COUNT) {
			$reply['DEBUG'][] = "adding from loop!";
			addFromLoop();
		}
		else {
			$reply['DEBUG'][] = "tracks > NEXT_COUNT(".NEXT_COUNT.") : " .$tracks;
		}

		if (count($playlist['next']) < NEXT_COUNT) {
			$screens = getScreens(NEXT_COUNT - count($playlist['next']));
			if ($screens && count($screens)) {
				foreach($screens as $screen) {
					// $screen = array_shift($screens);
					$screen['uuid'] = uuid();
					$reply['DEBUG'][] = "Adding screen : " . $screen['uuid'];
					array_push($playlist['next'], $screen);
				}
	    	$playlist['info']['updated'] = floor(1000* microtime(true));
			}
			else {
				$reply['DEBUG'][] = "nothing from getScreens!";
			}
		}
		else {
			$reply['DEBUG'][] = "next > NEXT_COUNT(".NEXT_COUNT.") : " .count($playlist['next']);
		}


	}





	function rotatePlaylist() {
		global $playlist, $reply;

		$next = null;
		$previous = $playlist['current'];

		if (!$playlist) {
			$reply['DEBUG'][] = "No playlist!";
			return false;
		}

		if (count($playlist['queue'])) {
			// shift from top
			$next = array_shift($playlist['queue']);
		}
		elseif (count($playlist['next'])) {
			$next = array_shift($playlist['next']);
		}
		else {
			$reply['DEBUG'][] = "No more playlist items";
			$reply['error'] = "No more playlist items";
		}

		if ($next) {
			$playlist['info']['rotated'] = floor(1000* microtime(true));
			if (isset($playlist['current'])) {
				array_push($playlist['next'], $playlist['current']);
			}
			if (count($playlist['history']) > 30) {
				array_shift($playlist['history']);
			}

			$playlist['current'] = $next;
			$reply['DEBUG'][] = "Updated current : " . print_r($next, true);
			$playlist['info']['updated'] = floor(1000* microtime(true));
		}
	}


	function uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      mt_rand( 0, 0xffff ),
      mt_rand( 0, 0x0fff ) | 0x4000,
      mt_rand( 0, 0x3fff ) | 0x8000,
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
	}


 	function addToQueue($id, $uuid = false)  {
		global $playlist, $reply;

		$item = findItem($uuid);
		if ($item && isset($item['where'])) {
			array_push($playlist['queue'], $playlist[$item['where']][$item['idx']]);
			unset($playlist[$item['where']][$item['idx']]);
			$reply['DEBUG'][] = "AddToQueue setting update marker";
			$playlist['info']['updated'] = floor(1000* microtime(true));
		}
		else {
			$item = getScreen($id);
			if (!$item) {
				return false;
			}
			$reply['DEBUG'][] = "AddToQueue Pushing item : $item";
			array_push($playlist['queue'], $item);
			$reply['DEBUG'][] = "AddToQueue setting update marker";
			$playlist['info']['updated'] = floor(1000* microtime(true));
		}
 	}

 	function addToNext($id, $uuid = false)  {
		global $playlist, $reply;

		$item = findItem($uuid);
		if ($item && isset($item['where']) && $item['where'] != "next") {
			array_push($playlist['next'], $playlist[$item['next']][$item['idx']]);
			unset($playlist[$item['where']][$item['idx']]);
			$reply['DEBUG'][] = "AddToNext setting update marker";
			$playlist['info']['updated'] = floor(1000* microtime(true));
		}
		else {
			$item = getScreen($id);
			if (!$item) {
				return false;
			}
			$reply['DEBUG'][] = "AddToNext Pushing item : $item";
			array_push($playlist['next'], $item);
			$reply['DEBUG'][] = "AddToNext setting update marker";
			$playlist['info']['updated'] = floor(1000* microtime(true));
		}
 	}


 	function playNext($id = null, $uuid = null) {
		global $playlist;

		$item = findItem($uuid);
		if (!$item) {
			return false;
		}
		array_unshift($playlist['queue'], $playlist[$item['where']][$item['idx']]);
		unset($playlist[$item['where']][$item['idx']]);
		$playlist['info']['updated'] = floor(1000* microtime(true));
 	}




	function findItem($uuid = null) {
		global $playlist;
		$result = array("where" => null, "idx" => null);

		if ($uuid === null) {
			return null;
		}

		foreach ($playlist['queue'] as $idx => $item) {
			if ($item['uuid'] == $uuid) {
				$result['where'] = "queue";
				$result['idx'] = $idx;
				return $result;
			}
		}
		foreach ($playlist['next'] as $idx => $item) {
			if ($item['uuid'] == $uuid) {
				$result['where'] = "next";
				$result['idx'] = $idx;
				return $result;
			}
		}
		return false;
	} 


	function getItemFromDB ($id = null) {
		if (!is_numeric($id)) {
			return false;
		}
		$sqlite = new SQLite3(SQLITE_FILE);
	}


 	function moveItem ($uuid = null, $where = null) {
 		global $playlist;
 		if (is_null($uuid) || is_null($where)) {
 			return false;
 		}

 		$item = findItem($uuid);
 		if (!$item) {
 			return false;
 		}

 	} // function moveItem


 	function deleteItem($uuid = null) {
 		global $playlist;

 		$where = null;
 		$key = null;

 		if (is_null($uuid)) {
 			return false;
 		}
 		foreach ($playlist['queue'] as $idx => $item) {
 			if ($item['uuid'] == $uuid) {
 				$key = $idx;
 				$where = 'queue';
 				break;
 			}
 		}

 		if ($where !== null && $key !== null && isset($playlist[$where][$idx])) {
 			unset($playlist[$where][$idx]);
			$playlist['info']['updated'] = floor(1000* microtime(true));
 			return true;
 		}

 		foreach ($playlist['next'] as $idx => $item) {
 			if ($item['uuid'] == $uuid) {
 				$key = $idx;
 				$where = 'next';
 				break;
 			}
 		}

 		if ($where !== null && $key !== null && isset($playlist[$where][$idx])) {
 			unset($playlist[$where][$idx]);
			$playlist['info']['updated'] = floor(1000* microtime(true));
 			return true;
 		}

 		return false;
 	}


 	function addItem($data = null) {
 		if (!is_array($data)) {
 			return false;
 		}

 		if (!isset($data['uuid'])) {
 			$data['uuid'] = uuid();
 		}
 	}



 	function checkUUIDs() {
 		global $playlist, $reply;

 		$changed = false;
 		if ($playlist && isset($playlist['current'])) {
 			if (!isset($playlist['current']['uuid'])) {
 				$playlist['current']['uuid'] = uuid();
 				$playlist['info']['updated'] = floor(1000* microtime(true));
 				$reply['DEBUG'][] = "current item has no uuid!";
 			}
 		}
 		if ($playlist['queue'] && count($playlist['queue'])) {
 			foreach ($playlist['queue'] as $idx => $item) {
	 			if (!isset($item['uuid'])) {
	 				$playlist['queue'][$idx]['uuid'] = uuid();
	 				$playlist['info']['updated'] = floor(1000* microtime(true));
	 				$reply['DEBUG'][] = "queued item $idx has no uuid!";
	 			}			
 			}
 		}

 		if ($playlist['next'] && count($playlist['next'])) {
 			foreach ($playlist['next'] as $idx => $item) {
	 			if (!isset($item['uuid'])) {
	 				$playlist['next'][$idx]['uuid'] = uuid();
	 				$playlist['info']['updated'] = floor(1000* microtime(true));
	 				$reply['DEBUG'][] = "next-item $idx has no uuid!";
	 			}
 			}
 		}
 	}



	// main()


 	if ($method == "GET") {
 		$request = $_GET;
 	}
 	elseif ($method == "POST") {
 		$request = json_decode(file_get_contents("php://input"), true);
 	}

 	if (isset($request['action'])) {
 		$action = $request['action'];
 		if (isset($request['uuid'])) {
 			$uuid = $request['uuid'];
 		}
 		if (isset($request['id'])) {
 			$id = $request['id'];
 		}
 		if (isset($request['data'])) {
 			$data = $request['data'];
 		}
 	}


 	switch (strtolower($action)) {
 		case 'pause':
 			// pausePlaylist();
 			break;
 		case 'delete':
 			deleteItem($uuid);
 			break;
 		case 'addtoqueue':
 			addToQueue($id, $uuid);
 			break;
 		case 'rotate':
 			$reply['DEBUG'][] = "calling rotatePlaylist()";
 			rotatePlaylist();
 			break;
 		case 'addtonext':
 			addToNext($id, $uuid);
 			break;
 		case 'playnext':
 			playNext($id, $uuid);
 			break;

 		default:
 			$reply['DEBUG'][] = "No action, returning playlist.";
 			$reply['status'] = "ok";
 			break;
 	}



 	// client is asking to rotate playlist. To allow, set $rotate to TRUE
 	// if (isset($request['rotate'])) {
 	// 	$reply['DEBUG'][] = "Rotate requested";


		// if ($playlist && $playlist['info']) {
		// 	$info = $playlist['info'];
		// 	$reply['DEBUG'][] = "processing info";
		// 	if (isset($info['updated']) && is_numeric($info['updated'])) {
		// 		$timeSinceUpdate 		= $now - $info['updated'];
		// 		$timeSinceRotation 	= $now - $info['lastrotation'];
		// 		$timeToRotation 		= $info['nextrotation'] - $now;
		// 		$duration = $info['nextrotation'] - $info['lastrotation'];
		// 		if (($timeToRotation < 25) || ($timeToRotation < ($duration / 2))) {
		// 			$rotate = true;
		// 			// $info['lastrotation'] = $info['nextrotation'];
		// 			$reply['DEBUG'][] = "$timeToRotation is less than half of $duration";
		// 		}
		// 		else {
		// 			$reply['error'] = "$timeToRotation is more than half of $duration, when rotate requested";
		// 		}
		// 	}
		// 	else { // if 'updated' not set
		// 		$firstRun = true;
		// 		$reply['DEBUG'][] = $playlist['info']['updated'] . " - first update, so setting updated to $now";
		// 		$playlist['info']['updated'] = $now;
		// 		$playlist['info']['lastrotation'] = $now;
		// 		$playlist['info']['nextrotation'] = $now + $playlist['current']['duration'];
		// 	}
		// }

 	// } // if rotate requested 




 	// if ($rotate === true) {
		// $reply['DEBUG'][] = "rotating!";
		// $reply['DEBUG'][] = "calling rotatePlaylist!";
 	// 	rotatePlaylist();
		// $reply['DEBUG'][] = "calling addNextTracks!";
 	// 	$playlist['info']['updated'] = $now;
 	// 	try {
		// 	$fresult = file_put_contents(PLAYLIST, json_encode($playlist));		
 	// 	}
 	// 	catch (Exception $e) {
		// 	$reply['DEBUG'][] = get_class($e) . ": " . $e->getMessage();
 	// 	}
		// $reply['DEBUG'][] = "Wrote $fresult bytes to file: " . PLAYLIST;
 	// }
 	// elseif ($firstRun === true) {
		// $reply['DEBUG'][] = "first run!";
 	// 	try {
		// 	$fresult = file_put_contents(PLAYLIST, json_encode($playlist));		
 	// 	}
 	// 	catch (Exception $e) {
		// 	$reply['DEBUG'][] = get_class($e) . ": " . $e->getMessage();
 	// 	}

		// $reply['DEBUG'][] = "Wrote $fresult bytes to file: " . PLAYLIST;

 	// }

 	$reply['request'] = $request;



	if (count($playlist['next']) < NEXT_COUNT) {
		$reply['DEBUG'][] = "Adding more tracks, @ line " . __LINE__;		
		addNextTracks();
	}

	if (!$playlist['current']) {
		$reply['DEBUG'][] = "calling rotatePlaylist, b/c no current item";
		rotatePlaylist();
	}

 	$reply['playlist'] = $playlist;

 	if (isset($reply['error'])) {
 		$reply['status'] = "error";
 	}
 	else {
 		$reply['status'] = "ok";
 	}

 	checkUUIDs();

 	// has info.updated been changed by code ?
 	if ($playlist['info']['updated'] > $lastupdated) {
 		$reply['DEBUG'][] = "Saving updated playlist.";

 		// save updated playlist to disk
 		file_put_contents(PLAYLIST, json_encode($playlist, JSON_PRETTY_PRINT));
 	}




 	$reply['playlist']['DEBUG'] = $reply['DEBUG'];

	// echo back reply with current / updated playlist
	echo json_encode($reply, JSON_PRETTY_PRINT);


?>