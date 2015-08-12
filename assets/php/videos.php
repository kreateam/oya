<?php

	header('Content-Type: application/json');

	error_reporting(E_ALL);

	define('VIDEO_DIR', __DIR__ . '/../video');
	define('JSON_FILE', __DIR__ . '/data/videos.json');

	if (!file_exists(VIDEO_DIR)) {
		die("Unable to find video dir: ". VIDEO_DIR);
	}


	$reply = array('videos' => array(), 'DEBUG' => array());
	$files = glob(VIDEO_DIR."/*");
	$baseUri = "assets/video/";

	$videoCount = 0;
	// default format
	$which = "widescreen";

	$reply['DEBUG'][] = "starting...";

	if (isset($_GET['width']) && isset($_GET['height'])) {
		$reply['DEBUG'][] = "updating aspect from GET params";
		$aspect = $_GET['width']/$_GET['height'];
		if ($aspect < 1) {
			$which = "tallscreen";
			//tallscreen
		}
		else {
			//widescreen
			$which = "widescreen";
		}
	}

	$reply['DEBUG'][] = "which : $which";

	if (file_exists(JSON_FILE)) {
		$reply['DEBUG'][] = "loading JSON";

		$json = json_decode(file_get_contents(JSON_FILE), true);
		if ($json && is_array($json)) {
			$videoCount = count($json);
		}

	}


	foreach ($files as $filename) {
		$pathinfo = pathinfo($filename);

		if (strpos($filename, "672x384_") > -1) {
			// don't double count
			$reply['DEBUG'][] = "Skipping : " . basename($filename);
			continue;
		}

		$file = array();
		$file["filename"] = $pathinfo['basename'];
		$file['filesize'] = filesize($filename);
		$file['filetime'] = filemtime($filename);

		$file['tallscreen'] = $baseUri . basename($filename);
		$file['widescreen'] = $baseUri . str_replace("448x768_", "672x384_", basename($filename));

		$file['uri'] = $file[$which];
		$file['id'] = count($reply['videos']);

		array_push($reply['videos'], $file);
	}

	if (count($reply['videos']) != $videoCount) {
		$reply['DEBUG'][] = "Updating JSON_FILE : ". count($reply['videos']) . ", " . $videoCount . " | " . JSON_FILE;
		file_put_contents(JSON_FILE, json_encode($reply['videos'], JSON_PRETTY_PRINT));
	}

	echo json_encode($reply, JSON_PRETTY_PRINT);

?>