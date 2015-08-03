<?php

	header('Content-Type: application/json');

	define('PLAYLIST', './data/playlist.json');


 	error_reporting(E_ALL);

 	$reply = array();

 	$method = $_SERVER['REQUEST_METHOD'];

	$reply['method'] = $method;	
	$reply['DEBUG'] = array();

	$reply['status'] = null;

	$rotate = false;
	$firstRun	= false;
	$now = time();
	$filemodified = filemtime(PLAYLIST);

	$json = file_get_contents(PLAYLIST);
	$playlist = json_decode($json, true);

	if (isset($playlist['info']) && isset($playlist['info']['updated'])) {
		if (!$playlist['info']['updated']) {
			$playlist['info']['updated'] = $now;
		}
		if ($playlist['info']['updated'] < $now) {
		}
	}



	function addNextTracks() {
		global $playlist;

		$tracks = count($playlist['next']);
		if ($tracks < 5) {
			if (count($playlist['history'])) {
				array_push($playlist['next'], array_shift($playlist['history']));
			}
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
			if (isset($playlist['current'])) {
				array_push($playlist['history'], $playlist['current']);
			}
			if (count($playlist['history'])>15) {
				array_shift($playlist['history']);
			}

			$next['started'] = $playlist['info']['nextrotation'];
			$playlist['current'] = $next;
			$playlist['info']['updated'] = $now;
			$playlist['info']['lastrotation'] = $playlist['info']['nextrotation'];
			$playlist['info']['nextrotation'] = $playlist['info']['lastrotation'] + $next['duration'];
			$next['switchAt'] = $playlist['info']['nextrotation'];
		}

		if (count($playlist['next']) < 5) {
			addNextTracks();
		}
	}


 	if ($method == "GET") {
 		$request = $_GET;
 	}
 	elseif ($method == "POST") {
 		$request = json_decode(file_get_contents("php://input"), true);
 	}

 	// client is asking to rotate playlist
 	if (isset($request['rotate'])) {
 		$reply['DEBUG'][] = "Rotate requested";

 		// rotate playlist, if not already rotated
		$json = file_get_contents(PLAYLIST);

		$playlist = json_decode($json, true);
		$reply['DEBUG'][] = "playlist loaded";

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

 	} // function rotatePlaylist

	function uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

      // 16 bits for "time_mid"
      mt_rand( 0, 0xffff ),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand( 0, 0x0fff ) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand( 0, 0x3fff ) | 0x8000,

      // 48 bits for "node"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
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
 		if (is_null($uuid)) {
 			return false;
 		}
 	}

 	function addItem($data = null) {
 		if (!is_array($data)) {
 			return false;
 		}

 		if (!isset($data['uuid'])) {
 			$data['uuid'] = uuid();
 		}

 	}

 	if ($rotate === true) {
		$reply['DEBUG'][] = "rotating!";
		$reply['DEBUG'][] = "calling rotatePlaylist!";
 		rotatePlaylist();
		$reply['DEBUG'][] = "calling addNextTracks!";
 		addNextTracks();
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



 	$reply['playlist'] = $playlist;

 	if (isset($reply['error'])) {
 		$reply['status'] = "error";
 	}
 	else {
 		$reply['status'] = "ok";
 	}

	// echo back current / updated playlist
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