<?php

	header('Content-Type: application/json');

	error_reporting(E_ALL);

	define('TEMPLATE_DIR', __DIR__ . '/assets/php/data/templates');
	define('TEMPLATEFILE', __DIR__ . '/assets/php/data/templates.json');
	define('UPDATE_INTERVAL', 1); // in seconds

	if (!file_exists(TEMPLATE_DIR)) {
		if (!mkdir(TEMPLATE_DIR)) {
			die("Unable to create: ". TEMPLATE_DIR);
		}
	}

	$updateCache = null;
	$reply = array('files' => array());

	if (file_exists(TEMPLATEFILE)) {
		$updateCache = (time() - filemtime(TEMPLATEFILE)) > UPDATE_INTERVAL; 
	}

	if ($updateCache === false) {
		$reply['files'] = json_decode(file_get_contents(TEMPLATEFILE), true);
		$reply['status'] = "ok";
		$reply['message'] = "Fetched from cache.";
	}
	else {
		$files = glob(TEMPLATE_DIR."/*");
		$baseUri = "assets/php/data/templates/";

		foreach ($files as $filename) {
			$pathinfo = pathinfo($filename);
			if (is_dir($filename)) {
				continue;
			}

			if ($pathinfo['extension'] == "json" || $pathinfo['extension'] == "form") {
				// only process .html files
				continue;
			}
			elseif (strpos($filename, ".inc.") > 1) {
				// skip include files 
				continue;
			}

			$file = array();
			$file["filename"] = $pathinfo['basename'];
			$file['filesize'] = filesize($filename);
			$file['filetime'] = filemtime($filename);
			$file['filecontent'] = file_get_contents($filename);

			$jsonfile = $pathinfo['dirname'] . "/" . $pathinfo['filename'] . ".json";
			if (file_exists($jsonfile)) {
				$file['json'] = json_decode(file_get_contents($jsonfile), true);
				// $file['json']["name"] = $file['filename'];
			}

			$formfile = $pathinfo['dirname'] . "/" . $pathinfo['filename'] . ".form";
			if (file_exists($formfile)) {
				$file['form'] = file_get_contents($formfile);
				// $file['json']["name"] = $file['filename'];
			}

			if (isset($file['json'])) {
				$file['json']['title'] = $file['filename'];
				$file['json']['filename'] = $file['filename'];
				$file['json']['uri'] = $baseUri . $file['filename'];
			}
			array_push($reply['files'], $file);
		} // foreach
	} // if !updateCache


	if ($updateCache) {
		file_put_contents(TEMPLATEFILE, $reply['files']);
	}

	echo json_encode($reply, JSON_PRETTY_PRINT);

?>