<?php

	header('Content-Type: application/json');

	define('PLAYLIST', __DIR__ . '/data/playlist.json');
	define('SQLITE_FILE', __DIR__ . '/data/screens.sqlite');


 	error_reporting(E_ALL);

 	$reply = array();

 	$method = $_SERVER['REQUEST_METHOD'];

	$reply['method'] = $method;	
	$reply['DEBUG'] = array();

	$reply['status'] = null;

	$now = time();
	$rotate = false;
	$firstRun	= false;

	$filemodified = filemtime(PLAYLIST);
	$lastupdated = 0;

	$json = file_get_contents(PLAYLIST);
	$playlist = json_decode($json, true);

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
		if ($playlist['info']['lastrotation'] === null) {
			$playlist['info']['updated'] = $now;
			$playlist['info']['lastrotation'] = $now;
			$playlist['info']['nextrotation'] = $now + $playlist['current']['duration'];
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





	function addNextTracks() {
		global $playlist, $reply;

		$reply['DEBUG'][] = "addNextTracks!";
		$tracks = count($playlist['next']);
		if ($tracks < 5) {
			if (count($playlist['history'])) {
				$playlist['info']['updated'] = time();
				array_push($playlist['next'], array_shift($playlist['history']));
			}
			else {
				$reply['DEBUG'][] = "nothing in history!";
			}
		}
		else {
			$reply['DEBUG'][] = "tracks > 5 : " .$tracks;
		}

		if (count($playlist['next']) < 5) {
			$screens = getScreens(5 - count($playlist['next']));
			if ($screens && count($screens)) {
				foreach($screens as $screen) {
					// $screen = array_shift($screens);
					$reply['DEBUG'][] = "Adding screen : " . json_encode($screen);
					array_push($playlist['next'], $screen);
				}
	    	$playlist['info']['updated'] = time();
			}
			else {
				$reply['DEBUG'][] = "nothing from getScreens!";
			}
		}
		else {
			$reply['DEBUG'][] = "next > 5 : " .count($playlist['next']);
		}


	}





	function rotatePlaylist() {
		global $playlist;

		$next = null;
		$previous = $playlist['current'];

		if (!$playlist) {
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
			$playlist['changed'] = true;
			if (isset($playlist['current'])) {
				array_push($playlist['history'], $playlist['current']);
			}
			if (count($playlist['history'])>15) {
				array_shift($playlist['history']);
			}

			$next['started'] = $playlist['info']['nextrotation'];
			$playlist['current'] = $next;
			$playlist['info']['updated'] = time();
			$playlist['info']['lastrotation'] = $playlist['info']['nextrotation'];
			$playlist['info']['nextrotation'] = $playlist['info']['lastrotation'] + $next['duration'];
			$next['switchAt'] = $playlist['info']['nextrotation'];
		}

		if (count($playlist['next']) < 5) {
			$reply['DEBUG'][] = "";
			addNextTracks();
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


 	function addToQueue($uuid) {
		global $playlist;

		$item = findItem($uuid);
		if (!$item) {
			return false;
		}
		array_push($playlist['queue'], $playlist[$item['where']][$item['idx']]);
		unset($playlist[$item['where']][$item['idx']]);
 	}


 	function playNext($uuid) {
		global $playlist;

		$item = findItem($uuid);
		if (!$item) {
			return false;
		}
		array_unshift($playlist['queue'], $playlist[$item['where']][$item['idx']]);
		unset($playlist[$item['where']][$item['idx']]);
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



	// main()


 	if ($method == "GET") {
 		$request = $_GET;
 	}
 	elseif ($method == "POST") {
 		$request = json_decode(file_get_contents("php://input"), true);
 	}


 	// client is asking to rotate playlist. To allow, set $rotate to TRUE
 	if (isset($request['rotate'])) {
 		$reply['DEBUG'][] = "Rotate requested";


		if ($playlist && $playlist['info']) {
			$info = $playlist['info'];
			$reply['DEBUG'][] = "processing info";
			if (isset($info['updated']) && is_numeric($info['updated'])) {
				$timeSinceUpdate 		= $now - $info['updated'];
				$timeSinceRotation 	= $now - $info['lastrotation'];
				$timeToRotation 		= $info['nextrotation'] - $now;
				$duration = $info['nextrotation'] - $info['lastrotation'];
				if (($timeToRotation < 25) || ($timeToRotation < ($duration / 2))) {
					$rotate = true;
					// $info['lastrotation'] = $info['nextrotation'];
					$reply['DEBUG'][] = "$timeToRotation is less than half of $duration";
				}
				else {
					$reply['error'] = "$timeToRotation is more than half of $duration, when rotate requested";
				}
			}
			else { // if 'updated' not set
				$firstRun = true;
				$reply['DEBUG'][] = $playlist['info']['updated'] . " - first update, so setting updated to $now";
				$playlist['info']['updated'] = $now;
				$playlist['info']['lastrotation'] = $now;
				$playlist['info']['nextrotation'] = $now + $playlist['current']['duration'];
			}
		}

 	} // if rotate requested 




 	if ($rotate === true) {
		$reply['DEBUG'][] = "rotating!";
		$reply['DEBUG'][] = "calling rotatePlaylist!";
 		rotatePlaylist();
		$reply['DEBUG'][] = "calling addNextTracks!";
 		addNextTracks();
 		$playlist['info']['updated'] = $now;
 		try {
			$fresult = file_put_contents(PLAYLIST, json_encode($playlist));		
 		}
 		catch (Exception $e) {
			$reply['DEBUG'][] = get_class($e) . ": " . $e->getMessage();
 		}
		$reply['DEBUG'][] = "Wrote $fresult bytes to file: " . PLAYLIST;
 	}
 	elseif ($firstRun === true) {
		$reply['DEBUG'][] = "first run!";
 		try {
			$fresult = file_put_contents(PLAYLIST, json_encode($playlist));		
 		}
 		catch (Exception $e) {
			$reply['DEBUG'][] = get_class($e) . ": " . $e->getMessage();
 		}

		$reply['DEBUG'][] = "Wrote $fresult bytes to file: " . PLAYLIST;

 	}

 	$reply['request'] = $request;

	if (count($playlist['next']) < 5) {
		$reply['DEBUG'][] = "Adding more tracks";
		addNextTracks();
	}

	if (!$playlist['current']) {
		rotatePlaylist();
	}

 	$reply['playlist'] = $playlist;

 	if (isset($reply['error'])) {
 		$reply['status'] = "error";
 	}
 	else {
 		$reply['status'] = "ok";
 	}

 	// has info.updated been changed by code ?
 	if ($playlist['info']['updated'] > $lastupdated) {
 		$reply['DEBUG'][] = "Saving updated playlist.";

 		// save updated playlist to disk
 		file_put_contents(PLAYLIST, json_encode($playlist, JSON_PRETTY_PRINT));
 	}

 	// fill up next tracks from library
 	// addNextTracks();

	// echo back reply with current / updated playlist
	echo json_encode($reply, JSON_PRETTY_PRINT);

// {
// 	"info" : {
// 		"clients" : [],
// 		"updated" : null,
// 		"lastrotation" : null,
// 		"nextrotation" : null
// 	},
// 	"current" : {
// 		"id" : 1,
// 		"name" : "Navn",
// 		"videoBefore" : null,
// 		"template" : "food-1.html",
// 		"switchAt" : false,
// 		"started" : false,
// 		"data" : {
// 			"duration" : 120,
// 			"title": "Tittel"
// 		}
// 	},
// 	"queue" : [
// 		{
// 			"id" : 10,
// 			"name" : "Navn",
// 			"videoBefore" : null,
// 			"template" : "food-1.html",
// 			"data" : {
// 				"duration" : 120,
// 				"title": "Tittel"
// 			}
// 		},
// 		{
// 			"id" : 12,
// 			"name" : "Navn",
// 			"videoBefore" : null,
// 			"template" : "food-1.html",
// 			"data" : {
// 				"duration" : 120,
// 				"title": "Tittel"
// 			}
// 		}
// 	],
// 	"comingup" : [
// 		{
// 			"id" : 2,
// 			"videoBefore" : null,
// 			"uri" : "weather.php",
// 			"duration" : 150
// 		}
// 	],
// 	"history" : [
// 	]
// }

?>